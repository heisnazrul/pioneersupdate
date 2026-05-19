"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";
import { pickLang } from "@/lib/i18nFallback";
/* --- Dummy data for online courses (.png / remote images) --- */
const COURSES = [
  {
    id: 1,
    title: "CES School – Online General English",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "20% OFF",
    priceNew: "£2,527",
    priceOld: "£2,800",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/4144096/pexels-photo-4144096.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 2,
    title: "Intensive Academic English – Live Online",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "15% OFF",
    priceNew: "£2,100",
    priceOld: "£2,470",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 3,
    title: "IELTS Preparation Evening Course",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "10% OFF",
    priceNew: "£1,950",
    priceOld: "£2,170",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/4143800/pexels-photo-4143800.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 4,
    title: "Business English for Professionals",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "20% OFF",
    priceNew: "£2,900",
    priceOld: "£3,620",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/6476587/pexels-photo-6476587.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 5,
    title: "Conversation Club – Speaking Focus",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "25% OFF",
    priceNew: "£1,400",
    priceOld: "£1,860",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/8867431/pexels-photo-8867431.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 6,
    title: "Cambridge Exam Prep (FCE/CAE)",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "20% OFF",
    priceNew: "£3,050",
    priceOld: "£3,820",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/4144100/pexels-photo-4144100.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 7,
    title: "Pronunciation & Accent Coaching",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "15% OFF",
    priceNew: "£1,980",
    priceOld: "£2,330",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/6954163/pexels-photo-6954163.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 8,
    title: "English for Travel & Tourism",
    provider: "English Academy Online",
    country: "United Kingdom",
    flag: "🇬🇧",
    mode: "Online",
    discountLabel: "20% OFF",
    priceNew: "£1,750",
    priceOld: "£2,190",
    priceUnit: "per week",
    image:
      "https://images.pexels.com/photos/3861964/pexels-photo-3861964.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
];

const COURSES_AR = [
  {
    id: 1,
    title: "CES أونلاين – إنجليزي عام",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 20%",
    priceNew: "﷼9,470",
    priceOld: "﷼10,500",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/4144096/pexels-photo-4144096.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 2,
    title: "إنجليزي مكثف أكاديمي – مباشر",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 15%",
    priceNew: "﷼7,880",
    priceOld: "﷼9,270",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 3,
    title: "مسار مسائي للتحضير لآيلتس",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 10%",
    priceNew: "﷼7,320",
    priceOld: "﷼8,140",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/4143800/pexels-photo-4143800.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 4,
    title: "إنجليزي للأعمال للمهنيين",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 20%",
    priceNew: "﷼11,450",
    priceOld: "﷼14,280",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/6476587/pexels-photo-6476587.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 5,
    title: "نادي المحادثة – تركيز على التحدث",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 25%",
    priceNew: "﷼5,520",
    priceOld: "﷼7,340",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/8867431/pexels-photo-8867431.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 6,
    title: "التحضير لاختبارات كامبريدج FCE/CAE",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 20%",
    priceNew: "﷼12,040",
    priceOld: "﷼15,060",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/4144100/pexels-photo-4144100.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 7,
    title: "تحسين النطق واللهجة",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 15%",
    priceNew: "﷼7,810",
    priceOld: "﷼9,190",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/6954163/pexels-photo-6954163.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
  {
    id: 8,
    title: "إنجليزي للسفر والسياحة",
    provider: "أكاديمية إنجليزية أونلاين",
    country: "المملكة المتحدة",
    flag: "🇬🇧",
    mode: "عن بُعد",
    discountLabel: "خصم 20%",
    priceNew: "﷼6,900",
    priceOld: "﷼8,640",
    priceUnit: "لكل أسبوع",
    image:
      "https://images.pexels.com/photos/3861964/pexels-photo-3861964.jpeg?auto=compress&cs=tinysrgb&w=800",
  },
];

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28;
const AUTO_MS = 4000;

/* ===== CARD (LTR, fixed 4:3 image ratio) ===== */
function CourseCard({ course, style, currency = "SAR", isArabic = false, href }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "online_courses";
  const inWishlist = isInWishlist(interactionType, course.id);
  const inCompare = isInCompare(interactionType, course.id);
  const currencyIcon = "/assets/sar.svg";
  const currencySymbol = currency === "GBP" ? "£" : null;
  const priceNewText =
    course.priceNewValue !== undefined && course.priceNewValue !== null
      ? Number(course.priceNewValue).toLocaleString("en-GB", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      : course.priceNew;
  const priceOldText =
    course.priceOldValue !== undefined && course.priceOldValue !== null
      ? Number(course.priceOldValue).toLocaleString("en-GB", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      : course.priceOld;
  const CardTag = href ? Link : "article";
  const cardProps = href ? { href } : {};
  return (
    <CardTag
      {...cardProps}
      className="shrink-0 rounded-[24px] bg-white block"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      {/* Image + overlays with ratio box */}
      <div className="relative m-3 overflow-hidden rounded-[22px] bg-[#E7F0FB]">
        {/* 4:3 ratio box (height = 75% of width) */}
        <div className="relative w-full pb-[75%]">
          <img
            src={course.image}
            alt={course.title}
            className="absolute inset-0 h-full w-full rounded-[22px] object-cover"
            loading="lazy"
          />
        </div>

        {/* wishlist heart */}
        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, course.id);
          }}
          className="absolute right-3 top-3 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md"
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
            toggleCompare(interactionType, course.id);
          }}
          className="absolute right-3 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={18}
            height={18}
            className="h-[18px] w-[18px]"
          />
        </button>

        {/* online pill */}
        <div className="absolute left-3 top-3 rounded-full bg-[#1F63AE] px-3 py-1 text-xs font-normal text-white shadow">
          {course.mode}
        </div>

        {/* discount pill */}
        {course.discountLabel ? (
          <div className="absolute left-3 top-12 mt-1 rounded-full bg-[#E53935] px-3 py-1 text-xs font-normal text-white shadow">
            {course.discountLabel}
          </div>
        ) : null}
      </div>

      {/* body */}
      <div className="px-5 pb-4">
        {/* provider pill */}
        <div className="flex justify-start">
          <span className="inline-flex rounded-full bg-[#E7F2FF] px-4 py-1 text-[13px] font-normal text-[#1F63AE]">
            {course.provider}
          </span>
        </div>

        {/* country + flag */}
        <div className="mt-4 flex items-center gap-2 text-[13px] text-slate-600">
          {course.flag && (course.flag.startsWith("http") || course.flag.startsWith("/")) ? (
            <img src={course.flag} alt={course.country || "Country"} className="h-4 w-4" />
          ) : course.flag ? (
            <span className="text-lg">{course.flag}</span>
          ) : (
            <span className="text-lg">🌍</span>
          )}
          <span>{course.country || (isArabic ? "المملكة المتحدة" : "United Kingdom")}</span>
        </div>

        {/* course title */}
        <h3
          className={`mt-1 text-[18px] font-extrabold leading-snug text-slate-900 ${
            isArabic ? "text-right" : "text-left"
          }`}
        >
          {course.title}
        </h3>

        {/* price row (LTR) */}
        <div className="mt-3 flex items-baseline gap-2 text-[14px] text-slate-700">
          <span className="inline-flex items-center gap-1 font-normal text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img src={currencyIcon} alt={currency} className="h-4 w-4 invert" />
            )}
            <span>{priceNewText}</span>
          </span>
          {priceOldText ? (
            <span className="text-slate-400 line-through">{priceOldText}</span>
          ) : null}
          <span className="text-slate-600">{isArabic ? "/ أسبوع" : "/ week"}</span>
        </div>
      </div>
    </CardTag>
  );
}

/* ===== SLIDER (responsive like your other sliders) ===== */
export default function OnlineCoursesSlider({ courses: fallbackCourses = COURSES }) {
  const viewportRef = useRef(null);
  const { data } = useApi("/courseenglish/home/online");
  const { currency, language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const localizedFallbackCourses = isArabic ? COURSES_AR : fallbackCourses;
  const section = getCourseEnglishMessages(language)?.pages?.homepage?.online_courses ?? {};
  const heading =
    section?.heading || pickLang(language, "Popular Online English Courses", "دورات اللغة الإنجليزية عبر الإنترنت");
  const ctaText = section?.view_all || pickLang(language, "View all programs", "عرض كل البرامج");
  const ctaUrl = "/online-courses";
  const courses = useMemo(() => {
    const apiCourses = data?.online_courses ?? [];
    if (!apiCourses.length) return localizedFallbackCourses;
    return apiCourses.map((course) => {
      const priceNewValue =
        currency === "SAR"
          ? course.price_new_sar ?? course.price_new_gbp ?? course.price_new
          : course.price_new_gbp ?? course.price_new_sar ?? course.price_new;
      const priceOldValue =
        currency === "SAR"
          ? course.price_old_sar ?? course.price_old_gbp ?? course.price_old
          : course.price_old_gbp ?? course.price_old_sar ?? course.price_old;
      const schoolName = course.provider || course.name || course.title;
      const schoolAr = course.provider_ar_name || course.ar_name || course.ar_title || course.provider;
      const cityName =
        course.city || course.city_name || (course.location ? course.location.split(",")[0]?.trim() : null);
      const cityAr = course.city_ar_name || course.city_ar || course.city_name || cityName;
      const displayName = isArabic
        ? [schoolAr, cityAr, schoolName].filter(Boolean).join(" - ")
        : [schoolName, cityName].filter(Boolean).join(" - ");

      return {
        id: course.id,
        title: displayName,
        provider: isArabic ? course.provider_ar_name || course.provider : course.provider || course.provider_ar_name,
        slug: course.school_slug || course.slug,
        country: isArabic ? course.country_ar_name || course.country : course.country || course.country_ar_name,
        flag: course.flag,
        mode: course.mode || (isArabic ? "عن بُعد" : "Online"),
        discountLabel: isArabic ? course.tag_ar_name || course.tag : course.tag || course.tag_ar_name,
        priceNewValue,
        priceOldValue,
        priceNew: priceNewValue,
        priceOld: priceOldValue,
        priceUnit: course.price_unit || (isArabic ? "/ أسبوع" : "per week"),
        image: course.image || course.thumbnail || "/assets/hero.png",
      };
    });
  }, [data, fallbackCourses, currency, isArabic]);
  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);

  // responsive visible count + peek
  const [visible, setVisible] = useState(3);
  const [peek, setPeek] = useState(0);

  const len = courses.length;

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
        setPeek(0.15); // ~15% of the next card
      }
    };

    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  // measure viewport -> card width
  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      let width;

      if (visible === 1 && peek > 0) {
        // 1 full card + peek
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

  // reset index when layout/data changes
  useEffect(() => {
    setIndex(0);
  }, [visible, len]);

  // autoplay
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
    <section className="py-16 sm:py-20 bg-[#EEF4FB]">
      <div className="px-2 md:px-20 xl:px-20 2xl:px-40">
        {/* heading */}
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

        {/* slider */}
        <div className="relative mt-10 sm:mt-10 mx-2 md:mx-6 lg:mx-20">
          {len > visible && (
            <>
              {/* Left arrow (md+) */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous courses"
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
                aria-label="Next courses"
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
              {courses.map((course) => (
                <CourseCard
                  key={course.id}
                  course={course}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                  href={course.slug ? `/online-course/${course.slug}?course_id=${course.id}` : undefined}
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
