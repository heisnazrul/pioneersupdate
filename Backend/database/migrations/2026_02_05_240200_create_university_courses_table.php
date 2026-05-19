<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('university_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('levels');
            $table->foreignId('subject_area_id')->nullable()->constrained('subject_areas');

            $table->string('name', 255);
            $table->string('ar_name', 300)->nullable();
            $table->string('slug', 255);

            $table->smallInteger('duration_value')->unsigned()->nullable();
            $table->string('duration_unit', 20)->default('month');

            $table->longText('overview')->nullable();
            $table->longText('ar_overview')->nullable();
            $table->string('awarding_body', 255)->nullable();
            $table->string('ar_awarding_body', 255)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'slug']);
            $table->index(['university_id', 'is_active']);
            $table->index(['level_id']);
            $table->index(['subject_area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_courses');
    }
};
