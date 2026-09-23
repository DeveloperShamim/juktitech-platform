@extends('layouts.admin')

@section('styles')
<style>
.email-cfg__stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.email-cfg__stat {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.email-cfg__stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.email-cfg__stat-icon--green { background: #dcfce7; color: #166534; }
.email-cfg__stat-icon--red   { background: #fee2e2; color: #991b1b; }
.email-cfg__stat-icon--blue  { background: #dbeafe; color: #1d4ed8; }
.email-cfg__stat-icon--gray  { background: #f1f5f9; color: #64748b; }

.email-cfg__stat-body span {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--adm-muted);
    margin-bottom: 4px;
}

.email-cfg__stat-body strong {
    display: block;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--adm-text);
    line-height: 1.35;
    word-break: break-word;
}

.email-cfg__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 22px;
    align-items: start;
}

.email-cfg__card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.email-cfg__card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.email-cfg__card-head h3 {
    margin: 0;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.email-cfg__card-head h3 i {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.email-cfg__card-body {
    padding: 22px 24px;
}

.email-cfg__step {
    margin-bottom: 28px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.email-cfg__step:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
}

.email-cfg__step-label {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.email-cfg__step-num {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.email-cfg__step-label h4 {
    margin: 0;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--adm-text);
}

.email-cfg__step-label p {
    margin: 2px 0 0;
    font-size: 0.78rem;
    color: var(--adm-muted);
}

.email-cfg__toggle-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 18px;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
}

.email-cfg__toggle-box__text strong {
    display: block;
    font-size: 0.9rem;
    color: var(--adm-text);
    margin-bottom: 4px;
}

.email-cfg__toggle-box__text small {
    display: block;
    font-size: 0.78rem;
    color: var(--adm-muted);
    line-height: 1.45;
}

.email-cfg__seg {
    display: inline-flex;
    padding: 4px;
    background: #e2e8f0;
    border-radius: 10px;
    gap: 4px;
}

.email-cfg__seg-btn {
    border: 0;
    background: transparent;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, box-shadow 0.15s;
    white-space: nowrap;
}

.email-cfg__seg-btn.is-active {
    background: #fff;
    color: #166534;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.1);
}

.email-cfg__seg-btn.is-active.is-off {
    color: #991b1b;
}

.email-cfg__presets {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}

.email-cfg__preset {
    border: 1px solid var(--adm-border);
    background: #fff;
    border-radius: 999px;
    padding: 7px 14px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--adm-text);
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s, color 0.15s;
}

.email-cfg__preset:hover {
    border-color: #93c5fd;
    background: #eff6ff;
    color: #1d4ed8;
}

.email-cfg__pw-wrap {
    position: relative;
}

.email-cfg__pw-wrap .input-field {
    padding-right: 46px;
}

.email-cfg__pw-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: var(--adm-muted);
    cursor: pointer;
    padding: 4px 6px;
    line-height: 1;
}

.email-cfg__pw-toggle:hover {
    color: var(--adm-primary);
}

.email-cfg__aside {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.email-cfg__tip {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.email-cfg__tip-head {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.email-cfg__tip-head i { color: #2563eb; }

.email-cfg__tip-body {
    padding: 14px 18px 16px;
}

.email-cfg__tip-body ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.email-cfg__tip-body li {
    position: relative;
    padding-left: 16px;
    margin-bottom: 10px;
    font-size: 0.8rem;
    color: var(--adm-muted);
    line-height: 1.55;
}

.email-cfg__tip-body li:last-child { margin-bottom: 0; }

.email-cfg__tip-body li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #93c5fd;
}

.email-cfg__provider {
    padding: 12px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fafbfc;
    margin-bottom: 8px;
}

.email-cfg__provider:last-child { margin-bottom: 0; }

.email-cfg__provider strong {
    display: block;
    font-size: 0.8rem;
    color: var(--adm-text);
    margin-bottom: 4px;
}

.email-cfg__provider span {
    display: block;
    font-size: 0.74rem;
    color: var(--adm-muted);
    line-height: 1.45;
}

.email-cfg__provider code {
    display: inline-block;
    margin-top: 4px;
    padding: 2px 7px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #334155;
    font-size: 0.72rem;
}

.email-cfg__save {
    margin-top: 8px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

.email-cfg__save .adm-form__submit {
    max-width: 320px;
}

.email-cfg__badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
}

.email-cfg__badge--on  { background: #dcfce7; color: #166534; }
.email-cfg__badge--off { background: #fee2e2; color: #991b1b; }

.email-cfg__badge-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

#is_smtp.email-cfg__hidden-select {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

@media (max-width: 1100px) {
    .email-cfg__layout { grid-template-columns: 1fr; }
    .email-cfg__stats  { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
    .email-cfg__toggle-box {
        flex-direction: column;
        align-items: stretch;
    }
    .email-cfg__seg { width: 100%; }
    .email-cfg__seg-btn { flex: 1; text-align: center; }
    .email-cfg__save .adm-form__submit { max-width: none; }
}
</style>
@endsection

@section('content')
@php
    $smtpOn = (int) ($data->is_smtp ?? 0) === 1;
    $hostDisplay = $data->smtp_host ?: __('Not configured');
    $fromDisplay = $data->from_email ?: __('Not set');
@endphp
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-envelope-open-text',
        'title' => __('Email Configuration'),
        'subtitle' => __('SMTP সার্ভার, অথেন্টিকেশন ও সেন্ডার তথ্য সেট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Email Settings'), 'url' => null],
            ['label' => __('Email Configuration'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="email-cfg__stats">
        <div class="email-cfg__stat">
            <div class="email-cfg__stat-icon {{ $smtpOn ? 'email-cfg__stat-icon--green' : 'email-cfg__stat-icon--red' }}">
                <i class="fas fa-power-off"></i>
            </div>
            <div class="email-cfg__stat-body">
                <span>{{ __('SMTP Status') }}</span>
                <strong>{{ $smtpOn ? __('Active') : __('Inactive') }}</strong>
            </div>
        </div>
        <div class="email-cfg__stat">
            <div class="email-cfg__stat-icon email-cfg__stat-icon--blue">
                <i class="fas fa-server"></i>
            </div>
            <div class="email-cfg__stat-body">
                <span>{{ __('SMTP Host') }}</span>
                <strong>{{ $hostDisplay }}</strong>
            </div>
        </div>
        <div class="email-cfg__stat">
            <div class="email-cfg__stat-icon email-cfg__stat-icon--gray">
                <i class="fas fa-at"></i>
            </div>
            <div class="email-cfg__stat-body">
                <span>{{ __('From Email') }}</span>
                <strong>{{ $fromDisplay }}</strong>
            </div>
        </div>
    </div>

    <div class="email-cfg__layout">
        <div class="email-cfg__card">
            <div class="email-cfg__card-head">
                <h3><i class="fas fa-cog"></i> {{ __('SMTP Settings') }}</h3>
                <span class="email-cfg__badge {{ $smtpOn ? 'email-cfg__badge--on' : 'email-cfg__badge--off' }}">
                    <span class="email-cfg__badge-dot"></span>
                    {{ $smtpOn ? __('Sending enabled') : __('Sending disabled') }}
                </span>
            </div>
            <div class="email-cfg__card-body">
                <div class="gocover"></div>
                <form action="{{ route('admin.generalsettings.update') }}" id="geniusform" method="POST" enctype="multipart/form-data" class="adm-form" style="padding:0;">
                    @csrf

                    {{-- Step 1: SMTP toggle --}}
                    <div class="email-cfg__step">
                        <div class="email-cfg__step-label">
                            <span class="email-cfg__step-num">1</span>
                            <div>
                                <h4>{{ __('Email Delivery') }}</h4>
                                <p>{{ __('SMTP চালু করলে সাইট থেকে ইমেইল পাঠানো হবে') }}</p>
                            </div>
                        </div>
                        <div class="email-cfg__toggle-box">
                            <div class="email-cfg__toggle-box__text">
                                <strong>{{ __('SMTP Status') }}</strong>
                                <small>{{ __('টগল করলে সাথে সাথে সেভ হবে। বন্ধ থাকলে ইমেইল পাঠানো বন্ধ থাকবে।') }}</small>
                            </div>
                            <div class="email-cfg__seg" role="group" aria-label="{{ __('SMTP Status') }}">
                                <button type="button" class="email-cfg__seg-btn {{ $smtpOn ? 'is-active' : '' }}" data-smtp-value="1">
                                    <i class="fas fa-check-circle"></i> {{ __('Activated') }}
                                </button>
                                <button type="button" class="email-cfg__seg-btn {{ !$smtpOn ? 'is-active is-off' : '' }}" data-smtp-value="0">
                                    <i class="fas fa-times-circle"></i> {{ __('Deactivated') }}
                                </button>
                            </div>
                            <select class="process select droplinks email-cfg__hidden-select {{ $smtpOn ? 'drop-success' : 'drop-danger' }}"
                                    name="is_smtp" id="is_smtp" aria-hidden="true" tabindex="-1">
                                <option value="1" {{ $smtpOn ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                <option value="0" {{ !$smtpOn ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Step 2: Server --}}
                    <div class="email-cfg__step">
                        <div class="email-cfg__step-label">
                            <span class="email-cfg__step-num">2</span>
                            <div>
                                <h4>{{ __('Server Configuration') }}</h4>
                                <p>{{ __('মেইল সার্ভারের হোস্ট, পোর্ট ও এনক্রিপশন') }}</p>
                            </div>
                        </div>

                        <p class="adm-form__hint mb-3" style="margin-top:0;">
                            <i class="fas fa-magic"></i>
                            {{ __('দ্রুত সেটআপ:') }}
                        </p>
                        <div class="email-cfg__presets">
                            <button type="button" class="email-cfg__preset" data-preset="gmail">Gmail</button>
                            <button type="button" class="email-cfg__preset" data-preset="outlook">Outlook</button>
                            <button type="button" class="email-cfg__preset" data-preset="cpanel">cPanel</button>
                        </div>

                        <div class="adm-form__grid">
                            <div class="adm-form__field adm-form__field--select">
                                <label for="driver">{{ __('Driver') }} *</label>
                                <select class="input-field adm-select" name="driver" id="driver" required>
                                    <option value="smtp" {{ $data->driver === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $data->driver === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mail" {{ $data->driver === 'mail' ? 'selected' : '' }}>Mail</option>
                                </select>
                                <small>{{ __('সাধারণত SMTP ব্যবহার করুন') }}</small>
                            </div>

                            <div class="adm-form__field adm-form__field--select">
                                <label for="email_encryption">{{ __('Encryption') }} *</label>
                                @php
                                    $encryption = strtolower(trim((string) $data->email_encryption));
                                    $knownEncryption = in_array($encryption, ['tls', 'ssl', ''], true);
                                @endphp
                                <select class="input-field adm-select" name="email_encryption" id="email_encryption" required>
                                    <option value="tls" {{ $encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ $encryption === '' ? 'selected' : '' }}>{{ __('None') }}</option>
                                    @if(!$knownEncryption)
                                        <option value="{{ $data->email_encryption }}" selected>{{ $data->email_encryption }}</option>
                                    @endif
                                </select>
                                <small>{{ __('Port 587 = TLS, Port 465 = SSL') }}</small>
                            </div>

                            <div class="adm-form__field adm-form__field--full">
                                <label for="smtp_host">{{ __('SMTP Host') }} *</label>
                                <input type="text" class="input-field" id="smtp_host" name="smtp_host" placeholder="smtp.gmail.com" value="{{ $data->smtp_host }}" required>
                            </div>

                            <div class="adm-form__field">
                                <label for="smtp_port">{{ __('SMTP Port') }} *</label>
                                <input type="number" class="input-field" id="smtp_port" name="smtp_port" placeholder="587" value="{{ $data->smtp_port }}" required min="1" max="65535">
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Authentication --}}
                    <div class="email-cfg__step">
                        <div class="email-cfg__step-label">
                            <span class="email-cfg__step-num">3</span>
                            <div>
                                <h4>{{ __('Authentication') }}</h4>
                                <p>{{ __('SMTP লগইন ইউজারনেম ও পাসওয়ার্ড') }}</p>
                            </div>
                        </div>
                        <div class="adm-form__grid">
                            <div class="adm-form__field adm-form__field--full">
                                <label for="smtp_user">{{ __('SMTP Username') }} *</label>
                                <input type="text" class="input-field" id="smtp_user" name="smtp_user" placeholder="{{ __('Your email or SMTP username') }}" value="{{ $data->smtp_user }}" required autocomplete="username">
                            </div>

                            <div class="adm-form__field adm-form__field--full">
                                <label for="smtp_pass">{{ __('SMTP Password') }} *</label>
                                <div class="email-cfg__pw-wrap">
                                    <input type="password" class="input-field" id="smtp_pass" name="smtp_pass" placeholder="{{ __('Enter SMTP password or app password') }}" value="{{ $data->smtp_pass }}" required autocomplete="current-password">
                                    <button type="button" class="email-cfg__pw-toggle" data-target="#smtp_pass" aria-label="{{ __('Show password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small>{{ __('Gmail-এ App Password ব্যবহার করুন') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Sender --}}
                    <div class="email-cfg__step">
                        <div class="email-cfg__step-label">
                            <span class="email-cfg__step-num">4</span>
                            <div>
                                <h4>{{ __('Sender Identity') }}</h4>
                                <p>{{ __('ইউজার যে নাম ও ইমেইল দেখবে') }}</p>
                            </div>
                        </div>
                        <div class="adm-form__grid">
                            <div class="adm-form__field">
                                <label for="from_email">{{ __('From Email') }} *</label>
                                <input type="email" class="input-field" id="from_email" name="from_email" placeholder="noreply@yourdomain.com" value="{{ $data->from_email }}" required>
                            </div>

                            <div class="adm-form__field">
                                <label for="from_name">{{ __('From Name') }} *</label>
                                <input type="text" class="input-field" id="from_name" name="from_name" placeholder="{{ __('Your Company Name') }}" value="{{ $data->from_name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="email-cfg__save adm-form__actions" style="margin-top:0;padding-top:0;border-top:0;">
                        <button class="adm-form__submit addProductSubmit-btn" type="submit">
                            <i class="fas fa-save"></i> {{ __('Save Configuration') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="email-cfg__aside">
            <div class="email-cfg__tip">
                <div class="email-cfg__tip-head">
                    <i class="fas fa-lightbulb"></i> {{ __('Quick Tips') }}
                </div>
                <div class="email-cfg__tip-body">
                    <ul>
                        <li>{{ __('সেভ করার পর পাসওয়ার্ড রিসেট বা নোটিফিকেশন ইমেইল দিয়ে টেস্ট করুন।') }}</li>
                        <li>{{ __('Port 587 + TLS বেশিরভাগ সার্ভারের জন্য সঠিক।') }}</li>
                        <li>{{ __('SMTP পাসওয়ার্ড কখনো শেয়ার করবেন না।') }}</li>
                    </ul>
                </div>
            </div>

            <div class="email-cfg__tip">
                <div class="email-cfg__tip-head">
                    <i class="fas fa-envelope-open-text"></i> {{ __('Common Providers') }}
                </div>
                <div class="email-cfg__tip-body">
                    <div class="email-cfg__provider">
                        <strong>Gmail</strong>
                        <span>smtp.gmail.com · Port 587 · TLS</span>
                        <code>App Password required</code>
                    </div>
                    <div class="email-cfg__provider">
                        <strong>Outlook / Office365</strong>
                        <span>smtp.office365.com · Port 587 · TLS</span>
                    </div>
                    <div class="email-cfg__provider">
                        <strong>cPanel / Shared Hosting</strong>
                        <span>mail.yourdomain.com · Port 465 · SSL</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset_image('assets/admin/js/distawk.js') }}"></script>
<script>
(function () {
    var presets = {
        gmail:   { host: 'smtp.gmail.com',      port: '587', encryption: 'tls', driver: 'smtp' },
        outlook: { host: 'smtp.office365.com',  port: '587', encryption: 'tls', driver: 'smtp' },
        cpanel:  { host: 'mail.yourdomain.com', port: '465', encryption: 'ssl', driver: 'smtp' }
    };

    document.querySelectorAll('.email-cfg__preset').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var preset = presets[btn.getAttribute('data-preset')];
            if (!preset) return;

            var host = document.getElementById('smtp_host');
            var port = document.getElementById('smtp_port');
            var enc  = document.getElementById('email_encryption');
            var drv  = document.getElementById('driver');

            if (host) host.value = preset.host;
            if (port) port.value = preset.port;
            if (enc)  enc.value  = preset.encryption;
            if (drv)  drv.value  = preset.driver;

            if (host) host.focus();
        });
    });

    var smtpSelect = document.getElementById('is_smtp');
    var segButtons = document.querySelectorAll('.email-cfg__seg-btn');

    function syncSegButtons(value) {
        segButtons.forEach(function (btn) {
            var active = btn.getAttribute('data-smtp-value') === value;
            btn.classList.toggle('is-active', active);
            btn.classList.toggle('is-off', active && value === '0');
        });
    }

    segButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!smtpSelect) return;
            var val = btn.getAttribute('data-smtp-value');
            if (smtpSelect.value === val) return;

            smtpSelect.value = val;
            smtpSelect.classList.toggle('drop-success', val === '1');
            smtpSelect.classList.toggle('drop-danger', val === '0');
            syncSegButtons(val);

            var event = new Event('change', { bubbles: true });
            smtpSelect.dispatchEvent(event);
        });
    });

    document.querySelectorAll('.email-cfg__pw-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(btn.getAttribute('data-target'));
            if (!input) return;
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }
        });
    });
})();
</script>
@endsection
