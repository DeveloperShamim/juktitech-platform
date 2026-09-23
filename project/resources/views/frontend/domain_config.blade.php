@extends('layouts.front')

@section('contents')
@php
    $isOwnDomain = (isset($isExistingDomain) && $isExistingDomain) || request()->get('type') == 'existing' || request()->get('domain_type') == 'existing';
    $domainYearly = (float) ($pricingPreview['domain_price_yearly'] ?? 0);
    $hostingYearly = (float) ($pricingPreview['hosting_price_yearly'] ?? 0);
    $hostingMonthly = (float) ($pricingPreview['hosting_price_monthly'] ?? optional($hostingPlan)->price_monthly ?? 0);
    $freeFirstYear = (bool) ($pricingPreview['free_domain_first_year'] ?? false);
    $hasMonthlyPrice = $hostingPlan && $hostingMonthly > 0;
    $defaultPeriod = request('billing_period') === 'monthly' && $hasMonthlyPrice ? 'monthly' : 'yearly';
@endphp
<section class="py-5 bg-light min-vh-100">
    <div class="container py-2 py-md-5">

        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="position-relative">
                    <div class="progress" style="height: 2px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%;"></div>
                    </div>
                    <div class="d-flex justify-content-between position-absolute w-100 top-0 translate-middle-y">
                        <div class="text-center">
                            <div class="step-circle bg-success text-white border-0 shadow-sm"><i class="fas fa-check small"></i></div>
                            <p class="step-label fw-bold d-none d-sm-block">সার্চ</p>
                        </div>
                        <div class="text-center">
                            <div class="step-circle bg-primary text-white border-0 shadow-sm">2</div>
                            <p class="step-label fw-bold d-none d-sm-block">কনফিগারেশন</p>
                        </div>
                        <div class="text-center">
                            <div class="step-circle bg-white border text-muted shadow-sm">3</div>
                            <p class="step-label fw-bold d-none d-sm-block">পেমেন্ট</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-start">

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-user-edit me-2 text-primary"></i>গ্রাহক তথ্য ও পেমেন্ট
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('front.domain.order') }}" method="POST" id="orderForm">
                            @csrf
                            <input type="hidden" name="domain" value="{{ $domain }}">
                            @if($isOwnDomain)
                                <input type="hidden" name="domain_type" value="existing">
                            @endif
                            @if($hostingPlan)
                                <input type="hidden" name="plan_id" value="{{ $hostingPlan->id }}">
                            @endif

                            @if($hostingPlan)
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase d-block mb-2">
                                    <i class="fas fa-sync-alt me-1"></i> হোস্টিং বিলিং প্ল্যান
                                </label>
                                <div class="billing-period-group" id="billingPeriodGroup">
                                    @if($hasMonthlyPrice)
                                    <label class="billing-period-card {{ $defaultPeriod === 'monthly' ? 'selected' : '' }}" data-period="monthly">
                                        <input type="radio" name="billing_period" value="monthly" {{ $defaultPeriod === 'monthly' ? 'checked' : '' }}>
                                        <div class="period-title">মাসিক</div>
                                        <div class="period-price">৳ {{ number_format($hostingMonthly, 0) }}/মাস</div>
                                    </label>
                                    @endif
                                    <label class="billing-period-card {{ $defaultPeriod === 'yearly' ? 'selected' : '' }}" data-period="yearly">
                                        <input type="radio" name="billing_period" value="yearly" {{ $defaultPeriod === 'yearly' ? 'checked' : '' }}>
                                        <div class="period-title">বাৎসরিক</div>
                                        <div class="period-price">৳ {{ number_format($hostingYearly, 0) }}/বছর</div>
                                    </label>
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="billing_period" value="yearly">
                            @endif

                            <div class="mb-4" id="billingYearsWrap">
                                <label class="form-label small fw-bold text-muted text-uppercase d-block mb-2" id="billingYearsTitle">
                                    <i class="fas fa-calendar-alt me-1"></i> কত বছরের জন্য কিনবেন?
                                </label>
                                <div class="billing-years-group" id="billingYearsGroup">
                                    @foreach([1, 2, 3] as $y)
                                    <label class="billing-year-card {{ $y === 1 ? 'selected' : '' }}" data-years="{{ $y }}">
                                        <input type="radio" name="billing_years" value="{{ $y }}" {{ $y === 1 ? 'checked' : '' }}>
                                        <div class="years-num">{{ $y }}</div>
                                        <div class="years-label">বছর</div>
                                    </label>
                                    @endforeach
                                </div>
                                <small class="text-muted" id="billingYearsHint">ডোমেইন ও হোস্টিং উভয়ের মূল্য নির্বাচিত বছর অনুযায়ী হিসাব হবে।</small>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">আপনার পূর্ণ নাম</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="customer_name" class="form-control border-0 bg-light py-2"
                                               value="{{ old('customer_name', optional($user)->name) }}" required placeholder="আপনার নাম লিখুন">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">ফোন নাম্বার</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light"><i class="fas fa-phone-alt text-muted"></i></span>
                                        <input type="text" name="customer_phone" class="form-control border-0 bg-light py-2"
                                               value="{{ old('customer_phone', optional($user)->phone) }}" required placeholder="আপনার ফোন নাম্বার">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted text-uppercase">আপনার ঠিকানা</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light align-items-start pt-2"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                        <textarea name="customer_address" class="form-control border-0 bg-light py-2" rows="2"
                                                  required placeholder="আপনার পূর্ণ ঠিকানা লিখুন">{{ old('customer_address', optional($user)->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold text-dark mb-3">পেমেন্ট মেথড</h6>
                                    <div class="payment-selection-wrapper">
                                        <input type="radio" class="btn-check" name="payment_method" id="creativepay" value="CreativePay" checked required>
                                        <label class="btn btn-outline-light p-3 w-100 payment-card active-card" for="creativepay">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-white rounded p-2 me-3 shadow-sm border border-light d-flex align-items-center justify-content-center" style="width:120px;height:48px;">
                                                        <span class="fw-bold text-primary" style="font-size:14px;">CreativePay</span>
                                                    </div>
                                                    <div class="text-start">
                                                        <b class="d-block text-dark h6 mb-1">অনলাইন পেমেন্ট</b>
                                                        <span class="text-muted small d-block">bKash, Nagad, Rocket পেমেন্ট</span>
                                                    </div>
                                                </div>
                                                <div class="check-icon"><i class="fas fa-check-circle text-success fa-2x"></i></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 d-none d-lg-block text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill fw-bold shadow-sm py-3">
                                    অর্ডার সম্পন্ন করুন <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-sidebar">
                    <div class="card-header bg-dark text-white py-3 border-0">
                        <h5 class="fw-bold mb-0">অর্ডার সামারি</h5>
                    </div>
                    <div class="card-body p-4" id="orderSummary"
                         data-own-domain="{{ $isOwnDomain ? '1' : '0' }}"
                         data-domain-yearly="{{ $domainYearly }}"
                         data-hosting-yearly="{{ $hostingYearly }}"
                         data-hosting-monthly="{{ $hostingMonthly }}"
                         data-free-first-year="{{ $freeFirstYear ? '1' : '0' }}">

                        <div class="text-center mb-4 border-bottom pb-3">
                            <span class="badge bg-soft-orange text-primary rounded-pill px-3 py-2 mb-2">
                                {{ $isOwnDomain ? 'হোস্টিং ডোমেইন' : 'নির্বাচিত ডোমেইন' }}
                            </span>
                            <h5 class="fw-bold text-dark mb-0 text-break">{{ $domain }}</h5>
                            <div class="d-flex justify-content-between mt-3 px-2">
                                <span class="text-muted small" id="domainPeriodLabel">রেজিস্ট্রেশন (১ বছর)</span>
                                <span class="fw-bold text-dark small" id="domainPriceDisplay">৳ 0.00</span>
                            </div>
                        </div>

                        @if($hostingPlan)
                        <div class="text-center mb-4 border-bottom pb-3">
                            <span class="badge bg-light text-success rounded-pill px-3 py-2 mb-2 border">নির্বাচিত হোস্টিং</span>
                            <h5 class="fw-bold text-dark mb-1">{{ $hostingPlan->name }}</h5>
                            <small class="text-muted d-block mb-3">ক্যাটাগরি: {{ $hostingPlan->category }}</small>
                            <div class="d-flex justify-content-between px-2">
                                <span class="text-muted small" id="hostingPeriodLabel">হোস্টিং (১ বছর)</span>
                                <span class="fw-bold text-dark small" id="hostingPriceDisplay">৳ {{ number_format($hostingYearly, 2) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-4 mt-2 px-2">
                            <h5 class="fw-bold text-dark mb-0">সর্বমোট:</h5>
                            <h3 class="fw-bold text-primary mb-0" id="grandTotalDisplay">৳ 0.00</h3>
                        </div>

                        <div class="alert alert-success border-0 rounded-3 mb-4 small py-2 text-center d-none" id="freeDomainAlert">
                            <i class="fas fa-gift me-1"></i> প্রথম বছরের ডোমেইন ফ্রি — অতিরিক্ত বছরের জন্য মূল্য যোগ হবে।
                        </div>

                        <div class="alert alert-info border-0 rounded-3 mb-4 small py-3" id="billingInfoAlert">
                            <i class="fas fa-shield-alt me-1"></i> পেমেন্ট সফল হলে সেবার মেয়াদ নির্বাচিত প্ল্যান অনুযায়ী সেট হবে।
                        </div>

                        <div class="d-lg-none">
                            <button type="submit" form="orderForm" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3 mt-2">
                                অর্ডার ও পেমেন্ট করুন <i class="fas fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    :root { --primary-color: var(--primary-color); --primary-hover: var(--primary-hover); }
    .text-primary { color: var(--primary-color) !important; }
    .bg-soft-orange { background-color: rgba(124, 58, 237, 0.1); }
    .btn-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #fff !important; }
    .btn-primary:hover { background-color: var(--primary-hover) !important; transform: translateY(-3px); }
    .step-circle { width: 32px; height: 32px; line-height: 32px; border-radius: 50%; display: inline-block; font-weight: bold; background: white; z-index: 2; position: relative; }
    .payment-card { cursor: pointer; transition: 0.2s; border: 2px solid #f0f0f0 !important; border-radius: 16px !important; background: #fff; }
    .active-card { border-color: var(--primary-color) !important; background-color: var(--primary-bg-light) !important; }
    @media (min-width: 992px) { .sticky-sidebar { position: sticky; top: 100px; z-index: 10; } }
    .billing-years-group { display: flex; gap: 12px; flex-wrap: wrap; }
    .billing-year-card { flex: 1; min-width: 90px; border: 2px solid #e2e8f0; border-radius: 14px; padding: 14px 10px; text-align: center; cursor: pointer; transition: 0.25s; position: relative; }
    .billing-year-card:hover { border-color: var(--primary-color); background: var(--primary-bg-light); }
    .billing-year-card input { position: absolute; opacity: 0; width: 0; height: 0; }
    .billing-year-card.selected { border-color: var(--primary-color); background: var(--primary-bg-light); }
    .billing-year-card .years-num { font-size: 28px; font-weight: 800; color: var(--primary-color); line-height: 1; }
    .billing-year-card .years-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    .billing-period-group { display: flex; gap: 12px; flex-wrap: wrap; }
    .billing-period-card { flex: 1; min-width: 140px; border: 2px solid #e2e8f0; border-radius: 14px; padding: 14px 12px; text-align: center; cursor: pointer; transition: 0.25s; position: relative; }
    .billing-period-card:hover { border-color: var(--primary-color); background: var(--primary-bg-light); }
    .billing-period-card input { position: absolute; opacity: 0; width: 0; height: 0; }
    .billing-period-card.selected { border-color: var(--primary-color); background: var(--primary-bg-light); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
    .billing-period-card .period-title { font-size: 15px; font-weight: 700; color: #1e293b; }
    .billing-period-card .period-price { font-size: 13px; color: var(--primary-color); font-weight: 600; margin-top: 4px; }
</style>

<script>
(function () {
    const summary = document.getElementById('orderSummary');
    if (!summary) return;
    const isOwnDomain = summary.dataset.ownDomain === '1';
    const domainYearly = parseFloat(summary.dataset.domainYearly) || 0;
    const hostingYearly = parseFloat(summary.dataset.hostingYearly) || 0;
    const hostingMonthly = parseFloat(summary.dataset.hostingMonthly) || 0;
    const freeFirstYear = summary.dataset.freeFirstYear === '1';
    const hasHosting = hostingYearly > 0 || hostingMonthly > 0;
    const fmt = (n) => '\u09F3 ' + n.toLocaleString('en-BD', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function getPeriod() {
        return document.querySelector('input[name="billing_period"]:checked')?.value || 'yearly';
    }

    function calcDomainTotal(years) {
        if (isOwnDomain) return 0;
        if (freeFirstYear) return domainYearly * Math.max(0, years - 1);
        return domainYearly * years;
    }

    function updatePeriodUI() {
        const period = getPeriod();
        document.querySelectorAll('.billing-period-card').forEach(card => {
            card.classList.toggle('selected', card.dataset.period === period);
        });
        const yearsWrap = document.getElementById('billingYearsWrap');
        const yearsTitle = document.getElementById('billingYearsTitle');
        const yearsHint = document.getElementById('billingYearsHint');
        if (!yearsWrap) return;

        if (period === 'monthly') {
            if (isOwnDomain) {
                yearsWrap.style.display = 'none';
            } else {
                yearsWrap.style.display = '';
                if (yearsTitle) yearsTitle.innerHTML = '<i class="fas fa-calendar-alt me-1"></i> ডোমেইন কত বছরের জন্য?';
                if (yearsHint) yearsHint.textContent = 'মাসিক হোস্টিং এর সাথে ডোমেইন বাৎসরিক হিসাবে কেনা হবে।';
            }
        } else {
            yearsWrap.style.display = '';
            if (yearsTitle) yearsTitle.innerHTML = '<i class="fas fa-calendar-alt me-1"></i> কত বছরের জন্য কিনবেন?';
            if (yearsHint) yearsHint.textContent = 'ডোমেইন ও হোস্টিং উভয়ের মূল্য নির্বাচিত বছর অনুযায়ী হিসাব হবে।';
        }
    }

    function updateSummary() {
        const period = getPeriod();
        const years = parseInt(document.querySelector('input[name="billing_years"]:checked')?.value || '1', 10);
        const domainTotal = calcDomainTotal(years);
        const hostingTotal = !hasHosting ? 0 : (period === 'monthly' ? hostingMonthly : hostingYearly * years);
        const grand = domainTotal + hostingTotal;

        updatePeriodUI();

        document.querySelectorAll('.billing-year-card').forEach(card => {
            card.classList.toggle('selected', parseInt(card.dataset.years, 10) === years);
        });
        const domainLabel = document.getElementById('domainPeriodLabel');
        const domainPrice = document.getElementById('domainPriceDisplay');
        if (domainLabel && domainPrice) {
            if (isOwnDomain) {
                domainLabel.textContent = '\u09AC\u09BF\u09A6\u09CD\u09AF\u09AE\u09BE\u09A8 \u09A1\u09CB\u09AE\u09C7\u09A8';
                domainPrice.textContent = '\u09F3 0.00';
                domainPrice.className = 'fw-bold text-success small';
            } else {
                domainLabel.textContent = '\u09B0\u09C7\u099C\u09BF\u09B8\u09CD\u099F\u09CD\u09B0\u09C7\u09B6\u09A8 (' + years + ' \u09AC\u099B\u09B0)';
                if (freeFirstYear && domainTotal === 0) {
                    domainPrice.textContent = '\u09F3 0.00 (\u09E7\u09AE \u09AC\u099B\u09B0 \u09AB\u09CD\u09B0\u09BF)';
                    domainPrice.className = 'fw-bold text-success small';
                } else {
                    domainPrice.textContent = fmt(domainTotal);
                    domainPrice.className = 'fw-bold text-dark small';
                }
            }
        }
        const hostingLabel = document.getElementById('hostingPeriodLabel');
        const hostingPrice = document.getElementById('hostingPriceDisplay');
        if (hostingLabel && hostingPrice) {
            hostingLabel.textContent = period === 'monthly'
                ? '\u09B9\u09CB\u09B8\u09CD\u099F\u09BF\u0982 (১ মাস)'
                : '\u09B9\u09CB\u09B8\u09CD\u099F\u09BF\u0982 (' + years + ' \u09AC\u099B\u09B0)';
            hostingPrice.textContent = fmt(hostingTotal);
        }
        const grandEl = document.getElementById('grandTotalDisplay');
        if (grandEl) grandEl.textContent = fmt(grand);
        const freeAlert = document.getElementById('freeDomainAlert');
        if (freeAlert) freeAlert.classList.toggle('d-none', !(freeFirstYear && !isOwnDomain));
    }
    document.querySelectorAll('input[name="billing_years"]').forEach(r => r.addEventListener('change', updateSummary));
    document.querySelectorAll('input[name="billing_period"]').forEach(r => r.addEventListener('change', updateSummary));
    updateSummary();
})();
</script>
@endsection
