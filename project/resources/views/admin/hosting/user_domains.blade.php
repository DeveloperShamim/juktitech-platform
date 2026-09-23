@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .custom-select-status { width: 120px; font-weight: bold; border-radius: 20px; cursor: pointer; }
    .adm-pro .custom-select-status.text-success { border-color: #28a745 !important; }
    .adm-pro .custom-select-status.text-warning { border-color: #ffc107 !important; }
    .adm-pro .custom-select-status.text-danger { border-color: #dc3545 !important; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-globe-americas',
        'title' => __('Customer Domains'),
        'subtitle' => __('Manage all customer domain registrations'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting'), 'url' => null],
            ['label' => __('Domains'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Domain List') }}</h3>
            <form action="{{ route('admin.user.domains') }}" method="GET" class="form-inline adm-panel__tools d-flex flex-wrap align-items-center">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Domain, Name or Email...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover w-100 mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Client') }}</th>
                            <th>{{ __('Domain Name') }}</th>
                            <th>{{ __('Registration') }}</th>
                            <th>{{ __('Expiry') }}</th>
                            <th class="text-center">{{ __('Status Control') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($domains as $domain)
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $domain->user->name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $domain->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <a href="http://{{ $domain->domain }}" target="_blank" class="text-primary font-weight-bold">{{ $domain->domain }}</a>
                                <div class="small text-muted">৳ {{ $domain->recurring_amount }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($domain->registration_date)->format('d M, Y') }}</td>
                            <td>
                                <span class="{{ \App\Services\PackageExpiryService::isDueDatePassed($domain->next_due_date) ? 'text-danger font-weight-bold' : 'text-success' }}">
                                    {{ \Carbon\Carbon::parse($domain->next_due_date)->format('d M, Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.user.domains.update', $domain->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-control form-control-sm custom-select-status {{ $domain->status == 'Active' ? 'text-success' : ($domain->status == 'Pending' ? 'text-warning' : 'text-danger') }}">
                                        <option value="Active" {{ $domain->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Pending" {{ $domain->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Suspended" {{ $domain->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="Expired" {{ $domain->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" class="btn-action edit" data-toggle="modal" data-target="#nsModal{{ $domain->id }}" title="{{ __('Nameservers') }}">
                                        <i class="fas fa-server"></i>
                                    </a>
                                    <a href="https://who.is/whois/{{ $domain->domain }}" target="_blank" class="btn-action view" title="{{ __('WHOIS') }}">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action delete" data-toggle="modal" data-target="#deleteModal{{ $domain->id }}" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="nsModal{{ $domain->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title font-weight-bold">Nameservers: {{ $domain->domain }}</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                    </div>
                                    <form action="{{ route('admin.user.domains.update', $domain->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4 bg-light">
                                            <div class="row">
                                                <div class="col-md-6 mb-3"><label class="small font-weight-bold">NS 1</label><input type="text" name="ns1" class="form-control" value="{{ $domain->ns1 }}"></div>
                                                <div class="col-md-6 mb-3"><label class="small font-weight-bold">NS 2</label><input type="text" name="ns2" class="form-control" value="{{ $domain->ns2 }}"></div>
                                                <div class="col-md-6 mb-3"><label class="small font-weight-bold">NS 3</label><input type="text" name="ns3" class="form-control" value="{{ $domain->ns3 }}"></div>
                                                <div class="col-md-6 mb-3"><label class="small font-weight-bold">NS 4</label><input type="text" name="ns4" class="form-control" value="{{ $domain->ns4 }}"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal{{ $domain->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-body p-5 text-center">
                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                        <h4 class="font-weight-bold">{{ __('Delete Domain?') }}</h4>
                                        <p class="text-muted">{{ __('Domain') }}: <strong>{{ $domain->domain }}</strong><br>{{ __('This action is permanent.') }}</p>
                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="button" class="btn btn-light border mr-3 px-4" data-dismiss="modal">{{ __('Cancel') }}</button>
                                            <a href="{{ route('admin.user.domains.delete', $domain->id) }}" class="btn btn-danger px-4">{{ __('Yes, Delete') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <h6 class="text-muted">{{ __('No domains found.') }}</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($domains->hasPages())
            <div class="px-3 pb-3 border-top d-flex justify-content-end">
                {{ $domains->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
