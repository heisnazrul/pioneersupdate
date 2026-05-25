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

export default function MobileInstituteDetails({ params }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { language, t } = useLocale();
    const isArabic = language === "ar";
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

    const getDiscountPrice = (price) => discountPercent > 0 ? price * (1 - discountPercent / 100) : null;

    if (!school) return <div className="p-20 text-center">Loading...</div>;

    const l = (key) => t(`pages.institute_details.${key}`);
    const loc = (en, ar) => (isArabic && ar) ? ar : en;

    return (
        <div className="pb-32 bg-[#F8FAFC]" dir={isArabic ? "rtl" : "ltr"}>
            
            {/* Mobile Hero */}
            <div className="relative mb-6">
                <div className="relative aspect-[4/3] w-full bg-slate-200">
                    {school.image && <img src={school.image} alt={loc(school.name, school.ar_name)} className="w-full h-full object-cover" />}
                    
                    {/* Top Actions */}
                    <div className="absolute top-0 left-0 w-full p-4 flex items-center justify-between z-10 pt-12">
                        <div className="flex items-center gap-3">
                            <button onClick={handleShare} className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                                <FontAwesomeIcon icon={faShare} />
                            </button>
                            <button className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white hover:text-red-500">
                                <FontAwesomeIcon icon={faHeart} />
                            </button>
                            <button className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white hover:text-[#0057B7]">
                                <FontAwesomeIcon icon={faExchangeAlt} />
                            </button>
                        </div>
                        <Link href="/language-institutes" className="h-10 w-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-slate-700 shadow-sm transition hover:bg-white">
                            <img src="/assets/icons/arrow-left.svg" width={20} height={20} alt="Back" className={`transform ${isArabic ? '' : 'rotate-180'}`} />
                        </Link>
                    </div>

                    {/* Logo Overlay */}
                    <div className="absolute bottom-2 left-1/2 -translate-x-1/2 z-20">
                        <div className="p-2 h-20 w-20 rounded-full border-[4px] border-white bg-white shadow-lg overflow-hidden flex items-center justify-center">
                            {school.logo ? (
                                <img src={school.logo} alt="Logo" className="w-full h-full object-contain" />
                            ) : (
                                <span className="text-xl font-medium text-slate-300">LOGO</span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Mobile Header Info */}
                <div className="relative z-10 -mt-8 rounded-t-[30px] bg-white pt-14 px-4 text-center pb-6 shadow-sm border-b border-gray-100">
                    <h1 className="text-2xl font-semibold text-slate-900 leading-tight mb-4">
                        {isArabic
                            ? `${school.ar_name || school.name} - ${school.city_ar || school.city} - ${school.name}`
                            : `${school.name} - ${school.city}`
                        }
                    </h1>

                    <div className="flex items-center justify-center gap-3 flex-wrap">
                        <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                            <span className="font-medium text-slate-900 line-clamp-1 text-sm max-w-[150px]">{loc(school.location, (school.city_ar ? `${school.country_ar}, ${school.city_ar}` : null))}</span>
                            {school.flag && <img src={school.flag} width={20} height={14} alt="Flag" className="rounded-sm" />}
                        </div>
                        <div className="flex items-center gap-2 bg-[#F0F7FC] border border-[#DCE6F1] px-4 py-3 rounded-2xl min-w-[140px] justify-center">
                            <span className="font-medium text-slate-900">{school.rating}</span>
                            <FontAwesomeIcon icon={faStar} className="text-[#F59E0B] text-sm" />
                        </div>
                    </div>
                </div>
            </div>

            <div className="px-4 space-y-8">
                {/* Step 1: Choose Course */}
                <div>
                    <div className="mb-4">
                        <h3 className="mb-2 text-2xl font-semibold text-slate-900">{l("step1")}</h3>
                        <p className="text-sm text-slate-500 mb-4">{l("step1Sub")}</p>
                        
                        <div className="flex flex-1 justify-start gap-2">
                            <div className="w-[48%]">
                                <HeroDatePicker
                                    label={l("startDate")}
                                    placeholder={l("selectStart")}
                                    selectedDate={startDate}
                                    onSelect={(date) => setStartDate(date)}
                                />
                            </div>
                            <div className="w-[48%]">
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

                    <div className="-mx-4 overflow-x-auto px-4 pb-4">
                        <div className="flex gap-3 snap-x snap-mandatory">
                            {courses.map((course) => {
                                const price = priceField(course, "price", currency);
                                const discPrice = getDiscountPrice(price);
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
                                                            -{discountPercent}%
                                                        </span>
                                                        <span className="text-sm font-medium text-slate-400 line-through">
                                                            <Price value={price} currency={currency} size="sm" />
                                                        </span>
                                                    </div>
                                                )}
                                                <div className="flex items-end gap-1 flex-1 justify-end">
                                                    <span className="text-xl font-bold text-slate-900">
                                                        <Price value={discPrice || price} currency={currency} size="lg" />
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

                {/* Step 2: Choose Accommodation */}
                <div>
                    <div className={`mb-4 ${isArabic ? "text-right" : "text-left"}`}>
                        <h3 className="text-2xl font-semibold text-slate-900">{l("step2")}</h3>
                        <p className="mt-1 text-sm text-slate-500">{l("step2Sub")}</p>
                    </div>

                    <div className="mt-3 grid grid-cols-2 gap-3">
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

                    {accommodations.length > 0 && selectedAccommodationId !== 'no-acc' && (
                        <div className="mt-4 -mx-4 overflow-x-auto px-4 pb-4">
                            <div className="flex snap-x snap-mandatory gap-3">
                                {accommodations.map((acc) => {
                                    const accPrice = priceField(acc, "fee_per_week", currency);
                                    const accDiscPrice = getDiscountPrice(accPrice);
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
                                                {accDiscPrice && (
                                                    <div className="flex flex-col items-start gap-1">
                                                        <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-medium text-white">-{discountPercent}%</span>
                                                        <span className="text-sm font-medium text-slate-400 line-through"><Price value={accPrice} currency={currency} size="sm" /></span>
                                                    </div>
                                                )}
                                                <div className="flex items-end gap-1 flex-1 justify-end">
                                                    <span className="text-xl font-bold text-slate-900">
                                                        <Price value={accDiscPrice || accPrice} currency={currency} size="lg" />
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
            </div>
            
            {/* Mobile Bottom Fixed Action Bar */}
            <div className="fixed bottom-0 left-0 w-full z-30 bg-white px-5 py-4 border-t border-gray-100 shadow-[0_-8px_20px_rgba(0,0,0,0.04)]">
                <div className="flex items-center justify-between gap-4">
                    <div className="text-start" dir="ltr">
                        <div className="text-2xl font-bold text-[#0B5DB6]">
                            <Price value={totalPrice} currency={currency} size="lg" />
                        </div>
                        <div className="mt-1 text-[10px] font-medium text-slate-500 text-right">{l("totalIncludes")}</div>
                    </div>
                    <Link
                        href={`/language-institutes/${slug}/booking?course_id=${selectedCourseId}&weeks=${weeks}&accommodation_id=${selectedAccommodationId}`}
                        className="flex items-center justify-center gap-2 rounded-xl bg-[#0057B7] flex-1 py-3.5 text-base font-semibold text-white shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-transform"
                    >
                        <span>{l("reviewRequest")}</span>
                        <span className="text-lg">→</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}
