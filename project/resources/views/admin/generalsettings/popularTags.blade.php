@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-tags',
        'title' => __('Popular Tags'),
        'subtitle' => __('Manage website popular tags'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('General Settings'), 'url' => null],
            ['label' => __('Popular Tags'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-tags"></i> {{ __('Tags') }}</h3>
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
                                        <label for="tags">{{ __('Tags') }} <small>{{ __('(Seperated By Comma(,))') }}</small></label>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="tawk-area">
                                    <input type="text" placeholder="{{ __('Tags') }}" class="tags" id="tags" name="tags" value="{{ $data->tags }}">
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

@section('scripts')
<script src="{{ asset_image('assets/admin/js/tagify.js') }}"></script>
<script>
    $('.tags').tagify();
</script>
@endsection
