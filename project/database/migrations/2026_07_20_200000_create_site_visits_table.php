<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500)->nullable();
            $table->string('page_type', 32)->default('other');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('source', 32)->default('direct');
            $table->string('referer', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['source', 'created_at']);
            $table->index(['page_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
