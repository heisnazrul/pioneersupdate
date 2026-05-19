<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('language_course_summer_camp_details', function (Blueprint $table): void {
            if (!Schema::hasColumn('language_course_summer_camp_details', 'images')) {
                $table->json('images')->nullable()->after('ar_safeguarding');
            }
        });
    }

    public function down(): void
    {
        Schema::table('language_course_summer_camp_details', function (Blueprint $table): void {
            if (Schema::hasColumn('language_course_summer_camp_details', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};

