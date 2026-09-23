<?php
/**
 * License Receiver — Creative Design License System
 * Master server এই file-এ POST করে LICENSE_KEY ও LICENSE_SIGNATURE .env-এ save করে।
 *
 * Security: push_token verify করা হয় (HMAC SHA-256)
 * Usage: https://yourclientdomain.com/license-receiver.php
 */

// শুধু POST method accept করা হবে
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

// Master server LicenseController::HMAC_SALT এর সাথে মিল
define('HMAC_SALT', 'your_secret_salt_key');

// JSON body পড়া
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

$licenseKey   = trim($input['license_key']   ?? '');
$signatureKey = trim($input['signature_key'] ?? '');
$expiryDate   = trim($input['expiry_date']   ?? '');
$pushToken    = trim($input['push_token']    ?? '');

if (empty($licenseKey) || empty($pushToken)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

// Domain normalize করা
$domain = strtolower(preg_replace('#^www\.#i', '', $_SERVER['HTTP_HOST'] ?? ''));

// Push token verify করা: HMAC(domain|license_key, SALT)
$expectedToken = hash_hmac('sha256', $domain . '|' . $licenseKey, HMAC_SALT);
if (!hash_equals($expectedToken, $pushToken)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
    exit;
}

// .env file খোঁজা
$envPath = null;
$searchPaths = [
    dirname(__DIR__) . '/.env',
    dirname(dirname(__DIR__)) . '/.env',
    __DIR__ . '/../.env',
];
foreach ($searchPaths as $path) {
    if (file_exists($path) && is_writable($path)) {
        $envPath = $path;
        break;
    }
}

if (!$envPath) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => '.env file not found or not writable.']);
    exit;
}

// .env-এ লেখা
function updateEnvValue(string $envPath, string $key, string $value): void
{
    $content = file_get_contents($envPath);
    $line    = $key . '=' . $value;
    if (preg_match('/^' . preg_quote($key, '/') . '=/m', $content)) {
        $content = preg_replace('/^' . preg_quote($key, '/') . '=.*/m', $line, $content);
    } else {
        $content = rtrim($content) . "\n" . $line . "\n";
    }
    file_put_contents($envPath, $content);
}

updateEnvValue($envPath, 'LICENSE_KEY',       $licenseKey);
updateEnvValue($envPath, 'LICENSE_SIGNATURE', $signatureKey);

// Lifetime হলে LICENSE_EXPIRY লেখার দরকার নেই
if (!empty($expiryDate) && $expiryDate !== 'lifetime') {
    updateEnvValue($envPath, 'LICENSE_EXPIRY', $expiryDate);
}

// OPcache clear যাতে নতুন .env কাজ করে
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Laravel bootstrap cache clear (config cache ইত্যাদি)
$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    foreach (['config.php', 'routes-v7.php', 'services.php', 'packages.php'] as $cacheFile) {
        $filePath = $bootstrapCache . '/' . $cacheFile;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
}

http_response_code(200);
echo json_encode([
    'status'  => 'ok',
    'message' => 'License saved successfully.',
    'domain'  => $domain,
]);
