<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unauthorized_logs', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('ip')->nullable();
            $table->text('reason');
            $table->text('url')->nullable();
            $table->timestamp('attempted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unauthorized_logs');
    }
};
