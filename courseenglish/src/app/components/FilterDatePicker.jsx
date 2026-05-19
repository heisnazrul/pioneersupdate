"use client";

import { useEffect, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";

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

export default function FilterDatePicker({
    label,
    placeholder,
    locale = "en-US",
    weekStartsOn = 0,
    dir = "ltr",
    selectedDate, // Controlled prop
    onSelect // Callback
}) {
    const [isOpen, setIsOpen] = useState(false);
    const [internalSelectedDate, setInternalSelectedDate] = useState(() => getDefaultMondayAfterTenDays());
    const [currentMonth, setCurrentMonth] = useState(() => getDefaultMondayAfterTenDays());
    const dropdownRef = useRef(null);
    const autoInitRef = useRef(false);

    const isControlled = typeof selectedDate !== 'undefined';
    // If controlled, use prop. If prop is null (but defined), use it. If undefined, use internal.
    // Note: If selectedDate is strictly undefined, it's uncontrolled.
    const fallbackDate = getDefaultMondayAfterTenDays();
    const minSelectableDate = getMinSelectableDate();
    const controlledDate = isSelectableDate(selectedDate, minSelectableDate) ? selectedDate : null;
    const internalDate = isSelectableDate(internalSelectedDate, minSelectableDate) ? internalSelectedDate : fallbackDate;
    const dateToRender = isControlled ? (controlledDate || fallbackDate) : internalDate;

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        };
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
    const getFirstDayOfMonth = (date) => new Date(date.getFullYear(), date.getMonth(), 1).getDay();

    const formatDate = (date) => {
        if (!date) return "";
        return new Intl.DateTimeFormat(locale, { year: "numeric", month: "long", day: "numeric" }).format(date);
    };

    const formatMonth = (date) => {
        return new Intl.DateTimeFormat(locale, { year: "numeric", month: "long" }).format(date);
    };

    const getWeekdays = () => {
        const base = new Date(2021, 7, 1); // Sunday
        return Array.from({ length: 7 }, (_, i) => {
            const dayIndex = (i + weekStartsOn) % 7;
            const date = new Date(base);
            date.setDate(base.getDate() + dayIndex);
            return new Intl.DateTimeFormat(locale, { weekday: "short" }).format(date);
        });
    };

    const handleDateClick = (date) => {
        const normalized = startOfDay(date);
        if (date.getDay() !== 1 || normalized < minSelectableDate) {
            return;
        }
        if (!isControlled) {
            setInternalSelectedDate(date);
        }
        setIsOpen(false);
        if (onSelect) {
            onSelect(date);
        }
    };

    const generateDays = () => {
        const daysInMonth = getDaysInMonth(currentMonth);
        const firstDay = getFirstDayOfMonth(currentMonth);
        const offset = (firstDay - weekStartsOn + 7) % 7;
        const days = [];

        for (let i = 0; i < offset; i += 1) {
            days.push(<div key={`empty-${i}`} className="h-8 w-8"></div>);
        }

        for (let i = 1; i <= daysInMonth; i += 1) {
            const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), i);
            // safe check for dateToRender in case it's null
            const isSelected = dateToRender && date.toDateString() === dateToRender.toDateString();
            const isToday = new Date().toDateString() === date.toDateString();
            const isMonday = date.getDay() === 1;
            const isBeforeMin = startOfDay(date) < minSelectableDate;
            const isDisabled = !isMonday || isBeforeMin;
            days.push(
                <button
                    key={i}
                    type="button"
                    className={`h-8 w-8 rounded-full text-xs font-normal transition flex items-center justify-center
                        ${isSelected ? "bg-[#0057B7] text-white shadow-md" : "text-slate-700 hover:bg-slate-100"}
                        ${isToday && !isSelected ? "text-[#0057B7] ring-1 ring-[#0057B7]" : ""}
                        ${isDisabled ? "opacity-30 cursor-not-allowed hover:bg-transparent" : ""}
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

    return (
        <div className="relative w-full" ref={dropdownRef}>
            <button
                type="button"
                className="flex w-full items-start justify-between gap-3"
                onClick={() => setIsOpen((prev) => !prev)}
            >
                <div className="min-w-0 flex flex-col">
                    <span className="truncate whitespace-nowrap text-start text-xs font-medium text-[#0F172A]">{label}</span>
                    <span className={`mt-1 truncate whitespace-nowrap text-start text-sm ${dateToRender ? "text-slate-600" : "text-gray-400"}`}>
                        {formatDate(dateToRender) || placeholder}
                    </span>
                </div>
                <span className="mt-2 inline-flex h-4 w-4 shrink-0 items-center justify-center text-slate-400">
                    <FontAwesomeIcon icon={faChevronDown} className={`transition ${isOpen ? "rotate-180" : ""}`} />
                </span>
            </button>

            {isOpen && (
                <div
                    className={`absolute z-40 mt-2 w-[min(320px,calc(100vw-2rem))] rounded-2xl border border-[#E6EEF7] bg-white p-4 shadow-[0_18px_40px_rgba(15,23,42,0.12)] sm:w-[340px] ${
                        dir === "rtl" ? "left-0 right-auto origin-top-right" : "right-0 left-auto origin-top-left"
                    }`}
                    dir={dir}
                >
                    <div className="mb-3 flex items-center justify-between">
                        <button type="button" onClick={prevMonth} className="p-1.5 hover:bg-slate-100 rounded-full text-slate-600 transition">
                            <FontAwesomeIcon icon={faChevronLeft} />
                        </button>
                        <h3 className="text-sm font-medium text-slate-900">{formatMonth(currentMonth)}</h3>
                        <button type="button" onClick={nextMonth} className="p-1.5 hover:bg-slate-100 rounded-full text-slate-600 transition">
                            <FontAwesomeIcon icon={faChevronRight} />
                        </button>
                    </div>

                    <div className="grid grid-cols-7 mb-2 text-center">
                        {getWeekdays().map((day) => (
                            <div key={day} className="text-[11px] font-medium text-slate-400 uppercase tracking-wider h-6 flex items-center justify-center">
                                {day}
                            </div>
                        ))}
                    </div>

                    <div className="grid grid-cols-7 gap-1 place-items-center">
                        {generateDays()}
                    </div>
                </div>
            )}
        </div>
    );
}
