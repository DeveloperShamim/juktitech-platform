@extends('layouts.load')

@section('content')
<div class="adm-form">
    @include('includes.admin.form-both')

    <div class="gocover"></div>

    <form id="geniusformdata" action="{{ route('admin.administator.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="adm-form__grid">
            <div class="adm-form__field">
                <label>{{ __('Name') }} *</label>
                <input type="text" class="input-field" name="name" placeholder="{{ __('User Name') }}" required value="">
            </div>

            <div class="adm-form__field">
                <label>{{ __('Email') }} *</label>
                <input type="email" class="input-field" name="email" placeholder="{{ __('Email Address') }}" required value="">
            </div>

            <div class="adm-form__field">
                <label>{{ __('Phone') }} *</label>
                <input type="text" class="input-field" name="phone" placeholder="{{ __('Phone Number') }}" required value="">
            </div>

            <div class="adm-form__field adm-form__field--full adm-form__field--select">
                <label>{{ __('Role') }} *</label>
                <select name="role_id" class="input-field adm-select" required>
                    @foreach ($roles as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Password') }} *</label>
                <input type="password" class="input-field" name="password" placeholder="{{ __('Password') }}" required value="">
            </div>
        </div>

        <div class="adm-form__actions">
            <button class="adm-form__submit addProductSubmit-btn" type="submit">{{ __('Create Staff') }}</button>
        </div>
    </form>
</div>
@endsection
