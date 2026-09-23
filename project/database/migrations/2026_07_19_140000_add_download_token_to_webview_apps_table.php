<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->string('download_token', 64)->nullable()->unique()->after('aab_path');
        });
    }

    public function down(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            $table->dropColumn('download_token');
        });
    }
};
