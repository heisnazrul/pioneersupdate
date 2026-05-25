"use client";

import { useMemo, useState, useCallback, Suspense, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch, faChevronUp, faChevronDown } from "@fortawesome/free-solid-svg-icons";
import HeroSearch from "@/components/shared/hero-search";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import InstituteCard from "@/components/shared/institute-card";
import SortDropdown from "@/components/shared/sort-dropdown";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

// Fallback mock data
import mockInstitutes from "@/mocdata/institutes.json";

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

// -- Hero Component --
function LanguageInstitutesHero() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { data } = useApi("/courseenglish/utilities");
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const page = t("pages.language_institutes", {});

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

    const courseTypes = (data?.language_course_types ?? []).map((type) =>
        isArabic ? type.ar_name || type.name : type.name || type.ar_name
    );

    const heroTitle = page?.hero?.heading || (isArabic ? "اكتشف أفضل معاهد اللغات حول العالم" : "Discover the Best Language Institutes Worldwide");
    const heroSubtitle = page?.hero?.subheading || (isArabic ? "مجموعة مختارة من أفضل معاهد اللغات المعتمدة." : "A curated selection of the best accredited language institutes.");

    const handleSearch = () => {
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

    const weeksOptions = Array.from({ length: 52 }, (_, i) =>
        isArabic ? `${i + 1} أسبوع` : `${i + 1} Week${i === 0 ? "" : "s"}`
    );

    return (
        <section className="relative flex flex-col items-center justify-center bg-gradient-to-b from-white/40 via-[#E0EFF8] to-white/60 px-4 pt-20 pb-10">
            <h1 className="mb-4 text-3xl font-bold text-[#0F172A] md:text-5xl whitespace-pre-line text-center">
                {heroTitle}
            </h1>
            <p className="mb-12 text-lg text-[#64748B] md:text-xl text-center">
                {heroSubtitle}
            </p>

            <div className="w-full max-w-6xl rounded-2xl bg-white px-4 py-3 shadow-sm">
                <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div className="flex flex-1 flex-col border-b border-gray-100 md:border-b-0 md:border-e md:px-2 text-start">
                        <HeroSearch
                            placeholder={page?.hero?.labels?.destination || (isArabic ? "ادخل وجهتك المفضلة" : "Enter your preferred destination")}
                            subPlaceholder={page?.hero?.labels?.destination_placeholder || (isArabic ? "ادخل الدولة أو المدينة أو المعهد" : "Enter country, city, or institute")}
                            value={destination?.name}
                            onSelect={(dest) => setDestination(dest)}
                            variant="borderless"
                        />
                    </div>
                    <div className="flex flex-1 flex-col border-b border-gray-100 md:border-b-0 md:border-e md:px-2 text-start">
                        <HeroDropdown
                            label={page?.hero?.labels?.course || (isArabic ? "نوع الدورة" : "Course type")}
                            placeholder={page?.hero?.labels?.course_placeholder || (isArabic ? "اختر نوع الدورة" : "Choose course type")}
                            options={courseTypes.length ? courseTypes : [
                                "General English",
                                "Intensive English",
                                "Semi-Intensive",
                                "IELTS Preparation",
                                "Business English",
                            ]}
                            value={courseType}
                            onSelect={(val) => setCourseType(val)}
                            variant="borderless"
                        />
                    </div>
                    <div className="flex flex-1 flex-col border-b border-gray-100 md:border-b-0 md:border-e md:px-2 text-start">
                        <HeroDropdown
                            label={page?.hero?.labels?.duration || (isArabic ? "عدد الأسابيع" : "Number of weeks")}
                            placeholder={page?.hero?.labels?.duration_placeholder || (isArabic ? "اختر عدد الأسابيع" : "Choose number of weeks")}
                            options={weeksOptions}
                            value={weeks ? (isArabic ? `${weeks} أسبوع` : `${weeks} Week${weeks === 1 ? "" : "s"}`) : ""}
                            onSelect={(val) => setWeeks(parseInt(val))}
                            variant="borderless"
                        />
                    </div>
                    <div className="flex flex-1 flex-col text-start md:px-2">
                        <HeroDatePicker
                            label={page?.hero?.labels?.start || (isArabic ? "تاريخ البداية" : "Start date")}
                            placeholder={page?.hero?.labels?.start_placeholder || (isArabic ? "اختر تاريخ البداية" : "Choose start date")}
                            selectedDate={startDate}
                            onSelect={(date) => setStartDate(date)}
                            variant="borderless"
                        />
                    </div>
                    <div className="flex justify-center md:justify-end shrink-0 md:ps-2">
                        <button
                            className="flex md:h-12 md:w-12 items-center justify-center rounded-full bg-[#0057B7] text-white transition hover:bg-[#004494]"
                            aria-label={isArabic ? "بحث" : "Search"}
                            onClick={handleSearch}
                        >
                            <FontAwesomeIcon icon={faSearch} className="h-full w-full" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
}

// -- Sidebar Component --
function SidebarSection({ title, children, defaultOpen = true }) {
    const [isOpen, setIsOpen] = useState(defaultOpen);

    return (
        <div className="rounded-2xl bg-[#F8FAFC] p-6 mb-4 last:mb-0 border border-[#EAF0F6]">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="flex w-full items-center justify-between text-start"
            >
                <span className="text-md font-semibold text-slate-900">{title}</span>
                <FontAwesomeIcon
                    icon={isOpen ? faChevronUp : faChevronDown}
                    className="h-4 w-4 text-slate-600"
                />
            </button>
            {isOpen && (
                <>
                    <div className="my-4 h-px w-full bg-slate-200/60"></div>
                    <div className="space-y-4 text-sm">{children}</div>
                </>
            )}
        </div>
    );
}

function RadioOption({ name, label, checked, onChange }) {
    return (
        <label className="group flex cursor-pointer items-center gap-3">
            <div className="relative flex items-center shrink-0">
                <input
                    type="radio"
                    name={name}
                    checked={checked}
                    onChange={onChange}
                    className="peer h-6 w-6 cursor-pointer appearance-none rounded-full border border-gray-300 bg-white checked:border-[#0057B7] checked:border-2 hover:border-[#0057B7]"
                />
                <div className="pointer-events-none absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#0057B7] opacity-0 peer-checked:opacity-100"></div>
            </div>
            <span className="text-sm font-medium text-[#0F172A] transition group-hover:text-[#0057B7]">{label}</span>
        </label>
    );
}

function LanguageInstitutesSidebar({ filters = {}, onFiltersChange }) {
    const { accommodation, pickup, insurance } = filters;
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const sidebar = t("pages.language_institutes.sidebar", {});

    const update = (key, value) => {
        onFiltersChange?.({ ...filters, [key]: value });
    };

    const allLabel = sidebar?.accommodation?.all || (isArabic ? "الكل" : "All");

    return (
        <aside className="flex flex-col gap-4">
            <SidebarSection title={sidebar?.accommodation?.title || (isArabic ? "السكن" : "Accommodation")}>
                <RadioOption
                    name="accommodation"
                    label={sidebar?.accommodation?.with_accommodation || (isArabic ? "مع إقامة" : "With Accommodation")}
                    checked={accommodation === true}
                    onChange={() => update("accommodation", true)}
                />
                <RadioOption
                    name="accommodation"
                    label={sidebar?.accommodation?.without_accommodation || (isArabic ? "بدون إقامة" : "Without Accommodation")}
                    checked={accommodation === false}
                    onChange={() => update("accommodation", false)}
                />
            </SidebarSection>

            <SidebarSection title={sidebar?.pickup?.title || (isArabic ? "الاستقبال من المطار" : "Airport Pickup")}>
                <RadioOption
                    name="pickup"
                    label={sidebar?.pickup?.with_pickup || (isArabic ? "مع توصيل" : "With Pickup")}
                    checked={pickup === true}
                    onChange={() => update("pickup", true)}
                />
                <RadioOption
                    name="pickup"
                    label={sidebar?.pickup?.without_pickup || (isArabic ? "بدون توصيل" : "Without Pickup")}
                    checked={pickup === false}
                    onChange={() => update("pickup", false)}
                />
            </SidebarSection>

            <SidebarSection title={sidebar?.insurance?.title || (isArabic ? "التأمين" : "Insurance")}>
                <RadioOption
                    name="insurance"
                    label={sidebar?.insurance?.with_insurance || (isArabic ? "مع تأمين" : "With Insurance")}
                    checked={insurance === true}
                    onChange={() => update("insurance", true)}
                />
                <RadioOption
                    name="insurance"
                    label={sidebar?.insurance?.without_insurance || (isArabic ? "بدون تأمين" : "Without Insurance")}
                    checked={insurance === false}
                    onChange={() => update("insurance", false)}
                />
            </SidebarSection>
        </aside>
    );
}

// -- Main Page Inner Component --
function LanguageInstitutesPageInner() {
    const searchParams = useSearchParams();
    const queryString = searchParams.toString();
    const selectedWeeksParam = searchParams.get("weeks");
    const selectedStartDateParam = searchParams.get("start_date");
    const { data, loading } = useApi(`/courseenglish/language-institutes?per_page=200&${queryString}`);
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const page = t("pages.language_institutes", {});

    const allCourses = useMemo(() => {
        const api = data?.courses ?? [];
        return api.length ? api : mockInstitutes;
    }, [data]);

    const tags = data?.tags ?? [];

    const [filters, setFilters] = useState({
        accommodation: null,
        pickup: null,
        insurance: null,
    });

    const [sortTag, setSortTag] = useState(null);
    const [sortPrice, setSortPrice] = useState(null);

    const handleSortChange = useCallback(({ tag, priceDirection }) => {
        setSortTag(tag);
        setSortPrice(priceDirection);
    }, []);

    const [visibleCount, setVisibleCount] = useState(PAGE_SIZE);

    const processedCourses = useMemo(() => {
        let list = [...allCourses];

        if (filters.accommodation !== null) {
            list = list.filter((c) => !!c.has_accommodation === filters.accommodation);
        }
        if (filters.pickup !== null) {
            list = list.filter((c) => !!c.has_pickup === filters.pickup);
        }
        if (filters.insurance !== null) {
            list = list.filter((c) => !!c.has_insurance === filters.insurance);
        }

        if (sortTag) {
            list = list.filter((c) => c.tags?.includes(sortTag) || c.tag === sortTag);
        }

        if (sortPrice) {
            const priceKey = "price_sar";
            list.sort((a, b) => {
                const pa = Number(a[priceKey] ?? a.price ?? 0);
                const pb = Number(b[priceKey] ?? b.price ?? 0);
                return sortPrice === "asc" ? pa - pb : pb - pa;
            });
        }

        return list;
    }, [allCourses, filters, sortTag, sortPrice]);

    const visibleCourses = processedCourses.slice(0, visibleCount);
    const hasMore = visibleCount < processedCourses.length;

    const handleFiltersChange = useCallback((newFilters) => {
        setFilters(newFilters);
        setVisibleCount(PAGE_SIZE);
    }, []);

    const headingText = `${processedCourses.length} ${page?.results?.count_label || (isArabic ? "معهدًا متاحًا بناءً على اختيارك" : "institutes available based on your choice")}`;
    const subheadingText = page?.results?.step_label || (isArabic ? "الخطوة 1: اختر المعهد المناسب لك" : "Step 1: Choose the right institute for you");
    const loadMoreText = t("layouts.common.load_more", isArabic ? "عرض المزيد" : "Load More");
    const noMatchText = isArabic ? "لا توجد دورات تطابق الفلاتر" : "No courses match your filters";
    const noMatchHint = isArabic ? "جرب تعديل خيارات الفلترة أو الترتيب" : "Try adjusting the sidebar or sort options";

    return (
        <div className="flex min-h-screen flex-col">
            <DesktopHeader />
            <main className="flex-1 bg-white">
                <LanguageInstitutesHero />

                <div className="container mx-auto px-4 py-8">
                    <div className="relative z-20 mb-6 flex flex-col items-start justify-between gap-4 pb-6 md:flex-row md:items-end">
                        <div>
                            <h2 className="text-2xl text-[#0F172A] text-start">
                                {headingText}
                            </h2>
                            <p className="mt-2 text-xl font-normal text-slate-700 text-start">{subheadingText}</p>
                        </div>

                        <SortDropdown tags={tags} onSortChange={handleSortChange} />
                    </div>

                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-4">
                        <div className="hidden lg:block lg:col-span-1 h-fit">
                            <LanguageInstitutesSidebar
                                filters={filters}
                                onFiltersChange={handleFiltersChange}
                            />
                        </div>

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
            <DesktopFooter />
        </div>
    );
}

export default function DesktopLanguageInstitutes() {
    return (
        <Suspense fallback={null}>
            <LanguageInstitutesPageInner />
        </Suspense>
    );
}
