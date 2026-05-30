"use client";

import { mergeGuestInteractionsOnLogin } from "@/lib/merge-guest-interactions";

const API_BASE =
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_URL ||
  "/api";

const COURSEENGLISH_ROLES = ["lg_student", "lg_agent"];

export function buildAuthUrl(path = "") {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${base}/courseenglish${suffix}`;
}

export function isCourseEnglishRole(role) {
  return COURSEENGLISH_ROLES.includes(role);
}

export function getDashboardPath(role) {
  if (role === "lg_agent") return "/agent/dashboard";
  if (role === "lg_student") return "/student/dashboard";
  return "/login";
}

export function getStoredAuthToken() {
  if (typeof window === "undefined") return null;
  return localStorage.getItem("auth_token");
}

export function getStoredAuthTokenType() {
  if (typeof window === "undefined") return "Bearer";
  return localStorage.getItem("auth_token_type") || "Bearer";
}

export function getStoredAuthUser() {
  if (typeof window === "undefined") return null;

  const raw = localStorage.getItem("auth_user");
  if (!raw) return null;

  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}

export function saveAuthSession(payload) {
  if (typeof window === "undefined" || !payload?.access_token || !payload?.user) return;

  localStorage.setItem("auth_token", payload.access_token);
  localStorage.setItem("auth_token_type", payload.token_type || "Bearer");
  localStorage.setItem("auth_user", JSON.stringify(payload.user));
  window.dispatchEvent(new Event("auth-update"));
  mergeGuestInteractionsOnLogin().catch(() => {});
}

export function clearAuthSession() {
  if (typeof window === "undefined") return;

  localStorage.removeItem("auth_token");
  localStorage.removeItem("auth_token_type");
  localStorage.removeItem("auth_user");
  window.dispatchEvent(new Event("auth-update"));
}

export async function logoutAuth() {
  const token = getStoredAuthToken();
  if (!token) return;

  await fetch(buildAuthUrl("/auth/logout"), {
    method: "POST",
    headers: {
      Accept: "application/json",
      Authorization: `${getStoredAuthTokenType()} ${token}`,
    },
  }).catch(() => {});
}

export async function fetchAuthMe(token = getStoredAuthToken()) {
  if (!token) {
    throw new Error("Unauthenticated.");
  }

  const response = await fetch(buildAuthUrl("/auth/me"), {
    headers: {
      Accept: "application/json",
      Authorization: `${getStoredAuthTokenType()} ${token}`,
    },
    cache: "no-store",
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(data?.message || "Authentication check failed.");
  }

  return data;
}

export async function login(credentials) {
  const response = await fetch(buildAuthUrl("/auth/login"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ ...credentials, app: "courseenglish" }),
  });

  const data = await response.json();

  if (!response.ok || data?.success === false) {
    throw new Error(data?.message || "Login failed.");
  }

  return data;
}

export async function register(payload) {
  const response = await fetch(buildAuthUrl("/auth/register"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ ...payload, app: "courseenglish" }),
  });

  const data = await response.json();

  if (!response.ok || data?.success === false) {
    throw new Error(data?.message || "Registration failed.");
  }

  return data;
}

export async function requestPasswordReset(email) {
  const response = await fetch(buildAuthUrl("/auth/forgot-password"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ email, app: "courseenglish" }),
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok || data?.success === false) {
    throw new Error(data?.message || "Failed to send reset link.");
  }

  return data;
}

export async function resetPassword(payload) {
  const response = await fetch(buildAuthUrl("/auth/reset-password"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ ...payload, app: "courseenglish" }),
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok || data?.success === false) {
    throw new Error(data?.message || "Failed to reset password.");
  }

  return data;
}

export async function setBookingPassword(payload, token = getStoredAuthToken()) {
  if (!token) {
    throw new Error("Unauthenticated.");
  }

  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const response = await fetch(`${base}/courseenglish/booking/set-password`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      Authorization: `${getStoredAuthTokenType()} ${token}`,
    },
    body: JSON.stringify(payload),
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok || data?.success === false) {
    throw new Error(data?.message || "Failed to set password.");
  }

  return data;
}
