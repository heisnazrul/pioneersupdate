"use client";

import { createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";

import { fetchApiJson } from "@/lib/api";

const STORAGE_KEY = "cs_currency";

const CurrencyContext = createContext(null);

export function CurrencyProvider({ children }) {
  const [currency, setCurrencyState] = useState("SAR");
  const [currencies, setCurrencies] = useState([]);
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    if (typeof window === "undefined") return;

    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
      setCurrencyState(saved);
    }

    fetchApiJson("/coursesat/home/currencies")
      .then((data) => {
        const list = data?.currencies ?? [];
        setCurrencies(list);

        const preferred = saved && list.some((item) => item.code === saved)
          ? saved
          : list.find((item) => item.code === "SAR")?.code || list[0]?.code || "SAR";

        setCurrencyState(preferred);
      })
      .catch(() => {})
      .finally(() => setLoaded(true));
  }, []);

  const setCurrency = useCallback((code) => {
    setCurrencyState(code);
    if (typeof window !== "undefined") {
      localStorage.setItem(STORAGE_KEY, code);
    }
  }, []);

  const activeCurrency = useMemo(
    () => currencies.find((item) => item.code === currency) || { code: currency },
    [currencies, currency]
  );

  const value = useMemo(
    () => ({
      currency,
      setCurrency,
      currencies,
      activeCurrency,
      loaded,
    }),
    [currency, setCurrency, currencies, activeCurrency, loaded]
  );

  return <CurrencyContext.Provider value={value}>{children}</CurrencyContext.Provider>;
}

export function useCurrency() {
  const context = useContext(CurrencyContext);
  if (!context) {
    throw new Error("useCurrency must be used within CurrencyProvider");
  }
  return context;
}
