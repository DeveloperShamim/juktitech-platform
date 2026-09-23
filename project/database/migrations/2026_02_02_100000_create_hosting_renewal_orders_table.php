<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_renewal_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_hosting_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('invoice_id')->nullable();
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index('user_id');
            $table->index('user_hosting_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_renewal_orders');
    }
};
