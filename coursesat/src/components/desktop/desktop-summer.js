"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser } from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { CurrencyAmount, getCoursePrice } from "@/lib/format-currency";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import { SUMMER_CAMPS_LISTING_URL } from "@/lib/summer-camps";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28;
const AUTO_MS = 4000;

function ProgramCard({ program, style, currency = "SAR", isArabic = false, href, t }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "summer_camps";
  const inWishlist = isInWishlist(interactionType, program.id);
  const inCompare = isInCompare(interactionType, program.id);
  const priceFromValue = getCoursePrice(program, currency, "from");

  const ageText = String(program.ageRange ?? "").trim();
  const hasAgeUnit = /(year|years|سنة|سنوات|عام|أعوام)/i.test(ageText);
  const ageWithUnit = ageText
    ? hasAgeUnit
      ? ageText
      : `${ageText} ${isArabic ? "سنة" : "years"}`
    : "";

  const CardTag = href ? Link : "article";
  const cardProps = href ? { href } : {};

  return (
    <CardTag
      {...cardProps}
      className="shrink-0 rounded-[24px] bg-white overflow-hidden transition duration-300 hover:shadow-lg block"
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
        <h3 className="text-[18px] font-bold leading-snug text-slate-900">{program.title}</h3>

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
          <CurrencyAmount
            currency={currency}
            amount={priceFromValue}
            className="inline-flex items-center gap-1 font-bold text-slate-900"
            iconClassName="h-4 w-4"
          />
          <span className="text-slate-500 font-medium">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>
        </div>
      </div>
    </CardTag>
  );
}

export default function DesktopSummer() {
  const viewportRef = useRef(null);
  const { data } = useApi("/coursesat/home/summer");
  const { language, direction, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";

  const heading = t("pages.homepage.summer_programs.heading", "Summer Programs");
  const subheading = t(
    "pages.homepage.summer_programs.subheading",
    "Discover the summer programs available this summer"
  );
  const ctaText = t("pages.homepage.summer_programs.view_all", "All programs");
  const ctaUrl = SUMMER_CAMPS_LISTING_URL;

  const programs = useMemo(() => {
    const apiPrograms = data?.summer_camps ?? [];
    return apiPrograms.map((camp) => ({
      id: camp.id,
      title: isArabic ? camp.ar_title || camp.ar_name || camp.title || camp.name : camp.title || camp.name || camp.ar_title,
      slug: camp.slug,
      campSlug: camp.camp_slug,
      city: isArabic ? camp.city_ar_name || camp.city : camp.city || camp.city_ar_name,
      country: isArabic ? camp.country_ar_name || camp.country : camp.country || camp.country_ar_name,
      ageRange: camp.age_range,
      description: isArabic
        ? camp.ar_description || camp.description || ""
        : camp.description || camp.ar_description || "",
      prices: camp.prices,
      image: getImageUrl(camp.image) || "/assets/hero.png",
      url: camp.url,
    }));
  }, [data?.summer_camps, isArabic]);

  const [cardW, setCardW] = useState(320);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);

  const len = programs.length;

  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;
      if (w >= 1536) setVisible(4);
      else if (w >= 1280) setVisible(3);
      else setVisible(2);
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
      setCardW(Math.max(260, Math.floor((w - totalGap) / visible)));
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible]);

  const maxIndex = Math.max(0, len - visible);

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

  const dir = -1;
  const trackStyle = {
    gap: `${GAP}px`,
    width: `${len * cardW + (len - 1) * GAP}px`,
    transform: `translateX(${dir * Math.min(index, maxIndex) * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = {
    width: `${cardW}px`,
    flex: `0 0 ${cardW}px`,
  };

  if (len === 0) return null;

  return (
    <section id="summer-programs" className="hidden md:block py-16 sm:py-20 w-full bg-white" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">
        <div className="text-center">
          <h2 className="py-4 text-3xl font-bold text-slate-900 sm:text-4xl">{heading}</h2>
          {subheading ? (
            <p className="mt-2 text-lg text-slate-500 max-w-2xl mx-auto">{subheading}</p>
          ) : null}
        </div>

        <div className="relative mt-10 px-4 md:px-12">
          {len > visible ? (
            <>
              <button
                type="button"
                onClick={prev}
                aria-label="Previous programs"
                className="absolute -left-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full border border-slate-200 bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 transition"
              >
                <Image src="/assets/icons/arrow-left.svg" alt="Previous" width={16} height={16} className="h-4 w-4" />
              </button>
              <button
                type="button"
                onClick={next}
                aria-label="Next programs"
                className="absolute -right-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full p-4 text-white shadow-md hover:brightness-110 transition"
                style={{ background: TOKENS.primary, boxShadow: TOKENS.primaryShadow }}
              >
                <Image src="/assets/icons/arrow-right-white.svg" alt="Next" width={16} height={16} className="h-4 w-4" />
              </button>
            </>
          ) : null}

          <div ref={viewportRef} className="overflow-hidden m-4" dir="ltr">
            <div className="flex" style={trackStyle}>
              {programs.map((program) => (
                <ProgramCard
                  key={program.id}
                  program={program}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                  href={SUMMER_CAMPS_LISTING_URL}
                  t={t}
                />
              ))}
            </div>
          </div>
        </div>

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
