"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28; // px
const AUTO_MS = 4000;

/* ---------- Card ---------- */
function ProgramCard({ program, style, currency = "SAR", isArabic = false, t }) {
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
      className="shrink-0 rounded-[24px] bg-white overflow-hidden transition duration-300 hover:shadow-lg"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      <div className="relative m-4 rounded-[20px] bg-[#E7F0FB]">
        <div className="relative w-full pb-[75%] overflow-hidden rounded-[20px]">
          <img
            src={program.image}
            alt={program.title}
            className="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
          />
        </div>

        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, program.id);
          }}
          className="absolute left-4 top-4 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:scale-110"
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
          className="absolute left-4 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:scale-110"
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

      <div className="px-6 pb-5 text-start">
        <h3 className="text-[18px] font-bold leading-snug text-slate-900">
          {program.title}
        </h3>

        <div className="mt-2 flex items-center gap-2 text-[13px] text-slate-600">
          <FontAwesomeIcon icon={faLocationDot} className="text-[#1F63AE]" />
          <span>
            {program.city}, {program.country}
          </span>
        </div>

        <div className="mt-1 flex items-center gap-2 text-[13px] text-slate-600">
          <FontAwesomeIcon icon={faUser} className="text-[#1F63AE]" />
          <span>{ageWithUnit}</span>
        </div>

        <p className="mt-3 line-clamp-2 text-[14px] leading-6 text-slate-500 min-h-[48px]">
          {program.description}
        </p>

        <div className="mt-4 text-[14px] text-slate-700 flex items-center gap-1">
          <span className="text-slate-500 font-medium">{isArabic ? "ابتداءً من " : "From "}</span>
          <span className="inline-flex items-center gap-1 font-bold text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img src={currencyIcon} alt={currency} className="h-4 w-4 invert" />
            )}
            <span>{priceText}</span>
          </span>
          <span className="text-slate-500 font-medium">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>
        </div>
      </div>
    </article>
  );
}

/* ---------- Main Component ---------- */
export default function DesktopSummer() {
  const viewportRef = useRef(null);
  const { data } = useApi("/courseenglish/home/summer");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const currency = "SAR";

  const heading = t("pages.homepage.summer_programs.heading", "Summer Programs");
  const subheading = t("pages.homepage.summer_programs.subheading", "Discover the summer programs available this summer");
  const ctaText = t("pages.homepage.summer_programs.view_all", "All programs");
  const ctaUrl = "/summer-programs";

  const offersCamps = data?.summer_camps;

  const dummyCamps = [
    {
      id: 1,
      title: "English Adventure on Brighton Seafront",
      ar_title: "مخيم صيفي في برايتون",
      city: "Brighton",
      city_ar_name: "برايتون",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      ageRange: "15–17 years",
      description: "Enjoy a unique experience learning English on the beautiful Brighton seafront.",
      ar_description: "استمتع بتجربة فريدة لتعلم اللغة الإنجليزية على الواجهة البحرية الجميلة.",
      priceFrom: "£500",
      image: "https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 2,
      title: "London City Explorer Camp",
      ar_title: "مخيم مستكشف مدينة لندن",
      city: "London",
      city_ar_name: "لندن",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      ageRange: "12-16 years",
      description: "Discover famous landmarks while improving your English skills.",
      ar_description: "اكتشف المعالم الشهيرة أثناء تحسين مهاراتك في اللغة الإنجليزية.",
      priceFrom: "£950",
      image: "https://images.pexels.com/photos/1181605/pexels-photo-1181605.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 3,
      title: "California Surf & Study",
      ar_title: "دراسة وتزلج على الأمواج في كاليفورنيا",
      city: "Santa Monica",
      city_ar_name: "سانتا مونيكا",
      country: "USA",
      country_ar_name: "الولايات المتحدة",
      ageRange: "14-18 years",
      description: "Combine English lessons with professional surf coaching.",
      ar_description: "اجمع بين دروس اللغة الإنجليزية وتدريب ركوب الأمواج المحترف.",
      priceFrom: "$2200",
      image: "https://images.pexels.com/photos/1181605/pexels-photo-1181605.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 4,
      title: "Oxford Academic Summer",
      ar_title: "الصيف الأكاديمي في أكسفورد",
      city: "Oxford",
      city_ar_name: "أكسفورد",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      ageRange: "16-19 years",
      description: "Experience student life at one of the world's most prestigious universities.",
      ar_description: "جرب حياة الطالب في واحدة من أرقى الجامعات في العالم.",
      priceFrom: "£2800",
      image: "https://images.pexels.com/photos/1181397/pexels-photo-1181397.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 5,
      title: "Toronto Tech Camp",
      ar_title: "مخيم تورونتو التقني",
      city: "Toronto",
      city_ar_name: "تورونتو",
      country: "Canada",
      country_ar_name: "كندا",
      ageRange: "13-17 years",
      description: "Learn coding and robotics alongside English classes.",
      ar_description: "تعلم البرمجة والروبوتات إلى جانب دروس اللغة الإنجليزية.",
      priceFrom: "C$1800",
      image: "https://images.pexels.com/photos/4145190/pexels-photo-4145190.jpeg?auto=compress&cs=tinysrgb&w=800",
    }
  ];

  const programs = useMemo(() => {
    const apiPrograms = offersCamps && offersCamps.length > 0 ? offersCamps : dummyCamps;
    return apiPrograms.map((camp) => ({
      id: camp.id,
      title: isArabic ? camp.ar_title || camp.ar_name || camp.title || camp.name : camp.title || camp.name || camp.ar_title,
      city: isArabic ? camp.city_ar_name || camp.city : camp.city || camp.city_ar_name,
      country: isArabic ? camp.country_ar_name || camp.country : camp.country || camp.country_ar_name,
      ageRange: camp.ageRange || camp.age_range,
      description: isArabic
        ? camp.ar_description || camp.description || ""
        : camp.description || camp.ar_description || "",
      priceFrom: camp.priceFrom || camp.price_from || "",
      image: camp.image || "/assets/hero.png",
    }));
  }, [offersCamps, isArabic]);

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);

  const len = programs.length;

  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;
      if (w >= 1536) {
        setVisible(4);
      } else if (w >= 1280) {
        setVisible(3);
      } else {
        setVisible(2);
      }
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      const totalGap = GAP * (visible - 1);
      const width = Math.max(260, Math.floor((w - totalGap) / visible));
      setCardW(width);
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible]);

  const maxIndex = Math.max(0, len - visible);

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
    transform: `translateX(${dir * Math.min(index, maxIndex) * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = {
    width: `${cardW}px`,
    flex: `0 0 ${cardW}px`,
  };

  if (len === 0) return null;

  return (
    <section className="hidden md:block py-16 sm:py-20 w-full bg-white" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">

        {/* Title */}
        <div className="text-center">
          <h2 className="py-4 text-3xl font-bold text-slate-900 sm:text-4xl">
            {heading}
          </h2>
          {subheading && (
            <p className="mt-2 text-lg text-slate-500 max-w-2xl mx-auto">
              {subheading}
            </p>
          )}
        </div>

        {/* Slider Area */}
        <div className="relative mt-10 px-4 md:px-12">
          {len > visible && (
            <>
              {/* Prev Button */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous programs"
                className="absolute -left-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full border border-slate-200 bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 transition"
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>

              {/* Next Button */}
              <button
                type="button"
                onClick={next}
                aria-label="Next programs"
                className="absolute -right-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full p-4 text-white shadow-md hover:brightness-110 transition"
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

          {/* Cards Track */}
          <div ref={viewportRef} className="overflow-hidden m-4">
            <div className="flex" style={trackStyle}>
              {programs.map((program) => (
                <ProgramCard
                  key={program.id}
                  program={program}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                  t={t}
                />
              ))}
            </div>
          </div>
        </div>

        {/* All programs button */}
        <div className="mt-12 flex justify-center">
          <Link
            href={ctaUrl}
            className="rounded-[8px] bg-[#1F63AE] px-8 py-3.5 text-sm font-bold !text-white shadow-md hover:bg-[#175093] transition-all"
          >
            {ctaText}
          </Link>
        </div>

      </div>
    </section>
  );
}
