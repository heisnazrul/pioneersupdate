import Link from 'next/link';
import Image from 'next/image';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFacebookF, faTwitter, faLinkedinIn, faInstagram } from '@fortawesome/free-brands-svg-icons';
import { getUniversityBranding, type BrandingNavItem } from '@/services/universityBranding';
import { cookies } from 'next/headers';

async function getSettings() {
    try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
        const res = await fetch(`${apiUrl}/api/settings/public`, {
            next: { revalidate: 60 }
        });
        if (!res.ok) return null;
        return await res.json();
    } catch {
        return null;
    }
}

function toLabel(item: BrandingNavItem | undefined): string {
    if (!item) return '';
    return item.label || item.ar_label || '';
}

function t(item: BrandingNavItem | undefined, isArabic: boolean): string {
    if (!item) return '';
    return isArabic
        ? (item.ar_label || item.label || '')
        : (item.label || item.ar_label || '');
}

export default async function Footer() {
    const cookieStore = await cookies();
    const isArabic = (cookieStore.get('uni_language')?.value || 'en').toLowerCase() === 'ar';

    const [settings, branding] = await Promise.all([
        getSettings(),
        getUniversityBranding(),
    ]);

    const contact = settings?.contact || {
        site_name: 'Pioneers Admissions',
        site_address: 'Riyadh, Saudi Arabia',
        site_phone: '+966 50 123 4567',
        site_email: 'info@pioneers.edu.com',
        social_links: []
    };

    const footer = branding?.footer;
    const footerLogo = footer?.logo || branding?.header?.logo?.main || '/logo.png';
    const footerColumns = footer?.columns || [];
    const destinations = footerColumns[0]?.items || [];
    const services = footerColumns[1]?.items || [];
    const contactItems = footerColumns[2]?.items || [];
    const socialLinks = footer?.social || contact.social_links || [];

    const getIcon = (platform: string) => {
        const p = (platform || '').toLowerCase();
        if (p.includes('facebook')) return faFacebookF;
        if (p.includes('twitter') || p.includes('x')) return faTwitter;
        if (p.includes('linkedin')) return faLinkedinIn;
        if (p.includes('instagram')) return faInstagram;
        return faFacebookF;
    };

    return (
        <footer className="bg-[#0B2A4A] text-white py-16 mt-auto border-t border-slate-800">
            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div>
                        <Link href="/" className="inline-flex mb-6">
                            <Image
                                src={footerLogo}
                                alt={isArabic ? (footer?.ar_brand || footer?.brand || 'Pioneers Admissions') : (footer?.brand || 'Pioneers Admissions')}
                                width={220}
                                height={52}
                                className="h-10 w-auto object-contain"
                            />
                        </Link>
                        <p className="text-slate-300 leading-relaxed mb-8 text-sm">
                            {isArabic
                                ? (footer?.ar_description || footer?.description || 'شريكك الموثوق للتعليم الدولي. نرشدك من اختيار الجامعة حتى القبول والتأشيرة باحترافية.')
                                : (footer?.description || 'Your trusted partner for international education. We guide you from university selection to visa approval with integrity and excellence.')}
                        </p>
                        <div className="flex gap-4">
                            {socialLinks.map((link: any, idx: number) => (
                                <a
                                    key={idx}
                                    href={link.url || '#'}
                                    className="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/20 transition-all group text-slate-300 hover:text-white"
                                    aria-label={link.platform}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <FontAwesomeIcon icon={getIcon(link.platform || '')} className="w-5 h-5" />
                                </a>
                            ))}
                        </div>
                    </div>

                    <div>
                        <h4 className="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6">{isArabic ? (footerColumns[0]?.ar_title || footerColumns[0]?.title || 'أفضل الوجهات') : (footerColumns[0]?.title || 'Top Destinations')}</h4>
                        <ul className="space-y-4">
                            {destinations.length > 0 ? destinations.map((item: BrandingNavItem, i: number) => (
                                <li key={i}>
                                    <Link href={item.url || '#'} className="text-sm text-slate-300 hover:text-white transition-colors">
                                        {t(item, isArabic)}
                                    </Link>
                                </li>
                            )) : (
                                <>
                                    <li><Link href="/destinations/usa" className="text-sm text-slate-300 hover:text-white transition-colors">Study in USA</Link></li>
                                    <li><Link href="/destinations/uk" className="text-sm text-slate-300 hover:text-white transition-colors">Study in UK</Link></li>
                                    <li><Link href="/destinations/canada" className="text-sm text-slate-300 hover:text-white transition-colors">Study in Canada</Link></li>
                                    <li><Link href="/destinations/australia" className="text-sm text-slate-300 hover:text-white transition-colors">Study in Australia</Link></li>
                                </>
                            )}
                        </ul>
                    </div>

                    <div>
                        <h4 className="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6">{isArabic ? (footerColumns[1]?.ar_title || footerColumns[1]?.title || 'خدماتنا') : (footerColumns[1]?.title || 'Our Services')}</h4>
                        <ul className="space-y-4">
                            {services.length > 0 ? services.map((item: BrandingNavItem, i: number) => (
                                <li key={i}>
                                    <Link href={item.url || '#'} className="text-sm text-slate-300 hover:text-white transition-colors">
                                        {t(item, isArabic)}
                                    </Link>
                                </li>
                            )) : (
                                <>
                                    <li><Link href="/services/application" className="text-sm text-slate-300 hover:text-white transition-colors">Admission Counseling</Link></li>
                                    <li><Link href="/services/visa" className="text-sm text-slate-300 hover:text-white transition-colors">Visa Assistance</Link></li>
                                    <li><Link href="/scholarships" className="text-sm text-slate-300 hover:text-white transition-colors">Scholarship Guidance</Link></li>
                                    <li><Link href="/services/agents" className="text-sm text-slate-300 hover:text-white transition-colors">Partner with Us</Link></li>
                                </>
                            )}
                        </ul>
                    </div>

                    <div>
                        <h4 className="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6">{isArabic ? (footerColumns[2]?.ar_title || footerColumns[2]?.title || 'تواصل معنا') : (footerColumns[2]?.title || 'Contact Us')}</h4>
                        <div className="space-y-4 text-sm text-slate-300">
                            {contactItems.length > 0 ? (
                                <>
                                    {contactItems.slice(0, 3).map((item: BrandingNavItem, i: number) => (
                                        <p key={i} className="flex items-center">
                                            {t(item, isArabic)}
                                        </p>
                                    ))}
                                    <Link href={contactItems[3]?.url || '/contact'} className="inline-block mt-4 text-white font-semibold border-b border-accent hover:border-white transition-colors pb-0.5">
                                        {t(contactItems[3], isArabic) || (isArabic ? 'احصل على الاتجاهات' : 'Get Directions')} →
                                    </Link>
                                </>
                            ) : (
                                <>
                                    <p className="flex items-start">{contact.site_address || contact.address}</p>
                                    <p className="flex items-center">{contact.site_phone || contact.phone}</p>
                                    <p className="flex items-center">{contact.site_email || contact.email}</p>
                                    <Link href="/contact" className="inline-block mt-4 text-white font-semibold border-b border-accent hover:border-white transition-colors pb-0.5">
                                        Get Directions →
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>

                <div className="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
                    <p>
                        {isArabic
                            ? (footer?.ar_copyright || footer?.copyright || `© ${new Date().getFullYear()} جميع الحقوق محفوظة`)
                            : (footer?.copyright || `© ${new Date().getFullYear()} ${contact.site_name || 'Pioneers Admissions'}. All rights reserved.`)}
                    </p>
                    <div className="flex gap-8">
                        <Link href="/privacy-policy" className="hover:text-slate-300 transition-colors">{isArabic ? 'سياسة الخصوصية' : 'Privacy Policy'}</Link>
                        <Link href="/terms-of-service" className="hover:text-slate-300 transition-colors">{isArabic ? 'شروط الخدمة' : 'Terms of Service'}</Link>
                        <Link href="/cookie-policy" className="hover:text-slate-300 transition-colors">{isArabic ? 'سياسة ملفات الارتباط' : 'Cookie Policy'}</Link>
                    </div>
                </div>
            </div>
        </footer>
    );
}
