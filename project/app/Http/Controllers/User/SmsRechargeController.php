<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\User;

class SmsRechargeController extends Controller
{
    /**
     * রিচার্জ হিস্ট্রি এবং ইনডেক্স পেজ
     */
    public function index()
    {
        $deposits = Deposit::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.recharge.index', compact('deposits'));
    }

    /**
     * পেমেন্ট রিকোয়েস্ট শুরু করা
     */
    public function process(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:10']);

        $user = Auth::user();
        $gs = DB::table('generalsettings')->first();

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email ?? 'customer@example.com',
            'amount'       => $request->amount,
            'metadata'     => [
                'user_id' => $user->id,
                'type'    => 'sms_recharge'
            ],
            'redirect_url' => route('user.recharge.success'),
            'cancel_url'   => route('user.recharge.cancel'),
            'webhook_url'  => route('api.recharge.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            return back()->with('error', 'পেমেন্ট গেটওয়ে রেসপন্স এরর।');
        } catch (\Exception $e) {
            return back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    /**
     * পেমেন্ট সফল হওয়ার পর ভেরিফিকেশন
     */
    public function success(Request $request)
    {
        $invoiceId = $request->get('invoice_id');
        if (!$invoiceId) {
            return redirect()->route('user.recharge.index')->with('error', 'ইনভয়েস আইডি পাওয়া যায়নি।');
        }

        $gs = DB::table('generalsettings')->first();

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                return redirect()->route('user.recharge.index')->with('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
            }

            if (isset($result['status'])) {
                $status = $result['status'];
                // COMPLETED, SUCCESS অথবা PENDING যাই হোক আমরা ডাটা প্রসেস করব
                if (in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                    
                    $meta = is_string($result['metadata']) ? json_decode($result['metadata'], true) : $result['metadata'];
                    
                    DB::transaction(function () use ($meta, $invoiceId, $status, $result) {
                        $this->processRechargeData($meta['user_id'], $result['amount'], $invoiceId, $status);
                    });

                    // গেটওয়ে রিডাইরেক্টে সেশন থাকে না – আবার লগইন করানো
                    if (!empty($meta['user_id'])) {
                        Auth::loginUsingId($meta['user_id']);
                    }

                    if ($status === 'PENDING') {
                        return redirect()->route('user.recharge.index')->with('success', 'পেমেন্ট পেন্ডিং! অনুমোদিত হলে ব্যালেন্স যোগ হবে।');
                    } else {
                        return redirect()->route('user.recharge.index')->with('success', 'রিচার্জ সফল হয়েছে!');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('SMS Recharge Success Error: ' . $e->getMessage());
            return redirect()->route('user.recharge.index')->with('error', 'সিস্টেম এরর: ' . $e->getMessage());
        }

        return redirect()->route('user.recharge.index')->with('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ।');
    }

    /**
     * মূল ডাটা প্রসেসিং ফাংশন
     */
    private function processRechargeData($userId, $amount, $invoiceId, $status)
    {
        $user = User::lockForUpdate()->find($userId);
        if (!$user) return;

        // ১. কাস্টমার ম্যানেজমেন্ট
        $customer = DB::table('customers')->where('phone', $user->phone)->first();
        $customerId = $customer ? $customer->id : DB::table('customers')->insertGetId([
            'user_id' => $user->id, 
            'name' => $user->name, 
            'phone' => $user->phone ?? '0000000000', 
            'created_at' => now(), 
            'updated_at' => now()
        ]);

        $paidAmount = 0;
        $dueAmount = $amount;

        // ২. সফল পেমেন্টের ক্ষেত্রে ব্যালেন্স আপডেট
        if (in_array($status, ['COMPLETED', 'SUCCESS'])) {
            $paidAmount = $amount;
            $dueAmount = 0;

            $alreadyProcessed = Deposit::where('trx_id', $invoiceId)->where('status', 'completed')->exists();
            if (!$alreadyProcessed) {
                // ওয়ালেট ব্যালেন্স যোগ
                $user->increment('wallet_balance', $amount);
                
                // ডিপোজিট হিস্ট্রি
                Deposit::updateOrCreate(
                    ['trx_id' => $invoiceId],
                    [
                        'user_id' => $userId, 
                        'amount' => $amount, 
                        'method' => 'Other', // আপনার ডাটাবেজে 'Other' সাপোর্ট করে
                        'status' => 'completed',
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]
                );
            }
        }

        // ৩. অর্ডার টেবিলে এন্ট্রি
        $existingOrder = DB::table('orders')->where('support_note', 'LIKE', "%$invoiceId%")->first();
        
        if (!$existingOrder) {
            DB::table('orders')->insert([
                'customer_id'    => $customerId,
                'order_number'   => 'REC-' . strtoupper(uniqid()),
                'description'    => 'SMS Wallet Recharge',
                'support_note'   => 'TXNID: ' . $invoiceId . ' | Status: ' . $status,
                'total_amount'   => $amount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => 'Other', // 'Online' এর বদলে 'Other' দিতে হবে
                'hash_token'     => md5(uniqid() . $invoiceId),
                'created_at'     => now(),
                'updated_at'     => now()
            ]);
        } else {
            // যদি অর্ডার পেন্ডিং থেকে সাকসেস হয়, তবে আপডেট হবে
            if (in_array($status, ['COMPLETED', 'SUCCESS'])) {
                DB::table('orders')->where('id', $existingOrder->id)->update([
                    'paid_amount' => $amount,
                    'due_amount'  => 0,
                    'support_note' => 'TXNID: ' . $invoiceId . ' | Status: COMPLETED',
                    'updated_at'  => now()
                ]);
            }
        }
    }

    /**
     * ওয়েব হুক সাপোর্ট
     */
    public function webhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = $request->all();
        if (isset($data['status'])) {
            $meta = is_string($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : ($data['metadata'] ?? []);
            if (isset($meta['type']) && $meta['type'] == 'sms_recharge') {
                DB::transaction(function () use ($meta, $data) {
                    $this->processRechargeData($meta['user_id'], $data['amount'], $data['invoice_id'], $data['status']);
                });
            }
        }
        return response()->json(['message' => 'Success']);
    }

    public function cancel(Request $request) {
        // ক্যানসেলে user_id নেই, তাই লগইন পেজে রিডাইরেক্ট
        return redirect()->route('login')->with('error', 'পেমেন্ট বাতিল হয়েছে। লগইন করে আবার চেষ্টা করুন।');
    }
}