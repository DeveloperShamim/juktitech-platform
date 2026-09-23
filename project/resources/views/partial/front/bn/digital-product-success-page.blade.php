<style>
    .dp-wrap { background: linear-gradient(180deg, #ecfdf5 0%, #f8fafc 45%, #ffffff 100%); min-height: 70vh; }
    .dp-card { border: none; border-radius: 24px; box-shadow: 0 20px 50px rgba(15,23,42,.08); overflow: hidden; background: #fff; }
    .dp-hero { background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: #fff; padding: 2rem; text-align: center; }
    .dp-hero .icon { width: 84px; height: 84px; border-radius: 50%; background: rgba(255,255,255,.18); display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; margin-bottom: 1rem; }
    .dp-body { padding: 2rem; }
    .dp-product { display: flex; gap: 16px; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; margin-bottom: 1.25rem; }
    .dp-product img { width: 84px; height: 84px; object-fit: cover; border-radius: 12px; }
    .dp-info-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; margin-bottom: 1.25rem; }
    .dp-info-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; }
    .dp-info-item .label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .dp-info-item .value { font-size: 15px; color: var(--text-dark); font-weight: 700; word-break: break-word; }
    .dp-license { background: #f0fdf4; border: 1px solid #86efac; border-radius: 16px; padding: 18px; margin-bottom: 1.25rem; }
    .dp-license-key { display: flex; align-items: center; justify-content: space-between; gap: 10px; background: #fff; border: 1px dashed #4ade80; border-radius: 12px; padding: 12px 14px; margin-top: 10px; }
    .dp-license-key code { color: #166534; font-size: 15px; font-weight: 800; word-break: break-all; }
    .dp-copy { border: 0; background: #16a34a; color: #fff; border-radius: 8px; padding: 7px 11px; font-weight: 700; white-space: nowrap; }
    .dp-limit { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 14px 16px; color: #1d4ed8; font-weight: 600; text-align: center; }
    .btn-orange { background: var(--primary-color); color: #fff; border: none; font-weight: 700; }
    .btn-orange:hover { background: var(--primary-hover); color: #fff; }
    @media (max-width: 767px) { .dp-info-grid { grid-template-columns: 1fr; } .dp-product { flex-direction: column; text-align: center; } }
</style>

<section class="dp-wrap py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="dp-card">
                    <div class="dp-hero">
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                        <h2 class="fw-bold mb-2">ধন্যবাদ!</h2>
                        <p class="mb-0 opacity-75">পেমেন্ট verified — আপনার ডিজিটাল প্রোডাক্ট ডাউনলোডের জন্য ready</p>
                    </div>

                    <div class="dp-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="dp-product">
                            @if($order->product?->photo)
                                <img src="{{ asset_image('assets/images/products/'.$order->product->photo) }}" alt="{{ $order->product->name }}">
                            @endif
                            <div>
                                <div class="text-muted small">Purchased item</div>
                                <h4 class="fw-bold mb-1">{{ $order->product->name }}</h4>
                                <div class="text-orange fw-bold" style="color:var(--primary-color);">৳ {{ number_format($order->amount, 0) }}</div>
                            </div>
                        </div>

                        <div class="dp-info-grid">
                            <div class="dp-info-item">
                                <div class="label">অর্ডার নং</div>
                                <div class="value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">Invoice ID</div>
                                <div class="value">{{ $order->invoice_id ?: '—' }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">নাম</div>
                                <div class="value">{{ $order->customer_name }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">ইমেইল</div>
                                <div class="value">{{ $order->customer_email }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">পেমেন্ট তারিখ</div>
                                <div class="value">{{ $order->paid_at?->format('d M Y, h:i A') ?: now()->format('d M Y, h:i A') }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">ফাইল</div>
                                <div class="value">{{ $order->product->digital_file_name ?: 'Digital download' }}</div>
                            </div>
                            <div class="dp-info-item">
                                <div class="label">ইনস্টল ডোমেইন</div>
                                <div class="value">{{ $order->install_domain ?: '—' }}</div>
                            </div>
                        </div>

                        @if($order->license)
                            <div class="dp-license">
                                <div class="fw-bold text-success"><i class="fas fa-key me-2"></i>লাইসেন্স অটো তৈরি হয়েছে</div>
                                <div class="small text-muted mt-1">ডোমেইন: {{ $order->license->domain_name }}</div>
                                <div class="dp-license-key">
                                    <code id="themeLicenseKey">{{ $order->license->license_key }}</code>
                                    <button type="button" class="dp-copy" onclick="navigator.clipboard.writeText(document.getElementById('themeLicenseKey').textContent);this.textContent='কপি হয়েছে'">কপি</button>
                                </div>
                            </div>
                        @elseif($order->isPaid())
                            <div class="alert alert-warning">লাইসেন্স তৈরি করা যায়নি। সাপোর্টে যোগাযোগ করুন এবং অর্ডার নং জানান।</div>
                        @endif

                        @if($order->canDownload())
                            <div class="dp-limit mb-4">
                                বাকি ডাউনলোড: {{ $order->downloadsRemaining() }} / {{ $order->download_limit }}
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('digital-product.download', $order->download_token) }}" class="btn btn-orange btn-lg rounded-pill px-5">
                                    <i class="fas fa-download me-2"></i> এখনই ডাউনলোড
                                </a>
                                <a href="{{ route('user.digital-downloads.index') }}" class="btn btn-dark btn-lg rounded-pill px-4">আমার ডাউনলোড</a>
                            </div>
                        @elseif($order->isPaid())
                            <div class="alert alert-warning">ডাউনলোড লিমিট শেষ — সাপোর্টে যোগাযোগ করুন</div>
                        @else
                            <div class="alert alert-warning">পেমেন্ট confirm হচ্ছে — কিছুক্ষণ পর refresh করুন</div>
                        @endif

                        <p class="text-muted small text-center mt-4 mb-0">
                            এই page bookmark করে রাখুন — limit শেষ হওয়ার আগে download নিতে পারবেন
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
