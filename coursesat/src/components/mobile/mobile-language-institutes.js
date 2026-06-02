"use client";

import { useMemo, useState, useCallback, Suspense, useEffect } from "react";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faArrowRight } from "@fortawesome/free-solid-svg-icons";
import MobileLanguageInstitutesSearchModal from "@/components/mobile/mobile-language-institutes-search-modal";
import MobileInstituteCard from "@/components/mobile/mobile-institute-card";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { mapCourseTypeOptions } from "@/lib/hero-search-data";
import {
    appendDestination,
    applyDestinationsToSearchParams,
    parseLegacyDestinationParams,
    removeDestination,
} from "@/lib/institute-search-targets";

const PAGE_SIZE = 12;

function parseQueryDate(dateString) {
    if (!dateString) return null;
    const [y, m, d] = String(dateString).split("-").map(Number);
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
}

function formatLocalDate(date) {
    if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "";
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}

// -- Mobile Filters & Sort Component --
function MobileInstituteFilters({ totalCount, onSortChange, searchData }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const [isSearchOpen, setIsSearchOpen] = useState(false);
    const [isSortOpen, setIsSortOpen] = useState(false);
    const [sortMode, setSortMode] = useState(null);

    const data = searchData;
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const page = t("pages.language_institutes", {});

    const courseTypes = mapCourseTypeOptions(data?.course_types, language);

    const weeksOptions = useMemo(
        () =>
            Array.from({ length: 52 }, (_, i) => {
                const n = i + 1;
                return {
                    label: isArabic ? `${n} أسبوع` : `${n} Week${n === 1 ? "" : "s"}`,
                    value: n,
                };
            }),
        [isArabic]
    );

    const [destinations, setDestinations] = useState([]);
    const [courseType, setCourseType] = useState(null);
    const [weeks, setWeeks] = useState(12);
    const [startDate, setStartDate] = useState(null);

    useEffect(() => {
        if (!data) return;
        setDestinations(parseLegacyDestinationParams(searchParams, data, isArabic));

        const typeParam = searchParams.get('course_type');
        if (typeParam) {
            setCourseType(typeParam);
        }

        const weeksParam = searchParams.get('weeks');
        if (weeksParam) setWeeks(parseInt(weeksParam));

        const dateParam = searchParams.get('start_date');
        if (dateParam) {
            const parsed = parseQueryDate(dateParam);
            if (parsed) setStartDate(parsed);
        }

    }, [data, searchParams, isArabic]);

    const handleSortSelect = (mode) => {
        const next = sortMode === mode ? null : mode;
        setSortMode(next);
        onSortChange?.(next);
    };

    const handleSearch = () => {
        setIsSearchOpen(false);
        const params = new URLSearchParams();
        applyDestinationsToSearchParams(params, destinations);

        if (courseType) params.set('course_type', courseType);
        if (weeks) params.set('weeks', weeks);
        if (startDate) params.set('start_date', formatLocalDate(startDate));

        router.push(`/language-institutes?${params.toString()}`);
    };

    const handleDestinationAdd = (selection) => {
        setDestinations((current) => appendDestination(current, selection));
    };

    const handleDestinationRemove = (key) => {
        setDestinations((current) => removeDestination(current, key));
    };

    const searchPlaceholder =
        page?.mobile?.search_placeholder ||
        (isArabic ? "ادخل وجهتك المفضلة .." : "Enter your preferred destination..");
    const resultsTitle =
        page?.mobile?.results_title ||
        (isArabic ? "معاهد اللغة الانجليزية" : "English Language Institutes");
    const sortSheetTitle =
        page?.mobile?.sort_sheet_title || (isArabic ? "عرض النتائج حسب" : "Show results by");
    const sortOptions = [
        { id: "price", label: page?.mobile?.sort_price || (isArabic ? "السعر" : "Price") },
        { id: "location", label: page?.mobile?.sort_location || (isArabic ? "موقع" : "Location") },
        { id: "features", label: page?.mobile?.sort_features || (isArabic ? "الخصائص" : "Features") },
    ];

    return (
        <div className="relative">
            <div className={`flex items-center gap-2 ${isArabic ? "flex-row-reverse" : ""}`}>
                <button
                    type="button"
                    className="flex flex-1 items-center rounded-md border border-[#D8E0EA] bg-white px-4 py-3.5 text-start"
                    onClick={() => setIsSearchOpen(true)}
                >
                    <span className="text-sm text-slate-500">{searchPlaceholder}</span>
                </button>
                <Link
                    href="/"
                    className="flex h-6 w-6 shrink-0 items-center justify-center text-slate-900"
                    aria-label={t("layouts.common.back_home", isArabic ? "العودة للرئيسية" : "Back to home")}
                >
                    <FontAwesomeIcon icon={faArrowRight} className="h-5 w-5" />
                </Link>
            </div>

            <div className="mt-4 flex items-center justify-between gap-3">
                <h2 className="text-base font-bold text-slate-900">
                    {resultsTitle} ({totalCount})
                </h2>
                <button
                    type="button"
                    className="inline-flex shrink-0 items-center gap-1 text-sm font-normal text-slate-700"
                    onClick={() => setIsSortOpen(true)}
                >
                    <span>{t("layouts.common.sort_by", isArabic ? "ترتيب حسب" : "Sort by")}</span>
                    <FontAwesomeIcon icon={faChevronDown} className="text-[10px] text-slate-500" />
                </button>
            </div>

            <MobileLanguageInstitutesSearchModal
                isOpen={isSearchOpen}
                onClose={() => setIsSearchOpen(false)}
                onSearch={handleSearch}
                searchData={data}
                page={page}
                destinations={destinations}
                onDestinationAdd={handleDestinationAdd}
                onDestinationRemove={handleDestinationRemove}
                courseType={courseType}
                onCourseTypeChange={setCourseType}
                weeks={weeks}
                onWeeksChange={setWeeks}
                startDate={startDate}
                onStartDateChange={setStartDate}
                weeksOptions={weeksOptions}
                courseTypes={courseTypes}
            />

            {isSortOpen && (
                <div className="fixed inset-0 z-[100]">
                    <div
                        className="absolute inset-0 bg-black/30"
                        onClick={() => setIsSortOpen(false)}
                    />
                    <div className="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white px-6 pb-8 pt-4 shadow-2xl">
                        <div className="mx-auto mb-5 h-1.5 w-14 rounded-full bg-slate-300" />
                        <div className="mb-6 flex items-center justify-between text-start">
                            <h3 className="text-lg font-bold text-slate-900">{sortSheetTitle}</h3>
                            <button
                                type="button"
                                className="text-2xl leading-none text-slate-700"
                                onClick={() => setIsSortOpen(false)}
                                aria-label={isArabic ? "إغلاق" : "Close"}
                            >
                                ×
                            </button>
                        </div>

                        <div className="space-y-4">
                            {sortOptions.map((option) => (
                                <label
                                    key={option.id}
                                    className="flex cursor-pointer items-center gap-3 text-base text-slate-800"
                                >
                                    <input
                                        type="radio"
                                        name="mobileInstituteSort"
                                        checked={sortMode === option.id}
                                        onChange={() => handleSortSelect(option.id)}
                                        className="h-5 w-5 border-gray-300 text-[#0057B7] accent-[#0057B7]"
                                    />
                                    <span>{option.label}</span>
                                </label>
                            ))}
                        </div>

                        <button
                            type="button"
                            className="mt-8 w-full rounded-2xl bg-[#0057B7] py-3.5 text-base font-medium text-white"
                            onClick={() => setIsSortOpen(false)}
                        >
                            {t("layouts.common.apply", isArabic ? "تطبيق" : "Apply")}
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}

// -- Main Page Inner Component --
function MobileLanguageInstitutesInner() {
    const searchParams = useSearchParams();
    const queryString = searchParams.toString();
    const selectedWeeksParam = searchParams.get("weeks");
    const selectedStartDateParam = searchParams.get("start_date");
    const { data, loading } = useApi(`/coursesat/language-institutes?per_page=200&${queryString}`);
    const { currency } = useCurrency();
    const { language, t } = useLocale();
    const isArabic = language === "ar";

    const allCourses = useMemo(() => data?.courses ?? [], [data?.courses]);
    const searchData = data?.search_data ?? null;

    const [sortMode, setSortMode] = useState(null);

    const handleSortChange = useCallback((mode) => {
        setSortMode(mode);
    }, []);

    const [visibleCount, setVisibleCount] = useState(PAGE_SIZE);

    const processedCourses = useMemo(() => {
        let list = [...allCourses];

        if (sortMode === "price") {
            list.sort((a, b) => {
                const pa = Number(getCoursePrice(a, currency, "new") ?? 0);
                const pb = Number(getCoursePrice(b, currency, "new") ?? 0);
                return pa - pb;
            });
        } else if (sortMode === "location") {
            list.sort((a, b) => {
                const la = isArabic
                    ? a.country_ar || a.location_ar || a.location || ""
                    : a.country_en || a.location || a.city || "";
                const lb = isArabic
                    ? b.country_ar || b.location_ar || b.location || ""
                    : b.country_en || b.location || b.city || "";
                return la.localeCompare(lb, isArabic ? "ar" : "en");
            });
        } else if (sortMode === "features") {
            list.sort((a, b) => Number(b.rating || 0) - Number(a.rating || 0));
        }

        return list;
    }, [allCourses, sortMode, currency, isArabic]);

    const visibleCourses = processedCourses.slice(0, visibleCount);
    const hasMore = visibleCount < processedCourses.length;

    const loadMoreText = t("layouts.common.load_more", isArabic ? "عرض المزيد" : "Load More");
    const noMatchText = isArabic ? "لا توجد معاهد تطابق الفلاتر" : "No institutes match your filters";
    const noMatchHint = isArabic ? "جرب تعديل خيارات الفلترة أو الترتيب" : "Try adjusting the filters or sort options";

    return (
        <div className="flex min-h-screen flex-col bg-white">
            <MobileHeader />
            <main className="flex-1 bg-white pb-24">
                <div className="px-4 pt-4">
                    <MobileInstituteFilters
                        totalCount={processedCourses.length}
                        onSortChange={handleSortChange}
                        searchData={searchData}
                    />
                </div>

                <div className="mt-2 px-4">
                    {loading ? (
                        <div className="flex justify-center py-20">
                            <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
                        </div>
                    ) : visibleCourses.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-20 text-center">
                            <p className="text-lg font-normal text-slate-600">{noMatchText}</p>
                            <p className="mt-2 text-sm text-slate-400">{noMatchHint}</p>
                        </div>
                    ) : (
                        <div className="flex flex-col gap-4">
                            {visibleCourses.map((course) => (
                                <MobileInstituteCard
                                    key={course.id}
                                    institute={course}
                                    searchParamsOverride={{
                                        weeks: selectedWeeksParam,
                                        start_date: selectedStartDateParam,
                                    }}
                                />
                            ))}
                        </div>
                    )}

                    {!loading && hasMore && (
                        <div className="mt-8 flex justify-center">
                            <button
                                className="flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-[#0057B7] bg-transparent px-8 py-3 font-medium text-[#0057B7] transition hover:bg-[#0057B7] hover:text-white"
                                onClick={() => setVisibleCount((prev) => prev + PAGE_SIZE)}
                            >
                                <span>{loadMoreText}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                    )}
                </div>
            </main>
            <MobileFooter />
            <MobileBottomNav />
        </div>
    );
}

export default function MobileLanguageInstitutes() {
    return (
        <Suspense fallback={null}>
            <MobileLanguageInstitutesInner />
        </Suspense>
    );
}
