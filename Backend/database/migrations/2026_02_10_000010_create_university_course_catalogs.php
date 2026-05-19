<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('university_course_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_area_id')->nullable()->constrained('subject_areas')->nullOnDelete();
            $table->string('name', 255);
            $table->string('ar_name', 300)->nullable();
            $table->string('slug', 255)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('university_course_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_catalog_id')->constrained('university_course_catalogs')->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('levels')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_catalog_id', 'level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_course_levels');
        Schema::dropIfExists('university_course_catalogs');
    }
};
