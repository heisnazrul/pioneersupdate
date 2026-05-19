"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheckCircle } from "@fortawesome/free-solid-svg-icons";
import Image from "next/image";
import Link from "next/link";
import HeroDatePicker from "@/app/components/HeroDatePicker";
import HeroDropdown from "@/app/components/HeroDropdown";

/* ── Helpers ──────────────────────────────────────────────── */

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
            <span className={`inline-flex items-center gap-1 ${className}`}>
                <img src="/assets/sar-black.svg" alt="SAR" width={iconSize} height={iconSize} className="inline-block" />
                <span>{num}</span>
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

function formatLocalDate(date) {
    const d = new Date(date);
    if (Number.isNaN(d.getTime())) return "";
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${day}`;
}

/* ── Labels ───────────────────────────────────────────────── */
const L = {
    studyDates: { en: "Study Dates", ar: "تواريخ الدراسة" },
    selectDate: { en: "Select Date", ar: "اختر التاريخ" },
    duration: { en: "Duration", ar: "المدة" },
    weeks: { en: "Weeks", ar: "الأسابيع" },
    selectWeeks: { en: "Select weeks", ar: "اختر الأسابيع" },
    couponQ: { en: "Do you have a coupon?", ar: "هل لديك كوبون؟" },
    couponCode: { en: "Coupon Code", ar: "رمز الكوبون" },
    apply: { en: "Apply", ar: "تطبيق" },
    regFee: { en: "Registration Fee", ar: "رسوم التسجيل" },
    totalDiscount: { en: "Total Discount", ar: "إجمالي الخصم" },
    totalFees: { en: "Total inclusive of all fees", ar: "الإجمالي شامل جميع الرسوم" },
    review: { en: "Review & Confirm", ar: "مراجعة وتأكيد" },
};

function l(key, isArabic) {
    const obj = L[key];
    return obj ? t(obj.en, obj.ar, isArabic) : key;
}

/* ── Component ────────────────────────────────────────────── */

export default function BookingSummary({
    institute,
    selectedCourse,
    selectedAccommodation,
    selectedPickup,
    selectedExtras = [],
    weeks = 1,
    startDate,
    onDateChange,
    onWeeksChange,
    currency = "GBP",
    isArabic = false,
    registrationFee = 50,
    discountPercent = 0,
    hideButton = false,
    slug,
    accAge,
}) {
    const weeksCount = weeks || 1;

    const coursePrice = selectedCourse ? priceField(selectedCourse, "price", currency) * weeksCount : 0;
    const accPrice = selectedAccommodation ? priceField(selectedAccommodation, "fee_per_week", currency) * weeksCount : 0;
    const pickupPrice = selectedPickup ? priceField(selectedPickup, "price", currency) : 0;
    const extrasPrice = (selectedExtras || []).reduce((sum, e) => sum + (priceField(e, "price", currency) || priceField(e, "amount", currency) || 0), 0);

    const subtotal = coursePrice + accPrice + pickupPrice + extrasPrice + registrationFee;
    const discountAmount = discountPercent > 0 ? subtotal * (discountPercent / 100) : 0;
    const total = subtotal - discountAmount;
    const oldTotal = discountPercent > 0 ? subtotal : null;

    // Date formatting
    const formattedStartDate = startDate
        ? new Date(startDate).toLocaleDateString(isArabic ? "ar-SA" : "en-GB", { day: "numeric", month: "long", year: "numeric" })
        : l("selectDate", isArabic);
    const endDate = startDate
        ? new Date(new Date(startDate).getTime() + weeksCount * 7 * 24 * 60 * 60 * 1000).toLocaleDateString(isArabic ? "ar-SA" : "en-GB", { day: "numeric", month: "long", year: "numeric" })
        : "-";

    return (
        <div className="w-full overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl">
            {/* Top Header */}
            <div className="bg-[#F0F7FC] py-6 text-center border-b border-gray-100 m-4 rounded-2xl">
                <h3 className="text-3xl font-semibold tracking-tight">
                    <Price value={total} currency={currency} size="lg" />
                </h3>
                <p className="text-sm font-medium text-slate-500">{l("totalFees", isArabic)}</p>
            </div>

            <div className="p-6">
                {/* Date & Duration — interactive */}
                <div className="mb-6 space-y-4">
                    {/* Date picker */}
                    {onDateChange ? (
                        <HeroDatePicker
                            label={l("studyDates", isArabic)}
                            placeholder={l("selectDate", isArabic)}
                            selectedDate={startDate}
                            onSelect={onDateChange}
                        />
                    ) : (
                        <div className="relative rounded-2xl border border-gray-200 bg-white px-5 py-4 transition hover:border-gray-300 hover:shadow-sm">
                            <label className="mb-1 block text-xs font-medium text-slate-400 uppercase tracking-wide">{l("studyDates", isArabic)}</label>
                            <div className="flex items-center justify-between gap-2">
                                <div className="text-base font-semibold text-[#1E293B]">{formattedStartDate}</div>
                                <div className="text-base font-semibold text-[#1E293B]">{endDate}</div>
                            </div>
                        </div>
                    )}

                    {/* Weeks */}
                    {onWeeksChange ? (
                        <HeroDropdown
                            label={l("duration", isArabic)}
                            placeholder={l("selectWeeks", isArabic)}
                            scroll
                            options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks", isArabic)}`, value: i + 1 }))}
                            onSelect={(opt) => onWeeksChange(opt.value)}
                            selectedValue={weeksCount}
                        />
                    ) : (
                        <div className="relative rounded-2xl border border-gray-200 bg-white px-5 py-4 transition hover:border-gray-300 hover:shadow-sm">
                            <label className="mb-1 block text-xs font-medium text-slate-400 uppercase tracking-wide">{l("duration", isArabic)}</label>
                            <div className="text-base font-semibold text-[#1E293B]">{weeksCount} {l("weeks", isArabic)}</div>
                        </div>
                    )}
                </div>

                {/* Coupon */}
                <div className="mb-8">
                    <label className="mb-3 block text-sm font-semibold text-[#1E293B]">{l("couponQ", isArabic)}</label>
                    <div className="relative">
                        <input
                            type="text"
                            placeholder={l("couponCode", isArabic)}
                            className="w-full rounded-xl border border-gray-200 bg-gray-50 pl-5 pr-28 py-3.5 text-sm font-medium text-slate-700 outline-none transition focus:border-[#0057B7] focus:bg-white placeholder:text-gray-400"
                        />
                        <button className="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-[#CBD5E1] px-6 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-400 hover:text-white">
                            {l("apply", isArabic)}
                        </button>
                    </div>
                </div>

                {/* Breakdown */}
                <div className="mb-8 space-y-4 text-sm font-normal">
                    {selectedCourse && (
                        <div className="flex justify-between text-[#1E293B]">
                            <span className="font-medium">{t(selectedCourse.name, selectedCourse.ar_name, isArabic)} ({weeksCount} {l("weeks", isArabic)})</span>
                            <span className="font-semibold"><Price value={coursePrice} currency={currency} size="sm" /></span>
                        </div>
                    )}

                    {selectedAccommodation && (
                        <div className="flex justify-between text-[#1E293B]">
                            <span className="font-medium">{t(selectedAccommodation.title, selectedAccommodation.ar_title, isArabic)} ({weeksCount} {l("weeks", isArabic)})</span>
                            <span className="font-semibold"><Price value={accPrice} currency={currency} size="sm" /></span>
                        </div>
                    )}

                    {selectedPickup && (
                        <div className="flex justify-between text-[#1E293B]">
                            <span className="font-medium">{t(selectedPickup.route, selectedPickup.ar_route, isArabic)}</span>
                            <span className="font-semibold"><Price value={pickupPrice} currency={currency} size="sm" /></span>
                        </div>
                    )}

                    {selectedExtras.map((extra, idx) => (
                        <div key={extra.id || idx} className="flex justify-between text-[#1E293B]">
                            <span className="font-medium">{t(extra.route || extra.name, extra.ar_name, isArabic)}</span>
                            <span className="font-semibold"><Price value={priceField(extra, "price", currency) || priceField(extra, "amount", currency)} currency={currency} size="sm" /></span>
                        </div>
                    ))}

                    <div className="flex justify-between text-[#1E293B]">
                        <span className="font-medium">{l("regFee", isArabic)}</span>
                        <span className="font-semibold"><Price value={registrationFee} currency={currency} size="sm" /></span>
                    </div>

                    {discountAmount > 0 && (
                        <div className="flex justify-between text-[#10B981] border-t border-dashed border-gray-200 pt-3 mt-3">
                            <span className="font-medium">{l("totalDiscount", isArabic)} ({discountPercent}%)</span>
                            <span className="font-semibold">-<Price value={discountAmount} currency={currency} size="sm" /></span>
                        </div>
                    )}
                </div>

                {/* Footer Action */}
                <div className="flex items-center gap-2 border-t border-gray-100 pt-6">
                    <div className="flex-1">
                        <div className="flex items-baseline gap-2">
                            <span className="text-xl font-semibold text-[#0057B7]"><Price value={total} currency={currency} /></span>
                            {oldTotal && <span className="text-xl font-medium text-slate-300 line-through decoration-2"><Price value={oldTotal} currency={currency} /></span>}
                        </div>
                        <div className="text-xs font-medium text-slate-400">{l("totalFees", isArabic)}</div>
                    </div>

                    {!hideButton && (<Link href={{
                        pathname: `/language-institutes/${slug || institute?.school?.slug || institute?.slug}/booking`,
                        query: {
                            course_id: selectedCourse?.id,
                            accommodation_id: selectedAccommodation?.id,
                            pickup_id: selectedPickup?.id,
                            weeks: weeks,
                            start_date: startDate ? formatLocalDate(startDate) : null,
                            extras: selectedExtras?.map(e => e.id).join(","),
                            acc_age: accAge
                        }
                    }} className="flex-1">
                        <button className="flex gap-1 w-full items-center justify-center rounded-xl bg-[#0057B7] p-4 text-sm font-medium text-white shadow-lg shadow-blue-200/50 transition hover:bg-[#004494] hover:shadow-blue-300/50">
                            <span>{l("review", isArabic)}</span>
                            <Image src="/assets/icons/arrow-left.svg" width={10} height={10} alt="Arrow" className="invert rotate-180" />
                        </button>
                    </Link>)}
                </div>
            </div>
        </div>
    );
}
