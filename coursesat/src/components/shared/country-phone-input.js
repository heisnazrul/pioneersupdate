"use client";
/* eslint-disable @next/next/no-img-element */

import { useEffect, useRef } from "react";

import { AUTH_COUNTRIES } from "@/lib/auth-countries";

export default function CountryPhoneInput({
  country,
  onCountryChange,
  countryOpen,
  onCountryOpenChange,
  value,
  onChange,
  placeholder,
  isRtl,
  variant = "auth",
}) {
  const dropdownRef = useRef(null);
  const isBooking = variant === "booking";

  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        onCountryOpenChange(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, [onCountryOpenChange]);

  const shellClass = isBooking
    ? "group relative flex h-12 w-full items-center overflow-hidden rounded-xl border border-gray-200 bg-white transition-all focus-within:border-[#0057B7]"
    : `group relative flex w-full items-center rounded-2xl border border-slate-200 bg-white shadow-sm transition-all focus-within:border-[#135FAE] focus-within:ring-4 focus-within:ring-blue-500/10 ${isRtl ? "flex-row-reverse" : ""}`;

  const buttonClass = isBooking
    ? "flex h-full items-center gap-2 border-l border-gray-200 bg-gray-50 px-3 transition-colors hover:bg-gray-100"
    : `flex h-full items-center gap-2 bg-slate-50/50 px-4 transition-colors hover:bg-slate-50 ${isRtl ? "rounded-r-2xl border-l border-slate-100" : "rounded-l-2xl border-r border-slate-100"}`;

  const inputClass = isBooking
    ? "min-w-0 flex-1 bg-transparent px-4 text-left text-sm text-slate-900 outline-none placeholder:font-normal placeholder:text-gray-300"
    : `flex-1 bg-transparent px-4 py-4 text-base font-medium text-slate-900 outline-none placeholder:font-normal placeholder:text-slate-300 ${isRtl ? "text-right" : "text-left"}`;

  const inputEl = (
    <input
      type="tel"
      name="phone"
      placeholder={placeholder}
      value={value}
      onChange={onChange}
      className={inputClass}
      dir="ltr"
    />
  );

  const countryEl = (
    <div className="relative h-full shrink-0 border-slate-100" ref={dropdownRef}>
      <button
        type="button"
        onClick={() => onCountryOpenChange(!countryOpen)}
        className={buttonClass}
      >
        <img
          src={`/assets/flags/${country.code}.svg`}
          alt={country.name}
          className={`object-cover shadow-sm ${isBooking ? "h-5 w-7 rounded-sm" : "h-5 w-7"}`}
        />
        <span className="text-sm font-medium text-slate-700" dir="ltr">
          {country.dial}
        </span>
        <svg
          className={`h-3 w-3 text-slate-400 transition-transform ${countryOpen ? "rotate-180" : ""}`}
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      {countryOpen && (
        <div
          className={`absolute top-full z-50 mt-2 max-h-60 w-64 overflow-y-auto rounded-xl border border-slate-100 bg-white py-1 shadow-xl ${isRtl ? "right-0" : "left-0"}`}
        >
          {AUTH_COUNTRIES.map((item) => (
            <button
              key={item.code}
              type="button"
              onClick={() => {
                onCountryChange(item);
                onCountryOpenChange(false);
              }}
              className={`flex w-full items-center gap-3 px-4 py-3 transition-colors hover:bg-slate-50 ${isRtl ? "text-right" : "text-left"}`}
            >
              <img src={`/assets/flags/${item.code}.svg`} alt={item.name} className="h-5 w-7 object-cover shadow-sm" />
              <span className="flex-1 text-sm font-normal text-slate-700">{item.name}</span>
              <span className="text-xs font-medium text-slate-600" dir="ltr">
                {item.dial}
              </span>
            </button>
          ))}
        </div>
      )}
    </div>
  );

  if (isBooking) {
    return (
      <div className={shellClass} dir="ltr">
        {inputEl}
        {countryEl}
      </div>
    );
  }

  return (
    <div className={shellClass}>
      {countryEl}
      {inputEl}
    </div>
  );
}
