<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'webview_template_path')) {
                $table->string('webview_template_path', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (Schema::hasColumn('generalsettings', 'webview_template_path')) {
                $table->dropColumn('webview_template_path');
            }
        });
    }
};
