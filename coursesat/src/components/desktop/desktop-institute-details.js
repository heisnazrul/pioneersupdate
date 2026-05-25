"use client";

import { useState, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams, useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faStar, faSignal, faUser, faClock, faBookOpen, faCheckCircle,
    faShieldAlt, faPlusCircle, faHeart, faShare, faExchangeAlt,
    faChevronLeft, faChevronRight, faArrowLeft, faArrowRight, faBed, faBath, faHouse, faChevronUp
} from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import { useLocale } from "@/components/providers/locale-provider";

// Import mock data directly for now
import mockDetails from "@/mocdata/institute-details.json";

function priceField(obj, field, currency) {
    if (!obj) return 0;
    const gbpKey = field ? `${field}_gbp` : "price_gbp";
    const sarKey = field ? `${field}_sar` : "price_sar";
    return currency === "SAR" ? (obj[sarKey] || 0) : (obj[gbpKey] || (obj.price || obj.amount || 0));
}

function fmtNum(value) {
    if (value === null || value === undefined) return "";
    return Number(value).toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function Price({ value, currency, className = "", size = "md" }) {
    if (value === null || value === undefined) return null;
    const num = fmtNum(value);
    const iconSize = size === "lg" ? 24 : size === "sm" ? 14 : 18;
    if (currency === "SAR") {
        return (
            <span className={`inline-flex items-center ${className}`}>
                <span>{num}</span>
                <img src="/assets/icons/sar.svg" alt="SAR" width={iconSize} height={iconSize} className="inline-block" />
            </span>
        );
    }
    return (
        <span className={`inline-flex items-center gap-0.5 ${className}`}>
            <span>£</span><span>{num}</span>
        </span>
    );
}

function formatLocalDate(date) {
    if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "";
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}

function parseQueryDate(dateString) {
    if (!dateString) return null;
    const [y, m, d] = String(dateString).split("-").map(Number);
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
}

function ImageSlider({ images, schoolName }) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const imgs = images && images.length > 0 ? images : [];

    if (imgs.length === 0) return null;

    const prev = () => setCurrentIndex(i => (i === 0 ? imgs.length - 1 : i - 1));
    const next = () => setCurrentIndex(i => (i === imgs.length - 1 ? 0 : i + 1));

    return (
        <div className="relative w-full aspect-[4/3] overflow-hidden rounded-2xl bg-gray-100 group">
            <img
                src={imgs[currentIndex]}
                alt={schoolName || "Gallery"}
                className="w-full h-full object-cover transition-all duration-500"
            />
            {imgs.length > 1 && (
                <>
                    <button onClick={prev} className="absolute left-2 top-1/2 -translate-y-1/2 h-8 w-8 rounded-full bg-white/80 flex items-center justify-center text-slate-700 shadow opacity-0 group-hover:opacity-100 transition hover:bg-white">
                        <FontAwesomeIcon icon={faChevronLeft} className="h-3 w-3" />
                    </button>
                    <button onClick={next} className="absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 rounded-full bg-white/80 flex items-center justify-center text-slate-700 shadow opacity-0 group-hover:opacity-100 transition hover:bg-white">
                        <FontAwesomeIcon icon={faChevronRight} className="h-3 w-3" />
                    </button>
                    <div className="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                        {imgs.map((_, idx) => (
                            <button
                                key={idx}
                                onClick={() => setCurrentIndex(idx)}
                                className={`h-2 rounded-full transition-all ${idx === currentIndex ? 'w-6 bg-[#0057B7]' : 'w-2 bg-white'}`}
                            />
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}

export default function DesktopInstituteDetails({ params }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    // Using a default currency for now. In a full implementation, read from settings context.
    const currency = "GBP";

    // Hardcode mock data
    const instituteData = mockDetails;

    const initialWeeks = searchParams.get('weeks') ? parseInt(searchParams.get('weeks')) : 12;
    const initialCourseId = searchParams.get('course_id') ? parseInt(searchParams.get('course_id')) : null;
    const initialStartDate = parseQueryDate(searchParams.get('start_date'));
    const initialAccId = searchParams.get('accommodation_id') || 'no-acc';
    const initialPickupId = searchParams.get('pickup_id') ? parseInt(searchParams.get('pickup_id')) : null;
    const initialExtras = searchParams.get('extras') ? searchParams.get('extras').split(',').map(Number) : [];
    const initialAccAge = searchParams.get('acc_age') ? parseInt(searchParams.get('acc_age')) : null;

    const [selectedCourseId, setSelectedCourseId] = useState(initialCourseId);
    const [selectedAccommodationId, setSelectedAccommodationId] = useState(initialAccId);
    const [selectedPickupId, setSelectedPickupId] = useState(initialPickupId);
    const [selectedExtras, setSelectedExtras] = useState(initialExtras);
    const [weeks, setWeeks] = useState(initialWeeks);
    const [startDate, setStartDate] = useState(initialStartDate);
    const [accAge, setAccAge] = useState(initialAccAge);
    const [toastMsg, setToastMsg] = useState(null);
    const slug = params?.slug || "lsi-education-london";

    const showToast = (msg) => {
        setToastMsg(msg);
        setTimeout(() => setToastMsg(null), 2500);
    };

    const handleShare = () => {
        const url = new URL(window.location.href);
        if (selectedCourseId) url.searchParams.set('course_id', selectedCourseId);
        navigator.clipboard.writeText(url.toString()).then(() => {
            showToast(isArabic ? 'تم نسخ الرابط!' : 'Link copied!');
        });
    };

    const school = instituteData?.school;
    const courses = instituteData?.courses || [];
    const accommodations = instituteData?.accommodations || [];
    const pickUps = instituteData?.pickups || [];
    const insurances = instituteData?.insurances || [];
    const supplements = instituteData?.supplements || [];

    const regFeeObj = instituteData?.registration_fee;
    const registrationFee = regFeeObj ? priceField(regFeeObj, "amount", currency) : 50;

    const discounts = instituteData?.discounts || [];
    const pioneersDiscounts = instituteData?.pioneers_discounts || [];
    const bestDiscount = discounts.length > 0 ? Math.max(...discounts.map(d => d.discount_percentage || 0)) : 0;
    const pioneersDisc = pioneersDiscounts.length > 0 ? pioneersDiscounts[0] : null;
    const discountPercent = bestDiscount > 0 ? bestDiscount : (pioneersDisc ? 20 : 0);

    useEffect(() => {
        if (courses.length > 0 && !selectedCourseId) {
            setSelectedCourseId(initialCourseId || courses[0].id);
        }
    }, [courses, selectedCourseId, initialCourseId]);

    const accreditationLogos = school?.accreditations || [];
    const selectedCourse = courses.find(c => c.id === selectedCourseId);
    const selectedAccommodation = accommodations.find(a => a.id === selectedAccommodationId);
    const allExtras = [...insurances, ...supplements];
    const selectedExtrasObjects = allExtras.filter(e => selectedExtras.includes(e.id));
    const selectedPickup = pickUps.find(p => p.id === selectedPickupId);

    const coursePrice = selectedCourse ? priceField(selectedCourse, "price", currency) * weeks : 0;
    const accPrice = selectedAccommodation ? priceField(selectedAccommodation, "fee_per_week", currency) * weeks : 0;
    const pickupPrice = selectedPickup ? (priceField(selectedPickup, "price", currency) || 0) : 0;
    const extrasPrice = selectedExtrasObjects.reduce((sum, e) => sum + (priceField(e, "price", currency) || priceField(e, "amount", currency) || 0), 0);
    const subtotal = coursePrice + accPrice + pickupPrice + extrasPrice + registrationFee;
    const discountAmount = discountPercent > 0 ? subtotal * (discountPercent / 100) : 0;
    const totalPrice = subtotal - discountAmount;

    const toggleExtra = (id) => {
        setSelectedExtras(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]);
    };

    const formatDisplayDate = (date) => {
        if (!date) return "12 مارس";
        return new Intl.DateTimeFormat(isArabic ? 'ar-EG' : 'en-GB', { weekday: 'long', month: 'long', day: 'numeric' }).format(date);
    };

    const schoolDisplayName = isArabic
        ? `${school?.ar_name || ''} - ${school?.city_ar || ''} - ${school?.name || ''}`
        : school?.name;

    const sliderImages = [];
    if (school?.image) sliderImages.push(school.image);
    if (school?.gallery) sliderImages.push(...school.gallery.filter(img => img !== school.image));

    const getDiscountPrice = (price) => discountPercent > 0 ? price * (1 - discountPercent / 100) : null;

    if (!school) return <div className="p-20 text-center">Loading...</div>;

    const l = (key) => t(`pages.institute_details.${key}`);
    const loc = (en, ar) => (isArabic && ar) ? ar : en;

    return (
        <div className="container mx-auto px-4 py-8" dir={isArabic ? "rtl" : "ltr"}>
            {/* Top Nav */}
            <div className="mb-6 flex">
                <Link href="/language-institutes" className="flex items-center gap-2 text-sm font-semibold text-[#102233] hover:text-[#0057B7] transition">
                    <FontAwesomeIcon icon={isArabic ? faArrowRight : faArrowLeft} className="h-4 w-4" />
                    <span>{l("backToList")}</span>
                </Link>
            </div>

            {/* Title Header & Actions */}
            <div className="flex flex-col justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h1 className="text-3xl font-bold text-[#102233] md:text-[32px] leading-tight" dir={isArabic ? "rtl" : "ltr"}>{schoolDisplayName}</h1>
                </div>

                {/* Actions (Share, Favorite, Compare) */}
                <div className="flex items-center gap-8 relative pb-1">
                    <button onClick={handleShare} className="flex items-center gap-2 text-sm font-semibold text-[#102233] hover:text-[#0057B7] transition">
                        <FontAwesomeIcon icon={faShare} className="h-4 w-4 hidden" />
                        <span>{l("share")}</span>
                    </button>
                    <button className="flex items-center gap-2 text-sm font-semibold text-[#102233] hover:text-red-500 transition">
                        <FontAwesomeIcon icon={faHeart} className="h-4 w-4 opacity-70" />
                        <span>{l("addFavorite")}</span>
                    </button>
                    <button className="flex items-center gap-2 text-sm font-semibold text-[#102233] hover:text-[#0057B7] transition">
                        <FontAwesomeIcon icon={faExchangeAlt} className="h-4 w-4 opacity-70" />
                        <span>{l("addCompare")}</span>
                    </button>
                    {toastMsg && (
                        <div className="absolute -bottom-12 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg z-50">
                            {toastMsg}
                        </div>
                    )}
                </div>
            </div>
            <div className="mt-4 mb-10 flex flex-row-reverse items-center gap-3 text-sm font-medium text-slate-600" dir="ltr">
                {school.flag && <img src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" />}
                <span>{loc(school.location, (school.city_ar ? `${school.country_ar} ، ${school.city_ar}` : null))}</span>
                <span className="flex flex-row-reverse items-center gap-1.5 text-[#F59E0B]">
                    <FontAwesomeIcon icon={faStar} className="h-3.5 w-3.5" />
                    <span className="font-semibold text-slate-800">{school.rating}</span>
                </span>
            </div>

            <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div className="lg:col-span-8 space-y-8">
                    {/* Description & Gallery */}
                    <div className="rounded-[20px] bg-white p-3 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-8">
                        {/* Left Side: Image Slider (In RTL, this renders on the right) */}
                        <div className="w-full md:w-[45%] hidden md:block">
                            <ImageSlider images={sliderImages} schoolName={loc(school.name, school.ar_name)} />
                        </div>
                        {/* Right Side: Text & Accreditations (In RTL, this renders on the left) */}
                        <div className="flex-1 space-y-6 py-4">
                            <p className="text-[14px] leading-relaxed text-black max-h-[160px] overflow-y-auto pr-3 scrollbar-thin text-right" dir={isArabic ? "rtl" : "ltr"}>
                                {loc(school.description, school.ar_description)}
                            </p>
                            {accreditationLogos.length > 0 && (
                                <div className="pt-2">
                                    <div className="flex flex-wrap justify-end gap-3" dir="ltr">
                                        {[...accreditationLogos, ...accreditationLogos].slice(0, 4).map((acc, idx) => (
                                            <div key={idx} className="relative h-[36px] w-[80px] rounded-[10px] border border-gray-200 bg-white p-1.5 flex items-center justify-center hover:border-[#0057B7] transition shadow-sm">
                                                {acc.logo && <img src={acc.logo} alt={loc(acc.name, acc.ar_name)} className="w-full h-full object-contain" />}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Step 1: Choose Course */}
                    <div>
                        <div className="mb-2 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <h3 className="text-xl font-semibold text-slate-900">{l("step1")}</h3>
                                <p className="text-sm text-slate-500 mt-1">{l("step1Sub")}</p>
                            </div>
                            <div className="flex gap-3 w-full md:w-auto">
                                <div className="w-full md:w-48">
                                    <HeroDatePicker
                                        label={l("startDate")}
                                        placeholder={l("selectStart")}
                                        selectedDate={startDate}
                                        onSelect={(date) => setStartDate(date)}
                                    />
                                </div>
                                <div className="w-full md:w-50">
                                    <HeroDropdown
                                        label={l("numWeeks")}
                                        placeholder={l("selectWeeks")}
                                        scroll
                                        options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks")}`, value: i + 1 }))}
                                        onSelect={(opt) => setWeeks(opt.value)}
                                        selectedValue={weeks}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="space-y-4 mt-4">
                            {courses.map((course) => {
                                const price = priceField(course, "price", currency);
                                const discPrice = getDiscountPrice(price);
                                return (
                                    <label key={course.id} className={`block cursor-pointer rounded-[20px] border-2 overflow-hidden transition-all bg-white ${selectedCourseId === course.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                        <div className="flex flex-col md:flex-row justify-between p-6 gap-6 relative">
                                            {/* Right side (Text info) */}
                                            <div className="flex-1">
                                                <div className="flex items-center gap-3 mb-4">
                                                    <h4 className="text-[18px] font-bold text-[#102233]">{loc(course.name, course.ar_name)}</h4>
                                                    {course.tag && <span className="rounded-md bg-[#4CAF50] px-2.5 py-0.5 text-[12px] font-medium text-white">{loc(course.tag, course.tag_ar)}</span>}
                                                </div>
                                                <div className="flex flex-wrap items-center gap-6 text-[13px] font-medium text-slate-500">
                                                    <span className="flex items-center gap-2"><FontAwesomeIcon icon={faBookOpen} className="text-[#0057B7] h-[15px] w-[15px]" /> {course.lessons} {l("lessonsWeek")}</span>
                                                    <span className="flex items-center gap-2"><FontAwesomeIcon icon={faClock} className="text-[#0057B7] h-[15px] w-[15px]" /> {course.hours} {l("hoursWeek")}</span>
                                                    <span className="flex items-center gap-2"><FontAwesomeIcon icon={faUser} className="text-[#0057B7] h-[15px] w-[15px]" /> +{course.min_age} {l("requiredAge")}</span>
                                                    <span className="flex items-center gap-2"><FontAwesomeIcon icon={faSignal} className="text-[#0057B7] h-[15px] w-[15px]" /> {course.level} {l("requiredLevel")}</span>
                                                </div>
                                            </div>
                                            
                                            {/* Left side (Checkmark and Price) */}
                                            <div className="flex flex-col items-end min-w-[150px] justify-between h-full">
                                                {/* Checkmark */}
                                                <div className="mb-4">
                                                    <img src={selectedCourseId === course.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                                </div>
                                                
                                                {/* Price */}
                                                <div className="text-left mt-auto">
                                                    <div className="flex items-baseline justify-end gap-1" dir={isArabic ? "rtl" : "ltr"}>
                                                        <span className="text-[20px] font-black text-[#102233]" dir="ltr"><Price value={discPrice || price} currency={currency} /></span>
                                                        <span className="text-[15px] font-bold text-[#102233] mx-1">/</span>
                                                        <span className="text-[15px] font-medium text-[#102233]">{l("perWeek")}</span>
                                                    </div>
                                                    {discPrice && (
                                                        <div className="flex justify-end items-center gap-2 mt-1" dir={isArabic ? "rtl" : "ltr"}>
                                                            <span className="text-[14px] font-medium text-slate-400 line-through" dir="ltr"><Price value={price} currency={currency} size="sm" /></span>
                                                            <span className="rounded-full bg-[#EF4444] px-2 py-0.5 text-[11px] font-bold text-white">
                                                                -{discountPercent}%
                                                            </span>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="course" className="hidden" value={course.id} checked={selectedCourseId === course.id} onChange={() => setSelectedCourseId(course.id)} />
                                    </label>
                                );
                            })}
                        </div>
                    </div>

                    {/* Step 2: Choose Accommodation */}
                    <div>
                        <div className="mb-2 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <h3 className="text-xl font-semibold text-slate-900">{l("step2")}</h3>
                                <p className="text-sm text-slate-500 mt-1">{l("step2Sub")}</p>
                            </div>
                            <div className="flex gap-3 w-full md:w-auto">
                                <div className="w-full md:w-48">
                                    <HeroDropdown
                                        label={l("age")}
                                        placeholder={l("age")}
                                        scroll
                                        options={[
                                            { label: "16", value: 16 },
                                            { label: "17", value: 17 },
                                            { label: "18+", value: 18 },
                                        ]}
                                        onSelect={(opt) => setAccAge(opt.value)}
                                        selectedValue={accAge}
                                    />
                                </div>
                                <div className="w-full md:w-50">
                                    <HeroDropdown
                                        label={l("numWeeks")}
                                        placeholder={l("selectWeeks")}
                                        scroll
                                        options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1}`, value: i + 1 }))}
                                        onSelect={(opt) => setWeeks(opt.value)}
                                        selectedValue={weeks}
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="space-y-4 mt-4">
                            <label className={`block cursor-pointer rounded-[20px] border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === 'no-acc' ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                <div className="flex flex-col md:flex-row justify-between p-6 gap-6 relative">
                                    <div className="flex-1">
                                        <h4 className="text-[18px] font-bold text-[#102233]">{l("withoutAcc")}</h4>
                                        <p className="text-[14px] text-slate-500 mt-1">{l("withoutAccSub")}</p>
                                    </div>
                                    <div className="flex flex-col items-end min-w-[150px] justify-start h-full">
                                        <div className="mb-4">
                                            <img src={selectedAccommodationId === 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="accommodation" className="hidden" value="no-acc" checked={selectedAccommodationId === 'no-acc'} onChange={() => setSelectedAccommodationId('no-acc')} />
                            </label>

                            {accommodations.map((acc) => {
                                const accPrice = priceField(acc, "fee_per_week", currency);
                                const accDiscPrice = getDiscountPrice(accPrice);
                                const featureList = acc.features ? acc.features.split(',').map(f => f.trim()) : [];
                                return (
                                    <label key={acc.id} className={`block cursor-pointer rounded-[20px] border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === acc.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                        <div className="flex flex-col md:flex-row justify-between p-6 gap-6 relative">
                                            {/* Right side (Text info) */}
                                            <div className="flex-1">
                                                <div className="flex items-center gap-3 mb-4">
                                                    <h4 className="text-[18px] font-bold text-[#102233]">{loc(acc.title, acc.ar_title)}</h4>
                                                    {acc.tag && <span className="rounded-md bg-[#4CAF50] px-2.5 py-0.5 text-[12px] font-medium text-white">{loc(acc.tag, acc.tag_ar)}</span>}
                                                </div>
                                                <div className="flex flex-wrap items-center gap-6 text-[13px] font-medium text-slate-500">
                                                    {featureList.map((feature, i) => (
                                                        <span key={i} className="flex items-center gap-2">
                                                            <FontAwesomeIcon icon={i === 0 ? faBed : i === 1 ? faBath : faHouse} className="text-[#0057B7] h-[15px] w-[15px]" />
                                                            {feature}
                                                        </span>
                                                    ))}
                                                </div>
                                            </div>
                                            
                                            {/* Left side (Checkmark and Price) */}
                                            <div className="flex flex-col items-end min-w-[150px] justify-between h-full">
                                                {/* Checkmark */}
                                                <div className="mb-4">
                                                    <img src={selectedAccommodationId === acc.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                                </div>
                                                
                                                {/* Price */}
                                                <div className="text-left mt-auto">
                                                    <div className="flex items-baseline justify-end gap-1" dir={isArabic ? "rtl" : "ltr"}>
                                                        <span className="text-[20px] font-black text-[#102233]" dir="ltr"><Price value={accDiscPrice || accPrice} currency={currency} /></span>
                                                        <span className="text-[15px] font-bold text-[#102233] mx-1">/</span>
                                                        <span className="text-[15px] font-medium text-[#102233]">{l("perWeek")}</span>
                                                    </div>
                                                    {accDiscPrice && (
                                                        <div className="flex justify-end items-center gap-2 mt-1" dir={isArabic ? "rtl" : "ltr"}>
                                                            <span className="text-[14px] font-medium text-slate-400 line-through" dir="ltr"><Price value={accPrice} currency={currency} size="sm" /></span>
                                                            <span className="rounded-full bg-[#EF4444] px-2 py-0.5 text-[11px] font-bold text-white">
                                                                -{discountPercent}%
                                                            </span>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="accommodation" className="hidden" value={acc.id} checked={selectedAccommodationId === acc.id} onChange={() => setSelectedAccommodationId(acc.id)} />
                                    </label>
                                );
                            })}
                        </div>
                    </div>

                    {/* Step 3: Extras */}
                    <div className="mt-8">
                        <div className="mb-4">
                            <h3 className="text-[20px] font-bold text-[#102233]">{l("extraOptions")}</h3>
                            <p className="text-[13px] text-slate-500 mt-1">{l("extraOptionsSub")}</p>
                        </div>
                        <div className="space-y-4">
                            {/* Airport Pickups */}
                            {pickUps.length > 0 && (
                                <div className="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                    <div className="flex items-center justify-between p-4 bg-white cursor-pointer select-none">
                                        <h4 className="font-bold text-[#102233] text-[15px]">{l("airport_pickup") || l("step3pickups")}</h4>
                                        <FontAwesomeIcon icon={faChevronUp} className="text-slate-400 h-4 w-4" />
                                    </div>
                                    <div className="px-2 pb-2">
                                        <div className="space-y-1 border-t border-gray-100 pt-2">
                                            <label className="flex items-center justify-between p-3 cursor-pointer rounded-lg hover:bg-gray-50 transition">
                                                <div className="flex items-center gap-3">
                                                    <div className={`h-5 w-5 rounded-full border-2 flex items-center justify-center ${!selectedPickupId ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                                        {!selectedPickupId && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
                                                    </div>
                                                    <div>
                                                        <div className="text-[14px] font-bold text-[#102233]">{l("noPickup") || l("without_pickup")}</div>
                                                        <div className="text-[12px] text-slate-500 mt-0.5">{l("noPickupSub") || l("without_pickup_subtitle")}</div>
                                                    </div>
                                                </div>
                                                <input type="radio" name="pickup" className="hidden" value="" checked={!selectedPickupId} onChange={() => setSelectedPickupId(null)} />
                                            </label>
                                            {pickUps.map(p => {
                                                const pPrice = priceField(p, "price", currency);
                                                return (
                                                    <label key={p.id} className="flex items-center justify-between p-3 cursor-pointer rounded-lg hover:bg-gray-50 transition border-t border-gray-50">
                                                        <div className="flex items-center gap-3">
                                                            <div className={`h-5 w-5 rounded-full border-2 flex items-center justify-center ${selectedPickupId === p.id ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                                                {selectedPickupId === p.id && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
                                                            </div>
                                                            <div>
                                                                <div className="text-[14px] font-bold text-[#102233]">{loc(p.route, p.ar_route)}</div>
                                                                <div className="text-[13px] font-bold text-[#102233] mt-0.5" dir="ltr"><Price value={pPrice} currency={currency} size="sm" /> <span className="text-[11px] font-medium text-slate-500">{l("perWeek")}</span></div>
                                                            </div>
                                                        </div>
                                                        <input type="radio" name="pickup" className="hidden" value={p.id} checked={selectedPickupId === p.id} onChange={() => setSelectedPickupId(p.id)} />
                                                    </label>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Visa/Insurances/Supplements */}
                            {insurances.map(ins => {
                                const iPrice = priceField(ins, "price", currency);
                                const isSelected = selectedExtras.includes(ins.id);
                                return (
                                    <label key={ins.id} className="flex items-center justify-between p-5 cursor-pointer rounded-xl border border-gray-200 bg-white hover:border-[#0057B7] transition">
                                        <div className="flex items-center gap-3">
                                            <div className={`h-5 w-5 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                                {isSelected && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
                                            </div>
                                            <div className="text-[15px] font-bold text-[#102233]">{loc(ins.name, ins.ar_name)}</div>
                                        </div>
                                        <div className="text-[14px] font-bold text-[#102233]" dir="ltr">
                                            <Price value={iPrice} currency={currency} size="sm" /> <span className="text-[11px] font-medium text-slate-500">{l("perWeek")}</span>
                                        </div>
                                        <input type="checkbox" className="hidden" checked={isSelected} onChange={() => toggleExtra(ins.id)} />
                                    </label>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* Sidebar Sticky Area */}
                <div className="lg:col-span-4 relative">
                    <div className="sticky top-24 bg-[#F8FAFC] rounded-[24px] p-6 shadow-sm border border-gray-100 flex flex-col gap-6">
                        {/* Top Price */}
                        <div className="flex flex-col items-center justify-center pt-2">
                            <div className="flex items-center gap-1 text-[40px] font-extrabold text-[#102233]" dir="ltr">
                                <Price value={totalPrice} currency={currency} size="lg" />
                            </div>
                            <div className="text-sm text-slate-500 font-medium mt-1">{l("totalIncludes")}</div>
                        </div>

                        {/* Dates Display Box */}
                        <div className="rounded-2xl border border-gray-200 bg-white px-4 py-3">
                            <div className="flex flex-col gap-3">
                                {/* Study Date */}
                                <div className="flex items-center justify-between border-b border-gray-100 pb-3">
                                    <div className="text-left w-1/2 mt-4">
                                        <div className="text-[11px] font-semibold text-[#102233]">{l("from")} {formatDisplayDate(startDate)}</div>
                                    </div>
                                    <div className="text-right w-1/2">
                                        <div className="text-[11px] text-slate-400 font-medium mb-1">{l("studyDate")}</div>
                                        <div className="text-[11px] font-semibold text-[#102233]">{l("to")} {formatDisplayDate(startDate)}</div>
                                    </div>
                                </div>
                                {/* Number of weeks */}
                                <div className="flex items-center justify-between pt-1">
                                    <div className="text-sm font-semibold text-[#102233]">{weeks} {l("weeks")}</div>
                                    <div className="text-xs text-slate-400 font-medium">{l("numWeeks")}</div>
                                </div>
                            </div>
                        </div>

                        {/* Discount Code */}
                        <div>
                            <div className="text-sm font-semibold text-[#102233] mb-3 text-right">{l("couponQ")}</div>
                            <div className="flex items-center gap-2">
                                <button className="rounded-[10px] bg-gray-300 px-5 py-3 text-sm font-medium text-white transition">{l("apply")}</button>
                                <input type="text" placeholder={l("couponCode")} className="w-full rounded-[10px] border border-gray-200 bg-white px-4 py-3 text-right text-sm outline-none placeholder:text-gray-400" dir={isArabic ? "rtl" : "ltr"} />
                            </div>
                        </div>

                        {/* Breakdown */}
                        <div className="space-y-4 pt-4 text-[13px] font-semibold text-[#102233]">
                            <div className="flex items-center justify-between">
                                <span dir="ltr"><Price value={coursePrice} currency={currency} size="sm" /></span>
                                <span className="text-right">{selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : l("step1")} ({weeks} {l("weeks")})</span>
                            </div>
                            <div className="flex items-center justify-between">
                                <span dir="ltr"><Price value={accPrice} currency={currency} size="sm" /></span>
                                <span className="text-right">{selectedAccommodation ? loc(selectedAccommodation.title, selectedAccommodation.ar_title) : l("step2")} ({weeks} {l("weeks")})</span>
                            </div>
                            <div className="flex items-center justify-between">
                                <span dir="ltr"><Price value={registrationFee} currency={currency} size="sm" /></span>
                                <span className="text-right">{l("registrationFee")}</span>
                            </div>
                            {discountAmount > 0 && (
                                <div className="flex items-center justify-between text-[#10B981]">
                                    <span dir="ltr">-<Price value={discountAmount} currency={currency} size="sm" /></span>
                                    <span>{l("totalDiscount")}</span>
                                </div>
                            )}
                        </div>

                        {/* Bottom Total & Button */}
                        <div className="pt-5 border-t border-gray-200">
                            <div className="flex items-end justify-between mb-4">
                                <div className="text-left" dir="ltr">
                                    <div className="flex items-baseline gap-2">
                                        <span className="text-xl font-bold text-[#0057B7]"><Price value={totalPrice} currency={currency} size="lg" /></span>
                                        {discountAmount > 0 && (
                                            <span className="text-sm font-medium text-slate-400 line-through">
                                                <Price value={subtotal} currency={currency} size="sm" />
                                            </span>
                                        )}
                                    </div>
                                    <div className="text-[11px] text-slate-500 mt-0.5 text-right w-full block" dir={isArabic ? "rtl" : "ltr"}>{l("totalIncludes")}</div>
                                </div>
                            </div>
                            <button
                                onClick={() => {
                                    router.push(`/language-institutes/${slug}/booking?course_id=${selectedCourseId}&weeks=${weeks}&accommodation_id=${selectedAccommodationId}`);
                                }}
                                className="w-full flex items-center justify-center gap-2 h-[52px] rounded-xl bg-[#0057B7] text-white font-semibold text-[15px] hover:bg-[#004A9C] transition"
                            >
                                <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-4 w-4" />
                                <span>{l("booking.reviewConfirm") || l("reviewRequest")}</span>
                            </button>
                        </div>
                    </div>

                    {/* WhatsApp Box */}
                    <div className="mt-6 rounded-2xl bg-[#F0FDF4] p-5 border border-[#DCFCE7] flex flex-col items-end text-right">
                        <h4 className="font-bold text-[#102233] mb-1 text-[15px]">{l("haveQuestion")}</h4>
                        <p className="text-[13px] text-slate-600 leading-relaxed max-w-[200px]">{l("haveQuestionDesc")}</p>
                        <Link href="https://wa.me/966550027268" target="_blank" className="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-white h-11 text-sm font-semibold text-[#16A34A] border border-[#16A34A] hover:bg-[#16A34A] hover:text-white transition">
                            <img src="/assets/icons/whatsapp.svg" alt="WhatsApp" className="h-4 w-4" onError={(e) => e.target.style.display = 'none'} />
                            <span>{l("contactUsWhatsapp")}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
