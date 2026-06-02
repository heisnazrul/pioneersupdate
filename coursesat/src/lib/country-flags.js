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
  japan: "jp",
  china: "cn",
  india: "in",
  brazil: "br",
  portugal: "pt",
  austria: "at",
  belgium: "be",
  sweden: "se",
  norway: "no",
  denmark: "dk",
  finland: "fi",
  poland: "pl",
  turkey: "tr",
  "united-arab-emirates": "ae",
  uae: "ae",
  egypt: "eg",
  qatar: "qa",
  kuwait: "kw",
  bahrain: "bh",
  oman: "om",
  jordan: "jo",
  lebanon: "lb",
  cyprus: "cy",
  greece: "gr",
  hungary: "hu",
  "czech-republic": "cz",
  czechia: "cz",
  slovakia: "sk",
  croatia: "hr",
  argentina: "ar",
  chile: "cl",
  mexico: "mx",
  colombia: "co",
  peru: "pe",
  singapore: "sg",
  thailand: "th",
  vietnam: "vn",
  indonesia: "id",
  "south-korea": "kr",
  korea: "kr",
  taiwan: "tw",
  "hong-kong": "hk",
  philippines: "ph",
};

const NAME_TO_CODE = {
  "united kingdom": "gb",
  uk: "gb",
  britain: "gb",
  england: "gb",
  scotland: "gb",
  wales: "gb",
  "المملكة المتحدة": "gb",
  "بريطانيا": "gb",
  "انجلترا": "gb",
  "إنجلترا": "gb",
  switzerland: "ch",
  swiss: "ch",
  suisse: "ch",
  schweiz: "ch",
  "سويسرا": "ch",
  "united states": "us",
  usa: "us",
  america: "us",
  "الولايات المتحدة": "us",
  "أمريكا": "us",
  canada: "ca",
  "كندا": "ca",
  australia: "au",
  "أستراليا": "au",
  germany: "de",
  "ألمانيا": "de",
  ireland: "ie",
  "إيرلندا": "ie",
  netherlands: "nl",
  holland: "nl",
  "هولندا": "nl",
  malaysia: "my",
  "ماليزيا": "my",
  "saudi arabia": "sa",
  "المملكة العربية السعودية": "sa",
  "السعودية": "sa",
  france: "fr",
  "فرنسا": "fr",
  "new zealand": "nz",
  "نيوزيلندا": "nz",
  malta: "mt",
  "مالطا": "mt",
  spain: "es",
  "إسبانيا": "es",
  italy: "it",
  "إيطاليا": "it",
  "south africa": "za",
  "جنوب افريقيا": "za",
  russia: "ru",
  "روسيا": "ru",
  japan: "jp",
  "اليابان": "jp",
  china: "cn",
  "الصين": "cn",
  india: "in",
  "الهند": "in",
  brazil: "br",
  "البرازيل": "br",
  portugal: "pt",
  "البرتغال": "pt",
  austria: "at",
  "النمسا": "at",
  belgium: "be",
  "بلجيكا": "be",
  sweden: "se",
  "السويد": "se",
  norway: "no",
  "النرويج": "no",
  denmark: "dk",
  "الدنمارك": "dk",
  finland: "fi",
  "فنلندا": "fi",
  poland: "pl",
  "بولندا": "pl",
  turkey: "tr",
  "تركيا": "tr",
  uae: "ae",
  "united arab emirates": "ae",
  "الإمارات": "ae",
  "الامارات": "ae",
  egypt: "eg",
  "مصر": "eg",
  qatar: "qa",
  "قطر": "qa",
  kuwait: "kw",
  "الكويت": "kw",
  bahrain: "bh",
  "البحرين": "bh",
  oman: "om",
  "عمان": "om",
  jordan: "jo",
  "الأردن": "jo",
  lebanon: "lb",
  "لبنان": "lb",
  cyprus: "cy",
  "قبرص": "cy",
  greece: "gr",
  "اليونان": "gr",
  hungary: "hu",
  "المجر": "hu",
  "czech republic": "cz",
  czechia: "cz",
  "التشيك": "cz",
  singapore: "sg",
  "سنغافورة": "sg",
  thailand: "th",
  "تايلاند": "th",
  vietnam: "vn",
  "فيتنام": "vn",
  indonesia: "id",
  "إندونيسيا": "id",
  "south korea": "kr",
  korea: "kr",
  "كوريا": "kr",
  taiwan: "tw",
  "تايوان": "tw",
  "hong kong": "hk",
  "هونغ كونغ": "hk",
  philippines: "ph",
  "الفلبين": "ph",
};

function normalizeCountryKey(value) {
  return String(value ?? "")
    .trim()
    .toLowerCase()
    .replace(/[^\w\s\u0600-\u06FF-]/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

function codeFromName(value) {
  const key = normalizeCountryKey(value);
  if (!key) return null;
  if (NAME_TO_CODE[key]) return NAME_TO_CODE[key];

  const slug = key.replace(/\s+/g, "-");
  if (SLUG_TO_CODE[slug]) return SLUG_TO_CODE[slug];

  return null;
}

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

  const slug = String(country.slug || country.country_slug || "")
    .trim()
    .toLowerCase();

  if (SLUG_TO_CODE[slug]) return SLUG_TO_CODE[slug];

  const names = [
    country.name,
    country.ar_name,
    country.country_name,
    country.country_en,
    country.country_ar,
    country.country_ar_name,
    country.country,
  ];

  for (const name of names) {
    const code = codeFromName(name);
    if (code) return code;
  }

  return null;
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

/** Resolve a listing/institute card flag from all known API fields. */
export function getInstituteCountryFlag(institute) {
  if (!institute) return null;

  const meta = {
    flag: institute.flag || institute.country_flag,
    country_code: institute.country_code,
    slug: institute.country_slug,
    country_slug: institute.country_slug,
    name: institute.country_en || institute.country_name || institute.country,
    ar_name: institute.country_ar || institute.country_ar_name,
    country_name: institute.country_name,
    country_en: institute.country_en,
    country_ar: institute.country_ar,
    country_ar_name: institute.country_ar_name,
    country: institute.country,
  };

  return getCountryFlagUrl(meta);
}

export function getInstituteCountryFlagFallback(institute) {
  return getLocalCountryFlagPath({
    country_code: institute?.country_code,
    slug: institute?.country_slug,
    name: institute?.country_en || institute?.country_name || institute?.country,
    ar_name: institute?.country_ar || institute?.country_ar_name,
    country_name: institute?.country_name,
    country_en: institute?.country_en,
    country_ar: institute?.country_ar,
    country_ar_name: institute?.country_ar_name,
    country: institute?.country,
  });
}
