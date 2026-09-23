<header class="cd-floating-navbar-wrap">
    <div class="cd-nav-island">
        <!-- Brand Logo -->
        <a href="{{ route('frontend.index') }}" class="cd-nav-brand" title="{{ $gs->title ?? 'Jukti Tech' }}">
            @if(!empty($gs->logo))
                <img src="{{ asset_image('assets/images/logo/' . $gs->logo) }}" 
                     alt="{{ $gs->title ?? 'Jukti Tech' }}" 
                     class="cd-nav-logo-img"
                     onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline-flex';">
                <span class="cd-nav-logo-fallback" style="display:none;">
                    <span class="cd-nav-logo-box"><i class="fas fa-bolt"></i></span>
                    <span class="cd-nav-brand-text">{{ $gs->title ?? 'Jukti Tech' }}</span>
                </span>
            @elseif(!empty($gs->favicon))
                <img src="{{ asset_image('assets/images/' . $gs->favicon) }}" 
                     alt="{{ $gs->title ?? 'Jukti Tech' }}" 
                     class="cd-nav-logo-img">
            @else
                <span class="cd-nav-logo-box"><i class="fas fa-bolt"></i></span>
                <span class="cd-nav-brand-text">{{ $gs->title ?? 'Jukti Tech' }}</span>
            @endif
        </a>

        <!-- Desktop Menu Links -->
        <nav class="cd-nav-menu-desktop">
            <ul class="cd-nav-links">
                <li><a href="{{ route('frontend.index') }}" class="is-active">হোম</a></li>
                <li><a href="#all-websites">ওয়েবসাইট কালেকশন</a></li>
                <li><a href="#features">ফিচারসমূহ</a></li>
                <li class="cd-has-dropdown">
                    <a href="javascript:void(0)" class="cd-dropdown-trigger">
                        অন্যান্য <i class="fas fa-chevron-down cd-drop-arrow"></i>
                    </a>
                    <div class="cd-nav-dropdown-menu">
                        <a href="{{ URL::to('/themes') }}"><i class="fas fa-palette me-2 text-warning"></i> সকল থিম ও স্ক্রিপ্ট</a>
                        <a href="{{ URL::to('/service') }}"><i class="fas fa-laptop-code me-2 text-info"></i> কাস্টম ওয়েব ডিজাইন</a>
                        <a href="{{ URL::to('/domain-search') }}"><i class="fas fa-globe me-2 text-primary"></i> ডোমেন সার্চ</a>
                        <a href="{{ URL::to('/contact') }}"><i class="fas fa-envelope me-2 text-success"></i> সরাসরি যোগাযোগ</a>
                        @auth
                            <div class="cd-drop-divider"></div>
                            <a href="{{ route('user.dashboard') }}"><i class="fas fa-user-circle me-2 text-danger"></i> ড্যাশবোর্ড</a>
                        @else
                            <div class="cd-drop-divider"></div>
                            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2 text-danger"></i> ক্লায়েন্ট লগইন</a>
                        @endauth
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Right Action Button (Apple Style Rounded Pill with Diagonal Arrow) -->
        <div class="cd-nav-right">
            @auth
                <div class="cd-nav-user-dropdown cd-has-dropdown">
                    <a href="javascript:void(0)" class="cd-nav-user-pill">
                        <i class="fas fa-user-circle text-orange"></i>
                        <span class="cd-user-name">{{ Str::limit(Auth::user()->name, 12) }}</span>
                        <i class="fas fa-chevron-down cd-drop-arrow ms-1" style="font-size: 9px;"></i>
                    </a>
                    <div class="cd-nav-dropdown-menu cd-user-menu">
                        <div class="cd-user-menu-header px-2 py-1 mb-1">
                            <strong class="d-block text-dark small">{{ Auth::user()->name }}</strong>
                            <small class="text-muted d-block" style="font-size: 11px;">{{ Auth::user()->phone ?? Auth::user()->email }}</small>
                        </div>
                        <div class="cd-drop-divider"></div>
                        <a href="{{ route('user.digital-downloads.index') }}">
                            <i class="fas fa-cloud-arrow-down me-2 text-primary"></i> আমার ডাউনলোড
                        </a>
                        <a href="{{ route('user.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2 text-warning"></i> ড্যাশবোর্ড
                        </a>
                        <div class="cd-drop-divider"></div>
                        <a href="{{ route('front.logout') }}" class="text-danger">
                            <i class="fas fa-sign-out-alt me-2 text-danger"></i> লগআউট
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="cd-nav-login-btn">
                    <i class="fas fa-user"></i>
                    <span>লগইন</span>
                </a>
            @endauth

            <a href="#walkthrough" class="cd-nav-action-btn" title="সরাসরি ডেমো দেখুন">
                <i class="fas fa-arrow-up-right-from-square"></i>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="cd-mobile-toggle" id="cdMobileToggle" aria-label="Toggle Navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div class="cd-mobile-drawer" id="cdMobileDrawer">
        <div class="cd-mobile-drawer__head">
            <a href="{{ route('frontend.index') }}" class="cd-drawer-brand" title="{{ $gs->title ?? 'Jukti Tech' }}">
                @if(!empty($gs->logo))
                    <img src="{{ asset_image('assets/images/logo/' . $gs->logo) }}" 
                         alt="{{ $gs->title ?? 'Jukti Tech' }}" 
                         class="cd-drawer-logo-img"
                         onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline-flex';">
                    <span class="cd-drawer-logo-fallback" style="display:none;">
                        <span class="cd-nav-logo-box"><i class="fas fa-bolt"></i></span>
                        <span class="cd-mobile-drawer__title">{{ $gs->title ?? 'Jukti Tech' }}</span>
                    </span>
                @elseif(!empty($gs->favicon))
                    <img src="{{ asset_image('assets/images/' . $gs->favicon) }}" 
                         alt="{{ $gs->title ?? 'Jukti Tech' }}" 
                         class="cd-drawer-logo-img">
                @else
                    <span class="cd-nav-logo-box"><i class="fas fa-bolt"></i></span>
                    <span class="cd-mobile-drawer__title">{{ $gs->title ?? 'Jukti Tech' }}</span>
                @endif
            </a>
            <button class="cd-mobile-close" id="cdMobileClose">&times;</button>
        </div>
        <ul class="cd-mobile-nav-links">
            <li><a href="{{ route('frontend.index') }}" class="is-active"><i class="fas fa-home me-2"></i> হোম</a></li>
            <li><a href="#all-websites"><i class="fas fa-laptop-code me-2"></i> ওয়েবসাইট কালেকশন</a></li>
            <li><a href="#features"><i class="fas fa-layer-group me-2"></i> ফিচারসমূহ</a></li>
            <li><a href="{{ URL::to('/themes') }}"><i class="fas fa-palette me-2"></i> রেডিমেড থিম ও স্ক্রিপ্ট</a></li>
            <li><a href="{{ URL::to('/service') }}"><i class="fas fa-code me-2"></i> কাস্টম ওয়েব ডেভেলপমেন্ট</a></li>
            <li><a href="{{ URL::to('/contact') }}"><i class="fas fa-envelope me-2"></i> যোগাযোগ</a></li>
        </ul>
        <div class="cd-mobile-drawer__footer">
            @auth
                <a href="{{ route('user.digital-downloads.index') }}" class="cd-drawer-btn cd-drawer-btn--primary mb-2">
                    <i class="fas fa-cloud-arrow-down me-1"></i> আমার ডাউনলোড
                </a>
                <a href="{{ route('user.dashboard') }}" class="cd-drawer-btn cd-drawer-btn--secondary">ড্যাশবোর্ড</a>
            @else
                <a href="{{ route('login') }}" class="cd-drawer-btn cd-drawer-btn--primary">
                    <i class="fas fa-user me-1"></i> ক্লায়েন্ট লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

<style>
/* ==========================================================================
   FLOATING ISLAND NAVBAR (APPLE-STYLE CLEAN FROSTED GLASS)
   ========================================================================== */

.cd-floating-navbar-wrap {
    position: fixed;
    top: 18px;
    left: 0;
    width: 100%;
    z-index: 1050;
    display: flex;
    justify-content: center;
    pointer-events: none;
    padding: 0 16px;
    font-family: 'Hind Siliguri', -apple-system, BlinkMacSystemFont, 'Inter', sans-serif;
}

.cd-nav-island {
    pointer-events: auto;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    background: rgba(255, 255, 255, 0.94);
    padding: 7px 10px 7px 18px;
    border-radius: 999px;
    box-shadow: 
        0 12px 36px -4px rgba(0, 0, 0, 0.10),
        0 0 0 1px rgba(0, 0, 0, 0.06);
    backdrop-filter: saturate(180%) blur(20px);
    -webkit-backdrop-filter: saturate(180%) blur(20px);
    width: auto;
    max-width: min(880px, calc(100vw - 32px));
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Brand Link and Logo Image */
.cd-nav-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none !important;
    flex-shrink: 0;
    transition: opacity 0.2s ease, transform 0.2s ease;
    padding: 0 4px 0 0;
}

.cd-nav-brand:hover {
    opacity: 0.88;
    transform: scale(1.02);
}

.cd-nav-logo-img {
    height: 42px;
    max-height: 46px;
    max-width: 165px;
    width: auto;
    object-fit: contain;
    display: block;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.04));
    transition: transform 0.2s ease;
}

.cd-nav-brand-text {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.2px;
}

.cd-nav-logo-fallback {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.cd-drawer-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none !important;
}

.cd-drawer-logo-img {
    height: 44px;
    max-height: 48px;
    max-width: 180px;
    width: auto;
    object-fit: contain;
    display: block;
}

.cd-drawer-logo-fallback {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Orange Star Brand Icon Box (Fallback) */
.cd-nav-logo-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
    color: #ffffff !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    text-decoration: none !important;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(255, 87, 34, 0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cd-nav-logo-box:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 14px rgba(255, 87, 34, 0.45);
}

/* Menu Links */
.cd-nav-menu-desktop {
    display: flex;
    align-items: center;
}

.cd-nav-links {
    display: flex;
    align-items: center;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.cd-nav-links li a {
    color: #4b5563;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    transition: color 0.18s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.cd-nav-links li a:hover,
.cd-nav-links li a.is-active {
    color: #111827;
}

.cd-nav-partner-link {
    position: relative;
}

.cd-partner-pill {
    background: #ff5722;
    color: #ffffff;
    font-size: 9.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 999px;
    letter-spacing: 0.02em;
}

/* Dropdown */
.cd-has-dropdown {
    position: relative;
}

.cd-drop-arrow {
    font-size: 10px;
    transition: transform 0.2s ease;
    color: #9ca3af;
}

.cd-nav-dropdown-menu {
    position: absolute;
    top: calc(100% + 14px);
    left: 50%;
    transform: translateX(-50%) translateY(8px);
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 8px;
    min-width: 220px;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.04);
    opacity: 0;
    visibility: hidden;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 100;
}

.cd-has-dropdown:hover .cd-nav-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.cd-has-dropdown:hover .cd-drop-arrow {
    transform: rotate(180deg);
}

.cd-nav-dropdown-menu a {
    display: flex;
    align-items: center;
    padding: 9px 12px;
    border-radius: 10px;
    color: #374151 !important;
    font-size: 13.5px !important;
    font-weight: 600;
    transition: background 0.15s ease, color 0.15s ease;
}

.cd-nav-dropdown-menu a:hover {
    background: #f3f4f6;
    color: #111827 !important;
}

.cd-drop-divider {
    height: 1px;
    background: #f3f4f6;
    margin: 6px 0;
}

/* Right Actions */
.cd-nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cd-nav-login-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 999px;
    background: #ffffff;
    border: 1.5px solid #ff5722;
    color: #ff5722 !important;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(255, 87, 34, 0.12);
}

.cd-nav-login-btn:hover {
    background: #ff5722;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.25);
}

.cd-nav-user-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #0f172a !important;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.2s ease;
}

.cd-nav-user-pill:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.cd-user-menu {
    right: 0 !important;
    left: auto !important;
    transform: translateY(8px) !important;
}

.cd-has-dropdown:hover .cd-user-menu {
    transform: translateY(0) !important;
}

.cd-nav-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #111827;
    color: #ffffff !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none !important;
    flex-shrink: 0;
    transition: transform 0.2s ease, background 0.2s ease;
}

.cd-nav-action-btn:hover {
    background: #ff5722;
    transform: scale(1.05);
}

/* Mobile Toggle */
.cd-mobile-toggle {
    display: none;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
    flex-direction: column;
    gap: 4px;
}

.cd-mobile-toggle span {
    display: block;
    width: 20px;
    height: 2px;
    background: #111827;
    border-radius: 2px;
    transition: all 0.3s ease;
}

/* Mobile Drawer */
.cd-mobile-drawer {
    position: fixed;
    top: 0;
    right: -100%;
    width: 82%;
    max-width: 340px;
    height: 100vh;
    background: #ffffff;
    z-index: 1100;
    box-shadow: -10px 0 35px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    padding: 24px;
    transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: auto;
}

.cd-mobile-drawer.is-open {
    right: 0;
}

.cd-mobile-drawer__head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f3f4f6;
    margin-bottom: 18px;
}

.cd-mobile-drawer__title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    flex-grow: 1;
}

.cd-mobile-close {
    background: #f3f4f6;
    border: none;
    color: #4b5563;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.cd-mobile-nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
    overflow-y: auto;
    flex-grow: 1;
}

.cd-mobile-nav-links li a {
    display: flex;
    align-items: center;
    padding: 12px 14px;
    border-radius: 12px;
    color: #374151;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none !important;
    transition: background 0.15s ease;
}

.cd-mobile-nav-links li a:hover,
.cd-mobile-nav-links li a.is-active {
    background: #f3f4f6;
    color: #ff5722;
}

.cd-mobile-drawer__footer {
    padding-top: 18px;
    border-top: 1px solid #f3f4f6;
}

.cd-drawer-btn--primary {
    display: block;
    width: 100%;
    text-align: center;
    padding: 12px;
    border-radius: 12px;
    background: #111827;
    color: #ffffff !important;
    font-weight: 700;
    text-decoration: none !important;
}

@media (max-width: 768px) {
    .cd-nav-menu-desktop {
        display: none;
    }
    .cd-mobile-toggle {
        display: flex;
    }
    .cd-nav-island {
        width: 100%;
        max-width: 94%;
        justify-content: space-between;
        padding: 6px 12px 6px 14px;
        gap: 12px;
    }
    .cd-nav-logo-img {
        height: 36px;
        max-height: 38px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('cdMobileToggle');
    const drawer = document.getElementById('cdMobileDrawer');
    const closeBtn = document.getElementById('cdMobileClose');

    if (toggle && drawer) {
        toggle.addEventListener('click', function() {
            drawer.classList.add('is-open');
        });
    }

    if (closeBtn && drawer) {
        closeBtn.addEventListener('click', function() {
            drawer.classList.remove('is-open');
        });
    }

    document.addEventListener('click', function(e) {
        if (drawer && drawer.classList.contains('is-open')) {
            if (!drawer.contains(e.target) && !toggle.contains(e.target)) {
                drawer.classList.remove('is-open');
            }
        }
    });
});
</script>
