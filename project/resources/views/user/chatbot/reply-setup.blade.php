@extends('layouts.front')
@section('title', 'অটো রিপ্লাই সেটআপ')
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
    
    .page-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
    .info-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .reply-type-btn { padding: 15px; border: 2px solid #e5e7eb; border-radius: 12px; text-align: center; cursor: pointer; transition: 0.3s; }
    .reply-type-btn:hover { border-color: #4e73df; background: #f8f9fc; }
    .reply-type-btn.active { border-color: #4e73df; background: #e7f0ff; }
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
    
    function selectReplyType(type) {
        document.querySelectorAll('.reply-type-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('reply_type').value = type;
        
        // Show/hide relevant fields
        document.querySelectorAll('.reply-field').forEach(field => field.style.display = 'none');
        document.getElementById(type + '-field').style.display = 'block';
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
                
                {{-- Page Header --}}
                <div class="page-header shadow-lg">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-reply-all me-2"></i>অটো রিপ্লাই সেটআপ
                            </h2>
                            <p class="mb-0 text-white-50">নির্দিষ্ট কীওয়ার্ডের জন্য স্বয়ংক্রিয় উত্তর তৈরি করুন।</p>
                        </div>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="info-box">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-lightbulb me-2"></i>কীভাবে কাজ করে?
                    </h6>
                    <p class="mb-0 small">যখন কেউ নির্দিষ্ট Keyword লিখে মেসেজ পাঠাবে, তখন স্বয়ংক্রিয়ভাবে আপনার সেট করা উত্তর পাঠানো হবে।</p>
                </div>

                {{-- Create New Reply --}}
                <div class="stat-card mb-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-plus-circle me-2 text-success"></i>নতুন অটো রিপ্লাই তৈরি করুন
                    </h5>
                    
                    <form action="{{ route('user.chatbot.reply.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Facebook পেজ নির্বাচন করুন</label>
                                <select name="facebook_page_id" class="form-select" required>
                                    <option value="">পেজ বেছে নিন...</option>
                                    @php
                                        $pages = \App\Models\FacebookPage::where('user_id', Auth::id())->where('is_connected', true)->get();
                                    @endphp
                                    @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ট্রিগার কীওয়ার্ড</label>
                                <input type="text" name="trigger_keyword" class="form-control" placeholder="উদা: হ্যালো, প্রাইস" required>
                                <small class="text-muted">যে শব্দ লিখলে রিপ্লাই যাবে</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">রিপ্লাই টাইপ নির্বাচন করুন</label>
                            <input type="hidden" name="reply_type" id="reply_type" value="text">
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="reply-type-btn active" onclick="selectReplyType('text')">
                                        <i class="fas fa-font fa-2x mb-2 text-primary"></i>
                                        <div class="fw-bold">টেক্সট মেসেজ</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reply-type-btn" onclick="selectReplyType('image')">
                                        <i class="fas fa-image fa-2x mb-2 text-success"></i>
                                        <div class="fw-bold">ইমেজ</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reply-type-btn" onclick="selectReplyType('product_carousel')">
                                        <i class="fas fa-shopping-bag fa-2x mb-2 text-warning"></i>
                                        <div class="fw-bold">প্রোডাক্ট ক্যারোসেল</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Text Field --}}
                        <div id="text-field" class="reply-field mb-3">
                            <label class="form-label fw-bold">রিপ্লাই টেক্সট</label>
                            <textarea name="reply_text" class="form-control" rows="4" placeholder="এখানে আপনার উত্তর লিখুন..."></textarea>
                        </div>
                        
                        {{-- Image Field --}}
                        <div id="image-field" class="reply-field mb-3" style="display: none;">
                            <label class="form-label fw-bold">ইমেজ URL</label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        {{-- Product Carousel Field (কাস্টমারের নিজের প্রোডাক্ট) --}}
                        <div id="product_carousel-field" class="reply-field mb-3" style="display: none;">
                            <label class="form-label fw-bold">প্রোডাক্ট নির্বাচন করুন</label>
                            <select name="product_ids[]" class="form-select" multiple size="5">
                                @php
                                    $products = \App\Models\UserChatbotProduct::where('user_id', Auth::id())->where('status', true)->orderBy('sort_order')->orderBy('id', 'desc')->get();
                                @endphp
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - ৳{{ number_format($product->price) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">একাধিক প্রোডাক্ট নির্বাচন করতে Ctrl চেপে ক্লিক করুন। প্রোডাক্ট নেই? <a href="{{ route('user.chatbot.products.create') }}">প্রোডাক্ট যোগ করুন</a></small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>সংরক্ষণ করুন
                            </button>
                            <a href="{{ route('user.chatbot.settings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Existing Replies --}}
                <div class="stat-card">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-list me-2 text-primary"></i>সংরক্ষিত অটো রিপ্লাই
                    </h5>
                    
                    @php
                        $replies = \App\Models\BotReply::whereHas('facebookPage', function($q) {
                            $q->where('user_id', Auth::id());
                        })->with('facebookPage')->latest()->get();
                    @endphp
                    
                    @if($replies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>পেজ</th>
                                    <th>কীওয়ার্ড</th>
                                    <th>টাইপ</th>
                                    <th>উত্তর</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($replies as $reply)
                                <tr>
                                    <td>
                                        <i class="fab fa-facebook text-primary me-1"></i>
                                        {{ $reply->facebookPage->page_name }}
                                    </td>
                                    <td><span class="badge bg-info">{{ $reply->keyword }}</span></td>
                                    <td>
                                        @if($reply->reply_type == 'text')
                                        <i class="fas fa-font text-muted"></i> টেক্সট
                                        @elseif($reply->reply_type == 'image')
                                        <i class="fas fa-image text-success"></i> ইমেজ
                                        @else
                                        <i class="fas fa-shopping-bag text-warning"></i> প্রোডাক্ট
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($reply->reply_text ?? 'Image/Product', 50) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('user.chatbot.reply.edit', $reply->id) }}" class="btn btn-sm btn-outline-primary me-1" title="এডিট করুন">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.chatbot.reply.delete', $reply->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ডিলিট করতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
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
                        <i class="fas fa-reply fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">এখনও কোনো অটো রিপ্লাই তৈরি করা হয়নি।</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
