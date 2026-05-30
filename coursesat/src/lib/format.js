export function formatCurrency(value, currency = "SAR") {
  const amount = Math.round(Number(value));
  if (Number.isNaN(amount)) return "-";

  if (currency === "GBP") {
    return `£${amount.toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
  }

  return `${amount.toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 })} SAR`;
}

export function formatDate(value) {
  if (!value) return "-";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleDateString("en-CA");
}
