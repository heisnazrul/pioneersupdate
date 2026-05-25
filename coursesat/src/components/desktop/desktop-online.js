"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28;
const AUTO_MS = 4000;

/* ===== CARD Component ===== */
function CourseCard({ course, style, currency = "SAR", isArabic = false, href, t }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "online_courses";
  const inWishlist = isInWishlist(interactionType, course.id);
  const inCompare = isInCompare(interactionType, course.id);
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
      className="shrink-0 rounded-[24px] bg-white block overflow-hidden transition duration-300 hover:shadow-lg"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      <div className="relative m-3 overflow-hidden rounded-[22px] bg-[#E7F0FB]">
        <div className="relative w-full pb-[75%]">
          <img
            src={course.image}
            alt={course.title}
            className="absolute inset-0 h-full w-full rounded-[22px] object-cover"
            loading="lazy"
          />
        </div>

        <button
          type="button"
          onClick={(event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleWishlist(interactionType, course.id);
          }}
          className="absolute right-3 top-3 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:scale-110"
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
          className="absolute right-3 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:scale-110"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={18}
            height={18}
            className="h-[18px] w-[18px]"
          />
        </button>

        <div className="absolute left-3 top-3 rounded-full bg-[#1F63AE] px-3 py-1 text-xs font-normal text-white shadow">
          {course.mode}
        </div>

        {course.discountLabel ? (
          <div className="absolute left-3 top-12 mt-1 rounded-full bg-[#E53935] px-3 py-1 text-xs font-normal text-white shadow">
            {course.discountLabel}
          </div>
        ) : null}
      </div>

      <div className="px-5 pb-4 text-start">
        <div className="flex justify-start">
          <span className="inline-flex rounded-full bg-[#E7F2FF] px-4 py-1 text-[13px] font-normal text-[#1F63AE]">
            {course.provider}
          </span>
        </div>

        <div className="mt-4 flex items-center gap-2 text-[16px] text-slate-600 justify-start">
          {course.flag && (course.flag.startsWith("http") || course.flag.startsWith("/")) ? (
            <img src={course.flag} alt={course.country} className="h-8 w-8" />
          ) : course.flag ? (
            <span className="text-lg leading-none">{course.flag}</span>
          ) : (
            <span className="text-lg leading-none">🌍</span>
          )}
          <span>{course.country}</span>
        </div>

        <h3 className="mt-2 text-[18px] font-bold leading-snug text-slate-900 line-clamp-2">
          {course.title}
        </h3>

        <div className="my-4 flex items-center gap-1">
          <span className="inline-flex items-center gap-1 font-bold text-slate-900">
            {currencySymbol ? (
              <span>{currencySymbol}</span>
            ) : (
              <img src={currencyIcon} alt={currency} className="h-4 w-4 invert" />
            )}
            <span>
              {(() => {
                const newAmount = formatAmount(course.priceNewValue ?? course.price);
                return newAmount ? formatNumber(newAmount) : course.price || "-";
              })()}
            </span>
          </span>
          {(() => {
            const oldAmount = formatAmount(course.priceOldValue);
            if (!oldAmount) return null;
            return (
              <span className="text-slate-400 line-through text-xs ml-1">
                {currencySymbol ? currencySymbol : "SAR "} {formatNumber(oldAmount)}
              </span>
            );
          })()}
          <span className="text-slate-500 font-medium ml-1">{t("pages.homepage.partners_offers.per_week", "/ week")}</span>
        </div>
      </div>
    </CardTag>
  );
}

/* ===== MAIN Component ===== */
export default function DesktopOnline() {
  const viewportRef = useRef(null);
  const { data } = useApi("/courseenglish/home/online");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const currency = "SAR";

  const heading = t("pages.homepage.online_courses.heading", "الدراسة عن بعد");
  const subheading = t("pages.homepage.online_courses.subheading", "تعلم اللغة الإنجليزية أينما كنت، بخيارات مرنة تناسب وقتك وأهدافك");
  const ctaText = t("pages.homepage.online_courses.view_all", "View all courses");
  const ctaUrl = "/online-courses";

  const offersOnline = data?.online_courses;

  const dummyOnline = [
    {
      id: 1,
      title: "سي اي اس - CES School",
      ar_title: "سي اي اس - CES School",
      provider: "English Academy Online",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      flag: "🇬🇧",
      price_old: 2000,
      price_new: 2527,
      discountLabel: "خصم 20%",
      image: "https://images.pexels.com/photos/4145153/pexels-photo-4145153.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 2,
      title: "سي اي اس - CES School",
      ar_title: "سي اي اس - CES School",
      provider: "English Academy Online",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      flag: "🇬🇧",
      price_old: 2000,
      price_new: 2527,
      discountLabel: "خصم 20%",
      image: "https://images.pexels.com/photos/5212361/pexels-photo-5212361.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 3,
      title: "سي اي اس - CES School",
      ar_title: "سي اي اس - CES School",
      provider: "English Academy Online",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      flag: "🇬🇧",
      price_old: 2000,
      price_new: 2527,
      discountLabel: "خصم 20%",
      image: "https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg?auto=compress&cs=tinysrgb&w=800",
    },
    {
      id: 4,
      title: "سي اي اس - CES School",
      ar_title: "سي اي اس - CES School",
      provider: "English Academy Online",
      country: "United Kingdom",
      country_ar_name: "المملكة المتحدة",
      flag: "🇬🇧",
      price_old: 2000,
      price_new: 2527,
      discountLabel: "خصم 20%",
      image: "https://images.pexels.com/photos/5151697/pexels-photo-5151697.jpeg?auto=compress&cs=tinysrgb&w=800",
    }
  ];

  const courses = useMemo(() => {
    const apiCourses = offersOnline && offersOnline.length > 0 ? offersOnline : dummyOnline;
    return apiCourses.map((course) => {
      const priceNewValue = course.price_new_sar ?? course.price_new_gbp ?? course.price_new ?? course.price;
      const priceOldValue = course.price_old_sar ?? course.price_old_gbp ?? course.price_old;
      const providerName = course.provider || course.name || course.title;
      const providerAr = course.provider_ar_name || course.ar_name || course.ar_title || course.provider;
      const schoolName = isArabic ? providerAr : providerName;

      return {
        id: course.id,
        title: isArabic ? course.ar_title || course.title : course.title || course.ar_title,
        provider: schoolName,
        slug: course.slug,
        country: isArabic ? course.country_ar_name || course.country : course.country || course.country_ar_name,
        flag: course.flag,
        mode: course.mode || (isArabic ? "عن بُعد" : "Online"),
        discountLabel: isArabic ? course.tag_ar_name || course.discountLabel || course.tag : course.discountLabel || course.tag || course.tag_ar_name,
        priceNewValue,
        priceOldValue,
        image: course.image || "/assets/hero.png",
      };
    });
  }, [offersOnline, isArabic]);

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);

  const len = courses.length;

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
    <section className="hidden md:block py-16 sm:py-20 bg-[#EEF4FB] w-full" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">

        {/* heading */}
        <div className="text-center">
          <h2 className="py-4 text-[32px] sm:text-[40px] font-bold text-[#111827] leading-[1.3]">
            {heading}
          </h2>
          {subheading && (
            <p className="mt-2 text-lg text-slate-500 max-w-2xl mx-auto">
              {subheading}
            </p>
          )}
        </div>

        {/* slider */}
        <div className="relative mt-10 px-4 md:px-12">
          {len > visible && (
            <>
              {/* Left Arrow */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous courses"
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

              {/* Right Arrow */}
              <button
                type="button"
                onClick={next}
                aria-label="Next courses"
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

          <div ref={viewportRef} className="overflow-hidden m-4">
            <div className="flex" style={trackStyle}>
              {courses.map((course) => (
                <CourseCard
                  key={course.id}
                  course={course}
                  style={cardStyle}
                  currency={currency}
                  isArabic={isArabic}
                  href={course.slug ? `/online-course/${course.slug}?course_id=${course.id}` : undefined}
                  t={t}
                />
              ))}
            </div>
          </div>
        </div>

        {/* View All Button */}
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
