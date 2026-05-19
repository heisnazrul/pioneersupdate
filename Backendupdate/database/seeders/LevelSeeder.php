<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['key' => 'foundation', 'name' => 'Foundation', 'ar_name' => 'البرنامج التأسيسي', 'sort_order' => 1],
            ['key' => 'international-foundation', 'name' => 'International Foundation', 'ar_name' => 'السنة التأسيسية الدولية', 'sort_order' => 2],
            ['key' => 'bachelor', 'name' => 'Bachelor', 'ar_name' => 'بكالوريوس', 'sort_order' => 3],
            ['key' => 'bachelor-top-up', 'name' => 'Bachelor Top-up', 'ar_name' => 'بكالوريوس (تكميلي)', 'sort_order' => 4],
            ['key' => 'integrated-master', 'name' => 'Integrated Master', 'ar_name' => 'ماجستير مدمج', 'sort_order' => 5],
            ['key' => 'pre-masters', 'name' => 'Pre-Masters', 'ar_name' => 'ما قبل الماجستير', 'sort_order' => 6],
            ['key' => 'graduate-diploma', 'name' => 'Graduate Diploma', 'ar_name' => 'دبلوم دراسات عليا', 'sort_order' => 7],
            ['key' => 'postgraduate-certificate', 'name' => 'Postgraduate Certificate (PGCert)', 'ar_name' => 'شهادة دراسات عليا', 'sort_order' => 8],
            ['key' => 'postgraduate-diploma', 'name' => 'Postgraduate Diploma (PGDip)', 'ar_name' => 'دبلوم دراسات عليا', 'sort_order' => 9],
            ['key' => 'masters', 'name' => 'Masters', 'ar_name' => 'ماجستير', 'sort_order' => 10],
            ['key' => 'mba', 'name' => 'MBA', 'ar_name' => 'ماجستير إدارة أعمال', 'sort_order' => 11],
            ['key' => 'mres', 'name' => 'MRes', 'ar_name' => 'ماجستير بحثي', 'sort_order' => 12],
            ['key' => 'phd-doctorate', 'name' => 'PhD / Doctorate', 'ar_name' => 'دكتوراه', 'sort_order' => 13],
            ['key' => 'professional-doctorate', 'name' => 'Professional Doctorate', 'ar_name' => 'دكتوراه مهنية', 'sort_order' => 14],
            ['key' => 'short-course', 'name' => 'Short Course', 'ar_name' => 'دورة قصيرة', 'sort_order' => 15],
            ['key' => 'distance-learning', 'name' => 'Distance Learning', 'ar_name' => 'التعليم عن بُعد', 'sort_order' => 16],
        ];

        foreach ($rows as $row) {
            DB::table('levels')->updateOrInsert(
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
