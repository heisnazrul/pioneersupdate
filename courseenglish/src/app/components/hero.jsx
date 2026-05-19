// components/HeroSection.js
"use client";

import Image from "next/image";
import { useRouter } from "next/navigation";
import { useMemo, useState } from "react";
import HeroSearch from "./HeroSearch";
import HeroDropdown from "./HeroDropdown";
import HeroDatePicker from "./HeroDatePicker";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

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

export default function HeroSection() {
  const router = useRouter();
  const { data: utilities } = useApi("/courseenglish/utilities");
  const { language } = useCourseEnglishSettings(); // 'ar' or 'en'
  const locale = getCourseEnglishMessages(language);
  const hero = locale?.pages?.homepage?.hero ?? {};
  const promoIcon = "/assets/icons/fire.svg";
  const figureImage = "/assets/fig.png";
  const heroServices = hero?.services ?? {};
  const services = useMemo(() => {
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
  }, [heroServices, language]);

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
        language === "ar" ? `${i + 1} أسبوع${i + 1 === 1 ? "" : ""}` : `${i + 1} Week${i === 0 ? "" : "s"}`
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
      if (destination.type === 'school') params.set('school_slug', destination.slug);
      if (destination.type === 'city') params.set('city_slug', destination.slug);
      if (destination.type === 'country') params.set('country_slug', destination.slug);
    }

    if (courseType) params.set('course_type', courseType);
    if (weeks) params.set('weeks', weeks);
    if (startDate) params.set('start_date', formatLocalDate(startDate));

    router.push(`/language-institutes?${params.toString()}`);
  };

  const renderServiceControls = (variant) => (
    <div
      className={
        variant === "desktop"
          ? "flex flex-wrap items-center gap-1.5"
          : "flex gap-1.5 text-sm"
      }
    >
      {services.map((service) => {
        const checked = !!selectedServices[service.id];
        return (
          <button
            key={`${variant}-${service.id}`}
            type="button"
            onClick={() => toggleService(service.id)}
            className={`inline-flex items-center gap-1.5 rounded-full px-2 py-1.5 ${variant === "desktop"
              ? "text-lg font-medium"
              : "text-sm font-normal"
              } text-slate-700 transition`}
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
  );

  const courseTypeOptions =
    utilities?.language_course_types?.map((type) =>
      language === "ar" ? type.ar_name || type.name : type.name || type.ar_name
    ) ?? [
      "General English",
      "Intensive English",
      "Semi-Intensive",
      "IELTS Preparation",
      "Business English",
    ];

  return (
    <section className="relative w-full md:bg-[#EDF5FB] overflow-hidden">
      {/* Big Ben — centered between student and content */}
      <div className="pointer-events-none select-none absolute inset-0 hidden md:block overflow-hidden">
        <Image
          src="/assets/hero.png"
          alt=""
          fill
          priority
          className="object-[50%_center]"
          style={{ opacity: 0.15 }}
        />
      </div>

      {/* ===== DESKTOP / LARGE SCREENS ===== */}
      <div dir="ltr" className="relative hidden md:flex md:flex-row md:items-stretch px-4 md:px-10 xl:px-30 2xl:px-40">

        {/* LEFT: Student image — large, fills hero height, bottom-anchored */}
        <div className="relative flex w-[40%] items-end justify-end z-10">
          <img
            src={figureImage}
            alt="Student giving thumbs up"
            className="relative z-10 h-auto w-full max-w-[600px]  origin-bottom"
            loading="lazy"
          />
        </div>

        {/* RIGHT: Text + form — RTL restored inside */}
        <div dir="rtl" className="w-[70%] flex flex-col justify-start z-10 pt-30 pb-8 pr-2">

          {/* Promo line — fire icon on left in RTL (last in DOM) */}
          <div className="mb-10 flex items-center gap-2 text-slate-700">
            <img
              src={promoIcon}
              alt="Promo icon"
              className="h-7 w-7 shrink-0"
              loading="lazy"
            />
            <p className="text-2xl font-normal text-slate-700 leading-snug">
              {hero.offer}
            </p>

          </div>

          {/* Heading — forced single line to match Figma */}
          <h1 className="text-6xl font-bold tracking-tight leading-[1.10] text-slate-900 ">
            {hero.headline}
          </h1>

          {/* Description */}
          <p className="mt-6 text-2xl font-normal text-[#8A99AB] leading-relaxed max-w-[640px]">
            {hero.subheadline}
          </p>

          {/* Search card */}
          <div className="mt-5 w-full max-w-[750px]">
            <HeroSearch
              placeholder={hero.destination_box?.placeholder}
              subPlaceholder={hero.destination_box?.subtext}
              onSelect={(dest) => setDestination(dest)}
            />

            <div className="mt-6 grid gap-6 sm:grid-cols-3">
              <HeroDropdown
                label={hero.course_label}
                placeholder={hero.course_placeholder}
                options={courseTypeOptions}
                selectedValue={courseType}
                onSelect={(val) => setCourseType(val)}
              />
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

            <div className="mt-6 text-2xl font-medium text-slate-700">
              {renderServiceControls("desktop")}
            </div>

            <button
              type="button"
              className="mt-4 mb-30 inline-flex items-center justify-center rounded-xl bg-[#1A70C4] px-14 py-3.5 text-[18px] font-semibold text-white shadow-md hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#1A70C4] focus:ring-offset-2"
              onClick={handleSearch}
            >
              {hero.search_button_text || hero.search_button}
            </button>
          </div>
        </div>
      </div>

      {/* ===== MOBILE / SMALL SCREENS ===== */}
      <div className="relative md:hidden flex justify-center px-2 py-6">
        <div className="w-full max-w-sm px-4 py-6">
          {/* Mobile heading */}
          <h2 className="text-center text-2xl font-extrabold leading-snug text-slate-900 pb-4">
            {hero.headline}
          </h2>

          {/* Fields */}
          <div className="mt-4 space-y-3">
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
                onSelect={(val) => setCourseType(val)}
              />
            </div>
          </div>

          {/* Radios */}
          <div className=" mt-4 text-xs font-normal text-slate-700">
            {renderServiceControls("mobile")}
          </div>

          {/* Button */}
          <button
            type="button"
            className="mt-5 inline-flex w-full items-center justify-center rounded-full bg-[#135FAE] px-4 py-3 text-sm font-normal text-white shadow-md hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#135FAE] focus:ring-offset-2 focus:ring-offset-white"
            onClick={handleSearch}
          >
            {hero.search_button_text || hero.search_button}
          </button>
        </div>
      </div>
    </section>
  );
}
