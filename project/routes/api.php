<?php

use Illuminate\Http\Request;
use App\Http\Controllers\User\ChatbotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Facebook Chatbot Webhook Routes
|--------------------------------------------------------------------------
*/

// Facebook Webhook Verification (GET)
Route::get('/webhook/facebook', [ChatbotController::class, 'verifyWebhook'])->name('facebook.webhook.verify');

// Facebook Webhook Handler (POST)
Route::post('/webhook/facebook', [ChatbotController::class, 'handleWebhook'])->name('facebook.webhook.handle');

/*
|--------------------------------------------------------------------------
| CreativePay SMS App API (background mobile app)
|--------------------------------------------------------------------------
*/
Route::post('/creativepay/sms/ping', [\App\Http\Controllers\Api\CreativePaySmsController::class, 'ping']);
Route::post('/creativepay/sms/ingest', [\App\Http\Controllers\Api\CreativePaySmsController::class, 'ingest']);
Route::get('/creativepay/app/sms-logs', [\App\Http\Controllers\Api\CreativePaySmsController::class, 'index']);

Route::post('/creativepay/app/login', [\App\Http\Controllers\Api\CreativePayAppAuthController::class, 'login']);
Route::post('/creativepay/app/qr-login', [\App\Http\Controllers\Api\CreativePayAppAuthController::class, 'loginWithQr']);
Route::post('/creativepay/app/logout', [\App\Http\Controllers\Api\CreativePayAppAuthController::class, 'logout']);
Route::get('/creativepay/app/me', [\App\Http\Controllers\Api\CreativePayAppAuthController::class, 'me']);

Route::get('/creativepay/app/summary', [\App\Http\Controllers\Api\CreativePayAppController::class, 'summary']);
Route::get('/creativepay/app/invoices', [\App\Http\Controllers\Api\CreativePayAppController::class, 'invoices']);
Route::post('/creativepay/app/invoices/{id}/approve', [\App\Http\Controllers\Api\CreativePayAppController::class, 'approve']);

Route::get('/creativepay/app/manage/menu', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'menu']);
Route::get('/creativepay/app/dashboard', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'dashboard']);
Route::get('/creativepay/app/manage/alerts', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'alerts']);
Route::get('/creativepay/app/manage/users', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'users']);

Route::get('/creativepay/app/manage/consultancy', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'consultancyIndex']);
Route::post('/creativepay/app/manage/consultancy', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'consultancyStore']);
Route::put('/creativepay/app/manage/consultancy/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'consultancyUpdate']);
Route::delete('/creativepay/app/manage/consultancy/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'consultancyDelete']);

Route::get('/creativepay/app/manage/contacts', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'contactIndex']);
Route::post('/creativepay/app/manage/contacts', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'contactStore']);
Route::put('/creativepay/app/manage/contacts/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'contactUpdate']);
Route::delete('/creativepay/app/manage/contacts/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'contactDelete']);

Route::get('/creativepay/app/manage/tickets', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketIndex']);
Route::get('/creativepay/app/manage/tickets/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketShow']);
Route::post('/creativepay/app/manage/tickets', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketStore']);
Route::put('/creativepay/app/manage/tickets/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketUpdate']);
Route::post('/creativepay/app/manage/tickets/{id}/reply', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketReply']);
Route::put('/creativepay/app/manage/tickets/{id}/status', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketUpdateStatus']);
Route::delete('/creativepay/app/manage/tickets/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'ticketDelete']);

Route::get('/creativepay/app/manage/domain-extensions', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'domainExtIndex']);
Route::post('/creativepay/app/manage/domain-extensions', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'domainExtStore']);
Route::put('/creativepay/app/manage/domain-extensions/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'domainExtUpdate']);
Route::delete('/creativepay/app/manage/domain-extensions/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'domainExtDelete']);

Route::get('/creativepay/app/manage/user-domains', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userDomainIndex']);
Route::put('/creativepay/app/manage/user-domains/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userDomainUpdate']);
Route::delete('/creativepay/app/manage/user-domains/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userDomainDelete']);

Route::get('/creativepay/app/manage/user-hostings', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userHostingIndex']);
Route::put('/creativepay/app/manage/user-hostings/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userHostingUpdate']);
Route::delete('/creativepay/app/manage/user-hostings/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'userHostingDelete']);
Route::get('/creativepay/app/manage/hosting-plans', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'hostingPlanIndex']);
Route::get('/creativepay/app/manage/hosting-servers', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'hostingServerIndex']);

Route::get('/creativepay/app/manage/licenses', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'licenseIndex']);
Route::get('/creativepay/app/manage/licenses/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'licenseShow']);
Route::post('/creativepay/app/manage/licenses', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'licenseStore']);
Route::put('/creativepay/app/manage/licenses/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'licenseUpdate']);
Route::delete('/creativepay/app/manage/licenses/{id}', [\App\Http\Controllers\Api\CreativePayAppManageController::class, 'licenseDelete']);

Route::get('/creativepay/app/stolen', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'index']);
Route::post('/creativepay/app/stolen/{id}/clear-cache', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'clearCache']);
Route::post('/creativepay/app/stolen/{id}/block', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'toggleBlock']);
Route::post('/creativepay/app/stolen/{id}/kill-switch', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'toggleKillSwitch']);
Route::post('/creativepay/app/stolen/{id}/status', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'toggleStatus']);
Route::delete('/creativepay/app/stolen/{id}', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'destroy']);
Route::delete('/creativepay/app/stolen', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'destroyAll']);
Route::post('/creativepay/app/stolen/delete-selected', [\App\Http\Controllers\Api\CreativePayAppStolenController::class, 'destroySelected']);

/*
|--------------------------------------------------------------------------
| License verification (public API)
|--------------------------------------------------------------------------
| POST /api/verify-license ও POST /api/log-unauthorized — routes/web.php এ
| সংজ্ঞায়িত (CSRF exempt)। api.php এ ডুপ্লিকেট রাখলে এ রুটগুলো আগে ম্যাচ হয়ে
| পুরনো ক্লায়েন্ট ভেঙে যায়।
|--------------------------------------------------------------------------
*/
