/* eslint-disable @next/next/no-img-element */
"use client";

import { useState, useRef, useEffect, useCallback } from "react";
import { createPortal } from "react-dom";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import { getImageUrl } from "@/lib/api";
import { getCountryFlagUrl, getLocalCountryFlagPath } from "@/lib/country-flags";
import { useHeroSearchData } from "@/lib/hero-search-data";
import { useLocale } from "@/components/providers/locale-provider";

const MAX_VISIBLE_INSTITUTES = 7;
const INSTITUTE_ROW_SIZE = 4;

function buildInstituteGridItems(institutes) {
  const visible = institutes.slice(0, MAX_VISIBLE_INSTITUTES);
  const items = [];

  if (visible.length <= INSTITUTE_ROW_SIZE - 1) {
    visible.forEach((institute) => items.push({ type: "institute", data: institute }));
    items.push({ type: "other-institutes" });
    return items;
  }

  visible.slice(0, INSTITUTE_ROW_SIZE).forEach((institute) => items.push({ type: "institute", data: institute }));
  items.push({ type: "other-institutes" });
  visible.slice(INSTITUTE_ROW_SIZE, MAX_VISIBLE_INSTITUTES).forEach((institute) =>
    items.push({ type: "institute", data: institute })
  );

  return items;
}

function HeroSearchOtherCard({ href, label, isArabic, onClose }) {
  return (
    <Link
      href={href}
      onClick={onClose}
      className="flex min-h-[108px] flex-col items-center justify-center rounded-xl bg-[#F3F6FA] p-4 text-center transition hover:bg-slate-100"
    >
      <span className="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#0057B7] text-white">
        <FontAwesomeIcon icon={faArrowLeft} className={isArabic ? "" : "rotate-180"} />
      </span>
      <span className="text-sm font-medium text-slate-700">{label}</span>
    </Link>
  );
}

function HeroSearchDropdownPanel({
  isArabic,
  t,
  filteredInstitutes,
  filteredCountries,
  filteredCities,
  handleSelect,
  loading,
  searchTerm,
  onClose,
}) {
  const instituteGridItems = buildInstituteGridItems(filteredInstitutes);

  return (
    <div className="max-h-[60vh] overflow-y-auto p-6 scrollbar-thin scrollbar-thumb-gray-200">
      {filteredInstitutes.length > 0 && (
        <section className="mb-8">
          <h3 className="mb-4 text-lg font-medium text-slate-900">{t("Popular Institutes", "أشهر المعاهد")}</h3>
          <div className="grid grid-cols-2 gap-3 sm:grid-cols-4">
            {instituteGridItems.map((item) =>
              item.type === "other-institutes" ? (
                <HeroSearchOtherCard
                  key="other-institutes"
                  href="/language-institutes"
                  label={t("Other Institutes", "معاهد أخرى")}
                  isArabic={isArabic}
                  onClose={onClose}
                />
              ) : (
                <button
                  key={item.data.id ?? item.data.slug ?? item.data.name}
                  type="button"
                  onClick={() => handleSelect(item.data, "school")}
                  className="group flex min-h-[108px] min-w-0 flex-col items-center justify-center rounded-xl border border-gray-100 p-4 text-center transition hover:border-blue-200 hover:bg-blue-50"
                >
                  <div className="relative mb-3 flex h-10 w-full items-center justify-center opacity-90 group-hover:opacity-100">
                    {item.data.logo ? (
                      <img
                        src={getImageUrl(item.data.logo)}
                        alt={item.data.name}
                        className="h-10 w-full object-contain"
                        loading="lazy"
                      />
                    ) : (
                      <span className="text-xs font-medium text-slate-500 group-hover:text-[#0057B7]">
                        {isArabic ? item.data.ar_name || item.data.name : item.data.name || item.data.ar_name}
                      </span>
                    )}
                  </div>
                  <span className="line-clamp-2 text-sm font-medium text-slate-700 group-hover:text-[#0057B7]">
                    {isArabic ? item.data.ar_name || item.data.name : item.data.name || item.data.ar_name}
                  </span>
                </button>
              )
            )}
          </div>
        </section>
      )}

      {filteredCountries.length > 0 && (
        <section className="mb-8">
          <h3 className="mb-4 text-lg font-medium text-slate-900">{t("Popular Countries", "أشهر الدول")}</h3>
          <div className="grid grid-cols-2 gap-3 sm:grid-cols-4">
            {filteredCountries.slice(0, 4).map((country) => {
              const flagSrc = getCountryFlagUrl(country);
              return (
                <button
                  key={country.id ?? country.slug ?? country.name}
                  type="button"
                  className="flex min-h-[108px] min-w-0 flex-col items-center justify-center rounded-xl border border-gray-100 p-4 text-center transition hover:border-blue-200 hover:bg-blue-50"
                  onClick={() => handleSelect(country, "country")}
                >
                  {flagSrc ? (
                    <img
                      src={flagSrc}
                      alt={country.name}
                      className="mb-2 h-8 w-8 rounded-full object-cover shadow-sm"
                      loading="lazy"
                      onError={(e) => {
                        const fallback = getLocalCountryFlagPath(country);
                        if (fallback && !e.currentTarget.src.endsWith(fallback)) {
                          e.currentTarget.src = fallback;
                        }
                      }}
                    />
                  ) : (
                    <span className="mb-2 text-3xl">🌍</span>
                  )}
                  <span className="text-sm font-medium text-slate-700">
                    {isArabic ? country.ar_name || country.name : country.name || country.ar_name}
                  </span>
                </button>
              );
            })}
            <HeroSearchOtherCard
              href="/language-institutes"
              label={t("Other Countries", "دول أخرى")}
              isArabic={isArabic}
              onClose={onClose}
            />
          </div>
        </section>
      )}

      {filteredCities.length > 0 && (
        <section>
          <h3 className="mb-4 text-lg font-medium text-slate-900">{t("Popular Cities", "أشهر المدن")}</h3>
          <div className="grid grid-cols-2 gap-3 sm:grid-cols-4">
            {filteredCities.slice(0, 4).map((city) => (
              <button
                key={city.id ?? `${city.slug}-${city.country_name || ""}`}
                type="button"
                className="flex min-h-[52px] min-w-0 flex-col items-center justify-center rounded-xl border border-gray-100 px-4 py-3 text-center transition hover:border-blue-200 hover:bg-blue-50"
                onClick={() => handleSelect(city, "city")}
              >
                <span className="text-sm font-medium text-slate-800">
                  {isArabic ? city.ar_name || city.name : city.name || city.ar_name}
                </span>
              </button>
            ))}
            <HeroSearchOtherCard
              href="/language-institutes"
              label={t("Other Cities", "مدن أخرى")}
              isArabic={isArabic}
              onClose={onClose}
            />
          </div>
        </section>
      )}

      {loading && (
        <div className="text-center py-8 text-slate-500">{t("Loading...", "جاري التحميل...")}</div>
      )}

      {!loading &&
        filteredInstitutes.length === 0 &&
        filteredCountries.length === 0 &&
        filteredCities.length === 0 && (
          <div className="text-center py-8 text-slate-500">
            No results found for &quot;{searchTerm}&quot;
          </div>
        )}
    </div>
  );
}

export default function HeroSearch({
  placeholder = "Enter your preferred destination",
  subPlaceholder = "Enter country, city, or institute",
  value = "",
  searchData = null,
  onSelect,
  variant = "default",
  wideDropdown = false,
  dropdownWidthRatio = 1,
  anchorRef = null,
}) {
  const [isOpen, setIsOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState(value || "");
  const [dropdownPos, setDropdownPos] = useState(null);
  const [mounted, setMounted] = useState(false);
  const dropdownRef = useRef(null);
  const fallback = useHeroSearchData();
  const { language, direction } = useLocale();
  const isArabic = language === "ar";
  const isRtl = direction === "rtl";

  const institutes = searchData?.schools ?? fallback.schools;
  const countries = searchData?.countries ?? fallback.countries;
  const cities = searchData?.cities ?? fallback.cities;
  const loading = searchData ? false : fallback.loading;

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    setSearchTerm(value || "");
  }, [value]);

  const updateDropdownPosition = useCallback(() => {
    const anchor = wideDropdown && anchorRef?.current ? anchorRef.current : dropdownRef.current;
    if (!anchor) return;

    const rect = anchor.getBoundingClientRect();
    const width = wideDropdown ? rect.width * dropdownWidthRatio : rect.width;
    const left = isRtl ? rect.right - width : rect.left;

    setDropdownPos({
      top: rect.bottom + 8,
      left,
      width,
    });
  }, [wideDropdown, anchorRef, dropdownWidthRatio, isRtl]);

  useEffect(() => {
    if (!isOpen) {
      setDropdownPos(null);
      return undefined;
    }

    updateDropdownPosition();
    window.addEventListener("resize", updateDropdownPosition);
    window.addEventListener("scroll", updateDropdownPosition, true);

    return () => {
      window.removeEventListener("resize", updateDropdownPosition);
      window.removeEventListener("scroll", updateDropdownPosition, true);
    };
  }, [isOpen, updateDropdownPosition]);

  useEffect(() => {
    function handleClickOutside(event) {
      const inInput = dropdownRef.current?.contains(event.target);
      const inPanel = event.target.closest?.("[data-hero-search-panel]");
      if (!inInput && !inPanel) {
        setIsOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const filterItems = (items) => {
    if (!searchTerm) return items;
    const lowerTerm = searchTerm.toLowerCase();
    return items.filter(
      (item) =>
        item.name?.toLowerCase().includes(lowerTerm) ||
        (item.ar_name && item.ar_name.toLowerCase().includes(lowerTerm))
    );
  };

  const filteredInstitutes = filterItems(institutes);
  const filteredCountries = filterItems(countries);
  const filteredCities = filterItems(cities);

  const handleSelect = (item, type) => {
    const displayValue = isArabic ? item.ar_name || item.name : item.name || item.ar_name;
    setSearchTerm(displayValue || "");
    setIsOpen(false);
    onSelect?.({ type, slug: item.slug, name: displayValue, ...item });
  };

  const t = (en, ar) => (isArabic ? ar : en);

  const panelProps = {
    isArabic,
    t,
    filteredInstitutes,
    filteredCountries,
    filteredCities,
    handleSelect,
    loading,
    searchTerm,
    onClose: () => setIsOpen(false),
  };

  const dropdownShellClass =
    "overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 animate-in fade-in zoom-in-95 duration-200";

  const dropdownPanel =
    isOpen &&
    dropdownPos &&
    mounted &&
    createPortal(
      <div
        data-hero-search-panel
        dir={direction}
        className={`fixed z-[500] ${dropdownShellClass}`}
        style={{
          top: dropdownPos.top,
          left: dropdownPos.left,
          width: dropdownPos.width,
        }}
      >
        <HeroSearchDropdownPanel {...panelProps} />
      </div>,
      document.body
    );

  return (
    <div className={`relative w-full ${isOpen ? "z-50" : "z-0"}`} ref={dropdownRef}>
      <div
        className={
          variant === "borderless"
            ? "cursor-text px-4 py-2 bg-transparent w-full"
            : "cursor-text rounded-xl border border-[#F6F8FA] bg-white px-8 py-3 2xl:py-4 shadow-sm transition hover:border-blue-300 focus-within:ring-2 focus-within:ring-blue-100"
        }
        onClick={() => setIsOpen(true)}
      >
        <div className="flex items-center gap-2.5">
          <div className="flex-1">
            <input
              type="text"
              className="w-full border-none p-0 text-sm focus:ring-0 outline-none bg-transparent"
              placeholder={placeholder}
              value={searchTerm}
              onChange={(e) => {
                setSearchTerm(e.target.value);
                setIsOpen(true);
              }}
              onFocus={() => setIsOpen(true)}
            />
            <p className="mt-0.5 text-sm text-slate-500">{subPlaceholder}</p>
          </div>
        </div>
      </div>

      {dropdownPanel}
    </div>
  );
}
