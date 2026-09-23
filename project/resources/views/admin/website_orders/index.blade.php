@extends('layouts.admin')

@section('styles')
<style>
    .order-stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .order-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    .order-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .order-stat-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .order-stat-lbl {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .order-tier-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-block;
    }
    .tier-source { background: #e0f2fe; color: #0369a1; }
    .tier-branding { background: #fef3c7; color: #b45309; }
    .tier-custom { background: #ede9fe; color: #6d28d9; }
    
    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-processing { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .status-completed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    .filter-tab-pill {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.15s ease;
        margin-right: 6px;
        margin-bottom: 8px;
    }
    .filter-tab-pill:hover, .filter-tab-pill.active {
        background: #ff5722;
        color: #ffffff;
        border-color: #ff5722;
    }
    .filter-tab-pill .badge {
        background: rgba(0,0,0,0.08);
        margin-left: 6px;
        font-size: 0.75rem;
        border-radius: 6px;
        padding: 3px 6px;
    }
    .filter-tab-pill.active .badge {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-shopping-bag',
        'title' => 'ওয়েবসাইট অর্ডারসমূহ (Website Orders)',
        'subtitle' => 'থিম, রেডি ওয়েবসাইট ও কাস্টম প্রজেক্টের লাইভ অর্ডার ট্র্যাকিং ও ডেলিভারি ম্যানেজমেন্ট',
        'breadcrumbs' => [
            ['label' => 'ড্যাশবোর্ড', 'url' => route('admin.dashboard')],
            ['label' => 'ওয়েবসাইট অর্ডার', 'url' => null],
        ],
    ])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="order-stat-card">
                <div>
                    <div class="order-stat-lbl">মোট অর্ডার</div>
                    <div class="order-stat-val">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="order-stat-icon" style="background: #eef2ff; color: #4338ca;">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="order-stat-card">
                <div>
                    <div class="order-stat-lbl">নতুন / পেন্ডিং</div>
                    <div class="order-stat-val" style="color: #ea580c;">{{ number_format($stats['pending']) }}</div>
                </div>
                <div class="order-stat-icon" style="background: #fff7ed; color: #ea580c;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="order-stat-card">
                <div>
                    <div class="order-stat-lbl">প্রসেসিং</div>
                    <div class="order-stat-val" style="color: #2563eb;">{{ number_format($stats['processing']) }}</div>
                </div>
                <div class="order-stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-spinner fa-spin-pulse"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="order-stat-card">
                <div>
                    <div class="order-stat-lbl">কমপ্লিট / ডেলিভার্ড</div>
                    <div class="order-stat-val" style="color: #16a34a;">{{ number_format($stats['completed']) }}</div>
                </div>
                <div class="order-stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Panel --}}
    <div class="adm-panel">
        <div class="adm-panel__head d-flex flex-wrap justify-content-between align-items-center" style="gap: 15px;">
            <div class="d-flex flex-wrap align-items-center">
                @php $currentStatus = request('status', 'all'); @endphp
                <a href="{{ route('admin.website_orders.index', ['status' => 'all', 'search' => request('search')]) }}" 
                   class="filter-tab-pill {{ $currentStatus == 'all' ? 'active' : '' }}">
                    সকল অর্ডার <span class="badge">{{ $stats['total'] }}</span>
                </a>
                <a href="{{ route('admin.website_orders.index', ['status' => 'Pending', 'search' => request('search')]) }}" 
                   class="filter-tab-pill {{ $currentStatus == 'Pending' ? 'active' : '' }}">
                    পেন্ডিং <span class="badge">{{ $stats['pending'] }}</span>
                </a>
                <a href="{{ route('admin.website_orders.index', ['status' => 'Processing', 'search' => request('search')]) }}" 
                   class="filter-tab-pill {{ $currentStatus == 'Processing' ? 'active' : '' }}">
                    প্রসেসিং <span class="badge">{{ $stats['processing'] }}</span>
                </a>
                <a href="{{ route('admin.website_orders.index', ['status' => 'Completed', 'search' => request('search')]) }}" 
                   class="filter-tab-pill {{ $currentStatus == 'Completed' ? 'active' : '' }}">
                    কমপ্লিট <span class="badge">{{ $stats['completed'] }}</span>
                </a>
                <a href="{{ route('admin.website_orders.index', ['status' => 'Cancelled', 'search' => request('search')]) }}" 
                   class="filter-tab-pill {{ $currentStatus == 'Cancelled' ? 'active' : '' }}">
                    বাতিল <span class="badge">{{ $stats['cancelled'] }}</span>
                </a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('admin.website_orders.index') }}" method="GET" class="d-flex align-items-center" style="min-width: 280px;">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="অর্ডার #, নাম, ফোন বা ডোমেইন..." value="{{ request('search') }}" style="border-radius: 10px 0 0 10px; font-size: 0.88rem;">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" style="background: #ff5722; border-color: #ff5722; border-radius: 0 10px 10px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.website_orders.index', ['status' => request('status', 'all')]) }}" class="btn btn-light btn-sm ml-2 text-danger" title="সার্চ মুছুন">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="adm-panel__body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead style="background: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="pl-4">অর্ডার আইডি ও তারিখ</th>
                            <th>থিম / প্রোডাক্ট</th>
                            <th>প্যাকেজ টিয়ার</th>
                            <th>কাস্টমার তথ্য</th>
                            <th>ডোমেইন</th>
                            <th>পেমেন্ট ও মেথড</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right pr-4">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="pl-4">
                                <div style="font-weight: 800; color: #0f172a;">
                                    #{{ $order->order_number }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y, h:i A') }}
                                </div>
                            </td>
                            <td>
                                @if($order->product)
                                    <div style="font-weight: 700; color: #1e293b;">
                                        <a href="{{ route('frontend.product.details', $order->product->slug) }}" target="_blank" class="text-dark" title="থিম দেখুন">
                                            {{ \Illuminate\Support\Str::limit($order->product->name, 28) }}
                                            <i class="fas fa-external-link-alt ml-1 text-muted" style="font-size: 0.7rem;"></i>
                                        </a>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        মূল্য: ৳ {{ number_format($order->product->price) }}
                                    </div>
                                @else
                                    <span style="font-weight: 600; color: #334155;">{{ $order->description ?? 'কাস্টম ওয়েবসাইট' }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $tier = $order->package_tier ?? 'Source Code';
                                    $tierClass = 'tier-source';
                                    if(str_contains(strtolower($tier), 'brand') || str_contains($tier, 'ব্র্যান্ডিং')) $tierClass = 'tier-branding';
                                    elseif(str_contains(strtolower($tier), 'custom') || str_contains($tier, 'কাস্টম')) $tierClass = 'tier-custom';
                                @endphp
                                <span class="order-tier-badge {{ $tierClass }}">
                                    {{ $order->package_tier ?? 'সোর্স কোড' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">
                                    {{ $order->customer->name ?? 'Guest Client' }}
                                </div>
                                @php $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone ?? ($order->customer->phone ?? '')); @endphp
                                @if($cleanPhone)
                                    <div style="font-size: 0.8rem;">
                                        <a href="https://wa.me/{{ str_starts_with($cleanPhone, '880') ? $cleanPhone : '88' . ltrim($cleanPhone, '0') }}" target="_blank" class="text-success font-weight-bold" title="WhatsApp-এ যোগাযোগ করুন">
                                            <i class="fab fa-whatsapp mr-1"></i> {{ $order->customer_phone ?? ($order->customer->phone ?? '') }}
                                        </a>
                                    </div>
                                @endif
                                @if($order->customer_email ?? ($order->customer->email ?? false))
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="far fa-envelope mr-1"></i> {{ $order->customer_email ?? $order->customer->email }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($order->domain_name)
                                    <span class="badge badge-light border px-2 py-1" style="font-weight: 600; font-family: monospace;">
                                        <i class="fas fa-globe mr-1 text-primary"></i> {{ $order->domain_name }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 0.78rem;">প্রযোজ্য নয় / দেওয়া হয়নি</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                    ৳ {{ number_format($order->total_amount) }}
                                </div>
                                <div style="font-size: 0.75rem;">
                                    <span class="badge badge-secondary px-2 py-0" style="font-size: 0.7rem;">
                                        {{ $order->payment_method ?? 'bKash/Nagad' }}
                                    </span>
                                    @if($order->trx_id)
                                        <div class="text-primary mt-1 font-weight-bold" style="font-size: 0.72rem; font-family: monospace;">
                                            Trx: {{ $order->trx_id }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $st = $order->status;
                                    $stClass = 'status-pending';
                                    $stIcon = 'fa-clock';
                                    if($st == 'Processing') { $stClass = 'status-processing'; $stIcon = 'fa-spinner fa-spin'; }
                                    elseif($st == 'Completed') { $stClass = 'status-completed'; $stIcon = 'fa-check'; }
                                    elseif($st == 'Cancelled') { $stClass = 'status-cancelled'; $stIcon = 'fa-times'; }
                                @endphp
                                <span class="status-badge {{ $stClass }}">
                                    <i class="fas {{ $stIcon }}"></i> {{ $st }}
                                </span>
                            </td>
                            <td class="text-right pr-4">
                                <div class="btn-group">
                                    {{-- Live Invoice Button --}}
                                    <a href="{{ route('frontend.invoice.live', ['order_number' => $order->order_number, 'token' => $order->hash_token]) }}" target="_blank" class="btn btn-light btn-sm mr-1" title="লাইভ ইনভয়েস দেখুন ও প্রিন্ট করুন" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <i class="fas fa-file-invoice text-primary"></i>
                                    </a>

                                    {{-- Quick Status Toggle Modal Trigger --}}
                                    <button type="button" class="btn btn-light btn-sm mr-1" data-toggle="modal" data-target="#statusModal-{{ $order->id }}" title="স্ট্যাটাস পরিবর্তন করুন" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <i class="fas fa-edit text-warning"></i>
                                    </button>

                                    {{-- Delete Modal Trigger --}}
                                    <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $order->id }}" title="ডিলিট করুন" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </div>

                                {{-- Status Modal --}}
                                <div class="modal fade text-left" id="statusModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
                                            <form action="{{ route('admin.website_orders.status', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title font-weight-bold" style="color: #0f172a;">
                                                        <i class="fas fa-tasks mr-2 text-primary"></i> অর্ডার স্ট্যাটাস পরিবর্তন
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body pt-3">
                                                    <p class="text-muted" style="font-size: 0.88rem;">
                                                        অর্ডার: <strong>#{{ $order->order_number }}</strong> ({{ $order->customer->name ?? 'Guest' }})
                                                    </p>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold" style="font-size: 0.85rem;">নতুন স্ট্যাটাস নির্বাচন করুন</label>
                                                        <select name="status" class="form-control" style="border-radius: 10px;">
                                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending (পেন্ডিং / নতুন অর্ডার)</option>
                                                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing (কাজ চলছে / প্রসেসিং)</option>
                                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed (ডেলিভারি সম্পন্ন)</option>
                                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled (বাতিল করা হয়েছে)</option>
                                                        </select>
                                                    </div>
                                                    @if($order->notes)
                                                        <div class="p-3 bg-light rounded" style="font-size: 0.8rem; border-left: 3px solid #ff5722;">
                                                            <strong>কাস্টমারের নোট:</strong> {{ $order->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 10px;">বাতিল</button>
                                                    <button type="submit" class="btn btn-primary" style="background: #ff5722; border-color: #ff5722; border-radius: 10px;">
                                                        <i class="fas fa-save mr-1"></i> আপডেট করুন
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delete Modal --}}
                                <div class="modal fade text-left" id="deleteModal-{{ $order->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 16px; border: none;">
                                            <form action="{{ route('admin.website_orders.destroy', $order->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title font-weight-bold text-danger">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i> অর্ডার ডিলিট নিশ্চিতকরণ
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body pt-3">
                                                    <p style="font-size: 0.9rem; color: #334155;">
                                                        আপনি কি সত্যিই অর্ডার <strong>#{{ $order->order_number }}</strong> ডিলিট করতে চান? এটি রিভার্স করা সম্ভব নয়।
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 10px;">না</button>
                                                    <button type="submit" class="btn btn-danger" style="border-radius: 10px;">
                                                        <i class="fas fa-trash-alt mr-1"></i> হ্যাঁ, ডিলিট করুন
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 text-secondary" style="opacity: 0.5;"></i>
                                    <h5 class="font-weight-bold">কোনো অর্ডার পাওয়া যায়নি</h5>
                                    <p class="small text-muted">ওয়েবসাইটে নতুন অর্ডার আসলে তা এখানে স্বয়ংক্রিয়ভাবে প্রদর্শিত হবে।</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-4 d-flex justify-content-between align-items-center border-top">
                    <div class="text-muted small">
                        দেখানো হচ্ছে {{ $orders->firstItem() ?? 0 }} থেকে {{ $orders->lastItem() ?? 0 }} (মোট {{ $orders->total() }} টি অর্ডার)
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
