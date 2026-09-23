<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->string('install_domain')->nullable()->after('customer_phone')->index();
            $table->unsignedBigInteger('license_id')->nullable()->after('install_domain')->index();
        });
    }

    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropIndex(['install_domain']);
            $table->dropIndex(['license_id']);
            $table->dropColumn(['install_domain', 'license_id']);
        });
    }
};
