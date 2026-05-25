"use client";
/* eslint-disable @next/next/no-img-element */

import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { usePathname } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown } from "@fortawesome/free-solid-svg-icons";
import { faUser } from "@fortawesome/free-regular-svg-icons";

import {
  getDesktopCurrencies,
  getDesktopMainNav,
  getDesktopTopNav,
  getLanguages,
} from "@/lib/site-nav";
import { useLocale } from "@/components/providers/locale-provider";

export default function DesktopHeader() {
  const pathname = usePathname();
  const { direction, language, messages, setLanguage, t } = useLocale();
  const isRtl = direction === "rtl";
  const [currency, setCurrency] = useState("SAR");
  const [languageOpen, setLanguageOpen] = useState(false);
  const [currencyOpen, setCurrencyOpen] = useState(false);
  const languageRef = useRef(null);
  const currencyRef = useRef(null);

  const topNav = useMemo(() => getDesktopTopNav(messages), [messages]);
  const mainNav = useMemo(() => getDesktopMainNav(messages), [messages]);
  const languages = useMemo(() => getLanguages(messages), [messages]);
  const currencies = useMemo(() => getDesktopCurrencies(messages), [messages]);

  useEffect(() => {
    function handleClick(event) {
      if (languageRef.current && !languageRef.current.contains(event.target)) {
        setLanguageOpen(false);
      }

      if (currencyRef.current && !currencyRef.current.contains(event.target)) {
        setCurrencyOpen(false);
      }
    }

    document.addEventListener("mousedown", handleClick);
    return () => document.removeEventListener("mousedown", handleClick);
  }, []);

  const activeLanguage =
    languages.find((item) => item.code === language) || languages[0];
  const activeCurrency =
    currencies.find((item) => item.code === currency) || currencies[0];

  const activeIndex = topNav.findIndex((link) =>
    link.href === "/" ? pathname === "/" : pathname?.startsWith(link.href)
  );

  const isTopActive = (index) => (activeIndex === -1 ? index === 0 : index === activeIndex);
  const isMainActive = (item) => {
    if (item.key === "offers") return false;
    if (item.href === "/") return pathname === "/";
    return pathname?.startsWith(item.href);
  };

  return (
    <header className="relative z-[80] w-full" dir={direction}>
      <div className="w-full overflow-visible bg-[#135FAE] text-white">
        <div className="overflow-visible px-4 md:px-10 xl:px-20 2xl:px-40">
          <div
            className="flex h-[56px] items-stretch justify-between"
          >
            <nav className="flex items-stretch gap-1.5 text-[14px]">
              {topNav.map((link, index) => (
                <Link
                  key={`${link.href}-${index}`}
                  href={link.href}
                  className={`flex items-center border-b-[4px] px-2 pt-[4px] transition ${link.style ?? ""
                    } ${isTopActive(index)
                      ? "border-white"
                      : "border-transparent text-white/90 hover:text-white"
                    }`}
                >
                  {link.label}
                </Link>
              ))}
            </nav>

            <div className="flex items-center self-center gap-3.5"
            >
              <div className="relative z-[90]" ref={languageRef}>
                <button
                  className="flex items-center gap-2 rounded px-2 py-1 opacity-90 transition hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                  aria-haspopup="listbox"
                  aria-expanded={languageOpen}
                  onClick={() => setLanguageOpen((value) => !value)}
                  type="button"
                  dir={direction}
                >
                  <img
                    src={activeLanguage?.flag || "/assets/flags/gb.svg"}
                    alt={activeLanguage?.label || t("layouts.common.search", "Language")}
                    className="h-[18px] w-[18px]"
                    loading="lazy"
                  />
                  <span className="font-normal">
                    {activeLanguage?.label || "English"}
                  </span>
                  <ChevronDown color="text-white" />
                </button>

                <div
                  className={`absolute z-[9999] mt-2 w-36 rounded-2xl bg-white text-slate-900 shadow-lg ring-1 ring-black/5 ${languageOpen ? "block" : "hidden"
                    } ${direction === "rtl" ? "left-0" : "right-0"}`}
                  role="listbox"
                  dir={direction}
                >
                  <div
                    className={`space-y-1 p-2 ${isRtl ? "text-right" : "text-left"
                      }`}
                  >
                    {languages.map((item) => (
                      <button
                        key={item.code}
                        className={`flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm transition ${language === item.code
                          ? "bg-slate-100 font-normal"
                          : "hover:bg-slate-50"
                          }`}
                        role="option"
                        aria-selected={language === item.code}
                        onClick={() => {
                          setLanguage(item.code);
                          setLanguageOpen(false);
                        }}
                      >
                        <img
                          src={item.flag}
                          alt={item.label}
                          className="h-[18px] w-[18px]"
                          loading="lazy"
                        />
                        <div className={isRtl ? "text-right" : "text-left"}>
                          <p className="text-slate-900">{item.label}</p>
                          <p className="text-xs text-slate-500">{item.code}</p>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              </div>

              <div className="relative z-[90]" ref={currencyRef}>
                <button
                  className="flex items-center gap-2 rounded px-2 py-1 opacity-90 transition hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                  aria-haspopup="listbox"
                  aria-expanded={currencyOpen}
                  onClick={() => setCurrencyOpen((value) => !value)}
                  type="button"
                  dir={direction}
                >
                  <img
                    src={activeCurrency?.iconLight || "/assets/sar.svg"}
                    alt={t("layouts.navbar.actions.compare", "Currency")}
                    className="h-[18px] w-[18px] brightness-0 invert"
                    loading="lazy"
                  />
                  <span className="font-normal text-white">
                    {activeCurrency?.label || currency}
                  </span>
                  <ChevronDown color="text-white" />
                </button>

                <div
                  className={`absolute z-[9999] mt-2 w-[11.5rem] rounded-2xl bg-white text-slate-900 shadow-lg ring-1 ring-black/5 ${currencyOpen ? "block" : "hidden"
                    } ${direction === "rtl" ? "left-0" : "right-0"}`}
                  role="listbox"
                  dir={direction}
                >
                  <div
                    className={`space-y-1 p-2 ${isRtl ? "text-right" : "text-left"
                      }`}
                  >
                    {currencies.map((item) => (
                      <button
                        key={item.code}
                        className={`flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm transition ${currency === item.code
                          ? "bg-slate-100 font-normal"
                          : "hover:bg-slate-50"
                          }`}
                        role="option"
                        aria-selected={currency === item.code}
                        onClick={() => {
                          setCurrency(item.code);
                          setCurrencyOpen(false);
                        }}
                      >
                        <span className="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-sm font-normal text-slate-900">
                          <img
                            src={item.iconDark || item.iconLight}
                            alt={item.label}
                            className="h-[18px] w-[18px]"
                            loading="lazy"
                          />
                        </span>
                        <div className={isRtl ? "text-right" : "text-left"}>
                          <p className="font-normal text-slate-900">{item.label}</p>
                          <p className="text-xs font-normal text-slate-500">
                            {item.code}
                          </p>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="w-full bg-white">
        <div className="px-4 md:px-10 xl:px-20  2xl:px-40">
          <div className="grid grid-cols-12 items-center gap-4 py-5">
            <div className="col-span-3 mt-[7px] flex items-center">
              <Link
                href="/"
                aria-label={t("layouts.navbar.main_nav.home", "Home")}
                className="flex items-center gap-3"
              >
                <img
                  src="/assets/logo/logo.png"
                  alt={t("pages.coursesat.shared.brand", "CourseSat")}
                  className="xl:h-[55px] 2xl:h-[75px] max-w-[230px] object-contain"
                  loading="lazy"
                />
              </Link>
            </div>

            <div className="col-span-6 mt-[2px]">
              <nav className="flex items-center justify-center gap-10 text-[14px] font-medium">
                {mainNav.map((item, index) => (
                  <Link
                    key={`${item.href}-${index}`}
                    href={item.href}
                    className={`transition ${isMainActive(item)
                      ? "!text-[#135FAE] font-bold"
                      : "text-slate-800 hover:text-[#135FAE]"
                      }`}
                  >
                    {item.label}
                  </Link>
                ))}
              </nav>
            </div>

            <div className="col-span-3 flex items-center justify-end gap-4" >
              <Link
                href="/#wishlist"
                aria-label={t("layouts.navbar.actions.wishlist", "Wishlist")}
                className="relative inline-flex h-[50px] w-[50px] items-center justify-center rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
              >
                <svg
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  fill="none"
                  aria-hidden="true"
                >
                  <path
                    d="M12.001 20.25S4.5 15.192 4.5 9.988c0-2.486 1.996-4.5 4.46-4.5 1.56 0 2.94.81 3.54 2.01.6-1.2 1.98-2.01 3.54-2.01 2.464 0 4.46 2.014 4.46 4.5 0 5.204-7.5 10.262-7.5 10.262z"
                    stroke="currentColor"
                    strokeWidth="1.6"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </Link>
              <Link
                href="/#compare"
                aria-label={t("layouts.navbar.actions.compare", "Compare")}
                className="relative inline-flex h-[50px] w-[50px] items-center justify-center rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
              >
                <svg
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  fill="none"
                  aria-hidden="true"
                >
                  <path
                    d="M7 7h10m0 0-3-3m3 3-3 3M17 17H7m0 0 3-3m-3 3 3 3"
                    stroke="currentColor"
                    strokeWidth="1.8"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </Link>



              <Link href="/#account" className="inline-flex items-center justify-center gap-2 rounded-xl bg-[#135FAE] px-10 py-3 text-[15px] !text-white shadow-sm transition hover:brightness-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40">
                <span className="text-white">
                  {t("layouts.navbar.actions.my_account", "My Account")}
                </span>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <div className="h-px w-full bg-slate-200" />
    </header>
  );
}

function ChevronDown({ color = "text-slate-800" }) {
  return (
    <FontAwesomeIcon
      icon={faChevronDown}
      className={`text-[11px] opacity-90 ${color}`}
      aria-hidden="true"
    />
  );
}
