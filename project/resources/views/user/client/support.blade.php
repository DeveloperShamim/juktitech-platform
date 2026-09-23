@extends('layouts.front')
@section('title', 'Support Tickets')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    
    /* সাইডবার কার্ড */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; background-color: #eee; }
    
    /* মেনু লিঙ্ক */
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    
    /* টিকেট কার্ড */
    .ticket-card { background: #fff; border-radius: 15px; padding: 25px; margin-bottom: 15px; border: 1px solid #edf2f7; transition: 0.3s; }
    .ticket-card:hover { border-color: #4e73df; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    /* স্ট্যাটাস ব্যাজ */
    .status-badge { font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 5px 12px; border-radius: 50px; }
    .badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-replied { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .badge-closed { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            {{-- বাম পাশের সাইডবার --}}
            <div class="col-lg-3">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar mb-3">
                        <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                    </div>
                    <div class="py-3">
                        <a href="{{ route('user.dashboard') }}" class="menu-link"><i class="fa-solid fa-house"></i> ড্যাশবোর্ড</a>
			
									<a href="{{ route('user.domains.index') }}" class="menu-link {{ request()->routeIs('user.domains.index') ? 'active' : '' }}">
    <i class="fa-solid fa-globe"></i> আমার ডোমেইন
</a>
{{-- আমার হোস্টিং মেনু লিংক --}}
<a href="{{ route('user.hostings.index') }}" class="menu-link {{ request()->routeIs('user.hostings.index') ? 'active' : '' }}">
    <i class="fa-solid fa-server"></i> আমার হোস্টিং
</a>
<a href="{{ route('user.chatbot.products.index') }}" class="menu-link {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}">
    <i class="fa-solid fa-box"></i> প্রোডাক্ট
</a><style>
    .submenu-container {
    background: #fdfdfd; /* সাব-মেনুর জন্য হালকা আলাদা ব্যাকগ্রাউন্ড */
    border-radius: 12px;
    margin: 2px 15px;
}

.ps-5 {
    padding-left: 45px !important; /* সাব-মেনু আইটেমগুলোকে একটু ভেতরে সরানোর জন্য */
    font-size: 14px !important;    /* সাব-মেনু ফন্ট একটু ছোট */
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

<div class="menu-item-group">
    {{-- প্যারেন্ট মেনু --}}
    <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/sms*') || request()->is('user/recharge*') ? 'open' : '' }}" onclick="toggleSubMenu(event, 'smsSubMenu')">
        <span><i class="fa-solid fa-comment-sms"></i> এসএমএস সার্ভিস</span>
        <i class="fa-solid fa-chevron-down mt-1" style="font-size: 12px;"></i>
    </a>
    
    {{-- সাব-মেনু --}}
    <div id="smsSubMenu" class="submenu-container" style="{{ request()->is('user/sms*') || request()->is('user/recharge*') ? 'display: block;' : 'display: none;' }}">
        
        <a href="{{ route('user.sms.panel') }}" class="menu-link ps-5 {{ request()->routeIs('user.sms.panel') ? 'active' : '' }}">
            <i class="fa-solid fa-paper-plane"></i> এসএমএস পাঠান
        </a>
        {{-- ২. এসএমএস হিস্ট্রি --}}
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
        
        <a href="{{ route('user.chatbot.connect.page') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.connect.page') ? 'active' : '' }}">
            <i class="fa-solid fa-link"></i> পেজ কানেক্ট
        </a>
        
        <a href="{{ route('user.chatbot.reply.setup') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.reply.setup') ? 'active' : '' }}">
            <i class="fa-solid fa-reply"></i> অটো রিপ্লাই
        </a>
        
        <a href="{{ route('user.chatbot.comment.automation') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.comment.automation') ? 'active' : '' }}">
            <i class="fa-solid fa-comments"></i> কমেন্ট অটোমেশন
        </a>
        
        <a href="{{ route('user.chatbot.pricing') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.pricing') ? 'active' : '' }}">
            <i class="fa-solid fa-crown"></i> প্যাকেজ কিনুন
        </a>
    </div>
</div>

                        <a href="{{ route('user.licenses') }}" class="menu-link"><i class="fa-solid fa-key"></i> আমার লাইসেন্স</a>
                        @include('user.partials.digital-download-menu')
                        <a href="{{ route('user.support') }}" class="menu-link active"><i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট</a>
                        <a href="{{ route('user.profile') }}" class="menu-link"><i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস</a>
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            {{-- ডান পাশের কন্টেন্ট --}}
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark m-0">সাপোর্ট টিকেট সমূহ</h4>
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newTicket">
                        <i class="fa-solid fa-circle-plus me-1"></i> নতুন টিকেট
                    </button>
                </div>

                @forelse($tickets as $ticket)
                <div class="ticket-card">
                    <div class="d-md-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="fw-bold text-dark mb-0 me-3">{{ $ticket->subject }}</h6>
                                <span class="status-badge badge-{{ strtolower($ticket->status) }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                            <div class="text-muted small">
                                <i class="fa-regular fa-calendar-check me-1"></i> 
                                {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M, Y h:i A') }}
                                <span class="mx-2">|</span>
                                <i class="fa-solid fa-hashtag me-1"></i> Ticket #{{ $ticket->id }}
                            </div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            {{-- বিস্তারিত ও রিপ্লাই বাটন --}}
                            <a href="{{ route('user.support.view', $ticket->id) }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-bold">
                                বিস্তারিত ও রিপ্লাই <i class="fa-solid fa-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <p class="mt-3 text-secondary small mb-0">
                        {{ Str::limit($ticket->message, 150) }}
                    </p>
                </div>
                @empty
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="fa-solid fa-envelope-open-text fa-4x text-light mb-3"></i>
                    <h5 class="fw-bold text-dark">কোন টিকেট পাওয়া যায়নি</h5>
                    <p class="text-muted small">সাহায্যের জন্য একটি নতুন টিকেট খুলুন।</p>
                </div>
                @endforelse
            </div>
            
        </div>
    </div>
    {{-- কাস্টম পেজিনেশন --}}
@php
    $paginator = $tickets;
@endphp

@if ($paginator->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                
                {{-- আগের পেজ বাটন (Previous) --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link border-0 rounded-circle me-1 shadow-none text-muted" 
                              style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle me-1 shadow-sm text-dark bg-white" 
                           href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                           style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </a>
                    </li>
                @endif

                {{-- পেজ নম্বরসমূহ --}}
                @foreach(range(1, $paginator->lastPage()) as $i)
                    {{-- শুধুমাত্র বর্তমান পেজের আশেপাশের পেজগুলো দেখাবে (ডিজাইন সুন্দর রাখার জন্য) --}}
                    @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link border-0 rounded-circle mx-1 shadow bg-primary text-white fw-bold" 
                                      style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">{{ $i }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0 rounded-circle mx-1 shadow-sm text-dark bg-white hover-bg-light" 
                                   href="{{ $paginator->url($i) }}" 
                                   style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">{{ $i }}</a>
                            </li>
                        @endif
                    @endif
                @endforeach

                {{-- পরের পেজ বাটন (Next) --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle ms-1 shadow-sm text-dark bg-white" 
                           href="{{ $paginator->nextPageUrl() }}" rel="next" 
                           style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link border-0 rounded-circle ms-1 shadow-none text-muted" 
                              style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                            <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i>
                        </span>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
@endif
</div>

{{-- নতুন টিকেট খোলার Modal --}}
<div class="modal fade" id="newTicket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-dark">নতুন টিকেট খুলুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('user.support.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">বিষয়</label>
                        <input type="text" name="subject" class="form-control rounded-3" placeholder="সমস্যার সংক্ষিপ্ত নাম..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">বিস্তারিত বর্ণনা</label>
                        <textarea name="message" class="form-control rounded-3" rows="5" placeholder="আপনার সমস্যাটি বিস্তারিত এখানে লিখুন..." required></textarea>
                    </div>
                    <input type="hidden" name="priority" value="medium">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow">
                        টিকিট সাবমিট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection