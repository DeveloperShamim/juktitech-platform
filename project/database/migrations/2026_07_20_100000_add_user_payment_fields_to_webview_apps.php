<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            if (!Schema::hasColumn('webview_apps', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('webview_apps', 'invoice_id')) {
                $table->string('invoice_id', 64)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('webview_apps', 'payment_status')) {
                $table->string('payment_status', 20)->default('unpaid')->after('invoice_id');
            }
            if (!Schema::hasColumn('webview_apps', 'build_price')) {
                $table->decimal('build_price', 10, 2)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('webview_apps', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('build_price');
            }
        });

        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'webview_app_build_price')) {
                $table->decimal('webview_app_build_price', 10, 2)->default(200)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('webview_apps', function (Blueprint $table) {
            foreach (['user_id', 'invoice_id', 'payment_status', 'build_price', 'paid_at'] as $col) {
                if (Schema::hasColumn('webview_apps', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('generalsettings', function (Blueprint $table) {
            if (Schema::hasColumn('generalsettings', 'webview_app_build_price')) {
                $table->dropColumn('webview_app_build_price');
            }
        });
    }
};
