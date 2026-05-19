"use client";

import { useMemo } from "react";
import OnlineCoursesHero from "@/app/components/OnlineCoursesHero";
import OnlineCourseCard from "@/app/components/OnlineCourseCard";
import SortDropdown from "@/app/components/SortDropdown";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

// Fallback mock data
import mockOnlineCourses from "@/data/mocks/onlineCourses.json";

export default function OnlineCoursesPage() {
  const { data, loading } = useApi("/courseenglish/online-courses");
  const { currency, language, isArabic } = useCourseEnglishSettings();
  const page = getCourseEnglishMessages(language)?.pages?.online_courses ?? {};

  const courses = useMemo(() => {
    const apiCourses = data?.online_courses ?? [];
    if (!apiCourses.length) return mockOnlineCourses;
    return apiCourses.map((course) => ({
      id: course.id,
      title: (isArabic ? course.ar_title : course.title) || course.name,
      provider: (isArabic ? course.provider_ar_name : course.provider) || course.provider,
      slug: course.school_slug || course.slug,
      providerAr: course.provider_ar_name,
      country: (isArabic ? course.country_ar_name : course.country) || course.country,
      countryAr: course.country_ar_name,
      flag: course.flag,
      mode: course.mode || "Online",
      discountLabel: isArabic ? course.tag_ar_name || course.tag : course.tag,
      priceValue:
        currency === "SAR"
          ? course.price_new_sar ?? course.price_new
          : course.price_new_gbp ?? course.price_new,
      currency,
      priceUnit: course.price_unit,
      lessons: course.lessons_per_week
        ? `${course.lessons_per_week} Lessons/week`
        : course.study_time
          ? `${course.study_time} Hours/week`
          : null,
      image: course.image || "/assets/hero.png",
      rating: course.rating,
    }));
  }, [data, currency, isArabic]);

  return (
    <main className="min-h-screen bg-[#F0F7FC]">
      <OnlineCoursesHero />

      <div className="container mx-auto px-4 py-8">
        {/* Header / Stats */}
        <div className="relative z-20 mb-8 flex flex-col items-start justify-between gap-4 border-b border-gray-200/60 pb-6 md:flex-row md:items-end">
          <div>
            <h2 className="text-2xl font-extrabold text-[#0F172A] md:text-3xl">
              {data?.total ?? courses.length} {page?.results?.count_label || (isArabic ? "دورات متاحة وفق اختيارك" : "courses available based on your choice")}
            </h2>
            <p className="mt-2 text-base font-normal text-slate-500">
              {page?.results?.step_label || (isArabic ? "الخطوة 1: اختر الدورة الإلكترونية المناسبة لك" : "Step 1: Choose the right online course for you")}
            </p>
          </div>

          {/* Sort Dropdown */}
          <SortDropdown />
        </div>

        {loading ? (
          <div className="flex justify-center py-20">
            <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
          </div>
        ) : (
          <>
            {/* Main Content Grid */}
            <div className="grid grid-cols-1 gap-6 md:grid-cols-3 xl:grid-cols-4">
              {courses.map((course) => (
                <OnlineCourseCard key={course.id} course={course} isArabic={isArabic} />
              ))}
          </div>

            {/* Load More Button */}
            <div className="mt-12 flex justify-center">
              <button className="flex items-center gap-2 rounded-full border-2 border-[#0057B7] bg-transparent px-8 py-3 font-medium text-[#0057B7] transition hover:bg-[#0057B7] hover:text-white">
                <span>{getCourseEnglishMessages(language)?.layouts?.common?.load_more || (isArabic ? "عرض المزيد" : "Load More")}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
              </button>
            </div>
          </>
        )}
      </div>
    </main>
  );
}
