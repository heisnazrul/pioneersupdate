import Image from 'next/image';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faFilePen,
    faUniversity,
    faPassport,
    faCheckDouble,
    faUserTie,
    faCalendarCheck,
    faArrowRight
} from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import Button from '@/components/ui/Button';

// Map icon strings to actual icons
const ICON_MAP: any = {
    faUniversity,
    faFilePen,
    faCheckDouble,
    faUserTie,
    faPassport,
    faCalendarCheck
};

import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('application', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'خدمات التقديم | Pioneers Admissions' : 'Application Services | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'تجاوز عملية التقديم الجامعي بثقة.' : 'Navigate the complex university application process with confidence.')
    };
}

export default async function ApplicationServicesPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('application', lang);
    const content = pageData?.content || {};

    const hero = content.hero || {};
    const services = content.services || {};
    const process = content.process || {};
    const cta = content.cta || {};

    const serviceItems = services.items || [];
    const stepItems = process.steps || [];

    return (
        <main className="pb-20">
            {/* Hero Section */}
            <section className="relative bg-[#003B5C] text-white pt-32 pb-24 overflow-hidden">
                <div className="absolute inset-0 opacity-10">
                    <Image src="/hero.png" alt="Background" fill className="object-cover" />
                </div>
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10 text-center">
                    <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 text-blue-200 text-sm font-bold tracking-wide uppercase mb-4">
                        {hero.badge || 'Premium Support'}
                    </span>
                    <h1 className="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
                        {hero.title || 'Expert Application Services'}
                    </h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto mb-10 leading-relaxed">
                        {hero.description || 'Navigate the complex university application process with confidence. Our team of experts is with you every step of the way.'}
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <Button href={hero.btn1_link || '/apply-now'} variant="white" size="lg" className="shadow-xl">
                            {hero.btn1_text || 'Start Your Application'}
                        </Button>
                        <Button href={hero.btn2_link || '#process'} variant="outline" size="lg" className="border-blue-300 text-blue-100 hover:bg-blue-900/50">
                            {hero.btn2_text || 'How It Works'}
                        </Button>
                    </div>
                </div>
            </section>

            {/* Services Grid */}
            <section className="py-20 bg-gray-50">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <SectionHeading
                        subtitle={services.subtitle || "Our Expertise"}
                        title={services.title || "Comprehensive Application Support"}
                        description={services.description || "We provide end-to-end services to maximize your chances of acceptance."}
                    />

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {serviceItems.length > 0 ? (
                            serviceItems.map((service: any, idx: number) => {
                                const Icon = ICON_MAP[service.icon] || faCheckDouble;
                                return (
                                    <div key={idx} className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                                        <div className="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-[#135FAE] mb-6 group-hover:scale-110 transition-transform">
                                            <FontAwesomeIcon icon={Icon} className="text-2xl" />
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-3">{service.title}</h3>
                                        <p className="text-gray-600 leading-relaxed">
                                            {service.description}
                                        </p>
                                    </div>
                                );
                            })
                        ) : (
                            <p className="text-center col-span-3 text-gray-500">No services defined.</p>
                        )}
                    </div>
                </div>
            </section>

            {/* Process Timeline */}
            <section id="process" className="py-20 bg-white">
                <div className="container mx-auto px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="flex flex-col md:flex-row gap-16 items-center">
                        <div className="w-full md:w-1/2">
                            <SectionHeading
                                subtitle={process.subtitle || "The Process"}
                                title={process.title || "Your Journey to Acceptance"}
                                description={process.description || "A structured approach designed to keep you organized and ahead of deadlines."}
                                align="left"
                                className="mb-0"
                            />

                            <div className="space-y-8 mt-12">
                                {stepItems.length > 0 ? (
                                    stepItems.map((step: any, idx: number) => (
                                        <div key={idx} className="flex gap-6 relative">
                                            {/* Connector Line */}
                                            {idx !== stepItems.length - 1 && (
                                                <div className="absolute left-[26px] top-12 bottom-[-32px] w-0.5 bg-gray-100"></div>
                                            )}

                                            <div className="flex-shrink-0 w-14 h-14 rounded-full bg-white border-2 border-[#135FAE] text-[#135FAE] flex items-center justify-center font-bold text-lg relative z-10 shadow-sm">
                                                {step.number || idx + 1}
                                            </div>
                                            <div className="pt-2">
                                                <h4 className="text-xl font-bold text-gray-900 mb-2">{step.title}</h4>
                                                <p className="text-gray-600">{step.description}</p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-gray-500">Processing steps not configured.</p>
                                )}
                            </div>
                        </div>

                        <div className="w-full md:w-1/2">
                            <div className="relative rounded-3xl overflow-hidden shadow-2xl bg-gray-100 aspect-[4/5] transform hover:scale-[1.02] transition-transform duration-500">
                                <Image
                                    src="/assets/blogs/sop.png"
                                    alt="Student Success"
                                    fill
                                    className="object-cover"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-[#003B5C]/90 to-transparent flex items-end p-10">
                                    <div className="text-white">
                                        <div className="text-5xl font-extrabold mb-2">{process.stat?.value || '98%'}</div>
                                        <div className="text-xl font-medium opacity-90">{process.stat?.label || 'Success Rate'}</div>
                                        <p className="mt-4 text-sm opacity-75 leading-relaxed">
                                            {process.stat?.description || 'Our students consistently secure offers from their top 3 university choices thanks to our strategic approach.'}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="py-24 bg-[#135FAE] text-white text-center">
                <div className="container mx-auto px-4">
                    <h2 className="text-3xl md:text-5xl font-extrabold mb-6">{cta.title || 'Ready to Start Your Journey?'}</h2>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto mb-10">
                        {cta.description || 'Book a free consultation with our experts and take the first step toward your dream university.'}
                    </p>
                    <Button href={cta.btn_link || '/apply-now'} variant="white" size="lg" className="shadow-xl rounded-full px-12 py-4 text-lg">
                        {cta.btn_text || 'Book Free Consultation'}
                    </Button>
                </div>
            </section>
        </main>
    );
}
