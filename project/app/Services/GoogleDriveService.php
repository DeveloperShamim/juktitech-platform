<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleDriveService
{
    private ?array $credentials = null;

    public function isConfigured(): bool
    {
        $path = config('digital_product.google_drive.service_account_path');
        return is_string($path) && is_readable($path);
    }

    public function extractFileId(string $input): ?string
    {
        $input = trim($input);
        if ($input === '') {
            return null;
        }

        if (preg_match('#^[\w-]{10,}$#', $input) && !str_contains($input, '/')) {
            return $input;
        }

        if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $input, $m)) {
            return $m[1];
        }

        if (preg_match('#[?&]id=([a-zA-Z0-9_-]+)#', $input, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Stream file from Google Drive without loading entire body into memory.
     */
    public function streamFileResponse(string $fileId, ?string $downloadName = null): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $token = $this->accessToken();
        $metaResponse = Http::withToken($token)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'fields' => 'name,mimeType,size',
                'supportsAllDrives' => 'true',
            ]);

        if (!$metaResponse->successful()) {
            throw new RuntimeException('Google Drive file not found or not shared with service account.');
        }

        $meta = $metaResponse->json();
        $driveName = $meta['name'] ?? 'download.zip';
        $filename = $this->resolveDownloadFilename($downloadName, $driveName);
        $mime = $this->resolveMimeType($meta['mimeType'] ?? null, $filename);

        return response()->streamDownload(function () use ($token, $fileId) {
            $downloadResponse = Http::withToken($token)
                ->withOptions(['stream' => true])
                ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                    'alt' => 'media',
                    'supportsAllDrives' => 'true',
                ]);

            if (!$downloadResponse->successful()) {
                throw new RuntimeException('Google Drive download failed.');
            }

            $body = $downloadResponse->toPsrResponse()->getBody();
            while (!$body->eof()) {
                echo $body->read(8192);
                if (function_exists('flush')) {
                    flush();
                }
            }
        }, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * @return array{body: string, filename: string, mime: string}
     */
    public function downloadFile(string $fileId): array
    {
        $token = $this->accessToken();
        $metaResponse = Http::withToken($token)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'fields' => 'name,mimeType,size',
                'supportsAllDrives' => 'true',
            ]);

        if (!$metaResponse->successful()) {
            throw new RuntimeException('Google Drive file not found or not shared with service account.');
        }

        $meta = $metaResponse->json();
        $downloadResponse = Http::withToken($token)
            ->withOptions(['stream' => true])
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'alt' => 'media',
                'supportsAllDrives' => 'true',
            ]);

        if (!$downloadResponse->successful()) {
            throw new RuntimeException('Google Drive download failed.');
        }

        return [
            'body'     => $downloadResponse->body(),
            'filename' => $meta['name'] ?? 'download.zip',
            'mime'     => $meta['mimeType'] ?? 'application/octet-stream',
        ];
    }

    public function streamDownload(string $fileId): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $file = $this->downloadFile($fileId);

        return response()->streamDownload(function () use ($file) {
            echo $file['body'];
        }, $file['filename'], [
            'Content-Type' => $file['mime'],
        ]);
    }

    private function accessToken(): string
    {
        return Cache::remember('google_drive_access_token', 3300, function () {
            $creds = $this->credentials();
            $jwt = $this->buildJwt($creds);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if (!$response->successful() || empty($response->json('access_token'))) {
                throw new RuntimeException('Google Drive auth failed: ' . $response->body());
            }

            return (string) $response->json('access_token');
        });
    }

    private function credentials(): array
    {
        if ($this->credentials !== null) {
            return $this->credentials;
        }

        $path = config('digital_product.google_drive.service_account_path');
        if (!$path || !is_readable($path)) {
            throw new RuntimeException('Google Drive service account JSON is not configured.');
        }

        $json = json_decode((string) file_get_contents($path), true);
        if (!is_array($json) || empty($json['client_email']) || empty($json['private_key'])) {
            throw new RuntimeException('Invalid Google Drive service account JSON.');
        }

        return $this->credentials = $json;
    }

    private function buildJwt(array $creds): string
    {
        $header = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $claim = $this->base64Url(json_encode([
            'iss'   => $creds['client_email'],
            'scope' => 'https://www.googleapis.com/auth/drive.readonly',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));

        $input = $header . '.' . $claim;
        $key = openssl_pkey_get_private($creds['private_key']);
        if ($key === false) {
            throw new RuntimeException('Invalid private key in service account JSON.');
        }

        openssl_sign($input, $signature, $key, OPENSSL_ALGO_SHA256);

        return $input . '.' . $this->base64Url($signature);
    }

    private function resolveDownloadFilename(?string $preferred, string $driveName): string
    {
        $driveName = trim($driveName) ?: 'download.zip';
        $preferred = trim((string) $preferred);

        if ($preferred === '') {
            return $driveName;
        }

        $driveExt = pathinfo($driveName, PATHINFO_EXTENSION);
        $preferredExt = pathinfo($preferred, PATHINFO_EXTENSION);

        if ($preferredExt !== '') {
            return $preferred;
        }

        if ($driveExt !== '') {
            return $preferred . '.' . $driveExt;
        }

        return $preferred;
    }

    private function resolveMimeType(?string $driveMime, string $filename): string
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            '7z'  => 'application/x-7z-compressed',
            'pdf' => 'application/pdf',
            default => $driveMime ?: 'application/octet-stream',
        };
    }

    private function base64Url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
