<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'heading' => 'Latest stories and guides',
                'subheading' => 'Browse insights, tips, and how-tos for your study journey.',
            ],
            'empty_state' => [
                'heading' => 'Articles',
                'message' => 'No posts available. Please cache data from the admin panel first.',
            ],
            'card' => [
                'read_more_label' => 'Read more',
                'category_fallback' => 'Article',
            ],
            'sidebar' => [
                'recent_title' => 'Recent posts',
                'recent_badge' => 'Latest',
                'categories_title' => 'Categories',
            ],
        ];

        $ar = [
            'hero' => [
                'heading' => 'أحدث المقالات والأدلة',
                'subheading' => 'اطّلع على النصائح والإرشادات لرحلتك الدراسية.',
            ],
            'empty_state' => [
                'heading' => 'المقالات',
                'message' => 'لا توجد مقالات حالياً. يرجى تحديث البيانات من لوحة التحكم أولاً.',
            ],
            'card' => [
                'read_more_label' => 'اقرأ المزيد',
                'category_fallback' => 'مقال',
            ],
            'sidebar' => [
                'recent_title' => 'أحدث المقالات',
                'recent_badge' => 'الأحدث',
                'categories_title' => 'التصنيفات',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'articles'],
            [
                'title' => 'Articles',
                'ar_title' => 'المقالات',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Articles',
                'meta_description' => 'Read the latest stories, tips, and study guides.',
                'is_active' => true,
                'display_order' => 6,
            ]
        );
    }
}
