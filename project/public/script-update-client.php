<?php
/**
 * Script Update Client — Creative Design License System
 *
 * ক্লায়েন্ট সাইটে রাখুন; LICENSE_KEY ও LICENSE_SIGNATURE .env থেকে পড়ে
 * লাইসেন্স সার্ভারে আপডেট চেক/ডাউনলোড করে।
 *
 * ব্যবহার (CLI):
 *   php script-update-client.php check
 *   php script-update-client.php download /path/to/save.zip
 *
 * ব্যবহার (include):
 *   require 'script-update-client.php';
 *   $info = script_update_check('https://license-server.com', 'Ecommerce Pro', '1.0.0');
 */

// ——— কনফিগ (.env থেকে পড়ুন বা এখানে সেট করুন) ———
function script_update_load_env(): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $paths = [
        dirname(__DIR__) . '/.env',
        __DIR__ . '/../.env',
    ];
    foreach ($paths as $path) {
        if (!is_readable($path)) {
            continue;
        }
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (strpos($line, '=') === false) {
                continue;
            }
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v, " \t\"'");
            if ($k !== '' && getenv($k) === false) {
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
        break;
    }
    $loaded = true;
}

function script_update_domain(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
    $host = strtolower(preg_replace('#^www\.#i', '', $host));

    return $host;
}

/**
 * @return array<string, mixed>|null
 */
function script_update_check(string $serverBase, string $scriptName, string $currentVersion = '1.0.0'): ?array
{
    script_update_load_env();

    $domain = script_update_domain();
    if ($domain === '' && getenv('LICENSE_GUARD_DOMAIN')) {
        $domain = strtolower(preg_replace('#^www\.#i', '', (string) getenv('LICENSE_GUARD_DOMAIN')));
    }

    $licenseKey = trim((string) (getenv('LICENSE_KEY') ?: ''));
    $signature  = trim((string) (getenv('LICENSE_SIGNATURE') ?: ''));

    if ($domain === '' || $licenseKey === '' || $signature === '') {
        return ['status' => 'invalid', 'message' => 'LICENSE_KEY, LICENSE_SIGNATURE বা domain নেই।'];
    }

    $url = rtrim($serverBase, '/') . '/api/check-update';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'domain'          => $domain,
            'license_key'     => $licenseKey,
            'signature'       => $signature,
            'script_name'     => $scriptName,
            'current_version' => $currentVersion,
        ]),
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode((string) $body, true);
    if (!is_array($data)) {
        return ['status' => 'error', 'message' => 'Invalid server response', 'http_code' => $code];
    }
    $data['http_code'] = $code;

    return $data;
}

/**
 * @return array{ok: bool, message: string, path?: string}
 */
function script_update_download(string $serverBase, string $scriptName, string $savePath, ?string $version = null): array
{
    script_update_load_env();

    $domain = script_update_domain();
    if ($domain === '' && getenv('LICENSE_GUARD_DOMAIN')) {
        $domain = strtolower(preg_replace('#^www\.#i', '', (string) getenv('LICENSE_GUARD_DOMAIN')));
    }

    $licenseKey = trim((string) (getenv('LICENSE_KEY') ?: ''));
    $signature  = trim((string) (getenv('LICENSE_SIGNATURE') ?: ''));

    if ($domain === '' || $licenseKey === '' || $signature === '') {
        return ['ok' => false, 'message' => 'LICENSE_KEY, LICENSE_SIGNATURE বা domain নেই।'];
    }

    $post = [
        'domain'      => $domain,
        'license_key' => $licenseKey,
        'signature'   => $signature,
        'script_name' => $scriptName,
    ];
    if ($version !== null && $version !== '') {
        $post['version'] = $version;
    }

    $url = rtrim($serverBase, '/') . '/api/download-update';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($post),
        CURLOPT_TIMEOUT        => 600,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($code !== 200 || stripos($type, 'zip') === false && stripos($type, 'octet') === false) {
        $err = json_decode((string) $body, true);

        return [
            'ok'      => false,
            'message' => is_array($err) ? ($err['message'] ?? 'Download failed') : 'Download failed (HTTP ' . $code . ')',
        ];
    }

    if (file_put_contents($savePath, $body) === false) {
        return ['ok' => false, 'message' => 'ফাইল সেভ করা যায়নি: ' . $savePath];
    }

    return ['ok' => true, 'message' => 'Saved', 'path' => $savePath];
}

// ——— CLI ———
if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $cmd    = $argv[1] ?? 'check';
    $server = $argv[2] ?? (getenv('LICENSE_REMOTE_BASE_URL') ?: '');
    $script = $argv[3] ?? (getenv('SCRIPT_NAME') ?: 'Ecommerce Pro');
    $ver    = $argv[4] ?? (getenv('APP_SCRIPT_VERSION') ?: '1.0.0');

    if ($server === '') {
        fwrite(STDERR, "Usage: php script-update-client.php check SERVER_URL [SCRIPT_NAME] [CURRENT_VERSION]\n");
        fwrite(STDERR, "       php script-update-client.php download SERVER_URL SAVE.zip [SCRIPT_NAME]\n");
        exit(1);
    }

    if ($cmd === 'download') {
        $save = $argv[3] ?? 'update.zip';
        $name = $argv[4] ?? (getenv('SCRIPT_NAME') ?: 'Ecommerce Pro');
        $r    = script_update_download($server, $name, $save);
        fwrite(STDOUT, json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
        exit($r['ok'] ? 0 : 1);
    }

    $r = script_update_check($server, $script, $ver);
    fwrite(STDOUT, json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
    exit(($r['status'] ?? '') === 'invalid' ? 1 : 0);
}
