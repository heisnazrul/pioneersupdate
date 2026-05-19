import { getCmsPage } from '@/services/cms';
import { getPublicSettings } from '@/services/settings';
import { getOffices } from '@/services/offices';
import LeadForm from '@/components/LeadForm';
import Link from 'next/link';
import Image from 'next/image';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMapMarkerAlt, faPhone, faEnvelope, faClock, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faFacebookF, faTwitter, faInstagram, faLinkedinIn, faYoutube, faTiktok } from '@fortawesome/free-brands-svg-icons';
import { getLang } from '@/lib/getLang';
import { t } from '@/lib/i18n';

export async function generateMetadata() {
    const lang = await getLang();
    const page = await getCmsPage('contact', lang);
    return {
        title: page?.meta_title || (lang === 'ar' ? 'تواصل معنا | Pioneers Admissions' : 'Contact Us | Pioneers Admissions'),
        description: page?.meta_description || (lang === 'ar' ? 'تواصل مع فريقنا.' : 'Get in touch with our team.'),
    };
}

export default async function ContactPage() {
    const lang = await getLang();
    const [page, settings, offices] = await Promise.all([
        getCmsPage('contact', lang),
        getPublicSettings(),
        getOffices()
    ]);

    const content = page?.content || {};
    const hero = content.hero || {};
    // Use settings for contact info, fallback to CMS if needed or empty
    const contactInfo: any = settings.contact || content.contact_info || {};

    // Use API offices, fallback to CMS offices if API returns empty (optional, but API should work)
    const rawOffices = Array.isArray(offices) ? offices : [];
    const officeList = rawOffices.length > 0 ? rawOffices : (Array.isArray(content.offices) ? content.offices : []);

    return (
        <main className="pb-20 bg-white">
            {/* Hero */}
            <header className="bg-[#003B5C] text-white py-24 relative overflow-hidden">
                <div className="absolute inset-0 opacity-10 bg-[url('/pattern.png')]"></div>
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10 text-center">
                    <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 backdrop-blur-sm text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                        {hero.badge || "We're Here for You"}
                    </span>
                    <h1 className="text-5xl md:text-6xl font-extrabold mb-6 tracking-tight">{hero.title || 'Contact Us'}</h1>
                    <p className="text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                        {hero.description || "Whether you have a question about universities, visas, or just want to say hello, we're ready to answer all your questions."}
                    </p>
                </div>
            </header>

            {/* Offices Grid */}
            <section className="py-24">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">{lang === 'ar' ? 'مكاتبنا حول العالم' : 'Our Global Offices'}</h2>
                        <p className="text-gray-500 max-w-2xl mx-auto">
                            {lang === 'ar' ? 'زورنا في أحد مكاتبنا للتشاور وجهًا لوجه.' : 'Visit us at one of our offices for a face-to-face consultation.'}
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                        {officeList.map((office: any) => (
                            <div key={office.id || office.slug} className="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">
                                <div className="h-48 relative overflow-hidden bg-gray-100">
                                    {office.image ? (
                                        <Image
                                            src={office.image}
                                            alt={office.city}
                                            fill
                                            className="object-cover group-hover:scale-105 transition-transform duration-500"
                                            unoptimized
                                        />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-gray-300">
                                            <FontAwesomeIcon icon={faMapMarkerAlt} size="3x" />
                                        </div>
                                    )}
                                    <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow-sm">
                                        {office.type}
                                    </div>
                                </div>
                                <div className="p-6 flex-1 flex flex-col">
                                    <h3 className="text-xl font-bold text-gray-900 mb-1">{office.city}</h3>
                                    <p className="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-4">{office.country}</p>

                                    <div className="space-y-3 mb-6 flex-1">
                                        <div className="flex items-start gap-3">
                                            <FontAwesomeIcon icon={faMapMarkerAlt} className="text-gray-400 mt-1 w-4" />
                                            <p className="text-sm text-gray-600 leading-snug">{office.address}</p>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <FontAwesomeIcon icon={faPhone} className="text-gray-400 w-4" />
                                            <p className="text-sm text-gray-600">{office.phone}</p>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <FontAwesomeIcon icon={faEnvelope} className="text-gray-400 w-4" />
                                            <p className="text-sm text-gray-600 break-all">{office.email}</p>
                                        </div>
                                    </div>

                                    <Link
                                        href={`/offices/${office.slug}`}
                                        className="w-full py-3 rounded-xl bg-gray-50 text-gray-900 font-bold text-sm hover:bg-[#003B5C] hover:text-white transition-all text-center flex items-center justify-center gap-2 group-hover:gap-3"
                                    >
                                        {lang === 'ar' ? 'عرض تفاصيل المكتب' : 'View Office Details'} <FontAwesomeIcon icon={faArrowRight} />
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* General Enquiries & Form */}
            <section className="py-24 bg-gray-50">
                <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-20">
                        <div>
                            <span className="text-blue-600 font-bold tracking-wider uppercase text-sm mb-2 block">General Inquiries</span>
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-6">Get in Touch</h2>
                            <p className="text-gray-500 text-lg mb-10 leading-relaxed">
                                {contactInfo.contact_description || contactInfo.description || "Can't make it to an office? No problem. Fill out the form or reach out to us directly through our general channels."}
                            </p>

                            <div className="space-y-8 mb-12">
                                <div className="flex gap-4">
                                    <div className="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl flex-shrink-0">
                                        <FontAwesomeIcon icon={faEnvelope} />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-gray-900 text-lg mb-1">Email Us</h4>
                                        <p className="text-gray-500 mb-1">For general questions and support:</p>
                                        <a href={`mailto:${contactInfo.site_email || contactInfo.email}`} className="text-blue-600 font-semibold hover:underline text-lg">{contactInfo.site_email || contactInfo.email || 'info@pioneers.edu.sa'}</a>
                                    </div>
                                </div>
                                <div className="flex gap-4">
                                    <div className="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl flex-shrink-0">
                                        <FontAwesomeIcon icon={faPhone} />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-gray-900 text-lg mb-1">Call Us</h4>
                                        <p className="text-gray-500 mb-1">Mon-Fri from 9am to 6pm:</p>
                                        <a href={`tel:${contactInfo.site_phone || contactInfo.phone}`} className="text-blue-600 font-semibold hover:underline text-lg">{contactInfo.site_phone || contactInfo.phone || '+966 50 123 4567'}</a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 className="font-bold text-gray-900 text-lg mb-4">Follow Us</h4>
                                <div className="flex gap-4">
                                    {(contactInfo.social_links || []).map((link: any) => {
                                        const p = (link.platform || '').toLowerCase();
                                        let icon = faFacebookF;
                                        if (p.includes('facebook')) icon = faFacebookF;
                                        else if (p.includes('twitter') || p.includes('x')) icon = faTwitter;
                                        else if (p.includes('instagram')) icon = faInstagram;
                                        else if (p.includes('linkedin')) icon = faLinkedinIn;
                                        else if (p.includes('youtube')) icon = faYoutube;
                                        else if (p.includes('tiktok')) icon = faTiktok;

                                        return (
                                            <a
                                                key={link.platform}
                                                href={link.url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="w-12 h-12 rounded-full border border-gray-200 text-gray-400 flex items-center justify-center hover:bg-[#003B5C] hover:text-white hover:border-[#003B5C] transition-all duration-300 shadow-sm hover:shadow-lg"
                                                aria-label={link.platform}
                                            >
                                                <FontAwesomeIcon icon={icon} className="text-xl" />
                                            </a>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>

                        <div className="w-full">
                            <LeadForm title="Send us a Message" />
                        </div>
                    </div>
                </div>
            </section>
        </main>
    );
}
