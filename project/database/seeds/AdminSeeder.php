<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure super admin role exists
        $role = Role::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Super Admin',
                'section' => json_encode([
                    "menu_builder", "pages", "categories", "add_post", "add_gallery",
                    "posts", "schedule_post", "drafts", "rss_feeds", "polls", "widgets",
                    "create_ads", "newsLetter", "contact_messages", "languages",
                    "general_settings", "social_settings", "seo_tools", "emails_settings",
                    "role_management", "font_option", "user_management", "cache_management",
                    "administration_management"
                ])
            ]
        );

        // Create or update Super Admin
        Admin::updateOrCreate(
            ['email' => 'admin@juktitech.com'],
            [
                'name' => 'Jukti Tech Admin',
                'phone' => '01775075543',
                'role_id' => 1,
                'photo' => null,
                'password' => Hash::make('admin123'),
                'token' => bin2hex(random_bytes(16)),
                'verify' => 1,
                'status' => 1,
            ]
        );

        // Also update any legacy admin row if exists
        $firstAdmin = Admin::find(1);
        if ($firstAdmin && $firstAdmin->email !== 'admin@juktitech.com') {
            $firstAdmin->update([
                'name' => 'Jukti Tech Admin',
                'email' => 'info@juktitech.com',
                'password' => Hash::make('admin123'),
                'status' => 1,
            ]);
        }
    }
}
