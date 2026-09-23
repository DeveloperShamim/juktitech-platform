@extends('layouts.front')

@section('meta')
    <title>{{ __('Contact With Us') }} - {{ $gs->title }}</title>
    <meta property="og:title" content="{{ __('Contact With Us') }}" />
@endsection

@section('contents')

    <section class="contact-hero py-5 position-relative overflow-hidden" style="background: var(--cta-gradient); min-height: 250px;">
        <div class="container py-5 text-center position-relative" style="z-index: 2;">
            <h1 class="fw-bold display-4 text-white mb-3">আমাদের সাথে <span class="hero-accent">যোগাযোগ</span> করুন</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}" class="text-white opacity-75 text-decoration-none">হোম</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">কন্টাক্ট</li>
                </ol>
            </nav>
        </div>
        <div class="position-absolute" style="width: 300px; height: 300px; background: rgba(124, 58, 237, 0.1); border-radius: 50%; top: -100px; right: -50px;"></div>
    </section>

    <section class="py-5 bg-white position-relative" style="margin-top: -50px;">
        <div class="container">
            <div class="row g-4">
                
                <div class="col-lg-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="info-card p-4 shadow-sm border-0 d-flex align-items-center">
                                <div class="info-icon me-3 shadow-sm">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">আমাদের অফিস</h6>
                                    <p class="small text-muted mb-0">{{ $gs->address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card p-4 shadow-sm border-0 d-flex align-items-center">
                                <div class="info-icon me-3 shadow-sm" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                                    <i class="fas fa-phone-volume"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">সরাসরি কথা বলুন</h6>
                                    <p class="small text-muted mb-0">{{ $gs->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card p-4 shadow-sm border-0 d-flex align-items-center">
                                <div class="info-icon me-3 shadow-sm" style="background: rgba(124, 58, 237, 0.1); color: var(--primary-color);">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">ইমেইল করুন</h6>
                                    <p class="small text-muted mb-0">{{ $gs->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 25px; background: #fff;">
                        <div class="mb-4">
                            <h3 class="fw-bold">আপনার কোন প্রশ্ন আছে?</h3>
                            <p class="text-muted">নিচের ফর্মটি পূরণ করুন, আমাদের সাপোর্ট টিম ২৪ ঘন্টার মধ্যে আপনার সাথে যোগাযোগ করবে।</p>
                        </div>

                        @if(Session::has('success'))
                            <div class="alert alert-success border-0 shadow-sm py-3 mb-4" style="background: #28a745; color: #fff; border-radius: 12px;">
                                <i class="fas fa-check-circle me-2 font-weight-bold"></i> {{ Session::get('success') }}
                            </div>
                        @endif

                        <form action="{{ route('frontend.contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name" class="form-control custom-input" id="name" placeholder="Name" required>
                                        <label for="name">আপনার নাম</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control custom-input" id="email" placeholder="Email" required>
                                        <label for="email">ইমেইল ঠিকানা</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="subject" class="form-control custom-input" id="subject" placeholder="Subject">
                                        <label for="subject">বিষয় (Subject)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-4">
                                        <textarea name="message" class="form-control custom-input" placeholder="Message" id="message" style="height: 150px" required></textarea>
                                        <label for="message">আপনার বিস্তারিত বার্তা লিখুন</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-lg orange-submit-btn px-5 py-3 shadow">
                                        মেসেজ পাঠান <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="container my-5 pb-5">
        <div class="rounded-4 overflow-hidden shadow-sm border">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57414.625054172706!2d89.40859319049999!3d25.921625650381614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39e2d915b0c3148d%3A0xfdd5019e20200f4!2sLalmonirhat!5e0!3m2!1sen!2sbd!4v1766334661797!5m2!1sen!2sbd" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    <style>
        .info-card {
            background: #fff;
            border-radius: 20px;
            transition: 0.4s;
            cursor: pointer;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        .info-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: rgba(124, 58, 237, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .custom-input {
            border: 1px solid #eee;
            background: #fafafa;
            border-radius: 12px !important;
        }
        .custom-input:focus {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px var(--accent-shadow-xs);
        }
        .orange-submit-btn {
            background: var(--primary-color);
            color: #fff;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .orange-submit-btn:hover {
            background: var(--primary-hover);
            color: #fff;
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.2);
        }
        .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.5); }
    </style>

@endsection