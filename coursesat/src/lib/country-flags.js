import { getImageUrl } from "@/lib/api";

/** ISO 3166-1 alpha-2 (lowercase) from common country slugs when API omits country_code. */
const SLUG_TO_CODE = {
  "united-kingdom": "gb",
  "united-states": "us",
  usa: "us",
  canada: "ca",
  australia: "au",
  germany: "de",
  ireland: "ie",
  netherlands: "nl",
  malaysia: "my",
  "saudi-arabia": "sa",
  bangladesh: "bd",
  france: "fr",
  "new-zealand": "nz",
  malta: "mt",
  spain: "es",
  italy: "it",
  switzerland: "ch",
  "south-africa": "za",
  russia: "ru",
};

export function resolveCountryCode(country) {
  if (!country) return null;

  const raw =
    country.country_code ||
    country.code ||
    (typeof country.id === "string" && country.id.length <= 3 ? country.id : null);

  if (raw) {
    const normalized = String(raw).trim().toLowerCase();
    if (normalized === "uk") return "gb";
    if (/^[a-z]{2}$/.test(normalized)) return normalized;
  }

  const slug = String(country.slug || "")
    .trim()
    .toLowerCase();

  return SLUG_TO_CODE[slug] || null;
}

export function getLocalCountryFlagPath(country) {
  const code = resolveCountryCode(country);
  return code ? `/assets/flags/${code}.svg` : null;
}

/** Prefer API/storage flag; fall back to bundled SVGs in public/assets/flags/. */
export function getCountryFlagUrl(country) {
  const fromApi = getImageUrl(country?.flag);
  if (fromApi) return fromApi;

  return getLocalCountryFlagPath(country);
}
