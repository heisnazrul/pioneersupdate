"use client";

import { useMemo, useState, useCallback, Suspense } from "react";
import { useSearchParams } from "next/navigation";
import LanguageInstitutesHero from "@/app/components/LanguageInstitutesHero";
import LanguageInstitutesSidebar from "@/app/components/LanguageInstitutesSidebar";
import InstituteCard from "@/app/components/InstituteCard";
import SortDropdown from "@/app/components/SortDropdown";
import MobileInstituteFilters from "@/app/components/MobileInstituteFilters";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

// Fallback mock data
import mockInstitutes from "@/data/mocks/institutes.json";

const PAGE_SIZE = 12;

function LanguageInstitutesPageInner() {
    const searchParams = useSearchParams();
    const queryString = searchParams.toString();
    const selectedWeeksParam = searchParams.get("weeks");
    const selectedStartDateParam = searchParams.get("start_date");
    const { data, loading } = useApi(`/courseenglish/language-institutes?per_page=200&${queryString}`);
    const { currency, isArabic, language } = useCourseEnglishSettings();
    const page = getCourseEnglishMessages(language)?.pages?.language_institutes ?? {};

    // ── All courses from API (or fallback) ────────────────────────
    const allCourses = useMemo(() => {
        const api = data?.courses ?? [];
        return api.length ? api : mockInstitutes;
    }, [data]);

    // Tags from API
    const tags = data?.tags ?? [];

    // ── Sidebar filter state ──────────────────────────────────────
    const [filters, setFilters] = useState({
        accommodation: null,
        pickup: null,
        insurance: null,
    });

    // ── Sort state ────────────────────────────────────────────────
    const [sortTag, setSortTag] = useState(null);
    const [sortPrice, setSortPrice] = useState(null); // "asc" | "desc" | null

    const handleSortChange = useCallback(({ tag, priceDirection }) => {
        setSortTag(tag);
        setSortPrice(priceDirection);
    }, []);

    // ── Load More state ───────────────────────────────────────────
    const [visibleCount, setVisibleCount] = useState(PAGE_SIZE);

    // ── Derive filtered + sorted list ─────────────────────────────
    const processedCourses = useMemo(() => {
        let list = [...allCourses];

        // Sidebar boolean filters
        if (filters.accommodation === true) {
            list = list.filter((c) => c.has_accommodation === true);
        }
        if (filters.pickup === true) {
            list = list.filter((c) => c.has_pickup === true);
        }
        if (filters.insurance === true) {
            list = list.filter((c) => c.has_insurance === true);
        }

        // Tag filter
        if (sortTag) {
            list = list.filter((c) => c.tag === sortTag);
        }

        // Price sort
        if (sortPrice) {
            const priceKey = currency === "SAR" ? "price_sar" : "price_gbp";
            list.sort((a, b) => {
                const pa = Number(a[priceKey] ?? a.price ?? 0);
                const pb = Number(b[priceKey] ?? b.price ?? 0);
                return sortPrice === "asc" ? pa - pb : pb - pa;
            });
        }

        return list;
    }, [allCourses, filters, sortTag, sortPrice, currency]);

    // Visible slice
    const visibleCourses = processedCourses.slice(0, visibleCount);
    const hasMore = visibleCount < processedCourses.length;

    // Reset visible count when filters change
    const handleFiltersChange = useCallback((newFilters) => {
        setFilters(newFilters);
        setVisibleCount(PAGE_SIZE);
    }, []);

    // CMS-driven text with fallbacks
    const headingText = isArabic
        ? `${processedCourses.length} ${page?.results?.count_label || "دورة متاحة بناءً على اختيارك"}`
        : `${processedCourses.length} ${page?.results?.count_label || "courses available based on your choice"}`;
    const subheadingText = isArabic
        ? (page?.results?.step_label || "الخطوة 1: اختر الدورة المناسبة لك")
        : (page?.results?.step_label || "Step 1: Choose the right course for you");
    const loadMoreText = getCourseEnglishMessages(language)?.layouts?.common?.load_more || (isArabic ? "عرض المزيد" : "Load More");
    const noMatchText = isArabic ? "لا توجد دورات تطابق الفلاتر" : "No courses match your filters";
    const noMatchHint = isArabic ? "جرب تعديل خيارات الفلترة أو الترتيب" : "Try adjusting the sidebar or sort options";

    return (
        <main className="min-h-screen bg-[#F0F7FC]">
            <div className="hidden md:block">
                <LanguageInstitutesHero />
            </div>

            <div className="container mx-auto px-4 py-8">
                <MobileInstituteFilters
                    totalCount={processedCourses.length}
                    tags={tags}
                    onSortChange={handleSortChange}
                />

                {/* Header / Stats */}
                <div className="relative z-20 mb-8 hidden flex-col items-start justify-between gap-4 border-b border-gray-200/60 pb-6 md:flex md:flex-row md:items-end">
                    <div>
                        <h2 className="text-2xl font-extrabold text-[#0F172A] md:text-3xl">
                            {headingText}
                        </h2>
                        <p className="mt-2 text-xl md:text-2xl font-normal text-slate-500">{subheadingText}</p>
                    </div>

                    {/* Sort Dropdown */}
                    <SortDropdown tags={tags} onSortChange={handleSortChange} />
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-4">
                    {/* Sidebar - 1 Column */}
                    <div className="hidden lg:block lg:col-span-1 h-fit">
                        <LanguageInstitutesSidebar
                            filters={filters}
                            onFiltersChange={handleFiltersChange}
                        />
                    </div>

                    {/* Cards Grid - 3 Columns */}
                    <div className="lg:col-span-3">
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
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
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

                        {/* Load More Button */}
                        {!loading && hasMore && (
                            <div className="mt-12 flex justify-center">
                                <button
                                    className="flex items-center gap-2 rounded-full border-2 border-[#0057B7] bg-transparent px-8 py-3 font-medium text-[#0057B7] transition hover:bg-[#0057B7] hover:text-white"
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
                </div>
            </div>
        </main>
    );
}

export default function LanguageInstitutesPage() {
    return (
        <Suspense fallback={null}>
            <LanguageInstitutesPageInner />
        </Suspense>
    );
}
