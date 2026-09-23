<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Fund;
use App\Models\FundTransaction;
use App\Models\User;
use App\Services\LicensePurchaseService;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenseGenerateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'paymentSuccess',
            'paymentCancel',
            'paymentWebhook',
        ]);
    }

    /**
     * লাইসেন্স জেনারেট ফর্ম দেখানো
     */
    public function showForm()
    {
        $user = Auth::user();
        $gs = DB::table('generalsettings')->first();
        $price = $gs->license_generation_price ?? 1000;
        $isFree = (bool) ($user->free_license_generation ?? false);
        $lifetimePrice = (float) ($gs->license_lifetime_price ?? $price * 10);

        return view('user.client.generate-license', compact('user', 'gs', 'price', 'isFree', 'lifetimePrice'));
    }

    /**
     * লাইসেন্স জেনারেট প্রসেস করা
     * - যদি ফ্রি: সরাসরি লাইসেন্স তৈরি করে POS-এ ইনভয়েস সেভ করা
     * - যদি পেইড: UddoktaPay-তে রিডাইরেক্ট
     */
    public function generate(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string|max:255|unique:licenses,domain_name',
            'validity'    => 'required|integer|in:0,1,2,3',
            'site_path'   => 'nullable|string|max:500',
        ], [
            'domain_name.unique' => 'এই ডোমেইনের জন্য ইতিমধ্যে একটি লাইসেন্স বিদ্যমান।',
            'domain_name.required' => 'ডোমেইন নাম আবশ্যক।',
            'validity.required' => 'মেয়াদ নির্বাচন করুন।',
        ]);

        $user = Auth::user();
        $gs = DB::table('generalsettings')->first();
        $price = $gs->license_generation_price ?? 1000;
        $lifetimePrice = (float) ($gs->license_lifetime_price ?? $price * 10);
        $isFree = (bool) ($user->free_license_generation ?? false);

        if ($isFree) {
            return $this->generateFreeLicense($user, $request->domain_name, (int) $request->validity, trim($request->site_path ?? ''), $gs);
        }

        // পেইড — CreativePay checkout
        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return back()->with('error', 'পেমেন্ট গেটওয়ে সেটআপ নেই। Admin → CreativePay সেটিংস করুন।');
        }

        $validity = (int) $request->validity;
        $totalAmount = $validity === 0 ? $lifetimePrice : ($price * $validity);

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email ?? ($user->phone . '@user.local'),
            'amount'       => (float) $totalAmount,
            'metadata'     => [
                'user_id'     => $user->id,
                'domain_name' => $request->domain_name,
                'validity'    => $validity,
                'amount'      => (float) $totalAmount,
                'site_path'   => trim($request->site_path ?? ''),
                'type'        => 'license_generate',
            ],
            'redirect_url' => route('user.license.generate.success'),
            'cancel_url'   => route('user.license.generate.cancel'),
            'webhook_url'  => route('user.license.generate.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }

            Log::error('License generate CreativePay no payment_url', ['response' => $result]);
            return back()->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা। আবার চেষ্টা করুন।');
        } catch (\Exception $e) {
            Log::error('License generate CreativePay exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    /**
     * বিনামূল্যে লাইসেন্স জেনারেট করা এবং POS-এ ইনভয়েস সেভ করা
     */
    private function generateFreeLicense($user, $domainName, int $validity, string $sitePath, $gs)
    {
        try {
            $licenseKey   = null;
            $signatureKey = null;
            $license      = null;

            DB::transaction(function () use ($user, $domainName, $validity, $sitePath, &$licenseKey, &$signatureKey, &$license) {
                $licenseKey   = LicenseService::generateLicenseKey();
                $signatureKey = LicenseService::generateSignature($licenseKey, $domainName);

                $license = License::create([
                    'user_id'       => $user->id,
                    'domain_name'   => $domainName,
                    'license_key'   => $licenseKey,
                    'signature_key' => $signatureKey,
                    'site_path'     => $sitePath ?: null,
                    'expiry_date'   => LicenseService::expiryDate($validity),
                    'status'        => 1,
                ]);

                $this->saveLicensePosOrder($user, $license, 0, 0, 0, 'Free', null);
            });

            $pushMsg = '';

            // ১. Local path দিলে সরাসরি .env লেখো
            if ($sitePath !== '' && $licenseKey && $signatureKey) {
                $r = LicenseService::writeToEnv($sitePath, $licenseKey, $signatureKey);
                $pushMsg .= $r['success'] ? ' Local .env আপডেট হয়েছে।' : ' ⚠️ Local .env: ' . $r['message'];
                Log::info('User free license .env write', ['license_id' => $license?->id ?? null, 'result' => $r]);
            }

            // ২. HTTP Push করো
            if ($licenseKey && $signatureKey) {
                $push = LicenseService::pushToClientSite(
                    $domainName, $licenseKey, $signatureKey,
                    $license && $license->expiry_date ? $license->expiry_date->toDateString() : null
                );
                $pushMsg .= $push['success'] ? ' সাইটে push সফল ✅' : '';
                Log::info('User free license push', ['license_id' => $license?->id ?? null, 'result' => $push]);
            }

            return redirect()->route('user.licenses')->with('success', 'লাইসেন্স সফলভাবে জেনারেট হয়েছে!' . $pushMsg);
        } catch (\Exception $e) {
            Log::error('Free license generate error: ' . $e->getMessage());
            return back()->with('error', 'লাইসেন্স জেনারেট করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * UddoktaPay পেমেন্ট সাকসেস কলব্যাক
     */
    public function paymentSuccess(Request $request)
    {
        $invoiceId = $request->get('invoice_id');

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                return redirect()->route('user.license.generate.thankyou')->with('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ।');
            }

            if (isset($result['status'])) {
                $status = $result['status'];

                if (in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                    $meta = is_string($result['metadata']) ? json_decode($result['metadata'], true) : $result['metadata'];

                    if (!isset($meta['type']) || $meta['type'] !== 'license_generate') {
                        return redirect()->route('user.licenses')->with('error', 'অবৈধ পেমেন্ট মেটাডেটা।');
                    }

                    DB::transaction(function () use ($meta, $invoiceId, $status) {
                        $this->processLicensePayment($meta, $invoiceId, $status);
                    });

                    // গেটওয়ে রিডাইরেক্টে সেশন থাকে না
                    if (!empty($meta['user_id'])) {
                        Auth::loginUsingId($meta['user_id']);
                    }

                    if ($status === 'PENDING') {
                        return redirect()->route('user.license.generate.thankyou')
                            ->with('pending', 'আপনার পেমেন্ট পেন্ডিং আছে। অ্যাডমিন পেমেন্ট নিশ্চিত করলে লাইসেন্স স্বয়ংক্রিয়ভাবে তৈরি হবে।');
                    }

                    return redirect()->route('user.license.generate.thankyou')
                        ->with('success', 'পেমেন্ট সফল! লাইসেন্স জেনারেট হয়েছে।');
                }
            }
        } catch (\Exception $e) {
            Log::error('License generate payment success error: ' . $e->getMessage());
        }

        return redirect()->route('user.license.generate.thankyou')
            ->with('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ।');
    }

    /**
     * পেমেন্ট বাতিল
     */
    public function paymentCancel()
    {
        return redirect()->route('user.license.generate.show')
            ->with('error', 'পেমেন্ট বাতিল হয়েছে। আবার চেষ্টা করুন।');
    }

    /**
     * UddoktaPay Webhook
     */
    public function paymentWebhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = $request->all();
        if (isset($data['status']) && isset($data['metadata'])) {
            $meta = is_string($data['metadata']) ? json_decode($data['metadata'], true) : $data['metadata'];
            if (isset($meta['type']) && $meta['type'] === 'license_generate') {
                DB::transaction(function () use ($meta, $data) {
                    $this->processLicensePayment($meta, $data['invoice_id'], $data['status']);
                });
            }
        }
        return response()->json(['message' => 'Success']);
    }

    /**
     * Thank You পেজ
     */
    public function thankYou()
    {
        return view('user.client.license-generate-thankyou');
    }

    /**
     * পেমেন্ট প্রসেস করা: লাইসেন্স তৈরি (যদি সফল) এবং POS ইনভয়েস সেভ করা
     */
    private function processLicensePayment(array $meta, $invoiceId, $status)
    {
        $userId     = $meta['user_id'];
        $domainName = $meta['domain_name'];
        $validity   = (int) ($meta['validity'] ?? 1);
        $amount     = (float) ($meta['amount'] ?? 0);
        $sitePath   = trim($meta['site_path'] ?? '');

        $user = User::find($userId);
        if (!$user) {
            Log::warning("License generate: User not found: {$userId}");
            return;
        }

        $purchaseService = app(LicensePurchaseService::class);
        $existingOrder   = $purchaseService->findLicenseOrderByInvoice($invoiceId);
        $isSuccess       = in_array($status, ['COMPLETED', 'SUCCESS']);

        if ($existingOrder) {
            if (!$isSuccess) {
                Log::info("License generate: order {$existingOrder->order_number} still pending");

                return;
            }

            $this->finalizePaidLicenseOrder($user, $existingOrder, $domainName, $validity, $amount, $sitePath, $invoiceId);

            return;
        }

        // ডোমেইন ইতিমধ্যে লাইসেন্স নেওয়া আছে কিনা চেক
        $domainExists = License::where('domain_name', $domainName)->exists();
        if ($domainExists) {
            Log::warning("License generate: Domain already has license: {$domainName}");
            // তবুও POS-এ order সেভ করা হবে reference-এর জন্য
        }

        $license = null;
        if ($isSuccess && !$domainExists) {
            $licenseKey   = LicenseService::generateLicenseKey();
            $signatureKey = LicenseService::generateSignature($licenseKey, $domainName);

            $license = License::create([
                'user_id'       => $userId,
                'domain_name'   => $domainName,
                'license_key'   => $licenseKey,
                'signature_key' => $signatureKey,
                'site_path'     => $sitePath ?: null,
                'expiry_date'   => LicenseService::expiryDate($validity),
                'status'        => 1,
            ]);

            // Local .env লেখো (path থাকলে)
            if ($sitePath !== '') {
                $envResult = LicenseService::writeToEnv($sitePath, $licenseKey, $signatureKey);
                Log::info('User paid license .env write', ['license_id' => $license->id, 'result' => $envResult]);
            }

            // HTTP Push
            $push = LicenseService::pushToClientSite(
                $domainName, $licenseKey, $signatureKey,
                $license->expiry_date ? $license->expiry_date->toDateString() : null
            );
            Log::info('User paid license push', ['license_id' => $license->id, 'result' => $push]);
        }

        // POS অর্ডার/ইনভয়েস সেভ করা
        $paidAmount = $isSuccess ? $amount : 0;
        $dueAmount  = $isSuccess ? 0 : $amount;

        $this->saveLicensePosOrder($user, $license, $amount, $paidAmount, $dueAmount, 'CreativePay', $invoiceId, $domainName, $validity);

        // Fund আপডেট (শুধু সফল পেমেন্টে)
        if ($isSuccess && $paidAmount > 0) {
            $fund = Fund::firstOrCreate(['id' => 1]);
            $fund->increment('current_balance', $paidAmount);

            FundTransaction::create([
                'type'           => 'income',
                'amount'         => $paidAmount,
                'payment_method' => 'CreativePay',
                'reference_id'   => 'LIC-GEN-' . $invoiceId,
                'note'           => 'লাইসেন্স জেনারেশন: ' . $domainName,
            ]);
        }
    }

    /**
     * পেন্ডিং অর্ডার পেইড হলে লাইসেন্স তৈরি + অর্ডার ক্লোজ
     */
    private function finalizePaidLicenseOrder(
        User $user,
        Order $order,
        string $domainName,
        int $validity,
        float $amount,
        string $sitePath,
        $invoiceId
    ): void {
        $license = License::where('domain_name', $domainName)->first();

        if (!$license) {
            $licenseKey   = LicenseService::generateLicenseKey();
            $signatureKey = LicenseService::generateSignature($licenseKey, $domainName);

            $license = License::create([
                'user_id'       => $user->id,
                'domain_name'   => $domainName,
                'license_key'   => $licenseKey,
                'signature_key' => $signatureKey,
                'site_path'     => $sitePath ?: null,
                'expiry_date'   => LicenseService::expiryDate($validity),
                'status'        => 1,
            ]);

            if ($sitePath !== '') {
                LicenseService::writeToEnv($sitePath, $licenseKey, $signatureKey);
            }

            LicenseService::pushToClientSite(
                $domainName,
                $licenseKey,
                $signatureKey,
                $license->expiry_date ? $license->expiry_date->toDateString() : null
            );
        }

        if ($order->due_amount > 0) {
            $collected = $order->due_amount;
            $order->paid_amount = $order->total_amount;
            $order->due_amount  = 0;
            $order->status      = 'Active';
            $order->save();

            $ref = 'LIC-GEN-' . $invoiceId;
            if (!FundTransaction::where('reference_id', $ref)->exists()) {
                $fund = Fund::firstOrCreate(['id' => 1]);
                $fund->increment('current_balance', $collected);
                FundTransaction::create([
                    'type'           => 'income',
                    'amount'         => $collected,
                    'payment_method' => 'CreativePay',
                    'reference_id'   => $ref,
                    'note'           => 'লাইসেন্স জেনারেশন: ' . $domainName,
                ]);
            }
        }

        $meta = json_decode($order->support_note ?? '{}', true);
        if (is_array($meta) && $license) {
            $meta['license_id'] = $license->id;
            $order->support_note = json_encode($meta);
            $order->save();
        }
    }

    /**
     * POS অর্ডার/ইনভয়েস তৈরি করা (License Generation এর জন্য)
     */
    public static function saveLicensePosOrder(
        $user,
        $license,
        float $totalAmount,
        float $paidAmount,
        float $dueAmount,
        string $paymentMethod,
        $invoiceId,
        string $domainName = '',
        int $validity = 1
    ) {
        try {
            // Customer তৈরি বা খোঁজা
            $customer = Customer::firstOrCreate(
                ['phone' => $user->phone],
                ['name' => $user->name, 'address' => $user->address, 'user_id' => $user->id]
            );

            if (!$customer->user_id) {
                $customer->user_id = $user->id;
                $customer->save();
            }

            // support_note হিসেবে metadata সেভ করা (POS থেকে পেইড করলে লাইসেন্স অ্যাক্টিভেট করতে লাগবে)
            $supportNote = json_encode([
                'type'        => 'license_generation',
                'user_id'     => $user->id,
                'domain_name' => $domainName ?: ($license ? $license->domain_name : ''),
                'validity'    => $validity,
                'license_id'  => $license ? $license->id : null,
                'invoice_id'  => $invoiceId,
            ]);

            $domainDisplay = $domainName ?: ($license ? $license->domain_name : '');

            $order = Order::create([
                'customer_id'    => $customer->id,
                'order_number'   => 'LIC-' . strtoupper(Str::random(8)),
                'description'    => 'License Generation: ' . $domainDisplay,
                'support_note'   => $supportNote,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => $paymentMethod,
                'hash_token'     => bin2hex(random_bytes(16)),
                'status'         => $dueAmount <= 0 ? 'Active' : 'Pending',
            ]);

            Log::info("License POS order created: {$order->order_number} for domain {$domainDisplay}");
        } catch (\Exception $e) {
            Log::error("License POS order save error: " . $e->getMessage());
        }
    }
}
