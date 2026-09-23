@extends('layouts.front')
@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ (Search Engine Optimization) --}}
    <title>{{ $gs->title }}</title>
    <meta name="description" content="{{ $seo->meta_description ?? 'যুক্তি টেক (Jukti Tech) বাংলাদেশের একটি অগ্রণী প্রযুক্তি প্রতিষ্ঠান। আমরা আধুনিক ওয়েব ডিজাইন, কাস্টম সফটওয়্যার, রেডিমেড লারাভেল ই-কমার্স স্ক্রিপ্ট এবং ২৪/৭ আইটি সহায়তা প্রদান করি।' }}">
    <meta name="keywords" content="{{ $seo->meta_keys ?? 'Jukti Tech,যুক্তি টেক,juktitech.com,juktitech,laravel website,laravel ecommerce script,web design bd,web development in bangladesh,software company bd' }}">
    <meta name="author" content="{{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ২. ওপেন গ্রাফ ট্যাগ (Facebook, LinkedIn, WhatsApp-এ শেয়ার করার জন্য) --}}
    <meta property="og:title" content="{{ $gs->title }}" />
    <meta property="og:description" content="আমাদের তৈরি করা সেরা ওয়েবসাইট এবং সফটওয়্যার প্রজেক্টগুলো একনজরে দেখে নিন। আপনার আইডিয়াকে বাস্তবে রূপ দিতে আমরা প্রস্তুত।" />
    {{-- এখানে og:image এর জন্য একটি ডিফল্ট ব্যানার ইমেজ ব্যবহার করা ভালো --}}
    <meta property="og:image" content="{{asset_image('assets/images/logo/'.$gs->og_baner)}}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $gs->title }}" />

    {{-- ৩. টুইটার কার্ড ট্যাগ (Twitter-এর জন্য) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $gs->title }}">
    <meta name="twitter:description" content="আমাদের তৈরি করা সেরা ওয়েবসাইট এবং সফটওয়্যার প্রজেক্টগুলো একনজরে দেখে নিন।">
    <meta name="twitter:image" content="{{asset_image('assets/images/logo/'.$gs->og_baner)}}">
@endsection
@section('contents')
@include('frontend.partials.home-hero', ['hero' => $hero])

{{-- 2. Live Video Walkthrough Section ("চোখের পলকে দেখুন পুরো সিস্টেম কীভাবে কাজ করে") --}}
@include('frontend.partials.video-walkthrough')

{{-- 3. Ready-made Website & Software Collection --}}
@include('frontend.partials.free-website-funnel')

<style>
/* ==========================================================================
   GLOBAL UNIFIED SECTION STYLES (JUKTI TECH DESIGN SYSTEM)
   ========================================================================== */
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

.cd-text-orange,
.cd-highlight-orange {
    color: #ff5722 !important;
    display: inline-block;
}

/* Home Brands Partner Marquee */
.home-brands {
    position: relative;
    overflow: hidden;
    padding: 64px 0 72px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

.home-brands__bg {
    display: none;
}

.home-brands__head {
    max-width: 760px;
    margin: 0 auto 36px;
}

.home-brands__title {
    font-size: clamp(1.5rem, 2.6vw, 2rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.35;
    margin: 0;
}

.home-brands__desc {
    display: none;
}

.home-brands__slider {
    position: relative;
    overflow: hidden;
    width: 100%;
}

.home-brands__track {
    display: flex;
    align-items: center;
    gap: 56px;
    width: max-content;
    padding: 8px 0;
    animation: homeBrandsSlide 35s linear infinite;
    will-change: transform;
}

.home-brands__slider:hover .home-brands__track {
    animation-play-state: paused;
}

@keyframes homeBrandsSlide {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.home-brands__card {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    min-width: 120px;
    padding: 0 8px;
    background: transparent;
    border: none;
    box-shadow: none;
}

.home-brands__card:hover {
    transform: none;
}

.home-brands__link {
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-brands__logo {
    max-height: 52px;
    max-width: 160px;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: grayscale(100%);
    opacity: 0.72;
    transition: filter 0.25s ease, opacity 0.25s ease;
}

.home-brands__card:hover .home-brands__logo {
    filter: grayscale(0%);
    opacity: 1;
}

@media (max-width: 575px) {
    .home-brands { padding: 48px 0 56px; }
    .home-brands__track { gap: 40px; animation-duration: 26s; }
    .home-brands__logo { max-height: 42px; max-width: 130px; }
}
</style>

@php $svc = $sections['service_section']; @endphp

<section id="services" class="cd-services-section">
    <div class="cd-services-ambient">
        <div class="cd-svc-glow cd-svc-glow-1"></div>
        <div class="cd-svc-glow cd-svc-glow-2"></div>
    </div>

    <div class="container position-relative">
        {{-- Section Head --}}
        <div class="cd-services-head text-center">
            @if(!empty($svc->badge))
                <div class="cd-section-badge">
                    <span class="cd-badge-dot"></span>
                    <span>⚡ {{ $svc->badge }}</span>
                </div>
            @endif

            <h2 class="cd-services-title">{!! $svc->title !!}</h2>

            @if(!empty($svc->description))
                <p class="cd-services-desc">{{ $svc->description }}</p>
            @endif

            @if(!empty($svc->extra['highlights']))
                <div class="cd-services-highlights">
                    @foreach($svc->extra['highlights'] as $highlight)
                        <span class="cd-svc-pill">
                            <i class="{{ $highlight['icon'] ?? 'fas fa-check-circle' }}"></i>
                            <span>{{ $highlight['text'] ?? '' }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 3-Column Balanced Services Grid (Apple Style Squircles & Cards) --}}
        <div class="cd-services-grid">
            @php
                $serviceIconThemes = [
                    [
                        'gradient' => 'linear-gradient(135deg, #38bdf8 0%, #0284c7 100%)',
                        'shadow' => 'rgba(2, 132, 199, 0.35)',
                        'badge' => 'জনপ্রিয় সার্ভিস'
                    ],
                    [
                        'gradient' => 'linear-gradient(135deg, #34d399 0%, #059669 100%)',
                        'shadow' => 'rgba(5, 150, 105, 0.35)',
                        'badge' => 'হাই-পারফর্ম্যান্স'
                    ],
                    [
                        'gradient' => 'linear-gradient(135deg, #f472b6 0%, #db2777 100%)',
                        'shadow' => 'rgba(219, 39, 119, 0.35)',
                        'badge' => 'গ্রোথ মার্কেটিং'
                    ],
                    [
                        'gradient' => 'linear-gradient(135deg, #fb923c 0%, #ea580c 100%)',
                        'shadow' => 'rgba(234, 88, 12, 0.35)',
                        'badge' => 'ইউনিক ক্রিয়েটিভ'
                    ],
                    [
                        'gradient' => 'linear-gradient(135deg, #818cf8 0%, #4f46e5 100%)',
                        'shadow' => 'rgba(79, 70, 229, 0.35)',
                        'badge' => 'মডার্ন ইউজার এক্সপেরিয়েন্স'
                    ],
                    [
                        'gradient' => 'linear-gradient(135deg, #2dd4bf 0%, #0d9488 100%)',
                        'shadow' => 'rgba(13, 148, 136, 0.35)',
                        'badge' => 'ব্র্যান্ড আইডেন্টিটি'
                    ],
                ];
            @endphp

            @forelse($services as $index => $service)
                @php 
                    $theme = $serviceIconThemes[$index % count($serviceIconThemes)]; 
                @endphp
                <div class="cd-service-card">
                    <div class="cd-service-card__top">
                        <div class="cd-service-squircle" style="background: {{ $theme['gradient'] }}; box-shadow: 0 10px 22px {{ $theme['shadow'] }};">
                            <i class="{{ $service->icon }}"></i>
                        </div>
                        <span class="cd-service-tag">
                            <span class="cd-service-tag-dot"></span>
                            {{ $theme['badge'] }}
                        </span>
                    </div>

                    <h3 class="cd-service-card-title">{{ $service->title }}</h3>
                    <p class="cd-service-card-desc">{{ $service->description }}</p>

                    <div class="cd-service-perks">
                        <span><i class="fas fa-check-circle text-success"></i> দ্রুততম ডেলিভারি ও প্রফেশনাল সাপোর্ট</span>
                        <span><i class="fas fa-check-circle text-success"></i> ১০০% রেসপন্সিভ ও কাস্টমাইজযোগ্য</span>
                    </div>

                    <div class="cd-service-card__footer">
                        <a href="{{ URL::to('/service') }}" class="cd-service-link">
                            <span>বিস্তারিত জানুন</span>
                            <span class="cd-service-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="cd-services-empty">
                    <p class="text-muted">কোনো সার্ভিস খুঁজে পাওয়া যায়নি।</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
/* ==========================================================================
   SERVICES SECTION (APPLE CLEAN LIGHT 3-COLUMN AESTHETIC)
   ========================================================================== */

.cd-services-section {
    position: relative;
    overflow: hidden;
    padding: 90px 0 95px;
    background: #ffffff;
    font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    border-top: 1px solid #e2e8f0;
}

.cd-services-ambient {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.cd-svc-glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(85px);
    opacity: 0.3;
}

.cd-svc-glow-1 {
    top: 10%;
    left: -60px;
    width: 450px;
    height: 450px;
    background: rgba(255, 87, 34, 0.08);
}

.cd-svc-glow-2 {
    bottom: 10%;
    right: -60px;
    width: 400px;
    height: 400px;
    background: rgba(2, 132, 199, 0.06);
}

.cd-services-head {
    max-width: 820px;
    margin: 0 auto 48px;
}

.cd-services-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 18px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    color: #111827;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.cd-services-badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
}

.cd-services-title {
    font-size: clamp(1.75rem, 3.5vw, 2.5rem);
    font-weight: 800;
    color: #111827;
    line-height: 1.32;
    margin-bottom: 14px;
    letter-spacing: -0.015em;
}

.cd-services-desc {
    margin: 0 auto 24px;
    max-width: 680px;
    font-size: 1.02rem;
    line-height: 1.7;
    color: #6b7280;
    font-weight: 500;
}

.cd-services-highlights {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
}

.cd-svc-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 16px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    font-size: 0.85rem;
    font-weight: 600;
    color: #4b5563;
}

.cd-svc-pill i {
    color: #ff5722;
    font-size: 0.8rem;
}

/* 3-Column Balanced Grid */
.cd-services-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}

.cd-services-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
}

/* Apple Service Card */
.cd-service-card {
    background: #ffffff;
    border: 1px solid #e5e7ea;
    border-radius: 22px;
    padding: 30px 26px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
}

.cd-service-card:hover {
    transform: translateY(-6px);
    border-color: #ff5722;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.07);
}

.cd-service-card__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

/* Apple Squircle Icon */
.cd-service-squircle {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.3rem;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.cd-service-card:hover .cd-service-squircle {
    transform: scale(1.08);
}

.cd-service-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
}

.cd-service-tag-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ff5722;
}

.cd-service-card-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 10px;
    line-height: 1.35;
}

.cd-service-card-desc {
    flex-grow: 1;
    margin: 0 0 18px;
    font-size: 0.92rem;
    line-height: 1.65;
    color: #6b7280;
}

.cd-service-perks {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 14px 0;
    margin-bottom: 18px;
    border-top: 1px dashed #f1f5f9;
    border-bottom: 1px dashed #f1f5f9;
}

.cd-service-perks span {
    font-size: 0.84rem;
    color: #4b5563;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cd-service-card__footer {
    margin-top: auto;
}

.cd-service-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #111827 !important;
    text-decoration: none !important;
    transition: color 0.2s ease, gap 0.2s ease;
}

.cd-service-arrow {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #111827;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    transition: background 0.2s ease, transform 0.2s ease, color 0.2s ease;
}

.cd-service-card:hover .cd-service-link {
    color: #ff5722 !important;
    gap: 12px;
}

.cd-service-card:hover .cd-service-arrow {
    background: #ff5722;
    color: #ffffff;
    transform: translateX(3px);
}

@media (max-width: 991px) {
    .cd-services-grid { 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
    }
}

@media (max-width: 640px) {
    .cd-services-section { 
        padding: 60px 0 70px; 
    }
    .cd-services-grid { 
        grid-template-columns: 1fr; 
    }
    .cd-service-card {
        padding: 24px 20px;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<style>
    /* ==========================================================================
       TESTIMONIALS SECTION (CLEAN WHITE SURFACE + ORANGE ACCENTS)
       ========================================================================== */
    .home-reviews {
        position: relative;
        overflow: hidden;
        padding: 90px 0 100px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .home-reviews__bg {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image: radial-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    .home-reviews__head {
        max-width: 760px;
        margin: 0 auto 44px;
    }

    .home-reviews__title {
        font-size: clamp(1.85rem, 3.4vw, 2.65rem);
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 14px;
    }

    .home-reviews__desc {
        margin: 0 auto;
        max-width: 660px;
        font-size: 1.02rem;
        line-height: 1.7;
        color: #64748b;
        font-weight: 500;
    }

    .home-reviews .testimonial-carousel .item {
        padding: 24px 12px 12px;
    }

    .home-reviews__card {
        background: #ffffff;
        border-radius: 20px;
        padding: 58px 26px 28px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid #e2e8f0;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
        text-align: center;
        height: 100%;
    }

    .home-reviews__card:hover {
        transform: translateY(-6px);
        border-color: #ff5722;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
    }

    .home-reviews__quote {
        position: absolute;
        top: 18px;
        right: 18px;
        font-size: 2.2rem;
        color: rgba(249, 115, 22, 0.12);
        line-height: 1;
    }

    .home-reviews__avatar-wrap {
        position: absolute;
        top: -42px;
        left: 50%;
        transform: translateX(-50%);
        width: 84px;
        height: 84px;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
        box-shadow: 0 8px 20px rgba(255, 87, 34, 0.25);
    }

    .home-reviews__avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        display: block;
    }

    .home-reviews__stars {
        color: #f59e0b;
        margin-bottom: 14px;
        font-size: 0.92rem;
    }

    .home-reviews__text {
        color: #475569;
        font-size: 0.94rem;
        line-height: 1.75;
        margin-bottom: 22px;
        min-height: 84px;
    }

    .home-reviews__name {
        margin: 0 0 4px;
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .home-reviews__role {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: #ff5722;
    }

    .home-reviews .owl-theme .owl-dots {
        margin-top: 24px;
    }

    .home-reviews .owl-theme .owl-dots .owl-dot span {
        width: 10px;
        height: 10px;
        margin: 5px 5px;
        background: #cbd5e1;
        transition: all 0.25s ease;
    }

    .home-reviews .owl-theme .owl-dots .owl-dot.active span {
        background: #ff5722;
        width: 28px;
        border-radius: 999px;
    }

    @media (max-width: 575px) {
        .home-reviews { padding: 60px 0 72px; }
        .home-reviews__text { min-height: auto; }
    }
	
	/* ==========================================================================
       RECENT CUSTOMER WEBSITE SPOTLIGHT SLIDER (SOFT SLATE SURFACE)
       ========================================================================== */
.home-portfolio {
    position: relative;
    overflow: hidden;
    padding: 90px 0 100px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.home-portfolio__bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image: 
        linear-gradient(to right, rgba(0, 0, 0, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
    background-size: 36px 36px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, #000 60%, transparent 100%);
    -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, #000 60%, transparent 100%);
}

.home-portfolio__ambient-glow {
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    filter: blur(140px);
    opacity: 0.22;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: radial-gradient(circle, #ff5722 0%, rgba(255, 87, 34, 0) 70%);
    pointer-events: none;
}

.home-portfolio__head {
    max-width: 760px;
    margin: 0 auto 42px;
    position: relative;
    z-index: 1;
}

.home-portfolio__title {
    font-size: clamp(1.85rem, 3.2vw, 2.65rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.28;
    margin-bottom: 14px;
    letter-spacing: -0.02em;
}

.home-portfolio__desc {
    margin: 0 auto;
    max-width: 660px;
    font-size: clamp(0.98rem, 1.25vw, 1.1rem);
    line-height: 1.68;
    color: #475569;
    font-weight: 500;
}

/* Spotlight Slider Stage */
.cd-spotlight-wrap {
    max-width: 1060px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.cd-spotlight-slider {
    position: relative;
    border-radius: 28px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08), 0 4px 16px rgba(0, 0, 0, 0.02);
    border: 1px solid #e2e8f0;
    background: #ffffff;
    overflow: hidden;
}

.cd-spotlight-viewport {
    position: relative;
    overflow: hidden;
    width: 100%;
}

.cd-spotlight-track {
    display: flex;
    transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    width: 100%;
}

.cd-spotlight-slide {
    min-width: 100%;
    width: 100%;
    flex-shrink: 0;
    display: grid;
    grid-template-columns: 1.15fr 1fr;
    align-items: center;
    background: #ffffff;
}

/* Left: Device Mockup Presentation */
.cd-spotlight-media {
    padding: 34px;
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    border-right: 1px solid #e2e8f0;
}

.cd-spotlight-browser {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12);
    border: 1px solid #cbd5e1;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.cd-spotlight-browser:hover {
    transform: translateY(-4px) scale(1.01);
}

.cd-sp-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
}

.cd-sp-dots {
    display: flex;
    gap: 5px;
}

.cd-sp-dots span {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
}

.cd-sp-dots span:nth-child(1) { background: #ef4444; }
.cd-sp-dots span:nth-child(2) { background: #f59e0b; }
.cd-sp-dots span:nth-child(3) { background: #10b981; }

.cd-sp-url {
    background: #ffffff;
    padding: 3px 14px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    font-size: 0.76rem;
    color: #475569;
    display: inline-flex;
    align-items: center;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, monospace;
}

.cd-sp-chip {
    font-size: 0.72rem;
    font-weight: 700;
    color: #166534;
    background: #dcfce7;
    padding: 3px 10px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.cd-sp-img-box {
    width: 100%;
    height: 310px;
    overflow: hidden;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cd-sp-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform 0.5s ease;
}

.cd-spotlight-browser:hover .cd-sp-img {
    transform: scale(1.03);
}

/* Right: Client Story & Live Button */
.cd-spotlight-content {
    padding: 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.cd-sp-tag-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.cd-sp-cat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 999px;
    background: #fff5f2;
    border: 1px solid rgba(255, 87, 34, 0.25);
    color: #ff5722;
    font-size: 0.84rem;
    font-weight: 700;
}

.cd-sp-status {
    font-size: 0.8rem;
    font-weight: 600;
    color: #10b981;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.cd-sp-title {
    font-size: clamp(1.4rem, 2.2vw, 1.85rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
    margin-bottom: 14px;
}

.cd-sp-summary {
    font-size: 0.94rem;
    color: #64748b;
    line-height: 1.62;
    margin-bottom: 22px;
}

.cd-sp-features {
    list-style: none;
    padding: 0;
    margin: 0 0 28px 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cd-sp-features li {
    font-size: 0.9rem;
    color: #334155;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.cd-sp-features li i {
    color: #10b981;
    margin-right: 10px;
    font-size: 0.95rem;
}

/* Action Button: Live Preview */
.cd-sp-btn-wrap {
    margin-bottom: 0;
}

.cd-sp-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 13px 28px;
    border-radius: 999px;
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff !important;
    font-size: 0.98rem;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 6px 20px rgba(255, 87, 34, 0.35);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.cd-sp-btn:hover {
    background: linear-gradient(135deg, #e64a19 0%, #ea580c 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(255, 87, 34, 0.45);
}

.cd-sp-btn i {
    transition: transform 0.2s ease;
}

.cd-sp-btn:hover i {
    transform: translateX(3px) translateY(-1px);
}

/* GLOBAL Controls Bar (Fixed at bottom of the spotlight card) */
.cd-sp-global-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 36px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.cd-sp-nav-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cd-sp-arrow-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.22s ease;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.cd-sp-arrow-btn:hover {
    background: #0f172a;
    color: #ffffff;
    border-color: #0f172a;
    transform: scale(1.08);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
}

.cd-sp-arrow-btn:active {
    transform: scale(0.96);
}

.cd-sp-indicators {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cd-sp-dot {
    width: 12px;
    height: 12px;
    border-radius: 999px;
    background: #cbd5e1;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    border: none;
    outline: none;
    padding: 0;
}

.cd-sp-dot.is-active {
    width: 32px;
    background: #ff5722;
}

.cd-sp-counter {
    font-size: 0.92rem;
    font-weight: 700;
    color: #475569;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    letter-spacing: 0.05em;
}

/* Responsive Media Queries */
@media (max-width: 991px) {
    .cd-spotlight-slide {
        grid-template-columns: 1fr;
    }
    .cd-spotlight-media {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 24px;
    }
    .cd-sp-img-box {
        height: 240px;
    }
    .cd-spotlight-content {
        padding: 28px 24px;
    }
    .cd-sp-global-bar {
        padding: 14px 20px;
    }
}

@media (max-width: 575px) {
    .home-portfolio {
        padding: 60px 0 70px;
    }
    .cd-sp-img-box {
        height: 200px;
    }
    .cd-sp-btn {
        width: 100%;
        justify-content: center;
    }
    .cd-spotlight-media {
        padding: 16px;
    }
    .cd-sp-arrow-btn {
        width: 38px;
        height: 38px;
        font-size: 0.9rem;
    }
}
</style>

@php
    $portfolioSec = $sections['portfolio_section'];
    $clientFeatures = [
        'egocartbd.com' => [
            '১-পেজ ফাস্ট চেকআউট ও পেমেন্ট গেটওয়ে',
            'পাঠাও ও স্টেডফাস্ট কুরিয়ার অটো বুকিং API',
            '০.৪ সেকেন্ডে আল্ট্রাফাস্ট পেজস্পিড (PageSpeed 99)',
            'ফেসবুক পিক্সেল, CAPI ও ফেক অর্ডার প্রিভেন্টর'
        ],
        'gadgetsgarbhub.com' => [
            'আধুনিক অ্যাপল-স্টাইল গ্যাজেট শপ ইন্টারফেস',
            'অটোমেটেড ইনভয়েস ও রিয়েল-টাইম এসএমএস নোটিফিকেশন',
            'ফুল মোবাইল অপ্টিমাইজড ও রেসপনসিভ UI',
            'সার্ভার-সাইড ট্র্যাকিং ও হাই-কনভার্টিং কার্ট সিস্টেম'
        ],
    ];
@endphp

<section id="portfolio" class="home-portfolio">
    <div class="home-portfolio__bg"></div>
    <div class="home-portfolio__ambient-glow"></div>

    <div class="container position-relative">
        <!-- Section Header -->
        <div class="home-portfolio__head text-center">
            <div class="cd-section-badge">
                <span class="cd-badge-dot"></span>
                <span>⚡ ক্লায়েন্ট সাকসেস স্টোরি • লাইভ প্রজেক্ট</span>
            </div>

            <h2 class="home-portfolio__title">
                আমাদের সাম্প্রতিক <span class="cd-text-orange">ডেলিভারকৃত ওয়েবসাইট</span>
            </h2>

            <p class="home-portfolio__desc">
                সরাসরি ক্লায়েন্টের জন্য তৈরি ও সফলভাবে ডেলিভারি করা লাইভ প্রজেক্টসমূহ একনজরে দেখুন। সম্পূর্ণ সন্তুষ্টির সাথে চলমান রিয়েল বিজনেস।
            </p>
        </div>

        <!-- Spotlight 1-at-a-time Slider -->
        <div class="cd-spotlight-wrap">
            <div class="cd-spotlight-slider" id="cdSpotlightSlider">
                <div class="cd-spotlight-viewport">
                    <div class="cd-spotlight-track" id="cdSpotlightTrack">
                        @forelse($portfolios as $idx => $portfolio)
                            @php
                                $parsedHost = parse_url($portfolio->url, PHP_URL_HOST) ?: 'live-client.com';
                                $cleanHost = preg_replace('/^www\./', '', $parsedHost);
                                $features = $clientFeatures[$cleanHost] ?? [
                                    '১০০% ইউনিক ও রেসপনসিভ ডিজাইন',
                                    'সুপারফাস্ট লোডিং ও এসইও ফ্রেন্ডলি কাঠামো',
                                    'পেমেন্ট গেটওয়ে ও অটোমেশন সাপোর্ট',
                                    '২৪/৭ ক্লায়েন্ট টেকনিক্যাল সাপোর্ট'
                                ];
                            @endphp
                            <div class="cd-spotlight-slide" data-slide-index="{{ $idx }}">
                                <!-- Left: Device Mockup Presentation -->
                                <div class="cd-spotlight-media">
                                    <div class="cd-spotlight-browser">
                                        <div class="cd-sp-bar">
                                            <div class="cd-sp-dots">
                                                <span></span><span></span><span></span>
                                            </div>
                                            <div class="cd-sp-url">
                                                <i class="fas fa-lock text-success me-1"></i>
                                                <span>{{ $cleanHost }}</span>
                                            </div>
                                            <span class="cd-sp-chip">
                                                <i class="fas fa-circle text-success" style="font-size: 6px;"></i> লাইভ
                                            </span>
                                        </div>
                                        <div class="cd-sp-img-box">
                                            <img src="{{ asset_image('assets/images/portfolio/'.$portfolio->photo) }}"
                                                 class="cd-sp-img"
                                                 alt="{{ $portfolio->title }}"
                                                 loading="lazy">
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Client Story & Live Button -->
                                <div class="cd-spotlight-content">
                                    <div class="cd-sp-tag-row">
                                        <span class="cd-sp-cat">
                                            <i class="fas fa-check-circle"></i> {{ $portfolio->category->name ?? 'ই-কমার্স সলিউশন' }}
                                        </span>
                                        <span class="cd-sp-status">
                                            <i class="fas fa-shield-halved"></i> সফলভাবে ডেলিভারি সম্পন্ন
                                        </span>
                                    </div>

                                    <h3 class="cd-sp-title">
                                        {{ $portfolio->title }}
                                    </h3>

                                    <p class="cd-sp-summary">
                                        ক্লায়েন্টের স্পেসিফিক রিকোয়ারমেন্ট অনুযায়ী তৈরি এই সাইটটিতে সর্বোচ্চ গতি, কনভার্সন ফ্রেন্ডলি লেআউট এবং অটোমেশন ফিচার যুক্ত রয়েছে।
                                    </p>

                                    <ul class="cd-sp-features">
                                        @foreach($features as $feat)
                                            <li>
                                                <i class="fas fa-check-circle"></i>
                                                <span>{{ $feat }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="cd-sp-btn-wrap">
                                        <a href="{{ $portfolio->url ?? '#' }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="cd-sp-btn"
                                           id="cdSpLiveBtn-{{ $idx }}">
                                            <span>সরাসরি ওয়েবসাইট ভিজিট করুন</span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">বর্তমানে কোনো প্রজেক্ট পাওয়া যায়নি।</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if(count($portfolios) > 0)
                <!-- Global Spotlight Controls Bar (Outside sliding viewport) -->
                <div class="cd-sp-global-bar">
                    <div class="cd-sp-nav-buttons">
                        <button type="button" class="cd-sp-arrow-btn" id="cdSpPrev" aria-label="পূর্ববর্তী প্রজেক্ট">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="cd-sp-arrow-btn" id="cdSpNext" aria-label="পরবর্তী প্রজেক্ট">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <div class="cd-sp-indicators" id="cdSpIndicators">
                        @foreach($portfolios as $idx => $portfolio)
                            <button type="button" class="cd-sp-dot {{ $idx === 0 ? 'is-active' : '' }}" data-target="{{ $idx }}" aria-label="প্রজেক্ট {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="cd-sp-counter" id="cdSpCounter">
                        ০১ / {{ sprintf('%02d', count($portfolios)) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</section>

<script>
(function() {
    function initSpotlightSlider() {
        const sliderBox = document.getElementById('cdSpotlightSlider');
        const track = document.getElementById('cdSpotlightTrack');
        const slides = document.querySelectorAll('.cd-spotlight-slide');
        const prevBtn = document.getElementById('cdSpPrev');
        const nextBtn = document.getElementById('cdSpNext');
        const dots = document.querySelectorAll('#cdSpIndicators .cd-sp-dot');
        const counter = document.getElementById('cdSpCounter');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;
        const totalSlides = slides.length;
        let autoPlayTimer = null;

        const banglaNumbers = ['০১', '০২', '০৩', '০৪', '০৫', '০৬', '০৭', '০৮', '০৯', '১০'];

        function updateSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            currentIndex = index;

            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            dots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === currentIndex);
            });

            if (counter) {
                const currentStr = banglaNumbers[currentIndex] || String(currentIndex + 1).padStart(2, '0');
                const totalStr = banglaNumbers[totalSlides - 1] || String(totalSlides).padStart(2, '0');
                counter.textContent = `${currentStr} / ${totalStr}`;
            }
        }

        if (totalSlides > 1) {
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    updateSlide(currentIndex + 1);
                    resetAutoplay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    updateSlide(currentIndex - 1);
                    resetAutoplay();
                });
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    updateSlide(i);
                    resetAutoplay();
                });
            });

            function startAutoplay() {
                if (autoPlayTimer) clearInterval(autoPlayTimer);
                autoPlayTimer = setInterval(function() {
                    updateSlide(currentIndex + 1);
                }, 6000);
            }

            function resetAutoplay() {
                if (autoPlayTimer) clearInterval(autoPlayTimer);
                startAutoplay();
            }

            if (sliderBox) {
                sliderBox.addEventListener('mouseenter', () => {
                    if (autoPlayTimer) clearInterval(autoPlayTimer);
                });
                sliderBox.addEventListener('mouseleave', () => {
                    startAutoplay();
                });
            }

            // Mobile touch swipe gesture
            let touchStartX = 0;
            let touchEndX = 0;

            track.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            track.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                const swipeDistance = touchEndX - touchStartX;
                if (Math.abs(swipeDistance) > 45) {
                    if (swipeDistance < 0) {
                        updateSlide(currentIndex + 1);
                    } else {
                        updateSlide(currentIndex - 1);
                    }
                    resetAutoplay();
                }
            }, { passive: true });

            startAutoplay();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSpotlightSlider);
    } else {
        initSpotlightSlider();
    }
})();
</script>

@php $reviews = $sections['reviews_section']; @endphp
<section id="testimonials" class="home-reviews">
    <div class="home-reviews__bg"></div>
    <div class="container position-relative">
        <div class="home-reviews__head text-center">
            @if(!empty($reviews->badge))
                <div class="cd-section-badge">
                    <span class="cd-badge-dot"></span>
                    <span>⭐ {{ $reviews->badge }}</span>
                </div>
            @endif

            <h2 class="home-reviews__title">{!! $reviews->title !!}</h2>

            @if(!empty($reviews->description))
                <p class="home-reviews__desc">{{ $reviews->description }}</p>
            @endif
        </div>

        <div class="owl-carousel owl-theme testimonial-carousel">
            @foreach($testimonials as $testimonial)
                <div class="item">
                    <div class="home-reviews__card testimonial-card">
                        <i class="fas fa-quote-right home-reviews__quote"></i>
                        <div class="home-reviews__avatar-wrap client-img-wrapper">
                            <img src="{{ asset_image('assets/images/testimonials/'.$testimonial->photo) }}"
                                 class="home-reviews__avatar client-img"
                                 alt="{{ $testimonial->name }}">
                        </div>

                        <div class="home-reviews__stars stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>

                        <p class="home-reviews__text review-text">"{{ $testimonial->message }}"</p>

                        <div class="client-info">
                            <h5 class="home-reviews__name">{{ $testimonial->name }}</h5>
                            <small class="home-reviews__role">{{ $testimonial->designation }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@php $brandsSec = $sections['brands_section']; @endphp
<section class="home-brands">
    <div class="container">
        <div class="home-brands__head text-center">
            <div class="cd-section-badge">
                <span class="cd-badge-dot"></span>
                <span>🤝 বিশ্বস্ত পার্টনার ও ক্লায়েন্ট</span>
            </div>
            <h2 class="home-brands__title">{{ $brandsSec->title }}</h2>
        </div>

        @if(isset($brands) && $brands->count())
            <div class="home-brands__slider">
                <div class="home-brands__track">
                    @foreach($brands as $brand)
                        <div class="home-brands__card">
                            @if(!empty($brand->url))
                                <a href="{{ $brand->url }}" target="_blank" rel="noopener" class="home-brands__link">
                            @endif
                                <img src="{{ asset_image('assets/images/brands/'.$brand->photo) }}"
                                     alt="{{ $brand->name }}"
                                     title="{{ $brand->name }}"
                                     class="home-brands__logo">
                            @if(!empty($brand->url))
                                </a>
                            @endif
                        </div>
                    @endforeach
                    @foreach($brands as $brand)
                        <div class="home-brands__card" aria-hidden="true">
                            @if(!empty($brand->url))
                                <a href="{{ $brand->url }}" target="_blank" rel="noopener" tabindex="-1" class="home-brands__link">
                            @endif
                                <img src="{{ asset_image('assets/images/brands/'.$brand->photo) }}"
                                     alt=""
                                     title="{{ $brand->name }}"
                                     class="home-brands__logo">
                            @if(!empty($brand->url))
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@php $cta = $sections['cta_section']; @endphp
<section class="home-cta">
    <div class="home-cta__bg"></div>
    <div class="container position-relative">
        <div class="home-cta__inner text-center">
            <div class="home-cta__badge">
                <span class="home-cta__badge-dot"></span>
                <span>🚀 ইনস্ট্যান্ট স্টার্ট • ফ্রি কনসালটেশন</span>
            </div>
            <h2 class="home-cta__title">{{ $cta->title }}</h2>
            @if(!empty($cta->description))
                <p class="home-cta__desc">{{ $cta->description }}</p>
            @endif
            <div class="home-cta__actions d-flex flex-wrap justify-content-center gap-3">
                <a href="tel:+88{{ $gs->phone }}" class="home-cta__btn home-cta__btn--primary">
                    <i class="fas fa-phone-alt"></i> {{ $cta->extra['call_text'] ?? 'কল করুন' }}
                </a>
                <a href="https://m.me/{{ $gs->facebook_page_link }}" class="home-cta__btn home-cta__btn--outline">
                    <i class="fab fa-facebook-messenger"></i> {{ $cta->extra['message_text'] ?? 'মেসেজ পাঠান' }}
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* ==========================================================================
   CALL TO ACTION (WHITE DOTTED MODERN LIGHT BACKGROUND)
   ========================================================================== */
.home-cta {
    position: relative;
    overflow: hidden;
    padding: 100px 0 105px;
    background-color: #ffffff;
    border-top: 1px solid #edf2f7;
    border-bottom: 1px solid #edf2f7;
}

.home-cta__bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image: 
        radial-gradient(#cbd5e1 1.5px, transparent 1.5px),
        radial-gradient(circle at 50% 30%, rgba(255, 87, 34, 0.05), transparent 70%);
    background-size: 24px 24px, auto;
    background-position: center center;
}

.home-cta__inner {
    max-width: 760px;
    margin: 0 auto;
}

.home-cta__badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 18px;
    border-radius: 999px;
    font-size: 0.88rem;
    font-weight: 700;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #ea580c;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(234, 88, 12, 0.08);
}

.home-cta__badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.25);
}

.home-cta__title {
    font-size: clamp(1.95rem, 3.5vw, 2.75rem);
    font-weight: 800;
    line-height: 1.3;
    margin: 0 0 16px;
    color: #0f172a;
    letter-spacing: -0.02em;
}

.home-cta__desc {
    margin: 0 auto 34px;
    max-width: 640px;
    font-size: 1.1rem;
    line-height: 1.7;
    color: #475569;
    font-weight: 500;
}

.home-cta__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 34px;
    border-radius: 999px;
    font-size: 1.02rem;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.home-cta__btn--primary {
    background: linear-gradient(135deg, #ff5722 0%, #f97316 100%);
    color: #ffffff !important;
    border: none;
    box-shadow: 0 10px 25px rgba(255, 87, 34, 0.3);
}

.home-cta__btn--primary:hover {
    background: linear-gradient(135deg, #e64a19 0%, #ea580c 100%);
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(255, 87, 34, 0.42);
    color: #ffffff !important;
}

.home-cta__btn--outline {
    background: #ffffff;
    color: #1e293b !important;
    border: 1.5px solid #cbd5e1;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
}

.home-cta__btn--outline:hover {
    transform: translateY(-2px);
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a !important;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.09);
}

.home-cta__btn--outline i {
    color: #0084ff;
}

@media (max-width: 575px) {
    .home-cta { padding: 65px 0 75px; }
    .home-cta__btn { width: 100%; max-width: 320px; }
}
</style>

@endsection


