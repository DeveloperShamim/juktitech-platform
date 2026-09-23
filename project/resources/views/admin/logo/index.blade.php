@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('LANGUAGE LOGO') }}">

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-language',
        'title' => __('Language Base Logo'),
        'subtitle' => __('Manage per-language header and footer logos'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Language Base Logo'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.languagelogo.create'),
            'label' => __('Set Logos'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Language Logos') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Language Name') }}</th>
                            <th>{{ __('Header logo') }}</th>
                            <th>{{ __('Footer logo') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-scale" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-scale" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this Row') }}.</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
"use strict";
var table = $('#geniustable').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.languagelogo.datatables') }}',
    columns: [
        {data: 'language_id', name: 'language_id'},
        {data: 'header_logo', name: 'header_logo'},
        {data: 'footer_logo', name: 'footer_logo'},
        {data: 'action', searchable: false, orderable: false}
    ],
    language: {
        processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">'
    },
    drawCallback: function (settings) {
        $('.select').niceSelect();
    }
});
</script>
@endsection
