<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_course_compares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('course_type', 50);
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->unique(['user_id', 'course_type', 'course_id'], 'lc_compare_unique');
            $table->index(['course_type', 'course_id'], 'lc_compare_course_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_compares');
    }
};

