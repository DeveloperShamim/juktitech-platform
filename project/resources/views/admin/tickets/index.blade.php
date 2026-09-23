@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .ticket-user-avatar {
        width: 35px;
        height: 35px;
        background: #eef2ff;
        color: #4e73df;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 12px;
    }
    .adm-pro .ticket-status-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
        display: inline-block;
    }
    .adm-pro .ticket-status-pending { background: #fff5e6; color: #ff9900; }
    .adm-pro .ticket-status-replied { background: #e6fcf5; color: #08d19e; }
    .adm-pro .ticket-status-closed { background: #f1f5f9; color: #64748b; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-life-ring',
        'title' => __('Support Helpdesk'),
        'subtitle' => __('Monitoring customer inquiries in real-time'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Tickets'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Tickets') }}</h3>
            <button class="btn btn-light btn-sm" type="button" onclick="location.reload();">
                <i class="fas fa-sync-alt mr-1 text-primary"></i> {{ __('Sync Data') }}
            </button>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" id="ticket-table" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Ref. #') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Ticket Subject') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Manage') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#ticket-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.tickets.index') }}",
            columns: [
                { data: 'id', name: 'id', render: function(data) { return `<b>#${data}</b>`; } },
                {
                    data: 'user_name',
                    render: function(data) {
                        return `<div class="d-flex align-items-center"><div class="ticket-user-avatar">${data ? data.charAt(0) : 'U'}</div><b>${data}</b></div>`;
                    }
                },
                {
                    data: 'subject',
                    render: function(data, type, row) {
                        let lastUpdate = row.updated_at ? moment(row.updated_at).fromNow() : 'No update';
                        return `<div>${data}</div><small class="text-muted">${lastUpdate}</small>`;
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        return `<span class="ticket-status-badge ticket-status-${data.toLowerCase()}">${data}</span>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
            ]
        });

        $(document).on('click', '.delete-ticket', function(e) {
            e.preventDefault();

            var url = $(this).attr('data-url');

            if(!url) {
                alert("Error: Delete URL not found!");
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "টিকেট এবং মেসেজ হিস্ট্রি সব মুছে যাবে!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'হ্যাঁ, ডিলিট করুন!',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(res) {
                            if(res.status == 'success') {
                                table.ajax.reload(null, false);
                                Swal.fire('Deleted!', res.message, 'success');
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'সার্ভারে কানেক্ট করা যাচ্ছে না।', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
