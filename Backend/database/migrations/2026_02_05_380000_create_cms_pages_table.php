<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->enum('app', ['courseenglish', 'university']);
            $table->string('slug');
            $table->string('title');
            $table->string('ar_title')->nullable();
            $table->text('content')->nullable();
            $table->text('ar_content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();

            $table->unique(['app', 'slug']);
            $table->index(['app', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
