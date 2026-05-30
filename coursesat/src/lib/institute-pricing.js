/**
 * Resolve a price from a currency map returned by the API (buildPriceMap).
 */
import { applyPioneersBenefits } from "@/lib/pioneers-discount";

export function getPriceFromMap(prices, currency) {
  if (!prices || !currency) return null;

  const code = String(currency).toUpperCase();
  if (prices[code] != null) return Number(prices[code]);

  const lower = code.toLowerCase();
  if (prices[lower] != null) return Number(prices[lower]);

  return null;
}

/**
 * Resolve a price from an API item using price maps, nested prices, or legacy *_gbp/*_sar fields.
 */
export function getItemPrice(item, currency, { field = null, pricesKey = null } = {}) {
  if (!item || !currency) return 0;

  const code = String(currency).toUpperCase();

  if (pricesKey && item[pricesKey]) {
    const mapped = getPriceFromMap(item[pricesKey], code);
    if (mapped != null) return mapped;
  }

  if (item.prices && !item.prices.new && typeof item.prices === "object") {
    const mapped = getPriceFromMap(item.prices, code);
    if (mapped != null) return mapped;
  }

  if (field) {
    const fieldPricesKey = `${field}_prices`;
    if (item[fieldPricesKey]) {
      const mapped = getPriceFromMap(item[fieldPricesKey], code);
      if (mapped != null) return mapped;
    }

    const gbpKey = `${field}_gbp`;
    const sarKey = `${field}_sar`;
    if (code === "SAR" && item[sarKey] != null) return Number(item[sarKey]);
    if (code === "GBP" && item[gbpKey] != null) return Number(item[gbpKey]);
  }

  if (item.prices?.new) {
    const mapped = getPriceFromMap(item.prices.new, code);
    if (mapped != null) return mapped;
  }

  if (code === "SAR" && item.price_sar != null) return Number(item.price_sar);
  if (code === "GBP" && item.price_gbp != null) return Number(item.price_gbp);

  const baseCurrency = String(item.base_currency || "GBP").toUpperCase();
  if (code === baseCurrency) {
    if (field && item[field] != null) return Number(item[field]);
    if (item.price != null) return Number(item.price);
    if (item.amount != null) return Number(item.amount);
    if (item.fee_per_week != null) return Number(item.fee_per_week);
  }

  return 0;
}

/**
 * Pick the weekly course fee for a given number of weeks using tier thresholds.
 * Uses the highest tier where from_weeks <= selectedWeeks.
 */
export function resolveWeeklyCourseFee(course, weeks, currency) {
  if (!course) return 0;

  const selectedWeeks = Math.max(1, Number(weeks) || 1);
  const tiers = Array.isArray(course.week_tiers) ? course.week_tiers : [];

  if (tiers.length === 0) {
    return getItemPrice(course, currency, { field: "price_per_week" })
      || getItemPrice(course, currency, { field: "price" });
  }

  let matched = tiers[0];
  for (const tier of tiers) {
    if (Number(tier.from_weeks) <= selectedWeeks) {
      matched = tier;
    } else {
      break;
    }
  }

  return getPriceFromMap(matched?.prices, currency)
    ?? Number(matched?.weekly_fee ?? 0);
}

export function parseApiDate(value) {
  if (!value) return null;
  if (value instanceof Date) {
    return Number.isNaN(value.getTime()) ? null : value;
  }

  const [y, m, d] = String(value).split("-").map(Number);
  if (!y || !m || !d) return null;

  return new Date(y, m - 1, d);
}

function addDays(date, days) {
  return new Date(date.getTime() + days * 24 * 60 * 60 * 1000);
}

function startOfDay(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

function endOfDay(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59, 999);
}

/**
 * Count accommodation weeks that fall within a supplement date window.
 * Each booked week is a 7-day block starting from the stay start date.
 */
export function countOverlappingWeeks(startDate, weeks, rangeStart, rangeEnd) {
  const stayStart = parseApiDate(startDate);
  const rangeFrom = parseApiDate(rangeStart);
  const rangeTo = parseApiDate(rangeEnd);

  if (!stayStart || !rangeFrom || !rangeTo) return 0;

  const normalizedStayStart = startOfDay(stayStart);
  const normalizedRangeFrom = startOfDay(rangeFrom);
  const normalizedRangeTo = endOfDay(rangeTo);
  const totalWeeks = Math.max(1, Number(weeks) || 1);

  let count = 0;

  for (let i = 0; i < totalWeeks; i += 1) {
    const weekStart = startOfDay(addDays(normalizedStayStart, i * 7));
    const weekEndExclusive = addDays(weekStart, 7);

    if (weekStart <= normalizedRangeTo && weekEndExclusive > normalizedRangeFrom) {
      count += 1;
    }
  }

  return count;
}

/**
 * Check whether a stay overlaps a supplement date window.
 */
export function stayOverlapsDateRange(startDate, weeks, rangeStart, rangeEnd) {
  return countOverlappingWeeks(startDate, weeks, rangeStart, rangeEnd) > 0;
}

/**
 * Build accommodation supplement line items for the sidebar summary.
 */
export function getAccommodationSupplements(accommodation, { startDate, weeks, accAge, currency, labels = {} }) {
  if (!accommodation || !accommodation.supplements) return [];

  const selectedWeeks = Math.max(1, Number(weeks) || 1);
  const items = [];
  const supplements = accommodation.supplements;

  const addSeasonal = (key, fallbackLabel) => {
    const entry = supplements[key];
    if (!entry || Number(entry.fee) <= 0 || !entry.start_date || !entry.end_date || !startDate) return;

    const overlappingWeeks = countOverlappingWeeks(
      startDate,
      selectedWeeks,
      entry.start_date,
      entry.end_date,
    );

    if (overlappingWeeks <= 0) return;

    const unit = getPriceFromMap(entry.prices, currency) ?? Number(entry.fee);
    const total = entry.per_week ? unit * overlappingWeeks : unit;

    items.push({
      key,
      label: key === "other" && entry.name ? entry.name : (labels[key] || fallbackLabel),
      unit,
      total,
      perWeek: Boolean(entry.per_week),
      weeks: overlappingWeeks,
    });
  };

  addSeasonal("summer", labels.summer || "Summer Supplement");
  addSeasonal("winter", labels.winter || "Winter Supplement");
  addSeasonal("other", labels.other || "Additional Supplement");

  const under18 = supplements.under_18;
  const minAge = Number(accommodation.min_age);
  const studentAge = accAge != null ? Number(accAge) : null;

  if (
    under18
    && Number(under18.fee) > 0
    && minAge > 0
    && studentAge != null
    && studentAge < minAge
  ) {
    const unit = getPriceFromMap(under18.prices, currency) ?? Number(under18.fee);
    items.push({
      key: "under_18",
      label: labels.under_18 || "Under 18 Supplement",
      unit,
      total: under18.per_week ? unit * selectedWeeks : unit,
      perWeek: Boolean(under18.per_week),
      weeks: selectedWeeks,
    });
  }

  return items;
}

/**
 * Build insurance line items (weekly fee × weeks + one-time admin fee).
 */
export function getInsurancePricing(insurance, weeks, currency, labels = {}) {
  if (!insurance) return { lines: [], total: 0 };

  const selectedWeeks = Math.max(1, Number(weeks) || 1);
  const weeklyUnit = getItemPrice(insurance, currency, { field: "price", pricesKey: "prices" })
    || getItemPrice(insurance, currency, { field: "amount", pricesKey: "prices" });
  const adminFee = getItemPrice(insurance, currency, {
    field: "admin_fee",
    pricesKey: "admin_fee_prices",
  });

  const lines = [];

  if (weeklyUnit > 0) {
    lines.push({
      key: "insurance_weekly",
      label: labels.insurance || insurance.name || "Insurance",
      unit: weeklyUnit,
      weeks: selectedWeeks,
      perWeek: true,
      total: weeklyUnit * selectedWeeks,
    });
  }

  if (adminFee > 0) {
    lines.push({
      key: "insurance_admin",
      label: labels.insurance_admin || "Insurance Admin Fee",
      total: adminFee,
      perWeek: false,
    });
  }

  const total = lines.reduce((sum, line) => sum + line.total, 0);

  return { lines, total, weeklyUnit, adminFee };
}

/**
 * Compute full institute detail pricing breakdown.
 */
export function computeInstitutePricing({
  selectedCourse,
  selectedCourseId,
  selectedAccommodation,
  selectedPickup,
  selectedInsurances = [],
  selectedSupplements = [],
  weeks,
  startDate,
  accAge,
  currency,
  registrationFeeObj,
  courseDiscountPercent = 0,
  referralDiscountPercent = 0,
  pioneersDiscounts = [],
  supplementLabels = {},
}) {
  const selectedWeeks = Math.max(1, Number(weeks) || 1);

  const weeklyCourseFee = selectedCourse
    ? resolveWeeklyCourseFee(selectedCourse, selectedWeeks, currency)
    : 0;
  const courseTotal = weeklyCourseFee * selectedWeeks;

  const weeklyAccFee = selectedAccommodation
    ? getItemPrice(selectedAccommodation, currency, { field: "fee_per_week", pricesKey: "fee_per_week_prices" })
    : 0;
  const accTotal = selectedAccommodation ? weeklyAccFee * selectedWeeks : 0;

  const oneTimeFees = [];

  if (selectedCourse) {
    const materialFee = getItemPrice(selectedCourse, currency, {
      field: "material_books_fee",
      pricesKey: "material_books_prices",
    });
    if (materialFee > 0) {
      oneTimeFees.push({
        key: "material_books",
        label: supplementLabels.material_books || "Material / Books Fee",
        total: materialFee,
      });
    }

    const regFee = getItemPrice(selectedCourse, currency, {
      field: "registration_admin_fee",
      pricesKey: "registration_admin_prices",
    });
    if (regFee > 0) {
      oneTimeFees.push({
        key: "registration",
        label: supplementLabels.registration || "Registration Fee",
        total: regFee,
      });
    }

    const mandatoryFee = getItemPrice(selectedCourse, currency, {
      field: "mandatory_additional_fee",
      pricesKey: "mandatory_additional_prices",
    });
    if (mandatoryFee > 0) {
      oneTimeFees.push({
        key: "mandatory",
        label: selectedCourse.mandatory_additional_fee_name || supplementLabels.mandatory || "Mandatory Fee",
        total: mandatoryFee,
      });
    }
  } else if (registrationFeeObj) {
    const regFee = getItemPrice(registrationFeeObj, currency, { field: "amount", pricesKey: "prices" });
    if (regFee > 0) {
      oneTimeFees.push({
        key: "registration",
        label: supplementLabels.registration || "Registration Fee",
        total: regFee,
      });
    }
  }

  const accSupplements = selectedAccommodation
    ? getAccommodationSupplements(selectedAccommodation, {
      startDate,
      weeks: selectedWeeks,
      accAge,
      currency,
      labels: supplementLabels,
    })
    : [];

  const pickupTotal = selectedPickup
    ? getItemPrice(selectedPickup, currency, { field: "price", pricesKey: "prices" })
    : 0;

  const insuranceLines = (selectedInsurances || []).flatMap(
    (insurance) => getInsurancePricing(insurance, selectedWeeks, currency, supplementLabels).lines,
  );
  const insuranceTotal = insuranceLines.reduce((sum, line) => sum + line.total, 0);

  const supplementLines = (selectedSupplements || []).map((supplement) => {
    const total = getItemPrice(supplement, currency, { field: "price", pricesKey: "prices" })
      || getItemPrice(supplement, currency, { field: "amount", pricesKey: "prices" });
    return {
      key: `supplement_${supplement.id}`,
      id: supplement.id,
      label: supplement.name,
      ar_label: supplement.ar_name,
      total,
      perWeek: false,
    };
  }).filter((line) => line.total > 0);

  const supplementsExtraTotal = supplementLines.reduce((sum, line) => sum + line.total, 0);

  const pioneers = applyPioneersBenefits({
    selectedWeeks,
    pioneersDiscounts,
    currency,
    pickupTotal,
    insuranceTotal,
    insuranceLines,
    accTotal,
    oneTimeFees,
  });

  const {
    adjustedPickupTotal,
    adjustedInsuranceTotal,
    adjustedInsuranceLines,
    adjustedAccTotal,
    adjustedOneTimeFees,
    cashLines: pioneersCashLines,
    freeLines: pioneersFreeLines,
    cashTotal: pioneersCashTotal,
    pickupWaived,
    pickupOriginalTotal,
    accWaived,
    accOriginalTotal,
  } = pioneers;

  const adjustedOneTimeTotal = adjustedOneTimeFees.reduce((sum, line) => sum + line.total, 0);
  const accSupplementsTotal = accSupplements.reduce((sum, line) => sum + line.total, 0);

  const courseDiscountAmount = courseDiscountPercent > 0
    ? courseTotal * (courseDiscountPercent / 100)
    : 0;

  const referralDiscountAmount = referralDiscountPercent > 0
    ? courseTotal * (referralDiscountPercent / 100)
    : 0;

  const subtotal = courseTotal + adjustedAccTotal + adjustedOneTimeTotal + accSupplementsTotal
    + adjustedPickupTotal + adjustedInsuranceTotal + supplementsExtraTotal;
  const total = subtotal - courseDiscountAmount - referralDiscountAmount - pioneersCashTotal;

  return {
    weeklyCourseFee,
    courseTotal,
    weeklyAccFee,
    accTotal,
    accPrice: adjustedAccTotal,
    accOriginalTotal: accWaived ? accOriginalTotal : adjustedAccTotal,
    accWaived,
    oneTimeFees: adjustedOneTimeFees,
    accSupplements,
    insuranceLines: adjustedInsuranceLines,
    insuranceTotal: adjustedInsuranceTotal,
    supplementLines,
    pickupTotal: adjustedPickupTotal,
    pickupOriginalTotal,
    pickupWaived,
    pioneersCashLines,
    pioneersFreeLines,
    pioneersCashTotal,
    courseDiscountPercent,
    courseDiscountAmount,
    referralDiscountPercent,
    referralDiscountAmount,
    coursePriceAfterReferral: courseTotal - referralDiscountAmount,
    subtotal,
    total,
  };
}
