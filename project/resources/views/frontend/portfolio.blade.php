@extends('layouts.front')

@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ --}}
    <title>{{ __('Portfolio') }} - {{ $gs->title }}</title>
    <meta name="description" content="আমাদের পোর্টফোলিও প্রজেক্টসমূহ">
    <meta name="keywords" content="portfolio,our port folio,service,our service,সার্ভিস,about us,aboutus,আমাদের সম্পর্কে,Jukti Tech,juktitech">
    <meta name="author" content="{{ __('Portfolio') }} - {{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ __('Portfolio') }} - {{ $gs->title }}" />
    <meta property="og:description" content="আমাদের পোর্টফোলিও প্রজেক্টসমূহ" />
    <meta property="og:image" content="{{ asset_image('assets/images/logo/port.webp') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Portfolio') }} - {{ $gs->title }}">
    <meta name="twitter:description" content="আমাদের পোর্টফোলিও প্রজেক্টসমূহ">
    <meta name="twitter:image" content="{{ asset_image('assets/images/logo/port.webp') }}">
@endsection

@section('contents')

    @php
        $portfolio_categories = \DB::table('portfolio_categories')->get();
        $portfolios = \App\Models\Portfolio::with('category')->orderBy('id', 'desc')->paginate(6);
    @endphp

    <section class="portfolio-hero py-5 text-white" style="background: var(--cta-gradient);">
        <div class="container py-5 text-center">
            <h1 class="fw-bold display-4 mb-3">আমাদের <span class="hero-accent">পোর্টফোলিও</span> প্রজেক্ট</h1>
            <p class="lead opacity-75">আমরা যেসব ওয়েবসাইট ও সফটওয়্যার তৈরি করেছি তার কিছু অংশ এখানে দেখুন</p>
        </div>
    </section>

    <section id="portfolio" class="py-5 bg-light position-relative" style="margin-top: -30px;">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="portfolio-filter">
                        <button class="btn-filter active" data-filter="*">সব</button>
                        @foreach($portfolio_categories as $p_cat)
                            <button class="btn-filter" data-filter=".{{ $p_cat->slug }}">{{ $p_cat->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row portfolio-container g-4">
                @forelse($portfolios as $portfolio)
                <div class="col-lg-4 col-md-6 portfolio-item {{ $portfolio->category->slug }}">
                    <div class="card border-0 shadow-sm overflow-hidden h-100 portfolio-wrapper">
                        <div class="portfolio-img-box position-relative">
                            <img src="{{ asset_image('assets/images/portfolio/'.$portfolio->photo) }}" class="img-fluid w-100" alt="{{ $portfolio->title }}">
                            <div class="portfolio-overlay d-flex flex-column align-items-center justify-content-center">
                                <h5 class="text-white fw-bold mb-1">{{ $portfolio->title }}</h5>
                                <p class="text-white-50 small mb-3">{{ $portfolio->category->name }}</p>
                                <a href="{{ $portfolio->url }}" target="_blank" class="btn btn-light btn-sm rounded-pill px-4 fw-bold text-orange">
                                    <i class="fas fa-link me-1"></i> সাইট দেখুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">বর্তমানে কোনো পোর্টফোলিও প্রজেক্ট নেই।</p>
                </div>
                @endforelse
            </div>

            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $portfolios->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4 text-center">
            <div class="custom-package-card p-5 shadow-sm rounded-4 border mx-auto" style="max-width: 850px; border-radius: 20px;">
                <h2 class="fw-bold mb-3" style="color: #222;">আপনার প্রজেক্টও এখানে দেখতে চান?</h2>
                <p class="text-muted mb-4 fs-5">আজই আমাদের সাথে যোগাযোগ করুন এবং আপনার স্বপ্নের ওয়েবসাইট তৈরি করুন</p>
                <a href="{{ url('/contact') }}" class="btn btn-outline-dark btn-lg px-5 py-2 rounded-pill fw-bold" style="border-width: 1.5px;">যোগাযোগ করুন</a>
            </div>
        </div>
    </section>

    <style>
        .portfolio-filter button {
            background: #fff; border: 1px solid #ddd; padding: 10px 25px; margin: 5px;
            border-radius: 50px; font-weight: 600; transition: 0.3s; color: #555; cursor: pointer;
        }
        .portfolio-filter button.active, .portfolio-filter button:hover {
            background: var(--primary-color); color: #fff; border-color: var(--primary-color);
        }
        .portfolio-wrapper { border-radius: 15px; transition: 0.4s; }
        .portfolio-img-box img { height: 280px; object-fit: cover; transition: 0.5s; }
        .portfolio-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(124, 58, 237, 0.9); opacity: 0;
            transition: 0.4s ease-in-out; transform: translateY(20px);
        }
        .portfolio-item:hover .portfolio-overlay { opacity: 1; transform: translateY(0); }
        .portfolio-item:hover img { transform: scale(1.1); }
        .text-orange { color: var(--primary-color) !important; }
        .pagination .page-link {
            color: #333; border-radius: 50%; margin: 0 5px; width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;
        }
        .pagination .active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
        .pagination .page-link:hover { background-color: #eee; color: var(--primary-color); }
    </style>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script>
        $(window).on('load', function() {
            var $grid = $('.portfolio-container').isotope({
                itemSelector: '.portfolio-item',
                layoutMode: 'fitRows',
                percentPosition: true
            });
            $('.portfolio-filter').on('click', 'button', function() {
                $('.portfolio-filter button').removeClass('active');
                $(this).addClass('active');
                $grid.isotope({ filter: $(this).attr('data-filter') });
            });
        });
    </script>
@endsection
