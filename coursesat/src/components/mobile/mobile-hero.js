"use client";

import Image from "next/image";
import { useRouter } from "next/navigation";
import { useMemo, useState } from "react";
import HeroSearch from "@/components/shared/hero-search";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import { mapCourseTypeOptions, useHeroSearchData } from "@/lib/hero-search-data";
import { useLocale } from "@/components/providers/locale-provider";

const DEFAULT_SERVICE_MAP = [
  { key: "accommodation" },
  { key: "pickup" },
  { key: "insurance" },
];

function formatLocalDate(date) {
  if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "";
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

export default function MobileHero() {
  const router = useRouter();
  const { courseTypes } = useHeroSearchData();
  const { language, messages, direction } = useLocale();
  const hero = messages?.pages?.homepage?.hero ?? {};

  const services = useMemo(() => {
    const heroServices = hero?.services ?? {};
    const defaultLabels = [
      language === "ar" ? "السكن" : "Accommodation",
      language === "ar" ? "الاستقبال من المطار" : "Pickup",
      language === "ar" ? "التأمين" : "Insurance",
    ];
    const list = Object.keys(heroServices).length > 0
      ? Object.entries(heroServices).map(([key, label]) => ({ key, label }))
      : DEFAULT_SERVICE_MAP.map((item, i) => ({
        key: item.key,
        label: defaultLabels[i],
      }));
    return list.map((item, index) => {
      const label = typeof item === "string" ? item : (item?.label || item?.name || "");
      const key = item?.key || DEFAULT_SERVICE_MAP[index]?.key || `service-${index}`;
      return {
        id: key,
        label: label || defaultLabels[index] || `Service ${index + 1}`,
        defaultChecked: index === 0,
      };
    });
  }, [hero.services, language]);

  const [selectedServices, setSelectedServices] = useState(() => {
    const initial = {};
    DEFAULT_SERVICE_MAP.forEach((item, index) => {
      if (index === 0) initial[item.key] = true;
    });
    return initial;
  });

  // -- Search State --
  const [destination, setDestination] = useState(null);
  const [courseType, setCourseType] = useState(null);
  const [weeks, setWeeks] = useState(12);
  const [startDate, setStartDate] = useState(null);

  const weeksOptions = useMemo(
    () =>
      Array.from({ length: 48 }, (_, i) =>
        language === "ar" ? `${i + 1} أسبوع` : `${i + 1} Week${i === 0 ? "" : "s"}`
      ),
    [language]
  );
  const formatWeeksValue = (w) =>
    w ? (language === "ar" ? `${w} أسبوع` : `${w} Week${w === 1 ? "" : "s"}`) : undefined;

  const toggleService = (id) => {
    setSelectedServices((prev) => ({
      ...prev,
      [id]: !prev[id],
    }));
  };

  const handleSearch = () => {
    const params = new URLSearchParams();

    if (destination) {
      if (destination.type === "school") params.set("school_slug", destination.slug);
      if (destination.type === "city") params.set("city_slug", destination.slug);
      if (destination.type === "country") params.set("country_slug", destination.slug);
    }

    if (courseType) params.set("course_type", courseType);
    if (weeks) params.set("weeks", weeks);
    if (startDate) params.set("start_date", formatLocalDate(startDate));

    router.push(`/language-institutes?${params.toString()}`);
  };

  const courseTypeOptions = useMemo(
    () => mapCourseTypeOptions(courseTypes, language),
    [courseTypes, language]
  );

  return (
    <section className="relative w-full overflow-hidden" dir={direction}>
      <div className="relative flex justify-center px-2 py-6" dir={direction}>
        <div className="w-full max-w-sm px-4 py-6">
          {/* Mobile heading */}
          <h2 className="text-center text-2xl font-bold leading-snug text-slate-900 pb-4">
            {hero.headline}
          </h2>

          {/* Fields */}
          <div className="relative z-40 mt-4 space-y-3">
            {/* Destination */}
            <HeroSearch
              placeholder={hero.destination_box?.placeholder}
              subPlaceholder={hero.destination_box?.subtext}
              onSelect={(dest) => setDestination(dest)}
            />

            {/* Row: number of weeks + start date */}
            <div className="grid grid-cols-2 gap-3">
              <HeroDropdown
                label={hero.weeks_label}
                placeholder={hero.weeks_placeholder}
                options={weeksOptions}
                scroll
                selectedValue={formatWeeksValue(weeks)}
                onSelect={(val) => setWeeks(parseInt(val))}
              />
              <HeroDatePicker
                label={hero.start_label}
                placeholder={hero.start_placeholder}
                selectedDate={startDate}
                onSelect={(date) => setStartDate(date)}
              />
            </div>

            {/* Course type full width */}
            <div className="mt-3">
              <HeroDropdown
                label={hero.course_label}
                placeholder={hero.course_placeholder}
                options={courseTypeOptions}
                selectedValue={courseType}
                onSelect={(option) =>
                  setCourseType(typeof option === "object" ? option.value : option)
                }
              />
            </div>
          </div>

          {/* Services checklist */}
          <div className="relative z-0 mt-4 text-xs font-normal text-slate-700">
            <div className="flex gap-1.5 text-sm justify-center">
              {services.map((service) => {
                const checked = !!selectedServices[service.id];
                return (
                  <button
                    key={`mobile-${service.id}`}
                    type="button"
                    onClick={() => toggleService(service.id)}
                    className="inline-flex items-center gap-1.5 rounded-full px-2 py-1.5 text-sm font-normal text-slate-700 transition"
                  >
                    <Image
                      src={
                        checked
                          ? "/assets/icons/selected-blue.svg"
                          : "/assets/icons/selected-null.svg"
                      }
                      alt={checked ? "Selected" : "Not selected"}
                      width={20}
                      height={20}
                      className="h-5 w-5"
                    />
                    <span>{service.label}</span>
                  </button>
                );
              })}
            </div>
          </div>

          {/* Search Button */}
          <button
            type="button"
            className="relative z-0 mt-5 inline-flex w-full items-center justify-center rounded-full bg-[#135FAE] px-4 py-3 text-sm font-normal text-white shadow-md hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#135FAE] focus:ring-offset-2 focus:ring-offset-white"
            onClick={handleSearch}
          >
            {hero.search_button_text || hero.search_button}
          </button>
        </div>
      </div>
    </section>
  );
}
