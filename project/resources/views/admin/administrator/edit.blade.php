@extends('layouts.load')

@section('content')
<div class="adm-form">
    @include('includes.admin.form-both')

    <div class="gocover"></div>

    <p class="adm-form__hint">{{ __('Editing') }}: <strong>{{ $data->name }}</strong></p>

    <form id="geniusformdata" action="{{ route('admin.administator.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="adm-form__grid">
            <div class="adm-form__field">
                <label>{{ __('Name') }} *</label>
                <input type="text" class="input-field" name="name" placeholder="{{ __('User Name') }}" required value="{{ $data->name }}">
            </div>

            <div class="adm-form__field">
                <label>{{ __('Email') }} *</label>
                <input type="email" class="input-field" name="email" placeholder="{{ __('Email Address') }}" required value="{{ $data->email }}">
            </div>

            <div class="adm-form__field">
                <label>{{ __('Phone') }} *</label>
                <input type="text" class="input-field" name="phone" placeholder="{{ __('Phone Number') }}" required value="{{ $data->phone }}">
            </div>

            <div class="adm-form__field adm-form__field--full adm-form__field--select">
                <label>{{ __('Role') }} *</label>
                <select name="role_id" class="input-field adm-select" required>
                    @foreach ($roles as $item)
                        <option {{ $item->id == $data->role_id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Password') }} *</label>
                <input type="password" class="input-field" name="password" placeholder="{{ __('Password') }}" value="">
            </div>
        </div>

        <div class="adm-form__actions">
            <button class="adm-form__submit adm-form__submit--edit addProductSubmit-btn" type="submit">{{ __('Update Staff') }}</button>
        </div>
    </form>
</div>
@endsection
