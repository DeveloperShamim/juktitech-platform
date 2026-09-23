@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('Language') }}">

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-globe',
        'title' => __('All Language'),
        'subtitle' => __('Manage website language translations'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Language'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.language.create'),
            'label' => __('Add Language'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Languages') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Language') }}</th>
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
        ajax: '{{ route('language.datatables') }}',
        columns: [
            { data: 'language', name: 'language' },
            { data: 'action', searchable: false, orderable: false }
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
