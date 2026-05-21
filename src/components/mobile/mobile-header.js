"use client";
/* eslint-disable @next/next/no-img-element */

import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBars,
  faChevronDown,
  faCircleQuestion,
  faHouse,
  faPhone,
  faSterlingSign,
  faXmark,
} from "@fortawesome/free-solid-svg-icons";
import {
  faFacebookF,
  faInstagram,
  faLinkedinIn,
  faWhatsapp,
  faXTwitter,
} from "@fortawesome/free-brands-svg-icons";

import {
  MOBILE_CURRENCIES,
  MOBILE_DRAWER_LINKS,
  MOBILE_LEGAL_LINKS,
  MOBILE_PROMO_TEXT,
  MOBILE_QUICK_LINKS,
  MOBILE_SOCIAL_LINKS,
  NAVBAR_TOP_LINKS,
} from "@/lib/site-nav";

function useDismiss(ref, onClose) {
  useEffect(() => {
    function onClick(event) {
      if (!ref.current) return;
      if (!ref.current.contains(event.target)) onClose?.();
    }

    function onEsc(event) {
      if (event.key === "Escape") onClose?.();
    }

    document.addEventListener("mousedown", onClick);
    document.addEventListener("keydown", onEsc);

    return () => {
      document.removeEventListener("mousedown", onClick);
      document.removeEventListener("keydown", onEsc);
    };
  }, [ref, onClose]);
}

export default function MobileHeader() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [languageOpen, setLanguageOpen] = useState(false);
  const [currencyOpen, setCurrencyOpen] = useState(false);
  const [language, setLanguage] = useState("en");
  const [currency, setCurrency] = useState("SAR");
  const [activeChip, setActiveChip] = useState(NAVBAR_TOP_LINKS[0]?.href || "/");
  const [thumb, setThumb] = useState({ width: 0, left: 0 });

  const chipsRef = useRef(null);
  const chipRefs = useRef({});
  const trackRef = useRef(null);
  const panelRef = useRef(null);

  useDismiss(panelRef, () => setMenuOpen(false));

  const chips = useMemo(() => NAVBAR_TOP_LINKS.slice(0, 4), []);

  const languages = useMemo(
    () => [
      { code: "en", label: "English", flag: "/assets/flags/gb.svg" },
      { code: "ar", label: "Arabic", flag: "/assets/flags/sa.svg" },
    ],
    []
  );

  const currencies = useMemo(() => MOBILE_CURRENCIES, []);

  useEffect(() => {
    document.body.style.overflow = menuOpen ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [menuOpen]);

  useEffect(() => {
    const container = chipsRef.current;
    if (!container) return;

    const updateThumb = (customLeft) => {
      const track = trackRef.current;
      if (!track) return;

      const trackWidth = track.clientWidth;
      const scrollWidth = container.scrollWidth;
      const clientWidth = container.clientWidth;
      const scrollLeft = customLeft ?? container.scrollLeft;
      const thumbWidth = Math.max(
        32,
        Math.min(80, (clientWidth / scrollWidth) * trackWidth)
      );
      const maxLeft = Math.max(0, trackWidth - thumbWidth);
      const left =
        scrollWidth > clientWidth
          ? (scrollLeft / (scrollWidth - clientWidth)) * maxLeft
          : 0;

      setThumb({ width: thumbWidth, left });
    };

    updateThumb();
    const onScroll = () => updateThumb();
    container.addEventListener("scroll", onScroll, { passive: true });

    const resizeObserver = new ResizeObserver(() => updateThumb());
    resizeObserver.observe(container);

    return () => {
      container.removeEventListener("scroll", onScroll);
      resizeObserver.disconnect();
    };
  }, []);

  const activateChip = (href) => {
    setActiveChip(href);

    const container = chipsRef.current;
    const target = chipRefs.current[href];
    if (!container || !target) return;

    const containerWidth = container.clientWidth;
    const targetCenter = target.offsetLeft + target.offsetWidth / 2;
    const nextLeft =
      href === (chips[0]?.href || "/")
        ? 0
        : Math.max(0, targetCenter - containerWidth / 2);

    container.scrollTo({
      left: nextLeft,
      behavior: "smooth",
    });
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

  const activeLanguage =
    languages.find((item) => item.code === language) || languages[0];
  const activeCurrency =
    currencies.find((item) => item.code === currency) || currencies[0];

  return (
    <>
      <header className="block w-full">
        <div className="flex items-center justify-center bg-[#1E6FBC] px-4 py-3 text-center text-sm leading-5 text-white">
          <div>
            <img
              src="/assets/icons/dscount-top.svg"
              alt="discount icon"
              className="h-10 w-10"
              loading="lazy"
            />
          </div>
          <div className="px-4">{MOBILE_PROMO_TEXT}</div>
        </div>

        <div className="bg-[#135FAE] text-white">
          <div className="flex items-center justify-between px-3 py-3">
            <button
              type="button"
              aria-label="Open menu"
              onClick={() => setMenuOpen(true)}
              className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 ring-4 ring-white/10 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              <FontAwesomeIcon icon={faBars} className="h-5 w-5" />
            </button>

            <Link href="/" aria-label="Home" className="flex flex-col items-center">
              <img
                src="/assets/logo/logo-white.png"
                alt="CourseSat"
                className="h-12 w-auto"
                loading="lazy"
              />
            </Link>

            <Link
              href="https://wa.me/0000000000"
              aria-label="Contact via WhatsApp"
              className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-[#25D366] ring-4 ring-white/10 hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              <FontAwesomeIcon
                icon={faWhatsapp}
                className="h-10 w-10 text-2xl text-white"
              />
            </Link>
          </div>

          <div className="px-2 pb-5">
            <div
              className="chips-scroll relative flex gap-4 overflow-x-auto pb-3"
              ref={chipsRef}
            >
              {chips.map((chip) => (
                <Chip
                  key={chip.href}
                  href={chip.href}
                  active={activeChip === chip.href}
                  onClick={() => activateChip(chip.href)}
                  innerRef={(element) => {
                    if (element) chipRefs.current[chip.href] = element;
                  }}
                >
                  {chip.label}
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
                  style={{
                    width: `${thumb.width}px`,
                    transform: `translateX(${thumb.left}px)`,
                  }}
                />
              </div>
            </div>
          </div>
        </div>

        {menuOpen && (
          <div className="fixed inset-0 z-50 bg-[#135FAE] text-white">
            <div className="flex justify-end px-3 py-3">
              <button
                type="button"
                aria-label="Close menu"
                onClick={() => setMenuOpen(false)}
                className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-white/20 ring-4 ring-white/10 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
              >
                <FontAwesomeIcon icon={faXmark} className="h-5 w-5" />
              </button>
            </div>

            <div
              ref={panelRef}
              className="h-[calc(100vh-56px)] overflow-y-auto px-3 pb-6"
            >
              <div className="mb-4 grid grid-cols-3 gap-3">
                {MOBILE_QUICK_LINKS.map((item) => (
                  <Tile
                    key={item.href}
                    href={item.href}
                    label={item.label}
                    icon={quickIconMap[item.icon] || faCircleQuestion}
                  />
                ))}
              </div>

              <div className="space-y-2">
                {MOBILE_DRAWER_LINKS.map((item) => (
                  <MenuButton key={item.href} href={item.href}>
                    {item.label}
                  </MenuButton>
                ))}
              </div>

              <div className="mt-4 grid grid-cols-2 gap-3">
                <Dropdown
                  label={activeLanguage?.label || "English"}
                  icon={
                    <img
                      src={activeLanguage?.flag || "/assets/flags/gb.svg"}
                      alt=""
                      className="h-4 w-6 rounded-[2px] object-cover ring-1 ring-white/20"
                    />
                  }
                  open={languageOpen}
                  setOpen={(nextValue) => {
                    setLanguageOpen(nextValue);
                    if (nextValue) setCurrencyOpen(false);
                  }}
                >
                  {languages.map((item) => (
                    <DropdownOption
                      key={item.code}
                      onClick={() => {
                        setLanguage(item.code);
                        setLanguageOpen(false);
                      }}
                      selected={language === item.code}
                      icon={
                        <img
                          src={item.flag}
                          alt=""
                          className="h-4 w-6 rounded-[2px] object-cover"
                        />
                      }
                      label={item.label}
                    />
                  ))}
                </Dropdown>

                <Dropdown
                  label={activeCurrency?.label || "Saudi Riyal"}
                  icon={
                    activeCurrency?.icon ? (
                      <img
                        src={activeCurrency.icon}
                        alt=""
                        className="h-4 w-4"
                      />
                    ) : (
                      <FontAwesomeIcon icon={faSterlingSign} className="h-4 w-4" />
                    )
                  }
                  open={currencyOpen}
                  setOpen={(nextValue) => {
                    setCurrencyOpen(nextValue);
                    if (nextValue) setLanguageOpen(false);
                  }}
                >
                  {currencies.map((item) => (
                    <DropdownOption
                      key={item.code}
                      onClick={() => {
                        setCurrency(item.code);
                        setCurrencyOpen(false);
                      }}
                      selected={currency === item.code}
                      icon={
                        item.icon ? (
                          <img src={item.icon} alt="" className="h-4 w-4" />
                        ) : (
                          <FontAwesomeIcon icon={faSterlingSign} className="h-4 w-4" />
                        )
                      }
                      label={item.label}
                    />
                  ))}
                </Dropdown>
              </div>

              <div className="mx-auto mt-5 flex items-center justify-center gap-3">
                {MOBILE_SOCIAL_LINKS.map((item) => (
                  <Social
                    key={item.platform}
                    href={item.href}
                    icon={socialIconMap[item.platform] || faLinkedinIn}
                    label={item.platform}
                  />
                ))}
              </div>

              <div className="mt-4 flex items-center justify-center gap-4 text-[12px] opacity-90">
                {MOBILE_LEGAL_LINKS.map((item, index) => (
                  <span key={item.href} className="flex items-center gap-4">
                    <Link href={item.href}>{item.label}</Link>
                    {index < MOBILE_LEGAL_LINKS.length - 1 ? <span>•</span> : null}
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

function Chip({ href, children, active, onClick, innerRef }) {
  return (
    <Link
      href={href}
      onClick={onClick}
      ref={innerRef}
      className={`whitespace-nowrap rounded-full px-3 py-1.5 text-[13px] font-normal focus:outline-none ${
        active ? "bg-white/15 text-white" : "bg-transparent text-white"
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
      <span className="w-full">{children}</span>
    </Link>
  );
}

function Dropdown({ label, icon, open, setOpen, children }) {
  return (
    <div className="relative z-[60]">
      <button
        type="button"
        onClick={() => setOpen(!open)}
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
        <div className="absolute left-0 right-0 z-[9999] mt-1 overflow-hidden rounded-lg bg-white text-slate-800 shadow-lg ring-1 ring-black/10">
          {children}
        </div>
      )}
    </div>
  );
}

function DropdownOption({ onClick, icon, label, selected = false }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="flex w-full items-center gap-2 px-3 py-2 text-sm hover:bg-slate-50"
      role="option"
      aria-selected={selected}
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
