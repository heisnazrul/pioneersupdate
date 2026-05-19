<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealPlanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_en' => 'Halfboard', 'name_ar' => 'نصف إقامة'],
            ['name_en' => 'Fullboard', 'name_ar' => 'إقامة كاملة'],
        ];

        foreach ($rows as $row) {
            DB::table('meal_plans')->updateOrInsert(
                ['name_en' => $row['name_en']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
