<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LanguageSchoolCourseSeeder extends Seeder
{
    public function run(): void
    {
        $path = '/Users/macbookpro2012/Downloads/courses.json';

        if (!file_exists($path)) {
            throw new \RuntimeException("courses.json not found at {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $cleanLine = rtrim(trim($line), ',');
            $row = json_decode($cleanLine, true);
            if (!is_array($row) || empty($row['id'])) {
                continue;
            }

            $branchId = $row['school_branch_id'] ?? null;
            $name = $row['name'] ?? '';
            $slugBase = $name . '-' . ($branchId ?? 'branch') . '-' . $row['id'];

            DB::table('language_school_courses')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'branch_id' => $branchId ? (int) $branchId : null,
                    'language_course_type_id' => isset($row['coursetype_id']) ? (int) $row['coursetype_id'] : null,
                    'language_course_tag_id' => isset($row['tag_id']) ? (int) $row['tag_id'] : null,
                    'slug' => Str::slug($slugBase),
                    'name' => $name,
                    'ar_name' => $row['ar_name'] ?? null,
                    'description' => $row['description'] ?? null,
                    'ar_description' => $row['ar_description'] ?? null,
                    'start_day' => $row['start_date'] ?? null,
                    'required_level' => $row['required_level'] ?? null,
                    'study_time' => $row['study_time'] ?? null,
                    'lessons_per_week' => $row['lessons_per_week'] ?? null,
                    'min_age' => $row['min_age'] ?? null,
                    'created_at' => isset($row['created_at']) ? Carbon::parse($row['created_at']) : now(),
                    'updated_at' => isset($row['updated_at']) ? Carbon::parse($row['updated_at']) : now(),
                ]
            );
        }
    }
}
