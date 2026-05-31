"use client";

import { useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGift, faXmark, faUser, faPercent } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { CurrencyAmount } from "@/components/shared/currency-amount";

export default function ReferralDiscountModal({
    open,
    onClose,
    referrerName,
    discountPercent,
    discountAmount,
    currency,
    activeCurrency,
}) {
    const { language, direction, t } = useLocale();
    const isArabic = language === "ar";
    const loc = (key, fallback = "") => t(`pages.institute_details.referralPopup.${key}`, fallback);

    useEffect(() => {
        if (!open) return undefined;

        const handleKeyDown = (event) => {
            if (event.key === "Escape") onClose();
        };

        document.body.style.overflow = "hidden";
        window.addEventListener("keydown", handleKeyDown);

        return () => {
            document.body.style.overflow = "";
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, [open, onClose]);

    if (!open) return null;

    return (
        <div
            className="fixed inset-0 z-[200] flex items-center justify-center p-4"
            dir={direction}
            role="dialog"
            aria-modal="true"
        >
            <button
                type="button"
                aria-label={loc("close", "Got it!")}
                className="absolute inset-0 bg-[#0f172a]/55 backdrop-blur-sm"
                onClick={onClose}
            />

            <div className="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/20 bg-white shadow-[0_24px_80px_rgba(0,87,183,0.35)]">
                <div className="relative overflow-hidden bg-gradient-to-br from-[#0057B7] via-[#1277BE] to-[#0284c7] px-6 pb-8 pt-7 text-white">
                    <button
                        type="button"
                        onClick={onClose}
                        className="absolute end-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white transition hover:bg-white/25"
                    >
                        <FontAwesomeIcon icon={faXmark} className="h-4 w-4" />
                    </button>

                    <div className="relative flex flex-col items-center text-center">
                        <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 shadow-lg ring-1 ring-white/30">
                            <FontAwesomeIcon icon={faGift} className="h-8 w-8 text-white" />
                        </div>
                        <h2 className="text-xl font-bold tracking-tight">
                            {loc("title", "Referral discount applied!")}
                        </h2>
                        <p className="mt-2 max-w-xs text-sm leading-relaxed text-white/85">
                            {loc("subtitle", "You will save on your course fee with this referral.")}
                        </p>
                    </div>
                </div>

                <div className="space-y-3 px-5 py-5">
                    <div className="flex items-center gap-3 rounded-2xl border border-sky-100 bg-gradient-to-r from-sky-50 to-white px-4 py-3.5">
                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0057B7] text-white shadow-sm">
                            <FontAwesomeIcon icon={faUser} className="h-5 w-5" />
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="text-xs font-semibold uppercase tracking-wide text-[#0057B7]">
                                {loc("referrer", "Referred by")}
                            </p>
                            <p className="truncate text-sm font-semibold text-[#102233]">
                                {referrerName || "—"}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-center gap-3 rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-50 to-white px-4 py-3.5">
                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                            <FontAwesomeIcon icon={faPercent} className="h-5 w-5" />
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                                {loc("discountLabel", "Your discount")}
                            </p>
                            <p className="text-sm font-semibold text-[#102233]">
                                {discountPercent != null ? `${discountPercent}%` : "—"}{" "}
                                {loc("offCourseFee", "off course fee")}
                            </p>
                        </div>
                        {discountAmount > 0 && (
                            <div className="shrink-0 text-end">
                                <CurrencyAmount
                                    currency={currency}
                                    amount={discountAmount}
                                    activeCurrency={activeCurrency}
                                    className="text-lg font-bold text-emerald-700"
                                    iconClassName="h-4 w-4"
                                    variant="light"
                                />
                            </div>
                        )}
                    </div>
                </div>

                <div className="border-t border-slate-100 px-5 py-4">
                    <button
                        type="button"
                        onClick={onClose}
                        className="w-full rounded-2xl bg-gradient-to-r from-[#0057B7] to-[#0284c7] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#0057B7]/25 transition hover:brightness-110"
                    >
                        {loc("close", "Got it!")}
                    </button>
                </div>
            </div>
        </div>
    );
}
