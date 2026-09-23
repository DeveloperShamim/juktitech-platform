@extends('layouts.front')

@section('meta')
    <title>লাইসেন্স প্রয়োজন — {{ $gs->title ?? 'Creative Design' }}</title>
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('contents')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .lic-page {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    /* Animated bg particles */
    .lic-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }

    .lic-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 40px 120px rgba(0,0,0,.4);
        width: 100%;
        max-width: 520px;
        overflow: hidden;
        animation: fadeUp .6s ease-out;
    }

    @keyframes fadeUp {
        from { transform: translateY(40px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    /* ── Top header ── */
    .lic-header {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        padding: 36px 32px 28px;
        text-align: center;
        position: relative;
    }
    .lic-header.has-domain { background: linear-gradient(135deg, #4f46e5, var(--primary-color)); }

    .lock-icon {
        width: 72px; height: 72px;
        background: rgba(255,255,255,.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        animation: pulse 2.5s infinite;
    }
    @keyframes pulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,.3); }
        50%      { box-shadow: 0 0 0 14px rgba(255,255,255,.0); }
    }
    .lock-icon i { font-size: 30px; color: #fff; }

    .lic-header h1 { color: #fff; font-size: 22px; font-weight: 800; margin: 0 0 6px; }
    .lic-header p  { color: rgba(255,255,255,.8); font-size: 14px; margin: 0; }

    /* ── Domain badge ── */
    .domain-badge {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 50px;
        padding: 7px 20px;
        display: inline-flex; align-items: center; gap: 8px;
        color: #fff; font-size: 15px; font-weight: 700;
        margin-top: 16px;
    }

    /* ── Body ── */
    .lic-body { padding: 32px; }

    /* Alerts */
    .lic-alert { border-radius: 12px; padding: 14px 18px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px; }
    .lic-alert.danger  { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }
    .lic-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .lic-alert.warning { background: #fffbeb; border: 1px solid #fde68a; color: #b45309; }

    /* Price box */
    .price-box {
        background: linear-gradient(135deg, #f0f4ff, #faf5ff);
        border: 2px dashed #a5b4fc;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        margin-bottom: 24px;
    }
    .price-box .amount { font-size: 42px; font-weight: 800; color: #4f46e5; line-height: 1; }
    .price-box .period { font-size: 13px; color: #6b7280; margin-top: 4px; }
    .price-box .inc    { display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #166534; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; margin-top: 10px; }

    /* Form */
    .lic-label { font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: .5px; }
    .lic-input { width: 100%; height: 46px; padding: 0 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; background: #f9fafb; transition: .3s; }
    .lic-input:focus { outline: none; border-color: #4f46e5; background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
    .lic-input[readonly] { background: #f3f4f6; color: #6b7280; cursor: not-allowed; }

    /* Pay button */
    .btn-pay {
        background: linear-gradient(135deg, #4f46e5, var(--primary-color));
        color: #fff; border: none; width: 100%;
        padding: 14px; border-radius: 12px; font-size: 16px; font-weight: 700;
        cursor: pointer; transition: .3s; margin-top: 8px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(79,70,229,.4); }
    .btn-pay:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* Existing license info */
    .exist-box {
        background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 14px; padding: 20px 22px; text-align: center;
    }
    .exist-box .lic-key { font-family: monospace; font-size: 13px; background: #dcfce7; border-radius: 8px; padding: 8px 12px; display: inline-block; color: #166534; margin-top: 8px; word-break: break-all; }

    /* Footer note */
    .lic-footer { text-align: center; padding: 16px 32px 24px; }
    .lic-footer p { font-size: 12px; color: #9ca3af; margin: 0; }

    /* Divider */
    .lic-divider { height: 1px; background: #f3f4f6; margin: 20px 0; }

    .secured-row { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 12px; font-size: 12px; color: #9ca3af; }
</style>

<div class="lic-page">
<div class="lic-card">

    {{-- ── HEADER ── --}}
    <div class="lic-header {{ $domain ? 'has-domain' : '' }}">
        <div class="lock-icon">
            <i class="fas {{ $domain ? 'fa-key' : 'fa-lock' }}"></i>
        </div>
        @if($domain)
            <h1>লাইসেন্স প্রয়োজন</h1>
            <p>এই সফটওয়্যারটি চালাতে একটি বৈধ লাইসেন্স কী প্রয়োজন</p>
            <div class="domain-badge">
                <i class="fas fa-globe"></i> {{ $domain }}
            </div>
        @else
            <h1>লাইসেন্স এরর!</h1>
            <p>এই সফটওয়্যারটি এই ডোমেইনের জন্য অনুমোদিত নয়</p>
        @endif
    </div>

    {{-- ── BODY ── --}}
    <div class="lic-body">

        {{-- Alerts --}}
        @if(session('error'))
            <div class="lic-alert danger"><i class="fas fa-circle-exclamation mt-1"></i> <span>{{ session('error') }}</span></div>
        @endif
        @if(session('success'))
            <div class="lic-alert success"><i class="fas fa-circle-check mt-1"></i> <span>{{ session('success') }}</span></div>
        @endif
        @if($errors->any())
            <div class="lic-alert danger">
                <i class="fas fa-circle-exclamation mt-1"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- ══ CASE 1: Domain আছে, License নেই → Purchase form ══ --}}
        @if($domain && !$existingLicense)

            <div class="price-box mb-4">
                <div class="amount">৳{{ number_format($price, 0) }}</div>
                <div class="period">এককালীন পেমেন্ট • লাইফটাইম লাইসেন্স ♾</div>
                <div class="inc"><i class="fas fa-check"></i> License Key + Signature Key অটো সেট হবে</div>
            </div>

            <form action="{{ route('public.license.checkout') }}" method="POST" id="buyForm">
                @csrf
                <input type="hidden" name="domain" value="{{ $domain }}">

                <div class="mb-3">
                    <label class="lic-label"><i class="fas fa-globe me-1"></i> আপনার ডোমেইন</label>
                    <input type="text" class="lic-input" value="{{ $domain }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="lic-label"><i class="fas fa-user me-1"></i> আপনার নাম</label>
                    <input type="text" name="name" class="lic-input" placeholder="যেমন: রাফিউল ইসলাম" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="lic-label"><i class="fas fa-envelope me-1"></i> ইমেইল ঠিকানা</label>
                    <input type="email" name="email" class="lic-input" placeholder="your@email.com" value="{{ old('email') }}" required>
                    <small style="font-size:11px;color:#6b7280;" class="mt-1 d-block">পেমেন্ট কনফার্মেশন এই ইমেইলে যাবে।</small>
                </div>

                <div class="lic-divider"></div>

                <button type="submit" class="btn-pay" id="payBtn">
                    <i class="fas fa-credit-card"></i>
                    ৳{{ number_format($price, 0) }} পেমেন্ট করে লাইসেন্স নিন
                </button>

                <div class="secured-row">
                    <i class="fas fa-shield-halved text-success"></i>
                    <span>উদ্যোক্তা পে দ্বারা সুরক্ষিত পেমেন্ট</span>
                    <span>•</span>
                    <span>পেমেন্টের পরপরই লাইসেন্স সক্রিয় হবে</span>
                </div>
            </form>

        {{-- ══ CASE 2: Domain আছে, License আছে → Info দেখাও ══ --}}
        @elseif($domain && $existingLicense)

            @if($existingLicense->status == 1 && ($existingLicense->expiry_date === null || \Carbon\Carbon::parse($existingLicense->expiry_date)->isFuture()))
                <div class="exist-box">
                    <i class="fas fa-circle-check fa-2x text-success mb-2"></i>
                    <h6 class="fw-bold mb-1">এই ডোমেইনের লাইসেন্স সক্রিয় আছে</h6>
                    <p style="font-size:13px;color:#6b7280;" class="mb-2">
                        মেয়াদ:
                        @if($existingLicense->expiry_date)
                            {{ \Carbon\Carbon::parse($existingLicense->expiry_date)->format('d M, Y') }}
                        @else
                            <span class="badge" style="background:#f3e8ff;color:#7e22ce;padding:3px 10px;border-radius:20px;">Lifetime ♾</span>
                        @endif
                    </p>
                    <div class="lic-key">{{ $existingLicense->license_key }}</div>
                    <p style="font-size:12px;color:#6b7280;margin-top:12px;">
                        কোনো সমস্যা হলে অ্যাডমিনের সাথে যোগাযোগ করুন।
                    </p>
                </div>
            @else
                <div class="lic-alert warning">
                    <i class="fas fa-triangle-exclamation mt-1"></i>
                    <span>আপনার লাইসেন্সের মেয়াদ শেষ হয়েছে বা নিষ্ক্রিয়। রিনিউ করতে অ্যাডমিনের সাথে যোগাযোগ করুন।</span>
                </div>
                <a href="{{ url('/contact') }}" class="btn-pay" style="text-decoration:none;">
                    <i class="fas fa-headset"></i> অ্যাডমিনের সাথে যোগাযোগ করুন
                </a>
            @endif

        {{-- ══ CASE 3: Domain নেই → Generic error ══ --}}
        @else
            <div class="lic-alert danger">
                <i class="fas fa-circle-exclamation mt-1"></i>
                <span>এই সফটওয়্যারটি আপনার ডোমেইনের জন্য অনুমোদিত নয়। বিস্তারিত জানতে আমাদের সাথে যোগাযোগ করুন।</span>
            </div>
            <a href="{{ url('/contact') }}" class="btn-pay" style="text-decoration:none;background:linear-gradient(135deg,#374151,#111827);">
                <i class="fas fa-headset"></i> সাপোর্টে যোগাযোগ করুন
            </a>
        @endif

    </div>

    {{-- ── FOOTER ── --}}
    <div class="lic-footer">
        <p>System Protection by <strong>{{ $gs->title ?? 'Creative Design' }}</strong> &copy; {{ date('Y') }}</p>
    </div>

</div>
</div>

<script>
document.getElementById('buyForm') && document.getElementById('buyForm').addEventListener('submit', function() {
    var btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> রিডাইরেক্ট হচ্ছে...';
});
</script>

@endsection
