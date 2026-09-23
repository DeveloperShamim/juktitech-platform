@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-edit',
        'title' => __('Edit Customer'),
        'subtitle' => __('Update customer profile information'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Customers'), 'url' => route('admin.customer.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Customer Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.customer.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Customer Name') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="name" value="{{ $customer->name }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Phone Number') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" class="input-field" name="phone" value="{{ $customer->phone }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Address') }}</h4></div>
                            <div class="col-lg-7"><textarea name="address" class="input-field" rows="3">{{ $customer->address }}</textarea></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-right">
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-light mr-2">{{ __('Cancel') }}</a>
                                <button class="addProductSubmit-btn" type="submit">{{ __('Update Customer') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
