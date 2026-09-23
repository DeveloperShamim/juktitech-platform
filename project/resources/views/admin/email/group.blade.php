@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-paper-plane',
        'title' => __('Group Email To Subscribers'),
        'subtitle' => __('Send bulk emails to customers or vendors'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Email Settings'), 'url' => null],
            ['label' => __('Group Email'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Compose Email') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form id="geniusform" action="{{ route('admin.subscriber.sendemail') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Select User Type') }} *</h4></div>
                            <div class="col-lg-7">
                                <select name="type" class="input-field" required>
                                    <option value="">{{ __('Choose User Type') }}</option>
                                    <option value="0">{{ __('Customers') }}</option>
                                    <option value="1">{{ __('Vendors') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Email Subject') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="subject" placeholder="{{ __('Email Subject') }}" required value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right">
                                <h4 class="heading">{{ __('Email Body') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                            <div class="col-lg-7">
                                <textarea class="nic-edit" name="body" placeholder="{{ __('Email Body') }}"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Send Email') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
