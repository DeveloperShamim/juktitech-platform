<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLicenseGenerationFields extends Migration
{
    public function up()
    {
        // users টেবিলে free_license_generation কলাম যোগ করা
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('free_license_generation')->default(false)->after('api_key')
                ->comment('Admin দ্বারা এই ইউজারকে বিনামূল্যে লাইসেন্স জেনারেট করার অনুমতি');
        });

        // generalsettings টেবিলে license_generation_price কলাম যোগ করা
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->decimal('license_generation_price', 10, 2)->default(1000.00)->nullable()
                ->comment('লাইসেন্স জেনারেশন চার্জ (টাকা)');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('free_license_generation');
        });

        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('license_generation_price');
        });
    }
}
