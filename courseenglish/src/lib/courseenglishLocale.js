"use client";

import ar from "@/locales/courseenglish/ar.json";
import en from "@/locales/courseenglish/en.json";
import { normalizeLang } from "@/lib/i18nFallback";

const LOCALES = {
  en,
  ar,
};

function getByPath(object, path) {
  if (!object || !path) return undefined;

  return String(path)
    .split(".")
    .reduce((current, key) => (current && key in current ? current[key] : undefined), object);
}

export function getCourseEnglishMessages(language = "ar") {
  const lang = normalizeLang(language);
  return LOCALES[lang] || LOCALES.ar;
}

export function getCourseEnglishMessage(path, language = "ar", fallback = undefined) {
  const lang = normalizeLang(language);
  const primary = getByPath(LOCALES[lang], path);

  if (primary !== undefined) {
    return primary;
  }

  const english = getByPath(LOCALES.en, path);

  if (english !== undefined) {
    return english;
  }

  return fallback;
}

export function formatCourseEnglishMessage(path, language = "ar", replacements = {}, fallback = undefined) {
  const template = getCourseEnglishMessage(path, language, fallback);

  if (typeof template !== "string") {
    return template;
  }

  return Object.entries(replacements).reduce((text, [key, value]) => {
    return text.replaceAll(`{${key}}`, String(value));
  }, template);
}
