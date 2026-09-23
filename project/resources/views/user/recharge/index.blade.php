@extends('layouts.front')
@section('title', 'Recharge Balance')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

{{-- আপনার দেওয়া স্টাইল --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    /* সাইডবার ডিজাইন */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    
    /* প্রোফাইল ইমেজ ও ডিফল্ট ইমেজ ডিজাইন */
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; cursor: pointer; }
    .menu-link i { width: 30px; font-size: 18px; transition: 0.3s; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    /* সাব-মেনু স্টাইল */
    .submenu-container {
        background: #fdfdfd;
        border-radius: 12px;
        margin: 2px 15px;
        display: none;
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

    /* রিচার্জ পেজ স্পেসিফিক (ইনপুট ফিল্ড) */
    .pro-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 30px; border: 1px solid #edf2f7; }
    .input-custom { width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s; font-size: 18px; font-weight: 600; color: #334155; }
    .input-custom:focus { border-color: #4e73df; background: #fff; box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1); }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- সাইডবার (আপনার দেওয়া কোড অনুযায়ী) --}}
            <div class="col-lg-3">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar mb-3" alt="User">
                        <h6 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h6>
                        <span class="badge bg-light text-primary border px-3">ক্লায়েন্ট</span>
                    </div>

                    <div class="py-3">
                        <a href="{{ route('user.dashboard') }}" class="menu-link">
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

            {{-- মেইন কন্টেন্ট (রিচার্জ ফর্ম) --}}
            <div class="col-lg-9">
                
                {{-- হেডার --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark m-0">অটোমেটিক ব্যালেন্স রিচার্জ</h4>
                    <span class="badge bg-primary fs-6 px-3 py-2">ব্যালেন্স: {{ Auth::user()->wallet_balance }} ৳</span>
                </div>

                <div class="row g-4">
                    {{-- রিচার্জ ফর্ম --}}
                    <div class="col-lg-6">
                        <div class="pro-card h-100">
                            <div class="text-center mb-5">
                                <div style="background: #e0e7ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                    <i class="fa-solid fa-wallet" style="color: #4e73df; font-size: 24px;"></i>
                                </div>
                                <h5 class="fw-bold text-dark">ব্যালেন্স অ্যাড করুন</h5>
                                <p class="text-muted small">বিকাশ, নগদ বা রকেটের মাধ্যমে ইনস্ট্যান্ট রিচার্জ করুন।</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success fw-bold">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('user.recharge.process') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="fw-bold text-dark mb-2">রিচার্জের পরিমাণ</label>
                                    <input type="number" name="amount" class="input-custom" placeholder="500" required>
                                    <small class="text-primary mt-2 d-block">সর্বনিম্ন ১০ টাকা রিচার্জ করা যাবে।</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3" style="background: #4e73df; border: none;">
                                    পেমেন্ট করুন <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </form>

                            <div class="mt-4 text-center">
                                <span class="text-muted small">bKash · Nagad · Rocket — CreativePay</span>
                            </div>
                        </div>
                    </div>

                    {{-- রিচার্জ হিস্ট্রি --}}
                    <div class="col-lg-6">
                        <div class="pro-card h-100">
                            <h5 class="fw-bold text-dark mb-4">রিচার্জ হিস্ট্রি</h5>
                            <div class="table-responsive">
                                <table class="table table-modern align-middle w-100">
                                    <thead>
                                        <tr>
                                            <th>তারিখ</th>
                                            <th>টাকা</th>
                                            <th class="text-end">অবস্থা</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits as $data)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $data->created_at->format('d M, Y') }}</div>
                                                <small class="text-muted">{{ $data->trx_id }}</small>
                                            </td>
                                            <td class="fw-bold text-success">+{{ $data->amount }} ৳</td>
                                            <td class="text-end">
                                                @if($data->status == 'completed')
                                                    <span class="badge bg-success rounded-pill px-3">Success</span>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-folder-open fa-2x mb-2 opacity-50"></i>
                                                <p class="mb-0 small">কোনো হিস্ট্রি নেই</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $deposits->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleSubMenu(event, menuId) {
        event.preventDefault();
        const menu = document.getElementById(menuId);
        const link = event.currentTarget;
        
        if (menu.style.display === "none" || menu.style.display === "") {
            menu.style.display = "block";
            link.classList.add('open');
        } else {
            menu.style.display = "none";
            link.classList.remove('open');
        }
    }
</script>

@endsection