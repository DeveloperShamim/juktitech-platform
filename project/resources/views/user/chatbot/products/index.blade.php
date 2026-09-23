@extends('layouts.front')
@section('title', 'আমার প্রোডাক্ট')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; background-color: #eee; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }
    .page-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
    .info-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
</style>

<script>
    function toggleSubMenu(event, menuId) {
        event.preventDefault();
        const menu = document.getElementById(menuId);
        const link = event.currentTarget;
        if (menu.style.display === "none") { menu.style.display = "block"; link.classList.add('open'); }
        else { menu.style.display = "none"; link.classList.remove('open'); }
    }
</script>

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
                        <a href="{{ route('user.dashboard') }}" class="menu-link"><i class="fa-solid fa-table-columns"></i> ড্যাশবোর্ড</a>
                        <a href="{{ route('user.domains.index') }}" class="menu-link"><i class="fa-solid fa-globe"></i> আমার ডোমেইন</a>
                        <a href="{{ route('user.hostings.index') }}" class="menu-link"><i class="fa-solid fa-server"></i> আমার হোস্টিং</a>
                        <a href="{{ route('user.chatbot.products.index') }}" class="menu-link {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> প্রোডাক্ট</a>
                        <div class="menu-item-group">
                            <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/chatbot*') ? 'open' : '' }}" onclick="toggleSubMenu(event, 'chatbotSubMenu')">
                                <span><i class="fab fa-facebook-messenger"></i> চ্যাটবট সার্ভিস</span>
                                <i class="fa-solid fa-chevron-down mt-1" style="font-size: 12px;"></i>
                            </a>
                            <div id="chatbotSubMenu" class="submenu-container" style="{{ request()->is('user/chatbot*') ? 'display: block;' : 'display: none;' }}">
                                <a href="{{ route('user.chatbot.settings') }}" class="menu-link ps-5"><i class="fa-solid fa-gauge"></i> ড্যাশবোর্ড</a>
                                <a href="{{ route('user.chatbot.flows.index') }}" class="menu-link ps-5"><i class="fa-solid fa-diagram-project"></i> বট ফ্লও</a>
                                <a href="{{ route('user.chatbot.connect.page') }}" class="menu-link ps-5"><i class="fa-solid fa-link"></i> পেজ কানেক্ট</a>
                                <a href="{{ route('user.chatbot.reply.setup') }}" class="menu-link ps-5"><i class="fa-solid fa-reply"></i> অটো রিপ্লাই</a>
                                <a href="{{ route('user.chatbot.comment.automation') }}" class="menu-link ps-5"><i class="fa-solid fa-comments"></i> কমেন্ট অটোমেশন</a>
                                <a href="{{ route('user.chatbot.products.index') }}" class="menu-link ps-5 {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> আমার প্রোডাক্ট</a>
                                <a href="{{ route('user.chatbot.pricing') }}" class="menu-link ps-5"><i class="fa-solid fa-crown"></i> প্যাকেজ কিনুন</a>
                            </div>
                        </div>
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="page-header shadow-lg">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 fw-bold"><i class="fas fa-box me-2"></i>আমার প্রোডাক্ট</h2>
                            <p class="mb-0 text-white-50">চ্যাটবটে প্রোডাক্ট ক্যারোজেল হিসেবে এই প্রোডাক্টগুলো দেখাবে। খালি থাকলে অ্যাডমিনের প্রোডাক্ট দেখাবে।</p>
                        </div>
                        <a href="{{ route('user.chatbot.products.create') }}" class="btn btn-light btn-lg"><i class="fas fa-plus me-2"></i>প্রোডাক্ট যোগ করুন</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="info-box">
                    <strong>কিভাবে কাজ করে?</strong> এখানে যুক্ত প্রোডাক্টগুলো Messenger এ "প্রোডাক্ট" বা "list" লিখলে অথবা মেনু থেকে "প্রোডাক্ট" ক্লিক করলে ক্যারোজেল হিসেবে দেখাবে। কোনো প্রোডাক্ট না থাকলে সাইটের অ্যাডমিনের প্রোডাক্ট দেখাবে।
                </div>

                <div class="stat-card">
                    @if($products->isEmpty())
                        <p class="text-muted mb-0">আপনার কোনো প্রোডাক্ট নেই। <a href="{{ route('user.chatbot.products.create') }}">প্রথম প্রোডাক্ট যোগ করুন</a></p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ছবি</th>
                                        <th>নাম</th>
                                        <th>মূল্য</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $p)
                                    <tr>
                                        <td>{{ $p->sort_order ?: '-' }}</td>
                                        <td>
                                            @if($p->image)
                                                <img src="{{ filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : asset('uploads/chatbot-products/'.$p->image) }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($p->name, 40) }}</td>
                                        <td>৳{{ number_format($p->price, 0) }}</td>
                                        <td>
                                            @if($p->status)
                                                <span class="badge bg-success">চালু</span>
                                            @else
                                                <span class="badge bg-secondary">বন্ধ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('user.chatbot.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary me-1">এডিট</a>
                                            <form action="{{ route('user.chatbot.products.delete', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('প্রোডাক্ট মুছবেন?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">ডিলিট</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $products->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
