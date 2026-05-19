<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class BrandingSeeder extends Seeder
{
    public function run(): void
    {
        $branding = [
            'app' => 'courseenglish',
            'header' => [
                'logo' => [
                    'main' => '/logo.png',
                    'ar' => '/logo.png',
                ],
                'top_nav' => [
                    ['label' => 'Language Institutes', 'ar_label' => 'معاهد اللغة', 'url' => '/language-institutes', 'href' => '/language-institutes'],
                    ['label' => 'Summer Programs', 'ar_label' => 'برامج الصيف', 'url' => '/summer-programs', 'href' => '/summer-programs'],
                    ['label' => 'Online Courses', 'ar_label' => 'الدورات الإلكترونية', 'url' => '/online-courses', 'href' => '/online-courses'],
                    ['label' => 'University Admissions', 'ar_label' => 'القبول الجامعي', 'url' => '/university-admissions', 'href' => '/university-admissions'],
                    ['label' => 'Travel & Tourism', 'ar_label' => 'السفر والسياحة', 'url' => '/travel-and-tourism', 'href' => '/travel-and-tourism'],
                    ['label' => 'Training & Professional Courses', 'ar_label' => 'التدريب والدورات المهنية', 'url' => '/training-and-professional-courses', 'href' => '/training-and-professional-courses'],
                ],
                'main_nav' => [
                    ['label' => 'Home', 'ar_label' => 'الرئيسية', 'url' => '/', 'href' => '/'],
                    ['label' => 'Offers', 'ar_label' => 'العروض', 'url' => '/offers', 'href' => '/offers'],
                    ['label' => 'About Us', 'ar_label' => 'من نحن', 'url' => '/about-us', 'href' => '/about-us'],
                    ['label' => 'Contact Us', 'ar_label' => 'اتصل بنا', 'url' => '/contact-us', 'href' => '/contact-us'],
                    ['label' => 'Articles', 'ar_label' => 'المقالات', 'url' => '/articles', 'href' => '/articles'],
                ],
                'currencies' => [
                    ['code' => 'SAR', 'label' => 'Saudi Riyal', 'symbol' => '﷼', 'icon' => '/assets/sar.svg'],
                    ['code' => 'GBP', 'label' => 'British Pound', 'symbol' => '£', 'icon' => '/assets/gbp.svg'],
                ],
                'languages' => [
                    ['code' => 'en', 'label' => 'English', 'flag' => '/assets/flags/gb.svg'],
                    ['code' => 'ar', 'label' => 'Arabic', 'flag' => '/assets/flags/sa.svg'],
                ],
                'buttons' => [
                    'compare' => ['icon' => 'compare', 'url' => '/compare'],
                    'wishlist' => ['icon' => 'heart', 'url' => '/wishlist'],
                    'account' => ['label' => 'My Account', 'ar_label' => 'حسابي', 'url' => '/student/dashboard', 'icon' => 'user'],
                ],
            ],
            'footer' => [
                'subscribe' => [
                    'heading' => 'Stay in the loop',
                    'placeholder' => 'Enter your email',
                    'button_text' => 'Subscribe',
                ],
                'columns' => [
                    [
                        'title' => 'Programs',
                        'items' => [
                            ['label' => 'Language Institutes', 'ar_label' => 'معاهد اللغة', 'url' => '/language-institutes'],
                            ['label' => 'Summer Programs', 'ar_label' => 'برامج الصيف', 'url' => '/summer-programs'],
                            ['label' => 'Distance Learning', 'ar_label' => 'التعلم عن بعد', 'url' => '/online-courses'],
                            ['label' => 'Travel & Tourism', 'ar_label' => 'السفر والسياحة', 'url' => '/travel-and-tourism'],
                        ],
                    ],
                    [
                        'title' => 'Company',
                        'items' => [
                            ['label' => 'About Us', 'ar_label' => 'من نحن', 'url' => '/about-us'],
                            ['label' => 'Careers', 'ar_label' => 'وظائف', 'url' => '/careers'],
                            ['label' => 'Blog', 'ar_label' => 'المدونة', 'url' => '/articles'],
                            ['label' => 'Contact', 'ar_label' => 'اتصل', 'url' => '/contact-us'],
                        ],
                    ],
                ],
                'description' => 'CourseEnglish helps learners find the right English programs, compare options, and book with confidence.',
                'social' => [
                    ['platform' => 'linkedin', 'url' => '#'],
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'instagram', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                ],
                'copyright' => 'All rights reserved © 2026',
                'brand' => 'CourseEnglish',
            ],
            'mobile' => [
                'promo' => [
                    'text' => 'Our exclusive offers guarantee the best prices and services. If you find a better price or service, we’ll match it.',
                    'text_ar' => 'عروضنا الحصرية تضمن أفضل الأسعار والخدمات. إذا وجدت سعراً أو خدمة أفضل فسوف نطابقها.',
                    'icon' => '/assets/icons/offer.png',
                ],
                'logo' => '/logo.png',
                'nav' => [
                    ['label' => 'Language Institutes', 'ar_label' => 'معاهد اللغة', 'url' => '/language-institutes'],
                    ['label' => 'Summer Programs', 'ar_label' => 'برامج الصيف', 'url' => '/summer-programs'],
                    ['label' => 'Online Courses', 'ar_label' => 'الدورات الإلكترونية', 'url' => '/online-courses'],
                    ['label' => 'University Admissions', 'ar_label' => 'القبول الجامعي', 'url' => '/university-admissions'],
                    ['label' => 'Travel & Tourism', 'ar_label' => 'السفر والسياحة', 'url' => '/travel-and-tourism'],
                    ['label' => 'Training & Professional Courses', 'ar_label' => 'التدريب والدورات المهنية', 'url' => '/training-and-professional-courses'],
                ],
                'quick_links' => [
                    ['label' => 'Home', 'ar_label' => 'الرئيسية', 'icon' => 'home', 'url' => '/'],
                    ['label' => 'Contact Us', 'ar_label' => 'اتصل بنا', 'icon' => 'phone', 'url' => '/contact-us'],
                    ['label' => 'FAQ', 'ar_label' => 'الأسئلة الشائعة', 'icon' => 'question', 'url' => '/articles'],
                ],
                'actions' => [
                    ['label' => 'My Account', 'icon' => 'user', 'url' => '/student/dashboard'],
                    ['label' => 'Compare', 'icon' => 'compare', 'url' => '/compare'],
                    ['label' => 'Wishlist', 'icon' => 'heart', 'url' => '/wishlist', 'badge' => 4],
                    ['label' => 'Institutes', 'icon' => 'building', 'url' => '/language-institutes'],
                    ['label' => 'Home', 'icon' => 'home', 'url' => '/'],
                ],
                'drawer_links' => [
                    ['label' => 'Offers', 'ar_label' => 'العروض', 'url' => '/offers'],
                    ['label' => 'About Us', 'ar_label' => 'من نحن', 'url' => '/about-us'],
                    ['label' => 'Team', 'ar_label' => 'فريق العمل', 'url' => '/team'],
                    ['label' => 'Blog', 'ar_label' => 'المدونة', 'url' => '/articles'],
                    ['label' => 'English Language Schools', 'ar_label' => 'مدارس اللغة الإنجليزية', 'url' => '/language-institutes'],
                    ['label' => 'Summer Program', 'ar_label' => 'برنامج صيفي', 'url' => '/summer-programs'],
                    ['label' => 'University Admission', 'ar_label' => 'القبول الجامعي', 'url' => '/university-admissions'],
                ],
                'drawer_social' => [
                    ['platform' => 'linkedin', 'url' => '#'],
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'instagram', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                ],
                'drawer_legal' => [
                    ['label' => 'Privacy Policy', 'ar_label' => 'سياسة الخصوصية', 'url' => '/privacy'],
                    ['label' => 'Terms & Conditions', 'ar_label' => 'الشروط والأحكام', 'url' => '/terms'],
                ],
            ],
        ];

        Setting::put('branding', $branding);
    }
}
