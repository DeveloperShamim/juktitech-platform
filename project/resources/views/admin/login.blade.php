<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="EliteDesign">

    <title>Login Admin Panel | {{ $gs->title }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset_image('assets/images/'.$gs->favicon) }}" type="image/x-icon">

    <!-- External CSS -->
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/fonts/flaticon/font/flaticon.css') }}">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Template main CSS -->
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/css/style.css') }}">
    @if(\App\Support\DemoMode::enabled())
    <style>
        .demo-account-box {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
        }
        .demo-account-box__label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }
        .demo-account-box__row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .demo-account-box__field {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            color: #9ca3af;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .demo-account-box__field--email {
            flex: 1;
            min-width: 0;
        }
        .demo-account-box__field--pass {
            flex: 0 0 88px;
            text-align: center;
        }
        .demo-account-box__use {
            flex-shrink: 0;
            background: #fff;
            border: 1px solid #d97706;
            color: #d97706;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .demo-account-box__use:hover {
            background: #fff7ed;
            color: #b45309;
            border-color: #b45309;
        }
        @media (max-width: 480px) {
            .demo-account-box__field { flex: 1 1 100%; }
            .demo-account-box__use { width: 100%; }
        }
    </style>
    @endif
</head>

<body id="top">
    <div class="page_loader"></div>

    <!-- Login start -->
    <div class="login-7">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-section">
                        <div class="logo text-center mb-3">
                            <a href="#">
                                <img src="{{ asset_image('assets/images/logo/'.$gs->logo) }}" alt="logo">
                            </a>
                        </div>
                        <h3>আপনাকে {{ $gs->title }} এর এডমিন প্যানেলে স্বাগতম!</h3>

                        <div class="login-inner-form mt-4">
                            {{-- এখানে এরর / সাকসেস মেসেজ দেখানোর জন্য --}}
                            @include('includes.admin.form-login')

                            <form id="loginform" action="{{ route('admin.login') }}" method="POST">
                                @csrf
                                <div class="form-group clearfix">
                                    <div class="form-box">
                                        <input name="email" id="loginEmail" type="email" class="form-control" placeholder="{{ __('Type Email Address') }}" value="{{ old('email') }}" required>
                                        <i class="flaticon-mail-2"></i>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <div class="form-box">
                                        <input name="password" id="loginPassword" type="password" class="form-control" placeholder="{{ __('Type Password') }}" required>
                                        <i class="flaticon-password"></i>
                                    </div>
                                </div>

                                <div class="checkbox form-group clearfix">
                                    <div class="form-check float-start">
                                        <input type="checkbox" name="remember" id="rp" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rp">{{ __('Remember Password') }}</label>
                                    </div>
                                    <a href="{{ route('admin.forgot') }}" class="link-light float-end forgot-password">{{ __('Forgot Password?') }}</a>
                                </div>

                                <div class="form-group clearfix">
                                    <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                    <button type="submit" class="btn btn-primary btn-lg btn-theme w-100">{{ __('Login') }}</button>
                                </div>

                                @if(\App\Support\DemoMode::enabled())
                                <div class="demo-account-box">
                                    <div class="demo-account-box__label">ডেমো একাউন্ট</div>
                                    <div class="demo-account-box__row">
                                        <div class="demo-account-box__field demo-account-box__field--email" id="demoEmailDisplay" title="{{ \App\Support\DemoMode::adminEmail() }}">{{ \App\Support\DemoMode::adminEmail() }}</div>
                                        <div class="demo-account-box__field demo-account-box__field--pass" id="demoPasswordDisplay">{{ \App\Support\DemoMode::adminPassword() }}</div>
                                        <button type="button" class="demo-account-box__use" id="demoUseBtn">Use</button>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div> <!-- /.login-inner-form -->
                    </div> <!-- /.form-section -->
                </div>
            </div>
        </div>
    </div>
    <!-- Login end -->

    <!-- JS Files -->
    <script src="{{ asset_image('assets/frontend/login/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset_image('assets/frontend/login/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- এখানে আগের AJAX হ্যান্ডলার যুক্ত -->
    <script src="{{asset_image('assets/admin/js/custom.js')}}"></script>
    <script src="{{asset_image('assets/admin/js/myscript.js')}}"></script>

    <!-- যদি এগুলো না থাকে, নিচের fallback AJAX কোড ব্যবহার হবে -->
    @if(\App\Support\DemoMode::enabled())
    <script>
        document.getElementById('demoUseBtn').addEventListener('click', function () {
            document.getElementById('loginEmail').value = @json(\App\Support\DemoMode::adminEmail());
            document.getElementById('loginPassword').value = @json(\App\Support\DemoMode::adminPassword());
            document.getElementById('loginEmail').focus();
        });
    </script>
    @endif
    <script>
        if (typeof $.ajax === 'function' && !window.loginHandlerLoaded) {
            window.loginHandlerLoaded = true;
            $('#loginform').on('submit', function(e) {
                e.preventDefault();
                let $form = $(this);
                let url = $form.attr('action');
                let data = $form.serialize();
                $.ajax({
                    method: 'POST',
                    url: url,
                    data: data,
                    success: function(resp) {
                        if (resp.redirect_url) {
                            window.location.href = resp.redirect_url;
                        } else {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            alert('Session expired (419). Page reload হচ্ছে — আবার login করুন।');
                            location.reload();
                            return;
                        }
                        let res = xhr.responseJSON;
                        if (res && res.errors) {
                            alert(Array.isArray(res.errors) ? res.errors.join("\n") : JSON.stringify(res.errors));
                        } else {
                            alert('Login failed. Please check credentials.');
                        }
                    }
                });
            });
        }
    </script>
</body>
</html>
