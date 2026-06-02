"use client";

import { useLocale } from "@/components/providers/locale-provider";

export default function MobileBestPriceGuaranteeSheet({ isOpen, onClose }) {
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  const page = t("pages.language_institutes", {});
  const cardCopy = page?.mobile?.card ?? {};

  if (!isOpen) return null;

  const title =
    cardCopy.best_price_hint_title ||
    (isArabic ? "ماذا يعني ضمان أفضل الأسعار؟" : "What does best price guarantee mean?");
  const body =
    cardCopy.best_price_hint_body ||
    (isArabic
      ? "نلتزم بتقديم أفضل الأسعار المتاحة للدورات والمعاهد المعروضة على منصتنا. في حال وجدت نفس الدورة لدى نفس المعهد بسعر أقل على موقع آخر، سنقوم بمراجعته وتقديم أفضل سعر ممكن لك."
      : "We are committed to offering the best available prices for courses and institutes on our platform. If you find the same course at the same institute for a lower price on another site, we will review it and offer you the best price possible.");

  return (
    <div className="fixed inset-0 z-[100]">
      <div className="absolute inset-0 bg-black/30" onClick={onClose} aria-hidden="true" />
      <div
        className="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white px-6 pb-10 pt-4 shadow-2xl"
        dir={isArabic ? "rtl" : "ltr"}
        role="dialog"
        aria-modal="true"
        aria-labelledby="best-price-guarantee-title"
      >
        <div className="mx-auto mb-4 h-1.5 w-14 rounded-full bg-slate-300" />

        <div className="mb-2 flex justify-start">
          <button
            type="button"
            className="text-2xl leading-none text-slate-700"
            onClick={onClose}
            aria-label={isArabic ? "إغلاق" : "Close"}
          >
            ×
          </button>
        </div>

        <h3
          id="best-price-guarantee-title"
          className="px-2 text-center text-lg font-bold leading-snug text-slate-900"
        >
          {title}
        </h3>

        <p className="mt-5 text-center text-sm leading-7 text-slate-600">{body}</p>
      </div>
    </div>
  );
}
