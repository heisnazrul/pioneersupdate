"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import DestinationDropdown from "@/app/components/DestinationDropdown";
import FilterDropdown from "@/app/components/FilterDropdown";
import FilterDatePicker from "@/app/components/FilterDatePicker";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function SummerProgramsHero() {
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const page = getCourseEnglishMessages(language)?.pages?.summer_programs ?? {};
    const heading = page?.hero?.heading || (isArabic ? "مخيمات صيفية لا تُنسى للشباب" : "Unforgettable Summer Camps for Teens");
    const subheading = page?.hero?.subheading || (isArabic ? "استكشف العالم، تعلم الإنجليزية، واصنع صداقات تدوم." : "Explore the world, learn English, and make lifelong friends.");
    return (
        <section className="relative flex flex-col items-center justify-center bg-gradient-to-b from-[#E0EFF8] to-[#F0F7FC] px-4 py-20 text-center md:py-28">
            {/* Headings */}
            <h1 className="mb-4 text-3xl font-extrabold text-[#0F172A] md:text-5xl">
                {heading}
            </h1>
            <p className="mb-12 text-lg text-[#64748B] md:text-xl">
                {subheading}
            </p>

            {/* Search Bar Container */}
            <div className="w-full max-w-6xl rounded-xl bg-white px-6 py-6 shadow-lg md:px-6">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                    {/* 1. Destination */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:pr-4">
                        <DestinationDropdown
                            label={isArabic ? "الوجهة" : "Destination"}
                            placeholder={isArabic ? "الدولة أو المدينة" : "Country or city"}
                        />
                    </div>

                    {/* 2. Age Group */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <FilterDropdown
                            label={isArabic ? "الفئة العمرية" : "Age Group"}
                            placeholder={isArabic ? "اختر العمر" : "Select age"}
                            options={
                                isArabic ? ["10-14 سنة", "14-17 سنة", "16-18 سنة"] : ["10-14 years", "14-17 years", "16-18 years"]
                            }
                        />
                    </div>

                    {/* 3. Duration */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <FilterDropdown
                            label={isArabic ? "المدة" : "Duration"}
                            placeholder={isArabic ? "اختر المدة" : "Select duration"}
                            options={
                                isArabic ? ["أسبوع", "أسبوعان", "3 أسابيع", "4+ أسابيع"] : ["1 Week", "2 Weeks", "3 Weeks", "4+ Weeks"]
                            }
                        />
                    </div>

                    {/* 4. Start Date */}
                    <div className="flex flex-1 flex-col text-left md:px-4">
                        <FilterDatePicker
                            label={isArabic ? "تاريخ البدء" : "Start Date"}
                            placeholder={isArabic ? "اختر تاريخ البدء" : "Select start date"}
                        />
                    </div>

                    {/* Search Button */}
                    <div className="flex justify-center md:justify-end">
                        <button
                            className="flex h-12 w-12 items-center justify-center rounded-full bg-[#0057B7] text-white transition hover:bg-[#004494]"
                            aria-label={isArabic ? "بحث" : "Search"}
                        >
                            <FontAwesomeIcon icon={faSearch} className="h-5 w-5" />
                        </button>
                    </div>

                </div>
            </div>
        </section>
    );
}
