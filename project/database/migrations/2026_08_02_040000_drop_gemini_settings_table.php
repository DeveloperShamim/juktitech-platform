<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('gemini_settings');
    }

    public function down(): void
    {
        // intentionally empty — Gemini feature removed
    }
};
