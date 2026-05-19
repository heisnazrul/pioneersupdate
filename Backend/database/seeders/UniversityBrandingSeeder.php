<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class UniversityBrandingSeeder extends Seeder
{
    public function run(): void
    {
        $branding = [
            'app' => 'university',
            'header' => [
                'logo' => [
                    'main' => '/logo.png',
                    'ar' => '/logo.png',
                ],
                'top_nav' => [
                    ['label' => 'Destinations', 'ar_label' => 'الوجهات', 'url' => '/destinations'],
                    ['label' => 'Courses', 'ar_label' => 'البرامج', 'url' => '/search/courses'],
                    ['label' => 'Universities', 'ar_label' => 'الجامعات', 'url' => '/search/universities'],
                    ['label' => 'Services', 'ar_label' => 'الخدمات', 'url' => '/services'],
                    ['label' => 'Contact', 'ar_label' => 'تواصل معنا', 'url' => '/contact'],
                ],
                'main_nav' => [
                    ['label' => 'Destinations', 'ar_label' => 'الوجهات', 'url' => '/destinations'],
                    ['label' => 'Courses', 'ar_label' => 'البرامج', 'url' => '/search/courses'],
                    ['label' => 'Universities', 'ar_label' => 'الجامعات', 'url' => '/search/universities'],
                    ['label' => 'Services', 'ar_label' => 'الخدمات', 'url' => '/services'],
                    ['label' => 'Contact', 'ar_label' => 'تواصل معنا', 'url' => '/contact'],
                ],
                'currencies' => [
                    ['code' => 'SAR', 'label' => 'Saudi Riyal', 'symbol' => '﷼', 'icon' => '/assets/sar.svg'],
                    ['code' => 'USD', 'label' => 'US Dollar', 'symbol' => '$', 'icon' => '/assets/usd.svg'],
                ],
                'languages' => [
                    ['code' => 'en', 'label' => 'English', 'flag' => '/assets/flags/gb.svg'],
                    ['code' => 'ar', 'label' => 'Arabic', 'flag' => '/assets/flags/sa.svg'],
                ],
                'buttons' => [
                    'compare' => ['icon' => 'compare', 'url' => '/student/dashboard/compare'],
                    'wishlist' => ['icon' => 'heart', 'url' => '/student/dashboard/wishlist'],
                    'account' => ['label' => 'Login', 'ar_label' => 'تسجيل الدخول', 'url' => '/login', 'icon' => 'user'],
                ],
            ],
            'footer' => [
                'subscribe' => [
                    'heading' => 'Get Study Abroad Updates',
                    'placeholder' => 'Enter your email',
                    'button_text' => 'Subscribe',
                ],
                'columns' => [
                    [
                        'title' => 'TOP DESTINATIONS',
                        'ar_title' => 'أفضل الوجهات',
                        'items' => [
                            ['label' => 'Study in USA', 'ar_label' => 'الدراسة في أمريكا', 'url' => '/destinations/usa'],
                            ['label' => 'Study in UK', 'ar_label' => 'الدراسة في المملكة المتحدة', 'url' => '/destinations/uk'],
                            ['label' => 'Study in Canada', 'ar_label' => 'الدراسة في كندا', 'url' => '/destinations/canada'],
                            ['label' => 'Study in Australia', 'ar_label' => 'الدراسة في أستراليا', 'url' => '/destinations/australia'],
                        ],
                    ],
                    [
                        'title' => 'OUR SERVICES',
                        'ar_title' => 'خدماتنا',
                        'items' => [
                            ['label' => 'Admission Counseling', 'ar_label' => 'استشارات القبول', 'url' => '/services/application'],
                            ['label' => 'Visa Assistance', 'ar_label' => 'مساعدة التأشيرة', 'url' => '/services/visa'],
                            ['label' => 'Scholarship Guidance', 'ar_label' => 'إرشاد المنح الدراسية', 'url' => '/scholarships'],
                            ['label' => 'Partner with Us', 'ar_label' => 'كن شريكاً معنا', 'url' => '/services/agents'],
                        ],
                    ],
                    [
                        'title' => 'CONTACT US',
                        'ar_title' => 'تواصل معنا',
                        'items' => [
                            ['label' => 'Riyadh, Saudi Arabia', 'ar_label' => 'الرياض، المملكة العربية السعودية', 'url' => '/contact'],
                            ['label' => '+966 50 123 4567', 'ar_label' => '+966 50 123 4567', 'url' => 'tel:+966501234567'],
                            ['label' => 'info@pioneersedu.com', 'ar_label' => 'info@pioneersedu.com', 'url' => 'mailto:info@pioneersedu.com'],
                            ['label' => 'Get Directions', 'ar_label' => 'احصل على الاتجاهات', 'url' => '/contact'],
                        ],
                    ],
                ],
                'description' => 'Your trusted partner for international education. We guide you from university selection to visa approval with integrity and excellence.',
                'ar_description' => 'شريكك الموثوق للتعليم الدولي. نرشدك من اختيار الجامعة حتى القبول والتأشيرة باحترافية.',
                'logo' => '/logo.png',
                'social' => [
                    ['platform' => 'linkedin', 'url' => '#'],
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'instagram', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                ],
                'copyright' => 'All rights reserved © 2026',
                'ar_copyright' => 'جميع الحقوق محفوظة © 2026',
                'brand' => 'PIONEERSADMISSIONS',
                'ar_brand' => 'بايونيرز للقبول',
            ],
            'mobile' => [
                'promo' => [
                    'text' => 'Apply confidently with expert support for admissions, visa, and accommodation.',
                    'text_ar' => 'قدّم بثقة مع دعم خبرائنا في القبول والتأشيرة والسكن.',
                    'icon' => '/assets/icons/offer.png',
                ],
                'logo' => '/logo.png',
                'nav' => [
                    ['label' => 'Destinations', 'ar_label' => 'الوجهات', 'url' => '/destinations'],
                    ['label' => 'Courses', 'ar_label' => 'البرامج', 'url' => '/search/courses'],
                    ['label' => 'Universities', 'ar_label' => 'الجامعات', 'url' => '/search/universities'],
                    ['label' => 'Services', 'ar_label' => 'الخدمات', 'url' => '/services'],
                    ['label' => 'Contact', 'ar_label' => 'تواصل معنا', 'url' => '/contact'],
                ],
                'quick_links' => [
                    ['label' => 'Destinations', 'ar_label' => 'الوجهات', 'icon' => 'map', 'url' => '/destinations'],
                    ['label' => 'Courses', 'ar_label' => 'البرامج', 'icon' => 'book', 'url' => '/search/courses'],
                    ['label' => 'Universities', 'ar_label' => 'الجامعات', 'icon' => 'building', 'url' => '/search/universities'],
                ],
                'actions' => [
                    ['label' => 'My Account', 'icon' => 'user', 'url' => '/student/dashboard'],
                    ['label' => 'Compare', 'icon' => 'compare', 'url' => '/student/dashboard/compare'],
                    ['label' => 'Wishlist', 'icon' => 'heart', 'url' => '/student/dashboard/wishlist', 'badge' => 0],
                    ['label' => 'Universities', 'icon' => 'building', 'url' => '/search/universities'],
                    ['label' => 'Home', 'icon' => 'home', 'url' => '/'],
                ],
                'drawer_links' => [
                    ['label' => 'Destinations', 'ar_label' => 'الوجهات', 'url' => '/destinations'],
                    ['label' => 'Courses', 'ar_label' => 'البرامج', 'url' => '/search/courses'],
                    ['label' => 'Universities', 'ar_label' => 'الجامعات', 'url' => '/search/universities'],
                    ['label' => 'Services', 'ar_label' => 'الخدمات', 'url' => '/services'],
                    ['label' => 'Contact', 'ar_label' => 'تواصل معنا', 'url' => '/contact'],
                ],
                'drawer_social' => [
                    ['platform' => 'linkedin', 'url' => '#'],
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'instagram', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                ],
                'drawer_legal' => [
                    ['label' => 'Privacy Policy', 'ar_label' => 'سياسة الخصوصية', 'url' => '/privacy'],
                    ['label' => 'Terms of Service', 'ar_label' => 'شروط الخدمة', 'url' => '/terms'],
                    ['label' => 'Cookie Policy', 'ar_label' => 'سياسة ملفات الارتباط', 'url' => '/cookie-policy'],
                ],
            ],
        ];

        Setting::put('branding_university', $branding);
    }
}
