<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PortfolioCategory;
use App\Models\Portfolio;

class PortfoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('portfolios')->delete();
        DB::table('portfolio_categories')->delete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $catWeb = PortfolioCategory::create([
            'name' => 'ওয়েব ডেভেলপমেন্ট',
            'slug' => 'web-development',
        ]);

        $catEcom = PortfolioCategory::create([
            'name' => 'ই-কমার্স সলিউশন',
            'slug' => 'ecommerce-solution',
        ]);

        $catSoft = PortfolioCategory::create([
            'name' => 'সফটওয়্যার ও ERP',
            'slug' => 'software-erp',
        ]);

        $portfolios = [
            [
                'category_id' => $catEcom->id,
                'title' => 'EGO Cart - Modern Gadget Store',
                'photo' => '1786080318_ChatGPT-Image-Aug-6-2026-10_25_02-PM.webp',
                'url' => 'https://egocartbd.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $catEcom->id,
                'title' => 'Gadget Garb Hub',
                'photo' => '1786078609_ChatGPT-Image-Aug-6-2026-09_55_38-PM.webp',
                'url' => 'https://gadgetsgarbhub.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('portfolios')->insert($portfolios);
    }
}
