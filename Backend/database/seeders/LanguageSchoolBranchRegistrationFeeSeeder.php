<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LanguageSchoolBranchRegistrationFeeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ["id"=>"5","school_branch_id"=>"6","fee"=>"95.00","created_at"=>"2025-03-26 02:53:10","updated_at"=>"2025-03-26 02:53:10"],
            ["id"=>"6","school_branch_id"=>"7","fee"=>"95.00","created_at"=>"2025-03-26 02:53:12","updated_at"=>"2025-03-26 02:53:12"],
            ["id"=>"7","school_branch_id"=>"8","fee"=>"95.00","created_at"=>"2025-03-26 02:53:16","updated_at"=>"2025-03-26 02:53:16"],
            ["id"=>"8","school_branch_id"=>"9","fee"=>"65.00","created_at"=>"2025-04-02 22:40:00","updated_at"=>"2025-04-02 22:40:00"],
            ["id"=>"9","school_branch_id"=>"21","fee"=>"100.00","created_at"=>"2025-04-02 22:40:55","updated_at"=>"2025-04-02 22:40:55"],
            ["id"=>"10","school_branch_id"=>"12","fee"=>"80.00","created_at"=>"2025-04-02 22:41:19","updated_at"=>"2025-04-02 22:41:19"],
            ["id"=>"11","school_branch_id"=>"22","fee"=>"50.00","created_at"=>"2025-04-02 22:45:01","updated_at"=>"2025-04-02 22:45:01"],
            ["id"=>"12","school_branch_id"=>"13","fee"=>"100.00","created_at"=>"2025-04-02 22:45:16","updated_at"=>"2025-04-02 22:45:16"],
            ["id"=>"13","school_branch_id"=>"23","fee"=>"120.00","created_at"=>"2025-04-02 22:45:38","updated_at"=>"2025-04-02 22:45:38"],
            ["id"=>"14","school_branch_id"=>"20","fee"=>"80.00","created_at"=>"2025-04-02 22:46:35","updated_at"=>"2025-04-02 22:46:35"],
            ["id"=>"15","school_branch_id"=>"19","fee"=>"50.00","created_at"=>"2025-04-02 22:47:40","updated_at"=>"2025-04-02 22:47:40"],
            ["id"=>"16","school_branch_id"=>"18","fee"=>"50.00","created_at"=>"2025-04-02 22:47:55","updated_at"=>"2025-04-02 22:47:55"],
            ["id"=>"17","school_branch_id"=>"24","fee"=>"55.00","created_at"=>"2025-04-02 22:48:40","updated_at"=>"2025-04-02 22:48:40"],
            ["id"=>"18","school_branch_id"=>"17","fee"=>"90.00","created_at"=>"2025-04-02 22:48:52","updated_at"=>"2025-04-02 22:48:52"],
            ["id"=>"19","school_branch_id"=>"10","fee"=>"98.00","created_at"=>"2025-04-02 22:49:06","updated_at"=>"2025-04-02 22:49:06"],
            ["id"=>"20","school_branch_id"=>"16","fee"=>"65.00","created_at"=>"2025-04-02 22:49:27","updated_at"=>"2025-04-02 22:49:27"],
            ["id"=>"21","school_branch_id"=>"14","fee"=>"115.00","created_at"=>"2025-04-02 22:49:51","updated_at"=>"2025-04-02 22:49:51"],
            ["id"=>"22","school_branch_id"=>"15","fee"=>"75.00","created_at"=>"2025-04-02 22:50:16","updated_at"=>"2025-04-02 22:50:16"],
            ["id"=>"23","school_branch_id"=>"25","fee"=>"50.00","created_at"=>"2025-04-02 22:51:01","updated_at"=>"2025-04-02 22:51:01"],
        ];

        foreach ($rows as $row) {
            DB::table('language_school_branch_registration_fees')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'branch_id' => (int) $row['school_branch_id'],
                    'amount' => (float) $row['fee'],
                    'created_at' => Carbon::parse($row['created_at']),
                    'updated_at' => Carbon::parse($row['updated_at']),
                ]
            );
        }
    }
}
