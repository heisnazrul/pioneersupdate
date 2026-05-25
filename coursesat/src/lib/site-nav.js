const TOP_NAV_ITEMS = [
  { href: "/language-institutes", key: "language_institutes", fallback: "Language Institutes" },
  { href: "/#summer-programs", key: "summer_programs", fallback: "Summer Programs" },
  { href: "/#online-courses", key: "online_courses", fallback: "Online Courses" },
  { href: "/#university-admissions", key: "university_admissions", fallback: "University Admissions" },
  { href: "/#travel-tourism", key: "travel_tourism", fallback: "Travel & Tourism" },
  {
    href: "/#training-professional",
    key: "training_professional",
    fallback: "Training & Professional Courses",
    style: "hidden xl:flex",
  },
];

const MAIN_NAV_ITEMS = [
  { href: "/", key: "home", fallback: "Home" },
  { href: "/", key: "offers", fallback: "Offers" },
  { href: "/about-us", key: "about_us", fallback: "About Us" },
  { href: "/contact-us", key: "contact_us", fallback: "Contact Us" },
  { href: "/articles", key: "articles", fallback: "Articles" },
];

export const MOBILE_SOCIAL_LINKS = [
  { platform: "linkedin", href: "#" },
  { platform: "facebook", href: "#" },
  { platform: "instagram", href: "#" },
  { platform: "twitter", href: "#" },
];

function getNavbar(locale) {
  return locale?.layouts?.navbar ?? {};
}

export function getDesktopTopNav(locale) {
  const topNav = getNavbar(locale)?.top_nav ?? {};

  return TOP_NAV_ITEMS.map((item) => ({
    href: item.href,
    label: topNav?.[item.key] || item.fallback,
    style: item.style,
  }));
}

export function getDesktopMainNav(locale) {
  const mainNav = getNavbar(locale)?.main_nav ?? {};

  return MAIN_NAV_ITEMS.map((item) => ({
    key: item.key,
    href: item.href,
    label: mainNav?.[item.key] || item.fallback,
  }));
}

export function getLanguages(locale) {
  const labels = getNavbar(locale)?.dropdowns?.language ?? {};

  return [
    { code: "en", label: labels?.en || "English", flag: "/assets/flags/gb.svg" },
    { code: "ar", label: labels?.ar || "Arabic", flag: "/assets/flags/sa.svg" },
  ];
}

export function getDesktopCurrencies(locale) {
  const currency = getNavbar(locale)?.dropdowns?.currency ?? {};

  return [
    {
      code: "SAR",
      label: currency?.sar?.name || "Saudi Riyal",
      iconLight: "/assets/sar.svg",
      iconDark: "/assets/sar-black.svg",
    },
    {
      code: "GBP",
      label: currency?.gbp?.name || "British Pound",
      iconLight: "/assets/gbp.svg",
      iconDark: "/assets/gbp-black.svg",
    },
  ];
}

export function getMobileCurrencies(locale) {
  const currency = getNavbar(locale)?.dropdowns?.currency ?? {};

  return [
    {
      code: "SAR",
      label: currency?.sar?.name || "Saudi Riyal",
      icon: "/assets/sar.svg",
    },
    {
      code: "GBP",
      label: currency?.gbp?.name || "British Pound",
      icon: "/assets/gbp.svg",
    },
  ];
}

export function getMobilePromoText(locale) {
  return (
    locale?.layouts?.mobile_nav?.promo ||
    "Our exclusive offers guarantee the best prices and services. If you find a better price or service, we'll match it."
  );
}

export function getMobileQuickLinks(locale) {
  const quickLinks = locale?.layouts?.mobile_nav?.quick_links ?? {};

  return [
    { label: quickLinks?.home || "Home", icon: "home", href: "/" },
    { label: quickLinks?.contact || "Contact Us", icon: "phone", href: "/contact-us" },
    { label: quickLinks?.faq || "FAQ", icon: "question", href: "/#faq" },
  ];
}

export function getMobileDrawerLinks(locale) {
  const mainNav = getNavbar(locale)?.main_nav ?? {};
  const topNav = getNavbar(locale)?.top_nav ?? {};
  const mobileNav = locale?.layouts?.mobile_nav ?? {};
  const footer = locale?.layouts?.footer ?? {};

  return [
    { label: mainNav?.about_us || "About Us", href: "/about-us" },
    { label: mobileNav?.drawer?.team || "Team", href: "/#team" },
    { label: footer?.blog || "Blog", href: "/articles" },
    {
      label: topNav?.language_institutes || "Language Institutes",
      href: "/language-institutes",
    },
    { label: topNav?.summer_programs || "Summer Programs", href: "/#summer-programs" },
    {
      label: topNav?.university_admissions || "University Admissions",
      href: "/#university-admissions",
    },
  ];
}

export function getMobileLegalLinks(locale) {
  const legal = locale?.layouts?.mobile_nav?.drawer?.legal ?? {};

  return [
    { label: legal?.privacy || "Privacy Policy", href: "/#privacy" },
    { label: legal?.terms || "Terms & Conditions", href: "/#terms" },
  ];
}
