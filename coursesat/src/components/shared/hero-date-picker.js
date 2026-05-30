"use client";

import { useState, useRef, useEffect, useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faChevronRight, faCalendarAlt } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";

const EN_DAY_LABELS = ["SAT", "FRI", "THU", "WED", "TUE", "MON", "SUN"];
const EN_MONTHS = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

const AR_DAY_LABELS = ["سبت", "الاحد", "الاثنين", "الثلاثاء", "الاربعاء", "الخميس", "الجمعة"];

const DAY_MS = 24 * 60 * 60 * 1000;

function startOfDay(date) {
  const d = new Date(date);
  d.setHours(0, 0, 0, 0);
  return d;
}

function getMinSelectableDate() {
  const today = startOfDay(new Date());
  return new Date(today.getTime() + 7 * DAY_MS);
}

function getDefaultMondayAfterTenDays() {
  const base = startOfDay(new Date(new Date().getTime() + 10 * DAY_MS));
  const day = base.getDay();
  const add = (8 - day) % 7;
  return new Date(base.getTime() + add * DAY_MS);
}

function isSelectableDate(date, minDate) {
  if (!(date instanceof Date) || Number.isNaN(date.getTime())) {
    return false;
  }
  const normalized = startOfDay(date);
  return normalized.getDay() === 1 && normalized >= minDate;
}

/** Map JS day (0=Sun … 6=Sat) to column index for Saturday-first grid. */
function getArabicColumnIndex(dayOfWeek) {
  return (dayOfWeek + 1) % 7;
}

/** Map JS day to column index for English Figma layout (Sat → Sun, right-to-left labels). */
function getEnglishColumnIndex(dayOfWeek) {
  return (7 - dayOfWeek - 1) % 7;
}

export default function HeroDatePicker({
  label,
  placeholder,
  selectedDate,
  onSelect,
  variant = "default",
  fromLabel,
  toLabel,
  endDate,
  formatDisplay,
}) {
  const { language } = useLocale();
  const isArabic = language === "ar";
  const [isOpen, setIsOpen] = useState(false);
  const [internalSelectedDate, setInternalSelectedDate] = useState(() => getDefaultMondayAfterTenDays());
  const [currentMonth, setCurrentMonth] = useState(() => getDefaultMondayAfterTenDays());

  const dropdownRef = useRef(null);
  const autoInitRef = useRef(false);

  const isControlled = typeof selectedDate !== "undefined";
  const fallbackDate = getDefaultMondayAfterTenDays();
  const minSelectableDate = getMinSelectableDate();
  const controlledDate = isSelectableDate(selectedDate, minSelectableDate) ? selectedDate : null;
  const internalDate = isSelectableDate(internalSelectedDate, minSelectableDate) ? internalSelectedDate : fallbackDate;
  const dateToRender = isControlled ? (controlledDate || fallbackDate) : internalDate;

  const dayLabels = isArabic ? AR_DAY_LABELS : EN_DAY_LABELS;
  const getColumnIndex = isArabic ? getArabicColumnIndex : getEnglishColumnIndex;

  const monthTitle = useMemo(() => {
    if (isArabic) {
      const month = new Intl.DateTimeFormat("ar-SA-u-ca-gregory", { month: "long" }).format(currentMonth);
      return `${month} ${currentMonth.getFullYear()}`;
    }
    return `${EN_MONTHS[currentMonth.getMonth()]} ${currentMonth.getFullYear()}`;
  }, [currentMonth, isArabic]);

  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  useEffect(() => {
    if (!isControlled) return;
    if (isSelectableDate(selectedDate, minSelectableDate)) return;
    if (!onSelect || autoInitRef.current) return;
    autoInitRef.current = true;
    onSelect(fallbackDate);
  }, [isControlled, selectedDate, onSelect, fallbackDate, minSelectableDate]);

  const getDaysInMonth = (date) => new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

  const handleDateClick = (date) => {
    const day = date.getDay();
    const normalized = startOfDay(date);
    if (day !== 1 || normalized < minSelectableDate) {
      return;
    }
    if (!isControlled) {
      setInternalSelectedDate(date);
    }
    setIsOpen(false);
    onSelect?.(date);
  };

  const generateDays = () => {
    const daysInMonth = getDaysInMonth(currentMonth);
    const firstDayColumn = getColumnIndex(
      new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1).getDay()
    );
    const days = [];

    for (let i = 0; i < firstDayColumn; i++) {
      days.push(<div key={`empty-${i}`} className="h-10 w-10" />);
    }

    for (let i = 1; i <= daysInMonth; i++) {
      const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), i);
      const isSelected = dateToRender && date.toDateString() === dateToRender.toDateString();
      const isToday = new Date().toDateString() === date.toDateString();
      const isMonday = date.getDay() === 1;
      const isBeforeMin = startOfDay(date) < minSelectableDate;
      const isDisabled = !isMonday || isBeforeMin;

      days.push(
        <button
          key={i}
          type="button"
          className={`flex h-10 w-10 items-center justify-center rounded-full text-sm font-normal transition
            ${
              isSelected
                ? isArabic
                  ? "bg-slate-200 text-slate-900"
                  : "bg-[#0057B7] text-white shadow-md"
                : isDisabled
                  ? "text-slate-300"
                  : isMonday
                    ? "font-medium text-slate-900 hover:bg-slate-100"
                    : "text-slate-300"
            }
            ${isToday && !isSelected ? "ring-1 ring-[#0057B7] text-[#0057B7]" : ""}
            ${isDisabled ? "cursor-not-allowed hover:bg-transparent" : ""}
          `}
          disabled={isDisabled}
          onClick={() => handleDateClick(date)}
        >
          {i}
        </button>
      );
    }

    return days;
  };

  const nextMonth = () => {
    setCurrentMonth(new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1));
  };

  const prevMonth = () => {
    setCurrentMonth(new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1));
  };

  const formatDate = (date) => {
    if (!date) return "";
    if (formatDisplay) return formatDisplay(date);
    if (isArabic) {
      return date.toLocaleDateString("ar-SA", {
        year: "numeric",
        month: "long",
        day: "numeric",
        calendar: "gregory",
      });
    }
    return date.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
  };

  const triggerClassName =
    variant === "sidebar"
      ? `cursor-pointer rounded-2xl border bg-white px-5 py-4 shadow-sm transition hover:border-gray-300 ${
          isOpen ? "border-blue-300 ring-2 ring-blue-100" : "border-gray-200"
        }`
      : variant === "borderless"
        ? "cursor-text bg-transparent px-4 py-2"
        : "cursor-text rounded-xl border border-[#F6F8FA] bg-white px-8 py-3 shadow-sm transition hover:border-blue-300 focus-within:ring-2 focus-within:ring-blue-100 2xl:py-4";

  return (
    <div className={`relative w-full ${isOpen ? "z-50" : "z-0"}`} ref={dropdownRef}>
      <div className={triggerClassName} onClick={() => setIsOpen(!isOpen)}>
        {variant === "sidebar" ? (
          <>
            <div className={`mb-2 text-[12px] text-slate-500 ${isArabic ? "text-right" : "text-left"}`}>{label}</div>
            <div className="flex items-center justify-between">
              <div className={`text-[14px] text-[#102233] ${isArabic ? "text-right" : "text-left"}`}>
                {toLabel} {formatDate(endDate || dateToRender) || placeholder}
              </div>
              <div className={`text-[14px] text-[#102233] ${isArabic ? "text-left" : "text-right"}`}>
                {fromLabel} {formatDate(dateToRender) || placeholder}
              </div>
            </div>
          </>
        ) : (
          <div className="flex items-center justify-between">
            <div className="min-w-0 text-start">
              <p className="truncate whitespace-nowrap text-[13px] font-normal text-slate-700 sm:text-sm">{label}</p>
              <div className="mt-1 flex items-center gap-2">
                {!dateToRender && <FontAwesomeIcon icon={faCalendarAlt} className="h-4 w-4 text-slate-400" />}
                <p className="truncate whitespace-nowrap text-[13px] text-slate-500 sm:text-sm">
                  {formatDate(dateToRender) || placeholder}
                </p>
              </div>
            </div>
          </div>
        )}
      </div>

      {isOpen && (
        <div
          dir={isArabic ? "rtl" : "ltr"}
          className={`absolute top-full z-[100] mt-2 rounded-2xl bg-white p-5 shadow-2xl ring-1 ring-black/5 animate-in fade-in zoom-in-95 duration-200 ${
            variant === "sidebar"
              ? "left-0 w-full"
              : `w-[320px] max-w-[calc(100vw-2rem)] sm:w-[340px] ${isArabic ? "left-0 right-auto origin-top-right" : "right-0 left-auto origin-top-left"}`
          }`}
        >
          <div className="mb-4 flex items-center justify-between">
            <button
              type="button"
              onClick={isArabic ? nextMonth : prevMonth}
              className="rounded-full p-2 text-slate-600 transition hover:bg-slate-100"
              aria-label={isArabic ? "الشهر التالي" : "Previous month"}
            >
              <FontAwesomeIcon icon={isArabic ? faChevronRight : faChevronLeft} />
            </button>
            <h3 className="text-lg font-semibold text-slate-900">{monthTitle}</h3>
            <button
              type="button"
              onClick={isArabic ? prevMonth : nextMonth}
              className="rounded-full p-2 text-slate-600 transition hover:bg-slate-100"
              aria-label={isArabic ? "الشهر السابق" : "Next month"}
            >
              <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} />
            </button>
          </div>

          <div className="mb-2 grid grid-cols-7">
            {dayLabels.map((day) => (
              <div
                key={day}
                className={`flex h-8 items-center justify-center text-xs font-medium text-slate-400 ${
                  isArabic ? "" : "uppercase tracking-wider"
                }`}
              >
                {day}
              </div>
            ))}
          </div>

          <div className="grid grid-cols-7 place-items-center gap-1">{generateDays()}</div>
        </div>
      )}
    </div>
  );
}
