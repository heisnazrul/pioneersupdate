<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MealPlanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 2,
                'name' => 'Halfboard',
                'ar_name' => 'نصف إقامة',
                'description' => '(Breakfast & Evening Meal)',
                'ar_description' => null,
            ],
            [
                'id' => 3,
                'name' => 'Fullboard',
                'ar_name' => 'إقامة كاملة',
                'description' => '(Breakfast, Lunch & Evening Meal)',
                'ar_description' => null,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('meal_plans')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'meal_code' => Str::upper(Str::snake($row['name'])),
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
