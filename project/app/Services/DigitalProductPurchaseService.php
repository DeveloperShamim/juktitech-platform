<?php

namespace App\Services;

use App\Models\License;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DigitalProductPurchaseService
{
    public function createPendingOrder(
        Product $product,
        array $customer,
        ?int $userId = null,
        ?string $installDomain = null
    ): ProductOrder
    {
        return ProductOrder::create([
            'product_id'      => $product->id,
            'user_id'         => $userId,
            'customer_name'   => $customer['name'],
            'customer_email'  => $customer['email'],
            'customer_phone'  => $customer['phone'] ?? null,
            'install_domain'  => $installDomain,
            'amount'          => $product->price,
            'status'          => 'pending',
            'download_token'  => Str::random(48),
            'download_limit'  => max(1, (int) ($product->download_limit ?: config('digital_product.default_download_limit', 3))),
        ]);
    }

    public function markPaid(ProductOrder $order, ?string $invoiceId): ProductOrder
    {
        return DB::transaction(function () use ($order, $invoiceId) {
            $lockedOrder = ProductOrder::query()->lockForUpdate()->findOrFail($order->id);

            if (!$lockedOrder->isPaid()) {
                $lockedOrder->update([
                    'status'     => 'paid',
                    'invoice_id' => $invoiceId ?: $lockedOrder->invoice_id,
                    'paid_at'    => now(),
                ]);
            }

            if (!$lockedOrder->license_id && $lockedOrder->install_domain && $lockedOrder->user_id) {
                $this->attachOrCreateLicense($lockedOrder);
            }

            return $lockedOrder->fresh(['product', 'license']);
        });
    }

    private function attachOrCreateLicense(ProductOrder $order): void
    {
        $domain = License::normalizeHostInput($order->install_domain);
        $user = User::find($order->user_id);

        if (!$domain || !$user) {
            Log::warning('Theme license generation skipped: missing domain or user', ['order_id' => $order->id]);
            return;
        }

        $existing = License::where('domain_name', $domain)->first();
        if ($existing) {
            if ((int) $existing->user_id === (int) $user->id) {
                $order->update(['license_id' => $existing->id]);
            } else {
                Log::error('Theme license domain belongs to another user', [
                    'order_id' => $order->id,
                    'domain' => $domain,
                    'license_id' => $existing->id,
                ]);
            }
            return;
        }

        try {
            $license = app(LicensePurchaseService::class)->createLicense(
                $user,
                $domain,
                LicensePurchaseService::PUBLIC_VALIDITY
            );
            $order->update(['license_id' => $license->id]);
        } catch (\Illuminate\Database\QueryException $exception) {
            $existing = License::where('domain_name', $domain)->first();
            if ($existing && (int) $existing->user_id === (int) $user->id) {
                $order->update(['license_id' => $existing->id]);
                return;
            }

            throw $exception;
        }
    }

    public function markFailed(ProductOrder $order): void
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'failed']);
        }
    }

    public function processWebhookMeta(array $meta, ?string $invoiceId, bool $isSuccess): ?ProductOrder
    {
        $orderId = $meta['order_id'] ?? null;
        if (!$orderId) {
            return null;
        }

        $order = ProductOrder::find($orderId);
        if (!$order) {
            return null;
        }

        if ($isSuccess) {
            return $this->markPaid($order, $invoiceId);
        }

        $this->markFailed($order);

        return $order;
    }

    public function findByInvoice(string $invoiceId): ?ProductOrder
    {
        return ProductOrder::where('invoice_id', $invoiceId)->first();
    }
}
