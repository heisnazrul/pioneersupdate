<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Levels (study level)
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique(); // e.g., bachelor, master
            $table->string('name', 80);
            $table->string('ar_name', 120)->nullable();
            $table->smallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Subject Areas
        Schema::create('subject_areas', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('name', 120);
            $table->string('ar_name', 160)->nullable();
            $table->string('slug', 140)->unique();
            $table->smallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Intake Terms
        Schema::create('intake_terms', function (Blueprint $table) {
            $table->id();
            $table->string('key', 30)->unique();
            $table->string('name', 50);
            $table->string('ar_name', 80)->nullable();
            $table->tinyInteger('month_num')->nullable()->index(); // 1..12
            $table->smallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Language Tests
        Schema::create('language_tests', function (Blueprint $table) {
            $table->id();
            $table->string('key', 30)->unique();
            $table->string('name', 60);
            $table->string('ar_name', 80)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // University Course Tags
        Schema::create('university_course_tags', function (Blueprint $table) {
            $table->id();
            $table->string('key', 60)->unique();
            $table->string('name', 80);
            $table->string('ar_name', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_course_tags');
        Schema::dropIfExists('language_tests');
        Schema::dropIfExists('intake_terms');
        Schema::dropIfExists('subject_areas');
        Schema::dropIfExists('levels');
    }
};
