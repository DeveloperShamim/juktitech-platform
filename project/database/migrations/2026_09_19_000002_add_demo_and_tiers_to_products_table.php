<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDemoAndTiersToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'demo_admin_link')) {
                $table->string('demo_admin_link')->nullable()->after('demo_link');
            }
            if (!Schema::hasColumn('products', 'demo_admin_email')) {
                $table->string('demo_admin_email')->nullable()->after('demo_admin_link');
            }
            if (!Schema::hasColumn('products', 'demo_admin_password')) {
                $table->string('demo_admin_password')->nullable()->after('demo_admin_email');
            }
            if (!Schema::hasColumn('products', 'pricing_tiers')) {
                $table->longText('pricing_tiers')->nullable()->after('license_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $cols = ['demo_admin_link', 'demo_admin_email', 'demo_admin_password', 'pricing_tiers'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
