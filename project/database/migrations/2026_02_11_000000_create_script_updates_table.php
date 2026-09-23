<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('script_updates', function (Blueprint $table) {
            $table->id();
            $table->string('version', 50)->unique();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('1=Latest available for download');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('script_updates');
    }
};
