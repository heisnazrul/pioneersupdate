<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('language_school_branches', 'about_en')) {
            Schema::table('language_school_branches', function (Blueprint $table) {
                $table->text('about_en')->nullable()->after('slug');
                $table->text('about_ar')->nullable()->after('about_en');
            });
        }

        if (Schema::hasColumn('language_schools', 'about_en')) {
            $schools = DB::table('language_schools')
                ->select('id', 'about_en', 'about_ar')
                ->get();

            foreach ($schools as $school) {
                DB::table('language_school_branches')
                    ->where('school_id', $school->id)
                    ->whereNull('about_en')
                    ->whereNull('about_ar')
                    ->update([
                        'about_en' => $school->about_en,
                        'about_ar' => $school->about_ar,
                    ]);
            }

            Schema::table('language_schools', function (Blueprint $table) {
                $table->dropColumn(['about_en', 'about_ar']);
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('language_schools', 'about_en')) {
            Schema::table('language_schools', function (Blueprint $table) {
                $table->text('about_en')->nullable()->after('slug');
                $table->text('about_ar')->nullable()->after('about_en');
            });

            $schoolIds = DB::table('language_schools')->pluck('id');

            foreach ($schoolIds as $schoolId) {
                $branch = DB::table('language_school_branches')
                    ->where('school_id', $schoolId)
                    ->orderBy('id')
                    ->first(['about_en', 'about_ar']);

                if ($branch) {
                    DB::table('language_schools')
                        ->where('id', $schoolId)
                        ->update([
                            'about_en' => $branch->about_en,
                            'about_ar' => $branch->about_ar,
                        ]);
                }
            }
        }

        if (Schema::hasColumn('language_school_branches', 'about_en')) {
            Schema::table('language_school_branches', function (Blueprint $table) {
                $table->dropColumn(['about_en', 'about_ar']);
            });
        }
    }
};
