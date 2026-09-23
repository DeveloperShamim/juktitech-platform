@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-crown', 'title' => __('Edit Brand'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Brands'), 'url' => route('admin.brand.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-edit"></i> {{ __('Brand Details') }}</h3>
            <a href="{{ route('admin.brand.index') }}" class="fe-link-btn"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
        </div>
        <div class="fe-card__body">
            <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="adm-form">
                @csrf @method('PUT')
                <div class="adm-form__grid">
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Brand Name') }} *</label>
                        <input type="text" class="input-field" name="name" value="{{ $brand->name }}" required>
                    </div>
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Website URL') }}</label>
                        <input type="text" class="input-field" name="url" value="{{ $brand->url }}">
                    </div>
                </div>
                <div class="fe-upload" style="margin-top:16px;">
                    <div class="fe-upload-preview" id="logo-preview">
                        <img src="{{ asset_image('assets/images/brands/'.$brand->photo) }}" alt="Logo">
                    </div>
                    <label class="upload-label" for="logo-input"><i class="fas fa-upload"></i> {{ __('Change Logo') }}</label>
                    <input type="file" name="photo" id="logo-input" accept="image/*">
                </div>
                <div class="fe-actions"><button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Update Brand') }}</button></div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('logo-input')?.addEventListener('change', function (e) {
    var file = e.target.files[0]; if (!file) return;
    var r = new FileReader();
    r.onload = function (ev) { document.getElementById('logo-preview').innerHTML = '<img src="'+ev.target.result+'" alt="">'; };
    r.readAsDataURL(file);
});
</script>
@endsection
