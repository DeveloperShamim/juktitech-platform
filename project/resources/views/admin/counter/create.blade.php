@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-chart-line', 'title' => __('Add New Counter'),
        'subtitle' => __('নতুন কাউন্টার যোগ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Counters'), 'url' => route('admin.counter.index')],
            ['label' => __('Add New'), 'url' => null],
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
            <form action="{{ route('admin.counter.store') }}" method="POST" class="adm-form">
                @csrf
                <div class="adm-form__grid">
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Title') }} *</label>
                        <input type="text" class="input-field" name="title" placeholder="হ্যাপি ক্লায়েন্ট" required>
                    </div>
                    <div class="adm-form__field adm-form__field--full">
                        <label>{{ __('Count Value') }} *</label>
                        <input type="text" class="input-field" name="count_value" placeholder="৫০০+" required>
                    </div>
                </div>
                <div class="fe-actions"><button class="fe-save" type="submit"><i class="fas fa-save"></i> {{ __('Save Counter') }}</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
