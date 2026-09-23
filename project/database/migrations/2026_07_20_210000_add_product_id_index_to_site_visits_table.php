<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('site_visits')) {
            return;
        }

        Schema::table('site_visits', function (Blueprint $table) {
            $table->index(['product_id', 'created_at'], 'site_visits_product_created_idx');
            $table->index(['path', 'created_at'], 'site_visits_path_created_idx');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('site_visits')) {
            return;
        }

        Schema::table('site_visits', function (Blueprint $table) {
            $table->dropIndex('site_visits_product_created_idx');
            $table->dropIndex('site_visits_path_created_idx');
        });
    }
};
