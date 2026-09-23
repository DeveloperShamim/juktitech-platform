@extends('layouts.admin')

@section('styles')
@include('admin.payments.partials.payment-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-qrcode',
        'title' => __('CreativePay App — QR Login'),
        'subtitle' => __('মোবাইল অ্যাপে লগইনের জন্য one-time QR কোড জেনারেট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Payments'), 'url' => route('admin.payments.index')],
            ['label' => __('QR Login'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.payments.partials.payment-nav')

    <div class="pay-qr-layout">
        <div class="pay-card">
            <div class="pay-card__head">
                <h3><i class="fas fa-qrcode"></i> {{ __('Generate QR Code') }}</h3>
            </div>
            <div class="pay-card__body" style="padding:20px 22px 22px;">
                <p class="text-muted small mb-3">
                    <strong>Creative Pay</strong> অ্যাপ → <strong>QR Login</strong> → নিচের QR স্ক্যান করুন।
                    QR <strong>৩ মিনিট</strong>ের জন্য valid এবং শুধু একবার কাজ করবে।
                </p>

                <button type="button" id="btnGenerateQr" class="pay-qr-generate mb-3">
                    <i class="fas fa-sync-alt"></i> {{ __('Generate New QR Code') }}
                </button>

                <div id="qrAlert" class="alert alert-danger d-none"></div>
                <div id="qrSuccess" class="alert alert-success d-none"></div>

                <div id="qrBox" class="pay-qr-box d-none">
                    <div id="qrCanvas" class="pay-qr-canvas"></div>
                    <p class="mb-1"><strong>{{ __('Expires in') }}:</strong> <span class="pay-qr-timer" id="qrCountdown">03:00</span></p>
                    <p class="text-muted small mb-0" id="qrExpiresAt"></p>
                </div>
            </div>
        </div>

        <div class="pay-card">
            <div class="pay-card__head">
                <h3><i class="fas fa-info-circle"></i> {{ __('How it works') }}</h3>
            </div>
            <div class="pay-card__body" style="padding:18px 20px 20px;">
                <ol class="pay-steps">
                    <li>
                        <span class="pay-steps__num">1</span>
                        <span><strong>Generate New QR Code</strong> বাটনে ক্লিক করুন</span>
                    </li>
                    <li>
                        <span class="pay-steps__num">2</span>
                        <span>ফোনে Creative Pay অ্যাপ খুলুন</span>
                    </li>
                    <li>
                        <span class="pay-steps__num">3</span>
                        <span>Login স্ক্রিনে <strong>QR Login</strong> ট্যাপ করুন</span>
                    </li>
                    <li>
                        <span class="pay-steps__num">4</span>
                        <span>QR স্ক্যান করলেই instant লগইন হবে — SMS পেমেন্ট শুনতে পারবে</span>
                    </li>
                </ol>

                <div class="pay-warn">
                    <i class="fas fa-shield-alt"></i>
                    <span>শুধু <strong>Super Admin</strong> QR জেনারেট করতে পারবেন। QR স্ক্রিনশট কাউকে দেবেন না।</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function () {
    var btn = document.getElementById('btnGenerateQr');
    var qrBox = document.getElementById('qrBox');
    var qrCanvas = document.getElementById('qrCanvas');
    var qrAlert = document.getElementById('qrAlert');
    var qrSuccess = document.getElementById('qrSuccess');
    var qrCountdown = document.getElementById('qrCountdown');
    var qrExpiresAt = document.getElementById('qrExpiresAt');
    var timer = null;
    var btnDefaultHtml = btn.innerHTML;

    function showError(msg) {
        qrAlert.textContent = msg;
        qrAlert.classList.remove('d-none');
        qrSuccess.classList.add('d-none');
    }

    function showSuccess(msg) {
        qrSuccess.textContent = msg;
        qrSuccess.classList.remove('d-none');
        qrAlert.classList.add('d-none');
    }

    function startCountdown(seconds) {
        if (timer) clearInterval(timer);
        var left = seconds;
        function tick() {
            var m = Math.floor(left / 60);
            var s = left % 60;
            qrCountdown.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            if (left <= 0) {
                clearInterval(timer);
                showError('QR expired. Generate a new one.');
                qrBox.classList.add('d-none');
            }
            left--;
        }
        tick();
        timer = setInterval(tick, 1000);
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Generating...") }}';
        qrAlert.classList.add('d-none');

        fetch('{{ route('admin.payments.app-qr.generate') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: '{}'
        })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = btnDefaultHtml;
            if (!res.ok || !res.json.ok) {
                showError(res.json.message || 'Could not generate QR.');
                return;
            }
            var qr = res.json.qr;
            qrCanvas.innerHTML = '';
            new QRCode(qrCanvas, {
                text: qr.qr_payload,
                width: 240,
                height: 240,
                colorDark: '#047857',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
            qrBox.classList.remove('d-none');
            qrExpiresAt.textContent = 'Valid until: ' + qr.expires_at;
            showSuccess(res.json.message);
            startCountdown(qr.expires_in || 180);
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = btnDefaultHtml;
            showError('Network error. Try again.');
        });
    });
})();
</script>
@endsection
