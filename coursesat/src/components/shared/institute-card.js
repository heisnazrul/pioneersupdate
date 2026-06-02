"use client";

import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar, faSignal, faBookOpen, faClock, faTrash, faEye } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { getImageUrl } from "@/lib/api";
import { getInstituteCountryFlag, getInstituteCountryFlagFallback } from "@/lib/country-flags";
import { useCourseEnglishInteractions } from "@/lib/interactions";

function stopCardNav(event) {
    event.preventDefault();
    event.stopPropagation();
}

export default function InstituteCard({
    institute,
    layout = "vertical",
    variant = "default",
    type = "language_courses",
    searchParamsOverride = null,
    onRemove = null,
}) {
    const { language, t } = useLocale();
    const { currency } = useCurrency();
    const isArabic = language === "ar";

    const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();

    const courseId = institute.id;
    const inWishlist = isInWishlist(type, courseId);
    const inCompare = isInCompare(type, courseId);
    const compareWeeks = Number(searchParamsOverride?.weeks || institute.weeks_param || 12) || 12;

    const baseUrl = '/language-institutes';
    const schoolSlug = institute.school_slug || institute.slug || institute.id;

    const cleanPart = (value) =>
        String(value ?? "")
            .replace(/\s*-\s*-\s*/g, " - ")
            .replace(/\s{2,}/g, " ")
            .replace(/^\s*-\s*|\s*-\s*$/g, "")
            .trim();

    const stripCityFromSchoolName = (school, city) => {
        const s = cleanPart(school);
        const c = cleanPart(city);
        if (!s || !c) return s;
        const escaped = c.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
        return cleanPart(
            s
                .replace(new RegExp(`\\s*-\\s*${escaped}$`, "i"), "")
                .replace(new RegExp(`^${escaped}\\s*-\\s*`, "i"), "")
        );
    };

    const schoolNameEnRaw = institute.school_name_en || institute.name_en || institute.name || institute.provider || institute.school_name || institute.ar_name;
    const schoolNameArRaw = institute.school_name_ar || institute.name_ar || institute.ar_name || institute.provider_ar_name || institute.school_ar_name || institute.name;
    const cityEn =
        institute.city ||
        institute.city_name ||
        (typeof institute.location === "string" ? institute.location.split(",")[0]?.trim() : null);
    const cityAr =
        institute.city_ar ||
        institute.city_ar_name ||
        cityEn;

    const schoolNameEn = stripCityFromSchoolName(schoolNameEnRaw, cityEn);
    const schoolNameAr = stripCityFromSchoolName(schoolNameArRaw, cityAr);

    const name = isArabic
        ? (schoolNameAr !== schoolNameEn
            ? [schoolNameAr, cleanPart(cityAr), cleanPart(schoolNameEn)].map(cleanPart).filter(Boolean).join(" - ")
            : [cleanPart(schoolNameAr), cleanPart(cityAr)].map(cleanPart).filter(Boolean).join(" - "))
        : [cleanPart(schoolNameEn), cleanPart(cityEn)].map(cleanPart).filter(Boolean).join(" - ");

    const courseName = isArabic
        ? (institute.course_ar_name || institute.course_name)
        : (institute.course_name || institute.course_ar_name);

    const courseType = isArabic
        ? (institute.course_type_ar || institute.course_type)
        : (institute.course_type || institute.course_type_ar);

    const location = isArabic
        ? (institute.country_ar || institute.country_ar_name || institute.country_name || institute.country)
        : (institute.country_en || institute.country_name || institute.country || institute.city);

    const flagSrc = getInstituteCountryFlag(institute);
    const flagFallbackSrc = getInstituteCountryFlagFallback(institute);

    const tag = isArabic
        ? (institute.tag_ar || institute.tag)
        : (institute.tag || institute.tag_ar);

    const priceValue = getCoursePrice(institute, currency, "new");
    const oldPriceValue = getCoursePrice(institute, currency, "old");

    const discountPercentage = oldPriceValue && priceValue && oldPriceValue > priceValue
        ? Math.round(((oldPriceValue - priceValue) / oldPriceValue) * 100)
        : (institute.discount_percent ?? 0);

    const imageSrc = getImageUrl(institute.image || institute.logo) || "/assets/hero.png";

    const queryParams = new URLSearchParams();
    const weeksParam = searchParamsOverride?.weeks ?? institute.weeks_param;
    const startDateParam = searchParamsOverride?.start_date ?? institute.start_date_param;
    if (weeksParam) queryParams.set("weeks", weeksParam);
    if (startDateParam) queryParams.set("start_date", startDateParam);
    queryParams.set("course_id", institute.id);
    const queryString = queryParams.toString();
    const detailUrl = `${baseUrl}/${schoolSlug}${queryString ? `?${queryString}` : ""}`;

    const handleSecondAction = async (e) => {
        stopCardNav(e);
        if (variant === "wishlist") {
            window.location.href = detailUrl;
            return;
        }
        await toggleCompare(type, institute.id, compareWeeks);
    };

    const handleWishlistAction = async (e) => {
        stopCardNav(e);
        if (variant === "wishlist" && onRemove) {
            await onRemove(institute.id, type);
            return;
        }
        await toggleWishlist(type, institute.id);
    };

    const actionButtons = (
        <>
            <button
                type="button"
                className="z-20 flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white text-slate-400 shadow-[0_6px_14px_rgba(15,23,42,0.08)] transition hover:text-[#EF4444]"
                onPointerDown={stopCardNav}
                onClick={handleWishlistAction}
                title={variant === "wishlist" ? "Remove from wishlist" : "Add to wishlist"}
            >
                {variant === "wishlist" ? (
                    <FontAwesomeIcon icon={faTrash} className="h-[18px] w-[18px] text-red-500" />
                ) : (
                    <Image
                        src={inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg"}
                        alt="Wishlist"
                        width={18}
                        height={18}
                        className="pointer-events-none h-[18px] w-[18px]"
                    />
                )}
            </button>
            <button
                type="button"
                className="z-20 flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white text-slate-400 shadow-[0_6px_14px_rgba(15,23,42,0.08)] transition hover:text-[#0057B7]"
                onPointerDown={stopCardNav}
                onClick={handleSecondAction}
                title={variant === "wishlist" ? "View details" : "Compare"}
            >
                {variant === "wishlist" ? (
                    <FontAwesomeIcon icon={faEye} className="h-[18px] w-[18px] text-blue-600" />
                ) : (
                    <Image
                        src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
                        alt="Compare"
                        width={18}
                        height={18}
                        className="pointer-events-none h-[18px] w-[18px]"
                    />
                )}
            </button>
        </>
    );

    if (layout === "horizontal") {
        return (
            <div className="group block">
                <div className="flex h-[180px] flex-row items-stretch overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md">
                    <div className="relative w-[240px] shrink-0 bg-slate-100">
                        <Link href={detailUrl} className="absolute inset-0 z-0 block">
                            <img
                                src={imageSrc}
                                alt={name}
                                className="h-full w-full object-cover"
                                onError={(e) => { e.target.src = "/assets/hero.png"; }}
                            />
                        </Link>
                        <div className="absolute left-3 top-3 z-20 flex flex-col gap-2">
                            {actionButtons}
                        </div>
                    </div>

                    <div className="flex flex-1 flex-col justify-between p-5">
                        <div>
                            <div className="mb-1 flex items-start justify-between">
                                <Link href={detailUrl} className={`text-lg font-medium leading-tight text-slate-900 line-clamp-2 ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
                                    {name}
                                </Link>
                                <div className="ml-2 flex shrink-0 items-center gap-1 rounded-md border border-amber-100 bg-amber-50 px-2 py-0.5">
                                    <FontAwesomeIcon icon={faStar} className="h-3 w-3 text-amber-500" />
                                    <span className="text-xs font-medium text-amber-700">{institute.rating || "5.0"}</span>
                                </div>
                            </div>

                            <div className="mb-3 flex items-center gap-2 text-xs text-slate-500">
                                <span>{location || (isArabic ? "المملكة المتحدة" : "The United Kingdom")}</span>
                            </div>

                            <div className="flex flex-wrap gap-2 text-xs text-slate-600">
                                <span className="inline-flex items-center gap-1.5 rounded-md border border-slate-100 bg-slate-50 px-2.5 py-1.5 font-normal">
                                    <FontAwesomeIcon icon={faSignal} className="h-3 w-3 text-blue-500" />
                                    {institute.level || "A1"}
                                </span>
                                <span className="inline-flex items-center gap-1.5 rounded-md border border-slate-100 bg-slate-50 px-2.5 py-1.5 font-normal">
                                    <FontAwesomeIcon icon={faClock} className="h-3 w-3 text-blue-500" />
                                    {institute.hours || "15"}h / {isArabic ? "أسبوع" : "week"}
                                </span>
                            </div>
                        </div>

                        <div className="mt-2 flex items-end justify-between border-t border-slate-100 pt-3">
                            <div className="flex flex-col">
                                <span className="text-[10px] font-normal uppercase tracking-wider text-slate-400">{isArabic ? "السعر يبدأ من" : "STARTING FROM"}</span>
                                <div className="flex items-baseline gap-1 text-slate-900">
                                    <span className="text-xl font-semibold">
                                        <CurrencyAmount currency={currency} amount={priceValue} />
                                    </span>
                                    <span className="text-xs font-normal text-slate-500">/{isArabic ? "أسبوع" : "week"}</span>
                                </div>
                            </div>

                            <Link href={detailUrl} className="rounded-lg bg-[#0057B7] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#004494]">
                                {isArabic ? "عرض التفاصيل" : "View Details"}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="group relative block">
            <div className="relative flex flex-col overflow-hidden rounded-2xl border border-[#DCE6F1] shadow-[0_10px_30px_rgba(15,23,42,0.08)] transition-all hover:shadow-[0_14px_40px_rgba(15,23,42,0.12)]">
                <div className="relative w-full shrink-0 p-2">
                    <div className="relative h-[220px] w-full overflow-hidden rounded-xl bg-slate-100">
                        <Link href={detailUrl} className="absolute inset-0 z-0 block">
                            <img
                                src={imageSrc}
                                alt={name}
                                className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                                onError={(e) => { e.target.src = "/assets/hero.png"; }}
                            />
                        </Link>

                        <div className="pointer-events-none absolute right-4 top-4 z-10 flex flex-col items-end gap-2">
                            {(institute.is_preferred || institute.tags?.includes("Top Rated")) && (
                                <div className="rounded-md bg-[#8F9BA6] px-4 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                    Top Rated
                                </div>
                            )}
                            {tag && (
                                <span className="rounded-md bg-[#0057B7] px-4 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                    {tag}
                                </span>
                            )}
                            {discountPercentage > 0 && variant !== "wishlist" && (
                                <span className="rounded-md bg-[#E32636] px-4 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                    {isArabic ? `خصم %${discountPercentage}` : `${discountPercentage}% OFF`}
                                </span>
                            )}
                        </div>

                        <div className="absolute left-4 top-4 z-20 flex flex-col gap-3">
                            {actionButtons}
                        </div>
                    </div>
                </div>

                <Link href={detailUrl} className="flex flex-1 flex-col p-4">
                    <div className="mb-3 flex w-full items-center justify-between gap-4">
                        <div className="flex items-center gap-2 whitespace-nowrap text-[13px] font-normal text-slate-600">
                            {flagSrc ? (
                                <img
                                    src={flagSrc}
                                    alt=""
                                    className="h-5 w-5 rounded-sm object-cover"
                                    onError={(e) => {
                                        const fallback = flagFallbackSrc;
                                        if (fallback && e.currentTarget.src !== fallback && !e.currentTarget.src.endsWith(fallback)) {
                                            e.currentTarget.src = fallback;
                                        }
                                    }}
                                />
                            ) : flagFallbackSrc ? (
                                <img
                                    src={flagFallbackSrc}
                                    alt=""
                                    className="h-5 w-5 rounded-sm object-cover"
                                />
                            ) : (
                                <span className="text-lg leading-none">🌍</span>
                            )}
                            <span>{location || ""}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <div className="flex gap-0.5 text-[#F59E0B]">
                                {Array.from({ length: 5 }).map((_, i) => (
                                    <FontAwesomeIcon
                                        key={i}
                                        icon={faStar}
                                        className={`h-[18px] w-[18px] ${i < Math.round(institute.rating || 0) ? "" : "text-[#E2E8F0]"}`}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>

                    <h3 className={`mb-4 text-md font-extrabold text-slate-900 ${isArabic ? "text-right" : "text-left"}`}>
                        {name}
                    </h3>

                    <div className="mb-2 flex items-center justify-around text-[13px] text-slate-500">
                        <div className="flex items-center gap-2">
                            <FontAwesomeIcon icon={faSignal} className="h-4 w-4 text-[#0B5ED7]" />
                            <span className="text-[13px] font-normal text-[#64748B]">{institute.level || "A1"}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <FontAwesomeIcon icon={faBookOpen} className="h-4 w-4 text-[#0B5ED7]" />
                            <span>{institute.lessons || "20"} {isArabic ? "درس" : "Lessons"}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <FontAwesomeIcon icon={faClock} className="h-4 w-4 text-[#0B5ED7]" />
                            <span>{institute.hours || "15"} {isArabic ? "ساعة" : "Hours"}</span>
                        </div>
                    </div>

                    <div className="my-2 inline-flex self-start items-center justify-center rounded-xl bg-gray-200 px-3 py-1 text-sm font-normal text-slate-600">
                        {courseName || courseType || "General English"}
                    </div>

                    <div className="mt-auto flex w-full gap-2 pt-2 text-lg">
                        <div className="flex items-center gap-1 text-slate-900">
                            <span className="inline-flex items-center  text-md font-semibold tracking-tight">
                                <CurrencyAmount currency={currency} amount={priceValue} />
                            </span>
                            <span className="text-sm text-slate-800"> / {isArabic ? "أسبوع" : "week"}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            {oldPriceValue ? (
                                <span className="inline-flex items-center font-normal text-slate-400 line-through">
                                    <CurrencyAmount currency={currency} amount={oldPriceValue} muted />
                                </span>
                            ) : null}
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    );
}
