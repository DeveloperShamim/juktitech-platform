<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCacheSignalToStolenDomains extends Migration
{
    public function up()
    {
        Schema::table('stolen_domains', function (Blueprint $table) {
            // Cache bust signal token — admin এটা regenerate করলে client cache clear হবে
            $table->string('cache_signal', 64)->nullable()->after('status')
                ->comment('Cache bust token — change this to force client to clear their license cache');
        });
    }

    public function down()
    {
        Schema::table('stolen_domains', function (Blueprint $table) {
            $table->dropColumn('cache_signal');
        });
    }
}
