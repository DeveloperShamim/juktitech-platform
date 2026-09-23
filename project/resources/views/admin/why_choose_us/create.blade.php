@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-star', 'title' => __('Add New Feature'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Why Choose Us'), 'url' => route('admin.why-choose-us.index')],
            ['label' => __('Add New'), 'url' => null],
        ],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-edit"></i> {{ __('Feature Details') }}</h3>
            <a href="{{ route('admin.why-choose-us.index') }}" class="fe-link-btn"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
        </div>
        <div class="fe-card__body">
            <form action="{{ route('admin.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" class="adm-form">
                @csrf
                <div class="adm-form__grid">
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Feature Title') }} *</label>
                        <input type="text" class="input-field" name="title" placeholder="অভিজ্ঞ টিম" required>
                    </div>
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Icon Class') }} *</label>
                        <input type="text" class="input-field" name="icon" placeholder="fas fa-users" required>
                        <small>Font Awesome ক্লাস</small>
                    </div>
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Description') }} *</label>
                        <textarea class="input-field" name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="fe-upload" style="margin-top:16px;">
                    <div class="fe-upload-preview" id="image-preview"><span class="text-muted">{{ __('No Image Selected') }}</span></div>
                    <label class="upload-label" for="image-input"><i class="fas fa-upload"></i> {{ __('Feature Image') }} *</label>
                    <input type="file" name="image" id="image-input" accept="image/*" required>
                </div>
                <div class="fe-actions"><button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Create Feature') }}</button></div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('image-input')?.addEventListener('change', function (e) {
    var file = e.target.files[0]; if (!file) return;
    var r = new FileReader();
    r.onload = function (ev) { document.getElementById('image-preview').innerHTML = '<img src="'+ev.target.result+'" alt="">'; };
    r.readAsDataURL(file);
});
</script>
@endsection
