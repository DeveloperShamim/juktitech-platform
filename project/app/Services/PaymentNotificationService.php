<?php

namespace App\Services;

use App\Classes\GeniusMailer;
use App\Models\ChatbotPackage;
use App\Models\GeneralSettings;
use App\Models\HostingPlan;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymentNotificationService
{
    public function notifyPaid(PaymentTransaction $txn, string $source = 'auto'): void
    {
        $this->sendOnce($txn, 'paid', function () use ($txn, $source) {
            $this->deliver($txn, 'paid', $source);
        });
    }

    public function notifyPending(PaymentTransaction $txn, string $source = 'auto'): void
    {
        $this->sendOnce($txn, 'pending', function () use ($txn, $source) {
            $this->deliver($txn, 'pending', $source);
        });
    }

    public function notifyFailed(PaymentTransaction $txn, string $reason = ''): void
    {
        $this->sendOnce($txn, 'failed', function () use ($txn, $reason) {
            $this->deliver($txn, 'failed', $reason);
        });
    }

    public function notifyByInvoice(string $invoiceId, string $status, string $source = 'auto'): void
    {
        $txn = PaymentTransaction::where('invoice_id', $invoiceId)->first();
        if (!$txn) {
            return;
        }

        match ($status) {
            'paid', 'completed' => $this->notifyPaid($txn, $source),
            'pending'           => $this->notifyPending($txn, $source),
            'failed'            => $this->notifyFailed($txn, $source),
            default             => null,
        };
    }

    private function sendOnce(PaymentTransaction $txn, string $event, callable $sender): void
    {
        $key = 'payment_email:' . $txn->id . ':' . $event;
        if (!Cache::add($key, 1, now()->addDay())) {
            return;
        }

        try {
            $sender();
        } catch (\Throwable $e) {
            Cache::forget($key);
            Log::error('Payment email failed', [
                'txn'   => $txn->id,
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function deliver(PaymentTransaction $txn, string $status, string $source = ''): void
    {
        $email = $this->resolveEmail($txn);
        if (!$email) {
            return;
        }

        $gs = GeneralSettings::first();
        $site = $gs->title ?? config('app.name', 'Creative Design');
        $meta = is_array($txn->metadata) ? $txn->metadata : [];
        $type = $this->resolveType($meta);
        $details = $this->buildDetails($txn, $meta, $type, $status, $source);

        $subject = match ($status) {
            'paid'    => "✅ পেমেন্ট সফল — {$details['title']} | {$site}",
            'pending' => "⏳ পেমেন্ট Pending — {$details['title']} | {$site}",
            default   => "❌ পেমেন্ট ব্যর্থ — {$details['title']} | {$site}",
        };

        $body = $this->wrapHtml($site, $subject, $details, $status);

        (new GeniusMailer())->sendCustomMail([
            'to'      => $email,
            'subject' => $subject,
            'body'    => $body,
        ]);
    }

    private function resolveEmail(PaymentTransaction $txn): ?string
    {
        $email = trim((string) $txn->email);
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $meta = is_array($txn->metadata) ? $txn->metadata : [];
        $userId = $meta['user_id'] ?? null;
        if ($userId) {
            $user = User::find($userId);
            if ($user?->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                return $user->email;
            }
        }

        return null;
    }

    private function resolveType(array $meta): string
    {
        if (!empty($meta['type'])) {
            return (string) $meta['type'];
        }
        if (!empty($meta['domain']) && !empty($meta['plan_id'])) {
            return 'domain_hosting_purchase';
        }

        return 'general';
    }

    private function buildDetails(
        PaymentTransaction $txn,
        array $meta,
        string $type,
        string $status,
        string $source
    ): array {
        $rows = [
            ['label' => 'Invoice ID', 'value' => $txn->invoice_id],
            ['label' => 'পরিমাণ', 'value' => '৳ ' . number_format((float) $txn->amount, 0)],
            ['label' => 'স্ট্যাটাস', 'value' => match ($status) {
                'paid'    => 'Paid / Completed',
                'pending' => 'Pending (যাচাইাধীন)',
                default   => 'Failed / Cancelled',
            }],
        ];

        if ($txn->payment_method) {
            $rows[] = ['label' => 'Payment Method', 'value' => ucfirst($txn->payment_method)];
        }
        if ($txn->transaction_id) {
            $rows[] = ['label' => 'TrxID', 'value' => $txn->transaction_id];
        }
        if ($txn->sender_number) {
            $rows[] = ['label' => 'Sender Number', 'value' => $txn->sender_number];
        }

        $title = 'Payment';
        $message = '';
        $cta = null;

        switch ($type) {
            case 'digital_product_purchase':
                $order = !empty($meta['order_id']) ? ProductOrder::with('product')->find($meta['order_id']) : null;
                $product = $order?->product ?: (!empty($meta['product_id']) ? Product::find($meta['product_id']) : null);
                $title = 'ডিজিটাল প্রোডাক্ট';
                $rows[] = ['label' => 'প্রোডাক্ট', 'value' => $product->name ?? 'Digital Product'];
                if ($order) {
                    $rows[] = ['label' => 'অর্ডার নং', 'value' => '#' . str_pad($order->id, 6, '0', STR_PAD_LEFT)];
                }
                if ($status === 'paid') {
                    $message = $source === 'admin_approved'
                        ? 'Admin আপনার পেমেন্ট approve করেছেন। এখন login করে ডাউনলোড করুন।'
                        : 'পেমেন্ট verified হয়েছে। Login করে ডিজিটাল ডাউনলোড page থেকে file নিন।';
                    $cta = ['label' => 'ডিজিটাল ডাউনলোড (Login Required)', 'url' => route('user.digital-downloads.index')];
                } elseif ($status === 'pending') {
                    $message = 'পেমেন্ট গ্রহণ হয়েছে, যাচাই চলছে। Admin approve হলে email/update পাবেন এবং download unlock হবে।';
                    $cta = ['label' => 'Dashboard', 'url' => route('user.dashboard')];
                }
                break;

            case 'sms_recharge':
                $title = 'SMS Wallet Recharge';
                $message = $status === 'paid'
                    ? 'SMS wallet recharge সফল হয়েছে।'
                    : 'SMS recharge payment pending — verify হলে balance add হবে।';
                $cta = ['label' => 'SMS Dashboard', 'url' => route('user.sms.index')];
                break;

            case 'chatbot_package':
                $pkg = !empty($meta['package_id']) ? ChatbotPackage::find($meta['package_id']) : null;
                $title = 'Chatbot Package';
                $rows[] = ['label' => 'Package', 'value' => $pkg->name ?? 'Chatbot Package'];
                $message = $status === 'paid' ? 'Chatbot package activate হয়েছে।' : 'Chatbot package payment pending।';
                $cta = ['label' => 'Chatbot Dashboard', 'url' => route('user.chatbot.dashboard')];
                break;

            case 'domain_hosting_purchase':
                $plan = !empty($meta['plan_id']) ? HostingPlan::find($meta['plan_id']) : null;
                $title = 'Domain + Hosting';
                $rows[] = ['label' => 'Domain', 'value' => $meta['domain'] ?? '—'];
                $rows[] = ['label' => 'Hosting Plan', 'value' => $plan->name ?? '—'];
                $message = $status === 'paid'
                    ? 'Domain/Hosting order confirm হয়েছে।'
                    : 'Domain/Hosting order pending verification।';
                $cta = ['label' => 'My Domains', 'url' => route('user.domains.index')];
                break;

            case 'domain_renewal':
                $title = 'Domain Renewal';
                $message = $status === 'paid' ? 'Domain renewal successful।' : 'Domain renewal pending।';
                $cta = ['label' => 'My Domains', 'url' => route('user.domains.index')];
                break;

            case 'hosting_renewal':
                $title = 'Hosting Renewal';
                $message = $status === 'paid' ? 'Hosting renewal successful।' : 'Hosting renewal pending।';
                $cta = ['label' => 'My Hosting', 'url' => route('user.hostings.index')];
                break;

            case 'license_renewal':
                $title = 'License Renewal';
                $message = $status === 'paid' ? 'License renewal successful।' : 'License renewal pending।';
                $cta = ['label' => 'My Licenses', 'url' => route('user.licenses')];
                break;

            case 'license_generate':
            case 'public_license_purchase':
                $title = 'License Purchase';
                $rows[] = ['label' => 'Domain', 'value' => $meta['domain_name'] ?? ($meta['domain'] ?? '—')];
                $message = $status === 'paid' ? 'License order confirm হয়েছে।' : 'License payment pending।';
                $cta = ['label' => 'My Licenses', 'url' => route('user.licenses')];
                break;

            default:
                $title = 'Service Payment';
                $message = $status === 'paid'
                    ? 'আপনার পেমেন্ট সফল হয়েছে।'
                    : ($status === 'pending' ? 'পেমেন্ট pending — যাচাই হচ্ছে।' : 'পেমেন্ট সম্পন্ন হয়নি।');
                $cta = ['label' => 'Dashboard', 'url' => route('user.dashboard')];
        }

        if ($source === 'admin_approved' && $status === 'paid') {
            $message = 'Admin panel থেকে আপনার পেমেন্ট approve করা হয়েছে। ' . $message;
        }

        return compact('title', 'rows', 'message', 'cta');
    }

    private function wrapHtml(string $site, string $subject, array $details, string $status): string
    {
        $color = match ($status) {
            'paid'    => '#15803d',
            'pending' => '#b45309',
            default   => '#b91c1c',
        };

        $rowsHtml = '';
        foreach ($details['rows'] as $row) {
            $rowsHtml .= '<tr><td style="padding:8px 12px;color:#64748b;font-size:13px;">' . e($row['label']) . '</td>'
                . '<td style="padding:8px 12px;font-weight:700;color:#0f172a;">' . e($row['value']) . '</td></tr>';
        }

        $ctaHtml = '';
        if (!empty($details['cta']['url'])) {
            $ctaHtml = '<p style="margin:24px 0;"><a href="' . e($details['cta']['url']) . '" '
                . 'style="background:#ff6600;color:#fff;text-decoration:none;padding:12px 24px;border-radius:8px;font-weight:700;display:inline-block;">'
                . e($details['cta']['label']) . '</a></p>';
        }

        return '<!DOCTYPE html><html lang="bn"><head><meta charset="UTF-8"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>'
            . '<div style="font-family:Inter,Arial,sans-serif;background:#f8fafc;padding:24px;">'
            . '<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">'
            . '<div style="background:' . $color . ';color:#fff;padding:20px 24px;"><h2 style="margin:0;font-size:20px;">' . e($subject) . '</h2></div>'
            . '<div style="padding:24px;"><p style="color:#334155;line-height:1.6;">' . e($details['message']) . '</p>'
            . '<table style="width:100%;border-collapse:collapse;background:#f8fafc;border-radius:12px;overflow:hidden;margin:16px 0;">' . $rowsHtml . '</table>'
            . $ctaHtml
            . '<p style="color:#94a3b8;font-size:12px;margin-top:24px;">— ' . e($site) . '</p></div></div></div>'
            . '</body></html>';
    }
}
