"use client";

/* eslint-disable @next/next/no-img-element */
import Image from "next/image";
import Link from "next/link";
import { getImageUrl } from "@/lib/api";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { useCourseEnglishInteractions } from "@/lib/interactions";

const TOKENS = {
  border: "#E4EDF8",
};

export function mapOnlineCourse(course, isArabic) {
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
}

export function onlineCourseHref(course) {
  return (
    course.url ||
    (course.schoolSlug
      ? `/online-courses/${course.schoolSlug}?course_id=${course.id}`
      : course.slug
        ? `/online-courses/${course.slug}?course_id=${course.id}`
        : undefined)
  );
}

export default function OnlineCourseCard({
  course,
  currency = "SAR",
  isArabic = false,
  href,
  t,
  className = "",
  style,
}) {
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
      className={`rounded-[24px] bg-white block overflow-hidden transition duration-300 hover:shadow-lg ${className}`}
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
          {course.flag ? (
            <img src={course.flag} alt={course.country} className="h-8 w-8" />
          ) : (
            <span className="text-xl leading-none">🌍</span>
          )}
          <span>{course.country}</span>
        </div>

        <h3 className="mt-2 text-[18px] font-bold leading-snug text-slate-900 line-clamp-2">
          {course.title}
        </h3>

        <div className="my-4 flex items-center gap-1">
          <CurrencyAmount
            currency={currency}
            amount={priceNewValue}
            className="inline-flex items-center gap-1 font-bold text-slate-900"
            iconClassName="h-4 w-4"
          />
          {priceOldValue ? (
            <CurrencyAmount
              currency={currency}
              amount={priceOldValue}
              className="inline-flex items-center gap-1 text-slate-400 line-through text-xs ml-1"
              iconClassName="h-3.5 w-3.5"
              muted
            />
          ) : null}
          <span className="text-slate-500 font-medium ml-1">{priceUnit}</span>
        </div>
      </div>
    </CardTag>
  );
}
