<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScriptUpdate;
use App\Services\LicenseApiVerifier;
use Illuminate\Http\Request;

class CheckUpdateController extends Controller
{
    /**
     * Script Update Check API
     * Client sites call this to check for available updates.
     *
     * POST /api/check-update
     * Body: domain, license_key, signature, script_name, current_version
     */
    public function checkUpdate(Request $request)
    {
        $domain = (string) $request->input('domain', '');
        $licenseKey = (string) $request->input('license_key', '');
        $signature = (string) ($request->input('signature') ?: $request->input('signature_key', ''));
        $scriptName = trim((string) $request->input('script_name', ''));
        $currentVersion = trim((string) $request->input('current_version', '1.0.0'));

        if ($scriptName === '') {
            return response()->json([
                'status' => 'invalid',
                'message' => 'script_name প্রয়োজন।',
            ], 400);
        }

        $check = LicenseApiVerifier::verify($domain, $licenseKey, $signature);
        if (!$check['ok']) {
            return response()->json([
                'status' => 'invalid',
                'message' => $check['message'],
            ], $check['http']);
        }

        $update = ScriptUpdate::where('script_name', $scriptName)
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->first();

        if (!$update) {
            return response()->json([
                'status' => 'up_to_date',
                'message' => 'আপডেট পাওয়া যায়নি।',
                'current_version' => $currentVersion,
                'latest_version' => $currentVersion,
            ]);
        }

        $latestVersion = $update->version;
        $hasUpdate = version_compare($latestVersion, $currentVersion, '>');

        if (!$hasUpdate) {
            return response()->json([
                'status' => 'up_to_date',
                'message' => 'আপনার স্ক্রিপ্ট সর্বশেষ ভার্সনে আছে।',
                'current_version' => $currentVersion,
                'latest_version' => $latestVersion,
            ]);
        }

        return response()->json([
            'status' => 'update_available',
            'message' => 'নতুন আপডেট উপলব্ধ।',
            'version' => $latestVersion,
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'download_url' => url('/api/download-update'),
            'release_notes' => $update->release_notes ? $update->release_notes : '',
        ]);
    }
}
