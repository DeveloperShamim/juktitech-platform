<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_transactions')) {
            return;
        }

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id', 64)->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('BDT');
            $table->json('metadata')->nullable();
            $table->string('redirect_url', 500);
            $table->string('cancel_url', 500)->nullable();
            $table->string('webhook_url', 500)->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method', 32)->nullable();
            $table->string('sender_number', 20)->nullable();
            $table->string('transaction_id', 64)->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('webhook_sent_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
