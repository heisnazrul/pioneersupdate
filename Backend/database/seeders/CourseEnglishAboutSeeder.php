<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishAboutSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'breadcrumb' => [
                'home' => 'Home',
                'current' => 'About Us',
            ],
            'page_title' => 'About Course English',
            'intro' => [
                'title' => 'We Help You Choose the Right Language Institute with Confidence',
                'paragraph_1' => 'A specialized platform for showcasing and comparing accredited language institutes around the world, helping you make the right decision before booking.',
                'paragraph_2' => 'We connect students and families with trusted institutes in different countries and help compare by quality, location, and cost.',
                'image' => '/img.png',
            ],
            'vision' => [
                'title' => 'Our Vision',
                'body' => 'To lead the market by managing outstanding talent and becoming the best specialized company in our field.',
            ],
            'mission' => [
                'title' => 'Our Mission',
                'body' => 'A specialized platform for showing and comparing accredited language institutes globally, and helping you choose with clarity.',
            ],
            'why' => [
                'title' => 'Why Us?',
                'subtitle' => 'Because we believe your educational journey starts with confidence.',
                'items' => [
                    [
                        'title' => 'Free Services',
                        'body' => 'Our free services include admission support and guidance to find the most suitable schools.',
                        'icon' => '/like.gif',
                    ],
                    [
                        'title' => 'Official Partnerships',
                        'body' => 'We partner with trusted English schools worldwide to secure better choices for your learning journey.',
                        'icon' => '/team.gif',
                    ],
                    [
                        'title' => 'Exclusive Prices',
                        'body' => 'Our prices are exclusive. If you find a better offer, we match it.',
                        'icon' => '/tag.gif',
                    ],
                ],
            ],
        ];

        $arContent = [
            'breadcrumb' => [
                'home' => 'الرئيسية',
                'current' => 'من نحن',
            ],
            'page_title' => 'عن كورس انجليزي',
            'intro' => [
                'title' => 'نساعدك في اختيار معهد اللغة الأنسب لك، بثقة ووضوح',
                'paragraph_1' => 'منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.',
                'paragraph_2' => 'نحن منصة تعليمية متخصصة تربط الطلاب والأسر بمعاهد اللغة المعتمدة في مختلف الدول، ونساعدك في مقارنة الخيارات واختيار الأنسب لك من حيث الجودة، الموقع، والتكلفة.',
                'image' => '/img.png',
            ],
            'vision' => [
                'title' => 'رؤيتنا',
                'body' => 'الريادة المحلية من خلال استراتيجية إدارة النشّات وتوفير الكوادر البشرية المتخصصة من داخل وخارج المملكة لتصبح أفضل شركة رائدة ومتخصصة في مجالها.',
            ],
            'mission' => [
                'title' => 'رسالتنا',
                'body' => 'منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.',
            ],
            'why' => [
                'title' => 'لماذا نحن؟',
                'subtitle' => 'لأننا نؤمن أن رحلتك التعليمية تبدأ بثقة',
                'items' => [
                    [
                        'title' => 'خدمات مجانية',
                        'body' => 'تشمل خدماتنا المجانية دعم القبول والتوجيه لمساعدتك في العثور على المدارس التي تناسب احتياجاتك بشكل أفضل.',
                        'icon' => '/like.gif',
                    ],
                    [
                        'title' => 'الشراكات الرسمية',
                        'body' => 'نحن نتشارك مع مدارس اللغة الإنجليزية الموثوقة في جميع أنحاء العالم لضمان حصولك على أفضل الخيارات في رحلة التعلم الخاصة بك.',
                        'icon' => '/team.gif',
                    ],
                    [
                        'title' => 'الاسعار والعروض الحصرية',
                        'body' => 'اسعارنا حصرية. اذا وجدت سعرا افضل فسوف ننافسك.',
                        'icon' => '/tag.gif',
                    ],
                ],
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'about-us'],
            [
                'title' => 'About Us',
                'ar_title' => 'من نحن',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($arContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'About Course English',
                'meta_description' => 'Learn about Course English, our mission, vision, and why students trust us.',
                'is_active' => true,
                'display_order' => 11,
            ]
        );
    }
}
