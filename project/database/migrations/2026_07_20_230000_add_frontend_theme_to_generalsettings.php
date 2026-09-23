<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('frontend_theme_settings')) {
            Schema::create('frontend_theme_settings', function (Blueprint $table) {
                $table->id();
                $table->longText('settings')->nullable();
                $table->timestamps();
            });

            DB::table('frontend_theme_settings')->insert([
                'id' => 1,
                'settings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_theme_settings');
    }
};
