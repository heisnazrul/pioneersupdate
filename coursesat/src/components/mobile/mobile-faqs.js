"use client";

import { useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronDown,
  faChevronUp,
  faArrowRight,
  faArrowDown,
  faArrowUp
} from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { normalizeFaqs, normalizeFaqCategories, resolveFaqCategory } from "@/lib/faq-content";

const BLUE = "#0072bc";            // section background
const BLUE_SOFT = "rgba(255,255,255,.10)"; // card bg over blue
const WHITE_80 = "rgba(255,255,255,.85)";
const RING = "rgba(255,255,255,.18)";
const SHADOW = "0 6px 18px rgba(0,0,0,.10)";

function Pill({ active, children, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`h-9 rounded-full px-4 text-xs whitespace-nowrap shrink-0 transition ${
        active ? "bg-white text-[#0072bc]" : "text-white"
      }`}
      style={{
        border: "none",
        background: active ? "white" : "rgba(255,255,255,.12)",
        boxShadow: active ? SHADOW : "none",
        color: active ? BLUE : "white"
      }}
    >
      {children}
    </button>
  );
}

function QAItem({ item, open, onToggle }) {
  return (
    <div className="rounded-2xl p-0.5">
      <button
        type="button"
        onClick={onToggle}
        aria-expanded={open}
        className="flex w-full items-center justify-between rounded-xl px-4 py-3 text-white text-start"
        style={{
          background: BLUE_SOFT,
          border: "none",
          boxShadow: "none"
        }}
      >
        <span className="text-sm font-medium text-start max-w-[85%] leading-snug">{item.q}</span>
        <FontAwesomeIcon icon={open ? faArrowUp : faArrowDown} className="text-white shrink-0 text-sm" />
      </button>

      {open && (
        <div
          role="region"
          className="mt-1 px-4 py-3 text-[14px] leading-6 text-start animate-fade-in"
          style={{ background: "transparent", color: "white", border: "none" }}
        >
          {item.a}
        </div>
      )}
    </div>
  );
}

export default function MobileFaqs() {
  const { data: faqsData } = useApi("/courseenglish/faqs");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";

  const heading = t("pages.homepage.faq.heading", "أسئلة واجوبة");
  const subheading = t(
    "pages.homepage.faq.subheading",
    "نساعدك على اتخاذ القرار بثقة، هذه أبرز الأسئلة التي يطرحها طلابنا"
  );
  const ctaText = t("pages.homepage.faq.cta", "هل لا زال لديك سؤال ؟");
  const helpText = t("pages.homepage.faq.help", "نحن موجودين لمساعدتك");
  const showMoreLabel = t("pages.homepage.faq.see_more", "اظهار المزيد");
  const ctaUrl = "/contact-us";

  const rawFaqs = faqsData?.faqs;

  const faqs = useMemo(() => normalizeFaqs(rawFaqs, isArabic), [rawFaqs, isArabic]);

  const categories = useMemo(
    () => normalizeFaqCategories(faqsData?.categories, faqs, isArabic),
    [faqsData?.categories, faqs, isArabic],
  );

  const [activeCat, setActiveCat] = useState("");
  const [openId, setOpenId] = useState(null);
  const [visible, setVisible] = useState(3);

  const currentCat = useMemo(
    () => resolveFaqCategory(activeCat, categories, isArabic),
    [categories, activeCat, isArabic],
  );

  const list = useMemo(() => {
    return faqs.filter((f) => f.cat === currentCat);
  }, [faqs, currentCat]);

  const currentOpenId = useMemo(() => {
    if (openId !== null) return openId;
    return list[0]?.id ?? null;
  }, [list, openId]);

  const shown = useMemo(() => {
    return list.slice(0, visible);
  }, [list, visible]);

  const handleCategoryClick = (c) => {
    setActiveCat(c);
    setOpenId(null);
    setVisible(3);
  };

  if (faqs.length === 0) return null;

  const btnDirClass = isArabic ? "rotate-180" : "";

  return (
    <section className="block md:hidden py-10 w-full" style={{ background: BLUE }} dir={direction}>
      <div className="px-4 text-start">
        
        <h2 className="text-2xl font-bold text-white text-center">{heading}</h2>
        <p className="mt-2 text-white/80 text-[14px] leading-relaxed text-center">
          {subheading}
        </p>

        {categories.length ? (
          <div className="mt-6 flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            {categories.map((c) => (
              <Pill key={c} active={c === currentCat} onClick={() => handleCategoryClick(c)}>
                {c}
              </Pill>
            ))}
          </div>
        ) : null}

        <div className="space-y-3 mt-6">
          {shown.map((it) => (
            <QAItem
              key={it.id}
              item={it}
              open={currentOpenId === it.id}
              onToggle={() => setOpenId(currentOpenId === it.id ? -1 : it.id)}
            />
          ))}

          {visible < list.length && (
            <button
              type="button"
              onClick={() => setVisible((v) => v + 3)}
              className="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-2 py-4 text-[15px] font-semibold text-[#0F172A] hover:bg-slate-50 transition duration-200"
              style={{ boxShadow: SHADOW }}
            >
              <span>{showMoreLabel}</span>
              <FontAwesomeIcon icon={faArrowDown} className="text-sm" />
            </button>
          )}
        </div>

        <div className="mt-8 border-t border-white/10 pt-6 flex items-center justify-between">
          <div className="text-start">
            <h3 className="text-lg font-bold text-white leading-none">{ctaText}</h3>
            <p className="mt-1 text-white/80 text-[13px]">{helpText}</p>
          </div>

          <button
            type="button"
            className={`grid h-10 w-10 place-items-center rounded-full bg-white text-[#0072bc] transition duration-200 hover:scale-105 ${btnDirClass}`}
            aria-label="Contact support"
            title="Contact support"
            style={{ boxShadow: SHADOW }}
            onClick={() => {
              if (typeof window !== "undefined") {
                window.location.href = ctaUrl;
              }
            }}
          >
            <FontAwesomeIcon icon={faArrowRight} className="text-sm" />
          </button>
        </div>

      </div>
    </section>
  );
}
