<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StolenDomain;
use App\Models\UnauthorizedLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseSecurityApiController extends Controller
{
    /**
     * লগ অনঅথোরাইজড এক্সেস (ক্লায়েন্ট সাইট থেকে কল হয়)
     *
     * লিগ্যাসি: domain (+ ip, url) → StolenDomain। নতুন: reason + attempted_at → UnauthorizedLog।
     */
    public function logUnauthorized(Request $request)
    {
        if ($request->filled('reason') && $request->has('attempted_at')) {
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

            $domain = $this->normalizeDomainForLog($request->input('domain')) ?? trim((string) $request->input('domain'));

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

        $domainRaw = $request->input('domain');
        if ($domainRaw === null || trim((string) $domainRaw) === '') {
            return response()->json(['status' => 'error', 'message' => 'domain প্রয়োজন।'], 422);
        }

        $domainKey = $this->normalizeDomainForLog($domainRaw) ?? trim((string) $domainRaw);

        StolenDomain::updateOrCreate(
            ['domain_name' => $domainKey],
            [
                'ip_address' => $request->input('ip'),
                'full_url'   => $request->input('url'),
                'status'     => 0,
            ]
        );

        return response()->json(['status' => 'success']);
    }

    /**
     * Cache Signal API — client site প্রতি ৩০ মিনিটে একবার call করে
     *
     * GET /api/cache-signal?domain=example.com
     */
    public function cacheSignal(Request $request)
    {
        $domain = $request->input('domain');
        $action = UnauthorizedLog::killSwitchWantsWipeOut($domain) ? 'wipe_out' : 'none';

        if (!$domain) {
            return response()->json(['signal' => null, 'action' => $action]);
        }

        $record = StolenDomain::where('domain_name', $domain)->first();

        if ($record && !empty($record->is_blocked)) {
            return response()->json([
                'signal'  => $record->cache_signal ?: 'blocked',
                'blocked' => true,
                'action'  => $action,
            ]);
        }

        if (!$record || !$record->cache_signal) {
            return response()->json(['signal' => 'default', 'blocked' => false, 'action' => $action]);
        }

        return response()->json(['signal' => $record->cache_signal, 'blocked' => false, 'action' => $action]);
    }

    private function normalizeDomainForLog(?string $value): ?string
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
