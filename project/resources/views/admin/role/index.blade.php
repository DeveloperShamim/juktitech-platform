@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('ROLE') }}">

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-tag',
        'title' => __('Roles'),
        'subtitle' => __('Manage roles and permissions'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Manage Roles'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.role.create'),
            'label' => __('Add New Role'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Roles') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th width="50%">{{ __('Permissions') }}</th>
                            <th>{{ __('Options') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.modal-delete', [
    'title' => __('Confirm Delete'),
    'message' => __('You are about to delete this Role.'),
])
@endsection

@section('scripts')
<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.role.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'section', name: 'section' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">'
        }
    });
</script>
@endsection
