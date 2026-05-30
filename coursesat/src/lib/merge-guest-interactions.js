"use client";

import {
  clearGuestInteractions,
  exportGuestInteractionsForMerge,
} from "@/lib/guest-interactions";

const API_BASE =
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_URL ||
  "/api";

function buildCourseEnglishUrl(path = "") {
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${base}/courseenglish${suffix}`;
}

export async function mergeGuestInteractionsOnLogin() {
  if (typeof window === "undefined") return;

  const token = localStorage.getItem("auth_token");
  if (!token) return;

  const guest = exportGuestInteractionsForMerge();
  const hasGuestItems = guest.wishlist.length > 0 || guest.compare.length > 0;
  if (!hasGuestItems) return;

  const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
  const response = await fetch(buildCourseEnglishUrl("/interactions/merge"), {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `${tokenType} ${token}`,
    },
    body: JSON.stringify(guest),
    cache: "no-store",
  });

  if (!response.ok) return;

  clearGuestInteractions();
  window.dispatchEvent(new Event("guest-interactions-update"));
}
