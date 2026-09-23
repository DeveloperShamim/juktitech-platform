@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-concierge-bell',
        'title' => __('Edit Service'),
        'subtitle' => __('Update service information'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Services'), 'url' => route('admin.service.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Service Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.service.update', $service->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Title') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="title" value="{{ $service->title }}" required></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Icon Class') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="icon" value="{{ $service->icon }}" required></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Description') }} *</h4></div>
                            <div class="col-lg-7"><textarea class="input-field" name="description" required>{{ $service->description }}</textarea></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7"><button class="addProductSubmit-btn" type="submit">{{ __('Update Service') }}</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
