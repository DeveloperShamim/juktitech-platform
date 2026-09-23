@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fab fa-facebook',
        'title' => __('Facebook Login'),
        'subtitle' => __('Configure Facebook OAuth login credentials'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Social Settings'), 'url' => null],
            ['label' => __('Facebook Login'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-cog"></i> {{ __('Facebook OAuth') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form action="{{ route('social.settings.update') }}" id="geniusform" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('App ID') }} *</h4>
                                <p class="sub-heading">{{ __('Get Your App ID from developers.facebook.com') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Enter App ID') }}" name="fclient_id" value="{{ $data->fclient_id }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('App Secret') }} *</h4>
                                <p class="sub-heading">{{ __('Get Your App Secret from developers.facebook.com') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Enter App Secret') }}" name="fclient_secret" value="{{ $data->fclient_secret }}" required>
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
                                <h4 class="heading">{{ __('Valid OAuth Redirect URI') }} *</h4>
                                <p class="sub-heading">{{ __('Copy this url and paste it to your Valid OAuth Redirect URI in developers.facebook.com.') }}</p>
                            </div>
                            <div class="col-lg-7">
                                @php
                                    $url = url('/auth/facebook/callback');
                                    $url = preg_replace("/^http:/i", "https:", $url);
                                @endphp
                                <input type="text" class="input-field" placeholder="{{ __('Enter Site URL') }}" name="fredirect" value="{{ $url }}" readonly>
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
