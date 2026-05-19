<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishCompareSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'title' => 'Compare Institutes',
                'subtitle' => 'Compare features, prices, and ratings side by side.',
            ],
            'table' => [
                'criteria_label' => 'Criteria',
                'price_label' => 'Price',
                'action_button_text' => 'View Institute',
            ],
        ];

        $ar = [
            'hero' => [
                'title' => 'مقارنة المعاهد',
                'subtitle' => 'قارن الميزات والأسعار والتقييمات جنبًا إلى جنب.',
            ],
            'table' => [
                'criteria_label' => 'المعيار',
                'price_label' => 'السعر',
                'action_button_text' => 'عرض المعهد',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'compare'],
            [
                'title' => 'Compare',
                'ar_title' => 'المقارنة',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Compare Institutes',
                'meta_description' => 'Compare institutes side by side to choose the best fit.',
                'is_active' => true,
                'display_order' => 10,
            ]
        );
    }
}
