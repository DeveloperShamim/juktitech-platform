@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-tags', 'title' => __('Edit Pricing Plan'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Pricing'), 'url' => route('admin.pricing.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-edit"></i> {{ __('Plan Details') }}</h3>
            <a href="{{ route('admin.pricing.index') }}" class="fe-link-btn"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
        </div>
        <div class="fe-card__body">
            <form action="{{ route('admin.pricing.update', $data->id) }}" method="POST" class="adm-form">
                @csrf @method('PUT')
                <div class="adm-form__grid">
                    <div class="adm-form__field"><label>{{ __('Plan Title') }} *</label><input type="text" class="input-field" name="title" value="{{ $data->title }}" required></div>
                    <div class="adm-form__field"><label>{{ __('Price') }} *</label><input type="text" class="input-field" name="price" value="{{ $data->price }}" required></div>
                    <div class="adm-form__field adm-form__field--full"><label>{{ __('Features') }} *</label><textarea class="input-field" name="features" rows="6" required>{{ $data->features }}</textarea><small>কমা (,) দিয়ে আলাদা করুন</small></div>
                    <div class="adm-form__field adm-form__field--full"><label>{{ __('Order Link') }}</label><input type="url" class="input-field" name="order_link" value="{{ $data->order_link }}"></div>
                    <div class="adm-form__field adm-form__field--full">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ $data->is_featured == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="is_featured">{{ __('Highlight as Best Value?') }}</label>
                        </div>
                    </div>
                </div>
                <div class="fe-actions"><button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Update Plan') }}</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
