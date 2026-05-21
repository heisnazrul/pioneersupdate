export const NAVBAR_TOP_LINKS = [
  { href: "/#language-institutes", label: "Language Institutes" },
  { href: "/#summer-programs", label: "Summer Programs" },
  { href: "/#online-courses", label: "Online Courses" },
  { href: "/#university-admissions", label: "University Admissions" },
  { href: "/#travel-tourism", label: "Travel & Tourism" },
  {
    href: "/#training-professional",
    label: "Training & Professional Courses",
    style: "hidden xl:flex",
  },
];

export const DESKTOP_TOP_NAV = NAVBAR_TOP_LINKS;

export const DESKTOP_MAIN_NAV = [
  { href: "/", label: "Home" },
  { href: "/#offers", label: "Offers" },
  { href: "/#about-us", label: "About Us" },
  { href: "/#contact-us", label: "Contact Us" },
  { href: "/#articles", label: "Articles" },
];

export const LANGUAGES = [
  { code: "en", label: "English", flag: "/assets/flags/gb.svg" },
  { code: "ar", label: "Arabic", flag: "/assets/flags/sa.svg" },
];

export const DESKTOP_CURRENCIES = [
  {
    code: "SAR",
    label: "Saudi Riyal",
    iconLight: "/assets/sar.svg",
    iconDark: "/assets/sar-black.svg",
  },
  {
    code: "GBP",
    label: "British Pound",
    iconLight: "/assets/gbp.svg",
    iconDark: "/assets/gbp-black.svg",
  },
];

export const MOBILE_CURRENCIES = [
  { code: "SAR", label: "Saudi Riyal", icon: "/assets/sar.svg" },
  { code: "GBP", label: "British Pound", icon: "/assets/gbp.svg" },
];

export const MOBILE_PROMO_TEXT =
  "Our exclusive offers guarantee the best prices and services. If you find a better price or service, we'll match it.";

export const MOBILE_QUICK_LINKS = [
  { label: "Home", icon: "home", href: "/" },
  { label: "Contact Us", icon: "phone", href: "/#contact-us" },
  { label: "FAQ", icon: "question", href: "/#faq" },
];

export const MOBILE_DRAWER_LINKS = [
  { label: "Offers", href: "/#offers" },
  { label: "About Us", href: "/#about-us" },
  { label: "Team", href: "/#team" },
  { label: "Blog", href: "/#blog" },
  { label: "Language Institutes", href: "/#language-institutes" },
  { label: "Summer Programs", href: "/#summer-programs" },
  { label: "University Admissions", href: "/#university-admissions" },
];

export const MOBILE_SOCIAL_LINKS = [
  { platform: "linkedin", href: "#" },
  { platform: "facebook", href: "#" },
  { platform: "instagram", href: "#" },
  { platform: "twitter", href: "#" },
];

export const MOBILE_LEGAL_LINKS = [
  { label: "Privacy Policy", href: "/#privacy" },
  { label: "Terms & Conditions", href: "/#terms" },
];
