<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatbotPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatbotPackagePurchaseController extends Controller
{
    public function index()
    {
        $packages = ChatbotPackage::where('status', 1)->orderBy('price', 'asc')->get();
        return view('user.chatbot.pricing', compact('packages')); 
    }

    public function buy($id)
    {
        $package = ChatbotPackage::findOrFail($id);
        $user = Auth::user();

        if (!$user->email) {
            return back()->with('error', 'আপনার প্রোফাইলে ইমেইল নেই।');
        }

        $gs = DB::table('generalsettings')->first();

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email,
            'amount'       => $package->price,
            'metadata'     => [
                'user_id'    => $user->id,
                'package_id' => $package->id,
                'type'       => 'chatbot_package'
            ],
            'redirect_url' => route('user.chatbot.payment.success'),
            'cancel_url'   => route('user.chatbot.payment.cancel'),
            'webhook_url'  => route('user.chatbot.payment.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            return back()->with('error', 'পেমেন্ট গেটওয়ে এরর।');
        } catch (\Exception $e) {
            return back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('============ CHATBOT PAYMENT SUCCESS START ============');
            Log::info('Request Method: ' . $request->method());
            Log::info('Request All Data: ' . json_encode($request->all()));
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Request Headers: ' . json_encode($request->headers->all()));
            Log::info('Auth User: ' . (Auth::check() ? Auth::id() : 'Not Logged In'));
            
            $invoiceId = $request->get('invoice_id');
            
            if (!$invoiceId) {
                Log::error('❌ Invoice ID Missing!');
                return redirect()->route('user.chatbot.pricing')->with('error', 'Invalid payment data.');
            }
            
            Log::info('Invoice ID: ' . $invoiceId);
            
            $gs = DB::table('generalsettings')->first();

            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                Log::error('CreativePay verify failed for invoice: ' . $invoiceId);
                return redirect()->route('user.chatbot.pricing')->with('error', 'Invalid payment data.');
            }

            Log::info('CreativePay Response: ' . json_encode($result));

            if (isset($result['status'])) {
                $status = $result['status'];
                Log::info('Payment Status: ' . $status);
                
                if ($status === 'COMPLETED' || $status === 'SUCCESS' || $status === 'PENDING') {
                    
                    $meta = is_string($result['metadata']) ? json_decode($result['metadata'], true) : $result['metadata'];
                    Log::info('Metadata: ' . json_encode($meta));
                    
                    DB::transaction(function () use ($meta, $invoiceId, $status, $result) {
                        Log::info('Starting Transaction...');
                        $this->processPaymentData($meta['user_id'], $meta['package_id'], $invoiceId, $status);
                        Log::info('Transaction Complete!');
                    });

                    // Auto Login User
                    Auth::loginUsingId($meta['user_id']);
                    Log::info('User Auto Logged In: ' . $meta['user_id']);

                    if ($status === 'PENDING') {
                        Log::info('Redirecting to Pricing (Pending)');
                        return redirect()->route('user.chatbot.pricing')->with('success', 'আপনার পেমেন্ট পেন্ডিং অবস্থায় আছে। অর্ডারটি বকেয়া হিসেবে জমা হয়েছে।');
                    } else {
                        Log::info('Redirecting to Pricing (Success)');
                        return redirect()->route('user.chatbot.pricing')->with('success', 'পেমেন্ট সফল! আপনার চ্যাটবট প্যাকেজ চালু হয়েছে।');
                    }
                }
            }
            
            Log::error('Payment Verification Failed - Invalid Status or Response');
            Log::error('Full Response: ' . json_encode($result));
            
        } catch (\Exception $e) {
            Log::error('============ PAYMENT EXCEPTION ============');
            Log::error('Exception Message: ' . $e->getMessage());
            Log::error('Exception File: ' . $e->getFile());
            Log::error('Exception Line: ' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('user.chatbot.pricing')->with('error', 'পেমেন্ট প্রসেস এ সমস্যা। Error: ' . $e->getMessage());
        }

        return redirect()->route('user.chatbot.pricing')->with('error', 'ভেরিফিকেশন ব্যর্থ।');
    }

    private function processPaymentData($userId, $packageId, $invoiceId, $status)
    {
        Log::info('--- PROCESS PAYMENT DATA START ---');
        Log::info('User ID: ' . $userId . ' | Package ID: ' . $packageId);
        
        $user = User::find($userId);
        $package = ChatbotPackage::find($packageId);

        if(!$user) {
            Log::error('User Not Found: ' . $userId);
            throw new \Exception('User not found');
        }
        
        if(!$package) {
            Log::error('Package Not Found: ' . $packageId);
            throw new \Exception('Package not found');
        }
        
        Log::info('User Found: ' . $user->name);
        Log::info('Package Found: ' . $package->name);

        if($user && $package) {
            // ১. কাস্টমার টেবিল চেক
            $customer = DB::table('customers')->where('phone', $user->phone)->first();
            if (!$customer) {
                $customerId = DB::table('customers')->insertGetId([
                    'user_id'    => $user->id,
                    'name'       => $user->name,
                    'phone'      => $user->phone,
                    'address'    => $user->address,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $customerId = $customer->id;
            }

            // ২. স্ট্যাটাস অনুযায়ী এমাউন্ট সেট করা
            $totalAmount = $package->price;
            $paidAmount = 0;
            $dueAmount = 0;

            if ($status === 'COMPLETED' || $status === 'SUCCESS') {
                // পেমেন্ট সফল: সব টাকা জমা, বাকি ০
                $paidAmount = $totalAmount;
                $dueAmount = 0;
                
                // সফল হলেই প্যাকেজ activate করা
                $this->activateChatbotPackage($user, $package);

            } else {
                // পেমেন্ট পেন্ডিং: টাকা জমা ০, বাকি সব (বকেয়া)
                $paidAmount = 0;
                $dueAmount = $totalAmount;
            }

            // ৩. অর্ডার টেবিলে ডাটা সেভ
            $existingOrder = DB::table('orders')->where('support_note', 'LIKE', "%$invoiceId%")->exists();
            
            if (!$existingOrder) {
                // support_note এ JSON format এ metadata save করা হচ্ছে যাতে POS থেকে package ID extract করা যায়
                $supportNoteData = [
                    'txn_id' => $invoiceId,
                    'status' => $status,
                    'type' => 'chatbot_package',
                    'chatbot_package_id' => $packageId, // এটি POS controller এ ব্যবহার হবে
                    'user_id' => $userId
                ];
                
                DB::table('orders')->insert([
                    'customer_id'    => $customerId,
                    'order_number'   => 'CHATBOT-' . strtoupper(uniqid()),
                    'description'    => 'Chatbot Package: ' . $package->name,
                    'support_note'   => json_encode($supportNoteData), // JSON format এ save করা হচ্ছে
                    'total_amount'   => $totalAmount,
                    'paid_amount'    => $paidAmount,
                    'due_amount'     => $dueAmount,
                    'payment_method' => 'Other', 
                    'hash_token'     => md5(uniqid()),
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
            }
        }
    }

    /**
     * Chatbot Package Activation Logic
     */
    private function activateChatbotPackage($user, $package)
    {
        Log::info('--- ACTIVATE CHATBOT PACKAGE ---');
        Log::info('User: ' . $user->name . ' (ID: ' . $user->id . ')');
        Log::info('Package: ' . $package->name . ' (ID: ' . $package->id . ')');
        
        try {
            $newExpiry = ($user->plan_expiry && Carbon::parse($user->plan_expiry)->isFuture())
                ? Carbon::parse($user->plan_expiry)->addDays($package->duration_days)
                : Carbon::now()->addDays($package->duration_days);

            Log::info('New Expiry: ' . $newExpiry);

            $user->update([
                'chatbot_package_id'      => $package->id,
                'active_plan'             => $package->name,
                'plan_expiry'             => $newExpiry,
                'connected_pages_limit'   => $package->page_limit,
                'monthly_message_limit'   => $package->message_limit,
                'updated_at'              => now()
            ]);

            Log::info('✅ Package Activated Successfully!');
            Log::info('Active Plan: ' . $user->fresh()->active_plan);
            
        } catch (\Exception $e) {
            Log::error('❌ Package Activation Failed!');
            Log::error('Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function paymentCancel() {
        return redirect()->route('user.chatbot.pricing')->with('error', 'পেমেন্ট বাতিল হয়েছে।');
    }

    public function paymentWebhook(Request $request) {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = $request->all();
        if (isset($data['status']) && in_array($data['status'], ['COMPLETED', 'SUCCESS'], true)) {
            $meta = is_string($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : ($data['metadata'] ?? []);
            if (isset($meta['type']) && $meta['type'] == 'chatbot_package') {
                DB::transaction(function () use ($meta, $data) {
                    $this->processPaymentData($meta['user_id'], $meta['package_id'], $data['invoice_id'], $data['status']);
                });
            }
        }
        return response()->json(['message' => 'Success']);
    }
}
