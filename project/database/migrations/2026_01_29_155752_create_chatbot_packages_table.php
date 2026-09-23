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
        Schema::create('chatbot_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // প্যাকেজের নাম (Basic, Premium, Pro)
            $table->string('name_bn')->nullable(); // বাংলা নাম
            $table->text('description')->nullable(); // প্যাকেজের বিবরণ
            $table->decimal('price', 10, 2); // মূল্য
            $table->integer('duration_days')->default(365); // মেয়াদ (দিনে) — ডিফল্ট বাৎসরিক
            $table->integer('page_limit')->default(1); // কতটি Facebook page connect করা যাবে
            $table->integer('message_limit')->default(1000); // মাসিক message limit
            $table->integer('auto_reply_limit')->default(50); // কতগুলো auto-reply rule তৈরি করা যাবে
            $table->integer('comment_automation_limit')->default(50); // কতগুলো comment automation rule
            $table->boolean('product_carousel')->default(false); // Product carousel feature আছে কিনা
            $table->boolean('analytics')->default(false); // Analytics feature
            $table->boolean('broadcast')->default(false); // Broadcast messaging
            $table->boolean('is_popular')->default(false); // Popular badge দেখাবে কিনা
            $table->boolean('status')->default(true); // Active/Inactive
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_packages');
    }
};
