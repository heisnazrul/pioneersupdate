<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishUniversityAdmissionsSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'badge' => 'Admissions Portal',
                'title' => 'Find Your Future',
                'description' => 'Explore thousands of world-class universities and specialized courses. take the first step towards your global education journey today.',
            ],
            'cards' => [
                [
                    'title' => 'Search Universities',
                    'description' => 'Browse top-ranked institutions across the globe and find the perfect campus for you.',
                    'button_text' => 'Explore Universities',
                    'url' => 'https://pioneersedu.com/search/universities',
                    'image' => 'https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'icon' => 'faUniversity',
                ],
                [
                    'title' => 'Search Courses',
                    'description' => 'Find the right degree or short course. Filter by subject, level, and location.',
                    'button_text' => 'Find Your Course',
                    'url' => 'https://pioneersedu.com/search/courses',
                    'image' => 'https://images.pexels.com/photos/1205651/pexels-photo-1205651.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'icon' => 'faGraduationCap',
                ],
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'Partner Universities'],
                ['value' => '10k+', 'label' => 'Courses Available'],
                ['value' => '50+', 'label' => 'Destinations'],
                ['value' => '100%', 'label' => 'Free Consultation'],
            ],
        ];

        $ar = [
            'hero' => [
                'badge' => 'بوابة القبول',
                'title' => 'اصنع مستقبلك',
                'description' => 'استكشف آلاف الجامعات العالمية والدورات المتخصصة. ابدأ رحلتك التعليمية العالمية اليوم.',
            ],
            'cards' => [
                [
                    'title' => 'ابحث عن الجامعات',
                    'description' => 'تصفح أفضل الجامعات حول العالم واختر الحرم المناسب لك.',
                    'button_text' => 'استكشف الجامعات',
                    'url' => $content['cards'][0]['url'],
                    'image' => $content['cards'][0]['image'],
                    'icon' => $content['cards'][0]['icon'],
                ],
                [
                    'title' => 'ابحث عن الدورات',
                    'description' => 'اختر الدرجة المناسبة أو الدورة القصيرة حسب التخصص والمستوى والموقع.',
                    'button_text' => 'ابحث عن دورة',
                    'url' => $content['cards'][1]['url'],
                    'image' => $content['cards'][1]['image'],
                    'icon' => $content['cards'][1]['icon'],
                ],
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'جامعات شريكة'],
                ['value' => '10k+', 'label' => 'دورات متاحة'],
                ['value' => '50+', 'label' => 'وجهات'],
                ['value' => '100%', 'label' => 'استشارة مجانية'],
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'university-admissions'],
            [
                'title' => 'University Admissions',
                'ar_title' => 'قبول الجامعات',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'University Admissions | Pioneers',
                'meta_description' => 'Find your dream university or course. Search through thousands of programs worldwide.',
                'is_active' => true,
                'display_order' => 9,
            ]
        );
    }
}
