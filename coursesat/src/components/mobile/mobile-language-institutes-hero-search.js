"use client";

import HeroSearch from "@/components/shared/hero-search";

/**
 * Mobile-only destination search for the language institutes modal.
 * Anchors the dropdown to the input field itself at full input width
 * (does not use the desktop wide-dropdown / half-width panel behavior).
 */
export default function MobileLanguageInstitutesHeroSearch(props) {
  return <HeroSearch {...props} variant="borderless" />;
}
