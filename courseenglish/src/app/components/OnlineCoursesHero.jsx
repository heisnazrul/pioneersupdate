"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import FilterDropdown from "@/app/components/FilterDropdown";
import FilterDatePicker from "@/app/components/FilterDatePicker";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function OnlineCoursesHero() {
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const locale = getCourseEnglishMessages(language);
    const page = locale?.pages?.online_courses ?? {};
    const heading = page?.hero?.heading || (isArabic ? "تعلّم الإنجليزية أونلاين من أي مكان" : "Master English Online from Anywhere");
    const subheading =
        page?.hero?.subheading ||
        (isArabic ? "دورات معتمدة ومرنة مصممة لنجاحك." : "Flexible, accredited online courses designed for your success.");
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

                    {/* 1. Course Type */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 md:border-b-0 md:border-r md:pb-0 md:pr-4">
                        <FilterDropdown
                            label={isArabic ? "مستوى الدورة" : "Course Level"}
                            placeholder={isArabic ? "اختر المستوى" : "Select level"}
                            options={
                                isArabic
                                    ? ["مبتدئ (A1-A2)", "متوسط (B1-B2)", "متقدم (C1-C2)"]
                                    : ["Beginner (A1-A2)", "Intermediate (B1-B2)", "Advanced (C1-C2)"]
                            }
                        />
                    </div>

                    {/* 2. Focus Area */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <FilterDropdown
                            label={isArabic ? "مجال التركيز" : "Focus Area"}
                            placeholder={isArabic ? "اختر المجال" : "Select focus"}
                            options={
                                isArabic
                                    ? ["إنجليزي عام", "إنجليزي للأعمال", "تحضير IELTS/TOEFL", "إنجليزي للأطفال"]
                                    : ["General English", "Business English", "IELTS/TOEFL Prep", "English for Kids"]
                            }
                        />
                    </div>

                    {/* 3. Schedule */}
                    <div className="flex flex-1 flex-col border-b border-gray-100 pb-2 md:border-b-0 md:border-r md:pb-0 md:px-4">
                        <FilterDropdown
                            label={isArabic ? "الجدول الزمني" : "Schedule"}
                            placeholder={isArabic ? "اختر الوقت" : "Select time"}
                            options={
                                isArabic ? ["صباحي", "مسائي", "ليلي", "نهاية الأسبوع"] : ["Morning", "Afternoon", "Evening", "Weekend"]
                            }
                        />
                    </div>

                    {/* 4. Start Date */}
                    <div className="flex flex-1 flex-col md:px-4">
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
