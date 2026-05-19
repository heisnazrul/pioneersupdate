<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_school_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('language_school_branches')->cascadeOnDelete();
            $table->foreignId('language_course_type_id')->constrained('language_course_types')->cascadeOnDelete();
            $table->foreignId('language_course_tag_id')->nullable()->constrained('language_course_tags')->nullOnDelete();
            $table->string('slug', 160)->unique();
            $table->string('name', 200);
            $table->string('ar_name', 200)->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('ar_description')->nullable();
            $table->string('start_day', 32)->nullable();
            $table->string('required_level', 32)->nullable();
            $table->string('study_time', 30)->nullable();
            $table->string('lessons_per_week', 30)->nullable();
            $table->string('min_age', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_courses');
    }
};
