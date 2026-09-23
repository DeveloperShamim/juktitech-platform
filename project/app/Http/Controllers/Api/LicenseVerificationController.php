<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\UnauthorizedLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @deprecated রুট আর এখানে নেই — একীভূত লজিক: {@see \App\Http\Controllers\Admin\LicenseController::verifyLicense}
 *           ও {@see \App\Http\Controllers\Api\LicenseSecurityApiController::logUnauthorized}
 */
class LicenseVerificationController extends Controller
{
    private const HMAC_SALT = 'your_secret_salt_key';

    private const TOKEN_SALT = 'YOUR_SECRET_SALT_HERE_12345';

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain'       => 'required|string|max:255',
            'license_key'  => 'required|string|max:255',
            'signature'    => 'required|string',
        ]);

        if ($validator->fails()) {
            $actionDomain = $this->normalizeDomain($request->input('domain'));

            return response()->json([
                'status'  => 'invalid',
                'message' => 'domain, license_key এবং signature প্রয়োজন।',
                'action'  => $this->wipeAction($actionDomain),
            ], 422);
        }

        $domain = $this->normalizeDomain($request->input('domain'));
        $licenseKey = trim((string) $request->input('license_key'));
        $signature = (string) $request->input('signature');

        if ($domain === null || $domain === '') {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'ডোমেইন সঠিক নয়।',
                'action'  => $this->wipeAction(null),
            ], 400);
        }

        $expectedSignature = hash_hmac('sha256', $domain, self::HMAC_SALT);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'সিগনেচার যাচাই ব্যর্থ।',
                'action'  => $this->wipeAction($domain),
            ], 403);
        }

        $license = License::where('domain_name', $domain)
            ->where('license_key', $licenseKey)
            ->first();

        if (!$license || !$this->isStatusActive($license->status)) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'লাইসেন্স পাওয়া যায়নি বা সক্রিয় নয়।',
                'action'  => $this->wipeAction($domain),
            ], 403);
        }

        $secureToken = md5($domain . self::TOKEN_SALT);

        return response()->json([
            'status'        => 'valid',
            'secure_token'  => $secureToken,
            'action'        => $this->wipeAction($domain),
        ]);
    }

    public function logUnauthorized(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain'       => 'required|string|max:255',
            'ip'           => 'nullable|string|max:45',
            'reason'       => 'required|string',
            'url'          => 'nullable|string',
            'attempted_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'ভ্যালিডেশন ব্যর্থ।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $domain = $this->normalizeDomain($request->input('domain')) ?? trim((string) $request->input('domain'));

        UnauthorizedLog::create([
            'domain'       => $domain,
            'ip'           => $request->input('ip'),
            'reason'       => $request->input('reason'),
            'url'          => $request->input('url'),
            'attempted_at' => Carbon::parse($request->input('attempted_at')),
        ]);

        return response()->json([
            'status'  => 'ok',
            'message' => 'লগ সংরক্ষিত হয়েছে।',
        ], 201);
    }

    private function isStatusActive(mixed $status): bool
    {
        if ($status === 'active' || $status === 'Active') {
            return true;
        }

        return (int) $status === 1;
    }

    private function wipeAction(?string $domain): string
    {
        return UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none';
    }

    private function normalizeDomain(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $value = preg_replace('#^https?://#i', '', $value);
        $value = preg_replace('#^www\.#i', '', $value);
        $value = explode('/', $value)[0];

        return strtolower(trim($value));
    }
}
