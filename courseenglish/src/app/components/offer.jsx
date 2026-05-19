"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faStar as faStarRegular,
} from "@fortawesome/free-regular-svg-icons";
import {
  faChevronLeft,
  faChevronRight,
  faStar as faStarSolid,
} from "@fortawesome/free-solid-svg-icons";
import { formatCurrency, useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";
import { pickLang } from "@/lib/i18nFallback";

/* ---------------- Dummy data ---------------- */

const INSTITUTES = [
  {
    id: 1,
    name: "CES School – London",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "General English Course",
    priceNew: "£2,527",
    priceOld: "£2,800",
    rating: 4.5,
    tag: "top-rated",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 2,
    name: "English World Academy – Brighton",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "Intensive English Course",
    priceNew: "£2,450",
    priceOld: "£2,900",
    rating: 4.8,
    tag: "best-offer",
    category: "all",
    image:
      "https://images.pexels.com/photos/5151697/pexels-photo-5151697.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 3,
    name: "Global Language School – London",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "General English Course",
    priceNew: "£2,600",
    priceOld: "£3,000",
    rating: 4.7,
    tag: "most-popular",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181605/pexels-photo-1181605.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 4,
    name: "City English Institute – Manchester",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "General English Course",
    priceNew: "£2,300",
    priceOld: "£2,700",
    rating: 4.4,
    tag: "top-rated",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181397/pexels-photo-1181397.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 5,
    name: "Oxford Language Centre",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "Exam Preparation Course",
    priceNew: "£2,900",
    priceOld: "£3,200",
    rating: 4.9,
    tag: "best-campus",
    category: "all",
    image:
      "https://images.pexels.com/photos/4145190/pexels-photo-4145190.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 6,
    name: "Cambridge International English",
    country: "United Kingdom",
    flag: "🇬🇧",
    courseType: "General English Course",
    priceNew: "£2,480",
    priceOld: "£2,950",
    rating: 4.6,
    tag: "best-match",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181398/pexels-photo-1181398.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
];

const INSTITUTES_AR = [
  {
    id: 1,
    name: "معهد CES – لندن",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "دورة إنجليزي عامة",
    priceNew: "﷼9,470",
    priceOld: "﷼10,500",
    rating: 4.5,
    tag: "top-rated",
    tagLabel: "الأعلى تقييماً",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181396/pexels-photo-1181396.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 2,
    name: "أكاديمية إنجليش وورلد – برايتون",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "دورة إنجليزي مكثفة",
    priceNew: "﷼9,180",
    priceOld: "﷼10,860",
    rating: 4.8,
    tag: "best-offer",
    tagLabel: "أفضل عرض",
    category: "all",
    image:
      "https://images.pexels.com/photos/5151697/pexels-photo-5151697.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 3,
    name: "مدرسة جلوبال للغة – لندن",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "دورة إنجليزي عامة",
    priceNew: "﷼9,760",
    priceOld: "﷼11,250",
    rating: 4.7,
    tag: "most-popular",
    tagLabel: "الأكثر طلباً",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181605/pexels-photo-1181605.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 4,
    name: "معهد سيتي الإنجليزي – مانشستر",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "دورة إنجليزي عامة",
    priceNew: "﷼8,630",
    priceOld: "﷼10,120",
    rating: 4.4,
    tag: "top-rated",
    tagLabel: "الأعلى تقييماً",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181397/pexels-photo-1181397.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 5,
    name: "مركز أكسفورد للغات",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "تحضير اختبارات",
    priceNew: "﷼10,900",
    priceOld: "﷼12,030",
    rating: 4.9,
    tag: "best-campus",
    tagLabel: "أفضل حرم",
    category: "all",
    image:
      "https://images.pexels.com/photos/4145190/pexels-photo-4145190.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
  {
    id: 6,
    name: "كامبريدج إنترناشيونال إنجليش",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    courseType: "دورة إنجليزي عامة",
    priceNew: "﷼9,330",
    priceOld: "﷼11,110",
    rating: 4.6,
    tag: "best-match",
    tagLabel: "أفضل تطابق",
    category: "all",
    image:
      "https://images.pexels.com/photos/1181398/pexels-photo-1181398.jpeg?auto=compress&cs=tinysrgb&w=1200",
  },
];

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

/* ---------------- Tabs ---------------- */

const TABS = [
  { id: "all", label: "All", labelAr: "الكل" },
  { id: "top-rated", label: "Highest rating", labelAr: "الأعلى تقييماً" },
  { id: "most-popular", label: "Most requested", labelAr: "الأكثر طلباً" },
  { id: "best-offer", label: "Best offer", labelAr: "أفضل عرض" },
  { id: "best-campus", label: "Best campus", labelAr: "أفضل حرم" },
  { id: "best-match", label: "Best match", labelAr: "أفضل تطابق" },
];

/* ---------------- Card ---------------- */

function InstituteCard({ item, style, currency = "SAR", isArabic = false, href }) {
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
      className="shrink-0 rounded-3xl bg-white block"
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
            toggleCompare(interactionType, item.id);
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

        {tagLabel ? (
          <div className="absolute right-4 top-4 rounded-md bg-[rgba(0,0,0,0.4)] px-3 py-1 text-xs font-normal text-white backdrop-blur">
            {tagLabel}
          </div>
        ) : null}

        {discountLabel ? (
          <div className="absolute right-4 top-12 mt-1 rounded-md bg-[#E53935] px-3 py-1 text-xs font-normal text-white">
            {discountLabel}
          </div>
        ) : null}
      </div>

      <div className="px-6 pb-5">
        <div className="flex items-center gap-1 text-sm">
          {Array.from({ length: totalStars }).map((_, idx) => (
            <FontAwesomeIcon
              key={idx}
              icon={idx < filledStars ? faStarSolid : faStarRegular}
              className={idx < filledStars ? "text-[#FFC107]" : "text-slate-300"}
            />
          ))}
        </div>

        <div className="mt-1 flex items-center gap-2 text-[13px] text-slate-600">
          <span>{item.country || (isArabic ? "المملكة المتحدة" : "United Kingdom")}</span>
          {item.flag && (item.flag.startsWith("http") || item.flag.startsWith("/")) ? (
            <img src={item.flag} alt={item.country || "Country"} className="h-4 w-4" />
          ) : item.flag ? (
            <span className="text-lg">{item.flag}</span>
          ) : (
            <span className="text-lg">🌍</span>
          )}
        </div>

        <h3
          className="mt-1 text-[18px] font-extrabold leading-snug text-slate-900"
        >
          {item.name}
        </h3>

        <div className="mt-3">
          <span className="inline-flex rounded-full bg-[#EEF3FF] px-4 py-1 text-[13px] font-normal text-slate-700">
            {item.courseType || (isArabic ? "دورة إنجليزي عامة" : "General English Course")}
          </span>
        </div>

        <div className="mt-4 flex gap-2 text-[14px] text-slate-700 justify-center" dir="ltr">
          {(() => {
            const oldAmount = formatAmount(item.priceOldValue ?? item.priceOld);
            if (!oldAmount) return null;
            return (
              <span className="inline-flex items-center gap-1 text-slate-400 line-through">
                {currencySymbol ? (
                  <span>{currencySymbol}</span>
                ) : (
                  <img
                    src={currencyIcon}
                    alt={currency}
                    className="h-4 w-4 invert"
                  />
                )}
                <span>{formatNumber(oldAmount)}</span>
              </span>
            );
          })()}
          <span className="inline-flex items-center gap-1 font-normal text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img
                src={currencyIcon}
                alt={currency}
                className="h-4 w-4 invert"
              />
            )}
            <span>
              {(() => {
                const newAmount = formatAmount(item.priceNewValue ?? item.priceNew);
                return newAmount ? formatNumber(newAmount) : item.priceNew || "-";
              })()}
            </span>
          </span>
          <span className="text-slate-600">{isArabic ? "/ أسبوع" : "/ week"}</span>
        </div>
      </div>
    </CardTag>
  );
}

/* ---------------- Slider ---------------- */

export default function PartnerInstitutesSlider({ institutes: fallbackInstitutes = INSTITUTES }) {
  const viewportRef = useRef(null);
  const tabsScrollRef = useRef(null);
  const tabRefs = useRef({});
  const { data: offersData } = useApi("/courseenglish/offers");
  const { currency, language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const locale = getCourseEnglishMessages(language);

  const offersTags = offersData?.language_course_tags ?? [];
  const offersCourses = offersData?.language_courses ?? [];
  const localizedFallbackInstitutes = isArabic
    ? INSTITUTES_AR
    : fallbackInstitutes;

  const institutes = useMemo(() => {
    if (!offersCourses.length) return localizedFallbackInstitutes;
    return offersCourses.map((course) => {
      const priceNewValue =
        currency === "SAR"
          ? course.price_new_sar ?? course.price_new_gbp ?? course.price_new
          : course.price_new_gbp ?? course.price_new_sar ?? course.price_new;
      const priceOldValue =
        currency === "SAR"
          ? course.price_old_sar ?? course.price_old_gbp ?? course.price_old
          : course.price_old_gbp ?? course.price_old_sar ?? course.price_old;
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
  }, [offersCourses, fallbackInstitutes, currency, isArabic]);

  const tabs = useMemo(() => {
    if (!offersTags.length)
      return TABS.map((tab) => ({
        ...tab,
        label: pickLang(language, tab.label, tab.labelAr),
      }));
    return [
      { id: "all", label: pickLang(language, "All", "الكل") },
      ...offersTags.map((tag) => ({
        id: String(tag.id),
        label: isArabic ? tag.ar_name || tag.name : tag.name || tag.ar_name,
      })),
    ];
  }, [offersTags, isArabic, language]);

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [activeTab, setActiveTab] = useState("all");
  const [underlineStyle, setUnderlineStyle] = useState({
    width: 0,
    left: 0,
  });

  const partnersMeta = locale?.pages?.homepage?.partners_offers ?? {};
  const offerHero = locale?.pages?.offers?.hero ?? {};
  const mobileHeading = offerHero?.headline || pickLang(language, "أفضل العروض", "أفضل العروض");
  const desktopHeading =
    partnersMeta?.heading || pickLang(language, "Partner institutes around the world", "شركاؤنا حول العالم");
  const viewAllLabel = partnersMeta?.view_all || pickLang(language, "View all schools", "عرض كل المعاهد");
  const viewAllUrl = "/language-institutes";

  const filtered = useMemo(() => {
    if (activeTab === "all") return institutes;
    const tagId = Number(activeTab);
    return institutes.filter(
      (item) => item.tagId === tagId || item.tagSlug === activeTab || item.tag === activeTab
    );
  }, [institutes, activeTab]);

  // NEW: how many cards visible for current breakpoint
  const [visible, setVisible] = useState(3);
  // NEW: how much of the next card peeks on mobile (0.1 = 10%)
  const [peek, setPeek] = useState(0);

  // breakpoint logic: xl=3, md=2, mobile=1 + peek
  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;
      if (w >= 1536) {
        setVisible(4);
        setPeek(0);
      } else if (w >= 1280) {
        setVisible(3);
        setPeek(0);
      } else if (w >= 768) {
        setVisible(2);
        setPeek(0);
      } else {
        setVisible(1);
        setPeek(0.15); // 10% of next card
      }
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  useEffect(() => {
    if (!tabs.length) return;
    if (!tabs.find((tab) => tab.id === activeTab)) {
      setActiveTab(tabs[0].id);
    }
  }, [tabs, activeTab]);

  const len = filtered.length;

  /* ----- card width (responsive) ----- */
  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      let width;

      if (visible === 1 && peek > 0) {
        // mobile: 1 full + 10% next
        width = Math.floor(w * (1 - peek));
      } else {
        const totalGap = GAP * (visible - 1);
        width = Math.max(260, Math.floor((w - totalGap) / visible));
      }

      setCardW(width);
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible, peek]);

  const maxIndex = Math.max(0, len - visible);

  useEffect(() => {
    setIndex(0);
  }, [activeTab, visible, len]);

  /* ----- autoplay (all breakpoints) ----- */
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

  /* ----- mobile tab scroll arrows + underline sync ----- */

  const scrollTabs = (direction) => {
    const el = tabsScrollRef.current;
    if (!el) return;
    const amount = 120;
    el.scrollBy({
      left: direction === "left" ? -amount : amount,
      behavior: "smooth",
    });
  };

  const updateUnderline = () => {
    const container = tabsScrollRef.current;
    const activeEl = tabRefs.current[activeTab];
    if (!container || !activeEl) return;

    const containerRect = container.getBoundingClientRect();
    const activeRect = activeEl.getBoundingClientRect();

    setUnderlineStyle({
      width: activeRect.width,
      left: activeRect.left - containerRect.left,
    });
  };

  useEffect(() => {
    updateUnderline();
  }, [activeTab]);

  useEffect(() => {
    const el = tabsScrollRef.current;
    if (!el) return;
    const handler = () => updateUnderline();
    el.addEventListener("scroll", handler);
    return () => el.removeEventListener("scroll", handler);
  }, [activeTab]);

  // mobile tab track/thumb (separate bar)
  const [tabThumb, setTabThumb] = useState({ width: 0, left: 0 });
  const tabTrackRef = useRef(null);

  const computeTabThumb = (customLeft) => {
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
  };

  useEffect(() => {
    computeTabThumb();
    const el = tabsScrollRef.current;
    if (!el) return;
    const onScroll = () => computeTabThumb();
    el.addEventListener("scroll", onScroll, { passive: true });
    const ro = new ResizeObserver(() => computeTabThumb());
    ro.observe(el);
    const roTrack = new ResizeObserver(() => computeTabThumb());
    if (tabTrackRef.current) roTrack.observe(tabTrackRef.current);
    return () => {
      el.removeEventListener("scroll", onScroll);
      ro.disconnect();
      roTrack.disconnect();
    };
  }, []);

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

  return (
    <section  className="bg-[#EAF2FF] py-10 sm:py-16">
      <div className="px-2 md:px-20 xl:px-20 2xl:px-40">
        {/* ---------- MOBILE HEADER ---------- */}
        <div className="md:hidden">
          <div className="my-4 flex items-center justify-between">
            <div className="flex px-4 text-center">
              <h2 className="text-2xl font-extrabold leading-snug text-slate-900">
                {mobileHeading}
              </h2>
            </div>
            <div className="flex items-center text-md font-normal text-[#1F63AE]">
              <Link href={viewAllUrl} className="pb-1">
                {viewAllLabel}
              </Link>
              <div className="text-lg">
                <FontAwesomeIcon icon={faChevronRight} />
              </div>
            </div>
          </div>

          {/* MOBILE TABS + ARROWS */}
          <div className="relative my-6">
            {/* left / right arrows */}
            <button
              type="button"
              onClick={() => scrollTabs("left")}
              className="absolute left-0 top-1/2 z-10 -translate-y-3/5 rounded-full bg-transparent p-1 text-slate-500"
              aria-label="Scroll tabs left"
            >
              <FontAwesomeIcon icon={faChevronLeft} />
            </button>
            <button
              type="button"
              onClick={() => scrollTabs("right")}
              className="absolute right-0 top-1/2 z-10 -translate-y-3/5 rounded-full bg-transparent p-1 text-slate-500"
              aria-label="Scroll tabs right"
            >
              <FontAwesomeIcon icon={faChevronRight} />
            </button>

            {/* wrapper so baseline only spans tags area */}
            <div className="mx-6 relative">
              {/* baseline */}
              <div className="pointer-events-none absolute left-0 right-0 bottom-0 h-[4px] ">
                <div
                  className="absolute h-full rounded-full bg-[#1F63AE] transition-all"
                  style={{
                    width: underlineStyle.width,
                    left: underlineStyle.left,
                  }}
                />
              </div>

              {/* scrollable tabs */}
              <div
                ref={tabsScrollRef}
                className="flex gap-6 overflow-x-auto pb-2 text-sm font-normal text-slate-500 scrollbar-hide"
              >
                {tabs.map((tab) => {
                  const isActive = activeTab === tab.id;
                  return (
                    <button
                      key={tab.id}
                      type="button"
                      ref={(el) => (tabRefs.current[tab.id] = el)}
                      onClick={() => activateTab(tab.id)}
                      className={`whitespace-nowrap ${
                        isActive ? "text-[#1F63AE]" : "text-slate-500"
                      }`}
                    >
                      {tab.label}
                    </button>
                  );
                })}
              </div>
            </div>
          </div>
          <div className="relative mt-2 px-6">
            <div
              className="pointer-events-none h-2 rounded-full bg-[#dbe5f4]"
              ref={tabTrackRef}
            >
              <div
                className="absolute h-2 rounded-full bg-white shadow-[0_0_6px_rgba(255,255,255,0.6)]"
                style={{
                  width: `${tabThumb.width}px`,
                  transform: `translateX(${tabThumb.left}px)`,
                }}
              />
            </div>
          </div>
        </div>

        {/* ---------- DESKTOP HEADER ---------- */}
        <div className="hidden flex-col items-center text-center md:flex">
          <h2 className="text-3xl font-extrabold text-slate-900 sm:text-4xl">
            {desktopHeading}
          </h2>
        </div>

        {/* DESKTOP TABS */}
        <div className="mt-8 hidden justify-center gap-8 text-sm font-normal text-slate-500 md:flex">
          {tabs.map((tab) => {
            const isActive = activeTab === tab.id;
            return (
              <button
                key={tab.id}
                type="button"
                onClick={() => setActiveTab(tab.id)}
                className="relative pb-2 transition-colors"
              >
                <span
                  className={isActive ? "text-[#1F63AE]" : "text-slate-500"}
                >
                  {tab.label}
                </span>
                {isActive && (
                  <span
                    className="pointer-events-none absolute left-0 right-0 bottom-0
                               mx-auto h-[4px] rounded-full bg-[#1F63AE]"
                  />
                )}
              </button>
            );
          })}
        </div>

        {/* ---------- SLIDER ---------- */}
        <div className="relative mt-8 sm:mt-10 mx-2 md:mx-6 lg:mx-20">
          {len > visible && (
            <>
              {/* nav buttons are md+ only, so mobile has none */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous institutes"
                className="absolute -left-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full border bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 md:grid"
                style={{ borderColor: TOKENS.border }}
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={20}
                  height={20}
                  className="h-5 w-5"
                />
              </button>

              <button
                type="button"
                onClick={next}
                aria-label="Next institutes"
                className="absolute -right-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full p-4 text-white md:grid"
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

          <div ref={viewportRef} className="mt-4 overflow-hidden">
            <div className="flex" style={trackStyle}>
              {filtered.map((inst) => (
                <InstituteCard
                  key={inst.id}
                  item={inst}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                  href={inst.slug ? `/language-institutes/${inst.slug}?course_id=${inst.id}` : undefined}
                />
              ))}
            </div>
          </div>
        </div>

        {/* Bottom “View all schools” button (desktop) */}
        <div className="mt-8 hidden justify-center md:flex">
          <Link
            href={viewAllUrl}
            className="rounded-full bg-[#1F63AE] px-8 py-3 text-sm font-normal text-white shadow-[0_10px_25px_rgba(31,99,174,0.4)] hover:brightness-110"
          >
            {viewAllLabel}
          </Link>
        </div>
      </div>
    </section>
  );
}
