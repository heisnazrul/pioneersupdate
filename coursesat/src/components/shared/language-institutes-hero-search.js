"use client";

import HeroSearch from "@/components/shared/hero-search";

export default function LanguageInstitutesHeroSearch({ anchorRef, ...props }) {
  return <HeroSearch {...props} anchorRef={anchorRef} wideDropdown dropdownWidthRatio={0.5} />;
}
