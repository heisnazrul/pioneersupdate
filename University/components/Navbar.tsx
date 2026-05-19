"use client";

import Image from 'next/image';
import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import Button from '@/components/ui/Button';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faGraduationCap, faBookOpen, faLayerGroup, faCertificate, faUserGraduate, faScroll, faBuildingColumns, faMoneyBillWave, faRankingStar, faCrown, faBook, faStar, faHandshake, faFileLines, faPassport, faHouse, faSackDollar, faUsers, faHeadset, faNewspaper, faMapMarkerAlt } from '@fortawesome/free-solid-svg-icons';
import { useAuth } from '@/context/AuthContext';
import { getUniversityBranding, type BrandingLanguage, type BrandingNavItem, type UniversityBranding } from '@/services/universityBranding';

export default function Navbar({ initialLang = 'en' }: { initialLang?: 'en' | 'ar' }) {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [expandedMobileMenu, setExpandedMobileMenu] = useState<string | null>(null);
    const pathname = usePathname();
    const router = useRouter();
    const [destinations, setDestinations] = useState<any[]>([]);
    const [offices, setOffices] = useState<any[]>([]);
    const [isLanguageMenuOpen, setIsLanguageMenuOpen] = useState(false);
    const [branding, setBranding] = useState<UniversityBranding | null>(null);
    const [failedLogoSrc, setFailedLogoSrc] = useState<string | null>(null);
    const languageMenuRef = useRef<HTMLDivElement>(null);
    const [language, setLanguage] = useState<'en' | 'ar'>(initialLang);
    const refreshRequestedRef = useRef(false);

    // Use the global Auth Context
    const { user, isAuthenticated } = useAuth();
    const isArabic = language === 'ar';
    const fallbackLanguages = [
        { code: 'en', label: 'English', flag: '/assets/flags/gb.svg' },
        { code: 'ar', label: 'Arabic', flag: '/assets/flags/sa.svg' },
    ];
    const languageOptions: Array<{ code: 'en' | 'ar'; label: string; short: string; flag: string }> = (
        branding?.header?.languages && branding.header.languages.length > 0
            ? branding.header.languages
            : fallbackLanguages
    ).map((option: BrandingLanguage) => ({
        code: (option.code === 'ar' ? 'ar' : 'en') as 'en' | 'ar',
        label: option.label || (option.code === 'ar' ? 'Arabic' : 'English'),
        short: option.code || 'en',
        flag: option.flag || (option.code === 'ar' ? '/assets/flags/sa.svg' : '/assets/flags/gb.svg'),
    }));
    const selectedLanguage = languageOptions.find((opt) => opt.code === language) || languageOptions[0];

    const applyLanguage = (nextLanguage: 'en' | 'ar') => {
        if (nextLanguage !== language) {
            setLanguage(nextLanguage);
            refreshRequestedRef.current = true;
        }
    };

    useEffect(() => {
        if (typeof window === 'undefined') return;
        localStorage.setItem('uni_language', language);
        document.cookie = `uni_language=${language}; path=/; max-age=31536000`;
        document.documentElement.lang = language;
        document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr';
        // Notify all same-tab components immediately (the native 'storage' event
        // only fires in OTHER tabs, so we dispatch a custom event here)
        window.dispatchEvent(new Event('uni:langchange'));
        if (refreshRequestedRef.current) {
            router.refresh();
            refreshRequestedRef.current = false;
        }
    }, [language, router]);

    useEffect(() => {
        const handleOutsideClick = (event: MouseEvent) => {
            if (!languageMenuRef.current) return;
            if (!languageMenuRef.current.contains(event.target as Node)) {
                setIsLanguageMenuOpen(false);
            }
        };

        document.addEventListener('mousedown', handleOutsideClick);
        return () => document.removeEventListener('mousedown', handleOutsideClick);
    }, []);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    useEffect(() => {
        const fetchNavData = async () => {
            const backend = process.env.NEXT_PUBLIC_BACKEND_URL;
            if (!backend) return;
            try {
                const query = `?lang=${encodeURIComponent(language)}`;
                const [destRes, officesData] = await Promise.all([
                    fetch(`${backend}/api/navbar/destinations${query}`).catch(() => null),
                    fetch(`${backend}/api/offices${query}`).then(res => res.json()).catch(() => null)
                ]);

                if (destRes && destRes.ok) {
                    const destJson = await destRes.json();
                    if (destJson?.data) setDestinations(destJson.data);
                }

                if (Array.isArray(officesData)) {
                    setOffices(officesData.slice(0, 3));
                } else if (officesData?.data && Array.isArray(officesData.data)) {
                    setOffices(officesData.data.slice(0, 3));
                }
            } catch (e) {
                // Silent fail in dev/offline to avoid console noise
            }
        };
        fetchNavData();
    }, [language]);

    useEffect(() => {
        const loadBranding = async () => {
            const data = await getUniversityBranding();
            if (data) {
                setBranding(data);
            }
        };
        loadBranding();
    }, []);

    useEffect(() => {
        if (isMobileMenuOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }, [isMobileMenuOpen]);

    const tLabel = (item: BrandingNavItem | undefined, fallback: string) => {
        if (!item) return fallback;
        if (isArabic) return item.ar_label || item.label || fallback;
        return item.label || item.ar_label || fallback;
    };
    const headerMainNav = branding?.header?.main_nav || [];
    const mobileNav = branding?.mobile?.nav || branding?.mobile?.drawer_links || [];
    const accountButton = branding?.header?.buttons?.account;
    const navLinks = [
        {
            key: 'destinations',
            name: isArabic ? 'الوجهات' : 'Destinations',
            path: '/destinations',
            children: destinations.length > 0 ? destinations.map(d => ({
                name: d.name,
                path: `/destinations/${d.slug}`,
                countryCode: d.country_code,
                flag: d.flag,
                pitch: d.pitch
            })) : []
        },
        {
            key: 'courses',
            name: isArabic ? 'الدورات' : 'Courses',
            path: '/search/courses',
            children: [
                {
                    name: isArabic ? 'شهادة التأسيس' : 'Foundation',
                    path: '/search/courses?level=foundation',
                    pitch: isArabic ? 'دورات التأسيس تساعد الطلاب الدوليين على الاستعداد للجامعة.' : 'Foundation courses help international students get ready for university.',
                    icon: faGraduationCap
                },
                {
                    name: isArabic ? 'درجة البكالوريوس' : 'Undergraduate Degree',
                    path: '/search/courses?level=bachelor',
                    pitch: isArabic ? 'استكشف درجات البكالوريوس في المملكة المتحدة لأي مسار مهني.' : 'Explore undergraduate degrees in the UK for almost any career path.',
                    icon: faBookOpen
                },
                {
                    name: isArabic ? 'درجات التكميل' : 'Top-Up Degrees',
                    path: '/search/courses?level=top-up',
                    pitch: isArabic ? 'تعرّف على درجات التكميل لإتمام شهادتك الجامعية.' : 'Learn about Top-Up degrees to complete your degree.',
                    icon: faLayerGroup
                },
                {
                    name: isArabic ? 'ما قبل الماجستير' : 'Pre-Masters',
                    path: '/search/courses?level=pre-masters',
                    pitch: isArabic ? 'ابحث عن برامج ما قبل الماجستير في المملكة المتحدة للتحضير للدراسات العليا.' : 'Find Pre-Masters programs in the UK to prepare you for postgraduate study.',
                    icon: faCertificate
                },
                {
                    name: isArabic ? 'درجة الماجستير' : 'Masters Degree',
                    path: '/search/courses?level=postgraduate',
                    pitch: isArabic ? 'استكشف درجات الماجستير في المملكة المتحدة للنمو الأكاديمي والمهني.' : 'Explore Master\'s degrees in the UK for further academic and career growth.',
                    icon: faUserGraduate
                },
                {
                    name: isArabic ? 'درجة الدكتوراه' : 'PhD-Degree',
                    path: '/search/courses?level=phd',
                    pitch: isArabic ? 'اكتشف فرص الدكتوراه في المملكة المتحدة للبحث العلمي المتقدم.' : 'Discover PhD opportunities in the UK for advanced research and expertise.',
                    icon: faScroll
                }
            ]
        },
        {
            key: 'universities',
            name: isArabic ? 'الجامعات' : 'Universities',
            path: '/search/universities',
            children: [
                {
                    name: isArabic ? 'البحث عن جامعة' : 'Find a University',
                    path: '/search/universities',
                    pitch: isArabic ? 'تصفح أكثر من 150 جامعة بريطانية لإيجاد المكان المثالي للدراسة.' : 'Browse over 150 top UK universities to find the perfect place to study.',
                    icon: faBuildingColumns
                },
                {
                    name: isArabic ? 'جامعات ميسورة التكلفة' : 'Affordable Universities',
                    path: '/search/universities?filter=affordable',
                    pitch: isArabic ? 'اكتشف الجامعات ذات الرسوم الدراسية المنخفضة.' : 'Discover affordable universities for low tuition fees.',
                    icon: faMoneyBillWave
                },
                {
                    name: isArabic ? 'التصنيفات' : 'Rankings',
                    path: '/search/universities?filter=rankings',
                    pitch: isArabic ? 'اختر من أفضل الجامعات البريطانية باستخدام دليل تصنيفاتنا الشامل.' : 'Choose from the best UK universities using our comprehensive rankings guide.',
                    icon: faRankingStar
                },
                {
                    name: isArabic ? 'أفضل الجامعات' : 'Top Universities',
                    path: '/search/universities?filter=top',
                    pitch: isArabic ? 'اختر من بين أرقى الجامعات البريطانية وفق تصنيفاتنا الشاملة.' : 'Choose from the best UK universities using our comprehensive rankings guide.',
                    icon: faCrown
                },
                {
                    name: isArabic ? 'أدلة التخصصات' : 'Subject Guides',
                    path: '/search/courses',
                    pitch: isArabic ? 'تصفح الجامعات حسب التخصص للعثور على الأنسب لاهتماماتك.' : 'Browse universities by subject to find the best match for your interests.',
                    icon: faBook
                },
                {
                    name: isArabic ? 'آراء الطلاب' : 'Student Reviews',
                    path: '/reviews',
                    pitch: isArabic ? 'اقرأ تقييمات الطلاب الدوليين لاتخاذ قرار مستنير.' : 'Read reviews from real international students to make an informed choice.',
                    icon: faStar
                }
            ]
        },
        {
            key: 'services',
            name: isArabic ? 'الخدمات' : 'Services',
            path: '/services',
            children: [
                {
                    name: isArabic ? 'المنح الدراسية' : 'Scholarships',
                    path: '/scholarships',
                    pitch: isArabic ? 'ابحث عن المساعدات المالية والمنح الدراسية لدعم دراستك.' : 'Find financial aid and scholarships to support your studies.',
                    icon: faSackDollar
                },
                {
                    name: isArabic ? 'خدمات التقديم' : 'Application Services',
                    path: '/services/application',
                    pitch: isArabic ? 'احصل على مساعدة متخصصة في طلب القبول الجامعي وخطاب الدوافع.' : 'Get expert help with your university application and personal statement.',
                    icon: faFileLines
                },
                {
                    name: isArabic ? 'برنامج الوكلاء' : 'Agent Program',
                    path: '/services/agents',
                    pitch: isArabic ? 'شاركنا لمساعدة الطلاب في تحقيق أحلامهم الدراسية في الخارج.' : 'Partner with us to help students achieve their study abroad dreams.',
                    icon: faHandshake
                },
                {
                    name: isArabic ? 'دعم التأشيرة' : 'Visa Support',
                    path: '/services/visa',
                    pitch: isArabic ? 'إرشادات حول متطلبات تأشيرة الطالب وإجراءات التقديم.' : 'Guidance on student visa requirements and application processes.',
                    icon: faPassport
                },
                {
                    name: isArabic ? 'السكن الطلابي' : 'Accommodation',
                    path: '/services/accommodation',
                    pitch: isArabic ? 'ابحث عن سكن طلابي آمن ومريح بالقرب من جامعتك.' : 'Find safe and comfortable student housing near your university.',
                    icon: faHouse
                },
                {
                    name: isArabic ? 'أدلة الطلاب' : 'Student Guides',
                    path: '/services/guides',
                    pitch: isArabic ? 'أدلة شاملة تساعدك على الاندماج في حياتك الطلابية الجديدة.' : 'Comprehensive guides to help you settle into your new student life.',
                    icon: faBookOpen
                }
            ]
        },
        {
            key: 'contact',
            name: isArabic ? 'تواصل معنا' : 'Contact',
            path: '/contact',
            children: [
                {
                    name: isArabic ? 'من نحن' : 'About Us',
                    path: '/about',
                    pitch: isArabic ? 'تعرّف على مهمتنا وانتشارنا العالمي.' : 'Learn more about our mission and global reach.',
                    icon: faUsers
                },
                {
                    name: isArabic ? 'تواصل معنا' : 'Contact Us',
                    path: '/contact',
                    pitch: isArabic ? 'تواصل مع فريقنا للحصول على دعم شخصي.' : 'Get in touch with our team for personalized support.',
                    icon: faHeadset
                },
                {
                    name: isArabic ? 'مدوّنتنا' : 'Our Blogs',
                    path: '/blogs',
                    pitch: isArabic ? 'ابقَ على اطلاع بأحدث أخبار الدراسة في الخارج.' : 'Stay updated with the latest study abroad news and guides.',
                    icon: faNewspaper
                },
                ...offices.map(office => ({
                    name: isArabic
                        ? `بايونيرز في ${office.country || office.city}`
                        : `Pioneers in ${office.country || office.city}`,
                    path: `/offices/${office.slug}`,
                    pitch: isArabic
                        ? (office.type ? `${office.type} في ${office.city}` : `يقع في ${office.city}`)
                        : (office.type ? `${office.type} in ${office.city}` : `Located in ${office.city}`),
                    icon: faMapMarkerAlt
                }))
            ]
        },
    ].map((item, index) => {
        const cmsItem = headerMainNav[index];
        return {
            ...item,
            name: tLabel(cmsItem, item.name),
            path: cmsItem?.url || item.path,
        };
    });


    const isHome = pathname === '/';
    const navClasses = isMobileMenuOpen
        ? 'bg-white py-4'
        : isHome
            ? isScrolled
                ? 'bg-white/70 backdrop-blur-md border-b border-white/20 shadow-sm py-3'
                : 'bg-transparent py-4'
            : `bg-white/90 backdrop-blur-md border-b border-gray-100 ${isScrolled ? 'shadow-sm py-3' : 'py-4'}`;

    const getDashboardLink = () => {
        if (!user) return '/login';
        if (user.role === 'uni_agent') return '/agent/dashboard';
        return '/student/dashboard';
    };

    const dashboardLink = getDashboardLink();

    const preferredLogoSrc = ((isArabic ? branding?.header?.logo?.ar : branding?.header?.logo?.main)
        || branding?.header?.logo?.main
        || '/logo.png');
    const logoSrc = failedLogoSrc === preferredLogoSrc ? '/logo.png' : preferredLogoSrc;

    return (
        <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${navClasses}`}>
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40 flex justify-between items-center">
                {/* Logo */}
                <Link href="/" className="z-[1002] flex items-center relative group">
                    <Image
                        src={logoSrc}
                        alt="Pioneers Admissions"
                        width={180}
                        height={50}
                        className="h-10 w-auto object-contain transition-transform group-hover:scale-105"
                        priority
                        onError={() => setFailedLogoSrc(preferredLogoSrc)}
                    />
                </Link>

                {/* Desktop Nav */}
                <div className="hidden xl:flex items-center gap-6 xl:gap-8">
                    {navLinks.map((link) => (
                        <div key={link.key} className="relative group">
                            <Link
                                href={link.path}
                                className={`text-[0.9rem] font-bold uppercase tracking-wide transition-colors hover:text-secondary flex items-center gap-1 ${pathname === link.path ? 'text-secondary' : 'text-slate-600'}`}
                            >
                                {link.name}
                                {link.children && (
                                    <FontAwesomeIcon icon={faChevronDown} className="text-xs opacity-70 group-hover:rotate-180 transition-transform duration-300" />
                                )}
                            </Link>

                            {/* Dropdown Menu */}
                            {link.children && (
                                <div className="absolute top-full left-1/2 pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform -translate-x-1/2 translate-y-2 group-hover:translate-y-0 w-[750px]">
                                    <div className="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden p-6 grid grid-cols-3 gap-4">
                                        {link.children.map((child: any) => (
                                            <Link
                                                key={child.name}
                                                href={child.path}
                                                className="flex items-start gap-4 p-3 rounded-xl hover:bg-blue-50 hover:border-blue-100 border border-transparent transition-all group/item"
                                            >
                                                {/* Icon or Flag */}
                                                <div className={`w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 relative ${child.countryCode || child.flag ? 'bg-white shadow-sm overflow-hidden h-7 w-10 mt-1' : 'bg-blue-50 text-[#135FAE]'}`}>
                                                    {child.flag ? (
                                                        <Image
                                                            src={child.flag}
                                                            alt={child.name}
                                                            width={40}
                                                            height={28}
                                                            className="object-contain w-full h-full"
                                                        />
                                                    ) : child.countryCode ? (
                                                        <Image
                                                            src={`https://flagcdn.com/w40/${child.countryCode.toLowerCase()}.png`}
                                                            alt={child.name}
                                                            width={40}
                                                            height={28}
                                                            className="object-cover w-full h-full"
                                                        />
                                                    ) : (
                                                        <FontAwesomeIcon icon={child.icon} className="text-lg" />
                                                    )}
                                                </div>

                                                {/* Content */}
                                                <div>
                                                    <h6 className="font-bold text-gray-900 group-hover/item:text-[#135FAE] transition-colors leading-tight mb-1">
                                                        {child.name}
                                                    </h6>
                                                    <p className="text-xs text-slate-500 font-medium leading-relaxed line-clamp-2">
                                                        {child.pitch}
                                                    </p>
                                                </div>
                                            </Link>
                                        ))}

                                        <div className="col-span-3 mt-2 pt-4 border-t border-gray-50 flex items-center justify-between">
                                            {link.key === 'courses' ? (
                                                <>
                                                    <span className="text-sm font-medium text-slate-500">{isArabic ? 'كل خياراتك لرحلة التعليم العالي.' : 'All your options for your Higher Education journey.'}</span>
                                                    <Link href={link.path} className="text-sm font-bold text-[#135FAE] hover:underline">
                                                        {isArabic ? 'عرض الكل' : 'View All'} &rarr;
                                                    </Link>
                                                </>
                                            ) : (
                                                <Link href={link.path} className="text-sm font-bold text-[#135FAE] hover:underline w-full text-center block">
                                                    {(isArabic ? 'عرض كل' : 'View All')} {link.name} &rarr;
                                                </Link>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                <div className="hidden xl:flex items-center gap-4">
                    <div className="relative" ref={languageMenuRef}>
                        <button
                            type="button"
                            onClick={() => setIsLanguageMenuOpen((prev) => !prev)}
                            className="inline-flex items-center gap-2 rounded-lg border border-slate-300  px-3 py-2.5 text-sm font-bold text-slate-700 hover:border-slate-400"
                            aria-label="Toggle language menu"
                        >
                            <Image src={selectedLanguage.flag} alt={selectedLanguage.label} width={18} height={14} className="h-[14px] w-[18px]  object-cover" />
                            <span>{selectedLanguage.label}</span>
                            <FontAwesomeIcon icon={faChevronDown} className={`text-sm font-bold text-slate-500 transition-transform ${isLanguageMenuOpen ? 'rotate-180' : ''}`} />
                        </button>
                        {isLanguageMenuOpen && (
                            <div className="absolute right-0 mt-2 w-40 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                {languageOptions.map((option) => (
                                    <button
                                        key={option.code}
                                        type="button"
                                        onClick={() => {
                                            applyLanguage(option.code);
                                            setIsLanguageMenuOpen(false);
                                        }}
                                        className={`flex w-full items-start gap-3 rounded-xl px-3 py-2  transition ${option.code === language ? 'bg-slate-100' : 'hover:bg-slate-50'}`}
                                    >
                                        <Image src={option.flag} alt={option.label} width={18} height={14} className="mt-0.5 h-[14px] w-[18px] rounded-[2px] object-cover" />
                                        <span className="leading-tight">
                                            <span className="block text-base font-semibold text-slate-900">{option.label}</span>
                                            <span className="block text-xs text-slate-500">{option.short}</span>
                                        </span>
                                    </button>
                                ))}
                            </div>
                        )}
                    </div>
                    {isAuthenticated ? (
                        <>
                            <Button
                                href={dashboardLink}
                                variant="ghost"
                                className="text-slate-600 font-bold text-sm border border-slate-300 rounded-lg hover:border-slate-400 backdrop-blur-sm"
                            >
                                {isArabic ? 'الحساب' : 'Account'}
                            </Button>
                        </>
                    ) : (
                        <Button href="/login" variant="ghost" className="text-slate-600 font-bold text-sm border border-slate-300 rounded-lg hover:border-slate-400 backdrop-blur-sm">
                            {isArabic ? (accountButton?.ar_label || 'تسجيل الدخول') : (accountButton?.label || 'Login')}
                        </Button>
                    )}
                    <Button href="/apply-now" variant="primary" size="md" className="uppercase tracking-wide bg-[#D32F2F] hover:bg-[#B71C1C] text-white border-none shadow-md shadow-red-500/20">
                        {isArabic ? 'قدّم الآن' : 'Apply Now'}
                    </Button>
                </div>

                {/* Mobile Toggle */}
                <button
                    className="xl:hidden p-2 z-[1002] focus:outline-none"
                    onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                    aria-label="Toggle menu"
                >
                    <div className="w-6 h-5 relative flex flex-col justify-between text-black">
                        <span className={`w-full h-0.5 bg-slate-900 rounded-full transition-all duration-300 origin-left ${isMobileMenuOpen ? 'rotate-45 translate-x-1' : ''}`}></span>
                        <span className={`w-full h-0.5 bg-slate-900 rounded-full transition-all duration-300 ${isMobileMenuOpen ? 'opacity-0' : 'opacity-100'}`}></span>
                        <span className={`w-full h-0.5 bg-slate-900 rounded-full transition-all duration-300 origin-left ${isMobileMenuOpen ? '-rotate-45 translate-x-1 ml-0.5' : ''}`}></span>
                    </div>
                </button>

                {/* Mobile Drawer Overlay */}
                <div
                    className={`xl:hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-[1000] transition-opacity duration-300 ${isMobileMenuOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}
                    onClick={() => setIsMobileMenuOpen(false)}
                />

                {/* Mobile Drawer */}
                <div className={`xl:hidden fixed inset-y-0 right-0 w-[85%] max-w-[320px] bg-white z-[1001] shadow-2xl transform transition-transform duration-300 ease-out flex flex-col ${isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'}`}>
                    <div className="flex-1 overflow-y-auto p-6 pt-24">
                        <div className="flex flex-col gap-2">
                            {navLinks.map((link, index) => (
                                <div key={link.key} className="border-b border-gray-50 last:border-0 pb-2 mb-2 last:mb-0 last:pb-0">
                                    {(() => {
                                        const mobileItem = mobileNav[index];
                                        const mobileLink = {
                                            ...link,
                                            name: tLabel(mobileItem, link.name),
                                            path: mobileItem?.url || link.path,
                                        };
                                        return mobileLink.children ? (
                                            // Accordion Item
                                            <div>
                                                <button
                                                    onClick={() => setExpandedMobileMenu(expandedMobileMenu === mobileLink.key ? null : mobileLink.key)}
                                                    className={`w-full flex items-center justify-between text-lg font-bold py-2 ${expandedMobileMenu === mobileLink.key ? 'text-[#135FAE]' : 'text-slate-700'}`}
                                                >
                                                    {mobileLink.name}
                                                    <FontAwesomeIcon
                                                        icon={faChevronDown}
                                                        className={`text-sm transition-transform duration-300 ${expandedMobileMenu === mobileLink.key ? 'rotate-180' : ''}`}
                                                    />
                                                </button>
                                                <div className={`overflow-hidden transition-all duration-300 ease-in-out ${expandedMobileMenu === mobileLink.key ? 'max-h-[500px] opacity-100' : 'max-h-0 opacity-0'}`}>
                                                    <div className="pl-4 py-2 flex flex-col gap-2">
                                                        {mobileLink.children.map((child: any) => (
                                                            <Link
                                                                key={child.path}
                                                                href={child.path}
                                                                onClick={() => setIsMobileMenuOpen(false)}
                                                                className="block py-2 text-base font-medium text-gray-500 hover:text-[#135FAE]"
                                                            >
                                                                {child.name}
                                                            </Link>
                                                        ))}
                                                        <Link
                                                            href={mobileLink.path}
                                                            onClick={() => setIsMobileMenuOpen(false)}
                                                            className="block py-2 text-base font-semibold text-[#135FAE] mt-2"
                                                        >
                                                            {(isArabic ? 'عرض كل' : 'View All')} {mobileLink.name} &rarr;
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        ) : (
                                            // Standard Link
                                            <Link
                                                href={mobileLink.path}
                                                className={`block text-lg font-bold py-2 transition-colors ${pathname === mobileLink.path ? 'text-secondary' : 'text-slate-700 hover:text-secondary'}`}
                                                onClick={() => setIsMobileMenuOpen(false)}
                                            >
                                                {mobileLink.name}
                                            </Link>
                                        );
                                    })()}
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="p-6 border-t border-gray-100 flex flex-col gap-3 bg-slate-50">
                        <div className="relative">
                            <button
                                type="button"
                                onClick={() => setIsLanguageMenuOpen((prev) => !prev)}
                                className="inline-flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700"
                                aria-label="Toggle language menu"
                            >
                                <span className="inline-flex items-center gap-2">
                                    <Image src={selectedLanguage.flag} alt={selectedLanguage.label} width={18} height={14} className="h-[14px] w-[18px] rounded-[2px] object-cover" />
                                    <span>{selectedLanguage.label}</span>
                                </span>
                                <FontAwesomeIcon icon={faChevronDown} className={`text-[10px] text-slate-500 transition-transform ${isLanguageMenuOpen ? 'rotate-180' : ''}`} />
                            </button>
                            {isLanguageMenuOpen && (
                                <div className="absolute bottom-full mb-2 w-full rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                    {languageOptions.map((option) => (
                                        <button
                                            key={option.code}
                                            type="button"
                                            onClick={() => {
                                                applyLanguage(option.code);
                                                setIsLanguageMenuOpen(false);
                                            }}
                                            className={`flex w-full items-start gap-3 rounded-xl px-3 py-2  transition ${option.code === language ? 'bg-slate-100' : 'hover:bg-slate-50'}`}
                                        >
                                            <Image src={option.flag} alt={option.label} width={18} height={14} className="mt-0.5 h-[14px] w-[18px] rounded-[2px] object-cover" />
                                            <span className="leading-tight">
                                                <span className="block text-base font-semibold text-slate-900">{option.label}</span>
                                                <span className="block text-xs text-slate-500">{option.short}</span>
                                            </span>
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>
                        {isAuthenticated ? (
                            <Button href={dashboardLink} onClick={() => { setIsMobileMenuOpen(false); }} variant="white" fullWidth className="border border-slate-200 justify-center">
                                {isArabic ? 'الحساب' : 'Account'}
                            </Button>
                        ) : (
                            <Button href="/login" onClick={() => { setIsMobileMenuOpen(false); }} variant="white" fullWidth className="border border-slate-200 justify-center">
                                {isArabic ? (accountButton?.ar_label || 'تسجيل الدخول') : (accountButton?.label || 'Login')}
                            </Button>
                        )}
                        <Button href="/apply-now" variant="primary" fullWidth onClick={() => setIsMobileMenuOpen(false)} className="justify-center bg-[#D32F2F] text-white">
                            {isArabic ? 'قدّم الآن' : 'Apply Now'}
                        </Button>
                    </div>
                </div>
            </div>
        </nav>
    );
}
