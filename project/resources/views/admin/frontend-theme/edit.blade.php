@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
<link href="{{ asset_image('assets/admin/css/bootstrap-colorpicker.css') }}" rel="stylesheet" />
<style>
.theme-editor__overview {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.theme-editor__chip {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 72px;
}

.theme-editor__chip-swatch {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    border: 2px solid rgba(255, 255, 255, 0.85);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
    flex-shrink: 0;
}

.theme-editor__chip-body span {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--adm-muted);
    margin-bottom: 3px;
}

.theme-editor__chip-body strong {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--adm-text);
    font-family: ui-monospace, monospace;
}

.theme-editor__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 22px;
    align-items: start;
}

.theme-editor__card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.theme-editor__card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.theme-editor__card-head h3 {
    margin: 0;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.theme-editor__card-head h3 i {
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

.theme-editor__card-body {
    padding: 22px 24px;
}

.theme-editor__presets {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 22px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}

.theme-editor__preset {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid var(--adm-border);
    background: #fff;
    border-radius: 999px;
    padding: 8px 14px 8px 10px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--adm-text);
    cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
}

.theme-editor__preset:hover {
    border-color: #93c5fd;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
    transform: translateY(-1px);
}

.theme-editor__preset-dots {
    display: inline-flex;
    gap: 3px;
}

.theme-editor__preset-dots i {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: block;
    border: 1px solid rgba(255, 255, 255, 0.6);
}

.theme-editor__group {
    margin-bottom: 24px;
    border: 1px solid #eef2f7;
    border-radius: 14px;
    overflow: hidden;
    background: #fafbfc;
}

.theme-editor__group:last-child {
    margin-bottom: 0;
}

.theme-editor__group-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: #fff;
    border-bottom: 1px solid #eef2f7;
}

.theme-editor__group-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.theme-editor__group-head h4 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--adm-text);
}

.theme-editor__group-head p {
    margin: 2px 0 0;
    font-size: 0.76rem;
    color: var(--adm-muted);
}

.theme-editor__group-body {
    padding: 14px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.theme-color-field {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: #fff;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.theme-color-field:focus-within {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.theme-color-field__swatch {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.12);
    flex-shrink: 0;
    cursor: pointer;
}

.theme-color-field__info {
    flex: 1;
    min-width: 0;
}

.theme-color-field__info label {
    display: block;
    margin: 0 0 2px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--adm-text);
    line-height: 1.35;
}

.theme-color-field__info code {
    font-size: 0.72rem;
    color: var(--adm-muted);
    background: transparent;
    padding: 0;
}

.theme-color-field__picker {
    flex-shrink: 0;
    width: 120px;
}

.theme-color-field__picker .input-group {
    margin: 0;
}

.theme-color-field__picker .form-control,
.theme-color-field__picker .input-field {
    height: 38px !important;
    min-height: 38px !important;
    padding: 6px 10px !important;
    font-size: 0.78rem !important;
    font-family: ui-monospace, monospace;
    border-radius: 8px !important;
}

.theme-color-field__picker .input-group-addon {
    padding: 0 8px;
    border-radius: 0 8px 8px 0 !important;
}

.theme-color-field__picker .input-group-addon i {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: inline-block;
}

.theme-editor__actions {
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.theme-editor__save {
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
    transition: transform 0.15s;
}

.theme-editor__save:hover {
    transform: translateY(-1px);
    color: #fff;
}

.theme-editor__reset {
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    padding: 11px 18px;
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--adm-text);
    background: #fff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.15s, border-color 0.15s;
}

.theme-editor__reset:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.theme-editor__aside {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 84px;
}

.theme-editor__preview {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.theme-editor__preview-head {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.theme-editor__preview-head i { color: #2563eb; }

.theme-editor__preview-body {
    padding: 16px;
}

.theme-mock {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

.theme-mock__nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    background: #fff;
}

.theme-mock__logo {
    width: 28px;
    height: 28px;
    border-radius: 8px;
}

.theme-mock__nav-links {
    display: flex;
    gap: 8px;
}

.theme-mock__nav-links span {
    width: 36px;
    height: 6px;
    border-radius: 999px;
    background: #e2e8f0;
}

.theme-mock__hero {
    padding: 22px 16px 18px;
    text-align: center;
    background: linear-gradient(180deg, #faf8ff 0%, #fff 100%);
}

.theme-mock__hero h5 {
    margin: 0 0 6px;
    font-size: 0.92rem;
    font-weight: 800;
    color: var(--adm-text);
}

.theme-mock__hero-accent {
    font-size: 0.78rem;
    font-weight: 800;
    margin-bottom: 12px;
}

.theme-mock__btn {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #fff;
    border: 0;
}

.theme-mock__stats {
    padding: 14px 12px;
    color: #fff;
    text-align: center;
    font-size: 0.72rem;
    font-weight: 700;
}

.theme-mock__footer {
    padding: 14px;
    color: #cbd5e1;
    font-size: 0.68rem;
    text-align: center;
}

.theme-mock__footer-icons {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
}

.theme-mock__footer-icons i {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    color: #fff;
}

.theme-editor__tip {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 16px 18px;
}

.theme-editor__tip h5 {
    margin: 0 0 10px;
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.theme-editor__tip h5 i { color: #f59e0b; }

.theme-editor__tip ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.theme-editor__tip li {
    position: relative;
    padding-left: 14px;
    margin-bottom: 8px;
    font-size: 0.78rem;
    color: var(--adm-muted);
    line-height: 1.5;
}

.theme-editor__tip li:last-child { margin-bottom: 0; }

.theme-editor__tip li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 7px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #93c5fd;
}

@media (max-width: 1200px) {
    .theme-editor__layout { grid-template-columns: 1fr; }
    .theme-editor__aside { position: static; }
    .theme-editor__overview { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .theme-editor__overview { grid-template-columns: 1fr; }
    .theme-editor__group-body { grid-template-columns: 1fr; }
    .theme-color-field { flex-wrap: wrap; }
    .theme-color-field__picker { width: 100%; }
}
</style>
@endsection

@section('content')
@php
    $groups = [
        [
            'icon' => 'fas fa-star',
            'title' => 'ব্র্যান্ড ও প্রাইমারি',
            'desc' => 'বাটন, লিংক ও মূল ব্র্যান্ড রঙ',
            'fields' => ['primary', 'primary_hover', 'primary_light'],
        ],
        [
            'icon' => 'fas fa-fill-drip',
            'title' => 'অ্যাকসেন্ট ও সেকেন্ডারি',
            'desc' => 'গ্রেডিয়েন্ট ও হাইলাইট রঙ',
            'fields' => ['accent', 'accent_hover', 'secondary'],
        ],
        [
            'icon' => 'fas fa-bolt',
            'title' => 'হিরো সেকশন',
            'desc' => 'হিরো টাইটেলে হাইলাইট টেক্সট',
            'fields' => ['hero_accent'],
        ],
        [
            'icon' => 'fas fa-shoe-prints',
            'title' => 'ফুটার',
            'desc' => 'ফুটার ব্যাকগ্রাউন্ড ও লিংক',
            'fields' => ['footer_start', 'footer_mid', 'footer_end', 'footer_link_hover', 'footer_icon'],
        ],
        [
            'icon' => 'fas fa-chart-bar',
            'title' => 'স্ট্যাটস সেকশন',
            'desc' => 'সংখ্যা/স্ট্যাট কাউন্টার ব্যাকগ্রাউন্ড',
            'fields' => ['stats_start', 'stats_mid', 'stats_end'],
        ],
        [
            'icon' => 'fas fa-font',
            'title' => 'টাইপোগ্রাফি',
            'desc' => 'হেডিং ও বডি টেক্সট রঙ',
            'fields' => ['dark_bg', 'text_dark', 'text_muted', 'text_heading'],
        ],
    ];

    $value = fn ($key) => old($key, $theme->{$key} ?? '#000000');
@endphp

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-palette',
        'title' => __('Frontend Theme Colors'),
        'subtitle' => __('হোমপেজ ও ফ্রন্টএন্ডের সকল রঙ এখান থেকে পরিবর্তন করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Frontend Theme'), 'url' => null],
        ],
    ])

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @include('admin.partials.frontend-setup-nav')

    <div class="theme-editor__overview">
        <div class="theme-editor__chip">
            <div class="theme-editor__chip-swatch" id="overview-primary" style="background: {{ $value('primary') }};"></div>
            <div class="theme-editor__chip-body">
                <span>{{ __('Primary') }}</span>
                <strong id="overview-primary-hex">{{ $value('primary') }}</strong>
            </div>
        </div>
        <div class="theme-editor__chip">
            <div class="theme-editor__chip-swatch" id="overview-accent" style="background: {{ $value('accent') }};"></div>
            <div class="theme-editor__chip-body">
                <span>{{ __('Accent') }}</span>
                <strong id="overview-accent-hex">{{ $value('accent') }}</strong>
            </div>
        </div>
        <div class="theme-editor__chip">
            <div class="theme-editor__chip-swatch" id="overview-hero" style="background: {{ $value('hero_accent') }};"></div>
            <div class="theme-editor__chip-body">
                <span>{{ __('Hero Accent') }}</span>
                <strong id="overview-hero-hex">{{ $value('hero_accent') }}</strong>
            </div>
        </div>
        <div class="theme-editor__chip">
            <div class="theme-editor__chip-swatch" id="overview-footer" style="background: linear-gradient(135deg, {{ $value('footer_start') }}, {{ $value('footer_end') }});"></div>
            <div class="theme-editor__chip-body">
                <span>{{ __('Footer') }}</span>
                <strong>{{ $value('footer_start') }}</strong>
            </div>
        </div>
    </div>

    <div class="theme-editor__layout">
        <div class="theme-editor__card">
            <div class="theme-editor__card-head">
                <h3><i class="fas fa-sliders-h"></i> থিম রঙ সেটিংস</h3>
            </div>
            <div class="theme-editor__card-body">
                <form action="{{ route('admin.frontend-theme.update') }}" method="POST" id="theme-form">
                    @csrf

                    <div class="theme-editor__presets">
                        <span style="font-size:0.78rem;font-weight:700;color:var(--adm-muted);align-self:center;margin-right:4px;">{{ __('Quick Presets') }}:</span>
                        <button type="button" class="theme-editor__preset" data-preset="purple">
                            <span class="theme-editor__preset-dots">
                                <i style="background:#7c3aed;"></i>
                                <i style="background:#ec4899;"></i>
                                <i style="background:#fde047;"></i>
                            </span>
                            Purple
                        </button>
                        <button type="button" class="theme-editor__preset" data-preset="blue">
                            <span class="theme-editor__preset-dots">
                                <i style="background:#2563eb;"></i>
                                <i style="background:#06b6d4;"></i>
                                <i style="background:#fbbf24;"></i>
                            </span>
                            Blue
                        </button>
                        <button type="button" class="theme-editor__preset" data-preset="emerald">
                            <span class="theme-editor__preset-dots">
                                <i style="background:#059669;"></i>
                                <i style="background:#10b981;"></i>
                                <i style="background:#fde047;"></i>
                            </span>
                            Emerald
                        </button>
                        <button type="button" class="theme-editor__preset" data-preset="rose">
                            <span class="theme-editor__preset-dots">
                                <i style="background:#e11d48;"></i>
                                <i style="background:#f43f5e;"></i>
                                <i style="background:#fcd34d;"></i>
                            </span>
                            Rose
                        </button>
                    </div>

                    @foreach($groups as $group)
                        <div class="theme-editor__group">
                            <div class="theme-editor__group-head">
                                <span class="theme-editor__group-icon"><i class="{{ $group['icon'] }}"></i></span>
                                <div>
                                    <h4>{{ $group['title'] }}</h4>
                                    <p>{{ $group['desc'] }}</p>
                                </div>
                            </div>
                            <div class="theme-editor__group-body">
                                @foreach($group['fields'] as $key)
                                    @if(in_array($key, $fields, true))
                                        <div class="theme-color-field" data-field="{{ $key }}">
                                            <div class="theme-color-field__swatch js-swatch-trigger" data-target="{{ $key }}" style="background: {{ $value($key) }};"></div>
                                            <div class="theme-color-field__info">
                                                <label for="field_{{ $key }}">{{ $labels[$key] ?? $key }}</label>
                                                <code class="js-hex-display" data-for="{{ $key }}">{{ $value($key) }}</code>
                                            </div>
                                            <div class="theme-color-field__picker">
                                                <div class="input-group colorpicker-component cp">
                                                    <input type="text"
                                                           class="form-control input-field color-field cp js-theme-input"
                                                           id="field_{{ $key }}"
                                                           name="{{ $key }}"
                                                           value="{{ $value($key) }}"
                                                           data-field="{{ $key }}">
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="theme-editor__actions">
                        <button type="submit" class="theme-editor__save">
                            <i class="fas fa-save"></i> থিম রঙ সেভ করুন
                        </button>
                        <button type="button" class="theme-editor__reset" id="theme-reset-btn">
                            <i class="fas fa-undo"></i> ডিফল্টে রিসেট
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.frontend-theme.reset') }}" method="POST" id="theme-reset-form" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <aside class="theme-editor__aside">
            <div class="theme-editor__preview">
                <div class="theme-editor__preview-head">
                    <i class="fas fa-eye"></i> লাইভ প্রিভিউ
                </div>
                <div class="theme-editor__preview-body">
                    <div class="theme-mock">
                        <div class="theme-mock__nav">
                            <div class="theme-mock__logo" id="mock-logo"></div>
                            <div class="theme-mock__nav-links">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <div class="theme-mock__hero">
                            <h5 style="color: var(--adm-text);">Creative Design</h5>
                            <div class="theme-mock__hero-accent" id="mock-hero-accent">আপনার ডিজিটাল সমাধান</div>
                            <button type="button" class="theme-mock__btn" id="mock-btn">Get Started</button>
                        </div>
                        <div class="theme-mock__stats" id="mock-stats">১০০+ প্রজেক্ট · ৫০+ ক্লায়েন্ট</div>
                        <div class="theme-mock__footer" id="mock-footer">
                            <div class="theme-mock__footer-icons">
                                <i class="fab fa-facebook-f" id="mock-icon-1"></i>
                                <i class="fab fa-instagram" id="mock-icon-2"></i>
                            </div>
                            © {{ date('Y') }} Creative Design
                        </div>
                    </div>
                </div>
            </div>

            <div class="theme-editor__tip">
                <h5><i class="fas fa-lightbulb"></i> দরকারী টিপস</h5>
                <ul>
                    <li>প্রাইমারি রঙ বদলালে বাটন, লিংক ও গ্রেডিয়েন্ট একসাথে আপডেট হবে।</li>
                    <li>হিরো সেকশনের হলুদ হাইলাইটের জন্য <strong>Hero Accent</strong> ব্যবহার করুন।</li>
                    <li>সেভ করার পর ফ্রন্টএন্ড রিফ্রেশ করে দেখুন — ক্যাশ থাকলে Ctrl+F5 চাপুন।</li>
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var presets = {
        purple: {
            primary: '#7c3aed', primary_hover: '#6d28d9', primary_light: '#9333ea',
            accent: '#ec4899', accent_hover: '#db2777', secondary: '#6366f1',
            hero_accent: '#fde047',
            footer_start: '#120a2e', footer_mid: '#0f0824', footer_end: '#0a0618',
            footer_link_hover: '#c4b5fd', footer_icon: '#a78bfa',
            stats_start: '#4c1d95', stats_mid: '#6d28d9', stats_end: '#9333ea',
            dark_bg: '#120a2e', text_dark: '#0f172a', text_muted: '#64748b', text_heading: '#334155'
        },
        blue: {
            primary: '#2563eb', primary_hover: '#1d4ed8', primary_light: '#3b82f6',
            accent: '#06b6d4', accent_hover: '#0891b2', secondary: '#6366f1',
            hero_accent: '#fbbf24',
            footer_start: '#0c1929', footer_mid: '#0a1420', footer_end: '#060d16',
            footer_link_hover: '#93c5fd', footer_icon: '#60a5fa',
            stats_start: '#1e3a8a', stats_mid: '#2563eb', stats_end: '#3b82f6',
            dark_bg: '#0c1929', text_dark: '#0f172a', text_muted: '#64748b', text_heading: '#1e293b'
        },
        emerald: {
            primary: '#059669', primary_hover: '#047857', primary_light: '#10b981',
            accent: '#14b8a6', accent_hover: '#0d9488', secondary: '#6366f1',
            hero_accent: '#fde047',
            footer_start: '#052e16', footer_mid: '#042713', footer_end: '#021a0d',
            footer_link_hover: '#6ee7b7', footer_icon: '#34d399',
            stats_start: '#064e3b', stats_mid: '#059669', stats_end: '#10b981',
            dark_bg: '#052e16', text_dark: '#0f172a', text_muted: '#64748b', text_heading: '#14532d'
        },
        rose: {
            primary: '#e11d48', primary_hover: '#be123c', primary_light: '#f43f5e',
            accent: '#fb7185', accent_hover: '#f43f5e', secondary: '#8b5cf6',
            hero_accent: '#fcd34d',
            footer_start: '#2a0a12', footer_mid: '#1f080e', footer_end: '#140509',
            footer_link_hover: '#fda4af', footer_icon: '#fb7185',
            stats_start: '#881337', stats_mid: '#be123c', stats_end: '#e11d48',
            dark_bg: '#2a0a12', text_dark: '#0f172a', text_muted: '#64748b', text_heading: '#4c0519'
        }
    };

    function getVal(key) {
        var input = document.querySelector('.js-theme-input[data-field="' + key + '"]');
        return input ? input.value : '';
    }

    function gradient(primary, primaryLight, accent) {
        return 'linear-gradient(90deg, ' + primary + ' 0%, ' + primaryLight + ' 52%, ' + accent + ' 100%)';
    }

    function statsGradient(start, mid, end, primary) {
        return 'linear-gradient(135deg, ' + start + ' 0%, ' + mid + ' 42%, ' + primary + ' 72%, ' + end + ' 100%)';
    }

    function footerGradient(start, mid, end) {
        return 'linear-gradient(180deg, ' + start + ' 0%, ' + mid + ' 55%, ' + end + ' 100%)';
    }

    function updatePreview() {
        var primary = getVal('primary');
        var primaryLight = getVal('primary_light');
        var primaryHover = getVal('primary_hover');
        var accent = getVal('accent');
        var heroAccent = getVal('hero_accent');
        var footerStart = getVal('footer_start');
        var footerMid = getVal('footer_mid');
        var footerEnd = getVal('footer_end');
        var footerIcon = getVal('footer_icon');
        var statsStart = getVal('stats_start');
        var statsMid = getVal('stats_mid');
        var statsEnd = getVal('stats_end');

        var mockLogo = document.getElementById('mock-logo');
        var mockBtn = document.getElementById('mock-btn');
        var mockHero = document.getElementById('mock-hero-accent');
        var mockStats = document.getElementById('mock-stats');
        var mockFooter = document.getElementById('mock-footer');
        var mockIcon1 = document.getElementById('mock-icon-1');
        var mockIcon2 = document.getElementById('mock-icon-2');

        if (mockLogo) mockLogo.style.background = gradient(primary, primaryLight, accent);
        if (mockBtn) mockBtn.style.background = gradient(primaryHover || primary, primary, accent);
        if (mockHero) mockHero.style.color = heroAccent;
        if (mockStats) mockStats.style.background = statsGradient(statsStart, statsMid, statsEnd, primary);
        if (mockFooter) mockFooter.style.background = footerGradient(footerStart, footerMid, footerEnd);
        if (mockIcon1) mockIcon1.style.background = footerIcon;
        if (mockIcon2) mockIcon2.style.background = footerIcon;

        var ovPrimary = document.getElementById('overview-primary');
        var ovAccent = document.getElementById('overview-accent');
        var ovHero = document.getElementById('overview-hero');
        var ovFooter = document.getElementById('overview-footer');
        var ovPrimaryHex = document.getElementById('overview-primary-hex');
        var ovAccentHex = document.getElementById('overview-accent-hex');
        var ovHeroHex = document.getElementById('overview-hero-hex');

        if (ovPrimary) ovPrimary.style.background = primary;
        if (ovAccent) ovAccent.style.background = accent;
        if (ovHero) ovHero.style.background = heroAccent;
        if (ovFooter) ovFooter.style.background = footerGradient(footerStart, footerMid, footerEnd);
        if (ovPrimaryHex) ovPrimaryHex.textContent = primary;
        if (ovAccentHex) ovAccentHex.textContent = accent;
        if (ovHeroHex) ovHeroHex.textContent = heroAccent;
    }

    function syncFieldDisplay(field, color) {
        var swatch = document.querySelector('.js-swatch-trigger[data-target="' + field + '"]');
        var hex = document.querySelector('.js-hex-display[data-for="' + field + '"]');
        if (swatch) swatch.style.background = color;
        if (hex) hex.textContent = color;
    }

    function applyPreset(name) {
        var preset = presets[name];
        if (!preset) return;

        Object.keys(preset).forEach(function (key) {
            var input = document.querySelector('.js-theme-input[data-field="' + key + '"]');
            if (!input) return;
            input.value = preset[key];
            $(input).closest('.cp').colorpicker('setValue', preset[key]);
            syncFieldDisplay(key, preset[key]);
        });

        updatePreview();
    }

    $(function () {
        $('.cp').colorpicker({ format: 'hex' }).on('changeColor', function (e) {
            var input = $(this).find('.js-theme-input')[0];
            if (!input) return;
            var color = e.color.toHex();
            input.value = color;
            syncFieldDisplay(input.getAttribute('data-field'), color);
            updatePreview();
        });

        document.querySelectorAll('.js-swatch-trigger').forEach(function (el) {
            el.addEventListener('click', function () {
                var key = el.getAttribute('data-target');
                var input = document.getElementById('field_' + key);
                if (input) input.focus();
            });
        });

        document.querySelectorAll('.theme-editor__preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                applyPreset(btn.getAttribute('data-preset'));
            });
        });

        document.getElementById('theme-reset-btn').addEventListener('click', function () {
            if (confirm('ডিফল্ট রঙে রিসেট করতে চান?')) {
                document.getElementById('theme-reset-form').submit();
            }
        });

        updatePreview();
    });
})();
</script>
@endsection
