<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishLanguageInstitutesSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'heading' => 'Discover the Best Language Institutes Worldwide',
                'subheading' => 'A curated selection of the best accredited language institutes.',
                'destination_label' => 'Destination',
                'destination_placeholder' => 'Country, city, or institute',
                'course_label' => 'Course Type',
                'course_placeholder' => 'Select course type',
                'course_options' => [
                    'General Language',
                    'Business Language',
                    'Exam Preparation',
                ],
                'duration_label' => 'Duration',
                'duration_placeholder' => 'Select duration',
                'start_label' => 'Start Date',
                'start_placeholder' => 'Select start date',
                'search_aria' => 'Search',
            ],
            'results' => [
                'count_label' => 'institutes available based on your choice',
                'step_label' => 'Step 1: Choose the right institute for you',
                'sort_label' => 'Sort by:',
                'sort_options' => [
                    'Most Popular',
                    'Price: Low to High',
                    'Price: High to Low',
                    'Rating',
                ],
            ],
            'sidebar' => [
                'sections' => [
                    [
                        'title' => 'Accommodation',
                        'options' => ['With Accommodation', 'Without Accommodation'],
                    ],
                    [
                        'title' => 'Airport Pickup',
                        'options' => ['With Pickup', 'Without Pickup'],
                    ],
                    [
                        'title' => 'Insurance',
                        'options' => ['With Insurance', 'Without Insurance'],
                    ],
                ],
            ],
            'load_more' => [
                'text' => 'Load More',
            ],
        ];

        $ar = [
            'hero' => [
                'heading' => 'اكتشف أفضل معاهد اللغات حول العالم',
                'subheading' => 'مجموعة مختارة من أفضل معاهد اللغات المعتمدة.',
                'destination_label' => 'الوجهة',
                'destination_placeholder' => 'الدولة أو المدينة أو المعهد',
                'course_label' => 'نوع الدورة',
                'course_placeholder' => 'اختر نوع الدورة',
                'course_options' => [
                    'لغة عامة',
                    'لغة الأعمال',
                    'التحضير للاختبارات',
                ],
                'duration_label' => 'المدة',
                'duration_placeholder' => 'اختر المدة',
                'start_label' => 'تاريخ البدء',
                'start_placeholder' => 'اختر تاريخ البدء',
                'search_aria' => 'بحث',
            ],
            'results' => [
                'count_label' => 'معهدًا متاحًا بناءً على اختيارك',
                'step_label' => 'الخطوة 1: اختر المعهد المناسب لك',
                'sort_label' => 'ترتيب حسب:',
                'sort_options' => [
                    'الأكثر شعبية',
                    'السعر: من الأقل إلى الأعلى',
                    'السعر: من الأعلى إلى الأقل',
                    'التقييم',
                ],
            ],
            'sidebar' => [
                'sections' => [
                    [
                        'title' => 'السكن',
                        'options' => ['مع سكن', 'بدون سكن'],
                    ],
                    [
                        'title' => 'استقبال المطار',
                        'options' => ['مع استقبال', 'بدون استقبال'],
                    ],
                    [
                        'title' => 'التأمين',
                        'options' => ['مع تأمين', 'بدون تأمين'],
                    ],
                ],
            ],
            'load_more' => [
                'text' => 'عرض المزيد',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'language-institutes'],
            [
                'title' => 'Language Institutes',
                'ar_title' => 'معاهد اللغات',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Language Institutes',
                'meta_description' => 'Discover the best accredited language institutes worldwide.',
                'is_active' => true,
                'display_order' => 4,
            ]
        );
    }
}
