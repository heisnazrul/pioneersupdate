"use client";

import { buildAuthUrl, getStoredAuthToken, getStoredAuthTokenType } from "@/lib/auth";

const API_BASE =
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_URL ||
  "/api";

function buildApiUrl(path) {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${base}${suffix}`;
}

function buildBookingUrl() {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  return `${base}/coursesat/bookings/language-course`;
}

function buildOnlineBookingUrl() {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  return `${base}/coursesat/bookings/online-course`;
}

export async function submitOnlineCourseBooking(payload, token = null) {
  const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
  };

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  const response = await fetch(buildOnlineBookingUrl(), {
    method: "POST",
    headers,
    body: JSON.stringify(payload),
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const error = new Error(data?.message || "Booking request failed.");
    error.status = response.status;
    error.errors = data?.errors || null;
    throw error;
  }

  return data;
}

export async function submitLanguageCourseBooking(payload, token = null) {
  const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
  };

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  const response = await fetch(buildBookingUrl(), {
    method: "POST",
    headers,
    body: JSON.stringify(payload),
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const error = new Error(data?.message || "Booking request failed.");
    error.status = response.status;
    error.errors = data?.errors || null;
    throw error;
  }

  return data;
}

export async function fetchBookingDetail(referenceNo, token = getStoredAuthToken()) {
  if (!referenceNo) {
    throw new Error("Booking reference is required.");
  }

  const headers = { Accept: "application/json" };
  if (token) {
    headers.Authorization = `${getStoredAuthTokenType()} ${token}`;
  }

  const response = await fetch(
    buildApiUrl(`/courseenglish/student/bookings/${encodeURIComponent(referenceNo)}`),
    { headers, cache: "no-store" },
  );

  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    throw new Error(data?.message || "Failed to load booking.");
  }

  return data?.data || null;
}

export function saveBookingConfirmation(booking) {
  if (typeof window === "undefined" || !booking?.reference_no) return;
  sessionStorage.setItem(
    `booking_confirmation_${booking.reference_no}`,
    JSON.stringify(booking),
  );
}

export function readBookingConfirmation(referenceNo) {
  if (typeof window === "undefined" || !referenceNo) return null;
  const raw = sessionStorage.getItem(`booking_confirmation_${referenceNo}`);
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}

export async function resolveBookingDetail(referenceNo) {
  const cached = readBookingConfirmation(referenceNo);
  if (cached) return cached;

  return fetchBookingDetail(referenceNo);
}
