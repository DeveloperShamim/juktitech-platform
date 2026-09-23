<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webview_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Internal label for admin');
            $table->string('app_name');
            $table->string('package_id');
            $table->string('website_url');
            $table->string('splash_tagline')->nullable();
            $table->text('splash_loading_message')->nullable();
            $table->string('exit_title')->nullable();
            $table->text('exit_message')->nullable();
            $table->string('exit_yes')->nullable();
            $table->string('exit_no')->nullable();
            $table->string('version_name')->default('1.0.0');
            $table->unsignedInteger('version_code')->default(1);
            $table->string('icon_path')->nullable();
            $table->string('status')->default('draft');
            $table->text('build_log')->nullable();
            $table->string('apk_path')->nullable();
            $table->timestamp('last_built_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webview_apps');
    }
};
