@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-briefcase',
        'title' => __('Add Project'),
        'subtitle' => __('Create a new portfolio project'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Portfolio'), 'url' => route('admin.portfolio.index')],
            ['label' => __('Add Project'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Project Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Project Title') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="form-control" name="title" required></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Category') }} *</h4></div>
                            <div class="col-lg-7">
                                <select class="form-control" name="category_id" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Project Photo') }} *</h4></div>
                            <div class="col-lg-7"><input type="file" name="photo" class="form-control" required></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Project URL') }}</h4></div>
                            <div class="col-lg-7"><input type="url" name="url" class="form-control" placeholder="https://"></div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7"><button class="addProductSubmit-btn w-100" type="submit">{{ __('SAVE PROJECT') }}</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
