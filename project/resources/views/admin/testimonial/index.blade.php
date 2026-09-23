@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-quote-left',
        'title' => __('Testimonials'),
        'subtitle' => __('ক্লায়েন্ট রিভিউ ও testimonial ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Testimonials'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.testimonial.create'),
            'label' => __('Add New Testimonial'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-card">
        <div class="fe-card__head">
            <h3><i class="fas fa-list"></i> {{ __('Manage Testimonials') }}</h3>
            <span class="fe-badge-count">{{ count($testimonials) }} {{ __('items') }}</span>
        </div>
        <div class="fe-card__body" style="padding:0 16px 16px;">
            <div class="table-responsiv">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Client') }}</th>
                            <th>{{ __('Rating') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="adm-thumb adm-thumb--avatar mr-3">
                                        <img src="{{ asset_image('assets/images/testimonials/'.$testimonial->photo) }}" alt="Photo">
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $testimonial->name }}</div>
                                        <small class="text-muted">{{ $testimonial->designation }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="text-warning">
                                    @for($i=1; $i<=$testimonial->rating; $i++)
                                        <i class="fas fa-star fa-xs"></i>
                                    @endfor
                                </span>
                            </td>
                            <td class="align-middle"><div class="adm-text-truncate">{{ $testimonial->message }}</div></td>
                            <td class="align-middle text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.testimonial.edit', $testimonial->id) }}" class="btn-action edit" title="Edit"><i class="fas fa-pen"></i></a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $testimonial->id }}" class="btn-action delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                @include('admin.partials.delete-row-modal', [
                                    'id' => $testimonial->id,
                                    'action' => route('admin.testimonial.destroy', $testimonial->id),
                                ])
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="fe-empty"><i class="fas fa-quote-left d-block"></i>{{ __('No Data Found') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
