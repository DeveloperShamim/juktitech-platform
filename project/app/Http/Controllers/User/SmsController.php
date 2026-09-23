<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\SmsHistory;
use App\Models\GeneralSettings;
use App\Models\WalletTransaction;

class SmsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reports = SmsHistory::where('user_id', $user->id)->latest()->paginate(10);
        return view('user.sms.index', compact('user', 'reports'));
    }

// এসএমএস পাঠানোর ইতিহাস দেখার ফাংশন
    public function smsHistory()
    {
        $user = Auth::user();
        
        // আপনার ডাটাবেস এবং এই কন্ট্রোলারের লজিক অনুযায়ী SmsHistory মডেল ব্যবহার করা হয়েছে
        $smsHistories = SmsHistory::where('user_id', $user->id)
                        ->latest()
                        ->paginate(11);

        // ভিউ ফাইলের লোকেশন অনুযায়ী রিটার্ন করা হয়েছে
        return view('user.sms.history', compact('smsHistories'));
    }


    public function send(Request $request)
    {
        $request->validate([
            'numbers' => 'required',
            'message' => 'required',
        ]);
// ১. নিষিদ্ধ শব্দের তালিকা (আপনার পছন্দমতো শব্দ বাড়াতে পারেন)
        $forbiddenWords = [
            'bet', 'betting', 'casino', 'gambling', '1xbet', 'win', 'lottary', 'baji', 
            'বাজি', 'জুয়া', 'ক্যাসিনো', 'লটারি', 'বেটিং', 'টাকা ইনকাম', 'জিতুন',
            'bkash hack', 'nagad hack', 'free money', 'click here', 
            'http://', 'https://', 'www.', '.com', 'bit.ly'
        ];

        // ২. মেসেজ চেক করা (ছোট হাতের অক্ষরে কনভার্ট করে)
        $lowerMsg = strtolower($request->message);
        
        foreach ($forbiddenWords as $word) {
            // যদি নিষিদ্ধ শব্দ পাওয়া যায়, সাথে সাথে এরর রিটার্ন করবে
            if (str_contains($lowerMsg, $word)) {
                return back()->with('error', "নিরাপত্তা ত্রুটি: আপনার মেসেজে নিষিদ্ধ শব্দ ('$word') রয়েছে। স্প্যামিং বা বেটিং লিংক পাঠানো সম্পূর্ণ নিষিদ্ধ।");
            }
        }
        $user = Auth::user();
        $gs = GeneralSettings::first();

        // ১. শুধুমাত্র নন-মাস্কিং রেট নেওয়া হলো
        $rate = $user->non_masking_rate;

        // ২. মেসেজ পার্ট কাউন্ট (বাংলা/ইংলিশ চেক)
        $message = $request->message;
        $isUnicode = strlen($message) != strlen(utf8_decode($message));
        $charLimit = $isUnicode ? 70 : 160;
        $msgLen = mb_strlen($message, 'UTF-8');
        
        $parts = ceil($msgLen / $charLimit);
        if($parts == 0) $parts = 1;

        // ৩. নম্বর প্রসেসিং
        $numberArray = explode(',', $request->numbers);
        $validNumbers = [];
        foreach($numberArray as $num) {
            $cleanNum = trim($num);
            if(strlen($cleanNum) >= 11) $validNumbers[] = $cleanNum;
        }
        
        if(count($validNumbers) == 0) return back()->with('error', 'সঠিক নম্বর পাওয়া যায়নি।');
        $numberStr = implode(',', $validNumbers);
        $totalNumbers = count($validNumbers);

        // ৪. মোট খরচ হিসাব
        $totalCost = $totalNumbers * $parts * $rate;

        // ৫. ব্যালেন্স চেক
        if($user->wallet_balance < $totalCost) {
            return back()->with('error', "পর্যাপ্ত ব্যালেন্স নেই। প্রয়োজন: $totalCost টাকা।");
        }

        try {
            // ৬. API কল (আপনার পজ কোডের মতো হুবহু)
            $response = Http::timeout(10)->get("http://bulksmsbd.net/api/smsapi", [
                'api_key'  => $gs->sms_api_key,
                'type'     => 'text', // পজ কোডে text ছিল তাই এখানেও text দেওয়া হয়েছে
                'number'   => $numberStr,
                'senderid' => $gs->sms_sender_id, // শুধুমাত্র নন-মাস্কিং আইডি
                'message'  => $message
            ]);

            // ৭. সাকসেস চেক এবং ব্যালেন্স ডিডাকশন
            if($response->successful()) { 
                
                DB::transaction(function() use ($user, $totalCost, $request, $numberStr, $totalNumbers, $parts) {
                    // ব্যালেন্স কাটানো
                    $user->decrement('wallet_balance', $totalCost);

                    // ট্রানজেকশন লগ
                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'type' => 'expense',
                        'amount' => $totalCost,
                        'payment_method' => 'SMS Panel',
                        'note' => "SMS Sent to $totalNumbers numbers"
                    ]);

                    // এসএমএস রিপোর্ট
                    SmsHistory::create([
                        'user_id'         => $user->id,
                        'sender_type'     => 'non_masking',
                        'message'         => $request->message,
                        'numbers'         => $numberStr,
                        'total_sms_count' => ($totalNumbers * $parts),
                        'cost'            => $totalCost,
                        'status'          => 'success'
                    ]);
                });

                return back()->with('success', "এসএমএস সফলভাবে পাঠানো হয়েছে। খরচ: $totalCost টাকা।");
            } else {
                return back()->with('error', 'এসএমএস গেটওয়ে রেসপন্স করছে না।');
            }

        } catch(\Exception $e) {
            return back()->with('error', 'সিস্টেম এরর: ' . $e->getMessage());
        }
    }
}