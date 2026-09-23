<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gs->title ?? 'CreativePay' }} — Payment</title>
    @if(!empty($gs->favicon))
        <link rel="shortcut icon" href="{{ asset_image('assets/images/' . $gs->favicon) }}" type="image/x-icon">
        <link rel="icon" href="{{ asset_image('assets/images/' . $gs->favicon) }}" type="image/x-icon">
    @elseif(!empty($gs->logo))
        <link rel="shortcut icon" href="{{ asset_image('assets/images/logo/' . $gs->logo) }}" type="image/png">
        <link rel="icon" href="{{ asset_image('assets/images/logo/' . $gs->logo) }}" type="image/png">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand: #D12053;
            --brand-dark: #b01040;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            background-color: #e4eaf3;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c5d4e8' fill-opacity='0.45'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 12px;
        }

        .modal {
            width: 100%;
            max-width: 680px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            overflow: hidden;
        }

        /* ── Top nav ── */
        .nav-box {
            margin: 24px 28px 0;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
        }
        .nav-box a, .nav-box button {
            background: none; border: none; cursor: pointer;
            color: #555; font-size: 20px; text-decoration: none;
            display: inline-flex; align-items: center; padding: 2px;
        }
        .nav-box a:hover, .nav-box button:hover { color: #222; }
        .nav-right { display: flex; gap: 14px; align-items: center; }

        /* ── Merchant row ── */
        .merchant-row {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 28px 28px 16px;
        }
        .merchant-avatar-wrap {
            width: 72px; height: 72px; border-radius: 50%;
            background: #f5f8fc; border: 1px solid #e8e8e8;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            padding: 12px;
        }
        .merchant-avatar {
            width: 100%; height: 100%;
            object-fit: contain; display: block;
        }
        .merchant-avatar-ph {
            width: 72px; height: 72px; border-radius: 50%;
            background: linear-gradient(135deg, #5b9bd5, #2e75b6);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 28px; font-weight: 700; flex-shrink: 0;
        }
        .merchant-info { flex: 1; min-width: 0; }
        .merchant-title {
            font-size: 21px; font-weight: 700; color: #2c3e50;
            margin-bottom: 12px; line-height: 1.3;
        }
        .action-btns { display: flex; gap: 10px; flex-wrap: wrap; }
        .action-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border: 1px solid #d0d0d0;
            border-radius: 8px; background: #fff;
            font-size: 13px; font-weight: 500; color: #2563a8;
            cursor: pointer; white-space: nowrap;
        }
        .action-btn:hover { background: #f7f9fc; }
        .action-btn i { font-size: 14px; }

        .info-panel {
            display: none; margin: 0 28px 14px; padding: 14px 16px;
            background: #f7f9fc; border: 1px solid #e8e8e8; border-radius: 8px;
            font-size: 14px; color: #555; line-height: 1.65;
        }
        .info-panel.show { display: block; }

        /* ── MOBILE BANKING bar ── */
        .banking-bar {
            margin: 10px 28px 0;
            background: #1a4480;
            color: #fff;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 14px 12px;
            border-radius: 8px;
            text-transform: uppercase;
        }

        /* ── Payment grid ── */
        .pay-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            padding: 20px 28px 24px;
        }
        .pay-cell {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            background: #fff;
            padding: 8px 10px;
            cursor: pointer;
            transition: box-shadow .15s, border-color .15s;
        }
        .pay-cell:hover { border-color: #b0c4de; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .pay-cell.selected {
            border-color: #2563a8;
            box-shadow: 0 0 0 2px rgba(37,99,168,.25);
        }
        .pay-cell input { display: none; }
        .pay-logo-wrap {
            background: transparent;
            border-radius: 6px;
            padding: 4px 6px;
            min-height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pay-logo-wrap img {
            width: 100%;
            max-height: 64px;
            object-fit: contain;
            display: block;
        }

        /* ── Pay button ── */
        .pay-btn-wrap { padding: 6px 28px 28px; }
        .btn-pay {
            width: 100%; border: none; border-radius: 10px;
            padding: 18px; font-size: 17px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            background: #d6e8fc; color: #1a4480;
            transition: background .15s;
        }
        .btn-pay:not(:disabled):hover { background: #b8d4f8; }
        .btn-pay:disabled { opacity: .65; cursor: not-allowed; }

        /* ── Step 2 — payment verify form ── */
        .step2 { display: none; padding-bottom: 24px; }
        .step2.active { display: block; }
        .step1.hidden { display: none; }

        .brand-logo-wrap {
            text-align: center;
            padding: 24px 28px 16px;
        }
        .brand-logo-wrap img {
            max-height: 56px;
            max-width: 200px;
            object-fit: contain;
        }

        .meta-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            padding: 0 28px 14px;
            align-items: stretch;
        }
        .meta-merchant {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .meta-merchant .m-av-wrap {
            width: 44px; height: 44px; border-radius: 50%;
            background: #f0f4f8; border: 1px solid #e8e8e8;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            padding: 6px;
        }
        .meta-merchant .m-av {
            width: 100%; height: 100%;
            object-fit: contain; display: block;
        }
        .meta-merchant .m-av-ph {
            width: 44px; height: 44px; border-radius: 50%;
            background: #5b9bd5; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; flex-shrink: 0;
        }
        .meta-merchant .m-text { min-width: 0; }
        .meta-merchant .m-name {
            font-size: 15px; font-weight: 700; color: #222;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .meta-merchant .m-inv {
            font-size: 12px; color: #888; margin-top: 3px;
        }
        .meta-merchant .m-inv span {
            display: block; color: #555; font-size: 11px;
            word-break: break-all; line-height: 1.3;
        }
        .meta-amount {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 130px;
        }
        .meta-amount .cur {
            font-size: 16px; color: #666; font-weight: 500;
        }
        .meta-amount .val {
            font-size: 28px; font-weight: 700; color: #222;
            line-height: 1.1;
        }

        .send-strip {
            margin: 0 28px 14px;
            padding: 12px 16px;
            background: #f7f9fc;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            font-size: 14px;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }
        .send-strip strong { color: #222; letter-spacing: .3px; }
        .send-strip button {
            padding: 6px 12px; font-size: 12px;
            border: 1px solid #d0d0d0; border-radius: 6px;
            background: #fff; cursor: pointer; color: #444;
        }

        .pay-instruction-box {
            margin: 0 28px 14px;
            padding: 14px 16px 12px;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
        }
        .pay-instruction-box .inst-head {
            font-size: 14px; font-weight: 700; color: #222;
            margin-bottom: 10px; line-height: 1.4;
        }
        .pay-instruction-box .inst-type {
            display: inline-block;
            font-size: 11px; font-weight: 600;
            background: #eef4ff; color: #2563a8;
            padding: 3px 8px; border-radius: 999px;
            margin-left: 6px; vertical-align: middle;
        }
        .pay-instruction-box ol {
            margin: 0; padding-left: 18px;
            font-size: 13px; color: #444; line-height: 1.55;
        }
        .pay-instruction-box li { margin-bottom: 6px; }
        .pay-instruction-box li:last-child { margin-bottom: 0; }
        .inst-copy-line {
            display: inline-flex; align-items: center; gap: 6px;
            flex-wrap: wrap;
        }
        .inst-copy-line strong { color: #111; }
        .inst-copy-btn {
            border: 1px solid #d0d7e2; background: #f8fafc;
            color: #2563a8; border-radius: 6px;
            padding: 2px 8px; font-size: 11px; cursor: pointer;
            line-height: 1.4; vertical-align: middle;
        }
        .inst-copy-btn:hover { background: #eef4ff; }
        .inst-copy-btn.copied { color: #15803d; border-color: #86efac; background: #f0fdf4; }

        .brand-panel {
            margin: 0 28px;
            background: var(--brand);
            border-radius: 12px;
            padding: 22px 20px 20px;
        }
        .brand-panel .bfld { margin-bottom: 16px; }
        .brand-panel .bfld:last-child { margin-bottom: 0; }
        .brand-panel label {
            display: block;
            font-size: 15px;
            font-weight: 500;
            color: #fff;
            margin-bottom: 8px;
        }
        .brand-panel input {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            background: #fff;
            color: #333;
        }
        .brand-panel input::placeholder { color: #aaa; }
        .brand-panel input:focus {
            box-shadow: 0 0 0 2px rgba(255,255,255,.35);
        }

        .btn-verify-wrap { padding: 20px 28px 0; }
        .btn-verify {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }
        .btn-verify:hover { background: var(--brand-dark); }

        .alert-err {
            margin: 14px 28px 0; padding: 12px 16px; border-radius: 8px;
            background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; font-size: 14px;
        }
    </style>
</head>
<body>

@php
    $siteName = $gs->title ?? 'CreativePay';
    $logoUrl  = !empty($gs->logo) ? asset_image('assets/images/logo/' . $gs->logo) : null;
    $faviconUrl = !empty($gs->favicon) ? asset_image('assets/images/' . $gs->favicon) : null;
    $brandIconUrl = $faviconUrl ?: $logoUrl;
    $logos    = [
        'bkash'  => asset_image('assets/payment/bkash.png') . '?v=5',
        'nagad'  => asset_image('assets/payment/nagad.png'),
        'rocket' => asset_image('assets/payment/rocket.png'),
    ];
    $brands = [
        'bkash'  => ['color' => '#D12053', 'dark' => '#b01040'],
        'nagad'  => ['color' => '#EE1C24', 'dark' => '#c91820'],
        'rocket' => ['color' => '#8C3494', 'dark' => '#702878'],
    ];
    $methodInstructions = [];
    foreach ($methods as $key => $m) {
        $methodInstructions[$key] = \App\Services\PaymentInstructionService::forMethod(
            $key,
            $m['account_type'] ?? 'personal',
            $m['number'],
            (float) $txn->amount
        );
    }
@endphp

<div class="modal">
    @if(session('error'))
        <div class="alert-err">{{ session('error') }}</div>
    @endif

    {{-- ═══ STEP 1 — Method selection ═══ --}}
    <div class="step1" id="step1">
        <div class="nav-box">
            <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
            <div class="nav-right">
                <button type="button"><i class="fas fa-language"></i></button>
                <a href="{{ route('payment.checkout.cancel', $txn->invoice_id) }}"><i class="fas fa-times"></i></a>
            </div>
        </div>

        <div class="merchant-row">
            @if($brandIconUrl)
                <div class="merchant-avatar-wrap">
                    <img src="{{ $brandIconUrl }}" alt="{{ $siteName }}" class="merchant-avatar">
                </div>
            @else
                <div class="merchant-avatar-ph">{{ strtoupper(substr($siteName, 0, 1)) }}</div>
            @endif
            <div class="merchant-info">
                <div class="merchant-title">{{ $siteName }}</div>
                <div class="action-btns">
                    <button type="button" class="action-btn" onclick="panel('support')"><i class="fas fa-headset"></i> Support</button>
                    <button type="button" class="action-btn" onclick="panel('faq')"><i class="fas fa-question-circle"></i> FAQ</button>
                    <button type="button" class="action-btn" onclick="panel('details')"><i class="fas fa-info-circle"></i> Details</button>
                </div>
            </div>
        </div>

        <div id="p-support" class="info-panel">Support: {{ $gs->phone ?? $gs->email ?? 'Admin-এর সাথে যোগাযোগ করুন' }}</div>
        <div id="p-faq" class="info-panel">
            1. পেমেন্ট মেথড select করুন<br>
            2. <b>Pay</b> বাটন চাপুন<br>
            3. Send Money করুন → TrxID দিন<br>
            4. Auto verify হলে সক্রিয় হবে
        </div>
        <div id="p-details" class="info-panel">
            Invoice: {{ $txn->invoice_id }}<br>
            Customer: {{ $txn->full_name }}<br>
            Amount: {{ number_format($txn->amount, 2) }} BDT
        </div>

        <div class="banking-bar">Mobile Banking</div>

        <div class="pay-grid">
            @foreach($methods as $key => $m)
            <label class="pay-cell" data-key="{{ $key }}" data-label="{{ $m['label'] }}" data-num="{{ $m['number'] }}">
                <input type="radio" name="sel" value="{{ $key }}">
                @if(isset($logos[$key]))
                    <div class="pay-logo-wrap">
                        <img src="{{ $logos[$key] }}" alt="{{ $m['label'] }}">
                    </div>
                @else
                    <div class="pay-logo-wrap">
                        <span style="font-weight:700;color:{{ $m['color'] }};font-size:13px;">{{ $m['label'] }}</span>
                    </div>
                @endif
            </label>
            @endforeach
        </div>

        <div class="pay-btn-wrap">
            <button type="button" class="btn-pay" id="btnPay" disabled>
                Pay {{ number_format($txn->amount, 2) }} BDT
            </button>
        </div>
    </div>

    {{-- ═══ STEP 2 — payment verify form ═══ --}}
    <div class="step2" id="step2">
        <div class="nav-box">
            <button type="button" id="btnBack"><i class="fas fa-arrow-left"></i></button>
            <div class="nav-right">
                <button type="button"><i class="fas fa-language"></i></button>
                <a href="{{ route('payment.checkout.cancel', $txn->invoice_id) }}"><i class="fas fa-times"></i></a>
            </div>
        </div>

        <div class="brand-logo-wrap" id="brandLogoWrap">
            <img id="brandLogo" src="{{ $logos['bkash'] ?? '' }}" alt="">
        </div>

        <div class="meta-row">
            <div class="meta-merchant">
                @if($brandIconUrl)
                    <div class="m-av-wrap">
                        <img src="{{ $brandIconUrl }}" alt="{{ $siteName }}" class="m-av">
                    </div>
                @else
                    <div class="m-av-ph">{{ strtoupper(substr($siteName, 0, 1)) }}</div>
                @endif
                <div class="m-text">
                    <div class="m-name">{{ $siteName }}</div>
                    <div class="m-inv">ইনভয়েস আইডি: <span>{{ $txn->invoice_id }}</span></div>
                </div>
            </div>
            <div class="meta-amount">
                <div class="cur">৳</div>
                <div class="val">{{ number_format($txn->amount, 2) }}</div>
            </div>
        </div>

        <div class="send-strip">
            <span><span id="sendActionLabel">Send Money</span>: <strong id="sNum"></strong></span>
            <button type="button" id="btnCopy"><i class="fas fa-copy"></i> Copy</button>
        </div>

        <div class="pay-instruction-box" id="payInstructionBox">
            <div class="inst-head">
                <span id="instActionLabel">Send Money</span> নির্দেশনা
                <span class="inst-type" id="instAccountType">Personal (পার্সোনাল)</span>
            </div>
            <ol id="instSteps"></ol>
        </div>

        <form method="POST" action="{{ route('payment.checkout.submit', $txn->invoice_id) }}">
            @csrf
            <input type="hidden" name="payment_method" id="fMethod">

            <div class="brand-panel">
                <div class="bfld">
                    <label>ট্রানজেকশন আইডি দিন</label>
                    <input type="text" name="transaction_id" placeholder="ট্রানজেকশন আইডি দিন" required
                           value="{{ old('transaction_id') }}" style="text-transform:uppercase">
                </div>
                <div class="bfld">
                    <label>ফোন নম্বর লিখুন</label>
                    <input type="tel" name="sender_number" placeholder="ফোন নম্বর লিখুন" required
                           value="{{ old('sender_number') }}">
                </div>
            </div>

            <div class="btn-verify-wrap">
                <button type="submit" class="btn-verify">Verify</button>
            </div>
        </form>
    </div>
</div>

<script>
const brands = @json($brands);
const logos  = @json($logos);
const methodInstructions = @json($methodInstructions);

let pick = null;
const cells = document.querySelectorAll('.pay-cell');
const btnPay = document.getElementById('btnPay');

function copyText(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        if (!btn) return;
        const old = btn.innerHTML;
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = old;
        }, 1500);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    });
}

function instCopyHtml(display, copyValue) {
    const text = String(display);
    const copy = String(copyValue ?? display).replace(/"/g, '&quot;');
    return `<span class="inst-copy-line"><strong>${text}</strong><button type="button" class="inst-copy-btn" data-copy="${copy}"><i class="fas fa-copy"></i> Copy</button></span>`;
}

function updatePaymentInstructions(key) {
    const inst = methodInstructions[key];
    if (!inst) return;

    document.getElementById('sendActionLabel').textContent = inst.action_label;
    document.getElementById('instActionLabel').textContent = inst.action_label_bn || inst.action_label;
    document.getElementById('instAccountType').textContent = inst.account_type_label;
    document.getElementById('sNum').textContent = inst.number;

    const stepsEl = document.getElementById('instSteps');
    stepsEl.innerHTML = (inst.steps || []).map(step => {
        let line = step;
        if (inst.number) {
            const numRe = new RegExp(inst.number.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            line = line.replace(numRe, instCopyHtml(inst.number));
        }
        const amountFull = inst.amount + ' BDT';
        if (inst.amount && line.includes(amountFull)) {
            line = line.replace(amountFull, instCopyHtml(amountFull, inst.amount));
        }
        return `<li>${line}</li>`;
    }).join('');
}

document.getElementById('payInstructionBox')?.addEventListener('click', e => {
    const btn = e.target.closest('.inst-copy-btn');
    if (btn) copyText(btn.dataset.copy, btn);
});

function applyBrand(key) {
    const b = brands[key];
    if (!b) return;
    document.documentElement.style.setProperty('--brand', b.color);
    document.documentElement.style.setProperty('--brand-dark', b.dark);
    document.getElementById('brandLogo').src = logos[key] || '';
}

function goStep2() {
    if (!pick) return;
    document.getElementById('fMethod').value = pick.key;
    updatePaymentInstructions(pick.key);
    applyBrand(pick.key);
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('active');
}

cells.forEach(c => {
    c.addEventListener('click', () => {
        cells.forEach(x => x.classList.remove('selected'));
        c.classList.add('selected');
        c.querySelector('input').checked = true;
        pick = { key: c.dataset.key, label: c.dataset.label, num: c.dataset.num };
        btnPay.disabled = false;
        goStep2();
    });
});

btnPay.addEventListener('click', goStep2);

document.getElementById('btnBack').addEventListener('click', () => {
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step1').classList.remove('hidden');
});

document.getElementById('btnCopy').addEventListener('click', () => {
    if (!pick) return;
    copyText(pick.num, document.getElementById('btnCopy'));
    const btn = document.getElementById('btnCopy');
    btn.innerHTML = '<i class="fas fa-check"></i> Copied';
    setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i> Copy', 1500);
});

function panel(n) {
    const el = document.getElementById('p-' + n);
    const open = el.classList.contains('show');
    ['support','faq','details'].forEach(p => document.getElementById('p-'+p).classList.remove('show'));
    if (!open) el.classList.add('show');
}

@if(old('payment_method'))
    (function(){
        const el = document.querySelector('.pay-cell[data-key="{{ old('payment_method') }}"]');
        if (el) el.click();
    })();
@endif
</script>
</body>
</html>
