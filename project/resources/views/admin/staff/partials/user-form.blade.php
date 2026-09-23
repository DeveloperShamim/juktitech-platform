@php
    $isEdit = ($mode ?? 'create') === 'edit';
    $user = $data ?? null;
@endphp

<div class="gocover"></div>

@if($isEdit && $user)
    <p class="adm-form__hint">{{ __('Editing user') }}: <strong>{{ $user->name }}</strong> — {{ $user->designation }}</p>
@endif

<div class="um-section">
    <div class="um-section__title"><i class="fas fa-camera"></i> {{ __('Profile Photo') }}</div>
    <div class="um-photo">
        <div class="um-photo__preview {{ ($isEdit && $user?->photo) ? '' : 'is-empty' }}" id="um-photo-preview"
             @if($isEdit && $user?->photo)
                 style="background-image:url('{{ asset_image('assets/images/admin/'.$user->photo) }}');"
             @endif>
            @if(!$isEdit || !$user?->photo)
                <i class="fas fa-user"></i>
            @endif
        </div>
        <div class="um-photo__info">
            <strong>{{ $isEdit ? __('Change Profile Photo') : __('Upload Profile Picture') }}</strong>
            <small>{{ __('Supported: jpeg, png, jpg — max 2MB recommended') }}</small>
            <label class="um-photo__btn" for="um-photo-input">
                <i class="fas fa-upload"></i> {{ __('Choose Photo') }}
            </label>
            <input type="file" name="photo" id="um-photo-input" accept="image/*" @if(!$isEdit) @endif>
        </div>
    </div>
</div>

<div class="um-section">
    <div class="um-section__title"><i class="fas fa-user"></i> {{ __('Account Information') }}</div>
    <div class="adm-form__grid">
        <div class="adm-form__field">
            <label>{{ __('Full Name') }} *</label>
            <input type="text" class="input-field" name="name" placeholder="{{ __('Enter Full Name') }}"
                   value="{{ old('name', $user->name ?? '') }}" required>
        </div>
        <div class="adm-form__field">
            <label>{{ __('Designation') }} *</label>
            <input type="text" class="input-field" name="designation" placeholder="{{ __('e.g. Sales Manager') }}"
                   value="{{ old('designation', $user->designation ?? '') }}" required>
        </div>
        <div class="adm-form__field">
            <label>{{ __('Email Address') }} *</label>
            <input type="email" class="input-field" name="email" placeholder="{{ __('name@example.com') }}"
                   value="{{ old('email', $user->email ?? '') }}" required>
        </div>
        <div class="adm-form__field">
            <label>{{ __('Phone Number') }} *</label>
            <input type="text" class="input-field" name="phone" placeholder="{{ __('017xxxxxxxx') }}"
                   value="{{ old('phone', $user->phone ?? '') }}" required>
        </div>
        <div class="adm-form__field adm-form__field--full">
            <label>
                {{ __('Password') }}
                @if($isEdit)
                    <small class="text-muted">({{ __('leave empty to keep current') }})</small>
                @else
                    *
                @endif
            </label>
            <input type="password" class="input-field" name="password"
                   placeholder="{{ $isEdit ? __('Enter new password') : __('Set a strong password') }}"
                   @if(!$isEdit) required @endif>
        </div>
    </div>
</div>

<div class="um-section">
    <div class="um-section__title"><i class="fas fa-sms"></i> {{ __('SMS & Wallet Settings') }}</div>
    <div class="adm-form__grid">
        <div class="adm-form__field">
            <label>{{ __('Opening Balance') }}</label>
            <input type="number" step="0.01" class="input-field" name="wallet_balance" placeholder="0.00"
                   value="{{ old('wallet_balance', $user->wallet_balance ?? '0.00') }}">
        </div>
        <div class="adm-form__field">
            <label>{{ __('Non-Masking Rate') }}</label>
            <input type="number" step="0.01" class="input-field" name="non_masking_rate" placeholder="0.50"
                   value="{{ old('non_masking_rate', $user->non_masking_rate ?? '0.50') }}">
        </div>
        <div class="adm-form__field">
            <label>{{ __('Masking Rate') }}</label>
            <input type="number" step="0.01" class="input-field" name="masking_rate" placeholder="0.80"
                   value="{{ old('masking_rate', $user->masking_rate ?? '0.80') }}">
        </div>
    </div>
</div>

@if($isEdit && $user)
<div class="um-section">
    <div class="um-section__title"><i class="fas fa-key"></i> {{ __('License Permission') }}</div>
    <div class="um-toggle-row">
        <div>
            <strong>{{ __('Free License Generation') }}</strong>
            <small>{{ __('Enabled হলে ইউজার বিনামূল্যে লাইসেন্স জেনারেট করতে পারবে') }}</small>
        </div>
        <label class="switch mb-0">
            <input type="checkbox" name="free_license_generation" value="1"
                   {{ old('free_license_generation', $user->free_license_generation) ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>
</div>
@endif

<div class="um-actions">
    <button class="um-save {{ $isEdit ? 'um-save--edit' : '' }} addProductSubmit-btn" type="submit">
        <i class="fas fa-save"></i>
        {{ $isEdit ? __('Update Profile') : __('Create User Account') }}
    </button>
</div>

<script>
(function () {
    var input = document.getElementById('um-photo-input');
    var preview = document.getElementById('um-photo-preview');
    if (!input || !preview) return;
    input.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.classList.remove('is-empty');
            preview.style.backgroundImage = 'url(' + e.target.result + ')';
            preview.innerHTML = '';
        };
        reader.readAsDataURL(file);
    });
})();
</script>
