"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser, faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import { SUMMER_CAMPS_LISTING_URL } from "@/lib/summer-camps";

const TOKENS = { border: "#E4EDF8", primary: "#1F63AE" };

function ProgramCard({ program, currency = "SAR", isArabic = false, href, t }) {
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
      className="shrink-0 w-[82vw] snap-start rounded-[24px] bg-white overflow-hidden block"
      style={{ border: `1px solid ${TOKENS.border}` }}
    >
      <div className="relative m-3 rounded-[20px] bg-[#E7F0FB]">
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
          className="absolute left-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg"}
            alt="Wishlist"
            width={16}
            height={16}
            className="h-[16px] w-[16px]"
          />
        </button>
        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleCompare(interactionType, program.id);
          }}
          className="absolute left-3 top-13 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={16}
            height={16}
            className="h-[16px] w-[16px]"
          />
        </button>
      </div>

      <div className="px-4 pb-4 text-start">
        <h3 className="text-[16px] font-bold leading-snug text-slate-900 min-h-[48px] line-clamp-2">
          {program.title}
        </h3>

        <div className="mt-2 flex items-center gap-1.5 text-[12px] text-slate-500">
          <FontAwesomeIcon icon={faLocationDot} className="text-[#1F63AE]" />
          <span>
            {program.city}, {program.country}
          </span>
        </div>

        <div className="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500">
          <FontAwesomeIcon icon={faUser} className="text-[#1F63AE]" />
          <span>{ageWithUnit}</span>
        </div>

        <p className="mt-2 line-clamp-2 text-[13px] leading-relaxed text-slate-500 min-h-[40px]">
          {program.description}
        </p>

        <div className="mt-3.5 text-[13px] text-slate-700 flex items-center justify-start gap-1" dir="ltr">
          <span className="text-slate-500 font-medium">{isArabic ? "ابتداءً من " : "From "}</span>
          <CurrencyAmount
            currency={currency}
            amount={priceFromValue}
            className="inline-flex items-center gap-0.5 font-bold text-slate-900"
            iconClassName="h-3.5 w-3.5"
          />
          <span className="text-slate-500 font-medium">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>
        </div>
      </div>
    </CardTag>
  );
}

export default function MobileSummer() {
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

  if (programs.length === 0) return null;

  return (
    <section id="summer-programs" className="block md:hidden bg-white py-10 w-full" dir={direction}>
      <div className="flex flex-col px-4">
        <div className="flex items-center justify-between">
          <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[55%] text-start">{heading}</h2>
          <Link
            href={ctaUrl}
            className="flex items-center gap-1 rounded-[8px] bg-[#1F63AE] px-4 py-2 text-sm font-bold !text-white hover:brightness-110 transition-all"
          >
            <span className="pb-0.5">{ctaText}</span>
            <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
          </Link>
        </div>
        {subheading ? <p className="mt-2 text-sm text-slate-500 text-start">{subheading}</p> : null}
      </div>

      <div className="mt-6">
        <div className="flex gap-4 overflow-x-auto px-4 snap-x snap-mandatory scrollbar-hide py-2">
          {programs.map((program) => (
            <ProgramCard
              key={program.id}
              program={program}
              currency={currency}
              isArabic={isArabic}
              href={SUMMER_CAMPS_LISTING_URL}
              t={t}
            />
          ))}
          <div className="shrink-0 w-4 snap-none" />
        </div>
      </div>
    </section>
  );
}
