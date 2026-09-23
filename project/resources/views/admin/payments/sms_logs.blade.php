@extends('layouts.admin')

@section('styles')
@include('admin.payments.partials.payment-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-sms',
        'title' => __('CreativePay — SMS Logs'),
        'subtitle' => __('মোবাইল অ্যাপ থেকে আসা SMS পেমেন্ট লগ মনিটর করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Payments'), 'url' => route('admin.payments.index')],
            ['label' => __('SMS Logs'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.payments.partials.payment-nav')

    <div class="pay-stats">
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--available"><i class="fas fa-inbox"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Available') }}</span>
                <strong>{{ $counts['available'] }}</strong>
            </div>
        </div>
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--matched"><i class="fas fa-link"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Matched') }}</span>
                <strong>{{ $counts['matched'] }}</strong>
            </div>
        </div>
        <div class="pay-stat">
            <span class="pay-stat__icon pay-stat__icon--ignored"><i class="fas fa-question-circle"></i></span>
            <div class="pay-stat__body">
                <span>{{ __('Unparsed') }}</span>
                <strong>{{ $counts['ignored'] }}</strong>
            </div>
        </div>
    </div>

    <div class="pay-card">
        <div class="pay-card__head">
            <h3><i class="fas fa-comment-dots"></i> {{ __('SMS Log History') }}</h3>
        </div>
        <div class="pay-card__body">
            <div class="pay-tabs">
                <a href="?status=all" class="pay-tab {{ $status === 'all' ? 'is-active' : '' }}">{{ __('All') }}</a>
                <a href="?status=available" class="pay-tab {{ $status === 'available' ? 'is-active' : '' }}">
                    {{ __('Available') }} <span class="pay-tab__count">{{ $counts['available'] }}</span>
                </a>
                <a href="?status=matched" class="pay-tab {{ $status === 'matched' ? 'is-active' : '' }}">
                    {{ __('Matched') }} <span class="pay-tab__count">{{ $counts['matched'] }}</span>
                </a>
                <a href="?status=ignored" class="pay-tab {{ $status === 'ignored' ? 'is-active' : '' }}">
                    {{ __('Unparsed') }} <span class="pay-tab__count">{{ $counts['ignored'] }}</span>
                </a>
            </div>

            <p class="pay-hint">
                <i class="fas fa-mobile-alt"></i>
                Creative Pay অ্যাপ চালু থাকলে bKash/Nagad/Rocket SMS এখানে দেখা যাবে। <strong>Available</strong> SMS গুলো পেমেন্টের TrxID এর সাথে মিলিয়ে Approve করুন।
            </p>

            <div class="table-responsiv px-1 pb-1">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('TrxID') }}</th>
                            <th>{{ __('Provider') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Sender') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Received') }}</th>
                            <th>{{ __('Message') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td><span class="pay-code">{{ $log->transaction_id }}</span></td>
                            <td><span class="pay-method">{{ ucfirst($log->provider ?? '-') }}</span></td>
                            <td><span class="pay-amount">৳{{ number_format($log->amount, 2) }}</span></td>
                            <td>{{ $log->sender_number ?? '—' }}</td>
                            <td>
                                @if($log->status === 'available')
                                    <span class="pay-pill pay-pill--available"><i class="fas fa-inbox"></i> Available</span>
                                @elseif($log->status === 'matched')
                                    <span class="pay-pill pay-pill--matched"><i class="fas fa-check"></i> Matched</span>
                                @elseif($log->status === 'ignored')
                                    <span class="pay-pill pay-pill--ignored"><i class="fas fa-exclamation"></i> Unparsed</span>
                                @else
                                    <span class="pay-pill pay-pill--ignored">{{ ucfirst($log->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($log->received_at)
                                    <span class="pay-date">
                                        {{ $log->received_at->format('d M Y') }}
                                        <small>{{ $log->received_at->format('H:i') }}</small>
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td><span class="pay-msg" title="{{ $log->raw_message }}">{{ \Illuminate\Support\Str::limit($log->raw_message, 80) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="pay-empty">
                                    <i class="fas fa-sms d-block"></i>
                                    <strong>{{ __('No SMS logs yet') }}</strong>
                                    <small>{{ __('Mobile App চালু আছে কিনা এবং QR Login করা আছে কিনা দেখুন') }}</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="pay-pagination">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
