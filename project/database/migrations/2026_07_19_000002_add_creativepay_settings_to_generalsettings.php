<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'payment_bkash_number')) {
                $table->string('payment_bkash_number', 20)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'payment_nagad_number')) {
                $table->string('payment_nagad_number', 20)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'payment_rocket_number')) {
                $table->string('payment_rocket_number', 20)->nullable();
            }
            if (!Schema::hasColumn('generalsettings', 'payment_auto_approve')) {
                $table->boolean('payment_auto_approve')->default(false);
            }
            if (!Schema::hasColumn('generalsettings', 'payment_instructions')) {
                $table->text('payment_instructions')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $cols = ['payment_bkash_number', 'payment_nagad_number', 'payment_rocket_number', 'payment_auto_approve', 'payment_instructions'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('generalsettings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
