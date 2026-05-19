import Image from 'next/image';
import { fetchScholarships } from '@/lib/api';
import ScholarshipsClient from '@/components/scholarships/ScholarshipsClient';
import BlogNewsSection from '@/components/BlogNewsSection';
import StudentReviewsSection from '@/components/StudentReviewsSection';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';

export async function generateMetadata() {
    const lang = await getLang();
    return {
        title: lang === 'ar'
            ? 'المنح الدراسية العالمية | Pioneers Admissions'
            : 'Global Scholarships | Pioneers Admissions',
        description: lang === 'ar'
            ? 'اكتشف فرص التمويل لدعم تعليمك في أفضل الجامعات حول العالم.'
            : 'Discover funding opportunities to support your education at top universities worldwide.',
    };
}

export default async function ScholarshipsPage() {
    const lang = await getLang();
    const scholarships = await fetchScholarships(lang) || [];

    return (
        <main className="bg-gray-50 pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#003B5C] text-white pt-32 pb-20 overflow-hidden">
                <div className="absolute inset-0 opacity-10">
                    <Image src="/hero.png" alt="Background" fill className="object-cover" priority />
                </div>
                <div className="container mx-auto px-4 md:px-8 lg:px-12 xl:px-20 relative z-10 text-center">
                    <h1 className="text-4xl md:text-6xl font-extrabold mb-6">{t('scholarships.title', lang)}</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto">
                        {t('scholarships.subtitle', lang)}
                    </p>
                </div>
            </section>

            {/* Scholarships Grid (Client Component) */}
            <ScholarshipsClient initialScholarships={scholarships} />

            <div className="section-divider mb-20"></div>

            {/* Reviews Section First */}
            <StudentReviewsSection />

            <div className="section-divider mb-20"></div>

            {/* Blogs Section Second */}
            <BlogNewsSection />

        </main>
    );
}
