"use client";

import { useMemo, useState } from "react";
import { useAgentApi } from "@/lib/agentApi";
import { pickLang } from "@/lib/i18nFallback";

export default function CoursePicker({ courseType, onSelect, dir }) {
  const endpoint =
    courseType === "online"
      ? "/courseenglish/home/online"
      : "/courseenglish/home/branding"; // fallback lightweight data
  const { data, loading, error, refetch } = useAgentApi(endpoint);
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";

  // Build a minimal list of courses (mocked from API response shapes we have)
  const courses = useMemo(() => {
    if (!data) return [];
    if (courseType === "online") {
      const arr = data?.online_courses || [];
      return arr.map((c) => ({
        id: c.id,
        title: isArabic ? c.provider_ar_name || c.provider || c.title : c.title || c.provider,
        city: c.city || "",
        country: c.country || "",
        pricePerWeek: c.price_new_sar || c.price_new_gbp || c.price_new || 0,
        currency: c.currency || "GBP",
      }));
    }
    const arr = data?.branding?.schools || [];
    return arr.map((c, idx) => ({
      id: c.id || idx + 1,
      title: isArabic ? c.ar_name || c.name : c.name,
      city: c.city || "",
      country: c.country || "",
      pricePerWeek: c.price || 0,
      currency: c.currency || "GBP",
    }));
  }, [data, courseType, isArabic]);

  if (loading) return <p className="text-sm text-slate-500" dir={dir}>{pickLang(lang, "Loading courses...", "جاري تحميل الدورات...")}</p>;
  if (error) return (
    <div className="text-sm text-rose-600" dir={dir}>
      {pickLang(lang, "Failed to load courses.", "تعذر تحميل الدورات.")} <button className="underline" onClick={refetch}>{pickLang(lang, "Retry", "إعادة المحاولة")}</button>
    </div>
  );

  return (
    <div className="grid gap-3 md:grid-cols-2" dir={dir}>
      {courses.slice(0, 8).map((course) => (
        <button
          key={course.id}
          type="button"
          onClick={() => onSelect(course)}
          className="rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm hover:border-[#1277BE]"
        >
          <p className="text-sm font-medium text-slate-900">{course.title}</p>
          <p className="text-xs text-slate-500">{[course.city, course.country].filter(Boolean).join(" • ")}</p>
          <p className="mt-2 text-sm font-normal text-[#1277BE]">
            {course.pricePerWeek ? `${course.pricePerWeek} ${course.currency}/wk` : pickLang(lang, "Price on request", "السعر عند الطلب")}
          </p>
        </button>
      ))}
    </div>
  );
}
