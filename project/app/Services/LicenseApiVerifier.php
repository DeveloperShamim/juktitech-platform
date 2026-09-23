<?php

namespace App\Services;

use App\Models\License;
use Carbon\Carbon;

/**
 * Public API (check-update, download-update) এর জন্য লাইসেন্স + সিগনেচার যাচাই।
 * নতুন সিস্টেম: LICENSE_SIGNATURE (.env) === licenses.signature_key
 */
class LicenseApiVerifier
{
    public const MSG_MISSING = 'domain, license_key এবং signature প্রয়োজন।';

    public const MSG_INVALID = 'লাইসেন্স কী, সিগনেচার বা ডোমেইন সঠিক নয় অথবা মেয়াদ শেষ হয়ে গেছে।';

    public const MSG_SIGNATURE = 'সিগনেচার যাচাই ব্যর্থ। লাইসেন্স কী ও সিগনেচার মিলছে না।';

    /**
     * @return array{ok: bool, license: ?License, message: string, http: int}
     */
    public static function verify(string $domain, string $licenseKey, ?string $signature): array
    {
        $domain = License::normalizeHostInput($domain) ?? '';
        $licenseKey = trim($licenseKey);
        $signature = trim((string) $signature);

        if ($domain === '' || $licenseKey === '' || $signature === '') {
            return [
                'ok' => false,
                'license' => null,
                'message' => self::MSG_MISSING,
                'http' => 400,
            ];
        }

        $license = License::findForApiVerification($domain, $licenseKey);

        if (!$license) {
            return [
                'ok' => false,
                'license' => null,
                'message' => self::MSG_INVALID,
                'http' => 403,
            ];
        }

        if (!self::signatureMatches($license, $domain, $licenseKey, $signature)) {
            return [
                'ok' => false,
                'license' => null,
                'message' => self::MSG_SIGNATURE,
                'http' => 403,
            ];
        }

        if (!License::isActiveForApi($license)) {
            return [
                'ok' => false,
                'license' => null,
                'message' => self::MSG_INVALID,
                'http' => 403,
            ];
        }

        $isExpired = $license->expiry_date !== null && Carbon::parse($license->expiry_date)->isPast();
        if ($isExpired) {
            return [
                'ok' => false,
                'license' => null,
                'message' => self::MSG_INVALID,
                'http' => 403,
            ];
        }

        return [
            'ok' => true,
            'license' => $license,
            'message' => '',
            'http' => 200,
        ];
    }

    public static function signatureMatches(License $license, string $domain, string $licenseKey, string $provided): bool
    {
        $expected = trim((string) ($license->signature_key ?? ''));
        if ($expected === '') {
            $expected = LicenseService::generateSignature($licenseKey, $domain);
        }

        return hash_equals($expected, $provided);
    }
}
