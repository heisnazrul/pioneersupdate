<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LanguageCourseTagSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id' => 1, 'name' => 'Top Rated', 'ar_name' => 'أعلى تقييم', 'created_at' => '2025-01-27 03:09:31', 'updated_at' => '2025-01-27 03:09:31'],
            ['id' => 2, 'name' => 'Most Requested', 'ar_name' => 'الأكثر طلبًا', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'name' => 'Best Offer', 'ar_name' => 'أفضل عرض', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'name' => 'Beautiful Location', 'ar_name' => 'موقع جميل', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'name' => 'Best Teaching', 'ar_name' => 'أفضل تعليم', 'created_at' => null, 'updated_at' => null],
        ];

        foreach ($rows as $row) {
            $createdAt = $row['created_at'] ? Carbon::parse($row['created_at']) : now();
            $updatedAt = $row['updated_at'] ? Carbon::parse($row['updated_at']) : $createdAt;

            DB::table('language_course_tags')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'tag_code' => Str::upper(Str::snake($row['name'])),
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
