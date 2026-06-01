"use client";

/* eslint-disable @next/next/no-img-element */
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faStar,
  faSignal,
  faBookOpen,
  faClock,
  faCircleInfo,
  faCircleQuestion,
} from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { getImageUrl } from "@/lib/api";
import { useCourseEnglishInteractions } from "@/lib/interactions";

function stopCardNav(event) {
  event.preventDefault();
  event.stopPropagation();
}

function cleanPart(value) {
  return String(value ?? "")
    .replace(/\s*-\s*-\s*/g, " - ")
    .replace(/\s{2,}/g, " ")
    .replace(/^\s*-\s*|\s*-\s*$/g, "")
    .trim();
}

function stripCityFromSchoolName(school, city) {
  const s = cleanPart(school);
  const c = cleanPart(city);
  if (!s || !c) return s;
  const escaped = c.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  return cleanPart(
    s
      .replace(new RegExp(`\\s*-\\s*${escaped}$`, "i"), "")
      .replace(new RegExp(`^${escaped}\\s*-\\s*`, "i"), "")
  );
}

function buildDetailUrl(institute, searchParamsOverride) {
  const baseUrl = "/language-institutes";
  const schoolSlug = institute.school_slug || institute.slug || institute.id;
  const queryParams = new URLSearchParams();
  const weeksParam = searchParamsOverride?.weeks ?? institute.weeks_param;
  const startDateParam = searchParamsOverride?.start_date ?? institute.start_date_param;
  if (weeksParam) queryParams.set("weeks", weeksParam);
  if (startDateParam) queryParams.set("start_date", startDateParam);
  queryParams.set("course_id", institute.id);
  const queryString = queryParams.toString();
  return `${baseUrl}/${schoolSlug}${queryString ? `?${queryString}` : ""}`;
}

export default function MobileInstituteCard({
  institute,
  type = "language_courses",
  searchParamsOverride = null,
}) {
  const { language, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";
  const page = t("pages.language_institutes", {});
  const cardCopy = page?.mobile?.card ?? {};

  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const inWishlist = isInWishlist(type, institute.id);
  const inCompare = isInCompare(type, institute.id);
  const compareWeeks = Number(searchParamsOverride?.weeks || institute.weeks_param || 12) || 12;

  const cityEn =
    institute.city ||
    institute.city_name ||
    (typeof institute.location === "string" ? institute.location.split(",")[0]?.trim() : null);
  const cityAr = institute.city_ar || institute.city_ar_name || cityEn;

  const schoolNameEn = stripCityFromSchoolName(
    institute.school_name_en || institute.name_en || institute.name || institute.school_name,
    cityEn
  );
  const schoolNameAr = stripCityFromSchoolName(
    institute.school_name_ar || institute.name_ar || institute.ar_name || institute.school_ar_name,
    cityAr
  );

  const name = isArabic
    ? schoolNameAr !== schoolNameEn
      ? [schoolNameAr, cleanPart(cityAr), cleanPart(schoolNameEn)].filter(Boolean).join(" - ")
      : [cleanPart(schoolNameAr), cleanPart(cityAr)].filter(Boolean).join(" - ")
    : [cleanPart(schoolNameEn), cleanPart(cityEn)].filter(Boolean).join(" - ");

  const courseName = isArabic
    ? institute.course_ar_name || institute.course_name
    : institute.course_name || institute.course_ar_name;

  const courseType = isArabic
    ? institute.course_type_ar || institute.course_type
    : institute.course_type || institute.course_type_ar;

  const location = isArabic
    ? institute.country_ar || institute.location_ar || institute.location
    : institute.country_en || institute.location || institute.city;

  const priceValue = getCoursePrice(institute, currency, "new");
  const oldPriceValue = getCoursePrice(institute, currency, "old");

  const discountPercentage =
    oldPriceValue && priceValue && oldPriceValue > priceValue
      ? Math.round(((oldPriceValue - priceValue) / oldPriceValue) * 100)
      : (institute.discount_percent ?? 0);

  const isTopRated =
    institute.is_preferred || institute.tags?.includes("Top Rated") || institute.tags?.includes("top rated");

  const imageSrc = getImageUrl(institute.image || institute.logo) || "/assets/hero.png";
  const detailUrl = buildDetailUrl(institute, searchParamsOverride);
  const rating = Math.round(Number(institute.rating || 5));

  const topRatedLabel = cardCopy.top_rated || (isArabic ? "أعلى تقييم" : "Top rated");
  const viewDetailsLabel = cardCopy.view_details || (isArabic ? "عرض التفاصيل" : "View details");
  const bestPriceLabel =
    cardCopy.best_price_guarantee || (isArabic ? "ضمان أفضل الأسعار" : "Best price guarantee");
  const noChargeLabel =
    cardCopy.no_charge_note || (isArabic ? "لن يتم خصم اي مبلغ منك بعد" : "You won't be charged yet");
  const perWeekLabel = cardCopy.per_week || (isArabic ? "/ للاسبوع" : "/ week");

  const hoursLabel = institute.hours
    ? isArabic
      ? `${institute.hours} ساعة`
      : `${institute.hours} hours`
    : isArabic
      ? "15 ساعة"
      : "15 hours";

  const lessonsLabel = isArabic
    ? `${institute.lessons || "20"} درس`
    : `${institute.lessons || "20"} lessons`;

  const handleWishlistAction = async (e) => {
    stopCardNav(e);
    await toggleWishlist(type, institute.id);
  };

  const handleCompareAction = async (e) => {
    stopCardNav(e);
    await toggleCompare(type, institute.id, compareWeeks);
  };

  return (
    <article className="overflow-hidden rounded-2xl border border-[#DCE6F1] bg-white shadow-[0_8px_24px_rgba(15,23,42,0.06)]">
      <div className="p-2">
        <div className="relative h-[183px] overflow-hidden rounded-xl bg-slate-100">
          <img
            src={imageSrc}
            alt={name}
            className="h-full w-full object-cover"
            loading="lazy"
            onError={(e) => {
              e.currentTarget.src = "/assets/hero.png";
            }}
          />

          <div className="absolute left-3 top-3 z-20 flex flex-col gap-2">
            <button
              type="button"
              className="flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white shadow-[0_6px_14px_rgba(15,23,42,0.08)]"
              onClick={handleWishlistAction}
              aria-label={isArabic ? "المفضلة" : "Wishlist"}
            >
              <Image
                src={inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg"}
                alt=""
                width={18}
                height={18}
                className="h-[18px] w-[18px]"
              />
            </button>
            <button
              type="button"
              className="flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white shadow-[0_6px_14px_rgba(15,23,42,0.08)]"
              onClick={handleCompareAction}
              aria-label={isArabic ? "مقارنة" : "Compare"}
            >
              <Image
                src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
                alt=""
                width={18}
                height={18}
                className="h-[18px] w-[18px]"
              />
            </button>
          </div>

          <div className="absolute right-3 top-3 z-10 flex flex-col items-end gap-2">
            {isTopRated ? (
              <span className="rounded-md bg-[#8F9BA6] px-3 py-1 text-[12px] font-normal text-white">
                {topRatedLabel}
              </span>
            ) : null}
            {discountPercentage > 0 ? (
              <span className="rounded-md bg-[#E32636] px-3 py-1 text-[12px] font-normal text-white">
                {isArabic ? `خصم ${discountPercentage}%` : `${discountPercentage}% OFF`}
              </span>
            ) : null}
          </div>

          <div className="pointer-events-none absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 items-center gap-1.5">
            <span className="h-1.5 w-1.5 rounded-full bg-white/50" />
            <span className="h-1.5 w-5 rounded-full bg-white" />
            <span className="h-1.5 w-1.5 rounded-full bg-white/50" />
            <span className="h-1.5 w-1.5 rounded-full bg-white/50" />
          </div>
        </div>
      </div>

      <div className="px-4 pb-4">
        <div className="mb-3 flex items-center justify-between gap-3">
          <div className="flex items-center gap-2 text-[13px] font-normal text-slate-600">
            {institute.flag && String(institute.flag).startsWith("http") ? (
              <img src={institute.flag} alt="" className="h-6 w-6 rounded-full object-cover" />
            ) : (
              <span className="text-lg leading-none">{institute.flag || "🇬🇧"}</span>
            )}
            <span>{location || (isArabic ? "المملكة المتحدة" : "United Kingdom")}</span>
          </div>
          <div className="flex gap-0.5 text-[#F59E0B]">
            {Array.from({ length: 5 }).map((_, i) => (
              <FontAwesomeIcon
                key={i}
                icon={faStar}
                className={`h-[18px] w-[18px] ${i < rating ? "" : "text-[#E2E8F0]"}`}
              />
            ))}
          </div>
        </div>

        <h3 className="mb-3 text-start text-base font-bold leading-snug text-slate-900">{name}</h3>

        <div className="mb-3 flex items-center justify-between text-[13px] text-[#64748B]">
          <div className="flex items-center gap-1.5">
            <FontAwesomeIcon icon={faClock} className="h-4 w-4 text-[#0B5ED7]" />
            <span>{hoursLabel}</span>
          </div>
          <div className="flex items-center gap-1.5">
            <FontAwesomeIcon icon={faBookOpen} className="h-4 w-4 text-[#0B5ED7]" />
            <span>{lessonsLabel}</span>
          </div>
          <div className="flex items-center gap-1.5">
            <FontAwesomeIcon icon={faSignal} className="h-4 w-4 text-[#0B5ED7]" />
            <span>{institute.level || "A1"}</span>
          </div>
        </div>

        <div className="mb-3 inline-flex rounded-xl bg-[#E8EDF3] px-3 py-1.5 text-sm font-normal text-slate-600">
          {courseName || courseType || (isArabic ? "دورة لغة انجليزية عامة" : "General English course")}
        </div>

        <div className="mb-4 flex items-center gap-2 text-sm text-slate-700">
          <span className="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#E8F0FA]">
            <Image src="/assets/icons/fire.svg" alt="" width={12} height={12} className="h-3 w-3" />
          </span>
          <span>{bestPriceLabel}</span>
          <FontAwesomeIcon icon={faCircleQuestion} className="h-4 w-4 shrink-0 text-slate-400" />
        </div>

        <div className="mb-3 border-t border-[#E8EEF4]" />

        <div className="flex items-end justify-between gap-3">
          <div className="min-w-0 flex-1 text-start">
            <div className="flex flex-wrap items-baseline justify-start gap-1 text-slate-900">
              <span className="text-lg font-bold">
                <CurrencyAmount currency={currency} amount={priceValue} />
              </span>
              <span className="text-sm font-normal text-slate-700">{perWeekLabel}</span>
            </div>
            {oldPriceValue ? (
              <div className="mt-0.5 text-base text-slate-400 line-through">
                <CurrencyAmount currency={currency} amount={oldPriceValue} muted />
              </div>
            ) : null}
            <div className="mt-2 flex items-center justify-start gap-1.5 text-xs text-slate-500">
              <FontAwesomeIcon icon={faCircleInfo} className="h-3 w-3 shrink-0" />
              <span>{noChargeLabel}</span>
            </div>
          </div>

          <Link
            href={detailUrl}
            className="inline-flex h-11 shrink-0 items-center justify-center rounded-xl bg-[#0057B7] px-5 text-sm font-medium text-white transition hover:bg-[#004494]"
          >
            {viewDetailsLabel}
          </Link>
        </div>
      </div>
    </article>
  );
}
