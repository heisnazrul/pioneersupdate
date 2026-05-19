<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishOffersSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'badge' => 'Limited Time Offers',
                'headline' => 'Exclusive Education Deals',
                'subheadline' => 'Explore our handpicked selection of specialized courses, camps, and training programs at unbeatable prices.',
            ],
            'sections' => [
                [
                    'title' => 'Language Courses',
                    'subtitle' => 'Top-rated institutes with exclusive discounts.',
                    'link' => '/language-institutes',
                ],
                [
                    'title' => 'Summer Programs',
                    'subtitle' => 'Unforgettable summer experiences for teens.',
                    'link' => '/summer-programs',
                ],
                [
                    'title' => 'Online Courses',
                    'subtitle' => 'Learn from anywhere with flexible schedules.',
                    'link' => '/online-courses',
                ],
                [
                    'title' => 'Professional Training',
                    'subtitle' => 'Boost your career with certified courses.',
                    'link' => '/training-and-professional-courses',
                ],
            ],
        ];

        $ar = [
            'hero' => [
                'badge' => 'عروض لفترة محدودة',
                'headline' => 'عروض تعليمية حصرية',
                'subheadline' => 'استكشف باقة من الدورات المتخصصة والبرامج الصيفية والتدريبية بأسعار لا تُنافس.',
            ],
            'sections' => [
                [
                    'title' => 'دورات اللغة',
                    'subtitle' => 'معاهد مميزة بخصومات حصرية.',
                    'link' => '/language-institutes',
                ],
                [
                    'title' => 'برامج الصيف',
                    'subtitle' => 'تجارب صيفية لا تُنسى للمراهقين.',
                    'link' => '/summer-programs',
                ],
                [
                    'title' => 'الدورات الإلكترونية',
                    'subtitle' => 'تعلم من أي مكان بجدول مرن.',
                    'link' => '/online-courses',
                ],
                [
                    'title' => 'التدريب المهني',
                    'subtitle' => 'عزز مسيرتك المهنية بدورات معتمدة.',
                    'link' => '/training-and-professional-courses',
                ],
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'offers'],
            [
                'title' => 'Offers',
                'ar_title' => 'العروض',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Special Offers | Pioneers Admissions',
                'meta_description' => 'Browse exclusive offers on language courses, summer camps, online courses, and professional training.',
                'is_active' => true,
                'display_order' => 2,
            ]
        );
    }
}
