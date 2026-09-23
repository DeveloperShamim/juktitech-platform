@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fab fa-google',
        'title' => __('Google Login'),
        'subtitle' => __('Configure Google OAuth login credentials'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Social Settings'), 'url' => null],
            ['label' => __('Google Login'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-cog"></i> {{ __('Google OAuth') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form action="{{ route('social.settings.update') }}" id="geniusform" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Client ID') }} *</h4>
                                <p class="sub-heading">{{ __('Get Your Client ID from console.cloud.google.com') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Enter Client ID') }}" name="gclient_id" value="{{ $data->gclient_id }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Client Secret') }} *</h4>
                                <p class="sub-heading">{{ __('Get Your Client Secret from console.cloud.google.com') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Enter Client Secret') }}" name="gclient_secret" value="{{ $data->gclient_secret }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Website URL') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Website URL') }}" value="{{ url('/') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Redirect URL') }} *</h4>
                                <p class="sub-heading">{{ __('Copy this url and paste it to your Redirect URL in console.cloud.google.com.') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Enter Site URL') }}" name="gredirect" value="{{ url('/auth/google/callback') }}" readonly>
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
