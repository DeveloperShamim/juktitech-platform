@extends('layouts.front')
@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ (Search Engine Optimization) --}}
    <title>{{ $product->name }} - {{ $gs->title }}</title>
    <meta name="description" content="{{ $product->meta_description}}">
    <meta name="keywords" content="{{ $product->meta_keyword }}">
    <meta name="author" content="{{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ২. ওপেন গ্রাফ ট্যাগ (Facebook, LinkedIn, WhatsApp-এ শেয়ার করার জন্য) --}}
    <meta property="og:title" content="{{ $product->name }} - {{ $gs->title }}" />
    <meta property="og:description" content="{{ $product->meta_description}}" />
    <meta property="og:image" content="{{ asset_image('assets/images/products/'.$product->photo) }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $product->name }} - {{ $gs->title }}" />

    {{-- ৩. টুইটার কার্ড ট্যাগ (Twitter-এর জন্য) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->name }} - {{ $gs->title }}">
    <meta name="twitter:description" content="{{ $product->meta_description}}">
    <meta name="twitter:image" content="{{ asset_image('assets/images/products/'.$product->photo) }}">
@endsection
@section('contents')
@php
    $tiers = $product->clean_pricing_tiers;
@endphp
<section class="product-details-area py-5 bg-white">
    <div class="container py-lg-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}" class="text-decoration-none text-muted">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('frontend.themes') }}" class="text-decoration-none text-muted">থিমস</a></li>
                <li class="breadcrumb-item active text-orange fw-bold" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-4 align-items-start">
            <div class="col-lg-6">
                <div class="product-image-wrapper shadow-lg rounded-4 overflow-hidden border bg-light">
                    <img src="{{ asset_image('assets/images/products/'.$product->photo) }}"
                         class="img-fluid w-100 main-product-img"
                         alt="{{ $product->name }}">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="ps-lg-2">
                    <span class="badge bg-soft-orange text-orange mb-2 px-3 py-2 rounded-pill">প্রিমিয়াম ওয়েবসাইট</span>
                    <h1 class="fw-bold mb-3 text-dark">{{ $product->name }}</h1>

                    <div class="price-box mb-4 d-flex align-items-center gap-3">
                        <h2 class="fw-bold mb-0 text-orange product-price">৳ {{ $product->price }}</h2>
                        @if($product->previous_price)
                            <del class="text-muted fs-4">৳ {{ $product->previous_price }}</del>
                        @endif
                    </div>

                    <p class="text-muted mb-4 fs-5 leading-relaxed">{{ $product->description }}</p>

                    <div class="d-flex flex-wrap gap-3 mb-3">
                        @if($product->demo_link)
                        <a href="#live-test-drive" class="btn btn-dark btn-lg px-5 rounded-3 shadow-sm transition">
                            <i class="fas fa-eye me-2"></i> লাইভ টেস্ট ড্রাইভ
                        </a>
                        @endif

                        @php
                            $userHasPurchased = false;
                            $userDownloadUrl = null;
                            if(Auth::check()) {
                                $existingPo = \App\Models\ProductOrder::where('user_id', Auth::id())
                                    ->where('product_id', $product->id)
                                    ->where('status', 'paid')
                                    ->first();
                                if($existingPo) {
                                    $userHasPurchased = true;
                                    $userDownloadUrl = route('digital-product.download', $existingPo->download_token);
                                }
                            }
                        @endphp

                        @if($userHasPurchased)
                            <a href="{{ $userDownloadUrl }}" class="btn btn-success btn-lg px-5 rounded-3 shadow transition">
                                <i class="fas fa-cloud-arrow-down me-2"></i> থিম ফাইল ডাউনলোড করুন
                            </a>
                        @else
                            <button type="button"
                                    class="btn btn-orange btn-lg px-5 rounded-3 shadow transition js-open-theme-order"
                                    data-tier-index="0"
                                    data-tier-title="{{ $tiers[0]['title'] ?? 'সোর্স কোড' }}"
                                    data-tier-price="{{ $product->price }}">
                                <i class="fas fa-shopping-cart me-2"></i> এখুনি অর্ডার করুন
                            </button>
                        @endif
                    </div>

                    <div class="mb-4 d-flex flex-wrap align-items-center gap-2">
                        @auth
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-xs">
                                <i class="fas fa-user-check text-success me-1"></i> লগইন অ্যাকাউন্ট: <strong>{{ Auth::user()->name }}</strong>
                            </span>
                            <a href="{{ route('user.digital-downloads.index') }}" class="btn btn-sm btn-link text-decoration-none text-primary fw-semibold p-0">
                                <i class="fas fa-folder-open me-1"></i> আমার ডাউনলোড
                            </a>
                        @else
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-xs">
                                <i class="fas fa-lock text-warning me-1"></i> ইতিমধ্যে কিনেছেন? ফাইল ডাউনলোড করতে <a href="{{ route('login', ['redirect_to' => url()->current()]) }}" class="text-orange fw-bold">লগইন করুন</a>
                            </span>
                        @endauth
                    </div>

                    <div class="feature-card p-4 rounded-4 border bg-light shadow-sm">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-star text-warning me-2"></i> মূল বৈশিষ্ট্যসমূহ:</h6>
                        <ul class="list-unstyled mb-0 row">
                            <li class="col-6 mb-3 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i> রেসপন্সিভ ডিজাইন
                            </li>
                            <li class="col-6 mb-3 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i> ফ্রি ইনস্টলেশন
                            </li>
                            <li class="col-6 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i> লাইফটাইম সাপোর্ট
                            </li>
                            <li class="col-6 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i> ফ্রি আপডেট
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- FEATURE 1: লাইভ টেস্ট ড্রাইভ (Live Test Drive & Credentials) --}}
        {{-- ========================================================================= --}}
        @php
            $demoUrl = $product->demo_link ?: 'https://laravelpro.projoss.com/';
            $adminUrl = $product->demo_admin_link ?: ($product->demo_link ? rtrim($product->demo_link, '/') . '/admin/login' : 'https://laravelpro.projoss.com/admin/login');
            $adminEmail = $product->demo_admin_email ?: 'admin@projoss.com';
            $adminPass = $product->demo_admin_password ?: 'password';
        @endphp

        <div class="row mt-5" id="live-test-drive">
            <div class="col-12">
                <div class="live-test-drive-card p-4 p-md-5 rounded-4 shadow-sm border bg-white">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <span class="live-badge d-inline-flex align-items-center mb-2">
                                <span class="live-dot-pulse"></span>
                                লাইভ টেস্ট ড্রাইভ
                            </span>
                            <h2 class="h4 fw-bold text-dark mb-1">কেনার আগে ওয়েবসাইটের লাইভ ডেমো ও অ্যাডমিন ড্যাশবোর্ড দেখুন</h2>
                            <p class="text-muted mb-0 small">নিচের ক্রেডেনশিয়াল ব্যবহার করে সরাসরি অ্যাডমিন প্যানেলে লগইন করে সব ফিচার টেস্ট করুন।</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ $demoUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold btn-sm">
                                লাইভ স্টোর দেখুন <i class="fas fa-arrow-up-right-from-square ms-1"></i>
                            </a>
                            <a href="{{ $adminUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-orange rounded-pill px-4 py-2 fw-semibold btn-sm shadow-sm">
                                অ্যাডমিন ড্যাশবোর্ড <i class="fas fa-arrow-up-right-from-square ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Credential 1: DEMO Site Link --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="credential-box p-3 rounded-3 h-100">
                                <div class="cred-label mb-2">
                                    <i class="fas fa-globe me-1 text-primary"></i> DEMO Site Link
                                </div>
                                <div class="cred-input-wrap d-flex align-items-center">
                                    <span class="cred-value text-truncate font-monospace flex-grow-1" title="{{ $demoUrl }}">{{ $demoUrl }}</span>
                                    <div class="cred-actions d-flex align-items-center gap-1 ms-1">
                                        <button type="button" class="btn btn-cred-copy-short" data-clipboard-text="{{ $demoUrl }}" title="কপি করুন" aria-label="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                        <a href="{{ $demoUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-cred-open-short" title="ওপেন করুন" aria-label="Open">
                                            <i class="fas fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Credential 2: Admin Login Link --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="credential-box p-3 rounded-3 h-100">
                                <div class="cred-label mb-2">
                                    <i class="fas fa-shield-halved me-1 text-warning"></i> Admin Login Link
                                </div>
                                <div class="cred-input-wrap d-flex align-items-center">
                                    <span class="cred-value text-truncate font-monospace flex-grow-1" title="{{ $adminUrl }}">{{ $adminUrl }}</span>
                                    <div class="cred-actions d-flex align-items-center gap-1 ms-1">
                                        <button type="button" class="btn btn-cred-copy-short" data-clipboard-text="{{ $adminUrl }}" title="কপি করুন" aria-label="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                        <a href="{{ $adminUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-cred-open-short" title="ওপেন করুন" aria-label="Open">
                                            <i class="fas fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Credential 3: Admin Login Email --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="credential-box p-3 rounded-3 h-100">
                                <div class="cred-label mb-2">
                                    <i class="far fa-envelope me-1 text-info"></i> Admin Login Email
                                </div>
                                <div class="cred-input-wrap d-flex align-items-center">
                                    <span class="cred-value text-truncate font-monospace flex-grow-1" title="{{ $adminEmail }}">{{ $adminEmail }}</span>
                                    <div class="cred-actions d-flex align-items-center gap-1 ms-1">
                                        <button type="button" class="btn btn-cred-copy-short" data-clipboard-text="{{ $adminEmail }}" title="কপি করুন" aria-label="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Credential 4: Admin Password --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="credential-box p-3 rounded-3 h-100">
                                <div class="cred-label mb-2">
                                    <i class="fas fa-key me-1 text-success"></i> Admin Password
                                </div>
                                <div class="cred-input-wrap d-flex align-items-center">
                                    <span class="cred-value text-truncate font-monospace fw-bold flex-grow-1" title="{{ $adminPass }}">{{ $adminPass }}</span>
                                    <div class="cred-actions d-flex align-items-center gap-1 ms-1">
                                        <button type="button" class="btn btn-cred-copy-short" data-clipboard-text="{{ $adminPass }}" title="কপি করুন" aria-label="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- FEATURE 2: ৩-টিয়ার প্যাকেজ ও প্রাইসিং (3-Tier Modern Light Pricing) --}}
        {{-- ========================================================================= --}}
        @php
            $tiers = $product->clean_pricing_tiers;
            $supportPhone = '8801775075543';
        @endphp

        @if(!empty($tiers) && count($tiers) >= 1)
        <div class="row mt-5" id="pricing-tiers">
            <div class="col-12">
                <div class="pricing-light-wrapper rounded-5 p-4 p-md-5">
                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <div class="pricing-light-badge d-inline-flex align-items-center gap-2 mb-3">
                            <span class="badge-dot"></span>
                            <span>💰 স্বচ্ছ ও সাশ্রয়ী প্যাকেজ</span>
                        </div>
                        <h2 class="display-6 fw-bold text-dark mb-2">স্বচ্ছ ও সাশ্রয়ী মূল্য — কোনো মাসিক চার্জ নেই</h2>
                        <p class="text-muted fs-5 mb-0">একবার পেমেন্ট করুন, আজীবনের জন্য ব্যবহার করুন। কোনো প্রকার অতিরিক্ত বা গোপন চার্জ নেই।</p>
                    </div>

                    {{-- 3 Tiers Grid --}}
                    <div class="row g-4 align-items-stretch">
                        @foreach($tiers as $index => $tier)
                            @php
                                $isFeatured = !empty($tier['is_featured']);
                                $tierTitle = $tier['title'] ?? ('প্যাকেজ ' . ($index + 1));
                                $tierSubtitle = $tier['subtitle'] ?? '';
                                $tierBadge = $tier['badge'] ?? '';
                                $tierPrice = $tier['price'] ?? 0;
                                $tierPrevPrice = $tier['previous_price'] ?? null;
                                $tierSavings = $tier['savings_text'] ?? null;
                                $tierHighlightTag = $tier['highlight_tag'] ?? null;
                                $tierHighlightSub = $tier['highlight_sub'] ?? null;
                                $tierFeatures = $tier['features'] ?? [];
                                $tierBtnText = $tier['btn_text'] ?? 'প্যাকেজটি নিন';
                                $tierBtnLink = $tier['btn_link'] ?? null;

                                if (!$tierBtnLink) {
                                    $msg = rawurlencode("Hello Jukti Tech! আমি '{$product->name}' এর '{$tierTitle}' প্যাকেজটি নিতে চাই। বিস্তারিত ও অর্ডার প্রসেস জানতে চাই।");
                                    $tierBtnLink = "https://wa.me/{$supportPhone}?text={$msg}";
                                }
                            @endphp
                            <div class="col-12 col-lg-4">
                                <div class="pricing-light-card h-100 d-flex flex-column {{ $isFeatured ? 'pricing-light-card-featured' : '' }}">
                                    @if($isFeatured)
                                        <div class="featured-light-ribbon">
                                            <i class="fas fa-fire-flame-curved me-1"></i> সর্বাধিক জনপ্রিয়
                                        </div>
                                    @endif

                                    <div class="pricing-card-header">
                                        @if($tierBadge)
                                            <span class="tier-pill-badge-light mb-3 d-inline-block">{{ $tierBadge }}</span>
                                        @endif
                                        <h3 class="tier-title text-dark fw-bold mb-2">{{ $tierTitle }}</h3>
                                        <p class="tier-desc text-muted small mb-4">{{ $tierSubtitle }}</p>

                                        <div class="tier-price-box mb-4">
                                            <div class="d-flex align-items-baseline gap-2 flex-wrap">
                                                <span class="tier-currency text-dark fw-bold">৳</span>
                                                <span class="tier-amount text-dark fw-extrabold">{{ is_numeric($tierPrice) ? number_format($tierPrice, 0) : $tierPrice }}</span>
                                                @if($tierPrevPrice)
                                                    <del class="tier-prev-price text-muted">৳ {{ is_numeric($tierPrevPrice) ? number_format($tierPrevPrice, 0) : $tierPrevPrice }}</del>
                                                @endif
                                                @if($tierSavings)
                                                    <span class="tier-save-badge-light">{{ $tierSavings }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($tierHighlightTag)
                                            <div class="tier-highlight-box-light mb-4">
                                                <div class="fw-bold text-dark small">{{ $tierHighlightTag }}</div>
                                                @if($tierHighlightSub)
                                                    <div class="text-muted x-small mt-1">{{ $tierHighlightSub }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="pricing-card-body flex-grow-1">
                                        <div class="tier-features-title text-muted x-small text-uppercase fw-bold mb-3 tracking-wider">
                                            প্যাকেজের অন্তর্ভুক্ত ফিচারসমূহ:
                                        </div>
                                        <ul class="tier-feature-list list-unstyled mb-0">
                                            @foreach($tierFeatures as $feat)
                                                @php
                                                    $featText = is_array($feat) ? ($feat['text'] ?? '') : $feat;
                                                    $isIncluded = is_array($feat) ? ($feat['included'] ?? true) : true;
                                                @endphp
                                                @if(!empty($featText))
                                                    <li class="d-flex align-items-start gap-2 mb-3 small {{ $isIncluded ? 'text-dark' : 'text-muted text-decoration-line-through opacity-50' }}">
                                                        @if($isIncluded)
                                                            <span class="feature-check-icon-light"><i class="fas fa-check"></i></span>
                                                        @else
                                                            <span class="feature-cross-icon-light"><i class="fas fa-times"></i></span>
                                                        @endif
                                                        <span>{{ $featText }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="pricing-card-footer mt-4 pt-3 border-top border-light-subtle">
                                        <button type="button"
                                                class="btn w-100 py-3 rounded-3 fw-bold js-open-theme-order {{ $isFeatured ? 'btn-tier-featured-light' : 'btn-tier-standard-light' }}"
                                                data-tier-index="{{ $index }}"
                                                data-tier-title="{{ $tierTitle }}"
                                                data-tier-price="{{ $tierPrice }}">
                                            {{ $tierBtnText }} <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bottom Notice Pill --}}
                    <div class="mt-5 text-center">
                        <div class="pricing-notice-pill-light d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill small">
                            <i class="fas fa-circle-info text-warning"></i>
                            <span class="text-secondary">
                                <strong class="text-dark">Notice:</strong> এই প্যাকেজের সাথে ডোমেইন ও হোস্টিং অন্তর্ভুক্ত নয়। ডোমেইন ও হোস্টিং প্রয়োজন হলে আলাদা চার্জ প্রযোজ্য।
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-5">
            <div class="col-12">
                <div class="p-4 p-md-5 border rounded-4 bg-light shadow-sm">
                    <h3 class="fw-bold mb-4 border-bottom pb-3 text-dark">বিস্তারিত বর্ণনা</h3>
                    <div class="details-content custom-html-content">
                        {!! $product->details !!}
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($product->tutorials))
        <div class="row mt-4">
            <div class="col-12">
                <div class="tutorial-section p-4 p-md-5 border rounded-4 bg-light shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 border-bottom pb-3">
                        <h3 class="fw-bold text-dark mb-0">
                            <i class="fas fa-play-circle text-orange me-2"></i> টিউটোরিয়াল
                        </h3>
                        <span class="badge bg-white text-dark border rounded-pill px-3 py-2">
                            {{ count($product->tutorials) }}টি ভিডিও
                        </span>
                    </div>

                    <div class="row g-3">
                        @foreach($product->tutorials as $tutorial)
                            @php
                                $tutorialTitle = $tutorial['title'] ?? 'টিউটোরিয়াল';
                                $tutorialUrl = $tutorial['url'] ?? '';
                                $videoId = $product->tutorialVideoId($tutorialUrl);
                                $embedUrl = $tutorialUrl ? $product->tutorialEmbedUrl($tutorialUrl) : null;
                                $thumbnailUrl = $product->tutorialThumbnailUrl($videoId);
                            @endphp
                            @if($tutorialUrl)
                            <div class="col-6 col-md-4 col-xl-3">
                                <article class="tutorial-card h-100 bg-white border rounded-4 overflow-hidden">
                                    @if($embedUrl && $thumbnailUrl)
                                        <button type="button"
                                                class="tutorial-thumb"
                                                data-embed="{{ $embedUrl }}"
                                                aria-label="{{ $tutorialTitle }} চালান">
                                            <img src="{{ $thumbnailUrl }}"
                                                 alt="{{ $tutorialTitle }}"
                                                 loading="lazy">
                                            <span class="tutorial-play"><i class="fas fa-play"></i></span>
                                        </button>
                                    @elseif($embedUrl)
                                        <div class="tutorial-video">
                                            <iframe src="{{ $embedUrl }}"
                                                    title="{{ $tutorialTitle }}"
                                                    loading="lazy"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    allowfullscreen></iframe>
                                        </div>
                                    @else
                                        <a href="{{ $tutorialUrl }}" target="_blank" rel="noopener noreferrer" class="tutorial-link-cover">
                                            <i class="fas fa-play-circle"></i>
                                        </a>
                                    @endif
                                    <div class="p-3">
                                        <h6 class="fw-bold text-dark mb-2 tutorial-title">{{ $tutorialTitle }}</h6>
                                        <a href="{{ $tutorialUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm rounded-pill tutorial-btn">
                                            <i class="fas fa-external-link-alt me-1"></i> দেখুন
                                        </a>
                                    </div>
                                </article>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .product-image-wrapper { line-height: 0; }
    .main-product-img {
        display: block;
        width: 100%;
        max-height: 420px;
        object-fit: contain;
        padding: 10px;
        transition: transform 0.5s ease;
    }
    .product-price { font-size: 2.5rem; }
    .bg-soft-orange { background-color: rgba(124, 58, 237, 0.1); }
    .text-orange { color: var(--primary-color) !important; }
    .btn-orange { background: var(--primary-color) !important; color: #fff !important; border: none; font-weight: 600; }
    .btn-orange:hover { background: var(--primary-hover) !important; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(124, 58, 237,0.2) !important; }
    .btn-dark:hover { transform: translateY(-3px); }
    .transition { transition: all 0.3s ease; }
    .product-image-wrapper:hover .main-product-img { transform: scale(1.02); }
    .leading-relaxed { line-height: 1.7; }
    .custom-html-content { font-size: 1.1rem; line-height: 1.8; color: #333; }
    .custom-html-content img { max-width: 100%; height: auto !important; border-radius: 10px; margin: 15px 0; }
    .tutorial-card { transition: transform .25s ease, box-shadow .25s ease; }
    .tutorial-card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(15,23,42,.12); }
    .tutorial-thumb {
        position: relative; width: 100%; padding: 0; border: 0; background: #0f172a;
        display: block; cursor: pointer; aspect-ratio: 16 / 9; overflow: hidden;
    }
    .tutorial-thumb img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .25s ease;
    }
    .tutorial-thumb:hover img { transform: scale(1.04); }
    .tutorial-play {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        background: rgba(15,23,42,.28); color: #fff; font-size: 1.6rem;
        transition: background .2s ease;
    }
    .tutorial-thumb:hover .tutorial-play { background: rgba(15,23,42,.42); }
    .tutorial-play i {
        width: 42px; height: 42px; border-radius: 50%;
        background: rgba(239,68,68,.92); display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 20px rgba(0,0,0,.25); padding-left: 3px;
    }
    .tutorial-video { position: relative; width: 100%; padding-top: 56.25%; background: #0f172a; }
    .tutorial-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
    .tutorial-link-cover {
        min-height: 130px; display: flex; align-items: center; justify-content: center;
        color: #fff; background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        text-decoration: none; font-size: 2.4rem;
    }
    .tutorial-link-cover:hover { color: #fff; }
    .tutorial-title {
        font-size: .92rem; line-height: 1.45;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .tutorial-btn { font-size: .76rem; padding: .25rem .8rem; }

    /* ========================================================== */
    /* FEATURE 1: লাইভ টেস্ট ড্রাইভ (Live Test Drive) STYLES */
    /* ========================================================== */
    .live-test-drive-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        position: relative;
    }
    .live-badge {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 50px;
    }
    .live-dot-pulse {
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
        margin-right: 6px;
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        animation: livePulse 1.8s infinite;
    }
    @keyframes livePulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .credential-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .credential-box:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
    }
    .cred-label {
        font-size: 0.74rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .cred-input-wrap {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 6px 4px 10px;
        transition: all 0.2s ease;
    }
    .cred-input-wrap:hover,
    .cred-input-wrap:focus-within {
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.08);
    }
    .cred-value {
        font-size: 0.83rem;
        color: #0f172a;
        user-select: all;
        line-height: 1.4;
    }
    .btn-cred-copy-short,
    .btn-cred-open-short {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: 0.82rem;
        transition: all 0.18s ease;
        flex-shrink: 0;
    }
    .btn-cred-copy-short:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .btn-cred-copy-short.copied {
        background: #ecfdf5 !important;
        border-color: #a7f3d0 !important;
        color: #059669 !important;
    }
    .btn-cred-open-short:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* ========================================================== */
    /* FEATURE 2: ৩-টিয়ার প্যাকেজ ও প্রাইসিং (MODERN LIGHT THEME) */
    /* ========================================================== */
    .pricing-light-wrapper {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 28px;
        box-shadow: 0 20px 50px -15px rgba(15, 23, 42, 0.06);
        position: relative;
    }
    .pricing-light-badge {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #ea580c;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 5px 16px;
        border-radius: 50px;
    }
    .pricing-light-badge .badge-dot {
        width: 8px;
        height: 8px;
        background: #ff5722;
        border-radius: 50%;
        display: inline-block;
    }
    .pricing-light-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 36px 26px;
        position: relative;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .pricing-light-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .pricing-light-card-featured {
        background: #ffffff;
        border: 2px solid #ff5722 !important;
        box-shadow: 0 16px 40px -10px rgba(255, 87, 34, 0.22);
    }
    .pricing-light-card-featured:hover {
        box-shadow: 0 24px 45px -10px rgba(255, 87, 34, 0.32);
    }
    .featured-light-ribbon {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #ff5722, #f97316);
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 18px;
        border-radius: 50px;
        box-shadow: 0 4px 14px rgba(255, 87, 34, 0.35);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    .tier-pill-badge-light {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 50px;
    }
    .pricing-light-card-featured .tier-pill-badge-light {
        background: #fff7ed;
        color: #ea580c;
        border-color: #fed7aa;
    }
    .tier-title {
        font-size: 1.35rem;
    }
    .tier-desc {
        min-height: 40px;
        line-height: 1.5;
    }
    .tier-currency {
        font-size: 1.4rem;
        color: #0f172a;
    }
    .tier-amount {
        font-size: 2.3rem;
        line-height: 1;
        letter-spacing: -0.02em;
    }
    .pricing-light-card-featured .tier-amount {
        color: #ea580c;
    }
    .tier-prev-price {
        font-size: 1.05rem;
    }
    .tier-save-badge-light {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 50px;
    }
    .tier-highlight-box-light {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 12px 14px;
    }
    .pricing-light-card-featured .tier-highlight-box-light {
        background: #fff7ed;
        border-color: #fdba74;
    }
    .feature-check-icon-light {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .feature-cross-icon-light {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .btn-tier-featured-light {
        background: linear-gradient(135deg, #ff5722, #ea580c);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 8px 25px rgba(255, 87, 34, 0.35);
        transition: all 0.3s ease;
    }
    .btn-tier-featured-light:hover {
        background: linear-gradient(135deg, #f97316, #e64a19);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 87, 34, 0.45);
        color: #ffffff !important;
    }
    .btn-tier-standard-light {
        background: #ffffff;
        color: #0f172a !important;
        border: 1.5px solid #0f172a;
        transition: all 0.3s ease;
    }
    .btn-tier-standard-light:hover {
        background: #0f172a;
        border-color: #0f172a;
        transform: translateY(-2px);
        color: #ffffff !important;
    }
    .pricing-notice-pill-light {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
    }
    .fw-extrabold { font-weight: 800; }
    .x-small { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.05em; }

    /* Toast Notification for Clipboard */
    #copy-toast-container {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }
    .copy-toast {
        background: #0f172a;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .copy-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div id="copy-toast-container"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. টিউটোরিয়াল প্লেয়ার হ্যান্ডলার
    document.querySelectorAll('.tutorial-thumb').forEach(function (button) {
        button.addEventListener('click', function () {
            var embedUrl = button.getAttribute('data-embed');
            if (!embedUrl) return;

            var iframe = document.createElement('iframe');
            iframe.src = embedUrl + (embedUrl.indexOf('?') >= 0 ? '&' : '?') + 'autoplay=1';
            iframe.title = button.getAttribute('aria-label') || 'Tutorial video';
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
            iframe.setAttribute('allowfullscreen', '');

            var player = document.createElement('div');
            player.className = 'tutorial-video';
            player.appendChild(iframe);
            button.replaceWith(player);
        });
    });

    // 2. লাইভ টেস্ট ড্রাইভ ক্লিপবোর্ড কপি হ্যান্ডলার
    function showCopyToast(msg) {
        var container = document.getElementById('copy-toast-container');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'copy-toast';
        toast.innerHTML = '<i class="fas fa-circle-check text-success"></i> <span>' + (msg || 'ক্লিপবোর্ডে কপি সম্পন্ন হয়েছে!') + '</span>';
        container.appendChild(toast);
        setTimeout(function() { toast.classList.add('show'); }, 10);
        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() { toast.remove(); }, 300);
        }, 2200);
    }

    document.querySelectorAll('.btn-cred-copy-short, .btn-cred-copy').forEach(function (button) {
        button.addEventListener('click', function () {
            var textToCopy = button.getAttribute('data-clipboard-text') || '';
            if (!textToCopy) return;

            var originalHtml = button.innerHTML;
            var originalTitle = button.getAttribute('title') || '';

            var performSuccess = function() {
                button.classList.add('copied');
                if (button.classList.contains('btn-cred-copy-short')) {
                    button.innerHTML = '<i class="fas fa-check text-success"></i>';
                } else {
                    button.innerHTML = '<i class="fas fa-check me-1"></i> কপি হয়েছে!';
                }
                button.setAttribute('title', 'কপি হয়েছে!');
                showCopyToast('ক্লিপবোর্ডে কপি সম্পন্ন হয়েছে: ' + textToCopy);
                setTimeout(function() {
                    button.classList.remove('copied');
                    button.innerHTML = originalHtml;
                    button.setAttribute('title', originalTitle);
                }, 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(performSuccess).catch(function() {
                    fallbackCopy(textToCopy, performSuccess);
                });
            } else {
                fallbackCopy(textToCopy, performSuccess);
            }
        });
    });

    function fallbackCopy(text, callback) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (callback) callback();
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }
});
</script>
@include('frontend.partials.theme-order-modal')

@auth
    @include('frontend.partials.theme-buy-modal')
@endauth
@endsection
