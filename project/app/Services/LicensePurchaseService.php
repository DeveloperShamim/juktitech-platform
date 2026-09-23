<?php

namespace App\Services;

use App\Http\Controllers\User\LicenseGenerateController;
use App\Models\Fund;
use App\Models\FundTransaction;
use App\Models\License;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * পাবলিক / পেমেন্ট গেটওয়ে থেকে লাইসেন্স ক্রয় — অর্ডার + লাইসেন্স প্রসেসিং।
 */
class LicensePurchaseService
{
    /** পাবলিক ব্লেড থেকে কেনা — Lifetime */
    public const PUBLIC_VALIDITY = 0;

    /**
     * @return array{domain:string,license_key:string,signature_key:string,push_success:bool,push_message:string}|null
     */
    public function processPublicPurchase(array $meta, ?string $invoiceId, bool $isSuccess): ?array
    {
        $domain = License::normalizeHostInput($meta['domain'] ?? '');
        $email  = trim((string) ($meta['email'] ?? ''));
        $name   = trim((string) ($meta['name'] ?? ($email ? explode('@', $email)[0] : 'Client')));
        $amount = (float) ($meta['amount'] ?? 0);

        if ($domain === '' || $email === '') {
            Log::warning('PublicLicense: missing domain or email', $meta);

            return null;
        }

        $existingLicense = License::where('domain_name', $domain)->first();
        if ($existingLicense) {
            return $this->licenseResult($existingLicense, true, 'License already exists.');
        }

        $user = $this->findOrCreateUser($email, $name);
        $existingOrder = $this->findLicenseOrderByInvoice($invoiceId);

        if ($existingOrder) {
            return $this->handleExistingOrder($existingOrder, $user, $domain, $amount, $invoiceId, $isSuccess);
        }

        $license = null;
        if ($isSuccess) {
            $license = $this->createLicense($user, $domain, self::PUBLIC_VALIDITY);
        }

        $paidAmount = $isSuccess ? $amount : 0;
        $dueAmount  = $isSuccess ? 0 : $amount;

        LicenseGenerateController::saveLicensePosOrder(
            $user,
            $license,
            $amount,
            $paidAmount,
            $dueAmount,
            'CreativePay',
            $invoiceId,
            $domain,
            self::PUBLIC_VALIDITY
        );

        if ($isSuccess && $paidAmount > 0) {
            $this->recordFundIncome($paidAmount, $invoiceId, $domain);
        }

        return $license ? $this->licenseResult($license, true, 'License created.') : null;
    }

    private function handleExistingOrder(
        Order $order,
        User $user,
        string $domain,
        float $amount,
        ?string $invoiceId,
        bool $isSuccess
    ): ?array {
        $license = License::where('domain_name', $domain)->first();

        if (!$isSuccess) {
            Log::info("PublicLicense: order {$order->order_number} still pending for {$domain}");

            return null;
        }

        if (!$license) {
            $license = $this->createLicense($user, $domain, self::PUBLIC_VALIDITY);
        }

        if ($order->due_amount > 0) {
            $collected = $order->due_amount;
            $order->paid_amount = $order->total_amount;
            $order->due_amount  = 0;
            $order->status      = 'Active';
            $order->save();

            $this->recordFundIncome($collected, $invoiceId, $domain);
        }

        $meta = json_decode($order->support_note ?? '{}', true);
        if (is_array($meta) && empty($meta['license_id']) && $license) {
            $meta['license_id'] = $license->id;
            $order->support_note = json_encode($meta);
            $order->save();
        }

        return $this->licenseResult($license, true, 'Payment completed.');
    }

    public function findLicenseOrderByInvoice(?string $invoiceId): ?Order
    {
        if (!$invoiceId) {
            return null;
        }

        return Order::query()
            ->where('description', 'LIKE', '%License Generation%')
            ->where('support_note', 'LIKE', '%' . $invoiceId . '%')
            ->first();
    }

    public function findOrCreateUser(string $email, string $name): User
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        return User::create([
            'name'     => $name,
            'email'    => $email,
            'username' => Str::slug($name) . '_' . Str::random(4),
            'phone'    => '000',
            'password' => Hash::make(Str::random(16)),
        ]);
    }

    public function createLicense(User $user, string $domain, int $validity = 0): License
    {
        $licenseKey   = LicenseService::generateLicenseKey();
        $signatureKey = LicenseService::generateSignature($licenseKey, $domain);

        $license = License::create([
            'user_id'       => $user->id,
            'domain_name'   => $domain,
            'license_key'   => $licenseKey,
            'signature_key' => $signatureKey,
            'expiry_date'   => LicenseService::expiryDate($validity),
            'status'        => 1,
        ]);

        $push = LicenseService::pushToClientSite(
            $domain,
            $licenseKey,
            $signatureKey,
            $license->expiry_date ? $license->expiry_date->toDateString() : null
        );

        Log::info("License created for {$domain}", ['license_id' => $license->id, 'push' => $push]);

        return $license;
    }

    private function recordFundIncome(float $amount, ?string $invoiceId, string $domain): void
    {
        if ($amount <= 0) {
            return;
        }

        $ref = 'LIC-PUB-' . ($invoiceId ?: Str::random(8));

        $already = FundTransaction::where('reference_id', $ref)->exists();
        if ($already) {
            return;
        }

        $fund = Fund::firstOrCreate(['id' => 1]);
        $fund->increment('current_balance', $amount);

        FundTransaction::create([
            'type'           => 'income',
            'amount'         => $amount,
            'payment_method' => 'CreativePay',
            'reference_id'   => $ref,
            'note'           => 'পাবলিক লাইসেন্স: ' . $domain,
        ]);
    }

    /**
     * @return array{domain:string,license_key:string,signature_key:string,push_success:bool,push_message:string}
     */
    private function licenseResult(License $license, ?bool $pushSuccess, ?string $pushMessage): array
    {
        if ($pushSuccess === null) {
            $push = LicenseService::pushToClientSite(
                $license->domain_name,
                $license->license_key,
                $license->signature_key ?? '',
                $license->expiry_date ? $license->expiry_date->toDateString() : null
            );
            $pushSuccess = $push['success'];
            $pushMessage = $push['message'];
        }

        return [
            'domain'        => $license->domain_name,
            'license_key'   => $license->license_key,
            'signature_key' => $license->signature_key,
            'push_success'  => (bool) $pushSuccess,
            'push_message'  => (string) ($pushMessage ?? ''),
        ];
    }
}
