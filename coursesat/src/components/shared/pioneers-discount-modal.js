"use client";

import { useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faGift,
    faXmark,
    faTags,
    faStar,
    faPlaneArrival,
    faShieldAlt,
    faBookOpen,
    faPenToSquare,
    faBed,
} from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { CurrencyAmount } from "@/lib/format-currency";

const FREE_FOR_ICONS = {
    pickup: faPlaneArrival,
    insurance: faShieldAlt,
    material_books: faBookOpen,
    registration: faPenToSquare,
    accommodation: faBed,
};

function getFreeForLabel(freeFor, t) {
    const key = `pages.institute_details.pioneersPopup.free_${freeFor}`;
    const fallbacks = {
        pickup: "Airport Pickup",
        insurance: "Insurance",
        material_books: "Material & Books",
        registration: "Registration Fee",
        accommodation: "Accommodation",
    };
    return t(key, fallbacks[freeFor] || freeFor);
}

export default function PioneersDiscountModal({
    open,
    onClose,
    qualifying = [],
    weeks,
    currency,
    activeCurrency,
}) {
    const { language, direction, t } = useLocale();
    const isArabic = language === "ar";
    const loc = (en, ar) => (isArabic && ar) ? ar : en;

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

    if (!open || !qualifying.length) return null;

    const cashTiers = qualifying.filter((tier) => tier.type === "cash" && tier.total > 0);
    const freeTiers = qualifying.filter((tier) => tier.type === "free_item");
    const totalCash = cashTiers.reduce((sum, tier) => sum + tier.total, 0);

    return (
        <div
            className="fixed inset-0 z-[200] flex items-center justify-center p-4"
            dir={direction}
            role="dialog"
            aria-modal="true"
            aria-labelledby="pioneers-popup-title"
        >
            <button
                type="button"
                aria-label={t("pages.institute_details.pioneersPopup.close", "Got it!")}
                className="absolute inset-0 bg-[#0f172a]/55 backdrop-blur-sm"
                onClick={onClose}
            />

            <div className="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/20 bg-white shadow-[0_24px_80px_rgba(0,87,183,0.35)] animate-in fade-in zoom-in-95 duration-300">
                <div className="relative overflow-hidden bg-gradient-to-br from-[#0057B7] via-[#1277BE] to-[#0284c7] px-6 pb-8 pt-7 text-white">
                    <div className="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10" />
                    <div className="pointer-events-none absolute -bottom-10 -left-8 h-32 w-32 rounded-full bg-white/10" />
                    <div className="pointer-events-none absolute right-10 top-8 text-white/20">
                        <FontAwesomeIcon icon={faStar} className="h-6 w-6" />
                    </div>

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
                        <h2 id="pioneers-popup-title" className="text-xl font-bold tracking-tight">
                            {t("pages.institute_details.pioneersPopup.title", "Pioneers Rewards Unlocked!")}
                        </h2>
                        <p className="mt-2 max-w-xs text-sm leading-relaxed text-white/85">
                            {loc(
                                `Your ${weeks}-week course unlocks exclusive Pioneers savings`,
                                `دورتك لمدة ${weeks} أسابيع تمنحك مزايا Pioneers الحصرية`,
                            )}
                        </p>
                    </div>
                </div>

                <div className="space-y-3 px-5 py-5">
                    {cashTiers.map((tier) => (
                        <div
                            key={`cash-${tier.id}`}
                            className="flex items-center gap-3 rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-50 to-white px-4 py-3.5"
                        >
                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                                <FontAwesomeIcon icon={faTags} className="h-5 w-5" />
                            </div>
                            <div className="min-w-0 flex-1">
                                <p className="text-xs font-semibold uppercase tracking-wide text-emerald-700">
                                    {t("pages.institute_details.pioneersPopup.cashLabel", "Cash bonus")}
                                </p>
                                <p className="truncate text-sm font-semibold text-[#102233]">
                                    {loc(tier.name, tier.ar_name)}
                                </p>
                                {tier.multiplier > 1 && (
                                    <p className="mt-0.5 text-xs text-slate-500">
                                        {loc(
                                            `${tier.multiplier}× bonus (${tier.tierWeeks} weeks each)`,
                                            `${tier.multiplier}× مكافأة (كل ${tier.tierWeeks} أسابيع)`,
                                        )}
                                    </p>
                                )}
                            </div>
                            <div className="shrink-0 text-end">
                                <CurrencyAmount
                                    currency={currency}
                                    amount={tier.total}
                                    activeCurrency={activeCurrency}
                                    className="text-lg font-bold text-emerald-700"
                                    iconClassName="h-4 w-4"
                                    variant="light"
                                />
                            </div>
                        </div>
                    ))}

                    {freeTiers.map((tier) => {
                        const icon = FREE_FOR_ICONS[tier.freeFor] || faGift;
                        return (
                            <div
                                key={`free-${tier.id}`}
                                className="flex items-center gap-3 rounded-2xl border border-sky-100 bg-gradient-to-r from-sky-50 to-white px-4 py-3.5"
                            >
                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0057B7] text-white shadow-sm">
                                    <FontAwesomeIcon icon={icon} className="h-5 w-5" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className="text-xs font-semibold uppercase tracking-wide text-[#0057B7]">
                                        {t("pages.institute_details.pioneersPopup.freeLabel", "Completely free")}
                                    </p>
                                    <p className="truncate text-sm font-semibold text-[#102233]">
                                        {getFreeForLabel(tier.freeFor, t)}
                                    </p>
                                    <p className="mt-0.5 truncate text-xs text-slate-500">
                                        {loc(tier.name, tier.ar_name)}
                                    </p>
                                </div>
                                <span className="shrink-0 rounded-full bg-[#0057B7]/10 px-3 py-1 text-xs font-bold text-[#0057B7]">
                                    {t("pages.institute_details.pioneersPopup.freeBadge", "FREE")}
                                </span>
                            </div>
                        );
                    })}
                </div>

                {totalCash > 0 && (
                    <div className="mx-5 mb-4 flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                        <span className="text-sm font-medium text-slate-600">
                            {t("pages.institute_details.pioneersPopup.totalSaved", "Total cash savings")}
                        </span>
                        <CurrencyAmount
                            currency={currency}
                            amount={totalCash}
                            activeCurrency={activeCurrency}
                            className="text-base font-bold text-[#102233]"
                            iconClassName="h-4 w-4"
                            variant="light"
                        />
                    </div>
                )}

                <div className="border-t border-slate-100 px-5 py-4">
                    <button
                        type="button"
                        onClick={onClose}
                        className="w-full rounded-2xl bg-gradient-to-r from-[#0057B7] to-[#0284c7] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#0057B7]/25 transition hover:brightness-110"
                    >
                        {t("pages.institute_details.pioneersPopup.close", "Got it!")}
                    </button>
                </div>
            </div>
        </div>
    );
}
