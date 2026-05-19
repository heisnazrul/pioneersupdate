"use client";

import { useEffect, useMemo, useState, use } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams, useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar, faSignal, faUser, faClock, faBookOpen } from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/app/components/HeroDropdown";
import HeroDatePicker from "@/app/components/HeroDatePicker";
import { useApi, formatCurrency, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function OnlineCourseDetailPage({ params }) {
  const { slug } = use(params);
  const searchParams = useSearchParams();
  const router = useRouter();
  const { currency, language } = useCourseEnglishSettings();
  const isArabic = language === "ar";

  const { data, loading, error } = useApi(`/courseenglish/online-courses/${slug}`);

  // Moved hooks to top level
  const initialWeeks = searchParams.get("weeks") ? parseInt(searchParams.get("weeks"), 10) : 1;
  const initialCourseId = searchParams.get("course_id") ? parseInt(searchParams.get("course_id"), 10) : null;
  const initialStartDate = searchParams.get("start_date") ? new Date(searchParams.get("start_date")) : null;

  const [selectedCourseId, setSelectedCourseId] = useState(initialCourseId);
  const [weeks, setWeeks] = useState(initialWeeks);
  const [startDate, setStartDate] = useState(initialStartDate);

  const school = data?.school;
  const courses = data?.courses || [];

  useEffect(() => {
    if (courses.length > 0 && !selectedCourseId) {
      const found = initialCourseId ? courses.find((c) => c.id === initialCourseId) : null;
      setSelectedCourseId(found ? found.id : courses[0].id);
    }
  }, [courses, selectedCourseId, initialCourseId]);

  const selectedCourse = courses.find((c) => c.id === selectedCourseId);

  const handleBookNow = () => {
    if (!selectedCourse || !startDate) {
      alert(isArabic ? "يرجى اختيار الدورة وتاريخ البدء" : "Please select a course and start date");
      return;
    }

    const query = new URLSearchParams({
      course_id: selectedCourse.id,
      weeks: weeks,
      start_date: startDate.toISOString().split("T")[0],
      type: "online_courses",
    }).toString();

    router.push(`/online-courses/${slug}/booking?${query}`);
  };

  if (loading) {
    return (
      <div className="p-20 text-center">
        <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" />
      </div>
    );
  }
  if (error || !school) {
    return <div className="p-20 text-center text-red-500">Failed to load online course details.</div>;
  }

  const coursePrice =
    currency === "SAR" ? selectedCourse?.price_sar ?? 0 : selectedCourse?.price_gbp ?? 0;
  const totalPrice = coursePrice * (weeks || 1);

  const priceLabel = formatCurrency(totalPrice, currency) || "-";
  const locationLabel = isArabic
    ? school?.country_ar || school?.location
    : school?.location || school?.country;

  return (
    <main className="min-h-screen pb-24 pt-0 lg:pt-6 bg-[#F8FAFC]">
      <div className="container mx-auto px-4">
        {/* ... existing header ... */}
        <div className="mb-6 hidden lg:flex items-center justify-between">
          <Link
            href="/online-courses"
            className="flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7]"
          >
            <Image src="/assets/icons/arrow-left.svg" width={16} height={16} alt="Back" />
            {isArabic ? "العودة إلى القائمة" : "Back to List"}
          </Link>
        </div>

        <div className="mb-8 flex flex-col items-start gap-2 md:flex-row md:items-center md:justify-between">
          <div>
            <h1 className="text-3xl font-semibold text-slate-900 md:text-4xl">
              {isArabic
                ? [school.ar_name, school.city_ar || school.city_ar_name || school.city || school.location, school.name].filter(Boolean).join(' - ')
                : school.name}
            </h1>
            <div className="mt-2 flex items-center gap-2 text-sm font-medium text-slate-500">
              {school.flag && (
                <Image src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" unoptimized />
              )}
              <span>{locationLabel}</span>
              <span>•</span>
              <span className="flex items-center gap-1 text-[#F59E0B]">
                <FontAwesomeIcon icon={faStar} /> {school.rating || 0}
              </span>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">
          <div className="lg:col-span-8 space-y-8">
            <div className="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6">
              <div className="flex-1 space-y-4">
                <p className="text-sm leading-relaxed text-slate-600 max-h-40 overflow-y-auto pr-2 scrollbar-thin">
                  {isArabic ? school.ar_description || school.description : school.description}
                </p>

                {/* Accreditations */}
                {(school.accreditations || []).length > 0 && (
                  <div className="pt-4 border-t border-gray-100">
                    <div className="flex flex-wrap gap-3">
                      {school.accreditations.map((acc, idx) => (
                        <div key={acc.id || idx} className="relative h-10 w-16 rounded-lg border border-gray-200 bg-white p-1.5 flex items-center justify-center hover:border-[#0057B7] transition">
                          {acc.logo && <Image src={acc.logo} alt={isArabic ? acc.ar_name || acc.name : acc.name} fill className="object-contain p-1" unoptimized />}
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>

              {/* Image Side */}
              <div className="w-full md:w-2/5 hidden md:block">
                <div className="relative aspect-[4/3] w-full overflow-hidden rounded-2xl bg-gray-100">
                  {school.image && <Image src={getImageUrl(school.image)} alt={school.name} fill className="object-cover transition-all duration-500" unoptimized />}
                </div>
              </div>
            </div>

            <div>
              <div className="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h3 className="text-xl font-semibold text-slate-900">
                  {isArabic ? "الخطوة 1: اختر الدورة المناسبة" : "Step 1: Choose the Suitable Course"}
                </h3>
                <div className="flex gap-4 w-full md:w-auto">
                  <div className="w-full md:w-56">
                    <HeroDatePicker
                      label={isArabic ? "تاريخ البدء" : "Start Date"}
                      placeholder={isArabic ? "اختر تاريخ البدء" : "Select start date"}
                      onSelect={(date) => setStartDate(date)}
                    />
                    {startDate && (
                      <div className="mt-1 text-xs text-slate-500 font-normal">
                        {isArabic ? "المحدد:" : "Selected:"} {startDate.toLocaleDateString()}
                      </div>
                    )}
                  </div>
                  <div className="w-full md:w-56">
                    <HeroDropdown
                      label={isArabic ? "الأسابيع" : "Weeks"}
                      placeholder={isArabic ? "اختر عدد الأسابيع" : "Select weeks"}
                      scroll
                      options={Array.from({ length: 52 }, (_, i) => ({
                        label: isArabic ? `${i + 1} أسبوع` : `${i + 1} Weeks`,
                        value: i + 1,
                      }))}
                      onSelect={(opt) => setWeeks(opt.value)}
                      selectedValue={weeks}
                    />
                  </div>
                </div>
              </div>

              <div className="space-y-4">
                {courses.map((course) => (
                  <label
                    key={course.id}
                    className={`block cursor-pointer rounded-2xl border-2 p-5 transition-all ${selectedCourseId === course.id
                      ? "border-[#0057B7] bg-[#F0F7FC]"
                      : "border-gray-100 bg-white hover:border-gray-200"
                      }`}
                  >
                    <div className="flex items-start gap-4">
                      <div className="mt-1 flex-none">
                        <Image
                          src={
                            selectedCourseId === course.id
                              ? "/assets/icons/selected-blue.svg"
                              : "/assets/icons/selected-null.svg"
                          }
                          width={24}
                          height={24}
                          alt={selectedCourseId === course.id ? "Selected" : "Select"}
                        />
                      </div>
                      <div className="flex-1">
                        <div className="flex justify-between items-start mb-2">
                          <div>
                            <div className="flex items-center gap-2">
                              <h4 className="text-lg font-medium text-slate-900">
                                {isArabic ? course.ar_name || course.name : course.name}
                              </h4>
                              {course.tag && (
                                <span className="rounded bg-[#10B981] px-2 py-0.5 text-[10px] font-medium text-white">
                                  {isArabic ? course.tag_ar_name || course.tag : course.tag}
                                </span>
                              )}
                            </div>
                            <div className="mt-2 flex flex-wrap gap-4 text-xs font-medium text-slate-500">
                              {course.lessons_per_week && (
                                <span className="flex items-center gap-1">
                                  <FontAwesomeIcon icon={faBookOpen} className="text-[#0057B7]" />{" "}
                                  {course.lessons_per_week} {isArabic ? "درس/أسبوع" : "Lessons/week"}
                                </span>
                              )}
                              {course.study_time && (
                                <span className="flex items-center gap-1">
                                  <FontAwesomeIcon icon={faClock} className="text-[#0057B7]" /> {course.study_time}{" "}
                                  {isArabic ? "ساعة/أسبوع" : "Hours/week"}
                                </span>
                              )}
                              {course.min_age && (
                                <span className="flex items-center gap-1">
                                  <FontAwesomeIcon icon={faUser} className="text-[#0057B7]" /> {course.min_age}+
                                </span>
                              )}
                              {course.required_level && (
                                <span className="flex items-center gap-1">
                                  <FontAwesomeIcon icon={faSignal} className="text-[#0057B7]" /> {course.required_level}
                                </span>
                              )}
                            </div>
                          </div>
                          <div className="text-end">
                            <div className="text-lg font-semibold text-[#0F172A]">
                              {formatCurrency(
                                currency === "SAR" ? course.price_sar : course.price_gbp,
                                currency
                              )}
                            </div>
                            <div className="text-xs text-slate-500">
                              {isArabic ? "لكل أسبوع" : "per week"}
                            </div>
                          </div>
                        </div>
                        {course.description && (
                          <p className="text-sm text-slate-600">
                            {isArabic ? course.ar_description || course.description : course.description}
                          </p>
                        )}
                      </div>
                    </div>
                  </label>
                ))}
              </div>
            </div>
          </div>

          <div className="lg:col-span-4">
            <div className="sticky top-4 space-y-6">
              <div className="rounded-3xl border border-gray-100 bg-white p-6 shadow-xl">
                <div className="rounded-2xl bg-[#F0F7FC] py-6 text-center border border-gray-100">
                  <h3 className="text-3xl font-semibold tracking-tight text-[#1E293B]">{priceLabel}</h3>
                  <p className="text-sm font-medium text-slate-500">
                    {isArabic ? "الإجمالي شامل الرسوم" : "Total inclusive of all fees"}
                  </p>
                </div>
                <div className="mt-6 space-y-4 text-sm font-normal text-[#1E293B]">
                  {selectedCourse && (
                    <div className="flex justify-between">
                      <span className="font-medium">
                        {isArabic ? selectedCourse.ar_name || selectedCourse.name : selectedCourse.name} (
                        {weeks} {isArabic ? "أسبوع" : "Weeks"})
                      </span>
                      <span className="font-semibold">{priceLabel}</span>
                    </div>
                  )}
                </div>
                <div className="mt-6">
                  <button type="button" onClick={handleBookNow} className="w-full rounded-xl bg-[#0057B7] p-4 text-sm font-medium text-white shadow-lg shadow-blue-200/50 transition hover:bg-[#004494]">
                    {isArabic ? "مراجعة الطلب" : "Review Request"}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
}
