<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDigitalDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // 1. Sync orders from orders table to product_orders for this user
        if ($user) {
            $customerIds = Customer::where('user_id', $user->id)
                ->when($user->phone, fn($q) => $q->orWhere('phone', $user->phone))
                ->pluck('id');

            if ($customerIds->isNotEmpty()) {
                $existingOrders = Order::whereIn('customer_id', $customerIds)
                    ->whereNotNull('product_id')
                    ->get();

                foreach ($existingOrders as $ord) {
                    $isPaid = in_array(strtolower($ord->status), ['completed', 'active', 'paid'], true)
                        || ($ord->paid_amount > 0 && $ord->due_amount <= 0);

                    $po = ProductOrder::where('user_id', $user->id)
                        ->where('product_id', $ord->product_id)
                        ->first();

                    if (!$po) {
                        ProductOrder::create([
                            'product_id'     => $ord->product_id,
                            'user_id'        => $user->id,
                            'customer_name'  => $user->name,
                            'customer_email' => $user->email,
                            'customer_phone' => $user->phone,
                            'install_domain' => $ord->domain_name,
                            'invoice_id'     => $ord->order_number,
                            'amount'         => $ord->total_amount,
                            'status'         => $isPaid ? 'paid' : 'pending',
                            'download_token' => Str::random(48),
                            'download_count' => 0,
                            'download_limit' => 10,
                            'paid_at'        => $isPaid ? ($ord->updated_at ?? now()) : null,
                        ]);
                    } elseif ($isPaid && $po->status !== 'paid') {
                        $po->update([
                            'status'  => 'paid',
                            'paid_at' => $po->paid_at ?: now(),
                        ]);
                    }
                }
            }
        }

        $orders = ProductOrder::with('product')
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->paginate(12);

        $paidCount = ProductOrder::where('user_id', $user->id)->where('status', 'paid')->count();
        $pendingCount = ProductOrder::where('user_id', $user->id)->where('status', 'pending')->count();
        $availableDownloads = ProductOrder::where('user_id', $user->id)
            ->where('status', 'paid')
            ->get()
            ->sum(fn (ProductOrder $o) => $o->downloadsRemaining());

        return view('user.client.digital-downloads', compact('orders', 'paidCount', 'pendingCount', 'availableDownloads'));
    }
}
