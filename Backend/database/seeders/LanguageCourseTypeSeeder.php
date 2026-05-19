<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LanguageCourseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id' => 1, 'name' => 'General English Course', 'ar_name' => 'دورة اللغة الإنجليزية العامة', 'created_at' => '2025-01-26 19:49:46', 'updated_at' => '2025-01-26 19:49:46'],
            ['id' => 2, 'name' => 'Intensive English Course', 'ar_name' => 'دورة اللغة الإنجليزية المكثفة', 'created_at' => '2025-01-26 19:49:52', 'updated_at' => '2025-01-26 19:49:52'],
            ['id' => 3, 'name' => 'Semi-Intensive English', 'ar_name' => 'دورة اللغة الإنجليزية شبه المكثفة', 'created_at' => '2025-01-27 03:13:22', 'updated_at' => '2025-01-27 03:13:22'],
            ['id' => 4, 'name' => 'IELTS Exam Preparation', 'ar_name' => 'دورة تحضير الآيلتس IELTS', 'created_at' => '2025-03-25 22:11:59', 'updated_at' => '2025-03-25 22:11:59'],
            ['id' => 5, 'name' => 'Super-Intensive English', 'ar_name' => 'دورة لغة إنجليزية عالية الكثافة', 'created_at' => '2025-03-25 22:12:23', 'updated_at' => '2025-03-25 22:12:23'],
        ];

        foreach ($rows as $row) {
            $createdAt = $row['created_at'] ? Carbon::parse($row['created_at']) : now();
            $updatedAt = $row['updated_at'] ? Carbon::parse($row['updated_at']) : $createdAt;

            DB::table('language_course_types')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'type_code' => Str::upper(Str::snake($row['name'])),
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'description' => null,
                    'ar_description' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]
            );
        }
    }
}
