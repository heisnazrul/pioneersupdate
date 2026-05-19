"use client";

import { useState, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import DestinationDropdown from "@/app/components/DestinationDropdown";
import FilterDropdown from "@/app/components/FilterDropdown";
import FilterDatePicker from "@/app/components/FilterDatePicker";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

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

export default function LanguageInstitutesHero() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const { data } = useApi("/courseenglish/utilities");
    const { isArabic, language } = useCourseEnglishSettings();
    const page = getCourseEnglishMessages(language)?.pages?.language_institutes ?? {};

    // -- State --
    const [destination, setDestination] = useState(null); // { type: 'school'|'city'|'country', slug: string, name: string }
    const [courseType, setCourseType] = useState(null); // string (name) or object? API filtering uses ID or name. Let's use name for now as existing codebase used names.
    const [weeks, setWeeks] = useState(12); // integer
    const [startDate, setStartDate] = useState(null); // Date object

    // -- Sync with URL Params on Load --
    useEffect(() => {
        if (!data) return;

        // 1. Destination
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

        // 2. Course Type
        const typeParam = searchParams.get('course_type');
        if (typeParam) {
            // Try to find matching name in courseTypes
            // We need to map string name to localized name if needed, but parameter usually checks against English name or ID.
            // Let's assume the parameter stores the English name for now.
            setCourseType(typeParam);
        }

        // 3. Weeks
        const weeksParam = searchParams.get('weeks');
        if (weeksParam) setWeeks(parseInt(weeksParam));

        // 4. Start Date
        const dateParam = searchParams.get('start_date');
        if (dateParam) {
            const parsed = parseQueryDate(dateParam);
            if (parsed) setStartDate(parsed);
        }

    }, [data, searchParams, isArabic]);


    const courseTypes = (data?.language_course_types ?? []).map((t) =>
        isArabic ? t.ar_name || t.name : t.name || t.ar_name
    );

    // CMS-driven text with fallbacks
    const heroTitle =
        page?.hero?.heading || (isArabic ? "اكتشف أفضل معاهد اللغة \n حول العالم" : "Discover the Best Language \n Institutes Worldwide");
    const heroSubtitle =
        page?.hero?.subheading || (isArabic ? ".مجموعة مختارة من أفضل معاهد اللغة المعتمدة" : "A curated selection of the best accredited language institutes.");

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

    return (
        <section className="relative flex flex-col items-center justify-center bg-gradient-to-b from-[#E0EFF8] to-[#F0F7FC] px-4 py-20  md:py-28">
            {/* Headings */}
            <h1 className="mb-4 text-3xl font-extrabold text-[#0F172A] md:text-5xl whitespace-pre-line">
                {heroTitle}
            </h1>
            <p className="mb-12 text-lg text-[#64748B] md:text-xl">
                {heroSubtitle}
            </p>

            {/* Search Bar Container */}
            <div className="w-full max-w-6xl rounded-xl bg-white px-6 py-6 shadow-lg md:px-6">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                    {/* 1. Destination */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-start  md:border-b-0 md:border-e md:pb-0 md:pe-4">
                        <DestinationDropdown
                            label={isArabic ? "الوجهة" : "Destination"}
                            placeholder={page?.hero?.labels?.destination_placeholder || (isArabic ? "البلد، المدينة، أو المعهد" : "Country, city, or institute")}
                            value={destination?.name}
                            onSelect={(dest) => setDestination(dest)}
                        />
                    </div>

                    {/* 2. Course Type — from API */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-start md:border-b-0 md:border-e md:pb-0 md:px-4">
                        <FilterDropdown
                            label={page?.hero?.labels?.course || (isArabic ? "نوع الدورة" : "Course Type")}
                            placeholder={page?.hero?.labels?.course_placeholder || (isArabic ? "اختر نوع الدورة" : "Select course type")}
                            options={courseTypes.length ? courseTypes : [
                                "General English",
                                "Intensive English",
                                "Semi-Intensive",
                                "IELTS Preparation",
                                "Business English",
                            ]}
                            value={courseType}
                            onSelect={(val) => setCourseType(val)}
                        />
                    </div>

                    {/* 3. Number of Weeks */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-start md:border-b-0 md:border-e md:pb-0 md:px-4">
                        <FilterDropdown
                            label={page?.hero?.labels?.duration || (isArabic ? "المدة" : "Duration")}
                            placeholder={page?.hero?.labels?.duration_placeholder || (isArabic ? "اختر المدة" : "Select duration")}
                            options={Array.from({ length: 52 }, (_, i) =>
                                isArabic ? `${i + 1} أسبوع` : `${i + 1} Week${i === 0 ? "" : "s"}`
                            )}
                            scroll
                            value={weeks ? (isArabic ? `${weeks} أسبوع` : `${weeks} Week${weeks === 1 ? "" : "s"}`) : ""}
                            onSelect={(val) => {
                                // Extract number from string "1 Week" -> 1
                                const num = parseInt(val);
                                setWeeks(num);
                            }}
                        />
                    </div>

                    {/* 4. Start Date */}
                    <div className="flex flex-1 flex-col text-start md:px-4">
                        <FilterDatePicker
                            label={page?.hero?.labels?.start || (isArabic ? "تاريخ البدء" : "Start Date")}
                            placeholder={page?.hero?.labels?.start_placeholder || (isArabic ? "اختر تاريخ البدء" : "Select start date")}
                            selectedDate={startDate}
                            onSelect={(date) => setStartDate(date)}
                        />
                    </div>

                    {/* Search Button */}
                    <div className="flex justify-center md:justify-end">
                        <button
                            className="flex h-12 w-12 items-center justify-center rounded-full bg-[#0057B7] text-white transition hover:bg-[#004494]"
                            aria-label={isArabic ? "بحث" : "Search"}
                            onClick={handleSearch}
                        >
                            <FontAwesomeIcon icon={faSearch} className="h-5 w-5" />
                        </button>
                    </div>

                </div>
            </div>
        </section>
    );
}
