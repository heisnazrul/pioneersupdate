<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = DB::table('blog_categories')
            ->pluck('id', 'slug');

        $rows = [
            [
                'title' => 'Top 10 Tips for Learning English Fast',
                'ar_title' => 'أفضل 10 نصائح لتعلم الإنجليزية بسرعة',
                'slug' => 'top-10-tips-for-learning-english-fast',
                'summary' => 'Simple habits that improve your English every day.',
                'ar_summary' => 'عادات بسيطة تُحسّن لغتك الإنجليزية يوميًا.',
                'content' => 'Build a daily routine, practice speaking, and immerse yourself in English media.',
                'ar_content' => 'ابنِ روتينًا يوميًا، وتدرّب على التحدث، واغمر نفسك في وسائل الإعلام الإنجليزية.',
                'category_slug' => 'learning-tips',
                'audience_scope' => 'all',
                'featured_image' => 'blog_images/learning-tips.jpg',
            ],
            [
                'title' => 'Why Study in the UK?',
                'ar_title' => 'لماذا الدراسة في المملكة المتحدة؟',
                'slug' => 'why-study-in-the-uk',
                'summary' => 'World-class education and a vibrant student experience.',
                'ar_summary' => 'تعليم عالمي وتجربة طلابية غنية.',
                'content' => 'The UK offers globally ranked institutions, diverse culture, and strong career pathways.',
                'ar_content' => 'توفر المملكة المتحدة مؤسسات عالمية، وثقافة متنوعة، ومسارات مهنية قوية.',
                'category_slug' => 'study-abroad',
                'audience_scope' => 'all',
                'featured_image' => 'blog_images/study-uk.jpg',
            ],
            [
                'title' => 'Mastering English Grammar: A Beginner’s Guide',
                'ar_title' => 'إتقان قواعد الإنجليزية: دليل للمبتدئين',
                'slug' => 'mastering-english-grammar-beginners-guide',
                'summary' => 'Understand the essentials without overwhelm.',
                'ar_summary' => 'افهم الأساسيات بدون تعقيد.',
                'content' => 'Start with tenses, sentence structure, and common grammar patterns.',
                'ar_content' => 'ابدأ بالأزمنة وبناء الجملة وأنماط القواعد الشائعة.',
                'category_slug' => 'grammar',
                'audience_scope' => 'all',
                'featured_image' => 'blog_images/grammar-guide.jpg',
            ],
            [
                'title' => 'IELTS Preparation Checklist',
                'ar_title' => 'قائمة التحضير لاختبار IELTS',
                'slug' => 'ielts-preparation-checklist',
                'summary' => 'A focused plan to prepare efficiently.',
                'ar_summary' => 'خطة مركّزة للتحضير بكفاءة.',
                'content' => 'Review band descriptors, take mock tests, and improve writing structure.',
                'ar_content' => 'راجع معايير الدرجات، وأدِّ اختبارات تجريبية، وحسّن بنية الكتابة.',
                'category_slug' => 'exams',
                'audience_scope' => 'all',
                'featured_image' => 'blog_images/ielts-checklist.jpg',
            ],
            [
                'title' => 'How English Skills Boost Your Career',
                'ar_title' => 'كيف تعزز مهارات الإنجليزية مسارك المهني',
                'slug' => 'how-english-skills-boost-your-career',
                'summary' => 'Communicate better and access more opportunities.',
                'ar_summary' => 'تواصل أفضل واغتنم فرصًا أكثر.',
                'content' => 'English proficiency improves hiring prospects and global mobility.',
                'ar_content' => 'إتقان الإنجليزية يرفع فرص التوظيف والتنقل عالميًا.',
                'category_slug' => 'careers',
                'audience_scope' => 'all',
                'featured_image' => 'blog_images/career-growth.jpg',
            ],
        ];

        foreach ($rows as $row) {
            $categoryId = $categoryIds[$row['category_slug']] ?? null;
            if (! $categoryId) {
                continue;
            }

            DB::table('blogs')->updateOrInsert(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'ar_title' => $row['ar_title'],
                    'summary' => $row['summary'],
                    'ar_summary' => $row['ar_summary'],
                    'content' => $row['content'],
                    'ar_content' => $row['ar_content'],
                    'category_id' => $categoryId,
                    'audience_scope' => $row['audience_scope'],
                    'featured_image' => $row['featured_image'],
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
