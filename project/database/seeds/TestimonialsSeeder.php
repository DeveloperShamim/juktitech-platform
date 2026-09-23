<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Testimonial;

class TestimonialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('testimonials')->truncate();

        $testimonials = [
            [
                'name' => 'নাসির শেখ',
                'designation' => 'ব্যবস্থাপনা পরিচালক, গ্যাজেট হাব',
                'photo' => '17662441141736235319620330853.png',
                'message' => 'Jukti Tech-এর কাজ সত্যিই অসাধারণ! আমরা তাদের ১-পেজ চেকআউট লারাভেল স্ক্রিপ্ট নিয়েছি। চেকআউট স্পিড দ্রুত হওয়ায় আমাদের সেলস দ্বিগুণ হয়েছে। ধন্যবাদ যুক্তি টেক টিমকে!',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'নাজমুল হাসান',
                'designation' => 'প্রধান নির্বাহী, ইগো মার্ট',
                'photo' => '1766244163173623517594248820.png',
                'message' => 'আমাদের ওয়েবসাইটের জন্য যুক্তি টেক (Jukti Tech) থেকে এসইও এবং কাস্টম ওয়েব ডিজাইন সার্ভিস নিয়েছিলাম। ডেলিভারির পর অর্গানিক ট্রাফিক ও কনভার্সন অনেক বৃদ্ধি পেয়েছে।',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'আকবর হোসেন',
                'designation' => 'প্রতিষ্ঠাতা, ই-লাইফ শপ',
                'photo' => '17662442291736235053786629294.png',
                'message' => 'যুক্তি টেক থেকে আমাদের নতুন কাস্টম ই-কমার্স পোর্টাল ডেভেলপ করিয়েছি। তাদের কোড কোয়ালিটি এবং ক্লিন ইন্টারফেস এক কথায় দুর্দান্ত!',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'জিয়া হাসান',
                'designation' => 'সহ-প্রতিষ্ঠাতা, টেক সলিউশন',
                'photo' => '176624429517362346181434000873.png',
                'message' => 'Jukti Tech এর সাথে কাজ করে আমরা অত্যন্ত আনন্দিত। তাদের কাস্টমার সাপোর্ট এবং ডেডলাইন মেইনটেইন করার ক্ষমতা অতুলনীয়।',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'খালেদ মাহমুদ',
                'designation' => 'অপারেশনস হেড, এক্সপ্রেস কুরিয়ার',
                'photo' => '176624433817362347301097715037.png',
                'message' => 'কুরিয়ার ট্র্যাকিং অটোমেশন ও এপিআই ইন্টিগ্রেশন প্রজেক্টটি যুক্তি টেক নিখুঁতভাবে শেষ করে দিয়েছে। তাদের ডেভেলপারদের দক্ষতা প্রশংসার দাবিদার।',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ফাতেমা আক্তার',
                'designation' => 'উদ্যোক্তা, নাবিওরা গ্লো',
                'photo' => '176624437417362348801814731466.png',
                'message' => 'আমাদের অনলাইন বুটিকের নতুন ওয়েবসাইটের ডিজাইন ও মোবাইল রেসপনসিভনেস দেখে আমরা মুগ্ধ। Jukti Tech টিমকে অসংখ্য ধন্যবাদ।',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('testimonials')->insert($testimonials);
    }
}
