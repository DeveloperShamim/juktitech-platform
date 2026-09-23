@extends('layouts.front')

@section('contents')
<div class="container-fluid" style="padding: 40px 20px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Header --}}
            <div class="card shadow-sm mb-4" style="border-left: 4px solid #1877f2;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #1877f2 0%, #0c5fcd 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                            <i class="fab fa-facebook-messenger" style="font-size: 30px; color: white;"></i>
                        </div>
                        <div>
                            <h2 class="mb-1" style="font-weight: 700; color: #1877f2;">
                                <i class="fas fa-book-open"></i> Facebook Chatbot App Setup Guide
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-clock"></i> মাত্র ৫ মিনিটে Facebook Chatbot App তৈরি করুন
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Navigation --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white" style="border-bottom: 2px solid #f0f2f5;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-list"></i> Quick Navigation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="#step1" class="btn btn-outline-primary btn-block" style="padding: 15px;">
                                <i class="fas fa-user-plus"></i> Step 1: Developer Account
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#step2" class="btn btn-outline-primary btn-block" style="padding: 15px;">
                                <i class="fas fa-plus-circle"></i> Step 2: Create App
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#step3" class="btn btn-outline-primary btn-block" style="padding: 15px;">
                                <i class="fas fa-comments"></i> Step 3: Messenger Setup
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#step4" class="btn btn-outline-success btn-block" style="padding: 15px;">
                                <i class="fas fa-key"></i> Step 4: Access Token
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#step5" class="btn btn-outline-success btn-block" style="padding: 15px;">
                                <i class="fas fa-link"></i> Step 5: Webhook
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#step6" class="btn btn-outline-success btn-block" style="padding: 15px;">
                                <i class="fas fa-check-circle"></i> Step 6: Testing
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 1 --}}
            <div class="card shadow-sm mb-4" id="step1">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-user-plus"></i> Step 1: Facebook Developer Account তৈরি
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>সময়:</strong> ২ মিনিট | <strong>প্রয়োজন:</strong> Facebook Account
                    </div>
                    
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>Facebook Developers Portal এ যান:</strong><br>
                            <a href="https://developers.facebook.com/" target="_blank" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-external-link-alt"></i> developers.facebook.com
                            </a>
                        </li>
                        <li class="mb-3">
                            <strong>Login করুন:</strong> আপনার Facebook Account দিয়ে
                        </li>
                        <li class="mb-3">
                            <strong>"Get Started" ক্লিক করুন</strong> এবং Developer হিসেবে Register করুন
                        </li>
                        <li class="mb-3">
                            <strong>Email Verification:</strong> আপনার Email Verify করুন
                        </li>
                        <li>
                            <strong>Phone Verification:</strong> (Optional) Phone Number Verify করুন
                        </li>
                    </ol>

                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle"></i> <strong>Complete!</strong> Developer Account তৈরি হয়ে গেছে
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="card shadow-sm mb-4" id="step2">
                <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-plus-circle"></i> Step 2: Facebook App তৈরি করুন
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>সময়:</strong> ১ মিনিট
                    </div>
                    
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>Navigate:</strong> Dashboard → My Apps → <button class="btn btn-sm btn-primary">Create App</button>
                        </li>
                        <li class="mb-3">
                            <strong>App Type Select করুন:</strong>
                            <div class="mt-2">
                                <span class="badge badge-primary" style="padding: 10px 15px; font-size: 14px;">
                                    <i class="fas fa-check"></i> Business
                                </span> অথবা
                                <span class="badge badge-secondary" style="padding: 10px 15px; font-size: 14px;">
                                    <i class="fas fa-check"></i> Other
                                </span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <strong>App Information দিন:</strong>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">App Name</th>
                                        <td>আপনার Chatbot এর নাম (Example: My Business Bot)</td>
                                    </tr>
                                    <tr>
                                        <th>Contact Email</th>
                                        <td>আপনার Email Address</td>
                                    </tr>
                                    <tr>
                                        <th>Business Account</th>
                                        <td>(Optional) - Skip করতে পারেন</td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        <li class="mb-3">
                            <strong>"Create App"</strong> বাটনে ক্লিক করুন
                        </li>
                        <li>
                            <strong>Security Check:</strong> Complete করুন
                        </li>
                    </ol>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-bookmark"></i> <strong>Important:</strong> App ID টা Note করে রাখুন!
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="card shadow-sm mb-4" id="step3">
                <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-comments"></i> Step 3: Messenger Product Add করুন
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>সময়:</strong> ৩০ সেকেন্ড
                    </div>
                    
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>Navigate:</strong> App Dashboard → Add Products
                        </li>
                        <li class="mb-3">
                            <strong>"Messenger"</strong> খুঁজুন
                        </li>
                        <li>
                            <strong>"Set Up"</strong> বাটনে ক্লিক করুন
                        </li>
                    </ol>

                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle"></i> Messenger Product Successfully Added!
                    </div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="card shadow-sm mb-4" id="step4">
                <div class="card-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-key"></i> Step 4: Page Access Token Generate করুন
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>খুবই গুরুত্বপূর্ণ!</strong> এই Token কখনো Public করবেন না!
                    </div>
                    
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>Navigate:</strong> Messenger → Settings → Access Tokens
                        </li>
                        <li class="mb-3">
                            <strong>"Add or Remove Pages"</strong> ক্লিক করুন
                        </li>
                        <li class="mb-3">
                            <strong>আপনার Facebook Page সিলেক্ট করুন</strong>
                        </li>
                        <li class="mb-3">
                            <strong>Permissions Allow করুন:</strong>
                            <ul class="mt-2">
                                <li>✅ Manage and access Page conversations in Messenger</li>
                                <li>✅ Read content posted on the Page</li>
                                <li>✅ Manage calls to action on the Page</li>
                            </ul>
                        </li>
                        <li class="mb-3">
                            <strong>"Done"</strong> ক্লিক করুন
                        </li>
                        <li>
                            <strong>"Generate Token"</strong> বাটনে ক্লিক করে Token Copy করুন
                        </li>
                    </ol>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-clipboard"></i> <strong>Token Save করুন:</strong> এই Token আপনার Database এ Save করতে হবে
                    </div>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="card shadow-sm mb-4" id="step5">
                <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-link"></i> Step 5: Webhook Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Webhook:</strong> Facebook থেকে আপনার Server এ Message পাঠানোর মাধ্যম
                    </div>
                    
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>Navigate:</strong> Messenger → Settings → Webhooks → Add Callback URL
                        </li>
                        <li class="mb-3">
                            <strong>Webhook Information দিন:</strong>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Callback URL</th>
                                        <td>
                                            <code>https://yourdomain.com/api/facebook/webhook</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Verify Token</th>
                                        <td>
                                            যেকোনো Random String (Example: MySecretToken123)
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </li>
                        <li class="mb-3">
                            <strong>Subscribe to Events:</strong>
                            <ul class="mt-2">
                                <li>✅ messages</li>
                                <li>✅ messaging_postbacks</li>
                                <li>✅ message_deliveries</li>
                                <li>✅ message_reads</li>
                            </ul>
                        </li>
                        <li class="mb-3">
                            <strong>"Verify and Save"</strong> ক্লিক করুন
                        </li>
                        <li>
                            <strong>Subscribe Your Page:</strong> Webhooks → Your Page → Subscribe
                        </li>
                    </ol>

                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle"></i> Webhook Successfully Configured!
                    </div>
                </div>
            </div>

            {{-- Step 6 --}}
            <div class="card shadow-sm mb-4" id="step6">
                <div class="card-header" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Step 6: Testing করুন
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="step-list">
                        <li class="mb-3">
                            <strong>আপনার Facebook Page এ যান</strong>
                        </li>
                        <li class="mb-3">
                            <strong>"Send Message"</strong> বাটনে ক্লিক করুন
                        </li>
                        <li class="mb-3">
                            <strong>"Hello"</strong> লিখে পাঠান
                        </li>
                        <li>
                            <strong>Laravel Log চেক করুন:</strong>
                            <code>storage/logs/laravel-{{ date('Y-m-d') }}.log</code>
                        </li>
                    </ol>

                    <div class="alert alert-success mt-3">
                        <i class="fas fa-thumbs-up"></i> <strong>Congratulations!</strong> আপনার Facebook Chatbot App সফলভাবে Setup হয়ে গেছে!
                    </div>
                </div>
            </div>

            {{-- Resources --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-book"></i> Additional Resources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-link"></i> Important Links:</h6>
                            <ul>
                                <li><a href="https://developers.facebook.com/" target="_blank">Developer Portal</a></li>
                                <li><a href="https://developers.facebook.com/docs/messenger-platform" target="_blank">Messenger Documentation</a></li>
                                <li><a href="https://webhook.site/" target="_blank">Webhook Tester</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-download"></i> Download Guides:</h6>
                            <ul>
                                <li><a href="{{ asset('/') }}FACEBOOK_APP_SETUP_GUIDE.md" target="_blank">Detailed Setup Guide</a></li>
                                <li><a href="{{ asset('/') }}FACEBOOK_APP_QUICK_START.md" target="_blank">Quick Start Guide</a></li>
                                <li><a href="{{ asset('/') }}FACEBOOK_APP_CHECKLIST.md" target="_blank">Setup Checklist</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="text-center mb-4">
                <a href="{{ route('user.chatbot.connect.page') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Back to Chatbot Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

<style>
.step-list {
    font-size: 16px;
    line-height: 1.8;
}

.step-list li {
    margin-bottom: 15px;
    padding-left: 10px;
}

.step-list strong {
    color: #333;
    font-weight: 600;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-radius: 0 !important;
}

.alert {
    border-radius: 8px;
}

.btn-block {
    display: block;
    width: 100%;
    text-align: left;
}

.btn-block i {
    margin-right: 10px;
}

code {
    background: #f4f4f4;
    padding: 4px 8px;
    border-radius: 4px;
    color: #e83e8c;
    font-size: 14px;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
}
</style>
@endsection
