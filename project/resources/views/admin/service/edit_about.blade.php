@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
<style>
.hero-edit__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 280px;
    gap: 22px;
    align-items: start;
}

.hero-edit__card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.hero-edit__card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.hero-edit__card-head h3 {
    margin: 0;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.hero-edit__card-head h3 i {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.hero-edit__tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px 22px 0;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
}

.hero-edit__tab {
    border: 0;
    background: transparent;
    padding: 10px 16px;
    border-radius: 10px 10px 0 0;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--adm-muted);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.15s, background 0.15s;
    margin-bottom: -1px;
    border: 1px solid transparent;
}

.hero-edit__tab:hover {
    color: var(--adm-text);
    background: rgba(255, 255, 255, 0.7);
}

.hero-edit__tab.is-active {
    color: #4338ca;
    background: #fff;
    border-color: #f1f5f9;
    border-bottom-color: #fff;
    box-shadow: 0 -2px 8px rgba(15, 23, 42, 0.04);
}

.hero-edit__tab i {
    font-size: 0.85rem;
}

.hero-edit__panel {
    display: none;
    padding: 22px 24px;
}

.hero-edit__panel.is-active {
    display: block;
}

.hero-edit__section {
    margin-bottom: 24px;
}

.hero-edit__section:last-child {
    margin-bottom: 0;
}

.hero-edit__section-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.hero-edit__section-num {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.hero-edit__section-head h4 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--adm-text);
}

.hero-edit__section-head p {
    margin: 2px 0 0;
    font-size: 0.76rem;
    color: var(--adm-muted);
}

.hero-edit__hint {
    margin: 0 0 16px;
    padding: 12px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    font-size: 0.82rem;
    color: #1e40af;
    line-height: 1.55;
}

.hero-edit__hint code {
    background: rgba(255, 255, 255, 0.7);
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 0.78rem;
}

.hero-edit__item {
    background: #fff;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 12px;
}

.hero-edit__item:last-child {
    margin-bottom: 0;
}

.hero-edit__item-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--adm-text);
}

.hero-edit__item-label i {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.hero-edit__mockup-toggle {
    display: inline-flex;
    padding: 4px;
    background: #e2e8f0;
    border-radius: 10px;
    gap: 4px;
    margin-bottom: 18px;
}

.hero-edit__mockup-btn {
    border: 0;
    background: transparent;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, box-shadow 0.15s;
}

.hero-edit__mockup-btn.is-active {
    background: #fff;
    color: #4338ca;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.1);
}

.hero-edit__upload {
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    background: #f8fafc;
    padding: 16px;
    text-align: center;
}

.hero-edit__upload-preview {
    width: 100%;
    max-width: 320px;
    height: 180px;
    margin: 0 auto 14px;
    border-radius: 12px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border: 1px solid var(--adm-border);
    background-color: #fff;
}

.hero-edit__upload-preview--about {
    max-width: 280px;
    height: 200px;
}

.hero-edit__upload label.upload-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 0;
}

.hero-edit__upload input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.hero-edit__upload small {
    display: block;
    margin-top: 10px;
    font-size: 0.74rem;
    color: var(--adm-muted);
}

.hero-edit__actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 18px 24px;
    border-top: 1px solid #f1f5f9;
    background: #fafbfc;
}

.hero-edit__save {
    border: 0;
    border-radius: 10px;
    padding: 12px 24px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.hero-edit__link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 10px;
    border: 1px solid var(--adm-border);
    background: #fff;
    color: var(--adm-text) !important;
    font-size: 0.86rem;
    font-weight: 700;
    text-decoration: none !important;
}

.hero-edit__link:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.hero-edit__aside {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 84px;
}

.hero-edit__tip {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 16px 18px;
}

.hero-edit__tip h5 {
    margin: 0 0 10px;
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.hero-edit__tip h5 i { color: #f59e0b; }

.hero-edit__tip ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.hero-edit__tip li {
    position: relative;
    padding-left: 14px;
    margin-bottom: 8px;
    font-size: 0.78rem;
    color: var(--adm-muted);
    line-height: 1.5;
}

.hero-edit__tip li:last-child { margin-bottom: 0; }

.hero-edit__tip li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 7px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #93c5fd;
}

.hero-edit__quick-links a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid var(--adm-border);
    background: #fff;
    color: var(--adm-text) !important;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none !important;
    margin-bottom: 8px;
    transition: border-color 0.15s, background 0.15s;
}

.hero-edit__quick-links a:last-child { margin-bottom: 0; }

.hero-edit__quick-links a:hover {
    border-color: #93c5fd;
    background: #eff6ff;
}

.hero-edit__quick-links a i {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

@media (max-width: 1100px) {
    .hero-edit__layout { grid-template-columns: 1fr; }
    .hero-edit__aside { position: static; }
}

@media (max-width: 640px) {
    .hero-edit__tabs { padding: 10px 12px 0; }
    .hero-edit__tab { padding: 8px 12px; font-size: 0.76rem; }
    .hero-edit__panel { padding: 16px; }
    .hero-edit__actions { padding: 14px 16px; }
}
</style>
@endsection

@section('content')
@php
    $mockup = $hero->mockup ?? [];
    $mockupType = old('mockup_type', $mockup['type'] ?? 'wireframe');

    $stats = $hero->stats ?? [];
    if (is_array(old('stat_value'))) {
        $stats = [];
        foreach (old('stat_value', []) as $i => $value) {
            $stats[] = [
                'icon' => old('stat_icon.'.$i, ''),
                'value' => $value,
                'label' => old('stat_label.'.$i, ''),
            ];
        }
    }

    $buttons = $hero->buttons ?? [];
    if (is_array(old('btn_text'))) {
        $buttons = [];
        foreach (old('btn_text', []) as $i => $text) {
            $buttons[] = [
                'text' => $text,
                'url' => old('btn_url.'.$i, ''),
                'style' => old('btn_style.'.$i, 'primary'),
                'icon' => old('btn_icon.'.$i, ''),
            ];
        }
    }

    $cats = $mockup['categories'] ?? [];
    if (is_array(old('mockup_categories'))) {
        $cats = old('mockup_categories', []);
    }

    $products = $mockup['products'] ?? [];
    if (is_array(old('product_price'))) {
        $products = [];
        foreach (old('product_price', []) as $i => $price) {
            $products[] = [
                'badge' => old('product_badge.'.$i, ''),
                'badge_class' => old('product_badge_class.'.$i, ''),
                'price' => $price,
                'rating' => old('product_rating.'.$i, ''),
            ];
        }
    }

    $aboutImage = !empty($data->image)
        ? asset_image('assets/images/about/'.$data->image)
        : asset_image('assets/images/noimage.png');

    $heroMockupImage = !empty($data->hero_mockup_image)
        ? asset_image('assets/images/hero/'.$data->hero_mockup_image)
        : null;
@endphp

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-home',
        'title' => __('Homepage Hero Section'),
        'subtitle' => __('হোমপেজ হিরো, মকাপ ও About ইমেজ এডিট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Homepage'), 'url' => route('admin.homepage.sections')],
            ['label' => __('Hero Section'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.partials.frontend-setup-nav')

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data" id="hero-edit-form">
        @csrf

        <div class="hero-edit__layout">
            <div class="hero-edit__card">
                <div class="hero-edit__card-head">
                    <h3><i class="fas fa-edit"></i> হিরো সেকশন কন্টেন্ট</h3>
                </div>

                <div class="hero-edit__tabs" role="tablist">
                    <button type="button" class="hero-edit__tab is-active" data-tab="basic">
                        <i class="fas fa-align-left"></i> মূল টেক্সট
                    </button>
                    <button type="button" class="hero-edit__tab" data-tab="stats">
                        <i class="fas fa-chart-line"></i> স্ট্যাট ও বাটন
                    </button>
                    <button type="button" class="hero-edit__tab" data-tab="mockup">
                        <i class="fas fa-desktop"></i> মকাপ
                    </button>
                    <button type="button" class="hero-edit__tab" data-tab="about">
                        <i class="fas fa-image"></i> About ইমেজ
                    </button>
                </div>

                {{-- Tab: Basic --}}
                <div class="hero-edit__panel is-active" data-panel="basic">
                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">1</span>
                            <div>
                                <h4>হিরো ব্যাজ</h4>
                                <p>টাইটেলের উপরে ছোট হাইলাইট টেক্সট</p>
                            </div>
                        </div>
                        <div class="adm-form__field adm-form__field--full">
                            <input type="text" class="input-field" name="hero_badge"
                                   value="{{ old('hero_badge', $hero->hero_badge ?? '') }}"
                                   placeholder="৫০০+ ওয়েবসাইট সফলভাবে ডেলিভারি">
                        </div>
                    </div>

                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">2</span>
                            <div>
                                <h4>হিরো টাইটেল <span class="text-danger">*</span></h4>
                                <p>মূল হেডলাইন — HTML সাপোর্টেড</p>
                            </div>
                        </div>
                        <p class="hero-edit__hint">
                            অ্যাকসেন্ট টেক্সটের জন্য: <code>&lt;span class="home-hero__accent"&gt;হাইলাইট&lt;/span&gt;</code>
                        </p>
                        <div class="adm-form__field adm-form__field--full">
                            <textarea class="input-field" name="title" rows="3" required placeholder="আপনার অনলাইন ব্যবসার সহযোগী">{{ old('title', $data->title ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">3</span>
                            <div>
                                <h4>হিরো বিবরণ <span class="text-danger">*</span></h4>
                                <p>টাইটেলের নিচের সংক্ষিপ্ত বর্ণনা</p>
                            </div>
                        </div>
                        <div class="adm-form__field adm-form__field--full">
                            <textarea class="input-field" name="subtitle" rows="4" required placeholder="আমরা ওয়েব ডিজাইন, সফটওয়্যার ও ডিজিটাল মার্কেটিং সেবা দিই...">{{ old('subtitle', $data->subtitle ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Tab: Stats & Buttons --}}
                <div class="hero-edit__panel" data-panel="stats">
                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">1</span>
                            <div>
                                <h4>হিরো স্ট্যাট (৩টি)</h4>
                                <p>সংখ্যা ও আইকন দিয়ে বিশ্বাসযোগ্যতা দেখান</p>
                            </div>
                        </div>

                        @for($i = 0; $i < 3; $i++)
                            @php $stat = $stats[$i] ?? ['icon' => '', 'value' => '', 'label' => '']; @endphp
                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label">
                                    <i class="fas fa-hashtag"></i> স্ট্যাট #{{ $i + 1 }}
                                </div>
                                <div class="adm-form__grid">
                                    <div class="adm-form__field">
                                        <label>আইকন ক্লাস</label>
                                        <input type="text" class="input-field" name="stat_icon[]"
                                               value="{{ old('stat_icon.'.$i, $stat['icon'] ?? '') }}"
                                               placeholder="fas fa-laptop-code">
                                        <small>Font Awesome ক্লাস</small>
                                    </div>
                                    <div class="adm-form__field">
                                        <label>মান</label>
                                        <input type="text" class="input-field" name="stat_value[]"
                                               value="{{ old('stat_value.'.$i, $stat['value'] ?? '') }}"
                                               placeholder="৫০০+">
                                    </div>
                                    <div class="adm-form__field adm-form__field--full">
                                        <label>লেবেল</label>
                                        <input type="text" class="input-field" name="stat_label[]"
                                               value="{{ old('stat_label.'.$i, $stat['label'] ?? '') }}"
                                               placeholder="ওয়েবসাইট ডেলিভারি">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">2</span>
                            <div>
                                <h4>হিরো বাটন (২টি)</h4>
                                <p>CTA বাটন — Primary ও Secondary</p>
                            </div>
                        </div>

                        @for($i = 0; $i < 2; $i++)
                            @php $btn = $buttons[$i] ?? ['text' => '', 'url' => '', 'style' => 'primary', 'icon' => '']; @endphp
                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label">
                                    <i class="fas fa-mouse-pointer"></i> বাটন #{{ $i + 1 }}
                                </div>
                                <div class="adm-form__grid">
                                    <div class="adm-form__field">
                                        <label>টেক্সট</label>
                                        <input type="text" class="input-field" name="btn_text[]"
                                               value="{{ old('btn_text.'.$i, $btn['text'] ?? '') }}"
                                               placeholder="প্যাকেজ দেখুন">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>URL</label>
                                        <input type="text" class="input-field" name="btn_url[]"
                                               value="{{ old('btn_url.'.$i, $btn['url'] ?? '') }}"
                                               placeholder="/priceing">
                                    </div>
                                    <div class="adm-form__field adm-form__field--select">
                                        <label>স্টাইল</label>
                                        <select class="input-field adm-select" name="btn_style[]">
                                            <option value="primary" @selected(old('btn_style.'.$i, $btn['style'] ?? '') === 'primary')>Primary</option>
                                            <option value="secondary" @selected(old('btn_style.'.$i, $btn['style'] ?? '') === 'secondary')>Secondary</option>
                                        </select>
                                    </div>
                                    <div class="adm-form__field">
                                        <label>আইকন</label>
                                        <input type="text" class="input-field" name="btn_icon[]"
                                               value="{{ old('btn_icon.'.$i, $btn['icon'] ?? '') }}"
                                               placeholder="fas fa-search">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Tab: Mockup --}}
                <div class="hero-edit__panel" data-panel="mockup">
                    <div class="hero-edit__section">
                        <div class="hero-edit__section-head">
                            <span class="hero-edit__section-num">1</span>
                            <div>
                                <h4>মকাপ টাইপ</h4>
                                <p>Wireframe CSS মকাপ অথবা ইমেজ আপলোড</p>
                            </div>
                        </div>

                        <select class="input-field adm-select d-none" name="mockup_type" id="mockup_type">
                            <option value="wireframe" @selected($mockupType === 'wireframe')>Wireframe</option>
                            <option value="image" @selected($mockupType === 'image')>Image</option>
                        </select>

                        <div class="hero-edit__mockup-toggle" role="group">
                            <button type="button" class="hero-edit__mockup-btn {{ $mockupType === 'wireframe' ? 'is-active' : '' }}" data-mockup="wireframe">
                                <i class="fas fa-code"></i> Wireframe
                            </button>
                            <button type="button" class="hero-edit__mockup-btn {{ $mockupType === 'image' ? 'is-active' : '' }}" data-mockup="image">
                                <i class="fas fa-image"></i> ইমেজ আপলোড
                            </button>
                        </div>

                        <div id="mockup-image-wrap" style="{{ $mockupType === 'image' ? '' : 'display:none;' }}">
                            <div class="hero-edit__upload">
                                @if($heroMockupImage)
                                    <div class="hero-edit__upload-preview" id="hero-mockup-preview" style="background-image:url('{{ $heroMockupImage }}');"></div>
                                @else
                                    <div class="hero-edit__upload-preview" id="hero-mockup-preview" style="background-image:url('{{ asset_image('assets/images/noimage.png') }}');"></div>
                                @endif
                                <label class="upload-label" for="hero_mockup_image">
                                    <i class="fas fa-upload"></i> মকাপ ইমেজ বেছে নিন
                                </label>
                                <input type="file" name="hero_mockup_image" id="hero_mockup_image" accept="image/*">
                                <small>JPG, PNG — সর্বোচ্চ ২MB</small>
                            </div>
                        </div>

                        <div id="mockup-wireframe-wrap" style="{{ $mockupType === 'wireframe' ? '' : 'display:none;' }}">
                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label"><i class="fas fa-comment-dots"></i> ফ্লোট ব্যাজ (৩টি)</div>
                                <div class="adm-form__grid">
                                    <div class="adm-form__field">
                                        <label>ফ্লোট ১</label>
                                        <input type="text" class="input-field" name="mockup_float_1" value="{{ old('mockup_float_1', $mockup['float_1'] ?? '') }}" placeholder="Secure Payment">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>ফ্লোট ২</label>
                                        <input type="text" class="input-field" name="mockup_float_2" value="{{ old('mockup_float_2', $mockup['float_2'] ?? '') }}" placeholder="Fast Delivery">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>ফ্লোট ৩</label>
                                        <input type="text" class="input-field" name="mockup_float_3" value="{{ old('mockup_float_3', $mockup['float_3'] ?? '') }}" placeholder="COD Available">
                                    </div>
                                </div>
                            </div>

                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label"><i class="fas fa-globe"></i> ব্রাউজার ও UI</div>
                                <div class="adm-form__grid">
                                    <div class="adm-form__field">
                                        <label>ব্রাউজার URL</label>
                                        <input type="text" class="input-field" name="mockup_browser_url" value="{{ old('mockup_browser_url', $mockup['browser_url'] ?? '') }}" placeholder="shop.yourstore.com">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>সার্চ প্লেসহোল্ডার</label>
                                        <input type="text" class="input-field" name="mockup_search_placeholder" value="{{ old('mockup_search_placeholder', $mockup['search_placeholder'] ?? '') }}">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>কার্ট সংখ্যা</label>
                                        <input type="text" class="input-field" name="mockup_cart_count" value="{{ old('mockup_cart_count', $mockup['cart_count'] ?? '') }}">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>সেল টেক্সট</label>
                                        <input type="text" class="input-field" name="mockup_sale_text" value="{{ old('mockup_sale_text', $mockup['sale_text'] ?? '') }}">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>ব্যানার বাটন</label>
                                        <input type="text" class="input-field" name="mockup_banner_btn" value="{{ old('mockup_banner_btn', $mockup['banner_btn'] ?? '') }}">
                                    </div>
                                    <div class="adm-form__field">
                                        <label>চেকআউট বাটন</label>
                                        <input type="text" class="input-field" name="mockup_checkout_btn" value="{{ old('mockup_checkout_btn', $mockup['checkout_btn'] ?? '') }}">
                                    </div>
                                    <div class="adm-form__field adm-form__field--full">
                                        <label>চেকআউট টেক্সট (HTML OK)</label>
                                        <input type="text" class="input-field" name="mockup_checkout_text" value="{{ old('mockup_checkout_text', $mockup['checkout_text'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label"><i class="fas fa-th-large"></i> ক্যাটাগরি (৪টি)</div>
                                <div class="adm-form__grid">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="adm-form__field">
                                            <label>ক্যাটাগরি {{ $i + 1 }}</label>
                                            <input type="text" class="input-field" name="mockup_categories[]"
                                                   value="{{ old('mockup_categories.'.$i, $cats[$i] ?? '') }}"
                                                   placeholder="{{ ['All', 'Electronics', 'Fashion', 'Home'][$i] ?? '' }}">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="hero-edit__item">
                                <div class="hero-edit__item-label"><i class="fas fa-shopping-bag"></i> প্রোডাক্ট (৩টি)</div>
                                @for($i = 0; $i < 3; $i++)
                                    @php $p = $products[$i] ?? []; @endphp
                                    <div class="hero-edit__item" style="background:#f8fafc;margin-top:10px;">
                                        <div class="hero-edit__item-label" style="margin-bottom:10px;">
                                            <i class="fas fa-box"></i> প্রোডাক্ট #{{ $i + 1 }}
                                        </div>
                                        <div class="adm-form__grid">
                                            <div class="adm-form__field">
                                                <label>ব্যাজ</label>
                                                <input type="text" class="input-field" name="product_badge[]" value="{{ old('product_badge.'.$i, $p['badge'] ?? '') }}" placeholder="Hot">
                                            </div>
                                            <div class="adm-form__field">
                                                <label>ব্যাজ ক্লাস</label>
                                                <input type="text" class="input-field" name="product_badge_class[]" value="{{ old('product_badge_class.'.$i, $p['badge_class'] ?? '') }}" placeholder="is-new">
                                            </div>
                                            <div class="adm-form__field">
                                                <label>দাম</label>
                                                <input type="text" class="input-field" name="product_price[]" value="{{ old('product_price.'.$i, $p['price'] ?? '') }}" placeholder="৳ ১,২৯৯">
                                            </div>
                                            <div class="adm-form__field">
                                                <label>রেটিং</label>
                                                <input type="text" class="input-field" name="product_rating[]" value="{{ old('product_rating.'.$i, $p['rating'] ?? '') }}" placeholder="4.8">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab: About Image --}}
                <div class="hero-edit__panel" data-panel="about">
                    <div class="hero-edit__section">
                        <p class="hero-edit__hint">
                            <i class="fas fa-info-circle"></i>
                            এই ইমেজ <strong>About Us</strong> পেজে ব্যবহার হয় — হোমপেজ হিরো মকাপ নয়।
                        </p>
                        <div class="hero-edit__upload">
                            <div class="hero-edit__upload-preview hero-edit__upload-preview--about" id="about-image-preview" style="background-image:url('{{ $aboutImage }}');"></div>
                            <label class="upload-label" for="image-upload">
                                <i class="fas fa-upload"></i> About ইমেজ বেছে নিন
                            </label>
                            <input type="file" name="image" id="image-upload" accept="image/*">
                            <small>JPG, PNG — About Us পেজের মূল ছবি</small>
                        </div>
                    </div>
                </div>

                <div class="hero-edit__actions">
                    <button class="hero-edit__save" type="submit">
                        <i class="fas fa-save"></i> আপডেট করুন
                    </button>
                    <a href="{{ route('admin.homepage.sections') }}" class="hero-edit__link">
                        <i class="fas fa-th-list"></i> অন্যান্য সেকশন
                    </a>
                </div>
            </div>

            <aside class="hero-edit__aside">
                <div class="hero-edit__tip">
                    <h5><i class="fas fa-lightbulb"></i> দরকারী টিপস</h5>
                    <ul>
                        <li>টাইটেলে <code>home-hero__accent</code> ক্লাস দিয়ে হাইলাইট টেক্সট যোগ করুন।</li>
                        <li>স্ট্যাট ৩টি রাখলে হিরো সেকশন সবচেয়ে ভালো দেখায়।</li>
                        <li>Wireframe মকাপ e-commerce সাইটের প্রিভিউ দেখায়।</li>
                        <li>সেভের পর হোমপেজ রিফ্রেশ করে দেখুন।</li>
                    </ul>
                </div>

                <div class="hero-edit__tip">
                    <h5><i class="fas fa-link"></i> দ্রুত লিংক</h5>
                    <div class="hero-edit__quick-links">
                        <a href="{{ route('admin.homepage.sections') }}">
                            <i class="fas fa-th-large"></i> হোমপেজ সেকশন
                        </a>
                        <a href="{{ route('admin.frontend-theme.edit') }}">
                            <i class="fas fa-palette"></i> থিম কালার
                        </a>
                        <a href="{{ url('/') }}" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt"></i> ফ্রন্টএন্ড দেখুন
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function () {
    document.querySelectorAll('.hero-edit__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-tab');

            document.querySelectorAll('.hero-edit__tab').forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
            });
            document.querySelectorAll('.hero-edit__panel').forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-panel') === target);
            });
        });
    });

    function setMockupType(type) {
        var select = document.getElementById('mockup_type');
        if (select) select.value = type;

        var imageWrap = document.getElementById('mockup-image-wrap');
        var wireWrap = document.getElementById('mockup-wireframe-wrap');
        if (imageWrap) imageWrap.style.display = type === 'image' ? '' : 'none';
        if (wireWrap) wireWrap.style.display = type === 'wireframe' ? '' : 'none';

        document.querySelectorAll('.hero-edit__mockup-btn').forEach(function (btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-mockup') === type);
        });
    }

    document.querySelectorAll('.hero-edit__mockup-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            setMockupType(btn.getAttribute('data-mockup'));
        });
    });

    function bindImagePreview(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (!input || !preview) return;

        input.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
            };
            reader.readAsDataURL(file);
        });
    }

    bindImagePreview('image-upload', 'about-image-preview');
    bindImagePreview('hero_mockup_image', 'hero-mockup-preview');
})();
</script>
@endsection
