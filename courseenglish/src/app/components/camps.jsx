"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser } from "@fortawesome/free-solid-svg-icons";
import { faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { formatCurrency, useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";
import { pickLang } from "@/lib/i18nFallback";
/* ---------- Dummy data ---------- */
const PROGRAMS = [
  {
    id: 1,
    title: "English Adventure on Brighton Seafront",
    city: "Brighton",
    country: "United Kingdom",
    ageRange: "15–17 years",
    description:
      "Enjoy a unique experience learning English in one of the UK’s liveliest seaside towns.",
    priceFrom: "£300",
    image:
      "https://images.pexels.com/photos/414999/pexels-photo-414999.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 2,
    title: "London Iconic Landmarks Summer Course",
    city: "London",
    country: "United Kingdom",
    ageRange: "14–18 years",
    description:
      "Discover London’s most famous sights while improving your English with new friends.",
    priceFrom: "£320",
    image:
      "https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 3,
    title: "Coastal English Program in Brighton",
    city: "Brighton",
    country: "United Kingdom",
    ageRange: "13–16 years",
    description:
      "Morning lessons and afternoon beach activities in a safe, supervised environment.",
    priceFrom: "£295",
    image:
      "https://images.pexels.com/photos/258196/pexels-photo-258196.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 4,
    title: "Cambridge University Experience",
    city: "Cambridge",
    country: "United Kingdom",
    ageRange: "16–18 years",
    description:
      "Stay in college-style accommodation and explore the historic city of Cambridge.",
    priceFrom: "£350",
    image:
      "https://images.pexels.com/photos/221524/pexels-photo-221524.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 5,
    title: "Oxford Academic English Summer",
    city: "Oxford",
    country: "United Kingdom",
    ageRange: "15–18 years",
    description:
      "Improve your academic English in the home of one of the world’s top universities.",
    priceFrom: "£340",
    image:
      "https://images.pexels.com/photos/415980/pexels-photo-415980.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
];

const PROGRAMS_AR = [
  {
    id: 1,
    title: "مغامرة الإنجليزية على شاطئ برايتون",
    city: "برايتون",
    country: "المملكة المتحدة",
    ageRange: "15-17",
    description: "تجربة مميزة لتعلم الإنجليزية في واحدة من أكثر المدن الساحلية حيوية في بريطانيا.",
    priceFrom: "﷼1,350",
    image:
      "https://images.pexels.com/photos/414999/pexels-photo-414999.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 2,
    title: "دورة صيفية في معالم لندن الشهيرة",
    city: "لندن",
    country: "المملكة المتحدة",
    ageRange: "14-18",
    description: "اكتشف أشهر معالم لندن أثناء تطوير لغتك الإنجليزية مع زملاء جدد.",
    priceFrom: "﷼1,440",
    image:
      "https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 3,
    title: "برنامج ساحلي للغة الإنجليزية في برايتون",
    city: "برايتون",
    country: "المملكة المتحدة",
    ageRange: "13-16",
    description: "دروس صباحية وأنشطة شاطئية بعد الظهر في بيئة آمنة وتحت إشراف.",
    priceFrom: "﷼1,300",
    image:
      "https://images.pexels.com/photos/258196/pexels-photo-258196.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 4,
    title: "تجربة جامعة كامبريدج",
    city: "كامبريدج",
    country: "المملكة المتحدة",
    ageRange: "16-18",
    description: "إقامة بأسلوب الجامعات واستكشاف المدينة التاريخية كامبريدج.",
    priceFrom: "﷼1,540",
    image:
      "https://images.pexels.com/photos/221524/pexels-photo-221524.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 5,
    title: "صيف أكاديمي في أكسفورد",
    city: "أكسفورد",
    country: "المملكة المتحدة",
    ageRange: "15-18",
    description: "طوّر لغتك الأكاديمية في موطن إحدى أعرق الجامعات بالعالم.",
    priceFrom: "﷼1,500",
    image:
      "https://images.pexels.com/photos/415980/pexels-photo-415980.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
];

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28; // px
const AUTO_MS = 4000;

/* ---------- Card ---------- */
function ProgramCard({ program, style, currency = "SAR", isArabic = false }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "summer_camps";
  const inWishlist = isInWishlist(interactionType, program.id);
  const inCompare = isInCompare(interactionType, program.id);
  const currencyIcon = "/assets/sar.svg";
  const currencySymbol = currency === "GBP" ? "£" : null;
  const priceText = String(program.priceFrom ?? "")
    .replace(/^(SAR|GBP)\s*/i, "")
    .replace(/^£\s*/i, "")
    .trim();

  const ageText = String(program.ageRange ?? "").trim();
  const hasAgeUnit = /(year|years|سنة|سنوات|عام|أعوام)/i.test(ageText);
  const ageWithUnit = ageText
    ? hasAgeUnit
      ? ageText
      : `${ageText} ${isArabic ? "سنة" : "years"}`
    : "";
  return (
    <article
      className="shrink-0 rounded-[24px] bg-white"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      {/* Image with fixed ratio & overlays */}
      <div className="relative m-4 rounded-[20px] bg-[#E7F0FB]">
        {/* ratio box: 4:3 */}
        <div className="relative w-full pb-[75%] overflow-hidden rounded-[20px]">
          <img
            src={program.image}
            alt={program.title}
            className="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
          />
        </div>

        {/* Heart icon */}
        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, program.id);
          }}
          className="absolute left-4 top-4 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg"}
            alt="Wishlist"
            width={18}
            height={18}
            className="h-[18px] w-[18px]"
          />
        </button>
        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleCompare(interactionType, program.id);
          }}
          className="absolute left-4 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={18}
            height={18}
            className="h-[18px] w-[18px]"
          />
        </button>
      </div>

      {/* Content */}
      <div className="px-6 pb-5">
        {/* Title */}
        <h3 className="text-[18px] font-extrabold leading-snug text-slate-900">
          {program.title}
        </h3>

        {/* Location */}
        <div className="mt-2 flex items-center gap-2 text-[13px] text-slate-600">
          <FontAwesomeIcon icon={faLocationDot} className="text-[#1F63AE]" />
          <span>
            {program.city}, {program.country}
          </span>
        </div>

        {/* Age range */}
        <div className="mt-1 flex items-center gap-2 text-[13px] text-slate-600">
          <FontAwesomeIcon icon={faUser} className="text-[#1F63AE]" />
          <span>{ageWithUnit}</span>
        </div>

        {/* Description */}
        <p className="mt-3 line-clamp-2 text-[14px] leading-6 text-slate-500">
          {program.description}
        </p>

        {/* Price */}
        <div className="mt-4 text-[14px] text-slate-700">
          <span className="text-slate-600">{isArabic ? "ابتداءً من " : "From "}</span>
          <span className="inline-flex items-center gap-1 font-medium text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img src={currencyIcon} alt={currency} className="h-4 w-4 invert" />
            )}
            <span>{priceText}</span>
          </span>
          <span className="text-slate-600">{isArabic ? " / أسبوع" : " / week"}</span>
        </div>
      </div>
    </article>
  );
}

/* ---------- Slider ---------- */
export default function SummerProgramsSlider({ programs: fallbackPrograms = PROGRAMS }) {
  const viewportRef = useRef(null);
  const { data } = useApi("/courseenglish/home/summer");
  const { currency, language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const localizedFallbackPrograms = isArabic ? PROGRAMS_AR : fallbackPrograms;
  const section = getCourseEnglishMessages(language)?.pages?.homepage?.summer_programs ?? {};
  const heading = section?.heading || pickLang(language, "Summer Programs", "المخيمات الصيفية");
  const ctaText = section?.view_all || pickLang(language, "View all programs", "عرض كل البرامج");
  const ctaUrl = "/summer-programs";
  const programs = useMemo(() => {
    const apiPrograms = data?.summer_camps ?? [];
    if (!apiPrograms.length) return localizedFallbackPrograms;
    return apiPrograms.map((camp) => ({
      id: camp.id,
      title: isArabic ? camp.ar_title || camp.ar_name || camp.title || camp.name : camp.title || camp.name || camp.ar_title,
      city: isArabic ? camp.city_ar_name || camp.city : camp.city || camp.city_ar_name,
      country: isArabic ? camp.country_ar_name || camp.country : camp.country || camp.country_ar_name,
      ageRange: camp.age_range,
      description: isArabic
        ? camp.ar_description || camp.description || ""
        : camp.description || camp.ar_description || "",
      priceFrom: formatCurrency(
        currency === "SAR"
          ? camp.price_from_sar ?? camp.price_from_gbp ?? camp.price_from
          : camp.price_from_gbp ?? camp.price_from_sar ?? camp.price_from,
        currency
      ),
      image: camp.image || "/assets/hero.png",
    }));
  }, [data, fallbackPrograms, currency, isArabic]);
  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);

  // responsive: how many cards visible per breakpoint
  const [visible, setVisible] = useState(3);
  // mobile peek: how much of the next card is visible
  const [peek, setPeek] = useState(0);

  const len = programs.length;

  // breakpoint logic: 2xl=4, xl=3, md=2, mobile=1 + peek
  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;

      if (w >= 1536) {
        // 2xl
        setVisible(4);
        setPeek(0);
      } else if (w >= 1280) {
        // xl
        setVisible(3);
        setPeek(0);
      } else if (w >= 768) {
        // md–lg
        setVisible(2);
        setPeek(0);
      } else {
        // mobile
        setVisible(1);
        setPeek(0.15); // ~15% of next card
      }
    };

    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  // Compute card width based on viewport + visible/peek
  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      let width;

      if (visible === 1 && peek > 0) {
        // mobile: 1 full + peek of next card
        width = Math.floor(w * (1 - peek));
      } else {
        const totalGap = GAP * (visible - 1);
        width = Math.max(
          260,
          Math.floor((w - totalGap) / visible)
        );
      }

      setCardW(width);
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible, peek]);

  const maxIndex = Math.max(0, len - visible);

  // reset index when layout/data changes
  useEffect(() => {
    setIndex(0);
  }, [visible, len]);

  // Autoplay
  useEffect(() => {
    if (len <= visible) return;
    const id = setInterval(() => {
      setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
    }, AUTO_MS);
    return () => clearInterval(id);
  }, [len, visible, maxIndex]);

  const prev = () => {
    if (len <= visible) return;
    setIndex((i) => (i - 1 < 0 ? maxIndex : i - 1));
  };

  const next = () => {
    if (len <= visible) return;
    setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
  };

  const dir = isArabic ? 1 : -1;
  const trackStyle = {
    gap: `${GAP}px`,
    width: cardW ? `${len * cardW + (len - 1) * GAP}px` : "auto",
    transform: `translateX(${dir * index * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = {
    width: `${cardW}px`,
    flex: `0 0 ${cardW}px`,
  };

  return (
    <section className="py-16 sm:py-20">
      <div className="px-2 md:px-20 xl:px-20 2xl:px-40">
        {/* Heading */}
                <div className="hidden md:block text-center">
                  <h2 className="py-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                    {heading}
                  </h2>
                </div>
                <div className="md:hidden my-4 flex items-center justify-between md:hidden">
                  <div className="flex px-4 text-center">
                    <h2 className="text-2xl font-extrabold leading-snug text-slate-900">
                      {heading}
                    </h2>
                  </div>
                  <div className="flex items-center text-md font-normal text-[#1F63AE]">
                    <Link href={ctaUrl} className="pb-1">
                      {ctaText}
                    </Link>
                    <div className="text-lg">
                      <FontAwesomeIcon icon={faChevronRight} />
                    </div>
                  </div>
                </div>

        {/* Slider */}
        <div className="relative mt-10 sm:mt-10 mx-2 md:mx-6 lg:mx-20">
          {len > visible && (
            <>
              {/* Left arrow (md+) */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous programs"
                className="absolute -left-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full border bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 md:grid"
                style={{ borderColor: TOKENS.border }}
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>

              {/* Right arrow (md+) */}
              <button
                type="button"
                onClick={next}
                aria-label="Next programs"
                className="absolute -right-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full px-4 py-4 text-white md:grid"
                style={{
                  background: TOKENS.primary,
                  boxShadow: TOKENS.primaryShadow,
                }}
              >
                <Image
                  src="/assets/icons/arrow-right-white.svg"
                  alt="Next"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>
            </>
          )}

          <div ref={viewportRef} className="overflow-hidden mt-4">
            <div className="flex" style={trackStyle}>
              {programs.map((program) => (
                <ProgramCard
                  key={program.id}
                  program={program}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                />
              ))}
            </div>
          </div>
        </div>

        {/* All programs button */}
        <div className="mt-10 flex justify-center">
          <Link
            href={ctaUrl}
            className="hidden md:block rounded-full bg-[#1F63AE] px-8 py-2.5 text-sm font-normal text-white shadow-md hover:bg-[#175093]"
          >
            {ctaText}
          </Link>
        </div>
      </div>
    </section>
  );
}
