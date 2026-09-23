@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-star',
        'title' => __('Website Favicon'),
        'subtitle' => __('Set the site favicon icon'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('General Settings'), 'url' => null],
            ['label' => __('Favicon'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-star"></i> {{ __('Favicon Upload') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-logo-area">
                <div class="gocover"></div>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <form class="uplogo-form" id="geniusform" action="{{ route('admin.generalsettings.update') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="currrent-logo">
                                <h4 class="title">{{ __('Current Favicon') }} :</h4>
                                <img src="{{ $data->favicon ? asset_image('assets/images/'.$data->favicon) : asset_image('assets/images/noimage.png') }}" alt="">
                            </div>
                            <div class="set-logo">
                                <h4 class="title">{{ __('Set New Favicon') }} :</h4>
                                <input class="img-upload1" type="file" name="favicon">
                            </div>
                            <div class="submit-area">
                                <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
