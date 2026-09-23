@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-cogs',
        'title' => __('SMS Settings'),
        'subtitle' => __('Configure your SMS gateway integration details'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('SMS Settings'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="adm-panel">
                <div class="adm-panel__head d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="adm-panel__title mb-0"><i class="fas fa-cogs"></i> {{ __('API Configuration') }}</h3>
                    <span class="badge badge-primary px-3 py-2"><i class="fas fa-server mr-1"></i> BulkSMSBD</span>
                </div>
                <div class="adm-panel__body p-4">
                    <div class="adm-form__hint mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>API Integration Guide:</strong>
                        Please login to your BulkSMSBD account to retrieve the API Key. Ensure that your Sender IDs are approved before using them here.
                    </div>

                    <form action="{{ route('admin.sms.settings.update') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">{{ __('API Key') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
                                <input type="text" name="sms_api_key" class="form-control" value="{{ $gs->sms_api_key ?? '' }}" placeholder="Enter your 32-digit API Key" required>
                            </div>
                            <small class="text-muted">This key is required to authenticate your requests with the SMS gateway.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">{{ __('Default Sender ID') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-paper-plane"></i></span></div>
                                        <input type="text" name="sms_sender_id" class="form-control" value="{{ $gs->sms_sender_id ?? '' }}" placeholder="e.g. 8809612xxxxxx">
                                    </div>
                                    <small class="text-muted"><i class="fas fa-check text-success mr-1"></i> Used for Non-Masking SMS.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">{{ __('Brand / Masking ID') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                        <input type="text" name="masking_sender_id" class="form-control" value="{{ $gs->masking_sender_id ?? '' }}" placeholder="e.g. MYCOMPANY">
                                    </div>
                                    <small class="text-muted"><i class="fas fa-star text-warning mr-1"></i> Used for Branded SMS (Optional).</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="addProductSubmit-btn">
                                <i class="fas fa-save mr-2"></i> {{ __('Save Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
