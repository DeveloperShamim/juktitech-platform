@extends('layouts.front')
@section('title', 'Profile Settings')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    
    /* সাইডবার ডিজাইন */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    
    /* প্রোফাইল ইমেজ ডিজাইন */
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    /* ফর্ম ডিজাইন */
    .form-card { background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #edf2f7; }
    .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 12px 15px; }
    .form-control:focus { border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1); }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            {{-- সাইডবার --}}
            <div class="col-lg-3">
                <div class="sidebar-card">
                    <div class="user-profile-box">
                        <div class="user-avatar-wrapper">
                            {{-- ডিফল্ট ইমেজ লজিক --}}
                            <img id="avatar-preview" src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar">
                        </div>
                        <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                        <span class="badge bg-light text-primary border mt-2">ক্লায়েন্ট</span>
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
                        <a href="{{ route('user.support') }}" class="menu-link"><i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট</a>
                        <a href="{{ route('user.profile') }}" class="menu-link active"><i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস</a>
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            {{-- কন্টেন্ট এরিয়া --}}
            <div class="col-lg-9">
                <h4 class="fw-bold text-dark mb-4">প্রোফাইল সেটিংস আপডেট</h4>
                
                @if(session('success')) 
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    </div> 
                @endif

                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- ব্যক্তিগত তথ্য --}}
                    <div class="form-card">
                        <h6 class="fw-bold text-primary mb-4"><i class="fa-solid fa-circle-user me-2"></i> ব্যক্তিগত তথ্য ও ছবি</h6>
                        <div class="row g-3">
                            <div class="col-12 mb-3">
                                <label class="form-label d-block fw-bold">প্রোফাইল ছবি পরিবর্তন</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-wrapper m-0" style="width: 70px; height: 70px;">
                                        <img id="avatar-preview-small" src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar">
                                    </div>
                                    <input type="file" name="photo" class="form-control" onchange="previewImage(this)">
                                </div>
                                <small class="text-muted d-block mt-2">অনুমোদিত ফরম্যাট: JPG, PNG (সর্বোচ্চ ২ মেগাবাইট)</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">পুরো নাম</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ফোন নাম্বার</label>
                                <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ইমেইল এড্রেস</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
							<div class="col-12">
    <label class="form-label">ঠিকানা (Address)</label>
    <textarea name="address" class="form-control" rows="2" placeholder="আপনার পূর্ণ ঠিকানা লিখুন">{{ Auth::user()->address }}</textarea>
</div>
                        </div>
                    </div>

                    {{-- পাসওয়ার্ড পরিবর্তন --}}
                    <div class="form-card border-danger-subtle">
                        <h6 class="fw-bold text-danger mb-4"><i class="fa-solid fa-shield-halved me-2"></i> পাসওয়ার্ড পরিবর্তন</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">নতুন পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" placeholder="পরিবর্তন না করলে ফাঁকা রাখুন">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">কনফার্ম পাসওয়ার্ড</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="পুনরায় নতুন পাসওয়ার্ড লিখুন">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> আপডেট সেভ করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('avatar-preview-small').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection