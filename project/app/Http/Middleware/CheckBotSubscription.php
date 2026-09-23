<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckBotSubscription
{
    /**
     * Handle an incoming request.
     * 
     * ইউজারের Facebook Chatbot subscription চেক করা
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ইউজার লগইন আছে কিনা চেক করা
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'অনুগ্রহ করে লগইন করুন।');
        }

        $user = Auth::user();

        // ইউজারের active_plan আছে কিনা চেক করা
        if (!isset($user->active_plan) || empty($user->active_plan)) {
            // যদি JSON request হয়
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'আপনার কোনো সক্রিয় চ্যাটবট প্ল্যান নেই। অনুগ্রহ করে একটি প্ল্যান কিনুন।'
                ], 403);
            }

            // যদি web request হয়
            return redirect()->route('user.chatbot.pricing')
                ->with('error', 'আপনার কোনো সক্রিয় চ্যাটবট প্ল্যান নেই। অনুগ্রহ করে একটি প্ল্যান কিনুন।');
        }

        // প্ল্যানের মেয়াদ আছে কিনা চেক করা
        if (isset($user->plan_expiry) && $user->plan_expiry) {
            $expiryDate = \Carbon\Carbon::parse($user->plan_expiry);
            
            if ($expiryDate->isPast()) {
                // যদি JSON request হয়
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'আপনার চ্যাটবট প্ল্যানের মেয়াদ শেষ হয়ে গেছে। অনুগ্রহ করে রিনিউ করুন।'
                    ], 403);
                }

                // যদি web request হয়
                return redirect()->route('user.chatbot.pricing')
                    ->with('error', 'আপনার চ্যাটবট প্ল্যানের মেয়াদ শেষ হয়ে গেছে। অনুগ্রহ করে রিনিউ করুন।');
            }
        }

        // সব ঠিক থাকলে request proceed করা
        return $next($request);
    }
}

