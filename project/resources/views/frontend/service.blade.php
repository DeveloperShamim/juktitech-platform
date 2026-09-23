@extends('layouts.front')

@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ (Search Engine Optimization) --}}
    <title>{{ __('Service') }} - {{ $gs->title }}</title>
    <meta name="description" content="আপনার অনলাইন ব্যবসার নির্ভরযোগ্য সহযোগী">
    <meta name="keywords" content="service,our service,সার্ভিস,about us,aboutus,আমাদের সম্পর্কে,Jukti Tech,juktitech,web development,software,ecommerce">
    <meta name="author" content="{{ __('Service') }} - {{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ২. ওপেন গ্রাফ ট্যাগ (Facebook, LinkedIn, WhatsApp-এ শেয়ার করার জন্য) --}}
    <meta property="og:title" content="{{ __('Service') }} - {{ $gs->title }}" />
    <meta property="og:description" content="আপনার অনলাইন ব্যবসার নির্ভরযোগ্য সহযোগী" />
    <meta property="og:image" content="{{ asset_image('assets/images/logo/service.webp') }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ __('Service') }} - {{ $gs->title }}" />

    {{-- ৩. টুইটার কার্ড ট্যাগ (Twitter-এর জন্য) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Service') }} - {{ $gs->title }}">
    <meta name="twitter:description" content="আপনার অনলাইন ব্যবসার নির্ভরযোগ্য সহযোগী">
    <meta name="twitter:image" content="{{ asset_image('assets/images/logo/service.webp') }}">

@endsection

@section('contents')

    @php
        $hero = \DB::table('about_section_contents')->where('id', 1)->first();
    @endphp

    <section class="hero-section py-5 text-white" style="background: var(--cta-gradient); position: relative;">
        <div class="container py-5 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="display-4 fw-bold mb-3">
                        @if(!empty($hero->title))
                            {!! str_replace('নির্ভরযোগ্য', '<span class="hero-accent">নির্ভরযোগ্য</span>', $hero->title) !!}
                        @else
                            আমাদের প্রিমিয়াম <span class="hero-accent">সেবাসমূহ</span>
                        @endif
                    </h1>
                    <p class="lead mb-0 opacity-75">
                        {{ $hero->subtitle ?? 'আমরা দিচ্ছি প্রিমিয়াম কোয়ালিটির ই-কমার্স ওয়েবসাইট, ডিজিটাল মার্কেটিং এবং সফটওয়্যার সলিউশন।' }}
                    </p>
                </div>
            </div>
        </div>
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: var(--primary-color);"></div>
    </section>

    <section id="our-services" class="py-5 bg-light">
        <div class="container-fluid px-lg-5 py-4">
            <div class="text-center mb-5">
                <h6 class="fw-bold text-uppercase" style="letter-spacing: 2px; color: var(--primary-color);">আমাদের দক্ষতা</h6>
                <h2 class="fw-bold" style="color: #1a1a1a;">আপনার ব্যবসার পূর্ণাঙ্গ সমাধান</h2>
                <div class="mx-auto mt-2" style="width: 60px; height: 3px; background: var(--primary-color);"></div>
            </div>

            <div class="row g-4">
                @php
                    $services = App\Models\Service::orderBy('id', 'desc')->get();
                @endphp

                @forelse($services as $service)
                    {{-- col-xl-3 মানে এক লাইনে ৪টি কার্ড --}}
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm p-4 text-center orange-hover-card">
                            <div class="icon-container mb-3 mx-auto d-flex align-items-center justify-content-center">
                                <i class="{{ $service->icon }}"></i>
                            </div>
                            <h5 class="fw-bold mb-2 card-title-text">{{ $service->title }}</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                                {{ $service->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">বর্তমানে কোনো সেবা যুক্ত করা নেই।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-5 text-center text-white" style="background: var(--cta-gradient);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-3">আপনার ব্যবসার গ্রোথ পার্টনার হতে আমরা প্রস্তুত</h3>
                    <p class="mb-4 opacity-100">সব সমাধান এক ছাদের নিচে - আজই শুরু করুন!</p>
                    <a href="{{ URL::to('/freeconsultant') }}" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold shadow">ফ্রি কনসালটেন্সি নিন</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        :root {
            --brand-orange: var(--primary-color);
            --brand-dark: #1a1a1a;
        }
        
        .orange-hover-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            background: #fff;
            border-bottom: 3px solid transparent !important;
        }
        
        .orange-hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(124, 58, 237, 0.1) !important;
            border-bottom: 3px solid var(--brand-orange) !important;
        }
        
        .icon-container {
            width: 65px;
            height: 65px;
            background: var(--navbar-border);
            color: var(--brand-orange);
            border-radius: 50%;
            font-size: 28px;
            transition: 0.3s;
        }
        
        .orange-hover-card:hover .icon-container {
            background: var(--brand-orange);
            color: #fff;
            transform: scale(1.1) rotate(5deg);
        }

        .card-title-text {
            color: var(--brand-dark);
            transition: 0.3s;
        }

        .orange-hover-card:hover .card-title-text {
            color: var(--brand-orange);
        }

        /* Hero Text Animation */
        .hero-section h1 {
            letter-spacing: -1px;
        }
    </style>

@endsection