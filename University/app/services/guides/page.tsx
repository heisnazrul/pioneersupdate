import Image from 'next/image';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faBookOpen,
    faPlane,
    faGraduationCap,
    faMoneyBillWave,
    faCity,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';
import BlogNewsSection from '@/components/BlogNewsSection';
import FeaturedGuidesCarousel from '@/components/FeaturedGuidesCarousel';
import ToolsResourcesSection from '@/components/ToolsResourcesSection';
import GuidesFAQ from '@/components/GuidesFAQ';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import { notFound } from 'next/navigation';

const ICON_MAP: any = {
    faPlane,
    faGraduationCap,
    faMoneyBillWave,
    faCity,
    faBookOpen
};

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('student-guide', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'أدلة الطلاب | Pioneers Admissions' : 'Student Guides | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'نصائح الخبراء وموارد شاملة.' : 'Expert advice and comprehensive resources.')
    };
}

export default async function GuidesPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('student-guide', lang);

    if (!pageData) {
        return notFound();
    }

    const { content } = pageData;
    const hero = content.hero || {};
    const categories = content.categories || [];
    const trust = content.trust_section || {};

    let guides: any[] = [];
    if (content.featured_guides_category_slug) {
        try {
            const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
            const res = await fetch(`${apiUrl}/api/blogs?category=${content.featured_guides_category_slug}`, {
                next: { revalidate: 60 }
            });
            const data = await res.json();
            if (data?.data) {
                guides = data.data.map((blog: any) => ({
                    title: blog.title,
                    category: blog.category,
                    read_time: blog.readTime || '5 min read',
                    image: blog.image || '/assets/placeholder.png',
                    link: `/blog/${blog.slug}`
                }));
            }
        } catch (error) {
            console.error('Failed to fetch featured guides:', error);
        }
    }

    let tools: any[] = [];
    try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
        const res = await fetch(`${apiUrl}/api/destination-guides`, {
            next: { revalidate: 60 }
        });
        const data = await res.json();
        if (data?.data) {
            tools = data.data;
        }
    } catch (error) {
        console.error('Failed to fetch destination guides:', error);
    }

    return (
        <main className="pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#002B49] text-white pt-32 pb-24 overflow-hidden">
                {/* Background Image with Overlay */}
                <div className="absolute inset-0">
                    <Image src="/hero.png" alt="Library" fill className="object-cover" priority />
                    <div className="absolute inset-0 bg-[#002B49]/80 mix-blend-multiply"></div>
                    <div className="absolute inset-0 bg-gradient-to-t from-[#002B49] via-transparent to-black/60"></div>
                </div>

                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10 text-center">
                    <span className="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur text-white text-sm font-bold tracking-wide uppercase mb-6 border border-white/20">
                        {hero.badge || 'Knowledge Hub'}
                    </span>
                    <h1 className="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 font-serif tracking-tight">
                        {hero.title || 'Essential Student Guides'}
                    </h1>
                    <p className="text-xl text-slate-200 mb-10 leading-relaxed max-w-2xl mx-auto font-light">
                        {hero.description || 'Expert advice, insider tips, and comprehensive resources to help you thrive in your international education journey.'}
                    </p>

                    {/* Search Bar Visual */}
                    <div className="max-w-xl mx-auto bg-white/10 backdrop-blur rounded-full p-2 flex border border-white/20 shadow-2xl">
                        <input
                            type="text"
                            placeholder="What do you need help with?"
                            className="bg-transparent border-none text-white placeholder-slate-300 px-6 py-3 w-full outline-none"
                        />
                        <button className="bg-white text-slate-900 rounded-full px-8 py-3 font-bold hover:bg-slate-100 transition-colors">
                            Search
                        </button>
                    </div>
                </div>
            </section>

            {/* Categories Grid */}
            <section className="py-20 bg-white -mt-12 relative z-20">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {categories.map((cat: any, idx: number) => (
                            <div key={idx} className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                                <div className={`w-12 h-12 rounded-lg ${cat.color} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                                    <FontAwesomeIcon icon={ICON_MAP[cat.icon] || faBookOpen} className="text-xl" />
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2">{cat.title}</h3>
                                <p className="text-gray-500 text-sm leading-relaxed">
                                    {cat.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Tools & Resources */}
            <ToolsResourcesSection data={content.tools_resources} items={tools} />

            {/* FAQ Section */}
            <GuidesFAQ data={content.faq} />

            {/* Why Trust Us / Info Block */}
            <section className="py-24 bg-white">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="bg-[#002B49] rounded-3xl p-8 md:p-16 text-white relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>

                        <div className="relative z-10 max-w-3xl">
                            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-6">{trust.title || 'Trusted by 10,000+ Students'}</h2>
                            <p className="text-lg text-blue-100 mb-8 leading-relaxed">
                                {trust.description || 'Our guides are written by experienced education counselors and alumni who have been through the process themselves.'}
                            </p>
                            <Button href={trust.cta_link || '/contact'} variant="primary" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] border-0 shadow-lg px-8">
                                {trust.cta_text || 'Speak to an Expert'}
                            </Button>
                        </div>
                    </div>
                </div>
            </section>

            {/* Featured Guides (Curated) */}
            {(content.featured_guides_category_slug || guides.length > 0) && (
                <section className="py-20 bg-gray-50 overflow-hidden">
                    <div className="r px-4 md:px-10 xl:px-30 2xl:px-50">
                        <SectionHeading
                            subtitle="Staff Picks"
                            title="Must-Read Guides"
                            description="Curated by our expert counselors to answer the most common student questions."
                            align="left"
                        />

                        {/* Horizontal Scroll / Slider */}
                        <FeaturedGuidesCarousel guides={guides} />
                    </div>
                </section>
            )}

            {/* Blog Component Integration */}
            <div className="bg-gray-50 pt-12">
                <BlogNewsSection />
            </div>
        </main>
    );
}
