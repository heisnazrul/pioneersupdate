<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('language_course_compares') && ! Schema::hasColumn('language_course_compares', 'weeks')) {
            Schema::table('language_course_compares', function (Blueprint $table) {
                $table->unsignedSmallInteger('weeks')->default(12)->after('course_id');
            });
        }

        if (Schema::hasTable('language_course_wishlists')) {
            Schema::table('language_course_wishlists', function (Blueprint $table) {
                $table->unique(['user_id', 'course_type', 'course_id'], 'language_course_wishlists_user_course_unique');
            });
        }

        if (Schema::hasTable('language_course_compares')) {
            Schema::table('language_course_compares', function (Blueprint $table) {
                $table->unique(['user_id', 'course_type', 'course_id'], 'language_course_compares_user_course_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('language_course_wishlists')) {
            Schema::table('language_course_wishlists', function (Blueprint $table) {
                $table->dropUnique('language_course_wishlists_user_course_unique');
            });
        }

        if (Schema::hasTable('language_course_compares')) {
            Schema::table('language_course_compares', function (Blueprint $table) {
                $table->dropUnique('language_course_compares_user_course_unique');
            });

            if (Schema::hasColumn('language_course_compares', 'weeks')) {
                Schema::table('language_course_compares', function (Blueprint $table) {
                    $table->dropColumn('weeks');
                });
            }
        }
    }
};
