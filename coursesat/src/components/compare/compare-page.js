"use client";

import { Fragment, useCallback, useEffect, useMemo, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faExchangeAlt, faEye, faTrash } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { fetchApiJson } from "@/lib/api";
import { getStoredAuthToken } from "@/lib/auth";
import { formatCurrency } from "@/lib/format";
import {
  readGuestCompare,
  removeGuestCompare,
  resolveGuestCompareItems,
  useCourseEnglishInteractions,
} from "@/lib/interactions";

const TYPE_LABELS = {
  language_courses: { en: "Language Course", ar: "دورة لغة" },
  online_courses: { en: "Online Course", ar: "دورة أونلاين" },
  summer_camps: { en: "Summer Camp", ar: "مخيم صيفي" },
  training_courses: { en: "Training Course", ar: "دورة تدريبية" },
};

export default function ComparePage() {
  const { direction, language, t } = useLocale();
  const isRtl = direction === "rtl";
  const isArabic = language === "ar";
  const loc = useCallback(
    (key, fallback = "") => t(`pages.student.compare.${key}`, fallback),
    [t],
  );

  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const { reloadInteractions } = useCourseEnglishInteractions();

  const getTitle = useCallback(
    (course) => (isArabic ? course?.ar_name || course?.name || "-" : course?.name || course?.ar_name || "-"),
    [isArabic],
  );

  const getSchool = useCallback(
    (course) =>
      isArabic ? course?.school_ar_name || course?.school_name || "-" : course?.school_name || course?.school_ar_name || "-",
    [isArabic],
  );

  const getCity = useCallback(
    (course) => (isArabic ? course?.city_ar_name || course?.city_name || "-" : course?.city_name || course?.city_ar_name || "-"),
    [isArabic],
  );

  const getCountry = useCallback(
    (course) =>
      isArabic ? course?.country_ar_name || course?.country_name || "-" : course?.country_name || course?.country_ar_name || "-",
    [isArabic],
  );

  const getType = useCallback(
    (courseType) => TYPE_LABELS[courseType]?.[language] || courseType || "-",
    [language],
  );

  const rows = useMemo(
    () => [
      { key: "type", label: loc("row_type", "Type"), value: (item) => getType(item.course_type) },
      { key: "course", label: loc("row_course", "Course"), value: (item) => getTitle(item.course) },
      { key: "weeks", label: loc("row_weeks", "Weeks"), value: (item) => item.weeks || "-" },
      { key: "school", label: loc("row_school", "School"), value: (item) => getSchool(item.course) },
      {
        key: "price",
        label: loc("row_price", "Price"),
        value: (item) => {
          const course = item.course || {};
          const currency = course.currency_code || course.currency || "SAR";
          if (item.course_type === "language_courses") {
            const perWeek =
              currency === "GBP"
                ? (course.price_per_week_gbp ?? course.price_gbp ?? course.price_per_week ?? course.price ?? 0)
                : (course.price_per_week_sar ?? course.price_sar ?? course.price_per_week ?? course.price ?? 0);
            const weeks = Number(item.weeks) || 12;
            return formatCurrency(Number(perWeek) * weeks, currency, language);
          }
          return formatCurrency(course.price, currency, language);
        },
      },
      { key: "lessons", label: loc("row_lessons", "Lessons / Week"), value: (item) => item.course?.lessons_per_week || "-" },
      { key: "study_time", label: loc("row_study_time", "Study Time"), value: (item) => item.course?.study_time || "-" },
      { key: "level", label: loc("row_level", "Required Level"), value: (item) => item.course?.required_level || "-" },
      { key: "start_date", label: loc("row_start_date", "Start Date"), value: (item) => item.course?.start_date || "-" },
      { key: "city", label: loc("row_city", "City"), value: (item) => getCity(item.course) },
      { key: "country", label: loc("row_country", "Country"), value: (item) => getCountry(item.course) },
    ],
    [loc, getType, getTitle, getSchool, getCity, getCountry, language],
  );

  const loadCompare = useCallback(async () => {
    setLoading(true);
    setError("");

    try {
      const token = getStoredAuthToken();
      if (token) {
        const json = await fetchApiJson("/courseenglish/compare");
        setItems((json?.items || []).filter((item) => item?.course?.id));
      } else {
        const guestItems = readGuestCompare();
        if (!guestItems.length) {
          setItems([]);
        } else {
          setItems(await resolveGuestCompareItems());
        }
      }
    } catch (e) {
      setError(e?.message || "Failed to load compare list");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadCompare();

    const onGuestUpdate = () => loadCompare();
    window.addEventListener("guest-interactions-update", onGuestUpdate);
    window.addEventListener("auth-update", onGuestUpdate);
    return () => {
      window.removeEventListener("guest-interactions-update", onGuestUpdate);
      window.removeEventListener("auth-update", onGuestUpdate);
    };
  }, [loadCompare]);

  const removeItem = async (row) => {
    try {
      const token = getStoredAuthToken();
      if (token) {
        const res = await fetchApiJson("/courseenglish/compare/remove", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            course_type: row.course_type,
            course_id: row.course_id,
          }),
        });
        if (res?.success === false) return;
      } else {
        removeGuestCompare(row.course_type, row.course_id);
      }

      setItems((prev) =>
        prev.filter(
          (item) => !(item.course_type === row.course_type && Number(item.course_id) === Number(row.course_id)),
        ),
      );
      await reloadInteractions();
    } catch {
      // ignore
    }
  };

  const desktopLabelWidth = 220;
  const desktopColMin = items.length >= 5 ? 220 : items.length === 4 ? 240 : 280;
  const desktopMinWidth = desktopLabelWidth + desktopColMin * items.length;
  const valueDividerClass = isRtl ? "border-r" : "border-l";
  const rowLabelAlignClass = isRtl ? "justify-end text-right" : "justify-start text-left";

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 md:px-6" dir={direction}>
      <div className="mb-6 rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm sm:p-6">
        <div className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-[#0057B7] shadow-inner">
          <FontAwesomeIcon icon={faExchangeAlt} className="h-5 w-5" />
        </div>
        <h1 className="text-3xl font-semibold text-slate-900">{loc("title", "Compare Courses")}</h1>
        <p className="mt-1 text-sm text-slate-500">{loc("subtitle", "Compare your selected courses side by side.")}</p>
      </div>

      {loading && <div className="py-16 text-center text-slate-500">{loc("loading", "Loading...")}</div>}

      {!loading && error && (
        <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-center text-red-700">{error}</div>
      )}

      {!loading && !error && items.length > 0 && (
        <>
          <p className="mb-2 text-center text-xs text-slate-400 sm:hidden">{loc("swipe", "Swipe sideways to see the rest")}</p>
          <div className="overflow-hidden rounded-2xl border border-gray-200 border-t bg-white text-gray-700 shadow-sm">
            <div className="overflow-x-auto">
              <div
                className="grid"
                style={{
                  minWidth: desktopMinWidth,
                  gridTemplateColumns: `${desktopLabelWidth}px repeat(${items.length}, minmax(${desktopColMin}px, 1fr))`,
                }}
              >
                <div className="flex h-44 items-center justify-center border-b border-gray-200 bg-slate-50 px-4">
                  <span className="text-sm font-medium uppercase tracking-wider text-slate-400">{loc("criteria", "Criteria")}</span>
                </div>

                {items.map((item) => {
                  const course = item.course || {};
                  const title = getTitle(course);
                  const imageSrc = course?.logo || course?.image || "/assets/hero.png";
                  const viewHref =
                    course.url ||
                    (item.course_type === "online_courses" && course.school_slug
                      ? `/online-courses/${course.school_slug}?course_id=${item.course_id}`
                      : course.school_slug
                        ? `/language-institutes/${course.school_slug}?course_id=${item.course_id}${item.weeks ? `&weeks=${item.weeks}` : ""}`
                        : "#");
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
                            <ActionIconLink href={viewHref} icon={faEye} label={loc("view", "View")} tone="blue" />
                            <ActionIconButton
                              onClick={() => removeItem(item)}
                              icon={faTrash}
                              label={loc("remove", "Remove")}
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
                      <div
                        className={`flex h-12 items-center border-b border-gray-200 px-4 text-sm font-normal text-slate-700 ${rowLabelAlignClass} ${rowBg}`}
                      >
                        {row.label}
                      </div>
                      {items.map((item) => (
                        <div
                          key={`${row.key}-${item.course_type}-${item.course_id}`}
                          className={`flex h-12 items-center justify-center border-b border-gray-200 px-4 text-sm text-slate-800 ${valueDividerClass} ${rowBg}`}
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
          <h2 className="text-2xl font-medium text-[#102233]">{loc("empty", "No courses in compare list")}</h2>
          <p className="mb-8 mt-2 text-slate-500">{loc("empty_sub", "Add courses from cards using the compare icon.")}</p>
          <Link
            href="/language-institutes"
            className="inline-flex items-center gap-2 rounded-xl bg-[#003B5C] px-8 py-3 font-medium text-white hover:bg-[#002a42]"
          >
            {loc("explore", "Explore Courses")}
          </Link>
        </div>
      )}
    </div>
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
