import Image from 'next/image';
import Link from 'next/link';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import Button from '@/components/ui/Button';

interface CmsContent {
    hero?: {
        badge: string;
        title: string;
        description: string;
    };
    requirements?: {
        title: string;
        items: Array<{
            title: string;
            description: string;
        }>;
    };
    process_steps?: {
        title: string;
        steps: Array<{
            number: string;
            title: string;
            description: string;
        }>;
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
    const page = await getCmsPage('applications', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'التقديمات | Pioneers Admissions' : 'Applications | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'ابدأ تقديم طلبك الجامعي.' : 'Start your university application process.')
    };
}

export default async function ApplicationsPage() {
    const lang = await getLang();
    const pageData = await getCmsPage('applications', lang);
    const content = pageData?.content as CmsContent;

    if (!content) return <div className="min-h-screen flex items-center justify-center">Failed to load content</div>;

    const { hero, requirements, process_steps, cta } = content;

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
                            <Link href="/portal/apply">
                                <Button className="rounded-full px-8 py-4 text-lg">Start Application</Button>
                            </Link>
                        </div>
                    </div>
                </section>
            )}

            {/* Application Timeline Section */}
            {process_steps && (
                <section className="py-24 bg-gray-50">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{process_steps.title}</h2>
                        </div>
                        <div className="relative">
                            {/* Line connecting steps */}
                            <div className="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-200 -translate-y-1/2 z-0"></div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                                {process_steps.steps.map((step, idx) => (
                                    <div key={idx} className="bg-white p-8 rounded-3xl border border-gray-100 shadow-lg text-center">
                                        <div className="w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold flex items-center justify-center mx-auto mb-6">
                                            {step.number}
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-3">{step.title}</h3>
                                        <p className="text-gray-500">{step.description}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>
            )}

            {/* Requirements Section */}
            {requirements && (
                <section className="py-24 relative">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="mb-16">
                            <h2 className="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{requirements.title}</h2>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {requirements.items.map((item, idx) => (
                                <div key={idx} className="flex gap-6 p-6 rounded-2xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-colors">
                                    <div className="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                        {idx + 1}
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-2">{item.title}</h3>
                                        <p className="text-gray-500">{item.description}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* CTA Section */}
            {cta && (
                <section className="py-24 bg-[#003B5C] text-white text-center">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <h2 className="text-3xl font-bold mb-6">{cta.title}</h2>
                        <p className="text-blue-100 max-w-2xl mx-auto mb-10">
                            {cta.description}
                        </p>
                        <Link href={cta.button_link}>
                            <Button className="bg-white text-[#003B5C] hover:bg-gray-100 rounded-full px-10 py-5 text-xl">{cta.button_text}</Button>
                        </Link>
                    </div>
                </section>
            )}
        </main>
    );
}
