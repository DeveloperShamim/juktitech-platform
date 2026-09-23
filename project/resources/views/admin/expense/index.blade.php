@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .expense-stat-box {
        padding: 20px;
        background: white;
        border-radius: 12px;
        border-left: 5px solid #d9534f;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .adm-pro .expense-icon {
        width: 35px;
        height: 35px;
        background-color: rgba(217, 83, 79, 0.1);
        color: #d9534f;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    .adm-pro .btn-expense-submit {
        background-color: #d9534f;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: bold;
        transition: 0.3s;
        color: #fff;
    }
    .adm-pro .btn-expense-submit:hover {
        background-color: #c9302c;
        transform: translateY(-2px);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-wallet',
        'title' => __('Daily Expenses Management'),
        'subtitle' => __('Record and track daily business expenses'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Expenses'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="expense-stat-box shadow-sm">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{ __("Today's Total Expense") }}</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">৳{{ number_format($expenses->where('expense_date', date('Y-m-d'))->sum('amount'), 2) }}</div>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-plus-circle"></i> {{ __('Add New Expense') }}</h3>
                </div>
                <div class="adm-panel__body p-4">
                    <form action="{{ route('admin.expense.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">{{ __('Expense Title') }} *</label>
                            <input type="text" class="form-control" name="title" placeholder="e.g. Office Rent / Snacks" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">{{ __('Amount (৳)') }} *</label>
                            <input type="number" class="form-control" name="amount" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">{{ __('Expense Date') }} *</label>
                            <input type="date" class="form-control" name="expense_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button class="btn btn-expense-submit w-100 shadow-sm" type="submit">
                            <i class="fas fa-check-circle mr-2"></i>{{ __('SUBMIT EXPENSE') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-history"></i> {{ __('Recent Expense History') }}</h3>
                </div>
                <div class="adm-panel__body">
                    <div class="table-responsiv px-3 pb-3">
                        <table class="table table-hover dt-responsive w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="expense-icon">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $expense->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-danger">৳{{ number_format($expense->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ date('d M, Y', strtotime($expense->expense_date)) }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">{{ __('No expenses recorded yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 mb-3 d-flex justify-content-center px-3">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
