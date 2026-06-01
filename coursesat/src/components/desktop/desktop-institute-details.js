"use client";

import { useState, useEffect, useRef } from "react";
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
import { useCurrency } from "@/components/providers/currency-provider";
import { useApi } from "@/lib/api";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import {
    computeInstitutePricing,
    getItemPrice,
    resolveWeeklyCourseFee,
} from "@/lib/institute-pricing";
import PioneersDiscountModal from "@/components/shared/pioneers-discount-modal";
import ReferralDiscountModal from "@/components/shared/referral-discount-modal";
import { resolveCoursePromotionPercent,
    resolveQualifyingPioneersDiscounts,
} from "@/lib/pioneers-discount";
import { applyReferralCode, getStoredReferral } from "@/lib/referral";
import { getStoredAuthUser } from "@/lib/auth";
import { buildInstituteBookingUrl, formatInstituteQueryDate } from "@/lib/institute-booking-url";
import { useCourseEnglishInteractions } from "@/lib/interactions";

function Price({ value, currency, activeCurrency, className = "", size = "md", muted = false }) {
    if (value === null || value === undefined) return null;

    const iconClassName = size === "lg" ? "h-[30px] w-[30px]" : size === "sm" ? "h-[10px] w-[10px]" : "h-[14px] w-[14px]";
    const textClass = size === "lg" ? "text-[30px] font-bold" : size === "sm" ? "text-[14px]" : "text-[18px] font-bold";

    return (
        <CurrencyAmount
            currency={currency}
            amount={value}
            activeCurrency={activeCurrency}
            className={`inline-flex items-center ${textClass} ${className}`}
            iconClassName={iconClassName}
            variant="light"
            muted={muted}
        />
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

const AR_FEATURE_MAP = {
    "single room": "غرفة فردية",
    "shared bathroom": "حمام مشترك",
    "halfboard": "نصف إقامة",
    "fullboard": "إقامة كاملة",
    "private bathroom": "حمام خاص",
    "age 18+": "+18 العمر",
};

function normalizeFeatureList(features, isArabic, featuresAr) {
    if (isArabic && Array.isArray(featuresAr) && featuresAr.length > 0) {
        return featuresAr.filter(Boolean).map((feature) => String(feature).trim());
    }

    const list = Array.isArray(features)
        ? features
        : typeof features === "string"
            ? features.split(",")
            : [];

    return list
        .filter(Boolean)
        .map((feature) => {
            const text = String(feature).trim();
            if (!isArabic) return text;
            return AR_FEATURE_MAP[text.toLowerCase()] || text;
        });
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

export default function DesktopInstituteDetails({ slug: slugProp }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { language, t } = useLocale();
    const { currency, activeCurrency } = useCurrency();
    const isArabic = language === "ar";

    const slug = slugProp;
    const apiPath = slug
        ? `/coursesat/language-institutes/${encodeURIComponent(slug)}${searchParams.toString() ? `?${searchParams.toString()}` : ""}`
        : null;
    const { data: instituteData, loading } = useApi(apiPath);

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
    const [pioneersPopup, setPioneersPopup] = useState(null);
    const [referralPopup, setReferralPopup] = useState(null);
    const [appliedReferral, setAppliedReferral] = useState(null);
    const [referralCodeInput, setReferralCodeInput] = useState("");
    const [referralApplying, setReferralApplying] = useState(false);
    const [referralError, setReferralError] = useState("");
    const pioneersSnapshotRef = useRef("");
    const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
    const interactionType = "language_courses";

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

    const handleApplyReferral = async () => {
        const code = referralCodeInput.trim();
        if (!code) {
            setReferralError(isArabic ? "يرجى إدخال كود الإحالة" : "Please enter a referral code.");
            return;
        }

        setReferralApplying(true);
        setReferralError("");

        try {
            const authUser = getStoredAuthUser();
            const data = await applyReferralCode(code);

            if (
                data.referrer_type === "student"
                && authUser?.id
                && Number(data.referrer_user_id) === Number(authUser.id)
            ) {
                throw new Error(isArabic ? "لا يمكنك استخدام كود الإحالة الخاص بك" : "You cannot use your own referral code.");
            }

            const referralEntry = {
                code: data.referral_code || code.toUpperCase(),
                referrer_type: data.referrer_type,
                referrer_name: data.referrer_name,
                discount_percent: data.discount_percent,
            };
            setAppliedReferral(referralEntry);
            setReferralCodeInput(referralEntry.code);
            setReferralPopup(referralEntry);
            showToast(isArabic ? "تم تطبيق كود الإحالة" : "Referral code applied.");
        } catch (err) {
            setReferralError(err.message || (isArabic ? "كود إحالة غير صالح" : "Invalid referral code."));
        } finally {
            setReferralApplying(false);
        }
    };

    const school = instituteData?.school;
    const courses = instituteData?.courses || [];
    const accommodations = instituteData?.accommodations || [];
    const pickUps = instituteData?.pickups || [];
    const insurances = instituteData?.insurances || [];
    const supplements = instituteData?.supplements || [];

    useEffect(() => {
        if (courses.length > 0 && !selectedCourseId) {
            setSelectedCourseId(initialCourseId || courses[0].id);
        }
    }, [courses, selectedCourseId, initialCourseId]);

    useEffect(() => {
        if (insurances.length === 0) return;
        const mandatoryIds = insurances.filter((ins) => ins.is_mandatory).map((ins) => ins.id);
        if (mandatoryIds.length === 0) return;
        setSelectedExtras((prev) => {
            const merged = [...new Set([...prev, ...mandatoryIds])];
            if (merged.length === prev.length && mandatoryIds.every((id) => prev.includes(id))) {
                return prev;
            }
            return merged;
        });
    }, [insurances]);

    useEffect(() => {
        const stored = getStoredReferral();
        if (stored?.code) {
            setAppliedReferral(stored);
            setReferralCodeInput(stored.code);
        }
    }, []);

    useEffect(() => {
        const tiers = instituteData?.pioneers_discounts || [];
        if (!tiers.length) return;

        const qualified = resolveQualifyingPioneersDiscounts(weeks, tiers, currency);
        const snapshot = qualified
            .map((tier) => `${tier.id}:${tier.type === "cash" ? `${tier.multiplier}x${tier.total}` : tier.freeFor}`)
            .join("|");

        if (pioneersSnapshotRef.current && snapshot !== pioneersSnapshotRef.current && qualified.length > 0) {
            setPioneersPopup({ qualifying: qualified, weeks });
        }
        pioneersSnapshotRef.current = snapshot;
    }, [weeks, instituteData, currency]);

    if (loading && !instituteData) {
        return (
            <div className="flex min-h-[50vh] items-center justify-center">
                <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
            </div>
        );
    }

    if (!loading && !school) {
        return (
            <div className="flex min-h-[50vh] flex-col items-center justify-center px-4 text-center">
                <p className="text-lg text-slate-600">{isArabic ? "المعهد غير موجود" : "Institute not found"}</p>
                <Link href="/language-institutes" className="mt-4 text-[#0057B7] hover:underline">
                    {isArabic ? "العودة إلى المعاهد" : "Back to institutes"}
                </Link>
            </div>
        );
    }

    const regFeeObj = instituteData?.registration_fee;
    const l = (key) => t(`pages.institute_details.${key}`);
    const loc = (en, ar) => (isArabic && ar) ? ar : en;

    const discounts = instituteData?.discounts || [];
    const pioneersDiscounts = instituteData?.pioneers_discounts || [];

    const accreditationLogos = school?.accreditations || [];
    const selectedCourse = courses.find(c => c.id === selectedCourseId);
    const courseDiscountPercent = resolveCoursePromotionPercent(selectedCourseId, discounts, selectedCourse);

    const handleToggleWishlist = async () => {
        if (!selectedCourseId) return;
        const added = !isInWishlist(interactionType, selectedCourseId);
        const ok = await toggleWishlist(interactionType, selectedCourseId);
        if (ok) {
            showToast(added
                ? (isArabic ? "تمت الإضافة إلى المفضلة" : "Added to wishlist")
                : (isArabic ? "تمت الإزالة من المفضلة" : "Removed from wishlist"));
        }
    };

    const handleToggleCompare = async () => {
        if (!selectedCourseId) return;
        const added = !isInCompare(interactionType, selectedCourseId);
        const ok = await toggleCompare(interactionType, selectedCourseId, weeks);
        if (ok) {
            showToast(added
                ? (isArabic ? "تمت الإضافة إلى المقارنة" : "Added to compare")
                : (isArabic ? "تمت الإزالة من المقارنة" : "Removed from compare"));
        }
    };

    const inWishlist = selectedCourseId ? isInWishlist(interactionType, selectedCourseId) : false;
    const inCompare = selectedCourseId ? isInCompare(interactionType, selectedCourseId) : false;
    const selectedAccommodation = selectedAccommodationId && selectedAccommodationId !== 'no-acc'
        ? accommodations.find(a => a.id === selectedAccommodationId)
        : null;
    const selectedInsurances = insurances.filter(
        (ins) => ins.is_mandatory || selectedExtras.includes(ins.id),
    );
    const selectedSupplements = supplements.filter((supp) => selectedExtras.includes(supp.id));
    const selectedPickup = pickUps.find(p => p.id === selectedPickupId);

    const referralDiscountPercent = appliedReferral?.discount_percent
        ? Number(appliedReferral.discount_percent)
        : 0;

    const pricing = computeInstitutePricing({
        selectedCourse,
        selectedAccommodation,
        selectedPickup,
        selectedInsurances,
        selectedSupplements,
        weeks,
        startDate,
        accAge,
        currency,
        registrationFeeObj: regFeeObj,
        courseDiscountPercent,
        referralDiscountPercent,
        pioneersDiscounts,
        supplementLabels: {
            material_books: l("materialBooksFee"),
            registration: l("registrationFee"),
            mandatory: l("mandatoryFee"),
            summer: l("summerSupplement"),
            winter: l("winterSupplement"),
            other: l("otherSupplement"),
            under_18: l("under18Supplement"),
            insurance: l("step3insurance"),
            insurance_admin: l("insuranceAdminFee"),
        },
    });

    const {
        courseTotal: coursePrice,
        accPrice,
        accOriginalTotal,
        accWaived,
        oneTimeFees,
        accSupplements,
        insuranceLines,
        supplementLines,
        pickupTotal: pickupPrice,
        pickupOriginalTotal,
        pickupWaived,
        pioneersCashLines,
        pioneersCashTotal,
        courseDiscountPercent: appliedCourseDiscountPercent,
        courseDiscountAmount,
        referralDiscountPercent: appliedReferralDiscountPercent,
        referralDiscountAmount,
        coursePriceAfterReferral,
        subtotal,
        total: totalPrice,
    } = pricing;

    const toggleExtra = (id) => {
        const insurance = insurances.find((ins) => ins.id === id);
        if (insurance?.is_mandatory && selectedExtras.includes(id)) return;
        setSelectedExtras(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]);
    };

    const formatDisplayDate = (date) => {
        if (!date) return "12 مارس";
        const formatted = new Intl.DateTimeFormat(isArabic ? 'ar-EG-u-nu-latn' : 'en-GB', { weekday: 'long', month: 'long', day: 'numeric' }).format(date);
        if (isArabic) {
            return formatted.replace(/\s*,\s*/g, '، ');
        }
        return formatted;
    };

    const schoolDisplayName = isArabic
        ? `${school?.ar_name || ''} - ${school?.city_ar || ''} - ${school?.name || ''}`
        : [school?.name, school?.city].filter(Boolean).join(' - ');

    const sliderImages = [];
    if (school?.image) sliderImages.push(school.image);
    const gallery = school?.gallery || school?.gallery_urls || [];
    if (gallery.length) sliderImages.push(...gallery.filter((img) => img !== school.image));

    const getDiscountPrice = (price) => courseDiscountPercent > 0 ? price * (1 - courseDiscountPercent / 100) : null;

    const studyEndDate =
        startDate instanceof Date && !Number.isNaN(startDate.getTime())
            ? new Date(startDate.getTime() + weeks * 7 * 24 * 60 * 60 * 1000)
            : null;

    if (!school) return <div className="p-20 text-center">Loading...</div>;

    const selectIconPosition = isArabic ? "left-4" : "right-4";

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
                    <button
                        type="button"
                        onClick={handleToggleWishlist}
                        className={`flex items-center gap-2 text-sm font-semibold transition ${inWishlist ? "text-red-500" : "text-[#102233] hover:text-red-500"}`}
                    >
                        <FontAwesomeIcon icon={faHeart} className={`h-4 w-4 ${inWishlist ? "opacity-100" : "opacity-70"}`} />
                        <span>{inWishlist ? l("inFavorite") : l("addFavorite")}</span>
                    </button>
                    <button
                        type="button"
                        onClick={handleToggleCompare}
                        className={`flex items-center gap-2 text-sm font-semibold transition ${inCompare ? "text-[#0057B7]" : "text-[#102233] hover:text-[#0057B7]"}`}
                    >
                        <FontAwesomeIcon icon={faExchangeAlt} className={`h-4 w-4 ${inCompare ? "opacity-100" : "opacity-70"}`} />
                        <span>{inCompare ? l("inCompare") : l("addCompare")}</span>
                    </button>
                    {toastMsg && (
                        <div className="absolute -bottom-12 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg z-50">
                            {toastMsg}
                        </div>
                    )}
                </div>
            </div>
            <div
                className="mt-4 mb-10 flex items-center justify-start gap-3 text-sm font-medium text-slate-600"
                dir={isArabic ? "rtl" : "ltr"}
            >
                {isArabic ? (
                    <>
                        {school.flag && <img src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" />}
                        <span>{loc(school.location, (school.city_ar ? `${school.country_ar} ، ${school.city_ar}` : null))}</span>
                        <span className="inline-flex items-center gap-1.5 text-[#F59E0B]">
                            <FontAwesomeIcon icon={faStar} className="h-3.5 w-3.5" />
                            <span className="font-semibold text-slate-800">{school.rating}</span>
                        </span>
                    </>
                ) : (
                    <>
                        {school.flag && <img src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" />}
                        <span>{loc(school.location, (school.city_ar ? `${school.country_ar} ، ${school.city_ar}` : null))}</span>
                        <span className="inline-flex items-center gap-1.5 text-[#F59E0B]">
                            <span className="font-semibold text-slate-800">{school.rating}</span>
                            <FontAwesomeIcon icon={faStar} className="h-3.5 w-3.5" />
                        </span>
                    </>
                )}
            </div>

            <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div className="lg:col-span-8 space-y-8">
                    {/* Description & Gallery */}
                    <div className="rounded-[15px] bg-white p-3 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-8">
                        <div className="w-full md:w-[45%] hidden md:block">
                            <ImageSlider images={sliderImages} schoolName={loc(school.name, school.ar_name)} />
                        </div>
                        <div className="flex-1 space-y-6 py-4">
                            <p
                                className={`max-h-[160px] overflow-y-auto text-[14px] leading-relaxed text-black scrollbar-thin ${isArabic ? "pr-3 text-right" : "pl-3 text-left"}`}
                                dir={isArabic ? "rtl" : "ltr"}
                            >
                                {loc(school.description, school.ar_description)}
                            </p>
                            {accreditationLogos.length > 0 && (
                                <div className="pt-2">
                                    <div className="flex flex-wrap justify-start gap-3">
                                        {accreditationLogos.map((acc, idx) => (
                                            <div key={acc.id ?? idx} className="relative flex h-[36px] w-[80px] items-center justify-center rounded-[10px] border border-gray-200 bg-white p-1.5 shadow-sm transition hover:border-[#0057B7]">
                                                {acc.logo && <img src={acc.logo} alt={loc(acc.name, acc.ar_name)} className="h-full w-full object-contain" />}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Step 1: Choose Course */}
                    <div>
                        <div className="mb-4 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
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
                        <div className="bg-white rounded-[15px] p-6 shadow-sm border border-gray-100 space-y-4">
                            {courses.map((course) => {
                                const price = resolveWeeklyCourseFee(course, weeks, currency);
                                const discPrice = getDiscountPrice(price);
                                return (
                                    <label key={course.id} className={`relative block cursor-pointer rounded-[15px] border-2 overflow-hidden transition-all bg-white ${selectedCourseId === course.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                        {/* Checkmark */}
                                        <div className={`absolute top-3 ${selectIconPosition}`}>
                                            <img src={selectedCourseId === course.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                        </div>
                                        <div className="flex flex-col md:flex-row justify-between p-6 gap-2 relative">
                                            {/* Right side (Text info) */}
                                            <div className="flex-1">
                                                <div className="flex items-center gap-3 mb-4">
                                                    <h4 className="text-[18px] font-bold text-[#102233]">{loc(course.name, course.ar_name)}</h4>
                                                    {course.tag && <span className="rounded-md bg-[#4CAF50] px-2.5 py-0.5 text-[12px] font-medium text-white">{loc(course.tag, course.tag_ar)}</span>}
                                                </div>
                                                <div className="flex flex-wrap items-center gap-2 xl:gap-3 text-[12px] font-medium text-slate-500">
                                                    <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faBookOpen} className="text-[#0057B7] h-[14px] w-[14px]" /> {course.lessons} {l("lessonsWeek")}</span>
                                                    <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faClock} className="text-[#0057B7] h-[14px] w-[14px]" /> {course.hours} {l("hoursWeek")}</span>
                                                    <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faUser} className="text-[#0057B7] h-[14px] w-[14px]" /> +{course.min_age} {l("requiredAge")}</span>
                                                    <span className="flex items-center gap-1.5"><FontAwesomeIcon icon={faSignal} className="text-[#0057B7] h-[14px] w-[14px]" /> {loc(course.level, course.ar_level)} {l("requiredLevel")}</span>
                                                </div>
                                            </div>

                                            {/* Left side (Checkmark and Price) */}
                                            <div className="mt-4 flex flex-col items-end min-w-[150px] justify-between h-full">


                                                {/* Price */}
                                                <div className="text-left mt-auto">
                                                    <div className="flex items-baseline justify-end gap-1">
                                                        <span className="text-[18px] font-bold text-[#102233]"><Price activeCurrency={activeCurrency} value={discPrice || price} currency={currency} /></span>
                                                        <span className="text-[15px] font-bold text-[#102233] mx-1">/</span>
                                                        <span className="text-[15px] font-medium text-[#102233]">{isArabic ? "للاسبوع" : "week"}</span>
                                                    </div>
                                                    {discPrice && (
                                                        <div className="flex justify-end items-center gap-2 mt-1">
                                                            <span className="text-[14px] font-medium text-slate-400 line-through"><Price activeCurrency={activeCurrency} value={price} currency={currency} size="sm" muted /></span>
                                                            <span className="rounded-full bg-[#EF4444] px-2 py-0.5 text-[11px] font-bold text-white">
                                                                -{courseDiscountPercent}%
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
                        <div className="mb-4 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
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

                        <div className="bg-white rounded-[15px] p-6 shadow-sm border border-gray-100 space-y-4">
                            <label className={`relative block cursor-pointer rounded-[15px] border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === 'no-acc' ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                {/* Checkmark */}
                                <div className={`absolute top-3 ${selectIconPosition}`}>
                                    <img src={selectedAccommodationId === 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                </div>
                                <div className="flex flex-col md:flex-row justify-between p-6 gap-2 relative">
                                    <div className="flex-1">
                                        <h4 className="text-[18px] font-bold text-[#102233]">{l("withoutAcc")}</h4>
                                        <p className="text-[14px] text-slate-500 mt-1">{l("withoutAccSub")}</p>
                                    </div>
                                </div>
                                <input type="radio" name="accommodation" className="hidden" value="no-acc" checked={selectedAccommodationId === 'no-acc'} onChange={() => setSelectedAccommodationId('no-acc')} />
                            </label>

                            {accommodations.map((acc) => {
                                const accWeekly = getItemPrice(acc, currency, { field: "fee_per_week", pricesKey: "fee_per_week_prices" });
                                const accDiscPrice = getDiscountPrice(accWeekly);
                                const featureList = normalizeFeatureList(acc.features, isArabic, acc.features_ar);
                                return (
                                    <label key={acc.id} className={`relative block cursor-pointer rounded-[20px] border-2 overflow-hidden transition-all bg-white ${selectedAccommodationId === acc.id ? 'border-[#0057B7]' : 'border-gray-100 hover:border-gray-200'}`}>
                                        {/* Checkmark */}
                                        <div className={`absolute top-3 ${selectIconPosition}`}>
                                            <img src={selectedAccommodationId === acc.id ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'} alt="" className="h-6 w-6" />
                                        </div>
                                        <div className="flex flex-col md:flex-row justify-between p-6 gap-6 relative">
                                            {/* Right side (Text info) */}
                                            <div className="flex-1">
                                                <div className="flex items-center gap-3 mb-4">
                                                    <h4 className="text-[18px] font-bold text-[#102233]">{loc(acc.name || acc.title, acc.ar_name || acc.ar_title)}</h4>
                                                    {acc.tag && <span className="rounded-md bg-[#4CAF50] px-2.5 py-0.5 text-[12px] font-medium text-white">{loc(acc.tag, acc.tag_ar)}</span>}
                                                </div>
                                                <div className="flex flex-wrap items-center gap-2 xl:gap-3 text-[14px] font-medium text-slate-500">
                                                    {featureList.map((feature, i) => (
                                                        <span key={i} className="flex items-center gap-2">
                                                            <FontAwesomeIcon icon={i === 0 ? faBed : i === 1 ? faBath : faHouse} className="text-[#0057B7] h-[14px] w-[14px]" />
                                                            {feature}
                                                        </span>
                                                    ))}
                                                </div>
                                            </div>

                                            {/* Left side (Checkmark and Price) */}
                                            <div className="mt-4 flex flex-col items-end min-w-[150px] justify-between h-full">

                                                {/* Price */}
                                                <div className="text-left mt-auto">
                                                    <div className="flex items-baseline justify-end gap-1">
                                                        <span className="text-[18px] font-bold text-[#102233]"><Price activeCurrency={activeCurrency} value={accDiscPrice || accWeekly} currency={currency} /></span>
                                                        <span className="text-[15px] font-bold text-[#102233] mx-1">/</span>
                                                        <span className="text-[15px] font-medium text-[#102233]">{isArabic ? "للاسبوع" : "week"}</span>
                                                    </div>
                                                    {accDiscPrice && (
                                                        <div className="flex justify-end items-center gap-2 mt-1">
                                                            <span className="text-[14px] font-medium text-slate-400 line-through"><Price activeCurrency={activeCurrency} value={accWeekly} currency={currency} size="sm" muted /></span>
                                                            <span className="rounded-full bg-[#EF4444] px-2 py-0.5 text-[11px] font-bold text-white">
                                                                -{courseDiscountPercent}%
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
                    <div>
                        <div className="mb-6">
                            <h3 className="text-xl font-semibold text-slate-900">{l("extraOptions")}</h3>
                            <p className="text-sm text-slate-500 mt-1">{l("extraOptionsSub")}</p>
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
                                                <div>
                                                    <div className="text-[16px] font-bold text-[#102233]">{l("noPickup") || l("without_pickup")}</div>
                                                    <div className="text-[14px] text-slate-500 mt-0.5">{l("noPickupSub") || l("without_pickup_subtitle")}</div>
                                                </div>
                                                <div className={`flex-shrink-0 h-5 w-5 rounded-full border-2 flex items-center justify-center ${!selectedPickupId ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                                    {!selectedPickupId && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
                                                </div>
                                                <input type="radio" name="pickup" className="hidden" value="" checked={!selectedPickupId} onChange={() => setSelectedPickupId(null)} />
                                            </label>
                                            {pickUps.map(p => {
                                                const pPrice = getItemPrice(p, currency, { field: "price", pricesKey: "prices" });
                                                return (
                                                    <label key={p.id} className="flex items-center justify-between p-3 cursor-pointer rounded-lg hover:bg-gray-50 transition border-t border-gray-50">
                                                        <div>
                                                            <div className="text-[16px] font-bold text-[#102233]">{loc(p.name || p.route, p.ar_name || p.ar_route)}</div>
                                                            <div className="flex items-center gap-1 mt-0.5">
                                                                <span className="text-[15px] font-bold text-[#102233]"><Price activeCurrency={activeCurrency} value={pPrice} currency={currency} size="sm" /></span>
                                                            </div>
                                                        </div>
                                                        <div className={`flex-shrink-0 h-5 w-5 rounded-full border-2 flex items-center justify-center ${selectedPickupId === p.id ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                                            {selectedPickupId === p.id && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
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
                                const iPrice = getItemPrice(ins, currency, { field: "price", pricesKey: "prices" })
                                    || getItemPrice(ins, currency, { field: "amount", pricesKey: "prices" });
                                const isSelected = ins.is_mandatory || selectedExtras.includes(ins.id);
                                return (
                                    <label key={ins.id} className={`flex items-center justify-between p-5 rounded-xl border border-gray-200 bg-white transition ${ins.is_mandatory ? "cursor-default" : "cursor-pointer hover:border-[#0057B7]"}`}>
                                        <div>
                                            <div className="flex items-center gap-2">
                                                <div className="text-[16px] font-bold text-[#102233]">{loc(ins.name, ins.ar_name)}</div>
                                                {ins.is_mandatory && (
                                                    <span className="rounded-md bg-[#0057B7] px-2 py-0.5 text-[11px] font-medium text-white">
                                                        {l("mandatory") || "Mandatory"}
                                                    </span>
                                                )}
                                            </div>
                                            <div className="flex items-center gap-1 mt-1">
                                                <span className="text-[15px] font-bold text-[#102233]"><Price activeCurrency={activeCurrency} value={iPrice} currency={currency} size="sm" /></span>
                                                <span className="text-[13px] font-bold text-[#102233] mx-1">/</span>
                                                <span className="text-[13px] font-medium text-slate-500">{isArabic ? "للاسبوع" : "week"}</span>
                                                {ins.original_price_sar && (
                                                    <span className="text-[14px] font-medium text-slate-400 line-through mr-2">
                                                        <Price activeCurrency={activeCurrency} value={currency === 'SAR' ? ins.original_price_sar : ins.original_price_sar} currency={currency} size="sm" />
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                        <div className={`flex-shrink-0 h-5 w-5 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-[#0057B7]' : 'border-gray-300'}`}>
                                            {isSelected && <div className="h-2.5 w-2.5 rounded-full bg-[#0057B7]"></div>}
                                        </div>
                                        <input
                                            type="checkbox"
                                            className="hidden"
                                            checked={isSelected}
                                            disabled={ins.is_mandatory}
                                            onChange={() => toggleExtra(ins.id)}
                                        />
                                    </label>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* Sidebar Sticky Area */}
                <div className="lg:col-span-4 relative sticky top-4 h-max flex flex-col gap-6">
                    <div className="bg-white rounded-[15px] p-2 shadow-sm border border-gray-100 flex flex-col gap-6">
                        {/* Top Price */}
                        <div className="flex rounded-[15px] bg-[#F0F7FC] flex-col items-center justify-center py-4">
                            <div className="flex items-center gap-1 text-[30px] font-bold text-[#102233]" dir="ltr">
                                <Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="lg" />
                            </div>
                            <div className="text-[12px] text-[#475569] ">{l("totalIncludes")}</div>
                        </div>

                        {/* Study Date Box */}
                        <div className="mx-2">
                            <HeroDatePicker
                                variant="sidebar"
                                label={l("studyDate")}
                                placeholder={l("selectStart")}
                                selectedDate={startDate}
                                onSelect={(date) => setStartDate(date)}
                                fromLabel={l("from")}
                                toLabel={l("to")}
                                endDate={studyEndDate}
                                formatDisplay={formatDisplayDate}
                            />
                        </div>

                        {/* Number of weeks Box */}
                        <div className="mx-2">
                            <HeroDropdown
                                variant="sidebar"
                                label={l("numWeeks")}
                                placeholder={l("selectWeeks")}
                                scroll
                                maxVisibleItems={8}
                                options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks")}`, value: i + 1 }))}
                                onSelect={(opt) => setWeeks(opt.value)}
                                selectedValue={weeks}
                            />
                        </div>

                        {/* Discount Code */}
                        <div className=" mx-2">
                            <div className={`mb-3 text-[18px] text-[#102233] ${isArabic ? "text-right" : "text-left"}`}>{l("couponQ")}</div>
                            <div className="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-1.5 shadow-sm">
                                <input
                                    type="text"
                                    value={referralCodeInput}
                                    onChange={(event) => {
                                        setReferralCodeInput(event.target.value.toUpperCase());
                                        setReferralError("");
                                    }}
                                    placeholder={l("couponCode")}
                                    className={`w-full bg-transparent px-3 text-[16px] outline-none placeholder:text-gray-400 ${isArabic ? "text-right" : "text-left"}`}
                                    dir={isArabic ? "rtl" : "ltr"}
                                />
                                <button
                                    type="button"
                                    onClick={handleApplyReferral}
                                    disabled={referralApplying}
                                    className={`shrink-0 rounded-xl px-8 py-3 text-[16px] text-white transition ${appliedReferral ? "bg-emerald-500 hover:bg-emerald-600" : "bg-[#0284c7] hover:bg-[#0369a1]"} disabled:opacity-60`}
                                >
                                    {referralApplying ? "..." : (appliedReferral ? l("applied") : l("apply"))}
                                </button>
                            </div>
                            {referralError ? (
                                <p className={`mt-2 text-sm text-red-600 ${isArabic ? "text-right" : "text-left"}`}>{referralError}</p>
                            ) : null}
                            {appliedReferral ? (
                                <p className={`mt-2 text-xs text-emerald-600 ${isArabic ? "text-right" : "text-left"}`}>
                                    {l("referralApplied")}: {appliedReferral.referrer_name} ({appliedReferralDiscountPercent}%)
                                </p>
                            ) : null}
                        </div>

                        {/* Breakdown */}
                        <div className="space-y-4 mx-4 pt-4 text-[14px] text-[#102233]">
                            <div className="flex items-center justify-between">
                                <span className="text-right">{selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : l("step1")} ({weeks} {l("weeks")})</span>
                                <span className="flex items-center gap-2">
                                    {referralDiscountAmount > 0 && (
                                        <span className="text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" muted />
                                        </span>
                                    )}
                                    <Price activeCurrency={activeCurrency} value={referralDiscountAmount > 0 ? coursePriceAfterReferral : coursePrice} currency={currency} size="sm" />
                                </span>
                            </div>
                            {selectedAccommodation && (
                                <div className="flex items-center justify-between">
                                    <span className="text-right">{loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)} ({weeks} {l("weeks")})</span>
                                    <span className="flex items-center gap-2">
                                        {accWaived && accOriginalTotal > 0 && (
                                            <span className="text-slate-400 line-through">
                                                <Price activeCurrency={activeCurrency} value={accOriginalTotal} currency={currency} size="sm" muted />
                                            </span>
                                        )}
                                        <Price activeCurrency={activeCurrency} value={accPrice} currency={currency} size="sm" />
                                    </span>
                                </div>
                            )}
                            {oneTimeFees.map((fee) => (
                                <div key={fee.key} className="flex items-center justify-between">
                                    <span className="text-right">{fee.label}{fee.waived ? ` (${l("freeWithPioneers")})` : ""}</span>
                                    <span className="flex items-center gap-2">
                                        {fee.waived && fee.originalTotal > 0 && (
                                            <span className="text-slate-400 line-through">
                                                <Price activeCurrency={activeCurrency} value={fee.originalTotal} currency={currency} size="sm" muted />
                                            </span>
                                        )}
                                        <Price activeCurrency={activeCurrency} value={fee.total} currency={currency} size="sm" />
                                    </span>
                                </div>
                            ))}
                            {accSupplements.map((supp) => (
                                <div key={supp.key} className="flex items-center justify-between">
                                    <span className="text-right">{supp.label}{supp.perWeek ? ` (${supp.weeks} ${l("weeks")})` : ""}</span>
                                    <span><Price activeCurrency={activeCurrency} value={supp.total} currency={currency} size="sm" /></span>
                                </div>
                            ))}
                            {(pickupWaived ? pickupOriginalTotal > 0 : pickupPrice > 0) && selectedPickup && (
                                <div className="flex items-center justify-between">
                                    <span className="text-right">
                                        {loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)}
                                        {pickupWaived ? ` (${l("freeWithPioneers")})` : ""}
                                    </span>
                                    <span className="flex items-center gap-2">
                                        {pickupWaived && pickupOriginalTotal > 0 && (
                                            <span className="text-slate-400 line-through">
                                                <Price activeCurrency={activeCurrency} value={pickupOriginalTotal} currency={currency} size="sm" muted />
                                            </span>
                                        )}
                                        <Price activeCurrency={activeCurrency} value={pickupPrice} currency={currency} size="sm" />
                                    </span>
                                </div>
                            )}
                            {insuranceLines.map((line) => (
                                <div key={line.key} className="flex items-center justify-between">
                                    <span className="text-right">
                                        {line.label}{line.perWeek ? ` (${line.weeks} ${l("weeks")})` : ""}{line.waived ? ` (${l("freeWithPioneers")})` : ""}
                                    </span>
                                    <span className="flex items-center gap-2">
                                        {line.waived && line.originalTotal > 0 && (
                                            <span className="text-slate-400 line-through">
                                                <Price activeCurrency={activeCurrency} value={line.originalTotal} currency={currency} size="sm" muted />
                                            </span>
                                        )}
                                        <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                                    </span>
                                </div>
                            ))}
                            {supplementLines.map((line) => (
                                <div key={line.key} className="flex items-center justify-between">
                                    <span className="text-right">{loc(line.label, line.ar_label)}</span>
                                    <span><Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" /></span>
                                </div>
                            ))}
                            {courseDiscountAmount > 0 && (
                                <div className="flex items-center justify-between text-[#10B981]">
                                    <span>{l("courseDiscount")} ({appliedCourseDiscountPercent}%)</span>
                                    <span>-<Price activeCurrency={activeCurrency} value={courseDiscountAmount} currency={currency} size="sm" /></span>
                                </div>
                            )}
                            {referralDiscountAmount > 0 && (
                                <div className="flex items-center justify-between text-[#10B981]">
                                    <span>
                                        {l("referralDiscount")} ({appliedReferral?.referrer_name}) ({appliedReferralDiscountPercent}%)
                                    </span>
                                    <span>-<Price activeCurrency={activeCurrency} value={referralDiscountAmount} currency={currency} size="sm" /></span>
                                </div>
                            )}
                            {pioneersCashLines.map((line) => (
                                <div key={line.key} className="flex items-center justify-between text-[#10B981]">
                                    <span>{loc(line.label, line.ar_label)}{line.multiplier > 1 ? ` (×${line.multiplier})` : ""}</span>
                                    <span>-<Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" /></span>
                                </div>
                            ))}
                        </div>

                        {/* Bottom Total & Button */}
                        <div className="py-4 border-t border-gray-200 flex items-center justify-between px-4">
                            <div className="flex flex-col">
                                <div className="flex items-center gap-2">
                                    <span className="text-[18px] font-bold text-[#0284c7]"><Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="md" /></span>
                                    {courseDiscountAmount + referralDiscountAmount + pioneersCashTotal > 0 && (
                                        <span className="text-[18px] font-medium text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={subtotal} currency={currency} size="md" />
                                        </span>
                                    )}
                                </div>
                                <div className="text-[12px] font-medium text-[#102233] mt-1">{l("totalIncludes")}</div>
                            </div>
                            <button
                                onClick={() => {
                                    router.push(buildInstituteBookingUrl(slug, {
                                        courseId: selectedCourseId,
                                        weeks,
                                        accommodationId: selectedAccommodationId,
                                        pickupId: selectedPickupId,
                                        startDate: formatInstituteQueryDate(startDate),
                                        extras: selectedExtras,
                                        accAge,
                                    }));
                                }}
                                className="p-4 flex items-center gap-2  rounded-xl bg-[#0284c7] text-white text-[14px] hover:bg-[#0369a1] transition"
                            >
                                <span>{l("booking.reviewConfirm") || l("reviewRequest")}</span>
                                <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-5 w-5 shrink-0" />
                            </button>
                        </div>
                    </div>

                    {/* WhatsApp Box */}
                    <div className="rounded-2xl bg-[#E8F5E9] px-5 py-6 md:py-8">
                        <h4 className={`mb-6 text-[16px] font-semibold leading-normal text-[#001432] ${isArabic ? "text-right" : "text-left"}`}>
                            {l("haveQuestion")}
                        </h4>
                        <p className={`mb-6 text-[15px] font-normal leading-[1.65] text-[#001432] ${isArabic ? "text-right" : "text-left"}`}>
                            {l("haveQuestionDesc")}
                        </p>
                        <Link
                            href="https://wa.me/966550027268"
                            target="_blank"
                            rel="noopener noreferrer"
                            dir={isArabic ? "rtl" : "ltr"}
                            className="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-[14px] font-semibold leading-none text-[#25D376] transition hover:bg-white/90 hover:text-[#25D366]"
                        >
                            <img
                                src="/assets/icons/whatsapp-circle.svg"
                                alt=""
                                width={20}
                                height={20}
                                className="h-7 w-7 shrink-0"
                            />
                            <span>{l("contactUsWhatsapp")}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <PioneersDiscountModal
                open={Boolean(pioneersPopup)}
                onClose={() => setPioneersPopup(null)}
                qualifying={pioneersPopup?.qualifying || []}
                weeks={pioneersPopup?.weeks || weeks}
                currency={currency}
                activeCurrency={activeCurrency}
            />

            <ReferralDiscountModal
                open={Boolean(referralPopup)}
                onClose={() => setReferralPopup(null)}
                referrerName={referralPopup?.referrer_name}
                discountPercent={referralPopup?.discount_percent}
                discountAmount={referralDiscountAmount}
                currency={currency}
                activeCurrency={activeCurrency}
            />
        </div>
    );
}
