"use client";

import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHeart, faExchangeAlt, faStar, faSignal, faBookOpen, faClock, faTrash, faEye } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";

export default function InstituteCard({
    institute,
    layout = "vertical",
    variant = "default",
    type = "language_courses",
    searchParamsOverride = null,
}) {
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const currency = "SAR";

    // Stub interactions for now
    const inWishlist = false;
    const inCompare = false;
    const toggleWishlist = async () => { };
    const toggleCompare = async () => { };

    const baseUrl = '/language-institutes';

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
        ? (institute.country_ar || institute.location_ar || institute.location)
        : (institute.country_en || institute.location || institute.city);

    const tag = isArabic
        ? (institute.tag_ar || institute.tag)
        : (institute.tag || institute.tag_ar);

    const priceValue =
        currency === "SAR"
            ? institute.price_sar ?? institute.price_per_week_sar ?? institute.price_per_week ?? institute.price
            : institute.price_gbp ?? institute.price_per_week_gbp ?? institute.price_per_week ?? institute.price;

    const oldPriceValue =
        currency === "SAR"
            ? institute.old_price_sar ?? institute.old_price_gbp ?? institute.old_price
            : institute.old_price_gbp ?? institute.old_price_sar ?? institute.old_price;

    const discountPercentage = oldPriceValue && priceValue && oldPriceValue > priceValue
        ? Math.round(((oldPriceValue - priceValue) / oldPriceValue) * 100)
        : 0;

    const currencyIcon = "/assets/sar.svg";
    const currencySymbol = currency === "GBP" ? "£" : null;

    const formatNumber = (value) =>
        new Intl.NumberFormat("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(Number(value));

    const queryParams = new URLSearchParams();
    const weeksParam = searchParamsOverride?.weeks ?? institute.weeks_param;
    const startDateParam = searchParamsOverride?.start_date ?? institute.start_date_param;
    if (weeksParam) queryParams.set("weeks", weeksParam);
    if (startDateParam) queryParams.set("start_date", startDateParam);
    queryParams.set("course_id", institute.id);
    const queryString = queryParams.toString();

    const handleSecondAction = async (e) => {
        e.preventDefault();
        e.stopPropagation();
        await toggleCompare(type, institute.id);
    };

    if (layout === "horizontal") {
        const imageSrc = institute.image || institute.logo || "/assets/hero.png";

        return (
            <Link href={`${baseUrl}/${institute.slug || institute.id}${queryString ? `?${queryString}` : ""}`} className="block group">
                <div className="flex flex-row items-stretch overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all h-[180px]">
                    <div className="relative w-[240px] shrink-0 bg-slate-100">
                        <img
                            src={imageSrc}
                            alt={name}
                            className="h-full w-full object-cover"
                            onError={(e) => { e.target.src = "/assets/hero.png"; }}
                        />
                        <div className="absolute top-3 left-3 flex flex-col gap-2">
                            <button
                                className="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-sm backdrop-blur-sm transition hover:bg-red-50 hover:text-red-600"
                                onClick={async (e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    await toggleWishlist();
                                }}
                            >
                                <FontAwesomeIcon icon={faHeart} className="h-3.5 w-3.5 text-slate-400" />
                            </button>
                            <button
                                className="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-sm backdrop-blur-sm transition hover:bg-blue-50 hover:text-blue-600"
                                onClick={handleSecondAction}
                            >
                                <FontAwesomeIcon icon={faExchangeAlt} className="h-3.5 w-3.5 text-slate-400" />
                            </button>
                        </div>
                    </div>

                    <div className="flex flex-1 flex-col p-5 justify-between">
                        <div>
                            <div className="flex justify-between items-start mb-1">
                                <h3 className={`text-lg font-medium text-slate-900 leading-tight line-clamp-2 ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
                                    {name}
                                </h3>
                                <div className="flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100 shrink-0 ml-2">
                                    <FontAwesomeIcon icon={faStar} className="h-3 w-3 text-amber-500" />
                                    <span className="text-xs font-medium text-amber-700">{institute.rating || "5.0"}</span>
                                </div>
                            </div>

                            <div className="flex items-center gap-2 text-xs text-slate-500 mb-3">
                                <span>{location || (isArabic ? "المملكة المتحدة" : "The United Kingdom")}</span>
                            </div>

                            <div className="flex flex-wrap gap-2 text-xs text-slate-600">
                                <span className="inline-flex items-center gap-1.5 rounded-md bg-slate-50 px-2.5 py-1.5 font-normal border border-slate-100">
                                    <FontAwesomeIcon icon={faSignal} className="h-3 w-3 text-blue-500" />
                                    {institute.level || "A1"}
                                </span>
                                <span className="inline-flex items-center gap-1.5 rounded-md bg-slate-50 px-2.5 py-1.5 font-normal border border-slate-100">
                                    <FontAwesomeIcon icon={faClock} className="h-3 w-3 text-blue-500" />
                                    {institute.hours || "15"}h / {isArabic ? "أسبوع" : "week"}
                                </span>
                            </div>
                        </div>

                        <div className="flex items-end justify-between border-t border-slate-100 pt-3 mt-2">
                            <div className="flex flex-col">
                                <span className="text-[10px] font-normal text-slate-400 uppercase tracking-wider">{isArabic ? "السعر يبدأ من" : "STARTING FROM"}</span>
                                <div className="flex items-baseline gap-1 text-slate-900">
                                    <span className="text-xl font-semibold">
                                        {currencySymbol || currency} {priceValue ? formatNumber(priceValue) : "-"}
                                    </span>
                                    <span className="text-xs font-normal text-slate-500">/{isArabic ? "أسبوع" : "week"}</span>
                                </div>
                            </div>

                            <button className="rounded-lg bg-[#0057B7] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#004494]">
                                {isArabic ? "عرض التفاصيل" : "View Details"}
                            </button>
                        </div>
                    </div>
                </div>
            </Link>
        )
    }

    return (
        <div className="block group relative">
            <div className="relative flex flex-col overflow-hidden rounded-2xl border border-[#DCE6F1] shadow-[0_10px_30px_rgba(15,23,42,0.08)] transition-all hover:shadow-[0_14px_40px_rgba(15,23,42,0.12)]">
                <div className="relative w-full shrink-0 p-2">
                    <Link href={`${baseUrl}/${institute.slug || institute.id}${queryString ? `?${queryString}` : ""}`} className="block relative h-[220px] w-full overflow-hidden rounded-xl">
                        <img
                            src={institute.image || "/assets/hero.png"}
                            alt={name}
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                        />
                    </Link>

                    <div className="absolute right-7 top-7 flex flex-col items-start gap-2 pointer-events-none">
                        {(institute.is_preferred || institute.tags?.includes("Top Rated")) && (
                            <div className="rounded-md bg-[#8F9BA6] px-2 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                Top Rated
                            </div>
                        )}
                        {tag && (
                            <span className="rounded-md bg-[#0057B7] px-2 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                {tag}
                            </span>
                        )}
                        {discountPercentage > 0 && (
                            <span className="rounded-md bg-[#E32636] px-4 py-1.5 text-[12px] font-normal text-white shadow-sm">
                                {isArabic ? `خصم %${discountPercentage}` : `${discountPercentage}% OFF`}
                            </span>
                        )}
                    </div>

                    <div className="absolute left-6 top-6 flex flex-col gap-3 pointer-events-auto">
                        <button
                            className="z-20 flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white text-slate-400 shadow-[0_6px_14px_rgba(15,23,42,0.08)] transition hover:text-[#EF4444]"
                            onClick={async (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                            }}
                        >
                            <Image
                                src="/assets/icons/heart-regular-black.svg"
                                alt="Wishlist"
                                width={18}
                                height={18}
                                className="h-[18px] w-[18px]"
                            />
                        </button>
                        <button
                            className="z-20 flex h-10 w-10 items-center justify-center rounded-full border border-[#E6EEF7] bg-white text-slate-400 shadow-[0_6px_14px_rgba(15,23,42,0.08)] transition hover:text-[#0057B7]"
                            onClick={handleSecondAction}
                        >
                            <Image
                                src="/assets/icons/compare.svg"
                                alt="Compare"
                                width={18}
                                height={18}
                                className="h-[18px] w-[18px]"
                            />
                        </button>
                    </div>
                </div>

                <Link href={`${baseUrl}/${institute.slug || institute.id}${queryString ? `?${queryString}` : ""}`} className="flex flex-1 flex-col p-4">
                    <div className="mb-3 flex w-full items-center justify-between gap-4">
                        <div className="flex items-center gap-2 text-[13px] font-normal text-slate-600 whitespace-nowrap">
                            {institute.flag && institute.flag.startsWith("http") ? (
                                <img src={institute.flag} alt="" className="h-5 w-5 rounded-sm object-cover" />
                            ) : (
                                <span className="text-lg leading-none">{institute.flag || "🇬🇧"}</span>
                            )}
                            <span>{location || (isArabic ? "المملكة المتحدة" : "The United Kingdom")}</span>
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

                    <h3 className={`mb-4 text-lg font-semibold text-slate-900 ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
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

                    <div className="mt-auto w-full flex pt-2 text-lg gap-4">
                        <div className="flex items-center gap-1 text-slate-900">
                            <span className="inline-flex items-center gap-1 text-md font-semibold tracking-tight">
                                <span>{priceValue ? formatNumber(priceValue) : "-"}</span>
                                {currencySymbol ? (
                                    <span>{currencySymbol}</span>
                                ) : (
                                    <img src={currencyIcon} alt={currency} className="h-4 w-4 invert" />
                                )}

                            </span>
                            <span className="text-sm text-slate-800"> / {isArabic ? "أسبوع" : "week"}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            {oldPriceValue ? (
                                <span className="inline-flex items-center gap-1 font-normal text-slate-400 line-through">
                                    <span>{formatNumber(oldPriceValue)}</span>
                                    {currencySymbol ? (
                                        <span>{currencySymbol}</span>
                                    ) : (
                                        <img src={currencyIcon} alt={currency} className="h-4 w-4 invert opacity-40" />
                                    )}

                                </span>
                            ) : null}
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    );
}
