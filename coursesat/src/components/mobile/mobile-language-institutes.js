"use client";

import { useMemo, useState, useCallback, Suspense, useEffect, useRef } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch, faChevronUp, faChevronDown, faFilter } from "@fortawesome/free-solid-svg-icons";
import LanguageInstitutesHeroSearch from "@/components/shared/language-institutes-hero-search";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import InstituteCard from "@/components/shared/institute-card";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { getCoursePrice } from "@/lib/format-currency";
import { mapCourseTypeOptions } from "@/lib/hero-search-data";

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
function MobileInstituteFilters({ totalCount, tags = [], onSortChange, searchData }) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const [isSearchOpen, setIsSearchOpen] = useState(false);
    const [isSortOpen, setIsSortOpen] = useState(false);
    const [selectedTag, setSelectedTag] = useState(null);
    const [priceDirection, setPriceDirection] = useState(null);
    const mobileSearchPanelRef = useRef(null);

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

    const [destination, setDestination] = useState(null);
    const [courseType, setCourseType] = useState(null);
    const [weeks, setWeeks] = useState(12);
    const [startDate, setStartDate] = useState(null);

    useEffect(() => {
        if (!data) return;

        const schoolSlug = searchParams.get('school_slug');
        const citySlug = searchParams.get('city_slug');
        const countrySlug = searchParams.get('country_slug');

        if (schoolSlug) {
            const match = data.schools?.find(s => s.slug === schoolSlug);
            if (match) setDestination({ type: 'school', slug: schoolSlug, name: isArabic ? match.ar_name || match.name : match.name });
        } else if (citySlug) {
            const match = data.cities?.find(c => c.slug === citySlug);
            if (match) setDestination({ type: 'city', slug: citySlug, name: isArabic ? match.ar_name || match.name : match.name });
        } else if (countrySlug) {
            const match = data.countries?.find(c => c.slug === countrySlug);
            if (match) setDestination({ type: 'country', slug: countrySlug, name: isArabic ? match.ar_name || match.name : match.name });
        }

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

    const handleTagSelect = (tagName) => {
        const next = selectedTag === tagName ? null : tagName;
        setSelectedTag(next);
        onSortChange?.({ tag: next, priceDirection });
    };

    const handlePriceSelect = (dir) => {
        const next = priceDirection === dir ? null : dir;
        setPriceDirection(next);
        onSortChange?.({ tag: selectedTag, priceDirection: next });
    };

    const handleSearch = () => {
        setIsSearchOpen(false);
        const params = new URLSearchParams();

        if (destination) {
            if (destination.type === 'school') params.set('school_slug', destination.slug);
            if (destination.type === 'city') params.set('city_slug', destination.slug);
            if (destination.type === 'country') params.set('country_slug', destination.slug);
        }

        if (courseType) params.set('course_type', courseType);
        if (weeks) params.set('weeks', weeks);
        if (startDate) params.set('start_date', formatLocalDate(startDate));

        router.push(`/language-institutes?${params.toString()}`);
    };

    return (
        <div className="md:hidden mb-6 mt-4 relative z-50">
            <div className="flex gap-2">
                <button
                    type="button"
                    className="flex flex-1 items-center gap-3 rounded-2xl border border-[#E1E8F0] bg-white px-4 py-3 text-start shadow-sm"
                    onClick={() => setIsSearchOpen(true)}
                >
                    <FontAwesomeIcon icon={faSearch} className="h-4 w-4 text-slate-400" />
                    <span className="text-sm text-slate-500">
                        {page?.hero?.labels?.destination_placeholder || (isArabic ? "أدخل وجهتك المفضلة" : "Enter your preferred destination")}
                    </span>
                    <span className="ms-auto text-slate-400">→</span>
                </button>
                <button
                    type="button"
                    className="flex h-[46px] w-[46px] shrink-0 items-center justify-center rounded-2xl border border-[#E1E8F0] bg-white shadow-sm"
                    onClick={() => setIsSortOpen(true)}
                >
                    <FontAwesomeIcon icon={faFilter} className="h-4 w-4 text-[#0057B7]" />
                </button>
            </div>

            <div className="mt-4 flex items-center justify-between pb-2">
                <button
                    type="button"
                    className="flex items-center gap-2 text-sm font-normal text-slate-700"
                    onClick={() => setIsSortOpen(true)}
                >
                    <span className="text-slate-400">⌄</span>
                    {t("layouts.common.sort_by", isArabic ? "ترتيب حسب" : "Sort by")}
                </button>
                <div className="text-sm font-normal text-slate-800">
                    {totalCount} {isArabic ? "معهد" : "institutes"}
                </div>
            </div>

            {isSearchOpen && (
                <div className="fixed inset-0 z-[100]">
                    <div
                        className="absolute inset-0 bg-black/30"
                        onClick={() => setIsSearchOpen(false)}
                    />
                    <div ref={mobileSearchPanelRef} className="absolute inset-x-4 top-6 rounded-3xl bg-white p-4 shadow-2xl overflow-visible">
                        <div className="flex justify-start">
                            <button
                                type="button"
                                className="text-2xl text-slate-700"
                                onClick={() => setIsSearchOpen(false)}
                            >
                                ×
                            </button>
                        </div>

                        <div className="mt-4 space-y-4">
                            <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start overflow-visible">
                                <LanguageInstitutesHeroSearch
                                    anchorRef={mobileSearchPanelRef}
                                    placeholder={page?.hero?.labels?.destination_placeholder || (isArabic ? "أدخل وجهتك المفضلة" : "Enter your preferred destination")}
                                    subPlaceholder={page?.hero?.labels?.destination || (isArabic ? "الوجهة" : "Destination")}
                                    value={destination?.name || ""}
                                    searchData={data}
                                    onSelect={(dest) => setDestination(dest)}
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start overflow-visible">
                                    <HeroDropdown
                                        label={page?.hero?.labels?.duration || (isArabic ? "الأسابيع" : "Weeks")}
                                        placeholder={page?.hero?.labels?.duration_placeholder || (isArabic ? "اختر الأسابيع" : "Select weeks")}
                                        options={weeksOptions}
                                        selectedValue={weeks}
                                        onSelect={(option) =>
                                          setWeeks(typeof option === "object" ? option.value : parseInt(option, 10))
                                        }
                                        scroll
                                        maxVisibleItems={8}
                                    />
                                </div>
                                <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start">
                                    <HeroDatePicker
                                        label={page?.hero?.labels?.start || (isArabic ? "تاريخ البدء" : "Start date")}
                                        placeholder={page?.hero?.labels?.start_placeholder || (isArabic ? "اختر التاريخ" : "Select start date")}
                                        selectedDate={startDate}
                                        onSelect={(date) => setStartDate(date)}
                                    />
                                </div>
                            </div>

                            <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start overflow-visible">
                                <HeroDropdown
                                    label={page?.hero?.labels?.course || (isArabic ? "نوع الدورة" : "Course type")}
                                    placeholder={page?.hero?.labels?.course_placeholder || (isArabic ? "اختر نوع الدورة" : "Select course type")}
                                    options={courseTypes}
                                    selectedValue={courseType}
                                    onSelect={(option) =>
                                      setCourseType(typeof option === "object" ? option.value : option)
                                    }
                                />
                            </div>
                        </div>

                        <button
                            type="button"
                            className="mt-6 w-full rounded-2xl bg-[#0057B7] py-3 text-base font-normal text-white"
                            onClick={handleSearch}
                        >
                            {t("layouts.common.search", isArabic ? "بحث" : "Search")}
                        </button>
                    </div>
                </div>
            )}

            {isSortOpen && (
                <div className="fixed inset-0 z-[100]">
                    <div
                        className="absolute inset-0 bg-black/30"
                        onClick={() => setIsSortOpen(false)}
                    />
                    <div className="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
                        <div className="mx-auto mb-4 h-1.5 w-14 rounded-full bg-slate-300" />
                        <div className="flex items-center justify-between text-start">
                            <div>
                                <h3 className="text-lg font-medium text-slate-900">
                                    {t("layouts.common.sort_by", isArabic ? "ترتيب حسب" : "Sort by")}
                                </h3>
                                <p className="text-sm text-slate-500">
                                    {isArabic ? "اختر طريقة الترتيب" : "Choose sorting method"}
                                </p>
                            </div>
                            <button
                                type="button"
                                className="text-2xl text-slate-700"
                                onClick={() => setIsSortOpen(false)}
                            >
                                ×
                            </button>
                        </div>

                        {tags.length > 0 && (
                            <div className="mt-4 text-start">
                                <p className="mb-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                    {isArabic ? "تصفية حسب الوسم" : "Filter by Tag"}
                                </p>
                                <div className="space-y-2">
                                    {tags.map((tag) => {
                                        const tagName = typeof tag === "string" ? tag : tag.name;
                                        const tagLabel = typeof tag === "string" ? tag : (isArabic ? (tag.ar_name || tag.name) : tag.name);
                                        return (
                                            <label key={tag.id || tagName} className="flex items-center gap-3 text-base text-slate-700">
                                                <input
                                                    type="checkbox"
                                                    checked={selectedTag === tagName}
                                                    onChange={() => handleTagSelect(tagName)}
                                                    className="h-5 w-5 rounded border-gray-300 text-[#0057B7] accent-[#0057B7]"
                                                />
                                                <span>{tagLabel}</span>
                                            </label>
                                        );
                                    })}
                                </div>
                            </div>
                        )}

                        <div className="mt-4 border-t border-gray-100 pt-4 text-start">
                            <p className="mb-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                {isArabic ? "ترتيب حسب السعر" : "Sort by Price"}
                            </p>
                            <div className="space-y-2">
                                <label className="flex items-center gap-3 text-base text-slate-700">
                                    <input
                                        type="radio"
                                        name="mobilePriceSort"
                                        checked={priceDirection === "asc"}
                                        onChange={() => handlePriceSelect("asc")}
                                    />
                                    <span>{isArabic ? "السعر: من الأقل إلى الأعلى" : "Price: Low to High"}</span>
                                </label>
                                <label className="flex items-center gap-3 text-base text-slate-700">
                                    <input
                                        type="radio"
                                        name="mobilePriceSort"
                                        checked={priceDirection === "desc"}
                                        onChange={() => handlePriceSelect("desc")}
                                    />
                                    <span>{isArabic ? "السعر: من الأعلى إلى الأقل" : "Price: High to Low"}</span>
                                </label>
                            </div>
                        </div>

                        <button
                            type="button"
                            className="mt-6 w-full rounded-2xl bg-[#0057B7] py-3 text-base font-normal text-white"
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
    const page = t("pages.language_institutes", {});

    const allCourses = useMemo(() => data?.courses ?? [], [data?.courses]);
    const tags = data?.tags ?? [];
    const searchData = data?.search_data ?? null;

    const [sortTag, setSortTag] = useState(null);
    const [sortPrice, setSortPrice] = useState(null);

    const handleSortChange = useCallback(({ tag, priceDirection }) => {
        setSortTag(tag);
        setSortPrice(priceDirection);
    }, []);

    const [visibleCount, setVisibleCount] = useState(PAGE_SIZE);

    const processedCourses = useMemo(() => {
        let list = [...allCourses];

        if (sortTag) {
            list = list.filter((c) => c.tags?.includes(sortTag) || c.tag === sortTag);
        }

        if (sortPrice) {
            list.sort((a, b) => {
                const pa = Number(getCoursePrice(a, currency, "new") ?? 0);
                const pb = Number(getCoursePrice(b, currency, "new") ?? 0);
                return sortPrice === "asc" ? pa - pb : pb - pa;
            });
        }

        return list;
    }, [allCourses, sortTag, sortPrice, currency]);

    const visibleCourses = processedCourses.slice(0, visibleCount);
    const hasMore = visibleCount < processedCourses.length;

    const headingText = page?.hero?.heading || (isArabic ? "اكتشف أفضل معاهد اللغات حول العالم" : "Discover the Best Language Institutes Worldwide");
    const subheadingText = page?.hero?.subheading || (isArabic ? "مجموعة مختارة من أفضل معاهد اللغات المعتمدة." : "A curated selection of the best accredited language institutes.");
    
    const loadMoreText = t("layouts.common.load_more", isArabic ? "عرض المزيد" : "Load More");
    const noMatchText = isArabic ? "لا توجد معاهد تطابق الفلاتر" : "No institutes match your filters";
    const noMatchHint = isArabic ? "جرب تعديل خيارات الفلترة أو الترتيب" : "Try adjusting the filters or sort options";

    return (
        <div className="flex min-h-screen flex-col">
            <MobileHeader />
            <main className="flex-1 bg-[#F0F7FC] pb-24">
                <div className="bg-gradient-to-b from-white/40 via-[#E0EFF8] to-white/40 px-4 pt-8 pb-4 rounded-b-3xl mb-4">
                    <h1 className="mb-2 text-2xl font-extrabold text-[#0F172A] whitespace-pre-line text-center">
                        {headingText}
                    </h1>
                    <p className="text-sm text-[#64748B] text-center">
                        {subheadingText}
                    </p>
                    <MobileInstituteFilters
                        totalCount={processedCourses.length}
                        tags={tags}
                        onSortChange={handleSortChange}
                        searchData={searchData}
                    />
                </div>

                <div className="px-4">
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
                                <InstituteCard
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
