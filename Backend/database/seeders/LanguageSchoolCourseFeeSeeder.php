<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LanguageSchoolCourseFeeSeeder extends Seeder
{
    public function run(): void
    {
        $path = '/Users/macbookpro2012/Downloads/course_fees.json';

        if (!file_exists($path)) {
            throw new \RuntimeException("course_fees.json not found at {$path}");
        }

        $payload = json_decode(file_get_contents($path), true);
        if (!is_array($payload)) {
            throw new \RuntimeException("Invalid JSON in {$path}");
        }

        $rows = [];
        foreach ($payload as $entry) {
            if (is_array($entry) && ($entry['type'] ?? null) === 'table' && ($entry['name'] ?? null) === 'course_fees') {
                $rows = $entry['data'] ?? [];
                break;
            }
        }

        foreach ($rows as $row) {
            $courseId = isset($row['course_id']) ? (int) $row['course_id'] : null;
            $weekNumber = isset($row['week_number']) ? (int) $row['week_number'] : null;
            $fee = isset($row['fee']) ? (float) $row['fee'] : null;

            if (!$courseId || !$weekNumber || $fee === null) {
                continue;
            }

            DB::table('language_school_course_fees')->updateOrInsert(
                [
                    'language_school_course_id' => $courseId,
                    'week_number' => $weekNumber,
                ],
                [
                    'fee' => $fee,
                    'valid_from' => null,
                    'valid_to' => null,
                    'price_split' => 'yes',
                    'created_at' => isset($row['created_at']) ? Carbon::parse($row['created_at']) : now(),
                    'updated_at' => isset($row['updated_at']) ? Carbon::parse($row['updated_at']) : now(),
                ]
            );
        }
    }
}
