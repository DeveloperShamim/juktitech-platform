<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScriptUpdate;
use App\Services\LicenseApiVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadUpdateController extends Controller
{
    /**
     * লাইসেন্স + সিগনেচার যাচাই করে আপডেট ZIP স্ট্রিম করে।
     *
     * POST /api/download-update
     * Body: domain, license_key, signature, script_name [, version]
     */
    public function download(Request $request)
    {
        $domain = (string) $request->input('domain', '');
        $licenseKey = (string) $request->input('license_key', '');
        $signature = (string) ($request->input('signature') ?: $request->input('signature_key', ''));
        $scriptName = trim((string) $request->input('script_name', ''));
        $version = trim((string) $request->input('version', ''));

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

        $query = ScriptUpdate::where('script_name', $scriptName)->where('is_active', 1);
        if ($version !== '') {
            $query->where('version', $version);
        }
        $update = $query->first();

        if (!$update) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'আপডেট ফাইল পাওয়া যায়নি।',
            ], 404);
        }

        if (!$update->file_path || !Storage::disk('public')->exists($update->file_path)) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'আপডেট ফাইল সার্ভারে নেই।',
            ], 404);
        }

        $fullPath = storage_path('app/public/' . $update->file_path);
        $fileName = $update->file_name ?: ('update_' . $update->version . '.zip');

        return response()->download($fullPath, $fileName, [
            'Content-Type' => 'application/zip',
            'X-Update-Version' => $update->version,
        ]);
    }
}
