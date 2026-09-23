<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->string('build_callback_token', 64)->nullable()->after('build_log');
            $table->string('github_run_id', 40)->nullable()->after('build_callback_token');
        });
    }

    public function down(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->dropColumn(['build_callback_token', 'github_run_id']);
        });
    }
};
