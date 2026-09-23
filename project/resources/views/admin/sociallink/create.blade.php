@extends('layouts.load')

@section('content')
<div class="adm-form">
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="gocover"></div>

    <form id="geniusformdata" action="{{ route('social.link.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="adm-form__grid">
            <div class="adm-form__field adm-form__field--full adm-form__field--select">
                <label>{{ __('Social Media Name') }} *</label>
                <select name="name" class="input-field adm-select">
                    <option value="">{{ __('Select a Social Media Here') }}</option>
                    <option value="facebook">{{ __('Facebook') }}</option>
                    <option value="twitter">{{ __('Twitter') }}</option>
                    <option value="instagram">{{ __('Instagram') }}</option>
                    <option value="linkedin">{{ __('Linkedin') }}</option>
                    <option value="youtube">{{ __('Youtube') }}</option>
                    <option value="vimeo">{{ __('Vimeo') }}</option>
                    <option value="pinterest">{{ __('Pinterest') }}</option>
                </select>
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Social Link') }} *</label>
                <input type="text" class="input-field" name="link" placeholder="{{ __('Social Link') }}" required value="">
            </div>

            <div class="adm-form__field adm-form__field--full">
                <label>{{ __('Icon') }} *</label>
                <div class="d-flex align-items-center">
                    <i class="mr-2" id="icn"></i>
                    <input type="text" id="icons" class="input-field" name="icon" placeholder="{{ __('Social Icon') }}" required value="">
                </div>
            </div>
        </div>

        <div class="adm-form__actions">
            <button class="adm-form__submit addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset_image('assets/admin/js/iconpicker.js') }}"></script>
<script src="{{ asset_image('assets/admin/js/icon.js') }}"></script>
@endsection
