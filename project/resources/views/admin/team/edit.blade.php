@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-edit',
        'title' => __('Edit Team Member'),
        'subtitle' => __('Update team member profile'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Team'), 'url' => route('admin.team.index')],
            ['label' => __('Edit'), 'url' => null],
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
                    <form action="{{ route('admin.team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Name') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="name" value="{{ $team->name }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Designation') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="designation" value="{{ $team->designation }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Current Photo') }}</h4></div>
                            <div class="col-lg-7">
                                <div class="adm-thumb adm-thumb--preview mb-2">
                                    <img src="{{ asset_image('assets/images/team/'.$team->photo) }}" alt="{{ $team->name }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Change Photo') }}</h4></div>
                            <div class="col-lg-7"><input type="file" class="input-field" name="photo"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">Facebook</h4></div>
                            <div class="col-lg-7"><input type="url" class="input-field" name="facebook" value="{{ $team->facebook }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">LinkedIn</h4></div>
                            <div class="col-lg-7"><input type="url" class="input-field" name="linkedin" value="{{ $team->linkedin }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Update Member') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
