<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishContactSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'breadcrumb' => [
                'home' => 'Home',
                'current' => 'Contact Us',
            ],
            'title' => 'Contact Us',
            'cards' => [
                [
                    'icon' => 'phone',
                    'label' => 'Customer Service',
                    'value' => '+966 55 487 9888',
                    'href' => 'tel:+966554879888',
                ],
                [
                    'icon' => 'whatsapp',
                    'label' => 'WhatsApp',
                    'value' => '+966 55 487 9888',
                    'href' => 'https://wa.me/966554879888',
                ],
                [
                    'icon' => 'email',
                    'label' => 'Email Us',
                    'value' => 'Courseenglish@gmail.com',
                    'href' => 'mailto:Courseenglish@gmail.com',
                ],
            ],
            'form' => [
                'title' => 'Or send us a message and we will get back to you quickly',
                'full_name_label' => 'Full Name',
                'full_name_placeholder' => 'Enter full name',
                'email_label' => 'Email',
                'email_placeholder' => 'Enter email',
                'message_label' => 'Message',
                'message_placeholder' => 'Write your message',
                'submit_text' => 'Send',
                'sending_text' => 'Sending...',
                'success_text' => 'Message sent successfully.',
                'fail_text' => 'Failed to send message. Please try again.',
            ],
        ];

        $arContent = [
            'breadcrumb' => [
                'home' => 'الرئيسية',
                'current' => 'اتصل بنا',
            ],
            'title' => 'اتصل بنا',
            'cards' => [
                [
                    'icon' => 'phone',
                    'label' => 'تواصل مع خدمة العملاء',
                    'value' => '+966 55 487 9888',
                    'href' => 'tel:+966554879888',
                ],
                [
                    'icon' => 'whatsapp',
                    'label' => 'تواصل بالواتساب',
                    'value' => '+966 55 487 9888',
                    'href' => 'https://wa.me/966554879888',
                ],
                [
                    'icon' => 'email',
                    'label' => 'راسلنا على',
                    'value' => 'Courseenglish@gmail.com',
                    'href' => 'mailto:Courseenglish@gmail.com',
                ],
            ],
            'form' => [
                'title' => 'أو يمكنك إرسال رسالة وسيتم الرد عليك في أسرع وقت',
                'full_name_label' => 'الاسم بالكامل',
                'full_name_placeholder' => 'ادخل الاسم بالكامل',
                'email_label' => 'البريد الالكتروني',
                'email_placeholder' => 'ادخل البريد الالكتروني',
                'message_label' => 'رسالتك',
                'message_placeholder' => 'رسالتك',
                'submit_text' => 'ارسال',
                'sending_text' => 'جاري الإرسال...',
                'success_text' => 'تم إرسال الرسالة بنجاح.',
                'fail_text' => 'تعذر إرسال الرسالة. حاول مرة أخرى.',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'contact-us'],
            [
                'title' => 'Contact Us',
                'ar_title' => 'اتصل بنا',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($arContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Contact Us | Course English',
                'meta_description' => 'Contact Course English via phone, WhatsApp, or email and send us a message.',
                'is_active' => true,
                'display_order' => 7,
            ]
        );
    }
}
