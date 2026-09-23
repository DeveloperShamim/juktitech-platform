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
        Schema::create('comment_automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facebook_page_id')->constrained()->onDelete('cascade');
            $table->string('trigger_word');
            $table->text('reply_comment')->nullable();
            $table->text('private_reply')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_automations');
    }
};
