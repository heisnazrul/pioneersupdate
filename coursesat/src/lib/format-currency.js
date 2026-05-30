/* eslint-disable @next/next/no-img-element */

export function getCoursePrice(course, currency, type = "new") {
  if (!course || !currency) return null;

  const prices = course?.prices?.[type];
  if (prices && prices[currency] != null) {
    return prices[currency];
  }

  const suffix = currency.toLowerCase();
  const legacyKey =
    type === "old"
      ? `price_old_${suffix}`
      : type === "from"
        ? `price_from_${suffix}`
        : `price_new_${suffix}`;

  if (course?.[legacyKey] != null) {
    return course[legacyKey];
  }

  if (type === "new") {
    return course?.price ?? course?.price_new ?? null;
  }

  if (type === "from") {
    return course?.price_from ?? course?.priceFrom ?? null;
  }

  return course?.old_price ?? course?.price_old ?? null;
}

export function formatPriceAmount(value) {
  if (value === null || value === undefined || value === "") return null;

  const amount = typeof value === "number" ? value : Number(String(value).replace(/,/g, ""));
  if (Number.isNaN(amount)) return null;

  return new Intl.NumberFormat("en-US", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(Math.round(amount));
}

export function getCurrencyDisplay(currencyCode, activeCurrency) {
  const code = String(currencyCode || "").toUpperCase();

  if (code === "SAR") {
    return {
      type: "icon",
      light: "/assets/sar.svg",
      dark: "/assets/sar-black.svg",
    };
  }

  if (code === "GBP") {
    return {
      type: "symbol",
      symbol: "£",
      light: "/assets/gbp.svg",
      dark: "/assets/gbp-black.svg",
    };
  }

  if (activeCurrency?.symbol) {
    return { type: "symbol", symbol: activeCurrency.symbol };
  }

  if (activeCurrency?.icon_light) {
    return {
      type: "icon",
      light: activeCurrency.icon_light,
      dark: activeCurrency.icon_dark || activeCurrency.icon_light,
    };
  }

  return { type: "code", symbol: code };
}

export function CurrencyAmount({
  currency,
  amount,
  activeCurrency,
  className = "inline-flex items-center gap-1",
  iconClassName = "h-4 w-4",
  variant = "light",
  muted = false,
}) {
  const formatted = formatPriceAmount(amount);
  if (formatted == null) return <span className={className}>-</span>;

  const display = getCurrencyDisplay(currency, activeCurrency);

  if (display.type === "icon") {
    const icon = variant === "dark" ? display.dark : display.light;
    return (
      <span className={className}>
        <img
          src={icon}
          alt={currency}
          className={`${iconClassName}${variant === "light" && !muted ? " invert" : ""}${muted ? " opacity-50" : ""}`}
        />
        <span>{formatted}</span>
      </span>
    );
  }

  return (
    <span className={className}>
      {display.symbol ? <span>{display.symbol}</span> : null}
      <span>{formatted}</span>
    </span>
  );
}

export function CurrencyIcon({
  currency,
  activeCurrency,
  className = "h-[18px] w-[18px]",
  variant = "light",
  inverted = false,
}) {
  const display = getCurrencyDisplay(currency, activeCurrency);

  if (display.type === "icon") {
    const icon = variant === "dark" ? display.dark : display.light;
    return (
      <img
        src={icon}
        alt={currency}
        className={`${className}${inverted ? " brightness-0 invert" : ""}`}
        loading="lazy"
      />
    );
  }

  return (
    <span className={`inline-flex items-center justify-center font-semibold ${className}`}>
      {display.symbol || currency}
    </span>
  );
}

export function mapNavCurrencies(apiCurrencies, localeCurrencies, language) {
  if (!apiCurrencies?.length) {
    return localeCurrencies;
  }

  const isArabic = language === "ar";

  return apiCurrencies.map((item) => {
    const localeItem = localeCurrencies.find((entry) => entry.code === item.code);

    return {
      code: item.code,
      label: isArabic
        ? item.ar_name || localeItem?.label || item.name || item.code
        : item.name || localeItem?.label || item.code,
      iconLight: item.icon_light || localeItem?.iconLight || localeItem?.icon,
      iconDark: item.icon_dark || localeItem?.iconDark || localeItem?.icon,
      icon: item.icon_light || localeItem?.icon,
      symbol: item.symbol || localeItem?.symbol,
    };
  });
}
