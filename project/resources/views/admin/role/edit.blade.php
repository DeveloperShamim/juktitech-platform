@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-tag',
        'title' => __('Edit Role'),
        'subtitle' => __('Update role name and permissions'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Manage Roles'), 'url' => route('admin.role.index')],
            ['label' => __('Edit Role'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.role.index'),
            'label' => __('Back'),
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Role Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form id="geniusform" action="{{ route('admin.role.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Name') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('Name') }}" value="{{ $data->name }}">
                            </div>
                        </div>

                        <hr>
                        <h5 class="text-center">{{ __('Permissions') }}</h5>
                        <hr>

                        @include('admin.role.partials.permissions', ['values' => $values])

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Update Role') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
