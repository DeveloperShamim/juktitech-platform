<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_digital')->default(false)->after('status');
            $table->string('google_drive_file_id')->nullable()->after('is_digital');
            $table->string('digital_file_name')->nullable()->after('google_drive_file_id');
            $table->unsignedSmallInteger('download_limit')->default(3)->after('digital_file_name');
        });

        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('invoice_id')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending');
            $table->string('download_token', 64)->unique();
            $table->unsignedSmallInteger('download_count')->default(0);
            $table->unsignedSmallInteger('download_limit')->default(3);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_orders');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_digital', 'google_drive_file_id', 'digital_file_name', 'download_limit']);
        });
    }
};
