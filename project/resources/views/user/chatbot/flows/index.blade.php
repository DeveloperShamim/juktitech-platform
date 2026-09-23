@extends('layouts.front')
@section('title', 'Bot Flows')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Hind Siliguri', 'Inter', sans-serif; background-color: #f8fafc; }

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

    .main-content-card { 
        background: #fff; 
        border-radius: 24px; 
        padding: 40px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.03); 
        border: 1px solid #edf2f7; 
    }

    .flow-card { 
        background: #fff; 
        border-radius: 16px; 
        padding: 20px; 
        border: 1px solid #edf2f7; 
        transition: 0.3s; 
        margin-bottom: 20px;
    }
    .flow-card:hover { 
        box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        transform: translateY(-3px); 
    }

    .flow-card .flow-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 15px; 
    }

    .flow-card .flow-name { 
        font-size: 18px; 
        font-weight: 700; 
        color: #2d3436; 
    }

    .flow-card .flow-description { 
        color: #636e72; 
        font-size: 14px; 
        margin-bottom: 15px; 
    }

    .flow-card .flow-meta { 
        display: flex; 
        gap: 15px; 
        font-size: 13px; 
        color: #95a5a6; 
    }

    .badge-active { 
        background: #d1fae5; 
        color: #065f46; 
        padding: 5px 12px; 
        border-radius: 20px; 
        font-size: 12px; 
        font-weight: 600; 
    }

    .badge-inactive { 
        background: #fee2e2; 
        color: #991b1b; 
        padding: 5px 12px; 
        border-radius: 20px; 
        font-size: 12px; 
        font-weight: 600; 
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
                    
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">
                                <i class="fa-solid fa-diagram-project me-2 text-primary"></i>
                                বট ফ্লো ম্যানেজমেন্ট
                            </h2>
                            <p class="text-muted mb-0">Drag & Drop দিয়ে chatbot conversation flow তৈরি করুন</p>
                        </div>
                        <a href="{{ route('user.chatbot.flows.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>নতুন ফ্লো তৈরি
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Bot Flows List --}}
                    @forelse($flows as $flow)
                    <div class="flow-card">
                        <div class="flow-header">
                            <div>
                                <div class="flow-name">{{ $flow->name }}</div>
                                @if($flow->is_active)
                                    <span class="badge-active">সক্রিয়</span>
                                @else
                                    <span class="badge-inactive">নিষ্ক্রিয়</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.chatbot.flows.edit', $flow->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> এডিট
                                </a>
                                <form action="{{ route('user.chatbot.flows.toggle', $flow->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $flow->is_active ? 'warning' : 'success' }}">
                                        <i class="fas fa-power-off"></i> {{ $flow->is_active ? 'বন্ধ' : 'চালু' }}
                                    </button>
                                </form>
                                <form action="{{ route('user.chatbot.flows.delete', $flow->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ডিলিট করতে চান?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if($flow->description)
                        <div class="flow-description">{{ $flow->description }}</div>
                        @endif
                        
                        <div class="flow-meta">
                            <span><i class="fas fa-calendar me-1"></i>{{ $flow->created_at->format('d M Y') }}</span>
                            @if($flow->facebookPage)
                            <span><i class="fab fa-facebook me-1"></i>{{ $flow->facebookPage->page_name }}</span>
                            @endif
                            <span><i class="fas fa-tag me-1"></i>{{ ucfirst($flow->trigger_type) }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-diagram-project fa-4x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">কোনো Bot Flow নেই</h5>
                        <p class="text-muted mb-4">নতুন conversation flow তৈরি করুন</p>
                        <a href="{{ route('user.chatbot.flows.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>প্রথম ফ্লো তৈরি করুন
                        </a>
                    </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($flows->hasPages())
                    <div class="mt-4">
                        {{ $flows->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
