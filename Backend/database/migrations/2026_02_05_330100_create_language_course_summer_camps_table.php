<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_course_summer_camps', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 160)->unique();
            $table->foreignId('branch_id')->constrained('language_school_branches')->cascadeOnDelete();
            $table->foreignId('course_type_id')->constrained('language_course_types')->cascadeOnDelete();
            $table->foreignId('tag_id')->nullable()->constrained('language_course_tags')->nullOnDelete();
            $table->string('name', 200);
            $table->string('ar_name', 200)->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('ar_description')->nullable();
            $table->string('required_level', 10)->nullable();
            $table->string('study_time', 10)->nullable();
            $table->smallInteger('lessons_per_week')->nullable();
            $table->string('age_range', 50)->nullable();
            $table->string('start_date', 10)->nullable();
            $table->date('payment_deadline')->nullable();
            $table->enum('fee_type', ['flat', 'weekly']);
            $table->decimal('fee_amount', 12, 2);
            $table->decimal('registration_fee', 12, 2)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->boolean('visible')->default(true);
            $table->enum('status', ['draft', 'published', 'suspended'])->default('published');
            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_id', 'idx_lc_camps_branch');
            $table->index('course_type_id', 'idx_lc_camps_type');
            $table->index('tag_id', 'idx_lc_camps_tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_summer_camps');
    }
};
