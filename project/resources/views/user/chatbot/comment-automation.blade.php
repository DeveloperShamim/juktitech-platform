@extends('layouts.front')
@section('title', 'কমেন্ট অটোমেশন')
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
    
    .page-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
    .info-box { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
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
                
                {{-- Page Header --}}
                <div class="page-header shadow-lg">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-comments me-2"></i>কমেন্ট অটোমেশন সেটআপ
                            </h2>
                            <p class="mb-0 text-white-50">পোস্টের কমেন্টে স্বয়ংক্রিয় উত্তর এবং ইনবক্স মেসেজ পাঠান।</p>
                        </div>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="info-box">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-lightbulb me-2"></i>কমেন্ট অটোমেশন কীভাবে কাজ করে?
                    </h6>
                    <p class="mb-0 small">যখন কেউ আপনার পোস্টে নির্দিষ্ট শব্দ লিখে কমেন্ট করবে, তখন স্বয়ংক্রিয়ভাবে:</p>
                    <ul class="mb-0 small mt-2">
                        <li><strong>পাবলিক রিপ্লাই:</strong> কমেন্টের নিচে সবাই দেখতে পারবে</li>
                        <li><strong>প্রাইভেট মেসেজ:</strong> শুধুমাত্র কমেন্টকারীর ইনবক্সে যাবে</li>
                    </ul>
                </div>

                {{-- Create New Automation --}}
                <div class="stat-card mb-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-plus-circle me-2 text-warning"></i>নতুন অটোমেশন তৈরি করুন
                    </h5>
                    
                    <form action="{{ route('user.chatbot.comment.store') }}" method="POST">
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
                                <label class="form-label fw-bold">ট্রিগার শব্দ</label>
                                <input type="text" name="trigger_word" class="form-control" placeholder="উদা: অর্ডার, কিনবো" required>
                                <small class="text-muted">কমেন্টে এই শব্দ থাকলে অটোমেশন চালু হবে</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">পাবলিক রিপ্লাই (কমেন্টে)</label>
                            <textarea name="reply_comment" class="form-control" rows="3" placeholder="কমেন্টের নিচে এই মেসেজটি দেখাবে..."></textarea>
                            <small class="text-muted">খালি রাখলে কোনো পাবলিক রিপ্লাই যাবে না</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রাইভেট মেসেজ (ইনবক্সে)</label>
                            <textarea name="private_reply" class="form-control" rows="3" placeholder="ইনবক্সে এই মেসেজটি পাঠানো হবে..."></textarea>
                            <small class="text-muted">খালি রাখলে কোনো ইনবক্স মেসেজ যাবে না</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save me-2"></i>সংরক্ষণ করুন
                            </button>
                            <a href="{{ route('user.chatbot.settings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Existing Automations --}}
                <div class="stat-card">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-list me-2 text-primary"></i>সংরক্ষিত অটোমেশন
                    </h5>
                    
                    @php
                        $automations = \App\Models\CommentAutomation::whereHas('facebookPage', function($q) {
                            $q->where('user_id', Auth::id());
                        })->with('facebookPage')->latest()->get();
                    @endphp
                    
                    @if($automations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>পেজ</th>
                                    <th>ট্রিগার শব্দ</th>
                                    <th>পাবলিক রিপ্লাই</th>
                                    <th>প্রাইভেট মেসেজ</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($automations as $automation)
                                <tr>
                                    <td>
                                        <i class="fab fa-facebook text-primary me-1"></i>
                                        {{ $automation->facebookPage->page_name }}
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ $automation->trigger_word }}</span></td>
                                    <td>
                                        @if($automation->reply_comment)
                                        <i class="fas fa-check-circle text-success"></i> হ্যাঁ
                                        @else
                                        <i class="fas fa-times-circle text-muted"></i> না
                                        @endif
                                    </td>
                                    <td>
                                        @if($automation->private_reply)
                                        <i class="fas fa-check-circle text-success"></i> হ্যাঁ
                                        @else
                                        <i class="fas fa-times-circle text-muted"></i> না
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('user.chatbot.comment.edit', $automation->id) }}" class="btn btn-sm btn-outline-primary me-1" title="এডিট করুন">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.chatbot.comment.delete', $automation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ডিলিট করতে চান?')">
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
                        <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">এখনও কোনো কমেন্ট অটোমেশন তৈরি করা হয়নি।</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
