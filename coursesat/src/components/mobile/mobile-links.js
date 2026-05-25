"use client";

import { useMemo } from "react";
import { useLocale } from "@/components/providers/locale-provider";

const FALLBACK_DESTINATIONS = [
  { label: "Language institutes in China", url: "/language-institutes/china" },
  { label: "Language institutes in Japan", url: "/language-institutes/japan" },
  { label: "Language institutes in South Korea", url: "/language-institutes/south-korea" },
  { label: "Language institutes in Malaysia", url: "/language-institutes/malaysia" },
  { label: "Language institutes in Singapore", url: "/language-institutes/singapore" },
  { label: "Language institutes in Thailand", url: "/language-institutes/thailand" },
  { label: "Language institutes in India", url: "/language-institutes/india" },
  { label: "Language institutes in Indonesia", url: "/language-institutes/indonesia" },
  { label: "Language institutes in the Philippines", url: "/language-institutes/philippines" },
  { label: "Language institutes in Vietnam", url: "/language-institutes/vietnam" },
  { label: "Language institutes in the UK", url: "/language-institutes/uk" },
  { label: "Language institutes in Ireland", url: "/language-institutes/ireland" },
  { label: "Language institutes in Germany", url: "/language-institutes/germany" },
  { label: "Language institutes in France", url: "/language-institutes/france" },
  { label: "Language institutes in the Netherlands", url: "/language-institutes/netherlands" },
  { label: "Language institutes in Canada", url: "/language-institutes/canada" },
  { label: "Language institutes in USA", url: "/language-institutes/usa" },
  { label: "Language institutes in Australia", url: "/language-institutes/australia" },
  { label: "Language institutes in South Africa", url: "/language-institutes/south-africa" },
];

const FALLBACK_DESTINATIONS_AR = [
  { label: "معاهد اللغة في الصين", url: "/language-institutes/china" },
  { label: "معاهد اللغة في اليابان", url: "/language-institutes/japan" },
  { label: "معاهد اللغة في كوريا الجنوبية", url: "/language-institutes/south-korea" },
  { label: "معاهد اللغة في ماليزيا", url: "/language-institutes/malaysia" },
  { label: "معاهد اللغة في سنغافورة", url: "/language-institutes/singapore" },
  { label: "معاهد اللغة في تايلاند", url: "/language-institutes/thailand" },
  { label: "معاهد اللغة في الهند", url: "/language-institutes/india" },
  { label: "معاهد اللغة في إندونيسيا", url: "/language-institutes/indonesia" },
  { label: "معاهد اللغة في الفلبين", url: "/language-institutes/philippines" },
  { label: "معاهد اللغة في فيتنام", url: "/language-institutes/vietnam" },
  { label: "معاهد اللغة في المملكة المتحدة", url: "/language-institutes/uk" },
  { label: "معاهد اللغة في إيرلندا", url: "/language-institutes/ireland" },
  { label: "معاهد اللغة في ألمانيا", url: "/language-institutes/germany" },
  { label: "معاهد اللغة في فرنسا", url: "/language-institutes/france" },
  { label: "معاهد اللغة في هولندا", url: "/language-institutes/netherlands" },
  { label: "معاهد اللغة في كندا", url: "/language-institutes/canada" },
  { label: "معاهد اللغة في الولايات المتحدة", url: "/language-institutes/usa" },
  { label: "معاهد اللغة في أستراليا", url: "/language-institutes/australia" },
  { label: "معاهد اللغة في جنوب أفريقيا", url: "/language-institutes/south-africa" },
];

function splitIntoColumns(items, cols) {
  const out = Array.from({ length: cols }, () => []);
  items.forEach((item, i) => out[i % cols].push(item));
  return out;
}

export default function MobileLinks() {
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const heading = t("pages.homepage.destinations.heading", "Best destinations to study languages");
  const links = isArabic ? FALLBACK_DESTINATIONS_AR : FALLBACK_DESTINATIONS;

  const mobileItems = useMemo(() => links.slice(0, 16), [links]);
  const mobileCols = useMemo(() => splitIntoColumns(mobileItems, 2), [mobileItems]);

  return (
    <section className="block md:hidden bg-[#F3F7FF] py-10 w-full" dir={direction}>
      <div className="px-4 mx-auto">
        
        {/* Title */}
        <h3 className="mb-6 text-center text-lg font-bold text-slate-900">
          {heading}
        </h3>

        {/* 2-Column Grid */}
        <div className="grid grid-cols-2 gap-x-4 gap-y-2 text-[12px] text-slate-700 leading-normal text-start px-2">
          {mobileCols.map((col, colIndex) => (
            <div key={colIndex} className="flex flex-col gap-2.5">
              {col.map((item, idx) => (
                <a
                  key={`${item.label}-${idx}`}
                  href={item.url || "#"}
                  className="leading-snug transition-colors hover:text-blue-600 hover:underline"
                >
                  {item.label}
                </a>
              ))}
            </div>
          ))}
        </div>

      </div>
    </section>
  );
}
