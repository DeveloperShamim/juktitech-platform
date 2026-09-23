@extends('layouts.front')
@section('title', 'ডিজিটাল ডাউনলোড')
@section('contents')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f3f4f6; }
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    .user-profile-box { text-align: center; padding: 35px 20px; border-bottom: 1px solid #f1f5f9; }
    .user-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 5px 15px rgba(0,0,0,0.1); object-fit: cover; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; transition: 0.3s; font-weight: 500; }
    .menu-link i { width: 30px; font-size: 18px; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }

    .dl-hero {
        background: linear-gradient(135deg, #1e293b 0%, #334155 55%, #475569 100%);
        border-radius: 20px; color: #fff; padding: 32px 36px; margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .dl-hero::after {
        content: ''; position: absolute; right: -30px; top: -30px; width: 180px; height: 180px;
        background: rgba(255,102,0,.15); border-radius: 50%;
    }
    .dl-stat {
        background: #fff; border-radius: 16px; border: 1px solid #edf2f7;
        padding: 20px 22px; height: 100%; box-shadow: 0 4px 6px rgba(0,0,0,.03);
        transition: .25s;
    }
    .dl-stat:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(15,23,42,.06); }
    .dl-stat .icon {
        width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 12px;
    }
    .dl-stat .num { font-size: 1.75rem; font-weight: 800; line-height: 1; color: #0f172a; }
    .dl-stat .lbl { font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px; }

    .table-card {
        background: #fff; border-radius: 20px; border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px rgba(0,0,0,.04); padding: 28px; overflow: hidden;
    }
    .table-card h5 { font-weight: 800; color: #0f172a; }
    .dl-table thead th {
        background: #f8fafc; border: none; padding: 14px 16px;
        font-size: 12px; text-transform: uppercase; letter-spacing: .04em;
        color: #64748b; font-weight: 700;
    }
    .dl-table tbody td { padding: 18px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; color: #334155; }
    .dl-table tbody tr:hover { background: #fafbfc; }
    .product-cell { display: flex; align-items: center; gap: 14px; min-width: 220px; }
    .product-thumb {
        width: 52px; height: 52px; border-radius: 12px; object-fit: cover;
        border: 1px solid #e2e8f0; background: #f8fafc; flex-shrink: 0;
    }
    .product-thumb-placeholder {
        width: 52px; height: 52px; border-radius: 12px; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center; color: #94a3b8;
    }
    .product-name { font-weight: 700; color: #0f172a; margin-bottom: 2px; }
    .product-meta { font-size: 12px; color: #94a3b8; }

    .badge-dl {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;
    }
    .badge-dl.paid { background: #dcfce7; color: #15803d; }
    .badge-dl.pending { background: #fef3c7; color: #b45309; }
    .badge-dl.failed { background: #fee2e2; color: #b91c1c; }

    .progress-wrap { min-width: 120px; }
    .progress-bar-dl { height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
    .progress-bar-dl span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, #ff6600, #ff8533); }

    .btn-dl {
        background: #ff6600; color: #fff; border: none; font-weight: 700;
        border-radius: 999px; padding: 8px 16px; font-size: 13px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-dl:hover { background: #e65c00; color: #fff; }
    .btn-dl-outline {
        border: 1px solid #cbd5e1; color: #475569; background: #fff; font-weight: 600;
        border-radius: 999px; padding: 8px 14px; font-size: 13px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-dl-outline:hover { background: #f8fafc; color: #0f172a; }

    .empty-box {
        text-align: center; padding: 60px 24px;
    }
    .empty-box .icon-wrap {
        width: 88px; height: 88px; border-radius: 50%; background: #f1f5f9;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 2rem; color: #94a3b8; margin-bottom: 16px;
    }

    @media (max-width: 991px) {
        .dl-table thead { display: none; }
        .dl-table tbody tr { display: block; border: 1px solid #edf2f7; border-radius: 16px; margin-bottom: 14px; padding: 16px; }
        .dl-table tbody td { display: flex; justify-content: space-between; align-items: center; border: none; padding: 8px 0; }
        .dl-table tbody td::before { content: attr(data-label); font-weight: 700; color: #64748b; font-size: 12px; text-transform: uppercase; }
        .dl-table tbody td:first-child { display: block; padding-bottom: 12px; margin-bottom: 8px; border-bottom: 1px solid #f1f5f9; }
        .dl-table tbody td:first-child::before { display: none; }
    }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('user.partials.client-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dl-hero">
                    <div class="position-relative" style="z-index:1;">
                        <h3 class="fw-bold mb-2"><i class="fas fa-cloud-arrow-down me-2"></i> ডিজিটাল ডাউনলোড সেন্টার</h3>
                        <p class="mb-0 opacity-75">আপনার কেনা থিম ও ডিজিটাল প্রোডাক্ট — এক জায়গায় manage ও download করুন</p>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="dl-stat">
                            <div class="icon bg-primary-subtle text-primary"><i class="fas fa-box"></i></div>
                            <div class="num">{{ $orders->total() }}</div>
                            <div class="lbl">মোট অর্ডার</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="dl-stat">
                            <div class="icon bg-success-subtle text-success"><i class="fas fa-circle-check"></i></div>
                            <div class="num">{{ $paidCount }}</div>
                            <div class="lbl">পরিশোধিত</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="dl-stat">
                            <div class="icon bg-warning-subtle text-warning"><i class="fas fa-hourglass-half"></i></div>
                            <div class="num">{{ $pendingCount }}</div>
                            <div class="lbl">পেন্ডিং</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="dl-stat">
                            <div class="icon" style="background:#fff7ed;color:#ea580c;"><i class="fas fa-download"></i></div>
                            <div class="num">{{ $availableDownloads }}</div>
                            <div class="lbl">ডাউনলোড বাকি</div>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <h5 class="m-0">অর্ডার তালিকা</h5>
                        <a href="{{ route('frontend.themes') }}" class="btn-dl-outline">
                            <i class="fas fa-store"></i> নতুন থিম কিনুন
                        </a>
                    </div>

                    @if($orders->count())
                        <div class="table-responsive">
                            <table class="table dl-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>প্রোডাক্ট</th>
                                        <th>অর্ডার</th>
                                        <th>তারিখ</th>
                                        <th>পরিমাণ</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>ডাউনলোড</th>
                                        <th class="text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        @php
                                            $used = $order->download_count;
                                            $limit = max(1, $order->download_limit);
                                            $pct = min(100, round(($used / $limit) * 100));
                                        @endphp
                                        <tr>
                                            <td data-label="প্রোডাক্ট">
                                                <div class="product-cell">
                                                    @if($order->product?->photo)
                                                        <img src="{{ asset_image('assets/images/products/'.$order->product->photo) }}" class="product-thumb" alt="">
                                                    @else
                                                        <div class="product-thumb-placeholder"><i class="fas fa-palette"></i></div>
                                                    @endif
                                                    <div>
                                                        <div class="product-name">{{ $order->product->name ?? 'Digital Product' }}</div>
                                                        <div class="product-meta">{{ $order->product->digital_file_name ?: 'Secure file download' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="অর্ডার">
                                                <span class="fw-bold text-primary">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td data-label="তারিখ">
                                                <div>{{ $order->created_at?->format('d M Y') }}</div>
                                                <div class="product-meta">{{ $order->created_at?->format('h:i A') }}</div>
                                            </td>
                                            <td data-label="পরিমাণ" class="fw-bold">৳ {{ number_format($order->amount, 0) }}</td>
                                            <td data-label="স্ট্যাটাস">
                                                @if($order->status === 'paid')
                                                    <span class="badge-dl paid"><i class="fas fa-check"></i> Paid</span>
                                                @elseif($order->status === 'pending')
                                                    <span class="badge-dl pending"><i class="fas fa-clock"></i> Pending</span>
                                                @else
                                                    <span class="badge-dl failed">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td data-label="ডাউনলোড">
                                                @if($order->isPaid())
                                                    <div class="progress-wrap">
                                                        <div class="small"><strong>{{ $order->downloadsRemaining() }}</strong> / {{ $order->download_limit }} বাকি</div>
                                                        <div class="progress-bar-dl"><span style="width:{{ $pct }}%"></span></div>
                                                    </div>
                                                @elseif($order->status === 'pending')
                                                    <span class="text-warning small fw-semibold"><i class="fas fa-spinner fa-spin me-1"></i> Admin verify</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td data-label="অ্যাকশন" class="text-end">
                                                @if($order->canDownload())
                                                    <a href="{{ route('digital-product.download', $order->download_token) }}" class="btn-dl">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <a href="{{ route('digital-product.order.success', $order->download_token) }}" class="btn-dl-outline ms-1">
                                                        Details
                                                    </a>
                                                @elseif($order->isPaid())
                                                    <span class="text-muted small">লিমিট শেষ</span>
                                                @elseif($order->status === 'pending')
                                                    <span class="text-muted small">Approve হলে unlock</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $orders->links() }}</div>
                    @else
                        <div class="empty-box">
                            <div class="icon-wrap"><i class="fas fa-folder-open"></i></div>
                            <h5 class="fw-bold mb-2">এখনো কোনো ডিজিটাল অর্ডার নেই</h5>
                            <p class="text-muted mb-4">Premium থিম কিনলে এখানে download link পাবেন</p>
                            <a href="{{ route('frontend.themes') }}" class="btn-dl px-4">
                                <i class="fas fa-shopping-bag"></i> থিম দেখুন
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
