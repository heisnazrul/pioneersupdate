import { notFound } from 'next/navigation';
import Link from 'next/link';
import { getUniversityBySlug } from '@/services/publicData';
import ConsultationSidebar from '@/components/course/ConsultationSidebar';
import FadeIn from '@/components/FadeIn';
import { getLang } from '@/lib/getLang';

export default async function UniversityDetailPage({ params }: { params: Promise<{ slug: string }> }) {
    const { slug } = await params;
    const lang = await getLang();
    const isAr = lang === 'ar';
    const university = await getUniversityBySlug(slug, lang);

    if (!university) {
        notFound();
    }

    const T = {
        partnerUniversity: isAr ? 'جامعة شريكة' : 'Partner University',
        rankingNA: isAr ? 'التصنيف: غير متوفر' : 'Ranking: N/A',
        aboutTitle: isAr ? `عن ${university.name}` : `About ${university.name}`,
        aboutDesc: isAr
            ? `${university.name} مؤسسة أكاديمية عالمية المستوى تُعرف بتميزها الأكاديمي وحيوية حياتها الجامعية. تقع في ${university.location}، وتقدم مجموعة متنوعة من البرامج لتأهيل الطلاب لمسيرات مهنية عالمية. بفضل مرافقها المتطورة والتزامها بالبحث العلمي، تجذب طلابًا من أكثر من 100 دولة.`
            : `${university.name} is a world-class institution known for its academic excellence and vibrant campus life. Located in ${university.location}, it offers a diverse range of programs designed to prepare students for global careers. With state-of-the-art facilities and a commitment to research, it attracts students from over 100 countries.`,
        publicResearch: isAr ? 'جامعة بحثية حكومية' : 'Public Research University',
        established: isAr ? 'تأسست في 1800م' : 'Established 1800s',
        popularCourses: isAr ? 'الدورات الشائعة' : 'Popular Courses',
        viewDetails: isAr ? 'عرض التفاصيل' : 'View Details →',
        noCourses: isAr ? 'لا توجد دورات محددة لهذه الجامعة بعد.' : 'No specific courses listed yet for this university.',
        browseAll: isAr ? 'تصفح جميع الدورات' : 'Browse all courses',
        intakesTitle: isAr ? 'مواعيد القبول والمواعيد النهائية' : 'Intakes & Deadlines',
        fallSep: isAr ? 'الخريف (سبتمبر)' : 'Fall (September)',
        fallDeadline: isAr ? 'الموعد النهائي: 15 يوليو' : 'Deadline: July 15',
        springJan: isAr ? 'الربيع (يناير)' : 'Spring (January)',
        springDeadline: isAr ? 'الموعد النهائي: 1 نوفمبر' : 'Deadline: November 01',
        summerMay: isAr ? 'الصيف (مايو)' : 'Summer (May)',
        limitedPrograms: isAr ? 'برامج محدودة' : 'Limited Programs',
        ctaTitle: isAr ? `هل تريد الدراسة في ${university.name}؟` : `Want to study at ${university.name}?`,
        ctaDesc: isAr
            ? 'يمكن لمستشارينا المتخصصين مساعدتك في عملية القبول، والتأشيرة، وطلبات المنح الدراسية.'
            : 'Our expert counselors can help you with the admission process, visa guidance, and scholarship applications.',
        applyNow: isAr ? 'تقدّم الآن' : 'Apply Now',
        qsRanking: isAr ? 'تصنيف QS' : 'QS Ranking',
        theRanking: isAr ? 'تصنيف THE' : 'THE Ranking',
        shanghaiRanking: isAr ? 'تصنيف شنغهاي' : 'Shanghai Ranking',
    };

    return (
        <div className="min-h-screen bg-background pb-24" dir={isAr ? 'rtl' : 'ltr'}>
            {/* 1. Hero Section */}
            <div className="relative h-[400px] w-full bg-primary overflow-hidden">
                <div className="absolute inset-0 bg-black/60 z-10"></div>
                <div className="absolute inset-0 z-0 bg-cover bg-center" style={{ backgroundImage: `url(${university.coverImageUrl || '/fallback_cover.jpg'})` }}></div>

                <div className="container max-w-6xl mx-auto px-4 relative z-20 h-full flex flex-col justify-center text-white">
                    <FadeIn>
                        <span className="inline-block bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4 border border-white/30">
                            {T.partnerUniversity}
                        </span>

                        <div className="flex flex-col md:flex-row items-start md:items-center gap-4 mb-4">
                            {university.logoUrl && (
                                <div className="p-3 rounded-xl shadow-lg flex-shrink-0">
                                    <img src={university.logoUrl} alt={`${university.name} Logo`} className="w-auto h-24" />
                                </div>
                            )}
                            <h1 className="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight">{university.name}</h1>
                        </div>

                        <div className="flex flex-wrap items-center gap-4 text-sm font-medium opacity-90">
                            <div className="flex items-center gap-2">
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {university.location}
                            </div>
                            {university.qsRanking && <span className="hidden md:inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/30 text-xs font-bold">🎓 QS #{university.qsRanking}</span>}
                            {university.theRanking && <span className="hidden md:inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/30 text-xs font-bold">🏫 THE #{university.theRanking}</span>}
                            {university.shanghaiRanking && <span className="hidden md:inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/30 text-xs font-bold">📊 Shanghai #{university.shanghaiRanking}</span>}
                            {!university.qsRanking && !university.theRanking && !university.shanghaiRanking && (
                                <span className="hidden md:inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/30 text-xs font-bold">{T.rankingNA}</span>
                            )}
                        </div>
                    </FadeIn>
                </div>
            </div>

            <div className="container max-w-6xl mx-auto px-4 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

                    {/* Main Content */}
                    <div className="lg:col-span-2">
                        {/* Rankings Row */}
                        <FadeIn className="grid grid-cols-3 gap-4 mb-12">
                            <div className="bg-white p-5 rounded-xl border border-gray-100 text-center shadow-sm">
                                <span className="block text-2xl font-extrabold text-primary mb-1">{university.qsRanking ? `#${university.qsRanking}` : 'N/A'}</span>
                                <span className="text-xs text-muted font-bold uppercase tracking-wide">{T.qsRanking}</span>
                            </div>
                            <div className="bg-white p-5 rounded-xl border border-gray-100 text-center shadow-sm">
                                <span className="block text-2xl font-extrabold text-secondary mb-1">{university.theRanking ? `#${university.theRanking}` : 'N/A'}</span>
                                <span className="text-xs text-muted font-bold uppercase tracking-wide">{T.theRanking}</span>
                            </div>
                            <div className="bg-white p-5 rounded-xl border border-gray-100 text-center shadow-sm">
                                <span className="block text-2xl font-extrabold text-[#C62828] mb-1">{university.shanghaiRanking ? `#${university.shanghaiRanking}` : 'N/A'}</span>
                                <span className="text-xs text-muted font-bold uppercase tracking-wide">{T.shanghaiRanking}</span>
                            </div>
                        </FadeIn>

                        {/* About */}
                        <FadeIn delay={0.1} className="mb-12">
                            <h2 className="text-2xl font-bold text-primary mb-4">{T.aboutTitle}</h2>
                            <p className="text-muted leading-relaxed mb-4">{T.aboutDesc}</p>
                            <div className="flex gap-2 flex-wrap">
                                <span className="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-sm font-medium">{T.publicResearch}</span>
                                <span className="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-sm font-medium">{T.established}</span>
                            </div>
                        </FadeIn>

                        {/* Popular Courses */}
                        <FadeIn delay={0.3} className="mb-12">
                            <h2 className="text-2xl font-bold text-primary mb-6">{T.popularCourses}</h2>
                            {university.coursesCatalog && university.coursesCatalog.length > 0 ? (
                                <div className="space-y-4">
                                    {university.coursesCatalog.slice(0, 12).map((cat) => (
                                        <div key={cat.catalogId} className="bg-white border border-gray-100 rounded-xl p-5 hover:shadow-md hover:border-secondary/30 transition-all group">
                                            <div className="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                                                <div className="flex-1">
                                                    <h3 className="font-bold text-lg text-foreground group-hover:text-primary transition-colors mb-1">{cat.name}</h3>
                                                    {cat.discipline && <p className="text-xs text-secondary font-semibold uppercase tracking-wide mb-3">{cat.discipline}</p>}
                                                    <div className="flex flex-wrap gap-2">
                                                        {cat.levels.map((lv) => (
                                                            <span key={lv.courseId} className="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-secondary text-xs font-semibold rounded-full border border-blue-100">
                                                                {lv.levelName}
                                                                {lv.duration && <span className="text-gray-400 font-normal">· {lv.duration}</span>}
                                                            </span>
                                                        ))}
                                                    </div>
                                                </div>
                                                {cat.slug && (
                                                    <a href={`/courses/${cat.slug}`} className="shrink-0 text-secondary font-bold text-sm hover:underline whitespace-nowrap">
                                                        {T.viewDetails}
                                                    </a>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : university.courses && university.courses.length > 0 ? (
                                <div className="space-y-4">
                                    {(university.courses as any[]).map((course: any) => (
                                        <a href={`/courses/${course.slug}`} key={course.id} className="block bg-white border border-gray-100 rounded-lg p-5 hover:shadow-md hover:border-secondary/30 transition-all group">
                                            <div className="flex justify-between items-center">
                                                <div>
                                                    <h3 className="font-bold text-lg text-foreground group-hover:text-primary transition-colors">{course.name}</h3>
                                                    <p className="text-sm text-muted">{course.level} • {course.duration}</p>
                                                </div>
                                                <span className="block text-secondary font-bold text-sm">{T.viewDetails}</span>
                                            </div>
                                        </a>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-8 bg-gray-50 rounded-xl text-center border border-dashed border-gray-200">
                                    <p className="text-muted">{T.noCourses}</p>
                                    <a href="/search/courses" className="text-secondary font-bold hover:underline mt-2 inline-block">{T.browseAll}</a>
                                </div>
                            )}
                        </FadeIn>
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1 space-y-8">
                        <ConsultationSidebar
                            universityName={university.name}
                            universityLogo={university.logoUrl}
                            coursesCatalog={university.coursesCatalog}
                            lang={lang}
                        />

                        {/* Intakes & Deadlines */}
                        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 className="text-lg font-bold text-primary mb-4">{T.intakesTitle}</h3>
                            <div className="space-y-4">
                                <div className="flex items-start gap-4 p-3 rounded-lg bg-blue-50 border border-blue-100">
                                    <div className="w-2 h-2 mt-2 rounded-full bg-primary flex-shrink-0"></div>
                                    <div>
                                        <span className="block text-sm font-bold text-gray-800">{T.fallSep}</span>
                                        <span className="text-xs text-red-500 font-medium">{T.fallDeadline}</span>
                                    </div>
                                </div>
                                <div className="flex items-start gap-4 p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                                    <div className="w-2 h-2 mt-2 rounded-full bg-secondary flex-shrink-0"></div>
                                    <div>
                                        <span className="block text-sm font-bold text-gray-800">{T.springJan}</span>
                                        <span className="text-xs text-emerald-600 font-medium">{T.springDeadline}</span>
                                    </div>
                                </div>
                                <div className="flex items-start gap-4 p-3 rounded-lg bg-gray-50 border border-gray-100 opacity-75">
                                    <div className="w-2 h-2 mt-2 rounded-full bg-gray-400 flex-shrink-0"></div>
                                    <div>
                                        <span className="block text-sm font-bold text-gray-600">{T.summerMay}</span>
                                        <span className="text-xs text-gray-500 font-medium">{T.limitedPrograms}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom CTA */}
            <section className="relative py-20 text-center text-white overflow-hidden -mb-24">
                {university.coverImageUrl ? (
                    <div className="absolute inset-0 z-0 bg-cover bg-center" style={{ backgroundImage: `url(${university.coverImageUrl})` }}></div>
                ) : (
                    <div className="absolute inset-0 z-0 bg-gray-900"></div>
                )}
                <div className="absolute inset-0 bg-black/80 z-10"></div>
                <div className="container max-w-4xl mx-auto px-4 relative z-20">
                    <h2 className="text-3xl font-extrabold mb-4">{T.ctaTitle}</h2>
                    <p className="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">{T.ctaDesc}</p>
                    <Link href="/apply-now" className="bg-red-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-red-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 active:scale-95 inline-block">
                        {T.applyNow}
                    </Link>
                </div>
            </section>
        </div>
    );
}
