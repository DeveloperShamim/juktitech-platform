@extends('layouts.front')

@section('contents')
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold mb-1">ডিজিটাল প্রোডাক্ট কিনুন</h3>
                        <p class="text-muted mb-4">{{ $product->name }} — ৳ {{ number_format($product->price, 0) }}</p>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="alert alert-light border mb-4">
                            <div class="small text-muted mb-1">লগইন করা অ্যাকাউন্ট</div>
                            <div class="fw-bold">{{ $user->name }}</div>
                            <div>{{ $user->email }}</div>
                            @if($user->phone)
                                <div>{{ $user->phone }}</div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('digital-product.checkout.submit', $product) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="install_domain" class="form-label fw-bold">কোন ডোমেইনে ইনস্টল করবেন? <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="install_domain"
                                       name="install_domain"
                                       value="{{ old('install_domain') }}"
                                       class="form-control form-control-lg @error('install_domain') is-invalid @enderror"
                                       placeholder="example.com"
                                       maxlength="255"
                                       required
                                       autofocus>
                                @error('install_domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">পেমেন্ট সফল হলে এই ডোমেইনের লাইসেন্স স্বয়ংক্রিয়ভাবে তৈরি হবে।</small>
                            </div>
                            <div class="alert alert-info small">
                                পেমেন্ট সফল হলে লাইসেন্স ও secure download page পাবেন।
                                সর্বোচ্চ <strong>{{ $product->download_limit ?: 3 }} বার</strong> ডাউনলোড করা যাবে।
                                Google Drive link সরাসরি public হবে না।
                            </div>

                            <button type="submit" class="btn btn-orange btn-lg w-100 rounded-3 fw-bold">
                                ৳ {{ number_format($product->price, 0) }} পেমেন্ট করুন
                            </button>
                            <a href="{{ route('frontend.product.details', $product->slug) }}" class="btn btn-link w-100 mt-2">ফিরে যান</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>.btn-orange{background:var(--primary-color);color:#fff;border:none}.btn-orange:hover{background:var(--primary-hover);color:#fff}</style>
@endsection
