@extends('layouts.admin')

@section('styles')
<style>
.guide-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.06); border: none; }
    .step-num { width: 36px; height: 36px; background: #4f46e5; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; flex-shrink: 0; }
    .step-body { flex: 1; }
    .step-title { font-weight: 700; font-size: 16px; color: #1e293b; margin-bottom: 6px; }
    .step-desc  { font-size: 14px; color: #64748b; margin: 0; line-height: 1.7; }
    .code-box   { background: #0f172a; color: #e2e8f0; border-radius: 10px; padding: 16px 20px; font-family: monospace; font-size: 13px; line-height: 1.8; position: relative; margin-top: 10px; }
    .code-box .comment { color: #64748b; }
    .code-box .key     { color: #a5f3fc; }
    .code-box .val     { color: #86efac; }
    .code-box .url     { color: #fda4af; text-decoration: underline; }

    .copy-btn { position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); color: #fff; border-radius: 6px; padding: 4px 10px; font-size: 12px; cursor: pointer; }
    .copy-btn:hover { background: rgba(255,255,255,.2); }

    .alert-info-custom { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 14px 18px; color: #1e40af; font-size: 14px; }
    .alert-success-custom { background: #f0fdf4; border: 1px solid #86efac; border-radius: 12px; padding: 14px 18px; color: #166534; font-size: 14px; }
    .alert-warning-custom { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 14px 18px; color: #92400e; font-size: 14px; }

    .divider { height: 1px; background: #f1f5f9; margin: 28px 0; }

    .faq-item { border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 12px; }
    .faq-q { font-weight: 700; color: #1e293b; font-size: 14px; margin-bottom: 6px; }
    .faq-a { font-size: 13px; color: #64748b; margin: 0; }

    .how-diagram { background: linear-gradient(135deg,#0f0c29,#302b63); border-radius: 14px; padding: 24px; color: #fff; font-family: monospace; font-size: 13px; line-height: 2; }
    .how-diagram .arrow { color: #a5f3fc; }
    .how-diagram .node  { background: rgba(255,255,255,.08); border-radius: 8px; padding: 6px 14px; display: inline-block; }
    .how-diagram .green { color: #86efac; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-book-open',
        'title' => __('Client Setup Guide'),
        'subtitle' => __('license-receiver.php setup instructions'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('Licenses'), 'url' => route('admin.license.index')], ['label' => __('Setup Guide'), 'url' => null]],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="font-weight-bold mb-1"><i class="fas fa-book-open text-primary mr-2"></i>Client Setup Guide</h5>
            <p class="text-muted mb-0">Client এর সাইটে license-receiver.php সেটআপ করার নির্দেশিকা</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.license.download-receiver') }}" class="btn btn-success shadow-sm font-weight-bold">
                <i class="fas fa-download mr-1"></i> license-receiver.php ডাউনলোড
            </a>
            <a href="{{ route('admin.license.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> লাইসেন্স লিস্ট
            </a>
        </div>
    </div>

    {{-- How it works --}}
    <div class="guide-card p-4 mb-4">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-diagram-project text-indigo mr-2"></i>কীভাবে কাজ করে (Path ছাড়াই)</h6>
        <div class="how-diagram">
<span class="node">আপনার License Server</span>  <span class="arrow">──────────────────────────────────→</span>  <span class="node">Client এর Website</span>
        লাইসেন্স তৈরি হয়                                                      license-receiver.php
              │                                                                          │
              │  POST <span class="arrow">https://clientdomain.com/license-receiver.php</span>  ←───────────┘
              │  { license_key, signature_key, push_token }
              │
              └─→ <span class="green">Client এর .env ফাইলে নিজেই লেখে ✅</span>
                  LICENSE_KEY=LIC-XXXX-XXXX-XXXX
                  LICENSE_SIGNATURE=abc123...
        </div>
        <div class="alert-info-custom mt-3">
            <i class="fas fa-lightbulb mr-2"></i>
            <strong>সুবিধা:</strong> আপনার file path জানার কোনো দরকার নেই। Client এর server নিজেই লেখে।
            Client কে শুধু <strong>একবার</strong> <code>license-receiver.php</code> তাদের site root-এ রাখতে হবে।
        </div>
    </div>

    {{-- Steps --}}
    <div class="guide-card p-4 mb-4">
        <h6 class="font-weight-bold mb-4"><i class="fas fa-list-ol text-primary mr-2"></i>Client কে কী করতে হবে (মাত্র ৩টি ধাপ)</h6>

        {{-- Step 1 --}}
        <div class="d-flex gap-3 mb-4">
            <div class="step-num">১</div>
            <div class="step-body">
                <div class="step-title">license-receiver.php ডাউনলোড করুন</div>
                <p class="step-desc">নিচের বাটন থেকে script ডাউনলোড করুন এবং client কে পাঠান (email বা WhatsApp)।</p>
                <a href="{{ route('admin.license.download-receiver') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="fas fa-download mr-1"></i> license-receiver.php ডাউনলোড
                </a>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Step 2 --}}
        <div class="d-flex gap-3 mb-4">
            <div class="step-num">২</div>
            <div class="step-body">
                <div class="step-title">File টি website root-এ আপলোড করুন</div>
                <p class="step-desc"><code>.env</code> ফাইলের পাশে (একই ফোল্ডারে) <code>license-receiver.php</code> রাখুন।</p>
                <div class="code-box">
<span class="comment"># ফাইল structure এভাবে হবে:</span>
<span class="key">website/</span>
├── <span class="val">.env</span>                 <span class="comment">← এখানে LICENSE_KEY সেভ হবে</span>
├── <span class="val">license-receiver.php</span>  <span class="comment">← এই ফাইলটি রাখুন</span>
├── index.php
└── ...
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Step 3 --}}
        <div class="d-flex gap-3 mb-4">
            <div class="step-num">৩</div>
            <div class="step-body">
                <div class="step-title">.env ফাইলে write permission নিশ্চিত করুন</div>
                <p class="step-desc">Linux/cPanel server এ .env ফাইলের permission হতে হবে <code>664</code> বা <code>666</code>।</p>
                <div class="code-box">
<span class="comment"># Terminal/SSH দিয়ে:</span>
chmod 664 .env

<span class="comment"># অথবা cPanel File Manager এ:</span>
<span class="val">.env → Right Click → Change Permissions → 664</span>
                </div>
            </div>
        </div>

        <div class="alert-success-custom">
            <i class="fas fa-circle-check mr-2"></i>
            <strong>এতটুকুই!</strong> এরপর থেকে আপনি যখনই এই client এর জন্য লাইসেন্স তৈরি বা push করবেন,
            তাদের <code>.env</code> ফাইল স্বয়ংক্রিয়ভাবে আপডেট হবে।
        </div>
    </div>

    {{-- Test --}}
    <div class="guide-card p-4 mb-4">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-flask text-warning mr-2"></i>Test করুন</h6>
        <p class="text-muted">Client <code>license-receiver.php</code> সঠিকভাবে রেখেছে কিনা browser থেকে চেক করুন:</p>
        <div class="code-box">
<span class="comment"># Browser এ visit করুন:</span>
<span class="url">https://clientdomain.com/license-receiver.php</span>

<span class="comment"># সঠিক হলে দেখাবে:</span>
<span class="val">{"status":"error","message":"Method not allowed."}</span>

<span class="comment"># এই response মানে script কাজ করছে ✅</span>
        </div>
        <div class="alert-warning-custom mt-3">
            <i class="fas fa-triangle-exclamation mr-2"></i>
            <strong>404 error দেখালে</strong> → file টি সঠিক জায়গায় নেই।<br>
            <strong>500 error দেখালে</strong> → .env এ write permission নেই।
        </div>
    </div>

    {{-- Client site এর .env এ যা যোগ করতে হবে (পুরনো সিস্টেম) --}}
    <div class="guide-card p-4 mb-4">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-code text-secondary mr-2"></i>পুরনো License System → নতুন Redirect URL</h6>
        <p class="text-muted">Client এর পুরনো license check script এ redirect URL আপডেট করুন:</p>
        <div class="code-box">
<span class="comment">// ❌ পুরনো (আর কাজ করবে না):</span>
header('Location: <span class="url">{{ $licenseServerUrl }}/license</span>');

<span class="comment">// ✅ নতুন (domain সহ):</span>
<span class="key">$domain</span> = <span class="val">$_SERVER['HTTP_HOST']</span> ?? '';
header('Location: <span class="url">{{ $licenseServerUrl }}/license?domain=</span>' . urlencode(<span class="key">$domain</span>));
        </div>
    </div>

    {{-- FAQ --}}
    <div class="guide-card p-4 mb-4">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-circle-question text-info mr-2"></i>সাধারণ প্রশ্ন</h6>

        <div class="faq-item">
            <div class="faq-q">❓ Client যদি cPanel ব্যবহার করে?</div>
            <p class="faq-a">cPanel → File Manager → public_html ফোল্ডারে <code>license-receiver.php</code> আপলোড করুন। .env file এর permission <strong>664</strong> করুন।</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">❓ Client এর site Laravel/WordPress/CodeIgniter?</div>
            <p class="faq-a"><code>license-receiver.php</code> সবসময় <strong>project root</strong> এ রাখুন — Laravel এর জন্য <code>public/</code> নয়, বরং <code>.env</code> এর পাশে।</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">❓ Push ব্যর্থ হলে কী করব?</div>
            <p class="faq-a">License list এ গিয়ে সেই license এর <span class="badge bg-success"><i class="fas fa-paper-plane"></i> Push</span> বাটন ক্লিক করুন। এটি যেকোনো সময় manually push করতে পারবেন।</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">❓ Client এর .env এ কী লেখা হয়?</div>
            <p class="faq-a">মাত্র ৩টি line যোগ হয়:</p>
            <div class="code-box mt-2">
LICENSE_KEY=LIC-XXXX-XXXX-XXXX
LICENSE_SIGNATURE=a1b2c3d4...64chars...
LICENSE_EXPIRY=2027-05-15
            </div>
        </div>
    </div>


</div>
@endsection
