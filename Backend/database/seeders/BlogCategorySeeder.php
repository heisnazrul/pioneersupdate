<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'name' => 'Learning Tips',
                'ar_name' => 'نصائح التعلم',
                'slug' => 'learning-tips',
                'description' => 'Short, practical tips to learn English faster.',
                'ar_description' => 'نصائح عملية ومختصرة لتعلم الإنجليزية بشكل أسرع.',
                'color' => '#1F63AE',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Study Abroad',
                'ar_name' => 'الدراسة في الخارج',
                'slug' => 'study-abroad',
                'description' => 'Guides and checklists for studying abroad.',
                'ar_description' => 'أدلة وقوائم تحقق للدراسة في الخارج.',
                'color' => '#0E7C86',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Grammar',
                'ar_name' => 'القواعد',
                'slug' => 'grammar',
                'description' => 'Clear explanations of English grammar rules.',
                'ar_description' => 'شروحات واضحة لقواعد اللغة الإنجليزية.',
                'color' => '#6A5ACD',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'Exams',
                'ar_name' => 'الاختبارات',
                'slug' => 'exams',
                'description' => 'IELTS and exam preparation guidance.',
                'ar_description' => 'إرشادات التحضير لاختبار IELTS والاختبارات الأخرى.',
                'color' => '#E67E22',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'name' => 'Careers',
                'ar_name' => 'المسار المهني',
                'slug' => 'careers',
                'description' => 'How English skills boost career growth.',
                'ar_description' => 'كيف تساعد مهارات الإنجليزية في تطوير المسار المهني.',
                'color' => '#2E7D32',
                'display_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('blog_categories')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'slug' => $row['slug'],
                    'description' => $row['description'],
                    'ar_description' => $row['ar_description'],
                    'color' => $row['color'],
                    'display_order' => $row['display_order'],
                    'is_active' => $row['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

