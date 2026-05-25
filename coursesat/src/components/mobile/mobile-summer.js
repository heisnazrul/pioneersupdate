"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser, faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
};

/* ---------- Card Component ---------- */
function ProgramCard({ program, currency = "SAR", isArabic = false, t }) {
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
      className="shrink-0 w-[82vw] snap-start rounded-[24px] bg-white overflow-hidden"
      style={{ border: `1px solid ${TOKENS.border}` }}
    >
      {/* Image with overlays */}
      <div className="relative m-3 rounded-[20px] bg-[#E7F0FB]">
        <div className="relative w-full pb-[75%] overflow-hidden rounded-[20px]">
          <img
            src={program.image}
            alt={program.title}
            className="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
          />
        </div>

        {/* Action buttons */}
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

      {/* Content body */}
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
          <span className="inline-flex items-center gap-0.5 font-bold text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img src={currencyIcon} alt={currency} className="h-3.5 w-3.5 invert" />
            )}
            <span>{priceText}</span>
          </span>
          <span className="text-slate-500 font-medium">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>
        </div>
      </div>
    </article>
  );
}

/* ---------- Mobile Main Component ---------- */
export default function MobileSummer() {
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
      image: "https://images.pexels.com/photos/5151697/pexels-photo-5151697.jpeg?auto=compress&cs=tinysrgb&w=800",
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

  if (programs.length === 0) return null;

  return (
    <section className="block md:hidden bg-white py-10 w-full" dir={direction}>
      
      {/* Title block */}
      <div className="flex flex-col px-4">
        <div className="flex items-center justify-between">
          <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[55%] text-start">
            {heading}
          </h2>
          <Link href={ctaUrl} className="flex items-center gap-1 rounded-[8px] bg-[#1F63AE] px-4 py-2 text-sm font-bold !text-white hover:brightness-110 transition-all">
            <span className="pb-0.5">{ctaText}</span>
            <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
          </Link>
        </div>
        {subheading && (
          <p className="mt-2 text-sm text-slate-500 text-start">
            {subheading}
          </p>
        )}
      </div>

      {/* Touch swipeable row */}
      <div className="mt-6">
        <div className="flex gap-4 overflow-x-auto px-4 snap-x snap-mandatory scrollbar-hide py-2">
          {programs.map((program) => (
            <ProgramCard
              key={program.id}
              program={program}
              currency={currency}
              isArabic={isArabic}
              t={t}
            />
          ))}
          {/* Peek padding */}
          <div className="shrink-0 w-4 snap-none"></div>
        </div>
      </div>

    </section>
  );
}
