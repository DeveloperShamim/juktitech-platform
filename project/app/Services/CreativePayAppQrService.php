<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreativePayAppQrService
{
    public const TTL_SECONDS = 180;

    public function generate(Admin $admin): array
    {
        $token = Str::random(48);
        $cacheKey = $this->cacheKey($token);

        Cache::put($cacheKey, [
            'admin_id' => $admin->id,
            'created_at' => now()->toIso8601String(),
        ], now()->addSeconds(self::TTL_SECONDS));

        $serverUrl = rtrim(config('app.url') ?: url('/'), '/');
        $payload = json_encode([
            'v' => 1,
            't' => $token,
            'u' => $serverUrl,
        ], JSON_UNESCAPED_SLASHES);

        return [
            'token'       => $token,
            'qr_payload'  => $payload,
            'server_url'  => $serverUrl,
            'expires_in'  => self::TTL_SECONDS,
            'expires_at'  => now()->addSeconds(self::TTL_SECONDS)->format('Y-m-d H:i:s'),
        ];
    }

    public function consume(string $token): ?Admin
    {
        $token = trim($token);
        if ($token === '') {
            return null;
        }

        $cacheKey = $this->cacheKey($token);
        $data = Cache::pull($cacheKey);
        if (!$data || empty($data['admin_id'])) {
            return null;
        }

        $admin = Admin::query()
            ->where('id', (int) $data['admin_id'])
            ->where('role_id', 1)
            ->where('verify', 1)
            ->first();

        return $admin;
    }

    protected function cacheKey(string $token): string
    {
        return 'creativepay_app_qr:' . $token;
    }
}
