<?php

namespace Database\Seeders;

use App\Models\SubjectArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SubjectAreaSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('subject_areas')) {
            return;
        }

        $rows = [
            ['id' => 1, 'name' => 'Business & Management', 'ar_name' => 'إدارة الأعمال'],
            ['id' => 2, 'name' => 'Economics & Finance', 'ar_name' => 'الاقتصاد والتمويل'],
            ['id' => 3, 'name' => 'Accounting & Banking', 'ar_name' => 'المحاسبة والمصارف'],
            ['id' => 4, 'name' => 'Sustainability & Environment', 'ar_name' => 'الاستدامة والبيئة'],
            ['id' => 5, 'name' => 'Education', 'ar_name' => 'التربية والتعليم'],
            ['id' => 6, 'name' => 'Law', 'ar_name' => 'القانون'],
            ['id' => 7, 'name' => 'Politics & International Relations', 'ar_name' => 'العلوم السياسية والعلاقات الدولية'],
            ['id' => 8, 'name' => 'Journalism & Media', 'ar_name' => 'الصحافة والإعلام'],
            ['id' => 9, 'name' => 'Sociology & Social Sciences', 'ar_name' => 'علم الاجتماع والعلوم الاجتماعية'],
            ['id' => 10, 'name' => 'Design & Applied Arts', 'ar_name' => 'التصميم والفنون التطبيقية'],
            ['id' => 11, 'name' => 'Architecture & Planning', 'ar_name' => 'العمارة والتخطيط'],
            ['id' => 12, 'name' => 'Arts & Humanities', 'ar_name' => 'الآداب والعلوم الإنسانية'],
            ['id' => 13, 'name' => 'English & Linguistics', 'ar_name' => 'اللغة الإنجليزية واللغويات'],
            ['id' => 14, 'name' => 'History & Philosophy', 'ar_name' => 'التاريخ والفلسفة'],
            ['id' => 15, 'name' => 'Engineering (General)', 'ar_name' => 'الهندسة العامة'],
            ['id' => 16, 'name' => 'Civil & Structural Engineering', 'ar_name' => 'الهندسة المدنية والإنشائية'],
            ['id' => 17, 'name' => 'Mechanical & Manufacturing Engineering', 'ar_name' => 'الهندسة الميكانيكية والتصنيع'],
            ['id' => 18, 'name' => 'Electrical & Electronic Engineering', 'ar_name' => 'الهندسة الكهربائية والإلكترونية'],
            ['id' => 19, 'name' => 'Chemical & Process Engineering', 'ar_name' => 'الهندسة الكيميائية وهندسة العمليات'],
            ['id' => 20, 'name' => 'Energy & Environmental Engineering', 'ar_name' => 'هندسة الطاقة والبيئة'],
            ['id' => 21, 'name' => 'Computer & Software Engineering', 'ar_name' => 'هندسة الحاسوب والبرمجيات'],
            ['id' => 22, 'name' => 'Aerospace & Aviation Engineering', 'ar_name' => 'هندسة الطيران والفضاء'],
            ['id' => 23, 'name' => 'Robotics & Mechatronics', 'ar_name' => 'الروبوتات والميكاترونكس'],
            ['id' => 24, 'name' => 'Materials & Industrial Engineering', 'ar_name' => 'هندسة المواد والصناعة'],
            ['id' => 25, 'name' => 'Biotechnology & Biomedical Engineering', 'ar_name' => 'الهندسة الطبية الحيوية والتقنيات الحيوية'],
            ['id' => 26, 'name' => 'Agriculture & Forestry', 'ar_name' => 'الزراعة والغابات'],
            ['id' => 27, 'name' => 'Environmental Sciences', 'ar_name' => 'العلوم البيئية'],
            ['id' => 28, 'name' => 'Marine & Nautical Sciences', 'ar_name' => 'العلوم البحرية والملاحة'],
            ['id' => 29, 'name' => 'Veterinary Sciences', 'ar_name' => 'العلوم البيطرية'],
            ['id' => 30, 'name' => 'Pharmacy & Pharmacology', 'ar_name' => 'الصيدلة وعلم الأدوية'],
            ['id' => 31, 'name' => 'Natural Sciences', 'ar_name' => 'العلوم الطبيعية'],
            ['id' => 32, 'name' => 'Biology & Life Sciences', 'ar_name' => 'علوم الحياة والأحياء'],
            ['id' => 33, 'name' => 'Chemistry', 'ar_name' => 'الكيمياء'],
            ['id' => 34, 'name' => 'Physics & Astronomy', 'ar_name' => 'الفيزياء والفلك'],
            ['id' => 35, 'name' => 'Mathematics & Statistics', 'ar_name' => 'الرياضيات والإحصاء'],
            ['id' => 36, 'name' => 'Earth & Geological Sciences', 'ar_name' => 'علوم الأرض والجيولوجيا'],
            ['id' => 37, 'name' => 'Computer Science & IT', 'ar_name' => 'علوم الحاسوب وتقنية المعلومات'],
            ['id' => 38, 'name' => 'Data Science & AI', 'ar_name' => 'علوم البيانات والذكاء الاصطناعي'],
            ['id' => 39, 'name' => 'Cyber Security & Information Systems', 'ar_name' => 'الأمن السيبراني ونظم المعلومات'],
            ['id' => 40, 'name' => 'Sports Science & Physical Education', 'ar_name' => 'علوم الرياضة والتربية البدنية'],
            ['id' => 41, 'name' => 'Hospitality, Tourism & Leisure', 'ar_name' => 'الضيافة والسياحة'],
            ['id' => 42, 'name' => 'Health Sciences', 'ar_name' => 'العلوم الصحية'],
            ['id' => 43, 'name' => 'Nursing & Healthcare', 'ar_name' => 'التمريض والرعاية الصحية'],
            ['id' => 44, 'name' => 'Criminology & Forensic Studies', 'ar_name' => 'علم الجريمة والعلوم الجنائية'],
            ['id' => 45, 'name' => 'Genetics & Microbiology', 'ar_name' => 'الوراثة والأحياء الدقيقة'],
            ['id' => 46, 'name' => 'Nutrition & Food Sciences', 'ar_name' => 'علوم التغذية والأغذية'],
            ['id' => 47, 'name' => 'Urban & Regional Studies', 'ar_name' => 'الدراسات الحضرية والإقليمية'],
            ['id' => 48, 'name' => 'Transportation & Logistics', 'ar_name' => 'النقل واللوجستيات'],
            ['id' => 49, 'name' => 'Religion & Theology', 'ar_name' => 'الدين واللاهوت'],
            ['id' => 50, 'name' => 'Archaeology & Heritage Studies', 'ar_name' => 'الآثار وإدارة التراث'],
            ['id' => 51, 'name' => 'Geography', 'ar_name' => 'الجغرافيا'],
            ['id' => 52, 'name' => 'Museum & Cultural Studies', 'ar_name' => 'دراسات المتاحف والثقافة'],
            ['id' => 53, 'name' => 'Occupational Health & Safety', 'ar_name' => 'الصحة والسلامة المهنية'],
            ['id' => 54, 'name' => 'Science, Technology & Society', 'ar_name' => 'العلوم والتكنولوجيا والمجتمع'],
        ];

        SubjectArea::unguard();
        foreach ($rows as $row) {
            $row['key'] = Str::slug($row['name']);
            $row['slug'] = Str::slug($row['name']);
            $row['sort_order'] = $row['id'];
            $row['is_active'] = true;
            SubjectArea::updateOrCreate(['key' => $row['key']], $row);
        }
        SubjectArea::reguard();
    }
}
