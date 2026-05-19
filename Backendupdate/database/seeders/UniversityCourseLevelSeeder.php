<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityCourseLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = DB::table('levels')->pluck('id');
        $catalogs = DB::table('university_course_catalogs')->pluck('id');

        foreach ($catalogs as $catalogId) {
            foreach ($levels as $levelId) {
                DB::table('university_course_levels')->updateOrInsert(
                    [
                        'course_catalog_id' => $catalogId,
                        'level_id' => $levelId,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
