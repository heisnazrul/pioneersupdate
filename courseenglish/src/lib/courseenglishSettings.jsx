"use client";

import { createContext, useContext, useEffect, useMemo, useState } from "react";

const SettingsContext = createContext({
  currency: "SAR",
  language: "ar",
  setCurrency: () => {},
  setLanguage: () => {},
  isArabic: false,
});

const normalizeLanguage = (lang) => (lang === "en" ? "en" : "ar");

export function CourseEnglishSettingsProvider({ children, initialLanguage = "ar" }) {
  // Keep server/client initial render consistent to avoid hydration mismatch.
  const [currency, setCurrency] = useState("SAR");
  const [language, setLanguageState] = useState(() => normalizeLanguage(initialLanguage));

  // Hydrate from persisted storage on mount (cookie > localStorage) without flicker.
  useEffect(() => {
    if (typeof window === "undefined") return;

    const storedCurrency = window.localStorage.getItem("ce_currency");
    if (storedCurrency && storedCurrency !== currency) setCurrency(storedCurrency);

    let storedLanguage = null;
    if (typeof document !== "undefined") {
      const match = document.cookie.match(/(?:^|;\s*)ce_language=([^;]+)/);
      storedLanguage = match ? decodeURIComponent(match[1]) : null;
    }
    if (!storedLanguage) {
      storedLanguage = window.localStorage.getItem("ce_language");
    }

    if (storedLanguage && normalizeLanguage(storedLanguage) !== language) {
      setLanguageState(normalizeLanguage(storedLanguage));
    }
  }, []);

  // Persist whenever language/currency changes.
  useEffect(() => {
    if (typeof window !== "undefined") {
      window.localStorage.setItem("ce_currency", currency);
      window.localStorage.setItem("ce_language", language);
    }

    if (typeof document !== "undefined") {
      document.cookie = `ce_language=${language}; path=/; max-age=31536000; samesite=lax`;
      document.documentElement.lang = language === "ar" ? "ar" : "en";
      document.documentElement.dir = language === "ar" ? "rtl" : "ltr";
    }
  }, [currency, language]);

  const setLanguage = (next) => setLanguageState(normalizeLanguage(next));

  const value = useMemo(
    () => ({
      currency,
      language,
      setCurrency,
      setLanguage,
      isArabic: language === "ar",
    }),
    [currency, language]
  );

  return <SettingsContext.Provider value={value}>{children}</SettingsContext.Provider>;
}

export function useCourseEnglishSettings() {
  return useContext(SettingsContext);
}
