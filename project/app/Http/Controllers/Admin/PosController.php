<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Fund;
use App\Models\FundTransaction;
use App\Models\GeneralSettings;
use App\Models\PaymentTransaction;
use App\Services\PosOrderActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use App\Jobs\SendPosSms;

class PosController extends Controller
{
    public function __construct(private PosOrderActivationService $activation) {}
    public function index(Request $request)
    {
        $query = Order::with('customer')->latest();

        if ($request->filter == 'due') {
            $query->where('due_amount', '>', 0);
        } elseif ($request->filter == 'paid') {
            $query->where('due_amount', '<=', 0);
        }

        $orders = $query->paginate(10)->appends($request->all());
        
        return view('admin.pos.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'total'          => 'required|numeric|min:0',
            'paid'           => 'nullable|numeric|min:0',
            'payment_method' => 'required',
        ]);

        // DB::transaction ব্যবহার করা হলো স্পিড বাড়ানোর জন্য
        try {
            DB::transaction(function () use ($request) {
                
                // ১. ইউজার হ্যান্ডলিং
                $user = User::where('phone', $request->phone)->first();
                $isNewUser = false;

                if (!$user) {
                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->phone . '@noemail.com'; 
                    $user->phone = $request->phone;
                    $user->password = Hash::make('12345678');
                    $user->status = 1;
                    $user->email_verified = 'No';
                    $user->save();
                    $isNewUser = true;
                }

                // ২. কাস্টমার হ্যান্ডলিং
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone],
                    ['name' => $request->name, 'address' => $request->address]
                );

                // ইউজার লিংক আপডেট (যদি না থাকে)
                if (!$customer->user_id) {
                    $customer->user_id = $user->id;
                    $customer->save();
                }

                // ৩. অর্ডার ক্রিয়েশন
                $paid_amount = $request->paid ?? 0;
                $order = Order::create([
                    'customer_id'    => $customer->id,
                    'description'    => $request->description, 
                    'support_note'   => $request->support_note, 
                    'order_number'   => 'INV-' . strtoupper(Str::random(8)),
                    'total_amount'   => $request->total,
                    'paid_amount'    => $paid_amount,
                    'payment_method' => $request->payment_method, 
                    'due_amount'     => $request->total - $paid_amount,
                    'hash_token'     => bin2hex(random_bytes(16))
                ]);

                // ৪. ফান্ড এবং ট্রানজেকশন
                if ($paid_amount > 0) {
                    $fund = Fund::firstOrCreate(['id' => 1]);
                    $fund->increment('current_balance', $paid_amount);

                    FundTransaction::create([
                        'type'            => 'income',
                        'amount'          => $paid_amount,
                        'payment_method'  => $request->payment_method,
                        'reference_id'    => $order->order_number,
                        'note'            => 'পস সেল: ' . $order->order_number
                    ]);
                }

                // ৫. অটোমেটিক রিচার্জ / প্যাকেজ / রিনিউ অ্যাক্টিভেশন
                if ($order->due_amount <= 0) {
                    $this->activation->activateIfFullyPaid($order, $user);
                }

                // ৬. এসএমএস (এটি ট্রানজেকশনের বাইরে রাখা ভালো, তবে এখানে লজিক সহজ রাখার জন্য রাখা হলো)
                // নতুন ইউজার হলে ওয়েলকাম মেসেজ
                if ($isNewUser) {
                    $this->sendSms($request->phone, "স্বাগতম! আপনার একাউন্ট খোলা হয়েছে। পাসওয়ার্ড: 12345678");
                }
                
                $liveLink = route('frontend.invoice.live', ['order_number' => $order->order_number, 'token' => $order->hash_token]);
                $orderMsg = "অর্ডার সফল। বিল: {$order->total_amount}tk, জমা: {$paid_amount}tk। ইনভয়েস: {$liveLink}";
                $this->sendSms($request->phone, $orderMsg);
            });

            return redirect()->back()->with('success', 'অর্ডার প্রসেসিং সফল হয়েছে!');

        } catch (\Exception $e) {
            Log::error("POS Store Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * ডিউ কালেকশন (অপ্টিমাইজড)
     */
    public function collectDue(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required'
        ]);

        try {
            // ডাটাবেস ট্রানজেকশন শুরু (সব কাজ একসাথে হবে, তাই দ্রুত হবে)
            DB::transaction(function () use ($request, $id) {
                
                // অর্ডার লক করা হলো যাতে একই সময়ে দুইবার রিকোয়েস্ট না আসে
                $order = Order::with('customer')->lockForUpdate()->findOrFail($id);

                if ($request->amount > $order->due_amount) {
                    throw new \Exception('অ্যামাউন্ট বকেয়ার চেয়ে বেশি হতে পারে না!');
                }

                // অর্ডার আপডেট
                $order->paid_amount += $request->amount;
                $order->due_amount -= $request->amount;
                $order->save();

                // ফান্ড আপডেট
                $fund = Fund::firstOrCreate(['id' => 1]);
                $fund->increment('current_balance', $request->amount);

                FundTransaction::create([
                    'type'            => 'due_collection',
                    'amount'          => $request->amount,
                    'payment_method'  => $request->payment_method,
                    'reference_id'    => $order->order_number,
                    'note'            => 'বকেয়া কালেকশন: ' . $order->order_number
                ]);

                // পেইড সম্পূর্ণ হলে সার্ভিস activate (রিচার্জ / রিনিউ / প্যাকেজ)
                if ($order->due_amount <= 0) {
                    $this->activation->activateIfFullyPaid($order->fresh());
                }

                // এসএমএস পাঠানো
                $liveLink = route('frontend.invoice.live', ['order_number' => $order->order_number, 'token' => $order->hash_token]);
                $message = "পেমেন্ট {$request->amount}tk রিসিভ। বকেয়া: {$order->due_amount}tk। ইনভয়েস: {$liveLink}";
                
                // কাস্টমার ফোন চেক
                if($order->customer && $order->customer->phone){
                    $this->sendSms($order->customer->phone, $message);
                }
            });

            return redirect()->back()->with('success', 'বকেয়া কালেক্ট এবং আপডেট সফল!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Payment Details popup থেকে Paid / Unpaid আপডেট
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status'         => 'required|in:paid,unpaid',
            'payment_method' => 'nullable|string|max:50',
        ]);

        try {
            $order = null;
            $notifyPhone = null;
            $notifyMessage = null;

            DB::transaction(function () use ($request, $id, &$order) {
                $order = Order::with('customer')->lockForUpdate()->findOrFail($id);
                $method = $request->payment_method ?: $order->payment_method ?: 'Cash';

                if ($request->status === 'paid') {
                    $collectAmount = $order->due_amount;

                    if ($collectAmount > 0) {
                        $fund = Fund::firstOrCreate(['id' => 1]);
                        $fund->increment('current_balance', $collectAmount);

                        FundTransaction::create([
                            'type'           => 'due_collection',
                            'amount'         => $collectAmount,
                            'payment_method' => $method,
                            'reference_id'   => $order->order_number,
                            'note'           => 'POS Payment Details — Paid: ' . $order->order_number,
                        ]);
                    }

                    $order->update([
                        'paid_amount'    => $order->total_amount,
                        'due_amount'     => 0,
                        'payment_method' => $method,
                    ]);
                } else {
                    $order->update([
                        'paid_amount'    => 0,
                        'due_amount'     => $order->total_amount,
                        'payment_method' => $method,
                    ]);
                }
            });

            $order->refresh();

            if ($request->status === 'paid') {
                $this->activation->activateIfFullyPaid($order);
                $this->syncCreativePayFromOrder($order, 'completed');

                if ($order->customer?->phone) {
                    $link = route('frontend.invoice.live', [
                        'order_number' => $order->order_number,
                        'token'        => $order->hash_token,
                    ]);
                    $notifyPhone   = $order->customer->phone;
                    $notifyMessage = "পেমেন্ট সম্পন্ন। Invoice: {$order->order_number}, Amount: {$order->total_amount}tk। {$link}";
                }

                Log::info("POS payment marked paid: {$order->order_number}");
            } else {
                $this->markOrderSupportNotePending($order);
                $this->syncCreativePayFromOrder($order, 'pending');
                Log::info("POS payment marked unpaid: {$order->order_number}");
            }

            if ($notifyPhone && $notifyMessage) {
                SendPosSms::dispatch($notifyPhone, $notifyMessage)->afterResponse();
            }

            $msg = $request->status === 'paid'
                ? 'অর্ডার Paid করা হয়েছে এবং সার্ভিস Activate হয়েছে।'
                : 'অর্ডার Unpaid (Due) করা হয়েছে।';

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            Log::error('POS payment status error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function extractPaymentInvoiceId(Order $order): ?string
    {
        $note = $order->support_note ?? '';

        if (preg_match('/CP-[A-Z0-9]+(?:-[0-9]+)?/i', $note, $m)) {
            return strtoupper($m[0]);
        }

        $meta = json_decode($note, true);
        if (is_array($meta) && !empty($meta['txn_id'])) {
            return $meta['txn_id'];
        }

        return null;
    }

    protected function syncCreativePayFromOrder(Order $order, string $targetStatus): void
    {
        $invoiceId = $this->extractPaymentInvoiceId($order);
        if (!$invoiceId) {
            return;
        }

        $txn = PaymentTransaction::where('invoice_id', $invoiceId)->first();
        if (!$txn) {
            return;
        }

        if ($targetStatus === 'completed' && $txn->status === 'pending') {
            $txn->update([
                'status'     => 'completed',
                'paid_at'    => now(),
                'admin_note' => 'POS Payment Details — marked paid',
            ]);
        } elseif ($targetStatus === 'pending' && $txn->status === 'completed') {
            $txn->update([
                'status'  => 'pending',
                'paid_at' => null,
                'admin_note' => 'POS Payment Details — marked unpaid',
            ]);
        }
    }

    protected function markOrderSupportNotePending(Order $order): void
    {
        $note = $order->support_note ?? '';
        if ($note === '') {
            return;
        }

        if (str_contains($note, 'Status:')) {
            $order->update([
                'support_note' => preg_replace('/Status:\s*[A-Z]+/i', 'Status: PENDING', $note),
                'status'       => 'Pending',
            ]);
            return;
        }

        $meta = json_decode($note, true);
        if (is_array($meta)) {
            $meta['status'] = 'PENDING';
            $order->update([
                'support_note' => json_encode($meta),
                'status'       => 'Pending',
            ]);
        }
    }

    public function downloadInvoice($order_number)
    {
        try {
            $order = Order::where('order_number', $order_number)->with('customer')->firstOrFail();
            $gs = GeneralSettings::first(); 
            $pdf = Pdf::loadView('frontend.invoice_pdf', compact('order', 'gs'))
                      ->setPaper('a4', 'portrait')
                      ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif']);
            return $pdf->download('Invoice-'.$order->order_number.'.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'পিডিএফ এরর।');
        }
    }

    // এসএমএস ফাংশনে টাইমআউট কমানো হয়েছে যাতে সাইট স্লো না হয়
    private function sendSms($number, $message)
    {
        try {
            $gs = GeneralSettings::first(); 
            if (!$gs || !$gs->sms_api_key || !$gs->sms_sender_id) return;

            // Timeout 3 সেকেন্ড করা হলো। এর বেশি সময় লাগলে স্কিপ করবে।
            Http::timeout(3)->get("http://bulksmsbd.net/api/smsapi", [
                'api_key'  => $gs->sms_api_key,     
                'type'     => 'text',
                'number'   => $number,
                'senderid' => $gs->sms_sender_id, 
                'message'  => $message
            ]);
        } catch (\Exception $e) {
            // এসএমএস না গেলে লগ ফাইলে থাকবে কিন্তু ইউজার আটকাবে না
            Log::warning("SMS Slow/Fail: " . $e->getMessage());
        }
    }
}