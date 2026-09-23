@extends('layouts.admin')

@section('styles')
<style>
.update-card { border: none; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); background: #fff; }
    .card-header-main { background: #fff; border-bottom: 1px solid #f1f3f9; padding: 25px; border-radius: 15px 15px 0 0 !important; }
    .form-control-custom { border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 15px; height: 48px; }
    #updates-table { border: none; width: 100% !important; }
    #updates-table thead th { background: #f8f9fc; color: #4e73df; font-weight: 700; text-transform: uppercase; font-size: 11px; border: none; padding: 18px; }
    #updates-table tbody td { padding: 18px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .badge-success { background: #dcfce7; color: #15803d; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 12px; }
    .badge-secondary { background: #e5e7eb; color: #6b7280; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 12px; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-cloud-upload-alt',
        'title' => __('Script Update Files'),
        'subtitle' => __('Upload and manage script updates'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('Script Updates'), 'url' => null]],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <p class="text-muted small mb-3">ইকমার্স স্ক্রিপ্টের আপডেট জিপ ফাইল আপলোড করুন। শুধু বৈধ <code>LICENSE_KEY</code> + <code>LICENSE_SIGNATURE</code> যুক্ত সাইট আপডেট পাবে। <a href="{{ route('docs.script_update') }}" target="_blank">ডকুমেন্টেশন</a></p>

    <div class="adm-panel mb-4">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-upload"></i> {{ __('Release New Update') }}</h3>
        </div>
        <div class="adm-panel__body p-4">
            <form action="{{ route('admin.script-updates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-secondary">Script Name</label>
                        <input type="text" name="script_name" class="form-control form-control-custom" placeholder="e.g. Ecommerce Pro" required>
                        @error('script_name')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-secondary">Version</label>
                        <input type="text" name="version" class="form-control form-control-custom" placeholder="e.g. 1.0.1, 1.2.0" required>
                        @error('version')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-secondary">ZIP File</label>
                        <input type="file" name="update_file" class="form-control form-control-custom" accept=".zip" required>
                        <small class="text-muted">Max 1GB, ZIP only</small>
                        @error('update_file')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-secondary">Release Notes (optional)</label>
                        <input type="text" name="release_notes" class="form-control form-control-custom" placeholder="What's new in this update">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Update Releases') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table" id="updates-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Script Name</th>
                            <th>Version</th>
                            <th>File</th>
                            <th class="text-center">Status</th>
                            <th>Released</th>
                            <th class="text-right">Actions</th>
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
    $(function() {
        $('#updates-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.script-updates.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'script_name', name: 'script_name' },
                { data: 'version', name: 'version' },
                { data: 'file', name: 'file_path' },
                { data: 'status', name: 'is_active', className: 'text-center' },
                { data: 'date', name: 'created_at' },
                { data: 'action', searchable: false, orderable: false, className: 'text-right' }
            ]
        });
    });
</script>
@endsection

