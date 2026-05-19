<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\UniversityCourseCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UniversityCourseLevelSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('university_course_levels')) {
            return;
        }

        $levels = Level::query()->orderBy('id')->pluck('id');
        if ($levels->isEmpty()) {
            return;
        }

        $catalogs = UniversityCourseCatalog::query()->orderBy('id')->pluck('id');
        if ($catalogs->isEmpty()) {
            return;
        }

        DB::table('university_course_levels')->truncate();

        $now = now();
        $batch = [];
        $batchSize = 1000;

        foreach ($catalogs as $catalogId) {
            foreach ($levels as $levelId) {
                $batch[] = [
                    'course_catalog_id' => $catalogId,
                    'level_id' => $levelId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= $batchSize) {
                    DB::table('university_course_levels')->insert($batch);
                    $batch = [];
                }
            }
        }

        if (! empty($batch)) {
            DB::table('university_course_levels')->insert($batch);
        }
    }
}
