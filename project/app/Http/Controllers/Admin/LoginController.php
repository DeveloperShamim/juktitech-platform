<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except(['showOtpForm', 'verifyOtp', 'resendOtp']);
        $this->middleware('auth:admin')->only(['showOtpForm', 'verifyOtp', 'resendOtp']);
    }

    public function loginForm()
    {
        return view('admin.login');
    }

    public function showOtpForm()
    {
        return redirect()->route('admin.dashboard');
    }

    public function verifyOtp()
    {
        return redirect()->route('admin.dashboard');
    }

    public function resendOtp()
    {
        return redirect()->route('admin.dashboard');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 422);
        }

        if (!Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            return response()->json(['errors' => [0 => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।']], 422);
        }

        if (Auth::guard('admin')->user()->verify == 0) {
            Auth::guard('admin')->logout();

            return response()->json(['errors' => [0 => 'Your Email is not Verified!']], 422);
        }

        if (Auth::guard('admin')->user()->role_id != 1) {
            Auth::guard('admin')->logout();

            return response()->json(['errors' => [0 => "Credentials Doesn't Match !"]], 422);
        }

        $request->session()->regenerate();

        return response()->json(['redirect_url' => route('admin.dashboard', [], false)]);
    }

    public function showForgotForm()
    {
        return view('admin.forgot');
    }

    public function forgot(Request $request)
    {
        if ($request->secret_key !== '7575') {
            return response()->json(['errors' => [0 => 'Invalid security key. Access denied.']]);
        }

        $gs = GeneralSettings::findOrFail(1);
        $input = $request->all();

        if (Admin::where('email', '=', $request->email)->count() > 0) {
            $admin = Admin::where('email', '=', $request->email)->firstOrFail();
            $autopass = Str::random(8);
            $input['password'] = bcrypt($autopass);
            $admin->update($input);
            $subject = 'Reset Password Request';
            $msg = 'Your New Password is : ' . $autopass;
            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $request->email,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = 'From: ' . $gs->from_name . '<' . $gs->from_email . '>';
                mail($request->email, $subject, $msg, $headers);
            }

            return response()->json('Your Password Reseted Successfully. Please Check your email for new Password.');
        }

        return response()->json(['errors' => [0 => 'No Account Found With This Email.']]);
    }
}
