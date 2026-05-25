import ar from "@/locales/ar.json";
import en from "@/locales/en.json";

const dictionaries = {
  ar,
  en,
};

export function resolveLanguage(language) {
  return language === "en" ? "en" : "ar";
}

export function isRtlLanguage(language) {
  return resolveLanguage(language) === "ar";
}

export function getLocaleMessages(language) {
  return dictionaries[resolveLanguage(language)];
}

export function getPreferredLanguage(acceptLanguage = "") {
  const normalized = acceptLanguage.toLowerCase();
  if (normalized.includes("en") && !normalized.includes("ar")) {
    return "en";
  }
  return "ar";
}

export function getLocaleValue(messages, path, fallback = "") {
  const value = path.split(".").reduce((current, key) => current?.[key], messages);
  return value ?? fallback;
}
