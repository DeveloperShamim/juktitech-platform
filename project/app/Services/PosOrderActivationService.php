<?php

namespace App\Services;

use App\Models\ChatbotPackage;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\DomainRenewalOrder;
use App\Models\HostingRenewalOrder;
use App\Models\LicenseRenewalOrder;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\ProductOrder;
use App\Models\User;
use App\Models\UserDomain;
use App\Models\UserHosting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosOrderActivationService
{
    /**
     * CreativePay invoice দিয়ে orders টেবিলের অর্ডার খুঁজে fully paid + সার্ভিস activate।
     */
    public function syncOrderAndActivate(string $invoiceId): bool
    {
        $order = $this->findOrderByInvoice($invoiceId);

        if ($order) {
            if ($order->due_amount > 0) {
                $order->update([
                    'paid_amount' => $order->total_amount,
                    'due_amount'  => 0,
                ]);
                $order->refresh();
            }

            return $this->activateIfFullyPaid($order);
        }

        return $this->activateRenewalFromPayment($invoiceId);
    }

    public function findOrderByInvoice(string $invoiceId): ?Order
    {
        $order = Order::where('support_note', 'LIKE', '%' . $invoiceId . '%')->first();
        if ($order) {
            return $order;
        }

        return Order::whereRaw(
            'JSON_UNQUOTE(JSON_EXTRACT(support_note, "$.txn_id")) = ?',
            [$invoiceId]
        )->first();
    }

    public function activateIfFullyPaid(Order $order, ?User $user = null): bool
    {
        if ($order->due_amount > 0) {
            return false;
        }

        $this->markSupportNoteCompleted($order);

        if ($this->isLicenseGenerationOrder($order)) {
            $this->generateLicenseFromPOS($order, $user);
        } elseif ($this->isDomainHostingOrder($order)) {
            $this->activateDomainHostingFromPOS($order);
        } elseif ($this->isSmsRechargeOrder($order)) {
            $this->processSmsRecharge($order);
        } elseif ($this->isChatbotPackageOrder($order)) {
            $this->activateChatbotPackageFromPOS($order, $user);
        }

        return true;
    }

    protected function markSupportNoteCompleted(Order $order): void
    {
        $note = $order->support_note ?? '';
        if ($note === '') {
            return;
        }

        if (str_contains($note, 'TXNID:') && str_contains($note, 'Status:')) {
            $order->update([
                'support_note' => preg_replace('/Status:\s*[A-Z]+/i', 'Status: COMPLETED', $note),
                'status'       => 'Active',
            ]);
            return;
        }

        $meta = json_decode($note, true);
        if (is_array($meta)) {
            $meta['status'] = 'COMPLETED';
            $order->update([
                'support_note' => json_encode($meta),
                'status'       => 'Active',
            ]);
        }
    }

    protected function activateRenewalFromPayment(string $invoiceId): bool
    {
        $txn = PaymentTransaction::where('invoice_id', $invoiceId)->first();
        if (!$txn || !$txn->isPaid()) {
            return false;
        }

        $meta = is_array($txn->metadata) ? $txn->metadata : [];
        $type = $meta['type'] ?? null;

        if ($type === 'license_renewal' && !empty($meta['license_id'])) {
            $order = LicenseRenewalOrder::where('license_id', $meta['license_id'])
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order && $order->markAsPaidAndRenewLicense()) {
                $order->update(['invoice_id' => $invoiceId]);
                return true;
            }
        }

        if ($type === 'hosting_renewal' && !empty($meta['hosting_id'])) {
            $order = HostingRenewalOrder::where('user_hosting_id', $meta['hosting_id'])
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order && $order->markAsPaidAndRenewHosting()) {
                $order->update(['invoice_id' => $invoiceId]);
                $hosting = UserHosting::with('server')->find($meta['hosting_id']);
                if ($hosting && $hosting->server && $hosting->username) {
                    \App\Services\WhmService::unsuspendAccount($hosting->server, $hosting->username);
                }
                return true;
            }
        }

        if ($type === 'domain_renewal' && !empty($meta['domain_id'])) {
            $order = DomainRenewalOrder::where('user_domain_id', $meta['domain_id'])
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order && method_exists($order, 'markAsPaidAndRenewDomain')) {
                $order->update(['invoice_id' => $invoiceId]);
                return $order->markAsPaidAndRenewDomain();
            }
        }

        if ($type === 'digital_product_purchase' && !empty($meta['order_id'])) {
            $order = ProductOrder::find($meta['order_id']);
            if ($order && $order->status === 'pending') {
                app(DigitalProductPurchaseService::class)->markPaid($order, $invoiceId);
                return true;
            }
            if ($order && $order->isPaid()) {
                return true;
            }
        }

        return false;
    }

    protected function isDomainHostingOrder(Order $order): bool
    {
        if (stripos($order->description ?? '', 'Domain Reg:') !== false
            || stripos($order->description ?? '', 'Hosting Order for:') !== false) {
            return true;
        }

        $meta = is_string($order->support_note ?? '') ? json_decode($order->support_note, true) : ($order->support_note ?? []);

        return is_array($meta) && isset($meta['domain']) && isset($meta['user_id']);
    }

    protected function isChatbotPackageOrder(Order $order): bool
    {
        return stripos($order->description ?? '', 'Chatbot') !== false
            || stripos($order->support_note ?? '', 'chatbot') !== false;
    }

    protected function isLicenseGenerationOrder(Order $order): bool
    {
        if (stripos($order->description ?? '', 'License Generation:') !== false) {
            return true;
        }

        $meta = is_string($order->support_note ?? '') ? json_decode($order->support_note, true) : ($order->support_note ?? []);

        return is_array($meta) && isset($meta['type'])
            && in_array($meta['type'], ['license_generation', 'public_license_purchase'], true);
    }

    protected function isSmsRechargeOrder(Order $order): bool
    {
        return stripos($order->description ?? '', 'SMS') !== false
            || stripos($order->description ?? '', 'Recharge') !== false;
    }

    protected function activateDomainHostingFromPOS(Order $order): void
    {
        try {
            $order->update(['status' => 'Active']);
            UserDomain::where('order_id', $order->id)->update(['status' => 'Active']);

            $userHosting = UserHosting::where('order_id', $order->id)->first();
            if ($userHosting) {
                $userHosting->update(['status' => 'Active']);
                $meta = json_decode($order->support_note, true);
                if (!empty($meta['plan_id']) && empty($userHosting->username)) {
                    app(\App\Http\Controllers\User\HostingController::class)->activateHosting($order->id);
                }
            }

            Log::info("POS Domain/Hosting activated: Order {$order->order_number}");
        } catch (\Exception $e) {
            Log::error('POS Domain/Hosting activation fail: ' . $e->getMessage());
        }
    }

    protected function activateChatbotPackageFromPOS(Order $order, ?User $user = null): void
    {
        try {
            $user = $this->resolveUser($order, $user);
            if (!$user) {
                Log::warning("POS Chatbot activation: User not found for order {$order->order_number}");
                return;
            }

            $meta = is_string($order->support_note ?? '') ? json_decode($order->support_note, true) : ($order->support_note ?? []);
            $packageId = is_array($meta) ? ($meta['chatbot_package_id'] ?? null) : null;

            if (!$packageId && stripos($order->support_note ?? '', 'chatbot_package') !== false) {
                $packageName = str_replace('Chatbot Package: ', '', $order->description ?? '');
                $package = ChatbotPackage::where('name', $packageName)->first();
                $packageId = $package?->id;
            }

            if (!$packageId) {
                Log::warning("POS Chatbot activation: Package ID not found for order {$order->order_number}");
                return;
            }

            $package = ChatbotPackage::find($packageId);
            if (!$package) {
                return;
            }

            $newExpiry = ($user->plan_expiry && Carbon::parse($user->plan_expiry)->isFuture())
                ? Carbon::parse($user->plan_expiry)->addDays($package->duration_days)
                : Carbon::now()->addDays($package->duration_days);

            $user->update([
                'chatbot_package_id'    => $package->id,
                'active_plan'           => $package->name,
                'plan_expiry'           => $newExpiry,
                'connected_pages_limit' => $package->page_limit,
                'monthly_message_limit' => $package->message_limit,
                'updated_at'            => now(),
            ]);

            Log::info("POS Chatbot activated: User {$user->id} -> Package {$packageId}");
        } catch (\Exception $e) {
            Log::error('POS Chatbot activation error: ' . $e->getMessage());
        }
    }

    protected function generateLicenseFromPOS(Order $order, ?User $user = null): void
    {
        try {
            $meta = is_string($order->support_note ?? '') ? json_decode($order->support_note, true) : ($order->support_note ?? []);

            if (!is_array($meta) || empty($meta['domain_name']) || empty($meta['user_id'])) {
                Log::warning("POS License Generate: Missing metadata in order {$order->order_number}");
                return;
            }

            $domainName = $meta['domain_name'];
            $validity   = (int) ($meta['validity'] ?? 1);
            $userId     = (int) $meta['user_id'];
            $licenseId  = $meta['license_id'] ?? null;

            if ($licenseId) {
                $existingLicense = \App\Models\License::find($licenseId);
                if ($existingLicense && (int) $existingLicense->status === 1) {
                    $order->update(['status' => 'Active']);
                    return;
                }
            }

            $domainLicense = \App\Models\License::where('domain_name', $domainName)->first();
            if ($domainLicense) {
                $order->update(['status' => 'Active']);
                return;
            }

            $licenseUser = $user ?? User::find($userId);
            if (!$licenseUser) {
                return;
            }

            $processor = app(LicensePurchaseService::class);
            $license   = $processor->createLicense($licenseUser, $domainName, $validity);

            $meta['license_id'] = $license->id;
            $order->update([
                'status'       => 'Active',
                'support_note' => json_encode($meta),
            ]);

            Log::info("POS License Generate: License created for domain {$domainName}");
        } catch (\Exception $e) {
            Log::error('POS License Generate Error: ' . $e->getMessage());
        }
    }

    protected function processSmsRecharge(Order $order): void
    {
        try {
            if (!$order->customer) {
                return;
            }

            $user = $this->resolveUser($order);
            if (!$user) {
                return;
            }

            if (Deposit::where('trx_id', $order->order_number)->exists()) {
                return;
            }

            $user->increment('wallet_balance', $order->total_amount);

            Deposit::create([
                'user_id' => $user->id,
                'amount'  => $order->total_amount,
                'method'  => 'POS / Admin',
                'trx_id'  => $order->order_number,
                'status'  => 'completed',
            ]);

            if (!$order->customer->user_id) {
                $order->customer->user_id = $user->id;
                $order->customer->save();
            }

            Log::info("POS SMS Recharge Success: User {$user->id} -> Amount {$order->total_amount}");
        } catch (\Exception $e) {
            Log::error('POS SMS Recharge Fail: ' . $e->getMessage());
        }
    }

    protected function resolveUser(Order $order, ?User $user = null): ?User
    {
        if ($user) {
            return $user;
        }

        if (!$order->customer) {
            return null;
        }

        if ($order->customer->user_id) {
            return User::find($order->customer->user_id);
        }

        $user = User::where('phone', $order->customer->phone)->first();
        if ($user && !$order->customer->user_id) {
            $order->customer->user_id = $user->id;
            $order->customer->save();
        }

        return $user;
    }
}
