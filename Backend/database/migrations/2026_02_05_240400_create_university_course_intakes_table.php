<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
    }
};
