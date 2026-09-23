<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_key')) {
                $table->string('category_key')->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'category_name')) {
                $table->string('category_name')->nullable()->after('category_key');
            }
            if (!Schema::hasColumn('products', 'badge_text')) {
                $table->string('badge_text')->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('products', 'badge_class')) {
                $table->string('badge_class')->nullable()->after('badge_text');
            }
            if (!Schema::hasColumn('products', 'features')) {
                $table->json('features')->nullable()->after('badge_class');
            }
            if (!Schema::hasColumn('products', 'license_type')) {
                $table->string('license_type')->default('আজীবন লাইসেন্স')->after('features');
            }
            if (!Schema::hasColumn('products', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('license_type');
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
            $cols = ['category_key', 'category_name', 'badge_text', 'badge_class', 'features', 'license_type', 'sort_order'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
