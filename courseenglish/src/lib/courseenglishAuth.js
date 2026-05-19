"use client";

const API_BASE =
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_URL ||
  "/api";

const COURSEENGLISH_ROLES = ["lg_student", "lg_agent"];

export function buildCourseEnglishAuthUrl(path = "") {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${base}/courseenglish${suffix}`;
}

export function isCourseEnglishRole(role) {
  return COURSEENGLISH_ROLES.includes(role);
}

export function getCourseEnglishDashboardPath(role) {
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

export function saveCourseEnglishAuthSession(payload) {
  if (typeof window === "undefined" || !payload?.access_token || !payload?.user) return;

  localStorage.setItem("auth_token", payload.access_token);
  localStorage.setItem("auth_token_type", payload.token_type || "Bearer");
  localStorage.setItem("auth_user", JSON.stringify(payload.user));
  window.dispatchEvent(new Event("auth-update"));
}

export function clearCourseEnglishAuthSession() {
  if (typeof window === "undefined") return;

  localStorage.removeItem("auth_token");
  localStorage.removeItem("auth_token_type");
  localStorage.removeItem("auth_user");
  window.dispatchEvent(new Event("auth-update"));
}

export async function fetchCourseEnglishMe(token = getStoredAuthToken()) {
  if (!token) {
    throw new Error("Unauthenticated.");
  }

  const response = await fetch(buildCourseEnglishAuthUrl("/auth/me"), {
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

export async function loginCourseEnglish(credentials) {
  const response = await fetch(buildCourseEnglishAuthUrl("/auth/login"), {
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

export async function registerCourseEnglish(payload) {
  const response = await fetch(buildCourseEnglishAuthUrl("/auth/register"), {
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

export async function logoutCourseEnglish(token = getStoredAuthToken()) {
  if (!token) return;

  await fetch(buildCourseEnglishAuthUrl("/auth/logout"), {
    method: "POST",
    headers: {
      Accept: "application/json",
      Authorization: `${getStoredAuthTokenType()} ${token}`,
    },
  });
}
