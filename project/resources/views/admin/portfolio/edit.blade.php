@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-briefcase',
        'title' => __('Edit Project'),
        'subtitle' => __('Update portfolio project'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Portfolio'), 'url' => route('admin.portfolio.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Project Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.portfolio.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Project Title') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="form-control" name="title" value="{{ $data->title }}" required></div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Category') }} *</h4></div>
                            <div class="col-lg-7">
                                <select class="form-control" name="category_id" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $data->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Current Photo') }}</h4></div>
                            <div class="col-lg-7">
                                <div class="adm-thumb adm-thumb--preview mb-2">
                                    <img src="{{ $data->photo ? asset_image('assets/images/portfolio/'.$data->photo) : asset_image('assets/images/noimage.png') }}" alt="{{ $data->title }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Update Photo') }}</h4></div>
                            <div class="col-lg-7"><input type="file" name="photo" class="form-control"></div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Project URL') }}</h4></div>
                            <div class="col-lg-7"><input type="text" class="form-control" name="url" value="{{ $data->url }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7">
                                <button class="addProductSubmit-btn w-100" type="submit">{{ __('UPDATE PROJECT') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
