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
      className={`h-12 rounded-full px-5 text-sm transition ${active ? "bg-white text-[#135AA3]" : "text-white"
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
    <div className="rounded-2xl p-0.5" style={{ background: "transparent" }}>
      <button
        type="button"
        onClick={onToggle}
        aria-expanded={open}
        className="flex w-full items-center justify-between rounded-2xl px-5 py-4 text-white hover:brightness-105 transition duration-200"
        style={{
          background: BLUE_SOFT,
          border: "none",
          boxShadow: "none"
        }}
      >
        <span className="text-base md:text-[17px] font-medium text-start">{item.q}</span>
        <FontAwesomeIcon icon={open ? faArrowUp : faArrowDown} className="text-white shrink-0" />
      </button>

      {open && (
        <div
          role="region"
          className="mt-1 px-5 py-4 text-[15px] leading-7 text-start animate-fade-in"
          style={{ background: "transparent", color: "white", border: "none" }}
        >
          {item.a}
        </div>
      )}
    </div>
  );
}

export default function DesktopFaqs() {
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

  // Derived list
  const list = useMemo(() => {
    return faqs.filter((f) => f.cat === currentCat);
  }, [faqs, currentCat]);

  // Derived first open item
  const currentOpenId = useMemo(() => {
    if (openId !== null) return openId;
    return list[0]?.id ?? null;
  }, [list, openId]);

  const shown = useMemo(() => {
    return list.slice(0, visible);
  }, [list, visible]);

  const handleCategoryClick = (c) => {
    setActiveCat(c);
    setOpenId(null); // Reset manually toggled open state
    setVisible(3); // Reset visible count
  };

  if (faqs.length === 0) return null;

  const btnDirClass = isArabic ? "rotate-180" : "";

  return (
    <section className="hidden md:block py-16 sm:py-20 w-full" style={{ background: BLUE }} dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto grid grid-cols-1 gap-12 md:grid-cols-2">

        {/* Left column */}
        <div className="flex flex-col text-start justify-between">
          <div>
            <h2 className="text-3xl font-bold text-white md:text-5xl">{heading}</h2>
            <p className="mt-4 max-w-lg xl:max-w-xl text-white/80 leading-relaxed text-[18px]">
              {subheading}
            </p>

            {categories.length ? (
              <div className="mt-8 flex flex-wrap gap-3">
                {categories.map((c) => (
                  <Pill key={c} active={c === currentCat} onClick={() => handleCategoryClick(c)}>
                    {c}
                  </Pill>
                ))}
              </div>
            ) : null}
          </div>

          <div className="mt-12 md:mt-16 pt-8">
            <h3 className="text-2xl font-bold text-white">{ctaText}</h3>
            <p className="mt-1 text-white/80 text-[15px]">{helpText}</p>

            <button
              type="button"
              className={`mt-6 grid h-12 w-12 place-items-center rounded-full bg-white text-[#0072bc] transition duration-200 hover:scale-105 hover:bg-slate-50 ${btnDirClass}`}
              aria-label="Contact support"
              title="Contact support"
              style={{ boxShadow: SHADOW }}
              onClick={() => {
                if (typeof window !== "undefined") {
                  window.location.href = ctaUrl;
                }
              }}
            >
              <FontAwesomeIcon icon={faArrowRight} />
            </button>
          </div>
        </div>

        {/* Right column */}
        <div className="space-y-4">
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
              className="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-2 py-4 text-[15px] font-semibold text-[#0F172A] hover:bg-slate-50 transition duration-200"
              style={{ boxShadow: SHADOW }}
            >
              <span>{showMoreLabel}</span>
              <FontAwesomeIcon icon={faArrowDown} className="text-sm" />
            </button>
          )}
        </div>

      </div>
    </section>
  );
}
