@extends('layouts.front')
@section('title', 'SMS Send History')
@section('contents')

{{-- Tailwind CSS & SweetAlert2 --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        prefix: 'tw-', 
        theme: { 
            extend: { 
                fontFamily: { sans: ['Inter', 'sans-serif'], },
                colors: { 
                    brand: { light: '#818cf8', DEFAULT: '#4f46e5', dark: '#3730a3' },
                }
            } 
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }

    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; display: none; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }

    .pro-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px;
    }
    .table-custom thead { background: #f8fafc; }
    .table-custom th { font-weight: 600; color: #475569; text-transform: uppercase; font-size: 12px; letter-spacing: 0.025em; padding: 12px 16px; border-bottom: 2px solid #e2e8f0; }
    .table-custom td { padding: 16px; vertical-align: middle; color: #1e293b; border-bottom: 1px solid #f1f5f9; }
    .badge-status { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
</style>

<div class="section-padding py-5">
    <div class="container-fluid tw-max-w-7xl tw-mx-auto">
        <div class="row g-4">
            
            {{-- ১. সাইডবার --}}
            <div class="col-lg-3 col-md-4 d-none d-md-block">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <div class="user-avatar-wrapper">
                            <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar">
                        </div>
                        <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                        <span class="badge bg-light text-primary border mt-2">ব্যালেন্স: {{ Auth::user()->wallet_balance }} ৳</span>
                    </div>
                    <div class="py-3">
                        <a href="{{ route('user.dashboard') }}" class="menu-link"><i class="fa-solid fa-table-columns"></i> ড্যাশবোর্ড</a>
                        
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
                        
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            {{-- ২. মেইন কন্টেন্ট --}}
            <div class="col-lg-9 col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="tw-font-bold tw-text-gray-800 mb-1">এসএমএস পাঠানোর ইতিহাস</h4>
                        <p class="tw-text-gray-500 tw-text-sm mb-0">আপনার পাঠানো সকল এসএমএস এর বিস্তারিত রিপোর্ট এখানে দেখুন।</p>
                    </div>
                    <a href="{{ route('user.sms.panel') }}" class="tw-bg-indigo-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-bold hover:tw-bg-indigo-700 tw-transition">
                        <i class="fa-solid fa-plus tw-mr-1"></i> নতুন এসএমএস
                    </a>
                </div>

                <div class="pro-card p-0 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>তারিখ</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>মেসেজ</th>
                                    <th>এসএমএস</th>
                                    <th>খরচ</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($smsHistories as $sms)
                                <tr>
                                    <td class="tw-whitespace-nowrap">
                                        <div class="tw-text-xs tw-text-gray-400">{{ $sms->created_at->format('d M, Y') }}</div>
                                        <div class="tw-font-semibold tw-text-gray-700">{{ $sms->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="tw-bg-gray-100 tw-px-2 tw-py-1 tw-rounded tw-text-xs tw-font-bold tw-text-gray-600">
                                            {{ Str::limit($sms->numbers, 15) }}
                                        </span>
                                        @if(count(explode(',', $sms->numbers)) > 1)
                                            <div class="tw-text-[10px] tw-text-indigo-500 tw-font-bold mt-1">+{{ count(explode(',', $sms->numbers)) - 1 }} More</div>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="tw-text-sm tw-text-gray-600 tw-line-clamp-2 tw-mb-0" title="{{ $sms->message }}">
                                            {{ $sms->message }}
                                        </p>
                                    </td>
                                    <td class="tw-text-center">
                                        <span class="tw-font-bold tw-text-indigo-600">{{ $sms->total_sms_count }}</span>
                                    </td>
                                    <td class="tw-whitespace-nowrap">
                                        <span class="tw-font-bold tw-text-gray-800">{{ number_format($sms->cost, 2) }} ৳</span>
                                    </td>
                                    <td>
                                        @if($sms->status == 'success')
                                            <span class="badge-status tw-bg-green-100 tw-text-green-600">সাফল্য</span>
                                        @elseif($sms->status == 'pending')
                                            <span class="badge-status tw-bg-yellow-100 tw-text-yellow-600">পেন্ডিং</span>
                                        @else
                                            <span class="badge-status tw-bg-red-100 tw-text-red-600">ব্যর্থ</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="tw-text-center tw-py-20">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" class="tw-w-16 tw-mx-auto tw-opacity-20 mb-3">
                                        <p class="tw-text-gray-400 tw-text-sm">এখনো কোনো এসএমএস পাঠানো হয়নি।</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

               @if ($smsHistories->hasPages())
    <div class="mt-4">
        <ul class="custom-pagination">
            
            {{-- ১. আগের পেজ বাটন (Previous) --}}
            @if ($smsHistories->onFirstPage())
                <li class="disabled">
                    <span><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li>
                    <a href="{{ $smsHistories->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                </li>
            @endif

            {{-- ২. পেজ নম্বর (বর্তমান পেজের আগে ও পরে ২টা করে দেখাবে) --}}
            @foreach(range(1, $smsHistories->lastPage()) as $i)
                @if($i >= $smsHistories->currentPage() - 2 && $i <= $smsHistories->currentPage() + 2)
                    @if ($i == $smsHistories->currentPage())
                        <li class="active"><span>{{ $i }}</span></li>
                    @else
                        <li><a href="{{ $smsHistories->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            {{-- ৩. পরের পেজ বাটন (Next) --}}
            @if ($smsHistories->hasMorePages())
                <li>
                    <a href="{{ $smsHistories->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                </li>
            @else
                <li class="disabled">
                    <span><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif

        </ul>
    </div>
@endif
				
				
				<style>
				/* কাস্টম পেজিনেশন ডিজাইন */
.custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
    gap: 8px; /* বাটনের মাঝের গ্যাপ */
    margin-top: 20px;
    margin-bottom: 20px;
}

.custom-pagination li a, 
.custom-pagination li span {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%; /* গোল বাটন */
    background: #ffffff;
    color: #64748b;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

/* হোভার ইফেক্ট */
.custom-pagination li a:hover {
    background: #fff7ed; /* খুব হালকা অরেঞ্জ */
    color: #ff6b00;      /* ব্র্যান্ড অরেঞ্জ */
    border-color: #ff6b00;
    transform: translateY(-2px);
}

/* একটিভ পেজ (বর্তমান পেজ) */
.custom-pagination li.active span {
    background: #ff6b00; /* ব্র্যান্ড অরেঞ্জ */
    color: white;
    border-color: #ff6b00;
    box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
}

/* ডিজেবল বাটন */
.custom-pagination li.disabled span {
    background: #f8fafc;
    color: #cbd5e1;
    cursor: default;
    border-color: #f1f5f9;
    box-shadow: none;
}
				</style>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSubMenu(event, menuId) {
        event.preventDefault();
        const menu = document.getElementById(menuId);
        menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
    }
</script>

@endsection