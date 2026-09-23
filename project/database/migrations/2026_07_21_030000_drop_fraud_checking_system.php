<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fraud_checks')) {
            Schema::drop('fraud_checks');
        }

        if (Schema::hasTable('fraud_packages')) {
            Schema::drop('fraud_packages');
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach ([
                    'package_id',
                    'fraud_check_limit',
                    'fraud_check_expiry',
                    'fraud_check_daily_limit',
                    'today_check_count',
                    'last_check_date',
                ] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('generalsettings')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                foreach ([
                    'bd_courier_api_key',
                    'fraud_pathao_user',
                    'fraud_pathao_password',
                    'fraud_steadfast_user',
                    'fraud_steadfast_password',
                    'fraud_redx_phone',
                    'fraud_redx_password',
                    'fraud_paperfly_user',
                    'fraud_paperfly_password',
                    'fraud_carrybee_phone',
                    'fraud_carrybee_password',
                ] as $column) {
                    if (Schema::hasColumn('generalsettings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Irreversible cleanup migration — fraud checking system removed intentionally.
    }
};
