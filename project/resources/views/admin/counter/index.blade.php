@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-chart-line',
        'title' => __('Counter Statistics'),
        'subtitle' => __('হোমপেজ কাউন্টার স্ট্যাটিস্টিক্স ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Counters'), 'url' => null],
        ],
        'action' => ['type' => 'link', 'url' => route('admin.counter.create'), 'label' => __('Add New Counter'), 'icon' => 'fas fa-plus'],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-list"></i> {{ __('Manage Statistics') }}</h3>
            <span class="fe-badge-count">{{ count($counters) }} {{ __('items') }}</span>
        </div>
        <div class="fe-card__body" style="padding:0 16px 16px;">
            <div class="table-responsiv">
                <table id="geniustable" class="table table-hover dt-responsive w-100">
                    <thead><tr><th>{{ __('Title') }}</th><th>{{ __('Count Value') }}</th><th class="text-right">{{ __('Options') }}</th></tr></thead>
                    <tbody>
                        @forelse($counters as $counter)
                        <tr>
                            <td class="align-middle font-weight-bold">{{ $counter->title }}</td>
                            <td class="align-middle"><span class="adm-badge-count">{{ $counter->count_value }}</span></td>
                            <td class="align-middle text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.counter.edit', $counter->id) }}" class="btn-action edit"><i class="fas fa-pen"></i></a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $counter->id }}" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                @include('admin.partials.delete-row-modal', ['id' => $counter->id, 'action' => route('admin.counter.destroy', $counter->id)])
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3"><div class="fe-empty"><i class="fas fa-chart-line d-block"></i>{{ __('No Data Found') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
