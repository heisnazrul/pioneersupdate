"use client";

import { useMemo, useRef, useState, useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronDown,
  faChevronUp,
  faArrowRight
} from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

/* ---- Theme tokens (match/adjust as needed) ---- */
const BLUE = "#135AA3";            // section background
const BLUE_SOFT = "rgba(255,255,255,.14)"; // card bg over blue
const WHITE_80 = "rgba(255,255,255,.85)";
const RING = "rgba(255,255,255,.18)";
const SHADOW = "0 6px 18px rgba(0,0,0,.10)";

const CACHE_KEY = "cache_faqs";

/* ---- Hooks ---- */
function useClickOutside(ref, handler) {
  useEffect(() => {
    const fn = (e) => ref.current && !ref.current.contains(e.target) && handler?.();
    document.addEventListener("mousedown", fn);
    document.addEventListener("touchstart", fn);
    return () => { document.removeEventListener("mousedown", fn); document.removeEventListener("touchstart", fn); };
  }, [ref, handler]);
}

/* ---- Components ---- */
function Pill({ active, children, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`h-10 rounded-full px-5 text-sm transition ${active ? "bg-white text-[#135AA3]" : "text-white"
        }`}
      style={{
        border: active ? "1px solid rgba(19,90,163,.08)" : `1px solid ${RING}`,
        background: active ? "white" : "transparent",
        boxShadow: active ? SHADOW : "none"
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
        className="flex w-full items-center justify-between rounded-2xl px-5 py-4 text-white"
        style={{
          background: BLUE_SOFT,
          border: `1px solid ${RING}`,
          boxShadow: SHADOW
        }}
      >
        <span className="text-base md:text-[17px] font-normal">{item.q}</span>
        <FontAwesomeIcon icon={open ? faChevronUp : faChevronDown} className="text-white/90" />
      </button>

      {open && (
        <div
          role="region"
          className="mt-2 rounded-2xl px-5 py-4 text-[15px] leading-7"
          style={{ background: "rgba(255,255,255,.10)", color: WHITE_80, border: `1px solid ${RING}` }}
        >
          {item.a}
        </div>
      )}
    </div>
  );
}

/* ---- Main section (English + LTR) ---- */
export default function FAQSectionEN() {
  const [faqs, setFaqs] = useState([]);
  const [categories, setCategories] = useState([]);
  const [cat, setCat] = useState("");
  const [openId, setOpenId] = useState(null);
  const [visible, setVisible] = useState(3);
  const [loaded, setLoaded] = useState(false);
  const { data: faqsData } = useApi("/courseenglish/faqs");
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const faqMeta = getCourseEnglishMessages(language)?.pages?.homepage?.faq ?? {};
  const heading = faqMeta?.heading || (isArabic ? "الأسئلة والأجوبة" : "Questions & Answers");
  const subheading =
    faqMeta?.subheading ||
    (isArabic
      ? "نساعدك على اتخاذ القرار بثقة. هذه أكثر الأسئلة شيوعًا التي يطرحها الطلاب."
      : "We help you decide with confidence. These are the most common questions prospective students ask us.");
  const ctaText = faqMeta?.cta || (isArabic ? "هل لديك سؤال؟" : "Still have a question?");
  const ctaUrl = "/contact-us";
  const helpText = faqMeta?.help || (isArabic ? "نحن هنا لمساعدتك" : "We're here to help you.");
  const showMoreLabel = faqMeta?.see_more || (isArabic ? "عرض المزيد" : "Show more");

  useEffect(() => {
    const normalize = (payload) =>
      payload.map((item, idx) => ({
        id: item.id ?? idx,
        cat: isArabic ? item.ar_category || item.category || "General" : item.category || item.ar_category || "General",
        q: isArabic ? item.ar_question || item.question || "" : item.question || item.ar_question || "",
        a: isArabic ? item.ar_answer || item.answer || "" : item.answer || item.ar_answer || "",
      }));

    const load = async () => {
      const { default: mockData } = await import("@/data/mocks/faqs.json");
      const normalized = normalize(mockData);
      setFaqs(normalized);

      if (normalized && normalized.length) {
        const cats = Array.from(new Set(normalized.map((f) => f.cat)));
        setCategories(cats);
        setCat(cats[0]);
        setOpenId(normalized[0].id);
      }
      setLoaded(true);
    };

    if (faqsData?.faqs?.length) {
      const normalized = normalize(faqsData.faqs);
      setFaqs(normalized);
      if (normalized && normalized.length) {
        const cats = Array.from(new Set(normalized.map((f) => f.cat)));
        setCategories(cats);
        setCat(cats[0]);
        setOpenId(normalized[0].id);
      }
      setLoaded(true);
      return;
    }

    load();
  }, [faqsData, isArabic]);

  const list = useMemo(() => faqs.filter((f) => f.cat === cat), [faqs, cat]);

  useEffect(() => {
    setVisible(3);
    if (list.length) setOpenId(list[0].id);
  }, [cat, list]);

  const shown = list.slice(0, visible);

  if (loaded && !faqs.length) return null;

  return (
    <section className="py-14 md:py-20" style={{ background: BLUE }}>
      <div className="px-4 md:px-10 xl:px-30 2xl:px-50 grid grid-cols-1 gap-10 px-4 md:grid-cols-2">
        {/* LEFT: Heading + filters + CTA */}
        <div className="order-1 md:order-none">
          <h2 className="text-4xl font-extrabold text-white md:text-5xl">{heading}</h2>
          <p className="mt-4 max-w-xl text-white/80">
            {subheading}
          </p>

          {categories.length ? (
            <div className="mt-6 flex flex-wrap gap-2 md:gap-3">
              {categories.map((c) => (
                <Pill key={c} active={c === cat} onClick={() => setCat(c)}>
                  {c}
                </Pill>
              ))}
            </div>
          ) : null}

          <div className="mt-6 md:mt-16">
            <h3 className="text-2xl font-medium text-white">{ctaText}</h3>
            <p className="mt-1 text-white/80">{helpText}</p>

            <button
              type="button"
              className="mt-5 grid h-12 w-12 place-items-center rounded-full bg-white text-[#135AA3] transition hover:opacity-90"
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

        {/* RIGHT: Accordion */}
        <div className="space-y-4">
          {shown.map((it) => (
            <QAItem
              key={it.id}
              item={it}
              open={openId === it.id}
              onToggle={() => setOpenId(openId === it.id ? -1 : it.id)}
            />
          ))}

          {visible < list.length && (
            <button
              type="button"
              onClick={() => setVisible(v => v + 3)}
              className="mt-2 w-full rounded-2xl bg-white px-2 py-4 text-center text-[15px] font-normal text-[#0F172A] transition hover:opacity-95"
              style={{ boxShadow: SHADOW }}
            >
              {showMoreLabel}
            </button>
          )}
        </div>
      </div>
    </section>
  );
}
