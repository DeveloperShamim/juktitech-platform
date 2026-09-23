<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Update section_contents
        DB::table('section_contents')->updateOrInsert(
            ['section_key' => 'service_section'],
            [
                'badge' => 'ডিজিটাল ইকোসিস্টেম',
                'title' => 'বাংলাদেশে এইবার আমরাই নিয়ে এসেছি সম্পূর্ণ ডিজিটাল ইকোসিস্টেম',
                'subtitle' => 'Jukti Tech শুধুমাত্র একটি সার্ভিস প্রোভাইডার নয়, আমরা আপনার ব্যবসার বিশ্বস্ত গ্রোথ পার্টনার। রেডিমেড লারাভেল স্ক্রিপ্ট থেকে কাস্টম সফটওয়্যার - সব সমাধান এক ছাদের নিচে।',
                'description' => 'আপনার ব্যবসাকে ডিজিটাল করার প্রতিটি ধাপে আমরা আছি আপনার পাশে।',
                'extra_json' => null,
                'updated_at' => now(),
            ]
        );

        // 2. Update about_section_contents
        $heroData = [
            'stats' => [
                ['icon' => 'fas fa-laptop-code', 'value' => '৫০০+', 'label' => 'ওয়েবসাইট ডেলিভারি'],
                ['icon' => 'fas fa-smile', 'value' => '১০০০+', 'label' => 'সন্তুষ্ট ক্লায়েন্ট'],
                ['icon' => 'fas fa-headset', 'value' => '২৪/৭', 'label' => 'সাপোর্ট সেবা'],
            ],
            'buttons' => [
                ['text' => 'লাইভ ডেমো দেখুন', 'url' => '#demo', 'style' => 'primary', 'icon' => 'fas fa-arrow-right'],
                ['text' => 'ওয়েবসাইট কালেকশন', 'url' => '#all-websites', 'style' => 'secondary', 'icon' => 'fas fa-layer-group'],
            ],
            'mockup' => [
                'type' => 'wireframe',
                'float_1' => 'Secure Payment',
                'float_2' => 'Fast Delivery',
                'float_3' => 'COD Available',
                'browser_url' => 'vantor.juktitech.com',
                'search_placeholder' => 'প্রোডাক্ট খুঁজুন...',
                'cart_count' => '3',
                'sale_text' => '৫০% OFF',
                'banner_btn' => 'Shop Now',
                'categories' => ['All', 'Electronics', 'Fashion', 'Home'],
                'products' => [
                    ['badge' => 'Hot', 'badge_class' => null, 'price' => '৳ ১,২৯৯', 'rating' => '4.8'],
                    ['badge' => null, 'badge_class' => null, 'price' => '৳ ৮৯৯', 'rating' => '4.6'],
                    ['badge' => 'New', 'badge_class' => 'is-new', 'price' => '৳ ২,৪৯৯', 'rating' => '5.0'],
                ],
                'checkout_text' => 'কার্ট: ৩ আইটেম · ৳ ৪,৫৯৭',
                'checkout_btn' => 'Checkout',
            ],
        ];

        DB::table('about_section_contents')->updateOrInsert(
            ['id' => 1],
            [
                'title' => 'আপনার ব্যবসাকে দিন আধুনিক রূপ<br><span class="cd-hero__accent">সুপারফাস্ট গতি ও স্কেলিং</span>',
                'subtitle' => 'এক ক্লিকে কুরিয়ার অটোমেশন, ০.৪ সেকেন্ডে সুপারফাস্ট লোডিং ও আজীবন লাইসেন্স।',
                'hero_badge' => '⚡ প্রিমিয়াম ওয়েবসাইট ও সফটওয়্যার সলিউশন',
                'hero_data' => json_encode($heroData),
                'hero_mockup_image' => null,
                'image' => '1766477456.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
