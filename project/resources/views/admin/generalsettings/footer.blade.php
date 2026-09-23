@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-shoe-prints',
        'title' => __('Website Footer'),
        'subtitle' => __('Manage footer text and copyright'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('General Settings'), 'url' => null],
            ['label' => __('Footer'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-shoe-prints"></i> {{ __('Footer Settings') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form class="uplogo-form" id="geniusform" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="left-area">
                                    <h4 class="heading">
                                        {{ __('Copyright Text') }} *
                                        <p class="sub-heading">{{ __('In Any Language') }}</p>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="tawk-area">
                                    <textarea name="copyright_text" required="">{{ $data->copyright_text }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="left-area">
                                    <h4 class="heading">
                                        {{ __('Footer Text') }} *
                                        <p class="sub-heading">{{ __('In Any Language') }}</p>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="tawk-area">
                                    <textarea class="nic-edit" name="footer_text">{{ $data->footer_text }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
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
