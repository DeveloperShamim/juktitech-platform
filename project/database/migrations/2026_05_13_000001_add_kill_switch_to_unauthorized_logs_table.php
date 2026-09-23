<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unauthorized_logs', function (Blueprint $table) {
            $table->boolean('kill_switch')->default(false)->after('attempted_at');
        });
    }

    public function down(): void
    {
        Schema::table('unauthorized_logs', function (Blueprint $table) {
            $table->dropColumn('kill_switch');
        });
    }
};
