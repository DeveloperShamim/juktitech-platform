<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>License System — সম্পূর্ণ ডকুমেন্টেশন | {{ $gs->title ?? 'Creative Design' }}</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
:root {
    --primary: #4f46e5;
    --primary-d: #3730a3;
    --green: #059669;
    --red: #dc2626;
    --orange: #d97706;
    --purple: #7c3aed;
    --cyan: #0891b2;
    --sidebar: #0f172a;
    --bg: #f8fafc;
    --card: #fff;
    --border: #e2e8f0;
    --text: #1e293b;
    --muted: #64748b;
    --code-bg: #0f172a;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}

/* ── Sidebar ── */
.sidebar{width:270px;min-height:100vh;background:var(--sidebar);position:fixed;top:0;left:0;overflow-y:auto;z-index:100;padding-bottom:40px;transition:.3s}
.sidebar-logo{padding:22px 20px;border-bottom:1px solid rgba(255,255,255,.08);color:#fff;font-size:14px;font-weight:700}
.sidebar-logo small{display:block;font-size:11px;color:#818cf8;font-weight:400;margin-top:3px}
.sidebar nav{padding:8px 0}
.nav-group{padding:14px 20px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#6366f1}
.nav-link{display:flex;align-items:center;gap:9px;padding:9px 20px;color:#94a3b8;text-decoration:none;font-size:13px;transition:.2s;border-left:3px solid transparent}
.nav-link:hover,.nav-link.active{background:rgba(255,255,255,.06);color:#fff;border-left-color:#818cf8}
.nav-link .icon{width:18px;text-align:center;font-size:14px}

/* ── Main ── */
.main{margin-left:270px;flex:1;padding:0}
.top-bar{background:#fff;border-bottom:1px solid var(--border);padding:18px 36px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.top-bar h1{font-size:18px;font-weight:800;color:var(--text)}
.top-bar .badges{display:flex;gap:8px}
.badge-v{background:#ede9fe;color:#5b21b6;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}

.doc-wrap{max-width:860px;padding:36px}

/* ── Section ── */
.doc-section{margin-bottom:52px}
.section-title{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--border)}
.section-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.si-blue{background:#eff6ff;color:#2563eb}
.si-green{background:#f0fdf4;color:#059669}
.si-purple{background:#faf5ff;color:#7c3aed}
.si-orange{background:#fffbeb;color:#d97706}
.si-red{background:#fef2f2;color:#dc2626}
.si-cyan{background:#ecfeff;color:#0891b2}
.si-dark{background:#f1f5f9;color:#0f172a}
.section-title h2{font-size:20px;font-weight:800;color:var(--text)}
.section-title p{font-size:13px;color:var(--muted);margin-top:3px}

/* ── Cards ── */
.card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:20px 22px;margin-bottom:16px}
.card-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px}
.card p{font-size:13.5px;color:var(--muted);line-height:1.75}

/* ── Flow Diagram ── */
.flow{background:linear-gradient(135deg,#0f0c29,#1e1b4b);border-radius:14px;padding:24px 28px;font-family:monospace;font-size:13px;line-height:2.2;color:#e2e8f0;overflow-x:auto}
.flow .node{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:3px 12px;display:inline-block}
.flow .arrow{color:#818cf8}
.flow .ok{color:#86efac;font-weight:700}
.flow .warn{color:#fbbf24}
.flow .muted{color:#64748b}

/* ── Code block ── */
.code-wrap{position:relative;margin:12px 0}
.code-block{background:var(--code-bg);color:#e2e8f0;border-radius:10px;padding:18px 20px;font-family:monospace;font-size:13px;line-height:1.8;overflow-x:auto}
.code-block .comment{color:#475569}
.code-block .key{color:#7dd3fc}
.code-block .val{color:#86efac}
.code-block .str{color:#fda4af}
.code-block .url{color:#c4b5fd;text-decoration:underline}
.code-block .hl{background:rgba(250,204,21,.12);border-radius:3px;padding:0 3px}
.copy-btn{position:absolute;top:10px;right:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#94a3b8;border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer;transition:.2s}
.copy-btn:hover{background:rgba(255,255,255,.15);color:#fff}

/* ── Steps ── */
.steps{counter-reset:step}
.step{display:flex;gap:16px;margin-bottom:20px}
.step-num{width:34px;height:34px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0}
.step-content .step-title{font-weight:700;font-size:14px;color:var(--text);margin-bottom:5px}
.step-content p{font-size:13.5px;color:var(--muted);line-height:1.7;margin:0}

/* ── Alert boxes ── */
.alert{border-radius:10px;padding:13px 16px;font-size:13.5px;line-height:1.7;display:flex;gap:10px;margin:14px 0}
.alert-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8}
.alert-success{background:#f0fdf4;border:1px solid #86efac;color:#166534}
.alert-warn{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
.alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}

/* ── API table ── */
.api-table{width:100%;border-collapse:collapse;margin:12px 0;font-size:13px}
.api-table th{background:#f8fafc;padding:11px 14px;text-align:left;font-weight:700;color:var(--muted);border-bottom:2px solid var(--border);text-transform:uppercase;font-size:11px;letter-spacing:.5px}
.api-table td{padding:11px 14px;border-bottom:1px solid var(--border);vertical-align:top;color:var(--text)}
.api-table tr:last-child td{border-bottom:none}
.method{display:inline-flex;align-items:center;padding:3px 9px;border-radius:5px;font-size:11px;font-weight:800;letter-spacing:.5px}
.method.get{background:#dcfce7;color:#166534}
.method.post{background:#dbeafe;color:#1d4ed8}
.param{background:#f1f5f9;border-radius:4px;padding:2px 7px;font-family:monospace;font-size:12px;color:var(--primary)}
.tag{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600}
.tag-req{background:#fee2e2;color:#991b1b}
.tag-opt{background:#f1f5f9;color:#64748b}
.tag-auto{background:#ede9fe;color:#5b21b6}

/* ── Response block ── */
.res-block{background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:16px 18px;font-family:monospace;font-size:12.5px;line-height:1.9;margin:10px 0}
.r-key{color:#4f46e5}
.r-str{color:#059669}
.r-num{color:#d97706}

/* ── Env preview ── */
.env-box{background:#0f172a;border-radius:10px;padding:16px 20px;font-family:monospace;font-size:13px;line-height:2;color:#e2e8f0;margin:10px 0}
.env-box .k{color:#7dd3fc}
.env-box .v{color:#86efac}
.env-box .c{color:#475569}

/* ── Divider ── */
.section-divider{height:1px;background:var(--border);margin:40px 0}

/* ── Scrollbar ── */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-thumb{background:#334155;border-radius:4px}

/* ── Mobile ── */
@media(max-width:768px){
    .sidebar{transform:translateX(-100%)}
    .main{margin-left:0}
    .doc-wrap{padding:24px 16px}
    .top-bar{padding:14px 16px}
}
</style>
</head>
<body>

<!-- ══════════════════════ SIDEBAR ══════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-shield-halved" style="color:#818cf8;margin-right:8px;"></i>
        License System
        <small>সম্পূর্ণ ডকুমেন্টেশন v2.0</small>
    </div>
    <nav>
        <div class="nav-group">ওভারভিউ</div>
        <a href="#overview" class="nav-link active"><span class="icon"><i class="fas fa-home"></i></span> সিস্টেম পরিচিতি</a>
        <a href="#flow" class="nav-link"><span class="icon"><i class="fas fa-diagram-project"></i></span> কার্যপ্রবাহ</a>

        <div class="nav-group">লাইসেন্স তৈরি</div>
        <a href="#admin-create" class="nav-link"><span class="icon"><i class="fas fa-user-shield"></i></span> Admin থেকে</a>
        <a href="#user-create" class="nav-link"><span class="icon"><i class="fas fa-user"></i></span> User থেকে</a>
        <a href="#public-buy" class="nav-link"><span class="icon"><i class="fas fa-credit-card"></i></span> Public Purchase Page</a>

        <div class="nav-group">Auto .env Save</div>
        <a href="#auto-sync" class="nav-link"><span class="icon"><i class="fas fa-rotate"></i></span> Auto-Sync (Client Script)</a>
        <a href="#http-push" class="nav-link"><span class="icon"><i class="fas fa-paper-plane"></i></span> HTTP Push</a>
        <a href="#client-setup" class="nav-link"><span class="icon"><i class="fas fa-wrench"></i></span> Client Setup</a>

        <div class="nav-group">API রেফারেন্স</div>
        <a href="#api-verify" class="nav-link"><span class="icon"><i class="fas fa-check-circle"></i></span> verify-license</a>
        <a href="#api-fetch" class="nav-link"><span class="icon"><i class="fas fa-download"></i></span> fetch-license</a>
        <a href="#api-cache" class="nav-link"><span class="icon"><i class="fas fa-signal"></i></span> cache-signal</a>

        <div class="nav-group">Security</div>
        <a href="#security" class="nav-link"><span class="icon"><i class="fas fa-lock"></i></span> HMAC + Token</a>
        <a href="#lifetime" class="nav-link"><span class="icon"><i class="fas fa-infinity"></i></span> Lifetime License</a>

        <div class="nav-group">লিংক</div>
        <a href="/admin/licenses" class="nav-link"><span class="icon"><i class="fas fa-key"></i></span> License Panel</a>
        <a href="/admin/licenses/setup-guide" class="nav-link"><span class="icon"><i class="fas fa-book-open"></i></span> Setup Guide</a>
        <a href="/docs/license-security-api" class="nav-link"><span class="icon"><i class="fas fa-file-code"></i></span> পুরনো API Docs</a>
    </nav>
</aside>

<!-- ══════════════════════ MAIN ══════════════════════ -->
<div class="main">
    <div class="top-bar">
        <h1><i class="fas fa-book-open" style="color:#4f46e5;margin-right:8px;"></i>License System — ডকুমেন্টেশন</h1>
        <div class="badges">
            <span class="badge-v">v2.0</span>
            <span class="badge-v" style="background:#dcfce7;color:#166534;">Updated {{ date('d M Y') }}</span>
        </div>
    </div>

    <div class="doc-wrap">

    <!-- ══ 1. OVERVIEW ══ -->
    <div class="doc-section" id="overview">
        <div class="section-title">
            <div class="section-icon si-blue"><i class="fas fa-home"></i></div>
            <div>
                <h2>সিস্টেম পরিচিতি</h2>
                <p>License System কী এবং কোন কোন component আছে</p>
            </div>
        </div>

        <div class="card">
            <div class="card-title">License System কী করে?</div>
            <p>এটি একটি সম্পূর্ণ Software License Management System। Admin বা User যখন কারো জন্য License Key তৈরি করে,
            সেটি সেই client এর website এর <code>.env</code> ফাইলে স্বয়ংক্রিয়ভাবে সেভ হয়।
            Client এর script সেই key দিয়ে এই server-এ verify করে নিজেকে সক্রিয় রাখে।</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-top:4px">
            <div class="card" style="border-top:3px solid #4f46e5">
                <div style="font-size:22px;margin-bottom:8px">🔑</div>
                <div class="card-title">License Key</div>
                <p>Format: <code>LIC-XXXX-XXXX-XXXX</code><br>প্রতিটি License unique।</p>
            </div>
            <div class="card" style="border-top:3px solid #7c3aed">
                <div style="font-size:22px;margin-bottom:8px">✍️</div>
                <div class="card-title">Signature Key</div>
                <p>64-char HMAC-SHA256।<br>Domain + License Key থেকে তৈরি।</p>
            </div>
            <div class="card" style="border-top:3px solid #059669">
                <div style="font-size:22px;margin-bottom:8px">⚡</div>
                <div class="card-title">Auto .env Save</div>
                <p>Client এর server নিজেই লেখে।<br>কোনো manual কাজ নেই।</p>
            </div>
            <div class="card" style="border-top:3px solid #d97706">
                <div style="font-size:22px;margin-bottom:8px">♾️</div>
                <div class="card-title">Lifetime License</div>
                <p>Expiry date ছাড়া license।<br>Admin ও User দিতে পারে।</p>
            </div>
        </div>
    </div>

    <!-- ══ 2. FLOW ══ -->
    <div class="doc-section" id="flow">
        <div class="section-title">
            <div class="section-icon si-purple"><i class="fas fa-diagram-project"></i></div>
            <div><h2>সম্পূর্ণ কার্যপ্রবাহ</h2><p>License তৈরি থেকে .env save পর্যন্ত</p></div>
        </div>

        <div class="flow">
<span class="muted">── তৈরির উৎস ──────────────────────────────────────────────</span>

<span class="node">Admin Panel</span>  <span class="arrow">│</span>  <span class="node">User Panel (Free/Paid)</span>  <span class="arrow">│</span>  <span class="node">Public Buy Page /license</span>
       <span class="arrow">└─────────────────────────┴──────────────────────────────┘</span>
                                       <span class="arrow">↓</span>
                         <span class="node">LicenseService::generateLicenseKey()</span>
                         <span class="node">LicenseService::generateSignature()</span>
                                       <span class="arrow">↓</span>
                         <span class="ok">Database: licenses table তে save</span>
                                       <span class="arrow">↓</span>
               <span class="muted">── .env auto-save (দুটো পদ্ধতি একসাথে) ──────────────</span>

          <span class="node">① HTTP Push</span>                       <span class="node">② Auto-Sync (প্রতি request)</span>
          POST → /license-receiver.php      Client script check করে:
          Client নিজেই .env এ লেখে          "LICENSE_KEY আছে?"
                                            না থাকলে → /api/fetch-license
                                            Server key দেয় → .env এ লেখে

                                       <span class="arrow">↓</span>
                         <span class="ok">Client .env ফাইল আপডেট হয়:</span>
                         LICENSE_KEY=LIC-XXXX-XXXX-XXXX
                         LICENSE_SIGNATURE=abc123...64chars
                         LICENSE_EXPIRY=2027-05-15
                                       <span class="arrow">↓</span>
                         <span class="ok">Client script verify করে → Active ✅</span>
        </div>
    </div>

    <!-- ══ 3. ADMIN CREATE ══ -->
    <div class="doc-section" id="admin-create">
        <div class="section-title">
            <div class="section-icon si-blue"><i class="fas fa-user-shield"></i></div>
            <div><h2>Admin থেকে License তৈরি</h2><p>/admin/licenses → Generate License</p></div>
        </div>

        <div class="card">
            <div class="card-title">Form Fields</div>
            <table class="api-table">
                <tr><th>Field</th><th>বিবরণ</th><th>প্রয়োজনীয়?</th></tr>
                <tr><td><span class="param">user_id</span></td><td>কোন client-এর জন্য</td><td><span class="tag tag-req">Required</span></td></tr>
                <tr><td><span class="param">domain_name</span></td><td>Client এর domain (যেমন: example.com)</td><td><span class="tag tag-req">Required</span></td></tr>
                <tr><td><span class="param">validity</span></td><td>0=Lifetime, 1=১ বছর, 2=২ বছর, 3=৩ বছর</td><td><span class="tag tag-req">Required</span></td></tr>
                <tr><td><span class="param">site_path</span></td><td>Same-server হলে folder path (ঐচ্ছিক)</td><td><span class="tag tag-opt">Optional</span></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">License Generate হলে স্বয়ংক্রিয়ভাবে কী হয়</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">১</div>
                    <div class="step-content">
                        <div class="step-title">Database-এ সেভ হয়</div>
                        <p><code>licenses</code> table-এ: license_key, signature_key, site_path, expiry_date</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">২</div>
                    <div class="step-content">
                        <div class="step-title">HTTP Push (Automatic)</div>
                        <p>Server সাথে সাথে <code>https://clientdomain.com/license-receiver.php</code> এ POST করে</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">৩</div>
                    <div class="step-content">
                        <div class="step-title">Table-এ 🟢 Push বাটন</div>
                        <p>যেকোনো সময় manually push করা যায়। HTTP Push ব্যর্থ হলে এটা ব্যবহার করুন।</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-lightbulb"></i>
            <span><strong>Signature Key দেখা:</strong> Admin table-এ Signature Key column এ ক্লিক করলে clipboard-এ copy হয়। Edit modal-এ পুরো key দেখা ও copy করা যায়।</span>
        </div>
    </div>

    <!-- ══ 4. USER CREATE ══ -->
    <div class="doc-section" id="user-create">
        <div class="section-title">
            <div class="section-icon si-green"><i class="fas fa-user"></i></div>
            <div><h2>User থেকে License তৈরি</h2><p>/license/generate</p></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="card" style="border-top:3px solid #059669">
                <div class="card-title">🎁 Free License</div>
                <p>Admin যদি কোনো user-এর <code>free_license_generation = 1</code> করে দেয়, সে বিনামূল্যে generate করতে পারে। সাথে সাথে license তৈরি হয়।</p>
            </div>
            <div class="card" style="border-top:3px solid #d97706">
                <div class="card-title">💳 Paid License</div>
                <p>UddoktaPay payment gateway দিয়ে payment করলে তারপর license তৈরি হয়। Pending payment-এ admin manually activate করতে পারে।</p>
            </div>
        </div>

        <div class="card" style="margin-top:4px">
            <div class="card-title">Validity Options</div>
            <table class="api-table">
                <tr><th>Value</th><th>মেয়াদ</th><th>মূল্য</th></tr>
                <tr><td><span class="param">1</span></td><td>১ বছর</td><td><code>license_generation_price × 1</code></td></tr>
                <tr><td><span class="param">2</span></td><td>২ বছর</td><td><code>license_generation_price × 2</code></td></tr>
                <tr><td><span class="param">3</span></td><td>৩ বছর</td><td><code>license_generation_price × 3</code></td></tr>
                <tr><td><span class="param">0</span></td><td>♾ Lifetime</td><td><code>license_lifetime_price</code> (default: price × 10)</td></tr>
            </table>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <span>User form-এ <strong>Site Root Path</strong> field আছে (optional)। দিলে same-server হলে সরাসরি .env-এ লেখে। না দিলেও HTTP Push বা Auto-Sync কাজ করবে।</span>
        </div>
    </div>

    <!-- ══ 5. PUBLIC BUY ══ -->
    <div class="doc-section" id="public-buy">
        <div class="section-title">
            <div class="section-icon si-orange"><i class="fas fa-credit-card"></i></div>
            <div><h2>Public Purchase Page</h2><p>/license?domain=example.com</p></div>
        </div>

        <div class="card">
            <div class="card-title">Client site যখন license error দেয়</div>
            <p>Client এর script license error পেলে এই page-এ redirect করবে domain সহ। সেখানে client সরাসরি payment করে license নিতে পারবে।</p>
        </div>

        <div class="card">
            <div class="card-title">Client script এ redirect URL আপডেট</div>
            <div class="code-wrap">
                <div class="code-block">
<span class="comment">// ❌ পুরনো (domain ছাড়া redirect)</span>
header(<span class="str">'Location: {{ $serverUrl }}/license'</span>);

<span class="comment">// ✅ নতুন (domain সহ redirect)</span>
<span class="key">$domain</span> = <span class="key">$_SERVER</span>[<span class="str">'HTTP_HOST'</span>] ?? <span class="str">''</span>;
header(<span class="str">'Location: {{ $serverUrl }}/license?domain='</span> . urlencode(<span class="key">$domain</span>));
                </div>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
            <div class="card" style="text-align:center">
                <div style="font-size:28px;margin-bottom:8px">🆕</div>
                <div class="card-title">Domain আছে, License নেই</div>
                <p style="font-size:12px">Payment form দেখায়। Client নাম + email দিয়ে UddoktaPay-তে pay করে।</p>
            </div>
            <div class="card" style="text-align:center">
                <div style="font-size:28px;margin-bottom:8px">✅</div>
                <div class="card-title">License Active আছে</div>
                <p style="font-size:12px">License Key দেখায়। Expiry date দেখায়। কোনো payment option নেই।</p>
            </div>
            <div class="card" style="text-align:center">
                <div style="font-size:28px;margin-bottom:8px">❌</div>
                <div class="card-title">Domain নেই (Legacy)</div>
                <p style="font-size:12px">Generic error দেখায়। Support contact বাটন দেখায়।</p>
            </div>
        </div>

        <div class="card" style="margin-top:4px">
            <div class="card-title">Routes</div>
            <table class="api-table">
                <tr><th>Method</th><th>URL</th><th>কাজ</th></tr>
                <tr><td><span class="method get">GET</span></td><td><code>/license?domain=xxx</code></td><td>Purchase page দেখায়</td></tr>
                <tr><td><span class="method post">POST</span></td><td><code>/license/buy</code></td><td>UddoktaPay-তে redirect</td></tr>
                <tr><td><span class="method get">GET</span></td><td><code>/license/buy/success</code></td><td>Payment success callback</td></tr>
                <tr><td><span class="method post">POST</span></td><td><code>/license/buy/webhook</code></td><td>UddoktaPay webhook</td></tr>
                <tr><td><span class="method get">GET</span></td><td><code>/license/buy/cancel</code></td><td>Payment cancel</td></tr>
            </table>
        </div>
    </div>

    <!-- ══ 6. AUTO-SYNC ══ -->
    <div class="doc-section" id="auto-sync">
        <div class="section-title">
            <div class="section-icon si-green"><i class="fas fa-rotate"></i></div>
            <div><h2>Auto-Sync — Client Script থেকে</h2><p>SystemGuard.php → autoSyncLicense()</p></div>
        </div>

        <div class="alert alert-success">
            <i class="fas fa-circle-check"></i>
            <span><strong>এটাই সবচেয়ে সহজ পদ্ধতি।</strong> Client এর existing script-এই এই code আছে। কোনো আলাদা setup লাগে না।</span>
        </div>

        <div class="card">
            <div class="card-title">কীভাবে কাজ করে</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">১</div>
                    <div class="step-content">
                        <div class="step-title">Client এর প্রতিটি request-এ</div>
                        <p><code>EnforceRemoteLicenseKillSwitch</code> middleware চলে</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">২</div>
                    <div class="step-content">
                        <div class="step-title">LICENSE_KEY চেক করে</div>
                        <p><code>.env</code> এ <code>LICENSE_KEY</code> এবং <code>LICENSE_SIGNATURE</code> আছে কিনা দেখে</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">৩</div>
                    <div class="step-content">
                        <div class="step-title">না থাকলে server-এ call করে</div>
                        <p><code>GET /api/fetch-license?domain=xxx&amp;token=yyy</code> → Server license key + signature দেয়</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">৪</div>
                    <div class="step-content">
                        <div class="step-title">নিজের .env এ লেখে</div>
                        <p><code>base_path('.env')</code> এ <code>LICENSE_KEY</code>, <code>LICENSE_SIGNATURE</code>, <code>LICENSE_EXPIRY</code> save করে</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Client .env এ যা লাগে</div>
            <div class="env-box">
<span class="c"># License Server এর URL (একবার manually দিতে হবে)</span>
<span class="k">LICENSE_REMOTE_BASE_URL</span>=<span class="v">{{ $serverUrl }}</span>

<span class="c"># Domain (optional, না দিলে HTTP_HOST থেকে নেয়)</span>
<span class="k">LICENSE_GUARD_DOMAIN</span>=<span class="v">example.com</span>

<span class="c"># নিচের গুলো Auto-Sync এ automatically set হবে:</span>
<span class="c"># LICENSE_KEY=LIC-XXXX-XXXX-XXXX</span>
<span class="c"># LICENSE_SIGNATURE=abc123...</span>
<span class="c"># LICENSE_EXPIRY=2027-05-15</span>
            </div>
        </div>
    </div>

    <!-- ══ 7. HTTP PUSH ══ -->
    <div class="doc-section" id="http-push">
        <div class="section-title">
            <div class="section-icon si-cyan"><i class="fas fa-paper-plane"></i></div>
            <div><h2>HTTP Push — Server থেকে Client এ</h2><p>license-receiver.php এর মাধ্যমে</p></div>
        </div>

        <div class="alert alert-warn">
            <i class="fas fa-triangle-exclamation"></i>
            <span><strong>এটি সেকেন্ডারি পদ্ধতি।</strong> Auto-Sync কাজ না করলে বা instant save করতে চাইলে এটা ব্যবহার হয়। License generate হলে server automatically try করে।</span>
        </div>

        <div class="card">
            <div class="card-title">Push payload (Server থেকে Client এ যায়)</div>
            <div class="res-block">
{
  <span class="r-key">"license_key"</span>:   <span class="r-str">"LIC-XXXX-XXXX-XXXX"</span>,
  <span class="r-key">"signature_key"</span>: <span class="r-str">"abc123...64chars..."</span>,
  <span class="r-key">"expiry_date"</span>:   <span class="r-str">"2027-05-15"</span>,
  <span class="r-key">"push_token"</span>:    <span class="r-str">"HMAC(domain|license_key, salt)"</span>
}
            </div>
        </div>

        <div class="card">
            <div class="card-title">Client এর license-receiver.php এর কাজ</div>
            <p>১. <code>push_token</code> verify করে (security check)<br>
            ২. <code>.env</code> ফাইলে <code>LICENSE_KEY</code>, <code>LICENSE_SIGNATURE</code> লেখে<br>
            ৩. <code>{"status":"ok"}</code> response দেয়</p>
        </div>
    </div>

    <!-- ══ 8. CLIENT SETUP ══ -->
    <div class="doc-section" id="client-setup">
        <div class="section-title">
            <div class="section-icon si-dark"><i class="fas fa-wrench"></i></div>
            <div><h2>Client Setup</h2><p>নতুন client কে কী করতে হবে</p></div>
        </div>

        <div class="alert alert-success">
            <i class="fas fa-circle-check"></i>
            <span><strong>Auto-Sync ব্যবহারকারীদের জন্য কোনো extra setup লাগে না।</strong> শুধু <code>LICENSE_REMOTE_BASE_URL={{ $serverUrl }}</code> .env এ দিলেই হবে।</span>
        </div>

        <div class="card">
            <div class="card-title">HTTP Push এর জন্য (one-time setup)</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">১</div>
                    <div class="step-content">
                        <div class="step-title">Script ডাউনলোড করুন</div>
                        <p><a href="/admin/licenses/download-receiver" style="color:#4f46e5">Admin → Licenses → Download license-receiver.php</a></p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">২</div>
                    <div class="step-content">
                        <div class="step-title">Website root এ আপলোড করুন</div>
                        <p><code>.env</code> ফাইলের পাশে রাখুন। cPanel-এ <code>public_html/</code> ফোল্ডারে।</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">৩</div>
                    <div class="step-content">
                        <div class="step-title">.env permission ঠিক করুন</div>
                        <p><code>chmod 664 .env</code> বা cPanel থেকে permission 664 দিন।</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">৪</div>
                    <div class="step-content">
                        <div class="step-title">Test করুন</div>
                        <p>Browser-এ <code>https://clientdomain.com/license-receiver.php</code> visit করুন। <code>{"status":"error","message":"Method not allowed."}</code> দেখালে সফল।</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ 9. API: VERIFY ══ -->
    <div class="doc-section" id="api-verify">
        <div class="section-title">
            <div class="section-icon si-blue"><i class="fas fa-check-circle"></i></div>
            <div><h2>API: verify-license</h2><p>Client script লাইসেন্স যাচাই করতে call করে</p></div>
        </div>

        <div class="card">
            <table class="api-table">
                <tr><th>Method</th><th>URL</th><th>Auth</th></tr>
                <tr><td><span class="method post">POST</span></td><td><code>{{ $serverUrl }}/api/verify-license</code></td><td>HMAC signature</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">Request Parameters</div>
            <table class="api-table">
                <tr><th>Parameter</th><th>Type</th><th>বিবরণ</th></tr>
                <tr><td><span class="param">domain</span></td><td>string</td><td>Client এর domain (www ছাড়া)</td></tr>
                <tr><td><span class="param">license_key</span></td><td>string</td><td>LIC-XXXX-XXXX-XXXX format</td></tr>
                <tr><td><span class="param">signature</span></td><td>string</td><td><code>hash_hmac('sha256', domain, HMAC_SALT)</code></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">Success Response</div>
            <div class="res-block">
{
  <span class="r-key">"status"</span>:        <span class="r-str">"valid"</span>,
  <span class="r-key">"blocked"</span>:       <span class="r-str">false</span>,
  <span class="r-key">"domain_name"</span>:   <span class="r-str">"example.com"</span>,
  <span class="r-key">"license_key"</span>:   <span class="r-str">"LIC-XXXX-XXXX-XXXX"</span>,
  <span class="r-key">"expiry_date"</span>:   <span class="r-str">"2027-05-15"</span>,  <span class="r-str" style="color:#475569">// বা "lifetime"</span>
  <span class="r-key">"secure_token"</span>:  <span class="r-str">"md5 token"</span>,
  <span class="r-key">"cache_signal"</span>:  <span class="r-str">"random string"</span>,
  <span class="r-key">"action"</span>:        <span class="r-str">"none"</span>  <span class="r-str" style="color:#475569">// বা "wipe_out"</span>
}
            </div>
        </div>

        <div class="card">
            <div class="card-title">PHP Example (Client Script)</div>
            <div class="code-wrap">
                <div class="code-block">
<span class="key">$domain</span>     = <span class="val">strtolower</span>(preg_replace(<span class="str">'#^www\.#i'</span>, <span class="str">''</span>, <span class="key">$_SERVER</span>[<span class="str">'HTTP_HOST'</span>]));
<span class="key">$licenseKey</span> = env(<span class="str">'LICENSE_KEY'</span>);
<span class="key">$signature</span>  = <span class="val">hash_hmac</span>(<span class="str">'sha256'</span>, <span class="key">$domain</span>, <span class="str">'{{ $hmacSalt }}'</span>);

<span class="key">$response</span>   = Http::post(<span class="str">'{{ $serverUrl }}/api/verify-license'</span>, [
    <span class="str">'domain'</span>      => <span class="key">$domain</span>,
    <span class="str">'license_key'</span> => <span class="key">$licenseKey</span>,
    <span class="str">'signature'</span>   => <span class="key">$signature</span>,
]);

<span class="comment">// Response check করুন</span>
<span class="key">$data</span> = <span class="key">$response</span>->json();
<span class="key">if</span> ((<span class="key">$data</span>[<span class="str">'status'</span>] ?? <span class="str">''</span>) !== <span class="str">'valid'</span>) {
    <span class="val">header</span>(<span class="str">'Location: {{ $serverUrl }}/license?domain='</span>.urlencode(<span class="key">$domain</span>));
    exit;
}
                </div>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </div>
    </div>

    <!-- ══ 10. API: FETCH ══ -->
    <div class="doc-section" id="api-fetch">
        <div class="section-title">
            <div class="section-icon si-green"><i class="fas fa-download"></i></div>
            <div><h2>API: fetch-license</h2><p>Client script নিজের license key fetch করতে call করে (Auto-Sync)</p></div>
        </div>

        <div class="card">
            <table class="api-table">
                <tr><th>Method</th><th>URL</th><th>Rate Limit</th></tr>
                <tr><td><span class="method get">GET</span></td><td><code>{{ $serverUrl }}/api/fetch-license</code></td><td>20 req/min</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">Parameters</div>
            <table class="api-table">
                <tr><th>Parameter</th><th>বিবরণ</th></tr>
                <tr><td><span class="param">domain</span></td><td>Client এর domain</td></tr>
                <tr><td><span class="param">token</span></td><td><code>hash_hmac('sha256', domain + '|' + YYYYmmddHH, HMAC_SALT)</code> — প্রতি ঘন্টায় বদলায়</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">Success Response</div>
            <div class="res-block">
{
  <span class="r-key">"status"</span>:        <span class="r-str">"ok"</span>,
  <span class="r-key">"license_key"</span>:   <span class="r-str">"LIC-XXXX-XXXX-XXXX"</span>,
  <span class="r-key">"signature_key"</span>: <span class="r-str">"abc123...64chars..."</span>,
  <span class="r-key">"expiry_date"</span>:   <span class="r-str">"2027-05-15"</span>
}
            </div>
        </div>

        <div class="alert alert-warn">
            <i class="fas fa-shield-halved"></i>
            <span>Token প্রতি ঘন্টায় বদলায়। Current hour এবং previous hour দুটোই accept করা হয় (clock drift এর জন্য)। Replay attack সম্ভব নয়।</span>
        </div>
    </div>

    <!-- ══ 11. API: CACHE-SIGNAL ══ -->
    <div class="doc-section" id="api-cache">
        <div class="section-title">
            <div class="section-icon si-purple"><i class="fas fa-signal"></i></div>
            <div><h2>API: cache-signal</h2><p>Kill-switch signal — admin চাইলে client script destroy করতে পারে</p></div>
        </div>

        <div class="card">
            <table class="api-table">
                <tr><th>Method</th><th>URL</th></tr>
                <tr><td><span class="method get">GET</span></td><td><code>{{ $serverUrl }}/api/cache-signal?domain=xxx</code></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-title">Response (action = wipe_out)</div>
            <div class="res-block">
{
  <span class="r-key">"action"</span>: <span class="r-str">"wipe_out"</span>  <span class="r-str" style="color:#475569">// সব DB table ও file delete হবে</span>
}
<span class="r-str" style="color:#475569">// Normal:</span>
{
  <span class="r-key">"action"</span>: <span class="r-str">"none"</span>
}
            </div>
        </div>
    </div>

    <!-- ══ 12. SECURITY ══ -->
    <div class="doc-section" id="security">
        <div class="section-title">
            <div class="section-icon si-red"><i class="fas fa-lock"></i></div>
            <div><h2>Security Architecture</h2><p>HMAC + Token সিস্টেম</p></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="card">
                <div class="card-title">🔐 HMAC_SALT</div>
                <p>সব signature calculation এ একই salt ব্যবহার হয়। Server ও Client script এ মিলিয়ে রাখতে হবে।</p>
                <div class="code-wrap" style="margin-top:10px">
                    <div class="code-block" style="padding:12px 14px">
<span class="comment">// Server: LicenseController.php</span>
<span class="key">private const</span> HMAC_SALT = <span class="str">'{{ $hmacSalt }}'</span>;

<span class="comment">// Client: license-receiver.php</span>
<span class="key">define</span>(<span class="str">'HMAC_SALT'</span>, <span class="str">'{{ $hmacSalt }}'</span>);
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-title">✍️ Signature Formula</div>
                <p>প্রতিটি সিস্টেমে ভিন্ন formula:</p>
                <div class="code-wrap" style="margin-top:10px">
                    <div class="code-block" style="padding:12px 14px">
<span class="comment">// verify-license (client verify এ):</span>
hash_hmac(<span class="str">'sha256'</span>, <span class="key">$domain</span>, SALT)

<span class="comment">// License Signature Key:</span>
hash_hmac(<span class="str">'sha256'</span>, <span class="key">$key</span>.<span class="str">'|'</span>.<span class="key">$domain</span>, SALT)

<span class="comment">// fetch-license token:</span>
hash_hmac(<span class="str">'sha256'</span>, <span class="key">$domain</span>.<span class="str">'|'</span>.<span class="key">$hour</span>, SALT)

<span class="comment">// push token:</span>
hash_hmac(<span class="str">'sha256'</span>, <span class="key">$domain</span>.<span class="str">'|'</span>.<span class="key">$key</span>, SALT)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ 13. LIFETIME ══ -->
    <div class="doc-section" id="lifetime">
        <div class="section-title">
            <div class="section-icon si-purple"><i class="fas fa-infinity"></i></div>
            <div><h2>Lifetime License</h2><p>মেয়াদহীন লাইসেন্স</p></div>
        </div>

        <div class="card">
            <div class="card-title">Lifetime কীভাবে কাজ করে</div>
            <p>Validity = <code>0</code> দিলে <code>expiry_date = NULL</code> database-এ। Verify করার সময় NULL expiry মানে কখনো expire হবে না। API response-এ <code>"expiry_date": "lifetime"</code> আসে।</p>
        </div>

        <div class="card">
            <div class="card-title">Table-এ Lifetime badge</div>
            <p>Admin table-এ Expiry column-এ <span style="background:#fdf4ff;color:#7e22ce;padding:2px 10px;border-radius:20px;font-size:12px;font-weight:700;">♾ Lifetime</span> badge দেখায়।</p>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <span>Lifetime license-এর মূল্য: <code>generalsettings.license_lifetime_price</code> (default = <code>license_generation_price × 10</code>)</span>
        </div>
    </div>

    <!-- ══ Footer ══ -->
    <div class="section-divider"></div>
    <div style="text-align:center;padding:20px 0 40px;color:var(--muted);font-size:13px">
        <p>License System Documentation — {{ $gs->title ?? 'Creative Design' }} &copy; {{ date('Y') }}</p>
        <p style="margin-top:6px">
            <a href="/admin/licenses" style="color:var(--primary);text-decoration:none;margin:0 10px"><i class="fas fa-key"></i> License Panel</a>
            <a href="/admin/licenses/setup-guide" style="color:var(--primary);text-decoration:none;margin:0 10px"><i class="fas fa-book-open"></i> Setup Guide</a>
            <a href="/admin/licenses/download-receiver" style="color:var(--primary);text-decoration:none;margin:0 10px"><i class="fas fa-download"></i> Download Receiver</a>
        </p>
    </div>

    </div>{{-- /doc-wrap --}}
</div>{{-- /main --}}

<script>
// Sidebar active link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

// Scroll spy
const sections = document.querySelectorAll('.doc-section[id]');
const navLinks  = document.querySelectorAll('.nav-link[href^="#"]');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
        if (window.scrollY >= s.offsetTop - 80) current = s.id;
    });
    navLinks.forEach(l => {
        l.classList.toggle('active', l.getAttribute('href') === '#' + current);
    });
});

// Copy code
function copyCode(btn) {
    const code = btn.previousElementSibling.innerText;
    navigator.clipboard.writeText(code).then(() => {
        btn.textContent = 'Copied!';
        btn.style.color = '#86efac';
        setTimeout(() => { btn.textContent = 'Copy'; btn.style.color = ''; }, 2000);
    });
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(a.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
</body>
</html>
