<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            foreach (['bkash', 'nagad', 'rocket'] as $provider) {
                $col = "payment_{$provider}_account_type";
                if (!Schema::hasColumn('generalsettings', $col)) {
                    $table->string($col, 20)->default('personal');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            foreach (['bkash', 'nagad', 'rocket'] as $provider) {
                $col = "payment_{$provider}_account_type";
                if (Schema::hasColumn('generalsettings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
