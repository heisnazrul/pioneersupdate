<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_school_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_school_id')->constrained('language_schools')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('slug', 160)->unique();
            $table->mediumText('description')->nullable();
            $table->mediumText('ar_description')->nullable();
            $table->json('gallery_urls')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_branches');
    }
};
