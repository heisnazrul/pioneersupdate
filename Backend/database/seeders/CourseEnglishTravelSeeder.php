<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishTravelSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'badge' => 'Discover the World',
                'title' => 'Plan Your Dream Journey',
                'description' => 'From flight bookings to visa assistance, we handle all the details so you can focus on making memories. Start your adventure today with Pioneers Travel.',
                'background_image' => 'https://images.pexels.com/photos/1271619/pexels-photo-1271619.jpeg?auto=compress&cs=tinysrgb&w=1600',
                'primary_cta_text' => 'Start Planning',
                'primary_cta_url' => '#inquiry-form',
                'secondary_cta_text' => 'View Packages',
                'secondary_cta_url' => '#destinations',
            ],
            'cta_card' => [
                'heading' => 'Ready to plan your trip?',
                'subheading' => 'Get a free consultation with our travel experts today.',
                'whatsapp_text' => 'WhatsApp',
                'whatsapp_url' => 'https://wa.me/1234567890',
                'call_text' => 'Call Us',
                'call_url' => 'tel:+1234567890',
                'inquire_text' => 'Inquire Now',
                'inquire_url' => '#inquiry-form',
            ],
            'features' => [
                'items' => [
                    ['icon' => 'faHeadset', 'title' => '24/7 Support', 'description' => 'Our team is available round the clock to assist you during your trip.'],
                    ['icon' => 'faTags', 'title' => 'Best Price Guarantee', 'description' => 'We offer competitive prices and exclusive deals for all destinations.'],
                    ['icon' => 'faGlobeAmericas', 'title' => 'Global Coverage', 'description' => 'Destinations across 6 continents with local partner support.'],
                    ['icon' => 'faUserShield', 'title' => '100% Secure', 'description' => 'Your bookings and payments are secure with our trusted platform.'],
                ],
            ],
            'destinations' => [
                'eyebrow' => 'Top Destinations',
                'heading' => 'Trending Holiday Spots',
                'view_all_text' => 'View All Destinations',
                'view_all_url' => '#',
                'items' => [
                    [
                        'name' => 'London, UK',
                        'image' => 'https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=800',
                        'tag' => 'Popular',
                        'url' => '/language-institutes?country_slug=united-kingdom',
                        'price' => '',
                    ],
                    [
                        'name' => 'Paris, France',
                        'image' => 'https://images.pexels.com/photos/1850619/pexels-photo-1850619.jpeg?auto=compress&cs=tinysrgb&w=800',
                        'tag' => 'Romance',
                        'url' => '/language-institutes?city_slug=paris',
                        'price' => '',
                    ],
                    [
                        'name' => 'Dubai, UAE',
                        'image' => 'https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&w=800',
                        'tag' => 'Luxury',
                        'url' => '/language-institutes?city_slug=dubai',
                        'price' => '',
                    ],
                    [
                        'name' => 'Istanbul, Turkey',
                        'image' => 'https://images.pexels.com/photos/3566139/pexels-photo-3566139.jpeg?auto=compress&cs=tinysrgb&w=800',
                        'tag' => 'Culture',
                        'url' => '/language-institutes?city_slug=istanbul',
                        'price' => '',
                    ],
                ],
            ],
            'services' => [
                'eyebrow' => 'Our Services',
                'heading' => 'Everything You Need for a Perfect Trip',
                'description' => 'We offer a comprehensive range of travel services to ensure your experience is seamless, memorable, and hassle-free.',
                'items' => [
                    ['icon' => 'faPlaneDeparture', 'title' => 'Flight Bookings', 'description' => 'Best deals on domestic and international flights. We ensure a smooth journey from takeoff to landing.'],
                    ['icon' => 'faHotel', 'title' => 'Hotel Reservations', 'description' => 'From luxury resorts to budget-friendly stays, find the perfect accommodation for your trip.'],
                    ['icon' => 'faPassport', 'title' => 'Visa Assistance', 'description' => 'Expert guidance for tourist, student, and business visas. We handle the paperwork, you pack the bags.'],
                    ['icon' => 'faMapMarkedAlt', 'title' => 'Tour Packages', 'description' => 'Curated holiday packages for families, couples, and solo travelers to the world\'s best destinations.'],
                    ['icon' => 'faUmbrellaBeach', 'title' => 'Beach Holidays', 'description' => 'Relax on the most pristine beaches with our exclusive island getaway packages.'],
                    ['icon' => 'faShip', 'title' => 'Cruises', 'description' => 'Set sail on a luxury cruise and explore multiple destinations with premium onboard amenities.'],
                ],
            ],
            'inquiry' => [
                'title' => 'Plan Your Trip',
                'description' => 'Fill out the form and our travel experts will contact you to arrange the perfect trip tailored to your needs.',
                'bullets' => [
                    'Direct flight bookings worldwide',
                    'Premium hotel reservations',
                    'Hassle-free visa assistance',
                ],
                'whatsapp_text' => 'Chat on WhatsApp',
                'whatsapp_url' => 'https://wa.me/1234567890',
                'form_title' => 'Travel Inquiry',
                'form_name_label' => 'Full Name',
                'form_name_placeholder' => 'Your Name',
                'form_phone_label' => 'Phone Number',
                'form_phone_placeholder' => '+1 234 567 890',
                'form_destination_label' => 'Destination',
                'form_destination_placeholder' => 'e.g. London, Dubai, Paris',
                'form_date_label' => 'Travel Date (Optional)',
                'form_message_label' => 'Requirements / Message',
                'form_message_placeholder' => 'Tell us more about your trip...',
                'form_submit_text' => 'Send Request',
            ],
        ];

        $ar = [
            'hero' => [
                'badge' => 'اكتشف العالم',
                'title' => 'خطط لرحلتك القادمة',
                'description' => 'من حجوزات الطيران إلى التأشيرات، نتولى التفاصيل لتستمتع برحلتك. ابدأ مغامرتك اليوم مع بايونيرز ترافل.',
                'background_image' => $content['hero']['background_image'],
                'primary_cta_text' => 'ابدأ التخطيط',
                'primary_cta_url' => $content['hero']['primary_cta_url'],
                'secondary_cta_text' => 'عرض الباقات',
                'secondary_cta_url' => $content['hero']['secondary_cta_url'],
            ],
            'cta_card' => [
                'heading' => 'جاهز لتخطيط رحلتك؟',
                'subheading' => 'احصل على استشارة مجانية مع خبرائنا اليوم.',
                'whatsapp_text' => 'واتساب',
                'whatsapp_url' => $content['cta_card']['whatsapp_url'],
                'call_text' => 'اتصل بنا',
                'call_url' => $content['cta_card']['call_url'],
                'inquire_text' => 'استفسر الآن',
                'inquire_url' => $content['cta_card']['inquire_url'],
            ],
            'features' => [
                'items' => [
                    ['icon' => 'faHeadset', 'title' => 'دعم 24/7', 'description' => 'فريقنا متاح على مدار الساعة لمساعدتك أثناء الرحلة.'],
                    ['icon' => 'faTags', 'title' => 'أفضل سعر مضمون', 'description' => 'نقدم أسعارًا تنافسية وعروضًا حصرية لجميع الوجهات.'],
                    ['icon' => 'faGlobeAmericas', 'title' => 'تغطية عالمية', 'description' => 'وجهات عبر 6 قارات مع شركاء محليين.'],
                    ['icon' => 'faUserShield', 'title' => 'أمان 100%', 'description' => 'حجوزاتك ومدفوعاتك آمنة عبر منصتنا الموثوقة.'],
                ],
            ],
            'destinations' => [
                'eyebrow' => 'أفضل الوجهات',
                'heading' => 'وجهات سياحية رائجة',
                'view_all_text' => 'عرض جميع الوجهات',
                'view_all_url' => $content['destinations']['view_all_url'],
                'items' => [
                    [
                        'name' => 'لندن، المملكة المتحدة',
                        'image' => $content['destinations']['items'][0]['image'],
                        'price' => '',
                        'tag' => 'رائجة',
                        'url' => $content['destinations']['items'][0]['url'],
                    ],
                    [
                        'name' => 'باريس، فرنسا',
                        'image' => $content['destinations']['items'][1]['image'],
                        'price' => '',
                        'tag' => 'رومانسية',
                        'url' => $content['destinations']['items'][1]['url'],
                    ],
                    [
                        'name' => 'دبي، الإمارات',
                        'image' => $content['destinations']['items'][2]['image'],
                        'price' => '',
                        'tag' => 'فاخرة',
                        'url' => $content['destinations']['items'][2]['url'],
                    ],
                    [
                        'name' => 'إسطنبول، تركيا',
                        'image' => $content['destinations']['items'][3]['image'],
                        'price' => '',
                        'tag' => 'ثقافة',
                        'url' => $content['destinations']['items'][3]['url'],
                    ],
                ],
            ],
            'services' => [
                'eyebrow' => 'خدماتنا',
                'heading' => 'كل ما تحتاجه لرحلة مثالية',
                'description' => 'نقدم مجموعة شاملة من خدمات السفر لضمان تجربة سلسة ومميزة.',
                'items' => [
                    ['icon' => 'faPlaneDeparture', 'title' => 'حجوزات الطيران', 'description' => 'أفضل العروض على الرحلات الداخلية والدولية لضمان رحلة سلسة من الإقلاع حتى الهبوط.'],
                    ['icon' => 'faHotel', 'title' => 'حجوزات الفنادق', 'description' => 'من المنتجعات الفاخرة إلى الإقامات الاقتصادية، اعثر على السكن المثالي لرحلتك.'],
                    ['icon' => 'faPassport', 'title' => 'مساعدة التأشيرات', 'description' => 'إرشاد خبير لتأشيرات السياحة والدراسة والأعمال. نتولى الأوراق وأنت تحزم الحقائب.'],
                    ['icon' => 'faMapMarkedAlt', 'title' => 'الباقات السياحية', 'description' => 'باقات عطلات منسقة للعائلات والأزواج والمسافرين الفرديين إلى أفضل الوجهات حول العالم.'],
                    ['icon' => 'faUmbrellaBeach', 'title' => 'عطلات الشواطئ', 'description' => 'استرخِ على أجمل الشواطئ مع باقات الجزر الحصرية لدينا.'],
                    ['icon' => 'faShip', 'title' => 'الرحلات البحرية', 'description' => 'أبحر في رحلة فاخرة واستكشف وجهات متعددة مع خدمات مميزة على متن السفينة.'],
                ],
            ],
            'inquiry' => [
                'title' => 'خطط لرحلتك',
                'description' => 'املأ النموذج وسيتواصل معك خبراؤنا لترتيب الرحلة المثالية لك.',
                'bullets' => [
                    'حجوزات طيران مباشرة حول العالم',
                    'حجوزات فنادق مميزة',
                    'مساعدة في التأشيرات بدون تعقيدات',
                ],
                'whatsapp_text' => 'تواصل عبر واتساب',
                'whatsapp_url' => $content['inquiry']['whatsapp_url'],
                'form_title' => 'طلب سفر',
                'form_name_label' => 'الاسم الكامل',
                'form_name_placeholder' => 'اسمك',
                'form_phone_label' => 'رقم الهاتف',
                'form_phone_placeholder' => '+1 234 567 890',
                'form_destination_label' => 'الوجهة',
                'form_destination_placeholder' => 'مثل لندن، دبي، باريس',
                'form_date_label' => 'تاريخ السفر (اختياري)',
                'form_message_label' => 'المتطلبات / الرسالة',
                'form_message_placeholder' => 'أخبرنا المزيد عن رحلتك...',
                'form_submit_text' => 'إرسال الطلب',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'travel-and-tourism'],
            [
                'title' => 'Travel & Tourism',
                'ar_title' => 'السفر والسياحة',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Travel & Tourism',
                'meta_description' => 'Plan your next trip with our travel experts and curated packages.',
                'is_active' => true,
                'display_order' => 8,
            ]
        );
    }
}
