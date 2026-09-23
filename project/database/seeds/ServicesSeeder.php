<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->truncate();

        $services = [
            [
                'title' => 'ওয়েব ডিজাইন ও ডেভেলপমেন্ট',
                'description' => 'কাস্টম রেসপন্সিভ ওয়েবসাইট, বিজনেস পোর্টাল এবং ল্যান্ডিং পেজ। প্যাকেজ শুরু ৮,০০০ টাকা থেকে।',
                'icon' => 'fas fa-globe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'কাস্টম সফটওয়্যার ডেভেলপমেন্ট',
                'description' => 'স্কুল, হাসপাতাল, ইনভেন্টরি ERP এবং ব্যবসায়িক ম্যানেজমেন্ট সফটওয়্যার ডেভেলপমেন্ট। শুরু ১০,০০০ টাকা থেকে।',
                'icon' => 'fas fa-laptop-code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'লারাভেল ই-কমার্স সলিউশন',
                'description' => 'হাই-স্পিড ১-পেজ চেকআউট, কুরিয়ার API ও ফ্রড প্রটেকশনসহ কমপ্লিট স্টোর। শুরু ১২,৫০০ টাকা থেকে।',
                'icon' => 'fas fa-shopping-cart',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'UI/UX ও মোবাইল অ্যাপ ডিজাইন',
                'description' => 'মডার্ন অ্যাপল-স্টাইল মোবাইল ও ওয়েব ইন্টারফেস ডিজাইন। প্যাকেজ শুরু ৩,৫০০ টাকা থেকে।',
                'icon' => 'fas fa-paint-brush',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'সোশ্যাল মিডিয়া ও পারফরম্যান্স মার্কেটিং',
                'description' => 'টার্গেটেড ফেসবুক ও গুগল অ্যাডস, সেলস ফানেল ও গ্রোথ স্ট্র্যাটেজি। প্যাকেজ শুরু ৫,০০০ টাকা থেকে।',
                'icon' => 'fab fa-facebook-f',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'প্রোডাক্ট ব্র্যান্ডিং ও গ্রাফিক্স ডিজাইন',
                'description' => 'প্রফেশনাল লোগো, ব্র্যান্ড আইডেন্টিটি, সোশ্যাল ব্যানার ও প্যাকেজিং ডিজাইন। শুরু ২,০০০ টাকা থেকে।',
                'icon' => 'fas fa-lightbulb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('services')->insert($services);
    }
}
