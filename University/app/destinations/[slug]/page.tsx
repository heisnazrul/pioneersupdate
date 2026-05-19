import { getDestinationBySlug } from '@/lib/data';
import Link from 'next/link';
import Image from 'next/image';
import { notFound } from 'next/navigation';
import ConsultationSidebar from '@/components/course/ConsultationSidebar';
import FadeIn from '@/components/FadeIn';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';
import { searchUniversities, searchCourses } from '@/services/publicData';

interface Props {
    params: Promise<{ slug: string }>;
}

export async function generateMetadata(props: Props) {
    const params = await props.params;
    const destination = await getDestinationBySlug(params.slug);
    if (!destination) return { title: 'Not Found' };
    return {
        title: `Study in ${destination.name} | Pioneers Admissions`,
        description: destination.shortPitch,
    };
}

export default async function DestinationDetailPage(props: Props) {
    const params = await props.params;
    const lang = await getLang();
    const isAr = lang === 'ar';
    const destination = await getDestinationBySlug(params.slug, lang);

    if (!destination) {
        notFound();
    }

    // Fetch universities from the real API (10+) filtered by destination/country
    const uniResponse = await searchUniversities({ destination: destination.name, pageSize: 12 }, lang);
    const apiUniversities = uniResponse?.items ?? [];

    // Use destination.topUniversities as a fallback if API returns nothing
    const displayUniversities = apiUniversities.length > 0
        ? apiUniversities
        : (destination.topUniversities ?? []);

    // FETCH MASTERS COURSES FROM THE FIRST UNIVERSITY VIA BACKEND API
    let rawCourses: any[] = [];
    if (displayUniversities.length > 0) {
        const firstUni = displayUniversities[0];

        // Query the backend directly for Masters courses at this specific university
        const coursesRes = await searchCourses({
            destination: destination.name,
            university: firstUni.slug,
            level: 'master',
            pageSize: 50 // Fetch enough to deduplicate by name
        }, lang);

        if (coursesRes && coursesRes.items) {
            rawCourses = coursesRes.items;
        }
    }

    // Deduplicate: ensure every course shown is a DIFFERENT course name.
    const seenNames = new Set<string>();
    const availableCourses = rawCourses.filter((c: any) => {
        const nameKey = (c.name || '').trim().toLowerCase();

        // Must have a name, and cannot have been seen before
        if (!nameKey || seenNames.has(nameKey)) {
            return false;
        }

        seenNames.add(nameKey);
        return true;
    }).slice(0, 10); // Cap at 10 items for the UI

    // Translation object
    const T = {
        topDestination: t('destinations.topDestination', lang),
        tuitionRange: t('destinations.tuitionRange', lang),
        visaTime: t('destinations.visaTime', lang),
        workRights: t('destinations.workRights', lang),
        scholarships: t('destinations.scholarships', lang),
        whyStudy: t('destinations.whyStudy', lang),
        topUniversities: t('destinations.topUniversities', lang),
        viewAll: t('common.viewAll', lang),
        popularPrograms: t('destinations.popularPrograms', lang),
        readyToApply: t('common.readyToApply', lang),
        applyNow: t('common.applyNow', lang),
        studyStart: isAr ? `ابدأ رحلتك للدراسة في ${destination.name} اليوم.` : `Start your journey to study in ${destination.name} today.`,
        requirements: isAr ? 'المتطلبات' : 'Requirements',
        needRoadmap: isAr ? 'هل تحتاج خطة تفصيلية؟' : 'Need a detailed roadmap?',
        downloadGuideDesc: isAr ? `حمّل دليلنا المجاني 2024 للدراسة في ${destination.name}.` : `Download our free 2024 Guide to Studying in ${destination.name}.`,
        downloadPdf: isAr ? 'تحميل PDF ↓' : 'Download PDF ↓',
        updatedFor: isAr ? 'محدّث لعام' : 'Updated for',
        quickFormat: isAr ? 'معلومات سريعة' : 'Quick Format',
        uniComingSoon: isAr ? 'قائمة الجامعات قريباً.' : 'Universities list coming soon.',
        programsComingSoon: isAr ? 'البرامج المتاحة لهذه الوجهة ستكون متاحة قريباً.' : 'Programs for this destination are coming soon.',
        contactUs: isAr ? 'تواصل معنا لمزيد من المعلومات' : 'Contact us for more info',
    };

    return (
        <div className="pb-24 bg-background" dir={isAr ? 'rtl' : 'ltr'}>
            {/* Hero */}
            <div className="relative h-[450px] bg-primary flex flex-col items-center justify-center text-center text-white overflow-hidden">
                <div className="absolute inset-0 bg-[#0B2A4A]/60 z-10"></div>
                <Image
                    src={destination.imageUrl}
                    alt={destination.name}
                    fill
                    unoptimized
                    sizes="100vw"
                    className="object-cover"
                    priority
                />
                <FadeIn className="relative z-20 max-w-4xl px-4 mt-16">
                    <span className="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider mb-4 border border-white/30">
                        {destination.region || T.topDestination}
                    </span>
                    <h1 className="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight drop-shadow-md">
                        {isAr ? 'الدراسة في ' : 'Study in '}{destination.name}
                    </h1>
                    <p className="text-xl md:text-2xl font-medium text-white/90 max-w-2xl mx-auto mb-8 drop-shadow-sm">{destination.shortPitch}</p>
                </FadeIn>
            </div>

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 -mt-16 z-30 relative">
                <FadeIn delay={0.1} className="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                    <div className="text-center p-4 border-r border-gray-100 last:border-0 border-b md:border-b-0">
                        <strong className="block text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">{T.tuitionRange}</strong>
                        <span className="text-lg md:text-xl font-bold text-primary block">{destination.tuitionRange}</span>
                    </div>
                    <div className="text-center p-4 border-r border-gray-100 last:border-0 border-b md:border-b-0">
                        <strong className="block text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">{T.visaTime}</strong>
                        <span className="text-lg md:text-xl font-bold text-secondary block">{destination.visaTimeline}</span>
                    </div>
                    <div className="text-center p-4 border-r border-gray-100 last:border-0">
                        <strong className="block text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">{T.workRights}</strong>
                        <span className="text-lg md:text-xl font-bold text-primary block">{destination.workRights}</span>
                    </div>
                    <div className="text-center p-4">
                        <strong className="block text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">{T.scholarships}</strong>
                        <span className="block text-green-600 font-bold text-sm bg-green-50 px-2 py-1 rounded inline-block">{destination.scholarships}</span>
                    </div>
                </FadeIn>
            </div>

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 pt-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
                {/* Main Content */}
                <div className="lg:col-span-2">

                    {/* About Text */}
                    <FadeIn delay={0.2} className="mb-16">
                        <h2 className="text-3xl font-bold text-primary mb-6">{T.whyStudy} {destination.name}?</h2>
                        <p className="text-lg text-muted leading-relaxed">{destination.description}</p>
                    </FadeIn>

                    {/* Top Universities — now showing 12 from the real API */}
                    <FadeIn delay={0.3} className="mb-16">
                        <div className="flex justify-between items-end mb-8">
                            <h2 className="text-2xl font-bold text-primary">{T.topUniversities}</h2>
                            <Link href="/search/universities" className="text-sm font-bold text-secondary hover:underline">{T.viewAll}</Link>
                        </div>
                        {displayUniversities.length > 0 ? (
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {displayUniversities.map((uni: any, idx: number) => (
                                    <Link
                                        href={`/universities/${uni.slug}`}
                                        key={uni.id ?? idx}
                                        className="bg-white border border-gray-200 p-5 rounded-xl flex items-center gap-4 hover:shadow-md transition-all hover:border-secondary/30 group"
                                    >
                                        <div className="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-primary font-bold text-xl group-hover:bg-primary group-hover:text-white transition-colors overflow-hidden relative shrink-0">
                                            {(uni.logoUrl || uni.logo) ? (
                                                <Image
                                                    src={uni.logoUrl || uni.logo}
                                                    alt={uni.name}
                                                    fill
                                                    className="object-contain p-1"
                                                    unoptimized
                                                />
                                            ) : (
                                                uni.name.charAt(0)
                                            )}
                                        </div>
                                        <div>
                                            <div className="font-bold text-foreground text-base group-hover:text-primary transition-colors">{uni.name}</div>
                                            <div className="text-sm text-muted">
                                                {uni.rank ? `#${uni.rank} • ` : ''}{uni.location}
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <p className="text-gray-500 font-medium">{T.uniComingSoon}</p>
                            </div>
                        )}
                    </FadeIn>

                    {/* Popular Programs — 2-col grid */}
                    <FadeIn delay={0.3} className="mb-16">
                        <h2 className="text-2xl font-bold text-primary mb-6">{T.popularPrograms}</h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {(availableCourses.length > 0 ? availableCourses : destination.popularPrograms ?? []).map((prog: any, idx: number) => {
                                // Support ar_name or name_ar fields from the API in Arabic mode
                                const displayName = isAr
                                    ? (prog.ar_name || prog.name_ar || prog.arName || prog.name)
                                    : prog.name;
                                return (
                                    <Link
                                        href={prog.slug ? `/courses/${prog.slug}` : (prog.id ? `/courses/${prog.id}` : '#')}
                                        key={idx}
                                        className="bg-white border border-gray-100 p-4 rounded-xl flex items-center justify-between gap-3 hover:shadow-md hover:border-secondary/30 transition-all group"
                                    >
                                        <div className="flex-1 min-w-0">
                                            <div className="font-bold text-sm text-foreground group-hover:text-primary truncate">
                                                {displayName}
                                            </div>
                                            <div className="text-xs text-gray-400 mt-0.5">
                                                {prog.level}{prog.duration ? ` • ${prog.duration}` : ''}
                                            </div>
                                        </div>
                                        <span className="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-secondary group-hover:text-white transition-all shrink-0 text-xs">→</span>
                                    </Link>
                                );
                            })}
                        </div>
                        {availableCourses.length === 0 && (!destination.popularPrograms || destination.popularPrograms.length === 0) && (
                            <div className="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <p className="text-gray-500 font-medium">{T.programsComingSoon}</p>
                                <Link href="/contact" className="text-sm text-blue-600 font-bold hover:underline mt-2 inline-block">{T.contactUs}</Link>
                            </div>
                        )}
                    </FadeIn>
                </div>

                {/* Sidebar */}
                <div className="lg:col-span-1">
                    {/* Apply Now Card */}
                    <div className="bg-[#135FAE] rounded-xl p-6 text-white text-center mb-8 shadow-lg">
                        <h3 className="font-bold text-xl mb-2">{T.readyToApply}</h3>
                        <p className="text-blue-100 text-sm mb-6">{T.studyStart}</p>
                        <Link href="/apply-now" className="block w-full py-3 bg-white text-[#135FAE] font-bold rounded-lg hover:bg-gray-50 transition-colors">
                            {T.applyNow} →
                        </Link>
                    </div>

                    {/* Requirements */}
                    {destination.requirements && destination.requirements.length > 0 && (
                        <div className="bg-white border border-gray-100 rounded-xl p-6 mb-8 shadow-sm">
                            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span className="w-1 h-6 bg-secondary rounded-full"></span>
                                {T.requirements}
                            </h3>
                            <ul className="space-y-3">
                                {destination.requirements.map((req: string, idx: number) => (
                                    <li key={idx} className="flex items-start gap-3 text-sm text-gray-600">
                                        <span className="mt-1 w-4 h-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                                        <span>{req}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    )}

                    {/* Guide Download Card */}
                    {destination.guide ? (
                        <div className="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center mb-8">
                            <div className="w-12 h-12 bg-red-100 text-red-500 rounded-lg flex items-center justify-center mx-auto mb-3 text-xl">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                            </div>
                            <h4 className="font-bold text-gray-900 mb-2">{destination.guide.title}</h4>
                            <p className="text-xs text-gray-500 mb-4">{T.updatedFor} {destination.guide.year}</p>
                            <a href={destination.guide.fileUrl} target="_blank" rel="noopener noreferrer" className="text-secondary font-bold text-sm hover:underline flex items-center justify-center gap-1">
                                {T.downloadPdf}
                            </a>
                        </div>
                    ) : (
                        <div className="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center mb-8">
                            <h4 className="font-bold text-gray-900 mb-2">{T.needRoadmap}</h4>
                            <p className="text-sm text-gray-500 mb-4">{T.downloadGuideDesc}</p>
                            <button className="text-secondary font-bold text-sm hover:underline">{T.downloadPdf}</button>
                        </div>
                    )}

                    {/* FAQ Quick Format */}
                    {destination.faqs && destination.faqs.length > 0 && (
                        <div className="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                            <h3 className="font-bold text-gray-900 mb-4">{T.quickFormat}</h3>
                            <div className="space-y-4">
                                {destination.faqs.slice(0, 3).map((faq: any, idx: number) => (
                                    <div key={idx} className="border-b border-gray-50 last:border-0 pb-3 last:pb-0">
                                        <p className="font-bold text-xs text-gray-700 mb-1">{faq.question}</p>
                                        <p className="text-xs text-gray-500">{faq.answer}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
