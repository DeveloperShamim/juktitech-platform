<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('script_updates', function (Blueprint $table) {
            $table->string('script_name', 100)->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('script_updates', function (Blueprint $table) {
            $table->dropColumn('script_name');
        });
    }
};
