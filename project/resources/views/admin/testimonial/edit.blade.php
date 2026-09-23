@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-quote-left',
        'title' => __('Edit Testimonial'),
        'subtitle' => __('Testimonial আপডেট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Testimonials'), 'url' => route('admin.testimonial.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="fe-layout fe-layout--single">
        <div class="fe-card">
            <div class="fe-card__head">
                <h3><i class="fas fa-edit"></i> {{ __('Testimonial Details') }}</h3>
                <a href="{{ route('admin.testimonial.index') }}" class="fe-link-btn"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
            </div>
            <div class="fe-card__body">
                <form action="{{ route('admin.testimonial.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" class="adm-form">
                    @csrf @method('PUT')
                    <div class="adm-form__grid">
                        <div class="adm-form__field">
                            <label>{{ __('Name') }} *</label>
                            <input type="text" class="input-field" name="name" value="{{ $testimonial->name }}" required>
                        </div>
                        <div class="adm-form__field">
                            <label>{{ __('Designation') }} *</label>
                            <input type="text" class="input-field" name="designation" value="{{ $testimonial->designation }}" required>
                        </div>
                        <div class="adm-form__field adm-form__field--select">
                            <label>{{ __('Rating') }} *</label>
                            <select class="input-field adm-select" name="rating" required>
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}" @selected($testimonial->rating == $i)>{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>
                        <div class="adm-form__field adm-form__field--full">
                            <label>{{ __('Message') }} *</label>
                            <textarea class="input-field" name="message" rows="4" required>{{ $testimonial->message }}</textarea>
                        </div>
                    </div>
                    <div class="fe-upload" style="margin-top:16px;">
                        <div class="fe-upload-preview" id="photo-preview">
                            <img src="{{ asset_image('assets/images/testimonials/'.$testimonial->photo) }}" alt="{{ $testimonial->name }}">
                        </div>
                        <label class="upload-label" for="photo-input"><i class="fas fa-upload"></i> {{ __('Change Photo') }}</label>
                        <input type="file" name="photo" id="photo-input" accept="image/*">
                        <small class="text-muted d-block mt-2">{{ __('খালি রাখলে পুরনো ছবি থাকবে') }}</small>
                    </div>
                    <div class="fe-actions">
                        <button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Update Testimonial') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('photo-input')?.addEventListener('change', function (e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (ev) {
        document.getElementById('photo-preview').innerHTML = '<img src="' + ev.target.result + '" alt="Preview">';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
