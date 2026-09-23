<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->truncate();

        $brands = [
            [
                'name' => 'ফাকল পুলিশ লাইন্স স্কুল অ্যান্ড কলেজ',
                'photo' => '1766158648phakal.png',
                'url' => 'https://phakal.edu.bd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'দৈনিক জনতার বাংলাদেশ',
                'photo' => '1766158843doinik-jonatar-bngaladesh.png',
                'url' => 'https://www.dailyjanatarbangladesh.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'সততা উন্নয়ন ফোরাম',
                'photo' => '1766159017sottat.png',
                'url' => 'https://facebook.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ইবইবিতান',
                'photo' => '1766159594eboi.png',
                'url' => 'https://eboibitan.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'আস্থা অনলাইন শপ',
                'photo' => '1766159723astha.png',
                'url' => 'https://asthaonlinnebd.shop/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'সুরক্ষা পে',
                'photo' => '1766159831surokhha.png',
                'url' => 'https://surokkhapaybd.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'মোমো ফুড',
                'photo' => '1766160015momo.png',
                'url' => 'https://momo.com.bd/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'যুক্তি টেক',
                'photo' => '1766477495mylogo.png',
                'url' => 'https://juktitech.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
