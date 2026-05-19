<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('levels')) {
            return;
        }

        $rows = [
            ['id' => 1, 'key' => 'foundation', 'name' => 'Foundation', 'ar_name' => 'البرنامج التأسيسي'],
            ['id' => 2, 'key' => 'international-foundation', 'name' => 'International Foundation', 'ar_name' => 'السنة التأسيسية الدولية'],
            ['id' => 3, 'key' => 'bachelor', 'name' => 'Bachelor', 'ar_name' => 'بكالوريوس'],
            ['id' => 4, 'key' => 'bachelor-top-up', 'name' => 'Bachelor Top-up', 'ar_name' => 'بكالوريوس (تكميلي)'],
            ['id' => 5, 'key' => 'integrated-master', 'name' => 'Integrated Master', 'ar_name' => 'ماجستير مدمج'],
            ['id' => 6, 'key' => 'pre-masters', 'name' => 'Pre-Masters', 'ar_name' => 'ما قبل الماجستير'],
            ['id' => 7, 'key' => 'graduate-diploma', 'name' => 'Graduate Diploma', 'ar_name' => 'دبلوم دراسات عليا'],
            ['id' => 8, 'key' => 'postgraduate-certificate', 'name' => 'Postgraduate Certificate (PGCert)', 'ar_name' => 'شهادة دراسات عليا'],
            ['id' => 9, 'key' => 'postgraduate-diploma', 'name' => 'Postgraduate Diploma (PGDip)', 'ar_name' => 'دبلوم دراسات عليا'],
            ['id' => 10, 'key' => 'masters', 'name' => 'Masters', 'ar_name' => 'ماجستير'],
            ['id' => 11, 'key' => 'mba', 'name' => 'MBA', 'ar_name' => 'ماجستير إدارة أعمال'],
            ['id' => 12, 'key' => 'mres', 'name' => 'MRes', 'ar_name' => 'ماجستير بحثي'],
            ['id' => 13, 'key' => 'phd-doctorate', 'name' => 'PhD / Doctorate', 'ar_name' => 'دكتوراه'],
            ['id' => 14, 'key' => 'professional-doctorate', 'name' => 'Professional Doctorate', 'ar_name' => 'دكتوراه مهنية'],
            ['id' => 15, 'key' => 'short-course', 'name' => 'Short Course', 'ar_name' => 'دورة قصيرة'],
            ['id' => 16, 'key' => 'distance-learning', 'name' => 'Distance Learning', 'ar_name' => 'التعليم عن بُعد'],
        ];

        Level::unguard();
        foreach ($rows as $row) {
            $row['sort_order'] = $row['id'];
            $row['is_active'] = true;
            Level::updateOrCreate(['key' => $row['key']], $row);
        }
        Level::reguard();
    }
}
