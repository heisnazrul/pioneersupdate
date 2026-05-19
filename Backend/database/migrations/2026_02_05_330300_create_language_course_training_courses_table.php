<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_course_training_courses', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 160)->unique();
            $table->foreignId('language_school_id')->constrained('language_schools')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('language_school_branches')->nullOnDelete();
            $table->foreignId('course_type_id')->constrained('language_course_types')->cascadeOnDelete();
            $table->foreignId('tag_id')->nullable()->constrained('language_course_tags')->nullOnDelete();
            $table->string('name', 200);
            $table->string('ar_name', 200)->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('ar_description')->nullable();
            $table->string('required_level', 10)->nullable();
            $table->string('study_time', 10)->nullable();
            $table->smallInteger('lessons_per_week')->nullable();
            $table->tinyInteger('min_age')->nullable();
            $table->string('start_date', 10)->nullable();
            $table->enum('fee_type', ['flat', 'weekly']);
            $table->decimal('fee_amount', 12, 2);
            $table->char('currency_code', 3)->default('USD');
            $table->decimal('registration_fee', 12, 2)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->boolean('visible')->default(true);
            $table->enum('status', ['draft', 'published', 'suspended'])->default('published');
            $table->timestamps();
            $table->softDeletes();

            $table->index('language_school_id', 'idx_lc_training_school');
            $table->index('branch_id', 'idx_lc_training_branch');
            $table->index('course_type_id', 'idx_lc_training_type');
            $table->index('tag_id', 'idx_lc_training_tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_training_courses');
    }
};
