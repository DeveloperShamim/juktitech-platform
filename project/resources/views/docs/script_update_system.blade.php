<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Script Update System | Creative Design</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
:root{--primary:#4f46e5;--bg:#f8fafc;--card:#fff;--border:#e2e8f0;--text:#1e293b;--muted:#64748b;--code:#0f172a}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.7}
.wrap{max-width:900px;margin:0 auto;padding:32px 24px 60px}
h1{font-size:26px;margin-bottom:8px}
.lead{color:var(--muted);margin-bottom:28px;font-size:15px}
.card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:22px 24px;margin-bottom:20px}
.card h2{font-size:18px;margin-bottom:12px;display:flex;align-items:center;gap:10px}
.card h2 i{color:var(--primary)}
.endpoint{background:#eef2ff;border-radius:8px;padding:10px 14px;font-family:monospace;font-size:13px;margin:12px 0;word-break:break-all}
table{width:100%;border-collapse:collapse;font-size:14px;margin:12px 0}
th,td{border:1px solid var(--border);padding:10px 12px;text-align:left}
th{background:#f1f5f9}
pre{background:var(--code);color:#e2e8f0;padding:16px;border-radius:10px;overflow-x:auto;font-size:12px;line-height:1.6}
.badge{display:inline-block;background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.nav-top{margin-bottom:24px}
.nav-top a{color:var(--primary);text-decoration:none;font-size:14px}
.warn{background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 16px;font-size:14px;margin-top:12px}
</style>
</head>
<body>
<div class="wrap">
    <p class="nav-top"><a href="{{ route('docs.license_security_api') }}"><i class="fas fa-arrow-left"></i> License Security API</a></p>
    <h1><i class="fas fa-sync-alt" style="color:var(--primary)"></i> Script Update System</h1>
    <p class="lead">লাইসেন্স সার্ভার: <strong>{{ $serverUrl }}</strong> — শুধু বৈধ <code>LICENSE_KEY</code> + <code>LICENSE_SIGNATURE</code> যুক্ত সাইট আপডেট পাবে।</p>

    <div class="card">
        <h2><i class="fas fa-cloud-upload-alt"></i> Admin — রিলিজ করুন</h2>
        <ol style="padding-left:20px;color:var(--muted)">
            <li>Admin → <strong>Script Update Files</strong> (<code>/admin/script-updates</code>)</li>
            <li><strong>Script Name</strong> — ক্লায়েন্টের <code>SCRIPT_NAME</code> এর সাথে হুবহু (যেমন <code>Ecommerce Pro</code>)</li>
            <li><strong>Version</strong> — <code>1.0.1</code>, <code>2.0.0</code> ইত্যাদি</li>
            <li>ZIP আপলোড (max 1GB) — <code>php artisan storage:link</code> চালু থাকতে হবে</li>
        </ol>
    </div>

    <div class="card">
        <h2><i class="fas fa-search"></i> POST /api/check-update</h2>
        <div class="endpoint">POST {{ $serverUrl }}/api/check-update</div>
        <table>
            <thead><tr><th>ফিল্ড</th><th>বাধ্যতা</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td><span class="badge">Required</span></td><td>ক্লায়েন্ট ডোমেইন</td></tr>
                <tr><td><code>license_key</code></td><td><span class="badge">Required</span></td><td><code>LICENSE_KEY</code></td></tr>
                <tr><td><code>signature</code></td><td><span class="badge">Required</span></td><td><code>LICENSE_SIGNATURE</code></td></tr>
                <tr><td><code>script_name</code></td><td><span class="badge">Required</span></td><td>Admin script name</td></tr>
                <tr><td><code>current_version</code></td><td>Optional</td><td>Default 1.0.0</td></tr>
            </tbody>
        </table>
        <div class="warn"><strong>403</strong> — কী বা সিগনেচার মিললে না, লাইসেন্স inactive, বা মেয়াদ শেষ।</div>
    </div>

    <div class="card">
        <h2><i class="fas fa-download"></i> POST /api/download-update</h2>
        <div class="endpoint">POST {{ $serverUrl }}/api/download-update</div>
        <p style="color:var(--muted);font-size:14px">check-update এর মতোই credentials। সফল হলে ZIP ফাইল স্ট্রিম। পাবলিক <code>storage/</code> URL আর ব্যবহার হয় না।</p>
    </div>

    <div class="card">
        <h2><i class="fas fa-code"></i> ক্লায়েন্ট উদাহরণ (PHP)</h2>
<pre>$ch = curl_init('{{ $serverUrl }}/api/check-update');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'domain'          => 'example.com',
        'license_key'     => getenv('LICENSE_KEY'),
        'signature'       => getenv('LICENSE_SIGNATURE'),
        'script_name'     => 'Ecommerce Pro',
        'current_version' => '1.0.0',
    ]),
]);
$result = json_decode(curl_exec($ch), true);</pre>
        <p style="margin-top:12px;font-size:14px;color:var(--muted)">রেডি ক্লায়েন্ট: <code>{{ $serverUrl }}/script-update-client.php</code> (CLI: <code>php script-update-client.php check SERVER_URL</code>)</p>
    </div>

    <div class="card">
        <h2><i class="fas fa-book"></i> Markdown সোর্স</h2>
        <p style="color:var(--muted);font-size:14px"><code>docs/SCRIPT_UPDATE_SYSTEM.md</code> — রিপোজিটরিতে সম্পূর্ণ গাইড।</p>
    </div>
</div>
</body>
</html>
