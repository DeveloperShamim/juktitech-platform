@extends('layouts.front')
@section('title', 'লাইসেন্স জেনারেশন')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    .thankyou-card {
        background: #fff;
        border-radius: 24px;
        padding: 60px 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        text-align: center;
        max-width: 520px;
        margin: 80px auto;
    }

    .icon-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
        font-size: 40px;
    }
    .icon-circle.success { background: #d1fae5; color: #059669; }
    .icon-circle.pending { background: #fef3c7; color: #d97706; }
    .icon-circle.error { background: #fee2e2; color: #dc2626; }

    .btn-go { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; padding: 14px 32px; border-radius: 12px; font-weight: 700; font-size: 15px; text-decoration: none; transition: 0.3s; margin-top: 24px; }
    .btn-go:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(78,115,223,0.35); color: #fff; }

    .btn-secondary-go { display: inline-flex; align-items: center; gap: 8px; background: #f1f5f9; color: #475569; padding: 12px 28px; border-radius: 12px; font-weight: 600; font-size: 14px; text-decoration: none; transition: 0.3s; margin-top: 12px; margin-left: 8px; }
    .btn-secondary-go:hover { background: #e2e8f0; color: #1e293b; }

    .steps-box { background: #f8fafc; border-radius: 12px; padding: 20px; text-align: left; margin-top: 28px; }
    .step-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
    .step-item:last-child { margin-bottom: 0; }
    .step-num { width: 26px; height: 26px; background: #4e73df; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; margin-top: 1px; }
    .step-text { font-size: 13px; color: #64748b; }
</style>

<div class="container">

    @if(session('success'))
    {{-- সফল লাইসেন্স জেনারেশন --}}
    <div class="thankyou-card">
        <div class="icon-circle success">
            <i class="fa-solid fa-check"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2">লাইসেন্স তৈরি হয়েছে!</h3>
        <p class="text-muted">{{ session('success') }}</p>
        <p class="text-muted" style="font-size: 13px;">আপনার লাইসেন্স কী এবং বিস্তারিত তথ্য "আমার লাইসেন্স" পেজে দেখতে পাবেন।</p>

        <div>
            <a href="{{ route('user.licenses') }}" class="btn-go">
                <i class="fa-solid fa-key"></i> আমার লাইসেন্স দেখুন
            </a>
            <a href="{{ route('user.license.generate.show') }}" class="btn-secondary-go">
                <i class="fa-solid fa-plus"></i> আরও জেনারেট করুন
            </a>
        </div>
    </div>

    @elseif(session('pending'))
    {{-- পেমেন্ট পেন্ডিং --}}
    <div class="thankyou-card">
        <div class="icon-circle pending">
            <i class="fa-solid fa-clock"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2">পেমেন্ট পেন্ডিং</h3>
        <p class="text-muted">{{ session('pending') }}</p>

        <div class="steps-box">
            <p class="fw-bold mb-3" style="font-size: 13px; color: #374151;">পরবর্তী ধাপ:</p>
            <div class="step-item">
                <span class="step-num">১</span>
                <span class="step-text">আপনার পেমেন্ট অ্যাডমিন কর্তৃক যাচাই করা হবে।</span>
            </div>
            <div class="step-item">
                <span class="step-num">২</span>
                <span class="step-text">অ্যাডমিন ইনভয়েস পেইড মার্ক করলে স্বয়ংক্রিয়ভাবে লাইসেন্স তৈরি হবে।</span>
            </div>
            <div class="step-item">
                <span class="step-num">৩</span>
                <span class="step-text">"আমার লাইসেন্স" পেজে আপনার লাইসেন্স দেখতে পাবেন।</span>
            </div>
        </div>

        <div>
            <a href="{{ route('user.licenses') }}" class="btn-go">
                <i class="fa-solid fa-key"></i> আমার লাইসেন্স দেখুন
            </a>
            <a href="{{ route('user.dashboard') }}" class="btn-secondary-go">
                <i class="fa-solid fa-house"></i> ড্যাশবোর্ড
            </a>
        </div>
    </div>

    @else
    {{-- Error বা অন্য স্ট্যাটাস --}}
    <div class="thankyou-card">
        <div class="icon-circle error">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2">কিছু একটা সমস্যা হয়েছে</h3>
        <p class="text-muted">{{ session('error') ?? 'পেমেন্ট প্রসেস করা যায়নি। আবার চেষ্টা করুন।' }}</p>

        <div>
            <a href="{{ route('user.license.generate.show') }}" class="btn-go">
                <i class="fa-solid fa-rotate-right"></i> আবার চেষ্টা করুন
            </a>
            <a href="{{ route('user.licenses') }}" class="btn-secondary-go">
                <i class="fa-solid fa-key"></i> আমার লাইসেন্স
            </a>
        </div>
    </div>
    @endif

</div>

@endsection
