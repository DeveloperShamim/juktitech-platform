@extends('layouts.admin')

@section('styles')
@include('admin.payments.partials.payment-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-wallet',
        'title' => __('CreativePay — Payments'),
        'subtitle' => __('কাস্টমার পেমেন্ট রিভিউ ও ম্যানুয়াল Approve/Reject করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Payments'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @include('admin.payments.partials.payment-nav')

    <div class="pay-stats">
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--pending"><i class="fas fa-clock"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Pending') }}</span>
                <strong>{{ $counts['pending'] }}</strong>
            </div>
        </div>
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--completed"><i class="fas fa-check-circle"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Completed') }}</span>
                <strong>{{ $counts['completed'] }}</strong>
            </div>
        </div>
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--failed"><i class="fas fa-times-circle"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Failed') }}</span>
                <strong>{{ $counts['failed'] }}</strong>
            </div>
        </div>
    </div>

    <div class="pay-card">
        <div class="pay-card__head">
            <h3><i class="fas fa-list-ul"></i> {{ __('Payment Transactions') }}</h3>
        </div>
        <div class="pay-card__body">
            <div class="pay-tabs">
                <a href="?status=pending" class="pay-tab {{ $status === 'pending' ? 'is-active' : '' }}">
                    {{ __('Pending') }} <span class="pay-tab__count">{{ $counts['pending'] }}</span>
                </a>
                <a href="?status=completed" class="pay-tab {{ $status === 'completed' ? 'is-active' : '' }}">
                    {{ __('Completed') }} <span class="pay-tab__count">{{ $counts['completed'] }}</span>
                </a>
                <a href="?status=failed" class="pay-tab {{ $status === 'failed' ? 'is-active' : '' }}">
                    {{ __('Failed') }} <span class="pay-tab__count">{{ $counts['failed'] }}</span>
                </a>
                <a href="?status=all" class="pay-tab {{ $status === 'all' ? 'is-active' : '' }}">
                    {{ __('All') }}
                </a>
            </div>

            <p class="pay-hint">
                <i class="fas fa-info-circle"></i>
                SMS মেথডে TrxID মিললে <strong>Approve</strong> করুন — অর্ডার Paid ও সার্ভিস Activate হবে। SMS লগ দেখতে উপরের <strong>SMS Logs</strong> ট্যাব ব্যবহার করুন।
            </p>

            <div class="table-responsiv px-1 pb-1">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Invoice') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('TrxID') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td><span class="pay-code">{{ $p->invoice_id }}</span></td>
                            <td>
                                <div class="pay-customer">
                                    <strong>{{ $p->full_name }}</strong>
                                    @if($p->sender_number)
                                        <small><i class="fas fa-phone-alt"></i> {{ $p->sender_number }}</small>
                                    @endif
                                    @if($p->email)
                                        <small><i class="fas fa-envelope"></i> {{ $p->email }}</small>
                                    @endif
                                </div>
                            </td>
                            <td><span class="pay-amount">৳{{ number_format($p->amount, 0) }}</span></td>
                            <td>
                                <span class="pay-method">
                                    <i class="fas fa-{{ ($p->payment_method ?? '') === 'sms' ? 'sms' : 'hand-holding-usd' }}"></i>
                                    {{ ucfirst($p->payment_method ?? '-') }}
                                </span>
                            </td>
                            <td>
                                @if($p->transaction_id)
                                    <span class="pay-code">{{ $p->transaction_id }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'pending')
                                    <span class="pay-pill pay-pill--pending"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($p->status === 'completed')
                                    <span class="pay-pill pay-pill--completed"><i class="fas fa-check"></i> Paid</span>
                                @elseif($p->status === 'failed')
                                    <span class="pay-pill pay-pill--failed"><i class="fas fa-times"></i> Failed</span>
                                @else
                                    <span class="pay-pill pay-pill--ignored">{{ ucfirst($p->status) }}</span>
                                @endif
                                @if($p->status === 'pending' && $p->admin_note)
                                    <span class="pay-note">{{ $p->admin_note }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="pay-date">
                                    {{ $p->created_at->format('d M Y') }}
                                    <small>{{ $p->created_at->format('H:i') }} · {{ $p->created_at->diffForHumans() }}</small>
                                </span>
                            </td>
                            <td class="text-right">
                                @if($p->status === 'pending' && $p->transaction_id)
                                    <div class="pay-actions">
                                        <form method="POST" action="{{ route('admin.payments.approve', $p->id) }}" class="d-inline"
                                              onsubmit="return confirm('এই পেমেন্ট Approve করবেন? অর্ডার Paid ও সার্ভিস Activate হবে।');">
                                            @csrf
                                            <button type="submit" class="pay-act pay-act--approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payments.reject', $p->id) }}" class="d-inline"
                                              onsubmit="return confirm('এই পেমেন্ট Reject করবেন?');">
                                            @csrf
                                            <button type="submit" class="pay-act pay-act--reject">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="pay-empty">
                                    <i class="fas fa-inbox d-block"></i>
                                    <strong>{{ __('No payments found') }}</strong>
                                    <small>{{ __('এই ফিল্টারে কোনো পেমেন্ট নেই') }}</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="pay-pagination">{{ $payments->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
