<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Services\CreativePayService;
use Illuminate\Http\Request;

class CreativePayAppController extends Controller
{
    public function __construct(private CreativePayService $pay) {}

    public function summary(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'ok'      => true,
            'paid'    => PaymentTransaction::where('status', 'completed')->count(),
            'unpaid'  => PaymentTransaction::where('status', 'pending')->count(),
            'failed'  => PaymentTransaction::where('status', 'failed')->count(),
            'total'   => PaymentTransaction::count(),
        ]);
    }

    public function invoices(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $status = $request->get('status', 'pending');
        $query  = PaymentTransaction::query()->orderByDesc('id');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query->limit(100)->get()->map(fn (PaymentTransaction $t) => $this->formatTxn($t));

        return response()->json([
            'ok'    => true,
            'items' => $items,
        ]);
    }

    public function approve(Request $request, $id)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $txn = PaymentTransaction::find($id);
        if (!$txn) {
            return response()->json(['ok' => false, 'message' => 'Invoice not found'], 404);
        }

        if ($txn->status !== 'pending') {
            return response()->json([
                'ok'      => false,
                'message' => 'Invoice already processed',
                'item'    => $this->formatTxn($txn->fresh()),
            ], 422);
        }

        $activated = $this->pay->approveAndActivate($txn, 'CreativePay SMS App — marked paid');

        return response()->json([
            'ok'        => true,
            'message'   => $activated
                ? 'Invoice paid and service activated'
                : 'Invoice paid',
            'item'      => $this->formatTxn($txn->fresh()),
            'activated' => $activated,
        ]);
    }

    protected function formatTxn(PaymentTransaction $t): array
    {
        $meta = is_array($t->metadata) ? $t->metadata : [];
        $type = $meta['type'] ?? null;

        return [
            'id'              => $t->id,
            'invoice_id'      => $t->invoice_id,
            'customer'        => $t->full_name,
            'amount'          => (float) $t->amount,
            'currency'        => $t->currency ?? 'BDT',
            'payment_method'  => $t->payment_method ? ucfirst($t->payment_method) : null,
            'transaction_id'  => $t->transaction_id,
            'sender_number'   => $t->sender_number,
            'status'          => $t->status,
            'status_label'    => $t->verifyStatus(),
            'is_paid'         => $t->isPaid(),
            'admin_note'      => $t->admin_note,
            'service_type'    => $type,
            'created_at'      => $t->created_at?->format('Y-m-d H:i:s'),
            'paid_at'         => $t->paid_at?->format('Y-m-d H:i:s'),
        ];
    }
}
