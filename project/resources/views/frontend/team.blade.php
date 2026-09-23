@extends('layouts.front')

@section('meta')
    {{-- ১. সাধারণ এসইও ট্যাগ --}}
    <title>{{ __('Our Team') }} - {{ $gs->title }}</title>
    <meta name="description" content="যুক্তি টেকের অভিজ্ঞ ও দক্ষ টিম মেম্বারদের সাথে পরিচিত হন।">
    <meta name="keywords" content="our team,team,আমাদের টিম,Jukti Tech,juktitech">
    <meta name="author" content="{{ __('Our Team') }} - {{ $gs->title }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ __('Our Team') }} - {{ $gs->title }}" />
    <meta property="og:description" content="যুক্তি টেকের অভিজ্ঞ ও দক্ষ টিম মেম্বারদের সাথে পরিচিত হন।" />
    <meta property="og:image" content="{{ asset_image('assets/images/logo/team.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Our Team') }} - {{ $gs->title }}">
    <meta name="twitter:description" content="যুক্তি টেকের অভিজ্ঞ ও দক্ষ টিম মেম্বারদের সাথে পরিচিত হন।">
    <meta name="twitter:image" content="{{ asset_image('assets/images/logo/team.png') }}">
@endsection

@section('contents')

    <section class="team-hero py-5 text-white" style="background: var(--cta-gradient);">
        <div class="container py-5 text-center">
            <h1 class="fw-bold display-4 mb-3">আমাদের <span class="hero-accent">টিম</span> দেখুন</h1>
            <p class="lead opacity-75">অভিজ্ঞ ডিজাইনার, ডেভেলপার ও মার্কেটিং বিশেষজ্ঞ — যারা আপনার প্রজেক্টকে সফল করতে প্রতিশ্রুতিবদ্ধ</p>
        </div>
    </section>

    <section class="py-5 bg-light position-relative" style="margin-top: -50px;">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @forelse($teams as $team)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card shadow-lg border-0 text-center">
                        <div class="image-box position-relative overflow-hidden">
                            <img src="{{ asset_image('assets/images/team/'.$team->photo) }}" class="img-fluid" alt="{{ $team->name }}">
                            <div class="social-overlay d-flex align-items-center justify-content-center">
                                @if($team->facebook)
                                    <a href="{{ $team->facebook }}" target="_blank" class="mx-2"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if($team->twitter)
                                    <a href="{{ $team->twitter }}" target="_blank" class="mx-2"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if($team->linkedin)
                                    <a href="{{ $team->linkedin }}" target="_blank" class="mx-2"><i class="fab fa-linkedin-in"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="info-box p-4 bg-white">
                            <h5 class="fw-bold mb-1 text-dark">{{ $team->name }}</h5>
                            <p class="small text-uppercase fw-bold" style="color: var(--primary-color);">{{ $team->designation }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">বর্তমানে কোনো টিম মেম্বার যুক্ত করা নেই।</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4 text-center">
            <div class="p-5 shadow-sm rounded-4 border mx-auto" style="max-width: 900px; border-radius: 20px;">
                <h2 class="fw-bold mb-3" style="color: #222;">কাস্টম প্যাকেজ প্রয়োজন?</h2>
                <p class="text-muted mb-4 fs-5">আপনার প্রজেক্টের জন্য বিশেষভাবে তৈরি প্যাকেজ চাইলে আমাদের সাথে যোগাযোগ করুন</p>
                <a href="{{ url('/contact') }}" class="btn btn-outline-dark btn-lg px-5 py-2 rounded-pill fw-bold" style="border-width: 1.5px;">যোগাযোগ করুন</a>
            </div>
        </div>
    </section>

    <style>
        .team-card { border-radius: 20px; overflow: hidden; transition: 0.4s; background: #fff; }
        .image-box { height: 320px; background: #f8f9fa; }
        .image-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .team-card:hover .image-box img { transform: scale(1.1); }
        .social-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(124, 58, 237, 0.85); opacity: 0; transition: 0.4s;
        }
        .team-card:hover .social-overlay { opacity: 1; }
        .social-overlay a {
            width: 40px; height: 40px; background: #fff; color: var(--primary-color);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: 0.3s; font-size: 18px;
        }
        .social-overlay a:hover { background: #333; color: #fff; transform: translateY(-5px); }
        .team-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }
        .info-box { border-top: 1px solid #f1f1f1; }
    </style>

@endsection
