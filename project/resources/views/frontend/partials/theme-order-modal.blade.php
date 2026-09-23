@php
    $modalTiers = $product->clean_pricing_tiers;
    $bkashNum = !empty($gs->payment_bkash_number) ? $gs->payment_bkash_number : '01775075543';
    $bkashType = !empty($gs->payment_bkash_account_type) ? ucfirst($gs->payment_bkash_account_type) : 'Personal';
    $nagadNum = !empty($gs->payment_nagad_number) ? $gs->payment_nagad_number : '01775075543';
    $nagadType = !empty($gs->payment_nagad_account_type) ? ucfirst($gs->payment_nagad_account_type) : 'Personal';
    $rocketNum = !empty($gs->payment_rocket_number) ? $gs->payment_rocket_number : '01775075543';
    $rocketType = !empty($gs->payment_rocket_account_type) ? ucfirst($gs->payment_rocket_account_type) : 'Personal';
@endphp

<div class="theme-order-modal-backdrop" id="themeOrderBackdrop"></div>

<div class="theme-order-modal" id="themeOrderModal" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="theme-order-modal-dialog">
        
        {{-- Close Button --}}
        <button type="button" class="theme-order-close-btn" id="themeOrderCloseBtn" aria-label="Close modal">
            <i class="fas fa-times"></i>
        </button>

        {{-- ======================================================== --}}
        {{-- VIEW 1: CHECKOUT FORM VIEW                               --}}
        {{-- ======================================================== --}}
        <div id="themeOrderFormView">
            {{-- Modal Header --}}
            <div class="theme-order-header">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset_image('assets/images/products/'.$product->photo) }}" 
                         alt="{{ $product->name }}" 
                         class="theme-order-thumb">
                    <div>
                        <div class="theme-order-badge">
                            <i class="fas fa-bolt text-warning me-1"></i> ইনস্ট্যান্ট অর্ডার ও চেকআউট
                        </div>
                        <h4 class="theme-order-title mb-0" id="orderModalTitle">{{ $product->name }}</h4>
                    </div>
                </div>
            </div>

            {{-- Form Container --}}
            <form id="themeOrderForm" action="{{ route('frontend.theme.order') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="package_tier" id="orderPackageTierInput" value="{{ $modalTiers[0]['title'] ?? 'সোর্স কোড' }}">

                {{-- STEP 1: Interactive Tier Selector --}}
                <div class="theme-order-section mb-4">
                    <label class="theme-order-label mb-2">
                        <i class="fas fa-layer-group text-primary me-1"></i> প্যাকেজ নির্বাচন করুন:
                    </label>
                    <div class="theme-order-tiers-grid">
                        @foreach($modalTiers as $idx => $t)
                            @php
                                $tTitle = $t['title'] ?? ('প্যাকেজ ' . ($idx + 1));
                                $tPrice = is_numeric($t['price'] ?? 0) ? (float)($t['price'] ?? 0) : 0;
                                $tPrev = is_numeric($t['previous_price'] ?? 0) ? (float)($t['previous_price'] ?? 0) : null;
                                $tSave = $t['savings_text'] ?? null;
                                $isFeat = !empty($t['is_featured']) || $idx === 1;
                            @endphp
                            <div class="theme-order-tier-card {{ $idx === 0 ? 'active' : '' }}" 
                                 data-tier-idx="{{ $idx }}"
                                 data-tier-title="{{ $tTitle }}"
                                 data-tier-price="{{ $tPrice }}"
                                 data-tier-save="{{ $tSave }}">
                                @if($isFeat)
                                    <span class="theme-order-popular-pill">🔥 জনপ্রিয়</span>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold tier-card-title text-dark">{{ $tTitle }}</div>
                                    <div class="tier-radio-indicator">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="tier-card-price fw-extrabold text-orange">৳ {{ number_format($tPrice, 0) }}</span>
                                    @if($tPrev)
                                        <del class="tier-card-prev text-muted small">৳ {{ number_format($tPrev, 0) }}</del>
                                    @endif
                                </div>
                                @if($tSave)
                                    <div class="tier-card-save small text-success fw-bold">{{ $tSave }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- STEP 2: Customer Details Form --}}
                <div class="theme-order-section mb-4">
                    <label class="theme-order-label mb-2">
                        <i class="fas fa-user-circle text-primary me-1"></i> আপনার তথ্য প্রদান করুন:
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="theme-order-input-wrap">
                                <label for="orderCustomerName">পূর্ণ নাম <span class="text-danger">*</span></label>
                                <div class="input-icon-box">
                                    <i class="fas fa-user"></i>
                                    <input type="text" 
                                           id="orderCustomerName" 
                                           name="customer_name" 
                                           class="form-control" 
                                           placeholder="যেমন: মোঃ সাকিব হাসান" 
                                           value="{{ auth()->check() ? auth()->user()->name : '' }}" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="theme-order-input-wrap">
                                <label for="orderCustomerPhone">হোয়াটসঅ্যাপ / মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <div class="input-icon-box">
                                    <i class="fab fa-whatsapp text-success"></i>
                                    <input type="tel" 
                                           id="orderCustomerPhone" 
                                           name="customer_phone" 
                                           class="form-control" 
                                           placeholder="017XXXXXXXX" 
                                           value="{{ auth()->check() ? auth()->user()->phone : '' }}" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="theme-order-input-wrap">
                                <label for="orderCustomerEmail">ইমেইল ঠিকানা <span class="text-muted">(ইনভয়েস পাওয়ার জন্য)</span></label>
                                <div class="input-icon-box">
                                    <i class="far fa-envelope"></i>
                                    <input type="email" 
                                           id="orderCustomerEmail" 
                                           name="customer_email" 
                                           class="form-control" 
                                           placeholder="you@example.com" 
                                           value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="theme-order-input-wrap">
                                <label for="orderDomainName">ইনস্টলেশন ডোমেইন <span class="text-muted">(যদি থাকে)</span></label>
                                <div class="input-icon-box">
                                    <i class="fas fa-globe"></i>
                                    <input type="text" 
                                           id="orderDomainName" 
                                           name="domain_name" 
                                           class="form-control" 
                                           placeholder="mystore.com (না থাকলে খালি রাখুন)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Payment Method Selection --}}
                <div class="theme-order-section mb-4">
                    <label class="theme-order-label mb-2">
                        <i class="fas fa-wallet text-primary me-1"></i> পেমেন্ট মেথড নির্বাচন করুন:
                    </label>

                    {{-- Payment Pills --}}
                    <div class="theme-order-payment-pills mb-3">
                        <label class="payment-pill-btn active" data-method="manual_bkash">
                            <input type="radio" name="payment_method" value="manual_bkash" checked class="d-none">
                            <span class="pay-icon-bkash">bKash</span>
                            <span>বিকাশ</span>
                        </label>
                        <label class="payment-pill-btn" data-method="manual_nagad">
                            <input type="radio" name="payment_method" value="manual_nagad" class="d-none">
                            <span class="pay-icon-nagad">নগদ</span>
                            <span>নগদ</span>
                        </label>
                        <label class="payment-pill-btn" data-method="manual_rocket">
                            <input type="radio" name="payment_method" value="manual_rocket" class="d-none">
                            <span class="pay-icon-rocket">Rocket</span>
                            <span>রকেট</span>
                        </label>
                        <label class="payment-pill-btn" data-method="booking">
                            <input type="radio" name="payment_method" value="booking" class="d-none">
                            <i class="fas fa-phone-volume text-primary"></i>
                            <span>বুকিং / পরামর্শ</span>
                        </label>
                    </div>

                    {{-- Instruction Box: Manual Mobile Banking --}}
                    <div id="manualPayInstructionBox" class="theme-order-instruction-box p-3 rounded-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small font-weight-bold text-dark" id="payMethodLabel">বিকাশ (Send Money / পেমেন্ট) নম্বর:</span>
                            <span class="badge badge-light border text-muted px-2 py-1 small" id="payAccountType">{{ $bkashType }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white border p-2 rounded-2 mb-2">
                            <span class="font-monospace fw-bold text-dark fs-5 ps-2" id="payAccountNumber">{{ $bkashNum }}</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-pay-number" id="btnCopyPayNumber" data-copy-target="payAccountNumber" title="নম্বর কপি করুন">
                                <i class="far fa-copy me-1"></i> <span>কপি করুন</span>
                            </button>
                        </div>
                        <p class="small text-muted mb-2">
                            <i class="fas fa-info-circle text-primary me-1"></i> উপরের নম্বরে নির্দিষ্ট পরিমাণ টাকা পাঠিয়ে নিচের বক্সে TrxID (Transaction ID) লিখুন।
                        </p>
                        <div class="input-icon-box">
                            <i class="fas fa-receipt text-warning"></i>
                            <input type="text" 
                                   id="orderTrxId" 
                                   name="trx_id" 
                                   class="form-control" 
                                   placeholder="TrxID লিখুন (যেমন: 9J3K8L2M)">
                        </div>
                    </div>

                    {{-- Instruction Box: Booking / Consultation --}}
                    <div id="bookingInstructionBox" class="theme-order-instruction-box p-3 rounded-3 mb-3 d-none" style="background: #f0fdf4; border-color: #bbf7d0;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success fs-5 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">কোনো অগ্রিম পেমেন্ট ছাড়াই অর্ডার বুক করুন</h6>
                                <p class="small text-muted mb-0">
                                    অর্ডারটি কনফার্ম করলে সাথে সাথে একটি অর্ডার আইডি তৈরি হবে। আমাদের সাপোর্ট টিম ৩০ মিনিটের মধ্যে আপনার সাথে ফোনে যোগাযোগ করে সমস্ত ডেমো ও প্রজেক্ট রিকোয়ারমেন্ট ফাইনাল করবে।
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Notes (Optional) --}}
                    <div class="theme-order-input-wrap">
                        <label for="orderNotes">অতিরিক্ত নোট বা বিশেষ চাহিদা <span class="text-muted">(ঐচ্ছিক)</span></label>
                        <textarea id="orderNotes" 
                                  name="notes" 
                                  rows="2" 
                                  class="form-control" 
                                  placeholder="কোনো বিশেষ ফিচার বা ব্র্যান্ডিংয়ের প্রয়োজনীয়তা থাকলে এখানে লিখতে পারেন..."></textarea>
                    </div>
                </div>

                {{-- Alert Box for Errors --}}
                <div id="themeOrderAlert" class="alert alert-danger d-none py-2 px-3 small rounded-3 mb-3"></div>

                {{-- Summary & Submit Button --}}
                <div class="theme-order-footer pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <span class="text-muted small d-block">মোট প্রদেয় মূল্য:</span>
                            <span class="theme-order-total-price fw-extrabold text-dark fs-3" id="orderSummaryTotalPrice">৳ {{ number_format($modalTiers[0]['price'] ?? 0, 0) }}</span>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-soft-orange text-orange px-3 py-2 rounded-pill small fw-bold" id="orderSummaryTierBadge">
                                {{ $modalTiers[0]['title'] ?? 'সোর্স কোড' }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-orange w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm transition" id="themeOrderSubmitBtn">
                        <span id="orderBtnText"><i class="fas fa-check-circle me-2"></i> অর্ডার কনফার্ম করুন</span>
                        <span id="orderBtnSpinner" class="d-none"><i class="fas fa-spinner fa-spin me-2"></i> অর্ডার প্রসেস হচ্ছে...</span>
                    </button>

                    <div class="d-flex align-items-center justify-content-center gap-3 mt-3 text-muted small">
                        <span><i class="fas fa-shield-alt text-success me-1"></i> ১০০% সুরক্ষিত</span>
                        <span><i class="fas fa-file-invoice text-primary me-1"></i> ইনস্ট্যান্ট ইনভয়েস</span>
                        <span><i class="fab fa-whatsapp text-success me-1"></i> সরাসরি সাপোর্ট</span>
                    </div>
                </div>
            </form>
        </div>

        {{-- ======================================================== --}}
        {{-- VIEW 2: ORDER SUCCESS CONFIRMATION VIEW                  --}}
        {{-- ======================================================== --}}
        <div id="themeOrderSuccessView" class="d-none text-center py-4">
            <div class="order-success-icon-wrap mb-3">
                <div class="order-success-icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>

            <span class="badge bg-soft-green text-success px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="fas fa-check-circle me-1"></i> অর্ডার সম্পন্ন হয়েছে
            </span>

            <h3 class="fw-extrabold text-dark mb-2">অভিনন্দন! আপনার অর্ডারটি গৃহীত হয়েছে</h3>
            <p class="text-muted mb-4 small px-md-4">
                আপনার অর্ডারের যাবতীয় তথ্য রেকর্ড করা হয়েছে। নিচের বোতামে ক্লিক করে এখনই লাইভ ইনভয়েস দেখতে ও ডাউনলোড করতে পারেন।
            </p>

            {{-- Order ID Pill --}}
            <div class="order-id-display-box p-3 rounded-3 mb-4 d-inline-flex align-items-center gap-3">
                <div class="text-start">
                    <span class="small text-muted d-block">অর্ডার আইডি:</span>
                    <strong class="font-monospace fs-4 text-dark" id="successOrderNumber">ORD-000000</strong>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-2 border" id="btnCopyOrderNumber" title="অর্ডার আইডি কপি করুন">
                    <i class="far fa-copy"></i>
                </button>
            </div>

            {{-- Summary Table --}}
            <div class="order-success-details-card p-3 rounded-3 text-start mb-4 bg-light border">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted small">থিমের নাম:</span>
                    <strong class="text-dark small" id="successProductName">{{ $product->name }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted small">নির্বাচিত প্যাকেজ:</span>
                    <strong class="text-dark small" id="successPackageTier">-</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted small">মোট মূল্য:</span>
                    <strong class="text-orange small" id="successTotalAmount">-</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted small">পেমেন্ট মেথড:</span>
                    <span class="badge badge-secondary" id="successPaymentMethod">-</span>
                </div>
            </div>

            {{-- High-Converting Actions --}}
            <div class="d-grid gap-2 mb-3">
                {{-- Live Invoice Button --}}
                <a href="#" id="successInvoiceBtn" target="_blank" class="btn btn-primary btn-lg py-3 rounded-3 fw-bold" style="background: #0f172a; border-color: #0f172a;">
                    <i class="fas fa-file-invoice me-2 text-warning"></i> লাইভ ইনভয়েস দেখুন ও প্রিন্ট করুন
                </a>

                {{-- WhatsApp Button (Optional / Secondary) --}}
                <a href="#" id="successWhatsappBtn" target="_blank" class="btn btn-success btn-lg py-3 rounded-3 fw-bold">
                    <i class="fab fa-whatsapp me-2"></i> WhatsApp-এ আমাদের জানান
                </a>
            </div>

            <button type="button" class="btn btn-link text-muted small" id="successCloseBtn">
                উইন্ডো বন্ধ করুন
            </button>
        </div>

    </div>
</div>

<style>
/* ============================================================
   THEME ORDER MODAL — ULTRA-PREMIUM MODERN LIGHT STYLING
   ============================================================ */
.theme-order-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    z-index: 99998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s ease-in-out;
}
.theme-order-modal-backdrop.active {
    opacity: 1;
    visibility: visible;
}

.theme-order-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.theme-order-modal.active {
    opacity: 1;
    visibility: visible;
}

.theme-order-modal-dialog {
    position: relative;
    width: 100%;
    max-width: 680px;
    background: #ffffff;
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
    border: 1px solid #e2e8f0;
    max-height: 90vh;
    overflow-y: auto;
    transform: translateY(20px) scale(0.98);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.theme-order-modal.active .theme-order-modal-dialog {
    transform: translateY(0) scale(1);
}

.theme-order-close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    border: none;
    color: #64748b;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    z-index: 10;
}
.theme-order-close-btn:hover {
    background: #fee2e2;
    color: #ef4444;
}

.theme-order-thumb {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}
.theme-order-badge {
    font-size: 0.75rem;
    font-weight: 700;
    color: #ea580c;
    background: #fff7ed;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 4px;
}
.theme-order-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
}

.theme-order-label {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    display: block;
}

/* 3 Tiers Grid inside modal */
.theme-order-tiers-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
@media (max-width: 576px) {
    .theme-order-tiers-grid {
        grid-template-columns: 1fr;
    }
}
.theme-order-tier-card {
    position: relative;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.theme-order-tier-card:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
}
.theme-order-tier-card.active {
    border-color: #ff5722;
    background: #fff8f5;
    box-shadow: 0 4px 15px rgba(255, 87, 34, 0.12);
}
.theme-order-popular-pill {
    position: absolute;
    top: -9px;
    right: 10px;
    background: #ff5722;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
}
.tier-radio-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    color: transparent;
    transition: all 0.2s ease;
}
.theme-order-tier-card.active .tier-radio-indicator {
    background: #ff5722;
    border-color: #ff5722;
    color: #fff;
}
.tier-card-title {
    font-size: 0.85rem;
}
.tier-card-price {
    font-size: 1.05rem;
}

/* Form Inputs */
.theme-order-input-wrap label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 6px;
    display: block;
}
.input-icon-box {
    position: relative;
}
.input-icon-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.95rem;
}
.input-icon-box .form-control {
    padding-left: 40px;
    height: 46px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    font-size: 0.9rem;
    font-weight: 500;
    color: #0f172a;
    transition: border-color 0.15s ease;
}
.input-icon-box .form-control:focus {
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.12);
}

/* Payment Pills */
.theme-order-payment-pills {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
@media (max-width: 576px) {
    .theme-order-payment-pills {
        grid-template-columns: repeat(2, 1fr);
    }
}
.payment-pill-btn {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.85rem;
    color: #334155;
    transition: all 0.2s ease;
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.payment-pill-btn:hover {
    background: #f1f5f9;
}
.payment-pill-btn.active {
    border-color: #ff5722;
    background: #fff8f5;
    color: #ff5722;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.1);
}
.pay-icon-bkash { color: #e2136e; font-weight: 900; }
.pay-icon-nagad { color: #f7941d; font-weight: 900; }
.pay-icon-rocket { color: #8c3494; font-weight: 900; }

.theme-order-instruction-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* Success View */
.order-success-icon-wrap {
    display: inline-block;
}
.order-success-icon {
    width: 72px;
    height: 72px;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto;
    animation: successPulse 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes successPulse {
    0% { transform: scale(0.6); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.order-id-display-box {
    background: #f1f5f9;
    border: 1px dashed #cbd5e1;
}
.bg-soft-green { background: #f0fdf4; }
.text-orange { color: #ff5722; }
.bg-soft-orange { background: #fff7ed; color: #ff5722; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tiers Data from PHP
    const tiersData = @json($modalTiers);
    const paymentInfo = {
        manual_bkash: {
            number: '{{ $bkashNum }}',
            type: '{{ $bkashType }}',
            label: 'বিকাশ (Send Money / পেমেন্ট) নম্বর:'
        },
        manual_nagad: {
            number: '{{ $nagadNum }}',
            type: '{{ $nagadType }}',
            label: 'নগদ (Send Money / পেমেন্ট) নম্বর:'
        },
        manual_rocket: {
            number: '{{ $rocketNum }}',
            type: '{{ $rocketType }}',
            label: 'রকেট (Send Money / পেমেন্ট) নম্বর:'
        }
    };

    const modalBackdrop = document.getElementById('themeOrderBackdrop');
    const modal = document.getElementById('themeOrderModal');
    const closeBtn = document.getElementById('themeOrderCloseBtn');
    const successCloseBtn = document.getElementById('successCloseBtn');
    const tierCards = document.querySelectorAll('.theme-order-tier-card');
    const packageTierInput = document.getElementById('orderPackageTierInput');
    const summaryPrice = document.getElementById('orderSummaryTotalPrice');
    const summaryBadge = document.getElementById('orderSummaryTierBadge');
    const paymentPills = document.querySelectorAll('.payment-pill-btn');
    const manualPayBox = document.getElementById('manualPayInstructionBox');
    const bookingPayBox = document.getElementById('bookingInstructionBox');
    const payMethodLabel = document.getElementById('payMethodLabel');
    const payAccountType = document.getElementById('payAccountType');
    const payAccountNumber = document.getElementById('payAccountNumber');
    const trxInput = document.getElementById('orderTrxId');
    const btnCopyPay = document.getElementById('btnCopyPayNumber');
    const orderForm = document.getElementById('themeOrderForm');
    const submitBtn = document.getElementById('themeOrderSubmitBtn');
    const btnText = document.getElementById('orderBtnText');
    const btnSpinner = document.getElementById('orderBtnSpinner');
    const alertBox = document.getElementById('themeOrderAlert');

    // Open & Close Functions
    window.openThemeOrderModal = function(tierIndex) {
        idx = parseInt(tierIndex) || 0;
        if (idx >= 0 && idx < tierCards.length) {
            selectTier(idx);
        }
        modalBackdrop.classList.add('active');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    function closeThemeOrderModal() {
        modalBackdrop.classList.remove('active');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeThemeOrderModal);
    if (successCloseBtn) successCloseBtn.addEventListener('click', closeThemeOrderModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeThemeOrderModal);

    // Escape key listener
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeThemeOrderModal();
        }
    });

    // Trigger on all .js-open-theme-order elements
    document.querySelectorAll('.js-open-theme-order').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const tierIdx = this.getAttribute('data-tier-index') || 0;
            window.openThemeOrderModal(tierIdx);
        });
    });

    // Tier Selection Logic
    function selectTier(index) {
        tierCards.forEach((card, i) => {
            if (i === index) {
                card.classList.add('active');
                const title = card.getAttribute('data-tier-title');
                const price = parseFloat(card.getAttribute('data-tier-price')) || 0;
                packageTierInput.value = title;
                summaryPrice.textContent = '৳ ' + price.toLocaleString('en-US');
                summaryBadge.textContent = title;
            } else {
                card.classList.remove('active');
            }
        });
    }

    tierCards.forEach((card, idx) => {
        card.addEventListener('click', function() {
            selectTier(idx);
        });
    });

    // Payment Method Switching Logic
    paymentPills.forEach(pill => {
        pill.addEventListener('click', function() {
            paymentPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            const method = this.getAttribute('data-method');
            if (method === 'booking') {
                manualPayBox.classList.add('d-none');
                bookingPayBox.classList.remove('d-none');
                trxInput.removeAttribute('required');
            } else {
                bookingPayBox.classList.add('d-none');
                manualPayBox.classList.remove('d-none');
                const info = paymentInfo[method];
                if (info) {
                    payMethodLabel.textContent = info.label;
                    payAccountType.textContent = info.type;
                    payAccountNumber.textContent = info.number;
                }
            }
        });
    });

    // Copy Account Number
    if (btnCopyPay) {
        btnCopyPay.addEventListener('click', function() {
            const num = payAccountNumber.textContent.trim();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(num).then(() => {
                    const originalHtml = btnCopyPay.innerHTML;
                    btnCopyPay.innerHTML = '<i class="fas fa-check text-success me-1"></i> কপি হয়েছে!';
                    setTimeout(() => { btnCopyPay.innerHTML = originalHtml; }, 2000);
                });
            }
        });
    }

    // Form Submission via AJAX
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alertBox.classList.add('d-none');
            alertBox.textContent = '';

            const phoneVal = document.getElementById('orderCustomerPhone').value.trim();
            if (!phoneVal) {
                alertBox.textContent = 'অনুগ্রহ করে আপনার ফোন / হোয়াটসঅ্যাপ নম্বর লিখুন।';
                alertBox.classList.remove('d-none');
                return;
            }

            // Spinner State
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');

            const formData = new FormData(orderForm);

            fetch(orderForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');

                if (data.success) {
                    // Populate Success View
                    document.getElementById('successOrderNumber').textContent = data.order_number;
                    document.getElementById('successPackageTier').textContent = data.package_tier;
                    document.getElementById('successTotalAmount').textContent = '৳ ' + (data.total_amount ? Number(data.total_amount).toLocaleString('en-US') : '0');
                    document.getElementById('successPaymentMethod').textContent = data.payment_method;

                    // Set Action Buttons
                    const invoiceBtn = document.getElementById('successInvoiceBtn');
                    if (invoiceBtn && data.invoice_url) {
                        invoiceBtn.href = data.invoice_url;
                    }

                    const waBtn = document.getElementById('successWhatsappBtn');
                    if (waBtn && data.whatsapp_url) {
                        waBtn.href = data.whatsapp_url;
                    }

                    // Copy Order Number Handler
                    const copyOrdBtn = document.getElementById('btnCopyOrderNumber');
                    if (copyOrdBtn) {
                        copyOrdBtn.onclick = function() {
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(data.order_number).then(() => {
                                    copyOrdBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
                                    setTimeout(() => { copyOrdBtn.innerHTML = '<i class="far fa-copy"></i>'; }, 2000);
                                });
                            }
                        };
                    }

                    // Switch view
                    document.getElementById('themeOrderFormView').classList.add('d-none');
                    document.getElementById('themeOrderSuccessView').classList.remove('d-none');
                } else {
                    alertBox.textContent = data.message || 'অর্ডার প্রসেস করার সময় সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
                    alertBox.classList.remove('d-none');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
                alertBox.textContent = 'নেটওয়ার্ক বা সার্ভারে সমস্যা হয়েছে। অনুগ্রহ করে কিছুক্ষণ পর চেষ্টা করুন।';
                alertBox.classList.remove('d-none');
                console.error(err);
            });
        });
    }
});
</script>
