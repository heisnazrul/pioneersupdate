<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreshFaqSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'category' => 'General',
                'ar_category' => 'عام',
                'question' => 'How do I choose the right English course?',
                'ar_question' => 'كيف أختار دورة اللغة الإنجليزية المناسبة؟',
                'answer' => 'Start with your goal, budget, destination, and preferred study intensity. Compare course type, weekly lessons, and accommodation options before booking.',
                'ar_answer' => 'ابدأ بتحديد هدفك وميزانيتك والوجهة المناسبة ومستوى الدراسة الذي تفضله. قارن بين نوع الدورة وعدد الدروس الأسبوعية وخيارات السكن قبل الحجز.',
                'display_order' => 1,
            ],
            [
                'category' => 'Booking',
                'ar_category' => 'الحجز',
                'question' => 'What is included in the total booking price?',
                'ar_question' => 'ماذا يشمل إجمالي سعر الحجز؟',
                'answer' => 'The final total may include tuition, registration fees, accommodation, airport pickup, insurance, and any selected add-ons depending on the school and package.',
                'ar_answer' => 'قد يشمل السعر النهائي الرسوم الدراسية ورسوم التسجيل والسكن والاستقبال من المطار والتأمين وأي خدمات إضافية يتم اختيارها بحسب المعهد أو الباقة.',
                'display_order' => 2,
            ],
            [
                'category' => 'Accommodation',
                'ar_category' => 'السكن',
                'question' => 'Can I book a course without accommodation?',
                'ar_question' => 'هل يمكنني حجز الدورة بدون سكن؟',
                'answer' => 'Yes. Many schools allow you to continue without accommodation if you prefer to arrange your own stay.',
                'ar_answer' => 'نعم، تتيح لك العديد من المعاهد متابعة الحجز بدون سكن إذا كنت تفضل ترتيب إقامتك بنفسك.',
                'display_order' => 3,
            ],
            [
                'category' => 'Travel',
                'ar_category' => 'السفر',
                'question' => 'Do you help with airport pickup and arrival support?',
                'ar_question' => 'هل تقدمون خدمة الاستقبال من المطار والمساعدة عند الوصول؟',
                'answer' => 'Yes. Many partner schools provide airport pickup options, and you can add them during the booking process when available.',
                'ar_answer' => 'نعم، توفر العديد من المعاهد الشريكة خيارات الاستقبال من المطار، ويمكنك إضافتها أثناء عملية الحجز عند توفرها.',
                'display_order' => 4,
            ],
            [
                'category' => 'Payments',
                'ar_category' => 'الدفع',
                'question' => 'Can I compare offers before making a payment?',
                'ar_question' => 'هل يمكنني مقارنة العروض قبل الدفع؟',
                'answer' => 'Yes. You can compare schools, course details, and pricing before confirming your reservation.',
                'ar_answer' => 'نعم، يمكنك مقارنة المعاهد وتفاصيل الدورات والأسعار قبل تأكيد الحجز.',
                'display_order' => 5,
            ],
            [
                'category' => 'Agents',
                'ar_category' => 'الوكلاء',
                'question' => 'Can an agent submit bookings on behalf of students?',
                'ar_question' => 'هل يمكن للوكيل تقديم الحجوزات نيابة عن الطلاب؟',
                'answer' => 'Yes. Agent users can manage student leads and submit bookings on behalf of their students through the platform workflow.',
                'ar_answer' => 'نعم، يمكن لمستخدمي الوكلاء إدارة الطلاب المحتملين وتقديم الحجوزات نيابة عن طلابهم من خلال سير العمل داخل المنصة.',
                'display_order' => 6,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('faqs')->updateOrInsert(
                ['category' => $row['category'], 'question' => $row['question']],
                array_merge($row, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
