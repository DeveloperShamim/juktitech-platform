@extends('layouts.admin')

{{-- ১. স্টাইল সেকশন (হেডারে লোড হবে) --}}
@section('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* Select2 ডিজাইন কাস্টমাইজেশন */
        .select2-container .select2-selection--single {
            height: 45px !important;
            border: 1px solid #d1d3e2;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px !important;
            top: 1px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 15px;
            color: #495057;
            font-size: 15px;
            line-height: 43px;
        }
        /* সার্চ বক্সের ডিজাইন */
        .select2-search__field {
            outline: none !important;
            border: 1px solid #ddd !important;
            padding: 5px !important;
        }

        /* ৩-ডট মেনু ডিজাইন */
        .action-dropdown { position: relative; }
        .custom-menu {
            display: none;
            position: absolute;
            right: 35px; /* বাটনের বামে */
            top: 0;
            background: #fff;
            min-width: 180px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            z-index: 99999;
            border-radius: 8px;
            border: 1px solid #eee;
            padding: 5px 0;
            text-align: left;
        }
        .custom-menu a {
            display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px; transition: 0.2s;
        }
        .custom-menu a:hover { background-color: #f0f4f8; color: #2d3274; }

        .pay-detail-box {
            background: #f8fafc;
            border: 1px solid #e8ecf1;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }
        .pay-detail-box .lbl { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .5px; }
        .pay-detail-box .val { font-size: 15px; font-weight: 600; color: #222; }
        .pay-status-toggle .btn { min-width: 120px; font-weight: 600; }
        .pay-status-toggle .btn.active-paid { background: #28a745; color: #fff; border-color: #28a745; }
        .pay-status-toggle .btn.active-unpaid { background: #dc3545; color: #fff; border-color: #dc3545; }
        .txn-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 16px; }
        .txn-lbl { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: .4px; display: block; margin-bottom: 2px; }
        .txn-val { font-size: 13px; font-weight: 600; color: #333; word-break: break-all; }
        .pd-method-badge { font-size: 14px; padding: 6px 14px; font-weight: 700; }
        .pd-method-badge.m-bkash { background: #fce4ef; color: #D12053; }
        .pd-method-badge.m-nagad { background: #fde8e8; color: #EE1C24; }
        .pd-method-badge.m-rocket { background: #f3e5f5; color: #8C3494; }
        .pd-method-badge.m-cash { background: #e8f5e9; color: #2e7d32; }
        .pd-method-badge.m-bank { background: #e3f2fd; color: #1565c0; }
        .pd-method-badge.m-default { background: #eceff1; color: #455a64; }
        .pd-pay-extra { font-size: 12px; margin-top: 8px; line-height: 1.6; }
        .pay-action-footer {
            background: #f0f4f8;
            border-top: 2px solid #dee2e6;
            padding: 16px 20px;
        }
        
        .pos-modal-high { z-index: 100000; }
        #paymentDetailsModal { z-index: 100001; }
        
        /* অন্যান্য */
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
        .bg-aliceblue { background-color: #f0f8ff; }
        input[type=number] { -moz-appearance: textfield; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
@endsection

{{-- ২. কন্টেন্ট সেকশন (বডিতে লোড হবে) --}}
@section('content')

@php
    $users = \App\Models\User::where('status', 1)->orderBy('name', 'asc')->get();
@endphp

<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-cash-register',
        'title' => __('Point of Sale (POS)'),
        'subtitle' => __('Create sales and manage orders'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('POS'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    {{-- মেইন ফর্ম --}}
    <form action="{{ route('admin.pos.store') }}" method="POST" id="pos-form">
        @csrf
        <div class="row">
            {{-- বাম পাশ --}}
            <div class="col-lg-7">
                {{-- প্রোডাক্ট --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold"><i class="fas fa-cart-plus mr-2"></i> Order Items</h5>
                        <button type="button" class="btn btn-light btn-sm font-weight-bold text-primary" id="add-item-btn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th width="40%" class="pl-4">Product</th>
                                    <th width="20%" class="text-center">Qty</th>
                                    <th width="20%" class="text-center">Price</th>
                                    <th width="15%" class="text-right">Total</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="product-list">
                                <tr class="product-row border-bottom">
                                    <td class="p-3 pl-4"><input type="text" class="form-control border-0 bg-light item-name" placeholder="Item name..." required></td>
                                    <td class="p-3"><input type="number" class="form-control border-0 bg-light text-center qty" value="1" min="1" required></td>
                                    <td class="p-3"><input type="number" class="form-control border-0 bg-light text-center price" placeholder="0.00" step="0.01" required></td>
                                    <td class="p-3 text-right font-weight-bold">৳<span class="row-total">0.00</span></td>
                                    <td class="p-3 text-center"><button type="button" class="btn btn-link text-danger p-0 remove-row d-none"><i class="fas fa-times-circle"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- কাস্টমার ইনফো --}}
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">Customer Information</h6>
                    
                    {{-- [IMPORTANT] সার্চ বক্স --}}
                    <div class="form-group mb-4 p-3 bg-aliceblue rounded border">
                        <label class="font-weight-bold text-primary mb-2">🔍 Search Existing Customer</label>
                        {{-- Select2 ক্লাস এবং আইডি দেওয়া আছে --}}
                        <select class="form-control select2-box w-100" id="customer_search">
                            <option value="">Start typing Name or Phone...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-phone="{{ $user->phone }}">
                                    {{ $user->name }} - {{ $user->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" id="final_name" name="name" placeholder="Full Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" id="final_phone" name="phone" placeholder="017xxxxxxxx" required>
                                <small class="text-muted">New number? Auto-account created.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Address / Note</label>
                        <textarea class="form-control bg-light border-0" name="support_note" rows="2" placeholder="Address..."></textarea>
                    </div>
                    <textarea name="description" id="final_description" class="d-none"></textarea>
                </div>
            </div>

            {{-- ডান পাশ --}}
            <div class="col-lg-5">
                <div class="card bg-gradient-primary text-white p-4 mb-4 shadow-lg border-0">
                    <div class="text-center mb-4">
                        <small class="text-uppercase opacity-75">Grand Total</small>
                        <h1 class="font-weight-bold">৳<span id="grand_total_display">0.00</span></h1>
                        <input type="hidden" name="total" id="total_bill_input" value="0">
                    </div>
                    <div class="row border-top border-white-50 pt-3">
                        <div class="col-6 border-right border-white-50">
                            <small class="text-uppercase opacity-75">Paid Now</small>
                            <input type="number" class="form-control bg-transparent border-0 text-white font-weight-bold p-0 shadow-none h3" name="paid" id="paid_amount" value="0" step="0.01">
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-uppercase opacity-75">Due</small>
                            <div class="h4 font-weight-bold text-warning m-0" id="due_display">৳ 0.00</div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <label class="font-weight-bold small text-muted">Payment Method</label>
                        <select name="payment_method" class="form-control bg-light border-0 h-auto py-2">
                            <option value="Cash">Cash (নগদ)</option>
                            <option value="Bkash">bKash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Bank">Bank Transfer</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-dark w-100 py-3 font-weight-bold shadow-lg" type="submit">
                    <i class="fas fa-check-circle mr-2"></i> COMPLETE SALE
                </button>
            </div>
        </div>
    </form>

    {{-- ৩. রিসেন্ট সেলস (অ্যাকশন বাটন ফিক্সড) --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold m-0 text-dark">Recent Sales</h5>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.pos.index') }}" class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">All</a>
                        <a href="{{ route('admin.pos.index', ['filter' => 'paid']) }}" class="btn btn-outline-success {{ request('filter') == 'paid' ? 'active' : '' }}">Paid</a>
                        <a href="{{ route('admin.pos.index', ['filter' => 'due']) }}" class="btn btn-outline-danger {{ request('filter') == 'due' ? 'active' : '' }}">Due</a>
                    </div>
                </div>

                @php
                    use App\Models\PaymentTransaction;
                    $methodLabels = [
                        'cash' => 'Cash (নগদ)', 'Cash' => 'Cash (নগদ)',
                        'bkash' => 'bKash', 'Bkash' => 'bKash', 'BKASH' => 'bKash',
                        'nagad' => 'Nagad', 'Nagad' => 'Nagad',
                        'rocket' => 'Rocket', 'Rocket' => 'Rocket',
                        'bank' => 'Bank Transfer', 'Bank' => 'Bank Transfer',
                        'other' => 'CreativePay', 'Other' => 'CreativePay',
                    ];
                    $ordersData = [];
                    foreach ($orders as $o) {
                        $rawNote = $o->support_note ?? '';
                        $parsed  = json_decode($rawNote, true);
                        $method  = trim($o->payment_method ?? '');
                        $gatewayTrx = null;
                        $senderPhone = null;

                        $invoiceId = is_array($parsed) && !empty($parsed['txn_id'])
                            ? $parsed['txn_id']
                            : (preg_match('/CP-[A-Z0-9]+(?:-[0-9]+)?/i', $rawNote, $m) ? strtoupper($m[0]) : null);

                        if ($invoiceId) {
                            $cpTxn = PaymentTransaction::where('invoice_id', $invoiceId)->first();
                            if ($cpTxn) {
                                if (!empty($cpTxn->payment_method)) {
                                    $method = $cpTxn->payment_method;
                                }
                                $gatewayTrx  = $cpTxn->transaction_id;
                                $senderPhone = $cpTxn->sender_number;
                            }
                        }

                        if ($method === '' || strtolower($method) === 'other') {
                            $method = $method !== '' ? $method : '—';
                        }

                        $ordersData[$o->id] = [
                            'invoice'      => $o->order_number,
                            'customer'     => $o->customer->name ?? '',
                            'phone'        => $o->customer->phone ?? '',
                            'total'        => (float) $o->total_amount,
                            'paid'         => (float) $o->paid_amount,
                            'due'          => (float) $o->due_amount,
                            'method'       => $method,
                            'methodLabel'  => $methodLabels[$method] ?? $methodLabels[strtolower($method)] ?? ucfirst($method),
                            'gatewayTrx'   => $gatewayTrx,
                            'senderPhone'  => $senderPhone,
                            'description'  => $o->description ?? '',
                            'note'         => is_array($parsed) ? $parsed : ($rawNote !== '' ? ['_raw' => $rawNote] : null),
                            'isPaid'       => $o->due_amount <= 0,
                        ];
                    }
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Invoice</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th class="text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="pl-4">
                                    <span class="font-weight-bold text-primary">{{ $order->order_number }}</span>
                                    <br><small class="text-muted">{{ $order->created_at->format('d M, h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="font-weight-bold">{{ $order->customer->name }}</span>
                                    <br><small class="text-muted">{{ $order->customer->phone }}</small>
                                </td>
                                <td class="font-weight-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                                <td class="text-success">৳{{ number_format($order->paid_amount, 2) }}</td>
                                <td class="text-danger">৳{{ number_format($order->due_amount, 2) }}</td>
                                <td>
                                    @if($order->due_amount > 0) <span class="badge badge-danger">Due</span>
                                    @else <span class="badge badge-success">Paid</span> @endif
                                </td>
                                <td class="text-right pr-4 position-relative">
                                    <div class="action-dropdown">
                                        <button class="btn btn-sm btn-light border rounded-circle action-btn" onclick="toggleMenu('menu-{{ $order->id }}')">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div id="menu-{{ $order->id }}" class="custom-menu">
                                            <a href="javascript:;" onclick="openPaymentDetails({{ $order->id }})" class="text-primary font-weight-bold">
                                                <i class="fas fa-receipt mr-2"></i> Payment Details
                                            </a>
                                            <div class="border-top"></div>
                                            <a href="{{ route('frontend.invoice.live', ['order_number' => $order->order_number, 'token' => $order->hash_token]) }}" target="_blank">
                                                <i class="fas fa-eye mr-2"></i> View Invoice
                                            </a>
                                            @if($order->due_amount > 0)
                                            <div class="border-top"></div>
                                            <a href="javascript:;" onclick="openDueModal('{{ $order->id }}')" class="text-success font-weight-bold">
                                                <i class="fas fa-money-bill-wave mr-2"></i> Collect Due
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4">No data found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Collect Due Modals (টেবিলের বাইরে) --}}
@foreach($orders as $order)
    @if($order->due_amount > 0)
    <div class="modal fade pos-modal-high" id="modal-{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Collect Due</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('admin.pos.due_collect', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <h2 class="text-danger font-weight-bold">৳{{ number_format($order->due_amount, 2) }}</h2>
                            <small class="text-muted">CURRENT DUE</small>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control font-weight-bold" max="{{ $order->due_amount }}" required step="0.01">
                        </div>
                        <div class="form-group mb-0">
                            <label>Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Bkash">bKash</option>
                                <option value="Nagad">Nagad</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success shadow">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Payment Details Modal --}}
<div class="modal fade pos-modal-high" id="paymentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-2"></i> Payment Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="paymentDetailsForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pay-detail-box">
                                <div class="lbl">Invoice</div>
                                <div class="val" id="pd-invoice">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pay-detail-box">
                                <div class="lbl">Customer</div>
                                <div class="val" id="pd-customer">—</div>
                                <small class="text-muted" id="pd-phone"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pay-detail-box text-center">
                                <div class="lbl">Total</div>
                                <div class="val text-primary" id="pd-total">৳0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pay-detail-box text-center">
                                <div class="lbl">Paid</div>
                                <div class="val text-success" id="pd-paid">৳0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pay-detail-box text-center">
                                <div class="lbl">Due</div>
                                <div class="val text-danger" id="pd-due">৳0</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pay-detail-box">
                                <div class="lbl">Payment Method</div>
                                <div class="val">
                                    <span class="badge pd-method-badge m-default" id="pd-method-display">—</span>
                                </div>
                                <div class="pd-pay-extra d-none" id="pd-pay-extra">
                                    <div id="pd-gtrx-wrap" class="d-none"><span class="text-muted">Customer TrxID:</span> <strong id="pd-gtrx"></strong></div>
                                    <div id="pd-sender-wrap" class="d-none"><span class="text-muted">Sender Number:</span> <strong id="pd-sender"></strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pay-detail-box">
                                <div class="lbl">Payment Status</div>
                                <div class="val" id="pd-pay-status">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="pay-detail-box">
                        <div class="lbl">Description</div>
                        <div class="val small font-weight-normal text-pre-wrap" id="pd-description">—</div>
                    </div>

                    <div class="pay-detail-box d-none" id="pd-note-wrap">
                        <div class="lbl">Payment Note / TXN</div>
                        <div id="pd-note"></div>
                    </div>
                </div>
                <div class="modal-footer pay-action-footer">
                    <div class="row w-100 align-items-end">
                        <div class="col-md-4">
                            <label class="font-weight-bold small mb-1">Payment Method</label>
                            <select name="payment_method" id="pd-method" class="form-control form-control-sm">
                                <option value="Cash">Cash (নগদ)</option>
                                <option value="Bkash">bKash</option>
                                <option value="Nagad">Nagad</option>
                                <option value="Rocket">Rocket</option>
                                <option value="Bank">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-8 text-md-right mt-3 mt-md-0">
                            <input type="hidden" name="status" id="pd-status" value="paid">
                            <button type="button" class="btn btn-secondary btn-sm mr-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success btn-sm font-weight-bold mr-1" onclick="document.getElementById('pd-status').value='paid'">
                                <i class="fas fa-check-circle mr-1"></i> Mark Paid
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold" onclick="document.getElementById('pd-status').value='unpaid'">
                                <i class="fas fa-times-circle mr-1"></i> Mark Unpaid
                            </button>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2 mb-0">
                        <b>Paid</b> = বকেয়া শোধ + সার্ভিস Activate &nbsp;|&nbsp; <b>Unpaid</b> = Due হিসেবে Pending
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- ৩. স্ক্রিপ্ট সেকশন (ফুটার এর আগে লোড হবে) --}}
@section('scripts')
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const ordersData = @json($ordersData ?? []);

    function escHtml(s) {
        return $('<div>').text(s || '').html();
    }

    function methodBadgeClass(method) {
        const k = (method || '').toLowerCase();
        if (k.includes('bkash')) return 'm-bkash';
        if (k.includes('nagad')) return 'm-nagad';
        if (k.includes('rocket')) return 'm-rocket';
        if (k.includes('cash')) return 'm-cash';
        if (k.includes('bank')) return 'm-bank';
        return 'm-default';
    }

    function normalizeMethodSelect(method) {
        const map = { bkash: 'Bkash', nagad: 'Nagad', rocket: 'Rocket', cash: 'Cash', bank: 'Bank' };
        const k = (method || 'Cash').toLowerCase();
        return map[k] || method || 'Cash';
    }

    function renderPaymentMethod(d) {
        const label = d.methodLabel || d.method || '—';
        const $badge = $('#pd-method-display');
        $badge.text(label).removeClass('m-bkash m-nagad m-rocket m-cash m-bank m-default').addClass(methodBadgeClass(d.method));

        let hasExtra = false;
        if (d.gatewayTrx) {
            $('#pd-gtrx').text(d.gatewayTrx);
            $('#pd-gtrx-wrap').show();
            hasExtra = true;
        } else {
            $('#pd-gtrx-wrap').hide();
        }
        if (d.senderPhone) {
            $('#pd-sender').text(d.senderPhone);
            $('#pd-sender-wrap').show();
            hasExtra = true;
        } else {
            $('#pd-sender-wrap').hide();
        }
        $('#pd-pay-extra').toggle(hasExtra);
    }

    function renderPaymentNote(note) {
        const $wrap = $('#pd-note-wrap');
        const $el   = $('#pd-note');
        if (!note) {
            $wrap.hide();
            return;
        }
        if (note.txn_id) {
            const st = (note.status || 'PENDING').toUpperCase();
            const badge = (st === 'COMPLETED' || st === 'PAID') ? 'success' : (st === 'PENDING' ? 'warning' : 'secondary');
            $el.html(
                '<div class="txn-grid">' +
                '<div><span class="txn-lbl">TXN ID</span><div class="txn-val">' + escHtml(note.txn_id) + '</div></div>' +
                '<div><span class="txn-lbl">Status</span><div><span class="badge badge-' + badge + '">' + escHtml(st) + '</span></div></div>' +
                (note.type ? '<div><span class="txn-lbl">Type</span><div class="txn-val">' + escHtml(note.type) + '</div></div>' : '') +
                '</div>'
            );
        } else if (note._raw) {
            $el.html('<div class="txn-val">' + escHtml(note._raw) + '</div>');
        } else {
            $wrap.hide();
            return;
        }
        $wrap.show();
    }

    $(document).ready(function() {
        
        // ১. Select2 চালু করা (সার্চ অপশন সহ)
        $('.select2-box').select2({
            placeholder: "Type Name or Phone to search...",
            allowClear: true,
            width: '100%'
        });

        // ২. সার্চ থেকে ডাটা ফিলাপ করা
        $('#customer_search').on('change', function() {
            var opt = $(this).find(':selected');
            if(opt.val()) {
                $('#final_name').val(opt.data('name'));
                $('#final_phone').val(opt.data('phone'));
            } else {
                $('#final_name').val('');
                $('#final_phone').val('');
            }
        });

        // ৩. ক্যালকুলেশন লজিক
        $('#add-item-btn').click(function() {
            $('#product-list').append(`
                <tr class="product-row border-bottom">
                    <td class="p-3 pl-4"><input type="text" class="form-control border-0 bg-light item-name" placeholder="Item name..." required></td>
                    <td class="p-3"><input type="number" class="form-control border-0 bg-light text-center qty" value="1" min="1" required></td>
                    <td class="p-3"><input type="number" class="form-control border-0 bg-light text-center price" placeholder="0.00" step="0.01" required></td>
                    <td class="p-3 text-right font-weight-bold">৳<span class="row-total">0.00</span></td>
                    <td class="p-3 text-center"><button type="button" class="btn btn-link text-danger p-0 remove-row"><i class="fas fa-times-circle"></i></button></td>
                </tr>
            `);
            checkBtn();
        });

        $(document).on('click', '.remove-row', function() { $(this).closest('tr').remove(); calc(); checkBtn(); });
        $(document).on('input', '.qty, .price, #paid_amount', function() { calc(); });

        function calc() {
            let total = 0;
            let desc = "";
            $('.product-row').each(function(i) {
                let name = $(this).find('.item-name').val();
                let q = parseFloat($(this).find('.qty').val()) || 0;
                let p = parseFloat($(this).find('.price').val()) || 0;
                let sub = q * p;
                $(this).find('.row-total').text(sub.toFixed(2));
                total += sub;
                if(name) desc += `${i+1}. ${name} (${q} x ${p}) = ${sub.toFixed(2)}\n`;
            });

            $('#grand_total_display').text(total.toFixed(2));
            $('#total_bill_input').val(total.toFixed(2));
            $('#final_description').val(desc);

            let paid = parseFloat($('#paid_amount').val()) || 0;
            let due = total - paid;
            if(due < 0) due = 0;
            $('#due_display').text('৳ ' + due.toFixed(2));
            
            if(due > 0) $('#due_display').removeClass('text-success').addClass('text-warning');
            else $('#due_display').removeClass('text-warning').addClass('text-success');
        }

        function checkBtn() {
            if($('.product-row').length > 1) $('.remove-row').show();
            else $('.remove-row').hide();
        }
        
        $('#pos-form').on('submit', function() { calc(); });
    });

    // ৩-ডট মেনু টগল (Custom JS)
    function toggleMenu(id) {
        $('.custom-menu').not('#' + id).hide();
        var menu = document.getElementById(id);
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }

    // বাইরে ক্লিক করলে মেনু বন্ধ
    window.onclick = function(e) {
        if (!e.target.matches('.action-btn') && !e.target.matches('.fa-ellipsis-v')) {
            $('.custom-menu').hide();
        }
    }

    // মডাল ওপেন
    function openDueModal(id) {
        $('.custom-menu').hide();
        $('#modal-' + id).modal('show');
    }

    function openPaymentDetails(orderId) {
        $('.custom-menu').hide();
        const d = ordersData[orderId];
        if (!d) return;

        const fmt = n => '৳' + parseFloat(n || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        $('#paymentDetailsForm').attr('action', '{{ url('admin/pos/payment-status') }}/' + orderId);
        $('#pd-invoice').text(d.invoice);
        $('#pd-customer').text(d.customer || '—');
        $('#pd-phone').text(d.phone || '');
        $('#pd-total').text(fmt(d.total));
        $('#pd-paid').text(fmt(d.paid));
        $('#pd-due').text(fmt(d.due));
        $('#pd-description').text(d.description || '—');
        renderPaymentMethod(d);
        $('#pd-pay-status').html(d.isPaid
            ? '<span class="badge badge-success">Paid</span>'
            : '<span class="badge badge-danger">Due / Unpaid</span>');
        $('#pd-method').val(normalizeMethodSelect(d.method));
        renderPaymentNote(d.note);
        $('#pd-status').val(d.isPaid ? 'paid' : 'unpaid');
        $('#paymentDetailsModal').modal('show');
    }
</script>
@endsection