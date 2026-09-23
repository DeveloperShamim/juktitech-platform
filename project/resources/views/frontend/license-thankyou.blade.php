@extends('layouts.front')

@section('meta')
    <title>পেমেন্ট {{ $type === 'success' ? 'সফল' : ($type === 'pending' ? 'পেন্ডিং' : 'ব্যর্থ') }} — {{ $gs->title ?? 'Creative Design' }}</title>
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('contents')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
    .ty-page { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#0f0c29,#302b63,#24243e); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 16px; }
    .ty-card { background:#fff; border-radius:24px; box-shadow:0 40px 120px rgba(0,0,0,.4); width:100%; max-width:460px; overflow:hidden; animation:fadeUp .6s ease-out; text-align:center; }
    @keyframes fadeUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }

    .ty-top-success { background:linear-gradient(135deg,#059669,#10b981); padding:44px 32px 32px; }
    .ty-top-pending  { background:linear-gradient(135deg,#d97706,#f59e0b); padding:44px 32px 32px; }
    .ty-top-error    { background:linear-gradient(135deg,#dc2626,#ef4444); padding:44px 32px 32px; }

    .ty-icon { width:80px; height:80px; background:rgba(255,255,255,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; }
    .ty-icon i { font-size:34px; color:#fff; }
    .ty-top-success h2, .ty-top-pending h2, .ty-top-error h2 { color:#fff; font-size:22px; font-weight:800; margin:0 0 8px; }
    .ty-top-success p, .ty-top-pending p, .ty-top-error p  { color:rgba(255,255,255,.85); font-size:14px; margin:0; line-height:1.6; }

    .ty-body { padding:32px; }
    .ty-body p { font-size:14px; color:#6b7280; line-height:1.7; }
    .btn-home { background:linear-gradient(135deg,#4f46e5,var(--primary-color)); color:#fff; border:none; padding:12px 32px; border-radius:50px; font-size:15px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-block; transition:.3s; margin-top:8px; }
    .btn-home:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(79,70,229,.35); color:#fff; }
    .ty-footer { padding:16px 32px 24px; font-size:12px; color:#9ca3af; }

    /* License Key Box */
    .lic-info-box { background:#f0fdf4; border:1.5px solid #86efac; border-radius:14px; padding:20px; text-align:left; margin-top:20px; }
    .lic-info-box h6 { font-size:13px; font-weight:700; color:#166534; margin:0 0 14px; display:flex; align-items:center; gap:6px; }
    .lic-row { margin-bottom:12px; }
    .lic-row label { font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:4px; }
    .lic-copy-wrap { display:flex; align-items:center; gap:8px; }
    .lic-val { font-family:monospace; font-size:12px; background:#fff; border:1px solid #d1fae5; border-radius:8px; padding:8px 12px; color:#065f46; word-break:break-all; flex:1; }
    .btn-copy { background:#10b981; color:#fff; border:none; border-radius:8px; padding:8px 14px; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap; transition:.2s; }
    .btn-copy:hover { background:#059669; }
    .btn-copy.copied { background:var(--secondary-color); }
    .push-fail-note { background:var(--primary-bg-lighter); border:1px solid #fed7aa; border-radius:10px; padding:12px 14px; font-size:12px; color:#9a3412; margin-top:14px; display:flex; gap:8px; align-items:flex-start; }
    .env-code { background:#1e1e2e; border-radius:10px; padding:14px; margin-top:12px; text-align:left; }
    .env-code pre { color:#a6e3a1; font-size:11px; margin:0; line-height:1.7; font-family:monospace; }
</style>

<div class="ty-page">
<div class="ty-card">

    @if($type === 'success')
    <div class="ty-top-success">
        <div class="ty-icon"><i class="fas fa-circle-check"></i></div>
        <h2>পেমেন্ট সফল! 🎉</h2>
        <p>{{ $message }}</p>
    </div>
    <div class="ty-body">

        @if(!empty($licenseData))
            @if($licenseData['push_success'] ?? false)
                <p>আপনার লাইসেন্স কী তৈরি হয়েছে এবং <strong>আপনার সাইটে স্বয়ংক্রিয়ভাবে সেট</strong> হয়ে গেছে। এখন আপনার সাইট পুনরায় লোড করুন।</p>
            @else
                <p>আপনার লাইসেন্স কী তৈরি হয়েছে। নিচের কী গুলো সংরক্ষণ করুন।</p>
            @endif

            {{-- License Key Info Box --}}
            <div class="lic-info-box">
                <h6><i class="fas fa-key"></i> আপনার লাইসেন্স তথ্য</h6>

                <div class="lic-row">
                    <label>ডোমেইন</label>
                    <div class="lic-copy-wrap">
                        <div class="lic-val">{{ $licenseData['domain'] }}</div>
                    </div>
                </div>

                <div class="lic-row">
                    <label>LICENSE_KEY</label>
                    <div class="lic-copy-wrap">
                        <div class="lic-val" id="lic-key">{{ $licenseData['license_key'] }}</div>
                        <button class="btn-copy" onclick="copyText('lic-key', this)"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                </div>

                <div class="lic-row">
                    <label>LICENSE_SIGNATURE</label>
                    <div class="lic-copy-wrap">
                        <div class="lic-val" id="lic-sig">{{ $licenseData['signature_key'] }}</div>
                        <button class="btn-copy" onclick="copyText('lic-sig', this)"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                </div>

                @if(!($licenseData['push_success'] ?? false))
                <div class="push-fail-note">
                    <i class="fas fa-triangle-exclamation mt-1"></i>
                    <span>অটো সেট হয়নি। আপনার সাইটের <code>.env</code> ফাইলে নিচের লাইন দুটো যোগ করুন:</span>
                </div>
                <div class="env-code">
                    <pre>LICENSE_KEY={{ $licenseData['license_key'] }}
LICENSE_SIGNATURE={{ $licenseData['signature_key'] }}</pre>
                </div>
                @endif
            </div>
        @else
            <p>আপনার লাইসেন্স কী তৈরি হয়েছে এবং সাইটে সেট হয়েছে।</p>
        @endif

        <a href="/" class="btn-home" style="margin-top:20px;"><i class="fas fa-home me-2"></i> হোম পেজে যান</a>
    </div>

    @elseif($type === 'pending')
    <div class="ty-top-pending">
        <div class="ty-icon"><i class="fas fa-clock"></i></div>
        <h2>পেমেন্ট পেন্ডিং</h2>
        <p>{{ $message }}</p>
    </div>
    <div class="ty-body">
        <p>আপনার পেমেন্ট যাচাই হওয়ার পর লাইসেন্স স্বয়ংক্রিয়ভাবে সক্রিয় হবে। এটি সাধারণত কয়েক মিনিটের মধ্যে সম্পন্ন হয়।</p>
        <a href="/" class="btn-home"><i class="fas fa-home me-2"></i> হোম পেজে যান</a>
    </div>

    @else
    <div class="ty-top-error">
        <div class="ty-icon"><i class="fas fa-circle-xmark"></i></div>
        <h2>সমস্যা হয়েছে</h2>
        <p>{{ $message }}</p>
    </div>
    <div class="ty-body">
        <p>অনুগ্রহ করে আবার চেষ্টা করুন বা সাপোর্টে যোগাযোগ করুন।</p>
        <a href="{{ route('public.license.show') }}" class="btn-home"><i class="fas fa-arrow-left me-2"></i> ফিরে যান</a>
    </div>
    @endif

    <div class="ty-footer">
        System Protection by <strong>{{ $gs->title ?? 'Creative Design' }}</strong> &copy; {{ date('Y') }}
    </div>
</div>
</div>

<script>
function copyText(elId, btn) {
    var text = document.getElementById(elId).innerText.trim();
    navigator.clipboard.writeText(text).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.add('copied');
        setTimeout(function() { btn.innerHTML = orig; btn.classList.remove('copied'); }, 2000);
    });
}
</script>
@endsection
