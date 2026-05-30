export function formatOnlineCourseQueryDate(date) {
  if (!(date instanceof Date) || Number.isNaN(date.getTime())) return null;
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

export function parseOnlineCourseQueryDate(dateString) {
  if (!dateString) return null;
  const [y, m, d] = String(dateString).split("-").map(Number);
  if (!y || !m || !d) return null;
  return new Date(y, m - 1, d);
}

export function buildOnlineCourseSelectionQuery({ courseId, weeks, startDate } = {}) {
  const params = new URLSearchParams();
  if (courseId != null && courseId !== "") params.set("course_id", String(courseId));
  if (weeks != null) params.set("weeks", String(weeks));
  if (startDate) params.set("start_date", startDate);
  return params.toString();
}

export function buildOnlineCourseDetailsUrl(slug, selections = {}) {
  const query = buildOnlineCourseSelectionQuery(selections);
  return `/online-courses/${encodeURIComponent(slug)}${query ? `?${query}` : ""}`;
}

export function buildOnlineCourseBookingUrl(slug, selections = {}) {
  const query = buildOnlineCourseSelectionQuery(selections);
  return `/online-courses/${encodeURIComponent(slug)}/booking${query ? `?${query}` : ""}`;
}

export function readOnlineCourseSelectionFromSearchParams(searchParams) {
  return {
    courseId: searchParams.get("course_id") ? Number(searchParams.get("course_id")) : null,
    weeks: searchParams.get("weeks") ? Number(searchParams.get("weeks")) : 4,
    startDate: parseOnlineCourseQueryDate(searchParams.get("start_date")),
  };
}
