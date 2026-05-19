<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('university_course_intake_term', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('intake_term_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['university_course_id', 'intake_term_id'], 'course_intake_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_course_intake_term');
    }
};
