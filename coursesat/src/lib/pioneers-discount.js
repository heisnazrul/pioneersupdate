const FREE_FOR_ALIASES = {
  pickup: "pickup",
  airport_pickup: "pickup",
  "airport pickup": "pickup",
  insurance: "insurance",
  registration: "registration",
  registration_fee: "registration",
  material_books: "material_books",
  material: "material_books",
  books: "material_books",
  accommodation: "accommodation",
  acc: "accommodation",
};

function getPriceFromMap(prices, currency) {
  if (!prices || !currency) return null;
  const code = String(currency).toUpperCase();
  if (prices[code] != null) return Number(prices[code]);
  const lower = code.toLowerCase();
  if (prices[lower] != null) return Number(prices[lower]);
  return null;
}

export function normalizePioneersFreeFor(value) {
  if (value === null || value === undefined || value === "") return null;
  const key = String(value).toLowerCase().trim();
  return FREE_FOR_ALIASES[key] || key;
}

function getPioneersUnitAmount(tier, currency) {
  return getPriceFromMap(tier.discount_amount_prices, currency)
    ?? Number(tier.discount_amount ?? 0);
}

/**
 * All pioneers tiers the user qualifies for at the selected week count.
 * Cash tiers: discount_amount × floor(selectedWeeks / tier.weeks)
 * Free tiers: unlock when selectedWeeks >= tier.weeks
 */
export function resolveQualifyingPioneersDiscounts(weeks, tiers = [], currency) {
  const selectedWeeks = Math.max(1, Number(weeks) || 1);

  return (tiers || [])
    .filter((tier) => selectedWeeks >= Number(tier.weeks))
    .map((tier) => {
      const tierWeeks = Math.max(1, Number(tier.weeks));
      const multiplier = Math.floor(selectedWeeks / tierWeeks);
      const freeFor = normalizePioneersFreeFor(tier.discount_full_for);

      if (freeFor) {
        return {
          id: tier.id,
          name: tier.name,
          ar_name: tier.ar_name,
          type: "free_item",
          freeFor,
          tierWeeks,
          multiplier: 1,
          unitAmount: 0,
          total: 0,
        };
      }

      const unitAmount = getPioneersUnitAmount(tier, currency);
      return {
        id: tier.id,
        name: tier.name,
        ar_name: tier.ar_name,
        type: "cash",
        tierWeeks,
        multiplier,
        unitAmount,
        total: unitAmount * multiplier,
      };
    });
}

export function applyPioneersBenefits({
  selectedWeeks,
  pioneersDiscounts,
  currency,
  pickupTotal,
  insuranceTotal,
  insuranceLines,
  accTotal,
  oneTimeFees,
}) {
  const qualifying = resolveQualifyingPioneersDiscounts(selectedWeeks, pioneersDiscounts, currency);
  const waivedKeys = new Set();
  const cashLines = [];
  const freeLines = [];
  let cashTotal = 0;

  for (const tier of qualifying) {
    if (tier.type === "free_item") {
      waivedKeys.add(tier.freeFor);
      freeLines.push({
        key: `pioneers_free_${tier.id}`,
        id: tier.id,
        label: tier.name,
        ar_label: tier.ar_name,
        freeFor: tier.freeFor,
        tierWeeks: tier.tierWeeks,
        type: "free_item",
      });
      continue;
    }

    if (tier.total > 0) {
      cashLines.push({
        key: `pioneers_cash_${tier.id}`,
        id: tier.id,
        label: tier.name,
        ar_label: tier.ar_name,
        type: "cash",
        tierWeeks: tier.tierWeeks,
        multiplier: tier.multiplier,
        unitAmount: tier.unitAmount,
        total: tier.total,
      });
      cashTotal += tier.total;
    }
  }

  const adjustedOneTimeFees = (oneTimeFees || []).map((fee) => {
    if (fee.key === "registration" && waivedKeys.has("registration")) {
      return { ...fee, originalTotal: fee.total, total: 0, waived: true };
    }
    if (fee.key === "material_books" && waivedKeys.has("material_books")) {
      return { ...fee, originalTotal: fee.total, total: 0, waived: true };
    }
    return fee;
  });

  const adjustedInsuranceLines = (insuranceLines || []).map((line) => (
    waivedKeys.has("insurance")
      ? { ...line, originalTotal: line.total, total: 0, waived: true }
      : line
  ));

  return {
    qualifying,
    cashLines,
    freeLines,
    cashTotal,
    waivedKeys,
    adjustedPickupTotal: waivedKeys.has("pickup") ? 0 : pickupTotal,
    pickupWaived: waivedKeys.has("pickup"),
    pickupOriginalTotal: pickupTotal,
    adjustedInsuranceTotal: waivedKeys.has("insurance") ? 0 : insuranceTotal,
    adjustedInsuranceLines,
    adjustedAccTotal: waivedKeys.has("accommodation") ? 0 : accTotal,
    accWaived: waivedKeys.has("accommodation"),
    accOriginalTotal: accTotal,
    adjustedOneTimeFees,
  };
}

export function resolveCoursePromotionPercent(selectedCourseId, discounts = [], selectedCourse = null) {
  const coursePromos = (discounts || []).filter(
    (entry) => !entry.course_id || Number(entry.course_id) === Number(selectedCourseId),
  );

  if (coursePromos.length > 0) {
    return Math.max(...coursePromos.map((entry) => Number(entry.discount_percentage) || 0));
  }

  return Number(selectedCourse?.discount_percent ?? selectedCourse?.promotion_percentage ?? 0) || 0;
}

export function buildPioneersPopupMessage(qualifying, loc, formatAmount) {
  if (!qualifying?.length) return "";

  const parts = qualifying.map((tier) => {
    const name = loc(tier.name, tier.ar_name);
    if (tier.type === "free_item") {
      return name;
    }
    if (tier.total > 0) {
      return `${name} (${formatAmount(tier.total)})`;
    }
    return name;
  });

  return parts.join(" + ");
}
