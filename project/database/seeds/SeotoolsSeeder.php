<?php

use Illuminate\Database\Seeder;
use App\Models\Seo;

class SeotoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'meta_keys' => 'Jukti Tech,যুক্তি টেক,juktitech.com,juktitech,laravel website,laravel ecommerce script,web design bd,web development in bangladesh,bangladesh software company,laravel expert bd,e-commerce website,landing page design,courier api integration,facebook chatbot automation,seo agency bd',
            'meta_description' => 'যুক্তি টেক (Jukti Tech) বাংলাদেশের একটি অগ্রণী প্রযুক্তি প্রতিষ্ঠান। আমরা আধুনিক ওয়েব ডিজাইন, কাস্টম সফটওয়্যার, রেডিমেড লারাভেল ই-কমার্স স্ক্রিপ্ট এবং ২৪/৭ আইটি সহায়তা প্রদান করি।',
            'google_analytics' => '',
        ];

        $seo = Seo::first();
        if ($seo) {
            $seo->update($data);
        } else {
            Seo::create($data);
        }
    }
}
