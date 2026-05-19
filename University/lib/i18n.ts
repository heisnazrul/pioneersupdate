import type { Lang } from './getLang';

type Translations = Record<string, Record<Lang, string>>;

const translations: Translations = {
    // ─── Common ────────────────────────────────────────────────────────────
    'common.home': { en: 'Home', ar: 'الرئيسية' },
    'common.back': { en: 'Back', ar: 'رجوع' },
    'common.viewAll': { en: 'View all', ar: 'عرض الكل' },
    'common.viewDetails': { en: 'View Details', ar: 'عرض التفاصيل' },
    'common.applyNow': { en: 'Apply Now', ar: 'قدّم الآن' },
    'common.contactUs': { en: 'Contact Us', ar: 'تواصل معنا' },
    'common.downloadPdf': { en: 'Download PDF ↓', ar: 'تحميل PDF ↓' },
    'common.learnMore': { en: 'Learn More', ar: 'اعرف أكثر' },
    'common.search': { en: 'Search', ar: 'بحث' },
    'common.browseAll': { en: 'Browse All Courses', ar: 'استعرض كل المقررات' },
    'common.perYear': { en: 'per year', ar: 'سنوياً' },
    'common.notAvailable': { en: 'N/A', ar: 'غير متاح' },
    'common.requirements': { en: 'Requirements', ar: 'المتطلبات' },
    'common.quickFaq': { en: 'Quick FAQ', ar: 'أسئلة سريعة' },
    'common.readyToApply': { en: 'Ready to Apply?', ar: 'هل أنت مستعد للتقديم؟' },
    'common.startJourney': { en: 'Start your journey today.', ar: 'ابدأ رحلتك اليوم.' },

    // ─── Navigation / Breadcrumbs ───────────────────────────────────────────
    'nav.courses': { en: 'Courses', ar: 'المقررات' },
    'nav.universities': { en: 'Universities', ar: 'الجامعات' },
    'nav.scholarships': { en: 'Scholarships', ar: 'المنح الدراسية' },
    'nav.destinations': { en: 'Destinations', ar: 'الوجهات' },
    'nav.blogs': { en: 'Blogs', ar: 'المقالات' },
    'nav.about': { en: 'About', ar: 'عن الشركة' },
    'nav.contact': { en: 'Contact', ar: 'تواصل معنا' },

    // ─── University Detail Page ─────────────────────────────────────────────
    'uni.popularCourses': { en: 'Popular Courses', ar: 'المقررات الشائعة' },
    'uni.about': { en: 'About', ar: 'نبذة عن' },
    'uni.rankings': { en: 'Rankings', ar: 'التصنيفات' },
    'uni.qsRanking': { en: 'QS Ranking', ar: 'تصنيف QS' },
    'uni.theRanking': { en: 'THE Ranking', ar: 'تصنيف THE' },
    'uni.shanghaiRanking': { en: 'Shanghai Ranking', ar: 'تصنيف شنغهاي' },
    'uni.visitWebsite': { en: 'Visit Website', ar: 'زيارة الموقع' },
    'uni.type': { en: 'Type', ar: 'النوع' },
    'uni.established': { en: 'Established', ar: 'تأسست' },
    'uni.noCourses': { en: 'No specific courses listed yet for this university.', ar: 'لا توجد مقررات مدرجة حتى الآن لهذه الجامعة.' },

    // ─── Course Detail Page ─────────────────────────────────────────────────
    'course.duration': { en: 'Duration', ar: 'المدة' },
    'course.level': { en: 'Level', ar: 'المستوى' },
    'course.intake': { en: 'Intake', ar: 'القبول' },
    'course.deadline': { en: 'Deadline', ar: 'آخر موعد' },
    'course.tuition': { en: 'Tuition (Est.)', ar: 'الرسوم الدراسية (تقديرية)' },
    'course.relatedCourses': { en: 'Related Courses', ar: 'مقررات ذات صلة' },
    'course.overview': { en: 'Course Overview', ar: 'نظرة عامة على المقرر' },
    'course.requirements': { en: 'Entry Requirements', ar: 'متطلبات القبول' },
    'course.contactUs': { en: 'Contact Us', ar: 'تواصل معنا' },
    'course.applyBtn': { en: 'Apply for this Course', ar: 'قدّم لهذا المقرر' },

    // ─── Scholarships Page ──────────────────────────────────────────────────
    'scholarships.title': { en: 'Global Scholarships', ar: 'المنح الدراسية العالمية' },
    'scholarships.subtitle': { en: 'Discover funding opportunities to support your education at top universities worldwide.', ar: 'اكتشف فرص التمويل لدعم تعليمك في أفضل الجامعات حول العالم.' },
    'scholarships.deadline': { en: 'Deadline', ar: 'آخر موعد' },
    'scholarships.amount': { en: 'Amount', ar: 'القيمة' },
    'scholarships.applyNow': { en: 'Apply Now', ar: 'قدّم الآن' },
    'scholarships.noResults': { en: 'No scholarships found.', ar: 'لم يتم العثور على منح.' },

    // ─── Destinations Page ──────────────────────────────────────────────────
    'destinations.title': { en: 'Study Abroad Destinations', ar: 'وجهات الدراسة في الخارج' },
    'destinations.subtitle': { en: 'Explore the world\'s top study destinations.', ar: 'استكشف أفضل وجهات الدراسة في العالم.' },
    'destinations.tuitionRange': { en: 'Tuition Range', ar: 'نطاق الرسوم' },
    'destinations.visaTime': { en: 'Visa Time', ar: 'وقت التأشيرة' },
    'destinations.workRights': { en: 'Work Rights', ar: 'حقوق العمل' },
    'destinations.scholarships': { en: 'Scholarships', ar: 'المنح' },
    'destinations.topUniversities': { en: 'Top Universities', ar: 'أفضل الجامعات' },
    'destinations.popularPrograms': { en: 'Popular Programs', ar: 'البرامج الشائعة' },
    'destinations.whyStudy': { en: 'Why Study in', ar: 'لماذا الدراسة في' },
    'destinations.comingSoon': { en: 'Universities list coming soon.', ar: 'قائمة الجامعات قادمة قريباً.' },
    'destinations.programsSoon': { en: 'Programs for this destination are coming soon.', ar: 'البرامج لهذه الوجهة قادمة قريباً.' },
    'destinations.topDestination': { en: 'Top Destination', ar: 'وجهة مميزة' },

    // ─── About Page ─────────────────────────────────────────────────────────
    'about.title': { en: 'About Us', ar: 'من نحن' },
    'about.mission': { en: 'Our Mission', ar: 'مهمتنا' },
    'about.vision': { en: 'Our Vision', ar: 'رؤيتنا' },
    'about.team': { en: 'Our Team', ar: 'فريقنا' },
    'about.values': { en: 'Our Values', ar: 'قيمنا' },

    // ─── Contact Page ────────────────────────────────────────────────────────
    'contact.title': { en: 'Contact Us', ar: 'تواصل معنا' },
    'contact.subtitle': { en: 'We\'re here to help with your study abroad journey.', ar: 'نحن هنا لمساعدتك في رحلة دراستك في الخارج.' },
    'contact.name': { en: 'Full Name', ar: 'الاسم الكامل' },
    'contact.email': { en: 'Email Address', ar: 'البريد الإلكتروني' },
    'contact.phone': { en: 'Phone Number', ar: 'رقم الهاتف' },
    'contact.message': { en: 'Message', ar: 'الرسالة' },
    'contact.send': { en: 'Send Message', ar: 'إرسال الرسالة' },
    'contact.offices': { en: 'Our Offices', ar: 'مكاتبنا' },

    // ─── Agents / Partners Page ──────────────────────────────────────────────
    'agents.title': { en: 'Partner with Us', ar: 'كن شريكنا' },
    'agents.subtitle': { en: 'Join our global network of education partners.', ar: 'انضم إلى شبكتنا العالمية من شركاء التعليم.' },

    // ─── Blogs Page ─────────────────────────────────────────────────────────
    'blogs.title': { en: 'Latest News & Insights', ar: 'آخر الأخبار والتحليلات' },
    'blogs.subtitle': { en: 'Stay informed about global education trends.', ar: 'ابق على اطلاع بأحدث توجهات التعليم العالمي.' },
    'blogs.readMore': { en: 'Read More', ar: 'اقرأ المزيد' },
    'blogs.byAuthor': { en: 'By', ar: 'بقلم' },

    // ─── Search Pages ────────────────────────────────────────────────────────
    'search.courses': { en: 'Search Courses', ar: 'البحث في المقررات' },
    'search.universities': { en: 'Search Universities', ar: 'البحث في الجامعات' },
    'search.noResults': { en: 'No results found.', ar: 'لم يتم العثور على نتائج.' },
    'search.filters': { en: 'Filters', ar: 'الفلاتر' },
    'search.clearFilters': { en: 'Clear All Filters', ar: 'مسح جميع الفلاتر' },
};

/**
 * Returns the translated string for the given key and language.
 * Falls back to English if the Arabic translation is missing.
 */
export function t(key: string, lang: Lang): string {
    const entry = translations[key];
    if (!entry) return key; // Return key itself as fallback
    return lang === 'ar'
        ? (entry.ar || entry.en)
        : (entry.en || entry.ar);
}

export default translations;
