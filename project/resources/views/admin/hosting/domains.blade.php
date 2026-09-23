@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-globe',
        'title' => __('Domain Pricing'),
        'subtitle' => __('Manage domain extension pricing'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting Management'), 'url' => null],
            ['label' => __('Domain Pricing'), 'url' => null],
        ],
        'action' => [
            'type' => 'modal',
            'label' => __('Add New Extension'),
            'icon' => 'fas fa-plus',
            'modal_target' => '#addDomainModal',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Extension List') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Extension') }}</th>
                            <th>{{ __('Registration') }}</th>
                            <th>{{ __('Renewal') }}</th>
                            <th>{{ __('Transfer') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($extensions as $ext)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $ext->extension }}</td>
                            <td><span class="badge badge-light border">৳ {{ $ext->registration_price }}</span></td>
                            <td><span class="badge badge-light border">৳ {{ $ext->renewal_price }}</span></td>
                            <td><span class="badge badge-light border">৳ {{ $ext->transfer_price }}</span></td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" data-toggle="modal" data-target="#editDomain{{ $ext->id }}" class="btn-action edit" title="{{ __('Edit') }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal{{ $ext->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editDomain{{ $ext->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>{{ __('Edit Domain Pricing') }}</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.hosting.domains.update', $ext->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4 text-left">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">{{ __('Extension') }}</label>
                                                <input type="text" name="extension" class="form-control" value="{{ $ext->extension }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">{{ __('Registration') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                                            <input type="number" name="registration_price" class="form-control" value="{{ $ext->registration_price }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">{{ __('Renewal') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                                            <input type="number" name="renewal_price" class="form-control" value="{{ $ext->renewal_price }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold">{{ __('Transfer') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                                            <input type="number" name="transfer_price" class="form-control" value="{{ $ext->transfer_price }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                            <button type="submit" class="btn btn-primary px-4">{{ __('Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal{{ $ext->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body text-center pb-5">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h4 class="mb-3">{{ __('Are you sure?') }}</h4>
                                        <p class="text-muted">{{ __('You are about to delete') }} <strong>{{ $ext->extension }}</strong> pricing.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center border-0">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                        <a href="{{ route('admin.hosting.domains.delete', $ext->id) }}" class="btn btn-danger">{{ __('Confirm Delete') }}</a>
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

<div class="modal fade" id="addDomainModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>{{ __('Add New Extension') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.hosting.domains.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">{{ __('Extension') }} <span class="text-danger">*</span></label>
                        <input type="text" name="extension" class="form-control" placeholder="e.g. .com" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Registration') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="registration_price" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Renewal') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="renewal_price" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Transfer') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="transfer_price" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
