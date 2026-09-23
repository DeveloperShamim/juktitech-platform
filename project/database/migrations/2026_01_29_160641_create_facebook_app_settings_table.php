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
        Schema::create('facebook_app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->nullable(); // Facebook App ID
            $table->text('app_secret')->nullable(); // Facebook App Secret (encrypted)
            $table->string('verify_token')->nullable(); // Webhook Verify Token
            $table->string('graph_version')->default('v18.0'); // Graph API Version
            $table->string('webhook_url')->nullable(); // Auto-generated webhook URL
            $table->text('redirect_url')->nullable(); // OAuth Redirect URL
            $table->text('privacy_policy_url')->nullable(); // Privacy Policy URL
            $table->text('terms_of_service_url')->nullable(); // Terms of Service URL
            $table->boolean('is_connected')->default(false); // Connection status
            $table->text('page_access_token')->nullable(); // Default page access token
            $table->timestamp('connected_at')->nullable(); // Connection timestamp
            $table->text('webhook_fields')->nullable(); // JSON: subscribed webhook fields
            $table->text('permissions')->nullable(); // JSON: granted permissions
            $table->text('error_log')->nullable(); // Last error if any
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_app_settings');
    }
};
