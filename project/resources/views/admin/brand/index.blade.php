@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-crown', 'title' => __('Brands'),
        'subtitle' => __('ব্র্যান্ড লোগো ও লিংক ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Brands'), 'url' => null],
        ],
        'action' => ['type' => 'link', 'url' => route('admin.brand.create'), 'label' => __('Add New Brand'), 'icon' => 'fas fa-plus'],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-list"></i> {{ __('Manage Brands') }}</h3>
            <span class="fe-badge-count">{{ count($brands) }} {{ __('items') }}</span>
        </div>
        <div class="fe-card__body" style="padding:0 16px 16px;">
            <div class="table-responsiv">
                <table class="table table-hover dt-responsive w-100">
                    <thead><tr><th>{{ __('Brand Name') }}</th><th>{{ __('Logo') }}</th><th>{{ __('URL') }}</th><th class="text-right">{{ __('Options') }}</th></tr></thead>
                    <tbody>
                        @foreach($brands as $brand)
                        <tr>
                            <td class="font-weight-bold">{{ $brand->name }}</td>
                            <td><div class="adm-thumb adm-thumb--logo"><img src="{{ asset_image('assets/images/brands/'.$brand->photo) }}" alt="{{ $brand->name }}"></div></td>
                            <td>
                                @if($brand->url)
                                    <a href="{{ $brand->url }}" target="_blank" class="adm-link"><i class="fas fa-external-link-alt"></i> {{ __('Visit Site') }}</a>
                                @else <span class="text-muted">N/A</span> @endif
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.brand.edit', $brand->id) }}" class="btn-action edit"><i class="fas fa-pen"></i></a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $brand->id }}" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                @include('admin.partials.delete-row-modal', ['id' => $brand->id, 'action' => route('admin.brand.destroy', $brand->id)])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
