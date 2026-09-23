<div class="sidebar-card">
    <div class="user-profile-box">
        <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar mb-3" alt="User">
        <h6 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h6>
        <span class="badge bg-light text-primary border px-3">ক্লায়েন্ট</span>
    </div>
    <div class="py-3">
        <a href="{{ route('user.dashboard') }}" class="menu-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
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
        <a href="{{ route('user.licenses') }}" class="menu-link {{ request()->routeIs('user.licenses') ? 'active' : '' }}">
            <i class="fa-solid fa-key"></i> আমার লাইসেন্স
        </a>
        @include('user.partials.digital-download-menu')
        <a href="{{ route('user.support') }}" class="menu-link {{ request()->routeIs('user.support') ? 'active' : '' }}">
            <i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট
        </a>
        <a href="{{ route('user.profile') }}" class="menu-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস
        </a>
        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4">
            <i class="fa-solid fa-right-from-bracket"></i> লগআউট
        </a>
    </div>
</div>
