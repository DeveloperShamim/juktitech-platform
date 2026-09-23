@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-envelope',
        'title' => __('Contact Messages'),
        'subtitle' => __('Review and manage contact form submissions'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Contact Messages'), 'url' => null],
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
            <h3 class="adm-panel__title"><i class="fas fa-inbox"></i> {{ __('All Messages') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td class="font-weight-bold">{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->subject ?? __('No Subject') }}</td>
                            <td>{{ $row->created_at ? $row->created_at->format('d M, Y') : 'N/A' }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" class="btn-action view" data-toggle="modal" data-target="#contactModal{{ $row->id }}" title="{{ __('View Message') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.contact.delete', $row->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete this message?') }}')" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="contactModal{{ $row->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-envelope-open-text mr-2"></i> {{ __('Message Details') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Sender Name') }}:</label>
                                            <p class="h6 font-weight-bold mb-0">{{ $row->name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Email Address') }}:</label>
                                            <p class="font-weight-bold text-primary mb-0">{{ $row->email }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Subject') }}:</label>
                                            <p class="mb-0">{{ $row->subject ?? 'N/A' }}</p>
                                        </div>
                                        <hr>
                                        <div class="mb-0">
                                            <label class="font-weight-bold text-muted small text-uppercase">{{ __('Message') }}:</label>
                                            <div class="p-3 bg-light mt-2 adm-message-box">{{ $row->message }}</div>
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
                            <td colspan="5" class="text-center py-5 text-muted">{{ __('No contact messages found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
