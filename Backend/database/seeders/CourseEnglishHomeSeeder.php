<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishHomeSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'promo_text' => 'Our offers are exclusive. We guarantee the best prices and services and will pay the difference if you find a better price or service.',
                'promo_icon' => '/assets/icons/fire.svg',
                'headline' => 'Start your study journey now',
                'subheadline' => 'Discover the best institutes and accredited programs around the world, and choose the destination that fits your goals with ease.',
                'promo' => 'Our offers are exclusive. We guarantee the best prices and services and will pay the difference if you find a better price or service.',
                'services' => ['Accommodation', 'Pickup', 'Insurance'],
                'button_text' => 'Search',
                'background_image' => '',
                'figure_image' => '/assets/fig.png',
                'search_placeholder' => 'Enter your preferred destination',
                'search_subtext' => 'Enter country, city, or institute',
                'course_label' => 'Course type',
                'course_placeholder' => 'Select course type',
                'weeks_label' => 'Number of weeks',
                'weeks_placeholder' => 'Select weeks',
                'start_label' => 'Start date',
                'start_placeholder' => 'Select start date',
                'search_button_text' => 'Search',
            ],
            'stats' => [
                'heading' => 'Our achievements in numbers',
                'body' => 'Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.',
                'items' => [
                    ['value' => '+15,000', 'label' => 'Students enrolled in accredited English programs abroad'],
                    ['value' => '+50', 'label' => 'Partner universities offering accredited English study programs abroad'],
                ],
            ],
            'stats_mobile' => [
                'heading' => 'Our achievements because thousands of students chose us',
                'body' => 'Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.',
                'items' => [
                    ['value' => '+15,000', 'label' => 'Students', 'ar_label' => 'طالب وطالبة'],
                    ['value' => '+50', 'label' => 'Partner universities', 'ar_label' => 'شراكة مع جامعات'],
                ],
            ],
            'certificates_meta' => [
                'heading' => 'We are accredited by many institutions',
                'subheading' => 'We are proud of our partnerships with leading English language institutes and accredited educational organizations around the world.',
            ],
            'partners' => [
                'heading' => 'Partner institutes around the world',
                'view_all_label' => 'View all schools',
                'view_all_url' => '/language-institutes',
                'arrow_left_icon' => '/assets/icons/arrow-left.svg',
                'arrow_right_icon' => '/assets/icons/arrow-right.svg',
            ],
            'summer' => [
                'heading' => 'Summer Programs',
                'cta_text' => 'View all programs',
                'cta_url' => '/summer-programs',
            ],
            'online' => [
                'heading' => 'Popular Online English Courses',
                'cta_text' => 'View all programs',
                'cta_url' => '/online-courses',
            ],
            'reviews' => [
                'heading' => 'What our students say about their English courses',
            ],
            'destinations' => [
                'heading' => 'Best destinations to study languages',
                'links' => [
                    ['label' => 'Language institutes in China', 'url' => '/language-institutes/china'],
                    ['label' => 'Language institutes in Japan', 'url' => '/language-institutes/japan'],
                    ['label' => 'Language institutes in South Korea', 'url' => '/language-institutes/south-korea'],
                    ['label' => 'Language institutes in Malaysia', 'url' => '/language-institutes/malaysia'],
                    ['label' => 'Language institutes in Singapore', 'url' => '/language-institutes/singapore'],
                    ['label' => 'Language institutes in Thailand', 'url' => '/language-institutes/thailand'],
                    ['label' => 'Language institutes in India', 'url' => '/language-institutes/india'],
                    ['label' => 'Language institutes in Indonesia', 'url' => '/language-institutes/indonesia'],
                    ['label' => 'Language institutes in the Philippines', 'url' => '/language-institutes/philippines'],
                    ['label' => 'Language institutes in Vietnam', 'url' => '/language-institutes/vietnam'],
                    ['label' => 'Language institutes in the UK', 'url' => '/language-institutes/uk'],
                    ['label' => 'Language institutes in France', 'url' => '/language-institutes/france'],
                    ['label' => 'Language institutes in Ireland', 'url' => '/language-institutes/ireland'],
                    ['label' => 'Language institutes in Germany', 'url' => '/language-institutes/germany'],
                    ['label' => 'Language institutes in the Netherlands', 'url' => '/language-institutes/netherlands'],
                    ['label' => 'Language institutes in Canada', 'url' => '/language-institutes/canada'],
                    ['label' => 'Language institutes in USA', 'url' => '/language-institutes/usa'],
                    ['label' => 'Language institutes in Australia', 'url' => '/language-institutes/australia'],
                    ['label' => 'Language institutes in South Africa', 'url' => '/language-institutes/south-africa'],
                ],
            ],
            'faq' => [
                'heading' => 'Questions & Answers',
                'subheading' => 'We help you decide with confidence. These are the most common questions prospective students ask us.',
                'cta_text' => 'Still have a question?',
                'cta_url' => '/contact-us',
                'cta_icon' => '/assets/icons/arrow-right.svg',
                'help_text' => "We're here to help you.",
                'show_more_label' => 'Show more',
            ],
            'blogs' => [
                'heading' => 'Blogs & Latest News',
                'cta_text' => 'All articles',
                'cta_url' => '/articles',
            ],
        ];

        $ar = [
            'hero' => [
                'promo_text' => 'عروضنا حصرية. نضمن أفضل الأسعار والخدمات وندفع الفرق إذا وجدت سعرًا أو خدمة أفضل.',
                'promo_icon' => $content['hero']['promo_icon'],
                'headline' => 'ابدأ رحلتك الدراسية الآن',
                'subheadline' => 'اكتشف أفضل المعاهد والبرامج المعتمدة حول العالم واختر الوجهة التي تناسب أهدافك بسهولة.',
                'promo' => 'عروضنا حصرية. نضمن أفضل الأسعار والخدمات وندفع الفرق إذا وجدت سعرًا أو خدمة أفضل.',
                'services' => ['السكن', 'الاستقبال', 'التأمين'],
                'button_text' => 'ابحث',
                'background_image' => $content['hero']['background_image'],
                'figure_image' => $content['hero']['figure_image'],
                'search_placeholder' => 'أدخل وجهتك المفضلة',
                'search_subtext' => 'أدخل الدولة أو المدينة أو المعهد',
                'course_label' => 'نوع الدورة',
                'course_placeholder' => 'اختر نوع الدورة',
                'weeks_label' => 'عدد الأسابيع',
                'weeks_placeholder' => 'اختر الأسابيع',
                'start_label' => 'تاريخ البدء',
                'start_placeholder' => 'اختر تاريخ البدء',
                'search_button_text' => 'ابحث',
            ],
            'stats' => [
                'heading' => 'إنجازاتنا بالأرقام',
                'body' => 'بفضل شركائنا الموثوقين والمؤسسات التعليمية الرائدة حول العالم ساعدنا آلاف الطلاب على تحقيق حلمهم في تعلم اللغة الإنجليزية في بيئات دولية معتمدة.',
                'items' => [
                    ['value' => '+15,000', 'label' => 'طلاب مسجلون في برامج إنجليزية معتمدة بالخارج'],
                    ['value' => '+50', 'label' => 'جامعات شريكة تقدم برامج دراسة اللغة الإنجليزية بالخارج'],
                ],
            ],
            'stats_mobile' => [
                'heading' => 'إنجازاتنا واعتمادنا لأن آلاف الطلاب اختارونا',
                'body' => 'بفضل شركائنا الموثوقين والمؤسسات التعليمية الرائدة حول العالم ساعدنا آلاف الطلاب على تحقيق حلمهم في تعلم اللغة الإنجليزية في بيئات دولية معتمدة.',
                'items' => [
                    ['value' => '+15,000', 'label' => 'طالب وطالبة', 'ar_label' => 'طالب وطالبة'],
                    ['value' => '+50', 'label' => 'شراكة مع جامعات', 'ar_label' => 'شراكة مع جامعات'],
                ],
            ],
            'certificates_meta' => [
                'heading' => 'معتمدون من العديد من المؤسسات',
                'subheading' => 'نفخر بشراكاتنا مع معاهد اللغة الإنجليزية الرائدة والمنظمات التعليمية المعتمدة حول العالم.',
            ],
            'partners' => [
                'heading' => 'المعاهد الشريكة حول العالم',
                'view_all_label' => 'عرض جميع المعاهد',
                'view_all_url' => $content['partners']['view_all_url'],
                'arrow_left_icon' => $content['partners']['arrow_left_icon'],
                'arrow_right_icon' => $content['partners']['arrow_right_icon'],
            ],
            'summer' => [
                'heading' => 'برامج الصيف',
                'cta_text' => 'عرض جميع البرامج',
                'cta_url' => $content['summer']['cta_url'],
            ],
            'online' => [
                'heading' => 'دورات اللغة الإنجليزية عبر الإنترنت',
                'cta_text' => $content['online']['cta_text'],
                'cta_url' => $content['online']['cta_url'],
            ],
            'reviews' => [
                'heading' => 'آراء طلابنا حول دورات اللغة الإنجليزية',
            ],
            'destinations' => [
                'heading' => 'أفضل الوجهات لدراسة اللغات',
                'links' => $content['destinations']['links'],
            ],
            'faq' => [
                'heading' => 'الأسئلة الشائعة',
                'subheading' => 'نحن نساعدك على اتخاذ القرار بثقة. هذه هي الأسئلة الأكثر شيوعًا التي يطرحها علينا الطلاب المحتملون.',
                'cta_text' => 'ما زال لديك سؤال؟',
                'cta_url' => $content['faq']['cta_url'],
                'cta_icon' => $content['faq']['cta_icon'],
                'help_text' => 'نحن هنا لمساعدتك',
                'show_more_label' => 'عرض المزيد',
            ],
            'blogs' => [
                'heading' => 'المدونة وآخر الأخبار',
                'cta_text' => $content['blogs']['cta_text'],
                'cta_url' => $content['blogs']['cta_url'],
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'home'],
            [
                'title' => 'Home',
                'ar_title' => 'الرئيسية',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'CourseEnglish | Home',
                'meta_description' => 'English courses with live feedback, modern lessons, and clear results.',
                'is_active' => true,
                'display_order' => 1,
            ]
        );
    }
}
