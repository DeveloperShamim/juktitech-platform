<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ChatbotPackage;

class ChatbotPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Sample chatbot packages
     */
    public function run(): void
    {
        DB::table('chatbot_packages')->truncate();

        $packages = [
            [
                'name' => 'Basic',
                'name_bn' => 'বেসিক',
                'description' => 'Perfect for small businesses starting with chatbot automation',
                'price' => 500,
                'duration_days' => 365,
                'page_limit' => 1,
                'message_limit' => 1000,
                'auto_reply_limit' => 20,
                'comment_automation_limit' => 10,
                'product_carousel' => false,
                'analytics' => false,
                'broadcast' => false,
                'is_popular' => false,
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Premium',
                'name_bn' => 'প্রিমিয়াম',
                'description' => 'Most popular plan with advanced features and analytics',
                'price' => 1200,
                'duration_days' => 365,
                'page_limit' => 3,
                'message_limit' => 5000,
                'auto_reply_limit' => 100,
                'comment_automation_limit' => 50,
                'product_carousel' => true,
                'analytics' => true,
                'broadcast' => false,
                'is_popular' => true,
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'name_bn' => 'প্রো',
                'description' => 'For large businesses with unlimited automation and broadcasting',
                'price' => 2500,
                'duration_days' => 365,
                'page_limit' => 10,
                'message_limit' => 20000,
                'auto_reply_limit' => 500,
                'comment_automation_limit' => 200,
                'product_carousel' => true,
                'analytics' => true,
                'broadcast' => true,
                'is_popular' => false,
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'name_bn' => 'এন্টারপ্রাইজ',
                'description' => 'Unlimited everything for large enterprises',
                'price' => 5000,
                'duration_days' => 365,
                'page_limit' => 999,
                'message_limit' => 999999,
                'auto_reply_limit' => 9999,
                'comment_automation_limit' => 9999,
                'product_carousel' => true,
                'analytics' => true,
                'broadcast' => true,
                'is_popular' => false,
                'status' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $packageData) {
            ChatbotPackage::create($packageData);
        }

        echo "✅ Sample chatbot packages created successfully!\n";
    }
}

