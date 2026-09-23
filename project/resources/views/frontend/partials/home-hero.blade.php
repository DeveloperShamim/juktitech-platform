@php
    $heroBadge = $hero->hero_badge ?? '⚡ প্রিমিয়াম ওয়েবসাইট ও সফটওয়্যার সলিউশন';
    $heroTitle = $hero->title ?? null;
    $heroSubtitle = $hero->subtitle ?? 'এক ক্লিকে কুরিয়ার অটোমেশন, ০.৪ সেকেন্ডে সুপারফাস্ট লোডিং ও আজীবন লাইসেন্স।';
    $buttons = $hero->buttons ?? [];
    $primaryBtnText = $buttons[0]['text'] ?? 'লাইভ ডেমো দেখুন';
    $primaryBtnUrl  = (!empty($buttons[0]['url']) && $buttons[0]['url'] !== '#demo') ? $buttons[0]['url'] : 'https://vantor.juktitech.com/';
    $secondaryBtnText = $buttons[1]['text'] ?? 'ওয়েবসাইট কালেকশন';
    $secondaryBtnUrl  = $buttons[1]['url'] ?? '#all-websites';
@endphp

<section class="cd-hero">
    <!-- Grid & Radial Ambient Backlight -->
    <div class="cd-hero__grid-bg"></div>
    <div class="cd-hero__glow-bg"></div>

    <div class="container position-relative cd-hero__content-wrap">
        <!-- Top Status Pill Badge -->
        <div class="cd-hero__badge-wrap text-center">
            <div class="cd-hero__pill-badge">
                <span class="cd-hero__pulse-dot"></span>
                <span>{{ $heroBadge }}</span>
                <span class="cd-badge-arrow ms-1">→</span>
            </div>
        </div>

        <!-- Main Headline -->
        <h1 class="cd-hero__headline text-center">
            @if(!empty($heroTitle))
                {!! $heroTitle !!}
            @else
                রেডি ওয়েবসাইট কিনুন<br>
                <span class="cd-text-orange">ইনস্ট্যান্ট</span> <span class="cd-text-dark">ডেলিভারি</span>
            @endif
        </h1>

        <!-- Subtitle Description (Short & Clean) -->
        <p class="cd-hero__subtext text-center">
            {!! $heroSubtitle !!}
        </p>

        <!-- CTA Action Buttons (Image 3 Style: Pill with Right Inset Icon Container) -->
        <div class="cd-hero__cta-group">
            <a href="{{ $primaryBtnUrl }}" target="_blank" rel="noopener noreferrer" class="cd-hero__btn cd-hero__btn--primary">
                <span class="cd-btn-label">{{ $primaryBtnText }}</span>
                <span class="cd-btn-box">
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
            <a href="{{ $secondaryBtnUrl }}" class="cd-hero__btn cd-hero__btn--secondary">
                <span class="cd-btn-label">{{ $secondaryBtnText }}</span>
                <span class="cd-btn-box">
                    <i class="fas fa-layer-group"></i>
                </span>
            </a>
        </div>

        <!-- Interactive Template Switcher Pills -->
        <div class="cd-switcher-wrap text-center">
            <div class="cd-switcher-bar" id="cdSwitcherBar">
                <button type="button" class="cd-switcher-tab is-active" data-tab="ecom" onclick="switchHeroTab('ecom')">
                    <span class="cd-tab-icon">🛍️</span> ই-কমার্স প্রো
                </button>
                <button type="button" class="cd-switcher-tab" data-tab="news" onclick="switchHeroTab('news')">
                    <span class="cd-tab-icon">📰</span> নিউজ ও ই-পেপার
                </button>
                <button type="button" class="cd-switcher-tab" data-tab="hosting" onclick="switchHeroTab('hosting')">
                    <span class="cd-tab-icon">🎁</span> ফ্রি ওয়েবসাইট (হোস্টিং ডিল)
                </button>
                <button type="button" class="cd-switcher-tab" data-tab="software" onclick="switchHeroTab('software')">
                    <span class="cd-tab-icon">⚡</span> কাস্টম সফটওয়্যার ও ERP
                </button>
            </div>
        </div>

        <!-- Master Multi-Device Stage (MacBook Studio + 2 Floating Angled iPhones + Glass Badges) -->
        <div class="cd-multi-stage">

            <!-- Ambient Radiant Underglow -->
            <div class="cd-stage-glow"></div>

            <!-- Floating Trust Badges (Glassmorphic) -->
            <div class="cd-float-badge cd-float-badge--top-left" title="Google PageSpeed">
                <div class="cd-fbadge-icon text-warning"><i class="fas fa-bolt"></i></div>
                <div class="cd-fbadge-text">
                    <strong>PageSpeed 99/100</strong>
                    <small>০.৪ সেকেন্ডে আল্ট্রাফাস্ট লোড</small>
                </div>
            </div>

            <div class="cd-float-badge cd-float-badge--top-right" title="Affiliate Offer">
                <div class="cd-fbadge-icon text-success"><i class="fas fa-gift"></i></div>
                <div class="cd-fbadge-text">
                    <strong>১০০% ফ্রি ওয়েবসাইট</strong>
                    <small>হোস্টিং নিলেই ফুল সেটআপ ফ্রি</small>
                </div>
            </div>

            <div class="cd-float-badge cd-float-badge--bottom-left" title="Security Blocker">
                <div class="cd-fbadge-icon text-danger"><i class="fas fa-shield-alt"></i></div>
                <div class="cd-fbadge-text">
                    <strong>ফেক অর্ডার রোধ</strong>
                    <small>০% রিটার্ন লস সিকিউরিটি</small>
                </div>
            </div>

            <div class="cd-float-badge cd-float-badge--bottom-right" title="Automation API">
                <div class="cd-fbadge-icon text-info"><i class="fas fa-truck-fast"></i></div>
                <div class="cd-fbadge-text">
                    <strong>Steadfast & Pathao API</strong>
                    <small>এক ক্লিকে অটো বুকিং ও ট্র্যাকিং</small>
                </div>
            </div>

            <!-- LEFT FLOATING IPHONE (Angle -12deg) -->
            <div class="cd-device-phone cd-device-phone--left">
                <div class="cd-phone-notch"></div>
                <div class="cd-phone-inner cd-phone-inner--cyan">
                    <div class="cd-phone-header">
                        <span class="cd-pill-tag bg-cyan">এক ক্লিকে COD অর্ডার</span>
                    </div>
                    <div class="cd-mini-form">
                        <div class="cd-mini-title">ইনস্ট্যান্ট চেকআউট</div>
                        <div class="cd-mini-input">
                            <span class="cd-mini-label">নাম:</span> মোঃ তানভীর হাসান
                        </div>
                        <div class="cd-mini-input">
                            <span class="cd-mini-label">মোবাইল:</span> 01775075543
                        </div>
                        <div class="cd-mini-input cd-mini-input--area">
                            <span class="cd-mini-label">ঠিকানা:</span> ধানমন্ডি, ঢাকা
                        </div>
                        <div class="cd-mini-btn">
                            <i class="fas fa-check-circle me-1"></i> অর্ডার কনফার্ম করুন
                        </div>
                    </div>
                    <div class="cd-phone-footer">
                        <span class="cd-dot-green"></span> ওটিপি ভেরিফিকেশন সক্রিয়
                    </div>
                </div>
            </div>

            <!-- CENTERPIECE: APPLE MACBOOK / STUDIO DISPLAY -->
            <div class="cd-device-laptop">
                <div class="cd-laptop-lid">
                    <div class="cd-laptop-camera"></div>
                    <div class="cd-laptop-screen">
                        <!-- Browser Header Bar -->
                        <div class="cd-screen-bar">
                            <div class="cd-screen-dots">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="cd-screen-url" id="cdScreenUrl">
                                <a href="https://vantor.juktitech.com/" target="_blank" rel="noopener" class="cd-url-link" id="cdUrlLink">
                                    <i class="fas fa-lock text-success me-1"></i> <span class="cd-url-text">vantor.juktitech.com</span>
                                    <i class="fas fa-arrow-up-right-from-square ms-1 cd-url-ext" style="font-size:0.65rem; opacity:0.7;"></i>
                                </a>
                            </div>
                            <div class="cd-screen-actions">
                                <a href="https://vantor.juktitech.com/" target="_blank" rel="noopener" class="cd-status-chip">
                                    <span class="cd-live-pulse-dot"></span> লাইভ ↗
                                </a>
                            </div>
                        </div>

                        <!-- DYNAMIC SCREEN CANVAS (Switches with Tabs) -->
                        <div class="cd-screen-canvas" id="cdScreenCanvas">

                            <!-- TAB 1: E-COMMERCE PRO SCREEN (LIVE EMBEDDED WEBSITE) -->
                            <div class="cd-canvas-view cd-canvas-view--iframe is-active" id="view-ecom">
                                <div class="cd-iframe-wrap">
                                    <iframe src="https://vantor.juktitech.com/" 
                                            class="cd-laptop-iframe" 
                                            title="Vantor eCommerce Pro Live Demo"
                                            loading="eager"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                    <div class="cd-iframe-hint">
                                        <i class="fas fa-hand-pointer me-1"></i> সরাসরি স্ক্রল করে ওয়েবসাইট ব্রাউজ করুন
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: NEWS & EPAPER SCREEN -->
                            <div class="cd-canvas-view" id="view-news">
                                <div class="cd-news-marquee">
                                    <span class="cd-news-tag">ব্রেকিং নিউজ</span>
                                    <span class="cd-news-ticker">লারাভেল ও রিঅ্যাক্ট প্রযুক্তিতে তৈরি দ্রুতগতির বাংলা সংবাদপত্র ও অনলাইন নিউজ পোর্টাল...</span>
                                </div>
                                <div class="cd-news-layout">
                                    <div class="cd-news-lead">
                                        <div class="cd-lead-thumb"></div>
                                        <div class="cd-lead-caption">
                                            <h5>আধুনিক বিজ্ঞাপন ম্যানেজমেন্ট ও এসইও ফ্রেন্ডলি নিউজ সিস্টেম</h5>
                                            <small>গুগল অ্যাডসেন্স রেডি • আনলিমিটেড ক্যাটাগরি ও ই-পেপার ভিউয়ার</small>
                                        </div>
                                    </div>
                                    <div class="cd-news-sidebar">
                                        <div class="cd-news-mini-item">
                                            <span class="badge-cat">রাজনীতি</span>
                                            <p>অনলাইন পাঠকদের জন্য ডার্ক মোড ও ফন্ট সাইজ কন্ট্রোল...</p>
                                        </div>
                                        <div class="cd-news-mini-item">
                                            <span class="badge-cat">আন্তর্জাতিক</span>
                                            <p>মোবাইল অপ্টিমাইজড এএমপি ও সোশ্যাল অটো শেয়ারিং...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="cd-ecom-footer">
                                    <span><i class="fas fa-newspaper text-danger"></i> ই-পেপার ইন্টিগ্রেশন</span>
                                    <span><i class="fas fa-ad text-primary"></i> অ্যাড স্পট কন্ট্রোল</span>
                                    <span><i class="fas fa-chart-line text-success"></i> এসইও অপ্টিমাইজড</span>
                                </div>
                            </div>

                            <!-- TAB 3: FREE WEBSITE WITH HOSTING SCREEN -->
                            <div class="cd-canvas-view" id="view-hosting">
                                <div class="cd-hosting-offer-box">
                                    <span class="cd-host-pill">এক্সক্লুসিভ পার্টনারশিপ</span>
                                    <h3>হোস্টিং কিনলেই ওয়েবসাইট সম্পূর্ণ ফ্রি!</h3>
                                    <p>আমাদের রেফারেল লিংকে Hostinger বা Hostnin থেকে ৫০% ছাড়ে হোস্টিং কিনুন—আমরা সাইট ফ্রি বানিয়ে দেব।</p>
                                    <div class="cd-host-coupons">
                                        <div class="cd-hcoupon">
                                            <span>Hostinger:</span> <code>JUKTI50</code>
                                        </div>
                                        <div class="cd-hcoupon">
                                            <span>Hostnin (BDIX):</span> <code>JUKTIBD</code>
                                        </div>
                                    </div>
                                    <div class="cd-steps-mini">
                                        <span>১. টেমপ্লেট পছন্দ</span> → 
                                        <span>২. হোস্টিং ক্রয়</span> → 
                                        <span>৩. ইনভয়েস সাবমিট</span> → 
                                        <strong class="text-success">৪. ২৪ ঘণ্টায় সাইট লাইভ!</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 4: CUSTOM SOFTWARE & ERP SCREEN -->
                            <div class="cd-canvas-view" id="view-software">
                                <div class="cd-erp-dashboard">
                                    <div class="cd-erp-header">
                                        <span>Jukti ERP Suite</span>
                                        <span class="cd-erp-chip">রিয়েল-টাইম ডাটা সিঙ্ক</span>
                                    </div>
                                    <div class="cd-erp-metrics">
                                        <div class="cd-erp-metric">
                                            <small>আজকের নেট বিক্রয়</small>
                                            <strong>৳ ২,৪৮,৯৫০</strong>
                                            <span class="text-success">+২২.৪% গ্রোথ</span>
                                        </div>
                                        <div class="cd-erp-metric">
                                            <small>ডেলিভারি সাকসেস</small>
                                            <strong>৯৮.২%</strong>
                                            <span class="text-info">স্টেডফাস্ট + পাঠাও</span>
                                        </div>
                                        <div class="cd-erp-metric">
                                            <small>অটোমেটেড ইনভয়েস</small>
                                            <strong>১,২৪০ টি</strong>
                                            <span class="text-warning">পিওএস প্রিন্ট রেডি</span>
                                        </div>
                                    </div>
                                    <div class="cd-erp-bars">
                                        <div class="cd-bar-col" style="height: 45%;"></div>
                                        <div class="cd-bar-col" style="height: 65%;"></div>
                                        <div class="cd-bar-col" style="height: 85%;"></div>
                                        <div class="cd-bar-col is-highest" style="height: 100%;"></div>
                                        <div class="cd-bar-col" style="height: 75%;"></div>
                                        <div class="cd-bar-col" style="height: 90%;"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="cd-laptop-base">
                    <div class="cd-laptop-notch"></div>
                </div>
            </div>

            <!-- RIGHT FLOATING IPHONE (Angle +12deg) -->
            <div class="cd-device-phone cd-device-phone--right">
                <div class="cd-phone-notch"></div>
                <div class="cd-phone-inner cd-phone-inner--dark">
                    <div class="cd-phone-header">
                        <span class="cd-pill-tag bg-orange">লাইভ অ্যানালিটিক্স</span>
                    </div>
                    <div class="cd-mini-stats">
                        <div class="cd-mstat-top">
                            <small>দৈনিক সেলস চার্ট</small>
                            <h3>৳ ১,৮৫,৪০০</h3>
                        </div>
                        <div class="cd-mini-chart">
                            <div class="cd-mbar" style="height: 35%;"></div>
                            <div class="cd-mbar" style="height: 60%;"></div>
                            <div class="cd-mbar" style="height: 45%;"></div>
                            <div class="cd-mbar is-active" style="height: 90%;"></div>
                            <div class="cd-mbar" style="height: 70%;"></div>
                        </div>
                        <div class="cd-mcourier-status">
                            <i class="fas fa-check-circle text-success me-1"></i> স্টেডফাস্ট এপিআই সিঙ্কড
                        </div>
                    </div>
                    <div class="cd-phone-footer">
                        <span class="cd-dot-orange"></span> রিয়েল-টাইম কনভার্সন ট্র্যাকিং
                    </div>
                </div>
            </div>

        </div>

        <!-- Mobile Clean Trust Pill Strip (Visible on mobile only, below laptop) -->
        <div class="cd-mobile-trust-strip">
            <div class="cd-mtrust-pill">
                <i class="fas fa-bolt text-warning"></i>
                <span><strong>99/100</strong> স্পিড</span>
            </div>
            <div class="cd-mtrust-pill">
                <i class="fas fa-gift text-success"></i>
                <span><strong>ফ্রি সাইট</strong> ডিল</span>
            </div>
            <div class="cd-mtrust-pill">
                <i class="fas fa-shield-alt text-danger"></i>
                <span><strong>০% ফেক</strong> রোধ</span>
            </div>
            <div class="cd-mtrust-pill">
                <i class="fas fa-truck-fast text-info"></i>
                <span><strong>অটো কুরিয়ার</strong> API</span>
            </div>
        </div>
    </div>
</section>

<style>
/* ==========================================================================
   JUKTI TECH — APPLE STUDIO MULTI-DEVICE HERO SHOWCASE
   ========================================================================== */

.cd-hero {
    position: relative;
    padding: 130px 0 80px;
    background: #fafbfc;
    overflow: hidden;
    font-family: 'Hind Siliguri', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* 1. Background Grid & Ambient Glow */
.cd-hero__grid-bg {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(to right, rgba(0, 0, 0, 0.04) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 35%, #000 60%, transparent 100%);
    -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 35%, #000 60%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}

.cd-hero__glow-bg {
    position: absolute;
    top: -120px;
    left: 50%;
    transform: translateX(-50%);
    width: 900px;
    height: 520px;
    background: radial-gradient(circle, rgba(255, 87, 34, 0.12) 0%, rgba(255, 112, 67, 0.05) 45%, transparent 75%);
    pointer-events: none;
    z-index: 0;
    filter: blur(50px);
}

.cd-hero__content-wrap {
    z-index: 1;
}

/* 2. Top Pill Status Badge */
.cd-hero__badge-wrap {
    margin-bottom: 22px;
}

.cd-hero__pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 8px 22px;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 87, 34, 0.22);
    border-radius: 999px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #1e293b;
    box-shadow: 0 4px 18px rgba(255, 87, 34, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    letter-spacing: -0.01em;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.cd-hero__pill-badge:hover {
    border-color: rgba(255, 87, 34, 0.45);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255, 87, 34, 0.15);
}

.cd-badge-arrow {
    color: #ff5722;
    font-weight: 700;
    transition: transform 0.2s ease;
}

.cd-hero__pill-badge:hover .cd-badge-arrow {
    transform: translateX(3px);
}

.cd-hero__pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #22c55e;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
    animation: cdPulseDot 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
}

@keyframes cdPulseDot {
    0% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}

/* 3. Headline & Subtitle (Hierarchy Design with Inline Laravel Logo) */
.cd-hero__headline {
    font-size: clamp(2.4rem, 5.2vw, 4.2rem);
    font-weight: 800;
    line-height: 1.25;
    color: #0f172a;
    margin-bottom: 16px;
    letter-spacing: -0.03em;
}

.cd-text-orange {
    color: #ff5722;
    font-weight: 800;
    display: inline-block;
}

.cd-text-dark {
    color: #0f172a;
    font-weight: 800;
    display: inline-block;
}

.cd-inline-laravel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    vertical-align: middle;
    width: 44px;
    height: 44px;
    margin: 0 4px;
}

.cd-laravel-cube {
    width: 40px;
    height: 40px;
    filter: drop-shadow(0 3px 6px rgba(255, 87, 34, 0.22));
    animation: cdFloatCube 3s ease-in-out infinite alternate;
}

@keyframes cdFloatCube {
    0% { transform: translateY(0px); }
    100% { transform: translateY(-4px); }
}

.cd-hero__accent {
    color: #ff5722;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.cd-hero__subtext {
    max-width: 660px;
    margin: 0 auto 26px;
    font-size: clamp(1rem, 1.25vw, 1.15rem);
    line-height: 1.65;
    color: #475569;
    font-weight: 500;
}

/* 4. CTA Action Buttons (Image 3 Style: Pill with Right Inset Icon Container) */
.cd-hero__cta-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.cd-hero__btn {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    border-radius: 999px;
    text-decoration: none;
    transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    padding: 6px 7px 6px 26px;
}

.cd-btn-label {
    font-size: 1.02rem;
    font-weight: 700;
    letter-spacing: -0.01em;
}

.cd-btn-box {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    transition: all 0.25s ease;
    flex-shrink: 0;
}

.cd-hero__btn--primary {
    background: #ff5722;
    color: #ffffff !important;
    box-shadow: 0 8px 24px rgba(255, 87, 34, 0.32);
    border: none;
}

.cd-hero__btn--primary .cd-btn-box {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}

.cd-hero__btn--primary:hover {
    background: #ea580c;
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(255, 87, 34, 0.46);
}

.cd-hero__btn--primary:hover .cd-btn-box {
    background: rgba(255, 255, 255, 0.35);
    transform: translateX(3px);
}

.cd-hero__btn--secondary {
    background: #ffffff;
    color: #0f172a !important;
    border: 1px solid rgba(15, 23, 42, 0.12);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}

.cd-hero__btn--secondary .cd-btn-box {
    background: #f1f5f9;
    color: #475569;
}

.cd-hero__btn--secondary:hover {
    border-color: #ff5722;
    color: #ff5722 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.12);
}

.cd-hero__btn--secondary:hover .cd-btn-box {
    background: #fff5f2;
    color: #ff5722;
    transform: translateX(3px);
}

/* 5. Interactive Template Switcher Bar */
.cd-switcher-wrap {
    margin-bottom: 36px;
}

.cd-switcher-bar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 999px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    max-width: 100%;
    overflow-x: auto;
    scrollbar-width: none;
}

.cd-switcher-bar::-webkit-scrollbar {
    display: none;
}

.cd-switcher-tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    background: transparent;
    border: none;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.22s ease;
    white-space: nowrap;
}

.cd-switcher-tab:hover {
    color: #111827;
    background: rgba(0, 0, 0, 0.03);
}

.cd-switcher-tab.is-active {
    background: #111827;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(17, 24, 39, 0.2);
}

.cd-tab-icon {
    font-size: 1rem;
}

/* 6. Multi-Device Stage Container */
.cd-multi-stage {
    position: relative;
    max-width: 1400px;
    margin: 0 auto;
    padding: 55px 70px 75px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cd-stage-glow {
    position: absolute;
    width: 960px;
    height: 520px;
    background: radial-gradient(ellipse at center, rgba(255, 87, 34, 0.22) 0%, rgba(255, 112, 67, 0.08) 55%, transparent 75%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -45%);
    filter: blur(80px);
    pointer-events: none;
    z-index: 1;
}

/* 7. Floating Frosted Trust Badges (Positioned Completely Outside Laptop) */
.cd-float-badge {
    position: absolute;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 14px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.09);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    z-index: 12;
    transition: all 0.3s ease;
    pointer-events: auto;
}

.cd-float-badge:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 14px 32px rgba(0, 0, 0, 0.13);
}

.cd-float-badge--top-left {
    top: 0px;
    left: -15px;
    animation: cdFloatA 4s ease-in-out infinite alternate;
}

.cd-float-badge--top-right {
    top: 0px;
    right: -15px;
    animation: cdFloatB 4.5s ease-in-out infinite alternate;
}

.cd-float-badge--bottom-left {
    bottom: 12px;
    left: -20px;
    animation: cdFloatB 4.2s ease-in-out infinite alternate;
}

.cd-float-badge--bottom-right {
    bottom: 12px;
    right: -20px;
    animation: cdFloatA 4.8s ease-in-out infinite alternate;
}

@keyframes cdFloatA {
    0% { transform: translateY(0px); }
    100% { transform: translateY(-8px); }
}

@keyframes cdFloatB {
    0% { transform: translateY(-6px); }
    100% { transform: translateY(2px); }
}

.cd-fbadge-icon {
    font-size: 1.25rem;
}

.cd-fbadge-text strong {
    display: block;
    font-size: 0.88rem;
    color: #111827;
    line-height: 1.2;
}

.cd-fbadge-text small {
    display: block;
    font-size: 0.74rem;
    color: #6b7280;
}

/* 8. Central Apple MacBook Display (Bigger, Prominent & 100% Visible) */
.cd-device-laptop {
    position: relative;
    z-index: 8;
    width: 1060px;
    max-width: 90%;
    margin: 0 auto;
    filter: drop-shadow(0 30px 65px rgba(0, 0, 0, 0.18));
}

.cd-laptop-lid {
    background: #0f172a;
    border-radius: 18px 18px 0 0;
    padding: 10px 10px 0;
    box-shadow: inset 0 0 0 1.5px #334155;
    position: relative;
}

.cd-laptop-camera {
    width: 6px;
    height: 6px;
    background: #334155;
    border-radius: 50%;
    margin: 0 auto 8px;
}

.cd-laptop-screen {
    background: #ffffff;
    border-radius: 10px 10px 0 0;
    overflow: hidden;
    height: 570px;
    position: relative;
    display: flex;
    flex-direction: column;
}

.cd-screen-bar {
    height: 40px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.cd-screen-dots {
    display: flex;
    gap: 6px;
}

.cd-screen-dots span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.cd-screen-dots span:nth-child(1) { background: #ef4444; }
.cd-screen-dots span:nth-child(2) { background: #f59e0b; }
.cd-screen-dots span:nth-child(3) { background: #10b981; }

.cd-screen-url {
    background: #ffffff;
    padding: 4px 18px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.78rem;
    color: #475569;
    border: 1px solid #e2e8f0;
    max-width: 380px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
}

.cd-url-link {
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: color 0.2s;
    max-width: 100%;
}

.cd-url-link:hover {
    color: #ff5722;
    text-decoration: underline;
}

.cd-url-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cd-mobile-trust-strip {
    display: none;
}

.cd-status-chip {
    font-size: 0.72rem;
    font-weight: 600;
    color: #15803d;
    background: #dcfce7;
    padding: 3px 10px;
    border-radius: 999px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    border: 1px solid #bbf7d0;
    transition: all 0.2s;
}

.cd-status-chip:hover {
    background: #bbf7d0;
    color: #14532d;
    transform: translateY(-1px);
}

.cd-live-pulse-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    margin-right: 5px;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulseDot 2s infinite;
}

@keyframes pulseDot {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

.cd-laptop-base {
    height: 18px;
    width: calc(100% + 40px);
    margin-left: -20px;
    background: linear-gradient(180deg, #e2e8f0 0%, #cbd5e1 100%);
    border-radius: 0 0 24px 24px;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    position: relative;
}

.cd-laptop-notch {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 110px;
    height: 5px;
    background: #94a3b8;
    border-radius: 0 0 6px 6px;
}

/* 9. Canvas Views */
.cd-screen-canvas {
    flex: 1;
    position: relative;
    overflow: hidden;
    background: #f8fafc;
}

.cd-canvas-view {
    position: absolute;
    inset: 0;
    padding: 16px;
    display: none;
    opacity: 0;
    transform: scale(0.98);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.cd-canvas-view.is-active {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    opacity: 1;
    transform: scale(1);
}

/* Live Iframe Embed Container */
.cd-canvas-view--iframe {
    padding: 0 !important;
    overflow: hidden;
}

.cd-canvas-view--iframe.is-active {
    display: block !important;
}

.cd-iframe-wrap {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #ffffff;
}

.cd-laptop-iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
    background: #ffffff;
}

.cd-iframe-hint {
    position: absolute;
    bottom: 12px;
    right: 14px;
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 500;
    backdrop-filter: blur(6px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    pointer-events: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    z-index: 10;
}

/* E-Commerce Screen UI */
.cd-ecom-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.cd-brand-title {
    font-weight: 800;
    color: #111827;
    font-size: 1rem;
}

.cd-ecom-tags {
    display: flex;
    gap: 6px;
}

.badge-pill {
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
}

.badge-pill.bg-orange {
    background: #ffedd5;
    color: #ea580c;
}

.badge-pill.bg-dark {
    background: #1e293b;
    color: #ffffff;
}

.cd-ecom-banner {
    background: linear-gradient(135deg, #ff5722 0%, #ea580c 100%);
    border-radius: 10px;
    padding: 14px 18px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.2);
}

.cd-banner-text h4 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
}

.cd-banner-text p {
    margin: 2px 0 0;
    font-size: 0.8rem;
    opacity: 0.9;
}

.cd-banner-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 14px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 1.1rem;
    backdrop-filter: blur(8px);
}

.cd-ecom-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}

.cd-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    gap: 10px;
    align-items: center;
}

.cd-item-thumb {
    width: 44px;
    height: 44px;
    border-radius: 6px;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}

.cd-item-thumb--1 { background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); }
.cd-item-thumb--2 { background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%); }
.cd-item-thumb--3 { background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%); }

.cd-item-info strong {
    display: block;
    font-size: 0.82rem;
    color: #1e293b;
}

.cd-item-info span {
    font-size: 0.78rem;
    font-weight: 700;
}

.cd-ecom-footer {
    display: flex;
    justify-content: space-around;
    padding: 8px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
}

/* News View */
.cd-news-marquee {
    background: #fee2e2;
    border: 1px solid #fecaca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.78rem;
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 12px;
}

.cd-news-tag {
    background: #dc2626;
    color: #ffffff;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 0.7rem;
}

.cd-news-layout {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 12px;
    margin-bottom: 10px;
}

.cd-news-lead {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.cd-lead-thumb {
    height: 120px;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
}

.cd-lead-caption {
    padding: 10px;
}

.cd-lead-caption h5 {
    font-size: 0.92rem;
    font-weight: 700;
    margin: 0 0 4px;
    color: #0f172a;
}

.cd-lead-caption small {
    font-size: 0.72rem;
    color: #64748b;
}

.cd-news-sidebar {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cd-news-mini-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px;
}

.badge-cat {
    font-size: 0.68rem;
    color: #2563eb;
    font-weight: 700;
}

.cd-news-mini-item p {
    margin: 3px 0 0;
    font-size: 0.74rem;
    color: #334155;
    line-height: 1.35;
}

/* Hosting View */
.cd-hosting-offer-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    border-radius: 12px;
    padding: 24px;
    color: #ffffff;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.cd-host-pill {
    background: #f97316;
    color: #ffffff;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 3px 12px;
    border-radius: 999px;
    margin-bottom: 10px;
}

.cd-hosting-offer-box h3 {
    font-size: 1.35rem;
    font-weight: 800;
    margin: 0 0 6px;
}

.cd-hosting-offer-box p {
    font-size: 0.84rem;
    color: #94a3b8;
    max-width: 480px;
    margin: 0 auto 16px;
}

.cd-host-coupons {
    display: flex;
    gap: 14px;
    margin-bottom: 16px;
}

.cd-hcoupon {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.8rem;
}

.cd-hcoupon code {
    color: #facc15;
    font-weight: 700;
    margin-left: 4px;
}

.cd-steps-mini {
    font-size: 0.78rem;
    color: #cbd5e1;
}

/* ERP View */
.cd-erp-dashboard {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.cd-erp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    color: #0f172a;
}

.cd-erp-chip {
    font-size: 0.72rem;
    background: #ecfdf5;
    color: #059669;
    padding: 3px 10px;
    border-radius: 999px;
}

.cd-erp-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin: 10px 0;
}

.cd-erp-metric {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px;
}

.cd-erp-metric small {
    display: block;
    font-size: 0.72rem;
    color: #64748b;
}

.cd-erp-metric strong {
    display: block;
    font-size: 1.05rem;
    color: #0f172a;
    margin: 2px 0;
}

.cd-erp-metric span {
    font-size: 0.7rem;
    font-weight: 700;
}

.cd-erp-bars {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    height: 110px;
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    padding: 12px 20px;
    gap: 12px;
}

.cd-bar-col {
    width: 24px;
    background: #e2e8f0;
    border-radius: 4px 4px 0 0;
    transition: all 0.3s ease;
}

.cd-bar-col.is-highest {
    background: linear-gradient(to top, #ff5722, #f97316);
}

/* 10. Flanking 3D Angled iPhones (Positioned smoothly behind laptop) */
.cd-device-phone {
    position: absolute;
    width: 190px;
    height: 370px;
    background: #0f172a;
    border-radius: 28px;
    padding: 8px;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.16);
    z-index: 4; /* Behind laptop screen so laptop is 100% visible */
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    top: 50%;
    transform-origin: center;
}

.cd-phone-notch {
    width: 50px;
    height: 10px;
    background: #1e293b;
    border-radius: 0 0 8px 8px;
    margin: 0 auto 6px;
}

.cd-phone-inner {
    height: calc(100% - 16px);
    border-radius: 20px;
    padding: 12px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.cd-phone-inner--cyan {
    background: linear-gradient(180deg, #ecfeff 0%, #cffafe 100%);
    border: 1px solid #a5f3fc;
}

.cd-phone-inner--dark {
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    color: #ffffff;
    border: 1px solid #334155;
}

.cd-device-phone--left {
    left: -35px;
    transform: translateY(-50%) rotate(-12deg);
}

.cd-device-phone--left:hover {
    transform: translateY(-54%) rotate(-4deg) scale(1.05);
    z-index: 10;
}

.cd-device-phone--right {
    right: -35px;
    transform: translateY(-50%) rotate(12deg);
}

.cd-device-phone--right:hover {
    transform: translateY(-54%) rotate(4deg) scale(1.05);
    z-index: 10;
}

/* Phone UI Elements */
.cd-pill-tag {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    display: inline-block;
}

.cd-pill-tag.bg-cyan {
    background: #06b6d4;
    color: #ffffff;
}

.cd-pill-tag.bg-orange {
    background: #f97316;
    color: #ffffff;
}

.cd-mini-form {
    background: #ffffff;
    border-radius: 10px;
    padding: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.cd-mini-title {
    font-size: 0.74rem;
    font-weight: 700;
    color: #0891b2;
    margin-bottom: 6px;
}

.cd-mini-input {
    font-size: 0.68rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 4px 6px;
    margin-bottom: 4px;
    color: #334155;
}

.cd-mini-label {
    font-weight: 700;
    color: #64748b;
}

.cd-mini-btn {
    background: #0891b2;
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 700;
    text-align: center;
    padding: 5px;
    border-radius: 5px;
    margin-top: 6px;
}

.cd-phone-footer {
    font-size: 0.65rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 5px;
}

.cd-dot-green {
    width: 6px;
    height: 6px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    margin-right: 4px;
}

.cd-dot-orange {
    width: 6px;
    height: 6px;
    background: #f97316;
    border-radius: 50%;
    display: inline-block;
    margin-right: 4px;
}

.cd-mstat-top small {
    font-size: 0.7rem;
    color: #94a3b8;
}

.cd-mstat-top h3 {
    font-size: 1.15rem;
    color: #ffffff;
    font-weight: 800;
    margin: 2px 0 10px;
}

.cd-mini-chart {
    height: 70px;
    display: flex;
    align-items: flex-end;
    gap: 8px;
    padding: 6px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    margin-bottom: 10px;
}

.cd-mbar {
    flex: 1;
    background: #334155;
    border-radius: 3px 3px 0 0;
}

.cd-mbar.is-active {
    background: #f97316;
}

.cd-mcourier-status {
    font-size: 0.68rem;
    color: #cbd5e1;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .cd-multi-stage {
        padding: 45px 30px 60px;
    }
    .cd-device-laptop {
        width: 100%;
        max-width: 94%;
    }
    .cd-laptop-screen {
        height: 500px;
    }
    .cd-float-badge--top-left {
        top: 6px;
        left: 5px;
    }
    .cd-float-badge--top-right {
        top: 6px;
        right: 5px;
    }
    .cd-float-badge--bottom-left {
        bottom: 10px;
        left: 5px;
    }
    .cd-float-badge--bottom-right {
        bottom: 10px;
        right: 5px;
    }
}

@media (max-width: 991px) {
    .cd-device-phone {
        display: none;
    }
    .cd-multi-stage {
        padding: 35px 15px 40px;
    }
    .cd-float-badge--top-left {
        left: 10px;
        top: 0px;
    }
    .cd-float-badge--top-right {
        right: 10px;
        top: 0px;
    }
    .cd-float-badge--bottom-left,
    .cd-float-badge--bottom-right {
        display: none;
    }
    .cd-laptop-screen {
        height: 380px;
    }
}

@media (max-width: 767px) {
    .cd-hero {
        padding-top: 90px;
        padding-bottom: 35px;
    }
    .cd-hero__headline {
        font-size: 1.85rem;
        line-height: 1.25;
        margin-bottom: 12px;
    }
    .cd-inline-laravel {
        width: 32px;
        height: 32px;
        margin: 0 3px;
    }
    .cd-laravel-cube {
        width: 28px;
        height: 28px;
    }
    .cd-hero__subtext {
        font-size: 0.88rem;
        line-height: 1.5;
        margin-bottom: 20px;
        padding: 0 10px;
    }
    .cd-hero__cta-group {
        flex-direction: column;
        gap: 10px;
        padding: 0 16px;
        margin-bottom: 22px;
    }
    .cd-hero__btn {
        width: 100%;
        padding: 6px 8px 6px 20px;
    }
    .cd-switcher-wrap {
        margin-bottom: 16px;
        padding: 0 8px;
    }
    .cd-switcher-bar {
        display: flex;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        gap: 5px;
        padding: 4px;
        border-radius: 999px;
    }
    .cd-switcher-tab {
        padding: 6px 13px;
        font-size: 0.8rem;
        flex-shrink: 0;
        white-space: nowrap;
    }
    
    /* Stage & Background */
    .cd-multi-stage {
        padding: 12px 6px 20px;
        width: 100%;
        overflow: visible;
    }
    .cd-stage-glow {
        width: 300px;
        height: 200px;
        filter: blur(40px);
    }
    .cd-float-badge {
        display: none !important;
    }
    .cd-device-phone {
        display: none !important;
    }

    /* Laptop Frame - Authentic 16:10 MacBook Pro on Mobile */
    .cd-device-laptop {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        filter: drop-shadow(0 16px 36px rgba(0, 0, 0, 0.16));
    }
    .cd-laptop-lid {
        border-radius: 14px 14px 0 0;
        padding: 6px 6px 0;
        box-shadow: inset 0 0 0 1px #334155;
    }
    .cd-laptop-camera {
        width: 4px;
        height: 4px;
        margin: 0 auto 5px;
    }
    .cd-laptop-screen {
        height: 250px;
        border-radius: 8px 8px 0 0;
    }
    .cd-screen-bar {
        height: 32px;
        padding: 0 8px;
    }
    .cd-screen-dots {
        gap: 3px;
    }
    .cd-screen-dots span {
        width: 6px;
        height: 6px;
    }
    .cd-screen-url {
        min-width: 0;
        flex: 1;
        margin: 0 6px;
        padding: 2px 8px;
        font-size: 0.68rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-radius: 4px;
    }
    .cd-url-link {
        font-size: 0.68rem;
    }
    .cd-status-chip {
        font-size: 0.62rem;
        padding: 2px 6px;
        flex-shrink: 0;
    }
    .cd-live-pulse-dot {
        width: 5px;
        height: 5px;
        margin-right: 3px;
    }
    
    /* Laptop Base - Sleek, slim chin */
    .cd-laptop-base {
        height: 10px;
        width: calc(100% + 14px);
        margin-left: -7px;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }
    .cd-laptop-notch {
        width: 50px;
        height: 3px;
        border-radius: 0 0 4px 4px;
    }

    /* Floating Hint Badge - Centered & Auto-fades out after 5s */
    .cd-iframe-hint {
        bottom: 8px;
        right: auto;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.65rem;
        padding: 3px 10px;
        white-space: nowrap;
        background: rgba(15, 23, 42, 0.88);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        animation: cdHintMobileFade 6s ease forwards;
    }

    /* Mobile Trust Strip */
    .cd-mobile-trust-strip {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-top: 14px;
        padding: 0 8px;
    }
    .cd-mtrust-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.7rem;
        color: #334155;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    .cd-mtrust-pill strong {
        color: #0f172a;
    }

    .cd-ecom-cards {
        grid-template-columns: 1fr;
    }
    .cd-item-card:nth-child(n+2) {
        display: none;
    }
    .cd-news-layout {
        grid-template-columns: 1fr;
    }
    .cd-news-sidebar {
        display: none;
    }
    .cd-erp-metrics {
        grid-template-columns: 1fr;
    }
    .cd-erp-metric:nth-child(n+2) {
        display: none;
    }
}

@keyframes cdHintMobileFade {
    0% { opacity: 0; transform: translate(-50%, 6px); }
    15% { opacity: 1; transform: translate(-50%, 0); }
    80% { opacity: 1; transform: translate(-50%, 0); }
    100% { opacity: 0; transform: translate(-50%, -4px); pointer-events: none; }
}

@media (max-width: 480px) {
    .cd-hero__headline {
        font-size: 1.55rem;
    }
    .cd-device-laptop {
        max-width: 100%;
    }
    .cd-laptop-screen {
        height: 230px;
    }
    .cd-screen-url {
        font-size: 0.64rem;
        padding: 1px 6px;
    }
}
</style>

<script>
function switchHeroTab(tabKey) {
    // 1. Update tab button active state
    document.querySelectorAll('.cd-switcher-tab').forEach(btn => {
        btn.classList.toggle('is-active', btn.dataset.tab === tabKey);
    });

    // 2. Switch canvas view
    document.querySelectorAll('.cd-canvas-view').forEach(view => {
        view.classList.remove('is-active');
    });
    const targetView = document.getElementById('view-' + tabKey);
    if (targetView) {
        targetView.classList.add('is-active');
    }

    // 3. Update simulated browser URL
    const urlMap = {
        'ecom': {
            display: 'vantor.juktitech.com',
            href: 'https://vantor.juktitech.com/'
        },
        'news': {
            display: 'news.juktitech.com',
            href: null
        },
        'hosting': {
            display: 'hostinger.juktitech.com/free-deal',
            href: null
        },
        'software': {
            display: 'erp.juktitech.com',
            href: null
        }
    };
    const urlEl = document.getElementById('cdScreenUrl');
    if (urlEl && urlMap[tabKey]) {
        const item = urlMap[tabKey];
        if (item.href) {
            urlEl.innerHTML = `<a href="${item.href}" target="_blank" rel="noopener" class="cd-url-link"><i class="fas fa-lock text-success me-1"></i> <span class="cd-url-text">${item.display}</span> <i class="fas fa-arrow-up-right-from-square ms-1 cd-url-ext" style="font-size:0.65rem; opacity:0.7;"></i></a>`;
        } else {
            urlEl.innerHTML = `<i class="fas fa-lock text-success me-1"></i> <span class="cd-url-text">${item.display}</span>`;
        }
    }
}
</script>
