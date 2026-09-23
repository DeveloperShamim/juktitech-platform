@extends('layouts.front')
@section('title', 'Buy Chatbot Package')
@section('contents')

{{-- Font Awesome & Google Fonts --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    body { font-family: 'Hind Siliguri', 'Inter', sans-serif; background-color: #f8fafc; }

    /* --- Sidebar styles --- */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; font-size: 15px; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #eff6ff; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    /* --- মেইন কন্টেন্ট স্টাইল --- */
    .main-content-card { 
        background: #fff; 
        border-radius: 24px; 
        padding: 40px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.03); 
        border: 1px solid #edf2f7; 
    }

    :root {
        --brand-orange: #fd5f00;
        --brand-bg-light: #fff5f0;
        --text-dark: #2d3436;
    }

    .pricing-card-wrapper { position: relative; height: 100%; }
    .pricing-card-modern {
        background: #fff;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #eee;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .pricing-card-modern.popular {
        border: 2px solid var(--brand-orange);
        transform: scale(1.05);
        box-shadow: 0 20px 50px rgba(253, 95, 0, 0.15);
        z-index: 10;
        position: relative;
    }

    /* অ্যাক্টিভ প্যাকেজ স্টাইল */
    .pricing-card-modern.active-pkg {
        border: 2px solid #27ae60;
        background-color: #f0fff4;
    }

    .best-value-badge {
        position: absolute; top: -15px; right: 20px;
        background: var(--brand-orange); color: #fff;
        padding: 5px 15px; border-radius: 5px;
        font-size: 12px; font-weight: 700;
        text-transform: uppercase;
    }

    .active-plan-badge {
        position: absolute; top: -15px; left: 20px;
        background: #27ae60; color: #fff;
        padding: 5px 15px; border-radius: 5px;
        font-size: 12px; font-weight: 700;
    }

    .plan-title { font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 25px; }
    .price-box { background-color: var(--brand-bg-light); padding: 25px 15px; border-radius: 15px; margin-bottom: 30px; }
    .price-text { color: var(--brand-orange); font-size: 42px; font-weight: 800; display: block; }
    .price-duration { color: #636e72; font-size: 14px; margin-top: 5px; display: block; }

    .feature-list-modern { list-style: none; padding: 0; margin: 0 0 30px 0; text-align: left; flex-grow: 1; }
    .feature-list-modern li { margin-bottom: 15px; color: #555; font-size: 15px; display: flex; align-items: center; }
    .feature-list-modern li i { color: #27ae60; margin-right: 12px; font-size: 18px; }

    .btn-pricing-modern { padding: 12px 30px; border-radius: 50px; font-weight: 600; font-size: 16px; text-decoration: none; transition: 0.3s; display: inline-block; width: 100%; text-align: center; }
    
    .btn-outline-custom { border: 2px solid #0984e3; color: #0984e3; background: transparent; }
    .btn-outline-custom:hover { background: #0984e3; color: #fff; }

    .btn-solid-custom { background: var(--brand-orange); color: #fff; border: 2px solid var(--brand-orange); }
    .btn-solid-custom:hover { background: #e65100; color: #fff; }

    .btn-active-custom { background: #27ae60; color: #fff; border: 2px solid #27ae60; cursor: default; }
    
    @media (max-width: 768px) {
        .pricing-card-modern.popular { transform: scale(1); margin: 20px 0; }
    }
    
    .submenu-container {
        background: #fdfdfd;
        border-radius: 12px;
        margin: 2px 15px;
    }
    
    .ps-5 {
        padding-left: 45px !important;
        font-size: 14px !important;
    }
    
    .menu-link i.fa-chevron-down {
        transition: transform 0.3s ease;
    }
    
    .menu-link.open i.fa-chevron-down {
        transform: rotate(180deg);
    }
</style>

<script>
    function toggleSubMenu(event, menuId) {
        event.preventDefault();
        const menu = document.getElementById(menuId);
        const link = event.currentTarget;
        
        if (menu.style.display === "none") {
            menu.style.display = "block";
            link.classList.add('open');
        } else {
            menu.style.display = "none";
            link.classList.remove('open');
        }
    }
</script>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- সাইডবার --}}
            <div class="col-lg-3">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <div class="user-avatar-wrapper">
                            <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar">
                        </div>
                        <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                        <span class="badge bg-light text-primary border mt-2">ক্লায়েন্ট</span>
                    </div>

                    <div class="py-3">
                        <a href="{{ route('user.dashboard') }}" class="menu-link">
                            <i class="fa-solid fa-table-columns"></i> ড্যাশবোর্ড
                        </a>
						
						<a href="{{ route('user.domains.index') }}" class="menu-link {{ request()->routeIs('user.domains.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-globe"></i> আমার ডোমেইন
                        </a>
                        
                        <a href="{{ route('user.hostings.index') }}" class="menu-link {{ request()->routeIs('user.hostings.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-server"></i> আমার হোস্টিং
                        </a>
                        
                        <a href="{{ route('user.chatbot.products.index') }}" class="menu-link {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-box"></i> প্রোডাক্ট
                        </a>
                        
                        {{-- ফ্রড সার্ভিস --}}{{-- এসএমএস সার্ভিস --}}
                        <div class="menu-item-group">
                            <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/sms*') || request()->is('user/recharge*') ? 'open' : '' }}" onclick="toggleSubMenu(event, 'smsSubMenu')">
                                <span><i class="fa-solid fa-comment-sms"></i> এসএমএস সার্ভিস</span>
                                <i class="fa-solid fa-chevron-down mt-1" style="font-size: 12px;"></i>
                            </a>
                            
                            <div id="smsSubMenu" class="submenu-container" style="{{ request()->is('user/sms*') || request()->is('user/recharge*') ? 'display: block;' : 'display: none;' }}">
                                <a href="{{ route('user.sms.panel') }}" class="menu-link ps-5 {{ request()->routeIs('user.sms.panel') ? 'active' : '' }}">
                                    <i class="fa-solid fa-paper-plane"></i> এসএমএস পাঠান
                                </a>
                                
                                <a href="{{ route('user.sms.history') }}" class="menu-link ps-5 {{ request()->routeIs('user.sms.history') ? 'active' : '' }}">
                                    <i class="fa-solid fa-history"></i> এসএমএস হিস্ট্রি
                                </a>
                                
                                <a href="{{ route('user.recharge.index') }}" class="menu-link ps-5 {{ request()->routeIs('user.recharge.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-wallet"></i> রিচার্জ করুন
                                </a>
                                
                                <a href="{{ route('user.sms.api_docs') }}" class="menu-link ps-5 {{ request()->routeIs('user.sms.api_docs') ? 'active' : '' }}">
                                    <i class="fa-solid fa-code"></i> এপিআই ডকুমেন্টেশন
                                </a>
                            </div>
                        </div>

                        {{-- Facebook Chatbot Menu --}}
                        <div class="menu-item-group">
                            <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/chatbot*') ? 'open' : '' }}" onclick="toggleSubMenu(event, 'chatbotSubMenu')">
                                <span><i class="fab fa-facebook-messenger"></i> চ্যাটবট সার্ভিস</span>
                                <i class="fa-solid fa-chevron-down mt-1" style="font-size: 12px;"></i>
                            </a>
                            
                            <div id="chatbotSubMenu" class="submenu-container" style="{{ request()->is('user/chatbot*') ? 'display: block;' : 'display: none;' }}">
                                <a href="{{ route('user.chatbot.settings') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.settings') ? 'active' : '' }}">
                                    <i class="fa-solid fa-gauge"></i> ড্যাশবোর্ড
                                </a>
                                
                                <a href="{{ route('user.chatbot.flows.index') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.flows.*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-diagram-project"></i> বট ফ্লও
                                </a>
                                
                                <a href="{{ route('user.chatbot.connect.page') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.connect.page') ? 'active' : '' }}">
                                    <i class="fa-solid fa-link"></i> পেজ কানেক্ট
                                </a>
                                
                                <a href="{{ route('user.chatbot.reply.setup') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.reply.setup') ? 'active' : '' }}">
                                    <i class="fa-solid fa-reply"></i> অটো রিপ্লাই
                                </a>
                                
                                <a href="{{ route('user.chatbot.comment.automation') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.comment.automation') ? 'active' : '' }}">
                                    <i class="fa-solid fa-comments"></i> কমেন্ট অটোমেশন
                                </a>
                                
                                <a href="{{ route('user.chatbot.products.index') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-box"></i> আমার প্রোডাক্ট
                                </a>
                                
                                <a href="{{ route('user.chatbot.pricing') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.pricing') ? 'active' : '' }}">
                                    <i class="fa-solid fa-crown"></i> প্যাকেজ কিনুন
                                </a>
                            </div>
                        </div>
    
                        <a href="{{ route('user.licenses') }}" class="menu-link">
                            <i class="fa-solid fa-key"></i> আমার লাইসেন্স
                        </a>
                        @include('user.partials.digital-download-menu')
                        <a href="{{ route('user.support') }}" class="menu-link">
                            <i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট
                        </a>
                        <a href="{{ route('user.profile') }}" class="menu-link">
                            <i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস
                        </a>
						
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4">
                            <i class="fa-solid fa-right-from-bracket"></i> লগআউট
                        </a>
                    </div>
                </div>
            </div>

            {{-- মেইন কন্টেন্ট এরিয়া --}}
            <div class="col-lg-9">
                <div class="main-content-card">
                    <div class="text-center mb-5">
                        <h1 class="fw-bold mb-2">
                            <i class="fas fa-crown" style="color: #ffc107;"></i> আপনার প্যাকেজ নির্বাচন করুন
                        </h1>
                        <p class="text-muted">আপনার ব্যবসার জন্য উপযুক্ত প্ল্যান বেছে নিন</p>
                    </div>

                    {{-- Current Plan Info --}}
                    @if(Auth::user()->active_plan)
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <strong><i class="fas fa-check-circle me-2"></i>বর্তমান প্ল্যান:</strong> {{ Auth::user()->active_plan }}
                                <br>
                                <small class="text-dark">মেয়াদ শেষ: {{ Auth::user()->plan_expiry ? Auth::user()->plan_expiry->format('d M Y') : 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row g-4 align-items-center justify-content-center">
                        @forelse($packages as $pkg)
                        @php
                            $isPopular = ($loop->iteration == 2); 
                            $isActive = (Auth::user()->active_plan == $pkg->name);
                        @endphp
                        
                        <div class="col-md-6 col-xl-4">
                            <div class="pricing-card-wrapper">
                                <div class="pricing-card-modern {{ $isActive ? 'active-pkg' : ($isPopular ? 'popular' : '') }}">
                                    
                                    {{-- কন্ডিশনাল ব্যাজ --}}
                                    @if($isActive)
                                        <div class="active-plan-badge"><i class="fas fa-check-circle"></i> বর্তমানে চালু আছে</div>
                                    @elseif($isPopular)
                                        <div class="best-value-badge">BEST VALUE</div>
                                    @endif

                                    <h4 class="plan-title">{{ $pkg->name }}</h4>

                                    <div class="price-box">
                                        <span class="price-text">৳{{ number_format($pkg->price) }}</span>
                                        <span class="price-duration">মেয়াদ: {{ $pkg->duration_text }}</span>
                                    </div>

                                    <ul class="feature-list-modern">
                                        <li><i class="fas fa-check-circle"></i> <span><strong>{{ $pkg->page_limit }}</strong> টি Facebook পেজ</span></li>
                                        <li><i class="fas fa-check-circle"></i> <span><strong>{{ number_format($pkg->message_limit) }}</strong> মেসেজ/মাস</span></li>
                                        <li><i class="fas fa-check-circle"></i> <span><strong>{{ $pkg->auto_reply_limit }}</strong> টি অটো-রিপ্লাই</span></li>
                                        <li><i class="fas fa-check-circle"></i> <span><strong>{{ $pkg->comment_automation_limit }}</strong> টি অটোমেশন</span></li>
                                        
                                        @if($pkg->product_carousel)
                                        <li><i class="fas fa-check-circle"></i> <span>প্রোডাক্ট ক্যারোসেল</span></li>
                                        @endif
                                        
                                        @if($pkg->analytics)
                                        <li><i class="fas fa-check-circle"></i> <span>এনালিটিক্স ড্যাশবোর্ড</span></li>
                                        @endif
                                        
                                        @if($pkg->broadcast)
                                        <li><i class="fas fa-check-circle"></i> <span>ব্রডকাস্ট মেসেজ</span></li>
                                        @endif
                                    </ul>

                                    <div class="mt-auto">
                                        @if($isActive)
                                            <button class="btn-pricing-modern btn-active-custom" disabled>
                                                <i class="fas fa-shield-alt"></i> প্যাকেজটি সক্রিয়
                                            </button>
                                        @else
                                            <a href="{{ route('user.chatbot.buy.package', $pkg->id) }}" class="btn-pricing-modern {{ $isPopular ? 'btn-solid-custom' : 'btn-outline-custom' }}">
                                                {{ $isPopular ? 'এখনই কিনুন' : 'অর্ডার করুন' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <h5 class="fw-bold text-muted">বর্তমানে কোনো প্যাকেজ চালু নেই।</h5>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
