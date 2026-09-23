@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-font',
        'title' => __('All Fonts'),
        'subtitle' => __('Manage website font families'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Website Fonts'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Fonts') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Fonts') }}</th>
                            <th>{{ __('Options') }}</th>
                        </tr>
                    </thead>
                </table>
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
        ajax: '{{ route('admin.fonts.datatables') }}',
        columns: [
            { data: 'font_family', name: 'font_family' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">',
        },
        drawCallback: function (settings) {
            $('.select').niceSelect();
        }
    });
</script>
@endsection
