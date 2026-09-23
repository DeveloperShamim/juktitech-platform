<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * billing_cycle ENUM এ '2 Years' / '3 Years' সেভ হলে Data truncated হয়।
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_hostings')) {
            return;
        }

        if (Schema::hasColumn('user_hostings', 'billing_cycle')) {
            DB::statement("ALTER TABLE user_hostings MODIFY COLUMN billing_cycle VARCHAR(50) NOT NULL DEFAULT 'Yearly'");
        }

        if (!Schema::hasColumn('user_hostings', 'billing_years')) {
            Schema::table('user_hostings', function ($table) {
                $table->unsignedTinyInteger('billing_years')->default(1)->after('billing_cycle');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('user_hostings')) {
            return;
        }

        if (Schema::hasColumn('user_hostings', 'billing_years')) {
            Schema::table('user_hostings', function ($table) {
                $table->dropColumn('billing_years');
            });
        }

        if (Schema::hasColumn('user_hostings', 'billing_cycle')) {
            DB::statement("ALTER TABLE user_hostings MODIFY COLUMN billing_cycle ENUM('Monthly','Yearly') NOT NULL DEFAULT 'Yearly'");
        }
    }
};
