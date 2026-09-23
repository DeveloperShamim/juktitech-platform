<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        /* |--------------------------------------------------------------------------
        | ১. লাইসেন্স সিস্টেমের API রাউটসমূহ
        |--------------------------------------------------------------------------
        */
        'api/verify-license',
        'api/check-update',
        'api/download-update',
        'api/log-unauthorized',

        /* |--------------------------------------------------------------------------
        | ২. অন্যান্য পেমেন্ট এবং নোটিফিকেশন রাউটসমূহ
        |--------------------------------------------------------------------------
        */
        'checkout/payment/*',
        'pay/*',
        'pay/gateway/webhook',
        'cflutter/notify',
        
        'user/paytm-notify',
        'user/razorpay-notify',
        'uflutter/notify',
        'user/ssl-notify',

        'user/deposit/*',
        'dflutter/notify',

        'domain-payment/success',
    'domain-payment/cancel',
    'domain-payment/webhook',
		'api/recharge/webhook', // <--- এই লাইনটি খুবই গুরুত্বপূর্ণ
		'user/recharge-success', // এসএমএস রিচার্জ পেমেন্ট কলব্যাক (গেটওয়ে POST দিয়ে রিডাইরেক্ট করে, CSRF টোকেন নেই)
		'user/recharge-cancel',

		/* |--------------------------------------------------------------------------
    | ৪. Facebook Chatbot Package Payment (UddoktaPay)
    |--------------------------------------------------------------------------
    */
    'chatbot-payment/success',       // Chatbot payment success callback
    'chatbot-payment/cancel',        // Chatbot payment cancel callback
    'chatbot-payment/webhook',       // Chatbot package payment webhook
    'api/chatbot/payment/webhook',   // Chatbot package payment webhook (API)

    /* |--------------------------------------------------------------------------
    | হোস্টিং রিনিউ পেমেন্ট (উদ্যোক্তা পে)
    |--------------------------------------------------------------------------
    */
    'user/hosting/renew/success',
    'user/hosting/renew/cancel',
    'user/hosting/renew/webhook',

    /* |--------------------------------------------------------------------------
    | পাবলিক লাইসেন্স পার্চেজ পেমেন্ট (UddoktaPay – /license/buy)
    |--------------------------------------------------------------------------
    */
    'license/buy/success',
    'license/buy/cancel',
    'license/buy/webhook',

    /* |--------------------------------------------------------------------------
    | লাইসেন্স রিনিউ পেমেন্ট (উদ্যোক্তা পে – ১০০০ টাকা)
    |--------------------------------------------------------------------------
    */
    'user/license/renew/success',
    'user/license/renew/cancel',
    'user/license/renew/webhook',

    /* |--------------------------------------------------------------------------
    | নতুন লাইসেন্স জেনারেশন পেমেন্ট (উদ্যোক্তা পে)
    |--------------------------------------------------------------------------
    */
    'user/license/generate/success',
    'user/license/generate/cancel',
    'user/license/generate/webhook',

    /* |--------------------------------------------------------------------------
    | ডোমেইন রিনিউ পেমেন্ট (উদ্যোক্তা পে)
    |--------------------------------------------------------------------------
    */
    'user/domain/renew/success',
    'user/domain/renew/cancel',
    'user/domain/renew/webhook',
		
		/* |--------------------------------------------------------------------------
    | ৫. API Services (SMS)
    |--------------------------------------------------------------------------
    */
    'api/v1/sms/send',
    'api/smsapi',

        /* |--------------------------------------------------------------------------
        | ৬. Facebook Messenger Webhook (GET/POST – Facebook সার্ভার থেকে কল হয়, CSRF নেই)
        |--------------------------------------------------------------------------
        */
        'chatbot/webhook/facebook',
        'api/webhook/facebook',

        /* Digital product payment webhook */
        'digital-product/payment/webhook',
    ];
}