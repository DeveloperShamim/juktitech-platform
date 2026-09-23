@extends('layouts.admin')

@section('styles')
@include('admin.staff.partials.user-mgmt-styles')
@endsection

@section('content')
<input type="hidden" id="headerdata" value="{{ __('User') }}">

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-users',
        'title' => __('All Users List'),
        'subtitle' => __('সকল ইউজার/ক্লায়েন্ট অ্যাকাউন্ট ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('User Management'), 'url' => null],
            ['label' => __('All Users'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.staff.create'),
            'label' => __('Add New User'),
            'icon' => 'fas fa-user-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.staff.partials.user-nav')

    <div class="um-card">
        <div class="um-card__head">
            <h3><i class="fas fa-list-ul"></i> {{ __('Registered Users') }}</h3>
            <span class="um-badge"><i class="fas fa-database"></i> DataTable</span>
        </div>
        <div class="um-card__body" style="padding:0 16px 16px;">
            <p class="um-hint">
                <i class="fas fa-info-circle"></i>
                Edit ক্লিক করলে মোডালে প্রোফাইল এডিট হবে। <strong>Login As</strong> দিয়ে সরাসরি ইউজার অ্যাকাউন্টে প্রবেশ করতে পারবেন।
            </p>
            <div class="table-responsiv px-2 pb-2">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
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
"use strict";
$('#geniustable').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.staff.datatables') }}',
    columns: [
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'action', searchable: false, orderable: false, className: 'text-right' }
    ],
    language: {
        processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">'
    },
    drawCallback: function () {
        $('.action-list').addClass('justify-content-end');
    }
});

$(document).on('click', '.edit[data-target="#modal1"]', function () {
    $('#modal1').find('.modal-title').html('{{ __('EDIT USER') }}');
});
</script>
@endsection
