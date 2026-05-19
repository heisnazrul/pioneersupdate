<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LanguageSchoolBranchHighSeasonFeeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ["id"=>"2","course_id"=>"45","start_date"=>"2025-06-15","end_date"=>"2025-08-31","fee"=>"35.00","created_at"=>"2025-04-02 22:38:42","updated_at"=>"2025-04-02 22:38:42"],
            ["id"=>"3","course_id"=>"46","start_date"=>"2025-06-15","end_date"=>"2025-08-31","fee"=>"35.00","created_at"=>"2025-04-02 22:38:46","updated_at"=>"2025-04-02 22:38:46"],
            ["id"=>"4","course_id"=>"47","start_date"=>"2025-06-15","end_date"=>"2025-08-31","fee"=>"35.00","created_at"=>"2025-04-02 22:38:51","updated_at"=>"2025-04-02 22:38:51"],
        ];

        foreach ($rows as $row) {
            $courseId = (int) $row['course_id'];
            $branchId = (int) (DB::table('language_school_courses')->where('id', $courseId)->value('branch_id') ?? 0);
            if (!$branchId) {
                continue;
            }

            DB::table('language_school_branch_high_season_fees')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'branch_id' => $branchId,
                    'week_start' => Carbon::parse($row['start_date'])->toDateString(),
                    'week_end' => Carbon::parse($row['end_date'])->toDateString(),
                    'fee' => (float) $row['fee'],
                    'created_at' => Carbon::parse($row['created_at']),
                    'updated_at' => Carbon::parse($row['updated_at']),
                ]
            );
        }
    }
}
