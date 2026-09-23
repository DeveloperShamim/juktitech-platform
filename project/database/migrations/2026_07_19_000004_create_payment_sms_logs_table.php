<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_sms_logs')) {
            return;
        }

        Schema::create('payment_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 20)->nullable();
            $table->string('transaction_id', 64);
            $table->decimal('amount', 12, 2);
            $table->string('sender_number', 20)->nullable();
            $table->text('raw_message');
            $table->string('sms_from', 64)->nullable();
            $table->timestamp('received_at')->nullable();
            $table->enum('status', ['available', 'matched', 'ignored'])->default('available');
            $table->unsignedBigInteger('payment_transaction_id')->nullable();
            $table->timestamp('matched_at')->nullable();
            $table->timestamps();

            $table->unique('transaction_id');
            $table->index('status');
            $table->index('amount');
            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_sms_logs');
    }
};
