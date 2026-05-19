<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BathroomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_en' => 'Shared Bathroom', 'name_ar' => 'حمام مشترك'],
            ['name_en' => 'Private Bathroom', 'name_ar' => 'حمام خاص'],
        ];

        foreach ($rows as $row) {
            DB::table('bathroom_types')->updateOrInsert(
                ['name_en' => $row['name_en']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
