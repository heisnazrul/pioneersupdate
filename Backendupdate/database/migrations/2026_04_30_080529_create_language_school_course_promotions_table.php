<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_school_course_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('language_school_courses')
                ->cascadeOnDelete();
            $table->decimal('promotion_percentage', 5, 2);
            $table->date('promo_from')->nullable();
            $table->date('promo_to')->nullable();
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_course_promotions');
    }
};
