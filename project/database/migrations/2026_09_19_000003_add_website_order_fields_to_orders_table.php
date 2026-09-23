<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'product_id')) {
                    $table->unsignedBigInteger('product_id')->nullable()->after('customer_id')->index();
                }
                if (!Schema::hasColumn('orders', 'package_tier')) {
                    $table->string('package_tier', 100)->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('orders', 'domain_name')) {
                    $table->string('domain_name', 255)->nullable()->after('package_tier');
                }
                if (!Schema::hasColumn('orders', 'customer_email')) {
                    $table->string('customer_email', 255)->nullable()->after('domain_name');
                }
                if (!Schema::hasColumn('orders', 'customer_phone')) {
                    $table->string('customer_phone', 50)->nullable()->after('customer_email');
                }
                if (!Schema::hasColumn('orders', 'trx_id')) {
                    $table->string('trx_id', 100)->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('support_note');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $columns = ['product_id', 'package_tier', 'domain_name', 'customer_email', 'customer_phone', 'trx_id', 'notes'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
