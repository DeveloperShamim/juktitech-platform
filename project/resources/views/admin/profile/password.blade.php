@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-lock',
        'title' => __('Change Password'),
        'subtitle' => __('Update your admin account password'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Change Password'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-key"></i> {{ __('Password Settings') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form id="geniusform" action="{{ route('admin.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Current Password') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="password" class="input-field" name="cpass" placeholder="{{ __('Enter Current Password') }}" required value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('New Password') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="password" class="input-field" name="newpass" placeholder="{{ __('Enter New Password') }}" required value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Re-Type New Password') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="password" class="input-field" name="renewpass" placeholder="{{ __('Re-Type New Password') }}" required value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
