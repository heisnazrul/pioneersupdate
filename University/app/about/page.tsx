import Image from 'next/image';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faQuoteLeft } from '@fortawesome/free-solid-svg-icons';
import { getCmsPage } from '@/services/cms';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('about', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'من نحن | Pioneers Admissions' : 'About Us | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'مرحبًا بكم في استشارات بايونيرز التعليمية.' : 'Welcome to Pioneers Educational Admission Consultancy.'),
    };
}

export default async function AboutPage() {
    const lang = await getLang();
    const page = await getCmsPage('about', lang);
    const content = page?.content || {};

    // Helper to render newlines as paragraphs
    const renderParagraphs = (paragraphs: string[]) => {
        return paragraphs?.map((p, index) => (
            <p key={index}>{p}</p>
        ));
    };

    return (
        <main className="bg-white min-h-screen pb-20">
            {/* Hero */}
            <div className="bg-[#003B5C] text-white py-24 relative overflow-hidden">
                <div className="absolute inset-0 opacity-10 bg-[url('/pattern.png')]"></div>
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10 text-center">
                    <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 backdrop-blur-sm text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                        {content.hero?.badge || 'Who We Are'}
                    </span>
                    <h1 className="text-5xl md:text-6xl font-extrabold mb-6">{content.hero?.title || 'About Pioneers'}</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto font-light">
                        {content.hero?.description || 'Transforming lives through international education since 2012.'}
                    </p>
                </div>
            </div>

            {/* Director's Message */}
            <section className="py-24">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="flex flex-col lg:flex-row items-center gap-16">
                        <div className="lg:w-2/5 relative">
                            <div className="relative h-[500px] w-full rounded-2xl overflow-hidden shadow-2xl bg-gray-100">
                                <Image
                                    src={content.director_message?.image || "https://placehold.co/600x800?text=Director"}
                                    alt={content.director_message?.name || "Director"}
                                    fill
                                    className="object-cover"
                                    unoptimized
                                />
                                <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8 text-white">
                                    <h3 className="text-2xl font-bold">{content.director_message?.name || 'Md Abdul Qaium'}</h3>
                                    <p className="opacity-90">{content.director_message?.role || 'Director'}</p>
                                </div>
                            </div>
                            <div className="absolute -top-6 -left-6 text-6xl text-blue-100 -z-10">
                                <FontAwesomeIcon icon={faQuoteLeft} />
                            </div>
                        </div>
                        <div className="lg:w-3/5">
                            <h2 className="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">{content.director_message?.title || 'Welcome to Pioneers EDU'}</h2>
                            <div className="space-y-4 text-lg text-slate-600 leading-relaxed text-justify">
                                {content.director_message?.paragraphs ? renderParagraphs(content.director_message.paragraphs) : (
                                    <>
                                        <p>Dear Valued Partners, Students, and Stakeholders,</p>
                                        <p>Welcome to Pioneers Educational Admission Consultancy (PEAC). From humble beginnings, we have grown into a global organization dedicated to transforming lives through international education.</p>
                                        <p>Our team of highly experienced representatives provides expertguidance. This unique blend of expertise and empathy sets us apart.</p>
                                        <p>We are proud of our high visa success rates and the trust placed in us by students and partners.</p>
                                    </>
                                )}
                                <p className="pt-4 font-bold text-slate-900">
                                    {content.director_message?.closing?.text || 'Warm regards,'}<br />
                                    {content.director_message?.closing?.name || 'Md Abdul Qaium'}<br />
                                    <span className="text-sm font-normal text-slate-500">{content.director_message?.closing?.position || 'Director, Pioneers Educational Admission Consultancy Ltd'}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CEO's Message */}
            <section className="py-24 bg-slate-50 border-t border-slate-200">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="flex flex-col lg:flex-row-reverse items-center gap-16">
                        <div className="lg:w-2/5 relative">
                            <div className="relative h-[500px] w-full rounded-2xl overflow-hidden shadow-2xl bg-gray-100">
                                <Image
                                    src={content.ceo_message?.image || "https://placehold.co/600x800?text=CEO"}
                                    alt={content.ceo_message?.name || "CEO"}
                                    fill
                                    className="object-cover"
                                    unoptimized
                                />
                                <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8 text-white">
                                    <h3 className="text-2xl font-bold">{content.ceo_message?.name || 'Hanan Asiri'}</h3>
                                    <p className="opacity-90">{content.ceo_message?.role || 'CEO'}</p>
                                </div>
                            </div>
                            <div className="absolute -top-6 -right-6 text-6xl text-blue-100 -z-10">
                                <FontAwesomeIcon icon={faQuoteLeft} />
                            </div>
                        </div>
                        <div className="lg:w-3/5">
                            <h2 className="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">{content.ceo_message?.title || 'A Message from the CEO'}</h2>
                            <div className="space-y-4 text-lg text-slate-600 leading-relaxed text-justify">
                                {content.ceo_message?.paragraphs ? renderParagraphs(content.ceo_message.paragraphs) : (
                                    <>
                                        <p>Dear Students, Parents, and Collaborators,</p>
                                        <p>As CEO of Pioneers Edu, I am honored to oversee an organization that prioritizes educational excellence and student success.</p>
                                        <p>At Pioneers EDU, we believe in creating opportunities through innovation, collaboration, and integrity.</p>
                                        <p>Thank you for trusting us to be part of your journey.</p>
                                    </>
                                )}
                                <p className="pt-4 font-bold text-slate-900">
                                    {content.ceo_message?.closing?.text || 'Warm regards,'}<br />
                                    {content.ceo_message?.closing?.name || 'Hanan Asiri'}<br />
                                    <span className="text-sm font-normal text-slate-500">{content.ceo_message?.closing?.position || 'CEO, Pioneers Educational Admission Consultancy Ltd'}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Team Section */}
            <section className="py-24">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="text-center mb-16">
                        <span className="text-blue-600 font-bold tracking-widest uppercase text-sm mb-2 block">{content.team?.badge || 'Our Experts'}</span>
                        <h2 className="text-4xl font-extrabold text-slate-900">{content.team?.title || 'Meet Our Team'}</h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        {(content.team?.members || []).map((member: any, idx: number) => (
                            <div key={idx} className="group bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                                <div className="h-80 w-full relative bg-gray-200 overflow-hidden">
                                    <Image
                                        src={member.image || `https://placehold.co/400x500?text=${member.name}`}
                                        alt={member.name}
                                        fill
                                        className="object-cover group-hover:scale-110 transition-transform duration-500"
                                        unoptimized
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                <div className="p-6 text-center">
                                    <h3 className="text-lg font-bold text-slate-900 mb-1">{member.name}</h3>
                                    <p className="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">{member.role}</p>
                                    <p className="text-sm text-slate-500 leading-relaxed">{member.desc}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </main>
    );
}
