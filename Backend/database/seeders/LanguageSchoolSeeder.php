<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LanguageSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 5,
                'name' => 'LSI Education',
                'ar_name' => 'معهد ال اس اي',
                'description' => 'The institute is based in London, the capital city of the United Kingdom. It also has branches in countries like the UK, the United States, Canada, New Zealand, and Australia, among others. Students benefit from a supportive learning environment, with qualified instructors, engaging activities that encourage language use, and organized trips to popular tourist destinations around London.',
                'ar_description' => 'يقع المعهد في مدينة لندن، عاصمة المملكة المتحدة. وله فروع في دول مثل المملكة المتحدة، الولايات المتحدة، كندا، نيوزيلندا وأستراليا، وغيرها. يستفيد الطلاب من بيئة تعليمية داعمة، مع مدرسين مؤهلين، وأنشطة تفاعلية تشجع على استخدام اللغة، ورحلات منظمة إلى وجهات سياحية شهيرة في لندن.',
                'logo' => 'school_logos/4KU1iLftzeqp4xDzfIkKMW0CP0dEW4qNyB8wBFPU.png',
                'accreditation_id' => '["1", "2"]',
                'created_at' => '2025-03-26 07:52:08',
                'updated_at' => '2025-03-26 07:52:08',
            ],
            [
                'id' => 7,
                'name' => 'Bath Academy of English',
                'ar_name' => 'معهد اكاديمية باث',
                'description' => 'Bath Academy of English, established in 1997, is a language school in Bath, England, offering a variety of English courses for international students. These include General English, Business English, and exam preparation programs like IELTS and Cambridge exams. The academy emphasizes personalized teaching with small class sizes, ensuring students receive individual attention. It also provides accommodation options, including homestays, to enhance students’ cultural and educational experience.',
                'ar_description' => 'أكاديمية بات الإنجليزية، التي تأسست في عام 1997، هي مدرسة لغة تقع في مدينة بات، إنجلترا، وتقدم مجموعة متنوعة من الدورات التعليمية للغة الإنجليزية للطلاب الدوليين. تشمل هذه الدورات الإنجليزية العامة، والإنجليزية للأعمال، وبرامج التحضير للامتحانات مثل IELTS و Cambridge. تركز الأكاديمية على التدريس الشخصي مع أحجام فصول صغيرة، مما يضمن حصول الطلاب على اهتمام فردي. كما توفر الأكاديمية خيارات إقامة، بما في ذلك الإقامة مع العائلات، لتعزيز تجربة الطلاب الثقافية والتعليمية.',
                'logo' => 'school_logos/JFqykqHk8seQytYLGBin71fjBydTNt1IVlrhoweD.png',
                'accreditation_id' => '["1","2","3","6"]',
                'created_at' => '2025-04-02 02:43:42',
                'updated_at' => '2025-04-02 02:43:42',
            ],
            [
                'id' => 8,
                'name' => 'Concorde International',
                'ar_name' => 'معهد كونكورد العالمي',
                'description' => 'Concorde International is a language school that offers English courses for international students. The school provides a range of programs, including General English, Business English, exam preparation, and specialized courses. With a focus on high-quality education, Concorde International offers personalized teaching in small class sizes. Located in the UK, the school also provides various accommodation options to help students immerse themselves in the language and culture.',
                'ar_description' => 'كونكورد إنترناشونال هي مدرسة لغات تقدم دورات في اللغة الإنجليزية للطلاب الدوليين. توفر المدرسة مجموعة من البرامج، بما في ذلك الإنجليزية العامة، الإنجليزية للأعمال، التحضير للامتحانات، والدورات المتخصصة. مع التركيز على التعليم عالي الجودة، تقدم كونكورد إنترناشونال تدريسًا شخصيًا في فصول صغيرة. تقع المدرسة في المملكة المتحدة، كما توفر خيارات إقامة متنوعة لمساعدة الطلاب على الاندماج في اللغة والثقافة.',
                'logo' => 'school_logos/7sEfdSKSFbCM3QYBVviplBgHyaWYLxx7ZB4wRH9a.png',
                'accreditation_id' => '["1","2","3","6","7"]',
                'created_at' => '2025-04-02 03:05:50',
                'updated_at' => '2025-04-02 03:05:50',
            ],
            [
                'id' => 9,
                'name' => 'Islington Centre for English',
                'ar_name' => 'مركز إزلنجتون للغة الإنجليزية',
                'description' => 'The Islington Centre for English is located in central London and offers a variety of courses suitable for all levels. It is equipped with modern learning tools that make learning easy and simple. The center\'s teachers are all native English speakers who encourage students to engage in class and practice speaking English as much as possible.',
                'ar_description' => 'يتموقع معهد The Islington Centre for English في قلب لندن ويقدم مجموعة من الدورات المناسبة لجميع المستويات. ويتميز المعهد بتوفر أدوات تعليمية حديثة تجعل عملية التعلم سهلة وبسيطة. جميع المدرسين في المعهد من المتحدثين الأصليين للغة الإنجليزية ويشجعون الطلاب على التفاعل داخل الفصول الدراسية والتحدث باللغة الإنجليزية بأقصى قدر ممكن.',
                'logo' => 'school_logos/JybRxwWmPXZJDfAztQhjmHFwhjSrZR6z6nFouHau.png',
                'accreditation_id' => '["1","2","3"]',
                'created_at' => '2025-04-02 03:09:05',
                'updated_at' => '2025-04-02 03:09:05',
            ],
            [
                'id' => 10,
                'name' => 'The London School of English',
                'ar_name' => 'معهد لندن للغة الإنجليزية',
                'description' => 'The London School of English is just 15 minutes away from central London and offers a great experience in learning English. Established in 1912, it provides students with innovative and modern tools through reports, practical training, and tracking students\' goals to learn the language in the best possible ways. The school has certified teachers with many years of experience. It is worth noting that the institute welcomes students from over 70 different nationalities each year.',
                'ar_description' => 'معهد The London School of English يقع على بعد 15 دقيقة فقط من وسط لندن ويعتبر تجربة مميزة لدراسة اللغة الإنجليزية. تأسس المعهد في عام 1912م ويقدم لطلابها أدوات مبتكرة وحديثة من خلال التقارير والتدريب العملي وتتبع أهداف الطلاب لتعلم اللغة بأفضل الطرق. يمتلك المعهد مدرسين معتمدين ذوي سنوات عديدة من الخبرة. ومن الجدير بالذكر أن المعهد يستقبل طلابًا من أكثر من 70 جنسية مختلفة حول العالم كل عام.',
                'logo' => 'school_logos/9CQTlvef2rP6VgnzWZqXqIXaKxKcwWbDROQw9IhA.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 03:13:16',
                'updated_at' => '2025-04-02 03:13:16',
            ],
            [
                'id' => 11,
                'name' => 'Beet Language Centre',
                'ar_name' => 'معهد بيت لانقويج سنتر',
                'description' => 'The "BEET English Language Center" is an educational institution specializing in teaching English, established in 1979 in Bournemouth, United Kingdom. The center offers a variety of programs, including General English, Business English, and exam preparation for IELTS and Cambridge exams. It is known for providing high-quality education with a focus on personalized teaching in small class sizes, ensuring individual attention for each student. Additionally, the center offers various accommodation options to enhance the cultural and educational experience for students.',
                'ar_description' => 'معهد "بيت إنجلش سنتر" (BEET English Language Center) هو مؤسسة تعليمية متخصصة في تدريس اللغة الإنجليزية، تأسس عام 1979 في مدينة بورنموث، المملكة المتحدة. يقدم المعهد برامج متنوعة مثل الإنجليزية العامة، الإنجليزية للأعمال، والتحضير لامتحانات IELTS وكامبريدج. يتميز بتقديم تعليم عالي الجودة مع التركيز على التدريس الشخصي في فصول صغيرة، مما يضمن اهتمامًا فرديًا لكل طالب. بالإضافة إلى ذلك، يوفر المعهد خيارات إقامة متنوعة لتعزيز تجربة الطلاب الثقافية والتعليمية.',
                'logo' => 'school_logos/Mu7eYf0XvQIMGEm8ZPmBOw3e9iWRkH0jow7JOV1v.png',
                'accreditation_id' => '["1","2","8","9","10","11","12"]',
                'created_at' => '2025-04-02 03:19:26',
                'updated_at' => '2025-04-02 03:19:26',
            ],
            [
                'id' => 12,
                'name' => 'Berlitz Manchester',
                'ar_name' => 'معهد بيرلتز',
                'description' => 'Berlitz Institute stands out with its fantastic location in the heart of Manchester, which many consider the second capital of the UK. This allows students to enjoy the city, practice the language naturally, and gain cultural experiences while forming friendships. The institute was founded in 1878, making it one of the oldest and most prestigious institutions. Since its establishment, it has maintained a high level of educational quality and value by offering a wide range of courses and selecting highly qualified teachers.',
                'ar_description' => 'يتميز معهد بيرلتز بموقعه الرائع في قلب مدينة مانشستر، التي يعتبرها الكثيرون العاصمة الثانية لبريطانيا. مما يتيح لطلابه الاستمتاع بالخروج في المدينة وممارسة اللغة بشكل طبيعي واكتساب الثقافة مع تكوين الصداقات. تأسس المعهد في عام 1878، مما يجعله من أعرق وأقدم المعاهد. ومنذ تأسيسه، حافظ المعهد على تقديم جودة وقيمة تعليمية عالية لطلابه من خلال تقديم دورات متنوعة واختيار مدرسين ذوي مستوى عالٍ.',
                'logo' => 'school_logos/Tl7UYKZd2CcnmFd9mQ5hGmgPPTbDS1iERx8JoH5d.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 03:23:24',
                'updated_at' => '2025-04-02 03:23:24',
            ],
            [
                'id' => 13,
                'name' => 'Britannia English Academy',
                'ar_name' => 'معهد بريطانيا انجلش اكاديمي',
                'description' => 'Britannia English Academy is an independent language school located in the heart of Manchester, UK. Established in 2012, it offers a range of English courses, including General English, Conversation Lessons, Exam Preparation for Cambridge FCE, CAE, and IELTS, as well as Business English and One-to-One sessions. With a maximum class size of 10 students, the academy ensures personalized attention from qualified teachers. Beyond academics, students can participate in social activities like conversation clubs and trips, enhancing their cultural experience. Accommodation options include homestays and student residences.',
                'ar_description' => 'أكاديمية بريتانيا الإنجليزية هي مدرسة لغات مستقلة تقع في قلب مانشستر، المملكة المتحدة. تأسست في عام 2012، وتقدم مجموعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، دروس المحادثة، التحضير للامتحانات مثل FCE، CAE، IELTS، بالإضافة إلى الإنجليزية للأعمال والدروس الفردية. مع حد أقصى لعدد الطلاب في الفصول يبلغ 10 طلاب، تضمن الأكاديمية الاهتمام الشخصي من قبل المعلمين المؤهلين. بالإضافة إلى الدروس الأكاديمية، يمكن للطلاب المشاركة في الأنشطة الاجتماعية مثل أندية المحادثة والرحلات، مما يعزز تجربتهم الثقافية. تشمل خيارات الإقامة الإقامة مع العائلات والإقامات الطلابية.',
                'logo' => 'school_logos/gQqyhxjtTEy6aBNcmcqy2EjJa5LSEwkFvrfAZ3Zr.png',
                'accreditation_id' => '["1","2","3","10"]',
                'created_at' => '2025-04-02 03:32:01',
                'updated_at' => '2025-04-02 03:32:01',
            ],
            [
                'id' => 14,
                'name' => 'Oxford International Study Centre',
                'ar_name' => 'معهد أكسفورد ستودي سنتر الدولي',
                'description' => 'The Oxford International Study Centre (OISC) is a private tutorial college and language school located in the heart of Oxford, UK. Established over four hundred years ago, the institution offers a diverse range of programs, including English language courses, GCSE and A-Level studies, university preparation, and professional training. OISC is renowned for its personalized teaching approach, maintaining small class sizes to ensure individual attention. The center also provides various accommodation options, including residential stays in historic university colleges during the summer months. Accredited by the Independent Schools Inspectorate, OISC is committed to delivering high-quality education and fostering a supportive learning environment.',
                'ar_description' => 'مركز أكسفورد الدولي للدراسات هو كلية تعليم خاص ومدرسة لغات تقع في قلب مدينة أكسفورد، المملكة المتحدة. تأسست منذ أكثر من أربعمائة عام، وتقدم المؤسسة مجموعة متنوعة من البرامج، بما في ذلك دورات اللغة الإنجليزية، ودروس GCSE وA-Level، والتحضير للجامعات، والتدريب المهني. يشتهر مركز أكسفورد الدولي بأسلوبه التعليمي الشخصي، حيث يحافظ على أحجام فصول صغيرة لضمان الاهتمام الفردي. كما يوفر المركز خيارات إقامة متنوعة، بما في ذلك الإقامة في الكليات الجامعية التاريخية خلال أشهر الصيف. معتمدة من هيئة تفتيش المدارس المستقلة، يلتزم OISC بتقديم تعليم عالي الجودة وتوفير بيئة تعليمية داعمة.',
                'logo' => 'school_logos/zQrnx4IzkBtzmFIKN25zePy0DlQB6MHaWlK4VpaU.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 03:34:08',
                'updated_at' => '2025-04-02 03:34:08',
            ],
            [
                'id' => 15,
                'name' => 'Preston Academy of English',
                'ar_name' => 'معهد بريستون أكاديمي',
                'description' => 'Preston Academy of English (PAE) is a private language school located in the heart of Preston, Lancashire, UK. Established in 2012, PAE offers a variety of English language courses, including General English, IELTS preparation, and English conversation classes. The academy emphasizes personalized teaching with small class sizes, ensuring individual attention for each student. Students also have access to a self-learning center and a student lounge equipped with amenities like free Wi-Fi. PAE is accredited by the British Council and is an authorized exam center for Trinity College London and LanguageCert.',
                'ar_description' => 'أكاديمية بريستون للغة الإنجليزية (PAE) هي مدرسة لغات خاصة تقع في قلب مدينة بريستون، لانكشاير، المملكة المتحدة. تأسست في عام 2012، وتقدم مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS، ودروس المحادثة. تركز الأكاديمية على التعليم الشخصي من خلال فصول صغيرة، مما يضمن اهتمامًا فرديًا بكل طالب. بالإضافة إلى ذلك، توفر PAE مرافق مثل مركز تعلم ذاتي وصالة للطلاب مزودة بخدمات الإنترنت اللاسلكي المجانية. الأكاديمية معتمدة من قبل المجلس الثقافي البريطاني، ومركز اختبار معتمد لامتحانات',
                'logo' => 'school_logos/HMkvfayNXeYWuFbjkZWZQTwTeq0g2TpxSZdglQbe.png',
                'accreditation_id' => '["1","2","13"]',
                'created_at' => '2025-04-02 05:31:35',
                'updated_at' => '2025-04-02 05:31:35',
            ],
            [
                'id' => 16,
                'name' => 'Select English Cambridge',
                'ar_name' => 'معهد سليكت انجليش',
                'description' => 'Select English is an independent language school located in Cambridge, UK, established in 1991. The school offers a variety of English language courses, including General English, Intensive English, IELTS preparation, and summer programs for young learners aged 10 and above. Select English prides itself on providing high-quality teaching with small class sizes, ensuring individual attention from experienced instructors. The facilities include bright classrooms, a computer room, a pleasant garden, a common room, and free wireless internet access. The school is accredited by the British Council and is a member of English UK. Cambridge, known for its prestigious university, offers a rich cultural and academic environment for students.',
                'ar_description' => 'تُعدّ Select English مدرسة لغات مستقلة تقع في مدينة كامبريدج، المملكة المتحدة، وقد تأسست عام 1991. تقدم المدرسة مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات IELTS، وبرامج صيفية للمراهقين بدءًا من سن 10 فما فوق. تتميز Select English بتقديم تعليم عالي الجودة مع التركيز على الفصول الصغيرة لضمان الاهتمام الفردي بكل طالب. تتضمن المرافق صفوفًا دراسية مشرقة، وغرفة حاسوب، وحديقة جميلة، وغرفة مشتركة، واتصال مجاني بشبكة الإنترنت اللاسلكية. المدرسة معتمدة من قبل المجلس الثقافي البريطاني وعضو في English UK.',
                'logo' => 'school_logos/0C1zzyhiKdHs9oVi0SVhUWH3KhBpwMFhzVq31J79.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:33:31',
                'updated_at' => '2025-04-02 05:33:31',
            ],
            [
                'id' => 17,
                'name' => 'UK College of English',
                'ar_name' => 'معهد اللغة الإنجليزية في المملكة المتحدة',
                'description' => 'UK College of English (UKCE) is an independent language school situated in the heart of London, UK. Established in 2001, UKCE offers a variety of English language courses, including General English, IELTS preparation, Occupational English Test (OET) preparation, and private lessons. The college emphasizes personalized teaching with small class sizes, ensuring individual attention for each student. Facilities include air-conditioned classrooms, free Wi-Fi, and modern student amenities. UKCE is accredited by the British Council and is a member of English UK. The college is conveniently located near Liverpool Street Station, making it easily accessible for students.',
                'ar_description' => 'هو معهد لغات مستقل يقع في قلب مدينة لندن، المملكة المتحدة. يقدم مجموعة متنوعة من دورات اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS وOET، والدروس الخصوصية. يتميز المعهد بتقديم تعليم مخصص مع أحجام فصول صغيرة، مما يضمن اهتمامًا فرديًا بكل طالب. تتضمن المرافق صفوفًا دراسية مجهزة تجهيزًا حديثًا، وغرفة حاسوب، وصالة للطلاب، واتصال مجاني بشبكة الإنترنت اللاسلكية. المعهد معتمد من قبل المجلس الثقافي البريطاني وعضو في English UK.',
                'logo' => 'school_logos/NVh1EKiWKNU0XluwFTZOaEAyWLMhWCpM7Iwd64TW.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:36:50',
                'updated_at' => '2025-04-02 05:36:50',
            ],
            [
                'id' => 18,
                'name' => 'Westbourne Academy',
                'ar_name' => 'معهد ويستبورن اكاديمى',
                'description' => 'Westbourne Academy in Bournemouth, Dorset, is a well-known English language school that offers a variety of English courses, including General English, IELTS preparation, and specialized programs. Located in the heart of Bournemouth, the academy is close to the beach, making it an ideal location for students to enjoy a vibrant coastal city while learning. Westbourne Academy prides itself on a supportive learning environment with small class sizes, providing personalized attention to each student. It also offers modern facilities, including a student lounge and access to free Wi-Fi. The school aims to deliver high-quality language education and cultural exchange.',
                'ar_description' => 'أكاديمية ويستبورن في بورنموث، دورست، هي مدرسة معروفة لتعليم اللغة الإنجليزية تقدم مجموعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والتحضير لامتحانات IELTS، وبرامج متخصصة. تقع الأكاديمية في قلب مدينة بورنموث، بالقرب من الشاطئ، مما يجعلها مكانًا مثاليًا للطلاب للاستمتاع بمدينة ساحلية حيوية أثناء تعلم اللغة. تفتخر أكاديمية ويستبورن ببيئة تعليمية داعمة مع فصول دراسية صغيرة، مما يوفر اهتمامًا شخصيًا لكل طالب. كما توفر الأكاديمية مرافق حديثة، بما في ذلك صالة للطلاب وإمكانية الوصول إلى الإنترنت اللاسلكي المجاني. تهدف الأكاديمية إلى تقديم تعليم لغوي عالي الجودة وتعزيز التبادل الثقافي.',
                'logo' => 'school_logos/F8wmvrqZnYABzZkHj7R7Yv6kFzjJShNnQ2w2E6mO.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:40:52',
                'updated_at' => '2025-04-02 05:40:52',
            ],
            [
                'id' => 20,
                'name' => 'Bright School of English',
                'ar_name' => 'مدرسة برايت للغة الإنجليزية',
                'description' => 'Southbourne School of English, established in 1966, is a family-run language school located in Bournemouth, UK. It offers a variety of English courses, including General English, Intensive English, and preparation for Cambridge and IELTS exams. The school provides modern classrooms, free Wi-Fi, and a student café. Accommodation options include homestays close to the school. Southbourne School emphasizes a friendly, supportive learning environment for students of all ages.',
                'ar_description' => 'تأسست مدرسة ساوثبورن للغة الإنجليزية في عام 1966، وهي مدرسة لغات عائلية تقع في بورنموث، المملكة المتحدة. تقدم المدرسة مجموعة متنوعة من الدورات في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات كامبريدج وIELTS. تشمل المرافق صفوفًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومقهى للطلاب. تشمل خيارات الإقامة الإقامة مع العائلات المضيفة القريبة من المدرسة. تركّز المدرسة على بيئة تعليمية ودية وداعمة.',
                'logo' => 'school_logos/ppeb09Zt8ysBs5wuDo5eOVS6MpRx6qHsT7FkfZic.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:46:55',
                'updated_at' => '2025-04-02 05:46:55',
            ],
            [
                'id' => 21,
                'name' => 'Southbourne School of English',
                'ar_name' => 'معهد ساوثبورن للغة الإنجليزية',
                'description' => 'Southbourne School of English, established in 1966, is a family-run language school located in a peaceful area of Bournemouth, UK. The school offers various English courses, including General English, Intensive English, and exam preparation for Cambridge and IELTS. Facilities include modern classrooms, free Wi-Fi, a café, and a student lounge. Accommodation is provided with local homestays. The school ensures a friendly, supportive environment for students of all ages.',
                'ar_description' => 'تأسست مدرسة ساوثبورن للغة الإنجليزية في عام 1966، وهي مدرسة لغات عائلية تقع في منطقة هادئة بمدينة بورنموث، المملكة المتحدة. تقدم المدرسة دورات متنوعة في اللغة الإنجليزية، بما في ذلك الإنجليزية العامة، والإنجليزية المكثفة، والتحضير لامتحانات كامبريدج وIELTS. تشمل المرافق صفوفًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومقهى، وصالة للطلاب. يتم توفير الإقامة مع العائلات المضيفة المحلية. تضمن المدرسة بيئة تعليمية وداعمة للطلاب من جميع الأعمار.',
                'logo' => 'school_logos/OyZVsK4zOHKE02ffiYZ7ISVdKm5WZTojtV5bQLDv.png',
                'accreditation_id' => '["1","2","11"]',
                'created_at' => '2025-04-02 05:50:06',
                'updated_at' => '2025-04-02 05:50:06',
            ],
            [
                'id' => 22,
                'name' => 'Twin English Centre',
                'ar_name' => 'معهد توين انجلش سنتر',
                'description' => 'Twin English Centres, established in 1993, operate campuses in London, Eastbourne, and Dublin. Accredited by the British Council in the UK and by ACELS in Ireland, they offer courses such as General English, Intensive English, IELTS Preparation, and Teacher Development. Facilities include modern classrooms, free Wi-Fi, and social areas. Accommodation options range from homestays to residential housing. Twin emphasizes employability and university progression support.',
                'ar_description' => 'تأسست مراكز توين الإنجليزية في عام 1993، وتدير فروعًا في لندن، إيستبورن، ودبلن. المعتمدة من المجلس الثقافي البريطاني في المملكة المتحدة وACELS في أيرلندا، تقدم المدرسة دورات مثل الإنجليزية العامة، الإنجليزية المكثفة، التحضير لامتحانات IELTS، وتطوير المعلمين. تشمل المرافق فصولًا دراسية حديثة، وإنترنت لاسلكي مجاني، ومناطق اجتماعية. خيارات الإقامة تشمل الإقامة مع العائلات المضيفة والمساكن الطلابية. تركّز توين على دعم التوظيف والتقدم الجامعي.',
                'logo' => 'school_logos/GcWGTqDaN0AqANmrdU5VzZrGGr3HFiKqi1JpRTXk.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:53:32',
                'updated_at' => '2025-04-02 05:53:32',
            ],
            [
                'id' => 23,
                'name' => 'Burlington School',
                'ar_name' => 'معهد بيرلينغتون للغة الإنجليزية',
                'description' => 'Burlington School, established in 1990, is a family-run English language school located in Balham, South London. The school offers a variety of courses, including General English, Business English, exam preparation, and programs for young learners and groups. Facilities include well-equipped classrooms, a library, computer access with free internet, and a cafeteria serving freshly cooked meals. Accommodation options are available on-site and in nearby homestays. Burlington School emphasizes a communicative approach to language learning, aiming to provide quality courses that meet diverse student needs.',
                'ar_description' => 'تأسست مدرسة بيرلينغتون في عام 1990، وهي مدرسة لغات عائلية تقع في منطقة بالهام بجنوب لندن. تقدم المدرسة مجموعة من الدورات، بما في ذلك الإنجليزية العامة، الإنجليزية للأعمال، التحضير للامتحانات، وبرامج للشباب والمجموعات. تشمل المرافق صفوفًا مجهزة تجهيزًا جيدًا، مكتبة، حواسيب مع إمكانية الوصول إلى الإنترنت المجاني، ومقهى يقدم وجبات مطهية طازجة. تتوفر خيارات الإقامة داخل المدرسة أو مع العائلات المضيفة القريبة. تركز مدرسة بيرلينغتون على نهج تواصلي في تعلم اللغة، وتهدف إلى تقديم دورات ذات جودة تلبي احتياجات الطلاب المتنوعة.',
                'logo' => 'school_logos/It7Hh5Ekj9cf3hoYqyb9wV9Y3MdDQxzT9JnDwDZT.png',
                'accreditation_id' => '["1","2"]',
                'created_at' => '2025-04-02 05:59:48',
                'updated_at' => '2025-04-02 05:59:48',
            ],
        ];

        foreach ($rows as $row) {
            $accreditationIds = [];
            if (! empty($row['accreditation_id'])) {
                $decoded = json_decode($row['accreditation_id'], true);
                if (is_array($decoded)) {
                    $accreditationIds = $decoded;
                }
            }

            DB::table('language_schools')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'ar_name' => $row['ar_name'],
                    'slug' => Str::slug($row['name']),
                    'description' => $row['description'],
                    'ar_description' => $row['ar_description'],
                    'logo' => $row['logo'],
                    'accreditation_ids' => json_encode($accreditationIds),
                    'rating' => 0,
                    'created_at' => Carbon::parse($row['created_at']),
                    'updated_at' => Carbon::parse($row['updated_at']),
                ]
            );
        }
    }
}
