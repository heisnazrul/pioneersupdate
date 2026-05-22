"use client";

import { createContext, useContext, useEffect, useMemo, useState } from "react";

import {
  getLocaleMessages,
  getLocaleValue,
  isRtlLanguage,
  resolveLanguage,
} from "@/lib/locale";

const LocaleContext = createContext(null);

export function LocaleProvider({ children, initialLanguage = "en" }) {
  const [language, setLanguage] = useState(resolveLanguage(initialLanguage));

  const messages = useMemo(() => getLocaleMessages(language), [language]);
  const direction = isRtlLanguage(language) ? "rtl" : "ltr";

  useEffect(() => {
    document.documentElement.lang = language;
    document.documentElement.dir = direction;
  }, [direction, language]);

  const value = useMemo(
    () => ({
      direction,
      language,
      messages,
      setLanguage,
      t: (path, fallback = "") => getLocaleValue(messages, path, fallback),
    }),
    [direction, language, messages]
  );

  return (
    <LocaleContext.Provider value={value}>{children}</LocaleContext.Provider>
  );
}

export function useLocale() {
  const context = useContext(LocaleContext);

  if (!context) {
    throw new Error("useLocale must be used within LocaleProvider");
  }

  return context;
}
