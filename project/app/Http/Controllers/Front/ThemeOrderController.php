<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ThemeOrderController extends Controller
{
    /**
     * Handle website theme order submission directly from the theme details page.
     */
    public function store(Request $request)
    {
        // Normalize field names if submitted as customer_name, customer_phone, customer_email
        $data = $request->all();
        if (isset($data['customer_name']) && empty($data['name'])) {
            $data['name'] = $data['customer_name'];
        }
        if (isset($data['customer_phone']) && empty($data['phone'])) {
            $data['phone'] = $data['customer_phone'];
        }
        if (isset($data['customer_email']) && empty($data['email'])) {
            $data['email'] = $data['customer_email'];
        }
        $request->merge($data);

        $validator = Validator::make($request->all(), [
            'product_id'     => 'required|exists:products,id',
            'tier_index'     => 'nullable|integer',
            'package_tier'   => 'nullable|string|max:191',
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|min:10|max:25',
            'email'          => 'nullable|email|max:255',
            'domain_name'    => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:manual_bkash,manual_nagad,manual_rocket,booking,online_gateway',
            'trx_id'         => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
        ], [
            'name.required'           => 'অনুগ্রহ করে আপনার নাম লিখুন।',
            'phone.required'          => 'অনুগ্রহ করে আপনার মোবাইল বা হোয়াটসঅ্যাপ নম্বর দিন।',
            'phone.min'               => 'সঠিক মোবাইল নম্বর দিন (কমপক্ষে ১০ ডিজিট)।',
            'payment_method.required' => 'পেমেন্ট মেথড নির্বাচন করুন।',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->getMessageBag()->toArray()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $cleanTiers = $product->clean_pricing_tiers ?? [];
                
                $tierIndex = (int) ($request->tier_index ?? 0);
                $selectedTier = null;

                // Match by package_tier title if passed
                if ($request->filled('package_tier')) {
                    foreach ($cleanTiers as $idx => $t) {
                        if (($t['title'] ?? '') === $request->package_tier) {
                            $tierIndex = $idx;
                            $selectedTier = $t;
                            break;
                        }
                    }
                }

                if (!$selectedTier) {
                    $selectedTier = $cleanTiers[$tierIndex] ?? ($cleanTiers[0] ?? null);
                }

                $tierTitle = $selectedTier['title'] ?? ($request->package_tier ?: 'স্ট্যান্ডার্ড প্যাকেজ');
                $amount = !empty($selectedTier['price']) && is_numeric($selectedTier['price'])
                    ? (float) $selectedTier['price']
                    : (float) $product->price;

                // 1. User Account Linking / Auto-Creation
                if (Auth::check()) {
                    $user = Auth::user();
                } else {
                    $user = User::where('phone', $request->phone)->first();
                    if (!$user && $request->email) {
                        $user = User::where('email', $request->email)->first();
                    }

                    if (!$user) {
                        $user = new User();
                        $user->name = $request->name;
                        $user->email = $request->email ?: ($request->phone . '@customer.local');
                        $user->phone = $request->phone;
                        $user->password = Hash::make(Str::random(12));
                        $user->status = 1;
                        $user->email_verified = 'Yes';
                        $user->save();
                    }
                }

                // 2. Customer record
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone],
                    [
                        'name'    => $request->name,
                        'user_id' => $user->id,
                        'address' => $request->domain_name ?: 'Online Order',
                    ]
                );

                if (!$customer->user_id) {
                    $customer->user_id = $user->id;
                    $customer->save();
                }

                // 3. Payment Method & Status Resolution
                $paymentMethod = $request->payment_method;
                $trxId = trim((string) $request->trx_id);
                $status = 'Pending';
                $paidAmount = 0;

                $methodLabels = [
                    'manual_bkash'   => 'bKash (বিকাশ ম্যানুয়াল)',
                    'manual_nagad'   => 'Nagad (নগদ ম্যানুয়াল)',
                    'manual_rocket'  => 'Rocket (রকেট ম্যানুয়াল)',
                    'booking'        => 'Booking (আলোচনা সাপেক্ষে পেমেন্ট)',
                    'online_gateway' => 'Online Gateway (CreativePay)',
                ];
                $paymentMethodName = $methodLabels[$paymentMethod] ?? $paymentMethod;

                // 4. Create Order
                $orderNumber = 'ORD-' . strtoupper(Str::random(7));
                $hashToken = bin2hex(random_bytes(16));

                $order = Order::create([
                    'customer_id'    => $customer->id,
                    'product_id'     => $product->id,
                    'package_tier'   => $tierTitle,
                    'domain_name'    => $request->domain_name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'trx_id'         => $trxId ?: null,
                    'description'    => "ওয়েবসাইট অর্ডার: {$product->name} — {$tierTitle}",
                    'support_note'   => "ডোমেইন: " . ($request->domain_name ?: 'দেওয়া হয়নি') . " | মেথড: {$paymentMethodName}",
                    'notes'          => $request->notes,
                    'order_number'   => $orderNumber,
                    'total_amount'   => $amount,
                    'paid_amount'    => $paidAmount,
                    'payment_method' => $paymentMethodName,
                    'due_amount'     => $amount - $paidAmount,
                    'hash_token'     => $hashToken,
                    'status'         => $status,
                ]);

                // 5. Create or sync ProductOrder so user sees the theme in Digital Downloads
                \App\Models\ProductOrder::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'user_id'    => $user->id,
                    ],
                    [
                        'customer_name'  => $request->name,
                        'customer_email' => $request->email ?: ($request->phone . '@customer.local'),
                        'customer_phone' => $request->phone,
                        'install_domain' => $request->domain_name,
                        'invoice_id'     => $order->order_number,
                        'amount'         => $amount,
                        'status'         => 'pending',
                        'download_token' => Str::random(48),
                        'download_count' => 0,
                        'download_limit' => $product->download_limit ?: 10,
                    ]
                );

                $invoiceUrl = route('frontend.invoice.live', [
                    'order_number' => $order->order_number,
                    'token'        => $order->hash_token,
                ]);

                $gs = GeneralSettings::first();
                $whatsappNumber = $gs?->phone ?: '8801775075543';
                $cleanWa = preg_replace('/[^0-9]/', '', $whatsappNumber);

                $waMessage = rawurlencode("Hello Jukti Tech! আমি একটি নতুন অর্ডার প্লেস করেছি।\n"
                    . "📌 অর্ডার আইডি: {$order->order_number}\n"
                    . "🌐 ওয়েবসাইট: {$product->name}\n"
                    . "📦 প্যাকেজ: {$tierTitle}\n"
                    . "💰 মূল্য: ৳ " . number_format($amount, 0) . "\n"
                    . "👤 নাম: {$request->name}\n"
                    . "📱 ফোন: {$request->phone}\n"
                    . ($request->domain_name ? "🔗 ডোমেইন: {$request->domain_name}\n" : '')
                    . "📄 ইনভয়েস: {$invoiceUrl}");

                $whatsappUrl = "https://wa.me/{$cleanWa}?text={$waMessage}";

                return response()->json([
                    'success'        => true,
                    'status'         => 'success',
                    'message'        => 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে!',
                    'order_number'   => $order->order_number,
                    'product_name'   => $product->name,
                    'tier_title'     => $tierTitle,
                    'package_tier'   => $tierTitle,
                    'amount'         => number_format($amount, 0),
                    'total_amount'   => $amount,
                    'payment_method' => $paymentMethodName,
                    'invoice_url'    => $invoiceUrl,
                    'whatsapp_url'   => $whatsappUrl,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Theme Order Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'অর্ডার প্রসেস করতে সাময়িক সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন বা সরাসরি যোগাযোগ করুন। (' . $e->getMessage() . ')',
            ], 500);
        }
    }
}
