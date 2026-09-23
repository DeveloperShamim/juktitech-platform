@php
    $currentRoute = Route::currentRouteName();
@endphp
<nav class="um-nav" aria-label="{{ __('User Management') }}">
    <a href="{{ route('admin.staff.create') }}" class="{{ $currentRoute === 'admin.staff.create' ? 'is-active' : '' }}">
        <i class="fas fa-user-plus"></i> {{ __('Add New User') }}
    </a>
    <a href="{{ route('admin.staff.index') }}" class="{{ $currentRoute === 'admin.staff.index' ? 'is-active' : '' }}">
        <i class="fas fa-list-ul"></i> {{ __('All Users List') }}
    </a>
</nav>
