<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\License;
use App\Models\LicenseRenewalOrder;
use App\Models\DomainRenewalOrder;
use App\Models\HostingRenewalOrder;
use App\Models\ProductOrder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\UserDomain;
use App\Models\UserHosting;
use App\Services\WhmService;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'licenseRenewPaymentSuccess',
            'licenseRenewPaymentCancel',
            'licenseRenewPaymentWebhook',
            'showLicenseRenewThankYou',
            'domainRenewPaymentSuccess',
            'domainRenewPaymentCancel',
            'domainRenewPaymentWebhook',
            'showDomainRenewThankYou',
        ]);
    }

    // =========================================================
    // ১. ড্যাশবোর্ড
    // =========================================================
    public function index()
    {
        $user = Auth::user();

        \App\Services\PackageExpiryService::syncForUser($user->id);

        // কাস্টমার আইডি বের করা
        $customer = DB::table('customers')->where('phone', $user->phone)->first();
        $customerId = $customer ? $customer->id : 0;

        // ডাটা লোড
        $digitalOrderCount = ProductOrder::where('user_id', $user->id)->count();
        $data['total_orders']    = DB::table('orders')->where('customer_id', $customerId)->count() + $digitalOrderCount;
        $data['active_licenses'] = License::where('user_id', $user->id)->where('status', 1)->count();
        $data['pending_tickets'] = DB::table('tickets')->where('user_id', $user->id)->where('status', 'pending')->count();

        // সাম্প্রতিক সার্ভিস অর্ডার + লাইসেন্স রিনিউ অর্ডার একসাথে (তারিখ অনুযায়ী সাজানো)
        $serviceOrders = DB::table('orders')
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
        $licenseRenewalOrders = LicenseRenewalOrder::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
        $domainRenewalOrders = DomainRenewalOrder::with('userDomain')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
        $hostingRenewalOrders = HostingRenewalOrder::with('userHosting.plan')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
        $digitalProductOrders = ProductOrder::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $serviceItems = $serviceOrders->map(function ($o) {
            return (object)[
                'type'            => 'service',
                'order_number'    => $o->order_number,
                'created_at'      => $o->created_at,
                'payment_method'  => $o->payment_method ?? '—',
                'total_amount'    => $o->total_amount,
                'paid_amount'     => $o->paid_amount ?? 0,
                'due_amount'      => $o->due_amount ?? 0,
                'description'     => $o->description ?? '—',
                'hash_token'      => $o->hash_token ?? '',
            ];
        });
        $licenseItems = $licenseRenewalOrders->map(function ($l) {
            return (object)[
                'type'            => 'license_renewal',
                'order_number'    => 'LR-' . $l->id,
                'created_at'      => $l->created_at,
                'payment_method'  => 'CreativePay',
                'total_amount'    => $l->amount,
                'paid_amount'     => $l->status === 'paid' ? $l->amount : 0,
                'due_amount'      => $l->status === 'paid' ? 0 : $l->amount,
                'status'          => $l->status, // pending, paid, cancelled
                'description'     => 'লাইসেন্স রিনিউ',
                'hash_token'      => null,
            ];
        });
        $domainItems = $domainRenewalOrders->map(function ($d) {
            return (object)[
                'type'            => 'domain_renewal',
                'order_number'    => 'DR-' . $d->id,
                'created_at'      => $d->created_at,
                'payment_method'  => 'CreativePay',
                'total_amount'    => $d->amount,
                'paid_amount'     => $d->status === 'paid' ? $d->amount : 0,
                'due_amount'      => $d->status === 'paid' ? 0 : $d->amount,
                'status'          => $d->status,
                'description'     => 'ডোমেইন রিনিউ' . ($d->userDomain ? ' – ' . $d->userDomain->domain : ''),
                'domain_name'     => $d->userDomain ? $d->userDomain->domain : '—',
                'hash_token'      => null,
            ];
        });
        $hostingItems = $hostingRenewalOrders->map(function ($h) {
            return (object)[
                'type'            => 'hosting_renewal',
                'order_number'    => 'HR-' . $h->id,
                'created_at'      => $h->created_at,
                'payment_method'  => 'CreativePay',
                'total_amount'    => $h->amount,
                'paid_amount'     => $h->status === 'paid' ? $h->amount : 0,
                'due_amount'      => $h->status === 'paid' ? 0 : $h->amount,
                'status'          => $h->status,
                'description'     => 'হোস্টিং রিনিউ' . ($h->userHosting ? ' – ' . $h->userHosting->domain : ''),
                'hosting_domain'  => $h->userHosting ? $h->userHosting->domain : '—',
                'plan_name'       => $h->userHosting && $h->userHosting->plan ? $h->userHosting->plan->name : '—',
                'hash_token'      => null,
            ];
        });
        $digitalItems = $digitalProductOrders->map(function ($d) {
            return (object)[
                'type'            => 'digital_product',
                'order_number'    => 'DP-' . str_pad($d->id, 6, '0', STR_PAD_LEFT),
                'created_at'      => $d->created_at,
                'payment_method'  => 'CreativePay',
                'total_amount'    => $d->amount,
                'paid_amount'     => $d->status === 'paid' ? $d->amount : 0,
                'due_amount'      => $d->status === 'paid' ? 0 : $d->amount,
                'status'          => $d->status,
                'description'     => 'ডিজিটাল প্রোডাক্ট – ' . ($d->product->name ?? 'Theme'),
                'product_name'    => $d->product->name ?? '—',
                'download_token'  => $d->download_token,
                'can_download'    => $d->canDownload(),
                'downloads_left'  => $d->downloadsRemaining(),
                'download_limit'  => $d->download_limit,
                'hash_token'      => null,
            ];
        });

        $merged = $serviceItems->concat($licenseItems)->concat($domainItems)->concat($hostingItems)->concat($digitalItems)
            ->sortByDesc(fn ($i) => \Carbon\Carbon::parse($i->created_at)->timestamp)
            ->values();
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $data['recent_orders'] = new LengthAwarePaginator(
            $currentPageItems,
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('user.client.dashboard', compact('data'));
    }
	
	
	public function userCpanelLogin($id)
{
    $hosting = UserHosting::where('user_id', Auth::id())->with('server')->findOrFail($id);
    $server = $hosting->server;

    if (!$server || !$hosting->username) {
        return back()->with('error', 'সার্ভার বা হোস্টিং তথ্য পাওয়া যায়নি।');
    }

    $session = WhmService::createUserSession($server, $hosting->username, 'cpaneld');

    if ($session['success']) {
        return redirect()->away($session['url']);
    }

    return back()->with('error', 'cPanel লগইন ব্যর্থ: ' . ($session['error'] ?? 'অজানা সমস্যা'));
}
	
	
	
	// ==================== হোস্টিং পেজ (আলাদা লিস্ট) ====================
    public function hostings()
    {
        \App\Services\PackageExpiryService::syncForUser(\Illuminate\Support\Facades\Auth::id());

        // ইউজারের হোস্টিং ডাটা পেজিনেশনসহ আনা হচ্ছে
        $hostings = \App\Models\UserHosting::with('plan')
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->orderBy('id', 'desc')
                    ->paginate(5);

        return view('user.hosting.index', compact('hostings'));
    }

    // =========================================================
    // ২. লাইসেন্স পেজ (সার্চ সহ)
    // =========================================================
    public function licenses(Request $request)
    {
        $query = License::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('license_key', 'LIKE', "%{$search}%")
                  ->orWhere('domain_name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $licenses = $query->orderBy('id', 'desc')->paginate(8)->appends($request->all());

        return view('user.client.licenses', compact('licenses'));
    }

    // লাইসেন্স ডোমেইন এডিট (ইউজার শুধু নিজের লাইসেন্স এডিট করতে পারবে)
    public function licenseUpdate(Request $request, $id)
    {
        $request->validate([
            'domain_name' => 'required|string|max:255|unique:licenses,domain_name,' . $id,
        ], [
            'domain_name.unique'    => 'এই ডোমেইনের জন্য ইতিমধ্যে একটি লাইসেন্স বিদ্যমান।',
            'domain_name.required'  => 'ডোমেইন নাম আবশ্যক।',
        ]);

        $license = License::where('user_id', Auth::id())->findOrFail($id);
        $license->domain_name = $request->domain_name;
        $license->save();

        return response()->json(['status' => 'success', 'message' => 'ডোমেইন নাম আপডেট হয়েছে।']);
    }

    // লাইসেন্স স্ট্যাটাস টগল (সচল ↔ অকেজো)
    public function licenseToggleStatus($id)
    {
        $license = License::where('user_id', Auth::id())->findOrFail($id);
        $license->status = $license->status == 1 ? 0 : 1;
        $license->save();

        $msg = $license->status == 1 ? 'লাইসেন্সটি সচল করা হয়েছে।' : 'লাইসেন্সটি অকেজো করা হয়েছে।';
        return response()->json(['status' => 'success', 'message' => $msg, 'new_status' => $license->status]);
    }

    // লাইসেন্স ডিলিট (শুধু নিজের)
    public function licenseDelete($id)
    {
        $license = License::where('user_id', Auth::id())->findOrFail($id);
        $license->delete();

        return response()->json(['status' => 'success', 'message' => 'লাইসেন্সটি মুছে ফেলা হয়েছে।']);
    }

    /**
     * লাইসেন্স রিনিউ – উদ্যোক্তা পে (UddoktaPay) দিয়ে রিনিউ ফি ১০০০ টাকা
     * শুধু অকেজো (status = 0) লাইসেন্স রিনিউ করা যাবে।
     */
    public function renewLicense($id)
    {
        $license = License::where('user_id', Auth::id())->findOrFail($id);

        if ($license->status == 1) {
            return redirect()->route('user.licenses')->with('info', 'এই লাইসেন্সটি ইতিমধ্যে সচল। রিনিউ করার দরকার নেই।');
        }

        $user = Auth::user();
        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়ে সেটআপ নেই। Admin → CreativePay সেটিংস করুন।');
        }

        $amount = 1000; // লাইসেন্স রিনিউ ফি ১০০০ টাকা

        // পেন্ডিং অর্ডার তৈরি – অ্যাডমিন প্যানেল থেকে ডিউ দেখাবে, পেইড করলে লাইসেন্স রিনিউ হবে
        try {
            $order = LicenseRenewalOrder::create([
                'user_id'    => $user->id,
                'license_id' => $license->id,
                'amount'     => $amount,
                'status'     => 'pending',
            ]);
            Log::info('License renewal order created', ['order_id' => $order->id, 'license_id' => $license->id]);
        } catch (\Throwable $e) {
            Log::error('License renewal order create failed', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user_id' => $user->id,
                'license_id' => $license->id,
            ]);
            return redirect()->back()->with('error', 'অর্ডার সেভ করতে সমস্যা: ' . $e->getMessage());
        }

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email ?? $user->username . '@user.local',
            'amount'       => (float) $amount,
            'metadata'     => [
                'user_id'    => $user->id,
                'license_id' => (int) $license->id,
                'type'       => 'license_renewal',
            ],
            'redirect_url' => route('user.license.renew.success') . '?license_id=' . (int) $license->id,
            'cancel_url'   => route('user.license.renew.cancel'),
            'webhook_url'  => route('user.license.renew.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            Log::error('License renew CreativePay no payment_url', ['response' => $result]);
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা। আবার চেষ্টা করুন।');
        } catch (\Exception $e) {
            Log::error('License renew CreativePay exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    /**
     * লাইসেন্স রিনিউ পেমেন্ট সাকসেস – ভেরিফাই করে লাইসেন্স সচল + মেয়াদ ১ বছর বাড়ান
     * কলব্যাক auth ছাড়া চলে – তাই রিডাইরেক্টে user.licenses গেলে লগইন চাইলে intended সেট থাকবে।
     */
    public function licenseRenewPaymentSuccess(Request $request)
    {
        $invoiceId = $request->get('invoice_id') ?? $request->input('invoice_id');
        $licensesUrl = route('user.licenses');

        // সবসময় পাবলিক থ্যাঙ্ক ইউ পেজে রিডাইরেক্ট – প্রটেক্টেড রাউটে কখনো নই, তাই Unauthenticated হবে না
        $toLicenses = function ($key, $message) use ($licensesUrl) {
            session()->put('url.intended', $licensesUrl);
            return redirect()->route('user.license.renew.thankyou')->with($key, $message);
        };

        Log::info('License renew success callback', ['invoice_id' => $invoiceId, 'has_invoice' => !empty($invoiceId)]);

        if (!$invoiceId) {
            return $toLicenses('error', 'অবৈধ পেমেন্ট ডেটা।');
        }

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                Log::warning('License renew verify failed or invalid json');
                return $toLicenses('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
            }
            Log::info('License renew verify response', [
                'invoice_id' => $invoiceId,
                'status'     => $result['status'] ?? null,
                'has_metadata' => isset($result['metadata']),
            ]);

            $statusRaw = $result['status'] ?? '';
            $status = is_string($statusRaw) ? strtoupper(trim($statusRaw)) : $statusRaw;
            if (!in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                return $toLicenses('error', 'পেমেন্ট স্ট্যাটাস অগ্রহণযোগ্য।');
            }

            $metaRaw = $result['metadata'] ?? null;
            if (is_string($metaRaw)) {
                $meta = json_decode($metaRaw, true) ?: [];
            } elseif (is_array($metaRaw)) {
                $meta = $metaRaw;
            } else {
                $meta = [];
            }

            $licenseId = isset($meta['license_id']) ? (int) $meta['license_id'] : null;
            $userId = isset($meta['user_id']) ? (int) $meta['user_id'] : null;
            $typeOk = !empty($meta['type']) && $meta['type'] === 'license_renewal';

            if (!$licenseId || !$typeOk) {
                $licenseId = (int) $request->get('license_id');
                $amount = isset($result['amount']) ? (float) $result['amount'] : 0;
                if ($licenseId && $amount >= 999 && $amount <= 1001) {
                    $license = License::find($licenseId);
                    if ($license) {
                        Log::info('License renew using URL fallback (metadata missing)', ['license_id' => $licenseId]);
                    } else {
                        $license = null;
                    }
                } else {
                    $license = null;
                }
                if (!$license) {
                    Log::warning('License renew invalid metadata', ['meta' => $meta, 'license_id' => $licenseId]);
                    return $toLicenses('error', 'অবৈধ পেমেন্ট টাইপ।');
                }
            } else {
                $license = License::find($licenseId);
                if (!$license) {
                    Log::warning('License renew license not found', ['license_id' => $licenseId]);
                    return $toLicenses('error', 'লাইসেন্স রেকর্ড মিলছে না।');
                }
                if ($userId !== null && (int) $license->user_id !== $userId) {
                    Log::warning('License renew user mismatch', ['license_user' => $license->user_id, 'meta_user' => $userId]);
                    return $toLicenses('error', 'লাইসেন্স রেকর্ড মিলছে না।');
                }
            }

            if (in_array($status, ['COMPLETED', 'SUCCESS'])) {
                $this->completeLicenseRenewalOrder($license->id, $invoiceId);
                Log::info('License renewed successfully', ['license_id' => $license->id]);
                return $toLicenses('success', 'লাইসেন্স রিনিউ সফল! মেয়াদ ১ বছর বাড়ানো হয়েছে।');
            }

            return $toLicenses('info', 'পেমেন্ট পেন্ডিং। কনফার্ম হলে লাইসেন্স সচল হবে।');
        } catch (\Exception $e) {
            Log::error('License renew success error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $toLicenses('error', 'পেমেন্ট প্রসেসে সমস্যা।');
        }
    }

    public function licenseRenewPaymentCancel(Request $request)
    {
        if ($request->has('invoice_id')) {
            return $this->licenseRenewPaymentSuccess($request);
        }
        session()->put('url.intended', route('user.licenses'));
        return redirect()->route('user.license.renew.thankyou')->with('error', 'পেমেন্ট বাতিল করা হয়েছে।');
    }

    /**
     * পাবলিক পেজ – লাইসেন্স রিনিউ সফল/বাতিলের পর (অথেন্টিকেশন লাগে না, তাই Unauthenticated হবে না)
     */
    public function showLicenseRenewThankYou()
    {
        return view('user.client.license-renew-thankyou');
    }

    /**
     * লাইসেন্স রিনিউ অর্ডার পেইড মার্ক + লাইসেন্স আপডেট (সাকসেস কলব্যাক, ওয়েবহুক ও অ্যাডমিন মার্ক অ্যাজ পেইড)
     */
    protected function completeLicenseRenewalOrder(int $licenseId, ?string $invoiceId = null): bool
    {
        $order = LicenseRenewalOrder::where('license_id', $licenseId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$order) {
            $license = License::find($licenseId);
            if (!$license) {
                return false;
            }
            DB::transaction(function () use ($license) {
                $license->update([
                    'status'      => 1,
                    'expiry_date' => Carbon::now()->addYear(),
                ]);
            });
            return true;
        }

        $newExpiry = Carbon::now()->addYear();
        DB::transaction(function () use ($order, $invoiceId, $newExpiry) {
            $order->update([
                'invoice_id' => $invoiceId ?? $order->invoice_id,
                'status'     => 'paid',
                'paid_at'    => Carbon::now(),
            ]);
            $order->license->update([
                'status'      => 1,
                'expiry_date' => $newExpiry,
            ]);
        });
        return true;
    }

    /**
     * লাইসেন্স রিনিউ ওয়েবহুক – উদ্যোক্তা পে থেকে IPN
     */
    public function licenseRenewPaymentWebhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        if (!in_array($data['status'] ?? '', ['COMPLETED', 'SUCCESS'])) {
            return response()->json(['message' => 'Ignored']);
        }

        $meta = is_string($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : ($data['metadata'] ?? []);
        if (empty($meta['type']) || $meta['type'] !== 'license_renewal' || empty($meta['license_id'])) {
            return response()->json(['message' => 'Invalid metadata']);
        }

        $licenseId = (int) $meta['license_id'];
        $license = License::find($licenseId);
        if (!$license) {
            return response()->json(['message' => 'License not found']);
        }

        try {
            $this->completeLicenseRenewalOrder($licenseId, $data['invoice_id'] ?? null);
        } catch (\Exception $e) {
            Log::error('License renew webhook error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success']);
    }

    // =========================================================
    // ডোমেইন রিনিউ – এক্সপায়ারড ডোমেইন UddoktaPay দিয়ে রিনিউ
    // =========================================================

    /**
     * ডোমেইন রিনিউ – শুধু Expired বা মেয়াদ উত্তীর্ণ ডোমেইন রিনিউ করা যাবে।
     */
    public function renewDomain($id)
    {
        $domain = UserDomain::where('user_id', Auth::id())->findOrFail($id);

        if ($domain->status !== 'Expired' && Carbon::parse($domain->next_due_date)->isFuture()) {
            return redirect()->route('user.domains.index')->with('info', 'এই ডোমেইনের মেয়াদ এখনো আছে। রিনিউ করার দরকার নেই।');
        }

        $user = Auth::user();
        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়ে সেটআপ নেই। Admin → CreativePay সেটিংস করুন।');
        }

        $amount = (float) ($domain->recurring_amount ?? 1000);
        if ($amount < 1) {
            $amount = 1000;
        }

        try {
            $order = DomainRenewalOrder::create([
                'user_id'        => $user->id,
                'user_domain_id' => $domain->id,
                'amount'         => $amount,
                'status'        => 'pending',
            ]);
            Log::info('Domain renewal order created', ['order_id' => $order->id, 'user_domain_id' => $domain->id]);
        } catch (\Throwable $e) {
            Log::error('Domain renewal order create failed', [
                'error' => $e->getMessage(),
                'user_domain_id' => $domain->id,
            ]);
            return redirect()->back()->with('error', 'অর্ডার সেভ করতে সমস্যা: ' . $e->getMessage());
        }

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email ?? $user->username . '@user.local',
            'amount'       => $amount,
            'metadata'     => [
                'user_id'        => $user->id,
                'user_domain_id' => (int) $domain->id,
                'type'           => 'domain_renewal',
            ],
            'redirect_url' => route('user.domain.renew.success') . '?domain_id=' . (int) $domain->id,
            'cancel_url'   => route('user.domain.renew.cancel'),
            'webhook_url'  => route('user.domain.renew.webhook'),
        ];

        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            Log::error('Domain renew CreativePay no payment_url', ['response' => $result]);
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা। আবার চেষ্টা করুন।');
        } catch (\Exception $e) {
            Log::error('Domain renew CreativePay exception', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'সার্ভার কানেকশন এরর।');
        }
    }

    /**
     * ডোমেইন রিনিউ পেমেন্ট সাকসেস – ভেরিফাই করে ডোমেইন একটিভ + next_due_date ১ বছর বাড়ান
     */
    public function domainRenewPaymentSuccess(Request $request)
    {
        $invoiceId = $request->get('invoice_id') ?? $request->input('invoice_id');
        $domainsUrl = route('user.domains.index');

        $toDomains = function ($key, $message) use ($domainsUrl) {
            session()->put('url.intended', $domainsUrl);
            return redirect()->route('user.domain.renew.thankyou')->with($key, $message);
        };

        Log::info('Domain renew success callback', ['invoice_id' => $invoiceId]);

        if (!$invoiceId) {
            return $toDomains('error', 'অবৈধ পেমেন্ট ডেটা।');
        }

        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                return $toDomains('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
            }

            $statusRaw = $result['status'] ?? '';
            $status = is_string($statusRaw) ? strtoupper(trim($statusRaw)) : $statusRaw;
            if (!in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                return $toDomains('error', 'পেমেন্ট স্ট্যাটাস অগ্রহণযোগ্য।');
            }

            $metaRaw = $result['metadata'] ?? null;
            if (is_string($metaRaw)) {
                $meta = json_decode($metaRaw, true) ?: [];
            } elseif (is_array($metaRaw)) {
                $meta = $metaRaw;
            } else {
                $meta = [];
            }

            $domainId = isset($meta['user_domain_id']) ? (int) $meta['user_domain_id'] : null;
            $typeOk = !empty($meta['type']) && $meta['type'] === 'domain_renewal';

            if (!$domainId || !$typeOk) {
                $domainId = (int) $request->get('domain_id');
                if ($domainId) {
                    $domain = UserDomain::find($domainId);
                    if (!$domain) {
                        $domainId = null;
                    }
                } else {
                    $domainId = null;
                }
                if (!$domainId) {
                    return $toDomains('error', 'অবৈধ পেমেন্ট টাইপ।');
                }
            } else {
                $domain = UserDomain::find($domainId);
                if (!$domain) {
                    return $toDomains('error', 'ডোমেইন রেকর্ড মিলছে না।');
                }
            }

            if (in_array($status, ['COMPLETED', 'SUCCESS'])) {
                $this->completeDomainRenewalOrder($domainId, $invoiceId);
                return $toDomains('success', 'ডোমেইন রিনিউ সফল! মেয়াদ ১ বছর বাড়ানো হয়েছে।');
            }

            return $toDomains('info', 'পেমেন্ট পেন্ডিং। কনফার্ম হলে ডোমেইন সচল হবে।');
        } catch (\Exception $e) {
            Log::error('Domain renew success error', ['error' => $e->getMessage()]);
            return $toDomains('error', 'পেমেন্ট প্রসেসে সমস্যা।');
        }
    }

    public function domainRenewPaymentCancel(Request $request)
    {
        if ($request->has('invoice_id')) {
            return $this->domainRenewPaymentSuccess($request);
        }
        session()->put('url.intended', route('user.domains.index'));
        return redirect()->route('user.domain.renew.thankyou')->with('error', 'পেমেন্ট বাতিল করা হয়েছে।');
    }

    public function showDomainRenewThankYou()
    {
        return view('user.client.domain-renew-thankyou');
    }

    protected function completeDomainRenewalOrder(int $userDomainId, ?string $invoiceId = null): bool
    {
        $order = DomainRenewalOrder::where('user_domain_id', $userDomainId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$order) {
            $domain = UserDomain::find($userDomainId);
            if (!$domain) {
                return false;
            }
            DB::transaction(function () use ($domain) {
                $domain->update([
                    'status'        => 'Active',
                    'next_due_date' => Carbon::now()->addYear(),
                ]);
            });
            return true;
        }

        $newDue = Carbon::now()->addYear();
        DB::transaction(function () use ($order, $invoiceId, $newDue) {
            $order->update([
                'invoice_id' => $invoiceId ?? $order->invoice_id,
                'status'     => 'paid',
                'paid_at'    => Carbon::now(),
            ]);
            $order->userDomain->update([
                'status'        => 'Active',
                'next_due_date' => $newDue,
            ]);
        });
        return true;
    }

    public function domainRenewPaymentWebhook(Request $request)
    {
        if (!app(\App\Services\CreativePayService::class)->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        if (!in_array($data['status'] ?? '', ['COMPLETED', 'SUCCESS'])) {
            return response()->json(['message' => 'Ignored']);
        }

        $meta = is_string($data['metadata'] ?? null) ? json_decode($data['metadata'], true) : ($data['metadata'] ?? []);
        if (empty($meta['type']) || $meta['type'] !== 'domain_renewal' || empty($meta['user_domain_id'])) {
            return response()->json(['message' => 'Invalid metadata']);
        }

        $domainId = (int) $meta['user_domain_id'];
        $domain = UserDomain::find($domainId);
        if (!$domain) {
            return response()->json(['message' => 'Domain not found']);
        }

        try {
            $this->completeDomainRenewalOrder($domainId, $data['invoice_id'] ?? null);
        } catch (\Exception $e) {
            Log::error('Domain renew webhook error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success']);
    }

public function hostingDetails($id)
    {
        // ১. হোস্টিং ডাটা এবং সাথে রিলেশনগুলো নিয়ে আসা হলো
        $hosting = \App\Models\UserHosting::with(['plan', 'server'])
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->findOrFail($id);

        // ২. ডিফল্ট স্ট্যাটাস (যাতে ভিউ ফাইলে এরর না আসে)
        $stats = [
            'disk_used' => 0,
            'disk_limit' => 'Unknown',
            'disk_percent' => 0,
            'bw_used' => 0,
            'bw_limit' => 'Unknown',
            'bw_percent' => 0
        ];

        // ৩. সার্ভার থেকে লাইভ ডাটা আনার চেষ্টা
        if ($hosting->server && $hosting->username) {
            $liveData = WhmService::getAccountStats($hosting->server, $hosting->username);

            if ($liveData) {
                // ডিস্ক ডাটা (MB তে)
                $diskUsed = (float) preg_replace('/[^0-9.]/', '', $liveData['diskused'] ?? 0);
                $diskLimit = $liveData['disklimit'] ?? '0';

                $stats['disk_used'] = $diskUsed;
                $stats['disk_limit'] = $diskLimit;

                // ডিস্ক পার্সেন্টেজ হিসাব
                if ($diskLimit === 'unlimited') {
                    $stats['disk_percent'] = ($diskUsed > 1024) ? 10 : 5; 
                } else {
                    $limitNum = (float) preg_replace('/[^0-9.]/', '', $diskLimit);
                    if ($limitNum > 0) {
                        $stats['disk_percent'] = round(($diskUsed / $limitNum) * 100);
                    }
                }

                // ব্যান্ডউইথ ডাটা
                $stats['bw_limit'] = $liveData['plan'] ?? $hosting->plan->name;
            }
        }

        // ৪. ভিউতে ডাটা পাঠানো
        return view('user.hosting.details', compact('hosting', 'stats'));
    }

    public function updateHostingPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:10|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        ], [
            'new_password.regex' => 'পাসওয়ার্ডে বড় হাতের অক্ষর, ছোট হাতের অক্ষর, সংখ্যা এবং স্পেশাল ক্যারেক্টার থাকতে হবে।'
        ]);

        $hosting = \App\Models\UserHosting::with('server')->where('user_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        $server = $hosting->server;

        if (!$server || !$hosting->username) {
            return back()->with('error', 'সার্ভার বা ইউজার তথ্য পাওয়া যায়নি।');
        }

        $whm_user = trim($server->username);
        $whm_hash = str_replace(["\r", "\n", " "], '', $server->access_hash);
        $hostname = preg_replace('#^https?://|/$#', '', trim($server->hostname));
        
        $api_url = "https://{$hostname}:2087/json-api/passwd?api.version=1";

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(20)
                ->withHeaders(['Authorization' => "whm $whm_user:$whm_hash"])
                ->asForm() 
                ->post($api_url, [
                    'user'     => $hosting->username,
                    'pass'     => $request->new_password, 
                    'password' => $request->new_password  
                ]);

            $result = $response->json();

            if (isset($result['metadata']['result']) && $result['metadata']['result'] == 1) {
                $hosting->update(['password' => $request->new_password]); 
                return back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।');
            } 
            
            $reason = $result['metadata']['reason'] ?? 'সার্ভার রিকোয়েস্ট রিজেক্ট করেছে।';
            return back()->with('error', 'ব্যর্থ হয়েছে: ' . $reason);

        } catch (\Exception $e) {
            return back()->with('error', 'সার্ভারের সাথে যোগাযোগ করতে সমস্যা হচ্ছে।');
        }
    }

public function domains(Request $request)
{
    $user = Auth::user();

    \App\Services\PackageExpiryService::syncForUser($user->id);
    
    // ডোমেইন কুয়েরি শুরু
    $domains = \App\Models\UserDomain::where('user_id', $user->id);

    // [NEW] যদি সার্চ ইনপুট থাকে, তবে ফিল্টার করবে
    if ($request->has('search') && $request->search != null) {
        $search = $request->search;
        $domains->where('domain', 'LIKE', "%{$search}%");
    }

    // রেজাল্ট পেজিনেশন সহ আনা
    $domains = $domains->orderBy('id', 'desc')->paginate(5);
    
    return view('user.domain.index', compact('domains'));
}

public function updateNameservers(Request $request, $id)
    {
        $request->validate([
            'ns1' => 'required|string',
            'ns2' => 'required|string',
        ]);

        // ১. ডোমেইন ভেরিফিকেশন
        $domain = \App\Models\UserDomain::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // ২. ডাটাবেস আপডেট
        $domain->update([
            'ns1' => $request->ns1,
            'ns2' => $request->ns2,
            'ns3' => $request->ns3,
            'ns4' => $request->ns4,
        ]);

        // ৩. এডমিনের কাছে নির্দিষ্ট নাম্বারে এসএমএস পাঠানো
        try {
            $user = Auth::user();
            
            // মেসেজ বডি
            $msg = "NS Update!\nUser: {$user->name}\nDomain: {$domain->domain}\nNS1: {$request->ns1}\nNS2: {$request->ns2}";
            
            // [ফিক্সড নাম্বার]
            $adminPhone = '01849832178'; 

            $this->sendAdminSms($adminPhone, $msg);

        } catch (\Exception $e) {
            Log::error("NS Update SMS Failed: " . $e->getMessage());
        }

        return back()->with('success', 'নেমসার্ভার সফলভাবে আপডেট করা হয়েছে!');
    }
	
	// প্রাইভেট হেল্পার: এডমিনকে এসএমএস পাঠানো
    private function sendAdminSms($number, $message)
    {
        try {
            $gs = DB::table('generalsettings')->first();
            
            if (!$gs || !$gs->sms_api_key || !$gs->sms_sender_id) return;

            // ৩ সেকেন্ডের মধ্যে এসএমএস পাঠানোর চেষ্টা করবে
            Http::timeout(3)->withoutVerifying()->get("http://bulksmsbd.net/api/smsapi", [
                'api_key'  => $gs->sms_api_key,
                'type'     => 'text',
                'number'   => $number,
                'senderid' => $gs->sms_sender_id,
                'message'  => $message
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin SMS Error: " . $e->getMessage());
        }
    }
	

    // =========================================================
    // ৩. সাপোর্ট টিকেট
    // =========================================================
    public function support()
    {
        $tickets = DB::table('tickets')
                        ->where('user_id', Auth::id())
                        ->orderBy('id', 'desc')
                        ->paginate(5);

        return view('user.client.support', compact('tickets'));
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required',
            'priority' => 'required'
        ]);

        DB::table('tickets')->insert([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'টিকেট সফলভাবে তৈরি হয়েছে।');
    }
    
    public function viewTicket($id)
    {
        $ticket = DB::table('tickets')->where('id', $id)->where('user_id', Auth::id())->first();
        if (!$ticket) return abort(404);

        $replies = DB::table('ticket_replies')->where('ticket_id', $id)->get();

        return view('user.client.view', compact('ticket', 'replies'));
    }

 public function replyTicket(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $ticket = DB::table('tickets')->where('id', $id)->where('user_id', Auth::id())->first();

        if (!$ticket) {
            return response()->json(['error' => 'টিকেট পাওয়া যায়নি।'], 404);
        }

        // ডুপ্লিকেট চেক (Cache Lock)
        $lockKey = 'reply_lock_' . Auth::id() . '_' . $id;
        if (\Illuminate\Support\Facades\Cache::has($lockKey)) {
            return response()->json(['error' => 'মেসেজ পাঠানো হচ্ছে...'], 429);
        }
        \Illuminate\Support\Facades\Cache::put($lockKey, true, 5);

        try {
            // [পরিবর্তন-১] insertGetId ব্যবহার করে আইডি নেওয়া
            $newId = DB::table('ticket_replies')->insertGetId([
                'ticket_id' => $id,
                'user_id'   => Auth::id(),
                'message'   => $request->message,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('tickets')->where('id', $id)->update([
                'updated_at' => now(),
                'status' => 'open'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'মেসেজ পাঠানো হয়েছে।',
                'data' => [
                    'id' => $newId, // [পরিবর্তন-২] নতুন আইডি পাঠানো হচ্ছে
                    'user_id' => Auth::id(),
                    'message' => $request->message,
                    'created_at' => now()->diffForHumans(),
                    'sender' => 'আপনি'
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget($lockKey);
            return response()->json(['error' => 'Error'], 500);
        }
    }

    public function checkNewMessages(Request $request, $id)
    {
        $last_id = (int) $request->last_id;

        $new_replies = DB::table('ticket_replies')
                        ->where('ticket_id', $id)
                        ->where('id', '>', $last_id)
                        ->orderBy('id', 'asc')
                        ->get();

        if ($new_replies->count() > 0) {
            $html = '';
            foreach ($new_replies as $reply) {
                $is_user = ($reply->user_id == Auth::id());
                $class = $is_user ? 'bubble-user' : 'bubble-admin';
                $sender = $is_user ? 'আপনি' : 'অ্যাডমিন সাপোর্ট';
                $time = \Carbon\Carbon::parse($reply->created_at)->diffForHumans();

                $html .= '<div class="bubble '.$class.'">
                            <strong>'.$sender.':</strong><br>
                            '.$reply->message.'
                            <span class="msg-meta">'.$time.'</span>
                          </div>';
            }

            return response()->json([
                'has_new' => true,
                'html' => $html,
                'last_id' => $new_replies->last()->id
            ]);
        }

        return response()->json(['has_new' => false]);
    }

/**
 * এপিআই ডকুমেন্টেশন পেজ ভিউ
 */
public function apiDocumentation()
{
    return redirect()->route('user.sms.api_docs');
}

/**
 * ইউজারের জন্য নতুন API Key জেনারেট বা রিসেট করা
 */
public function generateKey()
{
    $user = Auth::user();
    
    // ৬০ ক্যারেক্টারের একটি ইউনিক র‍্যান্ডম স্ট্রিং তৈরি করে সেভ করা
    $user->api_key = Str::random(60);
    $user->save();

    return back()->with('success', 'নতুন API Key সফলভাবে তৈরি হয়েছে: ' . $user->api_key);
}



/**
 * SMS API ডকুমেন্টেশন পেজ ভিউ
 */
public function smsApiDocumentation()
{
    $user = Auth::user();
    // ফোল্ডার পাথ অনুযায়ী ভিউ রিটার্ন করা হলো
    return view('user.sms.api_docs', compact('user'));
}
    // =========================================================
    // ৫. প্রোফাইল ও লগআউট
    // =========================================================
    public function profile()
    {
        $user = Auth::user();
        return view('user.client.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'  => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($file = $request->file('photo')) {
            admin_delete_image('assets/images/users', $user->photo ?? null);
            $input['photo'] = admin_upload_image($file, 'assets/images/users');
        }

        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        } else {
            unset($input['password']);
        }

        $user->update($input);

        return back()->with('success', 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}