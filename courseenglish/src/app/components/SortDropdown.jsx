"use client";

import { useEffect, useRef, useState } from "react";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function SortDropdown({ tags = [], onSortChange }) {
    const detailsRef = useRef(null);
    const [selectedTag, setSelectedTag] = useState(null);
    const [priceDirection, setPriceDirection] = useState(null); // "asc" | "desc" | null
    const { isArabic } = useCourseEnglishSettings();

    useEffect(() => {
        const handleClick = (event) => {
            const el = detailsRef.current;
            if (!el) return;
            if (!el.contains(event.target)) {
                el.open = false;
            }
        };

        document.addEventListener("click", handleClick);
        return () => document.removeEventListener("click", handleClick);
    }, []);

    const emitChange = (tag, price) => {
        onSortChange?.({ tag, priceDirection: price });
    };

    const handleTagClick = (tagName) => {
        const next = selectedTag === tagName ? null : tagName;
        setSelectedTag(next);
        emitChange(next, priceDirection);
    };

    const handlePriceClick = (direction) => {
        const next = priceDirection === direction ? null : direction;
        setPriceDirection(next);
        emitChange(selectedTag, next);
    };

    // Build summary label
    const parts = [];
    if (selectedTag) {
        const t = tags.find((t) => t.name === selectedTag);
        parts.push(isArabic ? (t?.ar_name || selectedTag) : selectedTag);
    }
    if (priceDirection === "asc") parts.push(isArabic ? "السعر ↑" : "Price ↑");
    if (priceDirection === "desc") parts.push(isArabic ? "السعر ↓" : "Price ↓");
    const defaultLabel = isArabic ? "الأكثر شعبية" : "Most Popular";
    const summaryLabel = parts.length ? parts.join(" + ") : defaultLabel;

    return (
        <details ref={detailsRef} className="relative z-30 w-[320px]">
            <summary
                className="flex w-full items-center gap-2 rounded-xl border border-[#DCE6F1] bg-white px-6 py-3 text-base shadow-sm cursor-pointer select-none [&::-webkit-details-marker]:hidden"
                style={{ listStyle: "none" }}
            >
                <span className="inline-flex h-5 w-5 items-center justify-center text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-5 w-5">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 4v14m0 0l-3-3m3 3l3-3M16 20V6m0 0l-3 3m3-3l3 3" />
                    </svg>
                </span>
                <div className="flex-1 min-w-0">
                    <span className="text-sm font-normal text-slate-500">{isArabic ? "ترتيب حسب: " : "Sort by: "}</span>
                    <span className="text-sm font-normal text-slate-800 truncate">{summaryLabel}</span>
                </div>
                <span className="inline-flex h-4 w-4 shrink-0 items-center justify-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2.2} stroke="currentColor" className="h-4 w-4">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </summary>

            <div className="absolute start-0 z-40 mt-3 w-full rounded-xl border border-[#E6EEF7] bg-white p-3 shadow-[0_10px_30px_rgba(15,23,42,0.08)]">

                {/* Tag Filters */}
                {tags.length > 0 && (
                    <div className="mb-2">
                        <p className="mb-1 px-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                            {isArabic ? "تصفية حسب الوسم" : "Filter by Tag"}
                        </p>
                        {tags.map((tag) => (
                            <button
                                key={tag.id || tag.name}
                                className={`w-full rounded-lg px-4 py-2.5 text-start text-sm font-normal transition ${selectedTag === tag.name
                                        ? "bg-[#0057B7] text-white"
                                        : "text-slate-600 hover:bg-[#F3F6FA] hover:text-slate-800"
                                    }`}
                                onClick={() => handleTagClick(tag.name)}
                            >
                                {isArabic ? (tag.ar_name || tag.name) : tag.name}
                            </button>
                        ))}
                    </div>
                )}

                {/* Price Sort */}
                <div className="border-t border-gray-100 pt-2">
                    <p className="mb-1 px-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                        {isArabic ? "ترتيب حسب السعر" : "Sort by Price"}
                    </p>
                    <button
                        className={`w-full rounded-lg px-4 py-2.5 text-start text-sm font-normal transition ${priceDirection === "asc"
                                ? "bg-[#0057B7] text-white"
                                : "text-slate-600 hover:bg-[#F3F6FA] hover:text-slate-800"
                            }`}
                        onClick={() => handlePriceClick("asc")}
                    >
                        {isArabic ? "السعر: من الأقل إلى الأعلى" : "Price: Low to High"}
                    </button>
                    <button
                        className={`w-full rounded-lg px-4 py-2.5 text-start text-sm font-normal transition ${priceDirection === "desc"
                                ? "bg-[#0057B7] text-white"
                                : "text-slate-600 hover:bg-[#F3F6FA] hover:text-slate-800"
                            }`}
                        onClick={() => handlePriceClick("desc")}
                    >
                        {isArabic ? "السعر: من الأعلى إلى الأقل" : "Price: High to Low"}
                    </button>
                </div>

                {/* Clear All */}
                {(selectedTag || priceDirection) && (
                    <button
                        className="mt-2 w-full rounded-lg border border-dashed border-slate-300 px-4 py-2 text-center text-xs font-normal text-slate-500 hover:bg-slate-50"
                        onClick={() => {
                            setSelectedTag(null);
                            setPriceDirection(null);
                            emitChange(null, null);
                        }}
                    >
                        {isArabic ? "مسح الكل" : "Clear All Filters"}
                    </button>
                )}
            </div>
        </details>
    );
}
