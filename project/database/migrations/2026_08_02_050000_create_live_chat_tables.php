<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('live_chat_conversations')) {
            Schema::create('live_chat_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('session_key', 64)->unique();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('visitor_name', 120)->nullable();
                $table->string('visitor_email', 180)->nullable();
                $table->string('visitor_phone', 40)->nullable();
                $table->string('status', 20)->default('open')->index(); // open|closed
                $table->unsignedInteger('admin_unread')->default(0);
                $table->unsignedInteger('visitor_unread')->default(0);
                $table->timestamp('last_message_at')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('live_chat_messages')) {
            Schema::create('live_chat_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id')->index();
                $table->string('sender', 20); // visitor|admin
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                $table->foreign('conversation_id')
                    ->references('id')
                    ->on('live_chat_conversations')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('live_chat_messages');
        Schema::dropIfExists('live_chat_conversations');
    }
};
