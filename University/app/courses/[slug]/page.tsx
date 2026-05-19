
import { notFound } from 'next/navigation';
import Link from 'next/link';
import { getCourseBySlug, searchCourses } from '@/services/publicData';
import ConsultationSidebar from '@/components/course/ConsultationSidebar';
import RelatedCourses from '@/components/course/RelatedCourses';
import FadeIn from '@/components/FadeIn';
import { getLang } from '@/lib/getLang';

export default async function CourseDetailPage({ params }: { params: Promise<{ slug: string }> }) {
    const { slug } = await params;
    const lang = await getLang();
    const isAr = lang === 'ar';
    const course = await getCourseBySlug(slug, lang);

    if (!course) {
        notFound();
    }

    // Mock "Related" logic: same level
    const relatedResponse = await searchCourses({ level: course.level, pageSize: 3 });
    const relatedCourses = relatedResponse.items
        .filter(c => c.id !== course.id)
        .slice(0, 2);

    const T = {
        home: isAr ? 'الرئيسية' : 'Home',
        courses: isAr ? 'الدورات' : 'Courses',
        onCampus: isAr ? 'حرم جامعي' : 'On-Campus',
        duration: isAr ? 'المدة' : 'Duration',
        intake: isAr ? 'القبول' : 'Intake',
        tuition: isAr ? 'الرسوم (تقريباً)' : 'Tuition (Est.)',
        perYear: isAr ? 'سنوياً' : 'per year',
        contactUs: isAr ? 'تواصل معنا' : 'Contact Us',
        deadline: isAr ? 'آخر موعد' : 'Deadline',
        open: isAr ? 'مفتوح' : 'Open',
        various: isAr ? 'متعدد' : 'Various',
        courseOverview: isAr ? 'نظرة عامة على الدورة' : 'Course Overview',
        entryRequirements: isAr ? 'شروط القبول' : 'Entry Requirements',
        academicScore: isAr ? 'المعدل الأكاديمي' : 'Academic Score',
        englishProficiency: isAr ? 'الكفاءة في اللغة الإنجليزية' : 'English Proficiency',
        workExperience: isAr ? 'الخبرة العملية' : 'Work Experience',
        workExpDetail: isAr ? 'غير مطلوبة، لكن التدريب ذو الصلة يُعدّ ميزة.' : 'Not required, but relevant internships are a plus.',
        notAvailable: isAr ? 'غير متوفر' : 'Not Available',
        howToApply: isAr ? 'كيفية التقديم' : 'How to Apply',
        checkEligibility: isAr ? 'التحقق من الأهلية' : 'Check Eligibility',
        checkEligibilityDesc: isAr ? 'أرسل ملفك لمستشارينا للحصول على تقييم مجاني.' : 'Submit your profile to our counselors for a free evaluation.',
        prepareDocs: isAr ? 'إعداد الوثائق' : 'Prepare Documents',
        prepareDocsDesc: isAr ? 'جمِّع كشوف الدرجات وخطاب النية وخطابات التوصية والوثائق المالية.' : 'Gather transcripts, SOP, LORs, and financial documents.',
        submitApp: isAr ? 'تقديم الطلب' : 'Submit Application',
        submitAppDesc: isAr ? `نراجع طلبك ونرسله إلى ${course.university}.` : `We review and submit your application to ${course.university}.`,
        didYouKnow: isAr ? 'هل تعلم؟' : 'Did you know?',
        employability: isAr ? `خريجو هذه الدورة يحققون نسبة توظيف تبلغ` : `Graduates from this course have a`,
        employabilityRate: isAr ? '95% معدل توظيف' : '95% employability rate',
        employabilityEnd: isAr ? 'خلال 6 أشهر من التخرج.' : 'within 6 months of graduation.',
    };

    return (
        <div className="min-h-screen bg-background pb-24" dir={isAr ? 'rtl' : 'ltr'}>
            {/* Breadcrumbs */}
            <div className="bg-white border-b border-gray-200">
                <div className="container max-w-6xl mx-auto px-4 py-4 text-sm text-muted">
                    <Link href="/" className="hover:text-primary transition-colors">{T.home}</Link>
                    <span className="mx-2">/</span>
                    <Link href="/search/courses" className="hover:text-primary transition-colors">{T.courses}</Link>
                    <span className="mx-2">/</span>
                    <span className="font-semibold text-primary">{course.name}</span>
                </div>
            </div>

            <div className="container max-w-6xl mx-auto px-4 py-8 md:py-12">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

                    {/* Main Content (Left Col) */}
                    <div className="lg:col-span-2">
                        {/* Header */}
                        <FadeIn className="mb-8">
                            <div className="flex items-center gap-4 mb-4">
                                {course.universityLogo && (
                                    <div className="w-auto h-24 rounded-lg bg-white border border-gray-100 p-2 flex items-center justify-center shadow-sm">
                                        <img src={course.universityLogo} alt={course.university} className="w-full h-full object-contain" />
                                    </div>
                                )}
                                <div>
                                    <div className="flex gap-2 mb-2">
                                        <span className="bg-blue-50 text-secondary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">{course.level}</span>
                                        <span className="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">{T.onCampus}</span>
                                    </div>
                                    <h1 className="text-3xl md:text-4xl font-extrabold text-primary leading-tight">{course.name}</h1>
                                </div>
                            </div>

                            <div className="flex items-center gap-2 text-lg text-gray-600 font-medium">
                                <svg className="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span>{course.university}</span>
                                <span className="text-gray-300 mx-2">|</span>
                                <span className="text-muted">{course.location}</span>
                            </div>
                        </FadeIn>

                        {/* Key Facts Grid */}
                        <FadeIn delay={0.1} className="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm grid grid-cols-2 gap-y-8 gap-x-4 md:grid-cols-4 mb-8">
                            <div>
                                <span className="block text-xs font-bold text-gray-400 uppercase mb-1">{T.duration}</span>
                                <span className="block text-lg font-bold text-foreground">{course.duration}</span>
                            </div>
                            <div>
                                <span className="block text-xs font-bold text-gray-400 uppercase mb-1">{T.intake}</span>
                                <span className="block text-sm font-bold text-foreground leading-snug">{course.intake?.join(', ') || T.various}</span>
                            </div>
                            <div>
                                <span className="block text-xs font-bold text-gray-400 uppercase mb-1">{T.tuition}</span>
                                {course.tuition ? (
                                    <>
                                        <span className="block text-lg font-bold text-foreground">
                                            {course.currency || ''} {Number(course.tuition).toLocaleString()}
                                        </span>
                                        <span className="text-xs text-muted">{T.perYear}</span>
                                    </>
                                ) : (
                                    <span className="block text-base font-bold text-secondary">{T.contactUs}</span>
                                )}
                            </div>
                            <div>
                                <span className="block text-xs font-bold text-gray-400 uppercase mb-1">{T.deadline}</span>
                                <span className="block text-lg font-bold text-foreground text-red-500">
                                    {course.applicationDeadline
                                        ? new Date(course.applicationDeadline).toLocaleDateString(isAr ? 'ar-EG' : 'en-GB', { day: 'numeric', month: 'short' })
                                        : T.open}
                                </span>
                            </div>
                        </FadeIn>

                        {/* Overview Section */}
                        {course.description && (
                            <FadeIn delay={0.15} className="mb-12">
                                <h3 className="text-xl font-bold text-primary mb-4">{T.courseOverview}</h3>
                                <div className="prose prose-blue max-w-none text-gray-600 bg-white p-6 rounded-xl border border-gray-100">
                                    <p>{course.description}</p>
                                </div>
                            </FadeIn>
                        )}

                        {/* Requirements */}
                        <FadeIn delay={0.2} className="mb-12">
                            <h2 className="text-2xl font-bold text-primary mb-6">{T.entryRequirements}</h2>
                            <ul className="space-y-4">
                                <li className="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100">
                                    <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-secondary shrink-0 font-bold">1</div>
                                    <div>
                                        <h4 className="font-bold text-foreground">{T.academicScore}</h4>
                                        <p className="text-muted text-sm">{course.degreeRequirement || T.notAvailable}</p>
                                    </div>
                                </li>
                                <li className="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100">
                                    <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-secondary shrink-0 font-bold">2</div>
                                    <div>
                                        <h4 className="font-bold text-foreground">{T.englishProficiency}</h4>
                                        <p className="text-muted text-sm">{course.languageRequirement || T.notAvailable}</p>
                                    </div>
                                </li>
                                <li className="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100">
                                    <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-secondary shrink-0 font-bold">3</div>
                                    <div>
                                        <h4 className="font-bold text-foreground">{T.workExperience}</h4>
                                        <p className="text-muted text-sm">{T.workExpDetail}</p>
                                    </div>
                                </li>
                            </ul>
                        </FadeIn>

                        {/* How to Apply */}
                        <FadeIn delay={0.3}>
                            <h2 className="text-2xl font-bold text-primary mb-6">{T.howToApply}</h2>
                            <div className="relative border-l-2 border-dashed border-gray-200 ml-4 space-y-8 pb-4">
                                <div className="pl-8 relative">
                                    <div className="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-secondary ring-4 ring-white"></div>
                                    <h4 className="font-bold text-lg text-foreground mb-2">{T.checkEligibility}</h4>
                                    <p className="text-muted">{T.checkEligibilityDesc}</p>
                                </div>
                                <div className="pl-8 relative">
                                    <div className="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-gray-300 ring-4 ring-white"></div>
                                    <h4 className="font-bold text-lg text-foreground mb-2">{T.prepareDocs}</h4>
                                    <p className="text-muted">{T.prepareDocsDesc}</p>
                                </div>
                                <div className="pl-8 relative">
                                    <div className="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-gray-300 ring-4 ring-white"></div>
                                    <h4 className="font-bold text-lg text-foreground mb-2">{T.submitApp}</h4>
                                    <p className="text-muted">{T.submitAppDesc}</p>
                                </div>
                            </div>
                        </FadeIn>
                    </div>

                    {/* Sidebar (Right Col) */}
                    <div className="lg:col-span-1">
                        <ConsultationSidebar
                            courseId={course.id}
                            courseName={course.name}
                            universityName={course.university}
                            universityLogo={course.universityLogo}
                            intakes={course.intake}
                            lang={lang}
                        />

                        {/* Extra Sidebar Info */}
                        <div className="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-100">
                            <h4 className="font-bold text-primary mb-2 flex items-center gap-2">
                                <svg className="w-5 h-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {T.didYouKnow}
                            </h4>
                            <p className="text-sm text-blue-900 leading-relaxed">
                                {T.employability} <span className="font-bold">{T.employabilityRate}</span> {T.employabilityEnd}
                            </p>
                        </div>

                        {/* Related Courses moved to sidebar */}
                        <RelatedCourses courses={relatedCourses} lang={lang} />
                    </div>

                </div>
            </div>
        </div>
    );
}
