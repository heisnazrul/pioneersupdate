"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import {
  faChevronLeft,
  faChevronRight,
  faStar as faStarSolid,
} from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
  bg: "#EAF2FF",
};

const FALLBACK_TABS = [
  { id: "all", label: "All" },
  { id: "top-rated", label: "Highest rating" },
  { id: "most-popular", label: "Most requested" },
  { id: "best-offer", label: "Best offer" },
  { id: "best-campus", label: "Best campus" },
  { id: "best-match", label: "Best match" },
];

/* ---------------- Card Component ---------------- */
function InstituteCard({ item, currency = "SAR", isArabic = false, href, t }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "language_courses";
  const inWishlist = isInWishlist(interactionType, item.id);
  const inCompare = isInCompare(interactionType, item.id);
  const filledStars = Math.round(item.rating || 0);
  const totalStars = 5;
  const tagLabel = item.tagLabel || (isArabic ? "الأعلى تقييماً" : "Top rated");
  const discountLabel = item.discountLabel;
  const currencyIcon = "/assets/sar.svg";
  const currencySymbol = currency === "GBP" ? "£" : null;

  const formatAmount = (value) => {
    if (value === null || value === undefined) return null;
    if (typeof value === "number") return value;
    if (typeof value === "string") {
      const match = value.replace(/,/g, "").match(/[\d.]+/);
      return match ? Number(match[0]) : null;
    }
    return null;
  };

  const formatNumber = (value) =>
    new Intl.NumberFormat("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(value);

  const CardTag = href ? Link : "article";
  const cardProps = href ? { href } : {};

  return (
    <CardTag
      {...cardProps}
      className="shrink-0 w-[82vw] snap-start rounded-3xl bg-white block overflow-hidden"
      style={{ border: `1px solid ${TOKENS.border}` }}
    >
      <div className="relative m-3 overflow-hidden rounded-2xl bg-[#E7F0FB]">
        <div className="relative w-full pb-[60%]">
          <img
            src={item.image}
            alt={item.name}
            className="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
          />
        </div>

        {/* Action Buttons */}
        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, item.id);
          }}
          className="absolute left-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md animate-fade-in"
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
            toggleCompare(interactionType, item.id);
          }}
          className="absolute left-3 top-13 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md animate-fade-in"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={16}
            height={16}
            className="h-[16px] w-[16px]"
          />
        </button>

        {tagLabel ? (
          <div className="absolute right-3 top-3 rounded bg-[rgba(0,0,0,0.5)] px-2.5 py-0.5 text-[11px] font-normal text-white backdrop-blur">
            {tagLabel}
          </div>
        ) : null}

        {discountLabel ? (
          <div className="absolute right-3 top-10 mt-1 rounded bg-[#E53935] px-2.5 py-0.5 text-[11px] font-normal text-white animate-bounce">
            {discountLabel}
          </div>
        ) : null}
      </div>

      <div className="px-4 pb-4">
        {/* Rating Stars */}
        <div className="flex items-center gap-0.5 text-xs justify-start">
          {Array.from({ length: totalStars }).map((_, idx) => (
            <FontAwesomeIcon
              key={idx}
              icon={idx < filledStars ? faStarSolid : faStarRegular}
              className={idx < filledStars ? "text-[#FFC107]" : "text-slate-300"}
            />
          ))}
        </div>

        {/* Location & Flag */}
        <div className="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500 justify-start">
          <span>{item.country}</span>
          {item.flag && (item.flag.startsWith("http") || item.flag.startsWith("/")) ? (
            <img src={item.flag} alt={item.country} className="h-3.5 w-3.5" />
          ) : item.flag ? (
            <span className="text-sm leading-none">{item.flag}</span>
          ) : (
            <span className="text-sm leading-none">🌍</span>
          )}
        </div>

        {/* School Name */}
        <h3 className="mt-1 text-[16px] font-bold leading-snug text-slate-900 text-start min-h-[48px] line-clamp-2">
          {item.name}
        </h3>

        {/* Course Type */}
        <div className="mt-2.5 flex justify-start">
          <span className="inline-flex rounded-full bg-[#EEF3FF] px-3.5 py-0.5 text-[11px] font-normal text-slate-600">
            {item.courseType}
          </span>
        </div>

        {/* Price Row */}
        <div className="mt-3.5 flex gap-2 text-[13px] text-slate-700 items-center text-left" dir={isArabic ? "rtl" : "ltr"}>
          <span className="inline-flex items-center gap-1 text-[16px] font-extrabold text-[#111827]">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img
                src={currencyIcon}
                alt={currency}
                className="h-3.5 w-3.5 invert"
              />
            )}
            <span>
              {(() => {
                const newAmount = formatAmount(item.priceNewValue ?? item.priceNew);
                return newAmount ? formatNumber(newAmount) : item.priceNew || "-";
              })()}
            </span>
          </span>
          <span className="text-[#111827] font-medium text-[14px]">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>

          {(() => {
            const oldAmount = formatAmount(item.priceOldValue ?? item.priceOld);
            if (!oldAmount) return null;
            return (
              <span className="inline-flex items-center gap-0.5 text-slate-400 line-through text-[14px] mr-1">
                {currencySymbol ? (
                  <span>{currencySymbol}</span>
                ) : (
                  <img
                    src={currencyIcon}
                    alt={currency}
                    className="h-3.5 w-3.5 invert opacity-50"
                  />
                )}
                <span>{formatNumber(oldAmount)}</span>
              </span>
            );
          })()}
        </div>
      </div>
    </CardTag>
  );
}

/* ---------------- Main Mobile Component ---------------- */
export default function MobileOffers() {
  const tabsScrollRef = useRef(null);
  const tabRefs = useRef({});
  const tabTrackRef = useRef(null);
  const { data: offersData } = useApi("/courseenglish/offers");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const currency = "SAR";

  const offersCourses = offersData?.language_courses;
  const offersTags = offersData?.language_course_tags;

  const institutes = useMemo(() => {
    const courses = offersCourses ?? [];
    if (!courses.length) return [];
    return courses.map((course) => {
      const priceNewValue = course.price_new_sar ?? course.price_new_gbp ?? course.price_new;
      const priceOldValue = course.price_old_sar ?? course.price_old_gbp ?? course.price_old;
      let discountLabel = null;
      if (priceOldValue && priceNewValue && priceOldValue > priceNewValue) {
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
        flag: course.flag,
        courseType: isArabic
          ? course.course_type_ar_name || course.course_type
          : course.course_type || course.course_type_ar_name,
        priceNewValue,
        priceOldValue,
        rating: course.rating,
        tagId: course.tag_id,
        tagSlug: course.tag_slug,
        tagLabel: isArabic ? course.tag_ar_name || course.tag : course.tag || course.tag_ar_name,
        tag: course.tag_slug || course.tag,
        discountLabel,
        image: course.image || "/assets/hero.png",
      };
    });
  }, [offersCourses, isArabic]);

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

  const [activeTab, setActiveTab] = useState("all");
  const [underlineStyle, setUnderlineStyle] = useState({ width: 0, left: 0 });
  const [tabThumb, setTabThumb] = useState({ width: 0, left: 0 });

  // Derived Safe Active Tab
  const currentTab = useMemo(() => {
    if (!tabs.length) return "all";
    return tabs.find((tab) => tab.id === activeTab) ? activeTab : tabs[0].id;
  }, [tabs, activeTab]);

  const filtered = useMemo(() => {
    if (currentTab === "all") return institutes;
    const tagId = Number(currentTab);
    return institutes.filter(
      (item) => item.tagId === tagId || item.tagSlug === currentTab || item.tag === currentTab
    );
  }, [institutes, currentTab]);

  const mobileHeading = t("pages.homepage.partners_offers.heading", "Partner institutes around the world");
  const viewAllLabel = t("pages.homepage.partners_offers.view_all", "View all schools");
  const viewAllUrl = "/language-institutes";

  // Tab underline indicator (wrapped in useCallback to satisfy exhaustive-deps)
  const updateUnderline = useCallback(() => {
    const container = tabsScrollRef.current;
    const activeEl = tabRefs.current[currentTab];
    if (!container || !activeEl) return;

    const containerRect = container.getBoundingClientRect();
    const activeRect = activeEl.getBoundingClientRect();

    setUnderlineStyle({
      width: activeRect.width,
      left: activeRect.left - containerRect.left + container.scrollLeft,
    });
  }, [currentTab]);

  useEffect(() => {
    updateUnderline();
  }, [updateUnderline]);

  useEffect(() => {
    const el = tabsScrollRef.current;
    if (!el) return;
    const handler = () => updateUnderline();
    el.addEventListener("scroll", handler);
    return () => el.removeEventListener("scroll", handler);
  }, [updateUnderline]);

  // Tab scrollbar thumb logic (wrapped in useCallback to satisfy exhaustive-deps)
  const computeTabThumb = useCallback((customLeft) => {
    const container = tabsScrollRef.current;
    const track = tabTrackRef.current;
    if (!container || !track) return;
    const trackWidth = track.clientWidth;
    const scrollWidth = container.scrollWidth;
    const clientWidth = container.clientWidth;
    const scrollLeft = customLeft ?? container.scrollLeft;
    const thumbWidth = Math.max(
      32,
      Math.min(80, (clientWidth / scrollWidth) * trackWidth)
    );
    const maxLeft = Math.max(0, trackWidth - thumbWidth);
    const ratio =
      scrollWidth > clientWidth ? scrollLeft / (scrollWidth - clientWidth) : 0;
    const left = ratio * maxLeft;
    setTabThumb({ width: thumbWidth, left });
  }, []);

  useEffect(() => {
    computeTabThumb();
    const el = tabsScrollRef.current;
    if (!el) return;
    const onScroll = () => computeTabThumb();
    el.addEventListener("scroll", onScroll, { passive: true });
    const ro = new ResizeObserver(() => computeTabThumb());
    ro.observe(el);
    const roTrack = new ResizeObserver(() => computeTabThumb());
    const currentTrack = tabTrackRef.current;
    if (currentTrack) roTrack.observe(currentTrack);
    return () => {
      el.removeEventListener("scroll", onScroll);
      ro.disconnect();
      roTrack.disconnect();
    };
  }, [computeTabThumb]);

  const activateTab = (id) => {
    setActiveTab(id);
    const container = tabsScrollRef.current;
    const target = tabRefs.current[id];
    if (!container || !target) return;
    const containerWidth = container.clientWidth;
    const targetCenter = target.offsetLeft + target.offsetWidth / 2;
    const scrollTo = Math.max(0, targetCenter - containerWidth / 2);
    container.scrollTo({ left: scrollTo, behavior: "smooth" });
    requestAnimationFrame(() => computeTabThumb(scrollTo));
  };

  const scrollTabs = (direction) => {
    const el = tabsScrollRef.current;
    if (!el) return;
    const amount = 120;
    el.scrollBy({
      left: direction === "left" ? -amount : amount,
      behavior: "smooth",
    });
  };

  return (
    <section className="block md:hidden bg-[#EAF2FF] py-10 w-full" dir={direction}>
      
      {/* Mobile Title Section */}
      <div className="flex items-center justify-between px-4">
        <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[55%] text-start">
          {mobileHeading}
        </h2>
        <Link href={viewAllUrl} className="flex items-center gap-1 rounded-[8px] bg-[#1F63AE] px-4 py-2 text-sm font-bold !text-white hover:brightness-110 transition-all">
          <span className="pb-0.5">{viewAllLabel}</span>
          <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
        </Link>
      </div>

      {/* Tabs Scrolling Track */}
      <div className="relative my-5">
        <button
          type="button"
          onClick={() => scrollTabs("left")}
          className="absolute left-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-transparent p-1 text-slate-400"
          aria-label="Scroll left"
        >
          <FontAwesomeIcon icon={faChevronLeft} />
        </button>
        <button
          type="button"
          onClick={() => scrollTabs("right")}
          className="absolute right-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-transparent p-1 text-slate-400"
          aria-label="Scroll right"
        >
          <FontAwesomeIcon icon={faChevronRight} />
        </button>

        {/* Baseline & Tab Labels */}
        <div className="mx-6 relative">
          <div className="pointer-events-none absolute left-0 right-0 bottom-0 h-[3px] bg-slate-200/70">
            <div
              className="absolute h-full bg-[#0072bc] transition-all duration-300"
              style={{
                width: underlineStyle.width,
                left: underlineStyle.left,
              }}
            />
          </div>

          <div
            ref={tabsScrollRef}
            className="flex gap-5 overflow-x-auto pb-2 text-sm font-medium text-slate-500 scrollbar-hide"
          >
            {tabs.map((tab) => {
              const isActive = currentTab === tab.id;
              return (
                <button
                  key={tab.id}
                  type="button"
                  ref={(el) => (tabRefs.current[tab.id] = el)}
                  onClick={() => activateTab(tab.id)}
                  className={`whitespace-nowrap transition-colors pb-1.5 ${
                    isActive ? "text-[#0072bc] font-bold" : "text-slate-500"
                  }`}
                >
                  {tab.label}
                </button>
              );
            })}
          </div>
        </div>
      </div>

      {/* Underline Progress Bar */}
      <div className="relative mt-2 px-6">
        <div
          className="pointer-events-none h-1 rounded-full bg-slate-200/50"
          ref={tabTrackRef}
        >
          <div
            className="absolute h-1 rounded-full bg-[#1F63AE] transition-all duration-300"
            style={{
              width: `${tabThumb.width}px`,
              transform: `translateX(${tabThumb.left}px)`,
            }}
          />
        </div>
      </div>

      {/* Swipe Cards Container */}
      <div className="mt-6">
        {filtered.length > 0 ? (
          <div className="flex gap-4 overflow-x-auto px-4 snap-x snap-mandatory scrollbar-hide py-2">
            {filtered.map((inst) => (
              <InstituteCard
                key={inst.id}
                item={inst}
                currency={currency}
                isArabic={isArabic}
                href={inst.slug ? `/language-institutes/${inst.slug}?course_id=${inst.id}` : undefined}
                t={t}
              />
            ))}
            {/* Peeking trailing card padding */}
            <div className="shrink-0 w-4 snap-none"></div>
          </div>
        ) : (
          <div className="py-12 text-center text-slate-500 font-medium">
            {isArabic ? "لا توجد معاهد متاحة حالياً." : "No institutes available."}
          </div>
        )}
      </div>

    </section>
  );
}
