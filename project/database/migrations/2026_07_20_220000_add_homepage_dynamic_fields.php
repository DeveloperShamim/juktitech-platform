<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('about_section_contents')) {
            Schema::table('about_section_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('about_section_contents', 'hero_badge')) {
                    $table->string('hero_badge')->nullable()->after('subtitle');
                }
                if (!Schema::hasColumn('about_section_contents', 'hero_data')) {
                    $table->longText('hero_data')->nullable()->after('hero_badge');
                }
                if (!Schema::hasColumn('about_section_contents', 'hero_mockup_image')) {
                    $table->string('hero_mockup_image')->nullable()->after('hero_data');
                }
            });
        }

        if (Schema::hasTable('section_contents')) {
            Schema::table('section_contents', function (Blueprint $table) {
                if (!Schema::hasColumn('section_contents', 'badge')) {
                    $table->string('badge')->nullable()->after('section_key');
                }
                if (!Schema::hasColumn('section_contents', 'description')) {
                    $table->text('description')->nullable()->after('subtitle');
                }
                if (!Schema::hasColumn('section_contents', 'extra_json')) {
                    $table->longText('extra_json')->nullable()->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('about_section_contents')) {
            Schema::table('about_section_contents', function (Blueprint $table) {
                foreach (['hero_badge', 'hero_data', 'hero_mockup_image'] as $col) {
                    if (Schema::hasColumn('about_section_contents', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('section_contents')) {
            Schema::table('section_contents', function (Blueprint $table) {
                foreach (['badge', 'description', 'extra_json'] as $col) {
                    if (Schema::hasColumn('section_contents', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
