"use client";

import { useEffect, useRef, useState } from "react";

export default function HeroDropdown({ label, placeholder, options = [], scroll = false, onSelect, selectedValue, variant = "default" }) {
    const [internalValue, setInternalValue] = useState("");
    const [isOpen, setIsOpen] = useState(false);
    const detailsRef = useRef(null);

    const isControlled = selectedValue !== undefined;

    // Determine current display value
    const currentVal = isControlled ? selectedValue : internalValue;

    // Find selected option object/string to get the label
    const getOptionLabel = (val) => {
        if (!val) return "";
        const found = options.find(opt => (typeof opt === "object" ? opt.value : opt) === val);
        if (found) return typeof found === "object" ? found.label : found;
        return val; // Fallback if not found but value exists (mostly for strings)
    };

    const displayLabel = getOptionLabel(currentVal);

    useEffect(() => {
        const handleClick = (event) => {
            const el = detailsRef.current;
            if (!el) return;
            if (!el.contains(event.target)) {
                if (el.open) {
                    el.open = false;
                    setIsOpen(false);
                }
            }
        };

        document.addEventListener("click", handleClick);
        return () => document.removeEventListener("click", handleClick);
    }, []);

    const handleSelect = (option) => {
        const val = typeof option === "object" ? option.value : option;

        if (onSelect) {
            onSelect(option);
        }

        if (!isControlled) {
            setInternalValue(val);
        }

        if (detailsRef.current) {
            detailsRef.current.open = false;
        }
        setIsOpen(false);
    };

    return (
        <details
            ref={detailsRef}
            className="relative"
            onToggle={(e) => setIsOpen(e.currentTarget.open)}
        >
            <summary
                className={variant === "borderless" ? "flex w-full cursor-pointer items-center justify-between gap-3 px-4 py-2 bg-transparent transition list-none [&::-webkit-details-marker]:hidden" : `flex w-full cursor-pointer items-center justify-between gap-3 rounded-xl border bg-white px-8 py-3 2xl:py-4 sm:px-5 transition list-none [&::-webkit-details-marker]:hidden ${isOpen ? "border-blue-300 ring-2 ring-blue-100" : "border-[#F6F8FA] shadow-sm"}`}
                style={{ listStyle: "none" }}
            >
                <div className="min-w-0 flex flex-col text-start">
                    <span className="truncate whitespace-nowrap text-[13px] font-normal text-black sm:text-sm">{label}</span>
                    <span className={`mt-1 truncate whitespace-nowrap text-[13px] sm:text-sm ${displayLabel ? "text-slate-700" : "text-slate-400"}`}>
                        {displayLabel || placeholder}
                    </span>
                </div>
            </summary>
            <div
                className={`absolute left-0 z-30 mt-2 w-full rounded-2xl border border-[#E6EEF7] bg-white p-2 shadow-[0_10px_30px_rgba(15,23,42,0.08)] ${scroll ? "max-h-56 overflow-y-auto" : ""
                    }`}
            >
                {options.map((option, idx) => {
                    const optLabel = typeof option === "object" ? option.label : option;
                    const optValue = typeof option === "object" ? option.value : option;
                    const isSelected = currentVal === optValue;

                    return (
                        <button
                            key={optValue || idx}
                            type="button"
                            onClick={() => handleSelect(option)}
                            className={`w-full rounded-xl px-3 py-2 text-sm text-start ${isSelected
                                ? "bg-[#F3F6FA] font-normal text-slate-800"
                                : "font-normal text-slate-500 hover:text-slate-700"
                                }`}
                        >
                            {optLabel}
                        </button>
                    );
                })}
            </div>
        </details>
    );
}
