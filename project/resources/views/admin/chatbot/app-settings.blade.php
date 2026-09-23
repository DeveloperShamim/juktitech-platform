@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fab fa-facebook',
        'title' => __('Facebook App Settings'),
        'subtitle' => __('Configure Facebook app credentials'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('Chatbot'), 'url' => route('admin.chatbot.packages.index')], ['label' => __('App Settings'), 'url' => null]],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fab fa-facebook"></i> {{ __('App Configuration') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-product-content">
                <div class="row">
            {{-- Main Settings Card --}}
            <div class="col-lg-8">
                <div class="product-description">
                    <div class="body-area">
                        {{-- Token Auto-generated Notice --}}
                        @if($setting->verify_token && !$setting->app_id)
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-magic mr-2"></i> Verify Token Auto-generated!</strong>
                                A secure webhook verify token has been created for you. You can use it or generate a new one.
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Connection Status Alert --}}
                        @if($setting->is_connected)
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-check-circle mr-2"></i> Connected!</strong>
                                Your Facebook App is connected and ready to use.
                                <small class="d-block mt-2">Connected at: {{ $setting->connected_at ? $setting->connected_at->format('d M Y, h:i A') : 'N/A' }}</small>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @elseif($setting->isConfigured())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle mr-2"></i> Configured but not connected</strong>
                                Test your connection below to activate the app.
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @else
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-info-circle mr-2"></i> Setup Required</strong>
                                Please configure your Facebook App credentials below. A verify token has been generated for you.
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Error Log --}}
                        @if($setting->error_log)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle mr-2"></i> Last Error:</strong>
                                {{ $setting->error_log }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.chatbot.app-settings.update') }}" method="POST" id="appSettingsForm">
                            @csrf

                            <div class="row">
                                {{-- App ID --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="app_id">
                                            Facebook App ID <span class="text-danger">*</span>
                                            <a href="https://developers.facebook.com/apps" target="_blank" class="text-primary ml-2">
                                                <i class="fas fa-external-link-alt"></i> Get App ID
                                            </a>
                                        </label>
                                        <input type="text" 
                                               class="input-field" 
                                               id="app_id" 
                                               name="app_id" 
                                               placeholder="Enter your Facebook App ID" 
                                               value="{{ old('app_id', $setting->app_id) }}" 
                                               required>
                                        @error('app_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- App Secret --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="app_secret">
                                            Facebook App Secret <span class="text-danger">*</span>
                                            <small class="text-muted">(Encrypted when saved)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="input-field" 
                                                   id="app_secret" 
                                                   name="app_secret" 
                                                   placeholder="Enter your Facebook App Secret" 
                                                   value="{{ old('app_secret', $setting->app_secret) }}" 
                                                   required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        id="toggleSecret">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('app_secret')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Verify Token --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="verify_token">
                                            Webhook Verify Token
                                            <small class="text-muted">(Used for webhook verification)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="input-field" 
                                                   id="verify_token" 
                                                   name="verify_token" 
                                                   placeholder="Auto-generated or enter custom token" 
                                                   value="{{ old('verify_token', $setting->verify_token) }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" 
                                                        type="button" 
                                                        id="generateTokenBtn">
                                                    <i class="fas fa-sync-alt"></i> Generate
                                                </button>
                                                <button class="btn btn-success" 
                                                        type="button" 
                                                        id="generateTokenManual"
                                                        title="Generate token instantly">
                                                    <i class="fas fa-magic"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Copy this token when setting up Facebook webhook. You can also edit it manually or click Generate.</small>
                                    </div>
                                </div>

                                {{-- Graph API Version --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="graph_version">Graph API Version</label>
                                        <select class="input-field" id="graph_version" name="graph_version">
                                            <option value="v18.0" {{ old('graph_version', $setting->graph_version) == 'v18.0' ? 'selected' : '' }}>v18.0 (Recommended)</option>
                                            <option value="v17.0" {{ old('graph_version', $setting->graph_version) == 'v17.0' ? 'selected' : '' }}>v17.0</option>
                                            <option value="v16.0" {{ old('graph_version', $setting->graph_version) == 'v16.0' ? 'selected' : '' }}>v16.0</option>
                                            <option value="v15.0" {{ old('graph_version', $setting->graph_version) == 'v15.0' ? 'selected' : '' }}>v15.0</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Page Connect OAuth Callback URL (মাস্ট হোয়াইটলিস্ট ইন ফেসবুক) --}}
                                <div class="col-lg-12">
                                    <div class="alert alert-warning border-warning mb-3">
                                        <strong><i class="fas fa-exclamation-triangle mr-2"></i>Valid OAuth Redirect URIs এ এই URL অবশ্যই যোগ করুন</strong><br>
                                        <span class="small">Facebook App → Facebook Login → Settings → Client OAuth Settings → Valid OAuth Redirect URIs</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="page_connect_callback_url">
                                            পেজ কানেক্ট OAuth Callback URL
                                            <small class="text-danger">(Facebook এ এই URL অবশ্যই whitelist করুন)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="input-field" 
                                                   id="page_connect_callback_url" 
                                                   value="{{ route('user.chatbot.facebook.callback') }}" 
                                                   readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('page_connect_callback_url')">
                                                    <i class="fas fa-copy"></i> কপি করুন
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">এই URL টি Facebook App এর <strong>Valid OAuth Redirect URIs</strong> তে যোগ না করলে "redirect URI is not whitelisted" এরর আসবে।</small>
                                    </div>
                                </div>

                                {{-- Redirect URL (অপশনাল - অন্যান্য OAuth এর জন্য) --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="redirect_url">
                                            OAuth Redirect URL (অন্যান্য ব্যবহারের জন্য)
                                            <small class="text-muted">(Optional)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="url" 
                                                   class="input-field" 
                                                   id="redirect_url" 
                                                   name="redirect_url" 
                                                   placeholder="https://yourdomain.com/auth/facebook/callback" 
                                                   value="{{ old('redirect_url', $setting->redirect_url ?? route('user.chatbot.facebook.callback')) }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('redirect_url')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('redirect_url')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Privacy Policy URL --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="privacy_policy_url">
                                            Privacy Policy URL <span class="text-danger">*</span>
                                            <small class="text-muted">(Required by Facebook)</small>
                                        </label>
                                        <input type="url" 
                                               class="input-field" 
                                               id="privacy_policy_url" 
                                               name="privacy_policy_url" 
                                               placeholder="https://yourdomain.com/privacy-policy" 
                                               value="{{ old('privacy_policy_url', $setting->privacy_policy_url ?? url('/privacy-policy')) }}">
                                        <small class="text-muted">Required for Facebook App approval. Must be publicly accessible.</small>
                                        @error('privacy_policy_url')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Terms of Service URL --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="terms_of_service_url">
                                            Terms of Service URL
                                            <small class="text-muted">(Optional but recommended)</small>
                                        </label>
                                        <input type="url" 
                                               class="input-field" 
                                               id="terms_of_service_url" 
                                               name="terms_of_service_url" 
                                               placeholder="https://yourdomain.com/terms-of-service" 
                                               value="{{ old('terms_of_service_url', $setting->terms_of_service_url ?? url('/terms-of-service')) }}">
                                        <small class="text-muted">Recommended for professional apps</small>
                                        @error('terms_of_service_url')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Data Deletion Instructions URL --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="data_deletion_url">
                                            Data Deletion Instructions URL
                                            <small class="text-muted">(Required by Facebook for App Review)</small>
                                        </label>
                                        <input type="url" 
                                               class="input-field" 
                                               id="data_deletion_url" 
                                               name="data_deletion_url" 
                                               placeholder="https://yourdomain.com/data-deletion" 
                                               value="{{ old('data_deletion_url', $setting->data_deletion_url ?? url('/data-deletion')) }}">
                                        <small class="text-muted">Facebook App Settings > App Review > Data Deletion Instructions URL এ এই লিঙ্ক দিতে হবে। ইউজার তার ডেটা ডিলিট করার নির্দেশনা এই পেজে থাকবে।</small>
                                        @error('data_deletion_url')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <button type="submit" class="addProductSubmit-btn">
                                        <i class="fas fa-save mr-2"></i> Save Settings
                                    </button>
                                    
                                    <button type="button" class="btn btn-success ml-2" id="testConnectionBtn">
                                        <i class="fas fa-plug mr-2"></i> Test Connection
                                    </button>

                                    @if($setting->is_connected)
                                        <a href="{{ route('admin.chatbot.app-settings.disconnect') }}" 
                                           class="btn btn-warning ml-2"
                                           onclick="return confirm('Are you sure you want to disconnect?')">
                                            <i class="fas fa-unlink mr-2"></i> Disconnect
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Webhook Info Card --}}
            <div class="col-lg-4">
                {{-- Webhook URL --}}
                <div class="product-description mb-3">
                    <div class="body-area">
                        <h5 class="mb-3">
                            <i class="fas fa-link text-primary mr-2"></i> Webhook URL
                        </h5>
                        <div class="form-group">
                            <label>Callback URL <small class="text-muted">(Copy this to Facebook)</small></label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="webhookUrlDisplay" 
                                       value="{{ $webhookUrl }}" 
                                       readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('webhookUrlDisplay')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Verify Token <small class="text-muted">(For webhook setup)</small></label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="verifyTokenDisplay" 
                                       value="{{ $setting->verify_token }}" 
                                       readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('verifyTokenDisplay')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            @if($setting->verify_token)
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i> Token generated successfully
                                </small>
                            @endif
                        </div>

                        <a href="{{ route('admin.chatbot.webhook-guide') }}" 
                           class="btn btn-info btn-block mt-3">
                            <i class="fas fa-book mr-2"></i> Webhook Setup Guide
                        </a>
                    </div>
                </div>

                {{-- Important URLs --}}
                <div class="product-description mb-3">
                    <div class="body-area">
                        <h5 class="mb-3">
                            <i class="fas fa-globe text-success mr-2"></i> Important URLs
                        </h5>
                        
                        {{-- Privacy Policy --}}
                        <div class="form-group">
                            <label class="small font-weight-bold">Privacy Policy</label>
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="privacyUrlDisplay" 
                                       value="{{ $setting->privacy_policy_url ?? url('/privacy-policy') }}" 
                                       readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('privacyUrlDisplay')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Page Connect Callback (Valid OAuth Redirect URIs এ যোগ করুন) --}}
                        <div class="form-group">
                            <label class="small font-weight-bold text-danger">Valid OAuth Redirect URI</label>
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="redirectUrlDisplay" 
                                       value="{{ route('user.chatbot.facebook.callback') }}" 
                                       readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('redirectUrlDisplay')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Facebook এ এই URL whitelist করুন</small>
                        </div>

                        {{-- Data Deletion URL --}}
                        <div class="form-group">
                            <label class="small font-weight-bold">Data Deletion URL</label>
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="dataDeletionUrlDisplay" 
                                       value="{{ $setting->data_deletion_url ?? url('/data-deletion') }}" 
                                       readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('dataDeletionUrlDisplay')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning alert-sm mt-2 p-2">
                            <small><i class="fas fa-info-circle mr-1"></i> Add these URLs to Facebook App Settings</small>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="product-description">
                    <div class="body-area">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-info mr-2"></i> Connection Info
                        </h5>
                        
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $setting->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>App ID:</strong></td>
                                <td>{{ $setting->app_id ? Str::limit($setting->app_id, 15) : 'Not set' }}</td>
                            </tr>
                            <tr>
                                <td><strong>API Version:</strong></td>
                                <td><code>{{ $setting->graph_version }}</code></td>
                            </tr>
                            @if($setting->connected_at)
                            <tr>
                                <td><strong>Connected:</strong></td>
                                <td>{{ $setting->connected_at->diffForHumans() }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
// Generate random token (client-side backup)
function generateRandomToken(length = 32) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let token = '';
    const array = new Uint8Array(length);
    window.crypto.getRandomValues(array);
    for (let i = 0; i < length; i++) {
        token += chars[array[i] % chars.length];
    }
    return token;
}

// Toggle App Secret visibility
document.getElementById('toggleSecret').addEventListener('click', function() {
    const secretInput = document.getElementById('app_secret');
    const icon = this.querySelector('i');
    
    if (secretInput.type === 'password') {
        secretInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        secretInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Manual Token Generation (Instant, Client-side)
document.getElementById('generateTokenManual').addEventListener('click', function() {
    const newToken = generateRandomToken(32);
    document.getElementById('verify_token').value = newToken;
    document.getElementById('verifyTokenDisplay').value = newToken;
    
    // Visual feedback
    const btn = this;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.remove('btn-success');
    btn.classList.add('btn-success');
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
    }, 1000);
    
    // Show message
    alert('✅ Token generated! Don\'t forget to Save Settings.');
});

// Generate Verify Token
document.getElementById('generateTokenBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    
    fetch('{{ route("admin.chatbot.app-settings.generate-token") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Token data:', data);
        if (data.success) {
            document.getElementById('verify_token').value = data.token;
            document.getElementById('verifyTokenDisplay').value = data.token;
            alert('✅ ' + data.message);
        } else {
            alert('❌ Failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Token generation error:', error);
        alert('❌ Error generating token: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Generate';
    });
});

// Test Connection
document.getElementById('testConnectionBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    
    fetch('{{ route("admin.chatbot.app-settings.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Connection test failed');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plug mr-2"></i> Test Connection';
    });
});

// Copy to Clipboard
function copyToClipboard(elementId) {
    const input = document.getElementById(elementId);
    input.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
    }, 1500);
}
</script>
@endsection

