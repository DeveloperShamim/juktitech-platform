@php
    $currentRoute = Route::currentRouteName();
@endphp
<nav class="pay-nav" aria-label="{{ __('CreativePay') }}">
    <a href="{{ route('admin.payments.index') }}" class="{{ $currentRoute === 'admin.payments.index' ? 'is-active' : '' }}">
        <i class="fas fa-wallet"></i> {{ __('Payments') }}
    </a>
    <a href="{{ route('admin.payments.sms-logs') }}" class="{{ $currentRoute === 'admin.payments.sms-logs' ? 'is-active' : '' }}">
        <i class="fas fa-sms"></i> {{ __('SMS Logs') }}
    </a>
    <a href="{{ route('admin.payments.app-qr') }}" class="{{ $currentRoute === 'admin.payments.app-qr' ? 'is-active' : '' }}">
        <i class="fas fa-qrcode"></i> {{ __('App QR Login') }}
    </a>
</nav>
