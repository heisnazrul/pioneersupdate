<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntakeTermSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'key' => 'january',
                'name' => 'January',
                'ar_name' => 'يناير',
                'month_num' => 1,
                'sort_order' => 1,
            ],
            [
                'key' => 'september',
                'name' => 'September',
                'ar_name' => 'سبتمبر',
                'month_num' => 9,
                'sort_order' => 2,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('intake_terms')->updateOrInsert(
                ['key' => $row['key']],
                array_merge($row, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
