<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('language_course_training_courses', function (Blueprint $table): void {
            if (Schema::hasColumn('language_course_training_courses', 'slug')) {
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('language_course_training_courses', 'currency_code')) {
                $table->dropColumn('currency_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('language_course_training_courses', function (Blueprint $table): void {
            if (!Schema::hasColumn('language_course_training_courses', 'slug')) {
                $table->string('slug', 160)->nullable()->after('id');
                $table->unique('slug');
            }

            if (!Schema::hasColumn('language_course_training_courses', 'currency_code')) {
                $table->char('currency_code', 3)->default('USD')->after('fee_amount');
            }
        });
    }
};
