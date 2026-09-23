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
        Schema::create('bot_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facebook_page_id')->constrained()->onDelete('cascade');
            $table->string('keyword');
            $table->text('reply_text');
            $table->enum('reply_type', ['text', 'image', 'product_carousel'])->default('text');
            $table->json('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_replies');
    }
};
