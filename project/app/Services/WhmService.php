<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhmService
{
    public static function normalizeHostname(?string $hostname): string
    {
        return preg_replace('#^https?://|/$#', '', trim((string) $hostname));
    }

    public static function normalizeApiToken($server): string
    {
        $token = $server->api_token ?? $server->access_hash ?? '';

        return str_replace(["\r", "\n", " "], '', trim((string) $token));
    }

    public static function apiPort($server): int
    {
        return (int) ($server->port ?? 2087);
    }

    public static function apiHeaders($server): array
    {
        return [
            'Authorization' => 'whm ' . trim($server->username) . ':' . self::normalizeApiToken($server),
        ];
    }

    /**
     * WHM / cPanel one-click SSO session তৈরি
     */
    public static function createUserSession($server, string $user, string $service = 'cpaneld'): array
    {
        $hostname = self::normalizeHostname($server->hostname);
        $user = strtolower(trim($user));
        $port = self::apiPort($server);
        $url = "https://{$hostname}:{$port}/json-api/create_user_session";

        try {
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->withHeaders(self::apiHeaders($server))
                ->get($url, [
                    'api.version' => 1,
                    'user'          => $user,
                    'service'       => $service,
                ]);

            $result = $response->json();

            if (!empty($result['data']['url'])) {
                return [
                    'success' => true,
                    'url'     => self::normalizeSessionUrl($result['data']['url'], $hostname),
                ];
            }

            $reason = $result['metadata']['reason']
                ?? ($result['cpanelresult']['error'] ?? 'Unknown WHM error');

            Log::warning('WHM create_user_session failed', [
                'hostname' => $hostname,
                'user'     => $user,
                'service'  => $service,
                'reason'   => $reason,
                'response' => $result,
            ]);

            return ['success' => false, 'error' => $reason];
        } catch (\Exception $e) {
            Log::error('WHM create_user_session exception', [
                'hostname' => $hostname,
                'user'     => $user,
                'service'  => $service,
                'message'  => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => 'Connection Error: ' . $e->getMessage()];
        }
    }

    protected static function normalizeSessionUrl(string $url, string $hostname): string
    {
        $parts = parse_url($url);
        if (!$parts || empty($parts['host'])) {
            return $url;
        }

        if ($parts['host'] !== $hostname) {
            $url = str_replace($parts['host'], $hostname, $url);
        }

        return $url;
    }

    /**
     * cPanel একাউন্ট তৈরি করার ফাংশন
     */
    public static function createAccount($server, $data)
    {
        $hostname = self::normalizeHostname($server->hostname);
        $port = self::apiPort($server);
        $url = "https://{$hostname}:{$port}/json-api/createacct";

        $params = [
            'api.version' => 1,
            'username'    => strtolower(trim($data['username'])),
            'domain'      => $data['domain'],
            'plan'        => $data['plan_name'],
            'contactemail'=> $data['email'],
            'password'    => $data['password'],
            'language'    => 'en',
            'useregns'    => 0,
            'reseller'    => 0,
        ];

        try {
            $response = Http::timeout(120)
                ->withoutVerifying()
                ->withHeaders(self::apiHeaders($server))
                ->get($url, $params);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('WHM Account Create Failed: ' . $e->getMessage());

            return ['metadata' => ['result' => 0, 'reason' => 'Connection Error: ' . $e->getMessage()]];
        }
    }

    /**
     * একাউন্ট সাসপেন্ড করার ফাংশন
     */
    public static function suspendAccount($server, $username, $reason = 'Overdue Payment')
    {
        $hostname = self::normalizeHostname($server->hostname);
        $port = self::apiPort($server);
        $url = "https://{$hostname}:{$port}/json-api/suspendacct";

        try {
            $response = Http::timeout(60)
                ->withoutVerifying()
                ->withHeaders(self::apiHeaders($server))
                ->get($url, [
                    'api.version' => 1,
                    'user'        => strtolower(trim($username)),
                    'reason'      => $reason,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('WHM Suspend Failed: ' . $e->getMessage());

            return ['metadata' => ['result' => 0, 'reason' => $e->getMessage()]];
        }
    }

    public static function getAccountStats($server, $username)
    {
        $hostname = self::normalizeHostname($server->hostname);
        $port = self::apiPort($server);
        $url = "https://{$hostname}:{$port}/json-api/listaccts";

        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->withHeaders(self::apiHeaders($server))
                ->get($url, [
                    'api.version' => 1,
                    'search'      => strtolower(trim($username)),
                    'searchtype'  => 'user',
                ]);

            $data = $response->json();

            if (isset($data['data']['acct'][0])) {
                return $data['data']['acct'][0];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * একাউন্ট আন-সাসপেন্ড করার ফাংশন
     */
    public static function unsuspendAccount($server, $username)
    {
        $hostname = self::normalizeHostname($server->hostname);
        $port = self::apiPort($server);
        $url = "https://{$hostname}:{$port}/json-api/unsuspendacct";

        try {
            $response = Http::timeout(60)
                ->withoutVerifying()
                ->withHeaders(self::apiHeaders($server))
                ->get($url, [
                    'api.version' => 1,
                    'user'        => strtolower(trim($username)),
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('WHM Unsuspend Failed: ' . $e->getMessage());

            return ['metadata' => ['result' => 0, 'reason' => $e->getMessage()]];
        }
    }
}
