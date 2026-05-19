<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LanguageSchoolCourseMaterialFeeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ["id"=>"2","course_id"=>"66","fee"=>"30.00","created_at"=>"2025-04-02 22:54:44","updated_at"=>"2025-04-02 22:54:44"],
            ["id"=>"3","course_id"=>"67","fee"=>"30.00","created_at"=>"2025-04-02 22:54:47","updated_at"=>"2025-04-02 22:54:47"],
            ["id"=>"4","course_id"=>"68","fee"=>"30.00","created_at"=>"2025-04-02 22:54:51","updated_at"=>"2025-04-02 22:54:51"],
            ["id"=>"5","course_id"=>"56","fee"=>"40.00","created_at"=>"2025-04-02 22:55:14","updated_at"=>"2025-04-02 22:55:14"],
            ["id"=>"6","course_id"=>"57","fee"=>"40.00","created_at"=>"2025-04-02 22:55:17","updated_at"=>"2025-04-02 22:55:17"],
            ["id"=>"7","course_id"=>"58","fee"=>"40.00","created_at"=>"2025-04-02 22:55:20","updated_at"=>"2025-04-02 22:55:20"],
            ["id"=>"8","course_id"=>"53","fee"=>"35.00","created_at"=>"2025-04-02 22:55:42","updated_at"=>"2025-04-02 22:55:42"],
            ["id"=>"9","course_id"=>"54","fee"=>"35.00","created_at"=>"2025-04-02 22:55:45","updated_at"=>"2025-04-02 22:55:45"],
            ["id"=>"10","course_id"=>"55","fee"=>"35.00","created_at"=>"2025-04-02 22:55:48","updated_at"=>"2025-04-02 22:55:48"],
            ["id"=>"11","course_id"=>"45","fee"=>"40.00","created_at"=>"2025-04-02 22:56:09","updated_at"=>"2025-04-02 22:56:09"],
            ["id"=>"12","course_id"=>"46","fee"=>"40.00","created_at"=>"2025-04-02 22:56:12","updated_at"=>"2025-04-02 22:56:12"],
            ["id"=>"13","course_id"=>"47","fee"=>"40.00","created_at"=>"2025-04-02 22:56:15","updated_at"=>"2025-04-02 22:56:15"],
        ];

        foreach ($rows as $row) {
            DB::table('language_school_course_material_fees')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'language_school_course_id' => (int) $row['course_id'],
                    'amount' => (float) $row['fee'],
                    'billing_unit' => 'course',
                    'billing_count' => 1,
                    'created_at' => Carbon::parse($row['created_at']),
                    'updated_at' => Carbon::parse($row['updated_at']),
                ]
            );
        }
    }
}
