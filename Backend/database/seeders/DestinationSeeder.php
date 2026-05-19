<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Destination;
use App\Models\DestinationFeature;
use App\Models\DestinationStat;
use App\Models\DestinationIntake;
use App\Models\DestinationFaq;
use App\Models\DestinationRequirement;
use App\Models\DestinationDiscipline;
use App\Models\Country;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Schema::disableForeignKeyConstraints();
        Destination::truncate();
        DestinationFeature::truncate();
        DestinationStat::truncate();
        DestinationIntake::truncate();
        DestinationFaq::truncate();
        DestinationRequirement::truncate();
        DestinationDiscipline::truncate();
        Schema::enableForeignKeyConstraints();

        $json = '[
  {
    "id": "dest_GB",
    "slug": "united-kingdom",
    "name": "United Kingdom",
    "region": "Europe",
    "description": "Study in United Kingdom and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=United Kingdom",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "GBP 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_US",
    "slug": "united-states",
    "name": "United States",
    "region": "North America",
    "description": "Study in United States and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=United States",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "USD 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_CA",
    "slug": "canada",
    "name": "Canada",
    "region": "North America",
    "description": "Study in Canada and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Canada",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "CAD 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_AU",
    "slug": "australia",
    "name": "Australia",
    "region": "Oceania",
    "description": "Study in Australia and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Australia",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "AUD 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_DE",
    "slug": "germany",
    "name": "Germany",
    "region": "Europe",
    "description": "Study in Germany and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Germany",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "EUR 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_IE",
    "slug": "ireland",
    "name": "Ireland",
    "region": "Europe",
    "description": "Study in Ireland and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Ireland",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "EUR 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_NL",
    "slug": "netherlands",
    "name": "Netherlands",
    "region": "Europe",
    "description": "Study in Netherlands and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Netherlands",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "EUR 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  },
  {
    "id": "dest_MY",
    "slug": "malaysia",
    "name": "Malaysia",
    "region": "Asia",
    "description": "Study in Malaysia and experience world-class education. Known for its academic excellence and vibrant culture, it\'s a top choice for international students.",
    "imageUrl": "https://placehold.co/800x600?text=Malaysia",
    "shortPitch": "World-renowned degrees & post-study work rights.",
    "tuitionRange": "MYR 15,000 - 35,000 / year",
    "visaTimeline": "4-6 weeks",
    "workRights": "Up to 3 years post-study",
    "scholarships": "Government & University mandated",
    "features": [
      "High Quality Education",
      "Multicultural Society",
      "Global Recognition"
    ],
    "stats": [
      {
        "label": "Universities",
        "value": "20+"
      },
      {
        "label": "Intl. Students",
        "value": "500k+"
      },
      {
        "label": "Post-Study Work",
        "value": "Yes"
      }
    ],
    "popularDisciplines": [
      "Business",
      "Engineering",
      "Health",
      "IT"
    ],
    "intakeTimeline": [
      {
        "month": "Jan",
        "event": "Winter Intake"
      },
      {
        "month": "May",
        "event": "Spring Intake"
      },
      {
        "month": "Sep",
        "event": "Fall Intake (Main)"
      }
    ],
    "requirements": [
      "Academic Transcripts",
      "English Proficiency (IELTS/TOEFL)",
      "Statement of Purpose",
      "Financial Proof"
    ],
    "faqs": [
      {
        "question": "Can I work while studying?",
        "answer": "Yes, usually 20 hours per week during term time."
      },
      {
        "question": "Are scholarships available?",
        "answer": "Yes, many universities offer merit-based scholarships."
      }
    ]
  }
]';

        $destinations = json_decode($json, true);

        $arNames = [
            'united-kingdom' => 'المملكة المتحدة',
            'united-states' => 'الولايات المتحدة',
            'canada' => 'كندا',
            'australia' => 'أستراليا',
            'germany' => 'ألمانيا',
            'ireland' => 'أيرلندا',
            'netherlands' => 'هولندا',
            'malaysia' => 'ماليزيا',
        ];

        $arRegions = [
            'Europe' => 'أوروبا',
            'North America' => 'أمريكا الشمالية',
            'Oceania' => 'أوقيانوسيا',
            'Asia' => 'آسيا',
        ];

        $arFeatures = [
            'High Quality Education' => 'تعليم عالي الجودة',
            'Multicultural Society' => 'مجتمع متعدد الثقافات',
            'Global Recognition' => 'اعتراف عالمي',
        ];

        $arStatLabels = [
            'Universities' => 'الجامعات',
            'Intl. Students' => 'الطلاب الدوليون',
            'Post-Study Work' => 'العمل بعد التخرج',
        ];

        $arStatValues = [
            'Yes' => 'نعم',
        ];

        $arDisciplines = [
            'Business' => 'إدارة الأعمال',
            'Engineering' => 'الهندسة',
            'Health' => 'العلوم الصحية',
            'IT' => 'تقنية المعلومات',
        ];

        $arIntakeMonths = [
            'Jan' => 'يناير',
            'May' => 'مايو',
            'Sep' => 'سبتمبر',
        ];

        $arIntakeEvents = [
            'Winter Intake' => 'القبول الشتوي',
            'Spring Intake' => 'القبول الربيعي',
            'Fall Intake (Main)' => 'قبول الخريف (الرئيسي)',
        ];

        $arRequirements = [
            'Academic Transcripts' => 'السجلات الأكاديمية',
            'English Proficiency (IELTS/TOEFL)' => 'إثبات إجادة اللغة الإنجليزية (IELTS/TOEFL)',
            'Statement of Purpose' => 'بيان الغرض',
            'Financial Proof' => 'إثبات القدرة المالية',
        ];

        $arFaqQuestions = [
            'Can I work while studying?' => 'هل يمكنني العمل أثناء الدراسة؟',
            'Are scholarships available?' => 'هل تتوفر منح دراسية؟',
        ];

        $arFaqAnswers = [
            'Yes, usually 20 hours per week during term time.' => 'نعم، عادةً 20 ساعة أسبوعياً خلال الفصل الدراسي.',
            'Yes, many universities offer merit-based scholarships.' => 'نعم، تقدم العديد من الجامعات منحاً على أساس التفوق.',
        ];

        foreach ($destinations as $data) {
            $arName = $arNames[$data['slug']] ?? $data['name'];
            $arRegion = $arRegions[$data['region']] ?? $data['region'];
            $arDescription = sprintf(
                'ادرس في %s وتمتع بتعليم عالمي المستوى. تشتهر بتميزها الأكاديمي وثقافتها النابضة، وهي خيار مفضل للطلاب الدوليين.',
                $arName
            );
            $arShortPitch = 'شهادات عالمية وفرص عمل بعد التخرج.';
            $arTuitionRange = str_replace(' / year', ' / سنة', $data['tuitionRange']);
            $arVisaTimeline = str_replace('weeks', 'أسابيع', $data['visaTimeline']);
            $arWorkRights = 'حتى 3 سنوات بعد التخرج';
            $arScholarships = 'منح حكومية وجامعية';

            // Find Country if exists
            $country = Country::where('slug', $data['slug'])->first();

            $dest = Destination::create([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'ar_name' => $arName,
                'country_id' => $country ? $country->id : null,
                'region' => $data['region'],
                'ar_region' => $arRegion,
                'description' => $data['description'],
                'ar_description' => $arDescription,
                'image_url' => $data['imageUrl'],
                'short_pitch' => $data['shortPitch'],
                'ar_short_pitch' => $arShortPitch,
                'tuition_range' => $data['tuitionRange'],
                'ar_tuition_range' => $arTuitionRange,
                'visa_timeline' => $data['visaTimeline'],
                'ar_visa_timeline' => $arVisaTimeline,
                'work_rights' => $data['workRights'],
                'ar_work_rights' => $arWorkRights,
                'scholarships_summary' => $data['scholarships'],
                'ar_scholarships_summary' => $arScholarships,
                'university_count' => 0, // Placeholder
                'is_active' => true,
            ]);

            // Features
            foreach ($data['features'] as $feature) {
                DestinationFeature::create([
                    'destination_id' => $dest->id,
                    'feature' => $feature,
                    'ar_feature' => $arFeatures[$feature] ?? $feature,
                ]);
            }

            // Stats
            foreach ($data['stats'] as $stat) {
                DestinationStat::create([
                    'destination_id' => $dest->id,
                    'label' => $stat['label'],
                    'ar_label' => $arStatLabels[$stat['label']] ?? $stat['label'],
                    'value' => $stat['value'],
                    'ar_value' => $arStatValues[$stat['value']] ?? $stat['value'],
                ]);
            }

            // Intakes
            if (isset($data['intakeTimeline'])) {
                foreach ($data['intakeTimeline'] as $intake) {
                    DestinationIntake::create([
                        'destination_id' => $dest->id,
                        'month' => $intake['month'],
                        'ar_month' => $arIntakeMonths[$intake['month']] ?? $intake['month'],
                        'event' => $intake['event'],
                        'ar_event' => $arIntakeEvents[$intake['event']] ?? $intake['event'],
                    ]);
                }
            }

            // FAQs
            if (isset($data['faqs'])) {
                foreach ($data['faqs'] as $faq) {
                    DestinationFaq::create([
                        'destination_id' => $dest->id,
                        'question' => $faq['question'],
                        'ar_question' => $arFaqQuestions[$faq['question']] ?? $faq['question'],
                        'answer' => $faq['answer'],
                        'ar_answer' => $arFaqAnswers[$faq['answer']] ?? $faq['answer'],
                    ]);
                }
            }

            // Requirements
            if (isset($data['requirements'])) {
                foreach ($data['requirements'] as $req) {
                    DestinationRequirement::create([
                        'destination_id' => $dest->id,
                        'requirement' => $req,
                        'ar_requirement' => $arRequirements[$req] ?? $req,
                    ]);
                }
            }

            // Disciplines
            if (isset($data['popularDisciplines'])) {
                foreach ($data['popularDisciplines'] as $disc) {
                    DestinationDiscipline::create([
                        'destination_id' => $dest->id,
                        'discipline' => $disc,
                        'ar_discipline' => $arDisciplines[$disc] ?? $disc,
                    ]);
                }
            }
        }
    }
}
