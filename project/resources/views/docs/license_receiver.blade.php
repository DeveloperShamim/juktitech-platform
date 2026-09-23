<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>License Receiver — ডকুমেন্টেশন | {{ $gs->title ?? 'Creative Design' }}</title>
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
.badge-stable{background:#dcfce7;color:#166534;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}

.doc-wrap{max-width:860px;padding:36px}

/* ── Section ── */
.doc-section{margin-bottom:52px}
.section-title{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid var(--border)}
.section-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.section-icon.indigo{background:#ede9fe;color:#4f46e5}
.section-icon.green{background:#dcfce7;color:#059669}
.section-icon.orange{background:#fff7ed;color:#d97706}
.section-icon.red{background:#fef2f2;color:#dc2626}
.section-icon.cyan{background:#ecfeff;color:#0891b2}
.section-icon.purple{background:#faf5ff;color:#7c3aed}
.section-icon.gray{background:#f1f5f9;color:#475569}

.section-info h2{font-size:18px;font-weight:800;color:var(--text)}
.section-info p{font-size:13px;color:var(--muted);margin-top:2px}

/* ── Cards ── */
.card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:24px;margin-bottom:16px}
.card-sm{padding:16px 20px}

/* ── Code ── */
.code-block{background:var(--code-bg);border-radius:12px;padding:0;overflow:hidden;margin:14px 0;position:relative}
.code-header{background:#1e293b;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.07)}
.code-lang{font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.8px}
.code-copy{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:#94a3b8;border-radius:6px;padding:4px 12px;font-size:11px;cursor:pointer;transition:.2s}
.code-copy:hover{background:rgba(255,255,255,.15);color:#fff}
.code-copy.copied{background:#059669;border-color:#059669;color:#fff}
pre{padding:20px;font-family:'Courier New',monospace;font-size:13px;line-height:1.8;overflow-x:auto;color:#e2e8f0}
.cm{color:#64748b}
.ck{color:#a5f3fc}
.cv{color:#86efac}
.cs{color:#fda4af}
.cf{color:#fbbf24}
.cn{color:#c4b5fd}

/* ── Inline code ── */
code{background:#f1f5f9;color:#c026d3;padding:2px 7px;border-radius:5px;font-size:13px;font-family:'Courier New',monospace}

/* ── Alert boxes ── */
.alert{border-radius:12px;padding:16px 20px;margin:16px 0;display:flex;gap:12px;font-size:14px;line-height:1.6}
.alert.info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}
.alert.success{background:#f0fdf4;border:1px solid #86efac;color:#166534}
.alert.warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
.alert.danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.alert i{margin-top:2px;flex-shrink:0}

/* ── Steps ── */
.step-list{display:flex;flex-direction:column;gap:16px}
.step{display:flex;gap:16px;align-items:flex-start}
.step-num{width:34px;height:34px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0;margin-top:2px}
.step-body h4{font-size:15px;font-weight:700;color:var(--text);margin-bottom:5px}
.step-body p{font-size:13px;color:var(--muted);line-height:1.7;margin:0}
.step-body p+p{margin-top:6px}

/* ── Table ── */
.doc-table{width:100%;border-collapse:collapse;font-size:13px}
.doc-table th{background:#f8fafc;padding:10px 14px;text-align:left;font-weight:700;color:var(--muted);border:1px solid var(--border);font-size:12px;text-transform:uppercase;letter-spacing:.5px}
.doc-table td{padding:10px 14px;border:1px solid var(--border);color:var(--text);line-height:1.5}
.doc-table tr:hover td{background:#f8fafc}
.badge-req{background:#fef2f2;color:#991b1b;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700}
.badge-opt{background:#f0fdf4;color:#166534;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700}
.badge-type{background:#ede9fe;color:#5b21b6;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;font-family:monospace}

/* ── Flow diagram ── */
.flow{background:linear-gradient(135deg,#0f0c29,#302b63);border-radius:14px;padding:28px;color:#fff;font-family:monospace;font-size:13px;line-height:2.2;text-align:center}
.flow .node{display:inline-block;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:8px 20px;margin:4px}
.flow .node.green-n{background:rgba(5,150,105,.2);border-color:#34d399}
.flow .node.red-n{background:rgba(220,38,38,.2);border-color:#f87171}
.flow .node.blue-n{background:rgba(79,70,229,.2);border-color:#818cf8}
.flow .arrow{color:#a5f3fc;font-size:18px;display:block;margin:2px 0}
.flow .label{font-size:11px;color:rgba(255,255,255,.5);display:block;margin-top:-4px}

/* ── HTTP status tags ── */
.http-badge{display:inline-block;padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;font-family:monospace}
.http-200{background:#dcfce7;color:#166534}
.http-400{background:#fff7ed;color:#c2410c}
.http-403{background:#fef2f2;color:#991b1b}
.http-405{background:#f1f5f9;color:#475569}
.http-500{background:#faf5ff;color:#7e22ce}

/* ── Grid ── */
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}

.divider{height:1px;background:var(--border);margin:32px 0}

@media(max-width:900px){
    .sidebar{transform:translateX(-100%)}
    .main{margin-left:0}
    .grid2{grid-template-columns:1fr}
}
</style>
</head>
<body>

{{-- ═══════════════════════════════
     SIDEBAR
═══════════════════════════════ --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-shield-halved" style="color:#818cf8;margin-right:8px"></i>
        {{ $gs->title ?? 'Creative Design' }}
        <small>License Receiver — Docs</small>
    </div>
    <nav>
        <div class="nav-group">শুরু করুন</div>
        <a href="#overview"     class="nav-link active"><span class="icon"><i class="fas fa-circle-info"></i></span> Overview</a>
        <a href="#how-it-works" class="nav-link"><span class="icon"><i class="fas fa-diagram-project"></i></span> কীভাবে কাজ করে</a>
        <a href="#install"      class="nav-link"><span class="icon"><i class="fas fa-download"></i></span> Installation</a>

        <div class="nav-group">কনফিগারেশন</div>
        <a href="#config"       class="nav-link"><span class="icon"><i class="fas fa-sliders"></i></span> HMAC_SALT সেটআপ</a>
        <a href="#env-paths"    class="nav-link"><span class="icon"><i class="fas fa-folder-open"></i></span> .env Path লজিক</a>

        <div class="nav-group">Security</div>
        <a href="#security"     class="nav-link"><span class="icon"><i class="fas fa-lock"></i></span> Token Verification</a>
        <a href="#responses"    class="nav-link"><span class="icon"><i class="fas fa-code"></i></span> HTTP Responses</a>

        <div class="nav-group">রেফারেন্স</div>
        <a href="#payload"      class="nav-link"><span class="icon"><i class="fas fa-paper-plane"></i></span> Payload Fields</a>
        <a href="#troubleshoot" class="nav-link"><span class="icon"><i class="fas fa-bug"></i></span> Troubleshooting</a>
        <a href="#faq"          class="nav-link"><span class="icon"><i class="fas fa-circle-question"></i></span> FAQ</a>

        <div class="nav-group">অন্যান্য</div>
        <a href="{{ route('docs.license_system') }}" class="nav-link"><span class="icon"><i class="fas fa-book"></i></span> License System Docs</a>
        <a href="{{ route('admin.license.download-receiver') }}" class="nav-link"><span class="icon"><i class="fas fa-file-arrow-down"></i></span> Script Download</a>
    </nav>
</aside>

{{-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ --}}
<main class="main">

    {{-- Top Bar --}}
    <div class="top-bar">
        <h1><i class="fas fa-file-code" style="color:#4f46e5;margin-right:10px"></i>license-receiver.php — ডকুমেন্টেশন</h1>
        <div class="badges">
            <span class="badge-v">v2.0</span>
            <span class="badge-stable">Stable</span>
        </div>
    </div>

    <div class="doc-wrap">

        {{-- ══════════════════════════════════════
             1. OVERVIEW
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="overview">
            <div class="section-title">
                <div class="section-icon indigo"><i class="fas fa-circle-info"></i></div>
                <div class="section-info">
                    <h2>Overview</h2>
                    <p>license-receiver.php কী এবং কেন দরকার</p>
                </div>
            </div>

            <div class="card">
                <p style="font-size:15px;line-height:1.8;color:var(--text)">
                    <strong>license-receiver.php</strong> হলো একটি standalone PHP script যা ক্লায়েন্টের website-এর root folder-এ রাখতে হয়।
                    License server (master site) যখন কাউকে license দেয়, তখন সে এই script-এ একটি POST request পাঠায়।
                    Script-টি সেই request verify করে এবং ক্লায়েন্টের <code>.env</code> ফাইলে <code>LICENSE_KEY</code> ও <code>LICENSE_SIGNATURE</code> স্বয়ংক্রিয়ভাবে সেট করে দেয়।
                </p>
            </div>

            <div class="grid2">
                <div class="card card-sm" style="border-left:4px solid #4f46e5">
                    <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">কারা ব্যবহার করবেন</div>
                    <p style="font-size:13px;line-height:1.7;color:var(--text)">
                        যারা <strong>{{ $gs->title ?? 'Creative Design' }}</strong>-এর script/software কিনেছেন এবং নিজের domain-এ চালাচ্ছেন।
                    </p>
                </div>
                <div class="card card-sm" style="border-left:4px solid #059669">
                    <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">কোথায় রাখবেন</div>
                    <p style="font-size:13px;line-height:1.7;color:var(--text)">
                        আপনার website-এর <strong>root folder</strong>-এ (যেখানে <code>.env</code> ফাইল আছে তার পাশে)।
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             2. HOW IT WORKS
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="how-it-works">
            <div class="section-title">
                <div class="section-icon cyan"><i class="fas fa-diagram-project"></i></div>
                <div class="section-info">
                    <h2>কীভাবে কাজ করে</h2>
                    <p>Push-based license delivery flow</p>
                </div>
            </div>

            <div class="flow">
                <div class="node blue-n">👤 ক্লায়েন্ট পেমেন্ট করে</div>
                <span class="arrow">↓</span>
                <div class="node blue-n">💳 UddoktaPay Payment Gateway</div>
                <span class="arrow">↓</span>
                <div class="node blue-n">⚙️ License Server ({{ $serverUrl }})</div>
                <span class="label">License Key + Signature Key তৈরি করে DB-তে save করে</span>
                <span class="arrow">↓</span>
                <div class="node">📤 HTTP POST → <span style="color:#fda4af">https://clientdomain.com/license-receiver.php</span></div>
                <span class="label">payload: license_key, signature_key, expiry_date, push_token</span>
                <span class="arrow">↓</span>
                <div class="node green-n">✅ license-receiver.php push_token verify করে</div>
                <span class="arrow">↓</span>
                <div class="node green-n">📝 .env ফাইলে LICENSE_KEY ও LICENSE_SIGNATURE লেখে</div>
                <span class="arrow">↓</span>
                <div class="node green-n">🔄 OPcache + Bootstrap Cache Clear</div>
                <span class="arrow">↓</span>
                <div class="node green-n">🎉 সাইট licensed ও সচল!</div>
            </div>

            <div class="alert info" style="margin-top:20px">
                <i class="fas fa-circle-info"></i>
                <div>License server প্রথমে <strong>HTTPS</strong> দিয়ে চেষ্টা করে। HTTPS fail হলে <strong>HTTP</strong> দিয়ে আবার চেষ্টা করে। উভয় ব্যর্থ হলে admin panel-এ manual push করা যায়।</div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             3. INSTALLATION
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="install">
            <div class="section-title">
                <div class="section-icon green"><i class="fas fa-download"></i></div>
                <div class="section-info">
                    <h2>Installation</h2>
                    <p>ধাপে ধাপে ক্লায়েন্ট সাইটে ইনস্টল করুন</p>
                </div>
            </div>

            <div class="step-list">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <h4>Script ডাউনলোড করুন</h4>
                        <p>Admin Panel → License → <strong>"Download Receiver Script"</strong> বাটনে ক্লিক করুন। অথবা সরাসরি এই লিংক থেকে:</p>
                        <div class="code-block">
                            <div class="code-header">
                                <span class="code-lang">URL</span>
                                <button class="code-copy" onclick="copyCode(this)">Copy</button>
                            </div>
                            <pre><span class="cv">{{ $serverUrl }}/admin/licenses/download-receiver</span></pre>
                        </div>
                    </div>
                </div>

                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <h4>সঠিক folder-এ upload করুন</h4>
                        <p>ফাইলটি আপনার website-এর <strong>public root</strong>-এ রাখুন — যেখানে <code>.env</code> আছে তার পাশে নয়, বরং <code>public_html/</code> বা <code>public/</code> folder-এ।</p>
                        <div class="code-block">
                            <div class="code-header">
                                <span class="code-lang">Directory Structure</span>
                            </div>
                            <pre><span class="cm"># cPanel / Shared Hosting</span>
public_html/
├── <span class="cv">license-receiver.php</span>   <span class="cm">← এখানে রাখুন</span>
├── index.php
├── .htaccess
└── ..

<span class="cm"># Laravel (public folder)</span>
your-project/
├── public/
│   ├── <span class="cv">license-receiver.php</span>   <span class="cm">← এখানে রাখুন</span>
│   └── index.php
├── .env                   <span class="cm">← এটি auto-update হবে</span>
└── ..</pre>
                        </div>
                    </div>
                </div>

                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <h4>File permission চেক করুন</h4>
                        <p><code>.env</code> ফাইলটি writable হতে হবে। cPanel File Manager দিয়ে <code>.env</code> ফাইলের permission <code>644</code> বা <code>664</code> দিন।</p>
                        <div class="code-block">
                            <div class="code-header">
                                <span class="code-lang">Terminal</span>
                                <button class="code-copy" onclick="copyCode(this)">Copy</button>
                            </div>
                            <pre>chmod 644 .env</pre>
                        </div>
                    </div>
                </div>

                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-body">
                        <h4>Browser-এ Test করুন</h4>
                        <p>Browser-এ <code>https://yourdomain.com/license-receiver.php</code> ওপেন করুন। নিচের response আসলে installation সফল:</p>
                        <div class="code-block">
                            <div class="code-header">
                                <span class="code-lang">Expected Response (GET Request)</span>
                            </div>
                            <pre><span class="cs">{"status":"error","message":"Method not allowed."}</span></pre>
                        </div>
                        <div class="alert success" style="margin-top:12px">
                            <i class="fas fa-circle-check"></i>
                            <div><strong>"Method not allowed"</strong> দেখালে বুঝবেন file সঠিকভাবে কাজ করছে — এটা GET request reject করছে, যা expected।</div>
                        </div>
                    </div>
                </div>

                <div class="step">
                    <div class="step-num">5</div>
                    <div class="step-body">
                        <h4>Admin Panel থেকে Push করুন</h4>
                        <p>Admin Panel → License List → আপনার domain খুঁজুন → <strong>"Push to Site"</strong> বাটনে ক্লিক করুন। <code>✅ Push সফল</code> দেখালে সব ঠিক আছে।</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             4. CONFIG — HMAC SALT
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="config">
            <div class="section-title">
                <div class="section-icon orange"><i class="fas fa-sliders"></i></div>
                <div class="section-info">
                    <h2>HMAC_SALT কনফিগারেশন</h2>
                    <p>License server ও receiver script-এর salt মিলতে হবে</p>
                </div>
            </div>

            <div class="alert warning">
                <i class="fas fa-triangle-exclamation"></i>
                <div>
                    Admin Panel থেকে <strong>Download</strong> করা script-এ HMAC_SALT স্বয়ংক্রিয়ভাবে সেট থাকে। কিন্তু manually file copy করলে নিচের salt মিলিয়ে নিন।
                </div>
            </div>

            <div class="card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <div style="font-size:13px;font-weight:700;color:var(--muted)">License Server-এর Current HMAC_SALT</div>
                    <span style="background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700">⚠️ গোপন রাখুন</span>
                </div>
                <div class="code-block" style="margin:0">
                    <div class="code-header">
                        <span class="code-lang">PHP — license-receiver.php এ রাখুন</span>
                        <button class="code-copy" onclick="copyCode(this)">Copy</button>
                    </div>
                    <pre><span class="cf">define</span>(<span class="cs">'HMAC_SALT'</span>, <span class="cv">'{{ $hmacSalt }}'</span>);</pre>
                </div>
            </div>

            <div class="alert danger" style="margin-top:4px">
                <i class="fas fa-shield-halved"></i>
                <div>
                    <strong>HMAC_SALT</strong> কখনো publicly share করবেন না। এটি দিয়ে push token তৈরি হয় — এটা ফাঁস হলে যে কেউ আপনার .env overwrite করতে পারবে।
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             5. ENV PATH LOGIC
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="env-paths">
            <div class="section-title">
                <div class="section-icon gray"><i class="fas fa-folder-open"></i></div>
                <div class="section-info">
                    <h2>.env Path Auto-Detection</h2>
                    <p>Script নিজেই .env খুঁজে নেয়</p>
                </div>
            </div>

            <p style="font-size:14px;color:var(--muted);margin-bottom:16px;line-height:1.7">
                Script-টি নিচের ক্রমে <code>.env</code> ফাইল খোঁজে। প্রথম যেটা <strong>readable ও writable</strong> পাবে সেটাই ব্যবহার করবে:
            </p>

            <table class="doc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Path</th>
                        <th>কখন কাজ করে</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1</strong></td>
                        <td><code>dirname(__DIR__)/.env</code></td>
                        <td>script <code>public/</code>-এ আছে, Laravel root-এ <code>.env</code> — <strong>সবচেয়ে সাধারণ</strong></td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td><code>dirname(dirname(__DIR__))/.env</code></td>
                        <td>script দুই স্তর ভেতরে থাকলে (subdirectory)</td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td><code>__DIR__/../.env</code></td>
                        <td>same-folder fallback</td>
                    </tr>
                </tbody>
            </table>

            <div class="alert info" style="margin-top:16px">
                <i class="fas fa-circle-info"></i>
                <div>কোনো path-এ <code>.env</code> না পেলে script <code>500</code> error return করে এবং কিছু লেখে না। এতে আপনার server নিরাপদ থাকে।</div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             6. SECURITY — TOKEN VERIFICATION
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="security">
            <div class="section-title">
                <div class="section-icon red"><i class="fas fa-lock"></i></div>
                <div class="section-info">
                    <h2>Security — Token Verification</h2>
                    <p>HMAC SHA-256 দিয়ে প্রতিটি request authenticate করা হয়</p>
                </div>
            </div>

            <div class="card">
                <h4 style="font-size:14px;font-weight:700;margin-bottom:12px">Push Token কীভাবে তৈরি হয়</h4>
                <div class="code-block" style="margin:0">
                    <div class="code-header">
                        <span class="code-lang">PHP — License Server (LicenseService.php)</span>
                    </div>
                    <pre><span class="cm">// Server-side token generation</span>
<span class="ck">$domain</span>    = <span class="cs">'example.com'</span>;   <span class="cm">// www ছাড়া, lowercase</span>
<span class="ck">$licenseKey</span> = <span class="cs">'LIC-ABCD-EFGH-IJKL'</span>;
<span class="ck">$HMAC_SALT</span> = <span class="cs">'{{ $hmacSalt }}'</span>;

<span class="ck">$pushToken</span> = <span class="cf">hash_hmac</span>(<span class="cs">'sha256'</span>, <span class="ck">$domain</span> . <span class="cs">'|'</span> . <span class="ck">$licenseKey</span>, <span class="ck">$HMAC_SALT</span>);</pre>
                </div>
            </div>

            <div class="card" style="margin-top:12px">
                <h4 style="font-size:14px;font-weight:700;margin-bottom:12px">Receiver Script-এ Verification</h4>
                <div class="code-block" style="margin:0">
                    <div class="code-header">
                        <span class="code-lang">PHP — license-receiver.php</span>
                    </div>
                    <pre><span class="cm">// Client site HTTP_HOST থেকে domain বের করা</span>
<span class="ck">$domain</span> = <span class="cf">strtolower</span>(<span class="cf">preg_replace</span>(<span class="cs">'#^www\.#i'</span>, <span class="cs">''</span>, <span class="ck">$_SERVER</span>[<span class="cs">'HTTP_HOST'</span>]));

<span class="cm">// Expected token compute</span>
<span class="ck">$expectedToken</span> = <span class="cf">hash_hmac</span>(<span class="cs">'sha256'</span>, <span class="ck">$domain</span> . <span class="cs">'|'</span> . <span class="ck">$licenseKey</span>, <span class="cf">HMAC_SALT</span>);

<span class="cm">// Timing-safe comparison</span>
<span class="cf">if</span> (!<span class="cf">hash_equals</span>(<span class="ck">$expectedToken</span>, <span class="ck">$pushToken</span>)) {
    <span class="cf">http_response_code</span>(<span class="cn">403</span>);
    <span class="cf">echo json_encode</span>([<span class="cs">'status'</span> => <span class="cs">'error'</span>, <span class="cs">'message'</span> => <span class="cs">'Invalid token.'</span>]);
    <span class="cf">exit</span>;
}</pre>
                </div>
            </div>

            <div class="alert success" style="margin-top:16px">
                <i class="fas fa-shield-halved"></i>
                <div>
                    <strong>hash_equals()</strong> ব্যবহার করা হয় timing attack প্রতিরোধ করতে। সাধারণ <code>===</code> comparison-এ timing side-channel attack সম্ভব।
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             7. HTTP RESPONSES
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="responses">
            <div class="section-title">
                <div class="section-icon purple"><i class="fas fa-code"></i></div>
                <div class="section-info">
                    <h2>HTTP Responses</h2>
                    <p>Script থেকে কী কী response আসতে পারে</p>
                </div>
            </div>

            <table class="doc-table">
                <thead>
                    <tr>
                        <th>HTTP Code</th>
                        <th>status</th>
                        <th>কারণ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="http-badge http-200">200 OK</span></td>
                        <td><code>ok</code></td>
                        <td>লাইসেন্স সফলভাবে <code>.env</code>-এ সেইভ হয়েছে</td>
                    </tr>
                    <tr>
                        <td><span class="http-badge http-400">400 Bad Request</span></td>
                        <td><code>error</code></td>
                        <td><code>license_key</code> বা <code>push_token</code> missing</td>
                    </tr>
                    <tr>
                        <td><span class="http-badge http-403">403 Forbidden</span></td>
                        <td><code>error</code></td>
                        <td>Push token invalid — HMAC mismatch</td>
                    </tr>
                    <tr>
                        <td><span class="http-badge http-405">405 Method Not Allowed</span></td>
                        <td><code>error</code></td>
                        <td>GET request এসেছে, শুধু POST accept হয়</td>
                    </tr>
                    <tr>
                        <td><span class="http-badge http-500">500 Server Error</span></td>
                        <td><code>error</code></td>
                        <td><code>.env</code> file পাওয়া যায়নি বা writable নয়</td>
                    </tr>
                </tbody>
            </table>

            <div class="card" style="margin-top:20px">
                <h4 style="font-size:14px;font-weight:700;margin-bottom:12px">Success Response Example</h4>
                <div class="code-block" style="margin:0">
                    <div class="code-header">
                        <span class="code-lang">JSON Response — 200 OK</span>
                    </div>
                    <pre>{
    <span class="ck">"status"</span>:  <span class="cs">"ok"</span>,
    <span class="ck">"message"</span>: <span class="cs">"License saved successfully."</span>,
    <span class="ck">"domain"</span>:  <span class="cs">"example.com"</span>
}</pre>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             8. PAYLOAD FIELDS
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="payload">
            <div class="section-title">
                <div class="section-icon cyan"><i class="fas fa-paper-plane"></i></div>
                <div class="section-info">
                    <h2>Payload Fields</h2>
                    <p>License server যে data POST করে</p>
                </div>
            </div>

            <table class="doc-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>বিবরণ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>license_key</code></td>
                        <td><span class="badge-type">string</span></td>
                        <td><span class="badge-req">Required</span></td>
                        <td>Generated license key — e.g. <code>LIC-ABCD-EFGH-IJKL</code></td>
                    </tr>
                    <tr>
                        <td><code>signature_key</code></td>
                        <td><span class="badge-type">string</span></td>
                        <td><span class="badge-req">Required</span></td>
                        <td>HMAC-SHA256 signature of <code>licenseKey|domain</code></td>
                    </tr>
                    <tr>
                        <td><code>push_token</code></td>
                        <td><span class="badge-type">string</span></td>
                        <td><span class="badge-req">Required</span></td>
                        <td>Security token — HMAC of <code>domain|licenseKey</code></td>
                    </tr>
                    <tr>
                        <td><code>expiry_date</code></td>
                        <td><span class="badge-type">string</span></td>
                        <td><span class="badge-opt">Optional</span></td>
                        <td><code>YYYY-MM-DD</code> অথবা <code>lifetime</code> (মেয়াদহীন)</td>
                    </tr>
                </tbody>
            </table>

            <div class="card" style="margin-top:20px">
                <h4 style="font-size:14px;font-weight:700;margin-bottom:12px">.env-এ যা লেখা হয়</h4>
                <div class="code-block" style="margin:0">
                    <div class="code-header">
                        <span class="code-lang">.env</span>
                        <button class="code-copy" onclick="copyCode(this)">Copy</button>
                    </div>
                    <pre><span class="cm"># License Server থেকে auto-set হয়</span>
<span class="ck">LICENSE_KEY</span>=<span class="cv">LIC-ABCD-EFGH-IJKL</span>
<span class="ck">LICENSE_SIGNATURE</span>=<span class="cv">a3f9b2c1d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1</span>
<span class="ck">LICENSE_EXPIRY</span>=<span class="cv">lifetime</span>   <span class="cm"># লাইফটাইম হলে এটি লেখা হয় না</span></pre>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             9. TROUBLESHOOTING
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="troubleshoot">
            <div class="section-title">
                <div class="section-icon red"><i class="fas fa-bug"></i></div>
                <div class="section-info">
                    <h2>Troubleshooting</h2>
                    <p>সাধারণ সমস্যা ও সমাধান</p>
                </div>
            </div>

            <div class="card" style="margin-bottom:14px;border-left:4px solid #dc2626">
                <h4 style="font-size:14px;font-weight:700;color:#dc2626;margin-bottom:8px">
                    <i class="fas fa-circle-xmark me-2"></i> Push fail: <code>cURL error 6: Could not resolve host</code>
                </h4>
                <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">
                    Domain-টি License server থেকে accessible নয় — DNS propagate হয়নি, বা domain live নয়। কিছুক্ষণ পর Admin Panel থেকে manual push করুন।
                </p>
            </div>

            <div class="card" style="margin-bottom:14px;border-left:4px solid #dc2626">
                <h4 style="font-size:14px;font-weight:700;color:#dc2626;margin-bottom:8px">
                    <i class="fas fa-circle-xmark me-2"></i> Push fail: <code>http_status: 404</code>
                </h4>
                <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">
                    <code>license-receiver.php</code> ফাইলটি সঠিক জায়গায় নেই বা upload হয়নি। <code>https://yourdomain.com/license-receiver.php</code> browser-এ ওপেন করে দেখুন।
                </p>
            </div>

            <div class="card" style="margin-bottom:14px;border-left:4px solid #d97706">
                <h4 style="font-size:14px;font-weight:700;color:#d97706;margin-bottom:8px">
                    <i class="fas fa-triangle-exclamation me-2"></i> <code>403 Forbidden: Invalid token</code>
                </h4>
                <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">
                    <code>HMAC_SALT</code> mismatch। Admin Panel থেকে script re-download করুন — downloaded script-এ HMAC_SALT স্বয়ংক্রিয়ভাবে সেট থাকে।
                </p>
            </div>

            <div class="card" style="margin-bottom:14px;border-left:4px solid #7c3aed">
                <h4 style="font-size:14px;font-weight:700;color:#7c3aed;margin-bottom:8px">
                    <i class="fas fa-triangle-exclamation me-2"></i> <code>500: .env not found or not writable</code>
                </h4>
                <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">
                    Script <code>.env</code> খুঁজে পাচ্ছে না বা write permission নেই।
                    <br>① File Manager-এ <code>.env</code> এর permission <code>644</code> করুন।
                    <br>② <code>license-receiver.php</code> সঠিক directory-তে আছে কিনা দেখুন।
                </p>
            </div>

            <div class="card" style="border-left:4px solid #059669">
                <h4 style="font-size:14px;font-weight:700;color:#059669;margin-bottom:8px">
                    <i class="fas fa-circle-check me-2"></i> Push সফল হলো কিন্তু site এখনো কাজ করছে না
                </h4>
                <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">
                    PHP OPcache এবং Laravel config cache clear না হওয়ার কারণ হতে পারে। Terminal-এ <code>php artisan config:clear</code> চালান, অথবা hosting control panel থেকে PHP cache clear করুন।
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             10. FAQ
        ══════════════════════════════════════ --}}
        <div class="doc-section" id="faq">
            <div class="section-title">
                <div class="section-icon gray"><i class="fas fa-circle-question"></i></div>
                <div class="section-info">
                    <h2>FAQ</h2>
                    <p>সচরাচর জিজ্ঞাসা</p>
                </div>
            </div>

            <div class="card card-sm" style="margin-bottom:12px">
                <div style="font-weight:700;color:var(--text);margin-bottom:6px">
                    <i class="fas fa-question-circle" style="color:var(--primary);margin-right:6px"></i>
                    license-receiver.php কি সবসময় server-এ থাকতে হবে?
                </div>
                <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.7">
                    License সফলভাবে push হওয়ার পরে এটি রেখে দেওয়া ভালো — ভবিষ্যতে renewal বা re-push-এ কাজে লাগে। তবে security-সচেতনরা push সম্পন্ন হলে delete বা rename করতে পারেন।
                </p>
            </div>

            <div class="card card-sm" style="margin-bottom:12px">
                <div style="font-weight:700;color:var(--text);margin-bottom:6px">
                    <i class="fas fa-question-circle" style="color:var(--primary);margin-right:6px"></i>
                    যদি domain <code>www</code> সহ হয়?
                </div>
                <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.7">
                    Script স্বয়ংক্রিয়ভাবে <code>www.</code> prefix সরিয়ে নেয়। License server-ও same normalization করে। তাই <code>www.example.com</code> ও <code>example.com</code> দুটোই কাজ করবে।
                </p>
            </div>

            <div class="card card-sm" style="margin-bottom:12px">
                <div style="font-weight:700;color:var(--text);margin-bottom:6px">
                    <i class="fas fa-question-circle" style="color:var(--primary);margin-right:6px"></i>
                    Lifetime license হলে <code>LICENSE_EXPIRY</code> লেখা হয়?
                </div>
                <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.7">
                    না। Lifetime license-এর ক্ষেত্রে <code>expiry_date</code> field <code>null</code> বা <code>lifetime</code> আসে — সেক্ষেত্রে <code>LICENSE_EXPIRY</code> <code>.env</code>-এ লেখা হয় না।
                </p>
            </div>

            <div class="card card-sm" style="margin-bottom:12px">
                <div style="font-weight:700;color:var(--text);margin-bottom:6px">
                    <i class="fas fa-question-circle" style="color:var(--primary);margin-right:6px"></i>
                    Push fail হলে কি license হারিয়ে যাবে?
                </div>
                <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.7">
                    না। License সবসময় License Server-এর database-এ সেইভ থাকে। Admin Panel → License List থেকে যেকোনো সময় manually push করা যায়।
                </p>
            </div>

            <div class="card card-sm">
                <div style="font-weight:700;color:var(--text);margin-bottom:6px">
                    <i class="fas fa-question-circle" style="color:var(--primary);margin-right:6px"></i>
                    Localhost বা local development-এ push কাজ করে না কেন?
                </div>
                <p style="font-size:13px;color:var(--muted);margin:0;line-height:1.7">
                    License server (<code>{{ $serverUrl }}</code>) publicly accessible একটি server থেকে run করে। সে <code>localhost</code> resolve করতে পারে না।
                    Local development-এ Admin Panel থেকে <code>site_path</code> দিয়ে লাইসেন্স তৈরি করুন (direct .env write)।
                </p>
            </div>
        </div>

        <div class="divider"></div>

        <div style="text-align:center;padding:20px 0 40px">
            <p style="font-size:13px;color:var(--muted)">
                License Receiver Docs — <strong>{{ $gs->title ?? 'Creative Design' }}</strong> &copy; {{ date('Y') }}
                &nbsp;|&nbsp;
                <a href="{{ route('docs.license_system') }}" style="color:var(--primary);text-decoration:none">License System Docs →</a>
            </p>
        </div>

    </div>
</main>

<script>
// Sidebar active link on scroll
const sections = document.querySelectorAll('.doc-section');
const navLinks  = document.querySelectorAll('.nav-link');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
    navLinks.forEach(l => {
        l.classList.remove('active');
        if (l.getAttribute('href') === '#' + current) l.classList.add('active');
    });
});

// Copy code
function copyCode(btn) {
    const pre = btn.closest('.code-block').querySelector('pre');
    navigator.clipboard.writeText(pre.innerText.trim()).then(() => {
        btn.textContent = '✓ Copied!';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 2000);
    });
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        const el = document.querySelector(a.getAttribute('href'));
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
</body>
</html>
