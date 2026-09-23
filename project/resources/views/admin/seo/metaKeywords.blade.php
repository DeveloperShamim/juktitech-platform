@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-search',
        'title' => __('Meta Keywords'),
        'subtitle' => __('Configure SEO meta description and keywords'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('SEO Tools'), 'url' => null],
            ['label' => __('Meta Keywords'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('SEO Meta Settings') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form class="uplogo-form" id="geniusform" action="{{ route('seo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Meta Description') }} *</h4>
                            </div>
                            <div class="col-lg-7">
                                <div class="tawk-area">
                                    <textarea name="meta_description">{{ $data->meta_description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Meta Keywords') }} *</h4>
                            </div>
                            <div class="col-lg-7">
                                <div class="tawk-area">
                                    <textarea name="meta_keys">{{ $data->meta_keys }}</textarea>
                                </div>
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
