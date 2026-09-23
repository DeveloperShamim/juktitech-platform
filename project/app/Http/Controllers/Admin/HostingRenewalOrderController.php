<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostingRenewalOrder;
use App\Services\WhmService;
use Illuminate\Http\Request;

class HostingRenewalOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * হোস্টিং রিনিউ অর্ডার লিস্ট – পেন্ডিং/পেইড সব
     */
    public function index(Request $request)
    {
        $query = HostingRenewalOrder::with(['user', 'userHosting.plan'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        $pending_count = HostingRenewalOrder::where('status', 'pending')->count();
        return view('admin.hosting-renewal-orders.index', compact('orders', 'pending_count'));
    }

    /**
     * পেন্ডিং অর্ডার পেইড মার্ক করে হোস্টিং রিনিউ + WHM আনসাস্পেন্ড
     */
    public function markAsPaid($id)
    {
        $order = HostingRenewalOrder::with('userHosting.server')->findOrFail($id);
        if (!$order->isPending()) {
            return redirect()->route('admin.hosting-renewal-orders.index')
                ->with('error', 'এই অর্ডারটি ইতিমধ্যে পেইড বা ক্যানসেল।');
        }
        if ($order->markAsPaidAndRenewHosting()) {
            $hosting = $order->userHosting;
            if ($hosting && $hosting->server && $hosting->username) {
                WhmService::unsuspendAccount($hosting->server, $hosting->username);
            }
            return redirect()->route('admin.hosting-renewal-orders.index')
                ->with('success', 'অর্ডার পেইড মার্ক করা হয়েছে এবং হোস্টিং রিনিউ হয়েছে।');
        }
        return redirect()->back()->with('error', 'কিছু সমস্যা হয়েছে।');
    }

    /**
     * পেইড অর্ডার আবার আনপেইড (পেন্ডিং) মার্ক করুন
     */
    public function markAsUnpaid($id)
    {
        $order = HostingRenewalOrder::findOrFail($id);
        if (!$order->isPaid()) {
            return redirect()->route('admin.hosting-renewal-orders.index')
                ->with('error', 'এই অর্ডারটি ইতিমধ্যে আনপেইড।');
        }
        $order->update([
            'status'  => 'pending',
            'paid_at' => null,
        ]);
        return redirect()->route('admin.hosting-renewal-orders.index')
            ->with('success', 'অর্ডার আনপেইড (পেন্ডিং) মার্ক করা হয়েছে।');
    }
}
