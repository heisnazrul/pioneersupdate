"use client";

import { useCallback } from "react";
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck, faCopy } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import {
    FooterPriceDisplay,
    Price,
    SummaryRow,
    formatDisplayDate,
} from "@/components/mobile/institute-booking-ui";
import { pickBookingImage } from "@/lib/student-media";

const PLATFORM_WHATSAPP_DISPLAY = "+966 55 002 7268";
const PLATFORM_WHATSAPP_E164 = "966550027268";

function buildWhatsAppHref(message) {
    return `https://wa.me/${PLATFORM_WHATSAPP_E164}?text=${encodeURIComponent(message)}`;
}

function CopyButton({ value, label, isRtl }) {
    const copy = useCallback(async () => {
        if (!value) return;
        try {
            await navigator.clipboard.writeText(String(value));
        } catch {
            // ignore
        }
    }, [value]);

    return (
        <button
            type="button"
            onClick={copy}
            className="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 transition hover:text-[#0B5DB6]"
            title={label}
        >
            <FontAwesomeIcon icon={faCopy} className="h-4 w-4" />
        </button>
    );
}

function SectionTitle({ children, isRtl }) {
    return (
        <h2 className={`mb-3 text-base font-bold text-[#102233] ${isRtl ? "text-right" : "text-left"}`}>
            {children}
        </h2>
    );
}

function DetailCard({ children, isRtl }) {
    return (
        <div className={`rounded-2xl border border-gray-100 bg-white p-5 shadow-sm ${isRtl ? "text-right" : "text-left"}`}>
            {children}
        </div>
    );
}

function parseBookingDate(dateStr) {
    if (!dateStr) return null;
    const [y, m, d] = String(dateStr).split("-").map(Number);
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
}

function accommodationSubtitle(acc, isArabic) {
    if (!acc) return null;
    const features = isArabic
        ? (acc.features_ar?.length ? acc.features_ar : acc.features)
        : acc.features;
    if (Array.isArray(features) && features.length) {
        return features.filter(Boolean).join(" - ");
    }
    const parts = isArabic
        ? [acc.bedroom_type_ar || acc.bedroom_type, acc.bathroom_type_ar || acc.bathroom_type, acc.meal_plan_ar || acc.meal_plan]
        : [acc.bedroom_type, acc.bathroom_type, acc.meal_plan];
    const labels = parts.map((item) => String(item || "").trim()).filter(Boolean);
    return labels.length ? labels.join(" - ") : null;
}

export default function BookingConfirmation({ booking }) {
    const { direction, language, t } = useLocale();
    const { activeCurrency } = useCurrency();
    const isArabic = language === "ar";
    const isRtl = direction === "rtl";
    const textAlign = isRtl ? "text-right" : "text-left";
    const loc = (key, fallback = "") => t(`pages.student.confirmation.${key}`, fallback);

    const currency = booking?.currency || "SAR";
    const bookingId = booking?.reference_no || booking?.booking_id || "-";
    const schoolName = isArabic
        ? booking?.school_ar_name || booking?.school_name
        : booking?.school_name || booking?.school_ar_name;
    const location = isArabic
        ? [booking?.country_ar_name, booking?.city_ar_name].filter(Boolean).join(" ، ")
        : [booking?.country_name, booking?.city_name].filter(Boolean).join(", ");
    const whatsapp = PLATFORM_WHATSAPP_DISPLAY;
    const whatsappHref = buildWhatsAppHref(
        loc("whatsapp_message", "Hello,\nI just submitted a booking request.\n\nReference: #{reference}\n\nThank you!")
            .replace("#{reference}", `#${bookingId}`),
    );
    const imageSrc = pickBookingImage(booking);

    const feeLines = Array.isArray(booking?.fee_lines) ? booking.fee_lines : [];
    const discountLines = Array.isArray(booking?.discount_lines) ? booking.discount_lines : [];
    const itemDiscounts = discountLines.filter((line) => !line.is_summary);
    const summaryDiscount = discountLines.find((line) => line.is_summary);
    const total = booking?.final_price ?? booking?.total ?? 0;
    const subtotal = booking?.subtotal ?? booking?.original_price ?? total;

    const courseTitle = isArabic
        ? booking?.course_ar_name || booking?.course_name
        : booking?.course_name || booking?.course_ar_name;
    const formattedStartDate = formatDisplayDate(parseBookingDate(booking?.start_date), isArabic);
    const weeks = booking?.weeks ?? 0;
    const acc = booking?.accommodation;
    const accTitle = acc
        ? (isArabic ? acc.type_ar || acc.ar_name || acc.name : acc.type || acc.name || acc.ar_name)
        : null;
    const accSubtitle = accommodationSubtitle(acc, isArabic);

    const pickupService = booking?.services?.find((s) => s.key === "pickup");
    const otherServices = booking?.services?.filter((s) => s.key !== "pickup") || [];

    const hasDiscount = itemDiscounts.length > 0 || summaryDiscount || (booking?.total_discount > 0);

    return (
        <div className="min-h-screen bg-white pb-28 md:pb-10" dir={direction}>
            <div className="mx-auto max-w-xl px-4 pt-6 md:pt-10">
                {/* Success header */}
                <div className="flex flex-col items-center text-center">
                    <div className="flex h-[72px] w-[72px] items-center justify-center rounded-full bg-[#22C55E] text-white shadow-sm">
                        <FontAwesomeIcon icon={faCheck} className="h-8 w-8" />
                    </div>
                    <h1 className="mt-5 text-[22px] font-bold leading-snug text-[#102233] md:text-[28px]">
                        {loc("title", "Your request was sent successfully")}
                    </h1>
                    <p className="mt-3 max-w-md text-sm leading-7 text-[#64748B] md:text-base">
                        {loc(
                            "subtitle",
                            "Our team will review your request and contact you on WhatsApp to confirm details and help you choose the best option before final confirmation.",
                        )}
                    </p>
                </div>

                {/* WhatsApp contact */}
                <a
                    href={whatsappHref}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-6 flex items-center justify-center gap-3 rounded-2xl bg-[#F0FDF4] px-4 py-3.5 transition hover:bg-[#DCFCE7]"
                >
                    <Image src="/assets/icons/whatsapp-circle.svg" alt="WhatsApp" width={28} height={28} />
                    <span dir="ltr" className="text-lg font-semibold text-[#22C55E]">{whatsapp}</span>
                </a>

                {/* Booking reference */}
                <div className="mt-4 flex items-center justify-between rounded-2xl border border-gray-100 bg-white px-4 py-4 shadow-sm" dir="ltr">
                    {isRtl ? (
                        <>
                            <CopyButton value={bookingId} label={loc("copy_booking", "Copy booking number")} isRtl={isRtl} />
                            <p className={`flex-1 text-sm font-medium text-[#102233] ${textAlign}`}>
                                {loc("booking_no", "Booking #")}
                                <span className="mx-1" dir="ltr">#{bookingId}</span>
                            </p>
                        </>
                    ) : (
                        <>
                            <p className="flex-1 text-sm font-medium text-[#102233]">
                                {loc("booking_no", "Booking #")}
                                <span className="mx-1">#{bookingId}</span>
                            </p>
                            <CopyButton value={bookingId} label={loc("copy_booking", "Copy booking number")} isRtl={isRtl} />
                        </>
                    )}
                </div>

                {/* Institute card */}
                <div className="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div className="flex items-start gap-3 p-4" dir="ltr">
                        {isRtl ? (
                            <>
                                <div className="min-w-0 flex-1 text-right">
                                    <h3 className="text-base font-semibold leading-tight text-[#102233]">{schoolName || "-"}</h3>
                                    <p className="mt-1 text-xs text-slate-500">{location || "-"}</p>
                                </div>
                                <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-50">
                                    <img
                                        src={imageSrc}
                                        alt={schoolName || "School"}
                                        className="h-full w-full object-cover"
                                        onError={(e) => {
                                            e.currentTarget.onerror = null;
                                            e.currentTarget.src = "/assets/hero.png";
                                        }}
                                    />
                                </div>
                            </>
                        ) : (
                            <>
                                <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-50">
                                    <img
                                        src={imageSrc}
                                        alt={schoolName || "School"}
                                        className="h-full w-full object-cover"
                                        onError={(e) => {
                                            e.currentTarget.onerror = null;
                                            e.currentTarget.src = "/assets/hero.png";
                                        }}
                                    />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <h3 className="text-base font-semibold leading-tight text-[#102233]">{schoolName || "-"}</h3>
                                    <p className="mt-1 text-xs text-slate-500">{location || "-"}</p>
                                </div>
                            </>
                        )}
                    </div>
                </div>

                {/* Price summary */}
                <div className="mt-6">
                    <SectionTitle isRtl={isRtl}>{loc("price_summary", "Price Summary")}</SectionTitle>
                    <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div className="space-y-3 text-[14px] text-[#102233]">
                            {feeLines.map((line) => {
                                const label = isArabic ? line.ar_label || line.label : line.label || line.ar_label;
                                const detail = isArabic ? line.ar_detail || line.detail : line.detail || line.ar_detail;
                                const rowLabel = detail ? `${label} (${detail})` : label;
                                return (
                                    <SummaryRow key={line.key || rowLabel} isRtl={isRtl} label={rowLabel}>
                                        <Price
                                            activeCurrency={activeCurrency}
                                            value={Math.abs(line.amount)}
                                            currency={currency}
                                            size="sm"
                                        />
                                    </SummaryRow>
                                );
                            })}

                            {itemDiscounts.map((line) => {
                                const label = isArabic ? line.ar_label || line.label : line.label || line.ar_label;
                                const amount = Math.abs(line.amount);
                                return (
                                    <SummaryRow key={line.key || label} isRtl={isRtl} className="text-[#10B981]" label={label}>
                                        <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                                            <Price
                                                activeCurrency={activeCurrency}
                                                value={amount}
                                                currency={currency}
                                                size="sm"
                                                accent="green"
                                            />
                                            <span className="text-[14px] font-medium">-</span>
                                        </span>
                                    </SummaryRow>
                                );
                            })}

                            {summaryDiscount ? (
                                <SummaryRow
                                    isRtl={isRtl}
                                    className="text-[#EF4444]"
                                    label={isArabic ? summaryDiscount.ar_label || summaryDiscount.label : summaryDiscount.label || summaryDiscount.ar_label}
                                >
                                    <Price
                                        activeCurrency={activeCurrency}
                                        value={Math.abs(summaryDiscount.amount)}
                                        currency={currency}
                                        size="sm"
                                        className="text-[#EF4444]"
                                    />
                                </SummaryRow>
                            ) : null}

                            {!summaryDiscount && booking?.total_discount > 0 && itemDiscounts.length === 0 ? (
                                <SummaryRow isRtl={isRtl} className="text-[#EF4444]" label={loc("total_discount", "Total Discount")}>
                                    <Price
                                        activeCurrency={activeCurrency}
                                        value={booking.total_discount}
                                        currency={currency}
                                        size="sm"
                                        className="text-[#EF4444]"
                                    />
                                </SummaryRow>
                            ) : null}
                        </div>

                        <div className="mt-4 border-t border-gray-100 pt-4">
                            <div className="flex items-start justify-between gap-4" dir="ltr">
                                {isRtl ? (
                                    <>
                                        <div className="flex shrink-0 flex-col items-end">
                                            <div className="flex items-center justify-end gap-2" dir="ltr">
                                                {hasDiscount && subtotal > total ? (
                                                    <FooterPriceDisplay
                                                        variant="compare"
                                                        value={subtotal}
                                                        currency={currency}
                                                        activeCurrency={activeCurrency}
                                                    />
                                                ) : null}
                                                <Price
                                                    activeCurrency={activeCurrency}
                                                    value={total}
                                                    currency={currency}
                                                    size="lg"
                                                    className="!text-[#0070CD]"
                                                />
                                            </div>
                                        </div>
                                        <div className="max-w-[55%] text-right text-sm font-semibold leading-snug text-[#102233]">
                                            {loc("grand_total", "Total ( Price includes all fees )")}
                                        </div>
                                    </>
                                ) : (
                                    <>
                                        <div className="max-w-[55%] text-left text-sm font-semibold leading-snug text-[#102233]">
                                            {loc("grand_total", "Total ( Price includes all fees )")}
                                        </div>
                                        <div className="flex shrink-0 flex-col items-end">
                                            <div className="flex items-center justify-end gap-2" dir="ltr">
                                                {hasDiscount && subtotal > total ? (
                                                    <FooterPriceDisplay
                                                        variant="compare"
                                                        value={subtotal}
                                                        currency={currency}
                                                        activeCurrency={activeCurrency}
                                                    />
                                                ) : null}
                                                <Price
                                                    activeCurrency={activeCurrency}
                                                    value={total}
                                                    currency={currency}
                                                    size="lg"
                                                    className="!text-[#0070CD]"
                                                />
                                            </div>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Course details */}
                <div className="mt-6">
                    <SectionTitle isRtl={isRtl}>{loc("course_details", "Course Details")}</SectionTitle>
                    <DetailCard isRtl={isRtl}>
                        <p className="text-sm font-medium text-[#102233]">{courseTitle || "-"}</p>
                        <p className="mt-2 text-xs text-slate-500">
                            {loc("duration", "Duration")}: {weeks} {loc("weeks", "weeks")}
                        </p>
                        <p className="mt-1 text-xs text-slate-500">
                            {loc("course_start", "Course start")}: {formattedStartDate}
                        </p>
                    </DetailCard>
                </div>

                {/* Accommodation details */}
                {accTitle ? (
                    <div className="mt-6">
                        <SectionTitle isRtl={isRtl}>{loc("acc_details", "Accommodation Details")}</SectionTitle>
                        <DetailCard isRtl={isRtl}>
                            <p className="text-sm font-medium text-[#102233]">{accTitle}</p>
                            {accSubtitle ? (
                                <p className="mt-2 text-xs text-slate-500">{accSubtitle}</p>
                            ) : null}
                        </DetailCard>
                    </div>
                ) : null}

                {/* Additional services */}
                <div className="mt-6">
                    <SectionTitle isRtl={isRtl}>{loc("add_services", "Additional Services")}</SectionTitle>
                    <DetailCard isRtl={isRtl}>
                        <p className="text-sm font-medium text-[#102233]">
                            {pickupService
                                ? (isArabic ? pickupService.ar_name || pickupService.name : pickupService.name)
                                : (isArabic ? "الاستقبال من المطار" : "Airport Pickup")}
                        </p>
                        <p className="mt-2 text-xs text-slate-500">
                            {pickupService?.detail || pickupService?.ar_detail
                                ? (isArabic ? pickupService.ar_detail || pickupService.detail : pickupService.detail || pickupService.ar_detail)
                                : (isArabic ? "بدون استقبال" : "No pickup")}
                        </p>
                        {otherServices.map((service) => (
                            <div key={service.key} className="mt-3 border-t border-gray-50 pt-3">
                                <p className="text-sm font-medium text-[#102233]">
                                    {isArabic ? service.ar_name || service.name : service.name}
                                </p>
                                {(service.detail || service.ar_detail) ? (
                                    <p className="mt-1 text-xs text-slate-500">
                                        {isArabic ? service.ar_detail || service.detail : service.detail}
                                    </p>
                                ) : null}
                            </div>
                        ))}
                    </DetailCard>
                </div>

                {/* Desktop footer buttons */}
                <div className="mt-6 hidden gap-3 md:flex">
                    <Link
                        href="/student/bookings"
                        className="flex-1 rounded-2xl border border-gray-200 bg-white py-3.5 text-center text-sm font-semibold text-[#102233] transition hover:bg-slate-50"
                    >
                        {loc("go_bookings", "My Bookings")}
                    </Link>
                    <Link
                        href="/"
                        className="flex-1 rounded-2xl bg-[#0070CD] py-3.5 text-center text-sm font-semibold text-white transition hover:bg-[#005EB3]"
                    >
                        {loc("back_home", "Back to Home")}
                    </Link>
                </div>
            </div>

            {/* Mobile fixed footer — Figma */}
            <div className="fixed bottom-0 left-0 z-30 w-full border-t border-[#E8ECF1] bg-white px-4 py-4 md:hidden">
                <div className="mx-auto flex max-w-xl gap-3">
                    <Link
                        href="/student/bookings"
                        className="flex h-12 flex-1 items-center justify-center rounded-2xl border border-gray-200 bg-white text-[15px] font-semibold text-[#102233] transition hover:bg-slate-50"
                    >
                        {loc("go_bookings", "My Bookings")}
                    </Link>
                    <Link
                        href="/"
                        className="flex h-12 flex-1 items-center justify-center rounded-2xl bg-[#0070CD] text-[15px] font-semibold text-white transition hover:bg-[#005EB3]"
                    >
                        {loc("back_home", "Back to Home")}
                    </Link>
                </div>
            </div>
        </div>
    );
}
