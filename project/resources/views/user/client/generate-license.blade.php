@extends('layouts.front')
@section('title', 'নতুন লাইসেন্স জেনারেট')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; background: #fff; border-bottom: 1px solid #f1f5f9; }
    .user-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px; }
    .user-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #4e73df; background-color: #eee; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; font-size: 15px; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .menu-link.active i { color: #4e73df; }
    .menu-link.text-danger:hover { background: #fff1f2; color: #e11d48; }

    .main-content-card { background: #fff; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }

    .lic-form-label { font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 8px; display: block; }
    .lic-input { width: 100%; height: 48px; padding: 0 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; transition: 0.3s; background: #f8fafc; }
    .lic-input:focus { outline: none; border-color: #4e73df; background: #fff; box-shadow: 0 0 0 3px rgba(78,115,223,0.1); }

    .validity-group { display: flex; gap: 12px; flex-wrap: wrap; }
    .validity-card { flex: 1; min-width: 100px; border: 2px solid #e2e8f0; border-radius: 14px; padding: 16px 12px; text-align: center; cursor: pointer; transition: 0.3s; position: relative; }
    .validity-card:hover { border-color: #4e73df; background: #f0f4ff; }
    .validity-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .validity-card.selected { border-color: #4e73df; background: #eff2ff; }
    .validity-card.lifetime-card { border-color: #d8b4fe; background: #fdf4ff; }
    .validity-card.lifetime-card:hover { border-color: #7e22ce; background: #f5f0ff; }
    .validity-card.lifetime-card.selected { border-color: #7e22ce; background: #f3e8ff; }
    .validity-card .years { font-size: 24px; font-weight: 800; color: #4e73df; line-height: 1; }
    .validity-card.lifetime-card .years { color: #7e22ce; font-size: 28px; }
    .validity-card .label { font-size: 13px; color: #64748b; margin-top: 4px; }
    .validity-card .price { font-size: 13px; font-weight: 700; color: #059669; margin-top: 6px; background: #d1fae5; padding: 3px 8px; border-radius: 20px; display: inline-block; }
    .validity-card.lifetime-card .price { background: #ede9fe; color: #7e22ce; }

    /* Site Path */
    .site-path-input { width: 100%; padding: 12px 16px; border: 1.5px dashed #a5b4fc; border-radius: 12px; font-size: 13px; font-family: monospace; background: #fafaff; transition: 0.3s; }
    .site-path-input:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

    .free-badge { background: linear-gradient(135deg, #059669, #10b981); color: #fff; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .paid-badge { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }

    .btn-generate { background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; border: none; padding: 14px 32px; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; width: 100%; }
    .btn-generate:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(78,115,223,0.35); }

    .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
    .info-box.warning { background: #fffbeb; border-color: #fde68a; }
    .divider { height: 1px; background: #f1f5f9; margin: 24px 0; }

    .total-price-display { background: #f8fafc; border: 1.5px dashed #4e73df; border-radius: 12px; padding: 16px 20px; text-align: center; }
    .total-price-display .amount { font-size: 28px; font-weight: 800; color: #4e73df; }
    .total-price-display .label { font-size: 13px; color: #94a3b8; }

    .submenu-container { background: #fdfdfd; border-radius: 12px; margin: 2px 15px; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }
    .menu-link i.fa-chevron-down { transition: transform 0.3s ease; }
    .menu-link.open i.fa-chevron-down { transform: rotate(180deg); }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">

            {{-- সাইডবার --}}
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
                        <a href="{{ route('user.domains.index') }}" class="menu-link"><i class="fa-solid fa-globe"></i> আমার ডোমেইন</a>
                        <a href="{{ route('user.hostings.index') }}" class="menu-link"><i class="fa-solid fa-server"></i> আমার হোস্টিং</a>
                        <a href="{{ route('user.chatbot.products.index') }}" class="menu-link"><i class="fa-solid fa-box"></i> প্রোডাক্ট</a>
<div class="menu-item-group">
                            <a href="javascript:void(0);" class="menu-link d-flex justify-content-between align-items-center" onclick="toggleSubMenu(event, 'smsSubMenu')">
                                <span><i class="fa-solid fa-comment-sms"></i> এসএমএস সার্ভিস</span>
                                <i class="fa-solid fa-chevron-down mt-1" style="font-size: 12px;"></i>
                            </a>
                            <div id="smsSubMenu" class="submenu-container" style="display: none;">
                                <a href="{{ route('user.sms.panel') }}" class="menu-link ps-5"><i class="fa-solid fa-paper-plane"></i> এসএমএস পাঠান</a>
                                <a href="{{ route('user.sms.history') }}" class="menu-link ps-5"><i class="fa-solid fa-history"></i> এসএমএস হিস্ট্রি</a>
                                <a href="{{ route('user.recharge.index') }}" class="menu-link ps-5"><i class="fa-solid fa-wallet"></i> রিচার্জ করুন</a>
                                <a href="{{ route('user.sms.api_docs') }}" class="menu-link ps-5"><i class="fa-solid fa-code"></i> এপিআই ডকুমেন্টেশন</a>
                            </div>
                        </div>

                        <a href="{{ route('user.licenses') }}" class="menu-link active"><i class="fa-solid fa-key"></i> আমার লাইসেন্স</a>
                        @include('user.partials.digital-download-menu')
                        <a href="{{ route('user.support') }}" class="menu-link"><i class="fa-solid fa-headset"></i> সাপোর্ট টিকেট</a>
                        <a href="{{ route('user.profile') }}" class="menu-link"><i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস</a>
                        <a href="{{ route('user.logout') }}" class="menu-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                    </div>
                </div>
            </div>

            {{-- মেইন কন্টেন্ট --}}
            <div class="col-lg-9">
                <div class="main-content-card">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">নতুন লাইসেন্স জেনারেট করুন</h5>
                            <p class="text-muted mb-0" style="font-size: 14px;">আপনার ডোমেইনের জন্য একটি নতুন লাইসেন্স তৈরি করুন</p>
                        </div>
                        @if($isFree)
                            <span class="free-badge"><i class="fa-solid fa-gift"></i> বিনামূল্যে</span>
                        @else
                            <span class="paid-badge"><i class="fa-solid fa-credit-card"></i> পেইড</span>
                        @endif
                    </div>

                    {{-- Alert Messages --}}
                    @if(session('error'))
                        <div class="alert alert-danger rounded-3 mb-4">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Info Box --}}
                    @if($isFree)
                        <div class="info-box mb-4">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fa-solid fa-circle-check text-success mt-1" style="font-size: 18px;"></i>
                                <div>
                                    <p class="fw-bold mb-1 text-success">বিনামূল্যে লাইসেন্স জেনারেশন সক্রিয়</p>
                                    <p class="mb-0 text-muted" style="font-size: 13px;">অ্যাডমিন আপনাকে বিনামূল্যে লাইসেন্স জেনারেট করার অনুমতি দিয়েছেন। ফর্ম পূরণ করে সাবমিট করলে সাথে সাথে লাইসেন্স তৈরি হবে।</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="info-box warning mb-4">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fa-solid fa-circle-info text-warning mt-1" style="font-size: 18px;"></i>
                                <div>
                                    <p class="fw-bold mb-1">পেমেন্ট প্রয়োজন</p>
                                    <p class="mb-0 text-muted" style="font-size: 13px;">লাইসেন্স জেনারেট করতে <strong>উদ্যোক্তা পে</strong>-এর মাধ্যমে পেমেন্ট করতে হবে। পেমেন্ট সফল হলে স্বয়ংক্রিয়ভাবে লাইসেন্স তৈরি হবে। পেমেন্ট পেন্ডিং থাকলে অ্যাডমিন ম্যানুয়ালি পেইড করলে লাইসেন্স তৈরি হবে।</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('user.license.generate.submit') }}" method="POST" id="licenseForm">
                        @csrf

                        <div class="mb-4">
                            <label class="lic-form-label">
                                <i class="fa-solid fa-globe text-primary me-1"></i> ডোমেইন নাম
                            </label>
                            <input type="text" name="domain_name" class="lic-input @error('domain_name') border-danger @enderror"
                                placeholder="example.com (http:// ছাড়া)" value="{{ old('domain_name') }}" required>
                            <small class="text-muted mt-1 d-block">যেমন: example.com বা sub.example.com</small>
                        </div>

                        <div class="mb-4">
                            <label class="lic-form-label">
                                <i class="fa-solid fa-folder-open text-warning me-1"></i>
                                সাইটের Root Path
                                <span class="text-muted" style="font-weight:400;font-size:12px;">(ঐচ্ছিক — .env অটো আপডেটের জন্য)</span>
                            </label>
                            <input type="text" name="site_path" class="site-path-input"
                                placeholder="যেমন: C:\xampp\htdocs\mysite  অথবা  /var/www/html/mysite"
                                value="{{ old('site_path') }}">
                            <small class="text-muted mt-1 d-block">
                                <i class="fa-solid fa-circle-info text-info me-1"></i>
                                সঠিক path দিলে লাইসেন্স তৈরির পর আপনার সাইটের <code>.env</code> ফাইলে <code>LICENSE_KEY</code> ও <code>LICENSE_SIGNATURE</code> স্বয়ংক্রিয়ভাবে সেভ হবে।
                            </small>
                        </div>

                        <div class="divider"></div>

                        <div class="mb-4">
                            <label class="lic-form-label">
                                <i class="fa-solid fa-calendar text-primary me-1"></i> মেয়াদ নির্বাচন করুন
                            </label>
                            <div class="validity-group" id="validityGroup">
                                <label class="validity-card selected" id="v1">
                                    <input type="radio" name="validity" value="1" checked onchange="updatePrice()">
                                    <div class="years">১</div>
                                    <div class="label">বছর</div>
                                    @if(!$isFree)
                                        <div class="price">৳{{ number_format($price, 0) }}</div>
                                    @else
                                        <div class="price" style="background:#d1fae5;color:#059669;">বিনামূল্যে</div>
                                    @endif
                                </label>
                                <label class="validity-card" id="v2">
                                    <input type="radio" name="validity" value="2" onchange="updatePrice()">
                                    <div class="years">২</div>
                                    <div class="label">বছর</div>
                                    @if(!$isFree)
                                        <div class="price">৳{{ number_format($price * 2, 0) }}</div>
                                    @else
                                        <div class="price" style="background:#d1fae5;color:#059669;">বিনামূল্যে</div>
                                    @endif
                                </label>
                                <label class="validity-card" id="v3">
                                    <input type="radio" name="validity" value="3" onchange="updatePrice()">
                                    <div class="years">৩</div>
                                    <div class="label">বছর</div>
                                    @if(!$isFree)
                                        <div class="price">৳{{ number_format($price * 3, 0) }}</div>
                                    @else
                                        <div class="price" style="background:#d1fae5;color:#059669;">বিনামূল্যে</div>
                                    @endif
                                </label>
                                <label class="validity-card lifetime-card" id="v0">
                                    <input type="radio" name="validity" value="0" onchange="updatePrice()">
                                    <div class="years">♾</div>
                                    <div class="label" style="font-weight:700;color:#7e22ce;">Lifetime</div>
                                    @if(!$isFree)
                                        <div class="price">৳{{ number_format($lifetimePrice, 0) }}</div>
                                    @else
                                        <div class="price">বিনামূল্যে</div>
                                    @endif
                                </label>
                            </div>
                        </div>

                        @if(!$isFree)
                            <div class="divider"></div>
                            <div class="total-price-display mb-4" id="totalDisplay">
                                <div class="label mb-1">মোট পরিশোধযোগ্য</div>
                                <div class="amount" id="totalAmount">৳{{ number_format($price, 0) }}</div>
                                <div class="label mt-1">উদ্যোক্তা পে-এর মাধ্যমে পেমেন্ট করুন</div>
                            </div>
                        @endif

                        <button type="submit" class="btn-generate" id="submitBtn">
                            @if($isFree)
                                <i class="fa-solid fa-key me-2"></i> বিনামূল্যে লাইসেন্স জেনারেট করুন
                            @else
                                <i class="fa-solid fa-lock me-2"></i> পেমেন্ট করে লাইসেন্স জেনারেট করুন
                            @endif
                        </button>

                        <p class="text-center text-muted mt-3" style="font-size: 12px;">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i>
                            সমস্ত লাইসেন্স নিরাপদভাবে এনক্রিপ্টেড এবং আমাদের সার্ভারে সংরক্ষিত।
                        </p>

                    </form>
                </div>

                {{-- লাইসেন্স তালিকায় ফেরত যাওয়ার লিংক --}}
                <div class="text-center mt-3">
                    <a href="{{ route('user.licenses') }}" class="text-muted" style="font-size: 14px;">
                        <i class="fa-solid fa-arrow-left me-1"></i> লাইসেন্স তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const pricePerYear   = {{ $price }};
const lifetimePrice  = {{ $lifetimePrice }};
const isFree = {{ $isFree ? 'true' : 'false' }};

function updatePrice() {
    const cards = document.querySelectorAll('.validity-card');
    cards.forEach(c => c.classList.remove('selected'));

    const selected = document.querySelector('input[name="validity"]:checked');
    if (!selected) return;

    const validity = parseInt(selected.value);
    const parentCard = selected.closest('.validity-card');
    if (parentCard) parentCard.classList.add('selected');

    if (!isFree) {
        const total = validity === 0 ? lifetimePrice : (pricePerYear * validity);
        const el = document.getElementById('totalAmount');
        if (el) {
            el.textContent = '৳' + total.toLocaleString('bn-BD');
            const lbl = el.nextElementSibling;
            if (lbl) lbl.textContent = validity === 0 ? 'Lifetime লাইসেন্স — একবার পেমেন্ট, সারাজীবন বৈধ' : 'উদ্যোক্তা পে-এর মাধ্যমে পেমেন্ট করুন';
        }
    }
}

// validity card click handler
document.querySelectorAll('.validity-card').forEach(card => {
    card.addEventListener('click', function () {
        const radio = this.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
            updatePrice();
        }
    });
});

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

// Form submit loading state
document.getElementById('licenseForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> প্রসেস হচ্ছে...';
});
</script>

@endsection
