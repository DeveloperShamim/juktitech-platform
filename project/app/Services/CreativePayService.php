<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\GeneralSettings;
use App\Models\PaymentSmsLog;
use App\Models\PaymentTransaction;
use App\Jobs\FireCreativePayWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreativePayService
{
    public function __construct(
        private PaymentSmsParser $smsParser
    ) {}

    public function getApiKey(): ?string
    {
        $gs = GeneralSettings::first();

        return $gs->uddoktapay_api_key ?? null;
    }

    public function getPaymentMode(): string
    {
        $gs = GeneralSettings::first();
        $mode = $gs->payment_mode ?? 'sms';

        return in_array($mode, ['sms', 'manual'], true) ? $mode : 'sms';
    }

    public function isSmsVerifyMode(): bool
    {
        return $this->getPaymentMode() === 'sms';
    }

    public function validateAppRequest(Request $request): bool
    {
        $key = $this->getApiKey();
        if (empty($key)) {
            return false;
        }

        return $request->header('X-CREATIVEPAY-APP-KEY') === $key
            || $request->header('X-CREATIVEPAY-API-KEY') === $key;
    }

    public function resolveAdminFromAppRequest(Request $request): ?Admin
    {
        if (!$this->validateAppRequest($request)) {
            return null;
        }

        $token = trim((string) $request->header('X-CREATIVEPAY-ADMIN-TOKEN', ''));
        if ($token === '') {
            return null;
        }

        return Admin::query()
            ->where('token', $token)
            ->where('role_id', 1)
            ->where('verify', 1)
            ->first();
    }

    public function validateAdminAppRequest(Request $request): bool
    {
        return $this->resolveAdminFromAppRequest($request) !== null;
    }

    public function createCheckout(array $data): array
    {
        $invoiceId = 'CP-' . strtoupper(Str::random(12)) . '-' . time();
        $metadata = array_merge($data['metadata'] ?? [], ['cp_invoice_id' => $invoiceId]);

        PaymentTransaction::create([
            'invoice_id'   => $invoiceId,
            'full_name'    => $data['full_name'] ?? 'Customer',
            'email'        => $data['email'] ?? null,
            'amount'       => $data['amount'],
            'metadata'     => $metadata,
            'redirect_url' => $data['redirect_url'],
            'cancel_url'   => $data['cancel_url'] ?? null,
            'webhook_url'  => $data['webhook_url'] ?? null,
            'status'       => 'pending',
            'gateway_provider' => null,
        ]);

        return [
            'invoice_id'  => $invoiceId,
            'payment_url' => route('payment.checkout.show', $invoiceId),
        ];
    }

    public function verify(string $invoiceId): ?array
    {
        $txn = PaymentTransaction::where('invoice_id', $invoiceId)->first();
        if (!$txn) {
            return null;
        }

        if ($this->isSmsVerifyMode() && $txn->status === 'pending' && !empty($txn->transaction_id)) {
            $this->retrySmsMatchForTransaction($txn);
            $txn->refresh();
        }

        return $this->formatResponse($txn);
    }

    public function formatResponse(PaymentTransaction $txn): array
    {
        return [
            'status'         => $txn->verifyStatus(),
            'invoice_id'     => $txn->invoice_id,
            'amount'         => (string) $txn->amount,
            'metadata'       => $txn->metadata ?? [],
            'payment_method' => $txn->payment_method ? ucfirst($txn->payment_method) : 'CreativePay',
            'transaction_id' => $txn->transaction_id,
            'sender_number'  => $txn->sender_number,
        ];
    }

    public function validateWebhookRequest(Request $request): bool
    {
        $key = $this->getApiKey();
        if (empty($key)) {
            return false;
        }

        return $request->header('X-CREATIVEPAY-API-KEY') === $key;
    }

    public function findByInvoice(string $invoiceId): ?PaymentTransaction
    {
        return PaymentTransaction::where('invoice_id', $invoiceId)->first();
    }

    public function submitCustomerPayment(PaymentTransaction $txn, array $input): PaymentTransaction
    {
        $trxId = strtoupper(trim($input['transaction_id']));

        if ($this->isDuplicateTrxId($trxId, $txn->id)) {
            throw new \RuntimeException('এই Transaction ID ইতিমধ্যে ব্যবহার হয়েছে।');
        }

        $txn->update([
            'payment_method'  => strtolower(trim($input['payment_method'])),
            'sender_number'   => $this->smsParser->normalizePhone($input['sender_number']),
            'transaction_id'  => $trxId,
            'status'          => 'pending',
            'paid_at'         => null,
        ]);
        $txn->refresh();

        if ($this->isSmsVerifyMode()) {
            $matched = $this->retrySmsMatchForTransaction($txn, $input['sender_number'] ?? null);
            if ($matched) {
                return $txn->fresh();
            }

            $smsLogs = PaymentSmsLog::where('transaction_id', $trxId)->get();
            if ($smsLogs->isNotEmpty()) {
                $reason = $this->describeSmsMismatch($txn, $smsLogs->first(), $input['sender_number'] ?? null);
                $txn->update(['admin_note' => $reason]);
            }

            app(PaymentNotificationService::class)->notifyPending($txn, 'submitted');

            return $txn->fresh();
        }

        app(PaymentNotificationService::class)->notifyPending($txn, 'submitted');

        return $txn->fresh();
    }

    public function tryMatchPendingBySms(PaymentSmsLog $sms): bool
    {
        $pending = PaymentTransaction::where('transaction_id', $sms->transaction_id)
            ->where('status', 'pending')
            ->get();

        foreach ($pending as $txn) {
            if ($this->matchTransactionWithSms($txn, $sms, $txn->sender_number)) {
                return true;
            }

            $txn->update([
                'admin_note' => $this->describeSmsMismatch($txn, $sms, $txn->sender_number),
            ]);
        }

        return false;
    }

    public function retrySmsMatchForTransaction(PaymentTransaction $txn, ?string $customerSender = null): bool
    {
        if ($txn->isPaid() || empty($txn->transaction_id)) {
            return false;
        }

        $sender = $customerSender ?: $txn->sender_number;
        $smsLogs = PaymentSmsLog::where('transaction_id', strtoupper($txn->transaction_id))
            ->where('status', 'available')
            ->orderByDesc('id')
            ->get();

        foreach ($smsLogs as $sms) {
            if ($this->matchTransactionWithSms($txn, $sms, $sender)) {
                return true;
            }
        }

        return false;
    }

    public function matchTransactionWithSms(
        PaymentTransaction $txn,
        PaymentSmsLog $sms,
        ?string $customerSender = null
    ): bool {
        if (!$sms->isAvailable() || $txn->isPaid()) {
            return false;
        }

        if (strtoupper($txn->transaction_id ?? '') !== strtoupper($sms->transaction_id ?? '')) {
            return false;
        }

        if (!$this->smsParser->amountsMatch((float) $txn->amount, (float) $sms->amount)) {
            return false;
        }

        $wasPaid = $txn->isPaid();

        $txn->update([
            'status'         => 'completed',
            'paid_at'        => now(),
            'payment_method' => $txn->payment_method ?: $sms->provider,
            'sender_number'  => $txn->sender_number ?: $sms->sender_number,
            'admin_note'     => null,
        ]);

        $sms->update([
            'status'                 => 'matched',
            'payment_transaction_id' => $txn->id,
            'matched_at'             => now(),
        ]);

        $txn->refresh();

        if (!$wasPaid && $txn->isPaid()) {
            app(PosOrderActivationService::class)->syncOrderAndActivate($txn->invoice_id);
            $this->fireWebhook($txn);
            app(PaymentNotificationService::class)->notifyPaid($txn);
        }

        return true;
    }

    protected function describeSmsMismatch(
        PaymentTransaction $txn,
        PaymentSmsLog $sms,
        ?string $customerSender = null
    ): string {
        $issues = [];

        if (strtoupper($txn->transaction_id ?? '') !== strtoupper($sms->transaction_id ?? '')) {
            $issues[] = 'TrxID mismatch';
        }

        if (!$this->smsParser->amountsMatch((float) $txn->amount, (float) $sms->amount)) {
            $issues[] = 'amount mismatch (expected ' . $txn->amount . ', SMS ' . $sms->amount . ')';
        }

        if (empty($issues)) {
            return 'SMS পাওয়া গেছে — verify pending';
        }

        return 'SMS verify failed: ' . implode('; ', $issues);
    }

    protected function isDuplicateTrxId(string $trxId, int $excludeId): bool
    {
        return PaymentTransaction::where('transaction_id', $trxId)
            ->where('id', '!=', $excludeId)
            ->whereIn('status', ['pending', 'completed'])
            ->exists();
    }

    public function approve(PaymentTransaction $txn, ?string $note = null, bool $fireWebhook = true): PaymentTransaction
    {
        $wasPaid = $txn->isPaid();

        $txn->update([
            'status'     => 'completed',
            'paid_at'    => now(),
            'admin_note' => $note,
        ]);
        $txn->refresh();

        if ($fireWebhook) {
            $this->fireWebhook($txn);
        }

        if (!$wasPaid && $txn->isPaid()) {
            app(PaymentNotificationService::class)->notifyPaid($txn, 'admin_approved');
        }

        return $txn;
    }

    public function approveAndActivate(PaymentTransaction $txn, ?string $note = null): bool
    {
        if ($txn->status !== 'pending') {
            return false;
        }

        $this->approve($txn, $note, fireWebhook: false);

        return app(PosOrderActivationService::class)->syncOrderAndActivate($txn->invoice_id);
    }

    public function reject(PaymentTransaction $txn, ?string $note = null): PaymentTransaction
    {
        $txn->update([
            'status'     => 'failed',
            'admin_note' => $note,
        ]);

        app(PaymentNotificationService::class)->notifyFailed($txn, $note ?? 'Admin rejected');

        return $txn;
    }

    public function fireWebhook(PaymentTransaction $txn): void
    {
        if (empty($txn->webhook_url)) {
            return;
        }

        FireCreativePayWebhook::dispatch($txn->id)->afterResponse();
    }

    public function isConfigured(): bool
    {
        if ($this->isSmsVerifyMode()) {
            return !empty($this->paymentMethods()) && !empty($this->getApiKey());
        }

        return !empty($this->paymentMethods());
    }

    public function paymentMethods(): array
    {
        $gs = GeneralSettings::first();
        $methods = [];

        if (!empty($gs->payment_bkash_number)) {
            $methods['bkash'] = [
                'label'        => 'bKash',
                'number'       => $gs->payment_bkash_number,
                'color'        => '#E2136E',
                'account_type' => PaymentInstructionService::normalizeType($gs->payment_bkash_account_type ?? 'personal'),
            ];
        }
        if (!empty($gs->payment_nagad_number)) {
            $methods['nagad'] = [
                'label'        => 'Nagad',
                'number'       => $gs->payment_nagad_number,
                'color'        => '#F6921E',
                'account_type' => PaymentInstructionService::normalizeType($gs->payment_nagad_account_type ?? 'personal'),
            ];
        }
        if (!empty($gs->payment_rocket_number)) {
            $methods['rocket'] = [
                'label'        => 'Rocket',
                'number'       => $gs->payment_rocket_number,
                'color'        => '#8C3494',
                'account_type' => PaymentInstructionService::normalizeType($gs->payment_rocket_account_type ?? 'personal'),
            ];
        }

        return $methods;
    }
}
