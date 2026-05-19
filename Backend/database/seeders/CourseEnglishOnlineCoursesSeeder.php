<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishOnlineCoursesSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'heading' => 'Master English Online from Anywhere',
                'subheading' => 'Flexible, accredited online courses designed for your success.',
                'level_label' => 'Course Level',
                'level_placeholder' => 'Select level',
                'level_options' => [
                    'Beginner (A1-A2)',
                    'Intermediate (B1-B2)',
                    'Advanced (C1-C2)',
                ],
                'focus_label' => 'Focus Area',
                'focus_placeholder' => 'Select focus',
                'focus_options' => [
                    'General English',
                    'Business English',
                    'IELTS/TOEFL Prep',
                    'English for Kids',
                ],
                'schedule_label' => 'Schedule',
                'schedule_placeholder' => 'Select time',
                'schedule_options' => [
                    'Morning',
                    'Afternoon',
                    'Evening',
                    'Weekend',
                ],
                'start_label' => 'Start Date',
                'start_placeholder' => 'Select start date',
                'search_aria' => 'Search',
            ],
            'results' => [
                'count_label' => 'courses available based on your choice',
                'step_label' => 'Step 1: Choose the right online course for you',
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
                'heading' => 'أتقن الإنجليزية عبر الإنترنت من أي مكان',
                'subheading' => 'دورات مرنة ومعتمدة مصممة لنجاحك.',
                'level_label' => 'مستوى الدورة',
                'level_placeholder' => 'اختر المستوى',
                'level_options' => [
                    'مبتدئ (A1-A2)',
                    'متوسط (B1-B2)',
                    'متقدم (C1-C2)',
                ],
                'focus_label' => 'مجال التركيز',
                'focus_placeholder' => 'اختر المجال',
                'focus_options' => [
                    'إنجليزي عام',
                    'إنجليزي للأعمال',
                    'تحضير IELTS/TOEFL',
                    'إنجليزي للأطفال',
                ],
                'schedule_label' => 'الجدول',
                'schedule_placeholder' => 'اختر الوقت',
                'schedule_options' => [
                    'صباحاً',
                    'بعد الظهر',
                    'مساءً',
                    'عطلة نهاية الأسبوع',
                ],
                'start_label' => 'تاريخ البدء',
                'start_placeholder' => 'اختر تاريخ البدء',
                'search_aria' => 'بحث',
            ],
            'results' => [
                'count_label' => 'دورات متاحة بناءً على اختيارك',
                'step_label' => 'الخطوة 1: اختر الدورة المناسبة عبر الإنترنت',
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
            ['app' => 'courseenglish', 'slug' => 'online-courses'],
            [
                'title' => 'Online Courses',
                'ar_title' => 'الدورات عبر الإنترنت',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Online Courses',
                'meta_description' => 'Browse accredited online English courses and flexible schedules.',
                'is_active' => true,
                'display_order' => 12,
            ]
        );
    }
}
