@extends('layouts.front')

@section('contents')
<style>
    .dp-wrap { background: linear-gradient(180deg, var(--primary-bg-lighter) 0%, #f8fafc 45%, #ffffff 100%); min-height: 70vh; }
    .dp-card { border: none; border-radius: 24px; box-shadow: 0 20px 50px rgba(15,23,42,.08); overflow: hidden; background: #fff; }
    .dp-head { padding: 2rem 2rem 1rem; text-align: center; }
    .dp-icon { width: 88px; height: 88px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; margin-bottom: 1rem; }
    .dp-icon.pending { background: #fef3c7; color: #d97706; }
    .dp-icon.error { background: #fee2e2; color: #dc2626; }
    .dp-body { padding: 0 2rem 2rem; }
    .dp-info-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
    .dp-info-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; }
    .dp-info-item .label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .dp-info-item .value { font-size: 15px; color: var(--text-dark); font-weight: 700; word-break: break-word; }
    .dp-note { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; border-radius: 14px; padding: 14px 16px; font-size: 14px; }
    .dp-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 1.5rem; }
    .btn-orange { background: var(--primary-color); color: #fff; border: none; font-weight: 700; }
    .btn-orange:hover { background: var(--primary-hover); color: #fff; }
    @media (max-width: 767px) { .dp-info-grid { grid-template-columns: 1fr; } }
</style>

<section class="dp-wrap py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="dp-card">
                    <div class="dp-head">
                        @if($type === 'pending')
                            <div class="dp-icon pending"><i class="fas fa-clock"></i></div>
                            <h2 class="fw-bold mb-2">পেমেন্ট Pending</h2>
                            <p class="text-muted mb-0">আপনার পেমেন্ট গ্রহণ হয়েছে, যাচাই চলছে</p>
                        @else
                            <div class="dp-icon error"><i class="fas fa-times-circle"></i></div>
                            <h2 class="fw-bold mb-2">অর্ডার সম্পন্ন হয়নি</h2>
                            <p class="text-muted mb-0">{{ $message }}</p>
                        @endif
                    </div>

                    <div class="dp-body">
                        @if($type === 'pending')
                            <div class="dp-note mb-4">
                                {{ $message }}
                            </div>
                        @endif

                        @if(!empty($order))
                            <div class="dp-info-grid mb-4">
                                <div class="dp-info-item">
                                    <div class="label">অর্ডার নং</div>
                                    <div class="value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">প্রোডাক্ট</div>
                                    <div class="value">{{ $order->product->name ?? '—' }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">পরিমাণ</div>
                                    <div class="value">৳ {{ number_format($order->amount, 0) }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">স্ট্যাটাস</div>
                                    <div class="value text-capitalize">{{ $order->status === 'pending' ? 'Pending' : ucfirst($order->status) }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">Invoice ID</div>
                                    <div class="value">{{ $invoiceId ?: ($order->invoice_id ?: '—') }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">ইমেইল</div>
                                    <div class="value">{{ $order->customer_email }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">তারিখ</div>
                                    <div class="value">{{ $order->created_at?->format('d M Y, h:i A') }}</div>
                                </div>
                                <div class="dp-info-item">
                                    <div class="label">ডাউনলোড লিমিট</div>
                                    <div class="value">{{ $order->download_limit }} বার</div>
                                </div>
                            </div>
                        @endif

                        <div class="dp-actions">
                            @auth
                                <a href="{{ route('user.digital-downloads.index') }}" class="btn btn-orange rounded-pill px-4">
                                    <i class="fas fa-download me-2"></i> আমার ডাউনলোড
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-dark rounded-pill px-4">ড্যাশবোর্ড</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-orange rounded-pill px-4">লগইন করুন</a>
                            @endauth
                            <a href="{{ route('frontend.themes') }}" class="btn btn-outline-secondary rounded-pill px-4">থিম তালিকা</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
