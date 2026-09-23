<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SocialLink;

class SocialLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('social_links')->truncate();

        $links = [
            [
                'name' => 'facebook',
                'link' => 'https://facebook.com/juktitech.official',
                'icon' => 'fab fa-facebook-f',
            ],
            [
                'name' => 'twitter',
                'link' => 'https://x.com/juktitech',
                'icon' => 'fab fa-twitter',
            ],
            [
                'name' => 'instagram',
                'link' => 'https://instagram.com/juktitech',
                'icon' => 'fab fa-instagram',
            ],
            [
                'name' => 'youtube',
                'link' => 'https://youtube.com/@juktitech',
                'icon' => 'fab fa-youtube',
            ],
            [
                'name' => 'linkedin',
                'link' => 'https://linkedin.com/company/juktitech',
                'icon' => 'fab fa-linkedin-in',
            ],
        ];

        DB::table('social_links')->insert($links);
    }
}
