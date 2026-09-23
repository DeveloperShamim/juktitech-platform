@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-circle',
        'title' => __('Edit Profile'),
        'subtitle' => __('Update your admin profile information'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Edit Profile'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Profile Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <div class="gocover"></div>
                    <form id="geniusform" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Current Profile Image') }} *</h4></div>
                            <div class="col-lg-7">
                                <div class="img-upload">
                                    <div id="image-preview" class="img-preview">
                                        <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i> {{ __('Upload Image') }}</label>
                                        <input type="file" name="photo" class="img-upload" id="image-upload">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Name') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('User Name') }}" required value="{{ $data->name }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Email') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="email" class="input-field" name="email" placeholder="{{ __('Email Address') }}" required value="{{ $data->email }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Phone') }} *</h4></div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="phone" placeholder="{{ __('Phone Number') }}" required value="{{ $data->phone }}">
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
