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
        if (Schema::hasTable('bot_flows')) {
            return;
        }

        Schema::create('bot_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('facebook_page_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('flow_data'); // JSON data for the flow
            $table->boolean('is_active')->default(false);
            $table->string('trigger_type')->default('keyword'); // keyword, button, menu
            $table->json('trigger_values')->nullable(); // keywords or button IDs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_flows');
    }
};
