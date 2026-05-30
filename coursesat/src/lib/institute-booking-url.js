export function formatInstituteQueryDate(date) {
  if (!(date instanceof Date) || Number.isNaN(date.getTime())) return null;
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

export function parseInstituteQueryDate(dateString) {
  if (!dateString) return null;
  const [y, m, d] = String(dateString).split("-").map(Number);
  if (!y || !m || !d) return null;
  return new Date(y, m - 1, d);
}

export function buildInstituteSelectionQuery({
  courseId,
  weeks,
  accommodationId,
  pickupId,
  startDate,
  extras = [],
  accAge,
} = {}) {
  const params = new URLSearchParams();

  if (courseId != null && courseId !== "") params.set("course_id", String(courseId));
  if (weeks != null) params.set("weeks", String(weeks));
  if (accommodationId != null && accommodationId !== "") {
    params.set("accommodation_id", String(accommodationId));
  }
  if (pickupId != null && pickupId !== "") params.set("pickup_id", String(pickupId));
  if (startDate) params.set("start_date", startDate);
  if (extras?.length) params.set("extras", extras.join(","));
  if (accAge != null && accAge !== "") params.set("acc_age", String(accAge));

  return params.toString();
}

export function buildInstituteDetailsUrl(slug, selections = {}) {
  const query = buildInstituteSelectionQuery(selections);
  return `/language-institutes/${encodeURIComponent(slug)}${query ? `?${query}` : ""}`;
}

export function buildInstituteBookingUrl(slug, selections = {}) {
  const query = buildInstituteSelectionQuery(selections);
  return `/language-institutes/${encodeURIComponent(slug)}/booking${query ? `?${query}` : ""}`;
}

export function readInstituteSelectionFromSearchParams(searchParams) {
  const extrasRaw = searchParams.get("extras");
  return {
    courseId: searchParams.get("course_id") ? Number(searchParams.get("course_id")) : null,
    weeks: searchParams.get("weeks") ? Number(searchParams.get("weeks")) : 12,
    accommodationId: searchParams.get("accommodation_id") || "no-acc",
    pickupId: searchParams.get("pickup_id") ? Number(searchParams.get("pickup_id")) : null,
    startDate: parseInstituteQueryDate(searchParams.get("start_date")),
    extras: extrasRaw ? extrasRaw.split(",").map(Number).filter(Boolean) : [],
    accAge: searchParams.get("acc_age") ? Number(searchParams.get("acc_age")) : null,
  };
}
