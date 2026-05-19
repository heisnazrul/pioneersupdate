<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversityCourseCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $subjectAreaIds = DB::table('subject_areas')->pluck('id', 'name');

        $courses = [
            ['name' => 'Business & Management', 'ar_name' => 'الأعمال والإدارة', 'subject' => 'Business & Management'],
            ['name' => 'Accounting', 'ar_name' => 'المحاسبة', 'subject' => 'Accounting & Banking'],
            ['name' => 'Finance', 'ar_name' => 'المالية', 'subject' => 'Economics & Finance'],
            ['name' => 'Economics', 'ar_name' => 'الاقتصاد', 'subject' => 'Economics & Finance'],
            ['name' => 'Marketing', 'ar_name' => 'التسويق', 'subject' => 'Business & Management'],
            ['name' => 'Education', 'ar_name' => 'التعليم', 'subject' => 'Education'],
            ['name' => 'Law', 'ar_name' => 'القانون', 'subject' => 'Law'],
            ['name' => 'Journalism', 'ar_name' => 'الصحافة', 'subject' => 'Journalism & Media'],
            ['name' => 'Architecture', 'ar_name' => 'العماره', 'subject' => 'Architecture & Planning'],
            ['name' => 'Engineering', 'ar_name' => 'الهندسة', 'subject' => 'Engineering (General)'],
            ['name' => 'Civil & Structural Engineering', 'ar_name' => 'الهندسة المدنية والإنشائية', 'subject' => 'Civil & Structural Engineering'],
            ['name' => 'Mechanical Engineering', 'ar_name' => 'الهندسة الميكانيكية', 'subject' => 'Mechanical & Manufacturing Engineering'],
            ['name' => 'Electrical Engineering', 'ar_name' => 'الهندسة الكهربائية', 'subject' => 'Electrical & Electronic Engineering'],
            ['name' => 'Computer & Software Engineering', 'ar_name' => 'هندسة الحاسوب والبرمجيات', 'subject' => 'Computer & Software Engineering'],
            ['name' => 'Environmental Engineering', 'ar_name' => 'الهندسة البيئية', 'subject' => 'Energy & Environmental Engineering'],
            ['name' => 'Biotechnology', 'ar_name' => 'التكنولوجيا الحيوية', 'subject' => 'Biotechnology & Biomedical Engineering'],
            ['name' => 'Pharmacy & Pharmacology', 'ar_name' => 'الصيدلة وعلم الأدوية', 'subject' => 'Pharmacy & Pharmacology'],
            ['name' => 'Biology', 'ar_name' => 'الأحياء', 'subject' => 'Biology & Life Sciences'],
            ['name' => 'Chemistry', 'ar_name' => 'الكيمياء', 'subject' => 'Chemistry'],
            ['name' => 'Physics', 'ar_name' => 'الفيزياء', 'subject' => 'Physics & Astronomy'],
            ['name' => 'Mathematics & Statistics', 'ar_name' => 'الرياضيات والإحصاء', 'subject' => 'Mathematics & Statistics'],
            ['name' => 'Computer Science', 'ar_name' => 'علوم الحاسب', 'subject' => 'Computer Science & IT'],
            ['name' => 'Data Science', 'ar_name' => 'علوم البيانات', 'subject' => 'Data Science & AI'],
            ['name' => 'Artificial Intelligence & Machine Learning', 'ar_name' => 'الذكاء الاصطناعي وتعلم الآلة', 'subject' => 'Data Science & AI'],
            ['name' => 'Information Security', 'ar_name' => 'أمن المعلومات', 'subject' => 'Cyber Security & Information Systems'],
            ['name' => 'Health Sciences', 'ar_name' => 'العلوم الصحية', 'subject' => 'Health Sciences'],
            ['name' => 'Nursing', 'ar_name' => 'التمريض', 'subject' => 'Nursing & Healthcare'],
            ['name' => 'Nutrition Sciences', 'ar_name' => 'علوم التغذية', 'subject' => 'Nutrition & Food Sciences'],
            ['name' => 'History', 'ar_name' => 'التاريخ', 'subject' => 'History & Philosophy'],
            ['name' => 'English Language & Literature', 'ar_name' => 'اللغة الإنجليزية وآدابها', 'subject' => 'English & Linguistics'],
        ];

        foreach ($courses as $index => $course) {
            $slug = Str::slug($course['name']);
            DB::table('university_course_catalogs')->updateOrInsert(
                ['slug' => $slug],
                [
                    'subject_area_id' => $subjectAreaIds[$course['subject']] ?? null,
                    'name' => $course['name'],
                    'ar_name' => $course['ar_name'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
