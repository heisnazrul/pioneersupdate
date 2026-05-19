<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class UniversityCmsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Home',
                'ar_title' => 'الرئيسية',
                'content' => [
                    'hero' => [
                        'headline' => 'Shape Your Future with World-Class Education',
                        'subheadline' => "Expert guidance and personalized support to help you secure admission at the world's leading universities.",
                        'background_image' => '/hero.png',
                        'figure_image' => '/fig.png',
                        'search_label' => 'Search Courses',
                        'search_placeholder_courses' => 'e.g. Computer Science, MBA...',
                        'search_placeholder_universities' => 'e.g. Oxford, Harvard...',
                        'country_label' => 'Country',
                        'country_placeholder' => 'All Countries',
                        'level_label' => 'Study Level',
                        'level_placeholder' => 'Select...',
                        'intake_label' => 'Intake',
                        'intake_placeholder' => 'Any Intake',
                        'tab_courses' => 'Find Courses',
                        'tab_universities' => 'Find Universities',
                        'search_button_text' => 'Search',
                    ],
                    'stats' => [
                        'heading' => 'Our achievements in numbers',
                        'body' => 'Thanks to our trusted partners and leading educational institutions around the world, we have helped thousands of students secure admissions in top universities.',
                        'mobile_heading' => 'Our achievements and accreditations',
                        'items' => [
                            ['value' => '+15,000', 'label' => 'Students'],
                            ['value' => '+50', 'label' => 'Partner Universities'],
                        ],
                    ],
                    'certificates' => [
                        'heading' => 'We are accredited by many institutions',
                        'body' => 'We are proud of our partnerships with leading educational organizations around the world.',
                    ],
                    'destinations' => [
                        'title' => 'Prestigious Destinations',
                        'subtitle' => 'Study Abroad',
                        'view_all' => 'View All',
                    ],
                    'universities' => [
                        'title' => 'Prestigious Universities',
                        'subtitle' => 'Top Ranked',
                        'view_all' => 'View All',
                        'browse_all' => 'Browse Universities',
                    ],
                    'reviews' => [
                        'video_title' => 'Student Video Reviews',
                        'video_subtitle' => 'Real Stories',
                        'text_title' => 'What Students Say',
                        'text_subtitle' => 'Written Reviews',
                    ],
                    'scholarships' => [
                        'title' => 'Scholarships You Can Apply For',
                        'subtitle' => 'Funding Opportunities',
                        'view_all' => 'View All',
                        'check_eligibility' => 'Check Eligibility',
                    ],
                    'trust' => [
                        'title' => 'Why Students Trust Us',
                        'subtitle' => 'Our Value',
                        'items' => [
                            [
                                'title' => '100% Free Service',
                                'description' => 'We do not charge students any fees for counseling or application processing.',
                                'icon' => 'gift',
                            ],
                            [
                                'title' => '100% Transparency',
                                'description' => 'No hidden costs or bias. We help you choose what is truly best for you.',
                                'icon' => 'eye',
                            ],
                            [
                                'title' => 'Expert Counselors',
                                'description' => 'Our team comprises alumni from top global universities.',
                                'icon' => 'user-check',
                            ],
                            [
                                'title' => '98% Visa Success',
                                'description' => 'Proven track record of success in difficult cases.',
                                'icon' => 'check-circle',
                            ],
                            [
                                'title' => 'End-to-End Support',
                                'description' => 'From counseling to pre-departure briefing.',
                                'icon' => 'globe',
                            ],
                        ],
                    ],
                    'faq' => [
                        'heading' => 'Questions & Answers',
                        'subheading' => 'We help you decide with confidence. These are the most common questions prospective students ask us.',
                        'cta_title' => 'Still have a question?',
                        'cta_text' => "We're here to help you.",
                        'show_more' => 'Show more',
                    ],
                    'blogs' => [
                        'heading' => 'Blogs & Latest News',
                        'heading_mobile' => 'Blogs & News',
                        'cta_text' => 'All articles',
                        'cta_mobile' => 'All',
                    ],
                ],
                'ar_content' => [
                    'hero' => [
                        'headline' => 'شكّل مستقبلك بتعليم عالمي المستوى',
                        'subheadline' => 'إرشاد متخصص ودعم شخصي لمساعدتك على الحصول على قبول في أفضل جامعات العالم.',
                        'background_image' => '/hero.png',
                        'figure_image' => '/fig.png',
                        'search_label' => 'ابحث عن البرامج',
                        'search_placeholder_courses' => 'مثال: علوم الحاسب، إدارة الأعمال...',
                        'search_placeholder_universities' => 'مثال: أكسفورد، هارفارد...',
                        'country_label' => 'الدولة',
                        'country_placeholder' => 'كل الدول',
                        'level_label' => 'المرحلة الدراسية',
                        'level_placeholder' => 'اختر...',
                        'intake_label' => 'موعد القبول',
                        'intake_placeholder' => 'أي موعد',
                        'tab_courses' => 'البحث عن البرامج',
                        'tab_universities' => 'البحث عن الجامعات',
                        'search_button_text' => 'بحث',
                    ],
                    'stats' => [
                        'heading' => 'إنجازاتنا بالأرقام',
                        'body' => 'بفضل شركائنا والمؤسسات التعليمية الرائدة حول العالم، ساعدنا آلاف الطلاب على الحصول على القبول الجامعي.',
                        'mobile_heading' => 'إنجازاتنا واعتماداتنا',
                        'items' => [
                            ['value' => '+15,000', 'label' => 'طالب'],
                            ['value' => '+50', 'label' => 'جامعة شريكة'],
                        ],
                    ],
                    'certificates' => [
                        'heading' => 'معتمدون من العديد من المؤسسات',
                        'body' => 'نفخر بشراكاتنا مع جهات تعليمية رائدة حول العالم.',
                    ],
                    'destinations' => [
                        'title' => 'أفضل الوجهات الدراسية',
                        'subtitle' => 'الدراسة بالخارج',
                        'view_all' => 'عرض الكل',
                    ],
                    'universities' => [
                        'title' => 'جامعات مرموقة',
                        'subtitle' => 'تصنيفات عالمية',
                        'view_all' => 'عرض الكل',
                        'browse_all' => 'تصفح الجامعات',
                    ],
                    'reviews' => [
                        'video_title' => 'تجارب الطلاب المصوّرة',
                        'video_subtitle' => 'قصص حقيقية',
                        'text_title' => 'ماذا يقول الطلاب',
                        'text_subtitle' => 'آراء مكتوبة',
                    ],
                    'scholarships' => [
                        'title' => 'منح يمكنك التقديم عليها',
                        'subtitle' => 'فرص تمويل',
                        'view_all' => 'عرض الكل',
                        'check_eligibility' => 'تحقق من الأهلية',
                    ],
                    'trust' => [
                        'title' => 'لماذا يثق بنا الطلاب',
                        'subtitle' => 'قيمتنا',
                        'items' => [
                            [
                                'title' => 'خدمة مجانية 100%',
                                'description' => 'لا نفرض رسومًا على الطلاب مقابل الاستشارات أو معالجة الطلبات.',
                                'icon' => 'gift',
                            ],
                            [
                                'title' => 'شفافية 100%',
                                'description' => 'بدون تكاليف مخفية أو توجيه منحاز. نساعدك على اختيار الأنسب لك.',
                                'icon' => 'eye',
                            ],
                            [
                                'title' => 'مستشارون خبراء',
                                'description' => 'فريقنا يضم خريجين من أفضل الجامعات العالمية.',
                                'icon' => 'user-check',
                            ],
                            [
                                'title' => 'نجاح تأشيرات 98%',
                                'description' => 'سجل مثبت للنجاح حتى في الحالات المعقدة.',
                                'icon' => 'check-circle',
                            ],
                            [
                                'title' => 'دعم شامل',
                                'description' => 'من الاستشارة وحتى ما قبل السفر.',
                                'icon' => 'globe',
                            ],
                        ],
                    ],
                    'faq' => [
                        'heading' => 'أسئلة وأجوبة',
                        'subheading' => 'نساعدك على اتخاذ القرار بثقة. هذه أكثر الأسئلة شيوعًا من الطلاب.',
                        'cta_title' => 'ما زال لديك سؤال؟',
                        'cta_text' => 'نحن هنا لمساعدتك.',
                        'show_more' => 'عرض المزيد',
                    ],
                    'blogs' => [
                        'heading' => 'المدونة وآخر الأخبار',
                        'heading_mobile' => 'المدونة والأخبار',
                        'cta_text' => 'جميع المقالات',
                        'cta_mobile' => 'الكل',
                    ],
                ],
            ],
            [
                'slug' => 'about',
                'title' => 'About Pioneers',
                'content' => [
                    'hero' => [
                        'badge' => 'Who We Are',
                        'title' => 'About Pioneers',
                        'description' => 'Transforming lives through international education since 2012.',
                        'image' => '',
                    ],
                    'director_message' => [
                        'image' => 'https://placehold.co/600x800?text=Director',
                        'name' => 'Md Abdul Qaium',
                        'role' => 'Director',
                        'title' => 'Welcome to Pioneers EDU',
                        'paragraphs' => [
                            'Dear Valued Partners, Students, and Stakeholders,',
                            'Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education. With offices across multiple countries, we guide students to prestigious institutions worldwide, ensuring their success and satisfaction.',
                            'Our team of highly experienced representatives, educated at renowned universities, provides expert, culturally sensitive guidance. This unique blend of expertise and empathy sets us apart.',
                            'We are proud of our high visa success rates and the trust placed in us by students and partners. Our commitment to innovation and personalized support continues to drive our growth and impact.'
                        ],
                        'closing' => [
                            'text' => 'Warm regards,',
                            'name' => 'Md Abdul Qaium',
                            'position' => 'Director, Pioneers Educational Admission Consultancy Ltd'
                        ]
                    ],
                    'ceo_message' => [
                        'image' => 'https://placehold.co/600x800?text=CEO',
                        'name' => 'Hanan Asiri',
                        'role' => 'CEO',
                        'title' => 'A Message from the CEO',
                        'paragraphs' => [
                            'Dear Students, Parents, and Collaborators,',
                            'As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success. Our mission is to empower students by providing access to world-class education and resources that shape their futures.',
                            'At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity. With our global reach and experienced team, we have successfully guided thousands of students toward achieving their academic dreams.',
                            'Thank you for trusting us to be part of your journey. Together, we will continue to break barriers and build brighter futures for generations to come.'
                        ],
                        'closing' => [
                            'text' => 'Warm regards,',
                            'name' => 'Hanan Asiri',
                            'position' => 'CEO, Pioneers Educational Admission Consultancy Ltd'
                        ]
                    ],
                    'team' => [
                        'badge' => 'Our Experts',
                        'title' => 'Meet Our Team',
                        'members' => [
                            [
                                'name' => 'Tasnim Zarin',
                                'role' => 'Admission Consultation Leader',
                                'desc' => 'Guiding and managing admissions to ensure a smooth enrollment process.',
                                'image' => 'https://placehold.co/400x500?text=Tasnim'
                            ],
                            [
                                'name' => 'Umme Habiba',
                                'role' => 'Sales Manager',
                                'desc' => 'Driving sales growth and leading teams to achieve business targets.',
                                'image' => 'https://placehold.co/400x500?text=Umme'
                            ],
                            [
                                'name' => 'Nazrul Islam',
                                'role' => 'System Administrator',
                                'desc' => 'Managing and securing IT systems to ensure seamless operations.',
                                'image' => 'https://placehold.co/400x500?text=Nazrul'
                            ],
                            [
                                'name' => 'Shohidul Hasan Mitu',
                                'role' => 'BD Office Manager',
                                'desc' => 'Overseeing operations and ensuring efficiency in BD office management.',
                                'image' => 'https://placehold.co/400x500?text=Shohidul'
                            ]
                        ]
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'من نحن',
                        'title' => 'عن بايونيرز',
                        'description' => 'نغير الحياة من خلال التعليم الدولي منذ عام 2012.',
                        'image' => '',
                    ],
                    'director_message' => [
                        'image' => 'https://placehold.co/600x800?text=Director',
                        'name' => 'محمد كايوم',
                        'role' => 'المدير',
                        'title' => 'مرحباً بكم في بايونيرز التعليمية',
                        'paragraphs' => [
                            'شركاؤنا وطلابنا الكرام،',
                            'مرحباً بكم في بايونيرز للاستشارات التعليمية (PEAC). من بدايات متواضعة، نمونا لنصبح منظمة عالمية مكرسة لتحويل الحياة من خلال التعليم الدولي. من خلال مكاتبنا في دول متعددة، نوجه الطلاب إلى مؤسسات مرموقة حول العالم، لضمان نجاحهم ورضاهم.',
                            'يقدم فريقنا من الممثلين ذوي الخبرة العالية، والمثقفين في جامعات مرموقة، إرشادات متخصصة وحساسة ثقافياً. هذا المزيج الفريد من الخبرة والتعاطف يميزنا.',
                            'نحن فخورون بمعدلات نجاح التأشيرات العالية لدينا وبالثقة التي يضعها الطلاب والشركاء فينا. يستمر التزامنا بالابتكار والدعم الشخصي في دفع نمونا وتأثيرنا.'
                        ],
                        'closing' => [
                            'text' => 'مع أطيب التحيات،',
                            'name' => 'محمد كايوم',
                            'position' => 'مدير، شركة بايونيرز للاستشارات التعليمية المحدودة'
                        ]
                    ],
                    'ceo_message' => [
                        'image' => 'https://placehold.co/600x800?text=CEO',
                        'name' => 'حنان عسيري',
                        'role' => 'الرئيس التنفيذي',
                        'title' => 'رسالة من الرئيس التنفيذي',
                        'paragraphs' => [
                            'الطلاب وأولياء الأمور والشركاء الأعزاء،',
                            'بصفتي الرئيس التنفيذي لشركة قطب الرواد التعليمية (Pioneers Edu)، يشرفني أن أشرف على منظمة تعطي الأولوية للتميز التعليمي ونجاح الطلاب. مهمتنا هي تمكين الطلاب من خلال توفير الوصول إلى تعليم وموارد عالمية المستوى تشكل مستقبلهم.',
                            'في بايونيرز التعليمية، نؤمن بخلق الفرص من خلال الابتكار والتعاون والنزاهة. من خلال انتشارنا العالمي وفريقنا ذو الخبرة، نجحنا في توجيه آلاف الطلاب نحو تحقيق أحلامهم الأكاديمية.',
                            'شكراً لثقتكم بنا لنكون جزءاً من رحلتكم. معاً، سنستمر في كسر الحواجز وبناء مستقبل أكثر إشراقاً للأجيال القادمة.'
                        ],
                        'closing' => [
                            'text' => 'مع أطيب التحيات،',
                            'name' => 'حنان عسيري',
                            'position' => 'الرئيس التنفيذي، شركة بايونيرز للاستشارات التعليمية المحدودة'
                        ]
                    ],
                    'team' => [
                        'badge' => 'خبراؤنا',
                        'title' => 'تعرف على فريقنا',
                        'members' => [
                            [
                                'name' => 'تسنيم زارين',
                                'role' => 'قائدة استشارات القبول',
                                'desc' => 'توجيه وإدارة القبول لضمان عملية تسجيل سلسة.',
                                'image' => 'https://placehold.co/400x500?text=Tasnim'
                            ],
                            [
                                'name' => 'أم حبيبة',
                                'role' => 'مديرة المبيعات',
                                'desc' => 'قيادة نمو المبيعات وقيادة الفرق لتحقيق أهداف العمل.',
                                'image' => 'https://placehold.co/400x500?text=Umme'
                            ],
                            [
                                'name' => 'نذر الإسلام',
                                'role' => 'مدير النظام',
                                'desc' => 'إدارة وتأمين أنظمة تكنولوجيا المعلومات لضمان عمليات سلسة.',
                                'image' => 'https://placehold.co/400x500?text=Nazrul'
                            ],
                            [
                                'name' => 'شهيدول حسن ميتو',
                                'role' => 'مدير مكتب تطوير الأعمال',
                                'desc' => 'الإشراف على العمليات وضمان الكفاءة في إدارة مكتب تطوير الأعمال.',
                                'image' => 'https://placehold.co/400x500?text=Shohidul'
                            ]
                        ]
                    ]
                ],
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'content' => [
                    'hero' => [
                        'badge' => "We're Here for You",
                        'title' => 'Contact Us',
                        'description' => "Whether you have a question about universities, visas, or just want to say hello, we're ready to answer all your questions."
                    ],
                    'contact_info' => [
                        'title' => 'Get in Touch',
                        'description' => "Can't make it to an office? No problem. Fill out the form or reach out to us directly through our general channels.",
                        'items' => [
                            ['title' => 'Email', 'value' => 'info@pioneers.edu.sa', 'icon' => 'faEnvelope'],
                            ['title' => 'Phone', 'value' => '+966 50 123 4567', 'icon' => 'faPhone'],
                            ['title' => 'Facebook', 'value' => '#', 'icon' => 'faFacebook'],
                            ['title' => 'Instagram', 'value' => '#', 'icon' => 'faInstagram'],
                        ]
                    ],
                    'offices' => [
                        'title' => 'Our Offices',
                        'description' => 'Visit our offices in major cities worldwide.',
                        'items' => [
                            ['city' => 'London', 'address' => '123 Oxford Street, London, W1D 1LP', 'phone' => '+44 20 7123 4567', 'email' => 'london@pioneers.edu'],
                            ['city' => 'Dubai', 'address' => 'Office 101, Business Bay, Dubai', 'phone' => '+971 4 123 4567', 'email' => 'dubai@pioneers.edu'],
                            ['city' => 'New Delhi', 'address' => 'Connaught Place, New Delhi', 'phone' => '+91 11 1234 5678', 'email' => 'delhi@pioneers.edu'],
                            ['city' => 'New York', 'address' => '5th Avenue, New York, NY', 'phone' => '+1 212 123 4567', 'email' => 'ny@pioneers.edu'],
                        ]
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'نحن هنا من أجلك',
                        'title' => 'اتصل بنا',
                        'description' => 'سواء كان لديك سؤال حول الجامعات، أو التأشيرات، أو ترغب فقط في إلقاء التحية، نحن مستعدون للإجابة على جميع أسئلتك.'
                    ],
                    'contact_info' => [
                        'title' => 'ابق على تواصل',
                        'description' => 'لا تستطيع زيارة مكتب؟ لا مشكلة. املأ النموذج أو تواصل معنا مباشرة من خلال قنواتنا العامة.',
                        'items' => [
                            ['title' => 'البريد الإلكتروني', 'value' => 'info@pioneers.edu.sa', 'icon' => 'faEnvelope'],
                            ['title' => 'الهاتف', 'value' => '+966 50 123 4567', 'icon' => 'faPhone'],
                            ['title' => 'فيسبوك', 'value' => '#', 'icon' => 'faFacebook'],
                            ['title' => 'إنستغرام', 'value' => '#', 'icon' => 'faInstagram'],
                        ]
                    ],
                    'offices' => [
                        'title' => 'مكاتبنا',
                        'description' => 'قم بزيارة مكاتبنا في المدن الرئيسية حول العالم.',
                        'items' => [
                            ['city' => 'لندن', 'address' => '123 شارع أكسفورد، لندن، W1D 1LP', 'phone' => '+44 20 7123 4567', 'email' => 'london@pioneers.edu'],
                            ['city' => 'دبي', 'address' => 'مكتب 101، الخليج التجاري، دبي', 'phone' => '+971 4 123 4567', 'email' => 'dubai@pioneers.edu'],
                            ['city' => 'نيودلهي', 'address' => 'كونوت بليس، نيودلهي', 'phone' => '+91 11 1234 5678', 'email' => 'delhi@pioneers.edu'],
                            ['city' => 'نيويورك', 'address' => 'الجادة الخامسة، نيويورك، NY', 'phone' => '+1 212 123 4567', 'email' => 'ny@pioneers.edu'],
                        ]
                    ]
                ],
            ],
            [
                'slug' => 'student-guide',
                'title' => 'Student Guide',
                'content' => [
                    'hero' => [
                        'badge' => 'Knowledge Hub',
                        'title' => 'Essential Student Guides',
                        'description' => 'Expert advice, insider tips, and comprehensive resources to help you thrive in your international education journey.',
                        'image' => '',
                    ],
                    'categories' => [
                        [
                            'title' => 'Pre-Departure',
                            'description' => 'Packing lists, flight tips, and essential checklists before you leave home.',
                            'icon' => 'faPlane',
                            'color' => 'bg-blue-50 text-blue-600'
                        ],
                        [
                            'title' => 'Academic Success',
                            'description' => 'Study tips, understanding grading systems, and how to ace your assignments.',
                            'icon' => 'faGraduationCap',
                            'color' => 'bg-green-50 text-green-600'
                        ],
                        [
                            'title' => 'Cost of Living',
                            'description' => 'Budgeting advice, part-time work rules, and banking guides for students.',
                            'icon' => 'faMoneyBillWave',
                            'color' => 'bg-orange-50 text-orange-600'
                        ],
                        [
                            'title' => 'City Guides',
                            'description' => 'Deep dives into student life in London, New York, Toronto, and more.',
                            'icon' => 'faCity',
                            'color' => 'bg-purple-50 text-purple-600'
                        ]
                    ],
                    'trust_section' => [
                        'title' => 'Trusted by 10,000+ Students',
                        'description' => 'Our guides are written by experienced education counselors and alumni who have been through the process themselves. We ensure every piece of advice is accurate, up-to-date, and actionable.',
                        'cta_text' => 'Speak to an Expert',
                        'cta_link' => '/contact'
                    ],
                    'tools_resources' => [
                        'title' => 'Tools & Resources',
                        'subtitle' => 'Free Downloads',
                        'description' => 'Essential templates and checklists to simplify your application process.',
                        'items' => []
                    ],
                    'faq' => [
                        'title' => 'Frequently Asked Questions',
                        'subtitle' => 'Common Questions',
                        'description' => 'Have questions? We have answers. If you can\'t find what you\'re looking for, feel free to contact our expert team.',
                        'cta' => [
                            'title' => 'Still have questions?',
                            'description' => 'Our counselors are ready to help you with your specific study abroad queries.',
                            'btn_text' => 'Contact Us',
                            'btn_link' => '/contact'
                        ],
                        'items' => [
                            [
                                'question' => 'How long does the study abroad application process take?',
                                'answer' => 'Typically, it takes 6-12 months. This includes researching universities, preparing for standardized tests (IELTS/TOEFL), gathering documents, applying for admission, and finally the visa process. We recommend starting at least a year in advance.'
                            ],
                            [
                                'question' => 'Can I work while studying abroad?',
                                'answer' => 'Yes, most countries allow international students to work part-time (usually 20 hours per week) during term time and full-time during breaks. Countries like the UK, Canada, Australia, and Germany have specific regulations that we can guide you through.'
                            ],
                            [
                                'question' => 'What are the English language requirements?',
                                'answer' => 'Requirements vary by country and university. Generally, a minimum IELTS score of 6.0-6.5 or a TOEFL iBT score of 80-90 is required for undergraduate and postgraduate courses. Some universities may offer waivers based on your academic background.'
                            ],
                            [
                                'question' => 'Are scholarship opportunities available for international students?',
                                'answer' => 'Absolutely! There are merit-based, need-based, and country-specific scholarships available. We help you identify and apply for scholarships that match your profile to reduce your financial burden.'
                            ],
                            [
                                'question' => 'Do you help with student accommodation?',
                                'answer' => 'Yes, we assist with finding suitable accommodation, whether it\'s on-campus university housing or private off-campus apartments. Check out our Accommodation section for options.'
                            ]
                        ]
                    ],
                    'featured_guides_category_slug' => 'visa'
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'مركز المعرفة',
                        'title' => 'أدلة الطلاب الأساسية',
                        'description' => 'نصائح الخبراء وتلميحات داخلية وموارد شاملة لمساعدتك على التفوق في رحلتك التعليمية الدولية.',
                        'image' => '',
                    ],
                    'categories' => [
                        [
                            'title' => 'قبل السفر',
                            'description' => 'قوائم التعبئة ونصائح الطيران وقوائم المراجعة الأساسية قبل مغادرة المنزل.',
                            'icon' => 'faPlane',
                            'color' => 'bg-blue-50 text-blue-600'
                        ],
                        [
                            'title' => 'النجاح الأكاديمي',
                            'description' => 'نصائح دراسية، فهم أنظمة التقييم، وكيفية التفوق في واجباتك.',
                            'icon' => 'faGraduationCap',
                            'color' => 'bg-green-50 text-green-600'
                        ],
                        [
                            'title' => 'تكلفة المعيشة',
                            'description' => 'نصائح حول الميزانية وقواعد العمل بدوام جزئي وأدلة مصرفية للطلاب.',
                            'icon' => 'faMoneyBillWave',
                            'color' => 'bg-orange-50 text-orange-600'
                        ],
                        [
                            'title' => 'أدلة المدن',
                            'description' => 'نظرات متعمقة في حياة الطلاب في لندن ونيويورك وتورنتو وغيرها.',
                            'icon' => 'faCity',
                            'color' => 'bg-purple-50 text-purple-600'
                        ]
                    ],
                    'trust_section' => [
                        'title' => 'موثوق من قبل أكثر من 10,000 طالب',
                        'description' => 'أدلتنا مكتوبة بواسطة مستشاري تعليم وخريجين ذوي خبرة مروا بهذه العملية بأنفسهم. نحن نضمن أن كل نصيحة دقيقة ومحدثة وقابلة للتطبيق.',
                        'cta_text' => 'تحدث إلى خبير',
                        'cta_link' => '/contact'
                    ],
                    'tools_resources' => [
                        'title' => 'أدوات وموارد',
                        'subtitle' => 'تنزيلات مجانية',
                        'description' => 'قوالب وقوائم مراجعة أساسية لتبسيط عملية التقديم.',
                        'items' => []
                    ],
                    'faq' => [
                        'title' => 'أسئلة وأجوبة',
                        'subtitle' => 'أسئلة شائعة',
                        'description' => 'لديك أسئلة؟ لدينا إجابات. إذا لم تجد ما تبحث عنه، فلا تتردد في الاتصال بفريق الخبراء لدينا.',
                        'cta' => [
                            'title' => 'هل ما زال لديك أسئلة؟',
                            'description' => 'مستشارونا مستعدون لمساعدتك في استفساراتك المحددة حول الدراسة بالخارج.',
                            'btn_text' => 'اتصل بنا',
                            'btn_link' => '/contact'
                        ],
                        'items' => [
                            [
                                'question' => 'كم من الوقت تستغرق عملية التقديم للدراسة بالخارج؟',
                                'answer' => 'عادةً ما تستغرق من 6 إلى 12 شهرًا. ويشمل ذلك البحث عن الجامعات، والتحضير للاختبارات الموحدة (IELTS / TOEFL)، وجمع الوثائق، والتقديم للقبول، وأخيرًا عملية التأشيرة. نوصي بالبدء قبل عام على الأقل.'
                            ],
                            [
                                'question' => 'هل يمكنني العمل أثناء الدراسة في الخارج؟',
                                'answer' => 'نعم، تسمح معظم البلدان للطلاب الدوليين بالعمل بدوام جزئي (عادة 20 ساعة في الأسبوع) خلال الفصل الدراسي وبدوام كامل أثناء فترات العطل. هناك لوائح محددة في بلدان مثل المملكة المتحدة وكندا وأستراليا وألمانيا يمكننا إرشادك خلالها.'
                            ],
                            [
                                'question' => 'ما هي متطلبات اللغة الإنجليزية؟',
                                'answer' => 'تختلف المتطلبات حسب الدولة والجامعة. بشكل عام، يلزم الحصول على درجة 6.0-6.5 في اختبار IELTS أو 80-90 في اختبار TOEFL iBT لبرامج البكالوريوس والدراسات العليا. قد تقدم بعض الجامعات إعفاءات بناءً على خلفيتك الأكاديمية.'
                            ],
                            [
                                'question' => 'هل تتوفر فرص منح دراسية للطلاب الدوليين؟',
                                'answer' => 'بالتأكيد! هناك منح دراسية قائمة على الجدارة وعلى الاحتياج ومنح خاصة بدول معينة. نحن نساعدك على تحديد والتقديم للمنح التي تناسب ملفك لتقليل أعبائك المالية.'
                            ],
                            [
                                'question' => 'هل تساعدون في السكن الطلابي؟',
                                'answer' => 'نعم، نساعد في إيجاد السكن المناسب، سواء كان سكنًا جامعيًا داخل الحرم أو شققًا خاصة خارجه. تحقق من قسم السكن لدينا لمعرفة الخيارات المتاحة.'
                            ]
                        ]
                    ],
                    'featured_guides_category_slug' => 'visa'
                ],
            ],
            [
                'slug' => 'visa-support',
                'title' => 'Visa Support',
                'content' => [
                    'hero' => [
                        'badge' => 'Visa Support Services',
                        'title' => 'Secure Your Student Visa',
                        'description' => 'Expert guidance for UK, USA, Canada, and Australia student visas. We minimize the risk of rejection with our proven methodology.'
                    ],
                    'services_section' => [
                        'title' => 'Comprehensive Visa Support',
                        'subtitle' => 'Detailed Assistance',
                        'description' => 'From document checklist to interview preparation, we cover every aspect of your application.',
                        'items' => [
                            [
                                'title' => 'Document Verification',
                                'description' => 'Reviewing your financial proofs, academic records, and sponsorship letters to meet embassy standards.',
                                'icon' => 'faCheckDouble'
                            ],
                            [
                                'title' => 'Mock Interviews',
                                'description' => 'One-on-one sessions simulating real visa interviews to boost your confidence and readiness.',
                                'icon' => 'faUserTie'
                            ],
                            [
                                'title' => 'Application Strategy',
                                'description' => 'Structuring your application to highlight your strong ties to your home country and genuine intent to study.',
                                'icon' => 'faScaleBalanced'
                            ],
                            [
                                'title' => 'Financial Guidance',
                                'description' => 'Expert advice on presenting funds, sponsorships, and scholarships correctly.',
                                'icon' => 'faFileShield'
                            ],
                            [
                                'title' => 'Slot Booking',
                                'description' => 'Assistance with booking biometric and interview slots at the earliest availability.',
                                'icon' => 'faTimeline'
                            ],
                            [
                                'title' => 'Post-Visa Support',
                                'description' => 'Pre-departure briefings and guidance on travel insurance and accommodation.',
                                'icon' => 'faPlaneDeparture'
                            ]
                        ]
                    ],
                    'process_section' => [
                        'title' => 'Your Roadmap to Approval',
                        'subtitle' => 'Step-by-Step',
                        'description' => 'A clear, structured timeline to ensure zero errors and maximum preparedness.',
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'Consultation',
                                'description' => 'We assess your profile and funding to determine the best visa strategy.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'Documentation',
                                'description' => 'Collecting and organizing every required document flawlessly.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'Application',
                                'description' => 'Filling out visa forms (DS-160, etc.) with precision.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'Interview Prep',
                                'description' => 'Intensive training for your embassy interview.'
                            ]
                        ]
                    ],
                    'cta_section' => [
                        'title' => "Don't Risk Your Visa Application",
                        'description' => 'Get it right the first time with our expert guidance. Book a consultation today.',
                        'button_text' => 'Book Visa Consultation',
                        'button_link' => '/apply-now'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'خدمات دعم التأشيرات',
                        'title' => 'احصل على تأشيرتك الدراسية',
                        'description' => 'إرشادات خبراء لتأشيرات الطلاب في المملكة المتحدة والولايات المتحدة وكندا وأستراليا. نقلل من مخاطر الرفض بمنهجيتنا المجربة.'
                    ],
                    'services_section' => [
                        'title' => 'دعم شامل للتأشيرات',
                        'subtitle' => 'مساعدة مفصلة',
                        'description' => 'من قائمة التحقق من الوثائق إلى التحضير للمقابلة، نغطي كل جانب من جوانب طلبك.',
                        'items' => [
                            [
                                'title' => 'التحقق من الوثائق',
                                'description' => 'مراجعة أدلتك المالية، والسجلات الأكاديمية، وخطابات الرعاية لتلبية معايير السفارة.',
                                'icon' => 'faCheckDouble'
                            ],
                            [
                                'title' => 'مقابلات تجريبية',
                                'description' => 'جلسات فردية تحاكي مقابلات التأشيرة الحقيقية لتعزيز ثقتك واستعدادك.',
                                'icon' => 'faUserTie'
                            ],
                            [
                                'title' => 'استراتيجية التقديم',
                                'description' => 'هيكلة طلبك لإبراز روابطك القوية ببلدك الأم ونيتك الحقيقية في الدراسة.',
                                'icon' => 'faScaleBalanced'
                            ],
                            [
                                'title' => 'توجيه مالي',
                                'description' => 'مشورة خبراء حول تقديم الأموال، والرعاية، والمنح الدراسية بشكل صحيح.',
                                'icon' => 'faFileShield'
                            ],
                            [
                                'title' => 'حجز المواعيد',
                                'description' => 'المساعدة في حجز مواعيد البصمات والمقابلات في أقرب وقت متاح.',
                                'icon' => 'faTimeline'
                            ],
                            [
                                'title' => 'دعم ما بعد التأشيرة',
                                'description' => 'إحاطات ما قبل السفر وتوجيهات حول تأمين السفر والإقامة.',
                                'icon' => 'faPlaneDeparture'
                            ]
                        ]
                    ],
                    'process_section' => [
                        'title' => 'خارطة طريق الموافقة الخاصة بك',
                        'subtitle' => 'خطوة بخطوة',
                        'description' => 'جدول زمني واضح ومنظم لضمان عدم وجود أخطاء وأقصى درجات الاستعداد.',
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'الاستشارة',
                                'description' => 'نقوم بتقييم ملفك الشخصي وتمويلك لتحديد أفضل استراتيجية للتأشيرة.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'الوثائق',
                                'description' => 'جمع وترتيب كل وثيقة مطلوبة بشكل لا تشوبه شائبة.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'التقديم',
                                'description' => 'ملء نماذج التأشيرة (DS-160، إلخ) بدقة تامة.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'التحضير للمقابلة',
                                'description' => 'تدريب مكثف لمقابلتك في السفارة.'
                            ]
                        ]
                    ],
                    'cta_section' => [
                        'title' => "لا تخاطر بطلب التأشيرة الخاص بك",
                        'description' => 'افعلها بالشكل الصحيح من المرة الأولى بمساعدة خبرائنا. احجز استشارة اليوم.',
                        'button_text' => 'احجز استشارة تأشيرة',
                        'button_link' => '/apply-now'
                    ]
                ],
            ],
            [
                'slug' => 'agents',
                'title' => 'Agents & Partners',
                'content' => [
                    'hero' => [
                        'badge' => 'B2B Partnership Program',
                        'title' => 'Grow Your Business with Pioneers',
                        'description' => 'Join our global network of recruitment partners. We empower agents with the tools, technology, and university connections needed to succeed.',
                        'cta_primary' => 'Become a Partner',
                        'cta_primary_link' => '/contact',
                        'cta_secondary' => 'Learn More',
                        'cta_secondary_link' => '#benefits',
                        'bg_image' => '/hero.png'
                    ],
                    'stats' => [
                        ['value' => '150+', 'label' => 'Global Universities'],
                        ['value' => '10k+', 'label' => 'Students Placed'],
                        ['value' => '50+', 'label' => 'Countries'],
                        ['value' => '99%', 'label' => 'Visa Success'],
                    ],
                    'services_section' => [
                        'title' => 'Empowering Your Growth',
                        'subtitle' => 'Why Choose Us',
                        'description' => 'We provide everything you need to scale your student recruitment business efficiently.',
                        'items' => [
                            [
                                'title' => 'Global Network',
                                'description' => 'Access our extensive network of 150+ top universities across the UK, USA, Canada, and Australia.',
                                'icon' => 'faGlobe'
                            ],
                            [
                                'title' => 'High Commissions',
                                'description' => 'Earn competitive commissions with timely payouts and transparent tracking systems.',
                                'icon' => 'faPercent'
                            ],
                            [
                                'title' => 'Marketing Support',
                                'description' => 'Get access to branded marketing materials, brochures, and digital assets to attract more students.',
                                'icon' => 'faChartLine'
                            ],
                            [
                                'title' => 'Dedicated Account Manager',
                                'description' => 'Work with a dedicated expert who will guide you through admissions and updates.',
                                'icon' => 'faUserGroup'
                            ],
                            [
                                'title' => 'Priority Training',
                                'description' => 'Regular training sessions on university courses, visa updates, and application processes.',
                                'icon' => 'faCheckCircle'
                            ],
                            [
                                'title' => '24/7 Support',
                                'description' => 'Our support team is always available to resolve queries and assist with urgent applications.',
                                'icon' => 'faHeadset'
                            ]
                        ]
                    ],
                    'process_section' => [
                        'title' => 'Simple Steps to Start',
                        'description' => 'Partnership Process',
                        'steps' => [
                            ['number' => '01', 'title' => 'Register', 'description' => 'Fill out our partner registration form with your business details.'],
                            ['number' => '02', 'title' => 'Verify', 'description' => 'Our team will review your application and conduct a quick validation call.'],
                            ['number' => '03', 'title' => 'Start Recruiting', 'description' => 'Get access to our portal and start submitting student applications.']
                        ]
                    ],
                    'cta_section' => [
                        'title' => 'Ready to grow with us?',
                        'button_text' => 'Register as a Partner Now',
                        'description' => 'Join over 500+ active agents today.'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'برنامج الشراكة (B2B)',
                        'title' => 'نمّ أعمالك مع بايونيرز',
                        'description' => 'انضم إلى شبكتنا العالمية من شركاء التوظيف. نحن نمكّن الوكلاء بالأدوات، والتكنولوجيا، وشبكات الجامعات اللازمة للنجاح.',
                        'cta_primary' => 'كن شريكاً',
                        'cta_primary_link' => '/contact',
                        'cta_secondary' => 'تعلم المزيد',
                        'cta_secondary_link' => '#benefits',
                        'bg_image' => '/hero.png'
                    ],
                    'stats' => [
                        ['value' => '150+', 'label' => 'جامعات عالمية'],
                        ['value' => '10k+', 'label' => 'طالب تم إلحاقهم'],
                        ['value' => '50+', 'label' => 'دول'],
                        ['value' => '99%', 'label' => 'نسبة نجاح التأشيرات'],
                    ],
                    'services_section' => [
                        'title' => 'تمكين نموك',
                        'subtitle' => 'لماذا نحن؟',
                        'description' => 'نوفر كل ما تحتاجه لتوسيع أعمال توظيف الطلاب بكفاءة.',
                        'items' => [
                            [
                                'title' => 'شبكة عالمية',
                                'description' => 'تواصل مع شبكتنا الواسعة من أكثر من 150 جامعة رائدة في المملكة المتحدة والولايات المتحدة وكندا وأستراليا.',
                                'icon' => 'faGlobe'
                            ],
                            [
                                'title' => 'عمولات عالية',
                                'description' => 'إكسب عمولات تنافسية مع دفعات في الوقت المحدد وأنظمة تتبع شفافة.',
                                'icon' => 'faPercent'
                            ],
                            [
                                'title' => 'دعم تسويقي',
                                'description' => 'الحصول على مواد تسويقية، وكتيبات، وأصول رقمية للعلامة التجارية لجذب المزيد من الطلاب.',
                                'icon' => 'faChartLine'
                            ],
                            [
                                'title' => 'مدير حسابات مخصص',
                                'description' => 'اعمل مع خبير مخصص سيرشدك خلال عمليات القبول والتحديثات.',
                                'icon' => 'faUserGroup'
                            ],
                            [
                                'title' => 'تدريب ذو أولوية',
                                'description' => 'جلسات تدريب منتظمة على الدورات الجامعية، وتحديثات التأشيرات، وعمليات التقديم.',
                                'icon' => 'faCheckCircle'
                            ],
                            [
                                'title' => 'دعم على مدار الساعة',
                                'description' => 'فريق الدعم لدينا متاح دائمًا لحل الاستفسارات والمساعدة في الطلبات العاجلة.',
                                'icon' => 'faHeadset'
                            ]
                        ]
                    ],
                    'process_section' => [
                        'title' => 'خطوات بسيطة للبدء',
                        'description' => 'عملية الشراكة',
                        'steps' => [
                            ['number' => '01', 'title' => 'سجل', 'description' => 'املأ نموذج تسجيل الشركاء بتفاصيل عملك.'],
                            ['number' => '02', 'title' => 'تحقق', 'description' => 'سيقوم فريقنا بمراجعة طلبك وإجراء مكالمة تحقق سريعة.'],
                            ['number' => '03', 'title' => 'ابدأ التوظيف', 'description' => 'احصل على وصول إلى البوابة الإلكترونية وابدأ في تقديم طلبات الطلاب.']
                        ]
                    ],
                    'cta_section' => [
                        'title' => 'مستعد للنمو معنا؟',
                        'button_text' => 'سجل كشريك الآن',
                        'description' => 'انضم إلى أكثر من 500 وكيل نشط اليوم.'
                    ]
                ],
            ],
            [
                'slug' => 'services',
                'title' => 'Our Services',
                'content' => [
                    'hero' => [
                        'badge' => 'World-Class Support',
                        'title' => 'Comprehensive Services for Your Global Journey.',
                        'description' => 'From your first counseling session to your first day on campus, we provide the tools, guidance, and support you need to succeed.',
                        'image' => ''
                    ],
                    'what_we_offer' => [
                        'title' => 'What We Offer',
                        'description' => 'Tailored solutions designed to make your study abroad experience seamless and stress-free.',
                        'items' => [
                            [
                                'title' => 'University Admissions',
                                'description' => 'Expert guidance on selecting courses and universities that align with your career goals. We handle the entire application process.',
                                'icon' => 'faUniversity',
                                'link' => '/services/application'
                            ],
                            [
                                'title' => 'Visa Assistance',
                                'description' => 'Comprehensive support for student visa applications, including document checklists, interview preparation, and filing.',
                                'icon' => 'faPassport',
                                'link' => '/services/visa'
                            ],
                            [
                                'title' => 'Accommodation',
                                'description' => 'Find your home away from home. We help you book safe and affordable student housing near your university.',
                                'icon' => 'faHome',
                                'link' => '/services/accommodation'
                            ],
                            [
                                'title' => 'Scholarships',
                                'description' => 'Discover funding opportunities. We track thousands of scholarships to help you finance your education.',
                                'icon' => 'faGraduationCap',
                                'link' => '/scholarships'
                            ],
                            [
                                'title' => 'Partner Network',
                                'description' => 'For agents and institutions. Join our global network to expand your reach and help more students succeed.',
                                'icon' => 'faHandshake',
                                'link' => '/services/agents'
                            ],
                            [
                                'title' => 'Student Guides',
                                'description' => 'Essential resources, checklists, and how-to guides for every step of your study abroad journey.',
                                'icon' => 'faBookOpen',
                                'link' => '/services/guides'
                            ]
                        ]
                    ],
                    'our_process' => [
                        'title' => 'Simple Steps to Success',
                        'description' => "We've simplified the complex study abroad process into a clear, manageable roadmap. Our experts are with you at every milestone.",
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'Profile Evaluation',
                                'description' => 'We analyze your academic background and career goals.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'University Selection',
                                'description' => 'Curated list of universities that match your profile.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'Application & Visa',
                                'description' => 'End-to-end support with documentation and filing.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'Pre-Departure',
                                'description' => 'Accommodation, flights, and briefing for your new life.'
                            ],
                        ]
                    ],
                    'partner_section' => [
                        'badge' => 'For Partners',
                        'title' => 'Grow with Pioneers Admissions',
                        'description' => 'Are you an education agent or institution? Join our global network to access exclusive resources, streamlined processing, and dedicated support to help your students succeed.',
                        'button_text' => 'Become a Partner',
                        'button_link' => '/services/agents',
                        'secondary_button_text' => 'Contact Our B2B Team',
                        'secondary_button_link' => '/contact'
                    ],
                    'cta' => [
                        'title' => 'Ready to start your journey?',
                        'description' => 'Book a free consultation with our experts today and take the first step towards your global education.',
                        'button_text' => 'Get Started Now',
                        'button_link' => '/consultation'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'دعم عالمي المستوى',
                        'title' => 'خدمات شاملة لرحلتك العالمية.',
                        'description' => 'من جلسة الاستشارة الأولى حتى يومك الأول في الحرم الجامعي، نقدم لك الأدوات، والتوجيه، والدعم الذي تحتاجه للنجاح.',
                        'image' => ''
                    ],
                    'what_we_offer' => [
                        'title' => 'ماذا نقدم',
                        'description' => 'حلول مصممة خصيصًا لجعل تجربة دراستك بالخارج سلسة وخالية من الإجهاد.',
                        'items' => [
                            [
                                'title' => 'القبول الجامعي',
                                'description' => 'توجيه من خبراء لاختيار الدورات والجامعات التي تتماشى مع أهدافك المهنية. نحن نتعامل مع عملية التقديم بأكملها.',
                                'icon' => 'faUniversity',
                                'link' => '/services/application'
                            ],
                            [
                                'title' => 'المساعدة في التأشيرة',
                                'description' => 'دعم شامل لطلبات تأشيرة الطالب، بما في ذلك قوائم الوثائق، والتحضير للمقابلة، وتقديم الطلب.',
                                'icon' => 'faPassport',
                                'link' => '/services/visa'
                            ],
                            [
                                'title' => 'السكن',
                                'description' => 'اعثر على منزلك بعيدًا عن المنزل. نساعدك في حجز سكن طلابي آمن وبأسعار معقولة بالقرب من جامعتك.',
                                'icon' => 'faHome',
                                'link' => '/services/accommodation'
                            ],
                            [
                                'title' => 'المنح الدراسية',
                                'description' => 'اكتشف فرص التمويل. نحن نتتبع آلاف المنح الدراسية لمساعدتك في تمويل تعليمك.',
                                'icon' => 'faGraduationCap',
                                'link' => '/scholarships'
                            ],
                            [
                                'title' => 'شبكة الشركاء',
                                'description' => 'للوكلاء والمؤسسات. انضم إلى شبكتنا العالمية لتوسيع نطاق وصولك ومساعدة المزيد من الطلاب على النجاح.',
                                'icon' => 'faHandshake',
                                'link' => '/services/agents'
                            ],
                            [
                                'title' => 'أدلة الطلاب',
                                'description' => 'موارد أساسية، وقوائم مراجعة، وأدلة إرشادية لكل خطوة في رحلة دراستك بالخارج.',
                                'icon' => 'faBookOpen',
                                'link' => '/services/guides'
                            ]
                        ]
                    ],
                    'our_process' => [
                        'title' => 'خطوات بسيطة للنجاح',
                        'description' => "لقد قمنا بتبسيط عملية الدراسة بالخارج المعقدة إلى خارطة طريق واضحة. خبراؤنا معك في كل خطوة.",
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'تقييم الملف الشخصي',
                                'description' => 'نقوم بتحليل خلفيتك الأكاديمية وأهدافك المهنية.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'اختيار الجامعة',
                                'description' => 'قائمة منسقة بالجامعات التي تتناسب مع ملفك الشخصي.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'التقديم والتأشيرة',
                                'description' => 'دعم متكامل من البداية للنهاية مع الوثائق وتقديم الطلبات.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'قبل السفر',
                                'description' => 'السكن، ورحلات الطيران، وإحاطة لحياتك الجديدة.'
                            ]
                        ]
                    ],
                    'partner_section' => [
                        'badge' => 'للشركاء',
                        'title' => 'نمّ مع قبولات بايونيرز',
                        'description' => 'هل أنت وكيل تعليمي أو مؤسسة؟ انضم إلى شبكتنا العالمية للوصول إلى موارد حصرية، ومعالجة مبسطة، ودعم مخصص لمساعدة طلابك على النجاح.',
                        'button_text' => 'كن شريكاً',
                        'button_link' => '/services/agents',
                        'secondary_button_text' => 'تواصل مع فريق B2B',
                        'secondary_button_link' => '/contact'
                    ],
                    'cta' => [
                        'title' => 'مستعد لبدء رحلتك؟',
                        'description' => 'احجز استشارة مجانية مع خبرائنا اليوم واتخذ الخطوة الأولى نحو تعليمك العالمي.',
                        'button_text' => 'ابدأ الآن',
                        'button_link' => '/consultation'
                    ]
                ],
            ],
            [
                'slug' => 'applications',
                'title' => 'Applications',
                'content' => [
                    'hero' => [
                        'badge' => 'Apply Now',
                        'title' => 'Start Your Application',
                        'description' => 'Your journey to a top university starts here. We guide you through every step.',
                        'image' => ''
                    ],
                    'requirements' => [
                        'title' => 'Admission Requirements',
                        'items' => [
                            ['title' => 'Academic Transcripts', 'description' => 'High school or university records.'],
                            ['title' => 'Language Profits', 'description' => 'IELTS, TOEFL, or PTE scores.'],
                            ['title' => 'Passport Copy', 'description' => 'Valid passport for travel.'],
                            ['title' => 'Statement of Purpose', 'description' => 'A personal essay explaining your goals.']
                        ]
                    ],
                    'process_steps' => [
                        'title' => 'Application Timeline',
                        'steps' => [
                            ['number' => '1', 'title' => 'Document Check', 'description' => 'We verify all your documents.'],
                            ['number' => '2', 'title' => 'Submission', 'description' => 'We submit to universities on your behalf.'],
                            ['number' => '3', 'title' => 'Offer Letter', 'description' => 'Receive conditional or unconditional offers.']
                        ]
                    ],
                    'cta' => [
                        'title' => 'Apply Online',
                        'description' => 'You can start your application process directly through our portal.',
                        'button_text' => 'Apply Now',
                        'button_link' => '/portal/apply'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'قدم الآن',
                        'title' => 'ابدأ تقديم طلبك',
                        'description' => 'تبدأ رحلتك إلى جامعة من الدرجة الأولى هنا. نرافقك في كل خطوة.',
                        'image' => ''
                    ],
                    'requirements' => [
                        'title' => 'متطلبات القبول',
                        'items' => [
                            ['title' => 'الشهادات الأكاديمية', 'description' => 'سجلات المدرسة الثانوية أو الجامعة.'],
                            ['title' => 'إثبات اللغة', 'description' => 'درجات IELTS أو TOEFL أو PTE.'],
                            ['title' => 'نسخة من جواز السفر', 'description' => 'جواز سفر صالح للسفر.'],
                            ['title' => 'رسالة الدوافع', 'description' => 'مقال شخصي يشرح أهدافك.']
                        ]
                    ],
                    'process_steps' => [
                        'title' => 'الجدول الزمني للتقديم',
                        'steps' => [
                            ['number' => '1', 'title' => 'التحقق من الوثائق', 'description' => 'نتحقق من جميع مستنداتك.'],
                            ['number' => '2', 'title' => 'التقديم', 'description' => 'نتقدم إلى الجامعات نيابة عنك.'],
                            ['number' => '3', 'title' => 'رسالة القبول', 'description' => 'تلقي عروض مشروطة أو غير مشروطة.']
                        ]
                    ],
                    'cta' => [
                        'title' => 'قدم عبر الإنترنت',
                        'description' => 'يمكنك بدء عملية التقديم مباشرة من خلال بوابتنا الإلكترونية.',
                        'button_text' => 'قدم الآن',
                        'button_link' => '/portal/apply'
                    ]
                ],
            ],
            [
                'slug' => 'application',
                'title' => 'Application Support',
                'content' => [
                    'hero' => [
                        'title' => 'Expert Application Services',
                        'description' => 'Navigate the complex university application process with confidence. Our team of experts is with you every step of the way.',
                        'btn1_text' => 'Start Your Application',
                        'btn1_link' => '/apply-now',
                        'btn2_text' => 'How It Works',
                        'btn2_link' => '#process'
                    ],
                    'services' => [
                        'subtitle' => 'Our Expertise',
                        'title' => 'Comprehensive Application Support',
                        'description' => 'We provide end-to-end services to maximize your chances of acceptance.',
                        'items' => [
                            [
                                'title' => 'University Selection',
                                'description' => 'We help you identify the best universities based on your academic profile, career goals, and budget.',
                                'icon' => 'faUniversity'
                            ],
                            [
                                'title' => 'SOP & LOR Editing',
                                'description' => 'Our experts refine your Statement of Purpose and Letters of Recommendation to make a compelling case.',
                                'icon' => 'faFilePen'
                            ],
                            [
                                'title' => 'Application Management',
                                'description' => 'We handle the entire application process, ensuring every form is filled correctly and submitted on time.',
                                'icon' => 'faCheckDouble'
                            ],
                            [
                                'title' => 'Interview Preparation',
                                'description' => 'Mock interviews and personalized coaching to help you ace your university or visa interviews.',
                                'icon' => 'faUserTie'
                            ],
                            [
                                'title' => 'Visa Assistance',
                                'description' => 'Complete guidance on visa documentation, financial proof, and interview strategies.',
                                'icon' => 'faPassport'
                            ],
                            [
                                'title' => 'Timeline Planning',
                                'description' => 'We create a customized timeline to ensure you meet all deadlines without stress.',
                                'icon' => 'faCalendarCheck'
                            ]
                        ]
                    ],
                    'process' => [
                        'subtitle' => 'The Process',
                        'title' => 'Your Journey to Acceptance',
                        'description' => 'A structured approach designed to keep you organized and ahead of deadlines.',
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'Profile Evaluation',
                                'description' => 'We analyze your academic background and career aspirations.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'University Shortlisting',
                                'description' => 'Selecting the right mix of ambitious, target, and safe universities.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'Document Preparation',
                                'description' => 'Drafting and refining your SOPs, LORs, and CVs.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'Application Submission',
                                'description' => 'Timely submission of applications to your chosen universities.'
                            ]
                        ],
                        'stat' => [
                            'value' => '98%',
                            'label' => 'Success Rate',
                            'description' => 'Our students consistently secure offers from their top 3 university choices thanks to our strategic approach.'
                        ]
                    ],
                    'cta' => [
                        'title' => 'Ready to Start Your Journey?',
                        'description' => 'Book a free consultation with our experts and take the first step toward your dream university.',
                        'btn_text' => 'Book Free Consultation',
                        'btn_link' => '/apply-now'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'title' => 'خدمات تقديم احترافية',
                        'description' => 'تجاوز عملية التقديم الجامعي المعقدة بثقة. فريق الخبراء لدينا معك في كل خطوة.',
                        'btn1_text' => 'ابدأ طلبك',
                        'btn1_link' => '/apply-now',
                        'btn2_text' => 'كيف نعمل',
                        'btn2_link' => '#process'
                    ],
                    'services' => [
                        'subtitle' => 'خبرتنا',
                        'title' => 'دعم شامل للتقديم',
                        'description' => 'نقدم خدمات من البداية إلى النهاية لزيادة فرص قبولك.',
                        'items' => [
                            [
                                'title' => 'اختيار الجامعة',
                                'description' => 'نساعدك على تحديد أفضل الجامعات بناءً على ملفك الأكاديمي، وأهدافك المهنية، وميزانيتك.',
                                'icon' => 'faUniversity'
                            ],
                            [
                                'title' => 'تحرير رسالة الدوافع وتوصيات',
                                'description' => 'يقوم خبراؤنا بتنقيح رسالة الدوافع ورسائل التوصية الخاصة بك لتعزيز قوة طلبك.',
                                'icon' => 'faFilePen'
                            ],
                            [
                                'title' => 'إدارة التقديم',
                                'description' => 'نتعامل مع عملية التقديم بأكملها، لضمان ملء كل نموذج بشكل صحيح وتقديمه في الوقت المحدد.',
                                'icon' => 'faCheckDouble'
                            ],
                            [
                                'title' => 'التحضير للمقابلة',
                                'description' => 'مقابلات تجريبية وتدريب مخصص لمساعدتك في اجتياز مقابلات الجامعة أو التأشيرة.',
                                'icon' => 'faUserTie'
                            ],
                            [
                                'title' => 'المساعدة في التأشيرة',
                                'description' => 'إرشادات كاملة حول وثائق التأشيرة، للإثبات المالي، واستراتيجيات المقابلة.',
                                'icon' => 'faPassport'
                            ],
                            [
                                'title' => 'تخطيط الجدول الزمني',
                                'description' => 'نقوم بإنشاء جدول زمني مخصص للتأكد من التزامك بجميع المواعيد النهائية دون توتر.',
                                'icon' => 'faCalendarCheck'
                            ]
                        ]
                    ],
                    'process' => [
                        'subtitle' => 'العملية',
                        'title' => 'رحلتك إلى القبول',
                        'description' => 'طريقة منظمة مصممة لإبقائك مرتباً وسابقاً للمواعيد النهائية.',
                        'steps' => [
                            [
                                'number' => '01',
                                'title' => 'تقييم الملف الشخصي',
                                'description' => 'نحلل خلفيتك الأكاديمية وطموحاتك المهنية.'
                            ],
                            [
                                'number' => '02',
                                'title' => 'ترشيح الجامعات',
                                'description' => 'اختيار المزيج الصحيح من الجامعات الطموحة والمتناسبة والآمنة.'
                            ],
                            [
                                'number' => '03',
                                'title' => 'تحضير الوثائق',
                                'description' => 'صياغة وتنقيح رسائل الدوافع، ورسائل التوصية، والسيرة الذاتية.'
                            ],
                            [
                                'number' => '04',
                                'title' => 'تقديم الطلب',
                                'description' => 'التقديم في الوقت المناسب للجامعات التي اخترتها.'
                            ]
                        ],
                        'stat' => [
                            'value' => '98%',
                            'label' => 'نسبة النجاح',
                            'description' => 'يضمن طلابنا باستمرار عروضاً من أفضل 3 خيارات جامعية بفضل نهجنا الاستراتيجي.'
                        ]
                    ],
                    'cta' => [
                        'title' => 'مستعد لبدء رحلتك؟',
                        'description' => 'احجز استشارة مجانية مع خبرائنا واتخذ الخطوة الأولى نحو جامعة أحلامك.',
                        'btn_text' => 'احجز استشارة مجانية',
                        'btn_link' => '/apply-now'
                    ]
                ],
            ],
            [
                'slug' => 'accommodation',
                'title' => 'Student Accommodation',
                'content' => [
                    'hero' => [
                        'badge' => 'Housing',
                        'title' => 'Find Your Perfect Home',
                        'description' => 'Browse our verified student accommodation options. Safe, comfortable, and affordable.',
                        'image' => ''
                    ],
                    'why_choose_us' => [
                        'title' => 'Why Our Housing?',
                        'items' => [
                            ['title' => 'Verified Listings', 'description' => 'All properties are personally checked.'],
                            ['title' => 'No Hidden Fees', 'description' => 'Transparent pricing policies.'],
                            ['title' => 'Student Centric', 'description' => 'Locations near universities and transit.'],
                            ['title' => '24/7 Support', 'description' => 'Assistance whenever you need it.']
                        ]
                    ],
                    'booking_process' => [
                        'title' => 'Easy Booking',
                        'steps' => [
                            ['number' => '1', 'title' => 'Search', 'description' => 'Filter by city and university.'],
                            ['number' => '2', 'title' => 'Select', 'description' => 'Choose your preferred room type.'],
                            ['number' => '3', 'title' => 'Reserve', 'description' => 'Pay a deposit to secure your room.']
                        ]
                    ],
                    'cta' => [
                        'title' => 'Need Help?',
                        'description' => 'Our accommodation team can help you find the right place.',
                        'button_text' => 'Contact Housing Team',
                        'button_link' => '/contact'
                    ]
                ],
                'ar_content' => [
                    'hero' => [
                        'badge' => 'السكن',
                        'title' => 'اعثر على مسكنك المثالي',
                        'description' => 'تصفح خيارات السكن الطلابي المعتمدة لدينا. آمنة ومريحة وبأسعار معقولة.',
                        'image' => ''
                    ],
                    'why_choose_us' => [
                        'title' => 'لماذا سكننا؟',
                        'items' => [
                            ['title' => 'قوائم معتمدة', 'description' => 'يتم فحص جميع العقارات شخصيًا.'],
                            ['title' => 'لا توجد رسوم خفية', 'description' => 'سياسات تسعير شفافة.'],
                            ['title' => 'مركز على الطلاب', 'description' => 'مواقع قريبة من الجامعات ووسائل النقل.'],
                            ['title' => 'دعم على مدار الساعة', 'description' => 'مساعدة متى احتجت إليها.']
                        ]
                    ],
                    'booking_process' => [
                        'title' => 'حجز سهل',
                        'steps' => [
                            ['number' => '1', 'title' => 'ابحث', 'description' => 'قم بالتصفية حسب المدينة والجامعة.'],
                            ['number' => '2', 'title' => 'اختر', 'description' => 'اختر نوع الغرفة المفضل لديك.'],
                            ['number' => '3', 'title' => 'احجز', 'description' => 'ادفع عربوناً لتأمين غرفتك.']
                        ]
                    ],
                    'cta' => [
                        'title' => 'تحتاج إلى مساعدة؟',
                        'description' => 'فريق السكن لدينا يمكنه مساعدتك في العثور على المكان المناسب.',
                        'button_text' => 'اتصل بفريق السكن',
                        'button_link' => '/contact'
                    ]
                ],
            ],
            [
                'slug' => 'destinations',
                'title' => 'Study Destinations',
                'ar_title' => 'وجهات الدراسة',
                'content' => [
                    'hero_title' => 'Study Abroad Destinations',
                    'hero_subtitle' => 'Choose from top-ranked universities and colleges in the world\'s most student-friendly countries.',
                    'filter_all' => 'All',
                    'filter_europe' => 'Europe',
                    'filter_na' => 'North America',
                    'filter_oceania' => 'Oceania',
                    'filter_budget' => 'Low Tuition',
                    'search_placeholder' => 'Search destinations...',
                    'no_match_title' => 'No matches found',
                    'no_match_text' => 'We couldn\'t find any destinations matching your search.',
                    'reset_filters' => 'Reset All Filters',
                    'showing' => 'Showing',
                    'destinations_word' => 'Destinations',
                    'cta' => [
                        'title' => 'Ready to Start Your Journey?',
                        'description' => 'Let our experts guide you to the right destination.',
                        'button_text' => 'Get Free Consultation',
                        'button_link' => '/contact',
                    ],
                ],
                'ar_content' => [
                    'hero_title' => 'وجهات الدراسة في الخارج',
                    'hero_subtitle' => 'اختر من بين أفضل الجامعات والكليات في أكثر الدول الملائمة للطلاب حول العالم.',
                    'filter_all' => 'الكل',
                    'filter_europe' => 'أوروبا',
                    'filter_na' => 'أمريكا الشمالية',
                    'filter_oceania' => 'أوقيانوسيا',
                    'filter_budget' => 'رسوم منخفضة',
                    'search_placeholder' => 'ابحث عن وجهة...',
                    'no_match_title' => 'لا توجد نتائج',
                    'no_match_text' => 'لم نتمكن من العثور على أي وجهة تطابق بحثك.',
                    'reset_filters' => 'إعادة تعيين الفلاتر',
                    'showing' => 'جارٍ عرض',
                    'destinations_word' => 'وجهة',
                    'cta' => [
                        'title' => 'هل أنت مستعد لبدء رحلتك؟',
                        'description' => 'دع خبراءنا يرشدونك إلى الوجهة المناسبة.',
                        'button_text' => 'احصل على استشارة مجانية',
                        'button_link' => '/contact',
                    ],
                ],
            ],
        ];

        foreach ($pages as $page) {
            $payload = json_encode($page['content'], JSON_UNESCAPED_UNICODE);
            $arPayload = json_encode($page['ar_content'] ?? $page['content'], JSON_UNESCAPED_UNICODE);
            CmsPage::updateOrCreate(
                ['app' => 'university', 'slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'ar_title' => $page['ar_title'] ?? null,
                    'content' => $payload,
                    'ar_content' => $arPayload,
                    'is_active' => true,
                ]
            );
        }
    }
}
