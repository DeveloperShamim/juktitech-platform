@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .fund-stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        color: #fff;
    }
    .adm-pro .fund-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .adm-pro .fund-stat-card h6,
    .adm-pro .fund-stat-card h3,
    .adm-pro .fund-stat-card i { color: #ffffff; }
    .adm-pro .fund-stat-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
    .adm-pro .fund-stat-danger { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); }
    .adm-pro .fund-stat-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
    .adm-pro .fund-badge-soft {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
    }
    .adm-pro .fund-badge-income { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .adm-pro .fund-badge-expense { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .adm-pro .fund-badge-withdraw { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-chart-line',
        'title' => __('Financial Statement & Fund Management'),
        'subtitle' => __('Track income, expenses, and fund balance'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Fund Report'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('includes.admin.form-both')

    <div class="adm-panel mb-4">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-filter"></i> {{ __('Date Filter') }}</h3>
        </div>
        <div class="adm-panel__body p-4">
            <form action="{{ route('admin.report.fund') }}" method="GET" class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="font-weight-bold mb-2"><i class="fas fa-calendar-alt mr-1"></i> {{ __('Select Date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') ?? date('Y-m-d') }}">
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> {{ __('Filter') }}
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.report.fund') }}" class="btn btn-light w-100 border">
                        <i class="fas fa-undo"></i> {{ __('Reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="fund-stat-card p-4 fund-stat-success shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small font-weight-bold mb-2">{{ __('Income') }} ({{ request('date') ? date('d M', strtotime(request('date'))) : __('Today') }})</h6>
                        <h3 class="font-weight-bold mb-0">৳ {{ number_format($total_income, 2) }}</h3>
                    </div>
                    <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="fund-stat-card p-4 fund-stat-danger shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small font-weight-bold mb-2">{{ __('Expense') }} ({{ request('date') ? date('d M', strtotime(request('date'))) : __('Today') }})</h6>
                        <h3 class="font-weight-bold mb-0">৳ {{ number_format($total_expense, 2) }}</h3>
                    </div>
                    <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="fund-stat-card p-4 fund-stat-primary shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small font-weight-bold mb-2">{{ __('Total Fund Balance') }}</h6>
                        <h3 class="font-weight-bold mb-0">৳ {{ number_format($current_fund, 2) }}</h3>
                    </div>
                    <i class="fas fa-wallet fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="adm-panel mb-4">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-hand-holding-usd"></i> {{ __('Fund Actions') }}</h3>
        </div>
        <div class="adm-panel__body p-4">
            <div class="d-flex flex-wrap adm-toolbar-gap">
                <button class="btn btn-success" data-toggle="modal" data-target="#addMoneyModal">
                    <i class="fas fa-plus-circle mr-1"></i> {{ __('Add Money') }}
                </button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#withdrawModal">
                    <i class="fas fa-minus-circle mr-1"></i> {{ __('Withdraw Money') }}
                </button>
            </div>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list-ul"></i> {{ __('Ledger (Datewise Transactions)') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trans)
                        <tr>
                            <td>
                                <span class="fund-badge-soft {{ $trans->type == 'income' ? 'fund-badge-income' : ($trans->type == 'expense' ? 'fund-badge-expense' : 'fund-badge-withdraw') }}">
                                    {{ strtoupper($trans->type) }}
                                </span>
                            </td>
                            <td class="font-weight-bold text-dark">৳ {{ number_format($trans->amount, 2) }}</td>
                            <td><span class="text-muted small">#{{ $trans->reference_id }}</span></td>
                            <td>{{ $trans->note }}</td>
                            <td class="text-muted small"><i class="far fa-clock mr-1"></i> {{ $trans->created_at->format('d M, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 mb-3 d-flex justify-content-center px-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMoneyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form action="{{ route('admin.report.fund_add') }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title font-weight-bold"><i class="fas fa-hand-holding-usd mr-2"></i>{{ __('Add Money to Fund') }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-5">
                    <div class="form-group mb-4">
                        <label class="h6 font-weight-bold mb-3">{{ __('Amount (৳)') }} *</label>
                        <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="h6 font-weight-bold mb-3">{{ __('Note') }}</label>
                        <textarea name="note" class="form-control" rows="4" placeholder="{{ __('Enter deposit reason or details...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-light btn-lg rounded-pill px-5 mr-3" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success btn-lg rounded-pill px-5">{{ __('Confirm Deposit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form action="{{ route('admin.report.fund_withdraw') }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title font-weight-bold"><i class="fas fa-money-bill-wave mr-2"></i>{{ __('Withdraw from Fund') }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-5">
                    <div class="form-group mb-4">
                        <label class="h6 font-weight-bold mb-3">{{ __('Amount (৳)') }} *</label>
                        <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                        <small class="text-muted mt-2 d-block">{{ __('Current Balance') }}: ৳ {{ number_format($current_fund, 2) }}</small>
                    </div>
                    <div class="form-group mb-0">
                        <label class="h6 font-weight-bold mb-3">{{ __('Withdrawal Reason') }}</label>
                        <textarea name="note" class="form-control" rows="4" placeholder="{{ __('Enter withdrawal reason...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-light btn-lg rounded-pill px-5 mr-3" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5">{{ __('Confirm Withdrawal') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
