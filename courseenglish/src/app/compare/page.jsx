"use client";

import { Fragment, useCallback, useEffect, useMemo, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faExchangeAlt,
  faTrash,
  faEye,
} from "@fortawesome/free-solid-svg-icons";
import { buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

const typeLabels = {
  language_courses: { en: "Language Course", ar: "دورة لغة" },
  online_courses: { en: "Online Course", ar: "دورة أونلاين" },
  summer_camps: { en: "Summer Camp", ar: "مخيم صيفي" },
  training_courses: { en: "Training Course", ar: "دورة تدريبية" },
};

function getAuthHeaders() {
  if (typeof window === "undefined") return null;
  const token = localStorage.getItem("auth_token");
  if (!token) return null;
  const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
  return {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: `${tokenType} ${token}`,
  };
}

export default function ComparePage() {
  const { language, isArabic } = useCourseEnglishSettings();
  const { reloadInteractions } = useCourseEnglishInteractions();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const apiOrigin = useMemo(() => {
    try {
      return new URL(buildApiUrl("")).origin;
    } catch {
      return "";
    }
  }, []);

  const abs = useCallback(
    (url) => {
      if (!url) return "/assets/placeholder.png";
      if (url.startsWith("http")) return url;
      if (url.startsWith("//")) return `https:${url}`;
      if (url.startsWith("/")) return `${apiOrigin}${url}`;
      return `${apiOrigin}/${url}`;
    },
    [apiOrigin]
  );

  const formatPrice = useCallback(
    (course) => {
      const price = course?.price;
      if (!price) return "-";
      const currency = course?.currency_code || "GBP";
      try {
        return new Intl.NumberFormat(isArabic ? "ar" : "en-GB", {
          style: "currency",
          currency,
          maximumFractionDigits: 0,
        }).format(price);
      } catch {
        return `${currency} ${price}`;
      }
    },
    [isArabic]
  );

  const getTitle = useCallback(
    (course) => (isArabic ? course?.ar_name || course?.name || "-" : course?.name || course?.ar_name || "-"),
    [isArabic]
  );

  const getSchool = useCallback(
    (course) =>
      isArabic ? course?.school_ar_name || course?.school_name || "-" : course?.school_name || course?.school_ar_name || "-",
    [isArabic]
  );

  const getCity = useCallback(
    (course) => (isArabic ? course?.city_ar_name || course?.city_name || "-" : course?.city_name || course?.city_ar_name || "-"),
    [isArabic]
  );

  const getCountry = useCallback(
    (course) =>
      isArabic ? course?.country_ar_name || course?.country_name || "-" : course?.country_name || course?.country_ar_name || "-",
    [isArabic]
  );

  const getType = useCallback((courseType) => typeLabels[courseType]?.[language] || courseType || "-", [language]);

  const getImage = useCallback(
    (course) => abs(course?.logo || course?.image || course?.thumbnail || course?.thumb || ""),
    [abs]
  );

  const rows = useMemo(
    () => [
      {
        key: "type",
        label: isArabic ? "النوع" : "Type",
        value: (item) => getType(item.course_type),
      },
      {
        key: "course",
        label: isArabic ? "الدورة" : "Course",
        value: (item) => getTitle(item.course),
      },
      {
        key: "school",
        label: isArabic ? "المدرسة" : "School",
        value: (item) => getSchool(item.course),
      },
      {
        key: "price",
        label: isArabic ? "السعر" : "Price",
        value: (item) => formatPrice(item.course),
      },
      {
        key: "lessons",
        label: isArabic ? "الدروس/الأسبوع" : "Lessons / Week",
        value: (item) => item.course?.lessons_per_week || "-",
      },
      {
        key: "study_time",
        label: isArabic ? "وقت الدراسة" : "Study Time",
        value: (item) => item.course?.study_time || "-",
      },
      {
        key: "level",
        label: isArabic ? "المستوى المطلوب" : "Required Level",
        value: (item) => item.course?.required_level || "-",
      },
      {
        key: "start_date",
        label: isArabic ? "تاريخ البدء" : "Start Date",
        value: (item) => item.course?.start_date || "-",
      },
      {
        key: "city",
        label: isArabic ? "المدينة" : "City",
        value: (item) => getCity(item.course),
      },
      {
        key: "country",
        label: isArabic ? "الدولة" : "Country",
        value: (item) => getCountry(item.course),
      },
    ],
    [isArabic, getType, getTitle, getSchool, formatPrice, getCity, getCountry]
  );

  const loadCompare = useCallback(async () => {
    const headers = getAuthHeaders();
    if (!headers) {
      setItems([]);
      setError(isArabic ? "يرجى تسجيل الدخول أولاً." : "Please login first.");
      setLoading(false);
      return;
    }
    setLoading(true);
    setError("");
    try {
      const res = await fetch(buildApiUrl("/courseenglish/compare"), { method: "GET", headers, cache: "no-store" });
      const json = await res.json();
      if (!res.ok) throw new Error(json?.message || "Failed");
      setItems(json?.items || []);
    } catch (e) {
      setError(e.message || "Failed to load compare list");
    } finally {
      setLoading(false);
    }
  }, [isArabic]);

  useEffect(() => {
    loadCompare();
  }, [loadCompare]);

  const removeItem = async (row) => {
    const headers = getAuthHeaders();
    if (!headers) return;
    const res = await fetch(buildApiUrl("/courseenglish/compare/remove"), {
      method: "POST",
      headers,
      body: JSON.stringify({
        course_type: row.course_type,
        course_id: row.course_id,
      }),
    });
    if (res.ok) {
      setItems((prev) =>
        prev.filter(
          (item) => !(item.course_type === row.course_type && Number(item.course_id) === Number(row.course_id))
        )
      );
      reloadInteractions?.();
    }
  };

  const pageText = useMemo(
    () => ({
      title: isArabic ? "مقارنة الدورات" : "Compare Courses",
      subtitle: isArabic ? "قارن دوراتك المختارة جنبًا إلى جنب." : "Compare your selected courses side by side.",
      empty: isArabic ? "لا توجد عناصر للمقارنة" : "No courses in compare list",
      emptyDesc: isArabic ? "أضف الدورات من البطاقات باستخدام زر المقارنة." : "Add courses from cards using the compare icon.",
      browse: isArabic ? "استكشاف الدورات" : "Explore Courses",
      view: isArabic ? "عرض" : "View",
      remove: isArabic ? "إزالة" : "Remove",
      loginHint: isArabic ? "يرجى تسجيل الدخول." : "Please login first.",
      swipe: isArabic ? "اسحب أفقيًا لرؤية بقية العناصر" : "Swipe sideways to see the rest",
      loading: isArabic ? "جاري التحميل..." : "Loading...",
      criteria: isArabic ? "المعيار" : "Criteria",
    }),
    [isArabic]
  );

  const desktopLabelWidth = 220;
  const desktopColMin = items.length >= 5 ? 220 : items.length === 4 ? 240 : 280;
  const desktopMinWidth = desktopLabelWidth + desktopColMin * items.length;
  const valueDividerClass = isArabic ? "border-r" : "border-l";
  const rowLabelAlignClass = isArabic ? "justify-end text-right" : "justify-start text-left";

  return (
    <main dir={isArabic ? "rtl" : "ltr"} className="min-h-screen bg-slate-50 py-14">
      <div className="container mx-auto px-3 sm:px-4">
        <div className="mb-6 rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm sm:p-6">
          <div className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-[#0057B7] shadow-inner">
            <FontAwesomeIcon icon={faExchangeAlt} className="h-5 w-5" />
          </div>
          <h1 className="text-3xl font-semibold text-slate-900">{pageText.title}</h1>
          <p className="mt-1 text-sm text-slate-500">{pageText.subtitle}</p>
        </div>

        {loading && <div className="py-16 text-center text-slate-500">{pageText.loading}</div>}

        {!loading && error && (
          <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-center text-red-700">{error}</div>
        )}

        {!loading && !error && items.length > 0 && (
          <>
            <p className="mb-2 text-center text-xs text-slate-400 sm:hidden">{pageText.swipe}</p>
            <div className="overflow-hidden rounded-2xl border border-gray-200 border-t bg-white text-gray-700 shadow-sm">
              <div className="overflow-x-auto">
                <div
                  className="grid"
                  style={{
                    minWidth: desktopMinWidth,
                    gridTemplateColumns: `${desktopLabelWidth}px repeat(${items.length}, minmax(${desktopColMin}px, 1fr))`,
                  }}
                >
                  <div className="h-44 border-b border-gray-200 bg-slate-50 px-4 flex items-center justify-center">
                    <span className="text-sm font-medium uppercase tracking-wider text-slate-400">{pageText.criteria}</span>
                  </div>

                  {items.map((item) => {
                    const course = item.course || {};
                    const title = getTitle(course);
                    const imageSrc = getImage(course);
                    return (
                      <div
                        key={`${item.course_type}:${item.course_id}`}
                        className={`h-44 border-b border-gray-200 bg-white p-4 ${valueDividerClass}`}
                      >
                        <div className="mx-auto h-full max-w-[280px] rounded-lg border border-gray-300 bg-gray-50 p-2 shadow-sm">
                          <div className="flex h-full gap-2">
                            <div className="relative flex-1 overflow-hidden rounded-md border border-gray-200 bg-white">
                              <Image src={imageSrc} alt={title} fill className="object-contain p-2" unoptimized />
                            </div>
                            <div className="flex w-8 flex-col items-center justify-start gap-2 pt-1">
                              <ActionIconLink href={course.url || "#"} icon={faEye} label={pageText.view} tone="blue" />
                              <ActionIconButton
                                onClick={() => removeItem(item)}
                                icon={faTrash}
                                label={pageText.remove}
                                danger
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    );
                  })}

                  {rows.map((row, rowIdx) => {
                    const rowBg = rowIdx % 2 === 0 ? "bg-gray-100" : "bg-white";
                    return (
                      <Fragment key={row.key}>
                        <div className={`h-12 border-b border-gray-200 px-4 text-sm font-normal text-slate-700 flex items-center ${rowLabelAlignClass} ${rowBg}`}>
                          {row.label}
                        </div>
                        {items.map((item) => (
                          <div
                            key={`${row.key}-${item.course_type}-${item.course_id}`}
                            className={`h-12 border-b border-gray-200 px-4 text-sm text-slate-800 flex items-center justify-center ${valueDividerClass} ${rowBg}`}
                          >
                            <span className="truncate">{row.value(item)}</span>
                          </div>
                        ))}
                      </Fragment>
                    );
                  })}
                </div>
              </div>
            </div>
          </>
        )}

        {!loading && !error && items.length === 0 && (
          <div className="py-20 text-center">
            <h2 className="text-2xl font-medium text-slate-900">{pageText.empty}</h2>
            <p className="mb-8 mt-2 text-slate-500">{pageText.emptyDesc}</p>
            <Link
              href="/"
              className="inline-flex items-center gap-2 rounded-xl bg-[#003B5C] px-8 py-3 font-medium text-white hover:bg-[#002a42]"
            >
              {pageText.browse}
            </Link>
          </div>
        )}
      </div>
    </main>
  );
}

function ActionIconLink({ href, icon, label, tone = "default" }) {
  const toneClass =
    tone === "blue"
      ? "border-blue-200 text-blue-700 hover:bg-blue-50"
      : "border-slate-200 text-slate-700 hover:bg-slate-50";
  return (
    <Link
      href={href}
      aria-label={label}
      title={label}
      className={`grid h-7 w-7 place-items-center rounded-full border bg-white shadow-sm transition ${toneClass}`}
    >
      <FontAwesomeIcon icon={icon} className="h-3 w-3" />
    </Link>
  );
}

function ActionIconButton({ onClick, icon, label, danger = false }) {
  const toneClass = danger
    ? "border-red-200 text-red-600 hover:bg-red-50"
    : "border-slate-200 text-slate-700 hover:bg-slate-50";
  return (
    <button
      type="button"
      onClick={onClick}
      aria-label={label}
      title={label}
      className={`grid h-7 w-7 place-items-center rounded-full border bg-white shadow-sm transition ${toneClass}`}
    >
      <FontAwesomeIcon icon={icon} className="h-3 w-3" />
    </button>
  );
}
