@extends('layouts.admin')

@section('styles')
<style>
/* ==========================================================================
   JUKTI TECH — STATE-OF-THE-ART MODERN SAAS ADMIN DASHBOARD
   ========================================================================== */
.content-area.admin-dashboard {
    --adm-bg: #f8fafc;
    --adm-card: #ffffff;
    --adm-text: #0f172a;
    --adm-muted: #64748b;
    --adm-border: #e2e8f0;
    --adm-primary: #ff5722;
    --adm-primary-hover: #ea580c;
    --adm-shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
    --adm-shadow-md: 0 4px 20px -2px rgba(15, 23, 42, 0.06);

    background: var(--adm-bg) !important;
    padding: 26px !important;
    min-height: calc(100vh - 66px);
    font-family: 'Hind Siliguri', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

.admin-dashboard * {
    box-sizing: border-box;
}

/* 1. Hero Greeting Banner (Modern Light Theme) */
.dash-modern-hero {
    background: linear-gradient(135deg, #ffffff 0%, #fff7ed 60%, #ffedd5 100%);
    border-radius: 20px;
    padding: 30px 34px;
    color: #0f172a;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 28px -5px rgba(249, 115, 22, 0.12);
    margin-bottom: 24px;
    border: 1px solid #fed7aa;
}
.dash-modern-hero::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(255, 87, 34, 0.12) 0%, rgba(255, 87, 34, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.dash-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 14px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid #fed7aa;
    color: #ea580c;
    font-size: 0.78rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.dash-hero-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}
.dash-hero-title {
    font-size: 1.65rem;
    font-weight: 800;
    margin: 0 0 8px;
    color: #0f172a;
    letter-spacing: -0.02em;
}
.dash-hero-title span {
    color: #ea580c;
}
.dash-hero-sub {
    font-size: 0.94rem;
    color: #475569;
    margin: 0;
    max-width: 620px;
    line-height: 1.55;
}
.dash-hero-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.dash-btn-hero-primary {
    background: linear-gradient(135deg, #ff5722, #ea580c);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    box-shadow: 0 4px 14px rgba(255, 87, 34, 0.35);
    transition: all 0.2s ease;
}
.dash-btn-hero-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.45);
    color: #ffffff !important;
}
.dash-btn-hero-secondary {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #1e293b !important;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}
.dash-btn-hero-secondary:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    transform: translateY(-2px);
    color: #0f172a !important;
}

/* 2. Key Metric Stat Cards */
.dash-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}
.dash-metric-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: var(--adm-shadow-sm);
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.dash-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--adm-shadow-md);
    border-color: #cbd5e1;
    text-decoration: none !important;
}
.dash-metric-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.dash-metric-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.dash-metric--orange .dash-metric-icon-box {
    background: #fff7ed;
    color: #ff5722;
    border: 1px solid #ffedd5;
}
.dash-metric--blue .dash-metric-icon-box {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #dbeafe;
}
.dash-metric--purple .dash-metric-icon-box {
    background: #faf5ff;
    color: #9333ea;
    border: 1px solid #f3e8ff;
}
.dash-metric--emerald .dash-metric-icon-box {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #d1fae5;
}
.dash-metric-badge {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #475569;
}
.dash-metric-number {
    font-size: 2.1rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 4px;
    letter-spacing: -0.02em;
}
.dash-metric-label {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
}
.dash-metric-sub {
    font-size: 0.78rem;
    color: var(--adm-muted);
    margin: 0;
}

/* 3. Quick Action Shortcuts */
.dash-section-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 18px;
    padding: 24px;
    box-shadow: var(--adm-shadow-sm);
    margin-bottom: 24px;
}
.dash-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.dash-section-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dash-shortcuts-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.dash-shortcut-item {
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.dash-shortcut-item:hover {
    background: #ffffff;
    border-color: #ff5722;
    box-shadow: 0 6px 18px rgba(255, 87, 34, 0.08);
    transform: translateY(-2px);
    text-decoration: none !important;
}
.dash-shortcut-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    color: #ff5722;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.dash-shortcut-item:hover .dash-shortcut-icon {
    background: #ff5722;
    color: #ffffff;
    border-color: #ff5722;
}
.dash-shortcut-text h5 {
    margin: 0 0 2px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #0f172a;
}
.dash-shortcut-text p {
    margin: 0;
    font-size: 0.76rem;
    color: var(--adm-muted);
}

/* 4. Two Column Layout */
.dash-two-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
.dash-data-table {
    width: 100%;
    margin: 0;
}
.dash-data-table th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 12px 16px;
    border-top: 1px solid var(--adm-border);
    border-bottom: 1px solid var(--adm-border);
}
.dash-data-table td {
    padding: 13px 16px;
    font-size: 0.85rem;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.dash-data-table tr:hover td {
    background: #f8fafc;
}
.dash-item-thumb {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

/* Responsive */
@media (max-width: 1200px) {
    .dash-stat-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-shortcuts-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-two-cols { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .content-area.admin-dashboard { padding: 16px !important; }
    .dash-stat-grid { grid-template-columns: 1fr; }
    .dash-shortcuts-grid { grid-template-columns: 1fr; }
    .dash-modern-hero { padding: 22px 20px; }
}
</style>
@endsection

@section('content')
@php
    $user = Auth::guard('admin')->user();
    $s = $stats;
    
    // Greeting by time of day
    $hour = now()->hour;
    if ($hour < 12) {
        $greeting = 'শুভ সকাল';
    } elseif ($hour < 16) {
        $greeting = 'শুভ দুপুর';
    } elseif ($hour < 19) {
        $greeting = 'শুভ অপরাহ্ন';
    } else {
        $greeting = 'শুভ সন্ধ্যা';
    }
@endphp

<div class="content-area admin-dashboard">

    {{-- 1. Modern Hero Greeting Banner --}}
    <div class="dash-modern-hero">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="dash-hero-pill">
                    <span class="dash-hero-dot"></span>
                    <span>সিস্টেম সম্পূর্ণ সুরক্ষিত ও অনলাইন · Jukti Tech Control Hub</span>
                </div>
                <h1 class="dash-hero-title">{{ $greeting }}, <span>{{ $user->name }}</span>!</h1>
                <p class="dash-hero-sub">
                    আপনার ডিজিটাল এজেন্সি প্ল্যাটফর্ম, লাইভ ওয়েবসাইট কালেকশন এবং গ্রাহকদের যাবতীয় অনুসন্ধান এখান থেকে সহজে পরিচালনা করুন।
                </p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="dash-hero-actions justify-content-lg-end">
                    <a href="{{ route('admin.products.create') }}" class="dash-btn-hero-primary">
                        <i class="fas fa-plus-circle"></i>
                        <span>নতুন ওয়েবসাইট আপলোড</span>
                    </a>
                    <a href="{{ route('frontend.index') }}" target="_blank" class="dash-btn-hero-secondary">
                        <i class="fas fa-globe"></i>
                        <span>ওয়েবসাইট লাইভ দেখুন ↗</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Four Key Modern Metric Stat Cards --}}
    <div class="dash-stat-grid">
        {{-- Stat 1: Websites Collection --}}
        <a href="{{ route('admin.products.index') }}" class="dash-metric-card dash-metric--orange">
            <div class="dash-metric-top">
                <div class="dash-metric-icon-box">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <span class="dash-metric-badge">রেডিমেড স্ক্রিপ্ট</span>
            </div>
            <div>
                <div class="dash-metric-number">{{ number_format($s['themes'] ?? 0) }}</div>
                <div class="dash-metric-label">ওয়েবসাইট কালেকশন</div>
                <p class="dash-metric-sub">হোমপেজ ও ক্যাটালগে সক্রিয় থিমসমূহ</p>
            </div>
        </a>

        {{-- Stat 2: Our Services --}}
        <a href="{{ route('admin.service.index') }}" class="dash-metric-card dash-metric--blue">
            <div class="dash-metric-top">
                <div class="dash-metric-icon-box">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="dash-metric-badge">এজেন্সি সেবা</span>
            </div>
            <div>
                <div class="dash-metric-number">{{ number_format($s['services'] ?? 0) }}</div>
                <div class="dash-metric-label">আমাদের সেবাসমূহ</div>
                <p class="dash-metric-sub">কাস্টম ওয়েব, মোবাইল ও আইটি সার্ভিস</p>
            </div>
        </a>

        {{-- Stat 3: Portfolio Projects --}}
        <a href="{{ route('admin.portfolio.index') }}" class="dash-metric-card dash-metric--purple">
            <div class="dash-metric-top">
                <div class="dash-metric-icon-box">
                    <i class="fas fa-briefcase"></i>
                </div>
                <span class="dash-metric-badge">প্রজেক্ট শোকেস</span>
            </div>
            <div>
                <div class="dash-metric-number">{{ number_format($s['portfolios'] ?? 0) }}</div>
                <div class="dash-metric-label">পোর্টফোলিও প্রজেক্ট</div>
                <p class="dash-metric-sub">সফল ক্লায়েন্ট প্রজেক্ট শোকেস</p>
            </div>
        </a>

        {{-- Stat 4: Contact Inquiries --}}
        <a href="{{ route('admin.contact.index') }}" class="dash-metric-card dash-metric--emerald">
            <div class="dash-metric-top">
                <div class="dash-metric-icon-box">
                    <i class="fas fa-envelope"></i>
                </div>
                <span class="dash-metric-badge">গ্রাহক যোগাযোগ</span>
            </div>
            <div>
                <div class="dash-metric-number">{{ number_format($s['contacts'] ?? 0) }}</div>
                <div class="dash-metric-label">ইনকোয়ারি ও মেসেজ</div>
                <p class="dash-metric-sub">গ্রাহকদের সরাসরি মেসেজ বক্স</p>
            </div>
        </a>
    </div>

    {{-- 3. Quick Action Shortcuts --}}
    <div class="dash-section-card">
        <div class="dash-section-head">
            <h3 class="dash-section-title">
                <i class="fas fa-bolt text-warning"></i>
                <span>দ্রুত শর্টকাট ও অ্যাকশন সেন্টার</span>
            </h3>
            <span class="badge bg-light text-dark border px-3 py-1 rounded-pill small">১-ক্লিক অ্যাকশন</span>
        </div>
        <div class="dash-shortcuts-grid">
            <a href="{{ route('admin.products.create') }}" class="dash-shortcut-item">
                <div class="dash-shortcut-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="dash-shortcut-text">
                    <h5>নতুন ওয়েবসাইট যুক্ত</h5>
                    <p>লাইভ ডেমো ও ৩-টিয়ার সহ</p>
                </div>
            </a>

            <a href="{{ route('admin.service.create') }}" class="dash-shortcut-item">
                <div class="dash-shortcut-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="dash-shortcut-text">
                    <h5>নতুন সার্ভিস যোগ</h5>
                    <p>এজেন্সি সেবা আপডেট করুন</p>
                </div>
            </a>

            <a href="{{ route('admin.about.edit') }}" class="dash-shortcut-item">
                <div class="dash-shortcut-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <div class="dash-shortcut-text">
                    <h5>হোমপেজ হিরো কনটেন্ট</h5>
                    <p>শিরোনাম ও মকআপ পরিবর্তন</p>
                </div>
            </a>

            <a href="{{ route('admin.generalsettings.websiteContent') }}" class="dash-shortcut-item">
                <div class="dash-shortcut-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="dash-shortcut-text">
                    <h5>জেনারেল সেটিংস</h5>
                    <p>ফোন, ইমেইল ও সোশ্যাল লিংক</p>
                </div>
            </a>
        </div>
    </div>

    {{-- 4. Two Columns: Recent Inquiries & Recent Websites --}}
    <div class="dash-two-cols">
        {{-- Column 1: Recent Contact Inquiries --}}
        <div class="dash-section-card mb-0">
            <div class="dash-section-head">
                <h3 class="dash-section-title">
                    <i class="fas fa-comments text-primary"></i>
                    <span>সর্বশেষ ইনকোয়ারি ও মেসেজ</span>
                </h3>
                <a href="{{ route('admin.contact.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size: 0.78rem;">
                    সকল মেসেজ দেখুন
                </a>
            </div>

            @php
                $recentContacts = \App\Models\Contact::latest()->take(5)->get();
            @endphp

            @if($recentContacts->count() > 0)
                <div class="table-responsive">
                    <table class="dash-data-table table mb-0">
                        <thead>
                            <tr>
                                <th>নাম ও যোগাযোগ</th>
                                <th>বিষয় / মেসেজ</th>
                                <th>তারিখ</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentContacts as $c)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $c->name }}</div>
                                        <div class="text-muted x-small">{{ $c->email ?: $c->phone }}</div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 220px;" title="{{ $c->subject ?: $c->message }}">
                                            {{ $c->subject ?: Str::limit($c->message, 45) }}
                                        </div>
                                    </td>
                                    <td class="text-muted x-small">
                                        {{ $c->created_at ? $c->created_at->diffForHumans() : 'সম্প্রতি' }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.contact.index') }}" class="btn btn-sm btn-light border rounded-pill px-2 py-1" style="font-size: 0.74rem;" title="মেসেজ দেখুন">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="far fa-envelope-open fa-2x mb-2 text-muted opacity-50"></i>
                    <p class="mb-0 small">বর্তমানে কোনো নতুন মেসেজ নেই।</p>
                </div>
            @endif
        </div>

        {{-- Column 2: Recent Websites in Collection --}}
        <div class="dash-section-card mb-0">
            <div class="dash-section-head">
                <h3 class="dash-section-title">
                    <i class="fas fa-laptop-code text-warning"></i>
                    <span>লাইভ ওয়েবসাইট কালেকশন</span>
                </h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size: 0.78rem;">
                    সকল ওয়েবসাইট
                </a>
            </div>

            @php
                $recentProducts = \App\Models\Product::latest()->take(5)->get();
            @endphp

            @if($recentProducts->count() > 0)
                <div class="table-responsive">
                    <table class="dash-data-table table mb-0">
                        <thead>
                            <tr>
                                <th>ছবি ও ওয়েবসাইট</th>
                                <th>মূল্য</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $prod)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset_image('assets/images/products/'.$prod->photo) }}" 
                                                 alt="{{ $prod->name }}" 
                                                 class="dash-item-thumb">
                                            <div>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 190px;" title="{{ $prod->name }}">
                                                    {{ $prod->name }}
                                                </div>
                                                <div class="text-muted x-small">{{ $prod->category_name ?: 'রেডিমেড সফটওয়্যার' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">৳ {{ number_format($prod->price, 0) }}</span>
                                    </td>
                                    <td>
                                        @if($prod->status == 1)
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill" style="font-size: 0.7rem;">সক্রিয়</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.7rem;">নিষ্ক্রিয়</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-sm btn-light border rounded-pill px-2 py-1" style="font-size: 0.74rem;" title="এডিট করুন">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <a href="{{ route('frontend.product.details', $prod->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-2 py-1" style="font-size: 0.74rem;" title="লাইভ প্রিভিউ দেখুন">
                                            <i class="fas fa-arrow-up-right-from-square text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-box-open fa-2x mb-2 text-muted opacity-50"></i>
                    <p class="mb-0 small">বর্তমানে কোনো ওয়েবসাইট পাওয়া যায়নি।</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
