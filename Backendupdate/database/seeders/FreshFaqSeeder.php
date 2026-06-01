<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FreshFaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::query()->update(['is_active' => false]);

        $rows = [
            // ─── Getting Started / البدء ─────────────────────────────────────
            [
                'category' => 'Getting Started',
                'ar_category' => 'البدء',
                'question' => 'How can I sign up for a Pioneers account?',
                'ar_question' => 'كيف يمكنني الاشتراك في حساب الرواد التعليمي؟',
                'answer' => 'It is easy. Visit our website, click Sign Up, and follow the steps. You can register with your email or a social media account.',
                'ar_answer' => 'الأمر سهل! قم بزيارة موقعنا الإلكتروني، وانقر على "Sign Up"، واتبع التعليمات. يمكنك التسجيل باستخدام بريد إلكتروني أو حساب من وسائل التواصل.',
                'display_order' => 1,
            ],
            [
                'category' => 'Getting Started',
                'ar_category' => 'البدء',
                'question' => 'Can I access the site from multiple devices?',
                'ar_question' => 'هل يمكنني الوصول إلى الموقع من عدة أجهزة؟',
                'answer' => 'Yes. Sign in with the same account on your phone, tablet, or computer. Your saved schools, wishlist, and booking progress stay synced.',
                'ar_answer' => 'نعم، يمكنك تسجيل الدخول بنفس الحساب من جوالك أو جهازك اللوحي أو الكمبيوتر. المدارس المحفوظة وقائمة الأمنيات ومتابعة الحجز تبقى متزامنة بين الأجهزة.',
                'display_order' => 2,
            ],
            [
                'category' => 'Getting Started',
                'ar_category' => 'البدء',
                'question' => 'How do I find and compare different schools or programmes?',
                'ar_question' => 'كيف أجد وأقارن بين المدارس أو البرامج المختلفة؟',
                'answer' => 'Use search filters for destination, course type, dates, and budget. Open any school page for full details, then add options to Compare to review them side by side.',
                'ar_answer' => 'استخدم فلاتر البحث حسب الوجهة ونوع الدورة والتواريخ والميزانية. افتح صفحة أي معهد لعرض التفاصيل كاملة، ثم أضف الخيارات إلى المقارنة لمراجعتها بجانب بعض.',
                'display_order' => 3,
            ],
            [
                'category' => 'Getting Started',
                'ar_category' => 'البدء',
                'question' => 'How do I save schools I like for later?',
                'ar_question' => 'كيف أحفظ المعاهد التي أعجبتني للرجوع إليها لاحقًا؟',
                'answer' => 'After signing in, tap the heart icon on any school or course card to add it to your wishlist. You can review saved options anytime from your student dashboard.',
                'ar_answer' => 'بعد تسجيل الدخول، اضغط على أيقونة القلب في بطاقة المعهد أو الدورة لإضافتها إلى قائمة الأمنيات. يمكنك مراجعة الخيارات المحفوظة في أي وقت من لوحة الطالب.',
                'display_order' => 4,
            ],
            [
                'category' => 'Getting Started',
                'ar_category' => 'البدء',
                'question' => 'What happens after I submit a booking request?',
                'ar_question' => 'ماذا يحدث بعد إرسال طلب الحجز؟',
                'answer' => 'Our team reviews your details, confirms availability with the school, and updates you by email or through your account. You can track status from My Bookings.',
                'ar_answer' => 'يراجع فريقنا بياناتك ويتأكد من التوفر لدى المعهد، ثم يحدّثك عبر البريد أو من خلال حسابك. يمكنك متابعة حالة الطلب من صفحة حجوزاتي.',
                'display_order' => 5,
            ],

            // ─── Pricing / التسعير ─────────────────────────────────────────
            [
                'category' => 'Pricing',
                'ar_category' => 'التسعير',
                'question' => 'What is included in the total booking price?',
                'ar_question' => 'ماذا يشمل إجمالي سعر الحجز؟',
                'answer' => 'The total may include tuition, registration fees, accommodation, airport transfer, insurance, and any add-ons you select. Each quote shows a clear breakdown before you confirm.',
                'ar_answer' => 'قد يشمل الإجمالي الرسوم الدراسية ورسوم التسجيل والسكن والاستقبال من المطار والتأمين وأي خدمات إضافية تختارها. كل عرض يوضح التفاصيل قبل التأكيد.',
                'display_order' => 6,
            ],
            [
                'category' => 'Pricing',
                'ar_category' => 'التسعير',
                'question' => 'Are registration fees charged separately?',
                'ar_question' => 'هل تُفرض رسوم التسجيل بشكل منفصل؟',
                'answer' => 'Some schools charge a one-time registration fee in addition to course fees. When applicable, it is listed separately in the price summary before checkout.',
                'ar_answer' => 'تفرض بعض المعاهد رسوم تسجيل لمرة واحدة بالإضافة إلى رسوم الدورة. عند وجودها، تظهر بشكل منفصل في ملخص السعر قبل إتمام الحجز.',
                'display_order' => 7,
            ],
            [
                'category' => 'Pricing',
                'ar_category' => 'التسعير',
                'question' => 'Can I compare prices before making a payment?',
                'ar_question' => 'هل يمكنني مقارنة الأسعار قبل الدفع؟',
                'answer' => 'Yes. Use Compare to view tuition, fees, and duration for multiple schools at once. You only pay after choosing the option that fits your budget and goals.',
                'ar_answer' => 'نعم، استخدم أداة المقارنة لعرض الرسوم الدراسية والرسوم الإضافية ومدة الدراسة لعدة معاهد دفعة واحدة. الدفع يتم فقط بعد اختيار الخيار المناسب لميزانيتك وهدفك.',
                'display_order' => 8,
            ],
            [
                'category' => 'Pricing',
                'ar_category' => 'التسعير',
                'question' => 'Do you offer discounts or special offers?',
                'ar_question' => 'هل توفرون خصومات أو عروضًا خاصة؟',
                'answer' => 'Yes. Seasonal offers and partner-school promotions appear on the Offers page and on eligible school profiles. Discounts are applied automatically when you book a qualifying course.',
                'ar_answer' => 'نعم، تظهر العروض الموسمية وخصومات المعاهد الشريكة في صفحة العروض وفي ملفات المعاهد المؤهلة. تُطبَّق الخصومات تلقائيًا عند حجز دورة مؤهلة.',
                'display_order' => 9,
            ],
            [
                'category' => 'Pricing',
                'ar_category' => 'التسعير',
                'question' => 'Which currencies are prices shown in?',
                'ar_question' => 'بأي عملات تُعرض الأسعار؟',
                'answer' => 'Prices are shown in the school\'s local currency with an approximate conversion when helpful. Final charges follow the school\'s invoice currency and payment terms.',
                'ar_answer' => 'تُعرض الأسعار بعملة المعهد المحلية مع تحويل تقريبي عند الحاجة. الرسوم النهائية تتبع عملة فاتورة المعهد وشروط الدفع الخاصة به.',
                'display_order' => 10,
            ],

            // ─── United Kingdom / المملكة المتحدة ──────────────────────────
            [
                'category' => 'United Kingdom',
                'ar_category' => 'المملكة المتحدة',
                'question' => 'Do I need a visa to study English in the UK?',
                'ar_question' => 'هل أحتاج إلى تأشيرة لدراسة اللغة الإنجليزية في المملكة المتحدة؟',
                'answer' => 'Most international students need a visa depending on course length and nationality. Short courses may qualify for a Standard Visitor visa; longer programmes usually require a Student visa. We guide you on the typical requirements.',
                'ar_answer' => 'يحتاج معظم الطلاب الدوليين إلى تأشيرة حسب مدة الدورة وجنسيتهم. الدورات القصيرة قد تناسب تأشيرة الزائر القياسية، بينما البرامج الأطول تتطلب عادةً تأشيرة طالب. نرشدك إلى المتطلبات المعتادة.',
                'display_order' => 11,
            ],
            [
                'category' => 'United Kingdom',
                'ar_category' => 'المملكة المتحدة',
                'question' => 'What English level do I need before applying?',
                'ar_question' => 'ما مستوى اللغة الإنجليزية المطلوب قبل التقديم؟',
                'answer' => 'Most UK language schools accept all levels from beginner to advanced. You may take a placement test on arrival so you are placed in the right class.',
                'ar_answer' => 'تقبل أغلب معاهد اللغة في بريطانيا جميع المستويات من المبتدئ إلى المتقدم. قد تجري اختبار تحديد مستوى عند الوصول لوضعك في الفصل المناسب.',
                'display_order' => 12,
            ],
            [
                'category' => 'United Kingdom',
                'ar_category' => 'المملكة المتحدة',
                'question' => 'How long can I study on a short-term course in the UK?',
                'ar_question' => 'ما المدة التي يمكنني الدراسة فيها في دورة قصيرة ببريطانيا؟',
                'answer' => 'Short courses range from one week to several months. The maximum stay allowed depends on your visa type and nationality. Check the course page for duration options.',
                'ar_answer' => 'تتراوح الدورات القصيرة من أسبوع إلى عدة أشهر. أقصى مدة مسموح بها تعتمد على نوع التأشيرة وجنسيتك. راجع صفحة الدورة لخيارات المدة المتاحة.',
                'display_order' => 13,
            ],
            [
                'category' => 'United Kingdom',
                'ar_category' => 'المملكة المتحدة',
                'question' => 'What accommodation options are available in the UK?',
                'ar_question' => 'ما خيارات السكن المتاحة في المملكة المتحدة؟',
                'answer' => 'Options typically include homestay with a local family, student residence, or shared apartments. Availability varies by city and school. You can add accommodation during booking when offered.',
                'ar_answer' => 'تشمل الخيارات عادةً الإقامة مع عائلة محلية أو سكن طلابي أو شقق مشتركة. التوفر يختلف حسب المدينة والمعهد. يمكنك إضافة السكن أثناء الحجز عند توفره.',
                'display_order' => 14,
            ],
            [
                'category' => 'United Kingdom',
                'ar_category' => 'المملكة المتحدة',
                'question' => 'When is the best time to apply for UK language courses?',
                'ar_question' => 'متى أفضل وقت للتقديم على دورات اللغة في بريطانيا؟',
                'answer' => 'Apply at least 4–8 weeks before your start date to secure a place and arrange visa documents. Popular summer dates fill quickly, so early booking is recommended.',
                'ar_answer' => 'يُفضَّل التقديم قبل 4–8 أسابيع من تاريخ البدء لضمان المقعد وترتيب مستندات التأشيرة. مواعيد الصيف المزدحمة تمتلئ بسرعة، لذا ننصح بالحجز المبكر.',
                'display_order' => 15,
            ],
        ];

        foreach ($rows as $row) {
            Faq::updateOrCreate(
                [
                    'category' => $row['category'],
                    'ar_question' => $row['ar_question'],
                ],
                array_merge($row, ['is_active' => true])
            );
        }

        $this->command?->info('Seeded ' . count($rows) . ' FAQs across 3 categories.');
    }
}
