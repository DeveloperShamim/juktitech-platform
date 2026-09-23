@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-quote-left',
        'title' => __('Add New Testimonial'),
        'subtitle' => __('নতুন ক্লায়েন্ট testimonial যোগ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Testimonials'), 'url' => route('admin.testimonial.index')],
            ['label' => __('Add New'), 'url' => null],
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
                <form action="{{ route('admin.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="adm-form">
                    @csrf
                    <div class="adm-form__grid">
                        <div class="adm-form__field">
                            <label>{{ __('Client Name') }} *</label>
                            <input type="text" class="input-field" name="name" placeholder="{{ __('Enter Client Name') }}" required>
                        </div>
                        <div class="adm-form__field">
                            <label>{{ __('Designation') }} *</label>
                            <input type="text" class="input-field" name="designation" placeholder="SEO Specialist" required>
                        </div>
                        <div class="adm-form__field adm-form__field--select">
                            <label>{{ __('Rating (1-5)') }} *</label>
                            <select class="input-field adm-select" name="rating" required>
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}">{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>
                        <div class="adm-form__field adm-form__field--full">
                            <label>{{ __('Client Message') }} *</label>
                            <textarea class="input-field" name="message" rows="4" required placeholder="{{ __('Enter Message') }}"></textarea>
                        </div>
                    </div>
                    <div class="fe-upload" style="margin-top:16px;">
                        <div class="fe-upload-preview" id="photo-preview"><span class="text-muted">{{ __('Photo preview') }}</span></div>
                        <label class="upload-label" for="photo-input"><i class="fas fa-upload"></i> {{ __('Client Photo') }} *</label>
                        <input type="file" name="photo" id="photo-input" accept="image/*" required>
                    </div>
                    <div class="fe-actions">
                        <button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Save Testimonial') }}</button>
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
