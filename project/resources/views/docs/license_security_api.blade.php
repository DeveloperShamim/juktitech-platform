@extends('layouts.front')
@section('meta')
<meta name="description" content="License System সম্পূর্ণ ডকুমেন্টেশন — verify-license API, cache-signal, kill_switch, HMAC security, stolen domain, deploy guide এবং client integration।">
@endsection
@section('title', 'License System — সম্পূর্ণ ডকুমেন্টেশন')
@section('contents')

<style>
    :root {
        --doc-bg: #f8fafc;
        --card-bg: #ffffff;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-muted: #64748b;
        --accent: #2563eb;
        --accent-soft: #eff6ff;
        --green: #059669;
        --green-soft: #ecfdf5;
        --red: #dc2626;
        --red-soft: #fef2f2;
        --orange: #d97706;
        --orange-soft: #fffbeb;
        --purple: #7c3aed;
        --purple-soft: #f5f3ff;
        --code-bg: #0f172a;
        --code-text: #e2e8f0;
    }
    .doc-page { background: var(--doc-bg); }
    .doc-wrap { max-width: 980px; margin: 0 auto; padding: 2.5rem 1.25rem 5rem; }

    /* Hero */
    .doc-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
        border-radius: 20px; padding: 2.25rem 1.75rem; color: #fff; margin-bottom: 1.75rem;
    }
    .doc-header h1 { font-size: 1.65rem; font-weight: 800; margin-bottom: 0.5rem; }
    .doc-header .subtitle { color: rgba(255,255,255,0.88); font-size: 0.95rem; margin-bottom: 1rem; line-height: 1.5; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25);
        border-radius: 100px; padding: 0.32rem 0.9rem; font-size: 0.78rem; margin: 0.2rem;
    }
    .base-url-pill {
        display: inline-flex; align-items: center; gap: 0.45rem;
        background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25);
        border-radius: 100px; padding: 0.35rem 1rem; font-size: 0.82rem;
    }
    .base-url-pill code { color: #bfdbfe; background: transparent; }

    /* Permalink */
    .doc-permalink {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: var(--accent-soft); border: 1px solid #bfdbfe;
        border-radius: 12px; padding: 0.65rem 1rem; font-size: 0.88rem; margin-bottom: 1.5rem; word-break: break-all;
    }
    .doc-permalink code { color: #1d4ed8; background: transparent; font-size: 0.85rem; }

    /* TOC */
    .toc-card {
        background: var(--accent-soft); border: 1px solid #bfdbfe;
        border-radius: 14px; padding: 1.2rem 1.4rem; margin-bottom: 1.75rem;
    }
    .toc-card h6 { font-weight: 700; color: var(--accent); margin-bottom: 0.65rem; font-size: 0.78rem; text-transform: uppercase; letter-spacing: .06em; }
    .toc-list { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 0.45rem; }
    .toc-list li a {
        display: inline-flex; align-items: center; gap: 0.3rem;
        background: #fff; border: 1px solid #bfdbfe; border-radius: 8px;
        padding: 0.28rem 0.7rem; font-size: 0.8rem; font-weight: 600; color: var(--accent); text-decoration: none;
    }
    .toc-list li a:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

    /* Notice */
    .rate-notice {
        background: var(--orange-soft); border: 1px solid #fcd34d;
        border-radius: 12px; padding: 1rem 1.15rem; font-size: 0.86rem;
        margin-bottom: 1.75rem; display: flex; align-items: flex-start; gap: 0.65rem;
    }
    .rate-notice i { color: var(--orange); margin-top: 0.12rem; flex-shrink: 0; }
    .note-arch {
        background: var(--purple-soft); border: 1px solid #ddd6fe;
        border-radius: 12px; padding: 1rem 1.15rem; font-size: 0.86rem;
        margin-bottom: 1.75rem; color: #5b21b6;
    }
    .note-arch strong { color: #4c1d95; }

    /* Cards */
    .doc-card {
        background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border);
        padding: 1.75rem 1.5rem; margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04); scroll-margin-top: 72px;
    }
    .doc-card h2 { font-size: 1.08rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .doc-card h3 { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin: 1.25rem 0 0.5rem; }
    .doc-card > p, .doc-card .lead-p { font-size: 0.88rem; color: var(--text-muted); line-height: 1.55; margin-bottom: 0.75rem; }

    /* Endpoint */
    .endpoint-row {
        display: flex; align-items: center; gap: 0.55rem;
        background: #f8fafc; border: 1px solid var(--border);
        border-radius: 10px; padding: 0.55rem 0.9rem; margin: 0.75rem 0 1rem; flex-wrap: wrap;
    }
    .badge-method { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800; color: #fff; letter-spacing: .04em; }
    .badge-post { background: var(--accent); }
    .badge-get  { background: var(--green); }
    .endpoint-url { font-size: 0.85rem; font-family: ui-monospace, Consolas, monospace; word-break: break-all; color: var(--text-primary); }

    /* Tables */
    .section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); margin: 1.1rem 0 0.45rem; }
    .param-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .param-table thead tr { background: #f8fafc; }
    .param-table th, .param-table td { padding: 0.5rem 0.65rem; border: 1px solid var(--border); vertical-align: top; }
    .param-table th { font-weight: 700; color: var(--text-primary); }
    .param-table code { background: #f1f5f9; padding: 0.08rem 0.35rem; border-radius: 4px; font-size: 0.8rem; color: #be123c; }
    .badge-req { display: inline-block; background: #fee2e2; color: #991b1b; padding: 0.08rem 0.4rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; }
    .badge-opt { display: inline-block; background: #f1f5f9; color: #64748b; padding: 0.08rem 0.4rem; border-radius: 4px; font-size: 0.68rem; font-weight: 700; }
    .badge-status { display: inline-block; padding: 0.08rem 0.4rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700; }
    .badge-green  { background: #d1fae5; color: #065f46; }
    .badge-red    { background: #fee2e2; color: #991b1b; }
    .badge-blue   { background: #dbeafe; color: #1e40af; }
    .badge-yellow { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }

    /* Code */
    .code-block {
        background: var(--code-bg); color: var(--code-text);
        padding: 1rem 1.1rem; border-radius: 12px; font-size: 0.8rem;
        overflow-x: auto; margin: 0.4rem 0 0;
    }
    .code-block pre { margin: 0; white-space: pre-wrap; word-break: break-word; font-family: ui-monospace, Consolas, monospace; line-height: 1.6; }
    .code-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin: 0.85rem 0 0.25rem; }

    /* Compare */
    .compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem; }
    @media (max-width: 720px) { .compare-grid { grid-template-columns: 1fr; } }
    .compare-box { border: 1px solid var(--border); border-radius: 12px; padding: 1rem; background: #fafafa; font-size: 0.84rem; }
    .compare-box h6 { font-size: 0.78rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-primary); }

    /* Grids */
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem; }
    @media (max-width: 720px) { .grid-3 { grid-template-columns: 1fr; } }
    .stat-box { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; padding: 1rem; text-align: center; }
    .stat-box .s-icon { font-size: 1.5rem; margin-bottom: 0.4rem; }
    .stat-box .s-label { font-size: 0.78rem; font-weight: 700; color: var(--text-primary); }
    .stat-box .s-desc  { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.2rem; }

    /* Alerts */
    .alert-danger-doc {
        background: var(--red-soft); border: 1px solid #fecaca;
        border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.85rem; color: #991b1b; margin-top: 0.75rem;
    }
    .alert-info-doc {
        background: var(--accent-soft); border: 1px solid #bfdbfe;
        border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.85rem; color: #1e40af; margin-top: 0.75rem;
    }
    .alert-warn-doc {
        background: var(--orange-soft); border: 1px solid #fcd34d;
        border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.85rem; color: #92400e; margin-top: 0.75rem;
    }
    .alert-success-doc {
        background: var(--green-soft); border: 1px solid #a7f3d0;
        border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.85rem; color: #065f46; margin-top: 0.75rem;
    }

    /* Flow */
    .flow { list-style: none; margin: 0.5rem 0 0; padding: 0; }
    .flow li { display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.5rem 0; font-size: 0.85rem; border-left: 2px solid var(--border); padding-left: 1rem; margin-left: 0.6rem; }
    .flow li:last-child { border-left-color: transparent; }
    .flow li .fn { font-weight: 700; color: var(--text-primary); }

    /* Footer */
    .doc-footer { text-align: center; padding: 1.5rem 0 0; color: var(--text-muted); font-size: 0.84rem; border-top: 1px solid var(--border); margin-top: 1.5rem; }
    .doc-footer a { color: var(--accent); text-decoration: none; font-weight: 600; }
    .doc-footer a:hover { text-decoration: underline; }
</style>

<div class="doc-page">
<div class="doc-wrap">

    {{-- Permalink --}}
    <div class="doc-permalink">
        <i class="fas fa-link text-primary"></i>
        <span>স্থায়ী ডক লিঙ্ক (বুকমার্ক):</span>
        <code>{{ route('docs.license_security_api') }}</code>
    </div>

    {{-- Hero --}}
    <div class="doc-header">
        <h1><i class="fas fa-shield-alt me-2"></i>License System — সম্পূর্ণ ডকুমেন্টেশন</h1>
        <p class="subtitle mb-2">Admin লাইসেন্স ম্যানেজমেন্ট, User পেমেন্ট, Public verify-license API, cache-signal, kill_switch, HMAC security, stolen domain এবং deploy গাইড — সব একসাথে।</p>
        <div>
            <span class="hero-badge"><i class="fas fa-check-circle"></i> Backward Compatible</span>
            <span class="hero-badge"><i class="fas fa-lock"></i> HMAC Security</span>
            <span class="hero-badge"><i class="fas fa-skull-crossbones"></i> Kill Switch</span>
            <span class="hero-badge"><i class="fas fa-bolt"></i> Cache Signal</span>
            <span class="hero-badge"><i class="fas fa-ban"></i> Auto Block</span>
        </div>
        <div class="base-url-pill mt-3">
            <i class="fas fa-server" style="font-size:.78rem;"></i>
            API Base: <code>{{ url('/') }}</code>
        </div>
    </div>

    {{-- TOC --}}
    <div class="toc-card">
        <h6><i class="fas fa-list me-1"></i>সূচিপত্র</h6>
        <ul class="toc-list">
            <li><a href="#overview"><i class="fas fa-th-large"></i> ওভারভিউ</a></li>
            <li><a href="#database"><i class="fas fa-database"></i> ডাটাবেস</a></li>
            <li><a href="#verify-license"><i class="fas fa-key"></i> verify-license API</a></li>
            <li><a href="#cache-signal"><i class="fas fa-bolt"></i> cache-signal</a></li>
            <li><a href="#check-update"><i class="fas fa-sync-alt"></i> check-update</a></li>
            <li><a href="#download-update"><i class="fas fa-download"></i> download-update</a></li>
            <li><a href="#log-unauthorized"><i class="fas fa-user-shield"></i> log-unauthorized</a></li>
            <li><a href="#kill-switch"><i class="fas fa-skull-crossbones"></i> Kill Switch</a></li>
            <li><a href="#stolen-log"><i class="fas fa-shield-alt"></i> Stolen Domain</a></li>
            <li><a href="#admin-panel"><i class="fas fa-cog"></i> Admin প্যানেল</a></li>
            <li><a href="#user-panel"><i class="fas fa-user"></i> User প্যানেল</a></li>
            <li><a href="#deploy"><i class="fas fa-rocket"></i> Deploy SQL</a></li>
            <li><a href="#client-code"><i class="fas fa-code"></i> Client Code</a></li>
            <li><a href="#troubleshoot"><i class="fas fa-wrench"></i> Troubleshooting</a></li>
            <li><a href="#routes"><i class="fas fa-map"></i> সব Routes</a></li>
        </ul>
    </div>

    <div class="note-arch">
        <strong><i class="fas fa-exclamation-circle me-1"></i>গুরুত্বপূর্ণ:</strong>
        <code>POST /api/verify-license</code> শুধু <code>routes/web.php</code> থেকে রেজিস্টার (CSRF exempt)।
        <code>routes/api.php</code> এ ডুপ্লিকেট রুট <strong>রাখবেন না</strong> — Laravel API ফাইল আগে লোড হলে পুরনো ক্লায়েন্ট ভুল কন্ট্রোলারে যায়।
    </div>

    <div class="rate-notice">
        <i class="fas fa-tachometer-alt"></i>
        <div><strong>Rate limit:</strong> পাবলিক API — <code>throttle:60,1</code> (প্রতি IP প্রতি মিনিটে ৬০ রিকোয়েস্ট)। সীমা ছাড়ালে HTTP <strong>429</strong>।</div>
    </div>


    {{-- ========== OVERVIEW ========== --}}
    <div class="doc-card" id="overview">
        <h2><i class="fas fa-th-large text-primary"></i> সিস্টেম ওভারভিউ</h2>
        <div class="grid-3">
            <div class="stat-box"><div class="s-icon">🏢</div><div class="s-label">Admin Panel</div><div class="s-desc">লাইসেন্স তৈরি, ম্যানেজ, ব্লক</div></div>
            <div class="stat-box"><div class="s-icon">👤</div><div class="s-label">User Panel</div><div class="s-desc">পেমেন্ট করে লাইসেন্স ও নবায়ন</div></div>
            <div class="stat-box"><div class="s-icon">🌐</div><div class="s-label">Public API</div><div class="s-desc">POST /api/verify-license</div></div>
        </div>
        <table class="param-table mt-2">
            <thead><tr><th>এন্ডপয়েন্ট</th><th>কন্ট্রোলার</th><th>মেথড</th></tr></thead>
            <tbody>
                <tr><td><code>POST /api/verify-license</code></td><td><code>Admin\LicenseController</code></td><td><code>verifyLicense</code></td></tr>
                <tr><td><code>POST /api/log-unauthorized</code></td><td><code>Api\LicenseSecurityApiController</code></td><td><code>logUnauthorized</code></td></tr>
                <tr><td><code>GET /api/cache-signal</code></td><td><code>Api\LicenseSecurityApiController</code></td><td><code>cacheSignal</code></td></tr>
                <tr><td><code>POST /api/check-update</code></td><td><code>Api\CheckUpdateController</code></td><td><code>checkUpdate</code> (+ signature)</td></tr>
                <tr><td><code>POST /api/download-update</code></td><td><code>Api\DownloadUpdateController</code></td><td><code>download</code></td></tr>
            </tbody>
        </table>
        <p class="section-label">ডোমেইন Normalize নিয়ম</p>
        <table class="param-table">
            <thead><tr><th>Input</th><th>Normalized Output</th></tr></thead>
            <tbody>
                <tr><td><code>https://www.example.com/path</code></td><td><code>example.com</code></td></tr>
                <tr><td><code>http://example.com/</code></td><td><code>example.com</code></td></tr>
                <tr><td><code>www.EXAMPLE.COM</code></td><td><code>example.com</code></td></tr>
                <tr><td><code>Example.Com</code></td><td><code>example.com</code></td></tr>
            </tbody>
        </table>
        <p class="small text-muted mt-1 mb-0">HMAC signature এই normalize করা স্ট্রিং দিয়েই গণনা করতে হবে।</p>
    </div>


    {{-- ========== DATABASE ========== --}}
    <div class="doc-card" id="database">
        <h2><i class="fas fa-database text-info"></i> ডাটাবেস কাঠামো</h2>

        <h3>📦 <code>licenses</code> — মূল লাইসেন্স ডেটা</h3>
        <table class="param-table">
            <thead><tr><th>Column</th><th>Type</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>user_id</code></td><td>bigint</td><td>লাইসেন্সের মালিক (users)</td></tr>
                <tr><td><code>domain_name</code></td><td>varchar</td><td>Normalized ডোমেইন (www/https ছাড়া, lowercase)</td></tr>
                <tr><td><code>license_key</code></td><td>varchar</td><td>Format: <code>LIC-XXXX-XXXX-XXXX</code></td></tr>
                <tr><td><code>expiry_date</code></td><td>datetime</td><td>মেয়াদ শেষের তারিখ</td></tr>
                <tr><td><code>status</code></td><td>int</td><td><span class="badge-status badge-green">1 = Active</span> <span class="badge-status badge-red">0 = Inactive</span></td></tr>
            </tbody>
        </table>

        <h3>🛡️ <code>stolen_domains</code> — চুরি লগ</h3>
        <table class="param-table">
            <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>domain_name</code></td><td>চেষ্টা করা ডোমেইন</td></tr>
                <tr><td><code>ip_address</code></td><td>আক্রমণকারীর IP</td></tr>
                <tr><td><code>cache_signal</code></td><td>Random token — পরিবর্তন হলে ক্লায়েন্ট cache মুছবে</td></tr>
                <tr><td><code>is_blocked</code></td><td><span class="badge-status badge-red">1 = Blocked</span> <span class="badge-status badge-green">0 = Unblocked</span></td></tr>
                <tr><td><code>status</code></td><td><span class="badge-status badge-yellow">0 = Pending</span> <span class="badge-status badge-green">1 = Resolved</span></td></tr>
            </tbody>
        </table>

        <h3>☠️ <code>unauthorized_logs</code> — Kill Switch লগ</h3>
        <table class="param-table">
            <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td>ডোমেইন নাম</td></tr>
                <tr><td><code>ip</code></td><td>IP ঠিকানা</td></tr>
                <tr><td><code>reason</code></td><td>কারণ</td></tr>
                <tr><td><code>attempted_at</code></td><td>সময়</td></tr>
                <tr><td><code>kill_switch</code></td><td><span class="badge-status badge-red">1 = wipe_out চালু</span> <span class="badge-status badge-green">0 = বন্ধ</span></td></tr>
            </tbody>
        </table>

        <h3>🔄 <code>license_renewal_orders</code> — নবায়ন অর্ডার</h3>
        <table class="param-table">
            <thead><tr><th>Column</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>license_id</code></td><td>কোন লাইসেন্সের জন্য</td></tr>
                <tr><td><code>amount</code></td><td>পেমেন্টের পরিমাণ</td></tr>
                <tr><td><code>invoice_id</code></td><td>UddoktaPay Invoice ID</td></tr>
                <tr><td><code>status</code></td><td><code>pending</code> / <code>paid</code> / <code>cancelled</code></td></tr>
            </tbody>
        </table>
    </div>


    {{-- ========== VERIFY LICENSE ========== --}}
    <div class="doc-card" id="verify-license">
        <h2><i class="fas fa-key text-primary"></i> <code>POST /api/verify-license</code></h2>
        <p class="lead-p">লাইসেন্স বৈধতা যাচাই। বডিতে <code>signature</code> <strong>থাকলে</strong> → HMAC মোড। না থাকলে → Legacy মোড। সব response এ <code>action</code> থাকে।</p>

        <div class="endpoint-row">
            <span class="badge-method badge-post">POST</span>
            <span class="endpoint-url">{{ url('/api/verify-license') }}</span>
        </div>

        <div class="compare-grid">
            <div class="compare-box">
                <h6><i class="fas fa-history text-secondary"></i> Legacy মোড (পুরনো ক্লায়েন্ট)</h6>
                <ul class="small ps-3 mb-0" style="line-height:1.8;">
                    <li><code>signature</code> ফিল্ড <strong>নেই বা খালি</strong></li>
                    <li>Invalid হলে <code>stolen_domains</code> এ auto-block</li>
                    <li>Valid হলে <code>is_blocked = 0</code> (auto-unblock)</li>
                    <li>Response এ <code>cache_signal</code> ও <code>secure_token</code></li>
                    <li>HTTP <strong>200</strong> (status JSON এ)</li>
                </ul>
            </div>
            <div class="compare-box">
                <h6><i class="fas fa-lock text-primary"></i> HMAC মোড (নতুন ক্লায়েন্ট)</h6>
                <ul class="small ps-3 mb-0" style="line-height:1.8;">
                    <li><code>signature = hash_hmac('sha256', domain, HMAC_SALT)</code></li>
                    <li>Invalid হলে <code>stolen_domains</code> block <strong>হয় না</strong></li>
                    <li>Valid হলে <code>is_blocked = 0</code> (auto-unblock)</li>
                    <li>Response এ <code>cache_signal</code> ও <code>secure_token</code></li>
                    <li>Signature ব্যর্থ হলে HTTP <strong>403</strong></li>
                </ul>
            </div>
        </div>

        <p class="section-label">Request ফিল্ড</p>
        <table class="param-table">
            <thead><tr><th>ফিল্ড</th><th>টাইপ</th><th>নোট</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td>string</td><td><span class="badge-req">Required</span> সার্ভার normalize করে।</td></tr>
                <tr><td><code>license_key</code></td><td>string</td><td><span class="badge-req">Required</span> যেমন <code>LIC-XXXX-XXXX-XXXX</code></td></tr>
                <tr><td><code>url</code></td><td>string</td><td><span class="badge-opt">Optional</span> Legacy মোডে stolen log এ সেভ হয়।</td></tr>
                <tr><td><code>signature</code></td><td>string</td><td><span class="badge-opt">Optional</span> থাকলে HMAC মোড চালু হয়।</td></tr>
            </tbody>
        </table>

        <p class="section-label">Response ফিল্ড</p>
        <table class="param-table">
            <thead><tr><th>ফিল্ড</th><th>মান</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>status</code></td><td><code>valid</code> / <code>invalid</code></td><td>লাইসেন্স বৈধ কিনা</td></tr>
                <tr><td><code>blocked</code></td><td><code>true</code> / <code>false</code></td><td>ডোমেইন blocked কিনা</td></tr>
                <tr><td><code>secure_token</code></td><td>md5 hash</td><td><code>md5(domain + TOKEN_SALT)</code></td></tr>
                <tr><td><code>cache_signal</code></td><td>random string / null</td><td>পরিবর্তন হলে ক্লায়েন্ট cache মুছবে</td></tr>
                <tr><td><code>action</code></td><td><code>none</code> / <code>wipe_out</code></td><td><code>wipe_out</code> হলে সাইট disable করতে হবে</td></tr>
            </tbody>
        </table>

        <div class="code-label">200 — Valid (Legacy ও HMAC উভয়)</div>
        <div class="code-block"><pre>{
  "status": "valid",
  "blocked": false,
  "domain_name": "example.com",
  "license_key": "LIC-ABCD-EFGH-IJKL",
  "expiry_date": "2028-01-15 00:00:00",
  "secure_token": "a1b2c3d4e5f6...",
  "cache_signal": "xK9mP2rT...",
  "action": "none"
}</pre></div>

        <div class="code-label">200 — Invalid (Legacy, auto-block হবে)</div>
        <div class="code-block"><pre>{
  "status": "invalid",
  "blocked": true,
  "message": "License key or domain is incorrect or has expired.",
  "action": "none"
}</pre></div>

        <div class="code-label">403 — HMAC Signature ব্যর্থ</div>
        <div class="code-block"><pre>{
  "status": "invalid",
  "message": "Signature verification failed.",
  "action": "none"
}</pre></div>

        <p class="section-label">cURL — Legacy</p>
        <div class="code-block"><pre>curl -X POST "{{ url('/api/verify-license') }}" \
  -H "Content-Type: application/json" \
  -d '{"domain":"example.com","license_key":"LIC-YOUR-KEY","url":"https://example.com"}'</pre></div>

        <p class="section-label">cURL — HMAC</p>
        <div class="code-block"><pre>curl -X POST "{{ url('/api/verify-license') }}" \
  -H "Content-Type: application/json" \
  -d '{"domain":"example.com","license_key":"LIC-YOUR-KEY","signature":"HMAC_SHA256_HEX_HERE"}'</pre></div>

        <div class="alert-info-doc">
            <strong>HMAC Salt:</strong> <code>LicenseController</code> এ — <code>HMAC_SALT = 'your_secret_salt_key'</code> এবং <code>TOKEN_SALT = 'YOUR_SECRET_SALT_HERE_12345'</code>। উৎপাদন ব্যবহারের আগে পরিবর্তন করুন।
        </div>
    </div>


    {{-- ========== CACHE SIGNAL ========== --}}
    <div class="doc-card" id="cache-signal">
        <h2><i class="fas fa-bolt text-warning"></i> <code>GET /api/cache-signal</code></h2>
        <p class="lead-p">Background এ poll করুন (যেমন প্রতি ৩০ মিনিট)। <code>signal</code> বদলালে local cache মুছে পুনরায় verify করুন। প্রতিটি response এ <code>action</code> থাকে (<code>kill_switch</code> একই নিয়ম)।</p>

        <div class="endpoint-row">
            <span class="badge-method badge-get">GET</span>
            <span class="endpoint-url">{{ url('/api/cache-signal') }}?domain=example.com</span>
        </div>

        <div class="code-label">domain নেই</div>
        <div class="code-block"><pre>{ "signal": null, "action": "none" }</pre></div>
        <div class="code-label">রেকর্ড নেই বা cache_signal খালি</div>
        <div class="code-block"><pre>{ "signal": "default", "blocked": false, "action": "none" }</pre></div>
        <div class="code-label">Blocked ডোমেইন</div>
        <div class="code-block"><pre>{ "signal": "…token…", "blocked": true, "action": "wipe_out" }</pre></div>
        <div class="code-label">Signal আছে, blocked নেই</div>
        <div class="code-block"><pre>{ "signal": "xK9mP2rT...", "blocked": false, "action": "none" }</pre></div>

        <div class="alert-warn-doc">
            <strong>Admin Cache Clear:</strong> Admin → Stolen Reports → ⚡ বাটন → <code>stolen_domains.cache_signal</code> নতুন random token পায়। পরের বার ক্লায়েন্ট poll করলে signal পরিবর্তিত দেখে cache মুছবে।
        </div>
    </div>


    {{-- ========== CHECK UPDATE ========== --}}
    <div class="doc-card" id="check-update">
        <h2><i class="fas fa-sync-alt text-secondary"></i> <code>POST /api/check-update</code></h2>
        <p class="lead-p">নতুন লাইসেন্স: <code>license_key</code> + <code>signature</code> (<code>LICENSE_SIGNATURE</code>) বাধ্যতামূলক। বৈধ হলে <code>script_updates</code> থেকে <code>version_compare</code>।</p>

        <div class="endpoint-row">
            <span class="badge-method badge-post">POST</span>
            <span class="endpoint-url">{{ url('/api/check-update') }}</span>
        </div>

        <table class="param-table">
            <thead><tr><th>ফিল্ড</th><th>বাধ্যতা</th><th>বিবরণ</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td><span class="badge-req">Required</span></td><td>Normalize হয়।</td></tr>
                <tr><td><code>license_key</code></td><td><span class="badge-req">Required</span></td><td><code>LICENSE_KEY</code></td></tr>
                <tr><td><code>signature</code></td><td><span class="badge-req">Required</span></td><td><code>LICENSE_SIGNATURE</code> — DB <code>signature_key</code></td></tr>
                <tr><td><code>script_name</code></td><td><span class="badge-req">Required</span></td><td>Admin আপলোডের নামের সাথে হুবহু মিল।</td></tr>
                <tr><td><code>current_version</code></td><td><span class="badge-opt">Optional</span></td><td>Default <code>1.0.0</code>।</td></tr>
            </tbody>
        </table>
        <p class="small text-muted mt-1 mb-2">লাইসেন্স/সিগনেচার ব্যর্থ → HTTP <strong>403</strong>। আপডেট থাকলে <code>download_url</code> = <code>POST /api/download-update</code>।</p>
        <a href="{{ route('docs.script_update') }}" class="small" style="color:var(--accent);">→ সম্পূর্ণ Script Update গাইড</a>
    </div>

    <div class="doc-card" id="download-update">
        <h2><i class="fas fa-download text-secondary"></i> <code>POST /api/download-update</code></h2>
        <p class="lead-p">ZIP ডাউনলোড — একই <code>domain</code>, <code>license_key</code>, <code>signature</code>, <code>script_name</code>।</p>
        <div class="endpoint-row">
            <span class="badge-method badge-post">POST</span>
            <span class="endpoint-url">{{ url('/api/download-update') }}</span>
        </div>
        <p class="small text-muted mb-0">সফল → ZIP স্ট্রিম। Rate limit: 30/min।</p>
    </div>


    {{-- ========== LOG UNAUTHORIZED ========== --}}
    <div class="doc-card" id="log-unauthorized">
        <h2><i class="fas fa-user-shield text-danger"></i> <code>POST /api/log-unauthorized</code></h2>
        <p class="lead-p">বডিতে <code>reason</code> ও <code>attempted_at</code> উভয়ই থাকলে <strong>নতুন ফরম্যাট</strong> (<code>unauthorized_logs</code>)। নইলে <strong>Legacy</strong> (<code>stolen_domains</code> updateOrCreate)।</p>

        <div class="endpoint-row">
            <span class="badge-method badge-post">POST</span>
            <span class="endpoint-url">{{ url('/api/log-unauthorized') }}</span>
        </div>

        <p class="section-label">Legacy বডি</p>
        <table class="param-table">
            <thead><tr><th>ফিল্ড</th><th>বাধ্যতা</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td><span class="badge-req">Required</span></td></tr>
                <tr><td><code>ip</code></td><td><span class="badge-opt">Optional</span></td></tr>
                <tr><td><code>url</code></td><td><span class="badge-opt">Optional</span></td></tr>
            </tbody>
        </table>
        <div class="code-label">Response 200</div>
        <div class="code-block"><pre>{ "status": "success" }</pre></div>

        <p class="section-label">নতুন বডি (UnauthorizedLog)</p>
        <table class="param-table">
            <thead><tr><th>ফিল্ড</th><th>বাধ্যতা</th></tr></thead>
            <tbody>
                <tr><td><code>domain</code></td><td><span class="badge-req">Required</span></td></tr>
                <tr><td><code>reason</code></td><td><span class="badge-req">Required</span></td></tr>
                <tr><td><code>attempted_at</code></td><td><span class="badge-req">Required</span></td></tr>
                <tr><td><code>ip</code></td><td><span class="badge-opt">Optional</span></td></tr>
                <tr><td><code>url</code></td><td><span class="badge-opt">Optional</span></td></tr>
            </tbody>
        </table>
        <div class="code-label">Response 201</div>
        <div class="code-block"><pre>{ "status": "ok", "message": "লগ সংরক্ষিত হয়েছে।" }</pre></div>
    </div>


    {{-- ========== KILL SWITCH ========== --}}
    <div class="doc-card" id="kill-switch">
        <h2><i class="fas fa-skull-crossbones text-danger"></i> Kill Switch — <code>action: wipe_out</code></h2>
        <p class="lead-p"><code>UnauthorizedLog::killSwitchWantsWipeOut($domain)</code> — <code>unauthorized_logs</code> এ ওই ডোমেইনের <code>kill_switch = true</code> সারি থাকলে সব API response এ <code>"action": "wipe_out"</code>।</p>

        <table class="param-table">
            <thead><tr><th>API</th><th>kill_switch চেক হয়</th></tr></thead>
            <tbody>
                <tr><td><code>POST /api/verify-license</code></td><td><span class="badge-status badge-green">হ্যাঁ — সব মোডে</span></td></tr>
                <tr><td><code>GET /api/cache-signal</code></td><td><span class="badge-status badge-green">হ্যাঁ — সব response এ</span></td></tr>
            </tbody>
        </table>

        <p class="section-label">Admin থেকে চালু/বন্ধ</p>
        <p class="small text-muted mb-2">Stolen Reports → ☠️ Kill Switch বাটন → Toggle on/off।</p>
        <a href="{{ route('admin.stolen.index') }}" class="small" style="color:var(--accent);">→ Admin Stolen Reports খুলুন</a>

        <div class="alert-danger-doc">
            <strong>⚠️ সতর্কতা:</strong> <code>action: wipe_out</code> পেলে ক্লায়েন্ট সাইট নিজেকে disable করবে। এটি অপরিবর্তনীয় ক্ষতি করতে পারে। শুধু বিশ্বস্ত পরিস্থিতিতে চালু করুন।
        </div>
    </div>


    {{-- ========== STOLEN DOMAIN ========== --}}
    <div class="doc-card" id="stolen-log">
        <h2><i class="fas fa-shield-alt text-info"></i> Stolen Domain সিস্টেম</h2>
        <p class="lead-p">Invalid লাইসেন্স দিয়ে প্রবেশের চেষ্টা স্বয়ংক্রিয়ভাবে log হয় এবং ডোমেইন block হয়।</p>

        <ul class="flow">
            <li><span class="fn">ক্লায়েন্ট verify করে →</span> invalid license_key বা domain</li>
            <li><span class="fn">stolen_domains এ log →</span> <code>is_blocked = 1</code>, নতুন <code>cache_signal</code></li>
            <li><span class="fn">Admin দেখতে পান →</span> Stolen Reports প্যানেলে</li>
            <li><span class="fn">Admin Block/Unblock করেন →</span> domain এর <code>is_blocked</code> toggle</li>
            <li><span class="fn">Valid verify হলে →</span> <code>is_blocked = 0</code> (auto-unblock)</li>
        </ul>

        <p class="section-label">Admin Action বাটনসমূহ</p>
        <table class="param-table">
            <thead><tr><th>বাটন</th><th>কাজ</th></tr></thead>
            <tbody>
                <tr><td>⚡ Cache Clear</td><td>নতুন <code>cache_signal</code> পাঠানো — ক্লায়েন্ট ৩০ মিনিটের মধ্যে cache মুছবে</td></tr>
                <tr><td>🔒 Block</td><td><code>is_blocked = 1</code> → ক্লায়েন্ট সাইট বন্ধ</td></tr>
                <tr><td>🔓 Unblock</td><td><code>is_blocked = 0</code> → ক্লায়েন্ট সাইট চালু</td></tr>
                <tr><td>☠️ Kill Switch</td><td><code>unauthorized_logs.kill_switch</code> toggle → <code>action: wipe_out</code></td></tr>
                <tr><td>🔄 Status</td><td>Pending ↔ Resolved</td></tr>
                <tr><td>🗑️ Delete</td><td>রিপোর্ট মুছা</td></tr>
            </tbody>
        </table>

        <div class="alert-danger-doc">
            <strong>সতর্কতা:</strong> ভুল key দিয়ে বারবার চেষ্টা করলে domain auto-block হয়। ক্লায়েন্টে প্রথমেই সঠিক key ও domain নিশ্চিত করুন।
        </div>
    </div>


    {{-- ========== ADMIN PANEL ========== --}}
    <div class="doc-card" id="admin-panel">
        <h2><i class="fas fa-cog text-secondary"></i> Admin প্যানেল</h2>

        <h3>নতুন লাইসেন্স তৈরি</h3>
        <p class="small text-muted">URL: <code>/admin/licenses</code> — ইউজার + ডোমেইন + মেয়াদ (১/২/৩ বছর) → <code>LIC-XXXX-XXXX-XXXX</code> তৈরি হয়।</p>

        <h3>রিনিউয়াল অর্ডার ম্যানেজমেন্ট</h3>
        <p class="small text-muted">URL: <code>/admin/license-renewal-orders</code></p>
        <table class="param-table">
            <thead><tr><th>Action</th><th>ফলাফল</th></tr></thead>
            <tbody>
                <tr><td><span class="badge-status badge-green">Mark as Paid</span></td><td>লাইসেন্স +১ বছর extend, status = paid</td></tr>
                <tr><td><span class="badge-status badge-red">Mark as Unpaid</span></td><td>পূর্বের অবস্থায় ফেরানো</td></tr>
            </tbody>
        </table>

        <h3>User কে বিনামূল্যে লাইসেন্স অনুমতি দেওয়া</h3>
        <div class="code-block"><pre>-- phpMyAdmin এ:
UPDATE users SET free_license_generation = 1 WHERE id = USER_ID;</pre></div>
    </div>


    {{-- ========== USER PANEL ========== --}}
    <div class="doc-card" id="user-panel">
        <h2><i class="fas fa-user text-success"></i> User প্যানেল</h2>
        <p class="lead-p">ক্লায়েন্ট নিজে লাইসেন্স কিনতে ও নবায়ন করতে পারে।</p>

        <table class="param-table">
            <thead><tr><th>URL</th><th>কাজ</th></tr></thead>
            <tbody>
                <tr><td><code>/my-licenses</code></td><td>সকল লাইসেন্স দেখা</td></tr>
                <tr><td><code>/license/generate</code></td><td>নতুন লাইসেন্স কেনা (UddoktaPay বা বিনামূল্যে)</td></tr>
                <tr><td><code>/license/renew/{id}</code></td><td>মেয়াদ শেষে নবায়ন করা</td></tr>
                <tr><td><code>/license/update/{id}</code></td><td>ডোমেইন পরিবর্তন</td></tr>
                <tr><td><code>/license/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
            </tbody>
        </table>

        <div class="alert-info-doc mt-2">
            <strong>মূল্য হিসাব:</strong> <code>license_generation_price × মেয়াদ (বছর)</code>।
            উদাহরণ: ১০০০ টাকা × ২ বছর = ২০০০ টাকা।
        </div>
    </div>


    {{-- ========== DEPLOY ========== --}}
    <div class="doc-card" id="deploy">
        <h2><i class="fas fa-rocket text-primary"></i> Server Deploy — phpMyAdmin SQL</h2>
        <p class="lead-p">SSH না থাকলে নিচের SQL গুলো phpMyAdmin → SQL ট্যাবে paste করে Execute করুন।</p>

        <div class="code-block"><pre>-- ১. stolen_domains এ নতুন column
ALTER TABLE `stolen_domains`
  ADD COLUMN IF NOT EXISTS `cache_signal` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `is_blocked` tinyint(1) NOT NULL DEFAULT 0;

-- ২. users এ free_license_generation
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `free_license_generation` tinyint(1) NOT NULL DEFAULT 0;

-- ৩. generalsettings এ license price
ALTER TABLE `generalsettings`
  ADD COLUMN IF NOT EXISTS `license_generation_price` decimal(10,2) DEFAULT 1000.00;

-- ৪. unauthorized_logs টেবিল
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

-- ৫. license_renewal_orders টেবিল
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</pre></div>

        <p class="section-label">SSH থাকলে</p>
        <div class="code-block"><pre>php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan cache:clear</pre></div>
    </div>


    {{-- ========== CLIENT CODE ========== --}}
    <div class="doc-card" id="client-code">
        <h2><i class="fas fa-code text-purple"></i> Client Site Integration</h2>

        <h3>Legacy পদ্ধতি (পুরনো সাইট)</h3>
        <div class="code-block"><pre>&lt;?php
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

$result = verifyLicense('example.com', 'LIC-ABCD-EFGH-IJKL', '{{ url('/') }}');

if ($result['status'] === 'valid') {
    if ($result['action'] === 'wipe_out') {
        die('Site disabled by administrator.');
    }
    // cache_signal চেক
    $saved = @file_get_contents('license_signal.txt');
    if ($saved !== ($result['cache_signal'] ?? '')) {
        // local cache মুছুন
        file_put_contents('license_signal.txt', $result['cache_signal'] ?? '');
    }
} else {
    die('License invalid or domain blocked.');
}</pre></div>

        <h3>HMAC পদ্ধতি (নতুন সাইট)</h3>
        <div class="code-block"><pre>&lt;?php
define('LICENSE_SERVER', '{{ url('/') }}');
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

// Cache signal চেক (background polling)
$cacheFile   = __DIR__ . '/.license_signal';
$savedSignal = file_exists($cacheFile) ? trim(file_get_contents($cacheFile)) : '';
if (!empty($result['cache_signal']) && $savedSignal !== $result['cache_signal']) {
    if (function_exists('opcache_reset')) opcache_reset();
    file_put_contents($cacheFile, $result['cache_signal']);
}

// ✅ License valid</pre></div>
    </div>


    {{-- ========== TROUBLESHOOT ========== --}}
    <div class="doc-card" id="troubleshoot">
        <h2><i class="fas fa-wrench text-warning"></i> Troubleshooting</h2>

        <h3>সমস্যা ১: পুরনো সাইট HTTP 500 দিচ্ছে</h3>
        <p class="small text-muted">কারণ: <code>unauthorized_logs</code> টেবিল নেই। উপরের Deploy SQL এ ৪ নম্বর SQL রান করুন।</p>

        <h3>সমস্যা ২: সাইট blocked কিন্তু license valid</h3>
        <div class="code-block"><pre>UPDATE stolen_domains SET is_blocked = 0 WHERE domain_name = 'example.com';</pre></div>
        <p class="small text-muted">অথবা Admin → Stolen Reports → 🔓 Unblock।</p>

        <h3>সমস্যা ৩: Kill Switch বন্ধ করা যাচ্ছে না</h3>
        <div class="code-block"><pre>UPDATE unauthorized_logs SET kill_switch = 0 WHERE domain = 'example.com';</pre></div>

        <h3>সমস্যা ৪: kill_switch column নেই</h3>
        <div class="code-block"><pre>ALTER TABLE `unauthorized_logs` ADD COLUMN `kill_switch` tinyint(1) NOT NULL DEFAULT 0;</pre></div>

        <h3>সমস্যা ৫: Domain normalize মিলছে না</h3>
        <p class="small text-muted">License edit করে domain আবার Save করুন — স্বয়ংক্রিয়ভাবে normalize হবে।</p>
    </div>


    {{-- ========== ROUTES ========== --}}
    <div class="doc-card" id="routes">
        <h2><i class="fas fa-map text-info"></i> সব Routes রেফারেন্স</h2>

        <p class="section-label">Public</p>
        <table class="param-table">
            <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
            <tbody>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/api/verify-license</code></td><td>লাইসেন্স ভেরিফিকেশন</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/api/cache-signal</code></td><td>Cache Signal চেক</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/api/log-unauthorized</code></td><td>Unauthorized লগ</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/api/check-update</code></td><td>আপডেট চেক (license + signature)</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/api/download-update</code></td><td>আপডেট ZIP</td></tr>
            </tbody>
        </table>

        <p class="section-label">Admin (auth:admin required)</p>
        <table class="param-table">
            <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
            <tbody>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/admin/licenses</code></td><td>লাইসেন্স লিস্ট</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/licenses/store</code></td><td>নতুন লাইসেন্স তৈরি</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/licenses/update/{id}</code></td><td>লাইসেন্স আপডেট</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/admin/licenses/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/admin/license-renewal-orders</code></td><td>রিনিউয়াল অর্ডার লিস্ট</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/license-renewal-orders/{id}/mark-paid</code></td><td>পেইড মার্ক</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/admin/stolen-reports</code></td><td>চুরির রিপোর্ট</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/stolen-reports/clear-cache/{id}</code></td><td>Cache Signal আপডেট</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/stolen-reports/block/{id}</code></td><td>Block/Unblock টগল</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/admin/stolen-reports/kill-switch/{id}</code></td><td>Kill Switch টগল</td></tr>
            </tbody>
        </table>

        <p class="section-label">User (auth required)</p>
        <table class="param-table">
            <thead><tr><th>Method</th><th>URL</th><th>কাজ</th></tr></thead>
            <tbody>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/my-licenses</code></td><td>আমার লাইসেন্সসমূহ</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/license/generate</code></td><td>নতুন লাইসেন্স ফর্ম</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/license/generate</code></td><td>লাইসেন্স জেনারেট</td></tr>
                <tr><td><span class="badge-method badge-get" style="font-size:.7rem;">GET</span></td><td><code>/license/renew/{id}</code></td><td>রিনিউ পেজ</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/license/update/{id}</code></td><td>লাইসেন্স আপডেট</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/license/delete/{id}</code></td><td>লাইসেন্স মুছা</td></tr>
                <tr><td><span class="badge-method badge-post" style="font-size:.7rem;">POST</span></td><td><code>/license/renew/webhook</code></td><td>UddoktaPay Webhook</td></tr>
            </tbody>
        </table>
    </div>


    {{-- Integration Checklist --}}
    <div class="doc-card">
        <h2><i class="fas fa-list-check text-success"></i> Integration চেকলিস্ট</h2>
        <ol class="small ps-3 mb-0" style="line-height:2;">
            <li>Install/load এ <code>POST /api/verify-license</code> — পুরনো: শুধু <code>domain + license_key</code>। নতুন: normalize করা domain এ HMAC + <code>signature</code>।</li>
            <li>প্রতিটি response এ <code>action</code> পড়ুন — <code>wipe_out</code> হলে নীতি প্রয়োগ করুন।</li>
            <li><code>GET /api/cache-signal?domain=…</code> poll (৩০ মিনিট) — <code>signal</code> বদলালে local cache মুছে পুনরায় verify।</li>
            <li>সন্দেহজনক ব্যবহার: <code>POST /api/log-unauthorized</code> — legacy বা নতুন ফরম্যাটে।</li>
            <li>আপডেট: <code>POST /api/check-update</code> — <code>LICENSE_KEY</code> + <code>LICENSE_SIGNATURE</code> পাঠান; গাইড: <a href="{{ route('docs.script_update') }}">Script Update System</a>।</li>
            <li><code>LICENSE_REMOTE_BASE_URL</code> = লাইসেন্স সার্ভারের root URL।</li>
        </ol>
    </div>


    <div class="doc-footer">
        <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>হোম</a>
        @auth
            &nbsp;·&nbsp;
            <a href="{{ route('admin.stolen.index') }}"><i class="fas fa-shield-alt me-1"></i>Stolen Reports</a>
            &nbsp;·&nbsp;
            <a href="{{ route('admin.license.index') }}"><i class="fas fa-key me-1"></i>Licenses</a>
        @endauth
        &nbsp;·&nbsp;
        <span>শেষ আপডেট: ১৫ মে ২০২৬</span>
    </div>

</div>
</div>

@endsection
