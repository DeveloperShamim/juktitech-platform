@extends('layouts.front')

@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ (Search Engine Optimization) --}}
    <title>{{ __('Price Plan') }} - {{ $gs->title }}</title>
    <meta name="description" content="আপনার ব্যবসার জন্য সঠিক প্যাকেজটি বেছে নিন। আমরা দিচ্ছি সাশ্রয়ী মূল্যে সেরা সার্ভিস।">
    <meta name="keywords" content="price plan,pricing,service,our service,সার্ভিস,about us,Jukti Tech,juktitech,packages">
    <meta name="author" content="{{ __('Price Plan') }} - {{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ২. ওপেন গ্রাফ ট্যাগ (Facebook, LinkedIn, WhatsApp-এ শেয়ার করার জন্য) --}}
    <meta property="og:title" content="{{ __('Price Plan') }} - {{ $gs->title }}" />
    <meta property="og:description" content="আপনার ব্যবসার জন্য সঠিক প্যাকেজটি বেছে নিন। আমরা দিচ্ছি সাশ্রয়ী মূল্যে সেরা সার্ভিস।" />
    <meta property="og:image" content="{{ asset_image('assets/images/logo/price.png') }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ __('Price Plan') }} - {{ $gs->title }}" />

    {{-- ৩. টুইটার কার্ড ট্যাগ (Twitter-এর জন্য) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Price Plan') }} - {{ $gs->title }}">
    <meta name="twitter:description" content="আপনার ব্যবসার জন্য সঠিক প্যাকেজটি বেছে নিন। আমরা দিচ্ছি সাশ্রয়ী মূল্যে সেরা সার্ভিস।">
    <meta name="twitter:image" content="{{ asset_image('assets/images/logo/price.png') }}">
@endsection

@section('contents')

    <section class="pricing-hero py-5 text-white" style="background: var(--cta-gradient);">
        <div class="container py-5 text-center">
            <h1 class="fw-bold display-4 mb-3">আমাদের <span class="hero-accent">প্রাইসিং</span> প্ল্যান</h1>
            <p class="lead opacity-75">আপনার ব্যবসার জন্য সঠিক প্যাকেজটি বেছে নিন। আমরা দিচ্ছি সাশ্রয়ী মূল্যে সেরা সার্ভিস।</p>
        </div>
    </section>

    <section class="py-5 bg-light position-relative" style="margin-top: -50px;">
        <div class="container">
            <div class="row g-4 justify-content-center align-items-stretch">
                
                @forelse($pricings as $pricing)
                <div class="col-lg-4 col-md-6 mb-4">
                    {{-- ডাটাবেসের 'is_featured' কলাম অনুযায়ী হাইলাইট --}}
                    <div class="card pricing-card h-100 border-0 shadow-lg text-center position-relative {{ $pricing->is_featured == 1 ? 'featured-card' : '' }}" style="border-radius: 25px; transition: 0.4s; background: #fff;">
                        
                        {{-- Best Value ব্যাজ --}}
                        @if($pricing->is_featured == 1)
                            <div class="popular-badge">BEST VALUE</div>
                        @endif

                        <div class="card-body p-4 p-md-5 d-flex flex-column">
                            <h3 class="fw-bold mb-3 text-dark">{{ $pricing->title }}</h3>
                            
                            <div class="price-box mb-4 py-3" style="background: var(--accent-shadow-xs); border-radius: 15px;">
                                <h2 class="display-4 fw-bold mb-0" style="color: var(--primary-color);">
                                    <span class="fs-3">{{ $pricing->currency }}</span>{{ $pricing->price }}
                                </h2>
                                <p class="text-muted mb-0 small text-uppercase fw-bold">{{ $pricing->duration }}</p>
                            </div>

                            {{-- ফিচার লিস্ট (সংশোধিত) --}}
                            <ul class="list-unstyled mb-5 text-start mx-auto w-100" style="max-width: 280px; min-height: 200px;">
                                @php
                                    // আপনার ডাটাবেসে কলামের নাম 'features'
                                    $feature_list = explode(',', $pricing->features);
                                @endphp
                                
                                @foreach($feature_list as $feature)
                                    @if(!empty(trim($feature)))
                                    <li class="mb-3 d-flex align-items-start">
                                        <i class="fas fa-check-circle me-3 mt-1" style="color: #28a745; font-size: 18px;"></i>
                                        <span class="text-muted" style="font-size: 15px; line-height: 1.5;">{{ trim($feature) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>

                            <div class="mt-auto">
                                {{-- ডাটাবেসের order_link ব্যবহার করা হয়েছে --}}
                                <a href="{{ $pricing->order_link ?? route('login') }}" target="_blank" 
                                   class="btn btn-lg w-100 py-3 fw-bold {{ $pricing->is_featured == 1 ? 'text-white shadow orange-active-btn' : 'shadow-sm blue-outline-btn' }}">
                                    {{ $pricing->is_featured == 1 ? 'এখনই কিনুন' : 'অর্ডার করুন' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">বর্তমানে কোনো প্রাইসিং প্ল্যান পাওয়া যায়নি।</p>
                </div>
                @endforelse

            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="p-5 shadow-sm rounded-4 border">
                        <h3 class="fw-bold mb-3">কাস্টম প্যাকেজ প্রয়োজন?</h3>
                        <p class="text-muted mb-4">আপনার যদি বিশেষ কোনো চাহিদা থাকে তবে আমাদের সরাসরি কল দিন অথবা মেসেজ করুন।</p>
                        <a href="{{ url('/contact') }}" class="btn btn-outline-dark btn-lg px-5 rounded-pill fw-bold">যোগাযোগ করুন</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .pricing-card { overflow: hidden; height: 100%; border: 1px solid #eee !important; }
        
        /* হাইলাইটেড অরেঞ্জ কার্ড (মাঝখানের জন্য) */
        .featured-card { 
            border: 2.5px solid var(--primary-color) !important; 
            transform: scale(1.05); 
            z-index: 10; 
            background-color: var(--primary-bg-light) !important; 
        }

        /* Best Value ব্যাজ ডিজাইন */
        .popular-badge {
            position: absolute;
            top: 20px;
            right: -10px;
            background: var(--primary-color);
            color: #fff;
            padding: 5px 15px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 5px 0 0 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* অরেঞ্জ বাটন স্টাইল */
        .orange-active-btn { background: var(--primary-color); border-radius: 50px; border: none; }
        .orange-active-btn:hover { background: var(--primary-hover); color: #fff; transform: translateY(-3px); }

        /* ব্লু আউটলাইন বাটন স্টাইল */
        .blue-outline-btn { 
            border: 1.5px solid var(--primary-color); 
            color: var(--primary-color); 
            border-radius: 50px; 
            background: transparent; 
            transition: 0.3s; 
        }
        .blue-outline-btn:hover { background: var(--primary-color); color: #fff; }

        .pricing-card:hover { box-shadow: 0 25px 50px rgba(0,0,0,0.1) !important; }
        .pricing-card ul li { transition: 0.2s; }
    </style>

@endsection