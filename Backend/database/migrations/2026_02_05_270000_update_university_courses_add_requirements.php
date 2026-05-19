<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop legacy course fees table now that fees are merged into courses
        Schema::dropIfExists('university_course_fees');

        Schema::table('university_courses', function (Blueprint $table) {
            $table->decimal('first_year_fee', 12, 2)->nullable()->after('duration_unit');
            $table->char('currency', 3)->default('USD')->after('first_year_fee');
            $table->longText('degree_requirement')->nullable()->after('ar_awarding_body');
            $table->longText('language_requirement')->nullable()->after('degree_requirement');
        });
    }

    public function down(): void
    {
        Schema::table('university_courses', function (Blueprint $table) {
            $table->dropColumn(['first_year_fee', 'currency', 'degree_requirement', 'language_requirement']);
        });

        Schema::create('university_course_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('university_courses')->onDelete('cascade');
            $table->foreignId('campus_id')->constrained('university_campuses')->onDelete('cascade');
            $table->decimal('first_year_fee', 12, 2)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->string('note', 255)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->unique(['course_id', 'campus_id']);
            $table->index(['course_id', 'is_active']);
        });
    }
};
