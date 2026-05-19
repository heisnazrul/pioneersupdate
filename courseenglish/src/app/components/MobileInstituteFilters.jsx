"use client";

import { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import DestinationDropdown from "@/app/components/DestinationDropdown";
import FilterDropdown from "@/app/components/FilterDropdown";
import FilterDatePicker from "@/app/components/FilterDatePicker";
import { useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function MobileInstituteFilters({ totalCount, tags = [], onSortChange }) {
    const [isSearchOpen, setIsSearchOpen] = useState(false);
    const [isSortOpen, setIsSortOpen] = useState(false);
    const [selectedTag, setSelectedTag] = useState(null);
    const [priceDirection, setPriceDirection] = useState(null);

    const { data } = useApi("/courseenglish/utilities");
    const { isArabic } = useCourseEnglishSettings();

    const courseTypes = (data?.language_course_types ?? []).map((t) =>
        isArabic ? t.ar_name || t.name : t.name || t.ar_name
    );

    const handleTagSelect = (tagName) => {
        const next = selectedTag === tagName ? null : tagName;
        setSelectedTag(next);
        onSortChange?.({ tag: next, priceDirection });
    };

    const handlePriceSelect = (dir) => {
        const next = priceDirection === dir ? null : dir;
        setPriceDirection(next);
        onSortChange?.({ tag: selectedTag, priceDirection: next });
    };

    return (
        <div className="md:hidden mb-6">
            {/* Search bar */}
            <button
                type="button"
                className="flex w-full items-center gap-3 rounded-2xl border border-[#E1E8F0] bg-white px-4 py-3 text-start shadow-sm"
                onClick={() => setIsSearchOpen(true)}
            >
                <FontAwesomeIcon icon={faSearch} className="h-4 w-4 text-slate-400" />
                <span className="text-sm text-slate-500">
                    {isArabic ? "أدخل وجهتك المفضلة" : "Enter your preferred destination"}
                </span>
                <span className="ms-auto text-slate-400">→</span>
            </button>

            {/* Sort + count row */}
            <div className="mt-4 flex items-center justify-between pb-4">
                <button
                    type="button"
                    className="flex items-center gap-2 text-sm font-normal text-slate-700"
                    onClick={() => setIsSortOpen(true)}
                >
                    <span className="text-slate-400">⌄</span>
                    {isArabic ? "ترتيب حسب" : "Sort by"}
                </button>
                <div className="text-sm font-normal text-slate-800">
                    {totalCount} {isArabic ? "دورة" : "courses"}
                </div>
            </div>

            {/* Search sheet */}
            {isSearchOpen && (
                <div className="fixed inset-0 z-[100]">
                    <div
                        className="absolute inset-0 bg-black/30"
                        onClick={() => setIsSearchOpen(false)}
                    />
                    <div className="absolute inset-x-4 top-6 rounded-3xl bg-white p-4 shadow-2xl">
                        <div className="flex justify-start">
                            <button
                                type="button"
                                className="text-2xl text-slate-700"
                                onClick={() => setIsSearchOpen(false)}
                            >
                                ×
                            </button>
                        </div>

                        <div className="mt-4 space-y-4">
                            <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3">
                                <DestinationDropdown
                                    label={isArabic ? "الوجهة" : "Destination"}
                                    placeholder={isArabic ? "أدخل وجهتك المفضلة" : "Enter your preferred destination"}
                                    mobile
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3">
                                    <FilterDropdown
                                        label={isArabic ? "الأسابيع" : "Weeks"}
                                        placeholder={isArabic ? "اختر الأسابيع" : "Select weeks"}
                                        options={Array.from({ length: 52 }, (_, i) =>
                                            isArabic ? `${i + 1} أسبوع` : `${i + 1} Week${i === 0 ? "" : "s"}`
                                        )}
                                        scroll
                                    />
                                </div>
                                <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3">
                                    <FilterDatePicker
                                        label={isArabic ? "تاريخ البدء" : "Start date"}
                                        placeholder={isArabic ? "اختر التاريخ" : "Select start date"}
                                    />
                                </div>
                            </div>

                            <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3">
                                <FilterDropdown
                                    label={isArabic ? "نوع الدورة" : "Course type"}
                                    placeholder={isArabic ? "اختر نوع الدورة" : "Select course type"}
                                    options={courseTypes.length ? courseTypes : [
                                        "General English",
                                        "Intensive English",
                                        "Semi-Intensive",
                                    ]}
                                />
                            </div>
                        </div>

                        <button
                            type="button"
                            className="mt-6 w-full rounded-2xl bg-[#0057B7] py-3 text-base font-normal text-white"
                        >
                            {isArabic ? "بحث" : "Search"}
                        </button>
                    </div>
                </div>
            )}

            {/* Sort bottom sheet */}
            {isSortOpen && (
                <div className="fixed inset-0 z-[100]">
                    <div
                        className="absolute inset-0 bg-black/30"
                        onClick={() => setIsSortOpen(false)}
                    />
                    <div className="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-6 shadow-2xl">
                        <div className="mx-auto mb-4 h-1.5 w-14 rounded-full bg-slate-300" />
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-lg font-medium text-slate-900">
                                    {isArabic ? "ترتيب حسب" : "Sort by"}
                                </h3>
                                <p className="text-sm text-slate-500">
                                    {isArabic ? "اختر طريقة الترتيب" : "Choose sorting method"}
                                </p>
                            </div>
                            <button
                                type="button"
                                className="text-2xl text-slate-700"
                                onClick={() => setIsSortOpen(false)}
                            >
                                ×
                            </button>
                        </div>

                        {/* Tag filters */}
                        {tags.length > 0 && (
                            <div className="mt-4">
                                <p className="mb-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                    {isArabic ? "تصفية حسب الوسم" : "Filter by Tag"}
                                </p>
                                <div className="space-y-2">
                                    {tags.map((tag) => (
                                        <label key={tag.id || tag.name} className="flex items-center gap-3 text-base text-slate-700">
                                            <input
                                                type="checkbox"
                                                checked={selectedTag === tag.name}
                                                onChange={() => handleTagSelect(tag.name)}
                                                className="h-5 w-5 rounded border-gray-300 text-[#0057B7] accent-[#0057B7]"
                                            />
                                            <span>{isArabic ? (tag.ar_name || tag.name) : tag.name}</span>
                                        </label>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Price sort */}
                        <div className="mt-4 border-t border-gray-100 pt-4">
                            <p className="mb-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                                {isArabic ? "ترتيب حسب السعر" : "Sort by Price"}
                            </p>
                            <div className="space-y-2">
                                <label className="flex items-center gap-3 text-base text-slate-700">
                                    <input
                                        type="radio"
                                        name="mobilePriceSort"
                                        checked={priceDirection === "asc"}
                                        onChange={() => handlePriceSelect("asc")}
                                    />
                                    <span>{isArabic ? "السعر: من الأقل إلى الأعلى" : "Price: Low to High"}</span>
                                </label>
                                <label className="flex items-center gap-3 text-base text-slate-700">
                                    <input
                                        type="radio"
                                        name="mobilePriceSort"
                                        checked={priceDirection === "desc"}
                                        onChange={() => handlePriceSelect("desc")}
                                    />
                                    <span>{isArabic ? "السعر: من الأعلى إلى الأقل" : "Price: High to Low"}</span>
                                </label>
                            </div>
                        </div>

                        <button
                            type="button"
                            className="mt-6 w-full rounded-2xl bg-[#0057B7] py-3 text-base font-normal text-white"
                            onClick={() => setIsSortOpen(false)}
                        >
                            {isArabic ? "تطبيق" : "Apply"}
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
