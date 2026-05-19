// components/MobileHeader.js
"use client";

import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBars,
  faXmark,
  faHouse,
  faPhone,
  faCircleQuestion,
  faChevronDown,
  faSterlingSign,
} from "@fortawesome/free-solid-svg-icons";
import {
  faWhatsapp,
  faLinkedinIn,
  faFacebookF,
  faInstagram,
  faXTwitter,
} from "@fortawesome/free-brands-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

function useDismiss(ref, onClose) {
  useEffect(() => {
    function onClick(e) {
      if (!ref.current) return;
      if (!ref.current.contains(e.target)) onClose?.();
    }
    function onEsc(e) {
      if (e.key === "Escape") onClose?.();
    }
    document.addEventListener("mousedown", onClick);
    document.addEventListener("keydown", onEsc);
    return () => {
      document.removeEventListener("mousedown", onClick);
      document.removeEventListener("keydown", onEsc);
    };
  }, [ref, onClose]);
}

const CHIPS = [
  { href: "/language-institutes", label: "Language Institutes" },
  { href: "/summer-programs", label: "Summer Programs" },
  { href: "/online-courses", label: "Online Courses" },
  { href: "/university-admissions", label: "University Admissions" },
];

export default function MobileHeader() {
  const { data } = useApi("/courseenglish/home/branding");
  const branding = data?.branding ?? {};
  const mobile = branding.mobile ?? {};
  const header = branding.header ?? {};
  const { language: lang, setLanguage: setLang, currency: cur, setCurrency: setCur } =
    useCourseEnglishSettings();
  const locale = getCourseEnglishMessages(lang);
  const layouts = locale?.layouts ?? {};
  const navbar = layouts?.navbar ?? {};
  const mobileNav = layouts?.mobile_nav ?? {};
  const getLabel = (itemOrLabel) => {
    const item = typeof itemOrLabel === "string" ? { label: itemOrLabel } : itemOrLabel;
    return item?.label || item?.name || "";
  };
  const promoText =
    mobileNav?.promo ||
    "Our exclusive offers guarantee the best prices and services. If you find a better price or service, we’ll match it.";
  const promoIcon = mobile.promo?.icon || "/assets/icons/dscount-top.svg";
  const logoSrc = mobile.logo || header.logo?.main || "/logo-white.png";

  const chips = useMemo(
    () => [
      { href: "/language-institutes", label: navbar?.top_nav?.language_institutes || "Language Institutes" },
      { href: "/summer-programs", label: navbar?.top_nav?.summer_programs || "Summer Programs" },
      { href: "/online-courses", label: navbar?.top_nav?.online_courses || "Online Courses" },
      { href: "/university-admissions", label: navbar?.top_nav?.university_admissions || "University Admissions" },
    ],
    [navbar]
  );

  const quickLinks = useMemo(
    () => [
      { label: mobileNav?.quick_links?.home || "Home", icon: "home", url: "/" },
      { label: mobileNav?.quick_links?.contact || "Contact Us", icon: "phone", url: "/contact-us" },
      { label: mobileNav?.quick_links?.faq || "FAQ", icon: "question", url: "/articles" },
    ],
    [mobileNav]
  );

  const drawerLinks = useMemo(
    () => [
      { label: navbar?.main_nav?.offers || "Offers", url: "/offers" },
      { label: navbar?.main_nav?.about_us || "About Us", url: "/about-us" },
      { label: mobileNav?.drawer?.team || "Team", url: "/team" },
      { label: layouts?.footer?.blog || "Blog", url: "/articles" },
      { label: navbar?.top_nav?.language_institutes || "Language Institutes", url: "/language-institutes" },
      { label: navbar?.top_nav?.summer_programs || "Summer Programs", url: "/summer-programs" },
      { label: navbar?.top_nav?.university_admissions || "University Admissions", url: "/university-admissions" },
    ],
    [layouts, mobileNav, navbar]
  );

  const socialLinks = useMemo(
    () =>
      mobile.drawer_social || [
        { platform: "linkedin", url: "#" },
        { platform: "facebook", url: "#" },
        { platform: "instagram", url: "#" },
        { platform: "twitter", url: "#" },
      ],
    [mobile.drawer_social]
  );

  const legalLinks = useMemo(
    () => [
      { label: mobileNav?.drawer?.legal?.privacy || "Privacy Policy", url: "/privacy" },
      { label: mobileNav?.drawer?.legal?.terms || "Terms & Conditions", url: "/terms" },
    ],
    [mobileNav]
  );

  const defaultLanguages = useMemo(
    () => [
      { code: "en", label: navbar?.dropdowns?.language?.en || "English", flag: "/assets/flags/gb.svg" },
      { code: "ar", label: navbar?.dropdowns?.language?.ar || "Arabic", flag: "/assets/flags/sa.svg" },
    ],
    [navbar]
  );
  const languages = useMemo(
    () => defaultLanguages,
    [defaultLanguages]
  );

  const defaultCurrencies = useMemo(
    () => [
      { code: "SAR", label: navbar?.dropdowns?.currency?.sar?.name || "Saudi Riyal", icon: "/assets/sar.svg" },
      { code: "GBP", label: navbar?.dropdowns?.currency?.gbp?.name || "British Pound", icon: "/assets/gbp.svg" },
    ],
    [navbar]
  );
  const currencies = useMemo(
    () => defaultCurrencies,
    [defaultCurrencies]
  );

  const [open, setOpen] = useState(false);
  const [openLang, setOpenLang] = useState(false);
  const [openCur, setOpenCur] = useState(false);
  const [activeChip, setActiveChip] = useState(chips[0]?.href || "/");
  const chipsRef = useRef(null);
  const chipRefs = useRef({});
  const [thumb, setThumb] = useState({ width: 0, left: 0 });

  const trackRef = useRef(null);
  const computeThumb = (customLeft) => {
    const track = trackRef.current;
    const container = chipsRef.current;
    if (!track || !container) return;
    const trackWidth = track.clientWidth;
    const scrollWidth = container.scrollWidth;
    const clientWidth = container.clientWidth;
    const scrollLeft = customLeft ?? container.scrollLeft;
    const thumbWidth = Math.max(32, Math.min(80, (clientWidth / scrollWidth) * trackWidth));
    const maxLeft = Math.max(0, trackWidth - thumbWidth);
    const left =
      scrollWidth > clientWidth
        ? (scrollLeft / (scrollWidth - clientWidth)) * maxLeft
        : 0;
    setThumb({ width: thumbWidth, left });
  };

  const panelRef = useRef(null);
  useDismiss(panelRef, () => setOpen(false));

  useEffect(() => {
    if (languages.length && !languages.find((item) => item.code === lang)) {
      setLang(languages[0].code);
    }
  }, [languages, lang]);

  useEffect(() => {
    if (currencies.length && !currencies.find((item) => item.code === cur)) {
      setCur(currencies[0].code);
    }
  }, [currencies, cur]);

  useEffect(() => {
    if (chips.length && !chips.find((chip) => chip.href === activeChip)) {
      setActiveChip(chips[0].href);
    }
  }, [chips, activeChip]);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [open]);

  useEffect(() => {
    const el = chipsRef.current;
    if (!el) return;

    const updateThumb = (left) => computeThumb(left);

    updateThumb();
    const onScroll = () => updateThumb();
    el.addEventListener("scroll", onScroll, { passive: true });
    const ro = new ResizeObserver(() => updateThumb());
    ro.observe(el);
    return () => {
      el.removeEventListener("scroll", onScroll);
      ro.disconnect();
    };
  }, []);

  const activateChip = (href) => {
    setActiveChip(href);
    const container = chipsRef.current;
    const target = chipRefs.current[href];
    if (!container || !target) return;
    const containerWidth = container.clientWidth;
    const targetCenter = target.offsetLeft + target.offsetWidth / 2;
    const scrollTo =
      href === (chips[0]?.href || "/") ? 0 : targetCenter - containerWidth / 2;
    const nextLeft = Math.max(0, scrollTo);
    container.scrollTo({
      left: nextLeft,
      behavior: "smooth",
    });
    requestAnimationFrame(() => computeThumb(nextLeft));
  };

  const quickIconMap = {
    home: faHouse,
    phone: faPhone,
    question: faCircleQuestion,
    faq: faCircleQuestion,
  };

  const socialIconMap = {
    linkedin: faLinkedinIn,
    facebook: faFacebookF,
    instagram: faInstagram,
    twitter: faXTwitter,
  };

  return (
    <>
      <header className="block md:hidden w-full">
        {/* Promo bar */}
        <div className="flex justify-center items-center px-4 py-3  bg-[#1E6FBC] text-white text-sm leading-5">
          <div>
            <img
              src={promoIcon}
              alt="discount icon"
              className="h-10 w-10"
              loading="lazy"
            />
          </div>
          <div className="px-4  text-center">
            {promoText}
          </div>
        </div>

        {/* Main blue bar */}
        <div className="bg-[#135FAE] text-white">
          <div className="px-3 py-3 flex items-center justify-between">
            {/* Hamburger */}
            <button
              type="button"
              aria-label="Open menu"
              onClick={() => setOpen(true)}
              className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 ring-4 ring-white/10 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              <FontAwesomeIcon icon={faBars} className="h-5 w-5" />
            </button>

            {/* Center logo */}
            <Link href="/" aria-label="Home" className="flex flex-col items-center">
              <img
                src={logoSrc}
                alt="Course"
                className="h-12 w-auto"
                loading="lazy"
              />
            </Link>

            {/* WhatsApp */}
            <Link
              href="https://wa.me/0000000000"
              aria-label="Contact via WhatsApp"
              className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20  text-[#25D366] ring-4 ring-white/10 hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              <FontAwesomeIcon icon={faWhatsapp} className="h-10 w-10 text-2xl text-white" />
            </Link>
          </div>

          {/* Category chips */}
          <div className="px-2 pb-5">
            <div
              className="chips-scroll relative flex gap-4 overflow-x-auto  pb-3"
              ref={chipsRef}
            >
              {chips.map((chip) => (
                <Chip
                  key={chip.href}
                  href={chip.href}
                  active={activeChip === chip.href}
                  onClick={() => activateChip(chip.href)}
                  innerRef={(el) => {
                    if (el) chipRefs.current[chip.href] = el;
                  }}
                >
                  {getLabel(chip)}
                </Chip>
              ))}
            </div>
            <div className="relative mt-2 w-full">
              <div
                ref={trackRef}
                className="pointer-events-none h-2.5 w-full rounded-full bg-[#2a7dc6]"
              >
                <div
                  className="absolute h-2.5 rounded-full bg-white shadow-[0_0_6px_rgba(255,255,255,0.6)]"
                  style={{ width: `${thumb.width}px`, transform: `translateX(${thumb.left}px)` }}
                />
              </div>
            </div>
          </div>
        </div>

        {/* Fullscreen drawer */}
        {open && (
          <div className="fixed inset-0 z-50 bg-[#135FAE] text-white">
            {/* Close button row */}
            <div className="px-3 py-3 flex justify-end">
              <button
                type="button"
                aria-label="Close menu"
                onClick={() => setOpen(false)}
                className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-white/20 ring-4 ring-white/10 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
              >
                <FontAwesomeIcon icon={faXmark} className="h-5 w-5" />
              </button>
            </div>

            {/* Drawer content */}
            <div
              ref={panelRef}
              className="px-3 pb-6 overflow-y-auto h-[calc(100vh-56px)]"
            >
              {/* Quick tiles */}
              <div className="grid grid-cols-3 gap-3 mb-4">
                {quickLinks.map((item) => (
                  <Tile
                    key={item.url || item.href || item.label}
                    href={item.url || item.href || "#"}
                    label={getLabel(item)}
                    icon={quickIconMap[item.icon] || faCircleQuestion}
                  />
                ))}
              </div>

              {/* Menu list */}
              <div className="space-y-2">
                {drawerLinks.map((item) => (
                  <MenuButton key={item.url || item.href || item.label} href={item.url || item.href || "#"}>
                    {getLabel(item)}
                  </MenuButton>
                ))}
              </div>

              {/* Language & Currency selectors */}
              <div className="mt-4 grid grid-cols-2 gap-3">
                {/* Language */}
                <Dropdown
                  label={getLabel((languages.find((item) => item.code === lang) || languages[0])?.label || "English")}
                  icon={
                    <img
                      src={(languages.find((item) => item.code === lang) || languages[0])?.flag || "/assets/flags/gb.svg"}
                      alt=""
                      className="h-4 w-6 rounded-[2px] object-cover ring-1 ring-white/20"
                    />
                  }
                  open={openLang}
                  setOpen={(v) => {
                    setOpenLang(v);
                    if (v) setOpenCur(false);
                  }}
                >
                  {languages.map((item) => (
                    <DropdownOption
                      key={item.code}
                      onClick={() => setLang(item.code)}
                      icon={<img src={item.flag} alt="" className="h-4 w-6 rounded-[2px] object-cover" />}
                      label={getLabel(item)}
                    />
                  ))}
                </Dropdown>

                {/* Currency */}
                <Dropdown
                  label={(currencies.find((item) => item.code === cur) || currencies[0])?.label || "SAR"}
                  icon={
                    (currencies.find((item) => item.code === cur) || currencies[0])?.icon ? (
                      <img
                        src={(currencies.find((item) => item.code === cur) || currencies[0])?.icon}
                        alt=""
                        className="h-4 w-4"
                      />
                    ) : (
                      <FontAwesomeIcon icon={faSterlingSign} className="h-4 w-4" />
                    )
                  }
                  open={openCur}
                  setOpen={(v) => {
                    setOpenCur(v);
                    if (v) setOpenLang(false);
                  }}
                >
                  {currencies.map((item) => (
                    <DropdownOption
                      key={item.code}
                      onClick={() => setCur(item.code)}
                      icon={
                        item.icon ? (
                          <img src={item.icon} alt="" className="h-4 w-4" />
                        ) : (
                          <FontAwesomeIcon icon={faSterlingSign} className="h-4 w-4" />
                        )
                      }
                      label={item.label || item.code}
                    />
                  ))}
                </Dropdown>
              </div>

              {/* Social icons */}
              <div className="mt-5 flex items-center gap-3 mx-auto justify-center">
                {socialLinks.map((item) => (
                  <Social
                    key={item.platform}
                    href={item.url || "#"}
                    icon={socialIconMap[item.platform] || faLinkedinIn}
                    label={item.platform}
                  />
                ))}
              </div>

              {/* Footer links */}
              <div className="mt-4 flex items-center justify-center gap-4 text-[12px] opacity-90">
                {legalLinks.map((item, idx) => (
                  <span key={item.url || item.label} className="flex items-center gap-4">
                    <Link href={item.url || "#"}>{getLabel(item)}</Link>
                    {idx < legalLinks.length - 1 ? <span>•</span> : null}
                  </span>
                ))}
              </div>
            </div>
          </div>
        )}
      </header>
      <style jsx>{`
      .chips-scroll {
        scrollbar-width: none;
      }
      .chips-scroll::-webkit-scrollbar {
        display: none;
      }
    `}</style>
    </>
  );
}

/* Small reusable pieces */

function Chip({ href, children, active, onClick, innerRef }) {
  return (
    <Link
      href={href}
      onClick={onClick}
      ref={innerRef}
      className={`whitespace-nowrap rounded-full px-3 py-1.5 text-[13px] font-normal focus:outline-none ${active ? "bg-white/15 text-white " : "bg-transparent text-white"
        }`}
    >
      {children}
    </Link>
  );
}

function Tile({ href, label, icon }) {
  return (
    <Link
      href={href}
      className="flex flex-col items-center justify-center gap-1 rounded-xl bg-white/10 px-3 py-4 text-center ring-1 ring-white/15 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
    >
      <FontAwesomeIcon icon={icon} className="h-5 w-5" />
      <span className="text-[12px]">{label}</span>
    </Link>
  );
}

function MenuButton({ href, children }) {
  return (
    <Link
      href={href}
      className="flex items-center rounded-xl bg-white/10 px-4 py-3 text-[15px] hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
    >
      <span className=" w-full">{children}</span>
    </Link>
  );
}

function Dropdown({ label, icon, open, setOpen, children }) {
  return (
    <div className="relative z-[60]">
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        className="flex w-full items-center justify-between rounded-lg bg-white/10 px-3 py-3 ring-1 ring-white/15 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
        aria-haspopup="listbox"
        aria-expanded={open}
        dir="ltr"
      >
        <span className="inline-flex items-center gap-2 text-sm">
          {icon}
          {label}
        </span>
        <FontAwesomeIcon
          icon={faChevronDown}
          className={`h-4 w-4 transition-transform ${open ? "rotate-180" : ""}`}
        />
      </button>
      {open && (
        <div className="absolute left-0 right-0 mt-1 overflow-hidden rounded-lg bg-white text-slate-800 shadow-lg ring-1 ring-black/10 z-[9999]">
          {children}
        </div>
      )}
    </div>
  );
}

function DropdownOption({ onClick, icon, label }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="flex w-full items-center gap-2 px-3 py-2 text-sm hover:bg-slate-50"
      role="option"
    >
      {icon}
      <span>{label}</span>
    </button>
  );
}

function Social({ href, icon, label }) {
  return (
    <Link
      href={href}
      aria-label={label}
      className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/15 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
    >
      <FontAwesomeIcon icon={icon} className="h-4 w-4" />
    </Link>
  );
}
