<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Services\LicensePurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublicLicensePurchaseController extends Controller
{
    /** Fixed license price for public purchase (Taka) */
    private const DEFAULT_PRICE = 3000;

    public function __construct(
        private readonly LicensePurchaseService $purchaseService
    ) {}

    // =========================================================
    // 1. License Purchase Page
    // GET /license?domain=example.com
    // =========================================================
    public function show(Request $request)
    {
        $gs     = DB::table('generalsettings')->first();
        $domain = License::normalizeHostInput($request->query('domain', ''));
        $price  = (float) ($gs->license_generation_price ?? self::DEFAULT_PRICE);

        $existingLicense = null;
        if ($domain) {
            $existingLicense = License::where('domain_name', $domain)->first();
        }

        return view('frontend.license', compact('gs', 'domain', 'price', 'existingLicense'));
    }

    // =========================================================
    // 2. Checkout — UddoktaPay-তে রিডাইরেক্ট
    // POST /license/buy
    // =========================================================
    public function checkout(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:255',
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|max:150',
        ], [
            'domain.required' => 'ডোমেইন নাম প্রয়োজন।',
            'name.required'   => 'নাম প্রয়োজন।',
            'email.required'  => 'ইমেইল প্রয়োজন।',
        ]);

        $domain = License::normalizeHostInput($request->domain);
        if (!$domain) {
            return back()->withErrors(['domain' => 'সঠিক ডোমেইন দিন।']);
        }

        if (License::where('domain_name', $domain)->exists()) {
            return back()->with('error', 'এই ডোমেইনের জন্য ইতিমধ্যে একটি লাইসেন্স বিদ্যমান।');
        }

        $gs    = DB::table('generalsettings')->first();
        $price = (float) ($gs->license_generation_price ?? self::DEFAULT_PRICE);

        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return back()->with('error', 'পেমেন্ট গেটওয়ে এখনো সেটআপ হয়নি। অ্যাডমিনের সাথে যোগাযোগ করুন।');
        }

        $requestData = [
            'full_name'    => $request->name,
            'email'        => $request->email,
            'amount'       => $price,
            'metadata'     => [
                'type'   => 'public_license_purchase',
                'domain' => $domain,
                'name'   => $request->name,
                'email'  => $request->email,
                'amount' => $price,
            ],
            'redirect_url' => route('public.license.success'),
            'cancel_url'   => route('public.license.cancel'),
            'webhook_url'  => route('public.license.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }

            Log::error('PublicLicense CreativePay no payment_url', ['response' => $result]);

            return back()->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        } catch (\Exception $e) {
            Log::error('PublicLicense CreativePay exception', ['error' => $e->getMessage()]);

            return back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    // =========================================================
    // 3. Payment Success Callback
    // =========================================================
    public function paymentSuccess(Request $request)
    {
        $invoiceId = $request->get('invoice_id');

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                return $this->thankYou('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ হয়েছে।');
            }

            if (isset($result['status']) && in_array($result['status'], ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                $meta = is_string($result['metadata']) ? json_decode($result['metadata'], true) : $result['metadata'];

                if (!isset($meta['type']) || $meta['type'] !== 'public_license_purchase') {
                    return $this->thankYou('error', 'অবৈধ পেমেন্ট মেটাডেটা।');
                }

                $isSuccess   = in_array($result['status'], ['COMPLETED', 'SUCCESS']);
                $licenseData = null;

                DB::transaction(function () use ($meta, $invoiceId, $isSuccess, &$licenseData) {
                    $licenseData = $this->purchaseService->processPublicPurchase($meta, $invoiceId, $isSuccess);
                });

                if ($result['status'] === 'PENDING') {
                    return $this->thankYou(
                        'pending',
                        'পেমেন্ট পেন্ডিং। অর্ডার তৈরি হয়েছে — অ্যাডমিন প্যানেল (POS) থেকে বকেয়া পরিশোধ হলে লাইসেন্স স্বয়ংক্রিয়ভাবে তৈরি হবে।'
                    );
                }

                if (!$licenseData) {
                    return $this->thankYou('error', 'লাইসেন্স তৈরি করা যায়নি। সাপোর্টে যোগাযোগ করুন।');
                }

                $pushOk = $licenseData['push_success'] ?? false;
                $msg    = $pushOk
                    ? 'পেমেন্ট সফল! আপনার লাইসেন্স তৈরি হয়েছে এবং সাইটে স্বয়ংক্রিয়ভাবে সেট হয়েছে।'
                    : 'পেমেন্ট সফল! লাইসেন্স তৈরি হয়েছে। নিচের কী গুলো আপনার .env ফাইলে সেট করুন।';

                return $this->thankYou('success', $msg, $licenseData);
            }
        } catch (\Exception $e) {
            Log::error('PublicLicense payment success error: ' . $e->getMessage());
        }

        return $this->thankYou('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ হয়েছে।');
    }

    public function paymentCancel()
    {
        return redirect()->route('public.license.show')
            ->with('error', 'পেমেন্ট বাতিল হয়েছে।');
    }

    public function paymentWebhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        if (!isset($data['status'], $data['metadata'])) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $meta = is_string($data['metadata']) ? json_decode($data['metadata'], true) : $data['metadata'];

        if (isset($meta['type']) && $meta['type'] === 'public_license_purchase') {
            $isSuccess = in_array($data['status'], ['COMPLETED', 'SUCCESS']);
            DB::transaction(function () use ($meta, $data, $isSuccess) {
                $this->purchaseService->processPublicPurchase(
                    $meta,
                    $data['invoice_id'] ?? null,
                    $isSuccess
                );
            });
        }

        return response()->json(['message' => 'Success']);
    }

    private function thankYou(string $type, string $message, ?array $licenseData = null)
    {
        $gs = DB::table('generalsettings')->first();

        return view('frontend.license-thankyou', compact('gs', 'type', 'message', 'licenseData'));
    }
}
