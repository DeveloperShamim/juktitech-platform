@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-book',
        'title' => __('Facebook Webhook Setup Guide'),
        'subtitle' => __('Step-by-step webhook configuration'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('App Settings'), 'url' => route('admin.chatbot.app-settings.index')], ['label' => __('Webhook Guide'), 'url' => null]],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-book"></i> {{ __('Setup Instructions') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">
                        {{-- Introduction --}}
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle mr-2"></i> What is a Webhook?</h5>
                            <p class="mb-0">
                                Webhooks allow Facebook to send real-time notifications to your server when users interact 
                                with your chatbot (messages, comments, etc.). This guide will help you set up the webhook 
                                connection between Facebook and your application.
                            </p>
                        </div>

                        {{-- Step-by-step Guide --}}
                        <div class="setup-guide mt-4">
                            <h4 class="mb-4"><i class="fas fa-list-ol text-primary mr-2"></i> Setup Steps</h4>

                            {{-- Step 1 --}}
                            <div class="setup-step mb-4 p-4 border rounded">
                                <h5 class="text-primary">
                                    <span class="badge badge-primary mr-2">1</span>
                                    Create Facebook App
                                </h5>
                                <ol class="mt-3">
                                    <li>Go to <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers</a></li>
                                    <li>Click <strong>"Create App"</strong></li>
                                    <li>Select <strong>"Business"</strong> as the app type</li>
                                    <li>Fill in your app details and create the app</li>
                                    <li>Add <strong>"Messenger"</strong> product to your app</li>
                                </ol>
                            </div>

                            {{-- Step 2 --}}
                            <div class="setup-step mb-4 p-4 border rounded">
                                <h5 class="text-success">
                                    <span class="badge badge-success mr-2">2</span>
                                    Configure App Settings in Admin Panel
                                </h5>
                                <ol class="mt-3">
                                    <li>Copy your <strong>App ID</strong> and <strong>App Secret</strong> from Facebook Developer Dashboard</li>
                                    <li>Go to <a href="{{ route('admin.chatbot.app-settings.index') }}">Facebook App Settings</a> in your admin panel</li>
                                    <li>Paste the App ID and App Secret</li>
                                    <li>Click <strong>"Generate"</strong> to create a Verify Token</li>
                                    <li>Click <strong>"Save Settings"</strong></li>
                                    <li>Click <strong>"Test Connection"</strong> to verify credentials</li>
                                </ol>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Your Webhook Details:</strong>
                                    <table class="table table-sm mt-2 mb-0">
                                        <tr>
                                            <td><strong>Callback URL:</strong></td>
                                            <td>
                                                <code class="text-primary">{{ $webhookUrl }}</code>
                                                <button class="btn btn-sm btn-outline-secondary ml-2" 
                                                        onclick="copyText('{{ $webhookUrl }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Verify Token:</strong></td>
                                            <td>
                                                <code class="text-success">{{ $setting->verify_token ?? 'Not Generated' }}</code>
                                                @if($setting->verify_token)
                                                <button class="btn btn-sm btn-outline-secondary ml-2" 
                                                        onclick="copyText('{{ $setting->verify_token }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- Step 3 --}}
                            <div class="setup-step mb-4 p-4 border rounded">
                                <h5 class="text-warning">
                                    <span class="badge badge-warning mr-2">3</span>
                                    Setup Webhook in Facebook
                                </h5>
                                <ol class="mt-3">
                                    <li>In your Facebook App Dashboard, go to <strong>Messenger > Settings</strong></li>
                                    <li>Scroll to <strong>"Webhooks"</strong> section</li>
                                    <li>Click <strong>"Add Callback URL"</strong></li>
                                    <li>Enter the following:
                                        <ul class="mt-2">
                                            <li><strong>Callback URL:</strong> <code>{{ $webhookUrl }}</code></li>
                                            <li><strong>Verify Token:</strong> <code>{{ $setting->verify_token ?? 'Generate first!' }}</code></li>
                                        </ul>
                                    </li>
                                    <li>Click <strong>"Verify and Save"</strong></li>
                                </ol>

                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Important:</strong> Make sure your website is accessible via HTTPS and publicly reachable. 
                                    Facebook won't be able to verify HTTP URLs or localhost.
                                </div>
                            </div>

                            {{-- Step 4 --}}
                            <div class="setup-step mb-4 p-4 border rounded">
                                <h5 class="text-info">
                                    <span class="badge badge-info mr-2">4</span>
                                    Subscribe to Webhook Fields
                                </h5>
                                <p>After webhook verification, subscribe to the following fields:</p>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6>Messaging Events:</h6>
                                        <ul>
                                            <li><code>messages</code> - Receive messages from users</li>
                                            <li><code>messaging_postbacks</code> - Button clicks</li>
                                            <li><code>message_deliveries</code> - Delivery confirmations</li>
                                            <li><code>message_reads</code> - Read receipts</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Feed Events (Optional):</h6>
                                        <ul>
                                            <li><code>feed</code> - Page post comments</li>
                                            <li><code>mention</code> - Page mentions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 5 --}}
                            <div class="setup-step mb-4 p-4 border rounded">
                                <h5 class="text-danger">
                                    <span class="badge badge-danger mr-2">5</span>
                                    Connect Facebook Page
                                </h5>
                                <ol class="mt-3">
                                    <li>In <strong>Messenger > Settings</strong>, scroll to <strong>"Access Tokens"</strong></li>
                                    <li>Click <strong>"Add or Remove Pages"</strong></li>
                                    <li>Select the Facebook Page you want to connect</li>
                                    <li>Copy the <strong>Page Access Token</strong></li>
                                    <li>Users will use this token to connect their pages in the user panel</li>
                                </ol>
                            </div>

                            {{-- Step 6 --}}
                            <div class="setup-step mb-4 p-4 border rounded bg-light">
                                <h5 class="text-success">
                                    <span class="badge badge-success mr-2">6</span>
                                    Test Your Setup
                                </h5>
                                <ol class="mt-3">
                                    <li>Send a test message to your Facebook Page</li>
                                    <li>Check if the webhook receives the event (check logs if needed)</li>
                                    <li>Verify that auto-replies are working</li>
                                    <li>Test comment automation on a page post</li>
                                </ol>

                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>Success!</strong> Your chatbot is now ready to receive and respond to messages!
                                </div>
                            </div>
                        </div>

                        {{-- Troubleshooting --}}
                        <div class="mt-5">
                            <h4 class="mb-3"><i class="fas fa-tools text-warning mr-2"></i> Troubleshooting</h4>
                            
                            <div class="accordion" id="troubleshootingAccordion">
                                {{-- Issue 1 --}}
                                <div class="card">
                                    <div class="card-header" id="issue1">
                                        <h6 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse1">
                                                <i class="fas fa-question-circle mr-2"></i> Webhook Verification Failed
                                            </button>
                                        </h6>
                                    </div>
                                    <div id="collapse1" class="collapse" data-parent="#troubleshootingAccordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>Make sure your website is using HTTPS</li>
                                                <li>Check that the Verify Token matches exactly</li>
                                                <li>Ensure your server is publicly accessible (not localhost)</li>
                                                <li>Check server logs for incoming requests from Facebook</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Issue 2 --}}
                                <div class="card">
                                    <div class="card-header" id="issue2">
                                        <h6 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse2">
                                                <i class="fas fa-question-circle mr-2"></i> Not Receiving Messages
                                            </button>
                                        </h6>
                                    </div>
                                    <div id="collapse2" class="collapse" data-parent="#troubleshootingAccordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>Verify that webhook fields are subscribed (messages, messaging_postbacks)</li>
                                                <li>Check if the Page is connected to your app</li>
                                                <li>Ensure Page Access Token is valid</li>
                                                <li>Check application logs for errors</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Issue 3 --}}
                                <div class="card">
                                    <div class="card-header" id="issue3">
                                        <h6 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse3">
                                                <i class="fas fa-question-circle mr-2"></i> App Connection Test Failed
                                            </button>
                                        </h6>
                                    </div>
                                    <div id="collapse3" class="collapse" data-parent="#troubleshootingAccordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>Double-check your App ID and App Secret</li>
                                                <li>Make sure the app is not in Development Mode with restrictions</li>
                                                <li>Verify Graph API version compatibility</li>
                                                <li>Check if your server can make outbound HTTPS requests</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-5">
                            <div class="col-lg-12 text-center">
                                <a href="{{ route('admin.chatbot.app-settings.index') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cog mr-2"></i> Go to App Settings
                                </a>
                                <a href="https://developers.facebook.com/docs/messenger-platform/webhooks" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-lg ml-2">
                                    <i class="fas fa-book mr-2"></i> Facebook Documentation
                                </a>
                            </div>
                        </div>
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
function copyText(text) {
    // Create temporary input
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    // Show feedback
    alert('✅ Copied to clipboard!');
}
</script>
@endsection

