<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishTrainingCoursesSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'heading' => 'Advance Your Career with Professional Courses',
                'subheading' => 'Gain Accredited Certificates and Specialized Skills.',
                'subject_label' => 'Subject',
                'subject_placeholder' => 'Select subject',
                'subject_options' => [
                    'Management',
                    'Marketing',
                    'IT & Tech',
                    'Finance',
                ],
                'location_label' => 'Location',
                'location_placeholder' => 'City or Online',
                'duration_label' => 'Duration',
                'duration_placeholder' => 'Select duration',
                'duration_options' => [
                    'Short (1-5 days)',
                    'Medium (1-4 weeks)',
                    'Long (1+ month)',
                ],
                'start_label' => 'Start Date',
                'start_placeholder' => 'Select start date',
                'search_aria' => 'Search',
            ],
            'results' => [
                'count_label' => 'professional courses available',
                'step_label' => 'Step 1: Choose the right course for your career',
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
                'heading' => 'ارتقِ بمسارك المهني عبر الدورات الاحترافية',
                'subheading' => 'احصل على شهادات معتمدة ومهارات متخصصة.',
                'subject_label' => 'المجال',
                'subject_placeholder' => 'اختر المجال',
                'subject_options' => [
                    'الإدارة',
                    'التسويق',
                    'تقنية المعلومات',
                    'التمويل',
                ],
                'location_label' => 'الموقع',
                'location_placeholder' => 'مدينة أو أونلاين',
                'duration_label' => 'المدة',
                'duration_placeholder' => 'اختر المدة',
                'duration_options' => [
                    'قصير (1-5 أيام)',
                    'متوسط (1-4 أسابيع)',
                    'طويل (أكثر من شهر)',
                ],
                'start_label' => 'تاريخ البدء',
                'start_placeholder' => 'اختر تاريخ البدء',
                'search_aria' => 'بحث',
            ],
            'results' => [
                'count_label' => 'دورات احترافية متاحة',
                'step_label' => 'الخطوة 1: اختر الدورة المناسبة لمسارك المهني',
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
            ['app' => 'courseenglish', 'slug' => 'training-and-professional-courses'],
            [
                'title' => 'Training & Professional Courses',
                'ar_title' => 'الدورات الاحترافية',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Training & Professional Courses',
                'meta_description' => 'Explore professional courses and certifications.',
                'is_active' => true,
                'display_order' => 14,
            ]
        );
    }
}
