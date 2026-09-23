<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Services\CreativePayService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private CreativePayService $pay) {}

    public function show(string $invoice)
    {
        $txn = $this->pay->findByInvoice($invoice);
        if (!$txn) {
            abort(404, 'পেমেন্ট পাওয়া যায়নি।');
        }

        if ($txn->isPaid()) {
            return redirect($this->successUrl($txn));
        }

        if ($txn->status === 'cancelled') {
            return redirect($txn->cancel_url ?: url('/'));
        }

        $methods = $this->pay->paymentMethods();
        if (empty($methods)) {
            return view('payment.not_configured');
        }

        $gs = GeneralSettings::first();

        return view('payment.checkout', compact('txn', 'methods', 'gs'));
    }

    public function submit(Request $request, string $invoice)
    {
        $txn = $this->pay->findByInvoice($invoice);
        if (!$txn || $txn->isPaid()) {
            return back()->with('error', 'অবৈধ পেমেন্ট রিকোয়েস্ট।');
        }

        $methods = array_keys($this->pay->paymentMethods());
        $request->validate([
            'payment_method'  => 'required|in:' . implode(',', $methods),
            'sender_number'   => 'required|string|min:11|max:15',
            'transaction_id'  => 'required|string|min:6|max:32',
        ], [
            'payment_method.required' => 'পেমেন্ট মেথড নির্বাচন করুন।',
            'sender_number.required'  => 'আপনার মোবাইল নম্বর দিন।',
            'transaction_id.required' => 'Transaction ID দিন।',
        ]);

        try {
            $this->pay->submitCustomerPayment($txn, [
                'payment_method' => $request->payment_method,
                'sender_number'  => $request->sender_number,
                'transaction_id' => strtoupper(trim($request->transaction_id)),
            ]);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        $txn->refresh();

        $url = $this->successUrl($txn);
        if ($txn->isPaid()) {
            $msg = 'success';
            $text = 'পেমেন্ট verify হয়েছে! TrxID ও amount মিলেছে।';
        } elseif ($this->pay->isSmsVerifyMode()) {
            $msg = 'info';
            if (!empty($txn->admin_note) && str_contains($txn->admin_note, 'mismatch')) {
                $text = 'পেমেন্ট PENDING — SMS-এ TrxID/amount/mobile match হয়নি। সঠিক তথ্য দিয়ে আবার চেষ্টা করুন।';
            } else {
                $text = 'পেমেন্ট PENDING — SMS verify হলে স্বয়ংক্রিয়ভাবে Paid হবে। ১-২ মিনিট অপেক্ষা করে পেজ refresh করুন।';
            }
        } else {
            $msg = 'info';
            $text = 'আপনার পেমেন্ট PENDING। Admin verify করলে সক্রিয় হবে।';
        }

        return redirect($url)->with($msg, $text);
    }

    public function status(string $invoice)
    {
        $txn = $this->pay->findByInvoice($invoice);
        if (!$txn) {
            return response()->json(['ok' => false, 'message' => 'Not found'], 404);
        }

        $result = $this->pay->verify($invoice);

        return response()->json([
            'ok'      => true,
            'status'  => $result['status'] ?? 'PENDING',
            'paid'    => ($result['status'] ?? '') === 'COMPLETED',
            'amount'  => $result['amount'] ?? null,
            'invoice' => $invoice,
        ]);
    }

    public function cancel(string $invoice)
    {
        $txn = $this->pay->findByInvoice($invoice);
        if ($txn && !$txn->isPaid()) {
            $txn->update(['status' => 'cancelled']);
        }
        $cancel = $txn->cancel_url ?? url('/');
        return redirect($cancel)->with('error', 'পেমেন্ট বাতিল হয়েছে।');
    }

    private function successUrl($txn): string
    {
        $base = $txn->redirect_url;
        $sep = str_contains($base, '?') ? '&' : '?';
        return $base . $sep . 'invoice_id=' . urlencode($txn->invoice_id);
    }
}
