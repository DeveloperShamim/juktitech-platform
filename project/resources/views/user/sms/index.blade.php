@extends('layouts.front')
@section('title', 'Send SMS')
@section('contents')
 
{{-- Tailwind CSS & SweetAlert2 --}}
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    tailwind.config = {
        prefix: 'tw-', 
        theme: { 
            extend: { 
                fontFamily: { sans: ['Inter', 'sans-serif'], },
                colors: { 
                    brand: { light: '#818cf8', DEFAULT: '#4f46e5', dark: '#3730a3' },
                    success: '#10b981', 
                    danger: '#ef4444',
                    warning: '#f59e0b'
                }
            } 
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

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
    .input-custom {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s;
        outline: none;
    }
    .input-custom:focus {
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .cost-badge {
        background: #f0f9ff;
        color: #0369a1;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #bae6fd;
    }
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
                        
                       <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            {{-- ২. মেইন কন্টেন্ট --}}
            <div class="col-lg-9 col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="tw-font-bold tw-text-gray-800 mb-1">এসএমএস পাঠান</h4>
                        <p class="tw-text-gray-500 tw-text-sm mb-0">অ্যাকাউন্ট থেকে সরাসরি নন-মাস্কিং এসএমএস পাঠান।</p>
                    </div>
                    <div class="tw-bg-indigo-50 tw-text-indigo-600 tw-px-4 tw-py-2 tw-rounded-lg tw-font-bold">
                        <i class="fa-solid fa-wallet tw-mr-2"></i> {{ Auth::user()->wallet_balance }} ৳
                    </div>
                </div>

                <div class="row g-4">
                    {{-- এসএমএস ফর্ম --}}
                    <div class="col-lg-8">
                        <div class="pro-card">
                            <form id="smsForm" action="{{ route('user.sms.send') }}" method="POST">
                                @csrf
                                
                                {{-- এসএমএস টাইপ --}}
                                <div class="mb-4">
                                    <label class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">এসএমএস টাইপ</label>
                                    <div class="tw-bg-gray-100 tw-p-3 tw-rounded-lg tw-text-gray-600 tw-font-bold tw-flex tw-items-center">
                                        <i class="fa-solid fa-comment-dots tw-mr-2 tw-text-indigo-500"></i> Non-Masking
                                        <input type="hidden" id="smsRate" value="{{ Auth::user()->non_masking_rate }}">
                                    </div>
                                </div>

                                {{-- মোবাইল নাম্বার --}}
                                <div class="mb-4">
                                    <label class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">মোবাইল নাম্বার</label>
                                    <textarea name="numbers" id="numbers" rows="2" class="input-custom" placeholder="017xxxxxxxx, 018xxxxxxxx" required onkeyup="calculateCost()"></textarea>
                                    <p class="tw-text-xs tw-text-gray-500 tw-mt-1"><i class="fa-solid fa-circle-info tw-mr-1"></i> একাধিক নাম্বার কমা (,) দিয়ে লিখুন।</p>
                                </div>

                                {{-- মেসেজ --}}
                                <div class="mb-4">
                                    <label class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">মেসেজ লিখুন</label>
                                    <textarea name="message" id="message" rows="5" class="input-custom" placeholder="আপনার বার্তা এখানে লিখুন..." required onkeyup="countChar()"></textarea>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="tw-text-sm tw-text-gray-600">
                                            <span id="charCount" class="tw-font-bold">0</span> Characters | <span id="smsCount" class="tw-font-bold tw-text-indigo-600">1</span> SMS
                                        </div>
                                        <div class="cost-badge" id="costDisplay">
                                            মোট খরচ: 0.00 ৳
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" id="submitBtn" class="tw-w-full tw-bg-indigo-600 tw-text-white tw-font-bold tw-py-3 tw-rounded-xl hover:tw-bg-indigo-700 tw-transition tw-flex tw-items-center tw-justify-center">
                                    <i class="fa-solid fa-paper-plane tw-mr-2"></i> এখনই পাঠান
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- ডান পাশের ইনফো প্যানেল --}}
                    <div class="col-lg-4">
                        <div class="pro-card tw-mb-4">
                            <h6 class="tw-font-bold tw-text-gray-800 tw-mb-3">এসএমএস রেট</h6>
                            <div class="tw-p-3 tw-bg-indigo-50 tw-rounded-lg d-flex justify-content-between">
                                <span class="tw-text-sm tw-font-medium text-indigo-700">নন-মাস্কিং রেট:</span>
                                <span class="tw-font-bold text-indigo-700">{{ Auth::user()->non_masking_rate }} ৳</span>
                            </div>
                            
                            <div class="tw-mt-4 tw-space-y-2">
                                <div class="tw-text-xs tw-text-gray-500 tw-flex tw-items-start">
                                    <i class="fa-solid fa-check tw-text-green-500 tw-mt-1 tw-mr-2"></i>
                                    <span>English: 160 Char = 1 SMS</span>
                                </div>
                                <div class="tw-text-xs tw-text-gray-500 tw-flex tw-items-start">
                                    <i class="fa-solid fa-check tw-text-green-500 tw-mt-1 tw-mr-2"></i>
                                    <span>Unicode/Bangla: 70 Char = 1 SMS</span>
                                </div>
                            </div>
                        </div>

                        <div class="pro-card tw-bg-yellow-50 tw-border-yellow-100">
                            <h6 class="tw-font-bold tw-text-yellow-800 tw-text-sm tw-mb-2">নির্দেশনা</h6>
                            <ul class="tw-text-xs tw-text-yellow-700 tw-space-y-2 tw-pl-0 tw-list-none">
                                <li>১. নাম্বার অবশ্যই ১১ ডিজিটের হতে হবে।</li>
                                <li>২. ভুল নাম্বারে এসএমএস পাঠালে ব্যালেন্স ফেরতযোগ্য নয়।</li>
                                <li>৩. প্রমোショナル এসএমএস রাত ৯টার পর পাঠানো থেকে বিরত থাকুন।</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ১. সাইডবার টগল
    function toggleSubMenu(event, menuId) {
        event.preventDefault();
        const menu = document.getElementById(menuId);
        menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
    }

    // ২. ক্যারেক্টার এবং এসএমএস কাউন্ট
    function countChar() {
        const message = document.getElementById('message').value;
        const len = message.length;
        const isBangla = /[^\x00-\x7F]/.test(message);
        const perSms = isBangla ? 70 : 160;

        let smsCount = Math.ceil(len / perSms) || 1;

        document.getElementById('charCount').innerText = len;
        document.getElementById('smsCount').innerText = smsCount;
        calculateCost(smsCount);
    }

    // ৩. খরচ ক্যালকুলেশন
    function calculateCost(smsCountInput = null) {
        const rate = parseFloat(document.getElementById('smsRate').value) || 0;
        const numbers = document.getElementById('numbers').value;
        const numList = numbers.split(',').filter(el => el.trim().length >= 11);
        const numCount = numList.length;
        
        if(!smsCountInput) {
            const message = document.getElementById('message').value;
            const isBangla = /[^\x00-\x7F]/.test(message);
            const perSms = isBangla ? 70 : 160;
            smsCountInput = Math.ceil(message.length / perSms) || 1;
        }

        const totalCost = (numCount * smsCountInput * rate).toFixed(2);
        document.getElementById('costDisplay').innerText = "মোট খরচ: " + totalCost + " ৳";
    }

    // ৪. মেইন লজিক (ভ্যালিডেশন ও পপআপ)
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- সেশন মেসেজ হ্যান্ডলিং (Laravel) ---
        @if(session('success'))
            Swal.fire({
                title: 'চমৎকার!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'ঠিক আছে',
                showClass: { popup: 'animate__animated animate__zoomIn' },
                hideClass: { popup: 'animate__animated animate__zoomOut' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'ওহ না!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'আবার দেখুন'
            });
        @endif

        // --- ফর্ম সাবমিশন ও স্প্যাম চেকার ---
        const form = document.getElementById('smsForm');
        
        // নিষিদ্ধ শব্দের তালিকা (আপনার প্রয়োজন মতো বাড়াতে পারেন)
        const forbiddenWords = [
            // Gambling & Betting
            'bet', 'betting', 'casino', 'gambling', '1xbet', 'win', 'lottary', 'baji', 'taka income',
            'বাজি', 'জুয়া', 'ক্যাসিনো', 'লটারি', 'বেটিং', 'টাকা ইনকাম', 'জিতুন', 'গেমিং',
            
            // Fake Offers & Spam
            'bkash hack', 'nagad hack', 'free money', 'click here', 'prize', 'congratulations',
            'বিকাশ হ্যাক', 'নগদ হ্যাক', 'ফ্রি টাকা', 'পুরস্কার', 'ধামাকা অফার', 'পাসওয়ার্ড', 'ওটিপি', 'লোন',
            
            // Links & Domains
            'http://', 'https://', 'www.', '.com', '.xyz', '.top', '.net', 'bit.ly', 'cutt.ly'
        ];

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // অটোমেটিক সাবমিট বন্ধ করা হলো

            const message = document.getElementById('message').value.toLowerCase();
            let foundBadWords = [];

            // মেসেজ চেক করা হচ্ছে
            forbiddenWords.forEach(word => {
                if (message.includes(word.toLowerCase())) {
                    foundBadWords.push(word);
                }
            });

            // যদি নিষিদ্ধ শব্দ পাওয়া যায়
            if (foundBadWords.length > 0) {
                Swal.fire({
                    title: 'সতর্কীকরণ!',
                    html: `
                        <div class="text-left">
                            <p class="mb-3 text-danger font-weight-bold" style="font-size:1.1em;">আপনার মেসেজে নিষিদ্ধ শব্দ বা স্প্যাম লিংক পাওয়া গেছে।</p>
                            <p class="mb-2 text-dark">আমাদের পলিসি অনুযায়ী বেটিং, জুয়া, ভুয়া অফার বা স্প্যামিং লিংক সেন্ড করা সম্পূর্ণ নিষিদ্ধ।</p>
                            <div class="p-3 rounded border border-danger bg-light text-danger text-sm mt-3">
                                <strong>শনাক্তকৃত শব্দ:</strong> <br> 
                                ${foundBadWords.map(w => `<span class="badge bg-danger text-white mr-1 mt-1">${w}</span>`).join(' ')}
                            </div>
                            <p class="mt-3 text-muted small">দয়া করে মেসেজটি পরিবর্তন করুন। বারবার এমন মেসেজ পাঠালে আপনার অ্যাকাউন্ট ব্লক হতে পারে।</p>
                        </div>
                    `,
                    icon: 'warning',
                    confirmButtonText: 'আমি বুঝতে পেরেছি',
                    confirmButtonColor: '#d33',
                    showClass: { popup: 'animate__animated animate__shakeX' },
                    allowOutsideClick: false
                });
                return; // সাবমিট থামিয়ে দেওয়া হলো
            }

            // যদি সব ঠিক থাকে, লোডার দেখাবে এবং সাবমিট করবে
            Swal.fire({
                title: 'প্রসেসিং হচ্ছে...',
                html: 'দয়া করে অপেক্ষা করুন, এসএমএস পাঠানো হচ্ছে।',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // ম্যানুয়ালি সাবমিট করা
            this.submit();
        });
    });
</script>

@endsection