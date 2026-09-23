@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-star', 'title' => __('Why Choose Us'),
        'subtitle' => __('Why Choose Us সেকশনের ফিচার ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Why Choose Us'), 'url' => null],
        ],
        'action' => ['type' => 'link', 'url' => route('admin.why-choose-us.create'), 'label' => __('Add New Feature'), 'icon' => 'fas fa-plus'],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-list"></i> {{ __('Manage Section') }}</h3>
            <span class="fe-badge-count">{{ count($features) }} {{ __('features') }}</span>
        </div>
        <div class="fe-card__body" style="padding:0 16px 16px;">
            <div class="table-responsiv">
                <table id="geniustable" class="table table-hover dt-responsive w-100">
                    <thead>
                        <tr>
                            <th width="15%">{{ __('Image') }}</th>
                            <th width="10%">{{ __('Icon') }}</th>
                            <th width="20%">{{ __('Title') }}</th>
                            <th width="35%">{{ __('Description') }}</th>
                            <th width="20%" class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($features as $feature)
                        <tr>
                            <td class="align-middle">
                                <div class="adm-thumb adm-thumb--feature">
                                    <img src="{{ $feature->image ? asset_image('assets/images/why-choose-us/'.$feature->image) : asset_image('assets/images/noimage.png') }}" alt="">
                                </div>
                            </td>
                            <td class="align-middle text-center"><div class="adm-icon-circle"><i class="{{ $feature->icon }}"></i></div></td>
                            <td class="align-middle font-weight-bold">{{ $feature->title }}</td>
                            <td class="align-middle text-muted">{{ Str::limit($feature->description, 80) }}</td>
                            <td class="align-middle text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.why-choose-us.edit', $feature->id) }}" class="btn-action edit"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $feature->id }}" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                @include('admin.partials.delete-row-modal', ['id' => $feature->id, 'action' => route('admin.why-choose-us.destroy', $feature->id)])
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="fe-empty"><i class="fas fa-star d-block"></i>{{ __('No Data Found') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
