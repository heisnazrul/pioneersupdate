<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishSummerProgramsSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'heading' => 'Unforgettable Summer Camps for Teens',
                'subheading' => 'Explore the world, learn English, and make lifelong friends.',
                'destination_label' => 'Destination',
                'destination_placeholder' => 'Country or city',
                'age_label' => 'Age Group',
                'age_placeholder' => 'Select age',
                'age_options' => [
                    '10-14 years',
                    '14-17 years',
                    '16-18 years',
                ],
                'duration_label' => 'Duration',
                'duration_placeholder' => 'Select duration',
                'duration_options' => [
                    '1 Week',
                    '2 Weeks',
                    '3 Weeks',
                    '4+ Weeks',
                ],
                'start_label' => 'Start Date',
                'start_placeholder' => 'Select start date',
                'search_aria' => 'Search',
            ],
            'results' => [
                'count_label' => 'camps available based on your choice',
                'step_label' => 'Step 1: Choose the perfect summer camp',
                'sort_label' => 'Sort by:',
                'sort_options' => [
                    'Most Popular',
                    'Price: Low to High',
                    'Price: High to Low',
                    'Rating',
                ],
            ],
            'load_more' => [
                'text' => 'Load More',
            ],
        ];

        $ar = [
            'hero' => [
                'heading' => 'مخيمات صيفية لا تُنسى للمراهقين',
                'subheading' => 'استكشف العالم وتعلم الإنجليزية وكون صداقات مدى الحياة.',
                'destination_label' => 'الوجهة',
                'destination_placeholder' => 'البلد أو المدينة',
                'age_label' => 'الفئة العمرية',
                'age_placeholder' => 'اختر العمر',
                'age_options' => [
                    '10-14 سنة',
                    '14-17 سنة',
                    '16-18 سنة',
                ],
                'duration_label' => 'المدة',
                'duration_placeholder' => 'اختر المدة',
                'duration_options' => [
                    'أسبوع واحد',
                    'أسبوعان',
                    '3 أسابيع',
                    '4 أسابيع أو أكثر',
                ],
                'start_label' => 'تاريخ البدء',
                'start_placeholder' => 'اختر تاريخ البدء',
                'search_aria' => 'بحث',
            ],
            'results' => [
                'count_label' => 'مخيمات متاحة بناءً على اختيارك',
                'step_label' => 'الخطوة 1: اختر المخيم الصيفي المثالي',
                'sort_label' => 'ترتيب حسب:',
                'sort_options' => [
                    'الأكثر شعبية',
                    'السعر: من الأقل إلى الأعلى',
                    'السعر: من الأعلى إلى الأقل',
                    'التقييم',
                ],
            ],
            'load_more' => [
                'text' => 'عرض المزيد',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'summer-programs'],
            [
                'title' => 'Summer Programs',
                'ar_title' => 'برامج الصيف',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Summer Programs',
                'meta_description' => 'Explore summer camps and programs for teens.',
                'is_active' => true,
                'display_order' => 13,
            ]
        );
    }
}
