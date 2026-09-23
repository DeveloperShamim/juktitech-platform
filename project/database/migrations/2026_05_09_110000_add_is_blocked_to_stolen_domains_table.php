<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBlockedToStolenDomainsTable extends Migration
{
    public function up()
    {
        Schema::table('stolen_domains', function (Blueprint $table) {
            if (!Schema::hasColumn('stolen_domains', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('status')
                    ->comment('Admin can block/unblock stolen domain');
            }
        });
    }

    public function down()
    {
        Schema::table('stolen_domains', function (Blueprint $table) {
            if (Schema::hasColumn('stolen_domains', 'is_blocked')) {
                $table->dropColumn('is_blocked');
            }
        });
    }
}
