<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            GeneralSettingsSeeder::class,
            SeotoolsSeeder::class,
            ServicesSeeder::class,
            PricingPlansSeeder::class,
            ProductsSeeder::class,
            PortfoliosSeeder::class,
            TestimonialsSeeder::class,
            WhyChooseUsSeeder::class,
            HomepageContentSeeder::class,
            SocialLinksSeeder::class,
            BrandsSeeder::class,
            ChatbotPackagesSeeder::class,
        ]);
    }
}
