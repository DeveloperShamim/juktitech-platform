<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SmsHistory;
use App\Models\GeneralSettings; // <--- এখানে 's' যুক্ত করা হয়েছে

class SmsManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // ১. সকল এসএমএস হিস্ট্রি দেখার জন্য
    public function history()
    {
        $logs = SmsHistory::with('user')->latest()->paginate(20);
        return view('admin.sms.history', compact('logs'));
    }

    // ২. গ্লোবাল সেটিংস পেজ
    public function settings()
    {
        // GeneralSettings মডেল ব্যবহার করা হচ্ছে
        $gs = GeneralSettings::first(); 
        return view('admin.sms.settings', compact('gs'));
    }

    // ৩. গ্লোবাল সেটিংস আপডেট করা
    public function settingsUpdate(Request $request)
    {
        // GeneralSettings মডেল ব্যবহার করা হচ্ছে
        $gs = GeneralSettings::find(1);
        
        if($gs) {
            $gs->sms_api_key = $request->sms_api_key;
            $gs->sms_sender_id = $request->sms_sender_id;
            $gs->masking_sender_id = $request->masking_sender_id;
            $gs->save();
        }

        return redirect()->back()->with('success', 'SMS API Settings Updated Successfully.');
    }

    // ৪. নির্দিষ্ট ইউজারের ব্যালেন্স আপডেট করা
    public function updateUserSmsSettings(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->wallet_balance = $request->wallet_balance;
        $user->non_masking_rate = $request->non_masking_rate;
        $user->masking_rate = $request->masking_rate;
        $user->save();

        return redirect()->back()->with('success', 'User Balance & SMS Rates Updated Successfully.');
    }
}