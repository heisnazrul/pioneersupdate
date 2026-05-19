import { fetchFullDestinations } from '@/lib/api';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';
import DestinationsClient from '@/components/DestinationsClient';
import FaqSection from '@/components/FaqSection';
import TrustSection from '@/components/TrustSection';
import BlogNewsSection from '@/components/BlogNewsSection';

export async function generateMetadata() {
    return {
        title: 'Destinations | Pioneers Admissions',
        description: 'Explore our study destinations around the world.',
    };
}

export default async function DestinationsPage() {
    const lang = await getLang();
    const isAr = lang === 'ar';

    const [destinationsData, cmsData] = await Promise.all([
        fetchFullDestinations(lang),
        getCmsPage('destinations', lang),
    ]);

    const destinations = destinationsData || [];
    const cms = cmsData?.content || {};

    // i18n labels passed to the Client Component
    const labels = {
        filters: {
            all: cms.filter_all || (isAr ? 'الكل' : 'All'),
            europe: cms.filter_europe || (isAr ? 'أوروبا' : 'Europe'),
            na: cms.filter_na || (isAr ? 'أمريكا الشمالية' : 'North America'),
            oceania: cms.filter_oceania || (isAr ? 'أوقيانوسيا' : 'Oceania'),
            budget: cms.filter_budget || (isAr ? 'رسوم منخفضة' : 'Low Tuition'),
        },
        searchPlaceholder: cms.search_placeholder || (isAr ? 'ابحث عن وجهة...' : 'Search destinations...'),
        noMatchTitle: cms.no_match_title || (isAr ? 'لا توجد نتائج' : 'No matches found'),
        noMatchText: cms.no_match_text || (isAr ? 'لم نجد أي وجهة مطابقة' : 'We couldn\'t find any destinations matching your search.'),
        resetFilters: cms.reset_filters || (isAr ? 'إعادة تعيين الفلاتر' : 'Reset All Filters'),
        showing: cms.showing || (isAr ? 'جارٍ عرض' : 'Showing'),
        destinationsWord: cms.destinations_word || (isAr ? 'وجهة' : 'Destinations'),
        dir: (isAr ? 'rtl' : 'ltr') as 'rtl' | 'ltr',
        lang,
    };

    return (
        <>
            {/* Hero Header */}
            <header className="bg-background py-16 text-center mb-12 border-b border-gray-100" dir={labels.dir}>
                <div className="w-full px-4 md:px-10 xl:px-20 2xl:px-40">
                    <h1 className="text-4xl md:text-5xl font-extrabold text-primary mb-4 tracking-tight">
                        {cms.hero_title || t('destinations.title', lang)}
                    </h1>
                    <p className="text-lg text-muted max-w-2xl mx-auto">
                        {cms.hero_subtitle || t('destinations.subtitle', lang)}
                    </p>
                </div>
            </header>

            {/* Destinations Grid */}
            <div className="w-full px-4 md:px-10 xl:px-20 2xl:px-40 pb-20" dir={labels.dir}>
                <DestinationsClient initialDestinations={destinations} labels={labels} />
            </div>

            <TrustSection lang={lang} />
            <FaqSection lang={lang} />
            <BlogNewsSection lang={lang} />
        </>
    );
}
