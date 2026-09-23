@extends('layouts.load')

@section('content')
<div class="adm-form">
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="gocover"></div>

    <form id="geniusformdata" action="{{ route('social.link.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="adm-form__grid">
            <div class="adm-form__field adm-form__field--full adm-form__field--select">
                <label>{{ __('Social Media Name') }} *</label>
                <select name="name" class="input-field adm-select">
                    <option value="">{{ __('Select a Social Media Here') }}</option>
                    <option value="facebook" {{ $data->name == 'facebook' ? 'selected' : '' }}>{{ __('Facebook') }}</option>
                    <option value="twitter" {{ $data->name == 'twitter' ? 'selected' : '' }}>{{ __('Twitter') }}</option>
                    <option value="instagram" {{ $data->name == 'instagram' ? 'selected' : '' }}>{{ __('Instagram') }}</option>
                    <option value="linkedin" {{ $data->name == 'linkedin' ? 'selected' : '' }}>{{ __('Linkedin') }}</option>
                    <option value="youtube" {{ $data->name == 'youtube' ? 'selected' : '' }}>{{ __('Youtube') }}</option>
                    <option value="vimeo" {{ $data->name == 'vimeo' ? 'selected' : '' }}>{{ __('Vimeo') }}</option>
                    <option value="pinterest" {{ $data->name == 'pinterest' ? 'selected' : '' }}>{{ __('Pinterest') }}</option>
                </select>
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Social Link') }} *</label>
                <input type="text" class="input-field" name="link" placeholder="{{ __('Social Link') }}" required value="{{ $data->link }}">
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Icon') }} *</label>
                <div class="d-flex align-items-center">
                    <i class="mr-2" id="icn"></i>
                    <input type="text" id="icons" class="input-field" name="icon" placeholder="{{ __('Social Icon') }}" required value="{{ $data->icon }}">
                </div>
            </div>
        </div>

        <div class="adm-form__actions">
            <button class="adm-form__submit adm-form__submit--edit addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset_image('assets/admin/js/iconpicker.js') }}"></script>
<script src="{{ asset_image('assets/admin/js/icon.js') }}"></script>
@endsection
