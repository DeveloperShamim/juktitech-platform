@extends('layouts.admin')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.license-card { border: none; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); background: #fff; }
    .card-header-main { background: #fff; border-bottom: 1px solid #f1f3f9; padding: 25px; border-radius: 15px 15px 0 0 !important; }
    .form-control-custom { border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 15px; height: 48px; transition: 0.3s; background: #f8fafc; }

    .select2-container--default .select2-selection--single { height: 48px; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; display: flex; align-items: center; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 15px; color: #495057; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; }

    #license-table { border: none; width: 100% !important; }
    #license-table thead th { background: #f8f9fc; color: #4e73df; font-weight: 700; text-transform: uppercase; font-size: 11px; border: none; padding: 14px; }
    #license-table tbody td { padding: 14px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 13px; }

    .action-btn-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-modern { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; transition: 0.3s; cursor: pointer; }
    .btn-edit { background: #eef2ff; color: #4338ca; }
    .btn-edit:hover { background: #4338ca; color: #fff; transform: translateY(-2px); }
    .btn-push { background: #ecfdf5; color: #059669; }
    .btn-push:hover { background: #059669; color: #fff; transform: translateY(-2px); }
    .btn-delete { background: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background: #e11d48; color: #fff; transform: translateY(-2px); }

    .badge-active   { background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 50px; font-weight: 600; font-size: 11px; }
    .badge-expired  { background: #fee2e2; color: #b91c1c; padding: 5px 12px; border-radius: 50px; font-weight: 600; font-size: 11px; }
    .badge-lifetime { background: #fdf4ff; color: #7e22ce; padding: 5px 12px; border-radius: 50px; font-weight: 700; font-size: 11px; border: 1px solid #e9d5ff; }

    .inline-form-group { display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap; }
    .flex-1 { flex: 1; min-width: 140px; }

    /* Signature Key */
    .sig-short { display: inline-flex; align-items: center; gap: 4px; background: #f0f0ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 3px 8px; }
    .sig-short:hover { background: #e0e7ff; }
    .sig-copy-toast { position: fixed; bottom: 20px; right: 20px; background: #4f46e5; color: #fff; padding: 10px 22px; border-radius: 10px; font-size: 14px; z-index: 9999; opacity: 0; transition: opacity .3s; pointer-events: none; }
    .sig-copy-toast.show { opacity: 1; }
    .sig-full-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; font-family: monospace; font-size: 12px; word-break: break-all; color: #4f46e5; display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .btn-copy-sig { background: #e0e7ff; border: none; border-radius: 8px; padding: 5px 10px; color: #4338ca; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
    .btn-copy-sig:hover { background: #4338ca; color: #fff; }

    /* Site Path input */
    .site-path-input { border: 1px dashed #a5b4fc; border-radius: 10px; padding: 10px 14px; height: 48px; font-family: monospace; font-size: 13px; background: #fafaff; }
    .site-path-input:focus { border-color: #6366f1; background: #fff; outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-key',
        'title' => __('License Management'),
        'subtitle' => __('Generate and manage client licenses'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('Licenses'), 'url' => null]],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('docs.license_system') }}" target="_blank" class="btn btn-sm font-weight-bold">
                <i class="fas fa-book-open mr-1"></i> ডকুমেন্টেশন
            </a>
            <a href="{{ route('admin.license.setup-guide') }}" class="btn btn-sm btn-outline-secondary font-weight-bold">
                <i class="fas fa-wrench mr-1"></i> Client Setup Guide
            </a>
            <a href="{{ route('admin.license.download-receiver') }}" class="btn btn-sm btn-outline-success font-weight-bold">
                <i class="fas fa-download mr-1"></i> Receiver Script
            </a>
        </div>
        <a href="{{ route('admin.license-renewal-orders.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
            <i class="fas fa-sync-alt mr-1"></i> রিনিউ অর্ডার
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ১. লাইসেন্স জেনারেট ফর্ম --}}
    <div class="card license-card mb-4 shadow-sm">
        <div class="card-header-main py-3">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-magic text-primary mr-2"></i>Generate License</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.license.store') }}" method="POST">
                @csrf
                {{-- Row 1: Client, Domain, Validity, Button --}}
                <div class="inline-form-group mb-3">
                    <div class="flex-1">
                        <label class="small font-weight-bold text-secondary">Target Client</label>
                        <select name="user_id" class="form-control searchable-select" required>
                            <option value="">Search & Select Client</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="small font-weight-bold text-secondary">Domain Name</label>
                        <input type="text" name="domain_name" class="form-control form-control-custom" placeholder="e.g. google.com" required>
                    </div>
                    <div>
                        <label class="small font-weight-bold text-secondary">Validity</label>
                        <select name="validity" class="form-control form-control-custom">
                            <option value="1">1 Year Plan</option>
                            <option value="2">2 Years Plan</option>
                            <option value="3">3 Years Plan</option>
                            <option value="0">♾ Lifetime</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary shadow-sm font-weight-bold">
                            <i class="fas fa-key mr-1"></i> Generate License
                        </button>
                    </div>
                </div>
                {{-- Row 2: Site Path (optional) --}}
                <div>
                    <label class="small font-weight-bold text-secondary">
                        <i class="fas fa-folder-open text-warning mr-1"></i>
                        Client Site Root Path
                        <span class="text-muted font-weight-normal">(ঐচ্ছিক — .env ফাইল অটো আপডেটের জন্য)</span>
                    </label>
                    <input type="text" name="site_path" class="form-control site-path-input"
                        placeholder="যেমন: C:\xampp\htdocs\clientsite   অথবা   /var/www/html/clientsite">
                    <small class="text-muted">সঠিক path দিলে client site এর .env ফাইলে LICENSE_KEY ও LICENSE_SIGNATURE অটো সেভ হবে।</small>
                </div>
            </form>
        </div>
    </div>

    {{-- ডাটা টেবিল --}}
    <div class="card license-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table" id="license-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Details</th>
                            <th>Domain</th>
                            <th>License Key</th>
                            <th>Signature Key</th>
                            <th>Expiry</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- এডিট মোডাল --}}
<div class="modal fade" id="edit-license-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-edit text-primary mr-2"></i>Edit License</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body p-4">
                <form id="edit-license-form">
                    @csrf
                    <input type="hidden" id="edit_license_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-secondary">Assigned Client</label>
                            <select id="edit_user_id" name="user_id" class="form-control searchable-select">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-secondary">Domain Name</label>
                            <input type="text" id="edit_domain_name" name="domain_name" class="form-control form-control-custom">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-secondary">
                            <i class="fas fa-folder-open text-warning mr-1"></i> Client Site Root Path
                            <span class="text-muted font-weight-normal">(পরিবর্তন করলে .env পুনরায় আপডেট হবে)</span>
                        </label>
                        <input type="text" id="edit_site_path" name="site_path" class="form-control site-path-input"
                            placeholder="যেমন: C:\xampp\htdocs\clientsite">
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-secondary">Signature Key</label>
                        <div class="sig-full-box">
                            <span id="edit_signature_key">—</span>
                            <button type="button" class="btn-copy-sig" onclick="copyToClipboard(document.getElementById('edit_signature_key').innerText)">
                                <i class="fas fa-copy mr-1"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-secondary">Status</label>
                        <select id="edit_status" name="status" class="form-control form-control-custom">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Update License
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Copy Toast --}}
<div id="sig-copy-toast" class="sig-copy-toast"><i class="fas fa-check mr-1"></i> Signature Key কপি হয়েছে!</div>

{{-- ডিলিট মোডাল --}}
<div class="modal fade" id="confirm-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-5 text-center">
                <div class="text-danger mb-4"><i class="fas fa-trash-alt fa-4x"></i></div>
                <h5 class="font-weight-bold">Confirm Delete?</h5>
                <div class="mt-4 d-flex justify-content-center">
                    <button type="button" class="btn btn-light border mr-2" data-dismiss="modal">No</button>
                    <button type="button" id="confirm-delete-btn" class="btn btn-danger shadow-sm">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('.searchable-select').select2({
            placeholder: "Search & Select Client",
            allowClear: true
        });

        $('#edit-license-modal').on('shown.bs.modal', function () {
            $('#edit_user_id').select2({ dropdownParent: $('#edit-license-modal') });
        });

        var table = $('#license-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.license.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'client_name', name: 'client_name' },
                { data: 'domain_info', name: 'domain_name' },
                { data: 'license_key', name: 'license_key' },
                { data: 'signature_info', name: 'signature_key', orderable: false, searchable: false },
                { data: 'expiry', name: 'expiry' },
                { data: 'status', name: 'status', className: 'text-center' },
                {
                    data: 'action', searchable: false, orderable: false, className: 'text-right',
                    render: function(data, type, row) {
                        return `<div class="action-btn-group">
                                    <button data-href="{{ url('admin/licenses/edit') }}/${row.id}" class="btn-modern btn-edit edit-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button data-id="${row.id}" class="btn-modern btn-push push-btn" title="Push to Client Site"><i class="fas fa-paper-plane"></i></button>
                                    <button data-href="{{ url('admin/licenses/delete') }}/${row.id}" class="btn-modern btn-delete delete-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </div>`;
                    }
                }
            ]
        });

        $(document).on('click', '.edit-btn', function() {
            var url = $(this).data('href');
            $.get(url, function(data) {
                $('#edit_license_id').val(data.id);
                $('#edit_domain_name').val(data.domain_name);
                $('#edit_status').val(data.status);
                $('#edit_user_id').val(data.user_id).trigger('change');
                $('#edit_signature_key').text(data.signature_key || '—');
                $('#edit_site_path').val(data.site_path || '');
                $('#edit-license-modal').modal('show');
            });
        });

        $('#edit-license-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#edit_license_id').val();
            $.ajax({
                url: '{{ url("admin/licenses/update") }}/' + id,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#edit-license-modal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: res.message || 'Updated!', showConfirmButton: false, timer: 2000 });
                }
            });
        });

        // Push বাটন
        $(document).on('click', '.push-btn', function() {
            var id = $(this).data('id');
            var btn = $(this);
            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ url("admin/licenses/push") }}/' + id,
                type: 'GET',
                success: function(res) {
                    btn.html('<i class="fas fa-paper-plane"></i>').prop('disabled', false);
                    var icon = res.status === 'success' ? 'success' : 'warning';
                    Swal.fire({ icon: icon, title: res.status === 'success' ? 'Push সফল!' : 'Push Result', text: res.message, timer: 4000, showConfirmButton: true });
                },
                error: function() {
                    btn.html('<i class="fas fa-paper-plane"></i>').prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Push করতে ব্যর্থ হয়েছে।' });
                }
            });
        });

        var deleteUrl = '';
        $(document).on('click', '.delete-btn', function() {
            deleteUrl = $(this).data('href');
            $('#confirm-delete').modal('show');
        });

        $('#confirm-delete-btn').on('click', function() {
            $.ajax({
                url: deleteUrl, type: 'GET',
                success: function(res) {
                    $('#confirm-delete').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Deleted!', showConfirmButton: false, timer: 1500 });
                }
            });
        });
    });

    function copyToClipboard(text) {
        if (!text || text === '—') return;
        navigator.clipboard.writeText(text).then(function() {
            var toast = document.getElementById('sig-copy-toast');
            toast.classList.add('show');
            setTimeout(function() { toast.classList.remove('show'); }, 2000);
        });
    }

    function copySignature(el) {
        copyToClipboard(el.getAttribute('data-full'));
    }
</script>
@endsection

