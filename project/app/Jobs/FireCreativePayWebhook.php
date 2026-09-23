<?php

namespace App\Jobs;

use App\Models\PaymentTransaction;
use App\Services\CreativePayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FireCreativePayWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 12;

    public int $tries = 2;

    public function __construct(public int $transactionId) {}

    public function handle(CreativePayService $pay): void
    {
        $txn = PaymentTransaction::find($this->transactionId);
        if (!$txn || empty($txn->webhook_url)) {
            return;
        }

        $apiKey  = $pay->getApiKey();
        $payload = $pay->formatResponse($txn);

        try {
            Http::withoutVerifying()
                ->timeout(8)
                ->connectTimeout(3)
                ->withHeaders([
                    'X-CREATIVEPAY-API-KEY' => $apiKey,
                    'Content-Type'          => 'application/json',
                    'Accept'                => 'application/json',
                ])
                ->post($txn->webhook_url, $payload);

            $txn->update(['webhook_sent_at' => now()]);
        } catch (\Throwable $e) {
            Log::error('CreativePay webhook failed', [
                'invoice' => $txn->invoice_id,
                'url'     => $txn->webhook_url,
                'error'   => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
