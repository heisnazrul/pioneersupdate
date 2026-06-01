/**
 * Mobile homepage destination link columns (Figma order: right column first, then left).
 * Each item filters /language-institutes via country_slug (same as hero search).
 */
export const MOBILE_DESTINATION_COLUMNS = {
  ar: [
    [
      { label: "معاهد لغة في بريطانيا", countrySlug: "united-kingdom" },
      { label: "أرخص معاهد اللغة في بريطانيا", countrySlug: "united-kingdom" },
      { label: "معاهد لغة في أمريكا", countrySlug: "united-states" },
      { label: "معاهد لغة في أيرلندا", countrySlug: "ireland" },
      { label: "معاهد لغة في الإمارات العربية المتحدة", countrySlug: "united-arab-emirates" },
      { label: "معاهد لغة في استراليا", countrySlug: "australia" },
      { label: "معاهد لغة في مالطا", countrySlug: "malta" },
      { label: "معاهد لغة في ألمانيا", countrySlug: "germany" },
    ],
    [
      { label: "معاهد لغة في الصين", countrySlug: "china" },
      { label: "معاهد لغة في ماليزيا", countrySlug: "malaysia" },
      { label: "معاهد لغة في تركيا", countrySlug: "turkey" },
      { label: "معاهد لغة في جنوب افريقيا", countrySlug: "south-africa" },
      { label: "معاهد لغة في قبرص", countrySlug: "cyprus" },
      { label: "معاهد لغة في تايلاند", countrySlug: "thailand" },
    ],
  ],
  en: [
    [
      { label: "Language institutes in the UK", countrySlug: "united-kingdom" },
      { label: "Cheapest language institutes in the UK", countrySlug: "united-kingdom" },
      { label: "Language institutes in the USA", countrySlug: "united-states" },
      { label: "Language institutes in Ireland", countrySlug: "ireland" },
      { label: "Language institutes in the UAE", countrySlug: "united-arab-emirates" },
      { label: "Language institutes in Australia", countrySlug: "australia" },
      { label: "Language institutes in Malta", countrySlug: "malta" },
      { label: "Language institutes in Germany", countrySlug: "germany" },
    ],
    [
      { label: "Language institutes in China", countrySlug: "china" },
      { label: "Language institutes in Malaysia", countrySlug: "malaysia" },
      { label: "Language institutes in Turkey", countrySlug: "turkey" },
      { label: "Language institutes in South Africa", countrySlug: "south-africa" },
      { label: "Language institutes in Cyprus", countrySlug: "cyprus" },
      { label: "Language institutes in Thailand", countrySlug: "thailand" },
    ],
  ],
};

export function buildLanguageInstituteSearchUrl(item, countries = []) {
  const params = new URLSearchParams();
  const slug =
    item.countrySlug ||
    countries.find(
      (c) =>
        c.slug === item.countrySlug ||
        c.name?.toLowerCase() === item.countrySlug?.toLowerCase() ||
        c.ar_name === item.countrySlug
    )?.slug;

  if (slug) {
    params.set("country_slug", slug);
  }

  const qs = params.toString();
  return qs ? `/language-institutes?${qs}` : "/language-institutes";
}
