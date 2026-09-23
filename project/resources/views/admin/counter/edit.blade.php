@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-chart-line', 'title' => __('Edit Counter'),
        'subtitle' => __('কাউন্টার আপডেট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Counters'), 'url' => route('admin.counter.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-edit"></i> {{ __('Counter Details') }}</h3>
            <a href="{{ route('admin.counter.index') }}" class="fe-link-btn"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
        </div>
        <div class="fe-card__body">
            <form action="{{ route('admin.counter.update', $counter->id) }}" method="POST" class="adm-form">
                @csrf @method('PUT')
                <div class="adm-form__grid">
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Title') }} *</label>
                        <input type="text" class="input-field" name="title" value="{{ $counter->title }}" required>
                    </div>
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Count Value') }} *</label>
                        <input type="text" class="input-field" name="count_value" value="{{ $counter->count_value }}" required>
                    </div>
                </div>
                <div class="fe-actions"><button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Update Counter') }}</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
