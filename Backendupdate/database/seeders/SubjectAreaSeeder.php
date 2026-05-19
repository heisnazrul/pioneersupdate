<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectAreaSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Business & Management', 'ar_name' => 'إدارة الأعمال'],
            ['name' => 'Economics & Finance', 'ar_name' => 'الاقتصاد والتمويل'],
            ['name' => 'Accounting & Banking', 'ar_name' => 'المحاسبة والمصارف'],
            ['name' => 'Sustainability & Environment', 'ar_name' => 'الاستدامة والبيئة'],
            ['name' => 'Education', 'ar_name' => 'التربية والتعليم'],
            ['name' => 'Law', 'ar_name' => 'القانون'],
            ['name' => 'Politics & International Relations', 'ar_name' => 'العلوم السياسية والعلاقات الدولية'],
            ['name' => 'Journalism & Media', 'ar_name' => 'الصحافة والإعلام'],
            ['name' => 'Sociology & Social Sciences', 'ar_name' => 'علم الاجتماع والعلوم الاجتماعية'],
            ['name' => 'Design & Applied Arts', 'ar_name' => 'التصميم والفنون التطبيقية'],
            ['name' => 'Architecture & Planning', 'ar_name' => 'العمارة والتخطيط'],
            ['name' => 'Arts & Humanities', 'ar_name' => 'الآداب والعلوم الإنسانية'],
            ['name' => 'English & Linguistics', 'ar_name' => 'اللغة الإنجليزية واللغويات'],
            ['name' => 'History & Philosophy', 'ar_name' => 'التاريخ والفلسفة'],
            ['name' => 'Engineering (General)', 'ar_name' => 'الهندسة العامة'],
            ['name' => 'Civil & Structural Engineering', 'ar_name' => 'الهندسة المدنية والإنشائية'],
            ['name' => 'Mechanical & Manufacturing Engineering', 'ar_name' => 'الهندسة الميكانيكية والتصنيع'],
            ['name' => 'Electrical & Electronic Engineering', 'ar_name' => 'الهندسة الكهربائية والإلكترونية'],
            ['name' => 'Chemical & Process Engineering', 'ar_name' => 'الهندسة الكيميائية وهندسة العمليات'],
            ['name' => 'Energy & Environmental Engineering', 'ar_name' => 'هندسة الطاقة والبيئة'],
            ['name' => 'Computer & Software Engineering', 'ar_name' => 'هندسة الحاسوب والبرمجيات'],
            ['name' => 'Aerospace & Aviation Engineering', 'ar_name' => 'هندسة الطيران والفضاء'],
            ['name' => 'Robotics & Mechatronics', 'ar_name' => 'الروبوتات والميكاترونكس'],
            ['name' => 'Materials & Industrial Engineering', 'ar_name' => 'هندسة المواد والصناعة'],
            ['name' => 'Biotechnology & Biomedical Engineering', 'ar_name' => 'الهندسة الطبية الحيوية والتقنيات الحيوية'],
            ['name' => 'Agriculture & Forestry', 'ar_name' => 'الزراعة والغابات'],
            ['name' => 'Environmental Sciences', 'ar_name' => 'العلوم البيئية'],
            ['name' => 'Marine & Nautical Sciences', 'ar_name' => 'العلوم البحرية والملاحة'],
            ['name' => 'Veterinary Sciences', 'ar_name' => 'العلوم البيطرية'],
            ['name' => 'Pharmacy & Pharmacology', 'ar_name' => 'الصيدلة وعلم الأدوية'],
            ['name' => 'Natural Sciences', 'ar_name' => 'العلوم الطبيعية'],
            ['name' => 'Biology & Life Sciences', 'ar_name' => 'علوم الحياة والأحياء'],
            ['name' => 'Chemistry', 'ar_name' => 'الكيمياء'],
            ['name' => 'Physics & Astronomy', 'ar_name' => 'الفيزياء والفلك'],
            ['name' => 'Mathematics & Statistics', 'ar_name' => 'الرياضيات والإحصاء'],
            ['name' => 'Earth & Geological Sciences', 'ar_name' => 'علوم الأرض والجيولوجيا'],
            ['name' => 'Computer Science & IT', 'ar_name' => 'علوم الحاسوب وتقنية المعلومات'],
            ['name' => 'Data Science & AI', 'ar_name' => 'علوم البيانات والذكاء الاصطناعي'],
            ['name' => 'Cyber Security & Information Systems', 'ar_name' => 'الأمن السيبراني ونظم المعلومات'],
            ['name' => 'Sports Science & Physical Education', 'ar_name' => 'علوم الرياضة والتربية البدنية'],
            ['name' => 'Hospitality, Tourism & Leisure', 'ar_name' => 'الضيافة والسياحة'],
            ['name' => 'Health Sciences', 'ar_name' => 'العلوم الصحية'],
            ['name' => 'Nursing & Healthcare', 'ar_name' => 'التمريض والرعاية الصحية'],
            ['name' => 'Criminology & Forensic Studies', 'ar_name' => 'علم الجريمة والعلوم الجنائية'],
            ['name' => 'Genetics & Microbiology', 'ar_name' => 'الوراثة والأحياء الدقيقة'],
            ['name' => 'Nutrition & Food Sciences', 'ar_name' => 'علوم التغذية والأغذية'],
            ['name' => 'Urban & Regional Studies', 'ar_name' => 'الدراسات الحضرية والإقليمية'],
            ['name' => 'Transportation & Logistics', 'ar_name' => 'النقل واللوجستيات'],
            ['name' => 'Religion & Theology', 'ar_name' => 'الدين واللاهوت'],
            ['name' => 'Archaeology & Heritage Studies', 'ar_name' => 'الآثار وإدارة التراث'],
            ['name' => 'Geography', 'ar_name' => 'الجغرافيا'],
            ['name' => 'Museum & Cultural Studies', 'ar_name' => 'دراسات المتاحف والثقافة'],
            ['name' => 'Occupational Health & Safety', 'ar_name' => 'الصحة والسلامة المهنية'],
            ['name' => 'Science, Technology & Society', 'ar_name' => 'العلوم والتكنولوجيا والمجتمع'],
        ];

        foreach ($rows as $index => $row) {
            $key = Str::slug($row['name']);

            DB::table('subject_areas')->updateOrInsert(
                ['key' => $key],
                [
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'slug' => $key,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
