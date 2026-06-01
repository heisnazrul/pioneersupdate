"use client";

import HeroDatePicker from "@/components/shared/hero-date-picker";
import MobileLanguageInstitutesHeroSearch from "@/components/mobile/mobile-language-institutes-hero-search";
import MobileHeroDropdown from "@/components/mobile/mobile-hero-dropdown";
import { useLocale } from "@/components/providers/locale-provider";

export default function MobileLanguageInstitutesSearchModal({
  isOpen,
  onClose,
  onSearch,
  searchData,
  page,
  destination,
  onDestinationChange,
  courseType,
  onCourseTypeChange,
  weeks,
  onWeeksChange,
  startDate,
  onStartDateChange,
  weeksOptions,
  courseTypes,
}) {
  const { language, t } = useLocale();
  const isArabic = language === "ar";

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-[100]">
      <div className="absolute inset-0 bg-black/30" onClick={onClose} />
      <div className="absolute inset-x-4 top-6 rounded-3xl bg-white p-4 shadow-2xl">
        <div className="flex justify-start">
          <button
            type="button"
            className="text-2xl text-slate-700"
            onClick={onClose}
            aria-label={isArabic ? "إغلاق" : "Close"}
          >
            ×
          </button>
        </div>

        <div className="mt-4 space-y-4">
          <div className="relative rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start">
            <MobileLanguageInstitutesHeroSearch
              placeholder={
                page?.hero?.labels?.destination_placeholder ||
                (isArabic ? "أدخل وجهتك المفضلة" : "Enter your preferred destination")
              }
              subPlaceholder={
                page?.hero?.labels?.destination ||
                (isArabic ? "الوجهة" : "Destination")
              }
              value={destination?.name || ""}
              searchData={searchData}
              onSelect={onDestinationChange}
            />
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div className="relative rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start">
              <MobileHeroDropdown
                label={page?.hero?.labels?.duration || (isArabic ? "الأسابيع" : "Weeks")}
                placeholder={
                  page?.hero?.labels?.duration_placeholder ||
                  (isArabic ? "اختر الأسابيع" : "Select weeks")
                }
                options={weeksOptions}
                selectedValue={weeks}
                onSelect={(option) =>
                  onWeeksChange(typeof option === "object" ? option.value : parseInt(option, 10))
                }
                scroll
                maxVisibleItems={8}
              />
            </div>
            <div className="rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start">
              <HeroDatePicker
                label={page?.hero?.labels?.start || (isArabic ? "تاريخ البدء" : "Start date")}
                placeholder={
                  page?.hero?.labels?.start_placeholder ||
                  (isArabic ? "اختر التاريخ" : "Select start date")
                }
                selectedDate={startDate}
                onSelect={onStartDateChange}
                variant="borderless"
              />
            </div>
          </div>

          <div className="relative rounded-2xl border border-[#E1E8F0] px-4 py-3 text-start">
            <MobileHeroDropdown
              label={page?.hero?.labels?.course || (isArabic ? "نوع الدورة" : "Course type")}
              placeholder={
                page?.hero?.labels?.course_placeholder ||
                (isArabic ? "اختر نوع الدورة" : "Select course type")
              }
              options={courseTypes}
              selectedValue={courseType}
              onSelect={(option) =>
                onCourseTypeChange(typeof option === "object" ? option.value : option)
              }
            />
          </div>
        </div>

        <button
          type="button"
          className="mt-6 w-full rounded-2xl bg-[#0057B7] py-3 text-base font-normal text-white"
          onClick={onSearch}
        >
          {t("layouts.common.search", isArabic ? "بحث" : "Search")}
        </button>
      </div>
    </div>
  );
}
