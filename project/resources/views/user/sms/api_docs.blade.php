@extends('layouts.front')
@section('title', 'SMS API Documentation')
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
                    brand: { light: '#818cf8', DEFAULT: '#4f46e5', dark: '#3730a3' }
                }
            } 
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }

    /* সাইডবার স্টাইল */
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; display: none; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }

    /* API কার্ড স্টাইল */
    .pro-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 24px; }
    pre { background: #1e293b; color: #f8fafc; padding: 20px; border-radius: 12px; font-size: 13px; position: relative; overflow-x: auto; font-family: 'Fira Code', monospace; }
    .copy-btn { position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 5px; padding: 4px 8px; font-size: 11px; cursor: pointer; }
    .endpoint-badge { background: #10b981; color: white; padding: 3px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; }

    /* সাব মেনু স্টাইল */
    .submenu-container {
        background: #fdfdfd;
        border-radius: 12px;
        margin: 2px 15px;
    }
    .menu-link i.fa-chevron-down {
        transition: transform 0.3s ease;
    }
    .menu-link.open i.fa-chevron-down {
        transform: rotate(180deg);
    }
</style>

<div class="section-padding py-5">
    <div class="container-fluid tw-max-w-7xl tw-mx-auto">
        <div class="row g-4">
            
            {{-- ১. সাইডবার (স্থির রাখা হয়েছে) --}}
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

                        {{-- ফ্রড সার্ভিস সাব-মেনু --}}{{-- এসএমএস সার্ভিস সাব-মেনু --}}
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

            {{-- ২. মেইন কন্টেন্ট (API Docs) --}}
            <div class="col-lg-9 col-md-8">
                <div class="tw-mb-6">
                    <h4 class="tw-font-bold tw-text-gray-800 mb-1">এসএমএস এপিআই ডকুমেন্টেশন</h4>
                    <p class="tw-text-gray-500 tw-text-sm">সহজেই আমাদের গেটওয়ে আপনার প্ল্যাটফর্মে ইন্টিগ্রেট করুন।</p>
                </div>

                <div class="row g-4">
                    {{-- বাম পাশের মেইন গাইড --}}
                    <div class="col-lg-8">
                        {{-- API Info Card --}}
                        <div class="pro-card">
                            <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                                <h5 class="tw-font-bold tw-text-gray-800 tw-mb-0">Send SMS Endpoint</h5>
                                <span class="endpoint-badge">GET/POST</span>
                            </div>

                            {{-- এন্ডপয়েন্ট ইউআরএল --}}
                            <div class="tw-mb-6">
                                <label class="tw-block tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-mb-2">API URL</label>
                                <div class="tw-bg-gray-100 tw-p-3 tw-rounded-lg tw-font-mono tw-text-xs tw-border tw-text-gray-700 tw-break-all">
                                    {{ url('/api/smsapi') }}?api_key={{ Auth::user()->api_key }}&type=text&number={number}&message={message}
                                </div>
                            </div>

                            {{-- প্যারামিটার টেবিল --}}
                            <div class="tw-overflow-x-auto tw-mb-6">
                                <table class="tw-w-full tw-text-left tw-text-sm">
                                    <thead class="tw-bg-gray-50 tw-text-gray-600">
                                        <tr>
                                            <th class="tw-py-2 tw-px-3 tw-border-b">Parameter</th>
                                            <th class="tw-py-2 tw-px-3 tw-border-b">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tw-text-gray-700">
                                        <tr>
                                            <td class="tw-py-2 tw-px-3 tw-border-b"><code>api_key</code></td>
                                            <td class="tw-py-2 tw-px-3 tw-border-b">আপনার ইউনিক এপিআই কী।</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-py-2 tw-px-3 tw-border-b"><code>number</code></td>
                                            <td class="tw-py-2 tw-px-3 tw-border-b">প্রাপকের নম্বর (যেমন: 017XXXXXXXX)।</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- কোড স্যাম্পল --}}
                            <div>
                                <h6 class="tw-font-bold tw-text-gray-800 tw-text-sm tw-mb-3">PHP Sample (cURL)</h6>
                                <div class="tw-relative group">
                                    <div onclick="copyText('phpSample')" class="copy-btn">Copy</div>
<pre id="phpSample">
$api_key = "{{ Auth::user()->api_key }}";
$url = "{{ url('/api/smsapi') }}";

$data = [
    'api_key'  => $api_key,
    'number'   => '01700000000',
    'message'  => 'This is a test message',
    'type'     => 'text'
];

$ch = curl_init($url."?".http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
</pre>
                                </div>
                            </div>
                        </div>

                        {{-- [NEW] প্লাগিন এবং অ্যাডন ডাউনলোড সেকশন --}}
                        <div class="pro-card tw-mt-6">
                            <h6 class="tw-font-bold tw-text-gray-800 tw-mb-4">রেডিমেড ইন্টিগ্রেশন (Plugins & Addons)</h6>
                            <div class="row g-3">
                                {{-- WordPress --}}
                                <div class="col-md-6">
                                    <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4 tw-flex tw-items-center tw-justify-between hover:tw-shadow-sm tw-transition tw-bg-white">
                                        <div class="tw-flex tw-items-center tw-gap-3">
                                            <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-blue-50 tw-flex tw-items-center tw-justify-center">
                                                <i class="fa-brands fa-wordpress tw-text-xl tw-text-[#21759b]"></i>
                                            </div>
                                            <div>
                                                <h6 class="tw-font-bold tw-text-sm tw-text-gray-700 tw-mb-0">WordPress Plugin</h6>
                                                <p class="tw-text-xs tw-text-gray-500 tw-mb-0">SMS Gateway</p>
                                            </div>
                                        </div>
                                        {{-- এখানে href এ আপনার ফাইলের লিংক দিন --}}
                                        <a href="https://drive.google.com/file/d/1rUdQoXZWqE4CSpdKopq_NgIrb2AUqD84/view?usp=drive_link" class="tw-bg-indigo-600 tw-text-white tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-font-medium hover:tw-bg-indigo-700 hover:tw-text-white tw-transition">
                                            <i class="fa-solid fa-download tw-mr-1"></i> ডাউনলোড
                                        </a>
                                    </div>
                                </div>

                                {{-- WHMCS --}}
                                <div class="col-md-6">
                                    <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4 tw-flex tw-items-center tw-justify-between hover:tw-shadow-sm tw-transition tw-bg-white">
                                        <div class="tw-flex tw-items-center tw-gap-3">
                                            <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-green-50 tw-flex tw-items-center tw-justify-center">
                                                <i class="fa-solid fa-server tw-text-xl tw-text-green-600"></i>
                                            </div>
                                            <div>
                                                <h6 class="tw-font-bold tw-text-sm tw-text-gray-700 tw-mb-0">WHMCS Addon</h6>
                                                <p class="tw-text-xs tw-text-gray-500 tw-mb-0">Module</p>
                                            </div>
                                        </div>
                                        {{-- এখানে href এ আপনার ফাইলের লিংক দিন --}}
                                        <a href="https://drive.google.com/file/d/1wPEdCh-qq8VqEuuOevW47-aOoviOW2HS/view?usp=drive_link" class="tw-bg-indigo-600 tw-text-white tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-font-medium hover:tw-bg-indigo-700 hover:tw-text-white tw-transition">
                                            <i class="fa-solid fa-download tw-mr-1"></i> ডাউনলোড
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- [END NEW SECTION] --}}

                    </div>

                    {{-- ডান পাশের ইনফো কার্ড --}}
                    <div class="col-lg-4">
                        <div class="pro-card tw-mb-4">
                            <h6 class="tw-font-bold tw-text-gray-800 tw-mb-3">Your API Key</h6>
                            <div class="tw-p-3 tw-bg-indigo-50 tw-rounded-lg tw-border tw-border-indigo-100 tw-break-all">
                                <code id="apiKeyDisplay" class="tw-text-xs tw-font-bold tw-text-indigo-700">{{ Auth::user()->api_key ?? 'Not Set' }}</code>
                            </div>
                            <button onclick="copyText('apiKeyDisplay')" class="tw-w-full tw-mt-3 tw-bg-white tw-border tw-text-gray-700 tw-text-xs tw-py-2 tw-rounded-lg hover:tw-bg-gray-50">
                                <i class="fa-regular fa-copy tw-mr-1"></i> কী কপি করুন
                            </button>
                        </div>

                        <div class="pro-card tw-bg-yellow-50 tw-border-yellow-100">
                            <h6 class="tw-font-bold tw-text-yellow-800 tw-text-sm tw-mb-2">Response Format</h6>
<pre class="!tw-p-3 !tw-text-[10px] !tw-bg-slate-800">
{
  "status": "success",
  "msg": "SMS Sent Successfully",
  "msg_id": "12345"
}
</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // সাইডবার টগল
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

    // কপি ফাংশন
    function copyText(id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text);
        Swal.fire({
            icon: 'success',
            title: 'কপি হয়েছে!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>

@endsection