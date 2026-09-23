<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'webview_build_driver')) {
                $table->string('webview_build_driver', 20)->nullable()->default('local');
            }
            if (!Schema::hasColumn('generalsettings', 'webview_github_token')) {
                $table->text('webview_github_token')->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'webview_github_repo')) {
                $table->string('webview_github_repo', 191)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'webview_github_workflow')) {
                $table->string('webview_github_workflow', 100)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'webview_github_ref')) {
                $table->string('webview_github_ref', 100)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'webview_github_callback_secret')) {
                $table->string('webview_github_callback_secret', 191)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $cols = [
                'webview_build_driver',
                'webview_github_token',
                'webview_github_repo',
                'webview_github_workflow',
                'webview_github_ref',
                'webview_github_callback_secret',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('generalsettings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
