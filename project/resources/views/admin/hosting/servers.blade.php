@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-server',
        'title' => __('WHM Servers'),
        'subtitle' => __('Manage WHM server connections'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting Management'), 'url' => null],
            ['label' => __('Servers'), 'url' => null],
        ],
        'action' => [
            'type' => 'modal',
            'label' => __('Add New Server'),
            'icon' => 'fas fa-plus',
            'modal_target' => '#addServerModal',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Server List') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Server Name') }}</th>
                            <th>{{ __('Hostname / IP') }}</th>
                            <th>{{ __('Username') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servers as $server)
                        <tr>
                            <td class="font-weight-bold">{{ $server->name }}</td>
                            <td><code>{{ $server->hostname }}</code></td>
                            <td><span class="badge badge-secondary">{{ $server->username }}</span></td>
                            <td>
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Online</span>
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.hosting.servers.login', $server->id) }}" target="_blank" class="btn-action view" title="{{ __('Login to WHM') }}">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                    <a href="javascript:;" class="btn-action edit" data-toggle="modal" data-target="#editModal{{ $server->id }}" title="{{ __('Edit') }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal{{ $server->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $server->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>{{ __('Edit Server') }}: {{ $server->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.hosting.servers.update', $server->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">{{ __('Server Name') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="{{ $server->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">{{ __('Hostname / IP') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="hostname" class="form-control" value="{{ $server->hostname }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">{{ __('Username') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="username" class="form-control" value="{{ $server->username }}" required>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold">{{ __('API Token / Access Hash') }} <span class="text-danger">*</span></label>
                                                <textarea name="access_hash" class="form-control" rows="5" required>{{ $server->access_hash }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary px-4">{{ __('Update Changes') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal{{ $server->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body text-center pb-5">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h4 class="mb-3">{{ __('Are you sure?') }}</h4>
                                        <p class="text-muted">{{ __('You are about to delete this server. This action cannot be undone.') }}</p>
                                    </div>
                                    <div class="modal-footer justify-content-center border-0">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                        <a href="{{ route('admin.hosting.servers.delete', $server->id) }}" class="btn btn-danger">{{ __('Confirm Delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addServerModal" tabindex="-1" role="dialog" aria-labelledby="addServerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-plus-circle mr-2"></i>{{ __('Add New WHM Server') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.hosting.servers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Server Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. USA Cloud Server 1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Hostname / IP Address') }} <span class="text-danger">*</span></label>
                                <input type="text" name="hostname" class="form-control" placeholder="e.g. 192.168.1.1 or s1.yourserver.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">{{ __('WHM Root/Reseller Username') }} <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Enter WHM username" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">{{ __('API Token / Access Hash') }} <span class="text-danger">*</span></label>
                        <textarea name="access_hash" class="form-control" rows="5" placeholder="Paste your WHM API token here..." required></textarea>
                        <div class="alert alert-info mt-2 py-2 px-3 border-0 small mb-0">
                            <i class="fas fa-info-circle mr-1"></i> <strong>How to get this?</strong> WHM &gt; Development &gt; Manage API Tokens
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Save Server') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
