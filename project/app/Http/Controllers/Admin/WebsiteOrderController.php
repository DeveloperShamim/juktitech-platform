<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class WebsiteOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display all website theme orders with filter & search capabilities.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'product'])
            ->where(function ($q) {
                $q->whereNotNull('product_id')
                  ->orWhere('order_number', 'like', 'ORD-%')
                  ->orWhere('description', 'like', '%ওয়েবসাইট%')
                  ->orWhere('description', 'like', '%থিম%');
            })
            ->latest();

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Filter (Order Number, Customer Name, Phone, Domain)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('domain_name', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->appends($request->all());

        // Quick Stats
        $baseQuery = Order::where(function ($q) {
            $q->whereNotNull('product_id')
              ->orWhere('order_number', 'like', 'ORD-%')
              ->orWhere('description', 'like', '%ওয়েবসাইট%')
              ->orWhere('description', 'like', '%থিম%');
        });

        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'pending'    => (clone $baseQuery)->where('status', 'Pending')->count(),
            'processing' => (clone $baseQuery)->where('status', 'Processing')->count(),
            'completed'  => (clone $baseQuery)->where('status', 'Completed')->count(),
            'cancelled'  => (clone $baseQuery)->where('status', 'Cancelled')->count(),
        ];

        return view('admin.website_orders.index', compact('orders', 'stats'));
    }

    /**
     * Update order status (Pending, Processing, Completed, Cancelled).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        // If completed and was unpaid, mark as paid
        if ($request->status === 'Completed' && $order->paid_amount == 0) {
            $order->paid_amount = $order->total_amount;
            $order->due_amount = 0;
        }

        $order->save();

        return redirect()->back()->with('success', "অর্ডার #{$order->order_number} এর স্ট্যাটাস '{$order->status}' এ পরিবর্তন হয়েছে।");
    }

    /**
     * Delete an order.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->back()->with('success', "অর্ডার #{$orderNumber} সফলভাবে ডিলিট করা হয়েছে।");
    }
}
