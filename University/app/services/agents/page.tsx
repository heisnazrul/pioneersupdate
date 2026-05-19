import Image from 'next/image';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faHandshake,
    faGlobe,
    faChartLine,
    faUserGroup,
    faPercent,
    faHeadset,
    faCheckCircle,
    faUniversity,
    faUsers,
    faPassport
} from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';

// Icon mapping (be safe with keys)
const ICON_MAP: any = {
    faGlobe, faChartLine, faUserGroup, faPercent, faHeadset, faCheckCircle,
    faUniversity, faUsers, faPassport
};

interface CmsContent {
    hero: {
        title: string;
        badge: string;
        description: string;
        cta_primary: string;
        cta_primary_link: string;
        cta_secondary: string;
        cta_secondary_link: string;
        bg_image: string;
    };
    stats: Array<{ value: string; label: string }>;
    services_section: { // Changed from benefits
        title: string;
        subtitle: string;
        description: string;
        items: Array<{ title: string; description: string; icon: string }>;
    };
    process_section: { // Changed from steps
        title: string;
        description: string;
        steps: Array<{ number: string; title: string; description: string }>;
    };
    cta_section: {
        title: string;
        button_text: string;
        description: string;
    };
}

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('agents', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'شبكة الوكلاء | Pioneers Admissions' : 'Agent Network | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'انضم إلى شبكة وكلائنا العالمية.' : 'Join our global agent network.')
    };
}

export default async function AgentsPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('agents', lang);
    const content = pageData?.content as CmsContent;

    if (!content) return null;

    return (
        <main className="pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#002B49] text-white pt-32 pb-24 overflow-hidden">
                <div className="absolute inset-0">
                    <Image src={content.hero.bg_image || "/hero.png"} alt="Background" fill className="object-cover" priority />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#002B49]/95 via-[#002B49]/80 to-[#002B49]/30"></div>
                </div>

                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                    <div className="flex flex-col md:flex-row items-center gap-12">
                        <div className="w-full md:w-1/2 text-left">
                            <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 text-blue-300 text-sm font-bold tracking-wide uppercase mb-4 border border-blue-500/30">
                                {content.hero.badge}
                            </span>
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                                {content.hero.title}
                            </h1>
                            <p className="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl">
                                {content.hero.description}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4">
                                <Button href={content.hero.cta_primary_link} variant="primary" size="lg" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] shadow-xl px-10 border-0">
                                    {content.hero.cta_primary}
                                </Button>
                                <Button href={content.hero.cta_secondary_link} variant="outline" size="lg" className="border-white/30 text-white hover:bg-white/10 hover:border-white">
                                    {content.hero.cta_secondary}
                                </Button>
                            </div>
                        </div>
                        <div className="w-full md:w-1/2 relative">
                            <div className="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white/5 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                                <Image
                                    src="/assets/blogs/scholarship.png"
                                    alt="Business Partnership"
                                    width={600}
                                    height={400}
                                    className="object-cover w-full h-auto"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div className="absolute bottom-6 left-6 right-6">
                                    <div className="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                                        <div className="flex items-center gap-4">
                                            <div className="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-xl">
                                                <FontAwesomeIcon icon={faHandshake} />
                                            </div>
                                            <div>
                                                <div className="text-white font-bold text-lg">Trusted Partner</div>
                                                <div className="text-gray-300 text-sm">Active Agents</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Stats Bar */}
            <section className="bg-white py-10 border-b border-gray-100 shadow-sm relative z-20 -mt-8 mx-4 md:mx-auto container max-w-6xl rounded-2xl">
                <div className="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-gray-100">
                    {content.stats && content.stats.map((stat, idx) => (
                        <div key={idx} className="text-center">
                            <div className="text-3xl font-extrabold text-[#135FAE]">{stat.value}</div>
                            <div className="text-sm text-gray-500 font-medium mt-1">{stat.label}</div>
                        </div>
                    ))}
                </div>
            </section>

            {/* Benefits Section */}
            <section id="benefits" className="py-24 bg-gray-50">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle={content.services_section?.subtitle}
                        title={content.services_section?.title}
                        description={content.services_section?.description}
                    />

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {content.services_section?.items?.map((benefit, idx) => (
                            <div key={idx} className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div className="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-[#135FAE] mb-6">
                                    <FontAwesomeIcon icon={ICON_MAP[benefit.icon] || faCheckCircle} className="text-2xl" />
                                </div>
                                <h3 className="text-xl font-bold text-gray-900 mb-3">{benefit.title}</h3>
                                <p className="text-gray-600 leading-relaxed text-sm">
                                    {benefit.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* How It Works */}
            <section className="py-24 bg-white">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle={content.process_section?.description} // Mapping description to subtitle based on Admin Form usage
                        title={content.process_section?.title}
                        align="center"
                    />

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-center relative mt-16">
                        <div className="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gray-100 -z-10"></div>

                        {content.process_section?.steps?.map((item, i) => (
                            <div key={i} className="relative bg-white p-4">
                                <div className="w-24 h-24 mx-auto bg-white rounded-full border-4 border-blue-50 flex items-center justify-center text-3xl font-extrabold text-[#135FAE] mb-6 shadow-sm">
                                    {item.number}
                                </div>
                                <h3 className="text-xl font-bold mb-3">{item.title}</h3>
                                <p className="text-gray-600 max-w-xs mx-auto">{item.description}</p>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mt-16">
                        <Button href={content.cta_section?.button_text ? "/contact" : "/contact"} variant="primary" size="lg" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] rounded-full px-12 py-4 shadow-xl border-0">
                            {content.cta_section?.button_text || "Register as a Partner Now"}
                        </Button>
                    </div>
                </div>
            </section>
        </main>
    );
}
