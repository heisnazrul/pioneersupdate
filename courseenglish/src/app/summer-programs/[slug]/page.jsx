"use client";

import { useEffect, useState, use, useMemo } from "react";
import Image from "next/image";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar, faCalendarAlt, faUser, faMapMarkerAlt, faBookOpen, faCheckCircle, faShare, faHeart, faBed, faGlobe, faChevronDown, faChevronUp, faShieldAlt, faFutbol, faGraduationCap, faExchangeAlt, faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/app/components/HeroDropdown";
import HeroDatePicker from "@/app/components/HeroDatePicker";

import { useApi, formatCurrency, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

export default function SummerCampDetailPage({ params }) {
    const { slug } = use(params);
    const router = useRouter();
    const { currency, language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const { toggleWishlist, isInWishlist, toggleCompare, isInCompare } = useCourseEnglishInteractions();

    // API returns { school: {...}, camps: [...] }
    const { data, loading, error } = useApi(`/courseenglish/summer-programs/${slug}`);

    // Helpers
    const t = (en, ar) => (isArabic && ar ? ar : en || "");

    const searchParams = useSearchParams();
    const campIdParam = searchParams.get('camp');

    const [selectedCampId, setSelectedCampId] = useState(null);
    const [weeks, setWeeks] = useState(1);
    const [startDate, setStartDate] = useState(null);
    const [toastMsg, setToastMsg] = useState(null);

    const school = data?.school;
    const camps = data?.camps || [];

    // Helpers
    const showToast = (msg) => {
        setToastMsg(msg);
        setTimeout(() => setToastMsg(null), 2500);
    };

    const handleShare = () => {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showToast(isArabic ? 'تم نسخ الرابط!' : 'Link copied!');
        });
    };

    const handleToggleWishlist = async () => {
        if (!selectedCamp.id) return;
        const result = await toggleWishlist('summer_camps', selectedCamp.id);
        if (result) showToast(isArabic ? 'تم التحديث!' : (isInWishlist('summer_camps', selectedCamp.id) ? 'Removed from Favorites' : 'Added to Favorites'));
    };

    const handleToggleCompare = async () => {
        if (!selectedCamp.id) return;
        const result = await toggleCompare('summer_camps', selectedCamp.id);
        if (result) showToast(isArabic ? 'تم التحديث!' : (isInCompare('summer_camps', selectedCamp.id) ? 'Removed from Compare' : 'Added to Compare'));
    };

    // Initialize selection
    useEffect(() => {
        if (camps.length > 0 && !selectedCampId) {
            if (campIdParam) {
                const paramCamp = camps.find(c => c.id == campIdParam);
                if (paramCamp) {
                    setSelectedCampId(paramCamp.id);
                    return;
                }
            }
            setSelectedCampId(camps[0].id);
        }
    }, [camps, campIdParam, selectedCampId]);

    const selectedCamp = useMemo(() => {
        return camps.find(c => c.id === selectedCampId) || camps[0];
    }, [camps, selectedCampId]);

    useEffect(() => {
        if (selectedCamp && selectedCamp.start_date) {
            setStartDate(new Date(selectedCamp.start_date));
        } else {
            setStartDate(null);
        }
    }, [selectedCamp]);

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-50">
                <div className="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-[#0057B7]" />
            </div>
        );
    }

    if (error || !school) {
        return <div className="p-20 text-center text-red-500">Failed to load summer camp details.</div>;
    }

    const campPrice = selectedCamp ? (currency === "SAR" ? selectedCamp.price_sar : selectedCamp.price_gbp) : 0;
    const totalPrice = campPrice ? campPrice * weeks : 0;
    const priceLabel = formatCurrency(totalPrice, currency) || "-";

    // School Location Label
    const locationLabel = school ? `${school.city || ''}, ${school.country || ''}` : "";
    const schoolDisplayName = isArabic
        ? `${school.ar_name || ''}`
        : `${school.name}`;

    const handleBookNow = () => {
        if (!startDate) {
            alert(isArabic ? "يرجى اختيار تاريخ البدء" : "Please select a start date");
            return;
        }

        const year = startDate.getFullYear();
        const month = String(startDate.getMonth() + 1).padStart(2, '0');
        const day = String(startDate.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;

        const query = new URLSearchParams({
            camp_id: selectedCamp.id,
            weeks: weeks,
            start_date: dateString,
            type: "summer_camps"
        }).toString();

        router.push(`/summer-programs/${slug}/booking?${query}`);
    };

    return (
        <main className="min-h-screen bg-[#F8FAFC] pb-20" dir={isArabic ? "rtl" : "ltr"}>

            {/* ─── DESKTOP HERO SECTION (School Level) ─── */}
            <div className="relative hidden w-full lg:block h-[500px]">
                {/* Hero Image (School Cover) */}
                <div className="absolute inset-0">
                    {school.image ? (
                        <Image src={getImageUrl(school.image)} alt={t(school.name, school.ar_name)} fill className="object-cover" priority unoptimized />
                    ) : (
                        <div className="w-full h-full bg-slate-900" />
                    )}
                    {/* Gradient Overlay */}
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-slate-900/10" />
                </div>

                {/* Desktop Navigation (Absolute Top) */}
                <div className="absolute top-0 left-0 w-full z-30 p-6">
                    <div className="container mx-auto flex items-center justify-between">
                        <Link href="/summer-programs" className="inline-flex items-center gap-2 rounded-full bg-black/20 backdrop-blur-md px-4 py-2 text-sm font-medium text-white transition hover:bg-black/40 border border-white/10">
                            <FontAwesomeIcon icon={faArrowLeft} className={`text-white h-4 w-4 ${isArabic ? "rotate-180" : ""}`} />
                            {isArabic ? "العودة إلى القائمة" : "Back to List"}
                        </Link>

                        {/* Desktop Action Buttons */}
                        <div className="flex items-center gap-3">
                            <button onClick={handleToggleCompare} className={`flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition backdrop-blur-md border ${selectedCamp.id && isInCompare('summer_camps', selectedCamp.id) ? 'bg-[#0057B7] border-[#0057B7] text-white' : 'bg-black/20 border-white/10 text-white hover:bg-black/40'}`}>
                                <FontAwesomeIcon icon={faExchangeAlt} className="h-4 w-4" />
                                <span className="hidden xl:inline">{isArabic ? "مقارنة" : "Compare"}</span>
                            </button>
                            <button onClick={handleToggleWishlist} className={`flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition backdrop-blur-md border ${selectedCamp.id && isInWishlist('summer_camps', selectedCamp.id) ? 'bg-red-500 border-red-500 text-white' : 'bg-black/20 border-white/10 text-white hover:bg-black/40'}`}>
                                <FontAwesomeIcon icon={faHeart} className="h-4 w-4" />
                                <span className="hidden xl:inline">{isArabic ? "مفضلة" : "Wishlist"}</span>
                            </button>
                            <button onClick={handleShare} className="flex items-center gap-2 rounded-full bg-black/20 backdrop-blur-md px-4 py-2 text-sm font-medium text-white transition hover:bg-black/40 border border-white/10">
                                <FontAwesomeIcon icon={faShare} className="h-4 w-4" />
                                <span className="hidden xl:inline">{isArabic ? "مشاركة" : "Share"}</span>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Toast Notification */}
                {toastMsg && (
                    <div className="fixed top-24 left-1/2 -translate-x-1/2 z-50 whitespace-nowrap rounded-lg bg-slate-900 px-6 py-3 text-sm font-medium text-white shadow-xl animate-fade-in-down">
                        {toastMsg}
                    </div>
                )}

                {/* Hero Content (Bottom Left) */}
                <div className="absolute bottom-0 left-0 w-full z-20 pb-12">
                    <div className="container mx-auto px-4">
                        <div className="max-w-4xl">
                            <div className="mb-4 flex items-center gap-3">
                                {school.logo && (
                                    <div className="h-16 w-16 rounded-full bg-white p-1">
                                        <Image src={getImageUrl(school.logo)} width={64} height={64} alt="Logo" className="h-full w-full rounded-full object-contain" unoptimized />
                                    </div>
                                )}
                                <span className="inline-block rounded-full bg-[#0057B7] px-3 py-1 text-xs font-medium text-white shadow-lg">
                                    {isArabic ? "مخيم صيفي" : "Summer Camp"}
                                </span>
                            </div>

                            <h1 className="text-4xl font-semibold text-white md:text-5xl leading-tight mb-4 drop-shadow-md">
                                {schoolDisplayName}
                            </h1>

                            <div className="flex flex-wrap items-center gap-6 text-white/90 font-medium text-sm md:text-base">
                                <div className="flex items-center gap-2">
                                    <div className="h-10 w-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center">
                                        <FontAwesomeIcon icon={faMapMarkerAlt} />
                                    </div>
                                    <span>{locationLabel}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <div className="h-10 w-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-[#F59E0B]">
                                        <FontAwesomeIcon icon={faStar} />
                                    </div>
                                    <span>{school?.rating || 5.0} / 5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ─── MOBILE HERO SECTION ─── */}
            <div className="lg:hidden relative">
                <div className="relative aspect-[4/3] w-full bg-slate-900">
                    {school.image && <Image src={getImageUrl(school.image)} alt={t(school.name, school.ar_name)} fill className="object-cover" priority unoptimized />}

                    {/* Actions */}
                    <div className="absolute top-0 left-0 w-full p-4 flex items-center justify-between z-10 pt-12">
                        <div className="flex items-center gap-3">
                            <button onClick={handleShare} className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition active:scale-95">
                                <FontAwesomeIcon icon={faShare} />
                            </button>
                            <button onClick={handleToggleWishlist} className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition active:scale-95 ${selectedCamp.id && isInWishlist('summer_camps', selectedCamp.id) ? 'text-red-500' : 'text-slate-700'}`}>
                                <FontAwesomeIcon icon={faHeart} />
                            </button>
                            <button onClick={handleToggleCompare} className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition active:scale-95 ${selectedCamp.id && isInCompare('summer_camps', selectedCamp.id) ? 'text-[#0057B7]' : 'text-slate-700'}`}>
                                <FontAwesomeIcon icon={faExchangeAlt} />
                            </button>
                        </div>
                        <Link href="/summer-programs" className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition active:scale-95">
                            <FontAwesomeIcon icon={faArrowLeft} className={`text-slate-700 h-5 w-5 ${isArabic ? "rotate-180" : ""}`} />
                        </Link>
                    </div>

                    {/* Logo Overlay */}
                    <div className="absolute -bottom-12 left-1/2 -translate-x-1/2 z-20">
                        <div className="h-24 w-24 rounded-full border-[4px] border-white bg-white shadow-md overflow-hidden flex items-center justify-center">
                            {school.logo ? <Image src={getImageUrl(school.logo)} alt="Logo" width={80} height={80} className="object-contain" unoptimized /> : <span className="font-medium text-slate-300">LOGO</span>}
                        </div>
                    </div>
                </div>

                {/* Mobile Header Data */}
                <div className="relative z-10 -mt-12 rounded-t-[30px] bg-white pt-16 px-4 text-center pb-6 shadow-sm mb-6">
                    <h1 className="text-2xl font-semibold text-slate-900 leading-tight mb-2">
                        {schoolDisplayName}
                    </h1>
                    <div className="text-sm font-medium text-slate-500 mb-4">{locationLabel}</div>

                    <div className="flex flex-wrap justify-center gap-2">
                        <span className="rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 flex items-center gap-1"><FontAwesomeIcon icon={faStar} className="text-amber-400" /> {school?.rating}</span>
                    </div>
                </div>
            </div>


            <div className="container mx-auto px-4 relative z-20 pt-8 lg:pt-12">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {/* ─── LEFT COLUMN (Camp List Accordion) ─── */}
                    <div className="lg:col-span-8 space-y-6">
                        <div className="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 lg:bg-transparent lg:p-0 lg:border-0">
                            <h2 className="text-xl md:text-2xl font-semibold text-slate-900 mb-2">{isArabic ? "برامجنا الصيفية" : "Our Summer Programs"}</h2>
                            <p className="text-slate-500 text-sm">
                                {isArabic ? "اضغط على البرنامج لعرض التفاصيل الكاملة واختياره" : "Click on a program to view full details and select it for booking."}
                            </p>
                        </div>

                        {camps.map((campItem) => {
                            const isSelected = selectedCampId === campItem.id;
                            const details = campItem.camp_details || {};
                            const itemPrice = currency === "SAR" ? campItem.price_sar : campItem.price_gbp;

                            return (
                                <div
                                    key={campItem.id}
                                    id={`camp-${campItem.id}`}
                                    onClick={() => setSelectedCampId(campItem.id)}
                                    className={`group rounded-3xl bg-white transition-all cursor-pointer overflow-hidden border-2 ${isSelected ? 'border-[#0057B7] shadow-xl shadow-blue-900/5' : 'border-transparent shadow-sm hover:border-slate-200'}`}
                                >
                                    {/* Card Header (Always Visible) */}
                                    <div className="p-6 flex flex-col md:flex-row gap-6">
                                        {/* Thumbnail */}
                                        <div className="shrink-0 relative w-full md:w-32 h-32 rounded-2xl overflow-hidden bg-slate-100">
                                            {campItem.image ? (
                                                <Image src={getImageUrl(campItem.image)} alt={t(campItem.name, campItem.ar_name)} fill className="object-cover" unoptimized />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-slate-300"><FontAwesomeIcon icon={faGlobe} /></div>
                                            )}
                                        </div>

                                        {/* Info */}
                                        <div className="flex-1">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    {campItem.tag && <span className="inline-block mb-2 rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-medium uppercase tracking-wide text-blue-600">{t(campItem.tag, campItem.tag_ar)}</span>}
                                                    <h3 className="text-xl font-medium text-slate-900 mb-2 leading-tight">{t(campItem.name, campItem.ar_name)}</h3>
                                                </div>
                                                <div className="text-right shrink-0">
                                                    <div className="text-lg font-semibold text-[#0057B7]">{formatCurrency(itemPrice, currency)}</div>
                                                    <div className="text-[10px] font-medium text-slate-400 uppercase">{t(campItem.fee_type, campItem.fee_type) || "Weekly"}</div>
                                                </div>
                                            </div>

                                            <div className="flex flex-wrap gap-4 mt-2 text-sm text-slate-600">
                                                <div className="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg">
                                                    <FontAwesomeIcon icon={faUser} className="text-[#0057B7] text-xs" />
                                                    <span className="font-medium">{campItem.age_range}</span>
                                                    <span className="text-xs opacity-70">{isArabic ? "سنة" : "years"}</span>
                                                </div>
                                                <div className="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg">
                                                    <FontAwesomeIcon icon={faCalendarAlt} className="text-[#0057B7] text-xs" />
                                                    <span>{campItem.start_date ? new Date(campItem.start_date).toLocaleDateString() : "Flexible"}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Chevron */}
                                        <div className={`absolute top-6 ${isArabic ? 'left-6' : 'right-6'} transition-transform duration-300 ${isSelected ? 'rotate-180' : ''}`}>
                                            <FontAwesomeIcon icon={faChevronDown} className="text-slate-300" />
                                        </div>
                                    </div>

                                    {/* Expanded Details */}
                                    <div className={`overflow-hidden transition-all duration-500 ease-in-out ${isSelected ? 'max-h-[2000px] opacity-100' : 'max-h-0 opacity-0'}`}>
                                        <div className="p-6 pt-0 border-t border-slate-100 mt-2">

                                            {/* Description / Overview */}
                                            <div className="mt-6 prose prose-slate prose-sm max-w-none text-slate-600">
                                                <p>{t(campItem.description, campItem.ar_description)}</p>
                                                {details.overview && <p className="mt-4">{t(details.overview, details.ar_overview)}</p>}
                                            </div>

                                            {/* Camp Gallery (New) */}
                                            {details.images && details.images.length > 0 && (
                                                <div className="mt-8">
                                                    <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-4">
                                                        {isArabic ? "صور المخيم" : "Camp Gallery"}
                                                    </h4>
                                                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                        {details.images.map((imgUrl, imgIdx) => (
                                                            <div key={imgIdx} className="relative aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-100 group-hover:shadow-md transition-all">
                                                                <Image
                                                                    src={getImageUrl(imgUrl)}
                                                                    alt={`Camp Image ${imgIdx + 1}`}
                                                                    fill
                                                                    className="object-cover transition-transform duration-500 hover:scale-110"
                                                                    unoptimized
                                                                />
                                                            </div>
                                                        ))}
                                                    </div>
                                                </div>
                                            )}

                                            {/* Three Columns: Academics, Activities, Accommodation */}
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                                                {/* What's Included (Visual) */}
                                                {campItem.included_items && campItem.included_items.length > 0 && (
                                                    <div className="col-span-full">
                                                        <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                            <FontAwesomeIcon icon={faCheckCircle} className="text-green-500" />
                                                            {isArabic ? "المميزات" : "Included Features"}
                                                        </h4>
                                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            {campItem.included_items.map((item, i) => (
                                                                <div key={i} className="flex items-center gap-2 text-sm text-slate-700 bg-green-50/50 p-3 rounded-xl border border-green-100">
                                                                    <div className="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xs shrink-0">
                                                                        <FontAwesomeIcon icon={faCheckCircle} />
                                                                    </div>
                                                                    <span className="font-normal">{item}</span>
                                                                </div>
                                                            ))}
                                                        </div>
                                                    </div>
                                                )}

                                                {/* Academics */}
                                                {details.academics && (
                                                    <div className="bg-slate-50 p-5 rounded-2xl">
                                                        <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                            <FontAwesomeIcon icon={faGraduationCap} className="text-blue-500" />
                                                            {isArabic ? "الدراسة" : "Academics"}
                                                        </h4>
                                                        <p className="text-sm text-slate-600 leading-relaxed">{t(details.academics, details.ar_academics)}</p>
                                                    </div>
                                                )}

                                                {/* Activities */}
                                                {details.activities && (
                                                    <div className="bg-slate-50 p-5 rounded-2xl">
                                                        <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                            <FontAwesomeIcon icon={faFutbol} className="text-orange-500" />
                                                            {isArabic ? "الأنشطة" : "Activities"}
                                                        </h4>
                                                        <p className="text-sm text-slate-600 leading-relaxed">{t(details.activities, details.ar_activities)}</p>
                                                    </div>
                                                )}

                                                {/* Accommodation */}
                                                {details.accommodation && (
                                                    <div className="bg-slate-50 p-5 rounded-2xl">
                                                        <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                            <FontAwesomeIcon icon={faBed} className="text-indigo-500" />
                                                            {isArabic ? "السكن" : "Accommodation"}
                                                        </h4>
                                                        <p className="text-sm text-slate-600 leading-relaxed">{t(details.accommodation, details.ar_accommodation)}</p>
                                                    </div>
                                                )}

                                                {/* Safeguarding */}
                                                {details.safeguarding && (
                                                    <div className="bg-slate-50 p-5 rounded-2xl">
                                                        <h4 className="text-sm font-semibold text-slate-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                            <FontAwesomeIcon icon={faShieldAlt} className="text-red-500" />
                                                            {isArabic ? "الأمان والغافية" : "Safeguarding"}
                                                        </h4>
                                                        <p className="text-sm text-slate-600 leading-relaxed">{t(details.safeguarding, details.ar_safeguarding)}</p>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>

                    {/* ─── RIGHT COLUMN (Sticky Sidebar) ─── */}
                    <div className="lg:col-span-4">
                        <div className="sticky top-24">
                            {/* "Boarding Pass" Booking Booking Card */}
                            {selectedCamp ? (
                                <div className="group relative overflow-hidden rounded-[2rem] bg-white p-1 shadow-2xl transition-all hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)]">
                                    <div className="rounded-[1.8rem] bg-white p-6 relative z-10">
                                        {/* Header */}
                                        <div className="text-center mb-6">
                                            <div className="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">
                                                {isArabic ? "سعر الباقة" : "Total Package Price"}
                                            </div>
                                            <div className="text-4xl font-semibold text-[#0057B7] tracking-tight">
                                                {priceLabel}
                                            </div>
                                            {selectedCamp.fee_type === 'weekly' && <div className="text-xs text-slate-400 font-medium mt-1">per {weeks} weeks</div>}
                                        </div>

                                        {/* Selected Camp Info */}
                                        <div className="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 text-center">
                                            <div className="text-xs text-blue-500 font-medium uppercase mb-1">{isArabic ? "البرنامج المختار" : "Selected Program"}</div>
                                            <div className="font-medium text-slate-900 text-sm">{t(selectedCamp.name, selectedCamp.ar_name)}</div>
                                        </div>

                                        {/* Inputs */}
                                        <div className="space-y-4">
                                            <div className="p-1 rounded-xl bg-slate-50 border border-slate-100">
                                                <HeroDatePicker
                                                    label={isArabic ? "تاريخ السفر" : "Travel Date"}
                                                    placeholder={isArabic ? "اختر التاريخ" : "Select Date"}
                                                    onSelect={(d) => setStartDate(d)}
                                                />
                                                {startDate && <div className="px-4 pb-2 text-xs font-medium text-[#0057B7] text-right">{startDate.toLocaleDateString()}</div>}
                                            </div>

                                            <div className="p-1 rounded-xl bg-slate-50 border border-slate-100">
                                                <HeroDropdown
                                                    label={isArabic ? "المدة" : "Duration"}
                                                    placeholder="Select Weeks"
                                                    scroll
                                                    options={Array.from({ length: 12 }, (_, i) => ({
                                                        label: `${i + 1} ${isArabic ? "أسبوع" : "Weeks"}`,
                                                        value: i + 1,
                                                    }))}
                                                    onSelect={(opt) => setWeeks(opt.value)}
                                                    selectedValue={weeks}
                                                />
                                            </div>
                                        </div>

                                        {/* Summary */}
                                        <div className="my-6 border-t border-dashed border-slate-200 pt-4 space-y-2">
                                            <div className="flex justify-between font-medium text-slate-900">
                                                <span>{weeks} {isArabic ? "أسبوع" : "Weeks"}</span>
                                                <span>{priceLabel}</span>
                                            </div>
                                        </div>

                                        {/* CTA */}
                                        <button
                                            onClick={handleBookNow}
                                            className="w-full rounded-xl bg-[#0057B7] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition transform active:scale-95 hover:bg-[#004494]"
                                        >
                                            {isArabic ? "احجز مكانك الآن" : "Reserve Your Spot"}
                                        </button>

                                        <div className="mt-4 text-center">
                                            <span className="text-xs font-medium text-slate-400 flex items-center justify-center gap-1">
                                                <FontAwesomeIcon icon={faCheckCircle} className="text-green-500" />
                                                {isArabic ? "ضمان أفضل سعر" : "Best Price Guarantee"}
                                            </span>
                                        </div>
                                    </div>
                                    {/* Decorative "Ticket" notches */}
                                    <div className="absolute -left-3 top-1/3 h-6 w-6 rounded-full bg-[#F8FAFC]" />
                                    <div className="absolute -right-3 top-1/3 h-6 w-6 rounded-full bg-[#F8FAFC]" />
                                </div>
                            ) : (
                                <div className="p-6 text-center text-slate-500">Select a camp to view price</div>
                            )}

                            {/* Need Help Card */}
                            <div className="mt-6 rounded-2xl bg-blue-50 p-6 text-center border border-blue-100">
                                <div className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-[#0057B7]">
                                    <FontAwesomeIcon icon={faShare} />
                                </div>
                                <h4 className="font-medium text-[#0057B7]">{isArabic ? "تحتاج مساعدة؟" : "Need Help?"}</h4>
                                <p className="mt-1 text-xs text-blue-600/80 mb-3">{isArabic ? "تحدث مع مستشارينا التعليميين" : "Speak to our education advisors"}</p>
                                <button className="text-sm font-medium text-[#0057B7] underline hover:text-blue-800">
                                    {isArabic ? "تواصل معنا" : "Contact Support"}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {/* Mobile Bottom Bar (Sticky) */}
            <div className="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-100 p-4 shadow-[0_-5px_20px_rgba(0,0,0,0.05)] z-30">
                <div className="flex items-center justify-between gap-4">
                    <div>
                        <div className="text-sm text-slate-500">{isArabic ? "الإجمالي" : "Total Price"}</div>
                        <div className="text-2xl font-semibold text-[#0057B7] leading-none">{priceLabel}</div>
                    </div>
                    <button
                        onClick={handleBookNow}
                        className="flex-1 rounded-xl bg-[#0057B7] py-3 text-sm font-medium text-white shadow-lg"
                    >
                        {isArabic ? "احجز الآن" : "Book Now"}
                    </button>
                </div>
            </div>

        </main >
    );
}
