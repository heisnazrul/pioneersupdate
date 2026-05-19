"use client";

import { useEffect, useState, useRef, use } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faStar, faSignal, faUser, faClock, faBookOpen, faCheckCircle,
    faPlane, faShieldAlt, faPlusCircle, faHeart, faShare, faExchangeAlt,
    faChevronLeft, faChevronRight, faBed, faBath, faHouse
} from "@fortawesome/free-solid-svg-icons";
import InstituteSidebar from "@/app/components/InstituteSidebar";
import HeroDropdown from "@/app/components/HeroDropdown";
import HeroDatePicker from "@/app/components/HeroDatePicker";
import { useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

/* ── Helpers ───────────────────────────────────────────────── */

function priceField(obj, field, currency) {
    if (!obj) return 0;
    const gbpKey = field ? `${field}_gbp` : "price_gbp";
    const sarKey = field ? `${field}_sar` : "price_sar";
    return currency === "SAR" ? (obj[sarKey] || 0) : (obj[gbpKey] || 0);
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
                <img src="/assets/sar-black.svg" alt="SAR" width={iconSize} height={iconSize} className="inline-block" />

            </span>
        );
    }
    return (
        <span className={`inline-flex items-center gap-0.5 ${className}`}>
            <span>£</span><span>{num}</span>
        </span>
    );
}

function t(en, ar, isArabic) {
    return isArabic && ar ? ar : (en || "");
}

function parseQueryDate(dateString) {
    if (!dateString) return null;
    const [y, m, d] = String(dateString).split("-").map(Number);
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
}

function formatLocalDate(date) {
    if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "";
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}

const AR_FEATURE_MAP = {
    "single room": "غرفة فردية",
    "shared bathroom": "حمام مشترك",
    "halfboard": "نصف إقامة",
    "fullboard": "إقامة كاملة",
    "private bathroom": "حمام خاص",
    "age 18+": "+18 العمر",
};

function localizeFeature(feature, isArabic) {
    if (!isArabic || !feature) return feature || "";
    const key = String(feature).trim().toLowerCase();
    return AR_FEATURE_MAP[key] || feature;
}

function featureIcon(feature, idx = 0) {
    const f = String(feature || "").toLowerCase();
    if (f.includes("room") || f.includes("غرفة")) return faBed;
    if (f.includes("bath") || f.includes("حمام")) return faBath;
    if (f.includes("board") || f.includes("إقامة")) return faHouse;
    if (idx === 0) return faBed;
    if (idx === 1) return faBath;
    return faHouse;
}

/* ── Labels ────────────────────────────────────────────────── */
const LABELS = {
    backToList: { en: "Back to List", ar: "العودة الى قائمة المعاهد" },
    share: { en: "Share", ar: "مشاركة" },
    addFavorite: { en: "Add to Favorites", ar: "اضافة للمفضلة" },
    inFavorite: { en: "Already in Favorites", ar: "مضاف للمفضلة" },
    addCompare: { en: "Add to Compare", ar: "اضافة للمقارنة" },
    inCompare: { en: "Already in Compare", ar: "مضاف للمقارنة" },
    aboutInstitute: { en: "About the Institute", ar: "عن المعهد" },
    accreditations: { en: "Accreditations", ar: "الاعتمادات" },
    step1: { en: "Step 1: Choose the Suitable Course", ar: "الخطوة 1: اختر الدورة المناسبة" },
    step1Sub: { en: "Prices calculated per week", ar: "الأسعار محسوبة لكل أسبوع" },
    startDate: { en: "Start Date", ar: "تاريخ البدء" },
    selectStart: { en: "Select start date", ar: "اختر تاريخ البدء" },
    weeks: { en: "Weeks", ar: "الأسابيع" },
    numWeeks: { en: "Number of Weeks", ar: "عدد الأسابيع" },
    selectWeeks: { en: "Select weeks", ar: "اختر الأسابيع" },
    lessonsWeek: { en: "Lessons/week", ar: "دروس/ الأسبوع" },
    hoursWeek: { en: "Hours/week", ar: "ساعة/ الأسبوع" },
    requiredAge: { en: "Required Age", ar: "العمر المطلوب" },
    requiredLevel: { en: "Required Level", ar: "المستوى المطلوب" },
    age: { en: "Age", ar: "العمر" },
    perWeek: { en: "/ week", ar: " / للاسبوع" },
    step2: { en: "Choose Accommodation", ar: "اختر السكن" },
    step2Sub: { en: "You can choose accommodation or continue without", ar: "يمكنك اختيار السكن أو المتابعة بدون سكن" },
    withAcc: { en: "With Accommodation", ar: "مع سكن" },
    withoutAcc: { en: "Without Accommodation", ar: "بدون سكن" },
    withoutAccSub: { en: "You can continue without accommodation", ar: "يمكنك المتابعة بدون اختيار سكن" },
    step3pickups: { en: "Airport Pickup", ar: "الاستقبال من المطار" },
    noPickup: { en: "No pickup needed", ar: "بدون استقبال" },
    noPickupSub: { en: "You can continue without pickup", ar: "يمكنك المتابعة بدون استقبال" },
    extraOptions: { en: "Additional Options", ar: "خيارات اضافية" },
    extraOptionsSub: { en: "You can add optional services as needed", ar: "يمكنك إضافة خدمات اختيارية حسب احتياجك" },
    step3insurance: { en: "Insurance", ar: "التأمين" },
    step3supplements: { en: "Additional Supplements", ar: "إضافات أخرى" },
    couponQ: { en: "Do you have a discount code?", ar: "هل لديك كود خصم؟" },
    couponCode: { en: "Discount code", ar: "كود الخصم" },
    apply: { en: "Apply", ar: "تطبيق" },
    totalIncludes: { en: "Total includes all fees", ar: "الإجمالي شامل جميع الرسوم" },
    reviewRequest: { en: "Review Request", ar: "مراجعة الطلب" },
    selected: { en: "Selected", ar: "محدد" },
    bestOffer: { en: "Best Offer", ar: "أفضل عرض" },
};

function l(key, isArabic) {
    const obj = LABELS[key];
    return obj ? t(obj.en, obj.ar, isArabic) : key;
}

/* ── Image Slider Component ───────────────────────────────── */

function ImageSlider({ images, schoolName }) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const imgs = images && images.length > 0 ? images : [];

    if (imgs.length === 0) return null;

    const prev = () => setCurrentIndex(i => (i === 0 ? imgs.length - 1 : i - 1));
    const next = () => setCurrentIndex(i => (i === imgs.length - 1 ? 0 : i + 1));

    return (
        <div className="relative w-full aspect-[4/3] overflow-hidden rounded-2xl bg-gray-100 group">
            <Image
                src={imgs[currentIndex]}
                alt={schoolName || "Gallery"}
                fill
                className="object-cover transition-all duration-500"
                unoptimized
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
                                className={`h-2 rounded-full transition-all ${idx === currentIndex ? 'w-6 bg-white' : 'w-2 bg-white/50'}`}
                            />
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}

/* ── Main Component ───────────────────────────────────────── */

export default function InstituteDetailPage({ params }) {
    const { slug } = use(params);
    const searchParams = useSearchParams();
    const { currency, isArabic } = useCourseEnglishSettings();
    const { toggleWishlist, toggleCompare, isInWishlist, isInCompare } = useCourseEnglishInteractions();

    const { data: instituteData, loading, error } = useApi(`/courseenglish/language-institutes/${slug}`);

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
    const [couponCode, setCouponCode] = useState("");
    const [toastMsg, setToastMsg] = useState(null);

    const showToast = (msg) => {
        setToastMsg(msg);
        setTimeout(() => setToastMsg(null), 2500);
    };

    const handleShare = () => {
        const url = new URL(window.location.origin + `/language-institutes/${slug}`);
        if (selectedCourseId) url.searchParams.set('course_id', selectedCourseId);
        if (weeks) url.searchParams.set('weeks', weeks);
        if (startDate) url.searchParams.set('start_date', formatLocalDate(startDate));
        if (selectedAccommodationId && selectedAccommodationId !== 'no-acc') url.searchParams.set('accommodation_id', selectedAccommodationId);
        if (selectedPickupId) url.searchParams.set('pickup_id', selectedPickupId);
        if (selectedExtras.length > 0) url.searchParams.set('extras', selectedExtras.join(','));
        if (accAge) url.searchParams.set('acc_age', accAge);
        navigator.clipboard.writeText(url.toString()).then(() => {
            showToast(isArabic ? 'تم نسخ الرابط!' : 'Link copied!');
        });
    };

    const handleToggleWishlist = async () => {
        if (!selectedCourseId) return;
        const result = await toggleWishlist('language_courses', selectedCourseId);
        if (result) showToast(isArabic ? 'تم التحديث!' : (isInWishlist('language_courses', selectedCourseId) ? 'Removed from Favorites' : 'Added to Favorites'));
    };

    const handleToggleCompare = async () => {
        if (!selectedCourseId) return;
        const result = await toggleCompare('language_courses', selectedCourseId);
        if (result) showToast(isArabic ? 'تم التحديث!' : (isInCompare('language_courses', selectedCourseId) ? 'Removed from Compare' : 'Added to Compare'));
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
    // Use pioneers discount for display (from ref screenshots showing 20%)
    const pioneersDisc = pioneersDiscounts.length > 0 ? pioneersDiscounts[0] : null;
    const discountPercent = bestDiscount > 0 ? bestDiscount : (pioneersDisc ? 20 : 0);

    useEffect(() => {
        if (courses.length > 0 && !selectedCourseId) {
            if (initialCourseId) {
                const found = courses.find(c => c.id === initialCourseId);
                setSelectedCourseId(found ? initialCourseId : courses[0].id);
            } else {
                setSelectedCourseId(courses[0].id);
            }
        }
    }, [courses, selectedCourseId, initialCourseId]);

    if (loading) return <div className="p-20 text-center"><div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent" role="status"></div></div>;
    if (error || !school) return <div className="p-20 text-center text-red-500">{isArabic ? "فشل تحميل تفاصيل المعهد." : "Failed to load institute details."}</div>;

    const accreditationLogos = school.accreditations || [];
    const selectedCourse = courses.find(c => c.id === selectedCourseId);
    const selectedAccommodation = accommodations.find(a => a.id === selectedAccommodationId);
    const allExtras = [...insurances, ...supplements];
    const selectedExtrasObjects = allExtras.filter(e => selectedExtras.includes(e.id));
    const selectedPickup = pickUps.find(p => p.id === selectedPickupId);

    const coursePrice = priceField(selectedCourse, "price", currency) * weeks;
    const accPrice = selectedAccommodation ? priceField(selectedAccommodation, "fee_per_week", currency) * weeks : 0;
    const pickupPrice = selectedPickup ? (priceField(selectedPickup, "price", currency) || 0) : 0;
    const extrasPrice = selectedExtrasObjects.reduce((sum, e) => sum + (priceField(e, "price", currency) || priceField(e, "amount", currency) || 0), 0);
    const subtotal = coursePrice + accPrice + pickupPrice + extrasPrice + registrationFee;
    const discountAmount = discountPercent > 0 ? subtotal * (discountPercent / 100) : 0;
    const totalPrice = subtotal - discountAmount;

    const toggleExtra = (id) => {
        setSelectedExtras(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]);
    };

    // School name: For Arabic → ar_name - city_ar - English name
    const schoolDisplayName = isArabic
        ? `${school.ar_name || ''} - ${school.city_ar || ''} - ${school.name}`
        : school.name;

    // Combine school.image + gallery for slider
    const sliderImages = [];
    if (school.image) sliderImages.push(school.image);
    if (school.gallery) sliderImages.push(...school.gallery.filter(img => img !== school.image));

    // Calculate discount price for courses
    const getDiscountPrice = (price) => {
        if (discountPercent > 0) {
            return price * (1 - discountPercent / 100);
        }
        return null;
    };

    return (
        <main className="min-h-screen pb-24 pt-0 lg:pt-6 bg-white">
            <div className="container mx-auto px-4">

                {/* Mobile Bottom Bar */}
                <div className="lg:hidden">
                    <div className="fixed bottom-0 left-1/2 z-30 w-full -translate-x-1/2 bg-white px-4 py-4 shadow-[0_-4px_20px_rgba(0,0,0,0.1)]">
                        <div className="flex items-center justify-between">
                            <div className="text-start">
                                <div className="text-2xl font-semibold leading-none text-slate-900">
                                    <Price value={totalPrice} currency={currency} size="lg" />
                                </div>
                                <div className="mt-1 text-xs text-slate-500">{l("totalIncludes", isArabic)}</div>
                            </div>
                            <Link
                                href={{
                                    pathname: `/language-institutes/${slug}/booking`,
                                    query: {
                                        course_id: selectedCourseId,
                                        weeks: weeks,
                                        start_date: startDate ? formatLocalDate(startDate) : null,
                                        accommodation_id: selectedAccommodationId,
                                        pickup_id: selectedPickupId,
                                        extras: selectedExtras.join(','),
                                        acc_age: accAge
                                    }
                                }}
                                className="flex items-center gap-2 rounded-2xl bg-[#0B5DB6] px-6 py-3 text-sm font-medium text-white shadow-lg shadow-blue-500/30"
                            >
                                <span>{l("reviewRequest", isArabic)}</span>
                                <span className="text-lg">→</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <div>
                    {/* Top Nav - Back + Action Buttons */}
                    <div className="mb-6 flex items-center justify-between hidden lg:flex">
                        <Link href="/language-institutes" className="flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7]">
                            <Image src="/assets/icons/arrow-left.svg" width={16} height={16} alt="Back" />
                            {l("backToList", isArabic)}
                        </Link>
                        {/* Action Buttons: Compare, Favorites, Share */}
                        <div className="flex items-center gap-4 relative">
                            <button onClick={handleToggleCompare} className={`flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition ${selectedCourseId && isInCompare('language_courses', selectedCourseId) ? 'border-[#0057B7] bg-[#0057B7] text-white' : 'border-gray-200 bg-white text-slate-600 hover:border-[#0057B7] hover:text-[#0057B7]'}`}>
                                <FontAwesomeIcon icon={faExchangeAlt} className="h-4 w-4" />
                                {selectedCourseId && isInCompare('language_courses', selectedCourseId) ? l("inCompare", isArabic) : l("addCompare", isArabic)}
                            </button>
                            <button onClick={handleToggleWishlist} className={`flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition ${selectedCourseId && isInWishlist('language_courses', selectedCourseId) ? 'border-red-400 bg-red-50 text-red-500' : 'border-gray-200 bg-white text-slate-600 hover:border-red-400 hover:text-red-500'}`}>
                                <FontAwesomeIcon icon={faHeart} className="h-4 w-4" />
                                {selectedCourseId && isInWishlist('language_courses', selectedCourseId) ? l("inFavorite", isArabic) : l("addFavorite", isArabic)}
                            </button>
                            <button onClick={handleShare} className="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:border-[#0057B7] hover:text-[#0057B7]">
                                <FontAwesomeIcon icon={faShare} className="h-4 w-4" />
                                {l("share", isArabic)}
                            </button>
                            {/* Toast notification */}
                            {toastMsg && (
                                <div className="absolute -bottom-12 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg animate-fade-in z-50">
                                    {toastMsg}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Mobile Hero (Matching Screenshot) */}
                    <div className="lg:hidden -mx-4 mb-6 relative">
                        {/* Hero Image Container */}
                        <div className="relative aspect-[4/3] w-full bg-slate-200">
                            {school.image && <Image src={school.image} alt={t(school.name, school.ar_name, isArabic)} fill className="object-cover" priority unoptimized />}

                            {/* Top Actions */}
                            <div className="absolute top-0 left-0 w-full p-4 flex items-center justify-between z-10 pt-12">
                                <div className="flex items-center gap-3">
                                    <button onClick={handleShare} className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                                        <FontAwesomeIcon icon={faShare} />
                                    </button>
                                    <button onClick={handleToggleWishlist} className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition hover:bg-white ${selectedCourseId && isInWishlist('language_courses', selectedCourseId) ? 'text-red-500' : 'text-slate-700'}`}>
                                        <FontAwesomeIcon icon={faHeart} />
                                    </button>
                                    <button onClick={handleToggleCompare} className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition hover:bg-white ${selectedCourseId && isInCompare('language_courses', selectedCourseId) ? 'text-[#0057B7]' : 'text-slate-700'}`}>
                                        <FontAwesomeIcon icon={faExchangeAlt} />
                                    </button>
                                </div>
                                <Link href="/language-institutes" className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                                    <Image src="/assets/icons/arrow-left.svg" width={20} height={20} alt="Back" className={`transform ${isArabic ? '' : 'rotate-180'}`} />
                                </Link>
                            </div>

                            {/* Image Badge (1/24) */}
                            <div className="absolute bottom-20 right-4 bg-black/60 backdrop-blur-md text-white text-xs font-medium px-3 py-1.5 rounded-full">
                                1/24
                            </div>

                            {/* Logo Overlay */}
                            <div className="absolute bottom-2 left-1/2 -translate-x-1/2 z-20">
                                <div className="p-2 h-30 w-30 rounded-full border-[4px] border-white bg-white shadow-lg overflow-hidden flex items-center justify-center">
                                    {school.logo ? (
                                        <Image src={school.logo} alt="Logo" width={100} height={100} className="object-contain" unoptimized />
                                    ) : (
                                        <span className="text-xl font-medium text-slate-300">LOGO</span>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Mobile Header Info */}
                        <div className="relative z-10 -mt-12 rounded-t-[30px] bg-white pt-16 px-4 text-center pb-2 ">
                            <h1 className="text-xl font-semibold text-slate-900 leading-tight mb-4">
                                {isArabic
                                    ? `${school.ar_name || school.name} - ${school.city_ar || school.city} - ${school.name}`
                                    : `${school.name} - ${school.city}`
                                }
                            </h1>

                            <div className="flex items-center justify-center gap-3 flex-wrap">
                                <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                                    <span className="font-medium text-slate-900 line-clamp-1 text-sm max-w-[150px]">{t(school.location, (school.city_ar ? `${school.country_ar}, ${school.city_ar}` : null), isArabic)}</span>
                                    {school.flag && <Image src={school.flag} width={20} height={14} alt="Flag" className="rounded-sm" unoptimized />}
                                </div>
                                <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                                    <span className="font-medium text-slate-900">{school.rating}</span>
                                    <FontAwesomeIcon icon={faStar} className="text-[#F59E0B] text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Title Header */}
                    <div className="my-8 hidden lg:flex flex-col items-start gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 className="text-3xl font-semibold text-slate-900 md:text-4xl">{schoolDisplayName}</h1>
                            <div className="mt-2 flex items-center gap-2 text-sm font-medium text-slate-500">
                                {school.flag && <Image src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" unoptimized />}
                                <span>{t(school.location, (school.city_ar ? `${school.country_ar} , ${school.city_ar}` : null), isArabic)}</span>
                                <span>•</span>
                                <span className="flex items-center gap-1 text-[#F59E0B]"><FontAwesomeIcon icon={faStar} /> {school.rating}</span>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">

                        {/* Main Content (Left) */}
                        <div className="lg:col-span-8 space-y-8">

                            {/* Description & Gallery */}
                            <div className="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6">
                                {/* Text Side */}
                                <div className="flex-1 space-y-4">
                                    <p className="text-sm leading-relaxed text-slate-600 max-h-40 overflow-y-auto pr-2 scrollbar-thin">
                                        {t(school.description, school.ar_description, isArabic)}
                                    </p>

                                    {/* Accreditations */}
                                    {accreditationLogos.length > 0 && (
                                        <div className="pt-4 border-t border-gray-100">
                                            <div className="flex flex-wrap gap-3">
                                                {accreditationLogos.map((acc, idx) => (
                                                    <div key={acc.id || idx} className="relative h-10 w-16 rounded-lg border border-gray-200 bg-white p-1.5 flex items-center justify-center hover:border-[#0057B7] transition">
                                                        {acc.logo && <Image src={acc.logo} alt={t(acc.name, acc.ar_name, isArabic)} fill className="object-contain p-1" unoptimized />}
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </div>
                                {/* Image Slider Side */}
                                <div className="w-full md:w-2/5 hidden md:block">
                                    <ImageSlider images={sliderImages} schoolName={t(school.name, school.ar_name, isArabic)} />
                                </div>
                            </div>

                            {/* Step 1: Choose Course */}
                            <div>
                                {/* Mobile layout (kept separate from desktop) */}
                                <div className="md:hidden" dir={isArabic ? "rtl" : "ltr"}>
                                    <div className="mb-4 ">
                                        <h3 className="mb-4 text-4xl font-semibold whitespace-nowrap text-slate-900">
                                            {isArabic ? "اختر الدورة" : "Choose Course"}
                                        </h3>
                                        <div className="flex flex-1 justify-start gap-1">
                                            <div className="w-[48%]">
                                                <HeroDatePicker
                                                    label={l("startDate", isArabic)}
                                                    placeholder={l("selectStart", isArabic)}
                                                    selectedDate={startDate}
                                                    onSelect={(date) => setStartDate(date)}
                                                />
                                            </div>
                                            <div className="w-[48%]">
                                                <HeroDropdown
                                                    label={l("numWeeks", isArabic)}
                                                    placeholder={l("selectWeeks", isArabic)}
                                                    scroll
                                                    options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks", isArabic)}`, value: i + 1 }))}
                                                    onSelect={(opt) => setWeeks(opt.value)}
                                                    selectedValue={weeks}
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div className="-mx-4 overflow-x-auto px-4 pb-2">
                                        <div className="flex gap-3">
                                            {courses.map((course) => {
                                                const price = priceField(course, "price", currency);
                                                const discPrice = getDiscountPrice(price);
                                                const isSelected = selectedCourseId === course.id;
                                                return (
                                                    <label
                                                        key={`mobile-${course.id}`}
                                                        className={`w-[310px] max-w-[88vw] shrink-0 cursor-pointer rounded-2xl border-2 bg-white p-3 shadow-sm transition ${isSelected ? "border-[#0B5DB6] ring-1 ring-[#0B5DB6]/20" : "border-[#DCE6F1]"
                                                            }`}
                                                    >
                                                        <div className={`mb-2 flex items-start justify-between gap-2 ${isArabic ? "flex-row-reverse" : ""}`}>
                                                            <img
                                                                src={isSelected ? "/assets/icons/selected-blue.svg" : "/assets/icons/selected-null.svg"}
                                                                alt=""
                                                                className="mt-0.5 h-7 w-7"
                                                            />
                                                            <div className={`flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                                <h4 className="line-clamp-2 text-xl font-semibold text-slate-900">
                                                                    {t(course.name, course.ar_name, isArabic)}
                                                                </h4>
                                                                {course.tag && (
                                                                    <span className="mt-2 inline-flex rounded-md bg-[#22C55E] px-2.5 py-1 text-xs font-medium text-white">
                                                                        {t(course.tag, course.tag_ar, isArabic)}
                                                                    </span>
                                                                )}
                                                            </div>
                                                        </div>

                                                        <div className="mb-2 border-t border-[#DCE6F1]" />

                                                        <div className="space-y-2">
                                                            <div dir="ltr" className={`flex items-center gap-1.5 text-lg text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                                {isArabic ? (
                                                                    <>

                                                                        <span>{l("hoursWeek", isArabic)}</span>
                                                                        <span className="leading-none font-semibold text-slate-900">{course.hours || "-"}</span>
                                                                        <FontAwesomeIcon icon={faClock} className="text-[#0B5DB6]" />
                                                                    </>
                                                                ) : (
                                                                    <>
                                                                        <FontAwesomeIcon icon={faClock} className="text-[#0B5DB6]" />
                                                                        <span className="leading-none font-semibold text-slate-900">{course.hours || "-"}</span>
                                                                        <span>{l("hoursWeek", isArabic)}</span>

                                                                    </>
                                                                )}
                                                            </div>
                                                            <div dir="ltr" className={`flex items-center gap-1.5 text-lg text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                                {isArabic ? (
                                                                    <>

                                                                        <span>{l("requiredLevel", isArabic)}</span>
                                                                        <span className="leading-none font-semibold text-slate-900">{course.level || "-"}</span>
                                                                        <FontAwesomeIcon icon={faSignal} className="text-[#0B5DB6]" />
                                                                    </>
                                                                ) : (
                                                                    <>
                                                                        <FontAwesomeIcon icon={faSignal} className="text-[#0B5DB6]" />
                                                                        <span className="leading-none font-semibold text-slate-900">{course.level || "-"}</span>
                                                                        <span>{l("requiredLevel", isArabic)}</span>

                                                                    </>
                                                                )}
                                                            </div>
                                                            <div dir="ltr" className={`flex items-center gap-1.5 text-lg text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                                {isArabic ? (
                                                                    <>

                                                                        <span>{l("lessonsWeek", isArabic)}</span>
                                                                        <span className="leading-none font-semibold text-slate-900">{course.lessons || "-"}</span>
                                                                        <FontAwesomeIcon icon={faBookOpen} className="text-[#0B5DB6]" />
                                                                    </>
                                                                ) : (
                                                                    <>
                                                                        <FontAwesomeIcon icon={faBookOpen} className="text-[#0B5DB6]" />
                                                                        <span className="leading-none font-semibold text-slate-900">{course.lessons || "-"}</span>
                                                                        <span>{l("lessonsWeek", isArabic)}</span>

                                                                    </>
                                                                )}
                                                            </div>
                                                            <div dir="ltr" className={`flex items-center gap-1.5 text-lg text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                                {isArabic ? (
                                                                    <>

                                                                        <span>{l("requiredAge", isArabic)}</span>
                                                                        <span className="leading-none font-semibold text-slate-900">+{course.min_age || "-"}</span>
                                                                        <FontAwesomeIcon icon={faUser} className="text-[#0B5DB6]" />
                                                                    </>
                                                                ) : (
                                                                    <>
                                                                        <FontAwesomeIcon icon={faUser} className="text-[#0B5DB6]" />
                                                                        <span className="leading-none font-semibold text-slate-900">+{course.min_age || "-"}</span>
                                                                        <span>{l("requiredAge", isArabic)}</span>

                                                                    </>
                                                                )}
                                                            </div>
                                                        </div>

                                                        <div className="mt-3 border-t border-[#DCE6F1] pt-3">
                                                            <div className="flex gap-2">
                                                                <div className="flex items-center gap-1 whitespace-nowrap">
                                                                    <span className="text-xl font-semibold text-slate-900">
                                                                        <Price value={discPrice || price} currency={currency} size="lg" />
                                                                    </span>
                                                                    <span className="pb-1 text-lg text-slate-500">{l("perWeek", isArabic)}</span>
                                                                </div>

                                                                <div className="flex items-center gap-2">
                                                                    <span className="text-base font-medium text-slate-400 line-through">
                                                                        <Price value={price} currency={currency} size="sm" />
                                                                    </span>
                                                                    <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-medium text-white">
                                                                        -{discountPercent}%
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div className="mt-3">
                                                                <span
                                                                    className={`flex w-full items-center justify-center rounded-xl px-4 py-3 text-base font-semibold ${isSelected ? "bg-[#DCE6F1] text-slate-800" : "bg-[#0B5DB6] text-white"
                                                                        }`}
                                                                >
                                                                    {isSelected
                                                                        ? (isArabic ? "تم اختيار الدورة" : "Course selected")
                                                                        : (isArabic ? "اختيار الدورة" : "Choose Course")}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <input
                                                            type="radio"
                                                            name="course-mobile"
                                                            className="hidden"
                                                            value={course.id}
                                                            checked={isSelected}
                                                            onChange={() => setSelectedCourseId(course.id)}
                                                        />
                                                    </label>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>

                                {/* Desktop / large screens (unchanged structure) */}
                                <div className="hidden md:block">
                                    <div className="mb-2 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div>
                                            <h3 className="text-xl font-semibold text-slate-900">{l("step1", isArabic)}</h3>
                                            <p className="text-sm text-slate-500 mt-1">{l("step1Sub", isArabic)}</p>
                                        </div>
                                        <div className="flex gap-3 w-full md:w-auto">
                                            <div className="w-full md:w-48">
                                                <HeroDatePicker
                                                    label={l("startDate", isArabic)}
                                                    placeholder={l("selectStart", isArabic)}
                                                    selectedDate={startDate}
                                                    onSelect={(date) => setStartDate(date)}
                                                />
                                            </div>
                                            <div className="w-full md:w-50">
                                                <HeroDropdown
                                                    label={l("numWeeks", isArabic)}
                                                    placeholder={l("selectWeeks", isArabic)}
                                                    scroll
                                                    options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks", isArabic)}`, value: i + 1 }))}
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
                                                <label key={course.id} className={`block cursor-pointer rounded-2xl border-2 overflow-hidden transition-all bg-white ${selectedCourseId === course.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                                    {/* Row 1: Icon + Title + Tag */}
                                                    <div className="flex items-center gap-3 px-5 pt-4 pb-3">
                                                        <div className="flex-1 flex items-center gap-2">
                                                            <h4 className="text-base font-medium text-slate-900">{t(course.name, course.ar_name, isArabic)}</h4>
                                                            {course.tag && <span className="rounded-md bg-[#10B981] px-2.5 py-1 text-[11px] font-medium text-white">{t(course.tag, course.tag_ar, isArabic)}</span>}
                                                        </div>
                                                        <div className="flex-none">
                                                            <img src={selectedCourseId === course.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-7 w-7" />
                                                        </div>
                                                    </div>
                                                    {/* Row 2: Details + Price */}
                                                    <div className="flex items-center justify-between px-5 pb-4 pt-2">
                                                        <div className="flex flex-wrap gap-4 text-xs font-medium text-slate-500">
                                                            <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faBookOpen} className="text-[#0057B7]" /> {course.lessons} {l("lessonsWeek", isArabic)}</span>
                                                            <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faClock} className="text-[#0057B7]" /> {course.hours} {l("hoursWeek", isArabic)}</span>
                                                            <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faUser} className="text-[#0057B7]" /> +{course.min_age} {l("requiredAge", isArabic)}</span>
                                                            <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faSignal} className="text-[#0057B7]" /> {course.level} {l("requiredLevel", isArabic)}</span>
                                                        </div>
                                                        <div className="text-left min-w-[100px]">
                                                            <div className="flex items-center text-xl font-semibold text-slate-900">
                                                                <Price value={discPrice || price} currency={currency} />
                                                                <div className="text-xl font-extralight text-slate-500">{l("perWeek", isArabic)}</div>
                                                            </div>
                                                            {discPrice && (
                                                                <div className="flex items-center gap-2 mt-1">
                                                                    <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-medium text-white flex items-center gap-0.5">
                                                                        <span className="rotate-180">↻</span> {discountPercent}%-
                                                                    </span>
                                                                    <span className="text-sm font-medium text-slate-400 line-through"><Price value={price} currency={currency} size="sm" /></span>
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="course" className="hidden" value={course.id} checked={selectedCourseId === course.id} onChange={() => setSelectedCourseId(course.id)} />
                                                </label>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>

                            {/* Step 2: Choose Accommodation */}
                            <div>
                                {/* Mobile layout */}
                                <div className="md:hidden" dir={isArabic ? "rtl" : "ltr"}>
                                    <div className={`mb-2 ${isArabic ? "text-right" : "text-left"}`}>
                                        <h3 className="text-4xl font-semibold text-slate-900">{isArabic ? "السكن" : "Accommodation"}</h3>
                                        <p className="mt-1 text-sm text-slate-500">{l("step2Sub", isArabic)}</p>
                                    </div>

                                    <div className="mt-3 grid grid-cols-2 gap-2">
                                        <button
                                            type="button"
                                            onClick={() => setSelectedAccommodationId('no-acc')}
                                            className={`relative rounded-2xl border bg-white px-3 py-4 transition ${selectedAccommodationId === 'no-acc' ? 'border-[#0B5DB6]' : 'border-[#DCE6F1]'
                                                }`}
                                        >
                                            <img
                                                src={selectedAccommodationId === 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                                alt=""
                                                className={`absolute top-7 h-7 w-7 -translate-y-1/2 ${isArabic ? "right-3" : "left-3"}`}
                                            />
                                            <span className="block  text-md font-semibold text-slate-900">{l("withoutAcc", isArabic)}</span>

                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => {
                                                if (accommodations.length > 0) {
                                                    setSelectedAccommodationId(accommodations[0].id);
                                                }
                                            }}
                                            className={`relative rounded-2xl border bg-white px-3 py-4 transition ${selectedAccommodationId !== 'no-acc' ? 'border-[#0B5DB6]' : 'border-[#DCE6F1]'
                                                }`}
                                        >
                                            <span className="block text-center text-xl font-semibold text-slate-900">{l("withAcc", isArabic)}</span>
                                            <img
                                                src={selectedAccommodationId !== 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                                alt=""
                                                className={`absolute top-7 h-7 w-7 -translate-y-1/2 ${isArabic ? "right-3" : "left-3"}`}
                                            />
                                        </button>
                                    </div>

                                    <div className="mt-3 grid grid-cols-2 gap-2">
                                        <HeroDropdown
                                            label={l("age", isArabic)}
                                            placeholder={l("age", isArabic)}
                                            scroll
                                            options={[
                                                { label: "16", value: 16 },
                                                { label: "17", value: 17 },
                                                { label: "18+", value: 18 },
                                            ]}
                                            onSelect={(opt) => setAccAge(opt.value)}
                                            selectedValue={accAge}
                                        />
                                        <HeroDropdown
                                            label={l("numWeeks", isArabic)}
                                            placeholder={l("selectWeeks", isArabic)}
                                            scroll
                                            options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1}`, value: i + 1 }))}
                                            onSelect={(opt) => setWeeks(opt.value)}
                                            selectedValue={weeks}
                                        />
                                    </div>

                                    <div className="mt-3 space-y-3">
                                        {accommodations.length > 0 && (
                                            <div className="-mx-4 overflow-x-auto px-4 pb-2">
                                                <div className="flex snap-x snap-mandatory gap-3">
                                                    {accommodations.map((acc) => {
                                                        const accPrice = priceField(acc, "fee_per_week", currency);
                                                        const accDiscPrice = getDiscountPrice(accPrice);
                                                        const featureList = (Array.isArray(acc.features) ? acc.features : [])
                                                            .filter(Boolean)
                                                            .map((feature) => localizeFeature(feature, isArabic));
                                                        const isSelected = selectedAccommodationId === acc.id;

                                                        return (
                                                            <label
                                                                key={acc.id}
                                                                className={`w-[310px] max-w-[88vw] shrink-0 snap-start cursor-pointer rounded-2xl border-2 bg-white p-4 shadow-sm transition ${isSelected ? 'border-[#0B5DB6]' : 'border-[#DCE6F1]'}`}
                                                            >
                                                                <div className="flex items-start gap-3">
                                                                    <img
                                                                        src={isSelected ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                                                        alt=""
                                                                        className="mt-0.5 h-8 w-8 shrink-0"
                                                                    />
                                                                    <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                                        <h4 className="line-clamp-1 text-2xl font-semibold leading-tight text-slate-900">{t(acc.title, acc.ar_title, isArabic)}</h4>
                                                                        <div className="mt-2 flex flex-wrap gap-2">
                                                                            {acc.tag && (
                                                                                <span className="inline-flex rounded-md bg-[#22C55E] px-2.5 py-1 text-xs font-medium text-white">
                                                                                    {t(acc.tag, acc.tag_ar, isArabic)}
                                                                                </span>
                                                                            )}
                                                                            {discountPercent > 0 && (
                                                                                <span className="inline-flex rounded-md bg-[#22C55E] px-2.5 py-1 text-xs font-medium text-white">
                                                                                    {isArabic ? `% ${discountPercent} خصم` : `${discountPercent}% Off`}
                                                                                </span>
                                                                            )}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div className="mt-3 border-t border-[#DCE6F1]" />

                                                                <div className="mt-3 space-y-2">
                                                                    {featureList.slice(0, 3).map((feature, idx) => (
                                                                        <div
                                                                            key={`feature-row-${idx}`}
                                                                            className={`flex items-center gap-1.5 text-lg text-slate-500 ${isArabic ? "" : ""}`}
                                                                        >
                                                                            {isArabic ? (
                                                                                <>
                                                                                    <FontAwesomeIcon icon={featureIcon(feature, idx)} className="text-[#0B5DB6]" />
                                                                                    <span>{feature}</span>

                                                                                </>
                                                                            ) : (
                                                                                <>
                                                                                    <span>{feature}</span>
                                                                                    <FontAwesomeIcon icon={featureIcon(feature, idx)} className="text-[#0B5DB6]" />

                                                                                </>
                                                                            )}
                                                                        </div>
                                                                    ))}
                                                                </div>

                                                                <div className="mt-3 border-t border-[#DCE6F1]" />

                                                                <div className="mt-3 flex items-center  gap-2">
                                                                    <div className="flex items-center gap-1 whitespace-nowrap">
                                                                        <span className="text-xl font-semibold text-slate-900">
                                                                            <Price value={accDiscPrice || accPrice} currency={currency} size="lg" />
                                                                        </span>
                                                                        <span className="text-lg text-slate-500">{l("perWeek", isArabic)}</span>

                                                                    </div>
                                                                    {accDiscPrice && (
                                                                        <div className="flex items-center gap-2">
                                                                            <span className="text-lg font-medium text-slate-400 line-through">
                                                                                <Price value={accPrice} currency={currency} size="sm" />
                                                                            </span>

                                                                        </div>
                                                                    )}

                                                                </div>

                                                                <div className="mt-3">
                                                                    <span
                                                                        className={`flex w-full items-center justify-center rounded-xl px-4 py-3 text-xl font-semibold ${isSelected ? "bg-[#DCE6F1] text-slate-800" : "bg-[#0B5DB6] text-white"
                                                                            }`}
                                                                    >
                                                                        {isSelected
                                                                            ? (isArabic ? "تم اختيار السكن" : "Accommodation selected")
                                                                            : (isArabic ? "اختيار" : "Choose")}
                                                                    </span>
                                                                </div>

                                                                <input
                                                                    type="radio"
                                                                    name="accommodation-mobile"
                                                                    className="hidden"
                                                                    value={acc.id}
                                                                    checked={isSelected}
                                                                    onChange={() => setSelectedAccommodationId(acc.id)}
                                                                />
                                                            </label>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Desktop / large screens (unchanged structure) */}
                                <div className="hidden md:block">
                                    <div className="mb-2 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div>
                                            <h3 className="text-xl font-semibold text-slate-900">{l("step2", isArabic)}</h3>
                                            <p className="text-sm text-slate-500 mt-1">{l("step2Sub", isArabic)}</p>
                                        </div>
                                        <div className="flex gap-3 w-full md:w-auto">
                                            <div className="w-full md:w-48">
                                                <HeroDropdown
                                                    label={l("age", isArabic)}
                                                    placeholder={l("age", isArabic)}
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
                                                    label={l("numWeeks", isArabic)}
                                                    placeholder={l("selectWeeks", isArabic)}
                                                    scroll
                                                    options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1}`, value: i + 1 }))}
                                                    onSelect={(opt) => setWeeks(opt.value)}
                                                    selectedValue={weeks}
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div className="space-y-4 mt-4">
                                        {/* Without Accommodation Card */}
                                        <label className={`block cursor-pointer rounded-2xl border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === 'no-acc' ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                            {/* Row 1: Title + Icon */}
                                            <div className="flex items-center gap-3 px-5 pt-4 pb-3">
                                                <div className="flex-1">
                                                    <h4 className="text-base font-medium text-slate-900">{l("withoutAcc", isArabic)}</h4>
                                                    <p className="text-sm text-slate-500">{l("withoutAccSub", isArabic)}</p>
                                                </div>
                                                <div className="flex-none">
                                                    <img src={selectedAccommodationId === 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-7 w-7" />
                                                </div>
                                            </div>
                                            <input type="radio" name="accommodation" className="hidden" value="no-acc" checked={selectedAccommodationId === 'no-acc'} onChange={() => setSelectedAccommodationId('no-acc')} />
                                        </label>

                                        {/* Accommodation Options */}
                                        {accommodations.map((acc) => {
                                            const accPrice = priceField(acc, "fee_per_week", currency);
                                            const accDiscPrice = getDiscountPrice(accPrice);
                                            return (
                                                <label key={acc.id} className={`block cursor-pointer rounded-2xl border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === acc.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                                    {/* Row 1: Title + Tag + Icon */}
                                                    <div className="flex items-center gap-3 px-5 pt-4 pb-3">
                                                        <div className="flex-1 flex items-center gap-2">
                                                            <h4 className="text-base font-medium text-slate-900">{t(acc.title, acc.ar_title, isArabic)}</h4>
                                                            {acc.tag && <span className="rounded-md bg-[#10B981] px-2.5 py-1 text-[11px] font-medium text-white">{t(acc.tag, acc.tag_ar, isArabic)}</span>}
                                                        </div>
                                                        <div className="flex-none">
                                                            <img src={selectedAccommodationId === acc.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-7 w-7" />
                                                        </div>
                                                    </div>
                                                    {/* Row 2: Features + Price */}
                                                    <div className="flex items-center justify-between px-5 pb-4 pt-2">
                                                        <div>
                                                            {(acc.features && acc.features.length > 0) && (
                                                                <div className="flex flex-wrap gap-2">
                                                                    {acc.features.map((feature, idx) => (
                                                                        <span key={idx} className="flex items-center gap-1.5 rounded-lg bg-gray-50 px-3 py-1.5 text-xs font-medium text-slate-600 border border-gray-100">
                                                                            <FontAwesomeIcon icon={faCheckCircle} className="text-[#10B981] h-3 w-3" />
                                                                            {feature}
                                                                        </span>
                                                                    ))}
                                                                </div>
                                                            )}
                                                        </div>
                                                        <div className="text-left min-w-[100px]">
                                                            <div className="flex items-center text-xl font-semibold text-slate-900">
                                                                <Price value={accDiscPrice || accPrice} currency={currency} />
                                                                <div className="text-xl font-extralight text-slate-500">{l("perWeek", isArabic)}</div>
                                                            </div>
                                                            {accDiscPrice && (
                                                                <div className="flex items-center gap-2 mt-1">
                                                                    <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-medium text-white flex items-center gap-0.5">
                                                                        <span className="rotate-180">↻</span> {discountPercent}%-
                                                                    </span>
                                                                    <span className="text-sm font-medium text-slate-400 line-through"><Price value={accPrice} currency={currency} size="sm" /></span>
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="accommodation" className="hidden" value={acc.id} checked={selectedAccommodationId === acc.id} onChange={() => setSelectedAccommodationId(acc.id)} />
                                                </label>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>

                            {/* ── Extra Options Section ── */}
                            {(pickUps.length > 0 || insurances.length > 0 || supplements.length > 0) && (
                                <div>
                                    <h3 className="text-xl font-semibold text-slate-900">{l("extraOptions", isArabic)}</h3>
                                    <p className="text-sm text-slate-400 mt-1 mb-6">{l("extraOptionsSub", isArabic)}</p>
                                </div>
                            )}

                            {/* Airport Pickups */}
                            {pickUps.length > 0 && (
                                <>
                                    {/* Mobile cards */}
                                    <div className="md:hidden" dir={isArabic ? "rtl" : "ltr"}>
                                        <div className="-mx-4 overflow-x-auto px-4 pb-2">
                                            <div className="flex snap-x snap-mandatory gap-3">
                                                <label className="relative w-[160px] shrink-0 snap-start cursor-pointer rounded-2xl border border-[#DCE6F1] bg-white px-4 py-4 shadow-sm">
                                                    <img
                                                        src={selectedPickupId === null ? "/assets/icons/selected-blue.svg" : "/assets/icons/selected-null.svg"}
                                                        alt=""

                                                        className={`absolute  top-3 h-7 w-7 ${isArabic ? "left-3" : "right-3"}`}
                                                    />
                                                    <div className="pt-1">
                                                        <h4 className=" text-xl font-semibold leading-tight text-slate-900">
                                                            {l("noPickup", isArabic)}
                                                        </h4>
                                                    </div>
                                                    <input
                                                        type="radio"
                                                        name="pickup-mobile"
                                                        className="hidden"
                                                        checked={selectedPickupId === null}
                                                        onChange={() => setSelectedPickupId(null)}
                                                    />
                                                </label>

                                                {pickUps.map((pickup) => (
                                                    <label
                                                        key={pickup.id}
                                                        className="relative w-[210px] shrink-0 snap-start cursor-pointer rounded-2xl border border-[#DCE6F1] bg-white px-4 py-4 shadow-sm"
                                                    >
                                                        <img
                                                            src={selectedPickupId === pickup.id ? "/assets/icons/selected-blue.svg" : "/assets/icons/selected-null.svg"}
                                                            alt=""
                                                            className={`absolute  top-3 h-7 w-7 ${isArabic ? "left-3" : "right-3"}`}
                                                        />
                                                        <div className="pt-1">
                                                            <h4 className="line-clamp-2 text-md font-semibold leading-tight text-slate-900">
                                                                {t(pickup.route, pickup.ar_route, isArabic)}
                                                            </h4>
                                                            <div className="mt-3 flex items-center gap-1">
                                                                <span className="text-md font-semibold leading-none text-slate-900">
                                                                    <Price value={priceField(pickup, "price", currency)} currency={currency} size="lg" />
                                                                </span>

                                                            </div>
                                                        </div>
                                                        <input
                                                            type="radio"
                                                            name="pickup-mobile"
                                                            className="hidden"
                                                            checked={selectedPickupId === pickup.id}
                                                            onChange={() => setSelectedPickupId(pickup.id)}
                                                        />
                                                    </label>
                                                ))}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Desktop list (unchanged) */}
                                    <div className="hidden md:block">
                                        <details open className="rounded-2xl border border-gray-200 bg-white overflow-hidden">
                                            <summary className="flex items-center justify-between cursor-pointer px-5 py-4 select-none list-none [&::-webkit-details-marker]:hidden">
                                                <h3 className="text-base font-medium text-slate-900">
                                                    {l("step3pickups", isArabic)}
                                                </h3>
                                                <svg className="h-4 w-4 text-slate-400 transition-transform [[open]>&]:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clipRule="evenodd" /></svg>
                                            </summary>
                                            <div className="divide-y divide-gray-100">
                                                {/* No pickup option */}
                                                <label className="block cursor-pointer px-5 py-4 hover:bg-gray-50 transition-colors">
                                                    <div className="flex items-start gap-3">
                                                        <div className="flex-1">
                                                            <span className="font-medium text-sm text-slate-900">{l("noPickup", isArabic)}</span>
                                                            <p className="text-xs text-slate-400 mt-0.5">{l("noPickupSub", isArabic)}</p>
                                                        </div>
                                                        <div className="flex-none pt-0.5">
                                                            <img src={selectedPickupId === null ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="pickup" className="hidden" checked={selectedPickupId === null} onChange={() => setSelectedPickupId(null)} />
                                                </label>
                                                {/* Pickup options */}
                                                {pickUps.map((pickup) => (
                                                    <label key={pickup.id} className="block cursor-pointer px-5 py-4 hover:bg-gray-50 transition-colors">
                                                        <div className="flex items-start gap-3">
                                                            <div className="flex-1">
                                                                <span className="font-medium text-sm text-slate-900">{t(pickup.route, pickup.ar_route, isArabic)}</span>
                                                                <div className="flex items-center gap-1 mt-0.5">
                                                                    <span className="text-sm font-semibold text-slate-800"><Price value={priceField(pickup, "price", currency)} currency={currency} size="sm" /></span>
                                                                    <span className="text-xs text-slate-400">{l("perWeek", isArabic)}</span>
                                                                </div>
                                                            </div>
                                                            <div className="flex-none pt-0.5">
                                                                <img src={selectedPickupId === pickup.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                                            </div>
                                                        </div>
                                                        <input type="radio" name="pickup" className="hidden" checked={selectedPickupId === pickup.id} onChange={() => setSelectedPickupId(pickup.id)} />
                                                    </label>
                                                ))}
                                            </div>
                                        </details>
                                    </div>
                                </>
                            )}

                            {/* Insurance */}
                            {insurances.length > 0 && (
                                <div>
                                    <h3 className="mb-6 text-xl font-semibold text-slate-900 flex items-center gap-2">
                                        <FontAwesomeIcon icon={faShieldAlt} className="text-[#0057B7]" />
                                        {l("step3insurance", isArabic)}
                                    </h3>
                                    <div className="space-y-4">
                                        {insurances.map((ins) => (
                                            <label key={ins.id} className={`block cursor-pointer rounded-2xl border-2 p-5 transition-all ${selectedExtras.includes(ins.id) ? 'border-[#0057B7] bg-[#F0F7FC]' : 'border-gray-100 bg-white hover:border-gray-200'}`}>
                                                <div className="flex items-center gap-4">
                                                    <div className="flex-1 flex items-center">
                                                        <div>
                                                            <div>
                                                                <h4 className="font-medium text-slate-900">{t(ins.name, ins.ar_name, isArabic)}</h4>

                                                            </div>
                                                            <div className=" flex font-semibold text-slate-900">
                                                                <Price value={priceField(ins, "amount", currency)} currency={currency} />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="flex-none">
                                                        <img src={selectedExtras.includes(ins.id) ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-7 w-7" />
                                                    </div>
                                                </div>
                                                <input type="checkbox" className="hidden" checked={selectedExtras.includes(ins.id)} onChange={() => toggleExtra(ins.id)} />
                                            </label>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Supplements */}
                            {supplements.length > 0 && (
                                <div>
                                    <h3 className="mb-6 text-xl font-semibold text-slate-900 flex items-center gap-2">
                                        <FontAwesomeIcon icon={faPlusCircle} className="text-[#0057B7]" />
                                        {l("step3supplements", isArabic)}
                                    </h3>
                                    <div className="space-y-4">
                                        {supplements.map((supp) => (
                                            <label key={supp.id} className={`block cursor-pointer rounded-2xl border-2 p-5 transition-all ${selectedExtras.includes(supp.id) ? 'border-[#0057B7] bg-[#F0F7FC]' : 'border-gray-100 bg-white hover:border-gray-200'}`}>
                                                <div className="flex items-center gap-4">
                                                    <div className="flex-1 flex justify-between items-center">
                                                        <div>
                                                            <h4 className="font-medium text-slate-900">{t(supp.name, supp.ar_name, isArabic)}</h4>

                                                            <div className="font-semibold text-slate-900">
                                                                <Price value={priceField(supp, "amount", currency)} currency={currency} />
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div className="flex-none">
                                                        <img src={selectedExtras.includes(supp.id) ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-7 w-7" />
                                                    </div>
                                                </div>
                                                <input type="checkbox" className="hidden" checked={selectedExtras.includes(supp.id)} onChange={() => toggleExtra(supp.id)} />
                                            </label>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Coupon box for mobile/tablet (sidebar is desktop only) */}
                            <div className="mt-8 lg:hidden" dir={isArabic ? "rtl" : "ltr"}>
                                <h3 className="mb-3 text-center text-3xl font-semibold text-slate-900">
                                    {l("couponQ", isArabic)}
                                </h3>
                                <div className="rounded-3xl border border-[#DCE6F1] bg-white p-2">
                                    <div className="relative">
                                        <input
                                            type="text"
                                            value={couponCode}
                                            onChange={(e) => setCouponCode(e.target.value)}
                                            placeholder={l("couponCode", isArabic)}
                                            className={`h-16 w-full rounded-2xl border-0 bg-transparent text-lg font-medium text-slate-700 outline-none placeholder:text-slate-500 ${isArabic ? "pl-28 pr-5 text-right" : "pl-5 pr-28 text-left"
                                                }`}
                                        />
                                        <button
                                            type="button"
                                            className={`absolute top-1/2 -translate-y-1/2 rounded-2xl bg-[#CBD5E1] px-8 py-3 text-xl font-semibold text-slate-600 ${isArabic ? "left-2" : "right-2"
                                                }`}
                                        >
                                            {l("apply", isArabic)}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {/* Sidebar (Right) */}
                        <div className="lg:col-span-4 hidden lg:block">
                            <InstituteSidebar
                                institute={instituteData}
                                selectedCourse={selectedCourse}
                                selectedAccommodation={selectedAccommodationId !== 'no-acc' ? selectedAccommodation : null}
                                selectedPickup={selectedPickup}
                                selectedExtras={selectedExtrasObjects}
                                weeks={weeks}
                                startDate={startDate}
                                onDateChange={setStartDate}
                                onWeeksChange={setWeeks}
                                currency={currency}
                                isArabic={isArabic}
                                registrationFee={registrationFee}
                                discountPercent={discountPercent}
                                slug={slug}
                                accAge={accAge}
                            />
                        </div>
                    </div>
                </div>

            </div>
        </main>
    );
}
