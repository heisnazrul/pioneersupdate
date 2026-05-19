"use client";

import { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronUp, faChevronDown } from "@fortawesome/free-solid-svg-icons";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

function SidebarSection({ title, children, defaultOpen = true }) {
    const [isOpen, setIsOpen] = useState(defaultOpen);

    return (
        <div className="rounded-2xl bg-white p-6 shadow-sm mb-4 last:mb-0 border border-gray-100">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="flex w-full items-center justify-between text-start"
            >
                <span className="text-base font-medium text-slate-900">{title}</span>
                <FontAwesomeIcon
                    icon={isOpen ? faChevronUp : faChevronDown}
                    className="h-3 w-3 text-slate-400"
                />
            </button>
            {isOpen && <div className="mt-4 space-y-3">{children}</div>}
        </div>
    );
}

function RadioOption({ name, label, checked, onChange }) {
    return (
        <label className="group flex cursor-pointer items-center justify-between">
            <span className="text-sm text-slate-600 transition group-hover:text-slate-900">{label}</span>
            <div className="relative flex items-center">
                <input
                    type="radio"
                    name={name}
                    checked={checked}
                    onChange={onChange}
                    className="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-gray-300 bg-white checked:border-[#0057B7] checked:bg-[#0057B7] hover:border-[#0057B7]"
                />
                <div className="pointer-events-none absolute left-1/2 top-1/2 h-2 w-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
            </div>
        </label>
    );
}

export default function LanguageInstitutesSidebar({ filters = {}, onFiltersChange }) {
    const { accommodation, pickup, insurance } = filters;
    const { isArabic } = useCourseEnglishSettings();

    const update = (key, value) => {
        onFiltersChange?.({ ...filters, [key]: value });
    };

    const allLabel = isArabic ? "الكل" : "All";

    return (
        <aside className="flex flex-col gap-4">
            <SidebarSection title={isArabic ? "الإقامة" : "Accommodation"}>
                <RadioOption
                    name="accommodation"
                    label={allLabel}
                    checked={accommodation === null || accommodation === undefined}
                    onChange={() => update("accommodation", null)}
                />
                <RadioOption
                    name="accommodation"
                    label={isArabic ? "مع إقامة" : "With Accommodation"}
                    checked={accommodation === true}
                    onChange={() => update("accommodation", true)}
                />
            </SidebarSection>

            <SidebarSection title={isArabic ? "التوصيل من المطار" : "Airport Pickup"}>
                <RadioOption
                    name="pickup"
                    label={allLabel}
                    checked={pickup === null || pickup === undefined}
                    onChange={() => update("pickup", null)}
                />
                <RadioOption
                    name="pickup"
                    label={isArabic ? "مع توصيل" : "With Pickup"}
                    checked={pickup === true}
                    onChange={() => update("pickup", true)}
                />
            </SidebarSection>

            <SidebarSection title={isArabic ? "التأمين" : "Insurance"}>
                <RadioOption
                    name="insurance"
                    label={allLabel}
                    checked={insurance === null || insurance === undefined}
                    onChange={() => update("insurance", null)}
                />
                <RadioOption
                    name="insurance"
                    label={isArabic ? "مع تأمين" : "With Insurance"}
                    checked={insurance === true}
                    onChange={() => update("insurance", true)}
                />
            </SidebarSection>
        </aside>
    );
}
