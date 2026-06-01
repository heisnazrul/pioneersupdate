<?php

namespace Database\Seeders;

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $this->publishReviewImages();

        $rows = $this->rows();

        foreach ($rows as $row) {
            $texts = $this->assignReviewTexts($row['review']);
            $createdAt = $this->parseSheetDate($row['date']);
            $screenshotPath = $this->screenshotStoragePath($row['screenshot_file'] ?? null);

            Review::updateOrCreate(
                [
                    'name' => $row['name'],
                    'university_name' => $row['university_name'],
                    'course_name' => $row['course_name'],
                    'created_at' => $createdAt,
                    'ar_review_text' => $texts['ar_review_text'],
                ],
                [
                    'institute_name' => $row['university_name'],
                    'title' => $row['course_name'],
                    'review_text' => $texts['review_text'],
                    'ar_review_text' => $texts['ar_review_text'],
                    'rating' => 5,
                    'gender' => $row['gender'] ?? null,
                    'screenshots' => $screenshotPath ? [$screenshotPath] : null,
                    'is_approved' => true,
                    'is_active' => true,
                    'updated_at' => $createdAt,
                ]
            );
        }

        $this->command?->info('Seeded ' . count($rows) . ' student reviews.');
    }

    private function publishReviewImages(): void
    {
        $sourceDir = database_path('seeders/assets/reviews');

        if (! is_dir($sourceDir)) {
            $this->command?->warn('Review assets folder missing: database/seeders/assets/reviews');

            return;
        }

        Storage::disk('public')->makeDirectory('reviews');

        foreach (glob($sourceDir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) ?: [] as $file) {
            $filename = basename($file);
            $destination = 'reviews/' . $filename;

            Storage::disk('public')->put($destination, file_get_contents($file));
        }
    }

    private function screenshotStoragePath(?string $filename): ?string
    {
        if (! $filename) {
            return null;
        }

        $path = 'reviews/' . $filename;

        return Storage::disk('public')->exists($path) ? $path : null;
    }

    /**
     * @return list<array{
     *     date: string,
     *     name: string,
     *     university_name: string,
     *     course_name: string,
     *     review: string,
     *     screenshot_file: ?string,
     *     gender?: string|null
     * }>
     */
    private function rows(): array
    {
        return [
            [
                'date' => '08/04/2025',
                'name' => 'Mohammed Omar M Mujallid',
                'university_name' => 'Glasgow',
                'course_name' => 'Science and Engineering',
                'review' => 'الحمدلله ويعطيكم الف عافيه 🙏🏻',
                'screenshot_file' => '01-mohammed-omar-m-mujallid.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '11/3/2026',
                'name' => 'Ahoud Faisal R Altamimi',
                'university_name' => 'Exeter',
                'course_name' => 'Genomic medicine',
                'review' => "التجربه مع مكتب الرواد كانت جدا ممتاز وأقدر بكل بساطه التوصيه لشخص آخر بالتعامل مع هذا المركز\nيتواصلوا بالتحديثات فور حدوثها\nيعطوا توصيات بالجامعات المناسبه\nيعطوا تفاصيل بأسباب الرفض فيه حال حصولها\n\nاتمنى لكم كل التوفيق",
                'screenshot_file' => '02-ahoud-faisal-r-altamimi.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '4/3/2025',
                'name' => 'Diala Mohannad M Mominkhan',
                'university_name' => 'Reading',
                'course_name' => 'Architecture',
                'review' => 'تمام يعطيكم العافيه، حبدأ باجراءات الفيزا',
                'screenshot_file' => '03-diala-mohannad-m-mominkhan.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '23/2/2026',
                'name' => 'Khalid Ahmed N Alotaibi',
                'university_name' => 'Alberta',
                'course_name' => 'Computing Science - Software Practice Option',
                'review' => 'الله يعطيكم الف الف عافيه ما تقصرون شكرًا لكم الشكر الجزيل ❤️',
                'screenshot_file' => '04-khalid-ahmed-n-alotaibi-alberta-1.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '30/3/2026',
                'name' => 'Anas Hassan H Alzahrani',
                'university_name' => 'Reading',
                'course_name' => 'Supply chain management',
                'review' => 'يعطيكم العافيه 🙏🏻🙏🏻🙏🏻🙏🏻🙏🏻🙏🏻',
                'screenshot_file' => '05-anas-hassan-h-alzahrani-reading.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '21/3/3036',
                'name' => 'Thabyah Abdullah M Alsaiari',
                'university_name' => 'Edinburgh',
                'course_name' => 'Economics and Politics MA (Hons)',
                'review' => 'الحمدلله ، بقدم على سفير الحين ، يعطيكم الف عافيه',
                'screenshot_file' => '06-thabyah-abdullah-m-alsaiari-edinburgh.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '9/1/2026',
                'name' => 'Reham Saeed S Alahmari',
                'university_name' => 'Glasgow',
                'course_name' => 'Biotechnology',
                'review' => 'الله يبارك بعمرك يارب اللهم لك الحمد',
                'screenshot_file' => '07-reham-saeed-s-alahmari.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '15/10/2025',
                'name' => 'Nader Naif S Alshammari',
                'university_name' => 'Cardiff',
                'course_name' => 'Economics',
                'review' => 'شاكر ومقدر',
                'screenshot_file' => '08-nader-naif-s-alshammari.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '09/02/2026',
                'name' => 'Mawahib Saleh A Alsamti',
                'university_name' => 'Bristol',
                'course_name' => 'Economy',
                'review' => "من جدد\nربي يسعدكم ياااارب ياكريم\nيالله مو مصدقه\nفرحانه الحمممد لله",
                'screenshot_file' => '09-mawahib-saleh-a-alsamti-1.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '03/04/2026',
                'name' => 'Bassam Abdulrazaq G Dar Bari',
                'university_name' => 'Manchester',
                'course_name' => 'Supply chain management',
                'review' => "جمعة مباركة اشكركم على احترافيتكم وجودة خدمتكم ماشاء الله لم ارى مثلها في مكاتب التعليم والسفر والدراسة بالخارج\n\nان شاء الله اي شخص مهتم راح اوصلهم لكم",
                'screenshot_file' => '10-bassam-abdulrazaq-g-dar-bari.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '13/04/2026',
                'name' => 'Abdulelah Moraya S Alqahtani',
                'university_name' => 'DMU',
                'course_name' => 'Electrical and Electronic Engineering',
                'review' => "الله يوفقكم الله يوفقكم الله يوفقكم\nشكرا جزيلا و الله يعطيكم العافيه و يسهل عليكم كل صعب والله انكم ما قصرتو أبدا 🎉🤍🤍🤍🤍",
                'screenshot_file' => '11-abdulelah-moraya-s-alqahtani-1.png',
                'gender' => 'male',
            ],
            [
                'date' => '13/04/2026',
                'name' => 'Abdulelah Moraya S Alqahtani',
                'university_name' => 'DMU',
                'course_name' => 'Electrical and Electronic Engineering',
                'review' => "كانت تجربتي معكم ممتازة جدًا من البداية حتى الحصول على القبول.\nأكثر ما أعجبني هو وضوح الإجراءات وتنظيم العمل واختياركم المناسب للجامعات.\nكذلك اهتمامكم بالتفاصيل والمتابعة الدقيقة في كل مرحلة أعطاني ثقة وراحة كبيرة.\nشكرًا لكم على احترافيتكم العالية وتعاونكم المميز",
                'screenshot_file' => '12-abdulelah-moraya-s-alqahtani-2.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '01/05/2026',
                'name' => 'Thabyah Abdullah M Alsaiari',
                'university_name' => 'Leeds',
                'course_name' => 'Economics',
                'review' => 'حقيقًة ما انسى فضلكم علي جدًا متعاونين ، وسرعة تنفيذ الطلبات ابدًا ما شفت منكم اي قصور سلمتم وشكر الله سعيكم ، وشكرًا ساره صدق ساعدتيني لاني كنت محتاره ووجهتيني علمتيني الصح بحكم خبرتك 🤍',
                'screenshot_file' => '13-thabyah-abdullah-m-alsaiari-leeds.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '01/05/2026',
                'name' => 'Fahd Farhan A Alanza',
                'university_name' => 'Reading',
                'course_name' => 'Architecture',
                'review' => "وعليكم السلام ورحمة الله وبركاته\nاهلا أستاذه\nكانت تجربتي معكم في توفير القبولات مميزة وسهلة جدًا وسرعة الاستجابة في متابعة الطلبات كانت من أكثر الأشياء اللي أعجبتني والتعامل كان احترافي وواضح وكل استفساراتي تم الرد عليها بشكل دقيق وسريع وأقدّر كثير اهتمامكم بالتفاصيل ومتابعة كل خطوة حتى إتمام القبول وبشكل عام تجربة مريحة وخلّت الإجراءات أسهل بكثير علي",
                'screenshot_file' => '14-fahd-farhan-a-alanza.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '05/05/2026',
                'name' => 'Anas Hassan H Alzahrani',
                'university_name' => 'Exeter',
                'course_name' => 'Data science',
                'review' => 'كانت تجربة ممتازة جدا وسلسة من البداية إلى النهاية ومساعدتك واهتمامكم موجود، أكثر شيء أعجبني هو سرعة تجاوبكم ووضوحكم في الإجابة على جميع استفساراتي. و حرصكم على متابعة الطلب خطوة بخطوة أعطاني راحة وثقة كبيرة. شكرًا لكم على احترافيتكم ودعمكم المستمر.',
                'screenshot_file' => '15-anas-hassan-h-alzahrani-exeter.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '22/05/2026',
                'name' => 'Ahmed Othman A Alamoudi',
                'university_name' => 'Durham',
                'course_name' => 'Finance',
                'review' => 'عليكم السلام ورحمه الله و بركاته، تجربتي مع المكتب كانت ممتازة جدًا، كانوا متعاونين وسريعين في الرد والمتابعة، والقبولات اللي وفرّوها لي كانت قوية ومناسبة لتخصصي وطموحي',
                'screenshot_file' => '16-ahmed-othman-a-alamoudi.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '21/05/2026',
                'name' => 'Amnah Mohammed H Albaraq',
                'university_name' => 'Southampton',
                'course_name' => 'Supply Chain Management',
                'review' => "كانت تجربتي معكم ممتازة جدًا، وتعاملكم كان راقي وسريع من بداية التقديم حتى توفير القبول.\nأكثر شيء أعجبني هو سرعة الرد على الاستفسارات والمتابعة المستمرة والوضوح في شرح جميع الخطوات والتفاصيل.\nأيضًا حسّيت باهتمامكم الحقيقي براحة الطالب وتقديم الخيارات المناسبة له، وهذا الشيء أعطاني ثقة وطمأنينة خلال إجراءات التقديم.\nشكرًا لكم على تعاونكم واحترافيتكم، وأتمنى لكم مزيد من التقدم والنجاح",
                'screenshot_file' => '17-amnah-mohammed-h-albaraq.jpg',
                'gender' => 'female',
            ],
            [
                'date' => '25/05/2026',
                'name' => 'Khalid Ahmed N Alotaibi',
                'university_name' => 'Alberta',
                'course_name' => 'Computing Science - Software Practice Option',
                'review' => "عليكم السلام ورحمة الله وبركاته\nالله يعطيكم العافيه ما قصرتو معي خدمه مجانيه تضاهي خدمات بالالوف\nقدمتو لي قبول البيرتا في غضون ثلاث اسابيع هذا شي انا شاكر جدا ومقدر انكم قدرتو توفرونه لي\nاللي عجبني سرعة ردكم اذا مره مره كان فيه تأخير يكون بسبب الاجازات واوقات العمل وما شابه , ويعطيكم الف عافيه",
                'screenshot_file' => '18-khalid-ahmed-n-alotaibi-alberta-2.jpg',
                'gender' => 'male',
            ],
            [
                'date' => '25/05/2026',
                'name' => 'Mawahib Saleh A Alsamti',
                'university_name' => 'Bristol',
                'course_name' => 'Economy',
                'review' => 'تجربتي مع رواد القبول كانت ممتازة جدًا، وكان أكثر شيء أعجبني سرعة الرد والمتابعة المستمرة والاهتمام بالتفاصيل. ساعدتموني في توفير عدة قبولات جامعية، وكنتم دائمًا متعاونين وصبورين في الإجابة على جميع استفساراتي. أشكركم على دعمكم واحترافيتكم، وأتمنى لكم المزيد من النجاح والتوفيق',
                'screenshot_file' => '19-mawahib-saleh-a-alsamti-2.jpg',
                'gender' => 'female',
            ],
        ];
    }

    private function parseSheetDate(string $value): Carbon
    {
        $value = trim(Str::replace('3036', '2026', $value));

        foreach (['d/m/Y', 'j/n/Y', 'd/n/Y', 'j/m/Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->startOfDay();
            } catch (\Throwable) {
                // try next format
            }
        }

        return now()->startOfDay();
    }

    /**
     * @return array{review_text: ?string, ar_review_text: ?string}
     */
    private function assignReviewTexts(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            return ['review_text' => null, 'ar_review_text' => null];
        }

        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return ['review_text' => null, 'ar_review_text' => $text];
        }

        return ['review_text' => $text, 'ar_review_text' => null];
    }
}
