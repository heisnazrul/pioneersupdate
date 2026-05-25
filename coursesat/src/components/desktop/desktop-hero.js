/* eslint-disable @next/next/no-img-element */
"use client";

import Image from "next/image";
import { useRouter } from "next/navigation";
import { useMemo, useState } from "react";
import HeroSearch from "@/components/shared/hero-search";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import { useApi } from "@/lib/api";
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

export default function DesktopHero() {
  const router = useRouter();
  const { data: utilities } = useApi("/courseenglish/utilities");
  const { language, messages, direction } = useLocale();
  const hero = messages?.pages?.homepage?.hero ?? {};
  const promoIcon = "/assets/icons/fire.svg";
  const figureImage = "/assets/fig.png";

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
    <section className="relative w-full bg-gradient-to-b from-white to-[#EDF5FB] overflow-hidden">
      {/* Big Ben — centered between student and content */}
      <div className="pointer-events-none select-none absolute inset-0 overflow-hidden">
        <Image
          src="/assets/hero.png"
          alt=""
          fill
          priority
          className="object-cover"
          style={{
            opacity: 0.15,
            objectPosition: "center",
            transform: "scale(0.95) translateY(10%)",
            WebkitMaskImage: "radial-gradient(ellipse at 50% 50%, black 40%, transparent 85%)",
            maskImage: "radial-gradient(ellipse at 50% 50%, black 40%, transparent 85%)"
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-b from-white via-transparent to-[#EDF5FB]/30 mix-blend-overlay"></div>
      </div>

      <div dir={direction} className="relative flex flex-row items-stretch px-4 md:px-10 xl:px-20 2xl:px-40">
        {/* TEXT + FORM: Left in LTR, Right in RTL */}
        <div dir={direction} className="md:w-[60%] 2xl:w-[60%] flex flex-col justify-start z-10 pt-20 2xl:pt-30 pb-8 ps-2">
          {/* Promo line */}
          <div className="mb-6 2xl:mb-10 flex items-center gap-4 2xl:gap-2 text-slate-700">
            <img
              src={promoIcon}
              alt="Promo icon"
              className="h-7 w-7 shrink-0"
              loading="lazy"
            />
            <p className="text-lg 2xl:text-2xl font-normal text-slate-700 leading-snug text-start">
              {hero.offer}
            </p>
          </div>

          {/* Heading */}
          <h1 className="text-5xl 2xl:text-6xl font-bold tracking-tight leading-[1.10] text-slate-900 text-start">
            {hero.headline}
          </h1>

          {/* Description */}
          <p className="mt-6 text-lg 2xl:text-2xl font-normal text-[#8A99AB] leading-relaxed max-w-[500px] 2xl:max-w-[640px] text-start">
            {hero.subheadline}
          </p>

          {/* Search card */}
          <div className="mt-5 w-full max-w-[650px] 2xl:max-w-[750px]">
            <HeroSearch
              placeholder={hero.destination_box?.placeholder}
              subPlaceholder={hero.destination_box?.subtext}
              onSelect={(dest) => setDestination(dest)}
            />

            <div className="mt-4 2xl:mt-6 grid gap-6 sm:grid-cols-3">
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

            {/* Services checklist */}
            <div className="mt-4 2xl:mt-6 text-lg 2xl:text-2xl font-medium text-slate-700 text-start">
              <div className="flex flex-wrap items-center gap-1.5 justify-start">
                {services.map((service) => {
                  const checked = !!selectedServices[service.id];
                  return (
                    <button
                      key={`desktop-${service.id}`}
                      type="button"
                      onClick={() => toggleService(service.id)}
                      className="inline-flex items-center gap-1.5 rounded-full px-2 py-1.5 text-lg font-medium text-slate-700 transition"
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

            <div className="text-start">
              <button
                type="button"
                className="mt-4 mb-0 2xl:mb-30 inline-flex items-center justify-center rounded-xl bg-[#1A70C4] px-14 py-3.5 text-[18px] font-semibold text-white shadow-md hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#1A70C4] focus:ring-offset-2"
                onClick={handleSearch}
              >
                {hero.search_button_text || hero.search_button}
              </button>
            </div>
          </div>
        </div>

        {/* STUDENT IMAGE: Right in LTR, Left in RTL */}
        <div className="relative flex md:w-[40%] 2xl:w-[40%] items-end justify-start z-10">
          <img
            src={figureImage}
            alt="Student giving thumbs up"
            className="relative z-10 h-auto w-full max-w-[450px] 2xl:max-w-[600px] origin-bottom"
            loading="lazy"
          />
        </div>
      </div>
    </section>
  );
}
