<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSmsLog;
use App\Services\CreativePayService;
use App\Services\PaymentSmsParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreativePaySmsController extends Controller
{
    public function __construct(
        private PaymentSmsParser $parser,
        private CreativePayService $pay
    ) {}

    public function ping(Request $request)
    {
        if (!$this->pay->validateAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'CreativePay SMS App connected',
            'mode'    => $this->pay->getPaymentMode(),
        ]);
    }

    public function index(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $status = $request->get('status', 'all');
        $query = PaymentSmsLog::query()->orderByDesc('id');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query->limit(200)->get()->map(fn (PaymentSmsLog $log) => $this->formatLog($log));

        return response()->json([
            'ok'    => true,
            'items' => $items,
            'counts' => [
                'available' => PaymentSmsLog::where('status', 'available')->count(),
                'matched'   => PaymentSmsLog::where('status', 'matched')->count(),
                'ignored'   => PaymentSmsLog::where('status', 'ignored')->count(),
                'total'     => PaymentSmsLog::count(),
            ],
        ]);
    }

    /**
     * Mobile app থেকে SMS body — parse করে DB save + pending payment match।
     * Parse fail হলেও raw SMS save হবে (web/app এ দেখা যাবে)।
     */
    public function ingest(Request $request)
    {
        if (!$this->pay->validateAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'body'        => 'required|string|min:5',
            'from'        => 'nullable|string|max:64',
            'received_at' => 'nullable|date',
        ]);

        $parsed = $this->parser->parse($request->body, $request->from);

        if (!$parsed) {
            return $this->storeUnparsedSms($request);
        }

        if (PaymentSmsLog::where('transaction_id', $parsed['transaction_id'])->exists()) {
            $existing = PaymentSmsLog::where('transaction_id', $parsed['transaction_id'])->first();
            $matched = $existing ? $this->pay->tryMatchPendingBySms($existing) : false;

            return response()->json([
                'ok'      => true,
                'message' => $matched ? 'Already recorded & payment matched' : 'Already recorded',
                'trx_id'  => $parsed['transaction_id'],
                'amount'  => (float) $existing->amount,
                'matched' => $matched,
                'parsed'  => true,
            ]);
        }

        $log = PaymentSmsLog::create([
            'provider'       => $parsed['provider'],
            'transaction_id' => $parsed['transaction_id'],
            'amount'         => $parsed['amount'],
            'sender_number'  => $parsed['sender_number'],
            'raw_message'    => $request->body,
            'sms_from'       => $request->from,
            'received_at'    => $request->received_at ?? now(),
            'status'         => 'available',
        ]);

        $matched = $this->pay->tryMatchPendingBySms($log);

        Log::info('CreativePay SMS ingested', [
            'trx'     => $log->transaction_id,
            'amount'  => $log->amount,
            'matched' => $matched,
        ]);

        return response()->json([
            'ok'      => true,
            'message' => $matched ? 'SMS saved & payment matched' : 'SMS saved',
            'trx_id'  => $log->transaction_id,
            'amount'  => (float) $log->amount,
            'matched' => $matched,
            'parsed'  => true,
        ]);
    }

    protected function storeUnparsedSms(Request $request)
    {
        $hashId = 'RAW-' . strtoupper(substr(md5($request->body . ($request->from ?? '')), 0, 16));

        if (PaymentSmsLog::where('transaction_id', $hashId)->exists()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Already recorded (unparsed)',
                'trx_id'  => $hashId,
                'parsed'  => false,
            ]);
        }

        PaymentSmsLog::create([
            'provider'       => null,
            'transaction_id' => $hashId,
            'amount'         => 0,
            'sender_number'  => null,
            'raw_message'    => $request->body,
            'sms_from'       => $request->from,
            'received_at'    => $request->received_at ?? now(),
            'status'         => 'ignored',
        ]);

        Log::warning('CreativePay SMS saved as unparsed', ['trx' => $hashId]);

        return response()->json([
            'ok'      => true,
            'message' => 'SMS saved (could not parse TrxID/amount — check SMS format)',
            'trx_id'  => $hashId,
            'parsed'  => false,
        ]);
    }

    protected function formatLog(PaymentSmsLog $log): array
    {
        return [
            'id'             => $log->id,
            'provider'       => $log->provider,
            'transaction_id' => $log->transaction_id,
            'amount'         => (float) $log->amount,
            'sender_number'  => $log->sender_number,
            'raw_message'    => $log->raw_message,
            'sms_from'       => $log->sms_from,
            'status'         => $log->status,
            'received_at'    => $log->received_at?->format('Y-m-d H:i:s'),
            'matched_at'     => $log->matched_at?->format('Y-m-d H:i:s'),
        ];
    }
}
