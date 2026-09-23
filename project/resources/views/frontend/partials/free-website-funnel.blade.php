@php
    $allProducts = $products ?? \App\Models\Product::where('status', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

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

    $categories = [];
    foreach ($allProducts as $p) {
        $key = $p->category_key ?: 'general';
        $name = $p->category_name ?: 'অন্যান্য';
        if (!isset($categories[$key])) {
            $categories[$key] = [
                'key' => $key,
                'name' => $name,
                'count' => 0
            ];
        }
        $categories[$key]['count']++;
    }
@endphp

<section class="cd-sites-showcase" id="all-websites">
    <!-- Ambient Background Lighting -->
    <div class="cd-sites-ambient">
        <div class="cd-sites-glow cd-sites-glow-1"></div>
        <div class="cd-sites-glow cd-sites-glow-2"></div>
    </div>

    <div class="container position-relative">
        <!-- Section Header -->
        <div class="cd-sites-head text-center">
            <div class="cd-section-badge">
                <span class="cd-badge-dot"></span>
                <span>⚡ রেডিমেড ওয়েবসাইট ও সফটওয়্যার কালেকশন</span>
            </div>
            <h2 class="cd-sites-title">
                আপনার পছন্দের ওয়েবসাইট বেছে নিন <br class="d-none d-md-block">
                <span class="cd-text-orange">১ ক্লিকে রেডি সেটআপ</span>
            </h2>
            <p class="cd-sites-subtitle">
                সম্পূর্ণ আধুনিক প্রযুক্তি, সুপারফাস্ট স্পিড এবং লাইফটাইম ফুল লাইসেন্স সহ তৈরি আমাদের রেডি সল্যুশনসমূহ। ডেমো দেখে সরাসরি অর্ডার করুন।
            </p>
        </div>

        <!-- Category Filter Tabs -->
        <div class="cd-sites-filter-wrap">
            <div class="cd-sites-tabs" id="cdSitesTabs">
                <button type="button" class="cd-stab is-active" data-cat="all">
                    <i class="fas fa-border-all me-1"></i> সকল ওয়েবসাইট ({{ $allProducts->count() }})
                </button>
                @foreach($categories as $catKey => $catData)
                    <button type="button" class="cd-stab" data-cat="{{ $catKey }}">
                        <i class="{{ $categoryIcons[$catKey] ?? 'fas fa-globe' }} me-1"></i> {{ $catData['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Websites Grid -->
        <div class="cd-sites-grid" id="cdSitesGrid">
            @forelse($allProducts as $product)
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
                    $orderUrl = !empty($product->purchase_link) ? $product->purchase_link : route('digital-product.checkout', $product);
                @endphp

                <div class="cd-site-card" data-cat="{{ $catKey }}">
                    @php
                        $productUrl = route('frontend.product.details', $product->slug);
                    @endphp

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
                    <div class="cd-site-body">
                        <!-- Price & Licensing -->
                        <div class="cd-site-meta">
                            <div class="cd-site-price-box">
                                <span class="cd-site-price-label">মূল্য</span>
                                <span class="cd-site-price">৳ {{ number_format($product->price, 0) }}</span>
                            </div>
                            <span class="cd-site-license-tag">
                                <i class="fas fa-certificate text-warning me-1"></i> {{ $licenseType }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="cd-site-title" title="{{ $product->name }}">
                            <a href="{{ $productUrl }}" class="text-dark text-decoration-none hover-orange">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <!-- Short Description -->
                        <p class="cd-site-desc">
                            {{ Str::limit(strip_tags($product->description), 92) }}
                        </p>

                        <!-- Key Feature Pills / Bullets -->
                        <ul class="cd-site-features">
                            @foreach($featuresList as $feat)
                                <li>
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <!-- TWO ACTION BUTTONS (The core requirement) -->
                        <div class="cd-site-actions">
                            <!-- BUTTON 1: LIVE DEMO -> Go to Single Product Page to view credentials and live test drive -->
                            <a href="{{ $productUrl }}#live-test-drive" 
                               class="cd-site-btn cd-site-btn--demo"
                               title="লাইভ টেস্ট ড্রাইভ ও বিস্তারিত দেখুন">
                                <span class="cd-btn-text">লাইভ ডেমো</span>
                                <span class="cd-btn-icon-wrap">
                                    <i class="fas fa-desktop"></i>
                                </span>
                            </a>

                            <!-- BUTTON 2: ORDER NOW OR DOWNLOAD -->
                            @php
                                $userOwnsFunnelTheme = false;
                                $funnelThemeDownloadLink = null;
                                if(Auth::check()) {
                                    $funnelPo = \App\Models\ProductOrder::where('user_id', Auth::id())
                                        ->where('product_id', $product->id)
                                        ->where('status', 'paid')
                                        ->first();
                                    if($funnelPo) {
                                        $userOwnsFunnelTheme = true;
                                        $funnelThemeDownloadLink = route('digital-product.download', $funnelPo->download_token);
                                    }
                                }
                            @endphp

                            @if($userOwnsFunnelTheme)
                                <a href="{{ $funnelThemeDownloadLink }}" 
                                   class="cd-site-btn"
                                   style="background: #10b981; color: #ffffff !important; border-radius: 999px; text-decoration: none !important; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px;"
                                   title="থিম ফাইল ডাউনলোড করুন">
                                    <span class="cd-btn-text">থিম ডাউনলোড</span>
                                    <span class="cd-btn-icon-wrap">
                                        <i class="fas fa-download"></i>
                                    </span>
                                </a>
                            @else
                                <a href="{{ $orderUrl }}" 
                                   target="_blank" 
                                   rel="noopener" 
                                   class="cd-site-btn cd-site-btn--order"
                                   title="এখুনি অর্ডার ও ফুল সেটআপ নিন">
                                    <span class="cd-btn-text">এখুনি অর্ডার করুন</span>
                                    <span class="cd-btn-icon-wrap">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="cd-sites-empty text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4>বর্তমানে কোনো রেডি ওয়েবসাইট পাওয়া যায়নি।</h4>
                </div>
            @endforelse
        </div>

        <!-- Bottom Bespoke Custom Project Strip -->
        <div class="cd-custom-banner">
            <div class="cd-cbanner-left">
                <div class="cd-cbanner-icon">
                    <i class="fas fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <h4 class="cd-cbanner-title">আপনার বিজনেসের জন্য সম্পূর্ণ কাস্টম ডিজাইন চান?</h4>
                    <p class="cd-cbanner-sub">আপনার মনমতো ইউনিক ফিচার, বিশেষ সফটওয়্যার কিংবা কাস্টম রিকোয়ারমেন্ট নিয়ে সরাসরি আমাদের সাথে কথা বলুন।</p>
                </div>
            </div>
            <div class="cd-cbanner-right">
                <a href="https://wa.me/8801775075543?text={{ urlencode('হ্যালো Jukti Tech, আমি একটি কাস্টম ওয়েবসাইট/সফটওয়্যার তৈরি করতে চাই। বিস্তারিত আলোচনা করতে আগ্রহী।') }}" 
                   target="_blank" 
                   rel="noopener" 
                   class="cd-cbanner-btn">
                    <i class="fab fa-whatsapp me-2 text-success"></i> হোয়াটসঅ্যাপে আলোচনা করুন
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* ==========================================================================
   JUKTI TECH — ALL WEBSITES & THEMES SHOWCASE (MODERN SAAS AESTHETIC)
   ========================================================================== */

.cd-sites-showcase {
    position: relative;
    padding: 95px 0 90px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    overflow: hidden;
    font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Ambient Backlight */
.cd-sites-ambient {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

.cd-sites-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.28;
}

.cd-sites-glow-1 {
    top: -150px;
    left: -150px;
    background: radial-gradient(circle, #ff5722 0%, rgba(255, 87, 34, 0) 70%);
}

.cd-sites-glow-2 {
    bottom: -150px;
    right: -150px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
}

/* 1. Header Styles */
.cd-sites-head {
    max-width: 820px;
    margin: 0 auto 38px;
    position: relative;
    z-index: 1;
}

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
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(249, 115, 22, 0.08);
}

.cd-section-badge .cd-badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    display: inline-block;
}
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.22);
    animation: cdPulseSites 2s infinite;
}

@keyframes cdPulseSites {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.35); opacity: 0.7; }
}

.cd-sites-title {
    font-size: clamp(1.85rem, 3.4vw, 2.75rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    letter-spacing: -0.02em;
    margin-bottom: 16px;
}

.cd-sites-subtitle {
    font-size: clamp(1rem, 1.3vw, 1.12rem);
    color: #475569;
    line-height: 1.68;
    max-width: 680px;
    margin: 0 auto;
    font-weight: 500;
}

/* 2. Filter Tabs */
.cd-sites-filter-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 42px;
    position: relative;
    z-index: 2;
}

.cd-sites-tabs {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    padding: 6px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
    flex-wrap: wrap;
    justify-content: center;
}

.cd-stab {
    background: transparent;
    border: none;
    padding: 9px 20px;
    border-radius: 999px;
    font-size: 0.92rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    display: inline-flex;
    align-items: center;
}

.cd-stab:hover {
    color: #ff5722;
    background: rgba(255, 87, 34, 0.05);
}

.cd-stab.is-active {
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(255, 87, 34, 0.35);
}

/* 3. Grid of Cards */
.cd-sites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 28px;
    position: relative;
    z-index: 1;
    margin-bottom: 50px;
}

.cd-site-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    transition: all 0.32s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}

.cd-site-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 48px rgba(15, 23, 42, 0.1), 0 4px 12px rgba(255, 87, 34, 0.08);
    border-color: rgba(255, 87, 34, 0.35);
}

/* Card Browser Header Bar */
.cd-site-browser {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #edf2f7;
}

.cd-browser-dots {
    display: flex;
    align-items: center;
    gap: 5px;
}

.cd-browser-dots span {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
}

.cd-browser-dots span:nth-child(1) { background: #ef4444; }
.cd-browser-dots span:nth-child(2) { background: #f59e0b; }
.cd-browser-dots span:nth-child(3) { background: #10b981; }

.cd-browser-url {
    background: #ffffff;
    padding: 3px 12px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    font-size: 0.75rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    max-width: 170px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, monospace;
}

.cd-site-cat-pill {
    font-size: 0.74rem;
    font-weight: 700;
    color: #475569;
    background: #e2e8f0;
    padding: 3px 10px;
    border-radius: 999px;
}

/* Card Media (Screenshot) */
.cd-site-media {
    position: relative;
    width: 100%;
    height: 220px;
    background: #f1f5f9;
    overflow: hidden;
}

.cd-site-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.cd-site-card:hover .cd-site-img {
    transform: scale(1.04);
}

.cd-site-badge-float {
    position: absolute;
    bottom: 12px;
    left: 12px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(8px);
}

.cd-badge--hot    { background: linear-gradient(135deg, #ff5722, #f97316); }
.cd-badge--blue   { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.cd-badge--purple { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
.cd-badge--green  { background: linear-gradient(135deg, #059669, #10b981); }
.cd-badge--amber  { background: linear-gradient(135deg, #d97706, #f59e0b); }
.cd-badge--indigo { background: linear-gradient(135deg, #4f46e5, #6366f1); }

/* Card Content Body */
.cd-site-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.cd-site-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.cd-site-price-box {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.cd-site-price-label {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 600;
}

.cd-site-price {
    font-size: 1.35rem;
    font-weight: 800;
    color: #ff5722;
    letter-spacing: -0.01em;
}

.cd-site-license-tag {
    font-size: 0.76rem;
    font-weight: 600;
    color: #334155;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 3px 10px;
    border-radius: 999px;
}

.cd-site-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.38;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.76em;
}

.cd-site-desc {
    font-size: 0.88rem;
    color: #64748b;
    line-height: 1.55;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Feature Checkmarks */
.cd-site-features {
    list-style: none;
    padding: 0;
    margin: 0 0 22px 0;
    display: flex;
    flex-direction: column;
    gap: 7px;
    border-top: 1px dashed #e2e8f0;
    padding-top: 14px;
}

.cd-site-features li {
    font-size: 0.84rem;
    color: #334155;
    font-weight: 500;
    display: flex;
    align-items: center;
}

/* TWO ACTION BUTTONS (The core requirement) */
.cd-site-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: auto;
}

.cd-site-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 14px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}

.cd-site-btn .cd-btn-icon-wrap {
    font-size: 0.82rem;
    transition: transform 0.2s ease;
}

/* Button 1: Live Demo (Secondary / Clean Slate Button) */
.cd-site-btn--demo {
    background: #f1f5f9;
    color: #1e293b !important;
    border: 1px solid #cbd5e1;
}

.cd-site-btn--demo:hover {
    background: #0f172a;
    color: #ffffff !important;
    border-color: #0f172a;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.18);
}

.cd-site-btn--demo:hover .cd-btn-icon-wrap {
    transform: scale(1.15);
}

/* Button 2: Order Now (Primary / Vibrant Orange Gradient Button) */
.cd-site-btn--order {
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff !important;
    border: 1px solid transparent;
    box-shadow: 0 4px 14px rgba(255, 87, 34, 0.32);
}

.cd-site-btn--order:hover {
    background: linear-gradient(135deg, #e64a19 0%, #ea580c 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(255, 87, 34, 0.45);
}

.cd-site-btn--order:hover .cd-btn-icon-wrap {
    transform: translateX(3px);
}

/* 4. Bottom Bespoke Custom Project Strip */
.cd-custom-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 30px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    color: #ffffff;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.15);
    position: relative;
    z-index: 1;
}

.cd-cbanner-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.cd-cbanner-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    background: rgba(255, 87, 34, 0.18);
    border: 1px solid rgba(255, 87, 34, 0.35);
    color: #ff7043;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.cd-cbanner-title {
    font-size: 1.22rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 5px;
}

.cd-cbanner-sub {
    font-size: 0.92rem;
    color: #94a3b8;
    margin: 0;
}

.cd-cbanner-btn {
    background: #ffffff;
    color: #0f172a !important;
    font-size: 0.94rem;
    font-weight: 700;
    padding: 12px 24px;
    border-radius: 999px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    flex-shrink: 0;
}

.cd-cbanner-btn:hover {
    background: #ff5722;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 87, 34, 0.4);
}

.cd-cbanner-btn:hover .text-success {
    color: #ffffff !important;
}

/* 5. Responsive Breakpoints */
@media (max-width: 991px) {
    .cd-sites-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 22px;
    }
    .cd-custom-banner {
        flex-direction: column;
        align-items: flex-start;
        padding: 26px;
    }
    .cd-cbanner-right {
        width: 100%;
    }
    .cd-cbanner-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 767px) {
    .cd-sites-showcase {
        padding: 70px 0 60px;
    }
    .cd-sites-grid {
        grid-template-columns: 1fr;
    }
    .cd-sites-tabs {
        border-radius: 16px;
        width: 100%;
        justify-content: flex-start;
        overflow-x: auto;
        flex-wrap: nowrap;
        padding: 6px;
        -webkit-overflow-scrolling: touch;
    }
    .cd-stab {
        white-space: nowrap;
        padding: 7px 14px;
        font-size: 0.84rem;
    }
    .cd-cbanner-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('#cdSitesTabs .cd-stab');
    const cards = document.querySelectorAll('#cdSitesGrid .cd-site-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('is-active'));
            this.classList.add('is-active');

            const filter = this.getAttribute('data-cat');

            cards.forEach(card => {
                const cardCat = card.getAttribute('data-cat');
                if (filter === 'all' || cardCat === filter) {
                    card.style.display = 'flex';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(12px)';
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 20);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
