"use client";

import { useState, useRef, useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronLeft, faChevronRight, faCalendarAlt } from "@fortawesome/free-solid-svg-icons";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const DAYS = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const MONTHS = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

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
    const day = base.getDay(); // 0 Sun ... 1 Mon
    const add = (8 - day) % 7; // next Monday (or today if Monday)
    return new Date(base.getTime() + add * DAY_MS);
}

function isSelectableDate(date, minDate) {
    if (!(date instanceof Date) || Number.isNaN(date.getTime())) {
        return false;
    }
    const normalized = startOfDay(date);
    return normalized.getDay() === 1 && normalized >= minDate;
}

export default function HeroDatePicker({ label, placeholder, selectedDate, onSelect }) {
    const { isArabic } = useCourseEnglishSettings();
    const [isOpen, setIsOpen] = useState(false);
    const [internalSelectedDate, setInternalSelectedDate] = useState(() => getDefaultMondayAfterTenDays());
    const [currentMonth, setCurrentMonth] = useState(() => getDefaultMondayAfterTenDays());

    const dropdownRef = useRef(null);
    const autoInitRef = useRef(false);

    const isControlled = typeof selectedDate !== 'undefined';
    const fallbackDate = getDefaultMondayAfterTenDays();
    const minSelectableDate = getMinSelectableDate();
    const controlledDate = isSelectableDate(selectedDate, minSelectableDate) ? selectedDate : null;
    const internalDate = isSelectableDate(internalSelectedDate, minSelectableDate) ? internalSelectedDate : fallbackDate;
    const dateToRender = isControlled ? (controlledDate || fallbackDate) : internalDate;

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

    const getDaysInMonth = (date) => {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    };

    const getFirstDayOfMonth = (date) => {
        return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
    };

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
        if (onSelect) {
            onSelect(date);
        }
    };

    const generateDays = () => {
        const daysInMonth = getDaysInMonth(currentMonth);
        const firstDay = getFirstDayOfMonth(currentMonth);
        const days = [];

        // Empty slots for previous month
        for (let i = 0; i < firstDay; i++) {
            days.push(<div key={`empty-${i}`} className="h-10 w-10"></div>);
        }

        // Days of current month
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
                    className={`h-10 w-10 rounded-full text-sm font-normal transition flex items-center justify-center
                    ${isSelected ? 'bg-[#0057B7] text-white shadow-md' : 'text-slate-700 hover:bg-slate-100'}
                    ${isToday && !isSelected ? 'text-[#0057B7] ring-1 ring-[#0057B7]' : ''}
                    ${isDisabled ? 'opacity-30 cursor-not-allowed hover:bg-transparent' : ''}
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
        return date.toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric' });
    };

    return (
        <div className="relative w-full" ref={dropdownRef}>
            {/* Trigger */}
            <div
                className={`cursor-pointer rounded-xl border ${isOpen ? 'border-blue-300 ring-2 ring-blue-100' : 'border-[#F6F8FA] shadow-sm'} bg-white px-8 py-4 sm:px-5 transition hover:border-blue-300`}
                onClick={() => setIsOpen(!isOpen)}
            >
                <div className="flex justify-between items-center">
                    <div className="min-w-0">
                        <p className="truncate whitespace-nowrap text-[13px] font-normal text-slate-700 sm:text-sm">{label}</p>
                        <div className="mt-1 flex items-center gap-2">
                            {!dateToRender && <FontAwesomeIcon icon={faCalendarAlt} className="h-4 w-4 text-slate-400" />}
                            <p className="truncate whitespace-nowrap text-[13px] text-slate-500 sm:text-sm">
                                {formatDate(dateToRender) || placeholder}
                            </p>
                        </div>
                    </div>
                    <FontAwesomeIcon icon={faChevronDown} className={`shrink-0 text-slate-400 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} />
                </div>
            </div>

            {/* Calendar Dropdown */}
            {isOpen && (
                <div
                    className={`absolute ${isArabic ? 'left-0 right-auto origin-top-right' : 'right-0 left-auto origin-top-left'} top-full z-[100] mt-2 w-[320px] max-w-[calc(100vw-2rem)] rounded-2xl bg-white p-5 shadow-2xl ring-1 ring-black/5 animate-in fade-in zoom-in-95 duration-200 sm:w-[340px]`}
                >

                    {/* Header */}
                    <div className="flex items-center justify-between mb-4">
                        <button type="button" onClick={prevMonth} className="p-2 hover:bg-slate-100 rounded-full text-slate-600 transition">
                            <FontAwesomeIcon icon={faChevronLeft} />
                        </button>
                        <h3 className="text-lg font-medium text-slate-900">
                            {MONTHS[currentMonth.getMonth()]} {currentMonth.getFullYear()}
                        </h3>
                        <button type="button" onClick={nextMonth} className="p-2 hover:bg-slate-100 rounded-full text-slate-600 transition">
                            <FontAwesomeIcon icon={faChevronRight} />
                        </button>
                    </div>

                    {/* Days Header */}
                    <div className="grid grid-cols-7 mb-2 ">
                        {DAYS.map(day => (
                            <div key={day} className="text-xs font-medium text-slate-400 uppercase tracking-wider h-8 flex items-center justify-center">
                                {day}
                            </div>
                        ))}
                    </div>

                    {/* Days Grid */}
                    <div className="grid grid-cols-7 gap-1 place-items-center">
                        {generateDays()}
                    </div>

                </div>
            )}

        </div>
    );
}
