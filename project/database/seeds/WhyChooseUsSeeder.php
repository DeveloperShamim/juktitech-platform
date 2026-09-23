<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WhyChooseUs;

class WhyChooseUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('why_choose_us')->truncate();

        $items = [
            [
                'title' => 'অভিজ্ঞ ফুল-স্ট্যাক টিম',
                'description' => 'আমাদের রয়েছে দক্ষ লারাভেল ও রিয়্যাক্ট ডেভেলপার, ইউআই ডিজাইনার এবং ডিজিটাল মার্কেটিং বিশেষজ্ঞ।',
                'icon' => 'fas fa-users fa-2x text-primary',
                'image' => '1766477598_why.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'অন-টাইম ডেলিভারি প্রতিশ্রুতি',
                'description' => 'আমরা কঠোরভাবে প্রজেক্ট টাইমলাইন ও ডেডলাইন মেনে চলি যাতে আপনার ব্যবসা সময়মত চালু হতে পারে।',
                'icon' => 'fas fa-clock fa-2x text-primary',
                'image' => '1766477568_why.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'স্বচ্ছ ও সাশ্রয়ী মূল্য',
                'description' => 'সেরা মানের আধুনিক সফটওয়্যার ও কোডিং স্ট্যান্ডার্ড, বাজেটের মধ্যে। কোনো গোপন বা লুকানো খরচ নেই।',
                'icon' => 'fas fa-hand-holding-dollar fa-2x text-primary',
                'image' => '1766477553_why.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '২৪/৭ ডেডিকেটেড সাপোর্ট',
                'description' => 'যেকোনো কারিগরি সহায়তা বা সিস্টেম সমস্যার দ্রুত সমাধানে আমাদের হেল্পডেস্ক টিম সর্বদা প্রস্তুত।',
                'icon' => 'fas fa-headset fa-2x text-primary',
                'image' => '1766477598_why.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('why_choose_us')->insert($items);
    }
}
