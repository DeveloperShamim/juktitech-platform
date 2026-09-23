@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-tag',
        'title' => __('Add Role'),
        'subtitle' => __('Create a new role with permissions'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Manage Roles'), 'url' => route('admin.role.index')],
            ['label' => __('Add Role'), 'url' => null],
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
                    <form id="geniusform" action="{{ route('admin.role.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Name') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('Name') }}" value="">
                            </div>
                        </div>

                        <hr>
                        <h5 class="text-center">{{ __('Permissions') }}</h5>
                        <hr>

                        @include('admin.role.partials.permissions')

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Create Role') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
