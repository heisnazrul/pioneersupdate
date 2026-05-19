import Image from 'next/image';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faPassport,
    faCheckDouble,
    faFileShield,
    faUserTie,
    faTimeline,
    faScaleBalanced,
    faPlaneDeparture,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';
import { getCmsPage } from '@/services/cms';
import { notFound } from 'next/navigation';
import { getLang } from '@/lib/getLang';

const ICON_MAP: any = {
    faPassport,
    faCheckDouble,
    faFileShield,
    faUserTie,
    faTimeline,
    faScaleBalanced,
    faPlaneDeparture
};

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('visa-support', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'دعم التأشيرات | Pioneers Admissions' : 'Visa Support | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'إرشادات الخبراء لتأشيرات الطلاب.' : 'Expert guidance for student visas.')
    };
}

export default async function VisaPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('visa-support', lang);

    if (!pageData) {
        return notFound();
    }

    const { content } = pageData;
    const hero = content.hero || {};
    const services = content.services_section || {};
    const process = content.process_section || {};
    const cta = content.cta_section || {};

    return (
        <main className="pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#001f3f] text-white pt-32 pb-24 overflow-hidden">
                {/* Background Image with Overlay */}
                <div className="absolute inset-0">
                    <Image src="/hero.png" alt="Visa Consultant" fill className="object-cover" priority />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#001f3f]/95 via-[#001f3f]/85 to-[#001f3f]/40"></div>
                </div>

                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                    <div className="flex flex-col md:flex-row items-center gap-12">
                        <div className="w-full md:w-1/2 text-left">
                            <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 text-blue-200 text-sm font-bold tracking-wide uppercase mb-4 border border-blue-500/30">
                                {hero.badge || 'Visa Support Services'}
                            </span>
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                                {hero.title || 'Secure Your Student Visa'}
                            </h1>
                            <p className="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl">
                                {hero.description || 'Expert guidance for UK, USA, Canada, and Australia student visas.'}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4">
                                <Button href="/apply-now" variant="primary" size="lg" className="!bg-[#135FAE] hover:!bg-[#0e4b8a] shadow-xl px-10 border-0">
                                    Start Visa Process
                                </Button>
                                <Button href="#process" variant="outline" size="lg" className="border-white/30 text-white hover:bg-white/10 hover:border-white">
                                    How It Works
                                </Button>
                            </div>
                        </div>

                        <div className="w-full md:w-1/2 relative hidden md:block">
                            {/* Decorative Card */}
                            <div className="relative rounded-2xl p-1 bg-gradient-to-br from-white/20 to-transparent shadow-2xl backdrop-blur-sm max-w-md mx-auto transform rotate-1 hover:rotate-0 transition-transform duration-500">
                                <div className="bg-[#002855] rounded-xl p-6 border border-white/10">
                                    <div className="flex items-center gap-4 mb-6">
                                        <div className="w-12 h-12 rounded-full bg-[#135FAE] flex items-center justify-center text-white text-xl shadow-lg">
                                            <FontAwesomeIcon icon={faPassport} />
                                        </div>
                                        <div>
                                            <div className="text-white font-bold text-lg">Visa Approved</div>
                                            <div className="text-blue-200 text-xs">Ready for Departure</div>
                                        </div>
                                    </div>
                                    <div className="space-y-3">
                                        <div className="h-2 bg-white/10 rounded w-3/4"></div>
                                        <div className="h-2 bg-white/10 rounded w-full"></div>
                                        <div className="h-2 bg-white/10 rounded w-1/2"></div>
                                    </div>
                                    <div className="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                                        <span className="text-green-400 text-sm font-bold flex items-center gap-2">
                                            <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                            99% Success Rate
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Services Grid */}
            <section className="py-24 bg-gray-50">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle={services.subtitle || "Detailed Assistance"}
                        title={services.title || "Comprehensive Visa Support"}
                        description={services.description || "From document checklist to interview preparation, we cover every aspect of your application."}
                    />

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {(services.items || []).map((service: any, idx: number) => (
                            <div key={idx} className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                                <div className="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-[#135FAE] mb-6 group-hover:scale-110 transition-transform">
                                    <FontAwesomeIcon icon={ICON_MAP[service.icon] || faCheckDouble} className="text-2xl" />
                                </div>
                                <h3 className="text-xl font-bold text-gray-900 mb-3">{service.title}</h3>
                                <p className="text-gray-600 leading-relaxed text-sm">
                                    {service.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Process Section */}
            <section id="process" className="py-24 bg-white overflow-hidden">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="flex flex-col md:flex-row gap-16 items-center">
                        <div className="w-full md:w-1/2">
                            <SectionHeading
                                subtitle={process.subtitle || "Step-by-Step"}
                                title={process.title || "Your Roadmap to Approval"}
                                description={process.description || "A clear, structured timeline to ensure zero errors and maximum preparedness."}
                                align="left"
                                className="mb-0"
                            />

                            <div className="space-y-8 mt-12">
                                {(process.steps || []).map((step: any, idx: number) => (
                                    <div key={idx} className="flex gap-6 relative group">
                                        {/* Connector */}
                                        {idx !== (process.steps || []).length - 1 && (
                                            <div className="absolute left-[26px] top-12 bottom-[-32px] w-0.5 bg-gray-100 group-hover:bg-blue-100 transition-colors"></div>
                                        )}

                                        <div className="flex-shrink-0 w-14 h-14 rounded-full bg-white border-2 border-[#135FAE] text-[#135FAE] group-hover:bg-[#135FAE] group-hover:text-white transition-colors flex items-center justify-center font-bold text-lg relative z-10 shadow-sm">
                                            {step.number}
                                        </div>
                                        <div className="pt-2">
                                            <h4 className="text-xl font-bold text-gray-900 mb-2">{step.title}</h4>
                                            <p className="text-gray-600">{step.description}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="w-full md:w-1/2">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-4 translate-y-8">
                                    <div className="bg-blue-50 p-6 rounded-2xl">
                                        <div className="text-4xl font-extrabold text-[#135FAE] mb-2">12+</div>
                                        <div className="text-sm text-gray-600 font-medium">Years Experience</div>
                                    </div>
                                    <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg">
                                        <div className="text-4xl font-extrabold text-green-500 mb-2">99%</div>
                                        <div className="text-sm text-gray-600 font-medium">Approval Rate</div>
                                    </div>
                                </div>
                                <div className="space-y-4">
                                    <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg">
                                        <div className="text-4xl font-extrabold text-orange-400 mb-2">24h</div>
                                        <div className="text-sm text-gray-600 font-medium">Support Response</div>
                                    </div>
                                    <div className="bg-blue-600 p-6 rounded-2xl text-white shadow-xl">
                                        <div className="text-4xl font-extrabold mb-2">5k+</div>
                                        <div className="text-sm text-blue-100 font-medium">Visas Granted</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="py-24 bg-[#135FAE] text-white text-center relative overflow-hidden">
                <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'radial-gradient(white 1px, transparent 1px)', backgroundSize: '30px 30px' }}></div>
                <div className="container mx-auto px-4 relative z-10">
                    <h2 className="text-3xl md:text-5xl font-extrabold mb-6">{cta.title || "Don't Risk Your Visa Application"}</h2>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto mb-10">
                        {cta.description || "Get it right the first time with our expert guidance. Book a consultation today."}
                    </p>
                    <Button href={cta.button_link || "/apply-now"} variant="white" size="lg" className="shadow-xl rounded-full px-12 py-4 text-lg font-bold">
                        {cta.button_text || "Book Visa Consultation"}
                    </Button>
                </div>
            </section>
        </main>
    );
}
