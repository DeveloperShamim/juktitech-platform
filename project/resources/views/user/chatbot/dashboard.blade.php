@extends('layouts.front')
@section('title', 'Chatbot Dashboard')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    body { font-family: 'Hind Siliguri', 'Inter', sans-serif; background-color: #f8fafc; }

    /* --- সাইডবার স্টাইল --- */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; font-size: 15px; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #eff6ff; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }
    .menu-link i.fa-chevron-down { transition: transform 0.3s ease; }
    .menu-link.open i.fa-chevron-down { transform: rotate(180deg); }

    /* --- মেইন কন্টেন্ট স্টাইল --- */
    .main-content-card { 
        background: #fff; 
        border-radius: 24px; 
        padding: 40px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.03); 
        border: 1px solid #edf2f7; 
    }

    .welcome-banner { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 40px; color: #fff; margin-bottom: 30px; position: relative; overflow: hidden; }
    .welcome-banner h2 { font-weight: 800; letter-spacing: -0.5px; }
    .welcome-banner .overlay-img { position: absolute; right: 40px; top: 50%; transform: translateY(-50%); opacity: 0.2; width: 150px; }

    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    
    .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 15px; }
    .bg-primary-subtle { background-color: #e0e7ff; color: #4338ca; }
    .bg-success-subtle { background-color: #dcfce7; color: #15803d; }
    .bg-warning-subtle { background-color: #ffedd5; color: #9a3412; }
    .bg-info-subtle { background-color: #dbeafe; color: #1e40af; }

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
                                    <i class="fa-solid fa-diagram-project"></i> বট ফ্লো
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

            {{-- মেইন কন্টেন্ট --}}
            <div class="col-lg-9">
                <div class="main-content-card">
                    
                    {{-- স্বাগতম ব্যানার --}}
                    <div class="welcome-banner shadow-lg mb-4">
                        <div class="position-relative z-1">
                            <h2 class="mb-2">
                                <i class="fab fa-facebook-messenger me-2"></i>
                                Facebook Chatbot ড্যাশবোর্ড
                            </h2>
                            <p class="mb-0 text-white-50">আপনার Facebook পেজের মেসেজ এবং কমেন্ট অটোমেশন ম্যানেজ করুন।</p>
                        </div>
                        <i class="fab fa-facebook-messenger overlay-img fa-8x"></i>
                    </div>

                    {{-- Subscription Alert --}}
                    @if(!Auth::user()->active_plan || (Auth::user()->plan_expiry && Auth::user()->plan_expiry < now()))
                        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>সাবস্ক্রিপশন প্রয়োজন!</strong>
                            চ্যাটবট ফিচার ব্যবহার করতে একটি সক্রিয় সাবস্ক্রিপশন দরকার।
                            <a href="{{ route('user.chatbot.pricing') }}" class="alert-link">প্যাকেজ দেখুন</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @elseif(Auth::user()->plan_expiry && Auth::user()->plan_expiry->diffInDays(now()) <= 7)
                        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                            <strong><i class="fas fa-info-circle me-2"></i>শীঘ্রই মেয়াদ শেষ!</strong>
                            আপনার প্ল্যান {{ Auth::user()->plan_expiry->diffInDays(now()) }} দিনের মধ্যে শেষ হবে।
                            <a href="{{ route('user.chatbot.pricing') }}" class="alert-link">রিনিউ করুন</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- স্ট্যাটাস গ্রিড --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="icon-box bg-primary-subtle">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="stat-label text-muted small fw-bold">কানেক্টেড পেজ</div>
                                <div class="h3 fw-bold mt-1 mb-0">{{ $connectedPages ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="icon-box bg-success-subtle">
                                    <i class="fas fa-reply"></i>
                                </div>
                                <div class="stat-label text-muted small fw-bold">অটো রিপ্লাই</div>
                                <div class="h3 fw-bold mt-1 mb-0">{{ $totalReplies ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="icon-box bg-warning-subtle">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="stat-label text-muted small fw-bold">অটোমেশন</div>
                                <div class="h3 fw-bold mt-1 mb-0">{{ $totalAutomations ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="icon-box bg-info-subtle">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div class="stat-label text-muted small fw-bold">মেসেজ পাঠানো</div>
                                <div class="h3 fw-bold mt-1 mb-0">{{ Auth::user()->messages_sent_this_month ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Current Plan Info --}}
                    @if(Auth::user()->active_plan)
                    <div class="stat-card mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-warning-subtle me-3">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">বর্তমান প্ল্যান</div>
                                        <div class="h5 fw-bold mb-0 text-primary">{{ Auth::user()->active_plan }}</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="text-muted small">মেয়াদ শেষ</div>
                                        <div class="fw-bold">{{ Auth::user()->plan_expiry ? Auth::user()->plan_expiry->format('d M Y') : 'N/A' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">পেজ লিমিট</div>
                                        <div class="fw-bold">{{ Auth::user()->connected_pages_limit ?? 1 }} টি</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="text-muted small mb-2">মাসিক মেসেজ লিমিট</div>
                                <div class="progress" style="height: 10px; border-radius: 10px;">
                                    @php
                                        $used = Auth::user()->messages_sent_this_month ?? 0;
                                        $limit = Auth::user()->monthly_message_limit ?? 1;
                                        $percentage = ($limit > 0) ? min(($used / $limit) * 100, 100) : 0;
                                    @endphp
                                    <div class="progress-bar {{ $percentage > 80 ? 'bg-danger' : 'bg-success' }}" 
                                         role="progressbar" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <strong>{{ number_format($used) }}</strong> / {{ number_format($limit) }} মেসেজ ব্যবহৃত
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-primary border-0 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-rocket fa-2x me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">চ্যাটবট শুরু করুন!</h6>
                                <p class="mb-0 small">Facebook Messenger অটোমেশন ব্যবহার করতে একটি প্যাকেজ নির্বাচন করুন।</p>
                            </div>
                            <a href="{{ route('user.chatbot.pricing') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-crown me-1"></i> প্যাকেজ দেখুন
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="stat-card mb-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-bolt me-2 text-warning"></i>দ্রুত অ্যাকশন
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('user.chatbot.connect.page') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-link d-block mb-2 fs-4"></i>
                                    <span class="d-block">নতুন পেজ কানেক্ট</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('user.chatbot.reply.setup') }}" class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-plus d-block mb-2 fs-4"></i>
                                    <span class="d-block">অটো রিপ্লাই যোগ</span>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('user.chatbot.comment.automation') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-comment-dots d-block mb-2 fs-4"></i>
                                    <span class="d-block">অটোমেশন সেটআপ</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Help Section --}}
                    <div class="stat-card">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-question-circle me-2 text-info"></i>কিভাবে শুরু করবেন?
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <div class="fs-2 mb-2">1️⃣</div>
                                    <div class="small fw-bold text-muted">Facebook পেজ কানেক্ট করুন</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <div class="fs-2 mb-2">2️⃣</div>
                                    <div class="small fw-bold text-muted">অটো-রিপ্লাই সেটআপ করুন</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <div class="fs-2 mb-2">3️⃣</div>
                                    <div class="small fw-bold text-muted">কমেন্ট অটোমেশন চালু করুন</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
