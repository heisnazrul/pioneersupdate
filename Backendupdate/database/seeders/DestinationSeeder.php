<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $countryIds = DB::table('countries')->pluck('id', 'slug');
        $now = now();

        $rows = [
            [
                'slug' => 'united-kingdom',
                'name' => 'United Kingdom',
                'ar_name' => 'المملكة المتحدة',
                'region' => 'Europe',
                'ar_region' => 'أوروبا',
                'description' => 'Study in United Kingdom and experience world-class education.',
                'ar_description' => 'ادرس في المملكة المتحدة وتمتع بتعليم عالمي المستوى.',
                'image_url' => 'https://placehold.co/800x600?text=United+Kingdom',
                'short_pitch' => 'World-renowned degrees & post-study work rights.',
                'ar_short_pitch' => 'شهادات عالمية وفرص عمل بعد التخرج.',
                'tuition_range' => 'GBP 15,000 - 35,000 / year',
                'ar_tuition_range' => 'GBP 15,000 - 35,000 / سنة',
                'visa_timeline' => '4-6 weeks',
                'ar_visa_timeline' => '4-6 أسابيع',
                'work_rights' => 'Up to 3 years post-study',
                'ar_work_rights' => 'حتى 3 سنوات بعد التخرج',
                'scholarships_summary' => 'Government & University mandated',
                'ar_scholarships_summary' => 'منح حكومية وجامعية',
                'entry_req_gpa' => 'High school completion with competitive grades depending on program.',
                'ar_entry_req_gpa' => 'إكمال الثانوية العامة بدرجات تنافسية حسب البرنامج.',
                'entry_req_language' => 'IELTS or equivalent English test is commonly required.',
                'ar_entry_req_language' => 'غالباً ما يُطلب اختبار IELTS أو ما يعادله في اللغة الإنجليزية.',
            ],
            [
                'slug' => 'united-states',
                'name' => 'United States',
                'ar_name' => 'الولايات المتحدة',
                'region' => 'North America',
                'ar_region' => 'أمريكا الشمالية',
                'description' => 'Study in United States and access globally recognized education.',
                'ar_description' => 'ادرس في الولايات المتحدة واستفد من تعليم معترف به عالمياً.',
                'image_url' => 'https://placehold.co/800x600?text=United+States',
                'short_pitch' => 'Top-ranked institutions and wide program choice.',
                'ar_short_pitch' => 'مؤسسات عالية التصنيف وخيارات برامج واسعة.',
                'tuition_range' => 'USD 15,000 - 35,000 / year',
                'ar_tuition_range' => 'USD 15,000 - 35,000 / سنة',
                'visa_timeline' => '4-8 weeks',
                'ar_visa_timeline' => '4-8 أسابيع',
                'work_rights' => 'Limited on-study work, OPT after graduation',
                'ar_work_rights' => 'عمل محدود أثناء الدراسة وبرنامج OPT بعد التخرج',
                'scholarships_summary' => 'University and merit scholarships',
                'ar_scholarships_summary' => 'منح جامعية ومنح تفوق',
                'entry_req_gpa' => 'Academic transcripts and competitive GPA vary by institution.',
                'ar_entry_req_gpa' => 'تختلف المتطلبات الأكاديمية والمعدل المطلوب حسب المؤسسة.',
                'entry_req_language' => 'TOEFL, IELTS, or Duolingo may be accepted by many institutions.',
                'ar_entry_req_language' => 'قد تقبل العديد من المؤسسات TOEFL أو IELTS أو Duolingo.',
            ],
            [
                'slug' => 'canada',
                'name' => 'Canada',
                'ar_name' => 'كندا',
                'region' => 'North America',
                'ar_region' => 'أمريكا الشمالية',
                'description' => 'Study in Canada with strong post-study options and student-friendly cities.',
                'ar_description' => 'ادرس في كندا مع خيارات قوية بعد التخرج ومدن مناسبة للطلاب.',
                'image_url' => 'https://placehold.co/800x600?text=Canada',
                'short_pitch' => 'Quality education and welcoming multicultural communities.',
                'ar_short_pitch' => 'تعليم عالي الجودة ومجتمعات متعددة الثقافات ومرحبة.',
                'tuition_range' => 'CAD 15,000 - 35,000 / year',
                'ar_tuition_range' => 'CAD 15,000 - 35,000 / سنة',
                'visa_timeline' => '4-6 weeks',
                'ar_visa_timeline' => '4-6 أسابيع',
                'work_rights' => 'Strong post-study pathways',
                'ar_work_rights' => 'مسارات قوية بعد الدراسة',
                'scholarships_summary' => 'Public and institutional scholarships',
                'ar_scholarships_summary' => 'منح حكومية ومؤسسية',
                'entry_req_gpa' => 'A solid academic background is required for most programs.',
                'ar_entry_req_gpa' => 'يلزم سجل أكاديمي قوي لمعظم البرامج.',
                'entry_req_language' => 'English proficiency such as IELTS or TOEFL is usually required.',
                'ar_entry_req_language' => 'عادةً ما يُطلب إثبات إجادة الإنجليزية مثل IELTS أو TOEFL.',
            ],
            [
                'slug' => 'australia',
                'name' => 'Australia',
                'ar_name' => 'أستراليا',
                'region' => 'Oceania',
                'ar_region' => 'أوقيانوسيا',
                'description' => 'Study in Australia with excellent universities and a vibrant lifestyle.',
                'ar_description' => 'ادرس في أستراليا مع جامعات متميزة ونمط حياة حيوي.',
                'image_url' => 'https://placehold.co/800x600?text=Australia',
                'short_pitch' => 'Excellent quality of life and strong career outcomes.',
                'ar_short_pitch' => 'جودة حياة ممتازة وفرص مهنية قوية.',
                'tuition_range' => 'AUD 15,000 - 35,000 / year',
                'ar_tuition_range' => 'AUD 15,000 - 35,000 / سنة',
                'visa_timeline' => '4-6 weeks',
                'ar_visa_timeline' => '4-6 أسابيع',
                'work_rights' => 'Post-study work options available',
                'ar_work_rights' => 'خيارات عمل متاحة بعد التخرج',
                'scholarships_summary' => 'University and government support',
                'ar_scholarships_summary' => 'دعم جامعي وحكومي',
                'entry_req_gpa' => 'Program entry depends on previous academic performance and documents.',
                'ar_entry_req_gpa' => 'يعتمد القبول على الأداء الأكاديمي السابق والوثائق المطلوبة.',
                'entry_req_language' => 'IELTS, TOEFL, or PTE are common English requirements.',
                'ar_entry_req_language' => 'تعد IELTS أو TOEFL أو PTE من المتطلبات الشائعة للغة الإنجليزية.',
            ],
            [
                'slug' => 'germany',
                'name' => 'Germany',
                'ar_name' => 'ألمانيا',
                'region' => 'Europe',
                'ar_region' => 'أوروبا',
                'description' => 'Study in Germany with strong academic reputation and research-led institutions.',
                'ar_description' => 'ادرس في ألمانيا مع سمعة أكاديمية قوية ومؤسسات قائمة على البحث.',
                'image_url' => 'https://placehold.co/800x600?text=Germany',
                'short_pitch' => 'Research excellence and strong engineering ecosystem.',
                'ar_short_pitch' => 'تميز بحثي وبيئة هندسية قوية.',
                'tuition_range' => 'EUR 5,000 - 20,000 / year',
                'ar_tuition_range' => 'EUR 5,000 - 20,000 / سنة',
                'visa_timeline' => '6-10 weeks',
                'ar_visa_timeline' => '6-10 أسابيع',
                'work_rights' => 'Student work and post-study routes',
                'ar_work_rights' => 'العمل أثناء الدراسة ومسارات ما بعد التخرج',
                'scholarships_summary' => 'DAAD and university scholarships',
                'ar_scholarships_summary' => 'منح DAAD ومنح جامعية',
                'entry_req_gpa' => 'Relevant academic preparation and recognized qualifications are required.',
                'ar_entry_req_gpa' => 'يُطلب إعداد أكاديمي مناسب ومؤهلات معترف بها.',
                'entry_req_language' => 'Programs may require English or German language proof depending on track.',
                'ar_entry_req_language' => 'قد تتطلب البرامج إثبات اللغة الإنجليزية أو الألمانية حسب المسار.',
            ],
            [
                'slug' => 'ireland',
                'name' => 'Ireland',
                'ar_name' => 'أيرلندا',
                'region' => 'Europe',
                'ar_region' => 'أوروبا',
                'description' => 'Study in Ireland in an English-speaking environment with modern universities.',
                'ar_description' => 'ادرس في أيرلندا ضمن بيئة ناطقة بالإنجليزية وجامعات حديثة.',
                'image_url' => 'https://placehold.co/800x600?text=Ireland',
                'short_pitch' => 'English-speaking study destination with strong tech links.',
                'ar_short_pitch' => 'وجهة دراسية ناطقة بالإنجليزية مع ارتباط قوي بقطاع التقنية.',
                'tuition_range' => 'EUR 10,000 - 28,000 / year',
                'ar_tuition_range' => 'EUR 10,000 - 28,000 / سنة',
                'visa_timeline' => '4-8 weeks',
                'ar_visa_timeline' => '4-8 أسابيع',
                'work_rights' => 'Post-study opportunities available',
                'ar_work_rights' => 'فرص متاحة بعد التخرج',
                'scholarships_summary' => 'Government and university scholarships',
                'ar_scholarships_summary' => 'منح حكومية وجامعية',
                'entry_req_gpa' => 'Admission decisions depend on previous academic performance.',
                'ar_entry_req_gpa' => 'تعتمد قرارات القبول على الأداء الأكاديمي السابق.',
                'entry_req_language' => 'English proficiency is typically required for international students.',
                'ar_entry_req_language' => 'عادةً ما يُطلب إثبات إجادة الإنجليزية للطلاب الدوليين.',
            ],
            [
                'slug' => 'netherlands',
                'name' => 'Netherlands',
                'ar_name' => 'هولندا',
                'region' => 'Europe',
                'ar_region' => 'أوروبا',
                'description' => 'Study in the Netherlands with internationally focused programs.',
                'ar_description' => 'ادرس في هولندا مع برامج ذات توجه دولي.',
                'image_url' => 'https://placehold.co/800x600?text=Netherlands',
                'short_pitch' => 'Innovative education and international classrooms.',
                'ar_short_pitch' => 'تعليم مبتكر وفصول دراسية دولية.',
                'tuition_range' => 'EUR 8,000 - 24,000 / year',
                'ar_tuition_range' => 'EUR 8,000 - 24,000 / سنة',
                'visa_timeline' => '4-6 weeks',
                'ar_visa_timeline' => '4-6 أسابيع',
                'work_rights' => 'Post-study orientation year available',
                'ar_work_rights' => 'سنة توجيهية متاحة بعد التخرج',
                'scholarships_summary' => 'Holland Scholarship and university funding',
                'ar_scholarships_summary' => 'منحة هولندا وتمويل جامعي',
                'entry_req_gpa' => 'Recognized school or university qualifications are needed.',
                'ar_entry_req_gpa' => 'يلزم تقديم مؤهلات مدرسية أو جامعية معترف بها.',
                'entry_req_language' => 'English proficiency evidence is required for English-taught programs.',
                'ar_entry_req_language' => 'يُطلب إثبات إجادة الإنجليزية للبرامج التي تدرّس بالإنجليزية.',
            ],
            [
                'slug' => 'malaysia',
                'name' => 'Malaysia',
                'ar_name' => 'ماليزيا',
                'region' => 'Asia',
                'ar_region' => 'آسيا',
                'description' => 'Study in Malaysia with affordable tuition and modern campuses.',
                'ar_description' => 'ادرس في ماليزيا برسوم مناسبة وحرم جامعي حديث.',
                'image_url' => 'https://placehold.co/800x600?text=Malaysia',
                'short_pitch' => 'Affordable international education in Asia.',
                'ar_short_pitch' => 'تعليم دولي ميسور التكلفة في آسيا.',
                'tuition_range' => 'MYR 12,000 - 35,000 / year',
                'ar_tuition_range' => 'MYR 12,000 - 35,000 / سنة',
                'visa_timeline' => '3-6 weeks',
                'ar_visa_timeline' => '3-6 أسابيع',
                'work_rights' => 'Limited student work availability',
                'ar_work_rights' => 'توفر محدود للعمل الطلابي',
                'scholarships_summary' => 'Institutional and partner scholarships',
                'ar_scholarships_summary' => 'منح مؤسسية ومنح الشركاء',
                'entry_req_gpa' => 'Most institutions require valid academic transcripts and supporting documents.',
                'ar_entry_req_gpa' => 'تطلب معظم المؤسسات سجلات أكاديمية ووثائق داعمة سارية.',
                'entry_req_language' => 'English proficiency or equivalent pathway evidence may be required.',
                'ar_entry_req_language' => 'قد يُطلب إثبات إجادة الإنجليزية أو ما يعادلها.',
            ],
        ];

        $features = [
            ['feature' => 'High Quality Education', 'ar_feature' => 'تعليم عالي الجودة'],
            ['feature' => 'Multicultural Society', 'ar_feature' => 'مجتمع متعدد الثقافات'],
            ['feature' => 'Global Recognition', 'ar_feature' => 'اعتراف عالمي'],
        ];

        $stats = [
            ['label' => 'Universities', 'ar_label' => 'الجامعات', 'value' => '20+', 'ar_value' => '20+'],
            ['label' => 'Intl. Students', 'ar_label' => 'الطلاب الدوليون', 'value' => '500k+', 'ar_value' => '500k+'],
            ['label' => 'Post-Study Work', 'ar_label' => 'العمل بعد التخرج', 'value' => 'Yes', 'ar_value' => 'نعم'],
        ];

        $intakes = [
            ['month' => 'Jan', 'ar_month' => 'يناير', 'event' => 'Winter Intake', 'ar_event' => 'القبول الشتوي'],
            ['month' => 'May', 'ar_month' => 'مايو', 'event' => 'Spring Intake', 'ar_event' => 'القبول الربيعي'],
            ['month' => 'Sep', 'ar_month' => 'سبتمبر', 'event' => 'Fall Intake (Main)', 'ar_event' => 'قبول الخريف (الرئيسي)'],
        ];

        $requirements = [
            ['requirement' => 'Academic Transcripts', 'ar_requirement' => 'السجلات الأكاديمية'],
            ['requirement' => 'English Proficiency (IELTS/TOEFL)', 'ar_requirement' => 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)'],
            ['requirement' => 'Statement of Purpose', 'ar_requirement' => 'بيان الغرض'],
            ['requirement' => 'Financial Proof', 'ar_requirement' => 'إثبات القدرة المالية'],
        ];

        $faqs = [
            ['question' => 'Can I work while studying?', 'ar_question' => 'هل يمكنني العمل أثناء الدراسة؟', 'answer' => 'Yes, usually 20 hours per week during term time.', 'ar_answer' => 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.'],
            ['question' => 'Are scholarships available?', 'ar_question' => 'هل تتوفر منح دراسية؟', 'answer' => 'Yes, many universities offer merit-based scholarships.', 'ar_answer' => 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.'],
        ];

        foreach ($rows as $row) {
            DB::table('destinations')->updateOrInsert(
                ['slug' => $row['slug']],
                [
                    'country_id' => $countryIds[$row['slug']] ?? null,
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'region' => $row['region'],
                    'ar_region' => $row['ar_region'],
                    'description' => $row['description'],
                    'ar_description' => $row['ar_description'],
                    'image_url' => $row['image_url'],
                    'short_pitch' => $row['short_pitch'],
                    'ar_short_pitch' => $row['ar_short_pitch'],
                    'tuition_range' => $row['tuition_range'],
                    'ar_tuition_range' => $row['ar_tuition_range'],
                    'visa_timeline' => $row['visa_timeline'],
                    'ar_visa_timeline' => $row['ar_visa_timeline'],
                    'work_rights' => $row['work_rights'],
                    'ar_work_rights' => $row['ar_work_rights'],
                    'scholarships_summary' => $row['scholarships_summary'],
                    'ar_scholarships_summary' => $row['ar_scholarships_summary'],
                    'entry_req_gpa' => $row['entry_req_gpa'],
                    'ar_entry_req_gpa' => $row['ar_entry_req_gpa'],
                    'entry_req_language' => $row['entry_req_language'],
                    'ar_entry_req_language' => $row['ar_entry_req_language'],
                    'university_count' => $this->resolveUniversityCount($countryIds[$row['slug']] ?? null),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );

            $destinationId = DB::table('destinations')->where('slug', $row['slug'])->value('id');
            if (! $destinationId) {
                continue;
            }

            DB::table('destination_features')->where('destination_id', $destinationId)->delete();
            DB::table('destination_stats')->where('destination_id', $destinationId)->delete();
            DB::table('destination_intakes')->where('destination_id', $destinationId)->delete();
            DB::table('destination_faqs')->where('destination_id', $destinationId)->delete();
            DB::table('destination_requirements')->where('destination_id', $destinationId)->delete();
            DB::table('destination_guides')->where('destination_id', $destinationId)->delete();

            foreach ($features as $feature) {
                DB::table('destination_features')->insert(array_merge($feature, [
                    'destination_id' => $destinationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            foreach ($stats as $stat) {
                DB::table('destination_stats')->insert(array_merge($stat, [
                    'destination_id' => $destinationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            foreach ($intakes as $intake) {
                DB::table('destination_intakes')->insert(array_merge($intake, [
                    'destination_id' => $destinationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            foreach ($faqs as $faq) {
                DB::table('destination_faqs')->insert(array_merge($faq, [
                    'destination_id' => $destinationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            foreach ($requirements as $requirement) {
                DB::table('destination_requirements')->insert(array_merge($requirement, [
                    'destination_id' => $destinationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            DB::table('destination_guides')->insert([
                'destination_id' => $destinationId,
                'title' => $row['name'] . ' Student Guide',
                'ar_title' => 'دليل الدراسة في ' . $row['ar_name'],
                'file_path' => 'destination_guides/sample-destination-guide.pdf',
                'year' => (int) date('Y'),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function resolveUniversityCount(?int $countryId): int
    {
        if (! $countryId) {
            return 0;
        }

        return (int) DB::table('universities')
            ->where('country_id', $countryId)
            ->where('is_active', true)
            ->count();
    }
}
