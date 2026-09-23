<?php

namespace App\Services;

use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\UserHosting;
use Carbon\Carbon;

class DomainHostingPricingService
{
    public static function normalizeYears(int|string|null $years): int
    {
        $years = (int) $years;

        return in_array($years, [1, 2, 3], true) ? $years : 1;
    }

    public static function normalizePeriod(?string $period): string
    {
        return strtolower(trim((string) $period)) === 'monthly' ? 'monthly' : 'yearly';
    }

    public static function isMonthlyCycle(?string $billingCycle): bool
    {
        return stripos((string) $billingCycle, 'month') !== false;
    }

    public static function billingCycleLabel(int $years, string $period = 'yearly'): string
    {
        if (self::normalizePeriod($period) === 'monthly') {
            return 'Monthly';
        }

        return match (self::normalizeYears($years)) {
            1 => 'Yearly',
            2 => '2 Years',
            3 => '3 Years',
            default => $years . ' Years',
        };
    }

    public static function hostingNextDueDate(string $period, int|string|null $years = 1): Carbon
    {
        if (self::normalizePeriod($period) === 'monthly') {
            return Carbon::now()->addMonth();
        }

        return Carbon::now()->addYears(self::normalizeYears($years));
    }

    public static function renewalAmount(UserHosting $hosting): float
    {
        $plan = $hosting->plan;

        if (self::isMonthlyCycle($hosting->billing_cycle)) {
            $monthly = $plan ? (float) $plan->price_monthly : 0.0;
            if ($monthly > 0) {
                return $monthly;
            }
        }

        $yearly = $plan ? (float) $plan->price_yearly : 0.0;
        if ($yearly > 0) {
            return $yearly;
        }

        return (float) ($hosting->amount ?? 0);
    }

    public static function renewNextDueDate(UserHosting $hosting): Carbon
    {
        if (self::isMonthlyCycle($hosting->billing_cycle)) {
            return Carbon::now()->addMonth();
        }

        return Carbon::now()->addYear();
    }

    /**
     * ডোমেইন সবসময় বাৎসরিক; হোস্টিং মাসিক বা বাৎসরিক হতে পারে।
     *
     * @return array{
     *   billing_period: string,
     *   billing_years: int,
     *   domain_price_yearly: float,
     *   hosting_price_yearly: float,
     *   hosting_price_monthly: float,
     *   hosting_unit_price: float,
     *   domain_price: float,
     *   hosting_price: float,
     *   total_price: float,
     *   free_domain_first_year: bool
     * }
     */
    public static function calculate(
        bool $isExistingDomain,
        ?DomainExtension $extInfo,
        ?HostingPlan $plan,
        int|string|null $years,
        ?string $period = 'yearly'
    ): array {
        $years  = self::normalizeYears($years);
        $period = self::normalizePeriod($period);

        $domainYearly   = (!$isExistingDomain && $extInfo) ? (float) $extInfo->registration_price : 0.0;
        $hostingYearly  = $plan ? (float) $plan->price_yearly : 0.0;
        $hostingMonthly = $plan ? (float) $plan->price_monthly : 0.0;
        $freeFirstYear  = false;
        $domainTotal    = 0.0;

        if (!$isExistingDomain && $extInfo) {
            if ($plan && (int) $plan->free_domain === 1 && self::isFreeDomainExtension($plan, $extInfo)) {
                $freeFirstYear = true;
                $domainTotal   = $domainYearly * max(0, $years - 1);
            } else {
                $domainTotal = $domainYearly * $years;
            }
        }

        if ($period === 'monthly') {
            $hostingUnit  = $hostingMonthly;
            $hostingTotal = $hostingMonthly;
        } else {
            $hostingUnit  = $hostingYearly;
            $hostingTotal = $hostingYearly * $years;
        }

        return [
            'billing_period'         => $period,
            'billing_years'          => $years,
            'domain_price_yearly'    => $domainYearly,
            'hosting_price_yearly'   => $hostingYearly,
            'hosting_price_monthly'  => $hostingMonthly,
            'hosting_unit_price'     => $hostingUnit,
            'domain_price'           => $domainTotal,
            'hosting_price'          => $hostingTotal,
            'total_price'            => $domainTotal + $hostingTotal,
            'free_domain_first_year' => $freeFirstYear,
        ];
    }

    public static function isFreeDomainExtension(HostingPlan $plan, DomainExtension $extInfo): bool
    {
        $raw = $plan->free_domain_extensions;
        $allowedIds = [];

        if (is_array($raw)) {
            $allowedIds = $raw;
        } else {
            $decoded = json_decode((string) $raw, true);
            $allowedIds = is_array($decoded) ? $decoded : explode(',', (string) $raw);
        }

        return in_array((string) $extInfo->id, array_map('strval', array_map('trim', $allowedIds)), true);
    }
}
