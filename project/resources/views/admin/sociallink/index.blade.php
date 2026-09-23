@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<input type="hidden" id="headerdata" value="{{ __('LINK') }}">
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-share-alt',
        'title' => __('Social Links'),
        'subtitle' => __('সোশ্যাল মিডিয়া প্রোফাইল লিংক ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Social Links'), 'url' => null],
        ],
        'action' => [
            'type' => 'modal', 'label' => __('Add New Link'), 'icon' => 'fas fa-plus',
            'modal_href' => route('social.link.create'), 'modal_target' => '#modal1', 'modal_id' => 'add-data',
        ],
    ])

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-card">
        <div class="fe-card__head"><h3><i class="fas fa-list"></i> {{ __('All Social Links') }}</h3></div>
        <div class="fe-card__body" style="padding:0 16px 16px;">
            <div class="table-responsiv">
                <table id="example" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr><th>{{ __('Name') }}</th><th>{{ __('Link') }}</th><th>{{ __('Icon') }}</th><th>{{ __('Options') }}</th></tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.modal-crud')
@include('admin.partials.modal-delete', ['title' => __('Confirm Delete'), 'message' => __('You are about to delete this Link.')])
@endsection

@section('scripts')
<script type="text/javascript">
"use strict";
$('#example').DataTable({
    ordering: false, processing: true, serverSide: true,
    ajax: '{{ route('social.link.datatables') }}',
    columns: [
        { data: 'name', name: 'name' },
        { data: 'link', name: 'link' },
        { data: 'icon', name: 'icon' },
        { data: 'action', searchable: false, orderable: false }
    ],
    language: { processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">' },
    drawCallback: function () { $('.select').niceSelect(); }
});
</script>
@endsection
