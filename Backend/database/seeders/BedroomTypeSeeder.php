<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BedroomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 3,
                'name' => 'Private Room',
                'ar_name' => 'غرفة خاصة',
                'description' => null,
                'ar_description' => null,
            ],
            [
                'id' => 4,
                'name' => 'Single Room',
                'ar_name' => 'غرفة فردية',
                'description' => null,
                'ar_description' => null,
            ],
            [
                'id' => 5,
                'name' => 'Twin room',
                'ar_name' => 'غرفة مزدوجة',
                'description' => null,
                'ar_description' => null,
            ],
            [
                'id' => 6,
                'name' => 'Shared Room',
                'ar_name' => 'غرفة مشتركة',
                'description' => null,
                'ar_description' => null,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('bedroom_types')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'bedroom_code' => Str::upper(Str::snake($row['name'])),
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
