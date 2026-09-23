<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('facebook_app_settings')) {
            return;
        }

        Schema::table('facebook_app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('facebook_app_settings', 'redirect_url')) {
                $table->text('redirect_url')->nullable();
            }
        });

        Schema::table('facebook_app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('facebook_app_settings', 'privacy_policy_url')) {
                $table->text('privacy_policy_url')->nullable();
            }
        });

        Schema::table('facebook_app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('facebook_app_settings', 'terms_of_service_url')) {
                $table->text('terms_of_service_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebook_app_settings', function (Blueprint $table) {
            $table->dropColumn(['redirect_url', 'privacy_policy_url', 'terms_of_service_url']);
        });
    }
};
