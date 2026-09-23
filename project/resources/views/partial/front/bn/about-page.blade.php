    @php
        $about_content = \DB::table('about_section_contents')->first();
        $counters = \DB::table('counters')->get();
        $pricings = \DB::table('pricing_plans')->get();
    @endphp

    <section class="about-hero py-5 text-white" style="background: var(--cta-gradient);">
        <div class="container py-5 text-center">
            <h1 class="fw-bold display-4 mb-3">আমাদের <span class="hero-accent">সম্পর্কে</span> জানুন</h1>
            <p class="lead opacity-75">দক্ষ টিম, সময়মত ডেলিভারি ও সাশ্রয়ী মূল্যে সেরা সেবা</p>
        </div>
    </section>

    <section class="py-5 bg-white overflow-hidden border-bottom">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-img-wrapper position-relative">
                        @if($about_content && $about_content->image)
                            <img src="{{ asset_image('assets/images/about/'.$about_content->image) }}" class="img-fluid rounded-4 shadow-lg main-about-img" alt="About Image">
                        @endif
                        @php $exp = $counters->where('id', 4)->first(); @endphp
                        @if($exp)
                        <div class="experience-badge bg-white p-3 shadow-lg rounded-3 position-absolute">
                            <h3 class="fw-bold mb-0 text-primary">{{ $exp->count_value }}</h3>
                            <p class="small mb-0 text-dark">{{ $exp->title }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <h6 class="text-primary fw-bold text-uppercase mb-3">{{ $gs->title ?? 'Jukti Tech' }} —</h6>
                    <h2 class="fw-bold mb-4 display-6" style="color: #1a1a1a;">{{ $about_content->title ?? 'আমাদের সম্পর্কে বিস্তারিত' }}</h2>
                    <p class="text-muted fs-5 mb-4 text-justify">{{ $about_content->subtitle }}</p>
                    <div class="row g-4 mt-2">
                        @foreach($counters as $counter)
                        <div class="col-6">
                            <div class="counter-item d-flex align-items-center p-3 border rounded-3 bg-light">
                                <div class="counter-text">
                                    <h4 class="fw-bold mb-0" style="color: var(--primary-color);">{{ $counter->count_value }}</h4>
                                    <p class="small text-muted mb-0">{{ $counter->title }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase">প্যাকেজ</h6>
                <h2 class="fw-bold">আমাদের জনপ্রিয় প্যাকেজসমূহ</h2>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach($pricings as $pricing)
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card h-100 p-4 border text-center rounded-4 shadow-sm bg-white {{ $pricing->is_featured ? 'featured-pricing' : '' }}">
                        <h4 class="fw-bold">{{ $pricing->title }}</h4>
                        <h2 class="display-5 fw-bold text-primary">৳{{ $pricing->price }}</h2>
                        <p class="text-muted small">{{ $pricing->duration }}</p>
                        <hr>
                        <ul class="list-unstyled text-start mb-4" style="min-height: 180px;">
                            @foreach(explode(',', $pricing->features) as $feature)
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> {{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ $pricing->order_link }}" target="_blank" class="btn {{ $pricing->is_featured ? 'btn-primary' : 'btn-outline-primary' }} w-100 rounded-pill fw-bold">অর্ডার করুন</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">
            <div class="custom-package-card p-5 shadow-sm rounded-4 border mx-auto bg-white" style="max-width: 850px; border-radius: 20px;">
                <h2 class="fw-bold mb-3">কাস্টম প্যাকেজ প্রয়োজন?</h2>
                <p class="text-muted mb-4 fs-5">আপনার প্রজেক্টের জন্য বিশেষভাবে তৈরি প্যাকেজ চাইলে আমাদের সাথে যোগাযোগ করুন</p>
                <a href="{{ url('/contact') }}" class="btn btn-outline-dark btn-lg px-5 py-2 rounded-pill fw-bold" style="border-width: 1.5px;">যোগাযোগ করুন</a>
            </div>
        </div>
    </section>

    <style>
        .main-about-img { border-bottom: 10px solid var(--primary-color); }
        .experience-badge { bottom: 25px; right: -15px; border-left: 5px solid var(--primary-color); min-width: 150px; z-index: 2; }
        .text-primary { color: var(--primary-color) !important; }
        .btn-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
        .btn-outline-primary { color: var(--primary-color) !important; border-color: var(--primary-color) !important; transition: 0.3s; }
        .btn-outline-primary:hover { background-color: var(--primary-color) !important; color: #fff !important; }
        .featured-pricing { border: 2.5px solid var(--primary-color) !important; transform: scale(1.05); z-index: 1; }
        .text-justify { text-align: justify; }
    </style>
