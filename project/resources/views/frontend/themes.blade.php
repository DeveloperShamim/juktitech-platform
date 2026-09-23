@extends('layouts.front') 

@section('meta')
    <title>প্রিমিয়াম ওয়েবসাইট ও সফটওয়্যার কালেকশন - {{ $gs->title }}</title>
    <meta name="description" content="আপনার ব্যবসার জন্য সম্পূর্ণ রেডিমেড, আধুনিক ও রেসপনসিভ ওয়েবসাইট এবং লারাভেল সফটওয়্যার কালেকশন। লাইভ ডেমো দেখে সরাসরি অর্ডার করুন।">
    <meta name="keywords" content="Product, laravel theme, ecommerce script bd, ready website bangladesh, web development bd, {{ $gs->title }}">
    <meta name="author" content="{{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="প্রিমিয়াম ওয়েবসাইট ও সফটওয়্যার কালেকশন - {{ $gs->title }}" />
    <meta property="og:description" content="আপনার ব্যবসার জন্য সম্পূর্ণ রেডিমেড, আধুনিক ও রেসপনসিভ ওয়েবসাইট এবং লারাভেল সফটওয়্যার কালেকশন।" />
    <meta property="og:image" content="{{ asset_image('assets/images/logo/'.$gs->og_baner) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endsection

@section('contents')
<section class="cd-themes-catalog-area py-5">
    <!-- Ambient Top Glow -->
    <div class="cd-ambient-top"></div>

    <div class="container py-lg-4 position-relative">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb cd-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}" class="text-muted text-decoration-none">হোম</a></li>
                <li class="breadcrumb-item active text-orange fw-bold" aria-current="page">থিম ও ওয়েবসাইট কালেকশন</li>
            </ol>
        </nav>

        {{-- Section Header --}}
        <div class="text-center mb-5 max-w-760 mx-auto">
            <div class="cd-section-badge mb-3">
                <span class="cd-badge-dot"></span>
                <span>⚡ রেডিমেড ওয়েবসাইট ও থিম ক্যাটালগ</span>
            </div>
            <h1 class="display-6 fw-extrabold text-dark mb-3">
                আমাদের প্রিমিয়াম <span class="text-orange">ওয়েবসাইট ও সফটওয়্যারসমূহ</span>
            </h1>
            <p class="text-muted fs-5 mb-0 leading-relaxed">
                আপনার ব্যবসার জন্য সম্পূর্ণ রেডিমেড, আধুনিক ও রেসপনসিভ সল্যুশন বেছে নিন। প্রতিটি ওয়েবসাইটের লাইভ ডেমো ও অ্যাডমিন ড্যাশবোর্ড টেস্ট ড্রাইভ করুন।
            </p>
        </div>

        @php
            // ডাটাবেস থেকে সকল একটিভ প্রোডাক্ট পেজিনেট করা হচ্ছে (প্রতি পেজে ৯টি করে)
            $products = \App\Models\Product::where('status', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(9);

            $categoryIcons = [
                'all' => 'fas fa-border-all',
                'ecom' => 'fas fa-shopping-bag',
                'news' => 'fas fa-newspaper',
                'courier' => 'fas fa-truck-fast',
                'lms' => 'fas fa-graduation-cap',
                'pos' => 'fas fa-cash-register',
                'agency' => 'fas fa-laptop-code',
                'general' => 'fas fa-globe',
            ];
        @endphp

        {{-- Themes Grid --}}
        <div class="row g-4" id="themesGrid">
            @forelse($products as $product)
                @php
                    $catKey = $product->category_key ?: 'general';
                    $catName = $product->category_name ?: 'ওয়েবসাইট';
                    $badgeText = $product->badge_text ?: '⚡ রেডিমেড সল্যুশন';
                    $badgeClass = $product->badge_class ?: 'cd-badge--hot';
                    $licenseType = $product->license_type ?: 'আজীবন লাইসেন্স';
                    $featuresList = $product->clean_features;
                    if (empty($featuresList)) {
                        $featuresList = ['১০০% রেসপনসিভ ডিজাইন', 'মডার্ন অ্যাডমিন প্যানেল', 'লাইফটাইম আপডেট ও সাপোর্ট', 'সহজ কাস্টমাইজেশন সুবিধা'];
                    }
                    $demoUrl = $product->demo_link ?: 'https://demo.juktitech.com';
                    $parsedHost = parse_url($demoUrl, PHP_URL_HOST) ?: 'demo.juktitech.com';
                    $productUrl = route('frontend.product.details', $product->slug);
                    $orderUrl = !empty($product->purchase_link) ? $product->purchase_link : route('digital-product.checkout', $product);
                @endphp

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="cd-site-card h-100 d-flex flex-column">
                        <!-- Card Top Browser Bar Frame -->
                        <div class="cd-site-browser">
                            <div class="cd-browser-dots">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="cd-browser-url">
                                <i class="fas fa-lock text-success me-1"></i>
                                <span>{{ $parsedHost }}</span>
                            </div>
                            <span class="cd-site-cat-pill">{{ $catName }}</span>
                        </div>

                        <!-- Card Image with Hover Overlay -->
                        <div class="cd-site-media">
                            <a href="{{ $productUrl }}" class="d-block w-100 h-100 text-decoration-none">
                                <img src="{{ asset_image('assets/images/products/'.$product->photo) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy"
                                     class="cd-site-img">
                            </a>
                            <div class="cd-site-badge-float {{ $badgeClass }}">
                                {{ $badgeText }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="cd-site-body d-flex flex-column flex-grow-1">
                            <!-- Price & Licensing -->
                            <div class="cd-site-meta mb-2">
                                <div class="cd-site-price-box">
                                    <span class="cd-site-price-label">মূল্য</span>
                                    <span class="cd-site-price">৳ {{ number_format($product->price, 0) }}</span>
                                </div>
                                <span class="cd-site-license-tag">
                                    <i class="fas fa-certificate text-warning me-1"></i> {{ $licenseType }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="cd-site-title mb-2" title="{{ $product->name }}">
                                <a href="{{ $productUrl }}" class="text-dark text-decoration-none hover-orange">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <!-- Short Description -->
                            <p class="cd-site-desc text-muted mb-3">
                                {{ Str::limit(strip_tags($product->description), 90) }}
                            </p>

                            <!-- Key Feature Pills / Bullets -->
                            <ul class="cd-site-features list-unstyled mb-4">
                                @foreach(array_slice($featuresList, 0, 4) as $feat)
                                    <li>
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>{{ $feat }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- TWO ACTION BUTTONS -->
                            <div class="cd-site-actions mt-auto d-flex gap-2">
                                <!-- BUTTON 1: LIVE DEMO -> Opens single product page #live-test-drive -->
                                <a href="{{ $productUrl }}#live-test-drive" 
                                   class="cd-site-btn cd-site-btn--demo flex-grow-1"
                                   title="লাইভ টেস্ট ড্রাইভ ও বিস্তারিত দেখুন">
                                    <span class="cd-btn-text">লাইভ ডেমো</span>
                                    <span class="cd-btn-icon-wrap">
                                        <i class="fas fa-desktop"></i>
                                    </span>
                                </a>

                                <!-- BUTTON 2: BUY / ORDER NOW / DOWNLOAD -->
                                @php
                                    $userOwnsThisTheme = false;
                                    $themeDownloadLink = null;
                                    if(Auth::check()) {
                                        $userPo = \App\Models\ProductOrder::where('user_id', Auth::id())
                                            ->where('product_id', $product->id)
                                            ->where('status', 'paid')
                                            ->first();
                                        if($userPo) {
                                            $userOwnsThisTheme = true;
                                            $themeDownloadLink = route('digital-product.download', $userPo->download_token);
                                        }
                                    }
                                @endphp

                                @if($userOwnsThisTheme)
                                    <a href="{{ $themeDownloadLink }}" 
                                       class="cd-site-btn flex-grow-1"
                                       style="background: #10b981; color: #ffffff !important; border-radius: 999px; text-decoration: none !important; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px;"
                                       title="থিম ফাইল ডাউনলোড করুন">
                                        <span class="cd-btn-text">থিম ডাউনলোড</span>
                                        <span class="cd-btn-icon-wrap">
                                            <i class="fas fa-download"></i>
                                        </span>
                                    </a>
                                @elseif($product->is_digital && $product->google_drive_file_id)
                                    @auth
                                    <button type="button"
                                            class="cd-site-btn cd-site-btn--order flex-grow-1 js-theme-buy"
                                            data-checkout-url="{{ route('digital-product.checkout.submit', $product) }}"
                                            data-product-name="{{ $product->name }} — ৳ {{ number_format($product->price, 0) }}">
                                        <span class="cd-btn-text">এখুনি কিনুন</span>
                                        <span class="cd-btn-icon-wrap">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </button>
                                    @else
                                    <a href="{{ route('digital-product.checkout', $product) }}" 
                                       class="cd-site-btn cd-site-btn--order flex-grow-1">
                                        <span class="cd-btn-text">এখুনি কিনুন</span>
                                        <span class="cd-btn-icon-wrap">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </a>
                                    @endauth
                                @else
                                    <a href="{{ $productUrl }}" 
                                       class="cd-site-btn cd-site-btn--order flex-grow-1">
                                        <span class="cd-btn-text">বিস্তারিত দেখুন</span>
                                        <span class="cd-btn-icon-wrap">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border shadow-sm max-w-600 mx-auto">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h4 class="text-dark fw-bold mb-2">বর্তমানে কোনো থিম পাওয়া যায়নি</h4>
                        <p class="text-muted mb-0">অনুগ্রহ করে পরবর্তীতে আবার চেক করুন অথবা কাস্টম প্রজেক্টের জন্য আমাদের সাথে যোগাযোগ করুন।</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination --}}
        @if($products->hasPages())
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center custom-modern-pagination">
                {{ $products->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<style>
/* ==========================================================================
   THEMES CATALOG MODERN LIGHT DESIGN
   ========================================================================== */
.cd-themes-catalog-area {
    background: #f8fafc;
    position: relative;
    min-height: 80vh;
    font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.cd-ambient-top {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 380px;
    background: radial-gradient(circle at 50% 0%, rgba(255, 87, 34, 0.08) 0%, rgba(248, 250, 252, 0) 70%);
    pointer-events: none;
}
.max-w-760 { max-width: 760px; }
.max-w-600 { max-width: 600px; }
.fw-extrabold { font-weight: 800; }
.text-orange { color: #ff5722 !important; }
.hover-orange:hover { color: #ff5722 !important; }

/* Breadcrumb */
.cd-breadcrumb {
    background: #ffffff;
    padding: 8px 18px;
    border-radius: 50px;
    display: inline-flex;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    font-size: 0.88rem;
}

/* Section Badge */
.cd-section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 18px;
    border-radius: 999px;
    background: #fff5f2;
    border: 1px solid rgba(249, 115, 22, 0.25);
    color: #f97316;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(249, 115, 22, 0.08);
}
.cd-badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    display: inline-block;
}

/* Site Card Container */
.cd-site-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
    display: flex;
    flex-direction: column;
}
.cd-site-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.12);
    border-color: #cbd5e1;
}

/* Browser Frame Top */
.cd-site-browser {
    background: #f1f5f9;
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.cd-browser-dots {
    display: flex;
    gap: 5px;
    align-items: center;
}
.cd-browser-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: block;
}
.cd-browser-dots span:nth-child(1) { background: #ef4444; }
.cd-browser-dots span:nth-child(2) { background: #f59e0b; }
.cd-browser-dots span:nth-child(3) { background: #10b981; }

.cd-browser-url {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 3px 12px;
    font-size: 0.72rem;
    color: #64748b;
    font-family: monospace;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
    display: flex;
    align-items: center;
}
.cd-site-cat-pill {
    background: #e2e8f0;
    color: #475569;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 50px;
}

/* Card Media */
.cd-site-media {
    position: relative;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #0f172a;
}
.cd-site-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    display: block;
}
.cd-site-card:hover .cd-site-img {
    transform: scale(1.05);
}
.cd-site-badge-float {
    position: absolute;
    bottom: 12px;
    left: 12px;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 2;
}
.cd-badge--hot {
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff;
}
.cd-badge--blue {
    background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
    color: #ffffff;
}
.cd-badge--purple {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    color: #ffffff;
}
.cd-badge--green {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: #ffffff;
}

/* Card Body */
.cd-site-body {
    padding: 22px 20px;
}
.cd-site-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.cd-site-price-box {
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.cd-site-price-label {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
}
.cd-site-price {
    font-size: 1.35rem;
    font-weight: 800;
    color: #ff5722;
}
.cd-site-license-tag {
    font-size: 0.76rem;
    font-weight: 700;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 3px 10px;
    border-radius: 50px;
}
.cd-site-title {
    font-size: 1.12rem;
    font-weight: 700;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.cd-site-desc {
    font-size: 0.86rem;
    line-height: 1.55;
    min-height: 42px;
}
.cd-site-features li {
    font-size: 0.82rem;
    color: #334155;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
}

/* Action Buttons */
.cd-site-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 0.86rem;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.25s ease;
}
.cd-site-btn--demo {
    background: #f8fafc;
    color: #1e293b !important;
    border: 1.5px solid #cbd5e1;
}
.cd-site-btn--demo:hover {
    background: #0f172a;
    color: #ffffff !important;
    border-color: #0f172a;
    transform: translateY(-2px);
}
.cd-site-btn--order {
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff !important;
    border: none;
    box-shadow: 0 4px 14px rgba(255, 87, 34, 0.3);
}
.cd-site-btn--order:hover {
    background: linear-gradient(135deg, #e64a19 0%, #ea580c 100%);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
    transform: translateY(-2px);
    color: #ffffff !important;
}

/* Pagination */
.custom-modern-pagination nav {
    display: flex;
    justify-content: center;
}
.custom-modern-pagination .pagination {
    gap: 6px;
}
.custom-modern-pagination .page-item .page-link {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    font-weight: 700;
    padding: 8px 16px;
    transition: all 0.2s ease;
}
.custom-modern-pagination .page-item.active .page-link {
    background: #ff5722;
    border-color: #ff5722;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}
.custom-modern-pagination .page-item .page-link:hover:not(.active) {
    background: #f1f5f9;
    border-color: #cbd5e1;
}
.custom-modern-pagination svg {
    width: 18px;
    height: 18px;
}
.custom-modern-pagination nav > div:first-child {
    display: none;
}
</style>

@auth
    @include('frontend.partials.theme-buy-modal')
@endauth
@endsection