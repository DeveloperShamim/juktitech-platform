<?php

namespace App\Services;

use App\Models\License;
use App\Models\User;
use App\Models\UserDomain;
use App\Models\UserHosting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * ডোমেইন/হোস্টিং/লাইসেন্স মেয়াদ শেষ হলে স্ট্যাটাস অটো আপডেট।
 * ক্রন + পেজ লোড দুটোতেই কল করা যায়।
 */
class PackageExpiryService
{
    public static function processAll(?Carbon $today = null): array
    {
        $today = ($today ?? Carbon::today())->copy()->startOfDay();

        return [
            'licenses' => self::processLicenses($today),
            'domains'  => self::processUserDomains($today),
            'hostings' => self::processUserHosting($today),
            'chatbot'  => self::processChatbotPackageExpiry($today),
        ];
    }

    /**
     * নির্দিষ্ট ইউজারের ডোমেইন/হোস্টিং সিঙ্ক (ড্যাশবোর্ড/লিস্ট ভিউতে)।
     */
    public static function syncForUser(?int $userId): array
    {
        if (!$userId) {
            return ['domains' => 0, 'hostings' => 0, 'licenses' => 0];
        }

        $today = Carbon::today()->startOfDay();

        return [
            'licenses' => self::processLicenses($today, $userId),
            'domains'  => self::processUserDomains($today, $userId),
            'hostings' => self::processUserHosting($today, $userId),
        ];
    }

    public static function processLicenses(Carbon $today, ?int $userId = null): int
    {
        $query = License::whereNotNull('expiry_date')
            ->where('expiry_date', '<', $today)
            ->where('status', 1);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $count = (int) $query->update(['status' => 0]);

        if ($count > 0) {
            Log::info('PackageExpiryService: Licenses expired', [
                'count' => $count,
                'user_id' => $userId,
            ]);
        }

        return $count;
    }

    public static function processUserDomains(Carbon $today, ?int $userId = null): int
    {
        if (!Schema::hasTable('user_domains') || !Schema::hasColumn('user_domains', 'status')) {
            return 0;
        }

        $query = UserDomain::whereNotNull('next_due_date')
            ->whereDate('next_due_date', '<', $today->toDateString())
            ->whereIn('status', ['Active', 'Suspended']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $count = (int) $query->update(['status' => 'Expired']);

        if ($count > 0) {
            Log::info('PackageExpiryService: User domains expired', [
                'count' => $count,
                'user_id' => $userId,
            ]);
        }

        return $count;
    }

    /**
     * হোস্টিং: মেয়াদ পার হলে সাথে সাথে Suspended (ডিঅ্যাকটিভ),
     * ৭ দিন গ্রেস শেষে Expired।
     *
     * @return array{suspended:int, expired:int}
     */
    public static function processUserHosting(Carbon $today, ?int $userId = null): array
    {
        $result = ['suspended' => 0, 'expired' => 0];

        if (!Schema::hasTable('user_hostings')) {
            return $result;
        }

        $activeQuery = UserHosting::with('server')
            ->where('status', 'Active')
            ->whereNotNull('next_due_date')
            ->whereDate('next_due_date', '<', $today->toDateString());

        if ($userId) {
            $activeQuery->where('user_id', $userId);
        }

        $hasSuspendedAt = Schema::hasColumn('user_hostings', 'suspended_at');
        $activeOverdue = $activeQuery->get();

        foreach ($activeOverdue as $hosting) {
            self::suspendHostingAccount($hosting);
            $data = ['status' => 'Suspended'];
            if ($hasSuspendedAt) {
                $data['suspended_at'] = $hosting->suspended_at ?? now();
            }
            $hosting->update($data);
            $result['suspended']++;
            Log::info('PackageExpiryService: Hosting suspended', [
                'id' => $hosting->id,
                'domain' => $hosting->domain,
                'user_id' => $userId,
            ]);
        }

        $sevenDaysAgo = $today->copy()->subDays(7);

        if ($hasSuspendedAt) {
            $expireQuery = UserHosting::where('status', 'Suspended')
                ->whereNotNull('suspended_at')
                ->where('suspended_at', '<=', $sevenDaysAgo);
        } else {
            $expireQuery = UserHosting::where('status', 'Suspended')
                ->whereNotNull('next_due_date')
                ->whereDate('next_due_date', '<', $sevenDaysAgo->toDateString());
        }

        if ($userId) {
            $expireQuery->where('user_id', $userId);
        }

        foreach ($expireQuery->get() as $hosting) {
            $hosting->update(['status' => 'Expired']);
            $result['expired']++;
            Log::info('PackageExpiryService: Hosting expired', [
                'id' => $hosting->id,
                'domain' => $hosting->domain,
                'user_id' => $userId,
            ]);
        }

        return $result;
    }

    public static function suspendHostingAccount(UserHosting $hosting): void
    {
        $server = $hosting->server;
        if (!$server || !$hosting->username) {
            return;
        }

        $whm_user = trim($server->username);
        $whm_hash = str_replace(["\r", "\n", " "], '', $server->access_hash);
        $hostname = preg_replace('#^https?://|/$#', '', trim($server->hostname));
        $cp_user = trim($hosting->username);
        $api_url = "https://{$hostname}:2087/json-api/suspendacct?api.version=1&user={$cp_user}";

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders(['Authorization' => "whm $whm_user:$whm_hash"])
                ->get($api_url);
            $result = $response->json();
            if (isset($result['metadata']['result']) && $result['metadata']['result'] == 0) {
                Log::warning('PackageExpiryService: WHM suspend failed', [
                    'domain' => $hosting->domain,
                    'reason' => $result['metadata']['reason'] ?? 'Unknown',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('PackageExpiryService: WHM suspend exception', [
                'domain' => $hosting->domain,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public static function processChatbotPackageExpiry(Carbon $today): int
    {
        $count = (int) User::whereNotNull('plan_expiry')
            ->where('plan_expiry', '<', $today)
            ->whereNotNull('active_plan')
            ->update([
                'active_plan' => null,
                'plan_expiry' => null,
                'connected_pages_limit' => 0,
                'monthly_message_limit' => 0,
                'messages_sent_this_month' => 0,
            ]);

        if ($count > 0) {
            Log::info('PackageExpiryService: Chatbot plans expired', ['count' => $count]);
        }

        return $count;
    }

    /**
     * তারিখ কি আজকের আগে শেষ হয়েছে (মেয়াদ শেষ দিন পর্যন্ত বৈধ)।
     */
    public static function isDueDatePassed($date): bool
    {
        if (!$date) {
            return false;
        }

        return Carbon::parse($date)->startOfDay()->lt(Carbon::today()->startOfDay());
    }
}
