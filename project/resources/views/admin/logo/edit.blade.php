@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .logo-upload-preview {
        width: 160px;
        height: 100px;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        object-fit: contain;
        background: #f8fafc;
        padding: 8px;
    }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-pen',
        'title' => __('Language Base Logo'),
        'subtitle' => __('Update language logos'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Language Base Logo'), 'url' => route('admin.languagelogo.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-pen"></i> {{ __('Logo Upload') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="gocover"></div>
            <form id="geniusform" action="{{ route('admin.languagelogo.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="product-description">
                    <div class="body-area">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area"><h4 class="heading">{{ __('Language') }} *</h4></div>
                            </div>
                            <div class="col-lg-12">
                                <select name="language_id" id="article_language_id" disabled>
                                    <option value="">{{ __('Please Select a Language') }}</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}" {{ $data->language_id == $language->id ? 'selected' : '' }}>{{ $language->language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="language_id" value="{{ $data->language_id }}">
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="left-area"><h4 class="heading">{{ __('Header logo') }} *</h4></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="img-upload custom-image-upload">
                                    <img src="{{ $data->header_logo ? asset_image('assets/images/logo/'.$data->header_logo) : asset_image('assets/admin/images/upload.png') }}" alt="" class="logo-upload-preview mb-2">
                                    <input type="file" name="header_logo" class="img-upload form-control">
                                    <p class="text">{{ __('Prefered Size: (334x50) or Square Sized Image') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="left-area"><h4 class="heading">{{ __('Footer logo') }} *</h4></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="img-upload custom-image-upload">
                                    <img src="{{ $data->footer_logo ? asset_image('assets/images/logo/'.$data->footer_logo) : asset_image('assets/admin/images/upload.png') }}" alt="" class="logo-upload-preview mb-2">
                                    <input type="file" name="footer_logo" class="img-upload form-control">
                                    <p class="text">{{ __('Prefered Size: (334x50) or Square Sized Image') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
