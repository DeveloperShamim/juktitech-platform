@extends('layouts.front')
@section('title', 'My Licenses')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    /* ── Sidebar ── */
    .sidebar-card { background:#fff; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.05); overflow:hidden; border:1px solid #edf2f7; }
    .user-profile-box { text-align:center; padding:35px 20px; background:#fff; border-bottom:1px solid #f1f5f9; }
    .user-avatar-wrapper { position:relative; width:100px; height:100px; margin:0 auto 15px; }
    .user-avatar { width:100%; height:100%; border-radius:50%; object-fit:cover; border:3px solid #4e73df; background-color:#eee; }
    .menu-link { display:flex; align-items:center; padding:14px 20px; color:#64748b; text-decoration:none; border-radius:12px; margin:5px 15px; transition:.3s; font-weight:500; font-size:15px; }
    .menu-link i { width:30px; font-size:18px; }
    .menu-link:hover, .menu-link.active { background:#f1f5f9; color:#4e73df; }
    .menu-link.active i { color:#4e73df; }
    .menu-link.text-danger:hover { background:#fff1f2; color:#e11d48; }
    .submenu-container { background:#fdfdfd; border-radius:12px; margin:2px 15px; }
    .ps-5 { padding-left:45px !important; font-size:14px !important; }
    .menu-link i.fa-chevron-down { transition:transform .3s ease; }
    .menu-link.open i.fa-chevron-down { transform:rotate(180deg); }

    /* ── Main card ── */
    .main-content-card { background:#fff; border-radius:20px; padding:30px; box-shadow:0 10px 30px rgba(0,0,0,.05); border:1px solid #edf2f7; }

    /* ── Search bar ── */
    .search-wrap { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:22px; }
    .search-input-group { position:relative; flex:1; min-width:200px; }
    .search-input-group i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:14px; pointer-events:none; }
    .search-input { width:100%; height:42px; padding:0 14px 0 40px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; transition:.3s; background:#f8fafc; }
    .search-input:focus { outline:none; border-color:#4e73df; background:#fff; box-shadow:0 0 0 3px rgba(78,115,223,.1); }
    .filter-select { height:42px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; background:#f8fafc; cursor:pointer; }
    .filter-select:focus { outline:none; border-color:#4e73df; }
    .btn-search { height:42px; padding:0 20px; background:#4e73df; color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; transition:.3s; white-space:nowrap; }
    .btn-search:hover { background:#224abe; }
    .btn-clear { height:42px; padding:0 16px; background:#f1f5f9; color:#64748b; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; transition:.3s; text-decoration:none; display:flex; align-items:center; }
    .btn-clear:hover { background:#e2e8f0; color:#374151; }

    /* ── Table ── */
    .custom-table { width:100%; border-collapse:separate; border-spacing:0 8px; }
    .custom-table thead th { background:#f8fafc; color:#475569; font-weight:600; padding:13px 15px; border:none; text-transform:uppercase; font-size:11px; letter-spacing:.5px; }
    .custom-table tbody tr { transition:.3s; }
    .custom-table tbody tr:hover td { background:#fafbff; }
    .custom-table tbody td { padding:14px 15px; background:#fff; border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9; vertical-align:middle; font-size:14px; }
    .custom-table tbody td:first-child { border-left:1px solid #f1f5f9; border-top-left-radius:12px; border-bottom-left-radius:12px; }
    .custom-table tbody td:last-child { border-right:1px solid #f1f5f9; border-top-right-radius:12px; border-bottom-right-radius:12px; }

    /* ── Badges ── */
    .badge-active  { background:#dcfce7; color:#15803d; padding:4px 12px; border-radius:50px; font-size:12px; font-weight:600; cursor:pointer; transition:.2s; border:none; }
    .badge-active:hover  { background:#bbf7d0; }
    .badge-expired { background:#fee2e2; color:#b91c1c; padding:4px 12px; border-radius:50px; font-size:12px; font-weight:600; cursor:pointer; transition:.2s; border:none; }
    .badge-expired:hover { background:#fecaca; }
    .badge-lifetime { background:#fdf4ff; color:#7e22ce; padding:4px 12px; border-radius:50px; font-size:12px; font-weight:700; display:inline-block; }

    /* ── Action buttons ── */
    .action-btn { width:32px; height:32px; border-radius:8px; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-size:13px; transition:.2s; }
    .btn-edit-action  { background:#eff6ff; color:#3b82f6; }
    .btn-edit-action:hover  { background:#dbeafe; }
    .btn-del-action   { background:#fff1f2; color:#e11d48; }
    .btn-del-action:hover   { background:#ffe4e6; }
    .btn-renew-action { background:#f0fdf4; color:#16a34a; font-size:12px; padding:0 12px; width:auto; border-radius:8px; height:32px; font-weight:600; }
    .btn-renew-action:hover { background:#dcfce7; }

    /* ── Key display ── */
    .license-key-wrap { display:flex; align-items:center; gap:8px; }
    .license-key-text { font-family:'Courier New',monospace; font-weight:700; color:#4e73df; font-size:13px; letter-spacing:.5px; }
    .btn-copy { background:none; border:none; cursor:pointer; color:#94a3b8; font-size:13px; padding:2px 4px; transition:.2s; }
    .btn-copy:hover { color:#4e73df; }

    /* ── Domain cell ── */
    .domain-cell { display:flex; align-items:center; gap:8px; }
    .domain-cell a { color:#64748b; font-size:12px; }
    .domain-cell a:hover { color:#4e73df; }

    /* ── Modal ── */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:9999; align-items:center; justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:#fff; border-radius:20px; padding:32px; width:100%; max-width:460px; box-shadow:0 25px 60px rgba(0,0,0,.15); animation:modalIn .25s ease; }
    @keyframes modalIn { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
    .modal-title { font-size:18px; font-weight:700; color:#1e293b; margin-bottom:6px; }
    .modal-sub   { font-size:13px; color:#94a3b8; margin-bottom:24px; }
    .modal-label { font-size:12px; font-weight:700; color:#374151; margin-bottom:6px; display:block; }
    .modal-input { width:100%; height:46px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; transition:.3s; }
    .modal-input:focus { outline:none; border-color:#4e73df; box-shadow:0 0 0 3px rgba(78,115,223,.1); }
    .modal-footer { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; }
    .btn-modal-cancel { padding:10px 22px; background:#f1f5f9; color:#64748b; border:none; border-radius:10px; font-weight:600; cursor:pointer; }
    .btn-modal-cancel:hover { background:#e2e8f0; }
    .btn-modal-save { padding:10px 24px; background:#4e73df; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer; }
    .btn-modal-save:hover { background:#224abe; }
    .btn-modal-delete { padding:10px 24px; background:#e11d48; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer; }
    .btn-modal-delete:hover { background:#be123c; }

    /* ── Toast ── */
    .toast-msg { position:fixed; bottom:28px; right:28px; z-index:99999; background:#1e293b; color:#fff; padding:14px 22px; border-radius:12px; font-size:14px; font-weight:500; display:none; align-items:center; gap:10px; box-shadow:0 8px 24px rgba(0,0,0,.2); max-width:320px; }
    .toast-msg.show { display:flex; animation:toastIn .3s ease; }
    @keyframes toastIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
    .toast-msg.success .toast-icon { color:#4ade80; }
    .toast-msg.error   .toast-icon { color:#f87171; }
</style>

<div class="section-padding py-5">
<div class="container">
<div class="row g-4">

    {{-- ── Sidebar ── --}}
    <div class="col-lg-3">
        <div class="sidebar-card">
            <div class="user-profile-box">
                <div class="user-avatar-wrapper">
                    <img src="{{ Auth::user()->photo ? asset_image('assets/images/users/'.Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="user-avatar">
                </div>
                <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                <span class="badge bg-light text-primary border mt-2">ক্লায়েন্ট</span>
            </div>
            <div class="py-3">
                <a href="{{ route('user.dashboard') }}" class="menu-link"><i class="fa-solid fa-table-columns"></i> ড্যাশবোর্ড</a>
                <a href="{{ route('user.domains.index') }}" class="menu-link {{ request()->routeIs('user.domains.index') ? 'active' : '' }}"><i class="fa-solid fa-globe"></i> আমার ডোমেইন</a>
                <a href="{{ route('user.hostings.index') }}" class="menu-link {{ request()->routeIs('user.hostings.index') ? 'active' : '' }}"><i class="fa-solid fa-server"></i> আমার হোস্টিং</a>
                <a href="{{ route('user.chatbot.products.index') }}" class="menu-link {{ request()->routeIs('user.chatbot.products.*') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> প্রোডাক্ট</a>
<div class="menu-item-group">
                    <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/sms*')||request()->is('user/recharge*') ? 'open' : '' }}" onclick="toggleSubMenu(event,'smsSubMenu')">
                        <span><i class="fa-solid fa-comment-sms"></i> এসএমএস সার্ভিস</span>
                        <i class="fa-solid fa-chevron-down mt-1" style="font-size:12px;"></i>
                    </a>
                    <div id="smsSubMenu" class="submenu-container" style="{{ request()->is('user/sms*')||request()->is('user/recharge*') ? 'display:block;' : 'display:none;' }}">
                        <a href="{{ route('user.sms.panel') }}"    class="menu-link ps-5"><i class="fa-solid fa-paper-plane"></i> এসএমএস পাঠান</a>
                        <a href="{{ route('user.sms.history') }}"  class="menu-link ps-5"><i class="fa-solid fa-history"></i> এসএমএস হিস্ট্রি</a>
                        <a href="{{ route('user.recharge.index') }}" class="menu-link ps-5"><i class="fa-solid fa-wallet"></i> রিচার্জ করুন</a>
                        <a href="{{ route('user.sms.api_docs') }}" class="menu-link ps-5"><i class="fa-solid fa-code"></i> এপিআই ডকুমেন্টেশন</a>
                    </div>
                </div>

                <div class="menu-item-group">
                    <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center {{ request()->is('user/chatbot*') ? 'open' : '' }}" onclick="toggleSubMenu(event,'chatbotSubMenu')">
                        <span><i class="fab fa-facebook-messenger"></i> চ্যাটবট সার্ভিস</span>
                        <i class="fa-solid fa-chevron-down mt-1" style="font-size:12px;"></i>
                    </a>
                    <div id="chatbotSubMenu" class="submenu-container" style="{{ request()->is('user/chatbot*') ? 'display:block;' : 'display:none;' }}">
                        <a href="{{ route('user.chatbot.settings') }}"          class="menu-link ps-5"><i class="fa-solid fa-gauge"></i> ড্যাশবোর্ড</a>
                        <a href="{{ route('user.chatbot.connect.page') }}"      class="menu-link ps-5"><i class="fa-solid fa-link"></i> পেজ কানেক্ট</a>
                        <a href="{{ route('user.chatbot.reply.setup') }}"       class="menu-link ps-5"><i class="fa-solid fa-reply"></i> অটো রিপ্লাই</a>
                        <a href="{{ route('user.chatbot.comment.automation') }}" class="menu-link ps-5"><i class="fa-solid fa-comments"></i> কমেন্ট অটোমেশন</a>
                        <a href="{{ route('user.chatbot.pricing') }}"           class="menu-link ps-5"><i class="fa-solid fa-crown"></i> প্যাকেজ কিনুন</a>
                    </div>
                </div>

                <a href="{{ route('user.licenses') }}" class="menu-link active"><i class="fa-solid fa-key"></i> আমার লাইসেন্স</a>
                @include('user.partials.digital-download-menu')
                <a href="{{ route('user.support') }}"  class="menu-link"><i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট</a>
                <a href="{{ route('user.profile') }}"  class="menu-link"><i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস</a>
                <a href="{{ route('user.logout') }}"   class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
            </div>
        </div>
    </div>

    {{-- ── Main Content ── --}}
    <div class="col-lg-9">
        <div class="main-content-card">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">আমার লাইসেন্স সমূহ</h5>
                    <p class="text-muted mb-0" style="font-size:13px;">
                        মোট <strong>{{ $licenses->total() }}</strong>টি লাইসেন্স পাওয়া গেছে
                    </p>
                </div>
                <a href="{{ route('user.license.generate.show') }}"
                   class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                   style="border-radius:10px; padding:9px 18px; font-weight:600;">
                    <i class="fa-solid fa-plus"></i> নতুন জেনারেট
                </a>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3 mb-4 py-2 px-3" style="font-size:14px;">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-4 py-2 px-3" style="font-size:14px;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- ── Search & Filter Bar ── --}}
            <form method="GET" action="{{ route('user.licenses') }}" id="searchForm">
                <div class="search-wrap">
                    <div class="search-input-group">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" class="search-input"
                               placeholder="লাইসেন্স কি বা ডোমেইন দিয়ে সার্চ করুন..."
                               value="{{ request('search') }}">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="all"   {{ request('status','all') == 'all'  ? 'selected' : '' }}>সব স্ট্যাটাস</option>
                        <option value="1"     {{ request('status') == '1'          ? 'selected' : '' }}>সচল</option>
                        <option value="0"     {{ request('status') == '0'          ? 'selected' : '' }}>অকেজো</option>
                    </select>
                    <button type="submit" class="btn-search">
                        <i class="fa-solid fa-search me-1"></i> সার্চ
                    </button>
                    @if(request('search') || (request('status') && request('status') !== 'all'))
                        <a href="{{ route('user.licenses') }}" class="btn-clear">
                            <i class="fa-solid fa-xmark me-1"></i> ক্লিয়ার
                        </a>
                    @endif
                </div>
            </form>

            {{-- ── Table ── --}}
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>লাইসেন্স কি</th>
                            <th>ডোমেইন</th>
                            <th>মেয়াদ শেষ</th>
                            <th>স্ট্যাটাস</th>
                            <th style="text-align:right;">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($licenses as $i => $license)
                        <tr id="row-{{ $license->id }}">
                            <td class="text-muted" style="font-size:13px;">
                                {{ ($licenses->currentPage() - 1) * $licenses->perPage() + $i + 1 }}
                            </td>

                            {{-- License Key --}}
                            <td>
                                <div class="license-key-wrap">
                                    <span class="license-key-text" id="key-{{ $license->id }}">{{ $license->license_key }}</span>
                                    <button class="btn-copy" onclick="copyKey('{{ $license->license_key }}','{{ $license->id }}')" title="কপি করুন">
                                        <i class="fa-regular fa-copy" id="copy-icon-{{ $license->id }}"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Domain --}}
                            <td>
                                <div class="domain-cell" id="domain-display-{{ $license->id }}">
                                    <span>{{ $license->domain_name ?? 'N/A' }}</span>
                                    <a href="http://{{ $license->domain_name }}" target="_blank" title="ভিজিট করুন">
                                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i>
                                    </a>
                                </div>
                            </td>

                            {{-- Expiry --}}
                            <td>
                                @if($license->expiry_date === null)
                                    <span class="badge-lifetime">
                                        <i class="fa-solid fa-infinity me-1"></i> লাইফটাইম
                                    </span>
                                    <br>
                                    <small class="text-muted" style="font-size:11px;">মেয়াদ শেষ হবে না</small>
                                @else
                                    @php $expiry = \Carbon\Carbon::parse($license->expiry_date); @endphp
                                    <span style="font-size:13px;">{{ $expiry->format('d M, Y') }}</span>
                                    <br>
                                    <small class="{{ $expiry->isPast() ? 'text-danger' : 'text-muted' }}" style="font-size:11px;">
                                        {{ $expiry->isPast() ? 'মেয়াদ শেষ ' : 'বাকি ' }}{{ $expiry->diffForHumans() }}
                                    </small>
                                @endif
                            </td>

                            {{-- Status Toggle --}}
                            <td>
                                <button class="{{ $license->status == 1 ? 'badge-active' : 'badge-expired' }}"
                                        id="status-btn-{{ $license->id }}"
                                        onclick="toggleStatus({{ $license->id }})"
                                        title="ক্লিক করে স্ট্যাটাস পরিবর্তন করুন">
                                    <i class="fa-solid {{ $license->status == 1 ? 'fa-circle-check' : 'fa-circle-xmark' }} me-1"
                                       id="status-icon-{{ $license->id }}"></i>
                                    <span id="status-text-{{ $license->id }}">
                                        {{ $license->status == 1 ? 'সচল' : 'অকেজো' }}
                                    </span>
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td style="text-align:right;">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    {{-- রিনিউ --}}
                                    @if($license->status != 1)
                                        <a href="{{ route('user.license.renew', $license->id) }}"
                                           class="action-btn btn-renew-action" title="রিনিউ করুন">
                                            <i class="fa-solid fa-rotate-right me-1"></i> রিনিউ
                                        </a>
                                    @endif

                                    {{-- এডিট --}}
                                    <button class="action-btn btn-edit-action"
                                            onclick="openEditModal({{ $license->id }}, '{{ addslashes($license->domain_name) }}')"
                                            title="ডোমেইন এডিট করুন">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    {{-- ডিলিট --}}
                                    <button class="action-btn btn-del-action"
                                            onclick="openDeleteModal({{ $license->id }}, '{{ addslashes($license->domain_name) }}')"
                                            title="লাইসেন্স মুছুন">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                @if(request('search') || request('status'))
                                    <i class="fa-solid fa-magnifying-glass mb-2 d-block" style="font-size:28px; color:#cbd5e1;"></i>
                                    সার্চের সাথে মিলে এমন কোনো লাইসেন্স পাওয়া যায়নি।
                                    <br><a href="{{ route('user.licenses') }}" style="font-size:13px;">সব দেখুন</a>
                                @else
                                    <i class="fa-solid fa-key mb-2 d-block" style="font-size:28px; color:#cbd5e1;"></i>
                                    আপনার কোনো লাইসেন্স নেই।
                                    <br><a href="{{ route('user.license.generate.show') }}" style="font-size:13px;">এখনই জেনারেট করুন</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Pagination ── --}}
            @if($licenses->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav><ul class="pagination mb-0">
                    @if($licenses->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link border-0 rounded-circle me-1 shadow-none text-muted"
                                  style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;">
                                <i class="fa-solid fa-chevron-left" style="font-size:12px;"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link border-0 rounded-circle me-1 shadow-sm text-dark bg-white"
                               href="{{ $licenses->previousPageUrl() }}" rel="prev"
                               style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-chevron-left" style="font-size:12px;"></i>
                            </a>
                        </li>
                    @endif
                    @foreach(range(1, $licenses->lastPage()) as $p)
                        @if($p >= $licenses->currentPage()-2 && $p <= $licenses->currentPage()+2)
                            @if($p == $licenses->currentPage())
                                <li class="page-item active">
                                    <span class="page-link border-0 rounded-circle mx-1 shadow bg-primary text-white fw-bold"
                                          style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;">{{ $p }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link border-0 rounded-circle mx-1 shadow-sm text-dark bg-white"
                                       href="{{ $licenses->url($p) }}"
                                       style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;">{{ $p }}</a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                    @if($licenses->hasMorePages())
                        <li class="page-item">
                            <a class="page-link border-0 rounded-circle ms-1 shadow-sm text-dark bg-white"
                               href="{{ $licenses->nextPageUrl() }}" rel="next"
                               style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-chevron-right" style="font-size:12px;"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link border-0 rounded-circle ms-1 shadow-none text-muted"
                                  style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;">
                                <i class="fa-solid fa-chevron-right" style="font-size:12px;"></i>
                            </span>
                        </li>
                    @endif
                </ul></nav>
            </div>
            @endif

        </div>
    </div>

</div>
</div>
</div>

{{-- ══════════════════════════════════════════
     Edit Domain Modal
══════════════════════════════════════════ --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <p class="modal-title"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>ডোমেইন এডিট করুন</p>
        <p class="modal-sub">নিচে নতুন ডোমেইন নাম দিন এবং সেভ করুন।</p>

        <label class="modal-label">নতুন ডোমেইন নাম</label>
        <input type="text" id="editDomainInput" class="modal-input" placeholder="example.com">
        <small class="text-danger d-block mt-1" id="editDomainError" style="display:none!important;"></small>

        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeEditModal()">বাতিল</button>
            <button class="btn-modal-save" id="saveEditBtn" onclick="saveEdit()">
                <i class="fa-solid fa-floppy-disk me-1"></i> সেভ করুন
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     Delete Confirm Modal
══════════════════════════════════════════ --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <p class="modal-title"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>লাইসেন্স মুছবেন?</p>
        <p class="modal-sub">একবার মুছলে আর ফেরত পাওয়া যাবে না।</p>
        <div style="background:#fff1f2;border-radius:10px;padding:14px 16px;font-size:14px;color:#be123c;">
            <i class="fa-solid fa-globe me-2"></i>
            <strong id="deleteDomainName"></strong>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeDeleteModal()">বাতিল</button>
            <button class="btn-modal-delete" id="confirmDeleteBtn" onclick="confirmDelete()">
                <i class="fa-solid fa-trash me-1"></i> হ্যাঁ, মুছুন
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="toast-msg" id="toastMsg">
    <i class="fa-solid fa-circle-check toast-icon"></i>
    <span id="toastText"></span>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let currentEditId   = null;
let currentDeleteId = null;

/* ── SubMenu ── */
function toggleSubMenu(event, menuId) {
    event.preventDefault();
    const menu = document.getElementById(menuId);
    const link = event.currentTarget;
    if (menu.style.display === 'none') { menu.style.display = 'block'; link.classList.add('open'); }
    else                               { menu.style.display = 'none';  link.classList.remove('open'); }
}

/* ── Toast ── */
function showToast(msg, type = 'success') {
    const t = document.getElementById('toastMsg');
    const icon = t.querySelector('.toast-icon');
    document.getElementById('toastText').textContent = msg;
    t.className = 'toast-msg show ' + type;
    icon.className = 'fa-solid toast-icon ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark');
    setTimeout(() => t.classList.remove('show'), 3500);
}

/* ── Copy key ── */
function copyKey(key, id) {
    navigator.clipboard.writeText(key).then(() => {
        const icon = document.getElementById('copy-icon-' + id);
        icon.className = 'fa-solid fa-check';
        icon.style.color = '#22c55e';
        setTimeout(() => { icon.className = 'fa-regular fa-copy'; icon.style.color = ''; }, 2000);
        showToast('লাইসেন্স কি কপি হয়েছে!');
    });
}

/* ── Status Toggle ── */
function toggleStatus(id) {
    const btn = document.getElementById('status-btn-' + id);
    btn.disabled = true;

    fetch('{{ url("/user/license/toggle-status") }}/' + id, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const isActive = data.new_status == 1;
            btn.className    = isActive ? 'badge-active' : 'badge-expired';
            document.getElementById('status-icon-' + id).className = 'fa-solid me-1 ' + (isActive ? 'fa-circle-check' : 'fa-circle-xmark');
            document.getElementById('status-text-' + id).textContent = isActive ? 'সচল' : 'অকেজো';
            showToast(data.message, 'success');
        } else {
            showToast('স্ট্যাটাস পরিবর্তন ব্যর্থ।', 'error');
        }
    })
    .catch(() => showToast('সার্ভার এরর।', 'error'))
    .finally(() => { btn.disabled = false; });
}

/* ── Edit Modal ── */
function openEditModal(id, domain) {
    currentEditId = id;
    document.getElementById('editDomainInput').value = domain;
    document.getElementById('editDomainError').style.display = 'none';
    document.getElementById('editModal').classList.add('show');
    setTimeout(() => document.getElementById('editDomainInput').focus(), 100);
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    currentEditId = null;
}
function saveEdit() {
    const domain  = document.getElementById('editDomainInput').value.trim();
    const errEl   = document.getElementById('editDomainError');
    const saveBtn = document.getElementById('saveEditBtn');

    if (!domain) { errEl.textContent = 'ডোমেইন নাম আবশ্যক।'; errEl.style.display = 'block'; return; }
    errEl.style.display = 'none';
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> সেভ হচ্ছে...';

    fetch('{{ url("/user/license/update") }}/' + currentEditId, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ domain_name: domain })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            // টেবিলের সেলে নতুন ডোমেইন আপডেট করা
            const cell = document.getElementById('domain-display-' + currentEditId);
            if (cell) {
                cell.querySelector('span').textContent = domain;
                const link = cell.querySelector('a');
                if (link) link.href = 'http://' + domain;
            }
            closeEditModal();
            showToast(data.message, 'success');
        } else {
            errEl.textContent = data.errors?.domain_name?.[0] ?? data.message ?? 'আপডেট ব্যর্থ।';
            errEl.style.display = 'block';
        }
    })
    .catch(() => { errEl.textContent = 'সার্ভার এরর।'; errEl.style.display = 'block'; })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> সেভ করুন';
    });
}

/* ── Delete Modal ── */
function openDeleteModal(id, domain) {
    currentDeleteId = id;
    document.getElementById('deleteDomainName').textContent = domain;
    document.getElementById('deleteModal').classList.add('show');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    currentDeleteId = null;
}
function confirmDelete() {
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> মুছছে...';

    fetch('{{ url("/user/license/delete") }}/' + currentDeleteId, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const row = document.getElementById('row-' + currentDeleteId);
            if (row) { row.style.opacity = '0'; row.style.transition = '.3s'; setTimeout(() => row.remove(), 300); }
            closeDeleteModal();
            showToast(data.message, 'success');
        } else {
            showToast('মুছতে ব্যর্থ।', 'error');
        }
    })
    .catch(() => showToast('সার্ভার এরর।', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-trash me-1"></i> হ্যাঁ, মুছুন';
    });
}

/* Close modals on backdrop click */
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

/* Close modals on Escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeEditModal(); closeDeleteModal(); }
});
</script>

@endsection
