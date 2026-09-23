@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-plus',
        'title' => __('Add New Team Member'),
        'subtitle' => __('Create a new team member profile'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Team'), 'url' => route('admin.team.index')],
            ['label' => __('Add Member'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Member Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Member Name') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="name" placeholder="{{ __('Enter Full Name') }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Designation') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="designation" placeholder="Ex: CEO, Manager" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Profile Photo') }} *</h4></div>
                            <div class="col-lg-7"><input type="file" class="input-field" name="photo" required></div>
                        </div>

                        <hr>
                        <h5 class="text-center mb-4 text-muted">{{ __('Social Media Links (Optional)') }}</h5>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">Facebook</h4></div>
                            <div class="col-lg-7"><input type="url" class="input-field" name="facebook" placeholder="https://facebook.com/username"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">Twitter</h4></div>
                            <div class="col-lg-7"><input type="url" class="input-field" name="twitter" placeholder="https://twitter.com/username"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">LinkedIn</h4></div>
                            <div class="col-lg-7"><input type="url" class="input-field" name="linkedin" placeholder="https://linkedin.com/in/username"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Create Member') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
