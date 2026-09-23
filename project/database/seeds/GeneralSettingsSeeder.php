<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralSettings;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'title' => 'Jukti Tech | যুক্তি টেক',
            'logo' => '1789807361_logo.webp',
            'email' => 'info@juktitech.com',
            'phone' => '+880 1775-075543',
            'address' => 'হাউজ: ১২, রোড: ০৪, ধানমন্ডি, ঢাকা, বাংলাদেশ',
            'theme_color' => '#f97316',
            'footer_color' => '#0f172a',
            'copyright_text' => 'Copyright © ' . date('Y') . ' Jukti Tech. All Rights Reserved.',
            'copyright_color' => '#94a3b8',
            'footer_text' => '<p>যুক্তি টেক বাংলাদেশের একটি আধুনিক আইটি সেবা ও ডিজিটাল ট্রান্সফরমেশন প্রতিষ্ঠান।</p>',
            'footer_details' => 'আমরা ছোট ও মাঝারি ব্যবসার জন্য কাস্টম ওয়েব ডেভেলপমেন্ট, রেডিমেড লারাভেল স্ক্রিপ্ট এবং ফুল-স্ট্যাক ডিজিটাল সলিউশন প্রদান করি। আপনার ব্যবসার ডিজিটালাইজেশনে আমরা সার্বক্ষণিক পাশে আছি।',
            'tags' => 'jukti tech, laravel, website, ecommerce, software, bangladesh, web development, seo, digital marketing',
            'from_name' => 'Jukti Tech',
            'from_email' => 'info@juktitech.com',
            'time_zone' => 'Asia/Dhaka',
            'version' => '3.0',
            'facebook_page_link' => 'https://facebook.com/juktitech.official',
            'payment_bkash_number' => '01775075543',
            'payment_nagad_number' => '01775075543',
            'payment_rocket_number' => '01775075543',
            'payment_bkash_account_type' => 'personal',
            'payment_nagad_account_type' => 'personal',
            'payment_rocket_account_type' => 'personal',
            'payment_mode' => 'sms',
            'payment_auto_approve' => 0,
            'payment_instructions' => 'বিকাশ/নগদ/রকেট পার্সোনাল নম্বরে সেন্ড মানি করে ট্রানজেকশন আইডি প্রদান করুন।',
            'license_generation_price' => 3000.00,
            'webview_app_build_price' => 200.00,
        ];

        $gs = GeneralSettings::first();
        if ($gs) {
            $gs->update($settings);
        } else {
            GeneralSettings::create($settings);
        }
    }
}
