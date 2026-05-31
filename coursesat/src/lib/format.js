import { formatCurrencyText } from "@/lib/format-currency";

export function formatCurrency(value, currency = "SAR", language = "en") {
  return formatCurrencyText(value, currency, language);
}

export function formatDate(value) {
  if (!value) return "-";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleDateString("en-CA");
}
