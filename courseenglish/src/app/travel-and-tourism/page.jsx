"use client";

import { useMemo } from "react";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import TravelHero from "@/app/components/TravelHero";
import TravelCTACard from "@/app/components/TravelCTACard";
import TravelServices from "@/app/components/TravelServices";
import TravelDestinations from "@/app/components/TravelDestinations";
import TravelFeatures from "@/app/components/TravelFeatures";
import TravelInquiryForm from "@/app/components/TravelInquiryForm";

export default function TravelAndTourismPage() {
    const { data, loading } = useApi("/courseenglish/travel-and-tourism");
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";

    const apiOrigin = useMemo(() => {
        try {
            return new URL(buildApiUrl("")).origin;
        } catch {
            return "";
        }
    }, []);

    const abs = (url) => {
        if (!url) return "/assets/placeholder.png";
        if (url.startsWith("http")) return url;
        if (url.startsWith("//")) return `https:${url}`;
        if (url.startsWith("/")) return `${apiOrigin}${url}`;
        return `${apiOrigin}/${url}`;
    };

    const content =
        isArabic
            ? (data?.ar_content && Object.keys(data.ar_content).length ? data.ar_content : data?.content) ?? {}
            : data?.content ?? {};

    if (loading || !content || Object.keys(content).length === 0) {
        return null;
    }

    return (
        <main className="min-h-screen bg-white">
            <TravelHero hero={content.hero} abs={abs} isArabic={isArabic} />
            <TravelCTACard cta={content.cta_card} isArabic={isArabic} />
            <TravelFeatures items={content.features?.items || []} isArabic={isArabic} />
            <TravelDestinations data={content.destinations} abs={abs} isArabic={isArabic} />
            <TravelServices data={content.services} isArabic={isArabic} />

            {/* Inquiry Section (with anchor for scrolling) */}
            <div className="bg-[#F0F7FC]">
                <TravelInquiryForm inquiry={content.inquiry} isArabic={isArabic} />
            </div>
        </main>
    );
}
