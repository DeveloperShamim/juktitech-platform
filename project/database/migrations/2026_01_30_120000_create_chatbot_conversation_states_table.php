<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_conversation_states', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id', 50)->comment('Facebook Messenger user PSID');
            $table->foreignId('facebook_page_id')->constrained()->onDelete('cascade');
            $table->foreignId('flow_id')->constrained('bot_flows')->onDelete('cascade');
            $table->string('current_node_id', 100)->nullable()->comment('Current node in flow');
            $table->timestamps();
            $table->unique(['sender_id', 'facebook_page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversation_states');
    }
};
