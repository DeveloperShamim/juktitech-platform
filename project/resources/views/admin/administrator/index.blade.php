@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('Administrator') }}">

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-shield',
        'title' => __('Administrator'),
        'subtitle' => __('Manage administrator accounts and roles'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Manage Administrator'), 'url' => null],
        ],
        'action' => [
            'type' => 'modal',
            'label' => __('Add New Staff'),
            'icon' => 'fas fa-plus',
            'modal_href' => route('admin.administator.create'),
            'modal_target' => '#modal1',
            'modal_id' => 'add-data',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Administrators') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Options') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.modal-crud')
@include('admin.partials.modal-delete', [
    'title' => __('Confirm Delete'),
    'message' => __('You are about to delete this User.'),
])
@endsection

@section('scripts')
<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.administator.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'role_id', name: 'role_id' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">'
        }
    });
</script>
@endsection
