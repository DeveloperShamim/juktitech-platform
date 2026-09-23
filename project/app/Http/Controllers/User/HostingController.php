<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HostingPlan;
use App\Models\UserHosting;
use App\Models\HostingRenewalOrder;
use App\Models\Order;
use App\Models\HostingServer;
use App\Models\User;
use App\Jobs\ProcessHosting;
use App\Services\DomainHostingPricingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class HostingController extends Controller
{
    public function index()
    {
        $plans = HostingPlan::where('status', 1)->get();
        return view('user.hosting.index', compact('plans'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'domain' => 'required',
            'plan_id' => 'required'
        ]);

        $domain = trim($request->domain);
        // ইতিমধ্যে এই ডোমেইনে এক্টিভ হোস্টিং থাকলে অর্ডার ব্লক
        if (UserHosting::where('domain', $domain)->where('status', 'Active')->exists()) {
            return redirect()->back()->with('error', 'এই ডোমেইনে ইতিমধ্যে এক্টিভ হোস্টিং আছে। নতুন করে অর্ডার করা যাবে না।');
        }

        $user = Auth::user();
        $plan = HostingPlan::findOrFail($request->plan_id);
        $billingPeriod = DomainHostingPricingService::normalizePeriod($request->input('billing_period', 'yearly'));
        $billingYears  = DomainHostingPricingService::normalizeYears($request->input('billing_years', 1));

        if ($billingPeriod === 'monthly' && (float) $plan->price_monthly <= 0) {
            return redirect()->back()->with('error', 'এই প্ল্যানে মাসিক মূল্য সেট করা নেই।');
        }

        $totalAmount = $billingPeriod === 'monthly'
            ? (float) $plan->price_monthly
            : (float) $plan->price_yearly * $billingYears;

        // অর্ডার তৈরি
        $order = Order::create([
            'customer_id' => $user->id, 
            'order_number' => 'HOST-' . strtoupper(Str::random(8)),
            'description' => "Hosting Purchase: " . $request->domain,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'due_amount' => $totalAmount,
            'payment_method' => 'Online',
            'status' => 'Pending',
            'support_note' => json_encode([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'domain' => $request->domain,
                'server_id' => $plan->server_id,
                'billing_period' => $billingPeriod,
                'billing_years' => $billingYears,
                'hosting_unit_price' => $billingPeriod === 'monthly' ? (float) $plan->price_monthly : (float) $plan->price_yearly,
                'hosting_price' => $totalAmount
            ]),
            'hash_token' => Str::random(20)
        ]);

        return $this->activateHosting($order->id); 
    }

    public function activateHosting($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $meta = json_decode($order->support_note, true);

            if (!$meta || !isset($meta['plan_id'])) {
                return redirect()->route('user.dashboard')->with('error', 'অর্ডার তথ্যে সমস্যা।');
            }

            // ১. সঠিক ইউজার খুঁজে বের করা (Foreign Key Fix)
            $user = User::find($meta['user_id'] ?? $order->customer_id);
            if (!$user) {
                return redirect()->route('user.dashboard')->with('error', 'ইউজার খুঁজে পাওয়া যায়নি।');
            }

            $plan = HostingPlan::find($meta['plan_id']);
            $serverId = $meta['server_id'] ?? ($plan ? $plan->server_id : null);
            
            // ২. অর্ডার স্ট্যাটাস সাথে সাথে Active (পেমেন্ট ডান)
            $order->update([
                'paid_amount' => $order->total_amount, 
                'due_amount' => 0, 
                'status' => 'Active' 
            ]);

            // ক্রেডেনশিয়াল জেনারেট
            $domainParts = explode('.', $meta['domain']);
            $baseName = Str::lower(preg_replace('/[^a-z0-9]/', '', $domainParts[0]));
            $cpanelUser = Str::limit($baseName, 6, '') . rand(10, 99);
            $cpanelPass = Str::random(12) . 'A1!';

            $billingYears  = DomainHostingPricingService::normalizeYears($meta['billing_years'] ?? 1);
            $billingPeriod = DomainHostingPricingService::normalizePeriod($meta['billing_period'] ?? 'yearly');
            $unitPrice     = (float) ($meta['hosting_unit_price']
                ?? ($billingPeriod === 'monthly' ? $plan->price_monthly : $plan->price_yearly));

            $hostingPayload = [
                'user_id'       => $user->id,
                'server_id'     => $serverId,
                'plan_id'       => $plan->id,
                'domain'        => $meta['domain'],
                'username'      => $cpanelUser,
                'password'      => encrypt($cpanelPass),
                'billing_cycle' => DomainHostingPricingService::billingCycleLabel($billingYears, $billingPeriod),
                'amount'        => $unitPrice,
                'next_due_date' => DomainHostingPricingService::hostingNextDueDate($billingPeriod, $billingYears),
                'status'        => 'Pending',
            ];

            if (Schema::hasColumn('user_hostings', 'billing_years')) {
                $hostingPayload['billing_years'] = $billingPeriod === 'monthly' ? 1 : $billingYears;
            }

            // ৩. হোস্টিং ডাটাবেসে সেভ (Pending) - ইউজার দেখবে তার হোস্টিং প্রসেসিং এ আছে
            $userHosting = UserHosting::updateOrCreate(
                ['order_id' => $order->id],
                $hostingPayload
            );

            // ৪. WHM এ cPanel তৈরি (sync queue হলে সাথে সাথে, নাহলে ব্যাকগ্রাউন্ডে)
            if (config('queue.default') === 'sync') {
                ProcessHosting::dispatchSync($userHosting->id);
            } else {
                ProcessHosting::dispatch($userHosting->id)->afterResponse();
            }

            // ৫. ইনস্ট্যান্ট সাকসেস মেসেজ
            return redirect()->route('user.dashboard')->with('success', 'পেমেন্ট সফল! আপনার হোস্টিং সেটআপ হচ্ছে, ১-২ মিনিটের মধ্যে এসএমএস পাবেন।');

        } catch (\Exception $e) {
            Log::error("Controller Activation Error: " . $e->getMessage());
            return redirect()->route('user.dashboard')->with('error', 'পেমেন্ট সেভ হয়েছে কিন্তু সেটআপে সমস্যা। সাপোর্টে যোগাযোগ করুন।');
        }
    }

    /**
     * হোস্টিং রিনিউ – উদ্যোক্তা পে (UddoktaPay) দিয়ে পেমেন্ট ইনিশিয়েট
     * সাস্পেন্ড বা এক্সপায়ার হোস্টিং রিনিউ করতে এই রাউটে আসে।
     */
    public function renew($id)
    {
        $hosting = UserHosting::with('plan')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Suspended', 'Expired'])
            ->findOrFail($id);

        $plan = $hosting->plan;
        if (!$plan) {
            return redirect()->route('user.hostings.index')->with('error', 'প্ল্যান তথ্য পাওয়া যাচ্ছে না।');
        }

        $user = Auth::user();
        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়ে সেটআপ নেই। Admin → CreativePay সেটিংস করুন।');
        }

        $amount = DomainHostingPricingService::renewalAmount($hosting);
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'প্ল্যানের মূল্য সেট করা নেই।');
        }

        try {
            $renewOrder = HostingRenewalOrder::create([
                'user_id'         => $user->id,
                'user_hosting_id' => $hosting->id,
                'amount'          => (float) $amount,
                'status'          => 'pending',
            ]);
            Log::info('Hosting renewal order created', ['order_id' => $renewOrder->id, 'hosting_id' => $hosting->id]);
        } catch (\Throwable $e) {
            Log::error('Hosting renewal order create failed', ['error' => $e->getMessage(), 'hosting_id' => $hosting->id]);
            return redirect()->back()->with('error', 'অর্ডার সেভ করতে সমস্যা।');
        }

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email ?? $user->username . '@user.local',
            'amount'       => (float) $amount,
            'metadata'     => [
                'user_id'    => $user->id,
                'hosting_id' => (int) $hosting->id,
                'plan_id'    => (int) $hosting->plan_id,
                'type'       => 'hosting_renewal',
            ],
            'redirect_url' => route('user.hosting.renew.success') . '?hosting_id=' . (int) $hosting->id,
            'cancel_url'   => route('user.hosting.renew.cancel'),
            'webhook_url'  => route('user.hosting.renew.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            Log::error('Hosting renew CreativePay no payment_url', ['response' => $result]);
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা। আবার চেষ্টা করুন।');
        } catch (\Exception $e) {
            Log::error('Hosting renew CreativePay exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    /**
     * রিনিউ পেমেন্ট সাকসেস – ভেরিফাই করে হোস্টিং অ্যাক্টিভ + next_due_date বাড়ান, WHM আনসাস্পেন্ড
     */
    public function renewPaymentSuccess(Request $request)
    {
        $invoiceId = $request->get('invoice_id');
        if (!$invoiceId) {
            return redirect()->route('user.hostings.index')->with('error', 'অবৈধ পেমেন্ট ডেটা।');
        }

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                return redirect()->route('user.hostings.index')->with('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
            }
            $status = $result['status'] ?? null;
            if (!in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                return redirect()->route('user.hostings.index')->with('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
            }

            $meta = is_string($result['metadata'] ?? null) ? json_decode($result['metadata'], true) : ($result['metadata'] ?? []);
            $hostingId = isset($meta['hosting_id']) ? (int) $meta['hosting_id'] : (int) $request->get('hosting_id');
            if (empty($meta['type']) || $meta['type'] !== 'hosting_renewal' || !$hostingId) {
                return redirect()->route('user.hostings.index')->with('error', 'অবৈধ পেমেন্ট টাইপ।');
            }

            $hosting = UserHosting::with('server')->find($hostingId);
            if (!$hosting || $hosting->user_id != ($meta['user_id'] ?? null)) {
                return redirect()->route('user.hostings.index')->with('error', 'হোস্টিং রেকর্ড মিলছে না।');
            }

            if (in_array($status, ['COMPLETED', 'SUCCESS'])) {
                $this->completeHostingRenewalOrder($hostingId, $invoiceId);
                $hosting = UserHosting::find($hostingId);
                if ($hosting) {
                    Auth::loginUsingId($hosting->user_id);
                    return redirect()->route('user.hosting.details', $hosting->id)
                        ->with('success', 'রিনিউ পেমেন্ট সফল! হোস্টিং আবার সচল হয়েছে।');
                }
                return redirect()->route('user.hostings.index')->with('success', 'রিনিউ পেমেন্ট সফল।');
            }

            Auth::loginUsingId($meta['user_id'] ?? 0);
            return redirect()->route('user.hostings.index')
                ->with('info', 'পেমেন্ট পেন্ডিং। কনফার্ম হলে হোস্টিং সচল হবে।');
        } catch (\Exception $e) {
            Log::error('Hosting renew success error', ['error' => $e->getMessage()]);
            return redirect()->route('user.hostings.index')->with('error', 'পেমেন্ট প্রসেসে সমস্যা।');
        }
    }

    public function renewPaymentCancel(Request $request)
    {
        if ($request->has('invoice_id')) {
            return $this->renewPaymentSuccess($request);
        }
        return redirect()->route('user.hostings.index')->with('error', 'পেমেন্ট বাতিল করা হয়েছে।');
    }

    /**
     * রিনিউ ওয়েবহুক – উদ্যোক্তা পে থেকে IPN
     */
    public function renewPaymentWebhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        if (!in_array($data['status'] ?? '', ['COMPLETED', 'SUCCESS'])) {
            return response()->json(['message' => 'Ignored']);
        }

        $meta = is_string($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : ($data['metadata'] ?? []);
        if (empty($meta['type']) || $meta['type'] !== 'hosting_renewal' || empty($meta['hosting_id'])) {
            return response()->json(['message' => 'Invalid metadata']);
        }

        $hosting = UserHosting::with('server')->find($meta['hosting_id']);
        if (!$hosting) {
            return response()->json(['message' => 'Hosting not found']);
        }

        try {
            $this->completeHostingRenewalOrder((int) $meta['hosting_id'], $data['invoice_id'] ?? null);
        } catch (\Exception $e) {
            Log::error('Hosting renew webhook error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success']);
    }

    /**
     * হোস্টিং রিনিউ অর্ডার পেইড মার্ক + হোস্টিং আপডেট + WHM আনসাস্পেন্ড (সাকসেস/ওয়েবহুক/অ্যাডমিন মার্ক অ্যাজ পেইড)
     */
    protected function completeHostingRenewalOrder(int $hostingId, ?string $invoiceId = null): bool
    {
        $order = HostingRenewalOrder::where('user_hosting_id', $hostingId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $hosting = UserHosting::with('server')->find($hostingId);
        if (!$hosting) {
            return false;
        }

        if ($order) {
            $order->update([
                'invoice_id' => $invoiceId ?? $order->invoice_id,
                'status'     => 'paid',
                'paid_at'    => Carbon::now(),
            ]);
        }

        DB::transaction(function () use ($hosting) {
            $data = [
                'status'        => 'Active',
                'next_due_date' => DomainHostingPricingService::renewNextDueDate($hosting),
            ];
            if (Schema::hasColumn('user_hostings', 'suspended_at')) {
                $data['suspended_at'] = null;
            }
            $hosting->update($data);
        });
        $this->unsuspendHostingAccount($hosting);
        return true;
    }

    /**
     * WHM এ অ্যাকাউন্ট আনসাস্পেন্ড করুন
     */
    protected function unsuspendHostingAccount(UserHosting $hosting)
    {
        $server = $hosting->server;
        if (!$server || !$hosting->username) {
            return;
        }
        $whm_user = trim($server->username);
        $whm_hash = str_replace(["\r", "\n", " "], '', $server->access_hash);
        $hostname = preg_replace('#^https?://|/$#', '', trim($server->hostname));
        $cp_user = trim($hosting->username);
        $api_url = "https://{$hostname}:2087/json-api/unsuspendacct?api.version=1&user={$cp_user}";
        try {
            $response = Http::withoutVerifying()->timeout(15)
                ->withHeaders(['Authorization' => "whm $whm_user:$whm_hash"])
                ->get($api_url);
            $result = $response->json();
            if (isset($result['metadata']['result']) && $result['metadata']['result'] == 0) {
                Log::warning('Hosting renew: WHM unsuspend failed', ['domain' => $hosting->domain, 'reason' => $result['metadata']['reason'] ?? 'Unknown']);
            }
        } catch (\Throwable $e) {
            Log::error('Hosting renew: WHM unsuspend exception', ['domain' => $hosting->domain, 'error' => $e->getMessage()]);
        }
    }
}