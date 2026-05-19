<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishLanguageInstituteDetailSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'top_nav' => [
                'back_label' => 'Back to List',
                'compare_label' => 'Compare',
                'save_label' => 'Save',
                'share_label' => 'Share',
                'back_icon' => '/assets/icons/arrow-left.svg',
                'compare_icon' => '/assets/icons/compare.svg',
                'save_icon' => '/assets/icons/heart-regular-black2.svg',
            ],
            'course_step' => [
                'title' => 'Step 1: Choose the Suitable Course',
                'lessons_label' => 'Lessons/week',
                'hours_label' => 'Hours',
                'age_label' => 'Age',
                'level_label' => 'Level',
                'price_suffix' => '/ week',
            ],
            'accommodation_step' => [
                'title' => 'Choose Accommodation',
                'optional_label' => '(Optional)',
                'no_accommodation_title' => 'No Accommodation',
                'no_accommodation_description' => 'Select this if you have arranged your own stay.',
                'price_suffix' => '/ week',
            ],
            'additional_options' => [
                'heading' => 'Additional Options',
            ],
            'sidebar_inquiry' => [
                'title' => 'Have a Question?',
                'description' => 'Do you have questions or need more info about this course?',
                'button_text' => 'Chat via WhatsApp',
            ],
            'booking_summary' => [
                'total_label' => 'Total inclusive of all fees',
                'study_dates_label' => 'Study Dates',
                'duration_label' => 'Duration',
                'coupon_label' => 'Do you have a coupon?',
                'coupon_placeholder' => 'Coupon Code',
                'coupon_apply_text' => 'Apply',
                'course_summary_label' => 'General English (12 Weeks)',
                'accommodation_summary_label' => 'Homestay (12 Weeks)',
                'registration_fee_label' => 'Registration Fee',
                'course_discount_label' => 'Course Discount',
                'foundation_discount_label' => 'Foundation Discount',
                'total_discount_label' => 'Total Discount',
                'confirm_button_text' => 'Review & Confirm',
            ],
        ];

        $ar = [
            'top_nav' => [
                'back_label' => 'العودة إلى القائمة',
                'compare_label' => 'مقارنة',
                'save_label' => 'حفظ',
                'share_label' => 'مشاركة',
                'back_icon' => $content['top_nav']['back_icon'],
                'compare_icon' => $content['top_nav']['compare_icon'],
                'save_icon' => $content['top_nav']['save_icon'],
            ],
            'course_step' => [
                'title' => 'الخطوة 1: اختر الدورة المناسبة',
                'lessons_label' => 'حصص/أسبوع',
                'hours_label' => 'ساعات',
                'age_label' => 'العمر',
                'level_label' => 'المستوى',
                'price_suffix' => $content['course_step']['price_suffix'],
            ],
            'accommodation_step' => [
                'title' => 'اختر السكن',
                'optional_label' => '(اختياري)',
                'no_accommodation_title' => 'بدون سكن',
                'no_accommodation_description' => 'اختر هذا الخيار إذا رتبت سكنك بنفسك.',
                'price_suffix' => $content['accommodation_step']['price_suffix'],
            ],
            'additional_options' => [
                'heading' => 'خيارات إضافية',
            ],
            'sidebar_inquiry' => [
                'title' => 'هل لديك سؤال؟',
                'description' => 'هل لديك أسئلة أو تحتاج لمزيد من المعلومات عن هذا البرنامج؟',
                'button_text' => 'تواصل عبر واتساب',
            ],
            'booking_summary' => [
                'total_label' => 'الإجمالي شامل جميع الرسوم',
                'study_dates_label' => 'تواريخ الدراسة',
                'duration_label' => 'المدة',
                'coupon_label' => 'هل لديك قسيمة خصم؟',
                'coupon_placeholder' => 'رمز القسيمة',
                'coupon_apply_text' => 'تطبيق',
                'course_summary_label' => $content['booking_summary']['course_summary_label'],
                'accommodation_summary_label' => $content['booking_summary']['accommodation_summary_label'],
                'registration_fee_label' => 'رسوم التسجيل',
                'course_discount_label' => 'خصم الدورة',
                'foundation_discount_label' => 'خصم المؤسسة',
                'total_discount_label' => 'إجمالي الخصم',
                'confirm_button_text' => 'مراجعة وتأكيد',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'language-institute-detail'],
            [
                'title' => 'Language Institute Detail',
                'ar_title' => 'تفاصيل معهد اللغة',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Institute Detail',
                'meta_description' => 'Explore institute details, courses, accommodation, and booking summary.',
                'is_active' => true,
                'display_order' => 5,
            ]
        );
    }
}
