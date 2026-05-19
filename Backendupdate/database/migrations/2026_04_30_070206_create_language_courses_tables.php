<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });

        Schema::create('language_school_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('language_school_branches')
                ->cascadeOnDelete();
            $table->foreignId('course_category_id')
                ->constrained('language_course_categories')
                ->restrictOnDelete();
            $table->string('course_name_from_school');
            $table->string('course_name_from_school_ar')->nullable();
            $table->string('slug');
            $table->decimal('hours_per_week', 5, 2)->nullable();
            $table->unsignedSmallInteger('lessons_per_week')->nullable();
            $table->string('min_level')->nullable();
            $table->unsignedSmallInteger('min_age')->nullable();
            $table->decimal('material_books_fee', 10, 2)->default(0);
            $table->decimal('registration_admin_fee', 10, 2)->default(0);
            $table->string('mandatory_additional_fee_name')->nullable();
            $table->decimal('mandatory_additional_fee', 10, 2)->default(0);

            // Week category pricing
            for ($i = 1; $i <= 7; $i++) {
                $table->unsignedSmallInteger("week_category_$i")->nullable();
                $table->decimal("weekly_fee_$i", 10, 2)->nullable();
            }

            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();

            $table->unique(['branch_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_courses');
        Schema::dropIfExists('language_course_categories');
    }
};
