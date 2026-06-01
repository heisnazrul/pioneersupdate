<?php

namespace Database\Seeders;

use App\Models\LanguageCourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LanguageCourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'General English', 'name_ar' => 'اللغة الإنجليزية العامة'],
            ['name_en' => 'General English Afternoon', 'name_ar' => 'اللغة الإنجليزية العامة (مسائية)'],
            ['name_en' => 'Semi-Intensive English', 'name_ar' => 'اللغة الإنجليزية شبه المكثفة'],
            ['name_en' => 'Intensive English', 'name_ar' => 'اللغة الإنجليزية المكثفة'],
            ['name_en' => 'Super-Intensive English', 'name_ar' => 'اللغة الإنجليزية فائقة المكثفة'],
            ['name_en' => 'IELTS Exam Preparation', 'name_ar' => 'التحضير لاختبار الآيلتس'],
            ['name_en' => 'TOEFL Exam Preparation', 'name_ar' => 'التحضير لاختبار التوفل'],
            ['name_en' => 'Academic English', 'name_ar' => 'اللغة الإنجليزية الأكاديمية'],
        ];

        foreach ($categories as $category) {
            LanguageCourseCategory::updateOrCreate(
                ['slug' => Str::slug($category['name_en'])],
                [
                    'name_en' => $category['name_en'],
                    'name_ar' => $category['name_ar'],
                    'is_active' => 'yes',
                ]
            );
        }

        $this->command?->info('Seeded ' . count($categories) . ' language course categories.');
    }
}
