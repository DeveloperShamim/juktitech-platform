<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Services\CreativePayService;
use Illuminate\Http\Request;

class PaymentManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $query = PaymentTransaction::query()->orderByDesc('id');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->paginate(25)->appends(['status' => $status]);
        $counts = [
            'pending'   => PaymentTransaction::where('status', 'pending')->count(),
            'completed' => PaymentTransaction::where('status', 'completed')->count(),
            'failed'    => PaymentTransaction::where('status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'status', 'counts'));
    }

    public function approve($id, CreativePayService $pay)
    {
        $txn = PaymentTransaction::findOrFail($id);
        if ($txn->status !== 'pending') {
            return back()->with('error', 'এই পেমেন্ট ইতিমধ্যে প্রসেস হয়েছে।');
        }
        $pay->approveAndActivate($txn, 'Admin approved');
        return back()->with('success', 'পেমেন্ট অনুমোদিত, অর্ডার Paid ও সার্ভিস Activate হয়েছে।');
    }

    public function reject($id, CreativePayService $pay)
    {
        $txn = PaymentTransaction::findOrFail($id);
        if ($txn->status !== 'pending') {
            return back()->with('error', 'এই পেমেন্ট ইতিমধ্যে প্রসেস হয়েছে।');
        }
        $pay->reject($txn, 'Admin rejected');
        return back()->with('success', 'পেমেন্ট প্রত্যাখ্যান করা হয়েছে।');
    }

    public function smsLogs(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = \App\Models\PaymentSmsLog::query()->orderByDesc('id');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $logs = $query->paginate(30)->appends(['status' => $status]);
        $counts = [
            'available' => \App\Models\PaymentSmsLog::where('status', 'available')->count(),
            'matched'   => \App\Models\PaymentSmsLog::where('status', 'matched')->count(),
            'ignored'   => \App\Models\PaymentSmsLog::where('status', 'ignored')->count(),
        ];

        return view('admin.payments.sms_logs', compact('logs', 'status', 'counts'));
    }
}
