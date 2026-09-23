<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PricingPlan;

class PricingPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pricing_plans')->truncate();

        $plans = [
            [
                'title' => 'স্টার্টার',
                'price' => '৩৫০০',
                'currency' => '৳',
                'duration' => 'এককালীন পেমেন্ট',
                'features' => '১ পেজের হাই-কনভার্টিং ল্যান্ডিং পেজ,মোবাইল ও ট্যাবলেট ফ্রেন্ডলি ডিজাইন,১০ জিবি ফাস্ট এসএসডি হোস্টিং,১ বছরের জন্য ফ্রি ডোমেইন সেটআপ,হোয়াটসঅ্যাপ ও সোশ্যাল চ্যাট ইন্টিগ্রেশন,বেসিক এসইও (SEO) অপ্টিমাইজেশন',
                'is_featured' => 0,
                'order_link' => 'https://wa.me/8801775075543?text=Hello Jukti Tech! আমি স্টার্টার প্যাকেজটি নিতে চাই।',
            ],
            [
                'title' => 'উদ্যোক্তা (বেস্ট চয়েস)',
                'price' => '৮০০০',
                'currency' => '৳',
                'duration' => 'এককালীন পেমেন্ট',
                'features' => '৫ পেজের প্রিমিয়াম ডায়নামিক ওয়েবসাইট,আধুনিক অ্যাপল-স্টাইল ক্লিন ইন্টারফেস,আনলিমিটেড হোস্টিং ও ব্যান্ডউইথ,ফ্রি .com ডোমেইন (১ বছর),অটোমেটেড সোশ্যাল মিডিয়া বট,১ মাসের ফ্রি টেকনিক্যাল সাপোর্ট ও মেইনটেনেন্স',
                'is_featured' => 1,
                'order_link' => 'https://wa.me/8801775075543?text=Hello Jukti Tech! আমি উদ্যোক্তা প্যাকেজটি নিতে চাই।',
            ],
            [
                'title' => 'বিজনেস প্রো',
                'price' => '১৮৫০০',
                'currency' => '৳',
                'duration' => 'এককালীন পেমেন্ট',
                'features' => 'ফুল লারাভেল ই-কমার্স সলিউশন,১-ক্লিক ক্যাশ অন ডেলিভারি চেকআউট,কুরিয়ার API (Steadfast/Pathao) অটোমেশন,বিকাশ/নগদ/রকেট পেমেন্ট গেটওয়ে,ফেসবুক পিক্সেল ও সার্ভার সাইড ট্র্যাকিং,৬ মাসের ডেডিকেটেড ভিআইপি সাপোর্ট',
                'is_featured' => 0,
                'order_link' => 'https://wa.me/8801775075543?text=Hello Jukti Tech! আমি বিজনেস প্রো প্যাকেজটি নিতে চাই।',
            ],
        ];

        DB::table('pricing_plans')->insert($plans);
    }
}
