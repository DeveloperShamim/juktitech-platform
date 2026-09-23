<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gemini_settings', function (Blueprint $table) {
            $table->id();
            $table->text('api_key')->nullable();
            $table->string('model', 80)->default('gemini-2.0-flash');
            $table->boolean('is_enabled')->default(false);
            $table->boolean('enable_website_chat')->default(true);
            $table->boolean('enable_facebook_chatbot')->default(true);
            $table->text('system_prompt')->nullable();
            $table->text('knowledge_base')->nullable();
            $table->string('welcome_message', 500)->nullable();
            $table->unsignedInteger('max_output_tokens')->default(1024);
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gemini_settings');
    }
};
