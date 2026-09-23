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
        Schema::table('users', function (Blueprint $table) {
            // Facebook Chatbot Subscription Fields
            $table->string('active_plan')->nullable()->after('masking_rate')->comment('Active chatbot plan name');
            $table->timestamp('plan_expiry')->nullable()->after('active_plan')->comment('Chatbot plan expiry date');
            $table->integer('connected_pages_limit')->default(1)->after('plan_expiry')->comment('Maximum Facebook pages allowed');
            $table->integer('monthly_message_limit')->default(1000)->after('connected_pages_limit')->comment('Monthly message limit');
            $table->integer('messages_sent_this_month')->default(0)->after('monthly_message_limit')->comment('Messages sent counter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove chatbot subscription fields
            $table->dropColumn([
                'active_plan',
                'plan_expiry',
                'connected_pages_limit',
                'monthly_message_limit',
                'messages_sent_this_month'
            ]);
        });
    }
};
