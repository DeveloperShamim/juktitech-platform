<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\StolenDomain;
use App\Models\UnauthorizedLog;
use App\Models\User;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    /** HMAC salt — LicenseService::HMAC_SALT এর সাথে মিল রাখুন */
    private const HMAC_SALT = 'your_secret_salt_key';

    private const TOKEN_SALT = 'YOUR_SECRET_SALT_HERE_12345';

    public function __construct()
   {
    $this->middleware('auth:admin')->except(['verifyLicense', 'fetchLicense']);
}

    // =========================================================
    // 1. License List (Datatables)
    // =========================================================
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = License::with('user')->orderBy('id', 'desc')->get();

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('client_name', function(License $data) {
                    return '<div class="client-info"><b>'.($data->user ? $data->user->name : 'N/A').'</b><small>'.($data->user ? $data->user->email : '').'</small></div>';
                })
                ->addColumn('domain_info', function(License $data) {
                    return '<div>
                                <span class="font-weight-bold text-dark d-block">' . $data->domain_name . '</span>
                                <a href="http://' . $data->domain_name . '" target="_blank" class="btn-visit-link">
                                    <i class="fas fa-external-link-alt"></i> Visit Site
                                </a>
                            </div>';
                })
                ->addColumn('expiry', function(License $data) {
                    if ($data->expiry_date === null) {
                        return '<span class="badge-lifetime"><i class="fas fa-infinity mr-1"></i>Lifetime</span>';
                    }
                    $date = Carbon::parse($data->expiry_date);
                    return '<span class="text-dark">' . $date->format('d M, Y') . '</span><br><small class="text-muted">(' . $date->diffForHumans() . ')</small>';
                })
                ->editColumn('status', function(License $data) {
                    $class = $data->status == 1 ? 'badge-active' : 'badge-expired';
                    $text  = $data->status == 1 ? 'Active' : 'Expired';
                    return '<span class="' . $class . '">' . $text . '</span>';
                })
                ->addColumn('signature_info', function(License $data) {
                    $sig = $data->signature_key ?? '—';
                    $short = strlen($sig) > 16 ? substr($sig, 0, 16) . '...' : $sig;
                    return '<span class="sig-short" title="' . $sig . '" data-full="' . $sig . '"
                                style="font-family:monospace;font-size:12px;cursor:pointer;color:#6366f1;"
                                onclick="copySignature(this)">' . $short . '
                            </span>';
                })
                ->addColumn('action', function(License $data) {
                    return '<div class="action-btn-group">
                                <button type="button" data-href="' . route('admin.license.edit', $data->id) . '" class="btn-modern btn-edit edit-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" data-href="' . route('admin.license.delete', $data->id) . '" class="btn-modern btn-delete delete-btn" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['client_name', 'domain_info', 'expiry', 'status', 'signature_info', 'action'])
                ->make(true);
        }

        $users = User::orderBy('name', 'asc')->get();
        return view('admin.license.index', compact('users'));
    }

    // =========================================================
    // 2. Store new license
    // =========================================================
    public function store(Request $request)
    {
        $normalizedDomain = License::normalizeHostInput($request->domain_name);
        if (!$normalizedDomain) {
            return back()->withErrors(['domain_name' => 'Domain is not valid.']);
        }

        $request->merge(['domain_name' => $normalizedDomain]);
        $request->validate([
            'user_id'     => 'required',
            'domain_name' => 'required|unique:licenses,domain_name',
            'validity'    => 'required|integer|in:0,1,2,3',
            'site_path'   => 'nullable|string|max:500',
        ]);

        $licenseKey   = LicenseService::generateLicenseKey();
        $signatureKey = LicenseService::generateSignature($licenseKey, $normalizedDomain);
        $sitePath     = trim($request->site_path ?? '');

        $license                = new License();
        $license->user_id       = $request->user_id;
        $license->domain_name   = $normalizedDomain;
        $license->license_key   = $licenseKey;
        $license->signature_key = $signatureKey;
        $license->site_path     = $sitePath ?: null;
        $license->expiry_date   = LicenseService::expiryDate((int) $request->validity);
        $license->status        = 1;
        $license->save();

        // ১. Same-server path দিলে সরাসরি .env লেখো
        $envMsg = '';
        if ($sitePath !== '') {
            $result = LicenseService::writeToEnv($sitePath, $licenseKey, $signatureKey);
            $envMsg = $result['success']
                ? ' .env ফাইল (local) আপডেট হয়েছে।'
                : ' ⚠️ Local .env ব্যর্থ: ' . $result['message'];
            Log::info('Admin license .env write', ['license_id' => $license->id, 'result' => $result]);
        }

        // ২. Client site এ HTTP push করো (path না থাকলেও চলবে)
        $pushResult = LicenseService::pushToClientSite(
            $normalizedDomain,
            $licenseKey,
            $signatureKey,
            $license->expiry_date ? $license->expiry_date->toDateString() : null
        );
        $pushMsg = $pushResult['success']
            ? ' Client site এ push সফল ✅'
            : ' ℹ️ Push (client site): ' . $pushResult['message'];
        Log::info('Admin license push', ['license_id' => $license->id, 'result' => $pushResult]);

        return back()->with('success', 'নতুন লাইসেন্স কী তৈরি হয়েছে!' . $envMsg . $pushMsg);
    }

    // =========================================================
    // 3. Edit (Ajax)
    // =========================================================
    public function edit($id)
    {
        $data = License::with('user')->findOrFail($id);
        return response()->json($data);
    }

    // =========================================================
    // 4. Update license
    // =========================================================
    public function update(Request $request, $id)
    {
        $normalizedDomain = License::normalizeHostInput($request->domain_name);
        if (!$normalizedDomain) {
            return response()->json(['errors' => ['domain_name' => ['Domain is not valid.']]], 422);
        }

        $request->merge(['domain_name' => $normalizedDomain]);
        $rules = [
            'user_id'     => 'required',
            'domain_name' => 'required|unique:licenses,domain_name,' . $id,
            'status'      => 'required',
            'site_path'   => 'nullable|string|max:500',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $license              = License::findOrFail($id);
        $license->user_id     = $request->user_id;
        $license->domain_name = $normalizedDomain;
        $license->status      = $request->status;
        $license->site_path   = trim($request->site_path ?? '') ?: null;
        $license->save();

        // site_path দিলে local .env লেখো
        $envMsg = '';
        if ($license->site_path && $license->license_key && $license->signature_key) {
            $result = LicenseService::writeToEnv($license->site_path, $license->license_key, $license->signature_key);
            $envMsg = $result['success'] ? ' Local .env আপডেট সফল।' : ' Local .env ব্যর্থ: ' . $result['message'];
        }

        // Client site এ push
        if ($license->license_key && $license->signature_key) {
            $pushResult = LicenseService::pushToClientSite(
                $license->domain_name,
                $license->license_key,
                $license->signature_key,
                $license->expiry_date ? $license->expiry_date->toDateString() : null
            );
            $envMsg .= $pushResult['success'] ? ' Push সফল ✅' : ' Push ব্যর্থ: ' . $pushResult['message'];
        }

        return response()->json(['status' => 'success', 'message' => 'License updated successfully.' . $envMsg]);
    }

    // =========================================================
    // 5. Manual Push — যেকোনো সময় client site এ push করো
    // =========================================================
    public function pushToSite($id)
    {
        $license = License::findOrFail($id);

        if (!$license->license_key || !$license->signature_key) {
            return response()->json(['status' => 'error', 'message' => 'License key বা Signature key নেই।']);
        }

        // Local .env (যদি path থাকে)
        $msgs = [];
        if ($license->site_path) {
            $r = LicenseService::writeToEnv($license->site_path, $license->license_key, $license->signature_key);
            $msgs[] = 'Local .env: ' . ($r['success'] ? '✅ সফল' : '❌ ' . $r['message']);
        }

        // HTTP Push
        $push = LicenseService::pushToClientSite(
            $license->domain_name,
            $license->license_key,
            $license->signature_key,
            $license->expiry_date ? $license->expiry_date->toDateString() : null
        );
        $msgs[] = 'HTTP Push: ' . ($push['success'] ? '✅ সফল' : '❌ ' . $push['message']);

        $allOk = $push['success'] || ($license->site_path && isset($r) && $r['success']);

        return response()->json([
            'status'  => $allOk ? 'success' : 'warning',
            'message' => implode(' | ', $msgs),
        ]);
    }

    // =========================================================
    // Setup Guide Page
    // =========================================================
    public function setupGuide()
    {
        $licenseServerUrl = url('/');
        $hmacSalt         = self::HMAC_SALT;
        return view('admin.license.setup-guide', compact('licenseServerUrl', 'hmacSalt'));
    }

    // =========================================================
    // Download receiver script (customized with correct HMAC_SALT)
    // =========================================================
    public function downloadReceiver()
    {
        $salt = self::HMAC_SALT;
        $host = $_SERVER['HTTP_HOST'] ?? 'license-server';

        $content = <<<PHP
<?php
/**
 * License Receiver — Creative Design License System
 * Master server এই file-এ POST করে LICENSE_KEY ও LICENSE_SIGNATURE .env-এ save করে।
 *
 * Security: push_token verify করা হয় (HMAC SHA-256)
 * Usage: https://yourclientdomain.com/license-receiver.php
 *
 * Generated by: {$host}
 */

if (\$_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

define('HMAC_SALT', '{$salt}');

\$input = json_decode(file_get_contents('php://input'), true);
if (empty(\$input)) {
    \$input = \$_POST;
}

\$licenseKey   = trim(\$input['license_key']   ?? '');
\$signatureKey = trim(\$input['signature_key'] ?? '');
\$expiryDate   = trim(\$input['expiry_date']   ?? '');
\$pushToken    = trim(\$input['push_token']    ?? '');

if (empty(\$licenseKey) || empty(\$pushToken)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

\$domain        = strtolower(preg_replace('#^www\\.#i', '', \$_SERVER['HTTP_HOST'] ?? ''));
\$expectedToken = hash_hmac('sha256', \$domain . '|' . \$licenseKey, HMAC_SALT);

if (!hash_equals(\$expectedToken, \$pushToken)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
    exit;
}

\$envPath = null;
\$searchPaths = [
    dirname(__DIR__) . '/.env',
    dirname(dirname(__DIR__)) . '/.env',
    __DIR__ . '/../.env',
];
foreach (\$searchPaths as \$path) {
    if (file_exists(\$path) && is_writable(\$path)) {
        \$envPath = \$path;
        break;
    }
}

if (!\$envPath) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => '.env file not found or not writable.']);
    exit;
}

function updateEnvValue(string \$envPath, string \$key, string \$value): void
{
    \$content = file_get_contents(\$envPath);
    \$line    = \$key . '=' . \$value;
    if (preg_match('/^' . preg_quote(\$key, '/') . '=/m', \$content)) {
        \$content = preg_replace('/^' . preg_quote(\$key, '/') . '=.*/m', \$line, \$content);
    } else {
        \$content = rtrim(\$content) . "\\n" . \$line . "\\n";
    }
    file_put_contents(\$envPath, \$content);
}

updateEnvValue(\$envPath, 'LICENSE_KEY',       \$licenseKey);
updateEnvValue(\$envPath, 'LICENSE_SIGNATURE', \$signatureKey);

if (!empty(\$expiryDate) && \$expiryDate !== 'lifetime') {
    updateEnvValue(\$envPath, 'LICENSE_EXPIRY', \$expiryDate);
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}

\$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
if (is_dir(\$bootstrapCache)) {
    foreach (['config.php', 'routes-v7.php', 'services.php', 'packages.php'] as \$cacheFile) {
        \$filePath = \$bootstrapCache . '/' . \$cacheFile;
        if (file_exists(\$filePath)) {
            @unlink(\$filePath);
        }
    }
}

http_response_code(200);
echo json_encode([
    'status'  => 'ok',
    'message' => 'License saved successfully.',
    'domain'  => \$domain,
]);
PHP;

        return response($content, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="license-receiver.php"',
        ]);
    }

    // =========================================================
    // Fetch License API (client auto-sync)
    // GET /api/fetch-license?domain=xxx&token=yyy
    //
    // Client script নিজে .env এ LICENSE_KEY না থাকলে এটা call করে।
    // Token = hash_hmac('sha256', domain + '|' + hour_block, HMAC_SALT)
    // =========================================================
    public function fetchLicense(Request $request): \Illuminate\Http\JsonResponse
    {
        $domain = License::normalizeHostInput($request->query('domain', ''));
        $token  = trim((string) $request->query('token', ''));

        if (!$domain || !$token) {
            return response()->json(['status' => 'error', 'message' => 'domain and token required.'], 400);
        }

        // Token verify — 1 ঘন্টার window (current + previous hour accept করে)
        $currentHour  = date('YmdH');
        $previousHour = date('YmdH', strtotime('-1 hour'));
        $validTokens  = [
            hash_hmac('sha256', $domain . '|' . $currentHour,  self::HMAC_SALT),
            hash_hmac('sha256', $domain . '|' . $previousHour, self::HMAC_SALT),
        ];

        $isValid = false;
        foreach ($validTokens as $vt) {
            if (hash_equals($vt, $token)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            return response()->json(['status' => 'error', 'message' => 'Invalid token.'], 403);
        }

        // License DB তে আছে কিনা
        $license = License::where('domain_name', $domain)->where('status', 1)->first();

        if (!$license || !$license->license_key || !$license->signature_key) {
            return response()->json(['status' => 'not_found', 'message' => 'No active license for this domain.'], 404);
        }

        // Lifetime নয়, expired কিনা চেক
        if ($license->expiry_date !== null && \Carbon\Carbon::parse($license->expiry_date)->isPast()) {
            return response()->json(['status' => 'expired', 'message' => 'License has expired.'], 403);
        }

        return response()->json([
            'status'        => 'ok',
            'license_key'   => $license->license_key,
            'signature_key' => $license->signature_key,
            'expiry_date'   => $license->expiry_date ? $license->expiry_date->toDateString() : 'lifetime',
        ]);
    }

    // =========================================================
    // 6. Delete license
    // =========================================================
    public function delete($id)
    {
        $license = License::findOrFail($id);
        $license->delete();

        return response()->json(['status' => 'success', 'message' => 'License deleted successfully']);
    }

    // =========================================================
    // 6. Public verify-license API
    // =========================================================
    public function verifyLicense(Request $request)
    {
        $domain     = License::normalizeHostInput($request->domain);
        $licenseKey = trim((string) $request->license_key);

        if (!$domain || !$licenseKey) {
            return response()->json([
                'status'  => 'invalid',
                'blocked' => false,
                'message' => 'Domain and license key are required.',
                'action'  => UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none',
            ]);
        }

        if ($request->filled('signature')) {
            return $this->verifyLicenseWithHmac($request, $domain, $licenseKey);
        }

        // ---- Legacy path ----
        $license = License::findForApiVerification($domain, $licenseKey);

        if ($license) {
            // Lifetime লাইসেন্স বা মেয়াদ শেষ হয়নি
            $isExpired = $license->expiry_date !== null && Carbon::parse($license->expiry_date)->isPast();

            if (License::isActiveForApi($license) && !$isExpired) {
                try {
                    StolenDomain::where('domain_name', $domain)->update(['is_blocked' => 0]);
                } catch (\Throwable $e) {}

                $cacheSignal = $this->getCacheSignal($domain);
                $secureToken = md5($domain . self::TOKEN_SALT);
                $action      = UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none';

                return response()->json([
                    'status'        => 'valid',
                    'blocked'       => false,
                    'domain_name'   => $license->domain_name,
                    'license_key'   => $license->license_key,
                    'expiry_date'   => $license->expiry_date ?? 'lifetime',
                    'secure_token'  => $secureToken,
                    'cache_signal'  => $cacheSignal,
                    'action'        => $action,
                ]);
            }
        }

        try {
            StolenDomain::updateOrCreate(
                ['domain_name' => $domain],
                [
                    'ip_address'   => $request->ip(),
                    'full_url'     => $request->input('url', 'http://' . $domain),
                    'status'       => 0,
                    'is_blocked'   => 1,
                    'cache_signal' => Str::random(32),
                ]
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'status'  => 'invalid',
            'blocked' => true,
            'message' => 'License key or domain is incorrect or has expired.',
            'action'  => UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none',
        ]);
    }

    // =========================================================
    // HMAC verification path
    // =========================================================
    private function verifyLicenseWithHmac(Request $request, string $domain, string $licenseKey): \Illuminate\Http\JsonResponse
    {
        $signature         = (string) $request->input('signature');
        $expectedSignature = hash_hmac('sha256', $domain, self::HMAC_SALT);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Signature verification failed.',
                'action'  => UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none',
            ], 403);
        }

        $license = License::findForApiVerification($domain, $licenseKey);

        if (!$license || !License::isActiveForApi($license)) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'License not found or inactive.',
                'action'  => UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none',
            ], 403);
        }

        // Lifetime লাইসেন্স কখনো expire হয় না
        $isExpired = $license->expiry_date !== null && Carbon::parse($license->expiry_date)->isPast();
        if ($isExpired) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'License has expired.',
                'action'  => UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none',
            ], 403);
        }

        try {
            StolenDomain::where('domain_name', $domain)->update(['is_blocked' => 0]);
        } catch (\Throwable $e) {}

        $cacheSignal = $this->getCacheSignal($domain);
        $secureToken = md5($domain . self::TOKEN_SALT);
        $action      = UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none';

        return response()->json([
            'status'        => 'valid',
            'blocked'       => false,
            'domain_name'   => $license->domain_name,
            'license_key'   => $license->license_key,
            'expiry_date'   => $license->expiry_date ?? 'lifetime',
            'secure_token'  => $secureToken,
            'cache_signal'  => $cacheSignal,
            'action'        => $action,
        ]);
    }

    private function getCacheSignal(string $domain): ?string
    {
        try {
            $row = StolenDomain::where('domain_name', $domain)->first();
            return $row ? $row->cache_signal : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
