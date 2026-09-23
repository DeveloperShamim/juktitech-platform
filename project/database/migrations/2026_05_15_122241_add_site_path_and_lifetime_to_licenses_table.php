<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // Client site root path (.env ফাইল লেখার জন্য)
            $table->string('site_path', 500)->nullable()->after('signature_key');
            // lifetime license এর জন্য expiry_date nullable করা হচ্ছে
            $table->dateTime('expiry_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('site_path');
            $table->dateTime('expiry_date')->nullable(false)->change();
        });
    }
};
