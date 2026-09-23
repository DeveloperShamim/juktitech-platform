@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-history',
        'title' => __('SMS Logs'),
        'subtitle' => __('Monitor all sent SMS history and costs'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('SMS Logs'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel mb-3">
        <div class="adm-panel__body p-3 d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-muted">{{ __('Total Sent') }}:</span>
            <span class="badge badge-primary px-3 py-2 font-weight-bold">{{ $logs->total() }}</span>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All History') }}</h3>
        </div>
        <div class="adm-panel__body p-0">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="15%">{{ __('Date') }}</th>
                            <th width="20%">{{ __('Sender Info') }}</th>
                            <th width="10%">{{ __('Type') }}</th>
                            <th width="15%">{{ __('Recipient') }}</th>
                            <th width="30%">{{ __('Message') }}</th>
                            <th width="10%" class="text-right">{{ __('Cost') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $log->created_at->format('d M, Y') }}</div>
                                <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($log->user)
                                <div class="d-flex align-items-center">
                                    <div class="adm-thumb adm-thumb--avatar mr-3">
                                        <span>{{ substr($log->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.staff.edit', $log->user->id) }}" class="font-weight-bold text-dark">
                                            {{ $log->user->name }}
                                        </a>
                                        <div class="small text-muted">{{ $log->user->phone }}</div>
                                    </div>
                                </div>
                                @else
                                <span class="text-danger font-weight-bold small">{{ __('User Deleted') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($log->sender_type == 'masking')
                                    <span class="badge badge-info">MASKING</span>
                                @else
                                    <span class="badge badge-secondary">NON-MASKING</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ Str::limit($log->numbers, 13) }}</div>
                                <small class="text-muted">Count: <strong>{{ $log->total_sms_count }} SMS</strong></small>
                            </td>
                            <td>
                                <div class="adm-message-box p-2 bg-light rounded small mb-2">
                                    {{ Str::limit($log->message, 50) }}
                                </div>
                                @if($log->status == 'success')
                                    <span class="badge badge-success">Success</span>
                                @else
                                    <span class="badge badge-danger">{{ $log->status }}</span>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold text-danger">- {{ $log->cost }} ৳</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">{{ __('No records found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">{{ $logs->links() }}</div>
        </div>
    </div>
</div>
@endsection
