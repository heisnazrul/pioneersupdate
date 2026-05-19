<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('name', 80);
            $table->string('ar_name', 120)->nullable();
            $table->smallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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

        Schema::create('intake_terms', function (Blueprint $table) {
            $table->id();
            $table->string('key', 30)->unique();
            $table->string('name', 50);
            $table->string('ar_name', 80)->nullable();
            $table->tinyInteger('month_num')->nullable()->index();
            $table->smallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('type')->default('public');
            $table->integer('established_year')->nullable();
            $table->string('website')->nullable();
            $table->integer('qs_ranking')->nullable();
            $table->integer('the_ranking')->nullable();
            $table->integer('shanghai_ranking')->nullable();
            $table->text('famous_for')->nullable();
            $table->text('ar_famous_for')->nullable();
            $table->text('fees')->nullable();
            $table->text('ar_fees')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

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

        Schema::create('university_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('course_catalog_id')->nullable()->constrained('university_course_catalogs')->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('levels');
            $table->smallInteger('duration_value')->unsigned()->nullable();
            $table->string('duration_unit', 20)->nullable();
            $table->decimal('first_year_fee', 12, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->longText('overview')->nullable();
            $table->longText('ar_overview')->nullable();
            $table->string('awarding_body', 255)->nullable();
            $table->string('ar_awarding_body', 255)->nullable();
            $table->longText('degree_requirement')->nullable();
            $table->longText('language_requirement')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'course_catalog_id', 'level_id'], 'university_courses_ucourse_level_unique');
            $table->index(['university_id', 'is_active']);
            $table->index(['level_id']);
        });

        Schema::create('university_course_intakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_course_id')->constrained('university_courses')->cascadeOnDelete();
            $table->foreignId('intake_term_id')->constrained('intake_terms')->cascadeOnDelete();
            $table->date('deadline_date')->nullable();
            $table->date('start_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_course_id', 'intake_term_id'], 'course_intake_unique');
            $table->index(['university_course_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_course_intakes');
        Schema::dropIfExists('university_courses');
        Schema::dropIfExists('university_course_levels');
        Schema::dropIfExists('university_course_catalogs');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('intake_terms');
        Schema::dropIfExists('subject_areas');
        Schema::dropIfExists('levels');
    }
};
