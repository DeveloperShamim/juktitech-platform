<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LicenseService
{
    /**
     * Admin LicenseController::HMAC_SALT এর সাথে মিল রাখুন।
     */
    private const HMAC_SALT = 'your_secret_salt_key';

    /**
     * Client site এ push করার endpoint path।
     * Client এর domain এর সাথে যুক্ত হয়।
     * যেমন: https://clientdomain.com/license-receiver.php
     */
    public const PUSH_ENDPOINT = '/license-receiver.php';

    // =========================================================
    // Key Generation
    // =========================================================

    public static function generateLicenseKey(): string
    {
        return 'LIC-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
    }

    public static function generateSignature(string $licenseKey, string $domain): string
    {
        return hash_hmac('sha256', $licenseKey . '|' . $domain, self::HMAC_SALT);
    }

    /**
     * validity: 1/2/3 = বছর, 0 = Lifetime (null expiry)
     */
    public static function expiryDate(int $validity): ?Carbon
    {
        return $validity === 0 ? null : Carbon::now()->addYears($validity);
    }

    // =========================================================
    // Push Token (Client site receiver script এটা দিয়ে verify করে)
    // =========================================================

    /**
     * Server থেকে client site এ push করার সময় authentication token।
     * HMAC of domain + licenseKey + HMAC_SALT
     */
    public static function generatePushToken(string $domain, string $licenseKey): string
    {
        return hash_hmac('sha256', $domain . '|' . $licenseKey, self::HMAC_SALT);
    }

    // =========================================================
    // Push to Client Site via HTTP
    // =========================================================

    /**
     * Client site এ HTTP POST করে LICENSE_KEY ও LICENSE_SIGNATURE পাঠায়।
     * Client site এ license-receiver.php থাকতে হবে।
     *
     * @return array{success: bool, message: string, http_status?: int}
     */
    public static function pushToClientSite(
        string $domain,
        string $licenseKey,
        string $signatureKey,
        ?string $expiryDate
    ): array {
        $domain = trim($domain);
        if ($domain === '') {
            return ['success' => false, 'message' => 'Domain নেই।'];
        }

        $pushToken = self::generatePushToken($domain, $licenseKey);
        $endpoint  = 'https://' . $domain . self::PUSH_ENDPOINT;

        $payload = [
            'license_key'   => $licenseKey,
            'signature_key' => $signatureKey,
            'expiry_date'   => $expiryDate ?? 'lifetime',
            'push_token'    => $pushToken,
        ];

        // প্রথমে HTTPS, ব্যর্থ হলে HTTP
        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['status']) && $body['status'] === 'ok') {
                    return ['success' => true, 'message' => 'Client site এ সফলভাবে push হয়েছে।', 'http_status' => $response->status()];
                }
                return ['success' => false, 'message' => 'Client site response: ' . ($body['message'] ?? 'Unknown error'), 'http_status' => $response->status()];
            }

            // HTTPS ব্যর্থ — HTTP দিয়ে চেষ্টা
            $httpEndpoint = 'http://' . $domain . self::PUSH_ENDPOINT;
            $response2 = Http::withoutVerifying()->timeout(10)->post($httpEndpoint, $payload);

            if ($response2->successful()) {
                $body2 = $response2->json();
                if (isset($body2['status']) && $body2['status'] === 'ok') {
                    return ['success' => true, 'message' => 'Client site এ push হয়েছে (HTTP)।', 'http_status' => $response2->status()];
                }
                return ['success' => false, 'message' => 'Client site response: ' . ($body2['message'] ?? 'Unknown error'), 'http_status' => $response2->status()];
            }

            return [
                'success'     => false,
                'message'     => "Client site respond করেনি। নিশ্চিত করুন {$domain}/license-receiver.php আছে।",
                'http_status' => $response2->status(),
            ];
        } catch (\Exception $e) {
            Log::warning("License push failed for {$domain}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
        }
    }

    // =========================================================
    // .env File Writer (same-server local path এর জন্য)
    // =========================================================

    /**
     * Client site এর .env ফাইলে সরাসরি লেখে (শুধু same-server এর ক্ষেত্রে)।
     *
     * @param  string $sitePath  Client site এর root path
     * @return array{success: bool, message: string}
     */
    public static function writeToEnv(string $sitePath, string $licenseKey, string $signatureKey): array
    {
        $sitePath = trim($sitePath);
        if ($sitePath === '') {
            return ['success' => false, 'message' => 'Site path দেওয়া হয়নি।'];
        }

        $envFile = rtrim($sitePath, '/\\') . DIRECTORY_SEPARATOR . '.env';

        if (!file_exists($envFile)) {
            return ['success' => false, 'message' => ".env ফাইল পাওয়া যায়নি: {$envFile}"];
        }

        if (!is_writable($envFile)) {
            return ['success' => false, 'message' => ".env ফাইল writable নয়: {$envFile}"];
        }

        $content = file_get_contents($envFile);

        if (preg_match('/^LICENSE_KEY=/m', $content)) {
            $content = preg_replace('/^LICENSE_KEY=.*/m', 'LICENSE_KEY=' . $licenseKey, $content);
        } else {
            $content = rtrim($content) . "\n\nLICENSE_KEY=" . $licenseKey . "\n";
        }

        if (preg_match('/^LICENSE_SIGNATURE=/m', $content)) {
            $content = preg_replace('/^LICENSE_SIGNATURE=.*/m', 'LICENSE_SIGNATURE=' . $signatureKey, $content);
        } else {
            $content = rtrim($content) . "\nLICENSE_SIGNATURE=" . $signatureKey . "\n";
        }

        if (file_put_contents($envFile, $content) === false) {
            return ['success' => false, 'message' => '.env ফাইলে লেখা ব্যর্থ হয়েছে।'];
        }

        return ['success' => true, 'message' => ".env ফাইল সফলভাবে আপডেট হয়েছে।"];
    }
}
