"use client";

import { useState, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams, useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faStar, faSignal, faUser, faClock, faBookOpen, faCheckCircle,
    faShieldAlt, faPlusCircle, faHeart, faShare, faExchangeAlt,
    faBed, faBath, faHouse
} from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import MobileInstituteDetailsAbout from "@/components/mobile/mobile-institute-details-about";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { useApi } from "@/lib/api";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { buildInstituteBookingUrl, formatInstituteQueryDate } from "@/lib/institute-booking-url";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import { applyReferralCode, getStoredReferral } from "@/lib/referral";
import { getStoredAuthUser } from "@/lib/auth";
import {
    computeInstitutePricing,
    getItemPrice,
    resolveWeeklyCourseFee,
} from "@/lib/institute-pricing";
import { resolveCoursePromotionPercent } from "@/lib/pioneers-discount";
import { CurrencyIcon, formatPriceAmount } from "@/lib/format-currency";

function Price({
    value,
    currency,
    activeCurrency,
    className = "",
    size = "md",
    muted = false,
    isNegative = false,
    signPosition = "start",
    accent = null,
}) {
    const { currencies } = useCurrency();
    const resolvedActiveCurrency = activeCurrency ?? currencies.find((entry) => entry.code === currency);

    if (value === null || value === undefined) return null;
    const iconClassName = size === "lg" ? "h-6 w-6" : size === "sm" ? "h-3.5 w-3.5" : "h-[18px] w-[18px]";
    const textClass = size === "lg" ? "text-[24px] font-bold" : size === "sm" ? "text-[14px]" : "text-[14px] font-medium";
    const accentClass = accent === "green" ? "text-[#10B981]" : isNegative ? "text-green-500" : "";

    return (
        <CurrencyAmount
            currency={currency}
            amount={Math.abs(value)}
            activeCurrency={resolvedActiveCurrency}
            sign={isNegative ? "-" : ""}
            signPosition={signPosition}
            iconAccent={accent === "green" ? "green" : null}
            className={`inline-flex items-center ${textClass} ${accentClass} ${className}`}
            iconClassName={iconClassName}
            muted={muted}
        />
    );
}

function FooterPriceDisplay({ value, currency, activeCurrency, variant = "main" }) {
    const isCompare = variant === "compare";
    const formatted = formatPriceAmount(value);
    const iconClass = "h-[18px] w-[18px]";

    if (isCompare) {
        return (
            <span className="relative inline-flex items-center gap-0.5 text-[20px] font-medium leading-none tabular-nums text-[#94A3B8] after:pointer-events-none after:absolute after:inset-x-0 after:top-1/2 after:h-px after:-translate-y-1/2 after:bg-[#94A3B8] after:content-['']">
                <CurrencyIcon
                    currency={currency}
                    activeCurrency={activeCurrency}
                    variant="dark"
                    className={`${iconClass} shrink-0 opacity-60`}
                />
                <span>{formatted}</span>
            </span>
        );
    }

    return (
        <CurrencyAmount
            currency={currency}
            amount={value}
            activeCurrency={activeCurrency}
            currencyAfter={false}
            iconClassName={iconClass}
            className="inline-flex items-center gap-0.5 text-[20px] font-bold leading-none tabular-nums text-[#102233]"
        />
    );
}

function SummaryRow({ label, children, className = "", isRtl }) {
    const labelEl = (
        <span className={`flex-1 font-normal text-[#102233] ${isRtl ? "text-right" : "text-left"}`}>
            {label}
        </span>
    );
    const priceEl = (
        <span className={`flex shrink-0 items-center gap-2 tabular-nums font-medium ${className}`}>
            {children}
        </span>
    );

    return (
        <div className="flex items-center justify-between gap-6" dir="ltr">
            {isRtl ? (
                <>
                    {priceEl}
                    {labelEl}
                </>
            ) : (
                <>
                    {labelEl}
                    {priceEl}
                </>
            )}
        </div>
    );
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

function RadioCircle({ selected, className = "" }) {
    return (
        <div
            className={`flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 ${selected ? "border-[#0B5DB6]" : "border-[#CBD5E1]"} ${className}`}
        >
            {selected && <div className="h-2.5 w-2.5 rounded-full bg-[#0B5DB6]" />}
        </div>
    );
}

export default function MobileInstituteDetails({ slug: slugProp }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const { currency, activeCurrency } = useCurrency();

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
    const [appliedReferral, setAppliedReferral] = useState(null);
    const [referralCodeInput, setReferralCodeInput] = useState("");
    const [referralApplying, setReferralApplying] = useState(false);
    const [referralError, setReferralError] = useState("");
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
    const discounts = instituteData?.discounts || [];
    const pioneersDiscounts = instituteData?.pioneers_discounts || [];

    const selectedCourse = courses.find((c) => c.id === selectedCourseId);
    const selectedAccommodation = selectedAccommodationId && selectedAccommodationId !== "no-acc"
        ? accommodations.find((a) => a.id === selectedAccommodationId)
        : null;
    const selectedInsurances = insurances.filter(
        (ins) => ins.is_mandatory || selectedExtras.includes(ins.id),
    );
    const selectedSupplements = supplements.filter((supp) => selectedExtras.includes(supp.id));
    const selectedPickup = pickUps.find((p) => p.id === selectedPickupId);

    const courseDiscountPercent = resolveCoursePromotionPercent(selectedCourseId, discounts, selectedCourse);
    const referralDiscountPercent = appliedReferral?.discount_percent
        ? Number(appliedReferral.discount_percent)
        : 0;

    const l = (key) => t(`pages.institute_details.${key}`);
    const loc = (en, ar) => (isArabic && ar) ? ar : en;

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
        weeklyCourseFee,
        weeklyAccFee,
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

    const getCourseDiscountPrice = (price) =>
        appliedCourseDiscountPercent > 0 ? price * (1 - appliedCourseDiscountPercent / 100) : null;

    const mobileSection = t("pages.institute_details.mobile", {});
    const chooseCourseTitle =
        mobileSection?.chooseCourseTitle || (isArabic ? "اختر الدورة" : "Choose course");
    const accommodationTitle =
        mobileSection?.accommodationTitle || (isArabic ? "السكن" : "Accommodation");
    const extrasTitle =
        mobileSection?.extrasTitle || l("extraOptions");
    const priceSummaryTitle =
        t("pages.institute_details.booking.priceSummary") || (isArabic ? "ملخص الرسوم" : "Price summary");
    const totalDiscountAmount = courseDiscountAmount + referralDiscountAmount + pioneersCashTotal;

    const pickupHeaderTitle = selectedPickup
        ? loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)
        : l("step3pickups");
    const pickupHeaderPrice = selectedPickup
        ? getItemPrice(selectedPickup, currency, { field: "price", pricesKey: "prices" })
        : null;

    const handlePickupHeaderToggle = () => {
        if (selectedPickupId != null) {
            setSelectedPickupId(null);
        }
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
            showToast(isArabic ? "تم تطبيق كود الإحالة" : "Referral code applied.");
        } catch (err) {
            setReferralError(err.message || (isArabic ? "كود إحالة غير صالح" : "Invalid referral code."));
        } finally {
            setReferralApplying(false);
        }
    };

    const toggleExtra = (id) => {
        const insurance = insurances.find((ins) => ins.id === id);
        if (insurance?.is_mandatory && selectedExtras.includes(id)) return;
        setSelectedExtras((prev) =>
            prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id],
        );
    };

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

    const branchDescription = loc(school.description, school.ar_description);
    const accreditationLogos = school?.accreditations || [];

    return (
        <div className="pb-32 bg-white" dir={isArabic ? "rtl" : "ltr"}>
            {toastMsg && (
                <div className="fixed top-20 left-1/2 z-50 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg">
                    {toastMsg}
                </div>
            )}
            
            {/* Mobile Hero */}
            <div className="relative mb-6">
                <div className="relative aspect-[4/3] w-full bg-slate-200">
                    {school.image && <img src={school.image} alt={loc(school.name, school.ar_name)} className="w-full h-full object-cover" />}
                    
                    {/* Top Actions */}
                    <div className="absolute top-0 left-0 w-full p-4 flex items-center justify-between z-10 pt-12" dir="ltr">
                        <div className="flex items-center gap-3">
                            <button onClick={handleShare} className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                                <FontAwesomeIcon icon={faShare} />
                            </button>
                            <button
                                type="button"
                                onClick={handleToggleWishlist}
                                className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition hover:bg-white ${inWishlist ? "text-red-500" : "text-slate-700 hover:text-red-500"}`}
                            >
                                <FontAwesomeIcon icon={faHeart} />
                            </button>
                            <button
                                type="button"
                                onClick={handleToggleCompare}
                                className={`h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition hover:bg-white ${inCompare ? "text-[#0057B7]" : "text-slate-700 hover:text-[#0057B7]"}`}
                            >
                                <FontAwesomeIcon icon={faExchangeAlt} />
                            </button>
                        </div>
                        <Link href="/language-institutes" className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                            <img src="/assets/icons/arrow-left.svg" width={20} height={20} alt="Back" className={`transform ${isArabic ? '' : 'rotate-180'}`} />
                        </Link>
                    </div>

                    {/* Logo Overlay */}
                    <div className="absolute bottom-2 left-1/2 -translate-x-1/2 z-20">
                        <div className="p-2 h-25 w-25 rounded-full border-[4px] border-white bg-white overflow-hidden flex items-center justify-center">
                            {school.logo ? (
                                <img src={school.logo} alt="Logo" className="w-full h-full object-contain" />
                            ) : (
                                <span className="text-xl font-medium text-slate-300">LOGO</span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Mobile Header Info */}
                <div className="relative z-10 -mt-14 rounded-t-[30px] bg-white pt-14 px-4 text-center pb-6 ">
                    <h1 className="text-2xl font-semibold text-slate-900 leading-tight mb-4">
                        {isArabic
                            ? `${school.ar_name || school.name} - ${school.city_ar || school.city} - ${school.name}`
                            : `${school.name} - ${school.city}`
                        }
                    </h1>

                    <div className="flex items-center justify-center gap-3 flex-wrap">
                        <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                            {school.flag && <img src={school.flag} width={20} height={14} alt="Flag" className="rounded-sm shrink-0" />}
                            <span className="font-medium text-slate-900 line-clamp-1 text-sm max-w-[150px]">{loc(school.location, (school.city_ar ? `${school.country_ar}, ${school.city_ar}` : null))}</span>
                            
                        </div>
                        <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                            <FontAwesomeIcon icon={faStar} className="text-[#F59E0B] w-4 h-4 shrink-0" />
                            <span className="font-medium text-slate-900 shrink-0">{school.rating}</span>
                           
                        </div>
                    </div>
                </div>
            </div>

            <div className="px-4 space-y-8">
                <MobileInstituteDetailsAbout
                    description={branchDescription}
                    accreditations={accreditationLogos}
                    isArabic={isArabic}
                    aboutLabel={l("aboutInstitute")}
                    accreditedByLabel={l("accreditedBy")}
                />

                {/* Choose Course */}
                <div>
                    <div
                        className="mb-4 flex items-stretch gap-2"
                        dir={isArabic ? "rtl" : "ltr"}
                    >
                        <h3 className="flex shrink-0 items-center text-base font-bold text-slate-900">
                            {chooseCourseTitle}
                        </h3>
                        <div className="flex min-w-0 flex-1 gap-2">
                            <div className="min-w-0 flex-1 rounded-md border border-[#E1E8F0] px-3 py-2">
                                <HeroDatePicker
                                    label={l("startDate")}
                                    placeholder={l("selectStart")}
                                    selectedDate={startDate}
                                    onSelect={(date) => setStartDate(date)}
                                    variant="borderless"
                                />
                            </div>
                            <div className="min-w-0 flex-1 rounded-md border border-[#E1E8F0] px-3 py-2">
                                <HeroDropdown
                                    label={l("numWeeks")}
                                    placeholder={l("selectWeeks")}
                                    scroll
                                    options={Array.from({ length: 52 }, (_, i) => ({
                                        label: `${i + 1} ${l("weeks")}`,
                                        value: i + 1,
                                    }))}
                                    onSelect={(opt) => setWeeks(opt.value)}
                                    selectedValue={weeks}
                                    variant="borderless"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="-mx-4 overflow-x-auto px-4 pb-4 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                        <div className="flex gap-3 snap-x snap-mandatory">
                            {courses.map((course) => {
                                const price = resolveWeeklyCourseFee(course, weeks, currency);
                                const discPrice = getCourseDiscountPrice(price);
                                const isSelected = selectedCourseId === course.id;
                                return (
                                    <label
                                        key={`mobile-${course.id}`}
                                        className={`w-[310px] max-w-[88vw] shrink-0 snap-start cursor-pointer rounded-2xl border-2 bg-white p-4 shadow-sm transition ${isSelected ? "border-[#0B5DB6] ring-1 ring-[#0B5DB6]/20" : "border-[#DCE6F1]"}`}
                                    >
                                        <div className={`mb-3 flex items-start justify-between gap-2 ${isArabic ? "flex-row-reverse" : ""}`}>
                                            <img
                                                src={isSelected ? "/assets/icons/selected-blue.svg" : "/assets/icons/selected-null.svg"}
                                                alt=""
                                                className="mt-0.5 h-7 w-7"
                                            />
                                            <div className={`flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                <h4 className="line-clamp-2 text-lg font-semibold text-slate-900">
                                                    {loc(course.name, course.ar_name)}
                                                </h4>
                                                {course.tag && (
                                                    <span className="mt-2 inline-flex rounded-md bg-[#22C55E] px-2.5 py-1 text-xs font-medium text-white">
                                                        {loc(course.tag, course.tag_ar)}
                                                    </span>
                                                )}
                                            </div>
                                        </div>

                                        <div className="mb-3 border-t border-[#DCE6F1]" />

                                        <div className="space-y-3">
                                            <div dir="ltr" className={`flex items-center gap-1.5 text-sm font-medium text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                {isArabic ? (
                                                    <><span className="text-slate-400">{l("hoursWeek")}</span><span className="text-slate-900">{course.hours || "-"}</span><FontAwesomeIcon icon={faClock} className="text-[#0B5DB6]" /></>
                                                ) : (
                                                    <><FontAwesomeIcon icon={faClock} className="text-[#0B5DB6]" /><span className="text-slate-900">{course.hours || "-"}</span><span className="text-slate-400">{l("hoursWeek")}</span></>
                                                )}
                                            </div>
                                            <div dir="ltr" className={`flex items-center gap-1.5 text-sm font-medium text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                {isArabic ? (
                                                    <><span className="text-slate-400">{l("requiredLevel")}</span><span className="text-slate-900">{course.level || "-"}</span><FontAwesomeIcon icon={faSignal} className="text-[#0B5DB6]" /></>
                                                ) : (
                                                    <><FontAwesomeIcon icon={faSignal} className="text-[#0B5DB6]" /><span className="text-slate-900">{course.level || "-"}</span><span className="text-slate-400">{l("requiredLevel")}</span></>
                                                )}
                                            </div>
                                            <div dir="ltr" className={`flex items-center gap-1.5 text-sm font-medium text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                {isArabic ? (
                                                    <><span className="text-slate-400">{l("lessonsWeek")}</span><span className="text-slate-900">{course.lessons || "-"}</span><FontAwesomeIcon icon={faBookOpen} className="text-[#0B5DB6]" /></>
                                                ) : (
                                                    <><FontAwesomeIcon icon={faBookOpen} className="text-[#0B5DB6]" /><span className="text-slate-900">{course.lessons || "-"}</span><span className="text-slate-400">{l("lessonsWeek")}</span></>
                                                )}
                                            </div>
                                            <div dir="ltr" className={`flex items-center gap-1.5 text-sm font-medium text-slate-500 ${isArabic ? "justify-end" : ""}`}>
                                                {isArabic ? (
                                                    <><span className="text-slate-400">{l("requiredAge")}</span><span className="text-slate-900">+{course.min_age || "-"}</span><FontAwesomeIcon icon={faUser} className="text-[#0B5DB6]" /></>
                                                ) : (
                                                    <><FontAwesomeIcon icon={faUser} className="text-[#0B5DB6]" /><span className="text-slate-900">+{course.min_age || "-"}</span><span className="text-slate-400">{l("requiredAge")}</span></>
                                                )}
                                            </div>
                                        </div>

                                        <div className="mt-4 border-t border-[#DCE6F1] pt-4">
                                            <div className="flex justify-between items-center" dir="ltr">
                                                {discPrice && (
                                                    <div className="flex flex-col items-start gap-1">
                                                        <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-medium text-white">
                                                            -{appliedCourseDiscountPercent}%
                                                        </span>
                                                        <span className="text-sm font-medium text-slate-400 line-through">
                                                            <Price value={price} currency={currency} activeCurrency={activeCurrency} size="sm" />
                                                        </span>
                                                    </div>
                                                )}
                                                <div className="flex items-end gap-1 flex-1 justify-end">
                                                    <span className="text-xl font-bold text-slate-900">
                                                        <Price value={discPrice || price} currency={currency} activeCurrency={activeCurrency} size="lg" />
                                                    </span>
                                                    <span className="pb-1 text-sm font-medium text-slate-500">{l("perWeek")}</span>
                                                </div>
                                            </div>
                                            <div className="mt-4">
                                                <span className={`flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition ${isSelected ? "bg-[#DCE6F1] text-[#0B5DB6]" : "bg-[#0B5DB6] text-white"}`}>
                                                    {isSelected ? l("selected") : l("step1")}
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

                {/* Accommodation */}
                <div>
                    <h3 className="mb-4 text-start text-base font-bold text-slate-900">
                        {accommodationTitle}
                    </h3>

                    <div className="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            onClick={() => setSelectedAccommodationId('no-acc')}
                            className={`relative rounded-2xl border bg-white px-4 py-5 transition shadow-sm ${selectedAccommodationId === 'no-acc' ? 'border-[#0B5DB6] ring-1 ring-[#0B5DB6]/20' : 'border-[#DCE6F1]'}`}
                        >
                            <img
                                src={selectedAccommodationId === 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                alt=""
                                className={`absolute top-4 h-6 w-6 ${isArabic ? "right-4" : "left-4"}`}
                            />
                            <span className={`block text-sm font-semibold text-slate-900 mt-6 ${isArabic ? "text-right" : "text-left"}`}>{l("withoutAcc")}</span>
                        </button>
                        <button
                            type="button"
                            onClick={() => {
                                if (accommodations.length > 0) {
                                    setSelectedAccommodationId(accommodations[0].id);
                                }
                            }}
                            className={`relative rounded-2xl border bg-white px-4 py-5 transition shadow-sm ${selectedAccommodationId !== 'no-acc' ? 'border-[#0B5DB6] ring-1 ring-[#0B5DB6]/20' : 'border-[#DCE6F1]'}`}
                        >
                            <img
                                src={selectedAccommodationId !== 'no-acc' ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                alt=""
                                className={`absolute top-4 h-6 w-6 ${isArabic ? "right-4" : "left-4"}`}
                            />
                            <span className={`block text-sm font-semibold text-slate-900 mt-6 ${isArabic ? "text-right" : "text-left"}`}>{l("withAcc")}</span>
                        </button>
                    </div>

                    <div className="mt-4 grid grid-cols-2 gap-2">
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
                        <HeroDropdown
                            label={l("numWeeks")}
                            placeholder={l("selectWeeks")}
                            scroll
                            options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1}`, value: i + 1 }))}
                            onSelect={(opt) => setWeeks(opt.value)}
                            selectedValue={weeks}
                        />
                    </div>

                    {accommodations.length > 0 && (
                        <div className="mt-4 -mx-4 overflow-x-auto px-4 pb-4 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                            <div className="flex snap-x snap-mandatory gap-3">
                                {accommodations.map((acc) => {
                                    const accWeekly = getItemPrice(acc, currency, {
                                        field: "fee_per_week",
                                        pricesKey: "fee_per_week_prices",
                                    });
                                    const featureList = (Array.isArray(acc.features) ? acc.features : (typeof acc.features === 'string' ? acc.features.split(',') : []))
                                        .filter(Boolean)
                                        .map((feature) => localizeFeature(feature, isArabic));
                                    const isSelected = selectedAccommodationId === acc.id;

                                    return (
                                        <label
                                            key={acc.id}
                                            className={`w-[310px] max-w-[88vw] shrink-0 snap-start cursor-pointer rounded-2xl border-2 bg-white p-4 shadow-sm transition ${isSelected ? 'border-[#0B5DB6] ring-1 ring-[#0B5DB6]/20' : 'border-[#DCE6F1]'}`}
                                        >
                                            <div className="flex items-start gap-3">
                                                <img
                                                    src={isSelected ? '/assets/icons/selected-blue.svg' : '/assets/icons/selected-null.svg'}
                                                    alt=""
                                                    className="mt-0.5 h-7 w-7 shrink-0"
                                                />
                                                <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                    <h4 className="line-clamp-1 text-lg font-semibold leading-tight text-slate-900">{loc(acc.title, acc.ar_title)}</h4>
                                                    <div className="mt-2 flex flex-wrap gap-2">
                                                        {acc.tag && (
                                                            <span className="inline-flex rounded-md bg-[#22C55E] px-2.5 py-1 text-xs font-medium text-white">
                                                                {loc(acc.tag, acc.tag_ar)}
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="mt-4 border-t border-[#DCE6F1]" />

                                            <div className="mt-4 space-y-3">
                                                {featureList.slice(0, 3).map((feature, idx) => (
                                                    <div key={`feature-row-${idx}`} className={`flex items-center gap-2 text-sm font-medium text-slate-500`}>
                                                        {isArabic ? (
                                                            <><FontAwesomeIcon icon={featureIcon(feature, idx)} className="text-[#0B5DB6] w-4" /><span>{feature}</span></>
                                                        ) : (
                                                            <><span>{feature}</span><FontAwesomeIcon icon={featureIcon(feature, idx)} className="text-[#0B5DB6] w-4" /></>
                                                        )}
                                                    </div>
                                                ))}
                                            </div>

                                            <div className="mt-4 border-t border-[#DCE6F1]" />

                                            <div className="mt-4 flex justify-between items-center" dir="ltr">
                                                <div className="flex items-end gap-1 flex-1 justify-end">
                                                    <span className="text-xl font-bold text-slate-900">
                                                        <Price value={accWeekly} currency={currency} activeCurrency={activeCurrency} size="lg" />
                                                    </span>
                                                    <span className="pb-1 text-sm font-medium text-slate-500">{l("perWeek")}</span>
                                                </div>
                                            </div>

                                            <div className="mt-4">
                                                <span className={`flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition ${isSelected ? "bg-[#DCE6F1] text-[#0B5DB6]" : "bg-[#0B5DB6] text-white"}`}>
                                                    {isSelected ? l("selected") : l("step2")}
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

                {/* Additional options — Figma unified section */}
                {(pickUps.length > 0 || insurances.length > 0 || supplements.length > 0) && (
                    <div>
                        <h3 className="mb-4 text-start text-base font-bold text-[#102233]">
                            {extrasTitle}
                        </h3>

                        <div className="space-y-3">
                            {pickUps.length > 0 && (
                                <div className="space-y-3">
                                    <div
                                        className={`rounded-xl border bg-white p-4 ${selectedPickupId ? "border-[#0B5DB6]" : "border-[#E1E8F0]"}`}
                                    >
                                        <div className="flex items-center gap-3" dir="ltr">
                                            <button
                                                type="button"
                                                onClick={handlePickupHeaderToggle}
                                                className="shrink-0"
                                                aria-label={l("noPickup")}
                                            >
                                                {selectedPickupId ? (
                                                    <img
                                                        src="/assets/icons/selected-blue.svg"
                                                        alt=""
                                                        className="h-7 w-7"
                                                    />
                                                ) : (
                                                    <RadioCircle selected={false} />
                                                )}
                                            </button>
                                            <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                <div className="text-[16px] font-bold text-[#102233]">
                                                    {pickupHeaderTitle}
                                                </div>
                                                {pickupHeaderPrice != null && (
                                                    <div className="mt-0.5 text-[15px] font-bold text-[#102233]" dir="ltr">
                                                        <Price value={pickupHeaderPrice} currency={currency} activeCurrency={activeCurrency} size="sm" />
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="-mx-4 overflow-x-auto px-4 pb-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                                        <div className="flex snap-x snap-mandatory gap-3">
                                            {pickUps.map((pickup) => {
                                                const pPrice = getItemPrice(pickup, currency, { field: "price", pricesKey: "prices" });
                                                const isSelected = selectedPickupId === pickup.id;

                                                return (
                                                    <label
                                                        key={pickup.id}
                                                        className={`w-[240px] max-w-[72vw] shrink-0 snap-start cursor-pointer rounded-xl border bg-white p-3 transition ${isSelected ? "border-[#0B5DB6]" : "border-[#E1E8F0]"}`}
                                                        dir="ltr"
                                                    >
                                                        <div className="flex items-start gap-2">
                                                            <RadioCircle selected={isSelected} />
                                                            <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                                                <div className="line-clamp-2 text-[14px] font-bold leading-snug text-[#102233]">
                                                                    {loc(pickup.name || pickup.route, pickup.ar_name || pickup.ar_route)}
                                                                </div>
                                                                <div className="mt-1 text-[14px] font-bold text-[#102233]" dir="ltr">
                                                                    <Price value={pPrice} currency={currency} activeCurrency={activeCurrency} size="sm" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input
                                                            type="radio"
                                                            name="pickup-mobile"
                                                            className="hidden"
                                                            value={pickup.id}
                                                            checked={isSelected}
                                                            onChange={() => setSelectedPickupId(pickup.id)}
                                                        />
                                                    </label>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {insurances.map((ins) => {
                                const iPrice = getItemPrice(ins, currency, { field: "price", pricesKey: "prices" })
                                    || getItemPrice(ins, currency, { field: "amount", pricesKey: "prices" });
                                const isSelected = ins.is_mandatory || selectedExtras.includes(ins.id);

                                return (
                                    <label
                                        key={`ins-${ins.id}`}
                                        className={`flex items-center gap-3 rounded-xl border bg-white p-4 transition ${isSelected ? "border-[#0B5DB6]" : "border-[#E1E8F0]"} ${ins.is_mandatory ? "cursor-default" : "cursor-pointer"}`}
                                        dir="ltr"
                                    >
                                        <RadioCircle selected={isSelected} />
                                        <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                            <div className="flex flex-wrap items-center gap-2">
                                                <span className="text-[16px] font-bold text-[#102233]">
                                                    {loc(ins.name, ins.ar_name)}
                                                </span>
                                                {ins.is_mandatory && (
                                                    <span className="rounded-md bg-[#0B5DB6] px-2 py-0.5 text-[11px] font-medium text-white">
                                                        {l("mandatory")}
                                                    </span>
                                                )}
                                            </div>
                                            <div className="mt-0.5 text-[15px] font-bold text-[#102233]" dir="ltr">
                                                <Price value={iPrice} currency={currency} activeCurrency={activeCurrency} size="sm" />
                                            </div>
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

                            {supplements.map((supp) => {
                                const sPrice = getItemPrice(supp, currency, { field: "price", pricesKey: "prices" })
                                    || getItemPrice(supp, currency, { field: "amount", pricesKey: "prices" });
                                const isSelected = selectedExtras.includes(supp.id);

                                return (
                                    <label
                                        key={`supp-${supp.id}`}
                                        className={`flex cursor-pointer items-center gap-3 rounded-xl border bg-white p-4 transition ${isSelected ? "border-[#0B5DB6]" : "border-[#E1E8F0]"}`}
                                        dir="ltr"
                                    >
                                        <RadioCircle selected={isSelected} />
                                        <div className={`min-w-0 flex-1 ${isArabic ? "text-right" : "text-left"}`}>
                                            <span className="text-[16px] font-bold text-[#102233]">
                                                {loc(supp.name, supp.ar_name)}
                                            </span>
                                            <div className="mt-0.5 text-[15px] font-bold text-[#102233]" dir="ltr">
                                                <Price value={sPrice} currency={currency} activeCurrency={activeCurrency} size="sm" />
                                            </div>
                                        </div>
                                        <input
                                            type="checkbox"
                                            className="hidden"
                                            checked={isSelected}
                                            onChange={() => toggleExtra(supp.id)}
                                        />
                                    </label>
                                );
                            })}
                        </div>
                    </div>
                )}

                <div className="mt-2">
                    <div className={`mb-3 text-base font-bold text-[#102233] ${isArabic ? "text-right" : "text-left"}`}>
                        {l("couponQ")}
                    </div>
                    <div className="flex items-center rounded-2xl border border-[#E1E8F0] bg-white p-1.5" dir="ltr">
                        <button
                            type="button"
                            onClick={handleApplyReferral}
                            disabled={referralApplying}
                            className="shrink-0 rounded-xl bg-[#E8ECF1] px-5 py-2.5 text-[15px] font-medium text-[#475569] transition disabled:opacity-60"
                        >
                            {referralApplying ? "..." : l("apply")}
                        </button>
                        <input
                            type="text"
                            value={referralCodeInput}
                            onChange={(event) => {
                                setReferralCodeInput(event.target.value.toUpperCase());
                                setReferralError("");
                            }}
                            placeholder={l("couponCode")}
                            className={`w-full bg-transparent px-3 py-2.5 text-[15px] outline-none placeholder:text-slate-400 ${isArabic ? "text-right" : "text-left"}`}
                            dir={isArabic ? "rtl" : "ltr"}
                        />
                    </div>
                    {referralError ? (
                        <p className={`mt-2 text-sm text-red-600 ${isArabic ? "text-right" : "text-left"}`}>{referralError}</p>
                    ) : null}
                    {appliedReferral ? (
                        <p className={`mt-2 text-xs text-emerald-600 ${isArabic ? "text-right" : "text-left"}`}>
                            {l("referralApplied")}: {appliedReferral.referrer_name} ({appliedReferral.discount_percent}%)
                        </p>
                    ) : null}
                </div>

                {/* Fee summary */}
                <div className="mt-6">
                    <h4 className={`mb-3 text-base font-bold text-[#102233] ${isArabic ? "text-right" : "text-left"}`}>
                        {priceSummaryTitle}
                    </h4>
                    <div className="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div className="space-y-4 text-[14px] text-[#102233]">
                            <SummaryRow
                                isRtl={isArabic}
                                label={`${selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : l("step1")} (${weeks} ${l("weeks")})`}
                            >
                                <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" />
                            </SummaryRow>

                            {selectedAccommodation && (
                                <SummaryRow
                                    isRtl={isArabic}
                                    label={`${loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)} (${weeks} ${l("weeks")})`}
                                >
                                    {accWaived && accOriginalTotal > 0 && (
                                        <span className="text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={accOriginalTotal} currency={currency} size="sm" muted />
                                        </span>
                                    )}
                                    <Price activeCurrency={activeCurrency} value={accPrice} currency={currency} size="sm" />
                                </SummaryRow>
                            )}

                            {oneTimeFees.map((fee) => (
                                <SummaryRow
                                    key={fee.key}
                                    isRtl={isArabic}
                                    label={`${fee.label}${fee.waived ? ` (${l("freeWithPioneers")})` : ""}`}
                                >
                                    {fee.waived && fee.originalTotal > 0 && (
                                        <span className="text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={fee.originalTotal} currency={currency} size="sm" muted />
                                        </span>
                                    )}
                                    <Price activeCurrency={activeCurrency} value={fee.total} currency={currency} size="sm" />
                                </SummaryRow>
                            ))}

                            {accSupplements.map((supp) => (
                                <SummaryRow
                                    key={supp.key}
                                    isRtl={isArabic}
                                    label={`${supp.label}${supp.perWeek ? ` (${supp.weeks} ${l("weeks")})` : ""}`}
                                >
                                    <Price activeCurrency={activeCurrency} value={supp.total} currency={currency} size="sm" />
                                </SummaryRow>
                            ))}

                            {(pickupWaived ? pickupOriginalTotal > 0 : pickupPrice > 0) && selectedPickup && (
                                <SummaryRow
                                    isRtl={isArabic}
                                    label={`${loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)}${pickupWaived ? ` (${l("freeWithPioneers")})` : ""}`}
                                >
                                    {pickupWaived && pickupOriginalTotal > 0 && (
                                        <span className="text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={pickupOriginalTotal} currency={currency} size="sm" muted />
                                        </span>
                                    )}
                                    <Price activeCurrency={activeCurrency} value={pickupPrice} currency={currency} size="sm" />
                                </SummaryRow>
                            )}

                            {insuranceLines.map((line) => (
                                <SummaryRow
                                    key={line.key}
                                    isRtl={isArabic}
                                    label={`${line.label}${line.perWeek ? ` (${line.weeks} ${l("weeks")})` : ""}${line.waived ? ` (${l("freeWithPioneers")})` : ""}`}
                                >
                                    {line.waived && line.originalTotal > 0 && (
                                        <span className="text-slate-400 line-through">
                                            <Price activeCurrency={activeCurrency} value={line.originalTotal} currency={currency} size="sm" muted />
                                        </span>
                                    )}
                                    <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                                </SummaryRow>
                            ))}

                            {supplementLines.map((line) => (
                                <SummaryRow key={line.key} isRtl={isArabic} label={loc(line.label, line.ar_label)}>
                                    <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                                </SummaryRow>
                            ))}

                            {courseDiscountAmount > 0 && (
                                <SummaryRow
                                    isRtl={isArabic}
                                    className="text-[#10B981]"
                                    label={`${l("courseDiscount")} (${appliedCourseDiscountPercent}%)`}
                                >
                                    <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                                        <Price
                                            activeCurrency={activeCurrency}
                                            value={courseDiscountAmount}
                                            currency={currency}
                                            size="sm"
                                            accent="green"
                                        />
                                        <span className="text-[14px] font-medium">-</span>
                                    </span>
                                </SummaryRow>
                            )}

                            {referralDiscountAmount > 0 && (
                                <SummaryRow
                                    isRtl={isArabic}
                                    className="text-[#10B981]"
                                    label={`${l("referralDiscount")} (${appliedReferral?.referrer_name}) (${appliedReferralDiscountPercent}%)`}
                                >
                                    <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                                        <Price
                                            activeCurrency={activeCurrency}
                                            value={referralDiscountAmount}
                                            currency={currency}
                                            size="sm"
                                            accent="green"
                                        />
                                        <span className="text-[14px] font-medium">-</span>
                                    </span>
                                </SummaryRow>
                            )}

                            {pioneersCashLines.map((line) => (
                                <SummaryRow
                                    key={line.key}
                                    isRtl={isArabic}
                                    className="text-[#10B981]"
                                    label={`${loc(line.label, line.ar_label)}${line.multiplier > 1 ? ` (${line.multiplier}x)` : ""}`}
                                >
                                    <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                                        <Price
                                            activeCurrency={activeCurrency}
                                            value={line.total}
                                            currency={currency}
                                            size="sm"
                                            accent="green"
                                        />
                                        <span className="text-[14px] font-medium">-</span>
                                    </span>
                                </SummaryRow>
                            ))}
                        </div>

                        <div className="mt-4 border-t border-gray-100 pt-4">
                            <div className="flex items-start justify-between gap-4" dir="ltr">
                                <div className="text-lg font-semibold text-[#102233]">
                                    {t("pages.institute_details.booking.total") || (isArabic ? "الاجمالي" : "Total")}
                                </div>
                                <div className="flex flex-col items-end">
                                    <div className="flex items-center justify-end gap-2" dir="ltr">
                                        {totalDiscountAmount > 0 && (
                                            <FooterPriceDisplay
                                                variant="compare"
                                                value={subtotal}
                                                currency={currency}
                                                activeCurrency={activeCurrency}
                                            />
                                        )}
                                        <FooterPriceDisplay
                                            variant="main"
                                            value={totalPrice}
                                            currency={currency}
                                            activeCurrency={activeCurrency}
                                        />
                                    </div>
                                    <p className="mt-1 text-right text-[12px] leading-snug text-[#64748B]">
                                        {l("totalIncludes")}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {/* Mobile Bottom Fixed Action Bar */}
            <div className="fixed bottom-0 left-0 w-full z-30 border-t border-[#E8ECF1] bg-white px-4 py-6">
                <div className="flex items-center justify-between gap-3" dir="ltr">
                    <Link
                        href={buildInstituteBookingUrl(slug, {
                            courseId: selectedCourseId,
                            weeks,
                            accommodationId: selectedAccommodationId,
                            pickupId: selectedPickupId,
                            startDate: formatInstituteQueryDate(startDate),
                            extras: selectedExtras,
                            accAge,
                        })}
                        className="flex min-h-[48px] flex-1 items-center justify-center gap-1 rounded-xl bg-[#0057B7] px-2 text-[15px] font-semibold text-white transition-transform active:scale-[0.98]"
                    >
                        {isArabic ? (
                            <>
                                <span className="text-[15px] leading-none">←</span>
                                <span>{l("reviewRequest")}</span>
                            </>
                        ) : (
                            <>
                                <span>{l("reviewRequest")}</span>
                                <span className="text-[15px] leading-none">→</span>
                            </>
                        )}
                    </Link>
                    <div className="shrink-0 pl-1">
                        <div className="flex items-center justify-end gap-2" dir="ltr">
                            {totalDiscountAmount > 0 && (
                                <FooterPriceDisplay
                                    variant="compare"
                                    value={subtotal}
                                    currency={currency}
                                    activeCurrency={activeCurrency}
                                />
                            )}
                            <FooterPriceDisplay
                                variant="main"
                                value={totalPrice}
                                currency={currency}
                                activeCurrency={activeCurrency}
                            />
                        </div>
                        <p className="mt-1 text-right text-[12px] leading-snug text-[#64748B]">
                            {l("totalIncludes")}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
