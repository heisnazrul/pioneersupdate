<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('university_courses', function (Blueprint $table) {
            // drop old unique index
            $table->dropUnique('university_courses_university_id_slug_unique');

            // add new foreign key
            $table->foreignId('course_catalog_id')
                ->after('university_id')
                ->nullable()
                ->constrained('university_course_catalogs')
                ->cascadeOnDelete();

            // remove old columns
            $table->dropForeign(['subject_area_id']);
            $table->dropColumn(['subject_area_id', 'name', 'ar_name', 'slug']);

            // duration unit should be optional in override table
            $table->string('duration_unit', 20)->nullable()->change();
        });

        Schema::table('university_courses', function (Blueprint $table) {
            $table->unique(['university_id', 'course_catalog_id', 'level_id'], 'university_courses_ucourse_level_unique');
        });
    }

    public function down(): void
    {
        Schema::table('university_courses', function (Blueprint $table) {
            $table->dropUnique('university_courses_ucourse_level_unique');
            $table->dropForeign(['course_catalog_id']);
            $table->dropColumn(['course_catalog_id']);

            $table->foreignId('subject_area_id')->nullable()->constrained('subject_areas');
            $table->string('name', 255);
            $table->string('ar_name', 300)->nullable();
            $table->string('slug', 255);
            $table->unique(['university_id', 'slug']);

            $table->string('duration_unit', 20)->default('month')->change();
        });
    }
};
