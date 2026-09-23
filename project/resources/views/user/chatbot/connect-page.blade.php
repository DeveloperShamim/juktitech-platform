@extends('layouts.front')
@section('title', 'Facebook পেজ কানেক্ট করুন')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    /* সাইডবার ডিজাইন */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; background-color: #eee; }
    
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; transition: 0.3s; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    /* কন্টেন্ট কার্ড */
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    
    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }
    .menu-link i.fa-chevron-down { transition: transform 0.3s ease; }
    .menu-link.open i.fa-chevron-down { transform: rotate(180deg); }
    
    .page-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
    .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
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

            {{-- মেইন এরিয়া --}}
            <div class="col-lg-9">
                @php
                    $connectedPages = \App\Models\FacebookPage::where('user_id', Auth::id())->where('is_connected', true)->get();
                @endphp
                
                {{-- Page Header --}}
                <div class="page-header shadow-lg">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 fw-bold">
                                <i class="fab fa-facebook me-2"></i>Facebook পেজ কানেক্ট করুন
                            </h2>
                            <p class="mb-0 text-white-50">আপনার Facebook পেজ এই সিস্টেমের সাথে কানেক্ট করুন।</p>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @php
                    $brokenTokenPages = $connectedPages->filter(fn ($p) => $p->tokenIsDefinitelyInvalid());
                @endphp
                @if($brokenTokenPages->count() > 0)
                <div class="alert alert-danger mb-4">
                    <strong><i class="fas fa-unlink me-2"></i>Facebook টোকেন নষ্ট — চেটবট রিপ্লাই যাচ্ছে না</strong>
                    <p class="mb-2 mt-2 small">
                        শুধু Facebook লগইন করলেই হবে না। লগইনের পর পেজ সিলেক্ট করে
                        <strong>«পেজ কানেক্ট করুন»</strong> বাটনও চাপতে হবে — তাতেই নতুন টোকেন সেভ হয়।
                    </p>
                    <ul class="mb-2 small">
                        @foreach($brokenTokenPages as $broken)
                            <li><strong>{{ $broken->page_name }}</strong></li>
                        @endforeach
                    </ul>
                    <ol class="small mb-3">
                        <li>«Facebook দিয়ে কানেক্ট» ক্লিক করুন</li>
                        <li>সব পারমিশন Allow দিন</li>
                        <li>পেজ সিলেক্ট করুন</li>
                        <li><strong>পেজ কানেক্ট করুন</strong> চাপুন</li>
                    </ol>
                    <a href="{{ route('user.chatbot.facebook.connect') }}" class="btn btn-sm btn-danger">
                        <i class="fab fa-facebook me-1"></i> এখনই আবার কানেক্ট করুন
                    </a>
                </div>
                @endif

                {{-- Info Box --}}
                <div class="info-box">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-info-circle me-2"></i>কানেক্ট করার পূর্বে জেনে নিন
                    </h6>
                    <ul class="mb-0 small">
                        <li>আপনাকে পেজের <strong>Admin</strong> হতে হবে (যে Facebook অ্যাকাউন্ট দিয়ে লগইন করবেন সেই অ্যাকাউন্টে কমপক্ষে একটি পেজের অ্যাডমিন এক্সেস থাকতে হবে)</li>
                        <li>অ্যাপ যদি <strong>Development</strong> মোডে থাকে তাহলে Facebook App Dashboard → Roles → Test Users এ নিজেকে <strong>Tester</strong> হিসেবে যোগ করুন</li>
                        <li>প্রথমে <strong>Facebook দিয়ে কানেক্ট</strong> বাটনে ক্লিক করুন, লগইন করার সময় <strong>সব পারমিশন অনুমোদন</strong> দিন</li>
                        <li>Webhook সঠিকভাবে Configure করতে হবে</li>
                        @if(Auth::user()->connected_pages_limit)
                        <li>আপনার প্ল্যানে সর্বোচ্চ <strong>{{ Auth::user()->connected_pages_limit }}</strong> টি পেজ কানেক্ট করা যাবে</li>
                        @endif
                    </ul>
                </div>

                {{-- Connect Form --}}
                <div class="stat-card mb-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-link me-2 text-primary"></i>নতুন পেজ কানেক্ট করুন
                    </h5>

                    @if(count($fbPagesList) > 0)
                    {{-- পেজ লিস্ট লোড হলে: শুধু পেজ সিলেক্ট করে কানেক্ট (টোকেন অটো নিবে) --}}
                    <form action="{{ route('user.chatbot.connect.page.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">পেজ সিলেক্ট করুন</label>
                            <select name="page_id" class="form-select" required>
                                <option value="">-- পেজ বেছে নিন --</option>
                                @foreach($fbPagesList as $p)
                                <option value="{{ $p['id'] }}">{{ $p['name'] }} ({{ $p['id'] }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">সিলেক্ট করলেই Access Token অটোমেটিক নিয়ে নেওয়া হবে</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plug me-2"></i>পেজ কানেক্ট করুন
                            </button>
                            <a href="{{ route('user.chatbot.settings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                            </a>
                        </div>
                    </form>
                    <hr class="my-4">
                    <p class="mb-0 small text-muted">
                        আরও পেজ লোড করতে চান? 
                        <a href="{{ route('user.chatbot.facebook.connect') }}">আবার Facebook দিয়ে কানেক্ট করুন</a>
                    </p>
                    @else
                    {{-- পেজ লিস্ট নেই: প্রথমে Facebook দিয়ে কানেক্ট করুন --}}
                    <p class="text-muted mb-4">পেজ কানেক্ট করতে প্রথমে নিচের বাটন দিয়ে Facebook এ লগইন করুন। আপনার পেজের তালিকা চলে আসবে এবং পেজ সিলেক্ট করলেই Access Token অটোমেটিক নিয়ে নেওয়া হবে।</p>
                    <a href="{{ route('user.chatbot.facebook.connect') }}" class="btn btn-primary btn-lg">
                        <i class="fab fa-facebook me-2"></i>Facebook দিয়ে কানেক্ট করুন
                    </a>
                    <a href="{{ route('user.chatbot.settings') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                    </a>
                    @endif
                </div>

                {{-- বাটন দেখা না গেলে --}}
                @if($connectedPages->count() > 0)
                <div class="alert alert-info mb-4">
                    <strong><i class="fas fa-hand-pointer me-2"></i>মেসেঞ্জারে বাটন দেখা যাচ্ছে না?</strong>
                    <ul class="mb-0 mt-2 small">
                        <li>নিচের টেবিলে প্রতিটি পেজের পাশে <strong>বাটন সেট করুন</strong> ক্লিক করুন (একবার করলেই হবে)।</li>
                        <li>মেসেঞ্জারে পেজে গিয়ে <strong>Message</strong> ক্লিক করার পর ওয়েলকাম স্ক্রিন শুধু <strong>নতুন চ্যাট</strong> এ দেখা যায়। আগের চ্যাট ডিলিট করে আবার Message করলে বাটন দেখাবে।</li>
                        <li>অ্যাপ Development মোডে থাকলে শুধু অ্যাপের <strong>Admin/Tester</strong> অ্যাকাউন্টে বাটন দেখা যায়।</li>
                    </ul>
                </div>
                @endif

                {{-- Connected Pages List --}}
                <div class="stat-card">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-list me-2 text-success"></i>কানেক্টেড পেজসমূহ
                    </h5>
                    
                    @if($connectedPages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>পেজ নাম</th>
                                    <th>পেজ আইডি</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>কানেক্ট তারিখ</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($connectedPages as $page)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="fab fa-facebook text-primary"></i>
                                            </div>
                                            <strong>{{ $page->page_name }}</strong>
                                        </div>
                                    </td>
                                    <td><code>{{ $page->page_id }}</code></td>
                                    <td>
                                        @php $tokenStatus = $page->tokenStatus(); @endphp
                                        @if($tokenStatus === true)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>কানেক্টেড
                                        </span>
                                        @elseif($tokenStatus === false)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>টোকেন নষ্ট — আবার কানেক্ট করুন
                                        </span>
                                        @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-question-circle me-1"></i>যাচাই হয়নি
                                        </span>
                                        @endif
                                    </td>
                                    <td>{{ $page->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('user.chatbot.set.buttons', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success me-1" title="Get Started ও মেনু বাটন আবার সেট করুন">
                                                <i class="fas fa-hand-pointer"></i> বাটন সেট করুন
                                            </button>
                                        </form>
                                        <form action="{{ route('user.chatbot.disconnect.page', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('পেজ ডিসকানেক্ট করতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-unlink"></i> ডিসকানেক্ট
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="fab fa-facebook fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">এখনও কোনো পেজ কানেক্ট করা হয়নি।</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
