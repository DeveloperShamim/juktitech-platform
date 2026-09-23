@php
    $product = $product ?? null;
    $currentCatKey = old('category_key', $product?->category_key ?? 'ecom');
    $currentCatName = old('category_name', $product?->category_name ?? 'ই-কমার্স');
    $currentBadgeText = old('badge_text', $product?->badge_text ?? '🔥 বেস্ট চয়েস • হট সেলার');
    $currentBadgeClass = old('badge_class', $product?->badge_class ?? 'cd-badge--hot');
    $currentLicense = old('license_type', $product?->license_type ?? 'আজীবন লাইসেন্স');
    $currentSortOrder = old('sort_order', $product?->sort_order ?? 0);

    $rawFeatures = old('features', $product?->clean_features ?? [
        '১-পেজ ফাস্ট চেকআউট',
        'পাঠাও ও স্টেডফাস্ট কুরিয়ার API',
        'বিকাশ ও নগদ অটো পেমেন্ট',
        'মেটা পিক্সেল ও কনভার্সন API'
    ]);
    if (is_string($rawFeatures)) {
        $rawFeatures = json_decode($rawFeatures, true) ?: [];
    }
    if (empty($rawFeatures)) {
        $rawFeatures = [''];
    }
@endphp

<div class="row mt-4">
    <div class="col-lg-4"></div>
    <div class="col-lg-7">
        <div class="p-3 mb-3 rounded" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1px solid #fed7aa;">
            <h5 class="font-weight-bold text-dark mb-1" style="color: #ea580c !important;">
                <i class="fas fa-laptop-code mr-1"></i> {{ __('ওয়েবসাইট কালেকশন ও শোকেস সেটিংস') }}
            </h5>
            <small class="text-muted">হোমপেজের "রেডিমেড ওয়েবসাইট কালেকশন" সেকশনের ক্যাটাগরি, ব্যাজ এবং কার্ডের ফিচারসমূহ এখান থেকে নিয়ন্ত্রণ করুন।</small>
        </div>
    </div>
</div>

<!-- ১. ক্যাটাগরি প্রিসেট ও কাস্টম ক্যাটাগরি -->
<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('ক্যাটাগরি / ফিল্টার ট্যাব') }} *</h4>
        <small class="text-muted d-block">হোমপেজে যে ট্যাবের অধীনে প্রদর্শিত হবে</small>
    </div>
    <div class="col-lg-7">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">ক্যাটাগরি প্রিসেট</label>
                <select id="preset_cat_selector" class="input-field">
                    <option value="ecom" data-name="ই-কমার্স" {{ $currentCatKey == 'ecom' ? 'selected' : '' }}>🛒 ই-কমার্স (eCommerce)</option>
                    <option value="news" data-name="নিউজ পোর্টাল" {{ $currentCatKey == 'news' ? 'selected' : '' }}>📰 নিউজ পোর্টাল (News)</option>
                    <option value="courier" data-name="কুরিয়ার ও ERP" {{ $currentCatKey == 'courier' ? 'selected' : '' }}>🚚 কুরিয়ার ও ERP (Courier)</option>
                    <option value="lms" data-name="অনলাইন কোর্স" {{ $currentCatKey == 'lms' ? 'selected' : '' }}>🎓 অনলাইন কোর্স (LMS)</option>
                    <option value="pos" data-name="পিওএস ও হিসাব" {{ $currentCatKey == 'pos' ? 'selected' : '' }}>💼 পিওএস ও ইনভেন্টরি (POS)</option>
                    <option value="agency" data-name="এজেন্সি ও বিজনেস" {{ $currentCatKey == 'agency' ? 'selected' : '' }}>✨ এজেন্সি ও বিজনেস (Agency)</option>
                    <option value="custom" data-name="" {{ !in_array($currentCatKey, ['ecom','news','courier','lms','pos','agency']) ? 'selected' : '' }}>⚙️ কাস্টম ক্যাটাগরি (Custom)</option>
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">প্রদর্শিত ক্যাটাগরি নাম</label>
                <input type="text" name="category_name" id="category_name_input" class="input-field" value="{{ $currentCatName }}" placeholder="যেমন: ই-কমার্স" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">ক্যাটাগরি কী (Slug/Filter Key)</label>
                <input type="text" name="category_key" id="category_key_input" class="input-field" value="{{ $currentCatKey }}" placeholder="e.g. ecom, news, restaurant" required>
            </div>
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">সিরিয়াল ক্রম (Sort Order)</label>
                <input type="number" name="sort_order" class="input-field" value="{{ $currentSortOrder }}" min="0" placeholder="0">
                <small class="text-muted">কম সংখ্যা আগে দেখাবে (যেমন ১, ২, ৩...)</small>
            </div>
        </div>
    </div>
</div>

<!-- ২. ব্যাজ ও লাইসেন্স -->
<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('প্রমোশনাল ব্যাজ ও লাইসেন্স') }}</h4>
    </div>
    <div class="col-lg-7">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">ব্যাজের টেক্সট</label>
                <input type="text" name="badge_text" class="input-field" value="{{ $currentBadgeText }}" placeholder="যেমন: 🔥 বেস্ট চয়েস • হট সেলার">
            </div>
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">ব্যাজের কালার / থিম</label>
                <select name="badge_class" class="input-field">
                    <option value="cd-badge--hot" {{ $currentBadgeClass == 'cd-badge--hot' ? 'selected' : '' }}>🔥 কমলা (Hot Seller - Orange)</option>
                    <option value="cd-badge--blue" {{ $currentBadgeClass == 'cd-badge--blue' ? 'selected' : '' }}>⚡ নীল (Ultrafast - Blue)</option>
                    <option value="cd-badge--purple" {{ $currentBadgeClass == 'cd-badge--purple' ? 'selected' : '' }}>🚀 বেগুনি (Automation - Purple)</option>
                    <option value="cd-badge--green" {{ $currentBadgeClass == 'cd-badge--green' ? 'selected' : '' }}>🎓 সবুজ (Academy - Green)</option>
                    <option value="cd-badge--amber" {{ $currentBadgeClass == 'cd-badge--amber' ? 'selected' : '' }}>💼 অ্যাম্বার (Smart POS - Amber)</option>
                    <option value="cd-badge--indigo" {{ $currentBadgeClass == 'cd-badge--indigo' ? 'selected' : '' }}>✨ ইন্ডিগো (Modern - Indigo)</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <label class="font-weight-bold text-muted small mb-1">লাইসেন্স টাইপ ট্যাগ</label>
                <input type="text" name="license_type" class="input-field" value="{{ $currentLicense }}" placeholder="আজীবন লাইসেন্স">
            </div>
        </div>
    </div>
</div>

<!-- ৩. ডাইনামিক ফিচারসমূহ রিপিটার -->
<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('মূল ফিচারসমূহ (চেকলিস্ট)') }} *</h4>
        <small class="text-muted d-block">কার্ডের বডিতে সবুজ টিকচিহ্ন সহ ৩-৪টি গুরুত্বপূর্ণ ফিচার বুলেট পয়েন্ট দিন</small>
    </div>
    <div class="col-lg-7">
        <div id="websiteFeatureRows" class="mb-2">
            @foreach($rawFeatures as $fIdx => $fVal)
                <div class="feature-item-row d-flex align-items-center mb-2" style="gap: 8px;">
                    <span class="badge badge-success p-2"><i class="fas fa-check"></i></span>
                    <input type="text" name="features[]" class="input-field mb-0" value="{{ $fVal }}" placeholder="যেমন: ১-পেজ ফাস্ট চেকআউট">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-feature-btn" title="মুছুন">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
            <button type="button" id="addFeatureRowBtn" class="btn btn-outline-success btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> নতুন ফিচার বুলেট যোগ করুন
            </button>
        </div>
    </div>
</div>

<!-- ৪. লাইভ টেস্ট ড্রাইভ ক্রেডেনশিয়ালস -->
<div class="row mt-4">
    <div class="col-lg-4"></div>
    <div class="col-lg-7">
        <div class="p-3 mb-3 rounded" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1px solid #bfdbfe;">
            <h5 class="font-weight-bold text-dark mb-1" style="color: #2563eb !important;">
                <i class="fas fa-key mr-1"></i> {{ __('লাইভ টেস্ট ড্রাইভ ও অ্যাডমিন ক্রেডেনশিয়ালস') }}
            </h5>
            <small class="text-muted">কাস্টমারকে কেনার আগে লাইভ ওয়েবসাইট ও অ্যাডমিন ড্যাশবোর্ড সরাসরি টেস্ট ড্রাইভ দেওয়ার জন্য লগইন তথ্য এখানে দিন।</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('অ্যাডমিন ড্যাশবোর্ড অ্যাক্সেস') }}</h4>
        <small class="text-muted d-block">ভিজিটররা এই ক্রেডেনশিয়াল ১-ক্লিকে কপি করে ডেমো অ্যাডমিন দেখতে পারবে</small>
    </div>
    <div class="col-lg-7">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label class="font-weight-bold text-muted small mb-1">অ্যাডমিন লগইন লিংক (Admin Login URL)</label>
                <input type="text" name="demo_admin_link" class="input-field" value="{{ old('demo_admin_link', $product?->demo_admin_link) }}" placeholder="যেমন: https://yourdomain.com/admin">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">অ্যাডমিন ইমেইল / ইউজারনেম</label>
                <input type="text" name="demo_admin_email" class="input-field" value="{{ old('demo_admin_email', $product?->demo_admin_email) }}" placeholder="যেমন: admin@demo.com">
            </div>
            <div class="col-md-6 mb-2">
                <label class="font-weight-bold text-muted small mb-1">অ্যাডমিন পাসওয়ার্ড</label>
                <input type="text" name="demo_admin_password" class="input-field" value="{{ old('demo_admin_password', $product?->demo_admin_password) }}" placeholder="যেমন: password">
            </div>
        </div>
    </div>
</div>

<!-- ৫. ৩-টিয়ার প্যাকেজ প্রাইসিং কাস্টমাইজেশন -->
@php
    $tiers = $product?->clean_pricing_tiers ?? [];
    $tier1 = $tiers[0] ?? [];
    $tier2 = $tiers[1] ?? [];
    $tier3 = $tiers[2] ?? [];
@endphp
<div class="row mt-4">
    <div class="col-lg-4"></div>
    <div class="col-lg-7">
        <div class="p-3 mb-3 rounded" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #cbd5e1;">
            <h5 class="font-weight-bold text-dark mb-1" style="color: #0f172a !important;">
                <i class="fas fa-tags mr-1"></i> {{ __('স্বচ্ছ ও সাশ্রয়ী ৩-টিয়ার প্যাকেজ প্রাইসিং') }}
            </h5>
            <small class="text-muted">রেফারেন্স স্ক্রিনশটের মতো ৩টি প্যাকেজ (সম্পূর্ণ সোর্স কোড, সোর্স কোড + ব্র্যান্ডিং, কাস্টম ডেভেলপমেন্ট) এর মূল্য কাস্টমাইজ করুন। ফাঁকা রাখলে স্বয়ংক্রিয় ডিফল্ট মান ব্যবহৃত হবে।</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('প্যাকেজ মূল্য ও ছাড়') }}</h4>
    </div>
    <div class="col-lg-7">
        <div class="row">
            <!-- Tier 1 -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-2 bg-white">
                    <span class="badge badge-secondary mb-1">টিয়ার ১: সোর্স কোড</span>
                    <label class="font-weight-bold text-muted small mb-1 d-block">বর্তমান মূল্য (৳)</label>
                    <input type="number" name="pricing_tiers[0][price]" class="input-field mb-2" value="{{ $tier1['price'] ?? 8500 }}" placeholder="৮৫০০">
                    <label class="font-weight-bold text-muted small mb-1 d-block">আগের মূল্য (কাটা দাগ)</label>
                    <input type="number" name="pricing_tiers[0][previous_price]" class="input-field mb-2" value="{{ $tier1['previous_price'] ?? 15000 }}" placeholder="১৫০০০">
                    <input type="hidden" name="pricing_tiers[0][title]" value="{{ $tier1['title'] ?? 'সম্পূর্ণ সোর্স কোড' }}">
                    <input type="hidden" name="pricing_tiers[0][badge]" value="{{ $tier1['badge'] ?? 'সেলফ-হোস্টিং ও ডেভেলপার' }}">
                </div>
            </div>

            <!-- Tier 2 -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-2 bg-white" style="border-color: #fb923c !important;">
                    <span class="badge badge-warning text-dark mb-1">🔥 টিয়ার ২: ব্র্যান্ডিং (হট)</span>
                    <label class="font-weight-bold text-muted small mb-1 d-block">বর্তমান মূল্য (৳)</label>
                    <input type="number" name="pricing_tiers[1][price]" class="input-field mb-2" value="{{ $tier2['price'] ?? 14500 }}" placeholder="১৪৫০০">
                    <label class="font-weight-bold text-muted small mb-1 d-block">আগের মূল্য (কাটা দাগ)</label>
                    <input type="number" name="pricing_tiers[1][previous_price]" class="input-field mb-2" value="{{ $tier2['previous_price'] ?? 25000 }}" placeholder="২৫০০০">
                    <input type="hidden" name="pricing_tiers[1][title]" value="{{ $tier2['title'] ?? 'সোর্স কোড + ব্র্যান্ডিং' }}">
                    <input type="hidden" name="pricing_tiers[1][badge]" value="{{ $tier2['badge'] ?? '🔥 সর্বাধিক জনপ্রিয়' }}">
                </div>
            </div>

            <!-- Tier 3 -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-2 bg-white">
                    <span class="badge badge-info mb-1">টিয়ার ৩: কাস্টম</span>
                    <label class="font-weight-bold text-muted small mb-1 d-block">বর্তমান মূল্য (৳)</label>
                    <input type="number" name="pricing_tiers[2][price]" class="input-field mb-2" value="{{ $tier3['price'] ?? 25000 }}" placeholder="২৫০০০">
                    <label class="font-weight-bold text-muted small mb-1 d-block">আগের মূল্য (কাটা দাগ)</label>
                    <input type="number" name="pricing_tiers[2][previous_price]" class="input-field mb-2" value="{{ $tier3['previous_price'] ?? 50000 }}" placeholder="৫০০০০">
                    <input type="hidden" name="pricing_tiers[2][title]" value="{{ $tier3['title'] ?? 'কাস্টম ডেভেলপমেন্ট' }}">
                    <input type="hidden" name="pricing_tiers[2][badge]" value="{{ $tier3['badge'] ?? 'এন্টারপ্রাইজ সলিউশন' }}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feature Row Template -->
<template id="featureRowTemplate">
    <div class="feature-item-row d-flex align-items-center mb-2" style="gap: 8px;">
        <span class="badge badge-success p-2"><i class="fas fa-check"></i></span>
        <input type="text" name="features[]" class="input-field mb-0" value="" placeholder="ফিচারের নাম লিখুন...">
        <button type="button" class="btn btn-outline-danger btn-sm remove-feature-btn" title="মুছুন">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const presetSelector = document.getElementById('preset_cat_selector');
    const catNameInput = document.getElementById('category_name_input');
    const catKeyInput = document.getElementById('category_key_input');

    if (presetSelector && catNameInput && catKeyInput) {
        presetSelector.addEventListener('change', function() {
            const val = this.value;
            const opt = this.options[this.selectedIndex];
            if (val !== 'custom') {
                catKeyInput.value = val;
                catNameInput.value = opt.getAttribute('data-name') || '';
            }
        });
    }

    const featureContainer = document.getElementById('websiteFeatureRows');
    const addFeatureBtn = document.getElementById('addFeatureRowBtn');
    const template = document.getElementById('featureRowTemplate');

    if (addFeatureBtn && featureContainer && template) {
        addFeatureBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const clone = template.content.cloneNode(true);
            featureContainer.appendChild(clone);
        });

        featureContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-feature-btn');
            if (removeBtn) {
                e.preventDefault();
                const row = removeBtn.closest('.feature-item-row');
                if (row) {
                    if (featureContainer.querySelectorAll('.feature-item-row').length > 1) {
                        row.remove();
                    } else {
                        row.querySelector('input').value = '';
                    }
                }
            }
        });
    }

    // Quick feature presets
    const quickPresets = {
        ecom: ['১-পেজ ফাস্ট চেকআউট', 'পাঠাও ও স্টেডফাস্ট কুরিয়ার API', 'বিকাশ ও নগদ অটো পেমেন্ট', 'মেটা পিক্সেল ও কনভার্সন API'],
        news: ['ই-পেপার ও পিডিএফ ভিউয়ার', 'গুগল অ্যাডসেন্স অপ্টিমাইজড স্পট', 'লাইভ ব্রেকিং নিউজ স্ক্রলার', 'ক্যাটাগরি আর্কাইভ ও ডার্ক মোড'],
        courier: ['মাল্টি-হাব পার্সেল ট্র্যাকিং', 'রাইডার অ্যাপ ও ক্যাশ কালেকশন', 'মার্চেন্ট প্যানেল ও ইনভয়েস', 'অটো এসএমএস নোটিফিকেশন'],
        lms: ['ভিডিও লেকচার ও প্রটেক্টেড প্লেয়ার', 'কুইজ, এক্সাম ও সার্টিফিকেট', 'অটো পেমেন্ট ও এনরোলমেন্ট', 'স্টুডেন্ট প্রোগ্রেস অ্যানালিটিক্স'],
        pos: ['বারকোড স্ক্যান ও থার্মাল প্রিন্ট', 'রিয়েল-টাইম স্টক অ্যালার্ট', 'বাকি খাতা ও কাস্টমার লেজার', 'দৈনিক ও মাসিক প্রফিট-লস রিপোর্ট'],
        agency: ['প্রিমিয়াম পোর্টফোলিও শোকেস', 'সার্ভিস প্যাকেজ ও প্রাইসিং টেবিল', 'ইনস্ট্যান্ট কোটেশন ও কনট্যাক্ট ফর্ম', '১০০% এসইও ফ্রেন্ডলি স্ট্রাকচার']
    };

    document.querySelectorAll('.quick-fill-features').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            const list = quickPresets[type];
            if (!list || !featureContainer) return;

            featureContainer.innerHTML = '';
            list.forEach(item => {
                const clone = template.content.cloneNode(true);
                clone.querySelector('input').value = item;
                featureContainer.appendChild(clone);
            });
        });
    });
});
</script>
