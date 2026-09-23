<?php

namespace App\Console\Commands;

use App\Services\PackageExpiryService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * প্যাকেজ এক্সপাইরি ক্রন জব:
 * - ডোমেইন (License / UserDomain): এক্সপাইরি ডেট অনুযায়ী এক্সপায়ার
 * - হোস্টিং: প্রথমে সাস্পেন্ড, ৭ দিন পর এক্সপায়ার
 * - চ্যাটবট প্যাকেজ: plan_expiry অনুযায়ী এক্সপায়ার
 */
class ProcessPackageExpiry extends Command
{
    protected $signature = 'packages:process-expiry';
    protected $description = 'ডোমেন, হোস্টিং ও চ্যাটবট প্যাকেজ এক্সপাইরি ডেট অনুযায়ী সাস্পেন্ড/এক্সপায়ার করুন';

    public function handle()
    {
        $stats = PackageExpiryService::processAll(Carbon::today());

        $this->info('Licenses deactivated: ' . ($stats['licenses'] ?? 0));
        $this->info('Domains expired: ' . ($stats['domains'] ?? 0));
        $this->info('Hostings suspended: ' . ($stats['hostings']['suspended'] ?? 0));
        $this->info('Hostings expired: ' . ($stats['hostings']['expired'] ?? 0));
        $this->info('Chatbot plans cleared: ' . ($stats['chatbot'] ?? 0));
        $this->info('Package expiry processing completed.');

        return 0;
    }
}
