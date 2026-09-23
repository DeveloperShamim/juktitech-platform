<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'payment_mode')) {
                $table->string('payment_mode', 20)->default('manual');
            }
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'gateway_provider')) {
                $table->string('gateway_provider', 32)->nullable()->after('status');
            }
            if (!Schema::hasColumn('payment_transactions', 'gateway_invoice_id')) {
                $table->string('gateway_invoice_id', 64)->nullable()->after('gateway_provider');
                $table->index('gateway_invoice_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'gateway_invoice_id')) {
                $table->dropIndex(['gateway_invoice_id']);
                $table->dropColumn('gateway_invoice_id');
            }
            if (Schema::hasColumn('payment_transactions', 'gateway_provider')) {
                $table->dropColumn('gateway_provider');
            }
        });

        Schema::table('generalsettings', function (Blueprint $table) {
            if (Schema::hasColumn('generalsettings', 'payment_mode')) {
                $table->dropColumn('payment_mode');
            }
        });
    }
};
