<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('university_course_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('university_courses')->cascadeOnDelete();
            $table->foreignId('campus_id')->constrained('university_campuses')->cascadeOnDelete();

            $table->decimal('first_year_fee', 12, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->string('note', 255)->nullable();
            $table->string('ar_note', 255)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'campus_id']);
            $table->index(['course_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_course_fees');
    }
};
