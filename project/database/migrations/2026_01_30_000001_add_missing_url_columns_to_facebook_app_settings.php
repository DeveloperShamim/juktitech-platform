<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * redirect_url, privacy_policy_url, terms_of_service_url, data_deletion_url
     * যদি টেবিলে না থাকে তাহলে যোগ করা হবে।
     */
    public function up(): void
    {
        Schema::table('facebook_app_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('facebook_app_settings', 'redirect_url')) {
                $table->text('redirect_url')->nullable();
            }
            if (!Schema::hasColumn('facebook_app_settings', 'privacy_policy_url')) {
                $table->text('privacy_policy_url')->nullable();
            }
            if (!Schema::hasColumn('facebook_app_settings', 'terms_of_service_url')) {
                $table->text('terms_of_service_url')->nullable();
            }
            if (!Schema::hasColumn('facebook_app_settings', 'data_deletion_url')) {
                $table->text('data_deletion_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebook_app_settings', function (Blueprint $table) {
            $columns = ['redirect_url', 'privacy_policy_url', 'terms_of_service_url', 'data_deletion_url'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('facebook_app_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
