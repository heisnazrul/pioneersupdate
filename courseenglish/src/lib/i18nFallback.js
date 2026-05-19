"use client";

// Simple helper to pick a language-aware fallback.
export function pickLang(language, enValue, arValue) {
  return language === "en" ? enValue : arValue ?? enValue ?? "";
}

// Normalize language code and default to Arabic-first behaviour.
export function normalizeLang(lang) {
  return lang === "en" ? "en" : "ar";
}
