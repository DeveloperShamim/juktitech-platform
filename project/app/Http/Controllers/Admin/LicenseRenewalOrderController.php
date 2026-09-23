<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LicenseRenewalOrder;
use Illuminate\Http\Request;

class LicenseRenewalOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * লাইসেন্স রিনিউ অর্ডার লিস্ট – পেন্ডিং/পেইড সব
     */
    public function index(Request $request)
    {
        $query = LicenseRenewalOrder::with(['user', 'license'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        $pending_count = LicenseRenewalOrder::where('status', 'pending')->count();
        return view('admin.license-renewal-orders.index', compact('orders', 'pending_count'));
    }

    /**
     * পেন্ডিং অর্ডার পেইড মার্ক করে লাইসেন্স রিনিউ
     */
    public function markAsPaid($id)
    {
        $order = LicenseRenewalOrder::with('license')->findOrFail($id);
        if (!$order->isPending()) {
            return redirect()->route('admin.license-renewal-orders.index')
                ->with('error', 'এই অর্ডারটি ইতিমধ্যে পেইড বা ক্যানসেল।');
        }
        if ($order->markAsPaidAndRenewLicense()) {
            return redirect()->route('admin.license-renewal-orders.index')
                ->with('success', 'অর্ডার পেইড মার্ক করা হয়েছে এবং লাইসেন্স রিনিউ হয়েছে।');
        }
        return redirect()->back()->with('error', 'কিছু সমস্যা হয়েছে।');
    }

    /**
     * পেইড অর্ডার আবার আনপেইড (পেন্ডিং) মার্ক করুন
     */
    public function markAsUnpaid($id)
    {
        $order = LicenseRenewalOrder::findOrFail($id);
        if (!$order->isPaid()) {
            return redirect()->route('admin.license-renewal-orders.index')
                ->with('error', 'এই অর্ডারটি ইতিমধ্যে আনপেইড।');
        }
        $order->update([
            'status'  => 'pending',
            'paid_at' => null,
        ]);
        return redirect()->route('admin.license-renewal-orders.index')
            ->with('success', 'অর্ডার আনপেইড (পেন্ডিং) মার্ক করা হয়েছে।');
    }
}
