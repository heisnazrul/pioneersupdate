"use client";

import { useMemo } from "react";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import OnlineCourseCard, { mapOnlineCourse, onlineCourseHref } from "@/components/shared/online-course-card";

export default function DesktopOnlineCourses() {
  const { data, loading } = useApi("/courseenglish/online-courses");
  const { language, direction, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";

  const courses = useMemo(
    () => (data?.online_courses ?? []).map((course) => mapOnlineCourse(course, isArabic)),
    [data?.online_courses, isArabic],
  );

  const heading = t("pages.online_courses.hero.heading", isArabic ? "الدراسة عن بعد" : "Master English Online from Anywhere");
  const subheading = t(
    "pages.online_courses.hero.subheading",
    isArabic
      ? "تعلم اللغة الإنجليزية أينما كنت، بخيارات مرنة تناسب وقتك وأهدافك"
      : "Flexible, accredited online courses designed for your success.",
  );

  return (
    <main className="min-h-screen bg-[#EEF4FB]" dir={direction}>
      <DesktopHeader />
      <div className="px-6 py-10 md:px-10 xl:px-20 2xl:px-40">
        <div className="mb-10 text-center">
          <h1 className="text-[32px] font-bold text-[#111827] leading-[1.3] sm:text-[40px]">{heading}</h1>
          <p className="mt-3 max-w-2xl mx-auto text-lg text-slate-500">{subheading}</p>
        </div>

        {loading ? (
          <div className="flex min-h-[40vh] items-center justify-center">
            <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#1F63AE] border-t-transparent" />
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {courses.map((course) => (
              <OnlineCourseCard
                key={course.id}
                course={course}
                isArabic={isArabic}
                currency={currency}
                href={onlineCourseHref(course)}
                t={t}
                className="w-full"
              />
            ))}
          </div>
        )}

        {!loading && courses.length === 0 ? (
          <div className="rounded-3xl border border-[#E4EDF8] bg-white p-12 text-center text-slate-500">
            {isArabic ? "لا توجد دورات أونلاين حالياً." : "No online courses available yet."}
          </div>
        ) : null}
      </div>
      <DesktopFooter />
    </main>
  );
}
