"use client";

import { useLocale } from "@/components/providers/locale-provider";
import {
  formatPriceAmount,
  getCurrencyDisplay,
  isCurrencyAfterAmount,
  renderCurrencySymbol,
} from "@/lib/format-currency";

export function CurrencyAmount({
  currency,
  amount,
  activeCurrency,
  className = "inline-flex items-center gap-1",
  iconClassName = "h-4 w-4",
  variant = "light",
  muted = false,
  language,
  currencyAfter,
  sign = "",
}) {
  const { language: localeLanguage } = useLocale();
  const resolvedLanguage = language ?? localeLanguage ?? "en";
  const after = currencyAfter ?? isCurrencyAfterAmount(resolvedLanguage);
  const formatted = formatPriceAmount(amount);

  if (formatted == null) return <span className={className}>-</span>;

  const display = getCurrencyDisplay(currency, activeCurrency);
  const amountNode = (
    <span>
      {sign}
      {formatted}
    </span>
  );
  const currencyNode = renderCurrencySymbol({
    display,
    currency,
    iconClassName,
    variant,
    muted,
  });

  return (
    <span className={className} dir={after ? "rtl" : "ltr"}>
      {after ? (
        <>
          {amountNode}
          {currencyNode}
        </>
      ) : (
        <>
          {currencyNode}
          {amountNode}
        </>
      )}
    </span>
  );
}
