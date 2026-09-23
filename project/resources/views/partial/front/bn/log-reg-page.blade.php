<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    .modern-login-section {
        background-color: #f1f5f9;
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 15px;
        font-family: 'Poppins', sans-serif;
    }

    .login-box-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        width: 100%;
        max-width: 950px;
        display: flex;
        flex-wrap: wrap;
    }

    .login-image-side {
        width: 50%;
        background: radial-gradient(circle at 10% 20%, rgba(255, 87, 34, 0.25), transparent 45%),
                    radial-gradient(circle at 90% 80%, rgba(59, 130, 246, 0.2), transparent 45%),
                    #0f172a;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 45px 40px;
        color: #fff;
    }

    .login-image-side h2, .login-image-side p {
        position: relative; z-index: 2;
    }

    .login-feature-list {
        list-style: none;
        padding: 0;
        margin: 25px 0 0;
        position: relative;
        z-index: 2;
    }
    .login-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        margin-bottom: 12px;
        font-size: 13.5px;
        color: #e2e8f0;
    }
    .login-feature-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(255, 87, 34, 0.15);
        color: #ff7043;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .login-form-side {
        width: 50%;
        padding: 50px 40px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .modern-input-group { margin-bottom: 20px; position: relative; }
    .modern-input-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }

    .modern-input {
        width: 100%; height: 50px; padding: 10px 20px;
        border: 2px solid #eee; border-radius: 10px;
        font-size: 15px; transition: 0.3s; background: #fdfdfd;
    }
    .modern-input:focus {
        border-color: #ff5722;
        background: #fff; outline: none;
    }

    .btn-modern {
        width: 100%; height: 50px;
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
        border: none; border-radius: 10px;
        color: #fff; font-weight: 700; font-size: 16px;
        cursor: pointer; transition: 0.3s;
        box-shadow: 0 4px 15px rgba(255, 87, 34, 0.3);
    }
    .btn-modern:hover { 
        background: linear-gradient(135deg, #f4511e 0%, #d84315 100%); 
        transform: translateY(-2px); 
        box-shadow: 0 6px 20px rgba(255, 87, 34, 0.4);
    }

    .forgot-link {
        text-align: right; display: block; margin-top: -10px; margin-bottom: 20px;
        font-size: 13px; color: #666; text-decoration: none;
    }
    .register-area {
        text-align: center; margin-top: 20px; padding-top: 20px;
        border-top: 1px dashed #ddd;
    }
    .register-trigger {
        color: #ff5722; font-weight: 700; text-decoration: none; cursor: pointer;
    }

    @media (max-width: 768px) {
        .login-image-side { display: none; }
        .login-form-side { width: 100%; padding: 40px 20px; }
    }
</style>

<section class="modern-login-section">
    <div class="login-box-wrapper">
        <div class="login-image-side">
            <div>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-10 mb-4">
                    <span style="color:#ff7043;">⚡</span>
                    <span class="small fw-semibold text-white">ক্লায়েন্ট পোর্টাল ও ডাউনলোড সেন্টার</span>
                </div>
                <h2 class="fw-bold fs-3 mb-2 text-white">স্বাগতম!</h2>
                <p class="mb-0 text-white-50 small">আপনার অ্যাকাউন্টে লগইন করে ওয়েবসাইট ও থিম ফাইল ডাউনলোড করুন।</p>

                <ul class="login-feature-list">
                    <li class="login-feature-item">
                        <span class="login-feature-icon"><i class="fas fa-cloud-arrow-down"></i></span>
                        <span>রেডি ওয়েবসাইট ও থিম ফাইল ইনস্ট্যান্ট ডাউনলোড</span>
                    </li>
                    <li class="login-feature-item">
                        <span class="login-feature-icon"><i class="fas fa-key"></i></span>
                        <span>লাইসেন্স কি ও ইনস্টলেশন ডোমেন ম্যানেজমেন্ট</span>
                    </li>
                    <li class="login-feature-item">
                        <span class="login-feature-icon"><i class="fas fa-headset"></i></span>
                        <span>২৪/৭ প্রিমিয়াম টেকনিক্যাল সাপোর্ট ও লাইভ চ্যাট</span>
                    </li>
                </ul>
            </div>
            
            <div class="pt-4 border-top border-white border-opacity-10 small text-white-50">
                <i class="fas fa-shield-alt text-success me-1"></i> ১০০% নিরাপদ ও এনক্রিপ্টেড ক্লায়েন্ট অ্যাকাউন্ট
            </div>
        </div>

        <div class="login-form-side">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">লগইন করুন</h3>
                <p class="text-muted small mb-0">আপনার ইমেইল বা ফোন নম্বর দিয়ে প্রবেশ করুন</p>
            </div>

            @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show small mb-4" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show small mb-4" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger small mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('front.login') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to', old('redirect_to')) }}">

                <div class="modern-input-group">
                    <label>ইমেইল বা মোবাইল নম্বর</label>
                    <input type="text" name="email" class="modern-input" placeholder="017xxxxxxxx বা example@gmail.com" value="{{ old('email') }}" required>
                </div>

                <div class="modern-input-group">
                    <label>পাসওয়ার্ড</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="loginPass" class="modern-input" placeholder="********" required>
                        <span onclick="togglePass('loginPass')" style="position: absolute; right: 15px; top: 15px; cursor: pointer; color: #999;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="small text-muted mb-0 d-flex align-items-center gap-1" style="cursor: pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> মনে রাখুন
                    </label>
                    <a href="{{ route('user.forgot.password') }}" class="forgot-link mb-0">পাসওয়ার্ড ভুলে গেছেন?</a>
                </div>

                <button type="submit" class="btn-modern">
                    <i class="fas fa-sign-in-alt me-1"></i> লগইন করুন
                </button>
            </form>

            <div class="register-area">
                <p class="text-muted small mb-1">নতুন গ্রাহক? এখনো অ্যাকাউন্ট নেই?</p>
                <a class="register-trigger" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fas fa-user-plus me-1"></i> নতুন অ্যাকাউন্ট রেজিস্ট্রেশন করুন
                </a>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="registerModalLabel">নতুন অ্যাকাউন্ট তৈরি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('front.register') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to', old('redirect_to')) }}">
                    <div class="modern-input-group mb-3">
                        <label class="small">আপনার নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="modern-input" placeholder="পূর্ণ নাম" required>
                    </div>
                    <div class="modern-input-group mb-3">
                        <label class="small">মোবাইল নম্বর <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="modern-input" placeholder="017xxxxxxxx" required>
                    </div>
                    <div class="modern-input-group mb-3">
                        <label class="small">ইমেইল <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="modern-input" placeholder="example@gmail.com" required>
                    </div>
                    <div class="modern-input-group mb-3">
                        <label class="small">পাসওয়ার্ড <span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="regPass" class="modern-input" placeholder="********" required>
                            <span onclick="togglePass('regPass')" style="position: absolute; right: 15px; top: 15px; cursor: pointer; color: #999;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="modern-input-group mb-4">
                        <label class="small">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="modern-input" placeholder="********" required>
                    </div>
                    <button type="submit" class="btn-modern" style="background: var(--primary-color);">রেজিস্ট্রেশন সম্পন্ন করুন</button>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <span class="small text-muted">ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="#" data-bs-dismiss="modal" class="text-primary fw-bold text-decoration-none">লগইন করুন</a></span>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePass(id) {
        var x = document.getElementById(id);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
