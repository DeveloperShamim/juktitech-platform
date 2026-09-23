<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AssetController;
use App\Http\Controllers\MediaController;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App as FacadeApp; // App-এর বদলে FacadeApp ব্যবহার করুন
use App\Http\Controllers\Front\FrontendController;
use App\Http\Controllers\WhyChooseUsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminTicketController; // এটি আপনার web.php এর উপরে যোগ করুন
use App\Http\Controllers\User\UserResetController;
use App\Http\Controllers\Api\LicenseSecurityApiController;
use App\Http\Controllers\User\ClientController;
use App\Http\Controllers\Admin\SmsManageController; // কন্ট্রোলারটি ইমপোর্ট করতে ভুলবেন না
use App\Http\Controllers\User\SmsController;
use App\Http\Controllers\User\SmsApiController;
use App\Http\Controllers\User\HostingController;
use App\Http\Controllers\Admin\HostingManageController;

// Uploaded images — always served via Laravel (works on cPanel without public_html/assets write access).
Route::get('/media/{path}', [MediaController::class, 'show'])->where('path', '.*');

// Legacy static assets fallback.
Route::get('/assets/{path}', [AssetController::class, 'show'])->where('path', '.*');









// SMS Gateway API – strict rate limit: 10 requests per minute (DDoS protection)
Route::group(['prefix' => 'api', 'middleware' => 'throttle:10,1'], function() {
    Route::get('/smsapi', [SmsApiController::class, 'sendSms']);
    Route::post('/smsapi', [SmsApiController::class, 'sendSms']);
    Route::post('/v1/sms/send', [SmsApiController::class, 'sendSms']);
});

// License security API – 60 requests per minute (DDoS protection)
Route::group(['prefix' => 'api', 'middleware' => 'throttle:60,1'], function() {
    Route::post('/log-unauthorized', [LicenseSecurityApiController::class, 'logUnauthorized']);
    Route::get('/cache-signal', [LicenseSecurityApiController::class, 'cacheSignal'])->name('api.cache.signal');
});

// লাইসেন্স / স্টোলেন লগ / ক্যাশ সিগনাল — পাবলিক API ডকুমেন্টেশন (ক্লায়েন্ট ইন্টিগ্রেশন)
Route::get('/docs/license-security-api', function () {
    return view('docs.license_security_api');
})->name('docs.license_security_api');

Route::get('/docs/license-system', function () {
    $gs = DB::table('generalsettings')->first();
    $serverUrl = url('/');
    $hmacSalt  = 'your_secret_salt_key';
    return view('docs.license_system', compact('gs', 'serverUrl', 'hmacSalt'));
})->name('docs.license_system');

Route::get('/docs/license-receiver', function () {
    $gs        = DB::table('generalsettings')->first();
    $serverUrl = url('/');
    $hmacSalt  = 'your_secret_salt_key';
    return view('docs.license_receiver', compact('gs', 'serverUrl', 'hmacSalt'));
})->name('docs.license_receiver');

Route::get('/docs/script-update-system', function () {
    $serverUrl = url('/');

    return view('docs.script_update_system', compact('serverUrl'));
})->name('docs.script_update');

// License verification API – 60 requests per minute (DDoS protection)
Route::post('/api/verify-license', [LicenseController::class, 'verifyLicense'])
    ->middleware('throttle:60,1')
    ->name('api.verify.license');

// Auto-sync: client script নিজে থেকে license key fetch করার জন্য
Route::get('/api/fetch-license', [LicenseController::class, 'fetchLicense'])
    ->middleware('throttle:20,1')
    ->name('api.fetch.license');

// Script Update API – লাইসেন্স + সিগনেচার যাচাই করে আপডেট চেক/ডাউনলোড
Route::post('/api/check-update', [\App\Http\Controllers\Api\CheckUpdateController::class, 'checkUpdate'])
    ->middleware('throttle:60,1')
    ->name('api.check.update');

Route::post('/api/download-update', [\App\Http\Controllers\Api\DownloadUpdateController::class, 'download'])
    ->middleware('throttle:30,1')
    ->name('api.download.update');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/api-documentation', [ClientController::class, 'apiDocumentation'])->name('api.docs');
    Route::post('/generate-api-key', [ClientController::class, 'generateKey'])->name('generate.api_key');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    Route::get('/licenses', [LicenseController::class, 'index'])->name('admin.license.index');
    Route::post('/licenses/store', [LicenseController::class, 'store'])->name('admin.license.store');
    Route::get('/licenses/edit/{id}', [LicenseController::class, 'edit'])->name('admin.license.edit');
    Route::post('/licenses/update/{id}', [LicenseController::class, 'update'])->name('admin.license.update');
    Route::get('/licenses/delete/{id}', [LicenseController::class, 'delete'])->name('admin.license.delete');
    Route::get('/licenses/push/{id}', [LicenseController::class, 'pushToSite'])->name('admin.license.push');
    Route::get('/licenses/setup-guide', [LicenseController::class, 'setupGuide'])->name('admin.license.setup-guide');
    Route::get('/licenses/download-receiver', [LicenseController::class, 'downloadReceiver'])->name('admin.license.download-receiver');

    // Script Updates (Ecommerce / Laravel Script Update Files)
    Route::get('/script-updates', [\App\Http\Controllers\Admin\ScriptUpdateController::class, 'index'])->name('admin.script-updates.index');
    Route::post('/script-updates', [\App\Http\Controllers\Admin\ScriptUpdateController::class, 'store'])->name('admin.script-updates.store');
    Route::get('/script-updates/download/{id}', [\App\Http\Controllers\Admin\ScriptUpdateController::class, 'download'])->name('admin.script-updates.download');
    Route::delete('/script-updates/{id}', [\App\Http\Controllers\Admin\ScriptUpdateController::class, 'destroy'])->name('admin.script-updates.delete');

    Route::get('/license-renewal-orders', [\App\Http\Controllers\Admin\LicenseRenewalOrderController::class, 'index'])->name('admin.license-renewal-orders.index');
    Route::post('/license-renewal-orders/{id}/mark-paid', [\App\Http\Controllers\Admin\LicenseRenewalOrderController::class, 'markAsPaid'])->name('admin.license-renewal-orders.mark-paid');
    Route::post('/license-renewal-orders/{id}/mark-unpaid', [\App\Http\Controllers\Admin\LicenseRenewalOrderController::class, 'markAsUnpaid'])->name('admin.license-renewal-orders.mark-unpaid');

    Route::get('/domain-renewal-orders', [\App\Http\Controllers\Admin\DomainRenewalOrderController::class, 'index'])->name('admin.domain-renewal-orders.index');
    Route::post('/domain-renewal-orders/{id}/mark-paid', [\App\Http\Controllers\Admin\DomainRenewalOrderController::class, 'markAsPaid'])->name('admin.domain-renewal-orders.mark-paid');
    Route::post('/domain-renewal-orders/{id}/mark-unpaid', [\App\Http\Controllers\Admin\DomainRenewalOrderController::class, 'markAsUnpaid'])->name('admin.domain-renewal-orders.mark-unpaid');

    Route::get('/hosting-renewal-orders', [\App\Http\Controllers\Admin\HostingRenewalOrderController::class, 'index'])->name('admin.hosting-renewal-orders.index');
    Route::post('/hosting-renewal-orders/{id}/mark-paid', [\App\Http\Controllers\Admin\HostingRenewalOrderController::class, 'markAsPaid'])->name('admin.hosting-renewal-orders.mark-paid');
    Route::post('/hosting-renewal-orders/{id}/mark-unpaid', [\App\Http\Controllers\Admin\HostingRenewalOrderController::class, 'markAsUnpaid'])->name('admin.hosting-renewal-orders.mark-unpaid');

    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentManageController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{id}/approve', [\App\Http\Controllers\Admin\PaymentManageController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/payments/{id}/reject', [\App\Http\Controllers\Admin\PaymentManageController::class, 'reject'])->name('admin.payments.reject');
    Route::get('/payments/sms-logs', [\App\Http\Controllers\Admin\PaymentManageController::class, 'smsLogs'])->name('admin.payments.sms-logs');
    Route::get('/payments/app-qr-login', [\App\Http\Controllers\Admin\CreativePayAppQrController::class, 'index'])->name('admin.payments.app-qr');
    Route::post('/payments/app-qr-login/generate', [\App\Http\Controllers\Admin\CreativePayAppQrController::class, 'generate'])->name('admin.payments.app-qr.generate');
});


// CreativePay – নিজস্ব পেমেন্ট চেকআউট
Route::get('/pay/{invoice}', [\App\Http\Controllers\Payment\CheckoutController::class, 'show'])->name('payment.checkout.show');
Route::post('/pay/{invoice}', [\App\Http\Controllers\Payment\CheckoutController::class, 'submit'])->name('payment.checkout.submit');
Route::get('/pay/{invoice}/status', [\App\Http\Controllers\Payment\CheckoutController::class, 'status'])->name('payment.checkout.status');
Route::get('/pay/{invoice}/cancel', [\App\Http\Controllers\Payment\CheckoutController::class, 'cancel'])->name('payment.checkout.cancel');

// Chatbot Package Payment Routes
Route::match(['get', 'post'], '/chatbot-payment/success', [\App\Http\Controllers\User\ChatbotPackagePurchaseController::class, 'paymentSuccess'])->name('user.chatbot.payment.success');
Route::match(['get', 'post'], '/chatbot-payment/cancel', [\App\Http\Controllers\User\ChatbotPackagePurchaseController::class, 'paymentCancel'])->name('user.chatbot.payment.cancel');
Route::post('/chatbot-payment/webhook', [\App\Http\Controllers\User\ChatbotPackagePurchaseController::class, 'paymentWebhook'])->name('user.chatbot.payment.webhook');




// ডোমেইন পেমেন্ট কলব্যাক রাউট
Route::match(['get', 'post'], '/domain-payment/success', [FrontendController::class, 'domainPaymentSuccess'])->name('front.domain.payment.success');
Route::get('/domain-payment/cancel', [FrontendController::class, 'domainPaymentCancel'])->name('front.domain.payment.cancel');
Route::post('/domain-payment/webhook', [FrontendController::class, 'domainPaymentWebhook'])->name('front.domain.payment.webhook');

// এসএমএস রিচার্জ পেমেন্ট কলব্যাক (auth বাইরে – গেটওয়ে রিডাইরেক্টে সেশন থাকে না, সাকসেসে আবার লগইন করানো হয়)
Route::match(['get', 'post'], '/user/recharge-success', [\App\Http\Controllers\User\SmsRechargeController::class, 'success'])->name('user.recharge.success');
Route::match(['get', 'post'], '/user/recharge-cancel', [\App\Http\Controllers\User\SmsRechargeController::class, 'cancel'])->name('user.recharge.cancel');

// লাইসেন্স জেনারেশন পেমেন্ট কলব্যাক (auth বাইরে – CreativePay redirect)
Route::match(['get', 'post'], '/user/license/generate/success', [\App\Http\Controllers\User\LicenseGenerateController::class, 'paymentSuccess'])->name('user.license.generate.success');
Route::get('/user/license/generate/cancel', [\App\Http\Controllers\User\LicenseGenerateController::class, 'paymentCancel'])->name('user.license.generate.cancel');
Route::post('/user/license/generate/webhook', [\App\Http\Controllers\User\LicenseGenerateController::class, 'paymentWebhook'])->name('user.license.generate.webhook');
Route::get('/user/license/generate/thankyou', [\App\Http\Controllers\User\LicenseGenerateController::class, 'thankYou'])->name('user.license.generate.thankyou');





Route::group(['prefix' => 'user', 'middleware' => 'auth', 'as' => 'user.'], function() {
    Route::get('/license/generate', [\App\Http\Controllers\User\LicenseGenerateController::class, 'showForm'])->name('license.generate.show');
    Route::post('/license/generate', [\App\Http\Controllers\User\LicenseGenerateController::class, 'generate'])->name('license.generate.submit');
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // সম্পূর্ণ পাথ (Full Path) সহ রাউট
    Route::get('/stolen-reports', [\App\Http\Controllers\Admin\DashboardController::class, 'stolenReports'])->name('admin.stolen.index');
    Route::post('/stolen-reports/clear-cache/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'clearCacheSignal'])->name('admin.stolen.clear-cache');
    Route::post('/stolen-reports/block/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'toggleBlockStolenReport'])->name('admin.stolen.block');
    Route::post('/stolen-reports/kill-switch/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'toggleKillSwitchUnauthorized'])->name('admin.stolen.kill_switch');

    Route::get('/stolen-reports/status/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'statusStolenReport'])->name('admin.stolen.status');
    
    Route::get('/stolen-reports/delete/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteStolenReport'])->name('admin.stolen.delete');
    Route::post('/stolen-reports/delete-all', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteAllStolenReports'])->name('admin.stolen.delete_all');
    Route::post('/stolen-reports/delete-selected', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteSelectedStolenReports'])->name('admin.stolen.delete_selected');
});

/*
|--------------------------------------------------------------------------
| Admin Chatbot Package Management Routes
|--------------------------------------------------------------------------
*/
// অ্যাডমিন ক্রন জব / শিডিউলড টাস্ক (সাইডবার থেকে এক্সেস)
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    Route::get('/cron-jobs', [\App\Http\Controllers\Admin\CronJobController::class, 'index'])->name('admin.cron.index');
    Route::post('/cron-jobs/run', [\App\Http\Controllers\Admin\CronJobController::class, 'run'])->name('admin.cron.run');
});

Route::group(['prefix' => 'admin/chatbot', 'as' => 'admin.chatbot.', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // Chatbot Packages CRUD
    Route::get('/packages', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'create'])->name('packages.create');
    Route::post('/packages/store', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/edit/{id}', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'edit'])->name('packages.edit');
    Route::post('/packages/update/{id}', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'update'])->name('packages.update');
    Route::get('/packages/delete/{id}', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'destroy'])->name('packages.delete');
    Route::get('/packages/toggle-status/{id}', [\App\Http\Controllers\Admin\ChatbotPackageController::class, 'toggleStatus'])->name('packages.toggle');
    
    // Connected Pages & Analytics
    Route::get('/connected-pages', [\App\Http\Controllers\Admin\ChatbotController::class, 'connectedPages'])->name('pages');
    Route::post('/pages/disconnect/{id}', [\App\Http\Controllers\Admin\ChatbotController::class, 'disconnectPage'])->name('pages.disconnect');
    Route::get('/analytics', [\App\Http\Controllers\Admin\ChatbotController::class, 'analytics'])->name('analytics');
    
    // Facebook App Settings
    Route::get('/app-settings', [\App\Http\Controllers\Admin\FacebookAppController::class, 'index'])->name('app-settings.index');
    Route::post('/app-settings/update', [\App\Http\Controllers\Admin\FacebookAppController::class, 'update'])->name('app-settings.update');
    Route::post('/app-settings/test-connection', [\App\Http\Controllers\Admin\FacebookAppController::class, 'testConnection'])->name('app-settings.test');
    Route::post('/app-settings/generate-token', [\App\Http\Controllers\Admin\FacebookAppController::class, 'generateToken'])->name('app-settings.generate-token');
    Route::get('/app-settings/disconnect', [\App\Http\Controllers\Admin\FacebookAppController::class, 'disconnect'])->name('app-settings.disconnect');
    Route::get('/webhook-guide', [\App\Http\Controllers\Admin\FacebookAppController::class, 'webhookGuide'])->name('webhook-guide');
});

// ── Public License Purchase ──────────────────────────────────
Route::get('/license',                 [\App\Http\Controllers\PublicLicensePurchaseController::class, 'show'])->name('public.license.show');
Route::post('/license/buy',            [\App\Http\Controllers\PublicLicensePurchaseController::class, 'checkout'])->name('public.license.checkout');
Route::match(['get','post'], '/license/buy/success',  [\App\Http\Controllers\PublicLicensePurchaseController::class, 'paymentSuccess'])->name('public.license.success');
Route::get('/license/buy/cancel',      [\App\Http\Controllers\PublicLicensePurchaseController::class, 'paymentCancel'])->name('public.license.cancel');
Route::post('/license/buy/webhook',    [\App\Http\Controllers\PublicLicensePurchaseController::class, 'paymentWebhook'])->name('public.license.webhook');

/*
|--------------------------------------------------------------------------
| Static Pages Routes
|--------------------------------------------------------------------------
*/
Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-service', [App\Http\Controllers\PageController::class, 'termsOfService'])->name('terms.service');
Route::get('/data-deletion', [App\Http\Controllers\PageController::class, 'dataDeletion'])->name('data.deletion');
Route::get('/about-us', [App\Http\Controllers\PageController::class, 'aboutUs'])->name('about.us');
Route::get('/contact-us', [App\Http\Controllers\PageController::class, 'contactUs'])->name('contact.us');



Route::prefix('user')->middleware('auth')->group(function() {
    
    // --- SMS Panel Routes ---
    
	// এটি বসান (SMS API Documentation)
    Route::get('/sms-api-docs', [\App\Http\Controllers\User\ClientController::class, 'smsApiDocumentation'])->name('user.sms.api_docs');
	
	Route::get('/sms-panel', [\App\Http\Controllers\User\SmsController::class, 'index'])->name('user.sms.panel');
    Route::post('/sms-send', [\App\Http\Controllers\User\SmsController::class, 'send'])->name('user.sms.send');

    // --- Recharge Routes ---
    Route::get('/recharge', [\App\Http\Controllers\User\SmsRechargeController::class, 'index'])->name('user.recharge.index');
    Route::post('/recharge-process', [\App\Http\Controllers\User\SmsRechargeController::class, 'process'])->name('user.recharge.process');
// এসএমএস পাঠানোর ইতিহাস দেখার রাউট
Route::get('/sms-history', [\App\Http\Controllers\User\SmsController::class, 'smsHistory'])->name('user.sms.history');

});

// --- API Webhook ---
Route::post('/api/recharge/webhook', [\App\Http\Controllers\User\SmsRechargeController::class, 'webhook'])->name('api.recharge.webhook');



Route::group(['prefix' => 'user', 'middleware' => ['auth', 'user']], function () {
    
    // হোস্টিং প্যাকেজ দেখা
    Route::get('/hosting', [HostingController::class, 'index'])->name('user.hosting.index');
	
Route::post('/hosting/password-update/{id}', [ClientController::class, 'updateHostingPassword'])->name('user.hosting.password.update');
Route::get('/hosting/details/{id}', [ClientController::class, 'hostingDetails'])->name('user.hosting.details');
    // হোস্টিং কেনার রিকোয়েস্ট
    Route::post('/hosting/buy', [HostingController::class, 'purchase'])->name('user.hosting.buy');

    // হোস্টিং রিনিউ – উদ্যোক্তা পে দিয়ে পেমেন্ট (সাস্পেন্ড/এক্সপায়ার হোস্টিং)
    Route::get('/hosting/renew/{id}', [HostingController::class, 'renew'])->name('user.hosting.renew');
});

// হোস্টিং রিনিউ পেমেন্ট কলব্যাক (উদ্যোক্তা পে রিডাইরেক্ট/ওয়েবহুক – CSRF এক্সসেপ্ট)
Route::match(['get', 'post'], '/user/hosting/renew/success', [HostingController::class, 'renewPaymentSuccess'])->name('user.hosting.renew.success');
Route::match(['get', 'post'], '/user/hosting/renew/cancel', [HostingController::class, 'renewPaymentCancel'])->name('user.hosting.renew.cancel');
Route::post('/user/hosting/renew/webhook', [HostingController::class, 'renewPaymentWebhook'])->name('user.hosting.renew.webhook');



Route::group(['prefix' => 'admin/manage', 'middleware' => ['auth:admin', 'admin.otp']], function () {
    
    // হোস্টিং ম্যানেজমেন্ট
    Route::get('/user-hostings', [HostingManageController::class, 'userHostings'])->name('admin.user.hostings');
    Route::post('/user-hosting/status-update/{id}', [HostingManageController::class, 'updateHostingStatus'])->name('admin.user.hosting.status');
    Route::get('/user-hosting/delete/{id}', [HostingManageController::class, 'deleteUserHosting'])->name('admin.user.hosting.delete');

    // ডোমেইন ম্যানেজমেন্ট
    Route::get('/user-domains', [HostingManageController::class, 'userDomains'])->name('admin.user.domains');
    Route::get('/user-domains/delete/{id}', [HostingManageController::class, 'deleteUserDomain'])->name('admin.user.domains.delete');
    Route::post('/user-domains/update/{id}', [HostingManageController::class, 'updateUserDomainNS'])->name('admin.user.domains.update');

});

// ১. ইউজারদের জন্য রাউট (এটি ইউজার গ্রুপের ভেতরে থাকবে)
Route::prefix('user')->middleware('auth')->group(function() {
    Route::get('/hosting/login-session/{id}', [\App\Http\Controllers\User\ClientController::class, 'userCpanelLogin'])->name('user.hosting.cp_login');
});

// অ্যাডমিন ইমপার্সোনেশন থেকে ফিরে আসার রাউট (web auth guard দিয়ে, কারণ তখন ইউজার হিসেবে লগইন থাকে)
Route::get('/admin/return-to-admin', 'Admin\StaffController@returnToAdmin')->name('admin.return_to_admin')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Facebook Chatbot Routes
|--------------------------------------------------------------------------
| 
| এখানে Facebook Chatbot এর সব routes আছে
| 
| ⚠️ গুরুত্বপূর্ণ নোট:
| - Webhook routes (GET/POST /webhook/facebook) auth middleware এর বাইরে থাকবে
|   কারণ Facebook server থেকে direct call আসবে
| - বাকি সব chatbot management routes auth + chatbot.subscription middleware এর ভেতরে থাকবে
|
*/

// Facebook Webhook Routes (No Auth Required - Facebook Server Calls)
Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/webhook/facebook', [\App\Http\Controllers\User\ChatbotController::class, 'verifyWebhook'])
        ->name('webhook.verify');
    
    Route::post('/webhook/facebook', [\App\Http\Controllers\User\ChatbotController::class, 'handleWebhook'])
        ->name('webhook.handle');
});

// Chatbot Management Routes (Auth + Subscription Required)
Route::prefix('user/chatbot')->middleware(['auth', 'chatbot.subscription'])->name('user.chatbot.')->group(function () {
    
    // চ্যাটবট ড্যাশবোর্ড ও সেটিংস পেজ
    Route::get('/settings', [\App\Http\Controllers\User\ChatbotController::class, 'botSettings'])
        ->name('settings');
    
    // Facebook Page Connect করা
    Route::get('/connect-page', [\App\Http\Controllers\User\ChatbotController::class, 'connectPage'])
        ->name('connect.page');
    Route::get('/facebook/connect', [\App\Http\Controllers\User\ChatbotController::class, 'redirectToFacebookConnect'])
        ->name('facebook.connect');
    Route::get('/facebook/callback', [\App\Http\Controllers\User\ChatbotController::class, 'facebookCallback'])
        ->name('facebook.callback');
    Route::post('/connect-page', [\App\Http\Controllers\User\ChatbotController::class, 'storeConnectedPage'])
        ->name('connect.page.store');
    Route::delete('/disconnect-page/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'disconnectPage'])
        ->name('disconnect.page');
    Route::post('/set-buttons/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'setButtonsForPage'])
        ->name('set.buttons');
    
    // Facebook App Setup Guide
    Route::get('/facebook-guide', function() {
        return view('user.chatbot.facebook-guide');
    })->name('facebook.guide');
    
    // Bot Reply Setup (Auto-reply management)
    Route::get('/reply-setup', [\App\Http\Controllers\User\ChatbotController::class, 'replySetup'])
        ->name('reply.setup');
    Route::get('/reply/edit/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'editReply'])
        ->name('reply.edit');
    Route::post('/reply/store', [\App\Http\Controllers\User\ChatbotController::class, 'storeReply'])
        ->name('reply.store');
    Route::put('/reply/update/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'updateReply'])
        ->name('reply.update');
    Route::delete('/reply/delete/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'deleteReply'])
        ->name('reply.delete');
    
    // Comment Automation Setup
    Route::get('/comment-automation', [\App\Http\Controllers\User\ChatbotController::class, 'commentAutomation'])
        ->name('comment.automation');
    Route::get('/comment/edit/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'editCommentAutomation'])
        ->name('comment.edit');
    Route::post('/comment/store', [\App\Http\Controllers\User\ChatbotController::class, 'storeCommentAutomation'])
        ->name('comment.store');
    Route::put('/comment/update/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'updateCommentAutomation'])
        ->name('comment.update');
    Route::delete('/comment/delete/{id}', [\App\Http\Controllers\User\ChatbotController::class, 'deleteCommentAutomation'])
        ->name('comment.delete');
    
    // Bot Flow Builder (Drag & Drop)
    Route::get('/bot-flows', [\App\Http\Controllers\User\BotFlowController::class, 'index'])
        ->name('flows.index');
    Route::get('/bot-flows/create', [\App\Http\Controllers\User\BotFlowController::class, 'create'])
        ->name('flows.create');
    Route::post('/bot-flows/store', [\App\Http\Controllers\User\BotFlowController::class, 'store'])
        ->name('flows.store');
    Route::get('/bot-flows/edit/{id}', [\App\Http\Controllers\User\BotFlowController::class, 'edit'])
        ->name('flows.edit');
    Route::put('/bot-flows/update/{id}', [\App\Http\Controllers\User\BotFlowController::class, 'update'])
        ->name('flows.update');
    Route::delete('/bot-flows/delete/{id}', [\App\Http\Controllers\User\BotFlowController::class, 'destroy'])
        ->name('flows.delete');
    Route::post('/bot-flows/toggle/{id}', [\App\Http\Controllers\User\BotFlowController::class, 'toggleStatus'])
        ->name('flows.toggle');

    // কাস্টমার নিজের প্রোডাক্ট (চ্যাটবট ক্যারোজেলে দেখাবে)
    Route::get('/products', [\App\Http\Controllers\User\UserChatbotProductController::class, 'index'])
        ->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\User\UserChatbotProductController::class, 'create'])
        ->name('products.create');
    Route::post('/products/store', [\App\Http\Controllers\User\UserChatbotProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/edit/{id}', [\App\Http\Controllers\User\UserChatbotProductController::class, 'edit'])
        ->name('products.edit');
    Route::put('/products/update/{id}', [\App\Http\Controllers\User\UserChatbotProductController::class, 'update'])
        ->name('products.update');
    Route::delete('/products/delete/{id}', [\App\Http\Controllers\User\UserChatbotProductController::class, 'destroy'])
        ->name('products.delete');
});

// Chatbot Pricing & Purchase (Auth Required)
Route::prefix('user/chatbot')->middleware('auth')->name('user.chatbot.')->group(function () {
    Route::get('/pricing', [\App\Http\Controllers\User\ChatbotPackagePurchaseController::class, 'index'])
        ->name('pricing');
    Route::get('/buy-package/{id}', [\App\Http\Controllers\User\ChatbotPackagePurchaseController::class, 'buy'])
        ->name('buy.package');
});

// (Removed old chatbot payment routes - Now using ChatbotPackagePurchaseController)

// ২. অ্যাডমিনদের জন্য রাউট (এটি আপনার আগের কোডেই আছে)
Route::group(['prefix' => 'admin/manage', 'middleware' => ['auth:admin', 'admin.otp']], function () {
    Route::get('/user-hosting/cpanel-login/{id}', [HostingManageController::class, 'cpanelLogin'])->name('admin.user.hosting.cp_login');
});

Route::group(['prefix' => 'admin/manage', 'middleware' => ['auth:admin', 'admin.otp']], function () {
    
    // ১. সার্ভার ম্যানেজমেন্ট
    Route::get('/servers', [HostingManageController::class, 'serverIndex'])->name('admin.hosting.servers');
    Route::post('/servers/store', [HostingManageController::class, 'serverStore'])->name('admin.hosting.servers.store');
    Route::get('/servers/delete/{id}', [HostingManageController::class, 'serverDelete'])->name('admin.hosting.servers.delete');

    // ২. হোস্টিং প্যাকেজ ম্যানেজমেন্ট
    Route::get('/plans', [HostingManageController::class, 'planIndex'])->name('admin.hosting.plans');
    Route::post('/plans/store', [HostingManageController::class, 'planStore'])->name('admin.hosting.plans.store');
    Route::get('/plans/delete/{id}', [HostingManageController::class, 'planDelete'])->name('admin.hosting.plans.delete');
Route::post('/plans/update/{id}', [HostingManageController::class, 'planUpdate'])->name('admin.hosting.plans.update');
    // ৩. ডোমেইন প্রাইসিং ম্যানেজমেন্ট
    Route::get('/domains', [HostingManageController::class, 'domainIndex'])->name('admin.hosting.domains');
    Route::post('/domains/store', [HostingManageController::class, 'domainStore'])->name('admin.hosting.domains.store');
    Route::get('/domains/delete/{id}', [HostingManageController::class, 'domainDelete'])->name('admin.hosting.domains.delete');
Route::get('/servers/login/{id}', [HostingManageController::class, 'serverLogin'])->name('admin.hosting.servers.login');
// সার্ভার আপডেট করার রাউট (এটিই আপনার মিসিং রাউট)
    Route::post('/servers/update/{id}', [HostingManageController::class, 'serverUpdate'])->name('admin.hosting.servers.update');
	// ডোমেইন প্রাইস আপডেট রাউট
Route::post('/domains/update/{id}', [HostingManageController::class, 'domainUpdate'])->name('admin.hosting.domains.update');
});

Route::get('/admin/pos/download/{order_number}', [PosController::class, 'downloadInvoice'])->name('admin.pos.download');
// --- Frontend / Public Routes ---
// ইনভয়েস লাইভ দেখার রাউট (এটি সবার জন্য উন্মুক্ত থাকবে)
// সংশোধিত রাউট
Route::get('/invoice/live/{order_number}', [\App\Http\Controllers\Front\FrontendController::class, 'liveInvoice'])->name('frontend.invoice.live');
// --- Admin Private Routes (Authenticated) ---
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {

// ১. সকল এসএমএস এর হিস্ট্রি দেখার জন্য
Route::get('/sms/history', [SmsManageController::class, 'history'])->name('admin.sms.history');

// ২. গ্লোবাল সেটিংস (API Key, Sender ID সেটআপ)
Route::get('/sms/settings', [SmsManageController::class, 'settings'])->name('admin.sms.settings');
Route::post('/sms/settings/update', [SmsManageController::class, 'settingsUpdate'])->name('admin.sms.settings.update');

// ৩. নির্দিষ্ট ইউজারের ব্যালেন্স এবং রেট আপডেট করার জন্য
Route::post('/user/update-sms-settings/{id}', [SmsManageController::class, 'updateUserSmsSettings'])->name('admin.user.sms.update');


    // POS & Invoice Management
    Route::get('/pos/create', [PosController::class, 'index'])->name('admin.pos.index'); // পস সেল পেজ
    Route::post('/pos/store', [PosController::class, 'store'])->name('admin.pos.store'); // সেল সেভ করা
    Route::get('/pos/invoices', [PosController::class, 'allInvoices'])->name('admin.pos.invoices'); // সকল ইনভয়েস তালিকা
    Route::post('/pos/due-collect/{id}', [PosController::class, 'collectDue'])->name('admin.pos.due_collect'); // বকেয়া সংগ্রহ
    Route::post('/pos/payment-status/{id}', [PosController::class, 'updatePaymentStatus'])->name('admin.pos.payment_status');

    // Customer Management
    Route::resource('customer', CustomerController::class)->names([
        'index' => 'admin.customer.index',
        'edit' => 'admin.customer.edit',
        'update' => 'admin.customer.update',
        'destroy' => 'admin.customer.destroy',
    ]);

    // Expense Management
    Route::resource('expense', ExpenseController::class)->names([
        'index' => 'admin.expense.index',
        'store' => 'admin.expense.store',
    ]);

    // Fund & Report Management
    Route::get('/report/fund-statement', [ReportController::class, 'fundStatement'])->name('admin.report.fund'); // ফান্ড স্টেটমেন্ট
    Route::post('/report/fund-add', [ReportController::class, 'addMoney'])->name('admin.report.fund_add'); // ফান্ডে টাকা জমা
    Route::post('/report/fund-withdraw', [ReportController::class, 'withdrawMoney'])->name('admin.report.fund_withdraw'); // ফান্ড থেকে উত্তোলন

});



Route::get('/hosting', [App\Http\Controllers\Front\FrontendController::class, 'hosting'])->name('frontend.hosting');








Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // টিকেট তালিকা
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    
    // টিকেট ভিউ এবং চ্যাট
    Route::get('/tickets/view/{id}', [AdminTicketController::class, 'view'])->name('tickets.view');
    
    // অ্যাডমিন রিপ্লাই (মেসেজ সেন্ড)
    Route::post('/tickets/reply/{id}', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    
    // টিকেট ডিলিট
    Route::get('/tickets/delete/{id}', [AdminTicketController::class, 'delete'])->name('tickets.delete');

    // [NEW] স্ট্যাটাস আপডেট (Pending/Replied/Closed)
    Route::post('/tickets/status/{id}', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');
    
    // [NEW] অটোমেটিক নতুন মেসেজ চেক (AJAX Polling)
    Route::get('/tickets/check-new/{id}', [AdminTicketController::class, 'checkNewMessages'])->name('tickets.check_new');
});




Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    Route::resource('team', 'App\Http\Controllers\Admin\TeamController')->names('admin.team');
});
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    Route::resource('counter', 'App\Http\Controllers\Admin\CounterController')->names('admin.counter');
});
Route::resource('testimonial', 'App\Http\Controllers\Admin\TestimonialController')->names('admin.testimonial');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::resource('portfolio-category', 'App\Http\Controllers\Admin\PortfolioCategoryController');
    Route::resource('portfolio', 'App\Http\Controllers\Admin\PortfolioController');
	Route::resource('pricing', 'App\Http\Controllers\Admin\PricingPlanController');
});





// Brand Logo Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {

    Route::resource('brand', 'App\Http\Controllers\Admin\BrandController')->names([
        'index'   => 'admin.brand.index',
        'create'  => 'admin.brand.create',
        'store'   => 'admin.brand.store',
        'edit'    => 'admin.brand.edit',
        'update'  => 'admin.brand.update',
        'destroy' => 'admin.brand.destroy',
    ]);

});



Route::get('admin/why-choose-us', [WhyChooseUsController::class, 'index'])->name('admin.why-choose-us.index');
Route::get('admin/why-choose-us/create', [WhyChooseUsController::class, 'create'])->name('admin.why-choose-us.create');
Route::post('admin/why-choose-us/store', [WhyChooseUsController::class, 'store'])->name('admin.why-choose-us.store');
Route::delete('admin/why-choose-us/{id}/destroy', [WhyChooseUsController::class, 'destroy'])->name('admin.why-choose-us.destroy');
Route::get('admin/why-choose-us/edit/{id}', [WhyChooseUsController::class, 'edit'])->name('admin.why-choose-us.edit');
Route::post('admin/why-choose-us/update/{id}', [WhyChooseUsController::class, 'update'])->name('admin.why-choose-us.update');


// Service Section Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // এখানে except(['show']) যোগ করা হয়েছে যাতে লারাভেল show মেথড না খোঁজে
    Route::resource('service', \App\Http\Controllers\Admin\ServiceController::class, ['except' => ['show']])->names([
        'index'   => 'admin.service.index',
        'create'  => 'admin.service.create',
        'store'   => 'admin.service.store',
        'edit'    => 'admin.service.edit',
        'update'  => 'admin.service.update',
        'destroy' => 'admin.service.destroy',
    ]);

    Route::get('service/edit-text', [\App\Http\Controllers\Admin\ServiceController::class, 'editContent'])->name('admin.service.edit_text');
    Route::post('service/update-text', [\App\Http\Controllers\Admin\ServiceController::class, 'updateContent'])->name('admin.service.update_text');
// ৩. About সেকশন (অনলাইন ব্যবসার সহযোগী) এডিট ও আপডেট রাউট
    Route::get('about-section/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'editAboutSection'])->name('admin.about.edit');
    Route::post('about-section/update', [\App\Http\Controllers\Admin\ServiceController::class, 'updateAboutSection'])->name('admin.about.update');

    Route::get('homepage/sections', [\App\Http\Controllers\Admin\HomepageController::class, 'sections'])->name('admin.homepage.sections');
    Route::post('homepage/sections/{key}', [\App\Http\Controllers\Admin\HomepageController::class, 'updateSection'])->name('admin.homepage.sections.update');

    Route::get('frontend-theme', [\App\Http\Controllers\Admin\FrontendThemeController::class, 'edit'])->name('admin.frontend-theme.edit');
    Route::post('frontend-theme', [\App\Http\Controllers\Admin\FrontendThemeController::class, 'update'])->name('admin.frontend-theme.update');
    Route::post('frontend-theme/reset', [\App\Http\Controllers\Admin\FrontendThemeController::class, 'reset'])->name('admin.frontend-theme.reset');
    

});

// অ্যাডমিন এরিয়া (Consultancy & Contact Management)
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // Consultancy Routes
    Route::get('/consultancy', [\App\Http\Controllers\Admin\ConsultancyController::class, 'index'])->name('admin.consultancy.index');
    Route::get('/consultancy/status/{id}/{status}', [\App\Http\Controllers\Admin\ConsultancyController::class, 'status'])->name('admin.consultancy.status');
    Route::get('/consultancy/delete/{id}', [\App\Http\Controllers\Admin\ConsultancyController::class, 'destroy'])->name('admin.consultancy.delete');
    
    // Contact Message Routes
    Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('admin.contact.index');
    Route::get('/contact/delete/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('admin.contact.delete');

});

// ফ্রন্টএন্ড সাবমিট রাউটস (ফাইলের শেষে যোগ করুন)
Route::post('/free-consultant/store', [\App\Http\Controllers\Admin\ConsultancyController::class, 'storeConsultancy'])->name('frontend.consultancy.store');
Route::post('/contact-submit', [\App\Http\Controllers\Admin\ContactController::class, 'store'])->name('frontend.contact.store');

// সঠিক কোড (এটি কপি করে বসান)
Route::get('/domain-search', [\App\Http\Controllers\Front\FrontendController::class, 'domainSearch'])->name('front.domain.search');
Route::post('/domain-check', [\App\Http\Controllers\Front\FrontendController::class, 'checkDomain'])->name('front.domain.check');


// ডোমেইন অর্ডার রাউট (লগইন করা ইউজারদের জন্য)
Route::middleware(['auth'])->group(function () {
    // অর্ডার কনফিগারেশন পেজ
    Route::get('/domain/configure', [\App\Http\Controllers\Front\FrontendController::class, 'domainConfig'])->name('front.domain.config');

    // অর্ডার সাবমিট (ডাটাবেসে সেভ)
    Route::post('/domain/order-submit', [\App\Http\Controllers\Front\FrontendController::class, 'domainOrderSubmit'])->name('front.domain.order');
});

Route::redirect('admin', 'admin/login');

Route::get('/service', function () {
    return view('frontend.service');
});

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/portfolio', function () {
    return view('frontend.portfolio');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin', 'admin.otp']], function() {
    
    // ১. প্রথমে ডাটাসমূহ আনার জন্য এই AJAX রাউটটি বসান
    Route::get('products/datatables', [\App\Http\Controllers\Admin\ProductController::class, 'datatables'])->name('products.datatables');
    // প্রোডাক্ট বা থিম ম্যানেজমেন্টের জন্য রিসোর্স রাউট (index, create, store, edit, update, destroy সব আছে)
    Route::resource('products', 'App\Http\Controllers\Admin\ProductController');
    
    // Website Orders Management
    Route::get('website-orders', [\App\Http\Controllers\Admin\WebsiteOrderController::class, 'index'])->name('website_orders.index');
    Route::post('website-orders/{id}/status', [\App\Http\Controllers\Admin\WebsiteOrderController::class, 'updateStatus'])->name('website_orders.status');
    Route::delete('website-orders/{id}', [\App\Http\Controllers\Admin\WebsiteOrderController::class, 'destroy'])->name('website_orders.destroy');
});

// ফ্রন্টএন্ড ডিটেইলস পেজ রাউট (স্লাগ সহ)
Route::get('/theme/{slug}', [App\Http\Controllers\Front\FrontendController::class, 'productDetails'])->name('frontend.product.details');
Route::post('/theme/order', [\App\Http\Controllers\Front\ThemeOrderController::class, 'store'])->name('frontend.theme.order');

// Team Route (সংশোধিত)
Route::get('/team', [\App\Http\Controllers\Front\FrontendController::class, 'team'])->name('frontend.team');
// SMS Gateway Page Route
Route::get('/smsgateway', [App\Http\Controllers\Front\FrontendController::class, 'smsgateway'])->name('front.smsgateway');
Route::get('/freeconsultant', function () {
    return view('frontend.freeconsultant');
});
Route::get('/contact', function () {
    return view('frontend.contact');
});

// নাম 'front.pricing' থেকে পরিবর্তন করে 'frontend.pricing' করা হলো
Route::get('/priceing', [\App\Http\Controllers\Front\FrontendController::class, 'pricing'])->name('frontend.pricing');





 



Route::get('/language/{id}', function ($id) {
    $language = DB::table('languages')->find($id);

    if ($language) {
        Session::put('language', $language->id);
        Session::put('locale', $language->code ?? 'en'); // উদাহরণ: 'en' বা 'bn'
        App::setLocale($language->code ?? 'en');
    }

    return redirect()->back();
})->name('front.language.change');



Route::prefix('admin')->group(function(){
    Route::get('/login','Admin\LoginController@loginForm')->name('admin.loginForm');
    Route::post('/login','Admin\LoginController@login')->name('admin.login');

    Route::get('/forgot', 'Admin\LoginController@showForgotForm')->name('admin.forgot');
    Route::post('/forgot', 'Admin\LoginController@forgot')->name('admin.forgot.submit');

    Route::get('/otp-verification', 'Admin\LoginController@showOtpForm')->name('admin.otp.show');
    Route::post('/otp-verification', 'Admin\LoginController@verifyOtp')->middleware('throttle:10,1')->name('admin.otp.verify');
    Route::post('/otp-resend', 'Admin\LoginController@resendOtp')->middleware('throttle:5,1')->name('admin.otp.resend');




    Route::group(['middleware' => 'permissions:languages'], function () {

        //------------Language area--------------
        Route::get('/language/datatables','Admin\LanguageController@datatables')->name('language.datatables');
        Route::get('/add-language','Admin\LanguageController@index')->name('admin.language.index');
        Route::get('/add-language/create','Admin\LanguageController@create')->name('admin.language.create');
        Route::post('/add-language','Admin\LanguageController@store')->name('admin.language.store');
        Route::get('/add-language/edit/{id}','Admin\LanguageController@edit')->name('admin.language.edit');
        Route::post('/add-language/update/{id}','Admin\LanguageController@update')->name('admin.language.update');
        Route::get('/add-language/delete/{id}','Admin\LanguageController@delete')->name('admin.language.delete');
        Route::get('/languages/status/{id}', 'Admin\LanguageController@status')->name('admin.language.status');
        //------------Language area end--------------


        //-------------Admin Language Area--------------
        Route::get('/admin-language/datatables','Admin\AdminLanguageController@datatables')->name('admin_language.datatables');
        Route::get('/admin-add-language','Admin\AdminLanguageController@index')->name('admin.admin_language.index');
        Route::get('/admin-add-language/create','Admin\AdminLanguageController@create')->name('admin.admin_language.create');
        Route::post('/admin-add-language','Admin\AdminLanguageController@store')->name('admin.admin_language.store');
        Route::get('/admin-add-language/edit/{id}','Admin\AdminLanguageController@edit')->name('admin.admin_language.edit');
        Route::post('/admin-add-language/update/{id}','Admin\AdminLanguageController@update')->name('admin.admin_language.update');
        Route::get('/admin-add-language/delete/{id}','Admin\AdminLanguageController@delete')->name('admin.admin_language.delete');
        Route::get('/admin-languages/status/{id}', 'Admin\AdminLanguageController@status')->name('admin.admin_language.status');
        //-------------Admin Language Area--------------
    });





    Route::group(['middleware' => 'permissions:general_settings'], function () {

        //------------General Settings-----------
        Route::post('/generalsettings/update','Admin\GeneralSettingsController@update')->name('admin.generalsettings.update');
        Route::get('/generalsettings/logo','Admin\GeneralSettingsController@logo')->name('admin.generalsettings.logo');
        Route::get('/generalsettings/favicon','Admin\GeneralSettingsController@favicon')->name('admin.generalsettings.favicon');
        Route::get('/generalsettings/website/content','Admin\GeneralSettingsController@websiteContent')->name('admin.generalsettings.websiteContent');
        Route::get('/generalsettings/footer','Admin\GeneralSettingsController@footer')->name('admin.generalsettings.footer');
        Route::get('/generalsettings/error/page','Admin\GeneralSettingsController@errorPage')->name('admin.generalsettings.errorPage');
        Route::get('/generalsettings/popular/tags','Admin\GeneralSettingsController@popularTags')->name('admin.generalsettings.popularTags');

        Route::get('/generalsettings/tawakto/{x}','Admin\GeneralSettingsController@tawkto')->name('admin.generalsettings.tawkto');
        Route::get('/generalsettings/disqus/{x}','Admin\GeneralSettingsController@disqus')->name('admin.generalsettings.disqus');
        Route::get('/generalsettings/smtp/{x}','Admin\GeneralSettingsController@smtp')->name('admin.generalsettings.smtp');
        Route::Get('/generalsettings/capcha/{x}','Admin\GeneralSettingsController@capcha')->name('admin.generalsettings.capcha');
        Route::Get('/generalsettings/emailverfication/{x}','Admin\GeneralSettingsController@emailVerfication')->name('admin.generalsettings.emailverfication');


        //-------------Language Base Logo Area----------------
        Route::get('/language/logo/datatables','Admin\LogoController@datatables')->name('admin.languagelogo.datatables');
        Route::get('/language/logo','Admin\LogoController@index')->name('admin.languagelogo.index');
        Route::get('/language/logo/create','Admin\LogoController@create')->name('admin.languagelogo.create');
        Route::post('/language/logo','Admin\LogoController@store')->name('admin.languagelogo.store');
        Route::get('/language/logo/edit/{id}','Admin\LogoController@edit')->name('admin.languagelogo.edit');
        Route::post('/language/logo/update/{id}','Admin\LogoController@update')->name('admin.languagelogo.update');
        Route::get('/language/logo/delete/{id}','Admin\LogoController@delete')->name('admin.languagelogo.delete');
        //-------------Language Base Logo Area----------------

        //------------General Settings-----------
    });



    Route::group(['middleware' => 'permissions:seo_tools'], function () {

        //------------Seo Management-------------
        Route::post('seo/update','Admin\SeoController@update')->name('seo.update');
        Route::get('seo/google-analytics','Admin\SeoController@googleAnalytics')->name('seo.google.analytics');
        Route::get('seo/meta-keywords','Admin\SeoController@metaKeywords')->name('seo.meta.keywords');
        //------------Seo Management-------------
    });


    Route::group(['middleware' => 'permissions:social_settings'], function () {

        //-------------SocialSettings Manage-----------
        Route::post('social-settings/update','Admin\SocialSettingsController@update')->name('social.settings.update');
        Route::get('social-settings/google','Admin\SocialSettingsController@google')->name('social.settings.google');
        Route::get('social-settings/facebook','Admin\SocialSettingsController@facebook')->name('social.settings.facebook');
        //-------------SocialSettings Manage-----------

        //-------------SocialLink Manage-------------
        Route::get('social-link/datatables','Admin\SocialLinkController@datatables')->name('social.link.datatables');
        Route::get('social-link','Admin\SocialLinkController@index')->name('social.link.index');
        Route::get('social-link/create','Admin\SocialLinkController@create')->name('social.link.create');
        Route::post('social-link','Admin\SocialLinkController@store')->name('social.link.store');
        Route::get('social-link/edit/{id}','Admin\SocialLinkController@edit')->name('social.link.edit');
        Route::post('social-link/update/{id}','Admin\SocialLinkController@update')->name('social.link.update');
        Route::get('social-link/delete/{id}','Admin\SocialLinkController@delete')->name('social.link.delete');
        //-------------SocialLink Manage-------------
    });




    Route::group(['middleware' => 'permissions:emails_settings'], function () {

        //---------------Email Manage-------------------
        Route::get('email/config','Admin\EmailController@config')->name('admin.email.config');
        Route::get('email/group','Admin\EmailController@group')->name('admin.email.group');
        Route::post('email/group','Admin\EmailController@groupmailsend')->name('admin.email.groupmailsend');
        //---------------Email Manage-------------------
    });


    Route::group(['middleware' => 'permissions:newsLetter'], function () {

        //---------------Subscriber----------------------
        Route::get('/subscriber/datatables','Admin\SubscriberController@datatables')->name('admin.subscriber.datatables');
        Route::get('/subscriber','Admin\SubscriberController@index')->name('admin.subscriber.index');
        Route::get('/subscriber/download','Admin\SubscriberController@download')->name('admin.subscriber.download');
        Route::get('/send-mail','Admin\SubscriberController@email')->name('admin.subscriber.email');
        Route::post('/send-mail','Admin\SubscriberController@sendemail')->name('admin.subscriber.sendemail');
        //---------------Subscriber----------------------
    });


    Route::group(['middleware' => 'permissions:role_management'], function () {

        //----------------Role Management-----------------
        Route::get('/role/datatables','Admin\RoleController@datatables')->name('admin.role.datatables');
        Route::get('/role','Admin\RoleController@index')->name('admin.role.index');
        Route::get('/role/create','Admin\RoleController@create')->name('admin.role.create');
        Route::post('/role','Admin\RoleController@store')->name('admin.role.store');
        Route::get('/role/edit/{id}','Admin\RoleController@edit')->name('admin.role.edit');
        Route::post('/role/update/{id}','Admin\RoleController@update')->name('admin.role.update');
        Route::get('/role/update/{id}','Admin\RoleController@delete')->name('admin.role.delete');
        //----------------Role Management-----------------
    });


    Route::group(['middleware' => 'permissions:user_management'], function () {
        //----------------Staff Management----------------
        Route::get('/user/datatables','Admin\StaffController@datatables')->name('admin.staff.datatables');
        Route::get('/user','Admin\StaffController@index')->name('admin.staff.index');
        Route::get('/user/create','Admin\StaffController@create')->name('admin.staff.create');
        Route::post('/user','Admin\StaffController@store')->name('admin.staff.store');
        Route::get('/user/edit/{id}','Admin\StaffController@edit')->name('admin.staff.edit');
        Route::post('/user/update/{id}','Admin\StaffController@update')->name('admin.staff.update');
        Route::get('/user/delete/{id}','Admin\StaffController@delete')->name('admin.staff.delete');
        // অ্যাডমিন থেকে ইউজার অ্যাকাউন্টে লগইন (Impersonation)
        Route::get('/user/login-as/{id}','Admin\StaffController@loginAsUser')->name('admin.staff.login_as');
        //----------------Staff Management----------------
    });

    Route::group(['middleware' => 'permissions:administration_management'], function () {
        //----------------Staff Management----------------
        Route::get('/administator/datatables','Admin\AdministerController@datatables')->name('admin.administator.datatables');
        Route::get('/administator','Admin\AdministerController@index')->name('admin.administator.index');
        Route::get('/administator/create','Admin\AdministerController@create')->name('admin.administator.create');
        Route::post('/administator/store','Admin\AdministerController@store')->name('admin.administator.store');
        Route::get('/administator/edit/{id}','Admin\AdministerController@edit')->name('admin.administator.edit');
        Route::post('/administator/update/{id}','Admin\AdministerController@update')->name('admin.administator.update');
        Route::get('/administator/delete/{id}','Admin\AdministerController@delete')->name('admin.administator.delete');
        //----------------Staff Management----------------
    });





    Route::group(['middleware' => 'permissions:site_map'], function () {
        Route::get('/sitemaps', 'Admin\SiteMapController@all')->name('admin.sitemap.all');
        Route::get('/sitemap.xml', 'Admin\SiteMapController@index')->name('sitemap.index');
        Route::get('/sitemap/categories.xml', 'Admin\SiteMapController@categories')->name('sitemap.categories');
        Route::get('/sitemap/subcategories.xml', 'Admin\SiteMapController@subcategories')->name('sitemap.subcategories');
        Route::get('/sitemap/posts.xml', 'Admin\SiteMapController@posts')->name('sitemap.posts');
    });

    Route::group(['middleware' => 'permissions:font_option'], function () {
        Route::get('/fonts/datatables','Admin\FontController@datatables')->name('admin.fonts.datatables');
        Route::get('/fonts','Admin\FontController@index')->name('fonts.index');
        Route::get('/fonts/status/{id}', 'Admin\FontController@status')->name('admin.fonts.status');
    });

    Route::group(['middleware' => 'permissions:cache_management'], function () {
        //----------------Cache Management-----------------
        Route::get('/cache','Admin\CacheController@clear')->name('admin.cache.clear');
        //----------------Cache Management-----------------
    });

    Route::get('/logout','Admin\DashboardController@logout')->name('admin.logout');
    Route::get('/dashboard','Admin\DashboardController@index')->name('admin.dashboard');

    Route::get('/profile', 'Admin\DashboardController@profile')->name('admin.profile');
    Route::post('/profile', 'Admin\DashboardController@profileUpdate')->name('admin.profile.update');
    Route::get('/password', 'Admin\DashboardController@passwordreset')->name('admin.password');
    Route::post('/password', 'Admin\DashboardController@changepass')->name('admin.password.update');

    Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
    Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
    Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
    Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
    Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');


});

// লাইসেন্স রিনিউ পেমেন্ট কলব্যাক ও সফলতা পেজ – অথেন্টিকেশন ছাড়া
Route::prefix('user')->group(function() {
    Route::get('/license/renew/success', [\App\Http\Controllers\User\ClientController::class, 'licenseRenewPaymentSuccess'])->name('user.license.renew.success');
    Route::post('/license/renew/success', [\App\Http\Controllers\User\ClientController::class, 'licenseRenewPaymentSuccess'])->name('user.license.renew.success.post');
    Route::get('/license/renew/cancel', [\App\Http\Controllers\User\ClientController::class, 'licenseRenewPaymentCancel'])->name('user.license.renew.cancel');
    Route::post('/license/renew/cancel', [\App\Http\Controllers\User\ClientController::class, 'licenseRenewPaymentCancel'])->name('user.license.renew.cancel.post');
    Route::post('/license/renew/webhook', [\App\Http\Controllers\User\ClientController::class, 'licenseRenewPaymentWebhook'])->name('user.license.renew.webhook');
    Route::get('/license/renew/thankyou', [\App\Http\Controllers\User\ClientController::class, 'showLicenseRenewThankYou'])->name('user.license.renew.thankyou');
});

// ডোমেইন রিনিউ পেমেন্ট কলব্যাক ও সফলতা পেজ – অথেন্টিকেশন ছাড়া
Route::prefix('user')->group(function() {
    Route::get('/domain/renew/success', [\App\Http\Controllers\User\ClientController::class, 'domainRenewPaymentSuccess'])->name('user.domain.renew.success');
    Route::post('/domain/renew/success', [\App\Http\Controllers\User\ClientController::class, 'domainRenewPaymentSuccess'])->name('user.domain.renew.success.post');
    Route::get('/domain/renew/cancel', [\App\Http\Controllers\User\ClientController::class, 'domainRenewPaymentCancel'])->name('user.domain.renew.cancel');
    Route::post('/domain/renew/cancel', [\App\Http\Controllers\User\ClientController::class, 'domainRenewPaymentCancel'])->name('user.domain.renew.cancel.post');
    Route::post('/domain/renew/webhook', [\App\Http\Controllers\User\ClientController::class, 'domainRenewPaymentWebhook'])->name('user.domain.renew.webhook');
    Route::get('/domain/renew/thankyou', [\App\Http\Controllers\User\ClientController::class, 'showDomainRenewThankYou'])->name('user.domain.renew.thankyou');
});

Route::prefix('user')->middleware('auth')->group(function() {

    // ... আপনার আগের রাউটগুলো ...
    Route::get('/dashboard', [\App\Http\Controllers\User\ClientController::class, 'index'])->name('user.dashboard');
    Route::get('/my-licenses', [\App\Http\Controllers\User\ClientController::class, 'licenses'])->name('user.licenses');
    Route::get('/digital-downloads', [\App\Http\Controllers\User\UserDigitalDownloadController::class, 'index'])->name('user.digital-downloads.index');

    Route::get('/license/renew/{id}', [\App\Http\Controllers\User\ClientController::class, 'renewLicense'])->name('user.license.renew')->where('id', '[0-9]+');

    // লাইসেন্স ম্যানেজমেন্ট (ইউজার)
    Route::post('/license/update/{id}', [\App\Http\Controllers\User\ClientController::class, 'licenseUpdate'])->name('user.license.update');
    Route::post('/license/toggle-status/{id}', [\App\Http\Controllers\User\ClientController::class, 'licenseToggleStatus'])->name('user.license.toggle.status');
    Route::post('/license/delete/{id}', [\App\Http\Controllers\User\ClientController::class, 'licenseDelete'])->name('user.license.delete');
    Route::get('/support-tickets', [\App\Http\Controllers\User\ClientController::class, 'support'])->name('user.support');
    Route::post('/support-tickets', [\App\Http\Controllers\User\ClientController::class, 'createTicket'])->name('user.support.store');
    
	
	
	// [নতুন রাউট] হোস্টিং লিস্ট দেখার জন্য
    Route::get('/my-hostings', [\App\Http\Controllers\User\ClientController::class, 'hostings'])->name('user.hostings.index');
	// নতুন ডোমেইন লিস্ট রাউট
    Route::get('/my-domains', [\App\Http\Controllers\User\ClientController::class, 'domains'])->name('user.domains.index');
	// [MISSING ROUTE] এই লাইনটিই আপনার মিসিং ছিল, এটি যোগ করুন:
    Route::post('/my-domains/ns-update/{id}', [\App\Http\Controllers\User\ClientController::class, 'updateNameservers'])->name('user.domain.ns.update');
	// ডোমেইন রিনিউ – এক্সপায়ারড ডোমেইনের জন্য (রিনিউ রাউট thankyou এর পরে থাকলে /thankyou সংখ্যা হিসেবে ম্যাচ করবে না)
    Route::get('/domain/renew/{id}', [\App\Http\Controllers\User\ClientController::class, 'renewDomain'])->name('user.domain.renew')->where('id', '[0-9]+');
	
    // বিস্তারিত ও রিপ্লাই রাউট
    Route::get('/support-tickets/view/{id}', [\App\Http\Controllers\User\ClientController::class, 'viewTicket'])->name('user.support.view');
    Route::post('/support-tickets/reply/{id}', [\App\Http\Controllers\User\ClientController::class, 'replyTicket'])->name('user.support.reply');

    // =================================================================
    // [MISSING] এই নতুন রাউটটি যোগ করুন (এটি ছাড়া অটো চেক কাজ করবে না)
    // =================================================================
    Route::get('/support-tickets/check-new/{id}', [\App\Http\Controllers\User\ClientController::class, 'checkNewMessages'])->name('user.support.check_new');


    // প্রোফাইল ও লগআউট
    Route::get('/profile', [\App\Http\Controllers\User\ClientController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [\App\Http\Controllers\User\ClientController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/logout', [\App\Http\Controllers\User\ClientController::class, 'logout'])->name('user.logout');
});

Route::get('/theme/{product:slug}/buy', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'checkoutForm'])->name('digital-product.checkout');
Route::post('/theme/{product:slug}/buy', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'checkout'])->name('digital-product.checkout.submit');
Route::match(['get', 'post'], '/digital-product/payment/success', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'paymentSuccess'])->name('digital-product.payment.success');
Route::get('/digital-product/payment/cancel/{product:slug}', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'paymentCancel'])->name('digital-product.payment.cancel');
Route::post('/digital-product/payment/webhook', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'paymentWebhook'])->name('digital-product.payment.webhook');
Route::get('/digital-download/{token}/success', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'orderSuccessPage'])->name('digital-product.order.success');
Route::get('/digital-download/{token}', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'downloadPage'])->name('digital-product.download.page');
Route::get('/digital-download/{token}/file', [\App\Http\Controllers\DigitalProductPurchaseController::class, 'download'])->name('digital-product.download');

Route::get('/','Front\FrontendController@index')->name('frontend.index');
// ২. আপনার থিম সিস্টেমের রাউট (এখানে বসান)
Route::get('/themes', function () {
    return view('frontend.themes');
})->name('frontend.themes');

Route::get('/hosting/{category}', [App\Http\Controllers\Front\FrontendController::class, 'hostingByCategory'])->name('front.hosting.category');

Route::post('the/genius/ocean/2441139', 'Front\FrontendController@subscription');
Route::get('finalize', 'Front\FrontendController@finalize');

Route::post('/subscribers','Front\SubscriberController@store')->name('front.subscribers.store');

Route::get('/contact/refresh_code','Front\FrontendController@refresh_code');
// লগইন পেজ রাউট (GET)
Route::get('/login', [\App\Http\Controllers\Front\RegisterController::class, 'LogReg'])->name('login');

// ইউজার পাসওয়ার্ড রিসেট (OTP সিস্টেম)
Route::group(['prefix' => 'user-reset'], function() {
    Route::get('/request', [UserResetController::class, 'showPhoneForm'])->name('user.forgot.password');
    Route::post('/send-otp', [UserResetController::class, 'sendOtp'])->name('user.forgot.send');
    Route::get('/otp', [UserResetController::class, 'showOtpForm'])->name('user.otp.verify');
    Route::post('/otp-check', [UserResetController::class, 'verifyOtp'])->name('user.otp.check');
    Route::get('/confirm', [UserResetController::class, 'showResetForm'])->name('user.password.reset');
    Route::post('/update', [UserResetController::class, 'updatePassword'])->name('user.password.update');
});





Route::post('/register','Front\RegisterController@register')->name('front.register');
Route::get('/register/verify/{token}', 'Front\RegisterController@token')->name('user.register.token');
// লগইন সাবমিট রাউট (POST)
Route::post('/login', [\App\Http\Controllers\Front\LoginController::class, 'login'])->name('front.login');
Route::get('/logout','Front\LoginController@logout')->name('front.logout');


Route::get('auth/{provider}', 'Front\SocialRegisterController@redirectToProvider')->name('social.provider');
Route::get('auth/{provider}/callback', 'Front\SocialRegisterController@handleProviderCallback');



Route::get('/change/language/{id}','Front\FrontendController@language')->name('front.language');