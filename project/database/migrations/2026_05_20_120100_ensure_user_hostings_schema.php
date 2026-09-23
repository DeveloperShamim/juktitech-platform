<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * হোস্টিং: status (Expired সহ), billing_cycle, billing_years, suspended_at — idempotent।
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_hostings')) {
            return;
        }

        if (Schema::hasColumn('user_hostings', 'status')) {
            DB::statement("ALTER TABLE user_hostings MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
        }

        if (Schema::hasColumn('user_hostings', 'billing_cycle')) {
            DB::statement("ALTER TABLE user_hostings MODIFY COLUMN billing_cycle VARCHAR(50) NOT NULL DEFAULT 'Yearly'");
        }

        if (!Schema::hasColumn('user_hostings', 'billing_years')) {
            Schema::table('user_hostings', function ($table) {
                $table->unsignedTinyInteger('billing_years')->default(1)->after('billing_cycle');
            });
        }

        if (!Schema::hasColumn('user_hostings', 'suspended_at')) {
            Schema::table('user_hostings', function ($table) {
                $table->timestamp('suspended_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // Intentionally empty — prior migrations handle rollback.
    }
};
