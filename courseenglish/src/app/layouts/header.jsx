// components/DesktopHeader.js
"use client";

import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { usePathname } from "next/navigation";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages, formatCourseEnglishMessage } from "@/lib/courseenglishLocale";
import {
  clearCourseEnglishAuthSession,
  fetchCourseEnglishMe,
  getStoredAuthToken,
  isCourseEnglishRole,
  logoutCourseEnglish,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

export default function DesktopHeader() {
  const { data } = useApi("/courseenglish/home/branding");
  const branding = data?.branding ?? {};
  const header = branding.header ?? {};
  const [openLang, setOpenLang] = useState(false);
  const [openCur, setOpenCur] = useState(false);
  const { currency, setCurrency, language, setLanguage } = useCourseEnglishSettings();
  const { wishlistCount, compareCount } = useCourseEnglishInteractions();
  const pathname = usePathname();
  const currencyRef = useRef(null);
  const languageRef = useRef(null);
  const accountRef = useRef(null);
  const [authUser, setAuthUser] = useState(null);
  const [openAccount, setOpenAccount] = useState(false);
  const [loggingOut, setLoggingOut] = useState(false);
  const locale = getCourseEnglishMessages(language);
  const navbar = locale?.layouts?.navbar ?? {};
  const studentMenu = navbar?.student_menu ?? {};
  const navLinks = useMemo(
    () => [
      { href: "/language-institutes", label: navbar?.top_nav?.language_institutes || "Language Institutes" },
      { href: "/summer-programs", label: navbar?.top_nav?.summer_programs || "Summer Programs" },
      { href: "/online-courses", label: navbar?.top_nav?.online_courses || "Online Courses" },
      { href: "/university-admissions", label: navbar?.top_nav?.university_admissions || "University Admissions" },
      { href: "/travel-and-tourism", label: navbar?.top_nav?.travel_tourism || "Travel & Tourism" },
      {
        href: "/training-and-professional-courses",
        label: navbar?.top_nav?.training_professional || "Training & Professional Courses",
        style: "hidden xl:flex",
      },
    ],
    [navbar]
  );
  const safeNavLinks = useMemo(
    () => navLinks.filter((link) => link && link.href),
    [navLinks]
  );
  const mainNav = useMemo(
    () => [
      { href: "/", label: navbar?.main_nav?.home || "Home" },
      { href: "/offers", label: navbar?.main_nav?.offers || "Offers" },
      { href: "/about-us", label: navbar?.main_nav?.about_us || "About Us" },
      { href: "/contact-us", label: navbar?.main_nav?.contact_us || "Contact Us" },
      { href: "/articles", label: navbar?.main_nav?.articles || "Articles" },
    ],
    [navbar]
  );
  const safeMainNav = useMemo(
    () => mainNav.filter((item) => item && item.href),
    [mainNav]
  );

  const defaultCurrencies = useMemo(
    () => [
      {
        code: "SAR",
        label: "Saudi Riyal",
        symbol: "﷼",
        iconLight: "/assets/sar.svg",
        iconDark: "/assets/sar-black.svg",
      },
      {
        code: "GBP",
        label: "British Pound",
        symbol: "£",
        iconLight: "/assets/gbp.svg",
        iconDark: "/assets/gbp-black.svg",
      },
    ],
    []
  );

  const currencies = useMemo(() => {
    return defaultCurrencies.map((item) => ({
      ...item,
      label:
        item.code === "GBP"
          ? navbar?.dropdowns?.currency?.gbp?.name || item.label
          : navbar?.dropdowns?.currency?.sar?.name || item.label,
      iconLight:
        item.iconLight ||
        item.icon ||
        (item.code === "GBP" ? "/assets/gbp.svg" : "/assets/sar.svg"),
      iconDark:
        item.iconDark ||
        (item.code === "GBP" ? "/assets/gbp-black.svg" : "/assets/sar-black.svg"),
    }));
  }, [defaultCurrencies, navbar]);

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

  useEffect(() => {
    if (currencies.length && !currencies.find((item) => item.code === currency)) {
      setCurrency(currencies[0].code);
    }
    if (languages.length && !languages.find((item) => item.code === language)) {
      setLanguage(languages[0].code);
    }
  }, [currencies, languages, currency, language]);

  const activeLanguage = languages.find((item) => item.code === language) || languages[0];
  const activeCurrency = currencies.find((item) => item.code === currency) || currencies[0];
  const logoSrc = header?.logo?.main || "/logo.png";
  const isAuthenticated = Boolean(authUser);
  const firstName = (authUser?.name || "").trim().split(/\s+/)[0] || (language === "ar" ? "أحمد" : "Ahmed");

  const getLabel = (item) => item?.label || item?.name || "";
  const accountLabel = useMemo(() => {
    return navbar?.actions?.my_account || (language === "ar" ? "حسابي" : "My Account");
  }, [navbar, language]);

  const activeIndex = safeNavLinks.findIndex((link) => pathname?.startsWith(link.href));
  const isActive = (index) => (activeIndex === -1 ? index === 0 : index === activeIndex);
  const isMainActive = (href) => {
    if (href === "/") return pathname === "/";
    return pathname?.startsWith(href);
  };

  // close dropdowns on outside click
  useEffect(() => {
    function handleClick(event) {
      if (currencyRef.current && !currencyRef.current.contains(event.target)) {
        setOpenCur(false);
      }
      if (languageRef.current && !languageRef.current.contains(event.target)) {
        setOpenLang(false);
      }
      if (accountRef.current && !accountRef.current.contains(event.target)) {
        setOpenAccount(false);
      }
    }
    document.addEventListener("mousedown", handleClick);
    return () => document.removeEventListener("mousedown", handleClick);
  }, []);

  useEffect(() => {
    let ignore = false;
    async function syncAuth() {
      try {
        const token = getStoredAuthToken();
        if (!token) {
          if (!ignore) setAuthUser(null);
          return;
        }

        const json = await fetchCourseEnglishMe(token);

        if (!isCourseEnglishRole(json?.user?.role)) {
          clearCourseEnglishAuthSession();
          if (!ignore) setAuthUser(null);
          return;
        }

        if (!ignore) {
          setAuthUser(json?.user || null);
          saveCourseEnglishAuthSession(json);
        }
      } catch {
        clearCourseEnglishAuthSession();
        if (!ignore) setAuthUser(null);
      }
    }
    syncAuth();
    const onStorage = (e) => {
      if (e.key === "auth_token" || e.key === "auth_user" || e.key === "auth_token_type") {
        syncAuth();
      }
    };
    window.addEventListener("storage", onStorage);
    return () => {
      ignore = true;
      window.removeEventListener("storage", onStorage);
    };
  }, []);

  const logout = async () => {
    if (loggingOut) return;
    setLoggingOut(true);
    try {
      await logoutCourseEnglish();
    } catch {
      // ignore
    } finally {
      clearCourseEnglishAuthSession();
      setAuthUser(null);
      setOpenAccount(false);
      setLoggingOut(false);
      window.location.href = "/";
    }
  };

  return (
    <header className="relative z-[80] hidden w-full md:block">
      {/* ===== Top blue bar ===== */}
      <div className="w-full bg-[#135FAE] text-white overflow-visible">
        <div className="px-4 md:px-10 xl:px-20 2xl:px-40 overflow-visible">
          <div className="flex items-stretch justify-between h-[56px]">
            {/* Left: your top navigation — unchanged order */}
            <nav className="flex items-stretch gap-1.5 text-[16px] font-semibold">
              {safeNavLinks.map((link, idx) => (
                <Link
                  key={`${link.href || link.label || "nav"}-${idx}`}
                  href={link.href}
                  className={`flex items-center px-2 border-b-[4px] pt-[4px] transition ${link.style ?? ""} ${isActive(idx) ? "border-white" : "border-transparent text-white/90 hover:text-white"
                    }`}
                >
                  {getLabel(link)}
                </Link>
              ))}
            </nav>

            {/* Right: Currency + Language with dropdowns */}
            <div className="flex items-center self-center gap-3.5">
              {/* Language */}
              <div className="relative z-[90]" ref={languageRef}>
                <button
                  className="flex flex-row items-center gap-2 rounded px-2 py-1 opacity-90 hover:opacity-100 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                  aria-haspopup="listbox"
                  aria-expanded={openLang}
                  onClick={() => setOpenLang((s) => !s)}
                  type="button"
                  dir={language === 'ar' ? 'rtl' : 'ltr'}
                >
                  <img
                    src={activeLanguage?.flag || "/assets/flags/gb.svg"}
                    alt={activeLanguage?.label || "Language"}
                    className="h-[18px] w-[18px]"
                    loading="lazy"
                  />
                  <span className="font-normal">{activeLanguage?.label || "English"}</span>
                  <ChevronDown color="text-white" />
                </button>

                <div
                  className={`absolute mt-2 w-36 rounded-2xl bg-white text-slate-900 shadow-lg ring-1 ring-black/5 z-[9999]
                  ${openLang ? "block" : "hidden"}
                  ${language === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right'}
                  `}
                  role="listbox"
                  dir={language === 'ar' ? 'rtl' : 'ltr'}
                >
                  <div className="p-2 space-y-1 text-left rtl:text-right">
                    {languages.map((item) => (
                      <button
                        key={item.code}
                        className={`flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm transition ${language === item.code ? "bg-slate-100 font-normal" : "hover:bg-slate-50"
                          }`}
                        role="option"
                        onClick={() => {
                          setLanguage(item.code);
                          setOpenLang(false);
                        }}
                      >
                        <img
                          src={item.flag}
                          alt={item.label}
                          className="h-[18px] w-[18px]"
                          loading="lazy"
                        />
                        <div className="text-left rtl:text-right">
                          <p className="text-slate-900">{item.label}</p>
                          <p className="text-xs text-slate-500">{item.code}</p>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              </div>

              {/* Currency */}
              <div className="relative z-[90]" ref={currencyRef}>
                <button
                  className="flex flex-row items-center gap-2 rounded px-2 py-1 opacity-90 hover:opacity-100 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                  aria-haspopup="listbox"
                  aria-expanded={openCur}
                  onClick={() => setOpenCur((s) => !s)}
                  type="button"
                  dir={language === 'ar' ? 'rtl' : 'ltr'}
                >
                  <img
                    src={activeCurrency?.iconLight || "/assets/sar.svg"}
                    alt="Currency"
                    className="h-[18px] w-[18px] brightness-0 invert"
                    loading="lazy"
                  />
                  <span className="font-normal text-white">{activeCurrency?.label || currency}</span>
                  <ChevronDown color="text-white" />
                </button>

                <div
                  className={`absolute mt-2 w-46 rounded-2xl bg-white text-slate-900 shadow-lg ring-1 ring-black/5 z-[9999]
                  ${openCur ? "block" : "hidden"}
                  ${language === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right'}
                  `}
                  role="listbox"
                  dir={language === 'ar' ? 'rtl' : 'ltr'}
                >
                  <div className="p-2 space-y-1 text-left rtl:text-right">
                    {currencies.map((item) => (
                      <button
                        key={item.code}
                        className={`flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm transition ${currency === item.code ? "bg-slate-100 font-normal" : "hover:bg-slate-50"
                          }`}
                        role="option"
                        onClick={() => {
                          setCurrency(item.code);
                          setOpenCur(false);
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
                        <div className="text-left rtl:text-right">
                          <p className="font-normal text-slate-900">{item.label}</p>
                          <p className="text-xs font-normal text-slate-500">{item.code}</p>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            </div>
            {/* end right */}
          </div>
        </div>
      </div>

      {/* ===== Main white bar ===== */}
      <div className="w-full bg-white">
        <div className="px-4 md:px-12 xl:px-20 2xl:px-40">
          <div className="grid grid-cols-12 items-center gap-4 py-3">
            {/* Left: Logo */}
            <div className="col-span-3 flex items-center mt-[7px]">
              <Link href="/" aria-label="Home" className="flex items-center gap-3">
                <img
                  src={logoSrc}
                  alt="Course"
                  className="h-[75px] max-w-[230px] object-contain"
                  loading="lazy"
                />
              </Link>
            </div>

            {/* Center: Menu */}
            <div className="col-span-6 mt-[2px]">
              <nav className="flex items-center justify-center gap-7 text-[16px] font-medium">
                {safeMainNav.map((item, idx) => (
                  <Link
                    key={`${item.href || item.label || "main"}-${idx}`}
                    href={item.href}
                    className={`transition ${isMainActive(item.href) ? "text-[#135FAE]" : "text-slate-800 hover:text-[#135FAE]"
                      }`}
                  >
                    {getLabel(item)}
                  </Link>
                ))}
              </nav>
            </div>

            {/* Right: Actions */}
            <div className="col-span-3 flex items-center gap-2.5 justify-end">
              {!isAuthenticated ? (
                <>
                  <Link
                    href="/compare"
                    aria-label="Compare"
                    className="relative h-[38px] w-[38px] rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 transition inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                  >
                    <svg
                      width="20"
                      height="20"
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
                    {compareCount > 0 && (
                      <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-blue-500 px-1 text-[9px] font-normal text-white">
                        {compareCount}
                      </span>
                    )}
                  </Link>

                  <Link
                    href="/wishlist"
                    aria-label="Wishlist"
                    className="relative h-[38px] w-[38px] rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 transition inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                  >
                    <svg
                      width="20"
                      height="20"
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
                    {wishlistCount > 0 && (
                      <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-normal text-white">
                        {wishlistCount}
                      </span>
                    )}
                  </Link>

                  <Link
                    href="/student/dashboard"
                    className="inline-flex items-center justify-center gap-2 rounded-xl bg-[#135FAE] px-7 py-2 text-white text-[15px] font-normal shadow-sm hover:brightness-110 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                  >
                    <i className="fa-regular fa-user" aria-hidden />
                    {accountLabel}
                  </Link>
                </>
              ) : (
                <>
                  <div className="relative" ref={accountRef}>
                    <div className="flex items-center gap-2.5">
                      <Link
                        href="/student/wishlist"
                        className="relative h-[38px] w-[38px] rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 transition inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                        aria-label={studentMenu?.wishlist || "Wishlist"}
                      >
                        <svg
                          width="20"
                          height="20"
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
                        {wishlistCount > 0 && (
                          <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-normal text-white">
                            {wishlistCount}
                          </span>
                        )}
                      </Link>

                      <Link
                        href="/student/compare"
                        className="relative h-[38px] w-[38px] rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 transition inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                        aria-label={studentMenu?.compare || "Compare"}
                      >
                        <svg
                          width="20"
                          height="20"
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
                        {compareCount > 0 && (
                          <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-normal text-white">
                            {compareCount}
                          </span>
                        )}
                      </Link>

                      <Link
                        href="/student/profile"
                        className="h-[38px] w-[38px] rounded-full bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 transition inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#135FAE]/40"
                        aria-label={studentMenu?.profile || "Profile"}
                      >
                        <i className="fa-regular fa-user text-sm" />
                      </Link>

                      <button
                        type="button"
                        onClick={() => setOpenAccount((s) => !s)}
                        className="inline-flex items-center gap-3 border-0 bg-transparent px-1 py-1 text-slate-900 ring-0 outline-none hover:bg-transparent focus:outline-none"
                      >
                        <div className=" leading-tight">
                          <p className="text-sm font-normal">
                            {formatCourseEnglishMessage("layouts.navbar.actions.hi", language, { name: firstName }, `Hi, ${firstName}`)}
                          </p>
                          <p className="text-sm font-semibold leading-none">
                            {navbar?.actions?.account || "Account"}
                          </p>
                        </div>
                        <i className={`fa-solid fa-chevron-${openAccount ? "up" : "down"} text-xs text-slate-600`} />
                      </button>
                    </div>

                    {openAccount && (
                      <div className="absolute left-0 mt-3 w-[300px] rounded-[26px] border border-slate-200 bg-white p-5 shadow-xl">
                        <div className="mb-4 flex items-center gap-4 border-b border-slate-200 pb-4">
                          <span className="grid h-16 w-16 place-items-center rounded-full bg-[#E8F1F8] text-slate-900">
                            <i className="fa-regular fa-user text-3xl" />
                          </span>
                          <div className="">
                            <p className="text-xl font-semibold leading-tight text-slate-900">
                              {formatCourseEnglishMessage("layouts.navbar.actions.hi", language, { name: firstName }, `Hello, ${firstName}`)}
                            </p>
                            <p className="text-md font-normal text-slate-500">{authUser?.email || ""}</p>
                          </div>

                        </div>

                        <nav className="space-y-1 ">
                          {[
                            { href: "/student/dashboard", icon: "fa-grip", label: studentMenu?.dashboard || "Dashboard" },
                            { href: "/student/profile", icon: "fa-user", label: studentMenu?.profile || "Profile" },
                            { href: "/student/bookings", icon: "fa-bookmark", label: studentMenu?.my_bookings || "My Bookings" },
                            { href: "/student/wishlist", icon: "fa-heart", label: studentMenu?.wishlist || "Wishlist" },
                            { href: "/student/compare", icon: "fa-arrow-right-arrow-left", label: studentMenu?.compare || "Compare" },
                            { href: "/referrals", icon: "fa-hand-holding-dollar", label: studentMenu?.referrals || "Referrals" },
                          ].map((item) => (
                            <Link
                              key={item.href}
                              href={item.href}
                              className="flex items-center  rounded-xl px-3 py-2 text-xl gap-4 font-medium text-slate-800 hover:bg-slate-50"
                              onClick={() => setOpenAccount(false)}
                            >
                              <i className={`fa-solid ${item.icon} text-[#0F78C8]`} />
                              <span>{item.label}</span>
                            </Link>
                          ))}
                          <button
                            type="button"
                            onClick={logout}
                            className="flex w-full items-center rounded-xl gap-4 px-3 py-2 text-xl font-medium text-rose-600 hover:bg-rose-50"
                          >
                            <i className="fa-solid fa-right-from-bracket" />
                            <span>{loggingOut ? (studentMenu?.signing_out || "Signing out...") : (studentMenu?.logout || "Logout")}</span>
                          </button>
                        </nav>
                      </div>
                    )}
                  </div>
                </>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Divider */}
      <div className="h-px w-full bg-slate-200" />
    </header>
  );
}

/* ===== Small inline icon ===== */
function ChevronDown({ color = "text-slate-800" }) {
  return <i className={`fa-solid fa-chevron-down text-[11px] opacity-90 ${color}`} aria-hidden />;
}
