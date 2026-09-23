@extends('layouts.front')
@section('title', 'User Dashboard')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    /* সাইডবার ডিজাইন */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    
    /* প্রোফাইল ইমেজ ও ডিফল্ট ইমেজ ডিজাইন */
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; transition: 0.3s; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    /* কন্টেন্ট কার্ড */
    .welcome-banner { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 20px; padding: 40px; color: #fff; margin-bottom: 30px; position: relative; overflow: hidden; }
    .welcome-banner h2 { font-weight: 800; letter-spacing: -0.5px; }
    .welcome-banner .overlay-img { position: absolute; right: 40px; top: 50%; transform: translateY(-50%); opacity: 0.2; width: 150px; }

    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; transition: 0.3s; height: 100%; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    
    /* আইকন বক্স কালার */
    .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 15px; }
    .bg-primary-subtle { background-color: #e0e7ff; color: #4338ca; }
    .bg-success-subtle { background-color: #dcfce7; color: #15803d; }
    .bg-warning-subtle { background-color: #ffedd5; color: #9a3412; }
    .bg-info-subtle { background-color: #e0f2fe; color: #0369a1; }
    .bg-danger-subtle { background-color: #fee2e2; color: #b91c1c; }
    
    .table-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 30px; border: 1px solid #edf2f7; }
    .table-modern thead th { background: #f8fafc; padding: 15px; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 700; border: none; }
    .table-modern tbody td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
    .badge-status { padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; }

    /* মডাল ডিজাইন */
    .invoice-modal-content { border-radius: 15px; border: none; }
    .invoice-header { background: #f8fafc; border-bottom: 1px solid #edf2f7; border-radius: 15px 15px 0 0; padding: 20px; }
    .invoice-item-list { background: #f9f9f9; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 13px; white-space: pre-wrap; }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- সাইডবার --}}
            <div class="col-lg-3">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar mb-3" alt="User">
                        <h6 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h6>
                        <span class="badge bg-light text-primary border px-3">ক্লায়েন্ট</span>
                    </div>

                    <div class="py-3">
                        <a href="{{ route('user.dashboard') }}" class="menu-link active">
                            <i class="fa-solid fa-table-columns"></i> ড্যাশবোর্ড
                        </a>
						
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

            {{-- মেইন এরিয়া --}}
            <div class="col-lg-9">
                
                {{-- স্বাগতম ব্যানার --}}
                <div class="welcome-banner shadow-lg">
                    <div class="position-relative z-1">
                        <h2 class="mb-2">শুভ দিন, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                        <p class="mb-0 text-white-50">আপনার প্রোজেক্ট এবং সার্ভিসগুলোর সর্বশেষ আপডেট এখান থেকে চেক করুন।</p>
                    </div>
                    <i class="fa-solid fa-rocket overlay-img fa-8x"></i>
                </div>

                {{-- স্ট্যাটাস গ্রিড --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="icon-box bg-primary-subtle">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div class="stat-label text-muted small fw-bold">মোট অর্ডার</div>
                            <div class="h3 fw-bold mt-1 mb-0">{{ $data['total_orders'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="icon-box bg-success-subtle">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="stat-label text-muted small fw-bold">অ্যাক্টিভ লাইসেন্স</div>
                            <div class="h3 fw-bold mt-1 mb-0">{{ $data['active_licenses'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="icon-box bg-warning-subtle">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div class="stat-label text-muted small fw-bold">পেন্ডিং সাপোর্ট</div>
                            <div class="h3 fw-bold mt-1 mb-0">{{ $data['pending_tickets'] }}</div>
                        </div>
                    </div>
                </div>

                {{-- সাম্প্রতিক অর্ডার টেবিল --}}
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark m-0">ট্রানজেকশন হিস্ট্রি</h5>
                        <a href="{{ route('user.digital-downloads.index') }}" class="btn btn-sm btn-light border fw-bold text-muted px-3">ডিজিটাল ডাউনলোড</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-modern align-middle w-100">
                            <thead>
                                <tr>
                                    <th>অর্ডার আইডি</th>
                                    <th>তারিখ</th>
                                    <th>পেমেন্ট মেথড</th>
                                    <th>টাকার পরিমাণ</th>
                                    <th class="text-center">অবস্থা</th>
                                    <th class="text-end">ইনভয়েস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_orders'] as $order)
                                <tr>
                                    <td class="fw-bold text-primary">
                                        #{{ $order->order_number }}
                                        @if($order->type === 'license_renewal')
                                            <span class="badge bg-info-subtle text-info border border-info ms-1" style="font-size: 10px;">লাইসেন্স</span>
                                        @elseif($order->type === 'domain_renewal')
                                            <span class="badge bg-info-subtle text-info border border-info ms-1" style="font-size: 10px;">ডোমেইন</span>
                                        @elseif($order->type === 'hosting_renewal')
                                            <span class="badge bg-info-subtle text-info border border-info ms-1" style="font-size: 10px;">হোস্টিং</span>
                                        @elseif($order->type === 'digital_product')
                                            <span class="badge bg-warning-subtle text-warning border border-warning ms-1" style="font-size: 10px;">ডিজিটাল</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                                    <td>
                                        <span class="text-dark"><i class="fa-solid fa-wallet me-1 text-muted"></i> {{ $order->payment_method }}</span>
                                    </td>
                                    <td class="fw-bold">৳ {{ number_format($order->total_amount) }}</td>
                                    <td class="text-center">
                                        @if($order->type === 'license_renewal' || $order->type === 'domain_renewal' || $order->type === 'hosting_renewal' || $order->type === 'digital_product')
                                            @if(($order->status ?? '') === 'paid')
                                                <span class="badge-status bg-success-subtle text-success border border-success-subtle">পরিশোধিত</span>
                                            @elseif(($order->status ?? '') === 'cancelled' || ($order->status ?? '') === 'failed')
                                                <span class="badge-status bg-secondary text-white border-0">বাতিল</span>
                                            @else
                                                <span class="badge-status bg-warning-subtle text-warning border border-warning-subtle">পেন্ডিং</span>
                                            @endif
                                        @else
                                            @if($order->due_amount <= 0)
                                                <span class="badge-status bg-success-subtle text-success border border-success-subtle">পরিশোধিত</span>
                                            @else
                                                <span class="badge-status bg-danger-subtle text-danger border border-danger-subtle">বকেয়া আছে</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($order->type === 'license_renewal')
                                            <a href="{{ route('user.licenses') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fa-solid fa-id-card me-1"></i> লাইসেন্স দেখুন
                                            </a>
                                        @elseif($order->type === 'domain_renewal')
                                            {{-- ডোমেইন রিনিউ ইনভয়েস মডাল বাটন --}}
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 view-domain-invoice-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#domainInvoiceModal"
                                                data-order="{{ $order->order_number }}"
                                                data-date="{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y h:i A') }}"
                                                data-method="{{ $order->payment_method }}"
                                                data-total="{{ number_format($order->total_amount) }}"
                                                data-paid="{{ number_format($order->paid_amount ?? 0) }}"
                                                data-due="{{ number_format($order->due_amount ?? 0) }}"
                                                data-desc="{{ $order->description ?? 'ডোমেইন রিনিউ' }}"
                                                data-domain="{{ $order->domain_name ?? '—' }}"
                                                data-status="{{ $order->status ?? 'pending' }}"
                                            >
                                                <i class="fa-solid fa-file-invoice me-1"></i> ইনভয়েস
                                            </button>
                                        @elseif($order->type === 'hosting_renewal')
                                            {{-- হোস্টিং রিনিউ ইনভয়েস মডাল বাটন --}}
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 view-hosting-invoice-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#hostingInvoiceModal"
                                                data-order="{{ $order->order_number }}"
                                                data-date="{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y h:i A') }}"
                                                data-method="{{ $order->payment_method }}"
                                                data-total="{{ number_format($order->total_amount) }}"
                                                data-paid="{{ number_format($order->paid_amount ?? 0) }}"
                                                data-due="{{ number_format($order->due_amount ?? 0) }}"
                                                data-desc="{{ $order->description ?? 'হোস্টিং রিনিউ' }}"
                                                data-domain="{{ $order->hosting_domain ?? '—' }}"
                                                data-plan="{{ $order->plan_name ?? '—' }}"
                                                data-status="{{ $order->status ?? 'pending' }}"
                                            >
                                                <i class="fa-solid fa-file-invoice me-1"></i> ইনভয়েস
                                            </button>
                                        @elseif($order->type === 'digital_product')
                                            @if(($order->status ?? '') === 'paid' && ($order->can_download ?? false))
                                                <a href="{{ route('digital-product.download', $order->download_token) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                    <i class="fa-solid fa-download me-1"></i> ডাউনলোড
                                                </a>
                                            @elseif(($order->status ?? '') === 'paid')
                                                <a href="{{ route('user.digital-downloads.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                    <i class="fa-solid fa-ban me-1"></i> লিমিট শেষ
                                                </a>
                                            @else
                                                <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i> যাচাইাধীন</span>
                                            @endif
                                        @else
                                            {{-- ইনভয়েস বাটন (সার্ভিস অর্ডার) --}}
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 view-invoice-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#invoiceModal"
                                                data-order="{{ $order->order_number }}"
                                                data-date="{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y h:i A') }}"
                                                data-method="{{ $order->payment_method }}"
                                                data-total="{{ number_format($order->total_amount) }}"
                                                data-paid="{{ number_format($order->paid_amount ?? 0) }}"
                                                data-due="{{ number_format($order->due_amount ?? 0) }}"
                                                data-desc="{{ $order->description ?? '—' }}"
                                                data-link="{{ route('frontend.invoice.live', ['order_number' => $order->order_number, 'token' => $order->hash_token ?? '']) }}"
                                            >
                                                <i class="fa-solid fa-eye me-1"></i> বিস্তারিত
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="mb-3 opacity-25"><i class="fa-solid fa-folder-open fa-4x"></i></div>
                                        এখনো কোনো অর্ডারের তথ্য নেই।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- কাস্টম পেজিনেশন (সরাসরি এই পেজের জন্য) --}}
@php
    $paginator = $data['recent_orders'];
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

            </div>
        </div>
    </div>
</div>

{{-- ইনভয়েস মডাল --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content invoice-modal-content">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-dark" id="invoiceModalLabel">ইনভয়েস ডিটেইলস</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted text-uppercase">অর্ডার আইডি</small>
                        <h6 class="fw-bold text-primary" id="modalOrderNo">#INV-0000</h6>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted text-uppercase">তারিখ</small>
                        <h6 class="fw-bold text-dark" id="modalDate">---</h6>
                    </div>
                </div>

                {{-- পণ্যের বিবরণ --}}
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">অর্ডার আইটেমসমূহ:</label>
                    <div class="invoice-item-list" id="modalDesc">
                        ---
                    </div>
                </div>

                {{-- হিসাব --}}
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">পেমেন্ট মেথড</span>
                    <span class="fw-bold text-dark" id="modalMethod">---</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">মোট বিল</span>
                    <span class="fw-bold text-dark">৳ <span id="modalTotal">0</span></span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-success">জমা দেওয়া হয়েছে</span>
                    <span class="fw-bold text-success">৳ <span id="modalPaid">0</span></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-danger">বকেয়া আছে</span>
                    <span class="fw-bold text-danger">৳ <span id="modalDue">0</span></span>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 rounded-bottom">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                <a href="#" id="modalPrintLink" target="_blank" class="btn btn-primary">
                    <i class="fa-solid fa-print me-1"></i> প্রিন্ট ইনভয়েস
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ডোমেইন রিনিউ ইনভয়েস মডাল --}}
<div class="modal fade" id="domainInvoiceModal" tabindex="-1" aria-labelledby="domainInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content invoice-modal-content">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-dark" id="domainInvoiceModalLabel">ডোমেইন রিনিউ ইনভয়েস</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted text-uppercase">অর্ডার আইডি</small>
                        <h6 class="fw-bold text-primary" id="domainModalOrderNo">#DR-0</h6>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted text-uppercase">তারিখ</small>
                        <h6 class="fw-bold text-dark" id="domainModalDate">---</h6>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">ডোমেইন</label>
                    <div class="invoice-item-list" id="domainModalDomain">---</div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">বিবরণ</label>
                    <div class="invoice-item-list" id="domainModalDesc">ডোমেইন রিনিউ</div>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">পেমেন্ট মেথড</span>
                    <span class="fw-bold text-dark" id="domainModalMethod">---</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">মোট বিল</span>
                    <span class="fw-bold text-dark">৳ <span id="domainModalTotal">0</span></span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-success">জমা দেওয়া হয়েছে</span>
                    <span class="fw-bold text-success">৳ <span id="domainModalPaid">0</span></span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-danger">বকেয়া আছে</span>
                    <span class="fw-bold text-danger">৳ <span id="domainModalDue">0</span></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">স্ট্যাটাস</span>
                    <span class="fw-bold" id="domainModalStatus">—</span>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 rounded-bottom">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                <a href="{{ route('user.domains.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-globe me-1"></i> আমার ডোমেইন দেখুন
                </a>
            </div>
        </div>
    </div>
</div>

{{-- হোস্টিং রিনিউ ইনভয়েস মডাল --}}
<div class="modal fade" id="hostingInvoiceModal" tabindex="-1" aria-labelledby="hostingInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content invoice-modal-content">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-dark" id="hostingInvoiceModalLabel">হোস্টিং রিনিউ ইনভয়েস</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted text-uppercase">অর্ডার আইডি</small>
                        <h6 class="fw-bold text-primary" id="hostingModalOrderNo">#HR-0</h6>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted text-uppercase">তারিখ</small>
                        <h6 class="fw-bold text-dark" id="hostingModalDate">---</h6>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">হোস্টিং (ডোমেইন)</label>
                    <div class="invoice-item-list" id="hostingModalDomain">---</div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">প্ল্যান</label>
                    <div class="invoice-item-list" id="hostingModalPlan">---</div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">বিবরণ</label>
                    <div class="invoice-item-list" id="hostingModalDesc">হোস্টিং রিনিউ</div>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">পেমেন্ট মেথড</span>
                    <span class="fw-bold text-dark" id="hostingModalMethod">---</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">মোট বিল</span>
                    <span class="fw-bold text-dark">৳ <span id="hostingModalTotal">0</span></span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-success">জমা দেওয়া হয়েছে</span>
                    <span class="fw-bold text-success">৳ <span id="hostingModalPaid">0</span></span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-danger">বকেয়া আছে</span>
                    <span class="fw-bold text-danger">৳ <span id="hostingModalDue">0</span></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">স্ট্যাটাস</span>
                    <span class="fw-bold" id="hostingModalStatus">—</span>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 rounded-bottom">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                <a href="{{ route('user.hostings.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-server me-1"></i> আমার হোস্টিং দেখুন
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.view-invoice-btn').click(function() {
            // বাটন থেকে ডাটা নেওয়া
            var orderNo = $(this).data('order');
            var date = $(this).data('date');
            var total = $(this).data('total');
            var paid = $(this).data('paid');
            var due = $(this).data('due');
            var method = $(this).data('method');
            var desc = $(this).data('desc');
            var link = $(this).data('link');

            // মডালে ডাটা বসানো
            $('#modalOrderNo').text('#' + orderNo);
            $('#modalDate').text(date);
            $('#modalTotal').text(total);
            $('#modalPaid').text(paid);
            $('#modalDue').text(due);
            $('#modalMethod').text(method);
            $('#modalDesc').text(desc); // আইটেম লিস্ট
            $('#modalPrintLink').attr('href', link); // প্রিন্ট লিংক সেট করা
        });

        $('.view-domain-invoice-btn').click(function() {
            var orderNo = $(this).data('order');
            var date = $(this).data('date');
            var total = $(this).data('total');
            var paid = $(this).data('paid');
            var due = $(this).data('due');
            var method = $(this).data('method');
            var desc = $(this).data('desc');
            var domain = $(this).data('domain');
            var status = $(this).data('status');
            var statusText = status === 'paid' ? 'পরিশোধিত' : (status === 'cancelled' ? 'বাতিল' : 'পেন্ডিং');
            $('#domainModalOrderNo').text('#' + orderNo);
            $('#domainModalDate').text(date);
            $('#domainModalDomain').text(domain);
            $('#domainModalDesc').text(desc);
            $('#domainModalMethod').text(method);
            $('#domainModalTotal').text(total);
            $('#domainModalPaid').text(paid);
            $('#domainModalDue').text(due);
            $('#domainModalStatus').text(statusText);
        });

        $('.view-hosting-invoice-btn').click(function() {
            var orderNo = $(this).data('order');
            var date = $(this).data('date');
            var total = $(this).data('total');
            var paid = $(this).data('paid');
            var due = $(this).data('due');
            var method = $(this).data('method');
            var desc = $(this).data('desc');
            var domain = $(this).data('domain');
            var plan = $(this).data('plan');
            var status = $(this).data('status');
            var statusText = status === 'paid' ? 'পরিশোধিত' : (status === 'cancelled' ? 'বাতিল' : 'পেন্ডিং');
            $('#hostingModalOrderNo').text('#' + orderNo);
            $('#hostingModalDate').text(date);
            $('#hostingModalDomain').text(domain);
            $('#hostingModalPlan').text(plan);
            $('#hostingModalDesc').text(desc);
            $('#hostingModalMethod').text(method);
            $('#hostingModalTotal').text(total);
            $('#hostingModalPaid').text(paid);
            $('#hostingModalDue').text(due);
            $('#hostingModalStatus').text(statusText);
        });
    });
</script>

@endsection