// components/LanguageDestinations.jsx
"use client";

import { useMemo } from "react";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

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

// Desktop → 4 columns
function splitIntoColumns(items, cols) {
  const out = Array.from({ length: cols }, () => []);
  items.forEach((item, i) => out[i % cols].push(item));
  return out;
}

export default function LanguageDestinations() {
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const destinations = getCourseEnglishMessages(language)?.pages?.homepage?.destinations ?? {};
  const heading =
    destinations?.heading ||
    (isArabic ? "أفضل الوجهات لدراسة اللغة" : "Best destinations to study languages");
  const links = isArabic ? FALLBACK_DESTINATIONS_AR : FALLBACK_DESTINATIONS;

  const desktopCols = useMemo(() => splitIntoColumns(links, 4), [links]);
  const mobileItems = links.slice(0, 16);
  const mobileCols = useMemo(() => splitIntoColumns(mobileItems, 2), [mobileItems]);

  return (
    <section className="bg-[#F3F7FF] py-12 md:py-20">
      <div className="px-4 md:px-10 xl:px-30 2xl:px-50">

        {/* ---------- DESKTOP ---------- */}
        <div className="hidden md:block">
          <h2 className="mb-10 md:mb-20 text-center text-3xl md:text-4xl font-extrabold text-slate-900">
            {heading}
          </h2>

          <div className="grid grid-cols-4 gap-x-16  text-sm text-slate-800">
            {desktopCols.map((col, colIdx) => (
              <div key={colIdx} className="space-y-2">
                {col.map((item, idx) => (
                  <a
                    key={`${item.label}-${idx}`}
                    href={item.url || "#"}
                    className="block transition-colors hover:text-blue-600"
                  >
                    {item.label}
                  </a>
                ))}
              </div>
            ))}
          </div>
        </div>

        {/* ---------- MOBILE ---------- */}
        <div className="md:hidden">
          <div className="rounded-2xl  px-4 py-5">

            <h3 className="mb-10 text-center text-xl font-extrabold text-slate-900">
              {heading}
            </h3>

            <div className="grid grid-cols-2 gap-x-2 gap-y-2 text-xs text-slate-800">
              {mobileCols.map((col, colIndex) => (
                <div key={colIndex} className="space-y-2">
                  {col.map((item, idx) => (
                    <a
                      key={`${item.label}-${idx}`}
                      href={item.url || "#"}
                      className="block leading-snug transition-colors hover:text-blue-600"
                    >
                      {item.label}
                    </a>
                  ))}
                </div>
              ))}
            </div>
          </div>
        </div>

      </div>
    </section>
  );
}
