<?php

namespace App\Jobs;

use App\Models\GeneralSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPosSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 5;

    public function __construct(
        protected string $number,
        protected string $message
    ) {}

    public function handle(): void
    {
        try {
            $gs = GeneralSettings::first();
            if (!$gs || !$gs->sms_api_key || !$gs->sms_sender_id) {
                return;
            }

            Http::timeout(3)->get('http://bulksmsbd.net/api/smsapi', [
                'api_key'  => $gs->sms_api_key,
                'type'     => 'text',
                'number'   => $this->number,
                'senderid' => $gs->sms_sender_id,
                'message'  => $this->message,
            ]);
        } catch (\Throwable $e) {
            Log::warning('POS SMS failed: ' . $e->getMessage());
        }
    }
}
