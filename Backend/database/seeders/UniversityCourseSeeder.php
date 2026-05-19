<?php

namespace Database\Seeders;

use App\Models\IntakeTerm;
use App\Models\Level;
use App\Models\University;
use App\Models\UniversityCourseCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UniversityCourseSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('university_courses')) {
            return;
        }

        // Clear existing data
        DB::table('university_course_intakes')->delete();
        DB::table('university_courses')->delete();

        $universities = University::query()->orderBy('id')->get(['id']);
        if ($universities->isEmpty()) {
            $this->command->warn('No universities found. Skipping.');
            return;
        }

        $catalogs = UniversityCourseCatalog::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id']);
        if ($catalogs->isEmpty()) {
            $this->command->warn('No course catalogs found. Run UniversityCourseCatalogSeeder first.');
            return;
        }

        $levels = Level::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'key']);
        if ($levels->isEmpty()) {
            $this->command->warn('No levels found. Run LevelSeeder first.');
            return;
        }

        $intakeTerms = IntakeTerm::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id']);

        $now = now();

        // Duration map per level key
        $durationMap = [
            'foundation' => [1, 'year'],
            'international-foundation' => [1, 'year'],
            'bachelor' => [3, 'years'],
            'bachelor-top-up' => [1, 'year'],
            'integrated-master' => [4, 'years'],
            'pre-masters' => [1, 'year'],
            'graduate-diploma' => [1, 'year'],
            'postgraduate-certificate' => [1, 'year'],
            'postgraduate-diploma' => [1, 'year'],
            'masters' => [1, 'year'],
            'mba' => [1, 'year'],
            'mres' => [1, 'year'],
            'phd-doctorate' => [3, 'years'],
            'professional-doctorate' => [3, 'years'],
            'short-course' => [6, 'months'],
            'distance-learning' => [2, 'years'],
        ];

        $total = $universities->count() * $catalogs->count() * $levels->count();
        $this->command->info("Seeding courses: {$universities->count()} universities × {$catalogs->count()} catalogs × {$levels->count()} levels = {$total} rows");

        $batch = [];
        $batchSize = 500;

        foreach ($universities as $university) {
            foreach ($catalogs as $catalog) {
                foreach ($levels as $level) {
                    $dur = $durationMap[$level->key] ?? [1, 'year'];

                    $batch[] = [
                        'university_id' => $university->id,
                        'course_catalog_id' => $catalog->id,
                        'level_id' => $level->id,
                        'duration_value' => $dur[0],
                        'duration_unit' => $dur[1],
                        'overview' => null,
                        'ar_overview' => null,
                        'awarding_body' => null,
                        'ar_awarding_body' => null,
                        'first_year_fee' => null,
                        'degree_requirement' => null,
                        'language_requirement' => null,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($batch) >= $batchSize) {
                        DB::table('university_courses')->insert($batch);
                        $batch = [];
                    }
                }
            }
        }

        if (!empty($batch)) {
            DB::table('university_courses')->insert($batch);
        }

        $this->command->info('Courses inserted. Assigning intake terms via SQL...');

        // Assign all intake terms to every course using raw SQL cross join.
        // This avoids loading 148K+ IDs into PHP memory.
        if ($intakeTerms->isNotEmpty()) {
            $intakeIds = $intakeTerms->pluck('id')->implode(', ');
            $nowStr = $now->format('Y-m-d H:i:s');

            DB::statement("
                INSERT INTO university_course_intakes
                    (university_course_id, intake_term_id, deadline_date, start_date, is_active, created_at, updated_at)
                SELECT
                    uc.id,
                    it.id,
                    NULL,
                    NULL,
                    1,
                    '{$nowStr}',
                    '{$nowStr}'
                FROM university_courses uc
                CROSS JOIN intake_terms it
                WHERE it.id IN ({$intakeIds})
            ");

            $this->command->info('Intake terms assigned.');
        }

        $count = DB::table('university_courses')->count();
        $this->command->info("Done! Total courses seeded: {$count}");
    }
}
