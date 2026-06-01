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
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import MobileInfiniteCarousel from "@/components/mobile/mobile-infinite-carousel";

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
  const priceNewValue = getCoursePrice(item, currency, "new");
  const priceOldValue = getCoursePrice(item, currency, "old");

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
        <div className="flex items-center gap-1 justify-between">
          <div className="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500 justify-start">
            
            {item.flag && (item.flag.startsWith("http") || item.flag.startsWith("/")) ? (
              <img src={item.flag} alt={item.country} className="h-3.5 w-3.5" />
            ) : item.flag ? (
              <span className="text-sm leading-none">{item.flag}</span>
            ) : (
              <span className="text-sm leading-none">🌍</span>
            )}
            <span>{item.country}</span>
          </div>
          <div className="flex items-center gap-0.5 text-xs justify-start">
            {Array.from({ length: totalStars }).map((_, idx) => (
              <FontAwesomeIcon
                key={idx}
                icon={idx < filledStars ? faStarSolid : faStarRegular}
                className={idx < filledStars ? "text-[#FFC107]" : "text-slate-300"}
              />
            ))}
          </div>
        </div>

        {/* School Name */}
        <h3 className="mt-2 text-[16px] font-bold leading-snug text-slate-900 text-start line-clamp-2">
          {item.name}
        </h3>

        {/* Course Type */}
        <div className="mt-2.5 flex justify-start">
          <span className="inline-flex rounded-full bg-[#EEF3FF] px-3.5 py-1 text-[14px] font-normal text-slate-600">
            {item.courseType}
          </span>
        </div>

        {/* Price Row */}
        <div className="mt-3.5 flex flex-wrap items-center gap-2 text-[13px] text-slate-700">
          <CurrencyAmount
            currency={currency}
            amount={priceNewValue}
            className="inline-flex items-center text-[16px] font-bold text-[#111827]"
            iconClassName="h-3.5 w-3.5"
          />
          <span className="text-[14px] font-medium text-[#111827]">
            {t("pages.homepage.partners_offers.per_week", "/ week")}
          </span>

          {priceOldValue ? (
            <CurrencyAmount
              currency={currency}
              amount={priceOldValue}
              className="inline-flex items-center gap-0.5 text-[14px] text-slate-400 line-through"
              iconClassName="h-3.5 w-3.5 invert"
              muted
            />
          ) : null}
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

  const [activeTab, setActiveTab] = useState("all");
  const [underlineStyle, setUnderlineStyle] = useState({ width: 0, left: 0 });
  const [tabsThumb, setTabsThumb] = useState({ width: 0, left: 0 });

  // Derived Safe Active Tab
  const currentTab = useMemo(() => {
    if (!tabs.length) return "all";
    return tabs.find((tab) => tab.id === activeTab) ? activeTab : tabs[0].id;
  }, [tabs, activeTab]);

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

  const mobileHeading = t(
    "pages.homepage.partners_offers.mobile_heading",
    t("pages.homepage.partners_offers.heading", "Partner institutes around the world")
  );
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

  const computeTabsThumb = useCallback(() => {
    const container = tabsScrollRef.current;
    const track = tabTrackRef.current;
    if (!container || !track) return;

    const trackWidth = track.clientWidth;
    const scrollWidth = container.scrollWidth;
    const clientWidth = container.clientWidth;
    const scrollable = scrollWidth - clientWidth;
    const scrollLeft = Math.abs(container.scrollLeft);
    const ratio = scrollable > 0 ? scrollLeft / scrollable : 0;
    const thumbWidth =
      scrollable > 0
        ? Math.max(36, (clientWidth / scrollWidth) * trackWidth)
        : Math.max(36, trackWidth * 0.18);
    const maxLeft = Math.max(0, trackWidth - thumbWidth);
    const left =
      scrollable > 0
        ? (isArabic ? (1 - ratio) * maxLeft : ratio * maxLeft)
        : (isArabic ? maxLeft : 0);

    setTabsThumb({ width: thumbWidth, left });
  }, [isArabic]);

  useEffect(() => {
    computeTabsThumb();
    const el = tabsScrollRef.current;
    if (!el) return undefined;

    const onScroll = () => computeTabsThumb();
    el.addEventListener("scroll", onScroll, { passive: true });
    const ro = new ResizeObserver(() => computeTabsThumb());
    ro.observe(el);
    const track = tabTrackRef.current;
    const roTrack = track ? new ResizeObserver(() => computeTabsThumb()) : null;
    if (track) roTrack.observe(track);

    return () => {
      el.removeEventListener("scroll", onScroll);
      ro.disconnect();
      roTrack?.disconnect();
    };
  }, [computeTabsThumb, tabs.length]);

  const activateTab = (id) => {
    setActiveTab(id);
    const container = tabsScrollRef.current;
    const target = tabRefs.current[id];
    if (!container || !target) return;
    const containerWidth = container.clientWidth;
    const targetCenter = target.offsetLeft + target.offsetWidth / 2;
    const scrollTo = Math.max(0, targetCenter - containerWidth / 2);
    container.scrollTo({ left: scrollTo, behavior: "smooth" });
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
        <Link
          href={viewAllUrl}
          className="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-[#1F63AE] transition-colors hover:text-[#135FAE]"
        >
          <span>{viewAllLabel}</span>
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

        {/* Tab labels + primary active bar indicator */}
        <div className="mx-6">
          <div className="relative mx-2">
            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-[3px]">
              <div
                className="absolute h-full rounded-sm bg-[#0072bc] transition-all duration-300"
                style={{
                  width: underlineStyle.width,
                  left: underlineStyle.left,
                }}
              />
            </div>

            <div
              ref={tabsScrollRef}
              className="tabs-scroll-hide flex gap-5 overflow-x-auto overflow-y-hidden pb-2 text-sm font-medium text-slate-500"
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

          {/* Secondary scroll — white track, gray thumb (tags overflow) */}
          <div className="relative mx-2 mt-3">
            <div
              ref={tabTrackRef}
              className="relative h-1.5 w-full overflow-hidden rounded-full bg-white"
            >
              <div
                className="absolute top-0 h-1.5 rounded-full bg-[#B8C4D0] transition-[transform,width] duration-150 ease-out"
                style={{
                  width: `${tabsThumb.width}px`,
                  transform: `translateX(${tabsThumb.left}px)`,
                }}
              />
            </div>
          </div>
        </div>
      </div>

      {/* Swipe Cards Container */}
      <div className="mt-4">
        {filtered.length > 0 ? (
          <MobileInfiniteCarousel
            items={filtered}
            getItemKey={(inst) => `${inst.id}-${inst.tagId ?? currentTab}`}
            renderItem={(inst, _idx, key) => (
              <InstituteCard
                key={key}
                item={inst}
                currency={currency}
                isArabic={isArabic}
                href={inst.school_slug || inst.slug ? `/language-institutes/${inst.school_slug || inst.slug}?course_id=${inst.id}` : undefined}
                t={t}
              />
            )}
          />
        ) : (
          <div className="py-12 text-center text-slate-500 font-medium">
            {isArabic ? "لا توجد معاهد متاحة حالياً." : "No institutes available."}
          </div>
        )}
      </div>

      <style jsx>{`
        .tabs-scroll-hide {
          -ms-overflow-style: none;
          scrollbar-width: none;
        }

        .tabs-scroll-hide::-webkit-scrollbar {
          display: none;
          width: 0;
          height: 0;
        }
      `}</style>
    </section>
  );
}
