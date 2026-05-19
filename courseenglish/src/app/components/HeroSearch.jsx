"use client";

import { useState, useRef, useEffect } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft, faSearch } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function HeroSearch({ placeholder = "Enter your preferred destination", subPlaceholder = "Enter country, city, or institute", onSelect }) {
    const [isOpen, setIsOpen] = useState(false);
    const [searchTerm, setSearchTerm] = useState("");
    const dropdownRef = useRef(null);
    const { data } = useApi("/courseenglish/utilities");
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const institutes = data?.schools ?? [];
    const countries = data?.countries ?? [];
    const cities = data?.cities ?? [];

    // Close dropdown on outside click
    useEffect(() => {
        function handleClickOutside(event) {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    // Filter logic (simple substring match)
    const filterItems = (items) => {
        if (!searchTerm) return items;
        const lowerTerm = searchTerm.toLowerCase();
        return items.filter(item =>
            item.name?.toLowerCase().includes(lowerTerm) ||
            (item.ar_name && item.ar_name.toLowerCase().includes(lowerTerm))
        );
    };

    const filteredInstitutes = filterItems(institutes);
    const filteredCountries = filterItems(countries);
    const filteredCities = filterItems(cities);
    const [showAllInstitutes, setShowAllInstitutes] = useState(false);

    const handleSelect = (item, type) => {
        const value = isArabic ? item.ar_name || item.name : item.name || item.ar_name;
        setSearchTerm(value || "");
        setIsOpen(false);
        if (onSelect) {
            // Need to pass type info because item doesn't have it explicitly effectively
            // But we know the source array.
            // Let's rely on caller to know or we inject type?
            // Existing logic in LanguageInstitutesHero expects { type, slug, name }.
            // Here 'item' is the object from API.
            // 'type' argument added to handleSelect.
            onSelect({ type, slug: item.slug, name: value, ...item });
        }
    };

    const t = (en, ar) => (isArabic ? ar : en);

    return (
        <div className="relative w-full" ref={dropdownRef}>
            {/* Input Field */}
            <div
                className="cursor-text rounded-xl border border-[#F6F8FA] bg-white px-8 py-4 shadow-sm transition hover:border-blue-300 focus-within:ring-2 focus-within:ring-blue-100"
                onClick={() => setIsOpen(true)}
            >
                <div className="flex items-center gap-2.5">

                    <div className="flex-1">
                        <input
                            type="text"
                            className="w-full border-none p-0 text-lg font-medium text-slate-800 focus:ring-0 placeholder:text-slate-400 outline-none"
                            placeholder={placeholder}
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            onFocus={() => setIsOpen(true)}
                        />
                        <p className="mt-0.5 text-sm text-slate-500">{subPlaceholder}</p>
                    </div>
                </div>
            </div>

            {/* Dropdown Menu */}
            {isOpen && (
                <div className="absolute left-0 top-full z-[100] mt-2 w-full origin-top-left overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 animate-in fade-in zoom-in-95 duration-200">
                    <div className="max-h-[60vh] overflow-y-auto p-6 scrollbar-thin scrollbar-thumb-gray-200">

                        {/* 1. Popular Institutes */}
                        {filteredInstitutes.length > 0 && (
                            <section className="mb-8">
                                <div className="mb-4 flex items-center justify-between">
                                    <h3 className="text-lg font-medium text-slate-900">{t("Popular Institutes", "أشهر المعاهد")}</h3>
                                    <Link href="/language-institutes" className="text-sm font-medium text-[#0057B7] hover:underline flex items-center gap-1">
                                        {t("View All", "عرض الجميع")} <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                    </Link>
                                </div>
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    {(showAllInstitutes ? filteredInstitutes : filteredInstitutes.slice(0, 6)).map((institute) => (
                                        <button
                                            key={institute.name}
                                            type="button"
                                            onClick={() => handleSelect(institute, 'school')}
                                            className="flex flex-col items-center justify-center rounded-xl border border-gray-100 p-4 hover:border-blue-200 hover:bg-blue-50 transition text-center group"
                                        >
                                            <div className="relative h-10 w-full mb-3 flex items-center justify-center opacity-90 group-hover:opacity-100">
                                                {institute.logo ? (
                                                    <img
                                                        src={institute.logo}
                                                        alt={institute.name}
                                                        className="h-10 w-full object-contain"
                                                        loading="lazy"
                                                    />
                                                ) : (
                                                    <span className="text-xs font-medium text-slate-500 group-hover:text-[#0057B7]">
                                                        {isArabic ? institute.ar_name || institute.name : institute.name || institute.ar_name}
                                                    </span>
                                                )}
                                            </div>
                                            <span className="text-sm font-medium text-slate-700 group-hover:text-[#0057B7] line-clamp-2">
                                                {isArabic ? institute.ar_name || institute.name : institute.name || institute.ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    {filteredInstitutes.length > 6 && (
                                        <button
                                            type="button"
                                            onClick={() => setShowAllInstitutes((s) => !s)}
                                            className="flex flex-col items-center justify-center rounded-xl bg-slate-50 p-4 hover:bg-slate-100 transition text-center"
                                        >
                                            <span className="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                                <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                            </span>
                                            <span className="text-sm font-medium text-slate-700">
                                                {showAllInstitutes ? t("Show less", "عرض أقل") : t("Other Institutes", "معاهد أخرى")}
                                            </span>
                                        </button>
                                    )}
                                </div>
                                <div className="mt-3">
                                    <Link
                                        href="/language-institutes"
                                        className="inline-flex items-center gap-2 text-sm font-medium text-[#0057B7] hover:underline"
                                    >
                                        {t("View All institutes", "عرض كل المعاهد")}
                                        <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                    </Link>
                                </div>
                            </section>
                        )}

                        {/* 2. Popular Countries */}
                        {filteredCountries.length > 0 && (
                            <section className="mb-8">
                                <h3 className="mb-4 text-lg font-medium text-slate-900">{t("Popular Countries", "أشهر الدول")}</h3>
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    {filteredCountries.slice(0, 4).map((country) => (
                                        <button
                                            key={country.name}
                                            className="flex flex-col items-center justify-center rounded-xl border border-gray-100 p-4 hover:border-blue-200 hover:bg-blue-50 transition text-center"
                                            onClick={() => {
                                                handleSelect(country, 'country');
                                            }}
                                        >
                                            {country.flag ? (
                                                <img
                                                    src={country.flag}
                                                    alt={country.name}
                                                    className="mb-2 h-8 w-8"
                                                    loading="lazy"
                                                />
                                            ) : (
                                                <span className="text-3xl mb-2">🌍</span>
                                            )}
                                            <span className="text-sm font-medium text-slate-700">
                                                {isArabic ? country.ar_name || country.name : country.name || country.ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    <button className="flex flex-col items-center justify-center rounded-xl bg-slate-50 p-4 hover:bg-slate-100 transition text-center">
                                        <span className="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                            <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                        </span>
                                        <span className="text-sm font-medium text-slate-700">{t("Other Countries", "دول أخرى")}</span>
                                    </button>
                                </div>
                            </section>
                        )}

                        {/* 3. Popular Cities */}
                        {filteredCities.length > 0 && (
                            <section>
                                <h3 className="mb-4 text-lg font-medium text-slate-900">{t("Popular Cities", "أشهر المدن")}</h3>
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    {filteredCities.slice(0, 4).map((city) => (
                                        <button
                                            key={`${city.name}-${city.country_name || ""}`}
                                            className="flex flex-col items-center justify-center rounded-xl border border-gray-100 p-4 hover:border-blue-200 hover:bg-blue-50 transition text-center"
                                            onClick={() => {
                                                handleSelect(city, 'city');
                                            }}
                                        >
                                            <span className="text-sm font-medium text-slate-800">
                                                {isArabic ? city.ar_name || city.name : city.name || city.ar_name}
                                            </span>
                                            <span className="text-xs text-slate-500">
                                                {isArabic ? city.country_ar_name || city.country_name : city.country_name || city.country_ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    <button className="flex flex-col items-center justify-center rounded-xl bg-slate-50 p-4 hover:bg-slate-100 transition text-center">
                                        <span className="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                            <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                        </span>
                                        <span className="text-sm font-medium text-slate-700">{t("Other Cities", "مدن أخرى")}</span>
                                    </button>
                                </div>
                            </section>
                        )}

                        {filteredInstitutes.length === 0 && filteredCountries.length === 0 && filteredCities.length === 0 && (
                            <div className="text-center py-8 text-slate-500">
                                No results found for &quot;{searchTerm}&quot;
                            </div>
                        )}

                    </div>
                </div>
            )}
        </div>
    );
}
