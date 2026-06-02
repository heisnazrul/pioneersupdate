<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccommodationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_en' => 'Homestay', 'name_ar' => 'إقامة مع عائلة'],
            ['name_en' => 'Residence', 'name_ar' => 'سكن طلابي'],
            ['name_en' => 'Furnished Apartment', 'name_ar' => 'شقة مفروشة'],
            ['name_en' => 'Studio', 'name_ar' => 'استوديو'],
            ['name_en' => 'Student House', 'name_ar' => 'بيت طلابي'],
            ['name_en' => 'Flat Share', 'name_ar' => 'شقة مشتركة'],
        ];

        foreach ($rows as $row) {
            DB::table('accommodation_types')->updateOrInsert(
                ['name_en' => $row['name_en']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command?->info('Seeded '.count($rows).' accommodation types.');
    }
}
