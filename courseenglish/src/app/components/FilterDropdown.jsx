"use client";

import { useEffect, useRef, useState } from "react";

export default function FilterDropdown({ label, placeholder, options = [], scroll = false, value, onSelect }) {
    const [isOpen, setIsOpen] = useState(false);
    const [selected, setSelected] = useState("");
    const dropdownRef = useRef(null);
    const isControlled = value !== undefined;

    const getOptionLabel = (val) => {
        if (val === null || val === undefined || val === "") return "";
        const found = options.find((opt) => (typeof opt === "object" ? opt.value : opt) === val);
        if (found) return typeof found === "object" ? found.label : found;
        return typeof val === "object" ? val.label || "" : val;
    };

    const displayValue = isControlled ? getOptionLabel(value) : selected;

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const handleSelect = (option) => {
        if (!isControlled) {
            setSelected(typeof option === "object" ? option.label : option);
        }
        setIsOpen(false);
        if (onSelect) {
            onSelect(option); // Pass full option object or value
        }
    };

    return (
        <div className="relative w-full" ref={dropdownRef}>
            <button
                type="button"
                className="flex w-full items-start justify-between gap-3 "
                onClick={() => setIsOpen((prev) => !prev)}
            >
                <div className="min-w-0 flex flex-col">
                    <span className="truncate whitespace-nowrap text-start text-xs font-medium text-[#0F172A]">{label}</span>
                    <span className={`mt-1 truncate whitespace-nowrap text-start text-sm ${displayValue ? "text-slate-600" : "text-gray-400"}`}>
                        {displayValue || placeholder}
                    </span>
                </div>
                <span className="mt-2 inline-flex h-4 w-4 shrink-0 items-center justify-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </button>

            {isOpen && (
                <div
                    className={`absolute left-0 z-40 mt-2 w-full max-w-full rounded-2xl border border-[#E6EEF7] bg-white py-2 shadow-[0_10px_30px_rgba(15,23,42,0.12)] ${scroll ? "max-h-56 overflow-y-auto" : ""
                        }`}
                >
                    {options.map((option, idx) => (
                        <button
                            key={typeof option === "object" ? option?.value ?? idx : option}
                            type="button"
                            onClick={() => handleSelect(option)}
                            className="w-full px-4 py-2 text-start text-sm font-normal text-slate-500 hover:text-slate-700"
                        >
                            {typeof option === "object" ? option.label : option}
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}
