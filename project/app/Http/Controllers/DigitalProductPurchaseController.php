<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Services\CreativePayService;
use App\Services\DigitalProductPurchaseService;
use App\Services\GoogleDriveService;
use App\Services\PaymentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DigitalProductPurchaseController extends Controller
{
    public function __construct(
        private readonly DigitalProductPurchaseService $purchaseService,
        private readonly GoogleDriveService $drive
    ) {
        $this->middleware('auth')->only(['checkoutForm', 'checkout']);
    }

    public function checkoutForm(Product $product)
    {
        if (!$product->is_digital || !$product->status) {
            abort(404);
        }

        if (!$product->google_drive_file_id) {
            return redirect()->route('frontend.product.details', $product->slug)
                ->with('error', 'এই ডিজিটাল প্রোডাক্ট এখনো সেটআপ করা হয়নি।');
        }

        $user = Auth::user();
        if (!$user->email) {
            return redirect()->route('user.dashboard')
                ->with('error', 'অর্ডার করতে প্রোফাইলে ইমেইল যোগ করুন।');
        }

        return view('frontend.digital_product_checkout', compact('product', 'user'));
    }

    public function checkout(Request $request, Product $product, CreativePayService $pay)
    {
        $rawDomain = trim((string) $request->input('install_domain'));
        if ($rawDomain === '' || mb_strlen($rawDomain) > 255) {
            return redirect()->route('digital-product.checkout', $product)
                ->withInput()
                ->withErrors(['install_domain' => 'যে ডোমেইনে থিম ইনস্টল করবেন সেটি লিখুন।']);
        }

        $domain = License::normalizeHostInput($rawDomain);
        if (!$domain || !preg_match('/^(?=.{4,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i', $domain)) {
            return redirect()->route('digital-product.checkout', $product)
                ->withInput()
                ->withErrors(['install_domain' => 'সঠিক ডোমেইন লিখুন, যেমন: example.com']);
        }

        return $this->initiatePayment($product, $pay, $domain);
    }

    private function initiatePayment(Product $product, CreativePayService $pay, string $domain)
    {
        if (!$product->is_digital || !$product->status || !$product->google_drive_file_id) {
            abort(404);
        }

        $user = Auth::user();
        if (!$user->email) {
            return redirect()->route('user.dashboard')
                ->with('error', 'অর্ডার করতে প্রোফাইলে ইমেইল প্রয়োজন।');
        }

        if (!$pay->isConfigured()) {
            return redirect()->route('frontend.product.details', $product->slug)
                ->with('error', 'পেমেন্ট গেটওয়ে সেটআপ হয়নি। অ্যাডমিনের সাথে যোগাযোগ করুন।');
        }

        if (License::where('domain_name', $domain)->exists()) {
            return redirect()->route('digital-product.checkout', $product)->withInput()->withErrors([
                'install_domain' => 'এই ডোমেইনের জন্য ইতোমধ্যে একটি লাইসেন্স রয়েছে। অন্য ডোমেইন দিন।',
            ]);
        }

        $order = $this->purchaseService->createPendingOrder(
            $product,
            ['name' => $user->name, 'email' => $user->email, 'phone' => $user->phone ?? null],
            $user->id,
            $domain
        );

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email,
            'amount'       => $product->price,
            'metadata'     => [
                'type'       => 'digital_product_purchase',
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'domain'     => $domain,
            ],
            'redirect_url' => route('digital-product.payment.success'),
            'cancel_url'   => route('digital-product.payment.cancel', $product),
            'webhook_url'  => route('digital-product.payment.webhook'),
        ];

        try {
            $result = $pay->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                $order->update(['invoice_id' => $result['invoice_id'] ?? null]);
                return redirect()->away($result['payment_url']);
            }

            return redirect()->route('frontend.product.details', $product->slug)
                ->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে।');
        } catch (\Throwable $e) {
            Log::error('Digital product checkout error', ['error' => $e->getMessage()]);
            return redirect()->route('frontend.product.details', $product->slug)
                ->with('error', 'সার্ভার এরর। আবার চেষ্টা করুন।');
        }
    }

    public function paymentSuccess(Request $request, CreativePayService $pay)
    {
        $invoiceId = $request->get('invoice_id');

        try {
            $result = $pay->verify($invoiceId);
            if (!$result) {
                return $this->thankYou('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ হয়েছে।');
            }

            if (!isset($result['status']) || !in_array($result['status'], ['COMPLETED', 'SUCCESS', 'PENDING'], true)) {
                return $this->thankYou('error', 'পেমেন্ট সম্পন্ন হয়নি।');
            }

            $meta = is_string($result['metadata']) ? json_decode($result['metadata'], true) : $result['metadata'];
            if (($meta['type'] ?? '') !== 'digital_product_purchase') {
                return $this->thankYou('error', 'অবৈধ পেমেন্ট।');
            }

            $order = null;
            if (in_array($result['status'], ['COMPLETED', 'SUCCESS'], true)) {
                $order = $this->purchaseService->markPaid(
                    ProductOrder::findOrFail($meta['order_id']),
                    $invoiceId
                );
            }

            if ($result['status'] === 'PENDING') {
                $order = ProductOrder::with('product')->find($meta['order_id'] ?? null);
                if ($order?->user_id) {
                    Auth::loginUsingId($order->user_id);
                }
                app(PaymentNotificationService::class)->notifyByInvoice($invoiceId, 'pending', 'gateway_return');
                return $this->thankYou(
                    'pending',
                    'আপনার পেমেন্ট যাচাইাধীন (Pending)। Admin approve হলে ডাউনলোড স্বয়ংক্রিয়ভাবে unlock হবে।',
                    $order,
                    $invoiceId
                );
            }

            if (!$order) {
                return $this->thankYou('error', 'অর্ডার প্রসেস করা যায়নি।');
            }

            if ($order->user_id) {
                Auth::loginUsingId($order->user_id);
            }

            return redirect()->route('digital-product.order.success', ['token' => $order->download_token])
                ->with('success', 'পেমেন্ট সফল! লাইসেন্স তৈরি হয়েছে—এখন ফাইল ডাউনলোড করুন।');
        } catch (\Throwable $e) {
            Log::error('Digital product payment success error', ['error' => $e->getMessage()]);
            return $this->thankYou('error', 'পেমেন্ট প্রসেস করতে সমস্যা হয়েছে।');
        }
    }

    public function paymentCancel(Product $product)
    {
        return redirect()->route('frontend.product.details', $product->slug)
            ->with('error', 'পেমেন্ট বাতিল হয়েছে।');
    }

    public function paymentWebhook(Request $request, CreativePayService $pay)
    {
        if (!$pay->validateWebhookRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        if (!isset($data['status'], $data['metadata'])) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $meta = is_string($data['metadata']) ? json_decode($data['metadata'], true) : $data['metadata'];
        if (($meta['type'] ?? '') === 'digital_product_purchase') {
            $isSuccess = in_array($data['status'], ['COMPLETED', 'SUCCESS'], true);
            $this->purchaseService->processWebhookMeta($meta, $data['invoice_id'] ?? null, $isSuccess);
        }

        return response()->json(['message' => 'Success']);
    }

    public function orderSuccessPage(string $token)
    {
        $order = ProductOrder::with(['product', 'license'])->where('download_token', $token)->firstOrFail();

        if ($order->status === 'pending') {
            return $this->thankYou(
                'pending',
                'পেমেন্ট যাচাইাধীন। Confirm হলে download unlock হবে।',
                $order,
                $order->invoice_id
            );
        }

        if (!$order->isPaid()) {
            abort(403, 'অর্ডার paid নয়।');
        }

        return view('frontend.digital_product_success', compact('order'));
    }

    public function downloadPage(string $token)
    {
        $order = ProductOrder::with(['product', 'license'])->where('download_token', $token)->firstOrFail();

        if ($order->status === 'pending') {
            $gs = DB::table('generalsettings')->first();
            return view('frontend.digital_product_thankyou', [
                'type'      => 'pending',
                'message'   => 'পেমেন্ট যাচাইাধীন। Confirm হলে download unlock হবে।',
                'order'     => $order,
                'invoiceId' => $order->invoice_id,
                'gs'        => $gs,
            ]);
        }

        if (!$order->isPaid()) {
            abort(403, 'অর্ডার paid নয়।');
        }

        return view('frontend.digital_product_success', compact('order'));
    }

    public function download(string $token)
    {
        $order = ProductOrder::with('product')->where('download_token', $token)->firstOrFail();

        if (!$order->canDownload()) {
            abort(403, $order->isPaid()
                ? 'ডাউনলোড লিমিট শেষ হয়েছে।'
                : 'পেমেন্ট সম্পন্ন হয়নি।');
        }

        $filename = $order->product->digital_file_name ?: (($order->product->slug ?? 'theme-file') . '.zip');

        // 1. Check local file storage
        $possiblePaths = [
            storage_path('app/digital_products/' . $filename),
            storage_path('app/public/digital_products/' . $filename),
            public_path('assets/files/' . $filename),
        ];

        foreach ($possiblePaths as $localPath) {
            if (file_exists($localPath) && is_readable($localPath)) {
                $order->increment('download_count');
                return response()->download($localPath, $filename);
            }
        }

        // 2. Stream from Google Drive if file ID is configured
        $fileId = $order->product->google_drive_file_id;
        if ($fileId && $this->drive->isConfigured()) {
            try {
                $order->increment('download_count');
                return $this->drive->streamFileResponse($fileId, $filename);
            } catch (\Throwable $e) {
                Log::error('Digital product download error', ['order' => $order->id, 'error' => $e->getMessage()]);
            }
        }

        // 3. Graceful fallback message instead of 404 abort
        return redirect()->route('user.digital-downloads.index')
            ->with('error', 'এই থিমটির ডাউনলোড ফাইল সার্ভারে প্রস্তুত করা হচ্ছে। তাৎক্ষণিক ফাইল পেতে আমাদের সাপোর্টে যোগাযোগ করুন।');
    }

    private function thankYou(string $type, string $message, ?ProductOrder $order = null, ?string $invoiceId = null)
    {
        $gs = DB::table('generalsettings')->first();
        return view('frontend.digital_product_thankyou', compact('gs', 'type', 'message', 'order', 'invoiceId'));
    }

}
