"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import {
  faChevronLeft,
  faChevronRight,
  faStar as faStarSolid,
} from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { CurrencyAmount, getCoursePrice } from "@/lib/format-currency";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
  bg: "#EAF2FF",
};

// gap between cards
const GAP = 20;
// autoplay interval
const AUTO_MS = 4000;

const FALLBACK_TABS = [
  { id: "all", label: "All" },
  { id: "top-rated", label: "Highest rating" },
  { id: "most-popular", label: "Most requested" },
  { id: "best-offer", label: "Best offer" },
  { id: "best-campus", label: "Best campus" },
  { id: "best-match", label: "Best match" },
];

/* ---------------- Card ---------------- */
function InstituteCard({ item, style, currency = "SAR", isArabic = false, href, t }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "language_courses";
  const inWishlist = isInWishlist(interactionType, item.id);
  const inCompare = isInCompare(interactionType, item.id);
  const filledStars = Math.round(item.rating || 0);
  const totalStars = 5;
  const tagLabel = item.tagLabel || (isArabic ? "الأعلى تقييماً" : "Top rated");
  const discountLabel = item.discountLabel;
  const priceNewValue = getCoursePrice(item, currency, "new");
  const priceOldValue = getCoursePrice(item, currency, "old");

  const CardTag = href ? Link : "article";
  const cardProps = href ? { href } : {};

  return (
    <CardTag
      {...cardProps}
      className="shrink-0 rounded-3xl bg-white block overflow-hidden transition-all duration-300 hover:shadow-lg"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      <div className="relative m-4 overflow-hidden rounded-2xl bg-[#E7F0FB]">
        <div className="relative w-full pb-[62%]">
          <img
            src={item.image}
            alt={item.name}
            className="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
          />
        </div>

        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, item.id);
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
            toggleCompare(interactionType, item.id);
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

        {tagLabel ? (
          <div className="absolute right-4 top-4 rounded-md bg-[rgba(0,0,0,0.4)] px-3 py-1 text-xs font-normal text-white backdrop-blur">
            {tagLabel}
          </div>
        ) : null}

        {discountLabel ? (
          <div className="absolute right-4 top-12 mt-1 rounded-md bg-[#E53935] px-3 py-1 text-xs font-normal text-white animate-pulse">
            {discountLabel}
          </div>
        ) : null}
      </div>

      <div className="px-6 pb-5">
        <div className="flex justify-between mb-5">
          <div className="mt-1 flex items-center gap-2 text-[14px] text-slate-600 justify-start">

            {item.flag && (item.flag.startsWith("http") || item.flag.startsWith("/")) ? (
              <img src={item.flag} alt={item.country} className="h-6 w-6" />
            ) : item.flag ? (
              <span className="text-xl leading-none">{item.flag}</span>
            ) : (
              <span className="text-xl leading-none">🌍</span>
            )}
            <span>{item.country}</span>
          </div>
          <div className="flex items-center gap-1 text-sm justify-start">
            {Array.from({ length: totalStars }).map((_, idx) => (
              <FontAwesomeIcon
                key={idx}
                icon={idx < filledStars ? faStarSolid : faStarRegular}
                className={idx < filledStars ? "text-[#FFC107]" : "text-slate-300"}
              />
            ))}
          </div>


        </div>

        <h3 className="mt-1 text-[18px] font-semibold leading-snug text-slate-900 text-start min-h-[54px] line-clamp-2">
          {item.name}
        </h3>

        <div className="mt-3 flex justify-start">
          <span className="inline-flex rounded-full bg-[#EEF3FF] px-4 py-1 text-[13px] font-normal text-slate-700">
            {item.courseType}
          </span>
        </div>

        <div className="mt-4 flex gap-2 text-[14px] text-slate-700 items-center text-left" dir={isArabic ? "rtl" : "ltr"}>
          <CurrencyAmount
            currency={currency}
            amount={priceNewValue}
            className="inline-flex items-center gap-1 text-[18px] font-bold text-[#111827]"
            iconClassName="h-4 w-4"
          />
          <span className="text-[#111827] font-medium text-[16px]">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>

          {priceOldValue ? (
            <CurrencyAmount
              currency={currency}
              amount={priceOldValue}
              className="inline-flex items-center gap-1 text-slate-400 line-through text-[16px] mr-1"
              iconClassName="h-4 w-4"
              muted
            />
          ) : null}
        </div>
      </div>
    </CardTag>
  );
}

/* ---------------- Main Desktop Component ---------------- */
export default function DesktopOffers() {
  const viewportRef = useRef(null);
  const { data: offersData } = useApi("/coursesat/home/offers");
  const { language, direction, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";

  const offersCourses = offersData?.language_courses;
  const offersTags = offersData?.language_course_tags;

  const institutes = useMemo(() => {
    const courses = offersCourses ?? [];
    if (!courses.length) return [];
    return courses.map((course) => {
      const priceNewValue = getCoursePrice(course, currency, "new");
      const priceOldValue = getCoursePrice(course, currency, "old");
      let discountLabel = null;
      if (course.discount_percent) {
        discountLabel = `${course.discount_percent}% OFF`;
      } else if (priceOldValue && priceNewValue && priceOldValue > priceNewValue) {
        const discount = Math.round((1 - priceNewValue / priceOldValue) * 100);
        if (discount > 0) discountLabel = `${discount}% OFF`;
      }

      const schoolName = course.school_name || course.name || course.ar_name;
      const schoolAr = course.school_ar_name || course.ar_name || course.school_name;
      const cityName =
        course.city || course.city_name || (course.location ? course.location.split(",")[0]?.trim() : null);
      const cityAr = course.city_ar_name || course.city_ar || course.city_name || cityName;
      const displayName = isArabic
        ? [schoolAr, cityAr, schoolName].filter(Boolean).join(" - ")
        : [schoolName, cityName].filter(Boolean).join(" - ");

      return {
        id: course.id,
        name: displayName,
        slug: course.slug,
        country: isArabic ? course.country_ar_name || course.country : course.country || course.country_ar_name,
        flag: getImageUrl(course.flag),
        courseType: isArabic
          ? course.course_type_ar_name || course.course_type
          : course.course_type || course.course_type_ar_name,
        prices: course.prices,
        priceNewValue,
        priceOldValue,
        rating: course.rating,
        tagId: course.tag_id,
        tagIds: course.tag_ids ?? [],
        tagSlug: course.tag_slug,
        tagLabel: isArabic ? course.tag_ar_name || course.tag : course.tag || course.tag_ar_name,
        tag: course.tag_slug || course.tag,
        discountLabel,
        image: getImageUrl(course.image) || "/assets/hero.png",
      };
    });
  }, [offersCourses, isArabic, currency]);

  const tabs = useMemo(() => {
    const tags = offersTags ?? [];
    if (!tags.length) {
      return FALLBACK_TABS.map((tab) => ({
        id: tab.id,
        label: t(`pages.homepage.partners_offers.tabs.${tab.id}`, tab.label),
      }));
    }
    return [
      { id: "all", label: t("pages.homepage.partners_offers.tabs.all", "All") },
      ...tags.map((tag) => ({
        id: String(tag.id),
        label: isArabic ? tag.ar_name || tag.name : tag.name || tag.ar_name,
      })),
    ];
  }, [offersTags, isArabic, t]);

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [activeTab, setActiveTab] = useState("all");

  // Derived Safe Active Tab
  const currentTab = useMemo(() => {
    if (!tabs.length) return "all";
    return tabs.find((tab) => tab.id === activeTab) ? activeTab : tabs[0].id;
  }, [tabs, activeTab]);

  const desktopHeading = t("pages.homepage.partners_offers.heading", "Partner institutes around the world");
  const viewAllLabel = t("pages.homepage.partners_offers.view_all", "View all schools");
  const viewAllUrl = "/language-institutes";

  const filtered = useMemo(() => {
    const matchesTab = (item) => {
      if (currentTab === "all") return true;

      const tagId = Number(currentTab);
      if (!Number.isNaN(tagId) && tagId > 0) {
        return item.tagIds?.includes(tagId) || item.tagId === tagId;
      }

      return item.tagSlug === currentTab || item.tag === currentTab;
    };

    const matched = institutes.filter(matchesTab);

    if (currentTab !== "all") {
      return matched;
    }

    const seen = new Set();
    return matched.filter((item) => {
      if (seen.has(item.id)) return false;
      seen.add(item.id);
      return true;
    });
  }, [institutes, currentTab]);

  const [visible, setVisible] = useState(3);

  // Responsive Breakpoints
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

  const len = filtered.length;

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
  }, [len, maxIndex, visible]);

  const prev = () => {
    if (len <= visible) return;
    setIndex((i) => (i - 1 < 0 ? maxIndex : i - 1));
  };

  const next = () => {
    if (len <= visible) return;
    setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
  };

  const handleTabClick = (tabId) => {
    setActiveTab(tabId);
    setIndex(0);
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

  return (
    <section className="hidden md:block bg-[#EAF2FF] py-16 w-full" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">

        {/* Header Title */}
        <div className="flex flex-col items-center text-center">
          <h2 className="text-3xl sm:text-[42px] font-bold text-[#111827] leading-[1.3] max-w-[400px] mx-auto">
            {desktopHeading}
          </h2>
        </div>

        {/* Tab Filters */}
        <div className="mt-10 flex justify-center text-[16px] font-medium text-slate-500">
          <div className="flex border-b-[2px]  border-slate-200/70">
            {tabs.map((tab) => {
              const isActive = currentTab === tab.id;
              return (
                <button
                  key={tab.id}
                  type="button"
                  onClick={() => handleTabClick(tab.id)}
                  className={`relative pb-3 px-4 transition-colors duration-200 ${isActive ? "text-[#0072bc] font-bold" : "text-slate-500 hover:text-slate-800"}`}
                >
                  <span>{tab.label}</span>
                  {isActive && (
                    <span
                      className="pointer-events-none absolute left-0 right-0 bottom-0
                                  h-[4px] bg-[#0072bc]"
                    />
                  )}
                </button>
              );
            })}
          </div>
        </div>

        {/* Slider Area */}
        <div className="relative mt-10 px-4 md:px-12">
          {len > visible && (
            <>
              {/* Prev Arrow */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous"
                className={`absolute -left-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full border border-slate-200 bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 transition`}
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={20}
                  height={20}
                  className="h-5 w-5"
                />
              </button>

              {/* Next Arrow */}
              <button
                type="button"
                onClick={next}
                aria-label="Next"
                className="absolute -right-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full p-4 text-white shadow-md hover:brightness-110 transition"
                style={{
                  background: TOKENS.primary,
                  boxShadow: TOKENS.primaryShadow,
                }}
              >
                <Image
                  src="/assets/icons/arrow-right-white.svg"
                  alt="Next"
                  width={20}
                  height={20}
                  className="h-5 w-5"
                />
              </button>
            </>
          )}

          {/* Cards Track */}
          <div ref={viewportRef} className="overflow-hidden m-4">
            {filtered.length > 0 ? (
              <div className="flex" style={trackStyle}>
                {filtered.map((inst) => (
                  <InstituteCard
                    key={`${inst.id}-${inst.tagId ?? currentTab}`}
                    item={inst}
                    style={cardStyle}
                    currency={currency}
                    isArabic={isArabic}
                    href={inst.school_slug || inst.slug ? `/language-institutes/${inst.school_slug || inst.slug}?course_id=${inst.id}` : undefined}
                    t={t}
                  />
                ))}
              </div>
            ) : (
              <div className="py-20 text-center text-slate-500 font-medium">
                {isArabic ? "لا توجد معاهد متاحة في هذا القسم حالياً." : "No institutes found under this category."}
              </div>
            )}
          </div>
        </div>

        {/* View All Button */}
        <div className="mt-12 flex justify-center">
          <Link
            href={viewAllUrl}
            className="rounded-[8px] bg-[#1F63AE] px-8 py-3.5 text-sm font-bold !text-white shadow-[0_10px_25px_rgba(31,99,174,0.4)] hover:brightness-110 transition-all"
          >
            {viewAllLabel}
          </Link>
        </div>

      </div>
    </section>
  );
}
