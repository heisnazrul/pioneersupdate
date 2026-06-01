"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { useHeroSearchData } from "@/lib/hero-search-data";
import {
  MOBILE_DESTINATION_COLUMNS,
  buildLanguageInstituteSearchUrl,
} from "@/lib/destination-links";

export default function MobileLinks() {
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const { countries } = useHeroSearchData();

  const heading = t(
    "pages.homepage.destinations.mobile_heading",
    t("pages.homepage.destinations.heading", "Best destinations to study languages")
  );
  const viewAll = t("pages.homepage.destinations.mobile_view_all", isArabic ? "جميع المعاهد" : "All institutes");
  const columns = MOBILE_DESTINATION_COLUMNS[isArabic ? "ar" : "en"];

  return (
    <section className="block md:hidden bg-[#F3F7FF] py-10 w-full" dir={direction}>
      <div className="px-4 mx-auto">
        <div className="flex items-center justify-between gap-3">
          <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[65%] text-start">
            {heading}
          </h2>
          <Link
            href="/language-institutes"
            className="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-[#1F63AE] transition-colors hover:text-[#135FAE]"
          >
            <span>{viewAll}</span>
            <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
          </Link>
        </div>

        <div className="mt-6 grid grid-cols-2 gap-x-6 gap-y-0 text-[13px] leading-snug text-slate-700 text-start">
          {columns.map((col, colIndex) => (
            <div key={colIndex} className="flex flex-col gap-3">
              {col.map((item) => (
                <Link
                  key={item.label}
                  href={buildLanguageInstituteSearchUrl(item, countries)}
                  className="transition-colors hover:text-[#1F63AE] hover:underline"
                >
                  {item.label}
                </Link>
              ))}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
