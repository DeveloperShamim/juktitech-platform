@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-headset',
        'title' => __('Free Consultancy Requests'),
        'subtitle' => __('Manage free consultation booking requests'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Consultancy'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ __('Success!') }}</strong> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Requests') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td class="font-weight-bold">{{ $row->name }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>
                                @if($row->status == 0)
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" class="btn-action view" data-toggle="modal" data-target="#consultancyModal{{ $row->id }}" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($row->status == 0)
                                    <a href="{{ route('admin.consultancy.status', [$row->id, 1]) }}" class="btn-action edit" title="{{ __('Mark as Completed') }}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('admin.consultancy.status', [$row->id, 0]) }}" class="btn-action view" title="{{ __('Mark as Pending') }}">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.consultancy.delete', $row->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete this request?') }}')" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="consultancyModal{{ $row->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i> {{ __('Request Details') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Customer Name') }}:</label>
                                            <p class="h6 font-weight-bold mb-0">{{ $row->name }}</p>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="font-weight-bold text-muted small text-uppercase">{{ __('Phone') }}:</label>
                                                <p class="font-weight-bold mb-0">{{ $row->phone }}</p>
                                            </div>
                                            <div class="col-6">
                                                <label class="font-weight-bold text-muted small text-uppercase">{{ __('Email') }}:</label>
                                                <p class="mb-0">{{ $row->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Submitted At') }}:</label>
                                            <p class="small mb-0">{{ $row->created_at ? $row->created_at->format('d M, Y - h:i A') : 'N/A' }}</p>
                                        </div>
                                        <hr>
                                        <div class="mb-0">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Message') }}:</label>
                                            <div class="p-3 bg-light mt-2 adm-message-box">{{ $row->message ?? __('No message provided.') }}</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">{{ __('No consultancy requests found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
