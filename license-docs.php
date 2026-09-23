<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>License System — Documentation</title>
<style>
  :root {
    --primary: #4f46e5;
    --primary-dark: #3730a3;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
    --bg: #f8fafc;
    --sidebar-bg: #1e1b4b;
    --text: #1e293b;
    --muted: #64748b;
    --border: #e2e8f0;
    --code-bg: #0f172a;
    --card-bg: #ffffff;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  /* Sidebar */
  .sidebar {
    width: 280px; min-height: 100vh; background: var(--sidebar-bg);
    position: fixed; top: 0; left: 0; overflow-y: auto; z-index: 100;
    padding-bottom: 40px;
  }
  .sidebar-logo {
    padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
    color: #fff; font-size: 15px; font-weight: 700; letter-spacing: .5px;
  }
  .sidebar-logo span { display: block; font-size: 11px; font-weight: 400; color: #a5b4fc; margin-top: 4px; }
  .sidebar nav { padding: 12px 0; }
  .sidebar nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; color: #c7d2fe; text-decoration: none;
    font-size: 13.5px; transition: all .2s; border-left: 3px solid transparent;
  }
  .sidebar nav a:hover, .sidebar nav a.active {
    background: rgba(255,255,255,0.07); color: #fff; border-left-color: #818cf8;
  }
  .sidebar nav .group-label {
    padding: 16px 20px 6px; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1.5px; color: #6366f1;
  }

  /* Main */
  .main { margin-left: 280px; flex: 1; padding: 40px; max-width: 960px; }

  /* Sections */
  section { margin-bottom: 60px; scroll-margin-top: 20px; }
  .section-header {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 28px; padding-bottom: 16px; border-bottom: 2px solid var(--border);
  }
  .section-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .section-header h2 { font-size: 22px; font-weight: 700; color: var(--text); }
  .section-header p { font-size: 13px; color: var(--muted); margin-top: 2px; }

  /* Cards */
  .card {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 12px; padding: 24px; margin-bottom: 20px;
  }
  .card h3 { font-size: 15px; font-weight: 600; margin-bottom: 12px; }

  /* Tables */
  .table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid var(--border); }
  table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
  thead th {
    background: #f1f5f9; padding: 11px 16px; text-align: left;
    font-weight: 600; color: var(--muted); font-size: 12px; text-transform: uppercase;
    letter-spacing: .5px;
  }
  tbody td { padding: 11px 16px; border-top: 1px solid var(--border); vertical-align: top; }
  tbody tr:hover { background: #f8fafc; }
  tbody tr:last-child td { border-bottom: none; }

  /* Code */
  pre {
    background: var(--code-bg); color: #e2e8f0; border-radius: 10px;
    padding: 20px; overflow-x: auto; font-size: 13px; line-height: 1.7;
    margin: 14px 0; font-family: 'Cascadia Code', 'Fira Code', Consolas, monospace;
  }
  code {
    background: #e0e7ff; color: #3730a3; padding: 2px 7px;
    border-radius: 5px; font-size: 13px; font-family: Consolas, monospace;
  }
  pre code { background: none; color: inherit; padding: 0; }

  /* Badges */
  .badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
  }
  .badge-green  { background: #d1fae5; color: #065f46; }
  .badge-red    { background: #fee2e2; color: #991b1b; }
  .badge-blue   { background: #dbeafe; color: #1e40af; }
  .badge-yellow { background: #fef3c7; color: #92400e; }
  .badge-purple { background: #ede9fe; color: #5b21b6; }

  /* Alerts */
  .alert {
    border-radius: 10px; padding: 14px 18px; margin: 16px 0;
    font-size: 13.5px; display: flex; gap: 12px; align-items: flex-start;
  }
  .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
  .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
  .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
  .alert-danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
  .alert-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }

  /* Grid */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  @media(max-width: 768px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

  /* Stat cards */
  .stat-card {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 12px; padding: 20px; text-align: center;
  }
  .stat-card .stat-icon { font-size: 28px; margin-bottom: 8px; }
  .stat-card .stat-value { font-size: 13px; font-weight: 600; color: var(--text); }
  .stat-card .stat-desc { font-size: 12px; color: var(--muted); margin-top: 4px; }

  /* Flow */
  .flow { display: flex; flex-direction: column; gap: 0; }
  .flow-item {
    display: flex; align-items: flex-start; gap: 16px;
    padding: 14px 0; position: relative;
  }
  .flow-item:not(:last-child)::after {
    content: ''; position: absolute; left: 19px; top: 46px;
    width: 2px; height: calc(100% - 10px); background: var(--border);
  }
  .flow-num {
    width: 40px; height: 40px; border-radius: 50%; background: var(--primary);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; flex-shrink: 0; z-index: 1;
  }
  .flow-content h4 { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
  .flow-content p { font-size: 13px; color: var(--muted); }

  /* Hero */
  .hero {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 16px; padding: 40px; color: #fff; margin-bottom: 40px;
  }
  .hero h1 { font-size: 28px; font-weight: 800; margin-bottom: 10px; }
  .hero p { font-size: 15px; opacity: .85; max-width: 560px; line-height: 1.6; }
  .hero-badges { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
  .hero-badge {
    background: rgba(255,255,255,0.15); color: #fff;
    padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
  }

  /* Method tag */
  .method {
    display: inline-block; padding: 2px 8px; border-radius: 4px;
    font-size: 11px; font-weight: 700; font-family: monospace;
  }
  .method-get  { background: #d1fae5; color: #065f46; }
  .method-post { background: #dbeafe; color: #1e40af; }

  /* Tab comparison */
  .compare { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .compare-col h4 { font-size: 13px; font-weight: 700; margin-bottom: 10px; padding: 8px 12px; border-radius: 8px; }
  .compare-col.old h4 { background: #fef3c7; color: #92400e; }
  .compare-col.new h4 { background: #d1fae5; color: #065f46; }

  /* Footer */
  .footer { margin-top: 60px; padding-top: 24px; border-top: 1px solid var(--border); text-align: center; color: var(--muted); font-size: 13px; }
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">
    🔐 License System
    <span>Creative Host BD — Documentation</span>
  </div>
  <nav>
    <div class="group-label">Overview</div>
    <a href="#overview">📋 সিস্টেম ওভারভিউ</a>
    <a href="#database">🗄️ ডাটাবেস কাঠামো</a>

    <div class="group-label">API</div>
    <a href="#api">🌐 verify-license API</a>
    <a href="#hmac">🔑 HMAC Security</a>

    <div class="group-label">Features</div>
    <a href="#admin">⚙️ Admin প্যানেল</a>
    <a href="#user">👤 User প্যানেল</a>
    <a href="#stolen">🛡️ Stolen Domain</a>
    <a href="#killswitch">☠️ Kill Switch</a>
    <a href="#cache">⚡ Cache Signal</a>

    <div class="group-label">Deploy</div>
    <a href="#deploy">🚀 Server Deploy</a>
    <a href="#client">💻 Client Integration</a>

    <div class="group-label">Help</div>
    <a href="#troubleshoot">🔧 Troubleshooting</a>
    <a href="#routes">🗺️ সব Routes</a>
  </nav>
</aside>

<!-- Main Content -->
<main class="main">

  <!-- Hero -->
  <div class="hero">
    <h1>🔐 License System Documentation</h1>
    <p>এই ডকুমেন্টেশনে লাইসেন্স সিস্টেমের সম্পূর্ণ বিবরণ, API রেফারেন্স, Deploy গাইড এবং Client Integration কোড রয়েছে।</p>
    <div class="hero-badges">
      <span class="hero-badge">✅ Backward Compatible</span>
      <span class="hero-badge">🔒 HMAC Security</span>
      <span class="hero-badge">☠️ Kill Switch</span>
      <span class="hero-badge">⚡ Cache Signal</span>
      <span class="hero-badge">🛡️ Auto Block</span>
    </div>
  </div>

  <!-- Overview -->
  <section id="overview">
    <div class="section-header">
      <div class="section-icon" style="background:#ede9fe;">📋</div>
      <div><h2>সিস্টেম ওভারভিউ</h2><p>পুরো লাইসেন্স সিস্টেমের সারসংক্ষেপ</p></div>
    </div>

    <div class="grid-3" style="margin-bottom:20px;">
      <div class="stat-card"><div class="stat-icon">🏢</div><div class="stat-value">Admin Panel</div><div class="stat-desc">লাইসেন্স তৈরি, ম্যানেজ, ব্লক</div></div>
      <div class="stat-card"><div class="stat-icon">👤</div><div class="stat-value">User Panel</div><div class="stat-desc">লাইসেন্স কেনা ও নবায়ন</div></div>
      <div class="stat-card"><div class="stat-icon">🌐</div><div class="stat-value">Public API</div><div class="stat-desc">POST /api/verify-license</div></div>
    </div>

    <div class="card">
      <h3>মূল ফিচারসমূহ</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>ফিচার</th><th>বিবরণ</th></tr></thead>
          <tbody>
            <tr><td><span class="badge badge-green">License Generation</span></td><td>Admin বা User পেমেন্ট করে নতুন লাইসেন্স তৈরি করতে পারে</td></tr>
            <tr><td><span class="badge badge-blue">Domain Binding</span></td><td>প্রতিটি লাইসেন্স একটি নির্দিষ্ট ডোমেইনের সাথে যুক্ত</td></tr>
            <tr><td><span class="badge badge-red">Auto Block</span></td><td>Invalid লাইসেন্স ব্যবহার করলে ডোমেইন স্বয়ংক্রিয়ভাবে ব্লক হয়</td></tr>
            <tr><td><span class="badge badge-yellow">Cache Signal</span></td><td>Admin ইচ্ছামতো ক্লায়েন্ট সাইটের cache মুছতে পারে</td></tr>
            <tr><td><span class="badge badge-red">Kill Switch</span></td><td>Admin যেকোনো সাইটকে রিমোটলি disable করতে পারে</td></tr>
            <tr><td><span class="badge badge-purple">HMAC Security</span></td><td>নতুন সাইটে HMAC signature দিয়ে শক্তিশালী নিরাপত্তা</td></tr>
            <tr><td><span class="badge badge-green">License Renewal</span></td><td>মেয়াদ শেষে UddoktaPay দিয়ে পেমেন্ট করে নবায়ন</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Database -->
  <section id="database">
    <div class="section-header">
      <div class="section-icon" style="background:#dbeafe;">🗄️</div>
      <div><h2>ডাটাবেস কাঠামো</h2><p>সকল টেবিলের বিস্তারিত বিবরণ</p></div>
    </div>

    <div class="card">
      <h3>📦 <code>licenses</code> টেবিল — মূল লাইসেন্স ডেটা</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Column</th><th>Type</th><th>বিবরণ</th></tr></thead>
          <tbody>
            <tr><td><code>id</code></td><td>bigint PK</td><td>Auto increment</td></tr>
            <tr><td><code>user_id</code></td><td>bigint</td><td>লাইসেন্সের মালিক (users টেবিল)</td></tr>
            <tr><td><code>domain_name</code></td><td>varchar(255)</td><td>Normalized ডোমেইন (www/https ছাড়া, lowercase)</td></tr>
            <tr><td><code>license_key</code></td><td>varchar(255)</td><td>Format: <code>LIC-XXXX-XXXX-XXXX</code></td></tr>
            <tr><td><code>expiry_date</code></td><td>datetime</td><td>মেয়াদ শেষের তারিখ</td></tr>
            <tr><td><code>status</code></td><td>int</td><td><span class="badge badge-green">1 = Active</span> <span class="badge badge-red">0 = Inactive</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <h3>🛡️ <code>stolen_domains</code> টেবিল — চুরি লগ</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
          <tbody>
            <tr><td><code>domain_name</code></td><td>চেষ্টা করা ডোমেইন</td></tr>
            <tr><td><code>ip_address</code></td><td>আক্রমণকারীর IP</td></tr>
            <tr><td><code>cache_signal</code></td><td>Random token — পরিবর্তন হলে ক্লায়েন্ট cache মুছবে</td></tr>
            <tr><td><code>is_blocked</code></td><td><span class="badge badge-red">1 = Blocked</span> <span class="badge badge-green">0 = Unblocked</span></td></tr>
            <tr><td><code>status</code></td><td><span class="badge badge-yellow">0 = Pending</span> <span class="badge badge-green">1 = Resolved</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid-2">
      <div class="card">
        <h3>☠️ <code>unauthorized_logs</code></h3>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
            <tbody>
              <tr><td><code>domain</code></td><td>ডোমেইন নাম</td></tr>
              <tr><td><code>ip</code></td><td>IP ঠিকানা</td></tr>
              <tr><td><code>reason</code></td><td>কারণ</td></tr>
              <tr><td><code>kill_switch</code></td><td>1 = wipe_out চালু</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card">
        <h3>🔄 <code>license_renewal_orders</code></h3>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
            <tbody>
              <tr><td><code>license_id</code></td><td>কোন লাইসেন্সের জন্য</td></tr>
              <tr><td><code>amount</code></td><td>পেমেন্টের পরিমাণ</td></tr>
              <tr><td><code>invoice_id</code></td><td>UddoktaPay Invoice</td></tr>
              <tr><td><code>status</code></td><td>pending / paid / cancelled</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      <span class="alert-icon">💡</span>
      <div><strong>phpMyAdmin SQL:</strong> নিচের <a href="#deploy">Deploy সেকশনে</a> সব টেবিলের সম্পূর্ণ SQL CREATE statement দেওয়া আছে।</div>
    </div>
  </section>

  <!-- API -->
  <section id="api">
    <div class="section-header">
      <div class="section-icon" style="background:#d1fae5;">🌐</div>
      <div><h2>verify-license API</h2><p>ক্লায়েন্ট সাইট লাইসেন্স যাচাইয়ের Public API</p></div>
    </div>

    <div class="card">
      <h3>Endpoint</h3>
      <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f1f5f9;border-radius:8px;font-family:monospace;">
        <span class="method method-post">POST</span>
        <code style="background:none;color:#1e293b;font-size:14px;">/api/verify-license</code>
        <span class="badge badge-yellow">Rate Limit: 60/min</span>
      </div>
    </div>

    <div class="card">
      <h3>Request Parameters</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>বিবরণ</th></tr></thead>
          <tbody>
            <tr><td><code>domain</code></td><td>string</td><td><span class="badge badge-red">হ্যাঁ</span></td><td>ক্লায়েন্ট সাইটের ডোমেইন</td></tr>
            <tr><td><code>license_key</code></td><td>string</td><td><span class="badge badge-red">হ্যাঁ</span></td><td>লাইসেন্স কী (LIC-XXXX-XXXX-XXXX)</td></tr>
            <tr><td><code>signature</code></td><td>string</td><td><span class="badge badge-blue">না</span></td><td>HMAC signature (নতুন ক্লায়েন্টের জন্য)</td></tr>
            <tr><td><code>url</code></td><td>string</td><td><span class="badge badge-blue">না</span></td><td>সম্পূর্ণ URL (logging এর জন্য)</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid-2">
      <div class="card">
        <h3>✅ Valid Response</h3>
        <pre><code>{
  "status": "valid",
  "blocked": false,
  "domain_name": "example.com",
  "license_key": "LIC-ABCD-EFGH-IJKL",
  "expiry_date": "2027-05-15T00:00:00Z",
  "secure_token": "a1b2c3d4...",
  "cache_signal": "xK9mP2...",
  "action": "none"
}</code></pre>
      </div>
      <div class="card">
        <h3>❌ Invalid Response</h3>
        <pre><code>{
  "status": "invalid",
  "blocked": true,
  "message": "License key or domain is
  incorrect or has expired.",
  "action": "none"
}</code></pre>
      </div>
    </div>

    <div class="card">
      <h3>Response Fields ব্যাখ্যা</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Field</th><th>মান</th><th>বিবরণ</th></tr></thead>
          <tbody>
            <tr><td><code>status</code></td><td><code>valid</code> / <code>invalid</code></td><td>লাইসেন্স বৈধ কিনা</td></tr>
            <tr><td><code>blocked</code></td><td><code>true</code> / <code>false</code></td><td>ডোমেইন blocked কিনা</td></tr>
            <tr><td><code>secure_token</code></td><td>md5 hash</td><td>অতিরিক্ত যাচাইয়ের জন্য</td></tr>
            <tr><td><code>cache_signal</code></td><td>random string</td><td>পরিবর্তন হলে ক্লায়েন্ট cache মুছবে</td></tr>
            <tr><td><code>action</code></td><td><code>none</code> / <code>wipe_out</code></td><td>wipe_out হলে সাইট disable করতে হবে</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="alert alert-warning">
      <span class="alert-icon">⚠️</span>
      <div><strong>action: wipe_out</strong> — এই response পেলে ক্লায়েন্ট সাইটকে নিজেকে disable করতে হবে। Admin Kill Switch চালু করেছে।</div>
    </div>
  </section>

  <!-- HMAC -->
  <section id="hmac">
    <div class="section-header">
      <div class="section-icon" style="background:#fef3c7;">🔑</div>
      <div><h2>HMAC Security</h2><p>নতুন ক্লায়েন্টের জন্য উন্নত নিরাপত্তা</p></div>
    </div>

    <div class="compare">
      <div class="compare-col old">
        <h4>⚠️ পুরনো পদ্ধতি (Legacy)</h4>
        <ul style="font-size:13px;padding-left:18px;line-height:2;">
          <li>Signature নেই</li>
          <li>Invalid হলে stolen_domains এ লগ হয়</li>
          <li>সহজ কিন্তু কম secure</li>
        </ul>
      </div>
      <div class="compare-col new">
        <h4>✅ নতুন পদ্ধতি (HMAC)</h4>
        <ul style="font-size:13px;padding-left:18px;line-height:2;">
          <li>HMAC-SHA256 signature</li>
          <li>Cleaner — stolen_domains log করে না</li>
          <li>বেশি secure</li>
        </ul>
      </div>
    </div>

    <div class="card" style="margin-top:16px;">
      <h3>HMAC Signature তৈরি করার নিয়ম</h3>
      <pre><code>// PHP
$signature = hash_hmac('sha256', $domain, 'your_secret_salt_key');

// উদাহরণ
$domain    = 'example.com';
$signature = hash_hmac('sha256', 'example.com', 'your_secret_salt_key');
// → "a3f9b2c1d4e5f6..."</code></pre>
    </div>

    <div class="alert alert-info">
      <span class="alert-icon">⚙️</span>
      <div><strong>Salt পরিবর্তন:</strong> <code>app/Http/Controllers/Admin/LicenseController.php</code> এ <code>HMAC_SALT</code> ও <code>TOKEN_SALT</code> constants পরিবর্তন করুন। পরিবর্তন করলে সব ক্লায়েন্টে নতুন salt দিতে হবে।</div>
    </div>
  </section>

  <!-- Admin -->
  <section id="admin">
    <div class="section-header">
      <div class="section-icon" style="background:#e0e7ff;">⚙️</div>
      <div><h2>Admin প্যানেল</h2><p>লাইসেন্স ম্যানেজমেন্ট ও নিয়ন্ত্রণ</p></div>
    </div>

    <div class="card">
      <h3>নতুন লাইসেন্স তৈরি — <code>/admin/licenses</code></h3>
      <div class="flow">
        <div class="flow-item"><div class="flow-num">১</div><div class="flow-content"><h4>ইউজার সিলেক্ট করুন</h4><p>ড্রপডাউন থেকে ক্লায়েন্টের অ্যাকাউন্ট বেছে নিন</p></div></div>
        <div class="flow-item"><div class="flow-num">২</div><div class="flow-content"><h4>ডোমেইন দিন</h4><p>www বা https দিলেও স্বয়ংক্রিয়ভাবে normalize হবে</p></div></div>
        <div class="flow-item"><div class="flow-num">৩</div><div class="flow-content"><h4>মেয়াদ সিলেক্ট করুন</h4><p>১ বছর / ২ বছর / ৩ বছর</p></div></div>
        <div class="flow-item"><div class="flow-num">৪</div><div class="flow-content"><h4>Submit করুন</h4><p>স্বয়ংক্রিয়ভাবে <code>LIC-XXXX-XXXX-XXXX</code> format এ key তৈরি হবে</p></div></div>
      </div>
    </div>

    <div class="card">
      <h3>Stolen Reports — Action বাটনসমূহ</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>বাটন</th><th>কাজ</th></tr></thead>
          <tbody>
            <tr><td>⚡ Cache Clear</td><td>ক্লায়েন্ট সাইটের cache মুছার signal পাঠানো (৩০ মিনিটের মধ্যে কার্যকর)</td></tr>
            <tr><td>🔒 Block</td><td>ডোমেইন block করা — ক্লায়েন্ট সাইট বন্ধ হবে</td></tr>
            <tr><td>🔓 Unblock</td><td>ডোমেইন unblock করা</td></tr>
            <tr><td>☠️ Kill Switch</td><td>রিমোট wipe-out চালু/বন্ধ করা</td></tr>
            <tr><td>🔄 Status</td><td>Pending → Resolved toggle</td></tr>
            <tr><td>🗑️ Delete</td><td>রিপোর্ট মুছা</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <h3>রিনিউয়াল অর্ডার ম্যানেজমেন্ট — <code>/admin/license-renewal-orders</code></h3>
      <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">User সাইট থেকে renew request করলে এখানে দেখা যাবে।</p>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Action</th><th>ফলাফল</th></tr></thead>
          <tbody>
            <tr><td><span class="badge badge-green">Mark as Paid</span></td><td>লাইসেন্স আরো ১ বছর extend হবে, status = paid</td></tr>
            <tr><td><span class="badge badge-red">Mark as Unpaid</span></td><td>পূর্বের অবস্থায় ফেরানো যাবে</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- User -->
  <section id="user">
    <div class="section-header">
      <div class="section-icon" style="background:#d1fae5;">👤</div>
      <div><h2>User প্যানেল</h2><p>ক্লায়েন্টের নিজস্ব লাইসেন্স ম্যানেজমেন্ট</p></div>
    </div>

    <div class="grid-2">
      <div class="card">
        <h3>বিনামূল্যে লাইসেন্স</h3>
        <div class="alert alert-success" style="margin:0 0 10px;">
          <span class="alert-icon">✅</span>
          <div>Admin যদি <code>free_license_generation = true</code> করে দেয়</div>
        </div>
        <p style="font-size:13px;color:var(--muted);">ইউজার সরাসরি লাইসেন্স পাবে, কোনো পেমেন্ট লাগবে না।</p>
      </div>
      <div class="card">
        <h3>পেমেন্ট করে লাইসেন্স</h3>
        <div class="alert alert-info" style="margin:0 0 10px;">
          <span class="alert-icon">💳</span>
          <div>UddoktaPay Gateway ব্যবহার করে</div>
        </div>
        <p style="font-size:13px;color:var(--muted);">মূল্য = <code>license_generation_price × মেয়াদ</code></p>
      </div>
    </div>

    <div class="card" style="margin-top:16px;">
      <h3>User লাইসেন্স Actions</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>URL</th><th>কাজ</th></tr></thead>
          <tbody>
            <tr><td><code>/my-licenses</code></td><td>সকল লাইসেন্স দেখা</td></tr>
            <tr><td><code>/license/generate</code></td><td>নতুন লাইসেন্স কেনা</td></tr>
            <tr><td><code>/license/renew/{id}</code></td><td>মেয়াদ শেষে নবায়ন করা</td></tr>
            <tr><td><code>/license/update/{id}</code></td><td>ডোমেইন পরিবর্তন</td></tr>
            <tr><td><code>/license/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Stolen Domain -->
  <section id="stolen">
    <div class="section-header">
      <div class="section-icon" style="background:#fee2e2;">🛡️</div>
      <div><h2>Stolen Domain সিস্টেম</h2><p>অবৈধ ব্যবহার স্বয়ংক্রিয়ভাবে ধরা ও ব্লক করা</p></div>
    </div>

    <div class="card">
      <h3>কীভাবে কাজ করে</h3>
      <div class="flow">
        <div class="flow-item"><div class="flow-num" style="background:#ef4444;">১</div><div class="flow-content"><h4>ক্লায়েন্ট verify করে</h4><p>POST /api/verify-license এ domain + license_key পাঠায়</p></div></div>
        <div class="flow-item"><div class="flow-num" style="background:#f59e0b;">২</div><div class="flow-content"><h4>Server চেক করে</h4><p>ডোমেইন ও কী মিলিয়ে দেখে, মেয়াদ চেক করে</p></div></div>
        <div class="flow-item"><div class="flow-num" style="background:#10b981;">৩ক</div><div class="flow-content"><h4>Valid হলে</h4><p>is_blocked = 0 হয় (auto unblock), status: valid রিটার্ন করে</p></div></div>
        <div class="flow-item"><div class="flow-num" style="background:#ef4444;">৩খ</div><div class="flow-content"><h4>Invalid হলে</h4><p>stolen_domains এ লগ হয়, is_blocked = 1, cache_signal নতুন হয়</p></div></div>
        <div class="flow-item"><div class="flow-num" style="background:#4f46e5;">৪</div><div class="flow-content"><h4>Admin দেখতে পান</h4><p>Stolen Reports এ নতুন entry আসে। Block/Unblock করা যায়।</p></div></div>
      </div>
    </div>

    <div class="card">
      <h3>Domain Normalize নিয়ম</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Input</th><th>Normalized Output</th></tr></thead>
          <tbody>
            <tr><td><code>https://www.example.com/path</code></td><td><code>example.com</code></td></tr>
            <tr><td><code>http://example.com/</code></td><td><code>example.com</code></td></tr>
            <tr><td><code>www.EXAMPLE.COM</code></td><td><code>example.com</code></td></tr>
            <tr><td><code>Example.Com</code></td><td><code>example.com</code></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Kill Switch -->
  <section id="killswitch">
    <div class="section-header">
      <div class="section-icon" style="background:#fee2e2;">☠️</div>
      <div><h2>Kill Switch</h2><p>রিমোট wipe-out — ক্লায়েন্ট সাইট disable করা</p></div>
    </div>

    <div class="alert alert-danger">
      <span class="alert-icon">🚨</span>
      <div><strong>সতর্কতা:</strong> Kill Switch চালু করলে ক্লায়েন্ট সাইট সম্পূর্ণ বন্ধ হয়ে যেতে পারে। সাবধানে ব্যবহার করুন।</div>
    </div>

    <div class="grid-2" style="margin-top:16px;">
      <div class="card">
        <h3>Kill Switch বন্ধ</h3>
        <pre style="margin:8px 0;"><code>{ "action": "none" }</code></pre>
        <p style="font-size:13px;color:var(--muted);">সাইট স্বাভাবিকভাবে কাজ করবে</p>
      </div>
      <div class="card">
        <h3>Kill Switch চালু ☠️</h3>
        <pre style="margin:8px 0;"><code>{ "action": "wipe_out" }</code></pre>
        <p style="font-size:13px;color:var(--muted);">ক্লায়েন্ট সাইট নিজেকে disable করবে</p>
      </div>
    </div>

    <div class="card" style="margin-top:16px;">
      <h3>কীভাবে চালু/বন্ধ করবেন</h3>
      <div class="flow">
        <div class="flow-item"><div class="flow-num">১</div><div class="flow-content"><h4>Admin প্যানেল খুলুন</h4><p>Stolen Reports সেকশনে যান</p></div></div>
        <div class="flow-item"><div class="flow-num">২</div><div class="flow-content"><h4>☠️ বাটনে ক্লিক করুন</h4><p>নির্দিষ্ট ডোমেইনের Kill Switch বাটনে ক্লিক করুন</p></div></div>
        <div class="flow-item"><div class="flow-num">৩</div><div class="flow-content"><h4>Confirm করুন</h4><p>পরের বার ক্লায়েন্ট verify করলে action=wipe_out পাবে</p></div></div>
      </div>
    </div>
  </section>

  <!-- Cache Signal -->
  <section id="cache">
    <div class="section-header">
      <div class="section-icon" style="background:#fef3c7;">⚡</div>
      <div><h2>Cache Signal সিস্টেম</h2><p>ক্লায়েন্ট সাইটের cache রিমোটলি মুছা</p></div>
    </div>

    <div class="card">
      <h3>উদ্দেশ্য</h3>
      <p style="font-size:13.5px;line-height:1.8;color:var(--muted);">ক্লায়েন্ট সাইট performance এর জন্য লাইসেন্স ভেরিফিকেশন cache করে রাখতে পারে। Cache Signal ব্যবহার করে Admin যেকোনো সময় ক্লায়েন্ট সাইটের cache মুছতে পারে — সাইটকে সরাসরি access না করেই।</p>
    </div>

    <div class="card">
      <h3>কীভাবে কাজ করে</h3>
      <div class="flow">
        <div class="flow-item"><div class="flow-num">১</div><div class="flow-content"><h4>Admin ⚡ বাটনে ক্লিক করে</h4><p>Stolen Reports এ নির্দিষ্ট ডোমেইনের Cache Clear বাটন</p></div></div>
        <div class="flow-item"><div class="flow-num">২</div><div class="flow-content"><h4>নতুন cache_signal তৈরি হয়</h4><p>stolen_domains টেবিলে নতুন random token সেভ হয়</p></div></div>
        <div class="flow-item"><div class="flow-num">৩</div><div class="flow-content"><h4>ক্লায়েন্ট verify করে</h4><p>API response এ নতুন cache_signal পায়</p></div></div>
        <div class="flow-item"><div class="flow-num">৪</div><div class="flow-content"><h4>ক্লায়েন্ট পার্থক্য দেখে</h4><p>পুরনো signal আলাদা → cache মুছে → নতুন signal সেভ করে</p></div></div>
      </div>
    </div>

    <div class="alert alert-warning">
      <span class="alert-icon">⚠️</span>
      <div><strong>সীমাবদ্ধতা:</strong> Blocked domain এর cache clear করা যাবে না। আগে Unblock করুন, তারপর Cache Clear করুন।</div>
    </div>
  </section>

  <!-- Deploy -->
  <section id="deploy">
    <div class="section-header">
      <div class="section-icon" style="background:#d1fae5;">🚀</div>
      <div><h2>Server Deploy নির্দেশিকা</h2><p>প্রথমবার এবং আপডেট deploy করার নিয়ম</p></div>
    </div>

    <div class="card">
      <h3>phpMyAdmin এ চালানোর SQL (সব টেবিল)</h3>
      <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">SSH না থাকলে এই SQL গুলো phpMyAdmin → SQL ট্যাবে paste করে Execute করুন:</p>
      <pre><code>-- ১. stolen_domains এ নতুন column
ALTER TABLE `stolen_domains`
  ADD COLUMN IF NOT EXISTS `cache_signal` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `is_blocked` tinyint(1) NOT NULL DEFAULT 0;

-- ২. users টেবিলে free_license_generation
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `free_license_generation` tinyint(1) NOT NULL DEFAULT 0;

-- ৩. generalsettings এ license price
ALTER TABLE `generalsettings`
  ADD COLUMN IF NOT EXISTS `license_generation_price` decimal(10,2) DEFAULT 1000.00;

-- ৪. unauthorized_logs টেবিল তৈরি
CREATE TABLE IF NOT EXISTS `unauthorized_logs` (
  `id`           bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain`       varchar(255) NOT NULL,
  `ip`           varchar(255) DEFAULT NULL,
  `reason`       text NOT NULL,
  `url`          text DEFAULT NULL,
  `attempted_at` timestamp NOT NULL,
  `kill_switch`  tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ৫. license_renewal_orders টেবিল তৈরি
CREATE TABLE IF NOT EXISTS `license_renewal_orders` (
  `id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    bigint(20) UNSIGNED NOT NULL,
  `license_id` bigint(20) UNSIGNED NOT NULL,
  `amount`     decimal(10,2) NOT NULL DEFAULT 1000.00,
  `invoice_id` varchar(255) DEFAULT NULL,
  `status`     varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at`    timestamp NULL DEFAULT NULL,
  `notes`      text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</code></pre>
    </div>

    <div class="card">
      <h3>SSH থাকলে (Terminal)</h3>
      <pre><code>php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan cache:clear</code></pre>
    </div>
  </section>

  <!-- Client Integration -->
  <section id="client">
    <div class="section-header">
      <div class="section-icon" style="background:#e0e7ff;">💻</div>
      <div><h2>Client Site Integration</h2><p>ক্লায়েন্ট সাইটে লাইসেন্স চেক করার PHP কোড</p></div>
    </div>

    <div class="card">
      <h3>সহজ পদ্ধতি (Legacy — পুরনো সাইটের জন্য)</h3>
      <pre><code>&lt;?php
function verifyLicense($domain, $licenseKey, $serverUrl) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $serverUrl . '/api/verify-license',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'domain'      => $domain,
            'license_key' => $licenseKey,
            'url'         => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
                             . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        ]),
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$result = verifyLicense(
    'example.com',
    'LIC-ABCD-EFGH-IJKL',
    'https://yourserver.com'
);

if ($result['status'] === 'valid') {
    if ($result['action'] === 'wipe_out') {
        die('Site disabled by administrator.');
    }
    // cache_signal চেক
    $saved = @file_get_contents('license_signal.txt');
    if ($saved !== $result['cache_signal']) {
        clearLocalCache(); // নিজের cache clear function
        file_put_contents('license_signal.txt', $result['cache_signal']);
    }
    // ✅ স্বাভাবিক কাজ চালিয়ে যান
} else {
    die('License invalid or domain blocked.');
}</code></pre>
    </div>

    <div class="card">
      <h3>উন্নত পদ্ধতি (HMAC — নতুন সাইটের জন্য)</h3>
      <pre><code>&lt;?php
define('LICENSE_SERVER', 'https://yourserver.com');
define('LICENSE_DOMAIN',  'example.com');
define('LICENSE_KEY',     'LIC-ABCD-EFGH-IJKL');
define('HMAC_SALT',       'your_secret_salt_key'); // server এর HMAC_SALT

function verifyLicenseHmac() {
    $domain    = LICENSE_DOMAIN;
    $signature = hash_hmac('sha256', $domain, HMAC_SALT);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => LICENSE_SERVER . '/api/verify-license',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'domain'      => $domain,
            'license_key' => LICENSE_KEY,
            'signature'   => $signature,
            'url'         => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
                             . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        ]),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$result = verifyLicenseHmac();

if (!$result || $result['status'] !== 'valid') {
    die('License verification failed.');
}

if ($result['action'] === 'wipe_out') {
    die('This installation has been remotely deactivated.');
}

// Cache signal চেক
$cacheFile   = __DIR__ . '/.license_signal';
$savedSignal = file_exists($cacheFile) ? trim(file_get_contents($cacheFile)) : '';
if ($result['cache_signal'] && $savedSignal !== $result['cache_signal']) {
    if (function_exists('opcache_reset')) opcache_reset();
    file_put_contents($cacheFile, $result['cache_signal']);
}

// ✅ লাইসেন্স valid — কাজ চালিয়ে যান</code></pre>
    </div>
  </section>

  <!-- Troubleshoot -->
  <section id="troubleshoot">
    <div class="section-header">
      <div class="section-icon" style="background:#fef3c7;">🔧</div>
      <div><h2>Troubleshooting</h2><p>সাধারণ সমস্যা ও সমাধান</p></div>
    </div>

    <div class="card">
      <h3>সমস্যা ১: পুরনো সাইট কাজ করছে না (HTTP 500)</h3>
      <div class="alert alert-danger" style="margin:8px 0;"><span class="alert-icon">🔴</span><div><strong>কারণ:</strong> সার্ভারে <code>unauthorized_logs</code> টেবিল নেই।</div></div>
      <p style="font-size:13px;margin-bottom:8px;"><strong>সমাধান:</strong> phpMyAdmin এ রান করুন:</p>
      <pre><code>CREATE TABLE IF NOT EXISTS `unauthorized_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `reason` text NOT NULL,
  `url` text DEFAULT NULL,
  `attempted_at` timestamp NOT NULL,
  `kill_switch` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</code></pre>
    </div>

    <div class="card">
      <h3>সমস্যা ২: সাইট blocked কিন্তু লাইসেন্স valid</h3>
      <div class="alert alert-warning" style="margin:8px 0;"><span class="alert-icon">🟡</span><div><strong>কারণ:</strong> <code>stolen_domains.is_blocked = 1</code> আছে।</div></div>
      <p style="font-size:13px;margin-bottom:8px;"><strong>সমাধান:</strong></p>
      <pre><code>UPDATE stolen_domains SET is_blocked = 0 WHERE domain_name = 'example.com';</code></pre>
      <p style="font-size:13px;color:var(--muted);">অথবা Admin → Stolen Reports → 🔓 Unblock বাটন</p>
    </div>

    <div class="card">
      <h3>সমস্যা ৩: Kill Switch বন্ধ করা যাচ্ছে না</h3>
      <pre><code>UPDATE unauthorized_logs SET kill_switch = 0 WHERE domain = 'example.com';</code></pre>
    </div>

    <div class="card">
      <h3>সমস্যা ৪: kill_switch column নেই</h3>
      <pre><code>ALTER TABLE `unauthorized_logs` ADD COLUMN `kill_switch` tinyint(1) NOT NULL DEFAULT 0;</code></pre>
    </div>

    <div class="card">
      <h3>সমস্যা ৫: Domain normalize এর কারণে লাইসেন্স পাচ্ছে না</h3>
      <div class="alert alert-info" style="margin:8px 0;"><span class="alert-icon">💡</span><div>License এডিট করে ডোমেইন আবার Save করুন — স্বয়ংক্রিয়ভাবে normalize হবে।</div></div>
    </div>
  </section>

  <!-- Routes -->
  <section id="routes">
    <div class="section-header">
      <div class="section-icon" style="background:#e0e7ff;">🗺️</div>
      <div><h2>সকল Routes</h2><p>সম্পূর্ণ route তালিকা</p></div>
    </div>

    <div class="card">
      <h3>Public (Authentication ছাড়া)</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
          <tbody>
            <tr><td><span class="method method-post">POST</span></td><td><code>/api/verify-license</code></td><td>লাইসেন্স ভেরিফিকেশন API</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/license</code></td><td>লাইসেন্স পেজ (frontend)</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <h3>Admin Routes</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
          <tbody>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/licenses</code></td><td>লাইসেন্স লিস্ট</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/licenses/store</code></td><td>নতুন লাইসেন্স তৈরি</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/licenses/edit/{id}</code></td><td>এডিট ডেটা (Ajax)</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/licenses/update/{id}</code></td><td>লাইসেন্স আপডেট</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/licenses/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/license-renewal-orders</code></td><td>রিনিউয়াল অর্ডার লিস্ট</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/license-renewal-orders/{id}/mark-paid</code></td><td>পেইড মার্ক</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/license-renewal-orders/{id}/mark-unpaid</code></td><td>আনপেইড মার্ক</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/stolen-reports</code></td><td>চুরির রিপোর্ট</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/stolen-reports/clear-cache/{id}</code></td><td>Cache Signal আপডেট</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/stolen-reports/block/{id}</code></td><td>Block/Unblock টগল</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/admin/stolen-reports/kill-switch/{id}</code></td><td>Kill Switch টগল</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/stolen-reports/status/{id}</code></td><td>Status পরিবর্তন</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/admin/stolen-reports/delete/{id}</code></td><td>রিপোর্ট মুছা</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <h3>User Routes</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
          <tbody>
            <tr><td><span class="method method-get">GET</span></td><td><code>/my-licenses</code></td><td>আমার লাইসেন্সসমূহ</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/license/generate</code></td><td>নতুন লাইসেন্স ফর্ম</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/license/generate</code></td><td>লাইসেন্স জেনারেট</td></tr>
            <tr><td><span class="method method-get">GET</span></td><td><code>/license/renew/{id}</code></td><td>রিনিউ পেজ</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/license/update/{id}</code></td><td>লাইসেন্স আপডেট</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/license/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
            <tr><td><span class="method method-get">GET/POST</span></td><td><code>/license/renew/success</code></td><td>পেমেন্ট সফল callback</td></tr>
            <tr><td><span class="method method-post">POST</span></td><td><code>/license/renew/webhook</code></td><td>UddoktaPay Webhook</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <div class="footer">
    <p>License System Documentation — Creative Host BD &nbsp;|&nbsp; শেষ আপডেট: ১৫ মে ২০২৬</p>
  </div>

</main>

<script>
// Active sidebar link on scroll
const sections = document.querySelectorAll('section[id]');
const links = document.querySelectorAll('.sidebar nav a');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 60) current = s.id;
  });
  links.forEach(l => {
    l.classList.remove('active');
    if (l.getAttribute('href') === '#' + current) l.classList.add('active');
  });
});
</script>
</body>
</html>
