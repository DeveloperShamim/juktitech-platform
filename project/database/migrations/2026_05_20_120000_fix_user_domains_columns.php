<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ডোমেইন: status ENUM সীমাবদ্ধতা + বহু-বছর (billing_years) ট্র্যাকিং।
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_domains')) {
            return;
        }

        if (Schema::hasColumn('user_domains', 'status')) {
            DB::statement("ALTER TABLE user_domains MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
        }

        if (!Schema::hasColumn('user_domains', 'billing_years')) {
            Schema::table('user_domains', function ($table) {
                $table->unsignedTinyInteger('billing_years')->default(1)->after('recurring_amount');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('user_domains')) {
            return;
        }

        if (Schema::hasColumn('user_domains', 'billing_years')) {
            Schema::table('user_domains', function ($table) {
                $table->dropColumn('billing_years');
            });
        }

        if (Schema::hasColumn('user_domains', 'status')) {
            DB::statement("ALTER TABLE user_domains MODIFY COLUMN status ENUM('Active','Pending','Suspended','Expired','Cancelled') NOT NULL DEFAULT 'Pending'");
        }
    }
};
