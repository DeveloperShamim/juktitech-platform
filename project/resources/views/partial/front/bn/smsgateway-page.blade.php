<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-orange: var(--primary-color);
        --brand-dark: var(--text-dark);
        --brand-blue: #3b82f6;
        --text-gray: var(--text-muted);
        --bg-light: #f8fafc;
    }

    body { font-family: 'Manrope', sans-serif; background-color: #fff; overflow-x: hidden; }

    /* --- ১. হিরো সেকশন স্টাইল --- */
    .hero-wrapper {
        background: linear-gradient(135deg, #0b1120 0%, #1e293b 100%);
        padding: 160px 0 100px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .hero-blob {
        position: absolute; width: 500px; height: 500px;
        background: var(--brand-orange); filter: blur(200px); opacity: 0.15;
        border-radius: 50%; z-index: 1;
    }
    .blob-1 { top: -100px; right: -100px; }
    .blob-2 { bottom: -100px; left: -100px; background: var(--brand-blue); }

    .hero-content { position: relative; z-index: 5; }
    
    .hero-img-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transform: perspective(1000px) rotateY(-10deg) rotateX(5deg);
        transition: transform 0.5s ease;
    }
    .hero-img-box:hover { transform: perspective(1000px) rotateY(0deg) rotateX(0deg); }

    /* --- ২. স্ট্যাটস কন্টেইনার (Floating) --- */
    .stats-container {
        margin-top: -60px; position: relative; z-index: 10;
        background: white; border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        padding: 40px; border-bottom: 4px solid var(--brand-orange);
    }

    /* --- ৩. ফিচার কার্ড --- */
    .feature-card-pro {
        padding: 40px 30px; border-radius: 20px; background: white;
        border: 1px solid #f1f5f9; transition: all 0.3s;
        height: 100%; position: relative; overflow: hidden;
    }
    .feature-card-pro:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--brand-orange);
    }
    .icon-circle {
        width: 70px; height: 70px; background: var(--primary-bg-lighter);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 28px; color: var(--brand-orange); margin-bottom: 25px;
        transition: 0.3s;
    }
    .feature-card-pro:hover .icon-circle { background: var(--brand-orange); color: white; }

    /* --- ৪. ডেভেলপার সেকশন স্টাইল --- */
    .dev-section { background: #f8fafc; padding: 100px 0; }
    .code-window {
        background: #1e293b; border-radius: 15px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    .code-header {
        background: var(--text-dark); padding: 15px 20px; display: flex; gap: 8px;
    }
    .dot { width: 12px; height: 12px; border-radius: 50%; }
    .code-body { padding: 25px; font-family: 'Courier New', monospace; color: #e2e8f0; font-size: 14px; }

    /* --- ৫. ডাউনলোড সেকশন স্টাইল --- */
    .download-card {
        background: white; border-radius: 16px; padding: 25px;
        border: 1px solid #e2e8f0; transition: 0.3s;
        display: flex; align-items: center; gap: 20px;
    }
    .download-card:hover {
        transform: translateY(-5px);
        border-color: var(--brand-blue);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }
    .dl-icon {
        width: 60px; height: 60px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 30px; flex-shrink: 0;
    }
    .wp-icon { background: #e0f2fe; color: #0284c7; }
    .whmcs-icon { background: #dcfce7; color: #16a34a; }

    /* --- ৬. প্রাইসিং সেকশন --- */
    .pricing-card {
        background: #fff; border-radius: 24px; padding: 50px 30px;
        text-align: center; border: 1px solid #e2e8f0; height: 100%;
        transition: 0.4s; position: relative;
    }
    .pricing-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    
    .pricing-card.popular {
        border: 2px solid var(--brand-orange);
        box-shadow: 0 15px 30px rgba(255, 107, 0, 0.1);
    }
    .badge-popular {
        position: absolute; top: 0; right: 30px; background: var(--brand-orange);
        color: white; padding: 8px 15px; font-size: 12px; font-weight: 800;
        border-radius: 0 0 10px 10px;
    }
    
    .price-box {
        background: var(--primary-bg-light); border-radius: 15px; padding: 25px 10px; margin: 30px 0;
    }
    .price-amount { font-size: 48px; font-weight: 800; color: var(--brand-orange); line-height: 1; }
    .check-list li {
        margin-bottom: 15px; display: flex; align-items: center; color: var(--text-gray); font-weight: 500;
    }
    .check-list li i { color: #16a34a; margin-right: 10px; font-size: 18px; }

    .btn-solid {
        background: var(--brand-orange); color: white; width: 100%;
        padding: 15px; border-radius: 12px; font-weight: 700; border: none;
    }
    .btn-outline {
        background: transparent; border: 2px solid var(--brand-blue); color: var(--brand-blue);
        width: 100%; padding: 15px; border-radius: 12px; font-weight: 700; transition: 0.3s;
    }
    .btn-outline:hover { background: var(--brand-blue); color: white; }

    /* --- ৭. ইউজ কেস --- */
    .use-case-img {
        height: 250px; width: 100%; object-fit: cover; border-radius: 15px;
        transition: 0.5s;
    }
    .use-case-card:hover .use-case-img { transform: scale(1.05); }
    .use-case-card { overflow: hidden; border-radius: 15px; position: relative; }
    .use-case-overlay {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: linear-gradient(to top, black, transparent);
        padding: 30px 20px; color: white;
    }
</style>

<div class="hero-wrapper">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    <div class="container hero-content">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="d-inline-block px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 mb-4">
                    <span class="text-warning me-2"><i class="fas fa-star"></i></span> বাংলাদেশের #১ SMS Gateway
                </div>
                <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">বিশ্বস্ত SMS Gateway <br><span class="hero-accent">SMS Gateway Solutions</span></h1>
                <p class="lead opacity-75 mb-5">bulk SMS, OTP verification, marketing campaign - ১০,০০০+ businesses trust us</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('login') }}" class="btn btn-solid px-5" style="width: auto;">ফ্রি অ্যাকাউন্ট খুলুন</a>
                    <a href="https://www.youtube.com/@official.creativedesign" class="btn btn-outline-light px-4" style="border-radius: 12px; padding: 15px;">
                        <i class="fas fa-play-circle me-2"></i> ভিডিও দেখুন
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-img-box">
                    <img src="{{ asset_image('assets/images/smsgateway.avif') }}" class="img-fluid rounded-3" alt="SMS Dashboard">
                    
                    <div class="position-absolute bg-white text-dark p-3 rounded-3 shadow-lg" style="bottom: 20px; left: -20px; width: 200px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle p-2 text-white"><i class="fas fa-check"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold">SMS পাঠানো হয়েছে</h6>
                                <small class="text-muted">সফলভাবে ডেলিভারি</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="stats-container">
        <div class="row text-center divide-cols">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-0 text-dark">৯৯.৯%</h2>
                <p class="text-muted small">ডেলিভারি রেট</p>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-0 text-dark">১০০০+</h2>
                <p class="text-muted small">সন্তুষ্ট ক্লায়েন্ট</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold mb-0 text-dark">৫০০+</h2>
                <p class="text-muted small">সক্রিয় ব্যবসায়ী</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold mb-0 text-dark">২৪/৭</h2>
                <p class="text-muted small">লাইভ সাপোর্ট</p>
            </div>
        </div>
    </div>
</div>

<section class="py-5 my-5">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">আমাদের ফিচারসমূহ</h6>
            <h2 class="display-5 fw-bold text-dark">কেন আমাদের বেছে নেবেন</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-paper-plane"></i></div>
                    <h4 class="fw-bold mb-3">বাল্ক SMS</h4>
                    <p class="text-muted">একসাথে হাজার হাজার মোবাইলে দ্রুত ও নির্ভরযোগ্য মেসেজ পাঠান এবং আপনার ক্যাম্পেইন বুস্ট করুন</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                    <h4 class="fw-bold mb-3">ব্র্যান্ডেড সেন্ডার ID</h4>
                    <p class="text-muted">আপনার ব্র্যান্ডের নাম (যেমন: 'GP', 'Uber') দিয়ে গ্রাহকদের কাছে প্রফেশনাল মেসেজ পাঠান</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-bolt"></i></div>
                    <h4 class="fw-bold mb-3">ইনস্ট্যান্ট OTP</h4>
                    <p class="text-muted">১-২ সেকেন্ডের মধ্যে OTP পাঠান এবং নিরাপদ লগইন/ভেরিফিকেশন নিশ্চিত করুন</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-code"></i></div>
                    <h4 class="fw-bold mb-3">RESTful API</h4>
                    <p class="text-muted">ডেভেলপারদের জন্য সহজ ইন্টিগ্রেশন এবং যেকোনো প্ল্যাটফর্মে তাৎক্ষণিক SMS পাঠান</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-file-invoice"></i></div>
                    <h4 class="fw-bold mb-3">ডিটেইল রিপোর্ট</h4>
                    <p class="text-muted">সম্পূর্ণ ডেলিভারি রিপোর্ট, ফেইল/সাকসেস লগ এবং বিস্তারিত অ্যানালিটিক্স</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card-pro">
                    <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                    <h4 class="fw-bold mb-3">শিডিউল SMS</h4>
                    <p class="text-muted">বিশেষ দিন বা সময়ে স্বয়ংক্রিয়ভাবে মেসেজ পাঠান এবং মার্কেটিং ক্যাম্পেইন পরিচালনা করুন</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="dev-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-primary fw-bold">DEVELOPER FRIENDLY</span>
                <h2 class="display-6 fw-bold mb-4 text-dark">সহজ ও শক্তিশালী API ইন্টিগ্রেশন</h2>
                <p class="lead text-muted mb-4">আমাদের সহজ ডকুমেন্টেশন অনুসরণ করে দ্রুত ইন্টিগ্রেট করুন। PHP, Laravel, Python ও Node.js - সব প্ল্যাটফর্মে সাপোর্টেড</p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> REST API Key</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Webhook Support</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Live Documentation</li>
                </ul>
                <a href="#" class="btn btn-outline-dark rounded-pill px-4">ডকুমেন্টেশন দেখুন <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-6">
                <div class="code-window">
                    <div class="code-header">
                        <div class="dot bg-danger"></div>
                        <div class="dot bg-warning"></div>
                        <div class="dot bg-success"></div>
                    </div>
                    <div class="code-body">
                        <span style="color:#c678dd">const</span> response = <span style="color:#c678dd">await</span> axios.post(<span style="color:#98c379">'{{ url('/') }}/api/sms/send'</span>, {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;api_key: <span style="color:#98c379">'YOUR_SECRET_KEY'</span>,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;message: <span style="color:#98c379">'Hello Customer, Your OTP is 1234'</span><br>
                        });<br><br>
                        console.log(response.data);
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">INTEGRATIONS</h6>
            <h2 class="display-5 fw-bold text-dark">পপুলার প্লাগইন ডাউনলোড</h2>
            <div style="width: 80px; height: 4px; background: var(--brand-orange); margin: 20px auto;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="download-card">
                    <div class="dl-icon wp-icon">
                        <i class="fab fa-wordpress"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">WordPress Plugin</h4>
                        <p class="text-muted small mb-2">WooCommerce SMS Notification & OTP</p>
                        <a href="https://drive.google.com/file/d/1rUdQoXZWqE4CSpdKopq_NgIrb2AUqD84/view?usp=sharing" class="btn btn-sm btn-solid rounded-pill px-4">
                            <i class="fas fa-download me-1"></i> ডাউনলোড
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-6">
                <div class="download-card">
                    <div class="dl-icon whmcs-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">WHMCS Module</h4>
                        <p class="text-muted small mb-2">Client Notification & Verification</p>
                        <a href="https://drive.google.com/file/d/1wPEdCh-qq8VqEuuOevW47-aOoviOW2HS/view?usp=sharing" class="btn btn-sm btn-outline rounded-pill px-4">
                            <i class="fas fa-download me-1"></i> ডাউনলোড
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5" id="pricing">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold">PRICING PLANS</h6>
            <h2 class="display-5 fw-bold text-dark">সাশ্রয়ী মূল্যে SMS পাঠান</h2>
            <div style="width: 80px; height: 4px; background: var(--brand-orange); margin: 20px auto;"></div>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <div class="pricing-card">
                    <h3 class="fw-bold">স্টার্টার</h3>
                    <div class="price-box">
                        <div class="price-amount"><span class="fs-4">৳</span>০.৫৫</div>
                        <p class="text-muted small mb-0 mt-2">প্রতি SMS</p>
                    </div>
                    <ul class="check-list">
                        <li><i class="fas fa-check-circle"></i> ১০,০০০ SMS প্রতি মাস</li>
                        <li><i class="fas fa-check-circle"></i> ব্র্যান্ডেড সেন্ডার ID</li>
                        <li><i class="fas fa-check-circle"></i> API অ্যাক্সেস</li>
                        <li><i class="fas fa-check-circle"></i> বেসিক সাপোর্ট</li>
                    </ul>
                    <a href="{{ route('user.recharge.index') }}" class="btn btn-outline">অর্ডার করুন</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="pricing-card popular" style="transform: scale(1.05);">
                    <div class="badge-popular">MOST POPULAR</div>
                    <h3 class="fw-bold">বিজনেস</h3>
                    <div class="price-box">
                        <div class="price-amount"><span class="fs-4">৳</span>০.৪৫</div>
                        <p class="text-muted small mb-0 mt-2">প্রতি SMS</p>
                    </div>
                    <ul class="check-list">
                        <li><i class="fas fa-check-circle"></i> ৫০,০০০ SMS প্রতি মাস</li>
                        <li><i class="fas fa-check-circle"></i> ডেডিকেটেড সেন্ডার ID</li>
                        <li><i class="fas fa-check-circle"></i> প্রায়োরিটি সাপোর্ট</li>
                        <li><i class="fas fa-check-circle"></i> ডিটেইল রিপোর্ট</li>
                        <li><i class="fas fa-check-circle"></i> শিডিউল SMS সুবিধা</li>
                    </ul>
                    <a href="{{ route('user.recharge.index') }}" class="btn btn-solid">এখন শুরু</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="pricing-card">
                    <h3 class="fw-bold">এন্টারপ্রাইজ</h3>
                    <div class="price-box">
                        <div class="price-amount"><span class="fs-4">৳</span>০.৩৫</div>
                        <p class="text-muted small mb-0 mt-2">প্রতি SMS</p>
                    </div>
                    <ul class="check-list">
                        <li><i class="fas fa-check-circle"></i> ১,০০,০০০ SMS</li>
                        <li><i class="fas fa-check-circle"></i> কাস্টম সেন্ডার (Brand Name)</li>
                        <li><i class="fas fa-check-circle"></i> ২৪/৭ ডেডিকেটেড সাপোর্ট</li>
                        <li><i class="fas fa-check-circle"></i> ১ জন অ্যাকাউন্ট ম্যানেজার</li>
                    </ul>
                    <a href="{{ route('user.recharge.index') }}" class="btn btn-outline">অর্ডার করুন</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-4">
        <h2 class="text-center fw-bold mb-5">আমাদের SMS Gateway কোথায় ব্যবহার করবেন?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                             <div class="use-case-card">
                    <img src="{{ asset_image('assets/images/ecommerce.webp') }}" class="use-case-img" alt="Education">
                    <div class="use-case-overlay">
                        <h4>ই-কমার্স নোটিফিকেশন</h4>
                        <p class="small mb-0">অর্ডার কনফার্ম ও ডেলিভারি আপডেট</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="use-case-card">
                    <img src="{{ asset_image('assets/images/school.avif') }}" class="use-case-img" alt="Education">
                    <div class="use-case-overlay">
                        <h4>শিক্ষা প্রতিষ্ঠান</h4>
                        <p class="small mb-0">পরীক্ষার রেজাল্ট ও ফি রিমাইন্ডার SMS</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="use-case-card">
                    <img src="{{ asset_image('assets/images/host.avif') }}" class="use-case-img" alt="Health">
                    <div class="use-case-overlay">
                        <h4>হোস্টিং কোম্পানি</h4>
                        <p class="small mb-0">সার্ভার নোটিফিকেশন ও পেমেন্ট রিমাইন্ডার</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: url('{{ asset_image('assets/images/back.avif') }}') center/cover fixed; position: relative;">
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(15, 23, 42, 0.9);"></div>
    <div class="container position-relative text-center py-5 text-white">
        <h2 class="display-5 fw-bold mb-4">আজই SMS Gateway ব্যবহার শুরু করুন</h2>
        <p class="lead opacity-75 mb-5">ফ্রি ট্রায়াল শুরু করুন এবং ২৪/৭ লাইভ সাপোর্টে আমাদের বিশেষজ্ঞ দলের সহায়তা নিন</p>
        <a href="{{ URL::to('/contact') }}" class="btn btn-lg btn-solid px-5 py-3 rounded-pill">যোগাযোগ করুন</a>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">সচরাচর জিজ্ঞাসিত প্রশ্ন</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                বাংলাদেশি ও non-masked SMS পাঠানো যায়?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                হ্যাঁ, আমাদের সিস্টেমে বাংলাদেশি নম্বর ও non-masked SMS পাঠানো যায়। আপনার ব্র্যান্ডের নাম (যেমন: Bkash) দিয়ে গ্রাহকদের কাছে প্রফেশনাল SMS পাঠাতে পারবেন।
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                API ডকুমেন্টেশন কি আছে?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                হ্যাঁ, আমাদের বিস্তারিত API ডকুমেন্টেশন ও লাইভ উদাহরণ সহ ইন্টিগ্রেশন গাইড পাবেন
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
