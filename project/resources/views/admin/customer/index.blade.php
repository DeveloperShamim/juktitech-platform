@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-users',
        'title' => __('Customers Management'),
        'subtitle' => __('View and manage all registered customers'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Customers'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Customers') }}</h3>
            <div class="adm-panel__tools">
                <input type="text" id="customerSearch" class="form-control form-control-sm" placeholder="{{ __('Search customers...') }}">
            </div>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="customerTable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Customer Details') }}</th>
                            <th>{{ __('Contact Info') }}</th>
                            <th>{{ __('Location') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle mr-3">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $customer->name }}</div>
                                        <small class="text-muted">{{ __('Customer ID') }}: #{{ $customer->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone-alt mr-2 text-muted small"></i>
                                    {{ $customer->phone }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-truncate adm-cell-truncate">
                                    <i class="fas fa-map-marker-alt mr-2 text-muted small"></i>
                                    {{ $customer->address ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn-action edit" title="{{ __('Edit Customer') }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.customer.destroy', $customer->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" onclick="return confirm('{{ __('Are you sure you want to delete this customer?') }}')" title="{{ __('Delete Customer') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 mb-3 d-flex justify-content-center px-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('customerSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('#customerTable tbody tr');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endsection
