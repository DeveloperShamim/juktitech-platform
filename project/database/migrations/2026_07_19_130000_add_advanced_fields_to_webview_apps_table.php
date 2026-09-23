<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->string('splash_color_start')->default('#15803D')->after('splash_loading_message');
            $table->string('splash_color_center')->default('#16A34A')->after('splash_color_start');
            $table->string('splash_color_end')->default('#22C55E')->after('splash_color_center');
            $table->boolean('push_enabled')->default(false)->after('splash_color_end');
            $table->string('google_services_path')->nullable()->after('push_enabled');
            $table->string('fcm_server_key')->nullable()->after('google_services_path');
            $table->string('keystore_path')->nullable()->after('fcm_server_key');
            $table->text('keystore_store_password')->nullable()->after('keystore_path');
            $table->string('keystore_alias')->default('release')->after('keystore_store_password');
            $table->text('keystore_key_password')->nullable()->after('keystore_alias');
            $table->string('release_apk_path')->nullable()->after('apk_path');
            $table->string('aab_path')->nullable()->after('release_apk_path');
        });
    }

    public function down(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->dropColumn([
                'splash_color_start',
                'splash_color_center',
                'splash_color_end',
                'push_enabled',
                'google_services_path',
                'fcm_server_key',
                'keystore_path',
                'keystore_store_password',
                'keystore_alias',
                'keystore_key_password',
                'release_apk_path',
                'aab_path',
            ]);
        });
    }
};
