<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Facebook App এ Data Deletion Instructions URL দরকার।
     */
    public function up(): void
    {
        if (!Schema::hasTable('facebook_app_settings') || Schema::hasColumn('facebook_app_settings', 'data_deletion_url')) {
            return;
        }

        Schema::table('facebook_app_settings', function (Blueprint $table) {
            $table->text('data_deletion_url')->nullable()->after('terms_of_service_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebook_app_settings', function (Blueprint $table) {
            $table->dropColumn('data_deletion_url');
        });
    }
};
