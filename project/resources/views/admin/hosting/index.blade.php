@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-server',
        'title' => __('Hosting Management'),
        'subtitle' => __('Manage all customer hosting services'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Customer Hostings') }}</h3>
            <form action="{{ route('admin.user.hostings') }}" method="GET" class="form-inline d-flex flex-wrap align-items-center">
                <select name="status" class="form-control form-control-sm mr-2 mb-2 mb-md-0" onchange="this.form.submit()">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Suspended" {{ request('status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="Expired" {{ request('status') === 'Expired' ? 'selected' : '' }}>Expired</option>
                    <option value="Terminated" {{ request('status') === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                <div class="input-group input-group-sm mb-2 mb-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Domain, Name, Email, cPanel user...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="hosting-table" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Domain & Package') }}</th>
                            <th>{{ __('Server & IP') }}</th>
                            <th>{{ __('Pricing') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hostings as $hosting)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="adm-thumb adm-thumb--avatar mr-3">
                                        <span>{{ strtoupper(substr($hosting->user->name ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $hosting->user->name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ $hosting->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary">{{ $hosting->domain }}</div>
                                <div class="small text-muted"><i class="fas fa-box"></i> {{ $hosting->plan->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light border text-dark font-weight-bold">{{ $hosting->ip_address ?? '0.0.0.0' }}</span>
                                <div class="small text-muted mt-1">{{ $hosting->server->name ?? 'No Server' }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold">৳ {{ number_format($hosting->amount, 2) }}</div>
                                <small class="text-muted text-capitalize">{{ $hosting->billing_cycle }}</small>
                            </td>
                            <td>
                                @php $statusClass = strtolower($hosting->status); @endphp
                                <span class="badge badge-{{ $statusClass === 'active' ? 'success' : ($statusClass === 'pending' ? 'warning' : ($statusClass === 'suspended' || $statusClass === 'expired' ? 'danger' : 'secondary')) }}">
                                    @if($statusClass === 'suspended')
                                        Suspended (ডিঅ্যাকটিভ)
                                    @elseif($statusClass === 'expired')
                                        Expired (ডিঅ্যাকটিভ)
                                    @else
                                        {{ $hosting->status }}
                                    @endif
                                </span>
                                @if($hosting->next_due_date)
                                    <div class="small {{ \App\Services\PackageExpiryService::isDueDatePassed($hosting->next_due_date) ? 'text-danger' : 'text-muted' }} mt-1">
                                        Due: {{ \Carbon\Carbon::parse($hosting->next_due_date)->format('d M, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" class="btn-action edit" data-toggle="modal" data-target="#manageModal{{ $hosting->id }}" title="{{ __('Manage') }}">
                                        <i class="fas fa-tools"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action delete" data-toggle="modal" data-target="#deleteModal{{ $hosting->id }}" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="manageModal{{ $hosting->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <div class="modal-header bg-dark text-white">
                                        <h6 class="modal-title font-weight-bold">Manage Hosting: {{ $hosting->domain }}</h6>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body p-4 text-left">
                                        <form action="{{ route('admin.user.hosting.status', $hosting->id) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('Domain Name') }}</label>
                                                    <input type="text" name="domain" class="form-control" value="{{ $hosting->domain }}" placeholder="example.com" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('Select Package') }}</label>
                                                    <select name="plan_id" class="form-control" required>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}" {{ $hosting->plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('Assign Server') }}</label>
                                                    <select name="server_id" class="form-control" required>
                                                        @foreach($servers as $server)
                                                            <option value="{{ $server->id }}" {{ $hosting->server_id == $server->id ? 'selected' : '' }}>{{ $server->name }} ({{ $server->hostname }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('Server IP Address') }}</label>
                                                    <input type="text" name="ip_address" class="form-control" value="{{ $hosting->ip_address }}" placeholder="e.g. 192.168.1.1">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('cPanel Username') }}</label>
                                                    <input type="text" name="username" class="form-control" value="{{ $hosting->username }}" placeholder="cPanel Username">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold d-block">{{ __('cPanel Password') }}</label>
                                                    <input type="text" name="password" class="form-control" value="{{ $hosting->password }}" placeholder="Password">
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label class="small font-weight-bold d-block">{{ __('Account Status') }}</label>
                                                    <select name="status" class="form-control">
                                                        <option value="Active" {{ $hosting->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Pending" {{ $hosting->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Suspended" {{ $hosting->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                                        <option value="Expired" {{ $hosting->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                                                        <option value="Terminated" {{ $hosting->status == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                                                <i class="fas fa-save mr-1"></i> {{ __('Update Configuration') }}
                                            </button>
                                        </form>
                                        <hr class="my-4">
                                        @if($hosting->username && $hosting->server)
                                        <div class="text-center mb-3">
                                            <p class="small text-muted mb-2">পাসওয়ার্ড ছাড়াই সরাসরি সিপ্যানেলে প্রবেশ করুন:</p>
                                            <a href="{{ route('admin.user.hosting.cp_login', $hosting->id) }}" target="_blank" class="btn btn-info btn-block text-white font-weight-bold py-2">
                                                <i class="fas fa-external-link-alt mr-2"></i> One-Click cPanel Login
                                            </a>
                                        </div>
                                        @else
                                        <div class="alert alert-warning small py-2 mb-0">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> লগইন বাটন সক্রিয় করতে ইউজারনেম ও সার্ভার আগে সেভ করুন।
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal{{ $hosting->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-body p-5 text-center">
                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                        <h4 class="font-weight-bold">{{ __('Delete Hosting?') }}</h4>
                                        <p class="text-muted">{{ __('Domain') }}: <strong>{{ $hosting->domain }}</strong><br>{{ __('This action is permanent.') }}</p>
                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="button" class="btn btn-light border mr-3 px-4" data-dismiss="modal">{{ __('Cancel') }}</button>
                                            <a href="{{ route('admin.user.hosting.delete', $hosting->id) }}" class="btn btn-danger px-4">{{ __('Yes, Delete') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <h6 class="text-muted">{{ __('No hostings found.') }}</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($hostings->total() > 0 || $hostings->hasPages())
            <div class="px-3 pb-3 border-top d-flex justify-content-between align-items-center flex-wrap">
                @if($hostings->total() > 0)
                    <small class="text-muted mb-2 mb-md-0">
                        {{ __('Showing') }} {{ $hostings->firstItem() }}–{{ $hostings->lastItem() }} {{ __('of') }} {{ $hostings->total() }}
                    </small>
                @else
                    <small class="text-muted mb-2 mb-md-0"></small>
                @endif
                @if($hostings->hasPages())
                    {{ $hostings->appends(['search' => request('search'), 'status' => request('status')])->links('pagination::bootstrap-4') }}
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
