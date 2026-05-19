<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LanguageSchoolAccommodationSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ["id"=>"11","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"6","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"1","required_age"=>"18","fee"=>"255.00","admin_charge"=>null,"under_sup"=>"20","created_at"=>"2025-03-26 02:45:49","updated_at"=>"2025-03-26 02:45:49"],
            ["id"=>"12","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"7","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"215.00","admin_charge"=>null,"under_sup"=>"20","created_at"=>"2025-03-26 02:46:30","updated_at"=>"2025-03-26 02:46:30"],
            ["id"=>"13","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"8","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"240.00","admin_charge"=>null,"under_sup"=>"20","created_at"=>"2025-03-26 02:47:05","updated_at"=>"2025-03-26 02:47:05"],
            ["id"=>"14","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"21","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"17","fee"=>"195.00","admin_charge"=>null,"under_sup"=>null,"created_at"=>"2025-04-02 23:35:29","updated_at"=>"2025-04-02 23:35:29"],
            ["id"=>"15","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"12","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"310.00","admin_charge"=>"55.00","under_sup"=>null,"created_at"=>"2025-04-02 23:39:10","updated_at"=>"2025-04-02 23:39:10"],
            ["id"=>"16","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"22","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"16","fee"=>"190.00","admin_charge"=>"50.00","under_sup"=>null,"created_at"=>"2025-04-02 23:50:45","updated_at"=>"2025-04-02 23:50:45"],
            ["id"=>"17","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"13","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"17","fee"=>"230.00","admin_charge"=>"70.00","under_sup"=>null,"created_at"=>"2025-04-03 00:03:49","updated_at"=>"2025-04-03 00:03:49"],
            ["id"=>"18","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"23","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>null,"fee"=>"175.00","admin_charge"=>"10.00","under_sup"=>null,"created_at"=>"2025-04-03 00:04:49","updated_at"=>"2025-04-03 00:04:49"],
            ["id"=>"19","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"20","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"390.00","admin_charge"=>"50.00","under_sup"=>null,"created_at"=>"2025-04-03 00:05:54","updated_at"=>"2025-04-03 00:05:54"],
            ["id"=>"20","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"19","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"16","fee"=>"270.00","admin_charge"=>null,"under_sup"=>null,"created_at"=>"2025-04-03 00:07:06","updated_at"=>"2025-04-03 00:07:06"],
            ["id"=>"21","name"=>"Residence","ar_name"=>"سكن طلاب","branch_id"=>"18","bedroom_id"=>"4","bathroom_id"=>"3","meal_id"=>"3","tag_id"=>"2","required_age"=>"18","fee"=>"160.00","admin_charge"=>"50.00","under_sup"=>null,"created_at"=>"2025-04-03 00:10:51","updated_at"=>"2025-04-03 00:10:51"],
            ["id"=>"22","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"9","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"16","fee"=>"255.00","admin_charge"=>"100.00","under_sup"=>null,"created_at"=>"2025-04-03 00:12:53","updated_at"=>"2025-04-03 00:12:53"],
            ["id"=>"23","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"24","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"260.00","admin_charge"=>"55.00","under_sup"=>null,"created_at"=>"2025-04-03 00:16:02","updated_at"=>"2025-04-03 00:16:02"],
            ["id"=>"24","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"17","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>null,"fee"=>"275.00","admin_charge"=>null,"under_sup"=>null,"created_at"=>"2025-04-03 00:23:53","updated_at"=>"2025-04-03 00:23:53"],
            ["id"=>"25","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"10","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"203.00","admin_charge"=>null,"under_sup"=>null,"created_at"=>"2025-04-03 00:24:33","updated_at"=>"2025-04-03 00:24:33"],
            ["id"=>"26","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"16","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"220.00","admin_charge"=>"65.00","under_sup"=>"35","created_at"=>"2025-04-03 00:25:34","updated_at"=>"2025-04-03 00:25:34"],
            ["id"=>"27","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"14","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"195.00","admin_charge"=>null,"under_sup"=>"17","created_at"=>"2025-04-03 00:26:30","updated_at"=>"2025-04-03 00:26:30"],
            ["id"=>"28","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"15","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>"2","required_age"=>"18","fee"=>"220.00","admin_charge"=>"75.00","under_sup"=>null,"created_at"=>"2025-04-03 00:27:10","updated_at"=>"2025-04-03 00:27:10"],
            ["id"=>"29","name"=>"Homestay","ar_name"=>"إقامة مع عائلة","branch_id"=>"25","bedroom_id"=>"4","bathroom_id"=>"2","meal_id"=>"2","tag_id"=>null,"required_age"=>"18","fee"=>"280.00","admin_charge"=>"50.00","under_sup"=>"25","created_at"=>"2025-04-03 00:28:16","updated_at"=>"2025-04-03 00:28:16"],
        ];

        foreach ($rows as $row) {
            $branchId = isset($row['branch_id']) ? (int) $row['branch_id'] : null;

            DB::table('language_school_accommodations')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'branch_id' => $branchId,
                    'language_course_tag_id' => isset($row['tag_id']) ? (int) $row['tag_id'] : null,
                    'bedroom_type_id' => isset($row['bedroom_id']) ? (int) $row['bedroom_id'] : null,
                    'bathroom_type_id' => isset($row['bathroom_id']) ? (int) $row['bathroom_id'] : null,
                    'meal_plan_id' => isset($row['meal_id']) ? (int) $row['meal_id'] : null,
                    'title' => $row['name'],
                    'ar_title' => $row['ar_name'] ?? null,
                    'required_age' => isset($row['required_age']) ? (int) $row['required_age'] : null,
                    'fee_per_week' => isset($row['fee']) ? (float) $row['fee'] : null,
                    'admin_charge' => isset($row['admin_charge']) ? (float) $row['admin_charge'] : null,
                    'under18_supplement_per_week' => isset($row['under_sup']) ? (float) $row['under_sup'] : null,
                    'notes' => null,
                    'created_at' => isset($row['created_at']) ? Carbon::parse($row['created_at']) : now(),
                    'updated_at' => isset($row['updated_at']) ? Carbon::parse($row['updated_at']) : now(),
                ]
            );
        }
    }
}
