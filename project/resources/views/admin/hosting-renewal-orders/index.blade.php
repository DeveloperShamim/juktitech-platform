@extends('layouts.admin')

@section('styles')
<style>
    .renewal-filter-btn { border-radius: 8px; font-weight: 500; margin-right: 6px; margin-bottom: 8px; }
    .renewal-filter-btn.active { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
    .renewal-table { min-width: 900px; }
    .renewal-table th { white-space: nowrap; font-size: 12px; text-transform: uppercase; color: #6c757d; }
    .renewal-table td { vertical-align: middle; }
    .renewal-card { display: none; border-radius: 12px; border: 1px solid #eee; margin-bottom: 12px; overflow: hidden; }
    .renewal-card .card-body { padding: 14px; }
    .renewal-card .row-item { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f0f0f0; }
    .renewal-card .row-item:last-child { border-bottom: none; }
    .renewal-card .row-item strong { min-width: 110px; color: #555; font-size: 13px; }
    .btn-mark-paid { font-weight: 600; border-radius: 8px; padding: 8px 16px; }
    @media (max-width: 768px) {
        .renewal-table-wrap { display: none; }
        .renewal-card { display: block; }
    }
    @media (min-width: 769px) {
        .renewal-cards-wrap { display: none; }
    }
    .pagination { flex-wrap: wrap; justify-content: center; gap: 4px; }
    .pagination .page-link { border-radius: 6px; padding: 8px 12px; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-server',
        'title' => __('Hosting Renewal Orders'),
        'subtitle' => __('Manage hosting renewal invoices'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting Renewal Orders'), 'url' => null],
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

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-server"></i> {{ __('Hosting Renewal Orders') }}</h3>
        </div>
        <div class="adm-panel__body p-3 p-md-4">
            <div class="mb-4">
                <p class="text-muted small mb-2">স্ট্যাটাস দিয়ে ফিল্টার করুন:</p>
                <a href="{{ route('admin.hosting-renewal-orders.index') }}" class="btn btn-sm renewal-filter-btn {{ !request('status') ? 'btn-primary active' : 'btn-outline-secondary' }}">
                    সব অর্ডার
                </a>
                <a href="{{ route('admin.hosting-renewal-orders.index', ['status' => 'pending']) }}" class="btn btn-sm renewal-filter-btn {{ request('status') === 'pending' ? 'btn-warning active' : 'btn-outline-warning' }}">
                    <i class="fas fa-clock mr-1"></i> আনপেইড (পেন্ডিং)
                    @if($pending_count > 0)
                        <span class="badge badge-light text-dark ml-1">{{ $pending_count }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.hosting-renewal-orders.index', ['status' => 'paid']) }}" class="btn btn-sm renewal-filter-btn {{ request('status') === 'paid' ? 'btn-success active' : 'btn-outline-success' }}">
                    <i class="fas fa-check-circle mr-1"></i> পেইড
                </a>
            </div>

            <div class="renewal-table-wrap table-responsive">
                <table class="table table-hover renewal-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ইউজার</th>
                            <th>হোস্টিং (ডোমেইন/প্ল্যান)</th>
                            <th>টাকা</th>
                            <th>ইনভয়েস</th>
                            <th>স্ট্যাটাস</th>
                            <th>তারিখ</th>
                            <th>পেইড তারিখ</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @if($order->user)
                                    <strong>{{ $order->user->name }}</strong><br>
                                    <small class="text-muted">{{ $order->user->email ?? $order->user->phone ?? '—' }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($order->userHosting)
                                    <strong>{{ $order->userHosting->domain ?? '—' }}</strong><br>
                                    <small class="text-muted">{{ $order->userHosting->plan ? $order->userHosting->plan->name : '—' }}</small>
                                @else
                                    #{{ $order->user_hosting_id }}
                                @endif
                            </td>
                            <td>{{ number_format($order->amount, 0) }} টাকা</td>
                            <td><small>{{ $order->invoice_id ?? '—' }}</small></td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge badge-warning">আনপেইড (পেন্ডিং)</span>
                                @elseif($order->status === 'paid')
                                    <span class="badge badge-success">পেইড</span>
                                @else
                                    <span class="badge badge-secondary">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td><small>{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                            <td><small>{{ $order->paid_at ? $order->paid_at->format('d/m/Y') : '—' }}</small></td>
                            <td>
                                @if($order->isPending())
                                    <form action="{{ route('admin.hosting-renewal-orders.mark-paid', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('এই ইনভয়েস পেইড মার্ক করে হোস্টিং রিনিউ করবেন?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success btn-mark-paid"><i class="fas fa-check mr-1"></i> পেইড</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.hosting-renewal-orders.mark-unpaid', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('এই ইনভয়েস আনপেইড (পেন্ডিং) মার্ক করবেন?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-clock mr-1"></i> আনপেইড</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">কোনো অর্ডার নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="renewal-cards-wrap">
                @forelse($orders as $order)
                <div class="card renewal-card">
                    <div class="card-body">
                        <div class="row-item">
                            <strong># অর্ডার</strong>
                            <span>{{ $order->id }}</span>
                        </div>
                        <div class="row-item">
                            <strong>ইউজার</strong>
                            <span>{{ $order->user ? $order->user->name : '—' }}</span>
                        </div>
                        <div class="row-item">
                            <strong>হোস্টিং</strong>
                            <span>{{ $order->userHosting ? $order->userHosting->domain : '#'.$order->user_hosting_id }}</span>
                        </div>
                        <div class="row-item">
                            <strong>টাকা</strong>
                            <span>{{ number_format($order->amount, 0) }} টাকা</span>
                        </div>
                        <div class="row-item">
                            <strong>স্ট্যাটাস</strong>
                            @if($order->status === 'pending')
                                <span class="badge badge-warning">আনপেইড (পেন্ডিং)</span>
                            @else
                                <span class="badge badge-success">পেইড</span>
                            @endif
                        </div>
                        <div class="row-item">
                            <strong>তারিখ</strong>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="row-item pt-2">
                            @if($order->isPending())
                            <form action="{{ route('admin.hosting-renewal-orders.mark-paid', $order->id) }}" method="POST" onsubmit="return confirm('এই ইনভয়েস পেইড মার্ক করে হোস্টিং রিনিউ করবেন?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block btn-mark-paid"><i class="fas fa-check mr-1"></i> পেইড</button>
                            </form>
                            @else
                            <form action="{{ route('admin.hosting-renewal-orders.mark-unpaid', $order->id) }}" method="POST" onsubmit="return confirm('এই ইনভয়েস আনপেইড মার্ক করবেন?');">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-block"><i class="fas fa-clock mr-1"></i> আনপেইড</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted py-4">কোনো অর্ডার নেই।</p>
                @endforelse
            </div>

            @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
