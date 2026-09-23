<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // ১. সেটিংস চেক করা
        $gs = GeneralSettings::findOrFail(1);

        // ২. ভ্যালিডেশন রুলস
        if($gs->is_capcha == 1)
        {
            $rules = [
                'email' => 'required', // মোবাইল বা ইমেইল উভয়ের জন্য শুধু required
                'password' => 'required',
                'g-recaptcha-response' => 'required|captcha'
            ];
        }
        else
        {
            $rules = [
                'email' => 'required',
                'password' => 'required'
            ];
        }

        // ৩. ভ্যালিডেশন চেক
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            // ভ্যালিডেশন ফেইল করলে আগের পেজে এরর সহ ফেরত পাঠাবে
            return back()->with('error', 'অনুগ্রহ করে সব তথ্য সঠিক দিন।')->withErrors($validator)->withInput();
        }

        // ৪. ইনপুটটি ইমেইল নাকি ফোন নাম্বার তা নির্ণয় করা
        $input = $request->email;
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // ৫. লগিন করার চেষ্টা
        if(Auth::attempt([$field => $input, 'password' => $request->password], $request->remember))
        {
            if ($request->filled('redirect_to') && !str_contains($request->redirect_to, '/login')) {
                return redirect($request->redirect_to)->with('success', 'লগইন সফল হয়েছে!');
            }
            return redirect()->intended(route('user.digital-downloads.index'))->with('success', 'লগইন সফল হয়েছে!');
        }

        // ৬. লগিন ব্যর্থ হলে এরর মেসেজ সহ ফেরত পাঠাবে
        return back()->with('error', 'আপনার ইমেইল/ফোন অথবা পাসওয়ার্ড ভুল!')->withInput();
    }

    public function logout()
    {
        auth()->logout();
        // লগআউট হওয়ার পর /login পেজে নিয়ে যাবে
        return redirect('/login')->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}