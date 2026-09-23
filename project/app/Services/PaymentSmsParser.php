<?php

namespace App\Services;

class PaymentSmsParser
{
    /**
     * Parse bKash / Nagad / Rocket payment received SMS.
     */
    public function parse(string $body, ?string $smsFrom = null): ?array
    {
        $body = trim($body);
        if ($body === '') {
            return null;
        }

        $provider = $this->detectProvider($body, $smsFrom);

        $trxId = $this->extractTrxId($body, $provider);
        $amount = $this->extractAmount($body);
        $sender = $this->extractSender($body);

        if (!$trxId || $amount === null || $amount <= 0) {
            return null;
        }

        return [
            'provider'       => $provider,
            'transaction_id' => strtoupper($trxId),
            'amount'         => round($amount, 2),
            'sender_number'  => $sender ? $this->normalizePhone($sender) : null,
        ];
    }

    protected function detectProvider(string $body, ?string $smsFrom): ?string
    {
        $hay = strtolower($body . ' ' . ($smsFrom ?? ''));

        if (str_contains($hay, 'bkash') || str_contains($hay, 'bKash')) {
            return 'bkash';
        }
        if (str_contains($hay, 'nagad')) {
            return 'nagad';
        }
        if (str_contains($hay, 'rocket') || str_contains($hay, '16216')) {
            return 'rocket';
        }

        if (preg_match('/TrxID|TxnID|Trx ID/i', $body)) {
            return 'bkash';
        }

        if (preg_match('/\bRef[\s:.]/i', $body)) {
            return 'nagad';
        }

        return null;
    }

    protected function extractTrxId(string $body, ?string $provider): ?string
    {
        $patterns = [
            '/TrxID[\s:]*([A-Z0-9]{6,20})/i',
            '/TxnID[\s:]*([A-Z0-9]{6,20})/i',
            '/Trx ID[\s:]*([A-Z0-9]{6,20})/i',
            '/Transaction ID[\s:]*([A-Z0-9]{6,20})/i',
            '/Ref[\s:.]*([A-Z0-9]{6,20})/i',
            '/Reference[\s:]*([A-Z0-9]{6,20})/i',
            '/Txn[\s:]*([A-Z0-9]{6,20})/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $body, $m)) {
                return $m[1];
            }
        }

        return null;
    }

    protected function extractAmount(string $body): ?float
    {
        $patterns = [
            '/received\s+(?:Tk|Taka|BDT)?\s*([\d,]+(?:\.\d{1,2})?)/i',
            '/(?:Tk|Taka|BDT)\s*([\d,]+(?:\.\d{1,2})?)\s*(?:received|has been received)/i',
            '/Amount[\s:]*(?:Tk|Taka|BDT)?\s*([\d,]+(?:\.\d{1,2})?)/i',
            '/amount[\s:]*([\d,]+(?:\.\d{1,2})?)\s*(?:tk|taka|টাকা|BDT)/iu',
            '/টাকা[\s:]*([\d,]+(?:\.\d{1,2})?)/u',
            '/(?:Tk|Taka|BDT)\s*([\d,]+(?:\.\d{1,2})?)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $body, $m)) {
                $amount = (float) str_replace(',', '', $m[1]);
                if ($amount > 0) {
                    return $amount;
                }
            }
        }

        return null;
    }

    protected function extractSender(string $body): ?string
    {
        $patterns = [
            '/from[\s:]*(?:0)?(1[3-9]\d{8})/i',
            '/Sender[\s:]*(?:0)?(1[3-9]\d{8})/i',
            '/Customer[\s:]*(?:0)?(1[3-9]\d{8})/i',
            '/A\/C[\s:]*(?:0)?(1[3-9]\d{8})/i',
            '/(?:^|\s)(?:0)?(1[3-9]\d{8})(?:\s|\.|,|$)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $body, $m)) {
                return '0' . substr(preg_replace('/\D/', '', $m[1]), -10);
            }
        }

        return null;
    }

    public function normalizePhone(?string $number): ?string
    {
        if ($number === null || $number === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $number);

        if (str_starts_with($digits, '880')) {
            $digits = '0' . substr($digits, 3);
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '1')) {
            $digits = '0' . $digits;
        }

        if (strlen($digits) >= 11) {
            return substr($digits, -11);
        }

        return $number;
    }

    public function phonesMatch(?string $a, ?string $b): bool
    {
        if (!$a || !$b) {
            return false;
        }

        $na = $this->normalizePhone($a);
        $nb = $this->normalizePhone($b);

        if (!$na || !$nb) {
            return false;
        }

        return $na === $nb
            || str_ends_with($na, substr($nb, -10))
            || str_ends_with($nb, substr($na, -10));
    }

    public function amountsMatch(float $expected, float $received, float $tolerance = 0.01): bool
    {
        return abs($expected - $received) <= $tolerance;
    }
}
