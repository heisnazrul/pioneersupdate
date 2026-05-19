"use client";

import { useEffect, useRef, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft, faSearch } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function DestinationDropdown({
    label = "Destination",
    placeholder = "Country, city, or institute",
    mobile = false,
    value,
    onSelect,
}) {
    const [isOpen, setIsOpen] = useState(false);
    const [searchTerm, setSearchTerm] = useState("");
    const [showAllInstitutes, setShowAllInstitutes] = useState(false);
    const dropdownRef = useRef(null);
    const { data } = useApi("/courseenglish/utilities");
    const { language } = useCourseEnglishSettings();
    const isArabic = language === "ar";
    const institutes = data?.schools ?? [];
    const countries = data?.countries ?? [];
    const cities = data?.cities ?? [];
    const selected = value || "";

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const t = (en, ar) => (isArabic ? ar : en);

    const filterItems = (items) => {
        if (!searchTerm) return items;
        const lowerTerm = searchTerm.toLowerCase();
        return items.filter((item) =>
            item.name?.toLowerCase().includes(lowerTerm) ||
            (item.ar_name && item.ar_name.toLowerCase().includes(lowerTerm))
        );
    };

    const filteredInstitutes = filterItems(institutes);
    const filteredCountries = filterItems(countries);
    const filteredCities = filterItems(cities);

    const handleSelect = (item, type) => {
        const selectedName = isArabic ? item.ar_name || item.name : item.name || item.ar_name;
        setSearchTerm(selectedName || "");
        setIsOpen(false);
        if (onSelect) {
            onSelect({ type, slug: item.slug, name: selectedName, ...item });
        }
    };

    const noResults =
        filteredInstitutes.length === 0 &&
        filteredCountries.length === 0 &&
        filteredCities.length === 0;

    return (
        <div className="relative w-full" ref={dropdownRef}>
            <div className="flex w-full flex-col">
                <span className="text-xs font-medium text-[#0F172A] text-start">{label}</span>
                <div className="mt-1 flex items-center gap-2">
                    <FontAwesomeIcon icon={faSearch} className="h-4 w-4 text-slate-400" />
                    <input
                        type="text"
                        className="w-full bg-transparent border-none p-0 text-sm text-slate-700 placeholder:text-gray-400 outline-none"
                        placeholder={placeholder}
                        value={isOpen ? searchTerm : (selected || searchTerm)}
                        onFocus={() => {
                            setSearchTerm(selected || searchTerm);
                            setIsOpen(true);
                        }}
                        onChange={(e) => {
                            setSearchTerm(e.target.value);
                            setIsOpen(true);
                        }}
                    />
                </div>
            </div>

            {isOpen && (
                <div
                    className={`absolute start-0 z-40 mt-4 rounded-3xl border border-[#E6EEF7] bg-white p-4 shadow-[0_18px_40px_rgba(15,23,42,0.12)] ${mobile ? "w-full max-w-full" : "w-[720px] max-w-[calc(100vw-2rem)]"
                        }`}
                >
                    <div className="max-h-[60vh] overflow-y-auto pr-1">
                        {/* Popular Institutes */}
                        {filteredInstitutes.length > 0 && (
                            <div className="mb-6">
                                <div className="mb-3 flex items-center justify-between">
                                    <h4 className="text-sm font-medium text-slate-800">{t("Popular institutes", "أشهر المعاهد")}</h4>
                                    <Link href="/language-institutes" className="text-xs font-medium text-[#0057B7] hover:underline flex items-center gap-1">
                                        {t("View All", "عرض الجميع")} <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                    </Link>
                                </div>
                                <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                    {(showAllInstitutes ? filteredInstitutes : filteredInstitutes.slice(0, 7)).map((inst) => (
                                        <button
                                            key={`${inst.slug || inst.name}`}
                                            type="button"
                                            onClick={() => handleSelect(inst, "school")}
                                            className="flex flex-col items-center justify-center rounded-2xl border border-[#E6EEF7] bg-white px-3 py-4 text-xs font-normal text-slate-700 hover:bg-[#F3F6FA]"
                                        >
                                            <div className="relative mb-2 h-6 w-20">
                                                {inst.logo ? (
                                                    <img src={inst.logo} alt={inst.name} className="h-6 w-20 object-contain" loading="lazy" />
                                                ) : (
                                                    <span className="text-[10px] text-slate-400">
                                                        {isArabic ? inst.ar_name || inst.name : inst.name || inst.ar_name}
                                                    </span>
                                                )}
                                            </div>
                                            <span className="line-clamp-2">
                                                {isArabic ? inst.ar_name || inst.name : inst.name || inst.ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    {filteredInstitutes.length > 7 && (
                                        <button
                                            type="button"
                                            onClick={() => setShowAllInstitutes((s) => !s)}
                                            className="flex flex-col items-center justify-center rounded-2xl bg-[#EEF5FB] px-3 py-4 text-xs font-normal text-slate-700"
                                        >
                                            <span>{showAllInstitutes ? t("Show less", "عرض أقل") : t("Other institutes", "معاهد أخرى")}</span>
                                            <span className="mt-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                                <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
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
                            </div>
                        )}

                        {/* Popular Countries */}
                        {filteredCountries.length > 0 && (
                            <div className="mb-6">
                                <h4 className="mb-3 text-sm font-medium text-slate-800">{t("Popular countries", "أشهر الدول")}</h4>
                                <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                    {filteredCountries.slice(0, 7).map((country) => (
                                        <button
                                            key={`${country.slug || country.name}`}
                                            type="button"
                                            onClick={() => handleSelect(country, "country")}
                                            className="flex flex-col items-center justify-center rounded-2xl border border-[#E6EEF7] bg-white px-3 py-4 text-xs font-normal text-slate-700 hover:bg-[#F3F6FA]"
                                        >
                                            {country.flag ? (
                                                <img src={country.flag} alt={country.name} className="h-6 w-6" loading="lazy" />
                                            ) : (
                                                <span className="text-2xl">🌍</span>
                                            )}
                                            <span className="mt-2">
                                                {isArabic ? country.ar_name || country.name : country.name || country.ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    <button
                                        type="button"
                                        className="flex flex-col items-center justify-center rounded-2xl bg-[#EEF5FB] px-3 py-4 text-xs font-normal text-slate-700"
                                    >
                                        <span>{t("Other countries", "دول أخرى")}</span>
                                        <span className="mt-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                            <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        )}

                        {/* Popular Cities */}
                        {filteredCities.length > 0 && (
                            <div>
                                <h4 className="mb-3 text-sm font-medium text-slate-800">{t("Popular cities", "أشهر المدن")}</h4>
                                <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                    {filteredCities.slice(0, 7).map((city) => (
                                        <button
                                            key={`${city.slug || city.name}-${city.country_name || ""}`}
                                            type="button"
                                            onClick={() => handleSelect(city, "city")}
                                            className="flex flex-col items-center justify-center rounded-2xl border border-[#E6EEF7] bg-white px-3 py-4 text-xs font-normal text-slate-700 hover:bg-[#F3F6FA]"
                                        >
                                            <span>{isArabic ? city.ar_name || city.name : city.name || city.ar_name}</span>
                                            <span className="mt-1 text-[11px] text-slate-400">
                                                {isArabic ? city.country_ar_name || city.country_name : city.country_name || city.country_ar_name}
                                            </span>
                                        </button>
                                    ))}
                                    <button
                                        type="button"
                                        className="flex flex-col items-center justify-center rounded-2xl bg-[#EEF5FB] px-3 py-4 text-xs font-normal text-slate-700"
                                    >
                                        <span>{t("Other cities", "مدن أخرى")}</span>
                                        <span className="mt-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#0057B7] text-white">
                                            <FontAwesomeIcon icon={faArrowLeft} className={`${isArabic ? "" : "rotate-180"}`} />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        )}

                        {noResults && (
                            <div className="py-6 text-center text-sm text-slate-500">
                                {isArabic ? "لا توجد نتائج مطابقة" : `No results found for "${searchTerm}"`}
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
