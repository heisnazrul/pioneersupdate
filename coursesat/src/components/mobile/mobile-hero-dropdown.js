"use client";

import { useEffect, useRef, useState } from "react";

const ITEM_HEIGHT_PX = 40;

/**
 * Mobile-only dropdown — panel is pinned to the trigger width (inset-x-0 / w-full).
 */
export default function MobileHeroDropdown({
  label,
  placeholder,
  options = [],
  scroll = false,
  maxVisibleItems = 8,
  onSelect,
  selectedValue,
}) {
  const [internalValue, setInternalValue] = useState("");
  const [isOpen, setIsOpen] = useState(false);
  const detailsRef = useRef(null);

  const isControlled = selectedValue !== undefined;
  const currentVal = isControlled ? selectedValue : internalValue;

  const getOptionLabel = (val) => {
    if (val === null || val === undefined || val === "") return "";
    const found = options.find((opt) => (typeof opt === "object" ? opt.value : opt) === val);
    if (found) return typeof found === "object" ? found.label : found;
    return String(val);
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

    onSelect?.(option);

    if (!isControlled) {
      setInternalValue(val);
    }

    if (detailsRef.current) {
      detailsRef.current.open = false;
    }
    setIsOpen(false);
  };

  const listMaxHeight =
    scroll && maxVisibleItems > 0 ? `${maxVisibleItems * ITEM_HEIGHT_PX}px` : undefined;

  return (
    <details
      ref={detailsRef}
      className={`relative w-full ${isOpen ? "z-50" : "z-0"}`}
      onToggle={(e) => setIsOpen(e.currentTarget.open)}
    >
      <summary
        className="flex w-full cursor-pointer list-none items-center justify-between gap-3 bg-transparent px-0 py-0 [&::-webkit-details-marker]:hidden"
        style={{ listStyle: "none" }}
      >
        <div className="min-w-0 flex flex-1 flex-col text-start">
          <span className="truncate whitespace-nowrap text-[13px] font-normal text-black">
            {label}
          </span>
          <span
            className={`mt-1 truncate whitespace-nowrap text-[13px] ${
              displayLabel ? "text-slate-700" : "text-slate-400"
            }`}
          >
            {displayLabel || placeholder}
          </span>
        </div>
      </summary>

      <div
        className={`absolute inset-x-0 top-full z-[110] mt-2 w-full rounded-2xl border border-[#E6EEF7] bg-white p-2 shadow-[0_10px_30px_rgba(15,23,42,0.08)] ${
          scroll ? "overflow-y-auto overscroll-contain scrollbar-thin scrollbar-thumb-gray-200" : ""
        }`}
        style={listMaxHeight ? { maxHeight: listMaxHeight } : undefined}
      >
        {options.map((option, idx) => {
          const optLabel = typeof option === "object" ? option.label : option;
          const optValue = typeof option === "object" ? option.value : option;
          const isSelected = currentVal === optValue;

          return (
            <button
              key={optValue ?? idx}
              type="button"
              onClick={() => handleSelect(option)}
              className={`w-full rounded-xl px-3 py-2.5 text-sm text-start transition ${
                isSelected
                  ? "bg-[#EAF2FF] font-medium text-[#0057B7]"
                  : "font-normal text-slate-600 hover:bg-[#F3F6FA] hover:text-slate-800"
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
