<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BedroomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_en' => 'Private Room', 'name_ar' => 'غرفة خاصة'],
            ['name_en' => 'Single Room', 'name_ar' => 'غرفة فردية'],
            ['name_en' => 'Twin Room', 'name_ar' => 'غرفة مزدوجة'],
            ['name_en' => 'Shared Room', 'name_ar' => 'غرفة مشتركة'],
            ['name_en' => 'Studio', 'name_ar' => 'استوديو'],
        ];

        foreach ($rows as $row) {
            DB::table('bedroom_types')->updateOrInsert(
                ['name_en' => $row['name_en']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
