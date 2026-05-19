<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('language_course_online_courses', 'thumbnail')) {
            Schema::table('language_course_online_courses', function (Blueprint $table): void {
                $table->string('thumbnail', 255)->nullable()->after('registration_fee');
            });
        }

        if (! Schema::hasColumn('language_course_summer_camps', 'thumbnail')) {
            Schema::table('language_course_summer_camps', function (Blueprint $table): void {
                $table->string('thumbnail', 255)->nullable()->after('registration_fee');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('language_course_online_courses', 'thumbnail')) {
            Schema::table('language_course_online_courses', function (Blueprint $table): void {
                $table->dropColumn('thumbnail');
            });
        }

        if (Schema::hasColumn('language_course_summer_camps', 'thumbnail')) {
            Schema::table('language_course_summer_camps', function (Blueprint $table): void {
                $table->dropColumn('thumbnail');
            });
        }
    }
};
