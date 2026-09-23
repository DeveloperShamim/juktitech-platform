@extends('layouts.front')

@section('contents')
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="mb-3"><i class="fas fa-check-circle text-success" style="font-size:3rem;"></i></div>
                        <h3 class="fw-bold">{{ $order->product->name }}</h3>
                        <p class="text-muted">Payment verified — secure download ready</p>

                        @if($order->canDownload())
                            <p class="small text-muted mb-4">
                                বাকি ডাউনলোড: <strong>{{ $order->downloadsRemaining() }}</strong> / {{ $order->download_limit }}
                            </p>
                            <a href="{{ route('digital-product.download', $order->download_token) }}" class="btn btn-orange btn-lg px-5 rounded-3 fw-bold">
                                <i class="fas fa-download me-2"></i> ফাইল ডাউনলোড
                            </a>
                        @elseif($order->isPaid())
                            <div class="alert alert-warning">ডাউনলোড লিমিট শেষ হয়েছে। সাপোর্টে যোগাযোগ করুন।</div>
                        @else
                            <div class="alert alert-warning">পেমেন্ট এখনো confirm হয়নি। কিছুক্ষণ পর refresh করুন।</div>
                        @endif

                        <p class="small text-muted mt-4 mb-0">এই page bookmark করে রাখুন — পরে আবার download করতে পারবেন (limit পর্যন্ত)।</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>.btn-orange{background:var(--primary-color);color:#fff;border:none}.btn-orange:hover{background:var(--primary-hover);color:#fff}</style>
@endsection
