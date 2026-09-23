@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
@php $activeTab = request('tab', 'service_section'); @endphp
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-th-large',
        'title' => __('Homepage Sections'),
        'subtitle' => __('হোমপেজের সকল সেকশন হেডিং ও টেক্সট এডিট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Homepage Sections'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.partials.frontend-setup-nav')

    <div class="fe-layout fe-layout--single">
        <div class="fe-card">
            <div class="fe-card__head">
                <h3><i class="fas fa-edit"></i> সেকশন কন্টেন্ট</h3>
                <a href="{{ route('admin.about.edit') }}" class="fe-link-btn">
                    <i class="fas fa-star"></i> হিরো সেকশন
                </a>
            </div>

            <div class="fe-tabs" role="tablist">
                @foreach($labels as $key => $label)
                    <button type="button"
                            class="fe-tab {{ $activeTab === $key ? 'is-active' : '' }}"
                            data-tab="{{ $key }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @foreach($labels as $key => $label)
                @php $sec = $sections[$key]; @endphp
                <div class="fe-panel {{ $activeTab === $key ? 'is-active' : '' }}" data-panel="{{ $key }}">
                    <form action="{{ route('admin.homepage.sections.update', $key) }}" method="POST" class="adm-form">
                        @csrf

                        <div class="adm-form__grid">
                            <div class="adm-form__field adm-form__field--full">
                                <label>ব্যাজ / ছোট লেবেল</label>
                                <input type="text" class="input-field" name="badge" value="{{ old('badge', $sec->badge) }}" placeholder="আমাদের সেবা">
                            </div>
                            <div class="adm-form__field adm-form__field--full">
                                <label>টাইটেল <span class="text-danger">*</span></label>
                                <textarea class="input-field" name="title" rows="2" required>{{ old('title', $sec->title) }}</textarea>
                                <small>HTML accent: <code>&lt;span class="home-services__accent"&gt;...&lt;/span&gt;</code></small>
                            </div>
                            <div class="adm-form__field adm-form__field--full">
                                <label>বিবরণ</label>
                                <textarea class="input-field" name="description" rows="3">{{ old('description', $sec->description) }}</textarea>
                            </div>
                        </div>

                        @if($key === 'service_section')
                            <div class="fe-item" style="margin-top:18px;">
                                <div class="fe-item__label"><i class="fas fa-bolt"></i> হাইলাইট পয়েন্ট</div>
                                @php $highlights = $sec->extra['highlights'] ?? []; @endphp
                                @for($i = 0; $i < max(4, count($highlights)); $i++)
                                    @php $h = $highlights[$i] ?? ['icon' => '', 'text' => '']; @endphp
                                    <div class="adm-form__grid" style="margin-bottom:10px;">
                                        <div class="adm-form__field">
                                            <label>আইকন #{{ $i + 1 }}</label>
                                            <input type="text" class="input-field" name="highlight_icon[]" value="{{ $h['icon'] ?? '' }}" placeholder="fas fa-bolt">
                                        </div>
                                        <div class="adm-form__field">
                                            <label>টেক্সট</label>
                                            <input type="text" class="input-field" name="highlight_text[]" value="{{ $h['text'] ?? '' }}">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @endif

                        @if($key === 'steps_section')
                            <div class="fe-item" style="margin-top:18px;">
                                <div class="fe-item__label"><i class="fas fa-shoe-prints"></i> ৩ ধাপ</div>
                                @php $steps = $sec->extra['steps'] ?? []; @endphp
                                @for($i = 0; $i < max(3, count($steps)); $i++)
                                    @php $step = $steps[$i] ?? []; @endphp
                                    <div class="fe-item" style="background:#f8fafc;margin-bottom:10px;">
                                        <div class="fe-item__label"><i class="fas fa-circle"></i> ধাপ #{{ $i + 1 }}</div>
                                        <div class="adm-form__grid">
                                            <div class="adm-form__field">
                                                <label>নম্বর</label>
                                                <input type="text" class="input-field" name="step_num[]" value="{{ $step['num'] ?? '' }}">
                                            </div>
                                            <div class="adm-form__field">
                                                <label>আইকন</label>
                                                <input type="text" class="input-field" name="step_icon[]" value="{{ $step['icon'] ?? '' }}">
                                            </div>
                                            <div class="adm-form__field adm-form__field--select">
                                                <label>রঙ ক্লাস</label>
                                                <select class="input-field adm-select" name="step_color[]">
                                                    @foreach(['is-blue','is-purple','is-green'] as $color)
                                                        <option value="{{ $color }}" @selected(($step['color'] ?? '') === $color)>{{ $color }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="adm-form__field adm-form__field--full">
                                                <label>টাইটেল</label>
                                                <input type="text" class="input-field" name="step_title[]" value="{{ $step['title'] ?? '' }}">
                                            </div>
                                            <div class="adm-form__field adm-form__field--full">
                                                <label>বিবরণ</label>
                                                <textarea class="input-field" name="step_desc[]" rows="2">{{ $step['desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @endif

                        @if($key === 'portfolio_section')
                            <div class="adm-form__grid" style="margin-top:18px;">
                                <div class="adm-form__field">
                                    <label>ফিল্টার "সকল" টেক্সট</label>
                                    <input type="text" class="input-field" name="filter_all" value="{{ $sec->extra['filter_all'] ?? 'সকল' }}">
                                </div>
                                <div class="adm-form__field">
                                    <label>আরো দেখুন টেক্সট</label>
                                    <input type="text" class="input-field" name="more_text" value="{{ $sec->extra['more_text'] ?? '' }}">
                                </div>
                            </div>
                        @endif

                        @if($key === 'team_section')
                            <div class="adm-form__grid" style="margin-top:18px;">
                                <div class="adm-form__field">
                                    <label>আরো দেখুন টেক্সট</label>
                                    <input type="text" class="input-field" name="more_text" value="{{ $sec->extra['more_text'] ?? '' }}">
                                </div>
                                <div class="adm-form__field">
                                    <label>আরো দেখুন URL</label>
                                    <input type="text" class="input-field" name="more_url" value="{{ $sec->extra['more_url'] ?? '/team' }}">
                                </div>
                            </div>
                        @endif

                        @if($key === 'cta_section')
                            <div class="adm-form__grid" style="margin-top:18px;">
                                <div class="adm-form__field">
                                    <label>কল বাটন টেক্সট</label>
                                    <input type="text" class="input-field" name="call_text" value="{{ $sec->extra['call_text'] ?? 'কল করুন' }}">
                                </div>
                                <div class="adm-form__field">
                                    <label>মেসেজ বাটন টেক্সট</label>
                                    <input type="text" class="input-field" name="message_text" value="{{ $sec->extra['message_text'] ?? 'মেসেজ পাঠান' }}">
                                </div>
                            </div>
                        @endif

                        <div class="fe-actions">
                            <button class="fe-save" type="submit">
                                <i class="fas fa-save"></i> {{ $label }} আপডেট
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    document.querySelectorAll('.fe-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var key = tab.getAttribute('data-tab');
            document.querySelectorAll('.fe-tab').forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
            });
            document.querySelectorAll('.fe-panel').forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-panel') === key);
            });
            if (window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', key);
                window.history.replaceState({}, '', url);
            }
        });
    });
})();
</script>
@endsection
