<?php

namespace Database\Seeders;

use App\Models\IntakeTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IntakeTermSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('intake_terms')) {
            return;
        }

        $rows = [
            [
                'id' => 1,
                'key' => 'january',
                'name' => 'January',
                'ar_name' => 'يناير',
                'month_num' => 1,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'key' => 'september',
                'name' => 'September',
                'ar_name' => 'سبتمبر',
                'month_num' => 9,
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        IntakeTerm::unguard();
        foreach ($rows as $row) {
            IntakeTerm::updateOrCreate(['key' => $row['key']], $row);
        }
        IntakeTerm::reguard();
    }
}
