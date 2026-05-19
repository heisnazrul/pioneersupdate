<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BathroomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 2,
                'name' => 'Shared Bathroom',
                'ar_name' => 'حمام مشترك',
                'description' => null,
                'ar_description' => null,
            ],
            [
                'id' => 3,
                'name' => 'Private Bathroom',
                'ar_name' => 'حمام خاص',
                'description' => null,
                'ar_description' => null,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('bathroom_types')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'bathroom_code' => Str::upper(Str::snake($row['name'])),
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'description' => $row['description'],
                    'ar_description' => $row['ar_description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
