"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import MobileInfiniteCarousel from "@/components/mobile/mobile-infinite-carousel";

const TOKENS = { border: "#E4EDF8", primary: "#1F63AE" };

function CourseCard({ course, currency = "SAR", isArabic = false, href, t }) {
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "online_courses";
  const inWishlist = isInWishlist(interactionType, course.id);
  const inCompare = isInCompare(interactionType, course.id);
  const priceNewValue = getCoursePrice(course, currency, "new");
  const priceOldValue = getCoursePrice(course, currency, "old");
  const priceUnit =
    course.priceUnit === "per course"
      ? isArabic
        ? "/ دورة"
        : "/ course"
      : t("pages.homepage.partners_offers.per_week", "/ week");

  const CardTag = href ? Link : "article";
  const cardProps = href ? { href } : {};

  return (
    <CardTag
      {...cardProps}
      className="shrink-0 w-[82vw] snap-start rounded-[24px] bg-white block overflow-hidden"
      style={{ border: `1px solid ${TOKENS.border}` }}
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
          className="absolute right-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md"
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
            toggleCompare(interactionType, course.id);
          }}
          className="absolute right-3 top-13 grid h-8 w-8 place-items-center rounded-full bg-white text-slate-700 shadow-md"
        >
          <Image
            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
            alt="Compare"
            width={16}
            height={16}
            className="h-[16px] w-[16px]"
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

      <div className="px-4 pb-4 text-start">
        <div className="flex justify-start">
          <span className="inline-flex rounded-full bg-[#E7F2FF] px-4 py-1 text-[12px] font-normal text-[#1F63AE]">
            {course.provider}
          </span>
        </div>

        <div className="mt-3 flex items-center gap-1.5 text-[12px] text-slate-500 justify-start">
          {course.flag ? (
            <img src={course.flag} alt={course.country} className="h-3.5 w-3.5" />
          ) : (
            <span className="text-sm leading-none">🌍</span>
          )}
          <span>{course.country}</span>
        </div>

        <h3 className="mt-1 text-[16px] font-bold leading-snug text-slate-900  line-clamp-2">
          {course.title}
        </h3>

        <div className="mt-3.5 flex items-center gap-1">
          <CurrencyAmount
            currency={currency}
            amount={priceNewValue}
            className="inline-flex items-center gap-0.5 font-bold text-slate-900"
            iconClassName="h-3.5 w-3.5"
          />
          {priceOldValue ? (
            <CurrencyAmount
              currency={currency}
              amount={priceOldValue}
              className="inline-flex items-center gap-0.5 text-slate-400 line-through text-xs ml-1"
              iconClassName="h-3 w-3"
              muted
            />
          ) : null}
          <span className="text-slate-500 font-medium ml-1">{priceUnit}</span>
        </div>
      </div>
    </CardTag>
  );
}

export default function MobileOnline() {
  const { data } = useApi("/coursesat/home/online");
  const { language, direction, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";

  const heading = t(
    "pages.homepage.online_courses.mobile_heading",
    t("pages.homepage.online_courses.heading", "Online Courses")
  );
  const ctaText = t(
    "pages.homepage.online_courses.mobile_view_all",
    t("pages.homepage.online_courses.view_all", "All courses")
  );
  const ctaUrl = "/online-courses";

  const courses = useMemo(() => {
    const apiCourses = data?.online_courses ?? [];
    return apiCourses.map((course) => {
      const providerName = course.provider || course.name || course.title;
      const providerAr = course.provider_ar_name || course.ar_name || course.ar_title || course.provider;

      return {
        id: course.id,
        title: isArabic ? course.ar_title || course.title : course.title || course.ar_title,
        provider: isArabic ? providerAr : providerName,
        slug: course.slug,
        schoolSlug: course.school_slug,
        country: isArabic ? course.country_ar_name || course.country : course.country || course.country_ar_name,
        flag: getImageUrl(course.flag),
        mode: isArabic ? "عن بُعد" : course.mode || "Online",
        discountLabel: isArabic
          ? course.tag_ar_name || course.discountLabel
          : course.discountLabel || course.tag,
        prices: course.prices,
        priceUnit: course.price_unit,
        image: getImageUrl(course.image) || "/assets/hero.png",
        url: course.url,
      };
    });
  }, [data?.online_courses, isArabic]);

  if (courses.length === 0) return null;

  return (
    <section className="block md:hidden bg-[#EEF4FB] py-10 w-full" dir={direction}>
      <div className="px-4">
        <div className="flex items-center justify-between gap-3">
          <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[55%] text-start">
            {heading}
          </h2>
          <Link
            href={ctaUrl}
            className="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-[#1F63AE] transition-colors hover:text-[#135FAE]"
          >
            <span>{ctaText}</span>
            <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
          </Link>
        </div>
      </div>

      <div className="mt-6">
        <MobileInfiniteCarousel
          items={courses}
          getItemKey={(course) => course.id}
          renderItem={(course, _idx, key) => (
            <CourseCard
              key={key}
              course={course}
              currency={currency}
              isArabic={isArabic}
              href={
                course.url ||
                (course.schoolSlug
                  ? `/online-courses/${course.schoolSlug}?course_id=${course.id}`
                  : course.slug
                    ? `/online-course/${course.slug}?course_id=${course.id}`
                    : undefined)
              }
              t={t}
            />
          )}
        />
      </div>
    </section>
  );
}
