import Image from 'next/image';
import Link from 'next/link';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faPassport,
    faUniversity,
    faHome,
    faGraduationCap,
    faHandshake,
    faBookOpen,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import Button from '@/components/ui/Button';

// Icon mapping matching the seeder strings
const iconMap: { [key: string]: any } = {
    faUniversity,
    faPassport,
    faHome,
    faGraduationCap,
    faHandshake,
    faBookOpen,
    faArrowRight
};

// Colors matching the user's static design order
const serviceStyles = [
    { color: "text-blue-600", bg: "bg-blue-50" },      // University Admissions
    { color: "text-purple-600", bg: "bg-purple-50" },  // Visa Assistance
    { color: "text-green-600", bg: "bg-green-50" },     // Accommodation
    { color: "text-yellow-600", bg: "bg-yellow-50" },   // Scholarships
    { color: "text-red-600", bg: "bg-red-50" },        // Partner Network
    { color: "text-indigo-600", bg: "bg-indigo-50" }    // Student Guides
];

interface CmsContent {
    hero?: {
        badge: string;
        title: string;
        description: string;
        button_text?: string;
        button_link?: string;
        secondary_button_text?: string;
        secondary_button_link?: string;
    };
    what_we_offer?: {
        title: string;
        description: string;
        items: Array<{
            title: string;
            description: string;
            icon: string;
            link?: string;
        }>;
    };
    our_process?: {
        title: string;
        description: string;
        steps: Array<{
            number: string;
            title: string;
            description: string;
        }>;
    };
    partner_section?: {
        badge: string;
        title: string;
        description: string;
        button_text: string;
        button_link: string;
        secondary_button_text: string;
        secondary_button_link: string;
    };
    cta?: {
        title: string;
        description: string;
        button_text: string;
        button_link: string;
    };
}

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('services', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'خدماتنا | Pioneers Admissions' : 'Our Services | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'خدمات شاملة لرحلتك العالمية.' : 'Comprehensive solutions for your study abroad journey.')
    };
}

export default async function ServicesPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('services', lang);
    const content = (pageData?.content || {}) as CmsContent;

    const { hero, what_we_offer, our_process, partner_section, cta } = content || {};

    return (
        <main className="bg-white min-h-screen">
            {/* Hero Section */}
            {hero && (
                <section className="relative bg-[#003B5C] text-white pt-40 pb-32 overflow-hidden">
                    <div className="absolute inset-0 opacity-10">
                        <Image src="/hero.png" alt="Background" fill className="object-cover" />
                    </div>
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                        <div className="max-w-4xl">
                            <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 backdrop-blur-sm text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                                {hero.badge}
                            </span>
                            <h1 className="text-5xl md:text-7xl font-extrabold leading-tight mb-8">
                                {hero.title}
                            </h1>
                            <p className="text-xl text-blue-100 max-w-2xl leading-relaxed mb-10">
                                {hero.description}
                            </p>
                            <div className="flex gap-4">
                                {hero.button_text && (
                                    <Link href={hero.button_link || '#'}>
                                        <Button className="rounded-full px-8 py-4 text-lg">{hero.button_text}</Button>
                                    </Link>
                                )}
                                {hero.secondary_button_text && (
                                    <Link href={hero.secondary_button_link || '#'} className="px-8 py-4 rounded-full border border-white/30 hover:bg-white/10 transition-colors font-bold flex items-center gap-2">
                                        {hero.secondary_button_text} <FontAwesomeIcon icon={faArrowRight} />
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            )}

            {/* Services Grid */}
            {what_we_offer && (
                <section className="py-24 relative">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="mb-16">
                            <h2 className="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{what_we_offer.title}</h2>
                            <p className="text-gray-500 text-lg max-w-2xl">
                                {what_we_offer.description}
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {what_we_offer.items.map((service, idx) => {
                                const Icon = iconMap[service.icon] || faUniversity;
                                const style = serviceStyles[idx % serviceStyles.length];

                                return (
                                    <Link
                                        href={service.link || '#'}
                                        key={idx}
                                        className="group block bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                                    >
                                        <div className={`w-16 h-16 rounded-2xl ${style.bg} ${style.color} flex items-center justify-center text-2xl mb-8 group-hover:scale-110 transition-transform`}>
                                            <FontAwesomeIcon icon={Icon} />
                                        </div>
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors">
                                            {service.title}
                                        </h3>
                                        <p className="text-gray-500 leading-relaxed mb-8">
                                            {service.description}
                                        </p>
                                        <div className="flex items-center gap-2 text-sm font-bold text-gray-900 group-hover:gap-4 transition-all">
                                            Learn More <FontAwesomeIcon icon={faArrowRight} className="text-blue-600" />
                                        </div>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>
                </section>
            )}

            {/* Process / Trust Section */}
            {our_process && (
                <section className="py-24 bg-gray-50">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                            <div>
                                <span className="text-blue-600 font-bold tracking-wider uppercase text-sm mb-2 block">Our Process</span>
                                <h2 className="text-4xl font-extrabold text-gray-900 mb-6">{our_process.title}</h2>
                                <p className="text-gray-500 text-lg mb-8 leading-relaxed">
                                    {our_process.description}
                                </p>

                                <ul className="space-y-6">
                                    {our_process.steps.map((item, i) => (
                                        <li key={i} className="flex gap-6">
                                            <span className="text-3xl font-black text-gray-200">{item.number}</span>
                                            <div>
                                                <h4 className="font-bold text-gray-900 text-lg">{item.title}</h4>
                                                <p className="text-gray-500 text-sm">{item.description}</p>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                            <div className="relative h-[600px] bg-blue-600 rounded-[40px] overflow-hidden shadow-2xl">
                                <Image src="/assets/blogs/campus.png" alt="Happy students" fill className="object-cover opacity-80 mix-blend-overlay" />
                                <div className="absolute bottom-0 left-0 right-0 p-10 bg-gradient-to-t from-black/80 to-transparent text-white">
                                    <h3 className="text-3xl font-bold mb-2">98% Success Rate</h3>
                                    <p className="text-blue-100">In visa approvals for top destinations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            )}

            {/* CTA Section */}
            {cta && (
                <section className="py-24 bg-white text-center">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <h2 className="text-3xl font-bold text-gray-900 mb-6">{cta.title}</h2>
                        <p className="text-gray-500 max-w-2xl mx-auto mb-10">
                            {cta.description}
                        </p>
                        <Link href={cta.button_link}>
                            <Button className="rounded-full px-10 py-5 text-xl shadow-xl shadow-blue-600/20">{cta.button_text}</Button>
                        </Link>
                    </div>
                </section>
            )}

            {/* Agent / Partner Section */}
            {partner_section && (
                <section className="py-24 bg-[#002840] text-center text-white relative overflow-hidden">
                    <div className="absolute inset-0 opacity-5 bg-[url('/pattern.png')]"></div>
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                        <div className="max-w-3xl mx-auto">
                            <span className="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                                {partner_section.badge}
                            </span>
                            <h2 className="text-3xl md:text-5xl font-extrabold mb-6">{partner_section.title}</h2>
                            <p className="text-blue-100 text-lg md:text-xl mb-10 leading-relaxed">
                                {partner_section.description}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href={partner_section.button_link} className="px-8 py-4 rounded-full bg-white text-[#002840] font-bold hover:bg-gray-100 transition-colors shadow-lg">
                                    {partner_section.button_text}
                                </Link>
                                <Link href={partner_section.secondary_button_link} className="px-8 py-4 rounded-full border border-white/30 hover:bg-white/10 transition-colors font-bold text-white">
                                    {partner_section.secondary_button_text}
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            )}
        </main>
    );
}
