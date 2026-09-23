<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $columns = [
                'fraud_pathao_user' => 'string',
                'fraud_pathao_password' => 'string',
                'fraud_steadfast_user' => 'string',
                'fraud_steadfast_password' => 'string',
                'fraud_redx_phone' => 'string',
                'fraud_redx_password' => 'string',
                'fraud_paperfly_user' => 'string',
                'fraud_paperfly_password' => 'string',
                'fraud_carrybee_phone' => 'string',
                'fraud_carrybee_password' => 'string',
            ];

            foreach ($columns as $name => $type) {
                if (!Schema::hasColumn('generalsettings', $name)) {
                    if (str_contains($name, 'password')) {
                        $table->text($name)->nullable();
                    } else {
                        $table->{$type}($name)->nullable();
                    }
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            foreach ([
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
            ] as $col) {
                if (Schema::hasColumn('generalsettings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
