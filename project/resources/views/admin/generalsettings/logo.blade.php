@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-image',
        'title' => __('Website Logo'),
        'subtitle' => __('Upload header, footer and OG banner logos'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('General Settings'), 'url' => null],
            ['label' => __('Website Logo'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-image"></i> {{ __('Logo Uploads') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-logo-area">
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="special-box bg-gray">
                            <div class="heading-area">
                                <h4 class="title">{{ __('Header Logo') }}</h4>
                            </div>
                            <form class="uplogo-form" id="geniusformHeaderLogo" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="currrent-logo">
                                    <img src="{{ $data->logo ? asset_image('assets/images/logo/'.$data->logo) : asset_image('assets/images/noimage.png') }}" alt="Header Logo">
                                </div>
                                <div class="set-logo">
                                    <input class="img-upload1" type="file" name="logo">
                                </div>
                                <div class="submit-area mb-4">
                                    <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="special-box bg-gray">
                            <div class="heading-area">
                                <h4 class="title">{{ __('Footer Logo') }}</h4>
                            </div>
                            <div class="gocover"></div>
                            <form class="uplogo-form" id="geniusformFooterLogo" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="currrent-logo">
                                    <img src="{{ $data->footer_logo ? asset_image('assets/images/logo/'.$data->footer_logo) : asset_image('assets/images/noimage.png') }}" alt="Footer Logo">
                                </div>
                                <div class="set-logo">
                                    <input class="img-upload1" type="file" name="footer_logo">
                                </div>
                                <div class="submit-area mb-4">
                                    <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="special-box bg-gray">
                            <div class="heading-area">
                                <h4 class="title">{{ __('OG Baner') }}</h4>
                            </div>
                            <div class="gocover"></div>
                            <form class="uplogo-form" id="geniusformOgBanner" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="currrent-logo">
                                    <img src="{{ $data->og_baner ? asset_image('assets/images/logo/'.$data->og_baner) : asset_image('assets/images/noimage.png') }}" alt="OG Banner">
                                </div>
                                <div class="set-logo">
                                    <input class="img-upload1" type="file" name="og_baner">
                                </div>
                                <div class="submit-area mb-4">
                                    <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="special-box bg-gray">
                            <div class="heading-area">
                                <h4 class="title">{{ __('Website Favicon') }}</h4>
                            </div>
                            <div class="gocover"></div>
                            <form class="uplogo-form" id="geniusformFavicon" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="currrent-logo text-center p-3">
                                    <img src="{{ $data->favicon ? asset_image('assets/images/'.$data->favicon) : asset_image('assets/images/noimage.png') }}" alt="Favicon" style="max-height: 64px; max-width: 64px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px; background: #fff;">
                                </div>
                                <div class="set-logo">
                                    <input class="img-upload1" type="file" name="favicon" accept=".ico,.png,.jpg,.jpeg,.svg">
                                    <small class="text-muted d-block mt-1">Recommended: 32x32 / 64x64 (.ico, .png)</small>
                                </div>
                                <div class="submit-area mb-4">
                                    <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
