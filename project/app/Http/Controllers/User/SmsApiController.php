<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SmsApiController extends Controller
{
    public function sendSms(Request $request)
    {
        // ১. রিকোয়েস্ট ভ্যালিডেশন
        if (!$request->api_key || !$request->number || !$request->message) {
            return response()->json(['status' => 'error', 'message' => 'Missing Parameters!'], 400);
        }

        // ২. ইউজার চেক
        $user = User::where('api_key', $request->api_key)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Invalid API Key!'], 401);
        }

        // ৩. স্প্যাম, বেটিং ও লিঙ্ক ডিটেকশন (সুরক্ষা স্তর)
        $securityCheck = $this->isSecureContent($request->message);
        if ($securityCheck !== true) {
            return response()->json([
                'status'  => 'error', 
                'message' => 'Security Threat: ' . $securityCheck 
            ], 403);
        }

        // ৪. ব্যালেন্স চেক (wallet_balance)
        $smsCost = $user->non_masking_rate ?? 0.50; 
        if ($user->wallet_balance < $smsCost) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient SMS Balance!'], 402);
        }

        $phone = $this->normalizePhoneNumber($request->number);
        $message = $request->message;
        $settings = DB::table('generalsettings')->first();

        try {
            // ৫. Bulk SMS BD গেটওয়ে কল
            $response = Http::get("http://bulksmsbd.net/api/smsapi", [
                'api_key'  => $settings->sms_api_key,
                'type'     => 'text',
                'number'   => $phone,
                'senderid' => $settings->sms_sender_id,
                'message'  => $message
            ]);

            $result = $response->json();

            if (isset($result['response_code']) && $result['response_code'] == 202) {
                $user->decrement('wallet_balance', $smsCost);

                DB::table('sms_histories')->insert([
                    'user_id'     => $user->id,
                    'sender_type' => 'non_masking',
                    'message'     => $message,
                    'numbers'     => $phone,
                    'cost'        => $smsCost,
                    'status'      => 'success',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'SMS sent successfully!',
                    'balance'   => number_format($user->wallet_balance, 2)
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gateway Error: ' . ($result['error_message'] ?? 'Unknown')], 502);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'System Error!'], 500);
        }
    }

    /**
     * বেটিং, স্প্যামিং এবং লিঙ্ক চেক করার ফাংশন
     */
    private function isSecureContent($message)
    {
        // নিষিদ্ধ শব্দ (বেটিং ও জুয়া)
        $bannedWords = [
            'বাজি', 'জুয়া', 'বেটিং', 'টাকা ইনকাম', 'অফার', 'বোনাস', 'বিকাশ অফার', 
            'নগদ অফার', 'টাকা জিতুন', 'উইন', 'ডিপোজিট', 'betting', 'baji', 'casino', 
            'win money', 'bonus', '1xbet', 'melbet', 'linebet', 'lottery', 't.me'
        ];

        // লিঙ্ক ব্লক করার প্যাটার্ন (সব ধরণের ডোমেইন এক্সটেনশন ধরবে)
        $urlPattern = '/(https?:\/\/[^\s]+)|(www\.[^\s]+)|([a-zA-Z0-9-]+\.(com|net|org|xyz|info|me|biz|live|win|tk|buzz|top))/i';

        $messageLower = mb_strtolower($message, 'UTF-8');

        // শব্দ চেক
        foreach ($bannedWords as $word) {
            if (str_contains($messageLower, $word)) {
                return "নিষিদ্ধ শব্দ ($word) পাওয়া গেছে। বেটিং বা স্প্যামিং আমাদের সিস্টেমে নিষিদ্ধ।";
            }
        }

        // লিঙ্ক চেক
        if (preg_match($urlPattern, $message)) {
            return "মেসেজে কোনো প্রকার লিঙ্ক বা ডোমেইন পাঠানো নিষিদ্ধ।";
        }

        return true;
    }

    private function normalizePhoneNumber($number) {
        return preg_replace('/\D+/', '', (string)$number);
    }
}