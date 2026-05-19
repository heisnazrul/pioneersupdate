<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSchoolInsuranceFeeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id' => 5, 'name' => 'Full comprehensive insurance', 'ar_name' => 'Full comprehensive insurance', 'school_branch_id' => 10, 'fee' => 7.50, 'created_at' => '2025-04-03 01:16:40', 'updated_at' => '2025-04-03 01:16:40', 'admin_charge' => null],
            ['id' => 6, 'name' => 'School Insurance', 'ar_name' => 'School Insurance', 'school_branch_id' => 16, 'fee' => 12.00, 'created_at' => '2025-04-03 01:17:53', 'updated_at' => '2025-04-03 01:17:53', 'admin_charge' => null],
            ['id' => 7, 'name' => 'School Insurance', 'ar_name' => 'School Insurance', 'school_branch_id' => 13, 'fee' => 11.12, 'created_at' => '2025-04-03 01:18:06', 'updated_at' => '2025-04-03 01:18:06', 'admin_charge' => null],
            ['id' => 8, 'name' => 'Medical & travel insurance', 'ar_name' => 'Medical & travel insurance', 'school_branch_id' => 15, 'fee' => 35.00, 'created_at' => '2025-04-03 01:18:46', 'updated_at' => '2025-04-03 01:18:46', 'admin_charge' => null],
            ['id' => 9, 'name' => 'Insurance (with Guard.me)', 'ar_name' => 'Insurance (with Guard.me)', 'school_branch_id' => 23, 'fee' => 7.00, 'created_at' => '2025-04-03 01:19:09', 'updated_at' => '2025-04-03 01:19:09', 'admin_charge' => '5.00'],
        ];

        foreach ($rows as $row) {
            DB::table('language_school_insurance_fees')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'branch_id' => (int) $row['school_branch_id'],
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'amount' => $row['fee'],
                    'admin_charge' => $row['admin_charge'],
                    'billing_unit' => 'week',
                    'billing_count' => 1,
                    'valid_from' => null,
                    'valid_to' => null,
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ]
            );
        }
    }
}
