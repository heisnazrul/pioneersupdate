"use client";

const WISHLIST_KEY = "coursesat_guest_wishlist";
const COMPARE_KEY = "coursesat_guest_compare";

function readJson(key) {
  if (typeof window === "undefined") return [];
  try {
    const raw = localStorage.getItem(key);
    const parsed = raw ? JSON.parse(raw) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

function writeJson(key, value) {
  if (typeof window === "undefined") return;
  localStorage.setItem(key, JSON.stringify(value));
}

function notifyGuestUpdate() {
  if (typeof window === "undefined") return;
  window.dispatchEvent(new Event("guest-interactions-update"));
}

export function toInteractionKey(courseType, courseId) {
  return `${courseType}:${Number(courseId)}`;
}

export function readGuestWishlist() {
  return readJson(WISHLIST_KEY);
}

export function readGuestCompare() {
  return readJson(COMPARE_KEY);
}

export function addGuestWishlist(courseType, courseId) {
  const items = readGuestWishlist();
  const key = toInteractionKey(courseType, courseId);
  if (items.some((item) => toInteractionKey(item.course_type, item.course_id) === key)) {
    return items;
  }
  const next = [...items, { course_type: courseType, course_id: Number(courseId) }];
  writeJson(WISHLIST_KEY, next);
  notifyGuestUpdate();
  return next;
}

export function removeGuestWishlist(courseType, courseId) {
  const key = toInteractionKey(courseType, courseId);
  const next = readGuestWishlist().filter(
    (item) => toInteractionKey(item.course_type, item.course_id) !== key,
  );
  writeJson(WISHLIST_KEY, next);
  notifyGuestUpdate();
  return next;
}

export function addGuestCompare(courseType, courseId, weeks = 12) {
  const items = readGuestCompare();
  const key = toInteractionKey(courseType, courseId);
  const next = items.filter((item) => toInteractionKey(item.course_type, item.course_id) !== key);
  next.push({
    course_type: courseType,
    course_id: Number(courseId),
    weeks: Math.max(1, Number(weeks) || 12),
  });
  writeJson(COMPARE_KEY, next);
  notifyGuestUpdate();
  return next;
}

export function removeGuestCompare(courseType, courseId) {
  const key = toInteractionKey(courseType, courseId);
  const next = readGuestCompare().filter(
    (item) => toInteractionKey(item.course_type, item.course_id) !== key,
  );
  writeJson(COMPARE_KEY, next);
  notifyGuestUpdate();
  return next;
}

export function clearGuestInteractions() {
  if (typeof window === "undefined") return;
  localStorage.removeItem(WISHLIST_KEY);
  localStorage.removeItem(COMPARE_KEY);
  notifyGuestUpdate();
}

export function guestWishlistKeys() {
  return new Set(readGuestWishlist().map((item) => toInteractionKey(item.course_type, item.course_id)));
}

export function guestCompareKeys() {
  return new Set(readGuestCompare().map((item) => toInteractionKey(item.course_type, item.course_id)));
}

export function guestCompareWeeksMap() {
  const map = {};
  readGuestCompare().forEach((item) => {
    map[toInteractionKey(item.course_type, item.course_id)] = Number(item.weeks) || 12;
  });
  return map;
}

export function exportGuestInteractionsForMerge() {
  return {
    wishlist: readGuestWishlist(),
    compare: readGuestCompare(),
  };
}
