<!doctype html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin OTP | {{ $gs->title ?? config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset_image('assets/images/'.$gs->favicon) }}" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset_image('assets/frontend/login/assets/css/style.css') }}">
</head>
<body id="top">
    <div class="login-7">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="form-section mt-5">
                        <div class="logo text-center mb-3">
                            <img src="{{ asset_image('assets/images/logo/'.$gs->logo) }}" alt="logo" style="max-height:48px;">
                        </div>
                        <h3 class="text-center">ইমেইলে পাঠানো OTP দিন</h3>
                        <p class="text-muted small text-center mb-4">
                            <strong>{{ Auth::guard('admin')->user()->email }}</strong> ঠিকানায় ৬ সংখ্যার কোড পাঠানো হয়েছে। কোড দিয়ে ভেরিফাই না করা পর্যন্ত প্যানেল খুলবে না।
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success small">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.otp.verify') }}" class="mt-3">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="otp" class="form-label small">OTP (৬ ডিজিট)</label>
                                <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="form-control form-control-lg text-center" placeholder="------" value="{{ old('otp') }}" required autocomplete="one-time-code">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">ভেরিফাই করে প্যানেলে যান</button>
                        </form>

                        <form method="POST" action="{{ route('admin.otp.resend') }}" class="text-center">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm">OTP আবার পাঠান</button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            <a href="{{ route('admin.logout') }}">লগআউট</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset_image('assets/frontend/login/assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
