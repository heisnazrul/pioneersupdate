"use client";

import { useMemo } from "react";
import SummerProgramsHero from "@/app/components/SummerProgramsHero";
import SummerCampCard from "@/app/components/SummerCampCard";
import SortDropdown from "@/app/components/SortDropdown";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

// Fallback mock data
import mockSummerCamps from "@/data/mocks/summerCamps.json";

export default function SummerProgramsPage() {
    const { data, loading } = useApi("/courseenglish/summer-programs");
    const { currency, language, isArabic } = useCourseEnglishSettings();
    const page = getCourseEnglishMessages(language)?.pages?.summer_programs ?? {};

    const camps = useMemo(() => {
        const apiCamps = data?.summer_camps ?? [];
        if (!apiCamps.length) return mockSummerCamps;
        return apiCamps.map((camp) => ({
            id: camp.id,
            title: (isArabic ? camp.ar_title : camp.title) || camp.name,
            slug: camp.slug,
            city: camp.city,
            country: (isArabic ? camp.country_ar_name : camp.country) || camp.country,
            countryAr: camp.country_ar_name,
            ageRange: camp.age_range,
            description: camp.description,
            descriptionAr: camp.ar_description,
            duration: camp.fee_type,
            startDate: camp.start_date,
            priceFromValue:
                currency === "SAR"
                    ? camp.price_from_sar ?? camp.price_from
                    : camp.price_from_gbp ?? camp.price_from,
            currency,
            image: camp.image || "/assets/hero.png",
            flag: camp.flag,
            discountLabel: isArabic ? camp.tag_ar_name || camp.tag : camp.tag,
        }));
    }, [data, currency, isArabic]);

    return (
        <main className="min-h-screen bg-[#F0F7FC]">
            <SummerProgramsHero />

            <div className="container mx-auto px-4 py-8">
                {/* Header / Stats */}
                <div className="relative z-20 mb-8 flex flex-col items-start justify-between gap-4 border-b border-gray-200/60 pb-6 md:flex-row md:items-end">
                    <div>
                        <h2 className="text-2xl font-extrabold text-[#0F172A] md:text-3xl">
                            {data?.total ?? camps.length} {page?.results?.count_label || (isArabic ? "مخيمات متاحة وفق اختيارك" : "camps available based on your choice")}
                        </h2>
                        <p className="mt-2 text-base font-normal text-slate-500">
                            {page?.results?.step_label || (isArabic ? "الخطوة 1: اختر المخيم الصيفي المناسب" : "Step 1: Choose the perfect summer camp")}
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
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-3 xl:grid-cols-4">
                            {camps.map((camp) => (
                                <SummerCampCard key={camp.id} program={camp} isArabic={isArabic} />
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
