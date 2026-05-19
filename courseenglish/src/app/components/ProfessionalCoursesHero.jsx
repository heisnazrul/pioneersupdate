"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import DestinationDropdown from "@/app/components/DestinationDropdown";
import FilterDropdown from "@/app/components/FilterDropdown";
import FilterDatePicker from "@/app/components/FilterDatePicker";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function ProfessionalCoursesHero() {
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const page = getCourseEnglishMessages(language)?.pages?.training_courses ?? {};
    const heading = page?.hero?.heading || (isArabic ? "طوّر مسارك المهني بدورات معتمدة" : "Advance Your Career with Professional Courses");
    const subheading = page?.hero?.subheading || (isArabic ? "احصل على شهادات معتمدة ومهارات متخصصة." : "Gain Accredited Certificates and Specialized Skills.");
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

                    {/* 1. Subject */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:pr-4">
                        <FilterDropdown
                            label={isArabic ? "المجال" : "Subject"}
                            placeholder={isArabic ? "اختر المجال" : "Select subject"}
                            options={
                                isArabic
                                    ? ["إدارة", "تسويق", "تقنية المعلومات", "مالية"]
                                    : ["Management", "Marketing", "IT & Tech", "Finance"]
                            }
                        />
                    </div>

                    {/* 2. Destination */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <DestinationDropdown
                            label={isArabic ? "الموقع" : "Location"}
                            placeholder={isArabic ? "مدينة أو عبر الإنترنت" : "City or Online"}
                        />
                    </div>

                    {/* 3. Duration */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 text-left md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <FilterDropdown
                            label={isArabic ? "المدة" : "Duration"}
                            placeholder={isArabic ? "اختر المدة" : "Select duration"}
                            options={
                                isArabic
                                    ? ["قصيرة (1-5 أيام)", "متوسطة (1-4 أسابيع)", "طويلة (أكثر من شهر)"]
                                    : ["Short (1-5 days)", "Medium (1-4 weeks)", "Long (1+ month)"]
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
