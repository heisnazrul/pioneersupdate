"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheckCircle } from "@fortawesome/free-solid-svg-icons";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { CurrencyIcon, formatPriceAmount } from "@/lib/format-currency";

export function Price({ value, currency, activeCurrency, className = "", size = "md", muted = false, accent = null }) {
    if (value === null || value === undefined) return null;

    const iconClassName = size === "lg" ? "h-[18px] w-[18px]" : size === "sm" ? "h-3.5 w-3.5" : "h-[14px] w-[14px]";
    const textClass = size === "lg" ? "text-[20px] font-bold" : size === "sm" ? "text-[14px]" : "text-[14px] font-medium";
    const accentClass = accent === "green" ? "text-[#10B981]" : "";

    return (
        <CurrencyAmount
            currency={currency}
            amount={value}
            activeCurrency={activeCurrency}
            currencyAfter={false}
            iconAccent={accent === "green" ? "green" : null}
            className={`inline-flex items-center gap-0.5 tabular-nums ${textClass} ${accentClass} ${className}`}
            iconClassName={iconClassName}
            muted={muted}
        />
    );
}

export function FooterPriceDisplay({ value, currency, activeCurrency, variant = "main" }) {
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

export function SummaryRow({ label, children, className = "", isRtl }) {
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

export function CardSectionHeader({ title, changeHref, changeLabel, isRtl }) {
    const changeBtn = (
        <Link
            href={changeHref}
            className="shrink-0 rounded border border-gray-100 bg-white px-3 py-1 text-[10px] font-medium text-slate-500 shadow-sm transition hover:bg-gray-50"
        >
            {changeLabel}
        </Link>
    );
    const titleEl = (
        <h4 className={`text-sm font-semibold text-[#102233] ${isRtl ? "text-right" : "text-left"}`}>
            {title}
        </h4>
    );

    return (
        <div className="mb-3 flex items-center justify-between gap-3" dir="ltr">
            {isRtl ? (
                <>
                    {changeBtn}
                    <div className="flex-1">{titleEl}</div>
                </>
            ) : (
                <>
                    <div className="flex-1">{titleEl}</div>
                    {changeBtn}
                </>
            )}
        </div>
    );
}

export function SummaryServiceRow({ isRtl, title, subtitle }) {
    const textBlock = (
        <div className={`flex-1 ${isRtl ? "text-right" : "text-left"}`}>
            <p className="text-sm font-medium text-[#102233]">{title}</p>
            {subtitle ? <p className="text-xs font-normal text-slate-500">{subtitle}</p> : null}
        </div>
    );
    const checkIcon = (
        <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] text-gray-400">
            <FontAwesomeIcon icon={faCheckCircle} />
        </div>
    );

    return (
        <div className="flex w-full items-start gap-2" dir="ltr">
            {isRtl ? (
                <>
                    {textBlock}
                    {checkIcon}
                </>
            ) : (
                <>
                    {checkIcon}
                    {textBlock}
                </>
            )}
        </div>
    );
}

export function BookingBackLink({ href, label, isRtl }) {
    const icon = (
        <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#EEF2F6]">
            <img
                src="/assets/icons/arrow-left.svg"
                width={16}
                height={16}
                alt=""
                className={isRtl ? "rotate-180" : ""}
            />
        </span>
    );

    return (
        <div className={`mb-3 flex ${isRtl ? "justify-start" : "justify-end"}`}>
            <Link href={href} className="inline-flex items-center gap-2 text-[14px] font-medium text-[#475569]">
                {isRtl ? (
                    <>
                        {icon}
                        <span>{label}</span>
                    </>
                ) : (
                    <>
                        {icon}
                        <span>{label}</span>
                    </>
                )}
            </Link>
        </div>
    );
}

export function formatDisplayDate(date, isArabic) {
    if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "-";
    return date.toLocaleDateString(isArabic ? "ar-SA" : "en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

export function accommodationFeatureLine(acc) {
    const list = Array.isArray(acc?.features)
        ? acc.features
        : typeof acc?.features === "string"
            ? acc.features.split(",")
            : [];
    const labels = list.map((item) => String(item).trim()).filter(Boolean);
    return labels.length ? labels.join(" - ") : null;
}

export function InstituteBookingPriceSummary({
    l,
    loc,
    isRtl,
    textAlign,
    currency,
    activeCurrency,
    selectedCourse,
    selectedAccommodation,
    selectedPickup,
    selection,
    pricing,
    appliedReferral,
    referralDiscountPercent,
}) {
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
        courseDiscountPercent: appliedCourseDiscountPercent,
        courseDiscountAmount,
        referralDiscountAmount,
        subtotal,
        total: totalPrice,
    } = pricing;

    const totalDiscountAmount = courseDiscountAmount + referralDiscountAmount + pricing.pioneersCashTotal;

    return (
        <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h3 className={`mb-4 text-base font-bold text-[#102233] ${textAlign}`}>{l("priceSummary")}</h3>
            <div className="space-y-3 text-[14px] text-[#102233]">
                {selectedCourse && (
                    <SummaryRow
                        isRtl={isRtl}
                        label={`${loc(selectedCourse.name, selectedCourse.ar_name)} (${selection.weeks} ${l("weeks")})`}
                    >
                        <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" />
                    </SummaryRow>
                )}

                {selectedAccommodation && (
                    <SummaryRow
                        isRtl={isRtl}
                        label={`${loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)} (${selection.weeks} ${l("weeks")})`}
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
                        isRtl={isRtl}
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
                        isRtl={isRtl}
                        label={`${supp.label}${supp.perWeek ? ` (${supp.weeks} ${l("weeks")})` : ""}`}
                    >
                        <Price activeCurrency={activeCurrency} value={supp.total} currency={currency} size="sm" />
                    </SummaryRow>
                ))}

                {(pickupWaived ? pickupOriginalTotal > 0 : pickupPrice > 0) && selectedPickup && (
                    <SummaryRow
                        isRtl={isRtl}
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
                        isRtl={isRtl}
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
                    <SummaryRow key={line.key} isRtl={isRtl} label={loc(line.label, line.ar_label)}>
                        <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                    </SummaryRow>
                ))}

                {courseDiscountAmount > 0 && (
                    <SummaryRow
                        isRtl={isRtl}
                        className="text-[#10B981]"
                        label={`${l("courseDiscount")} (${appliedCourseDiscountPercent}%)`}
                    >
                        <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                            <Price activeCurrency={activeCurrency} value={courseDiscountAmount} currency={currency} size="sm" accent="green" />
                            <span className="text-[14px] font-medium">-</span>
                        </span>
                    </SummaryRow>
                )}

                {referralDiscountAmount > 0 && (
                    <SummaryRow
                        isRtl={isRtl}
                        className="text-[#10B981]"
                        label={`${l("referralDiscount")} (${appliedReferral?.referrer_name}) (${referralDiscountPercent}%)`}
                    >
                        <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                            <Price activeCurrency={activeCurrency} value={referralDiscountAmount} currency={currency} size="sm" accent="green" />
                            <span className="text-[14px] font-medium">-</span>
                        </span>
                    </SummaryRow>
                )}

                {pioneersCashLines.map((line) => (
                    <SummaryRow
                        key={line.key}
                        isRtl={isRtl}
                        className="text-[#10B981]"
                        label={`${loc(line.label, line.ar_label)}${line.multiplier > 1 ? ` (${line.multiplier}x)` : ""}`}
                    >
                        <span className="inline-flex items-center gap-0.5 text-[#10B981]">
                            <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" accent="green" />
                            <span className="text-[14px] font-medium">-</span>
                        </span>
                    </SummaryRow>
                ))}
            </div>

            <div className="mt-4 border-t border-gray-100 pt-4">
                <div className="flex items-start justify-between gap-4" dir="ltr">
                    <div className="text-lg font-semibold text-[#102233]">{l("total")}</div>
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
                            {l("totalInclude")}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
