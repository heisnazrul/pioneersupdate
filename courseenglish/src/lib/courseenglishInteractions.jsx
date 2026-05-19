"use client";

import { createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";
import { buildApiUrl } from "@/lib/courseenglishApi";

const InteractionsContext = createContext({
  wishlistKeys: new Set(),
  compareKeys: new Set(),
  wishlistCount: 0,
  compareCount: 0,
  isInWishlist: () => false,
  isInCompare: () => false,
  toggleWishlist: async () => false,
  toggleCompare: async () => false,
});

const toKey = (courseType, courseId) => `${courseType}:${Number(courseId)}`;

function getAuthHeaders() {
  if (typeof window === "undefined") return null;
  const token = localStorage.getItem("auth_token");
  if (!token) return null;
  const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
  return {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: `${tokenType} ${token}`,
  };
}

async function fetchProtected(path, method = "GET", body) {
  const headers = getAuthHeaders();
  if (!headers) {
    return { ok: false, status: 401, json: { message: "Unauthenticated." } };
  }

  const response = await fetch(buildApiUrl(path), {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
    cache: "no-store",
  });

  const contentType = response.headers.get("content-type") || "";
  const json = contentType.includes("application/json")
    ? await response.json()
    : { message: await response.text() };

  return { ok: response.ok, status: response.status, json };
}

export function CourseEnglishInteractionsProvider({ children }) {
  const [wishlistKeys, setWishlistKeys] = useState(new Set());
  const [compareKeys, setCompareKeys] = useState(new Set());

  const loadLists = useCallback(async () => {
    const headers = getAuthHeaders();
    if (!headers) {
      setWishlistKeys(new Set());
      setCompareKeys(new Set());
      return;
    }

    const [wishlistRes, compareRes] = await Promise.all([
      fetchProtected("/courseenglish/wishlist"),
      fetchProtected("/courseenglish/compare"),
    ]);

    if (wishlistRes.ok) {
      setWishlistKeys(new Set(wishlistRes.json?.keys || []));
    }
    if (compareRes.ok) {
      setCompareKeys(new Set(compareRes.json?.keys || []));
    }
  }, []);

  useEffect(() => {
    loadLists();
    if (typeof window === "undefined") return undefined;
    const onStorage = (event) => {
      if (event.key === "auth_token" || event.key === "auth_token_type") {
        loadLists();
      }
    };
    window.addEventListener("storage", onStorage);
    return () => window.removeEventListener("storage", onStorage);
  }, [loadLists]);

  const ensureAuthenticated = () => {
    if (typeof window === "undefined") return false;
    const token = localStorage.getItem("auth_token");
    if (token) return true;
    window.location.href = "/login";
    return false;
  };

  const toggleWishlist = useCallback(async (courseType, courseId) => {
    if (!ensureAuthenticated()) return false;
    const key = toKey(courseType, courseId);
    const exists = wishlistKeys.has(key);
    const path = exists ? "/courseenglish/wishlist/remove" : "/courseenglish/wishlist/add";
    const result = await fetchProtected(path, "POST", {
      course_type: courseType,
      course_id: Number(courseId),
    });
    if (!result.ok) return false;
    setWishlistKeys((prev) => {
      const next = new Set(prev);
      if (exists) next.delete(key);
      else next.add(key);
      return next;
    });
    return true;
  }, [wishlistKeys]);

  const toggleCompare = useCallback(async (courseType, courseId) => {
    if (!ensureAuthenticated()) return false;
    const key = toKey(courseType, courseId);
    const exists = compareKeys.has(key);
    const path = exists ? "/courseenglish/compare/remove" : "/courseenglish/compare/add";
    const result = await fetchProtected(path, "POST", {
      course_type: courseType,
      course_id: Number(courseId),
    });
    if (!result.ok) return false;
    setCompareKeys((prev) => {
      const next = new Set(prev);
      if (exists) next.delete(key);
      else next.add(key);
      return next;
    });
    return true;
  }, [compareKeys]);

  const value = useMemo(() => ({
    wishlistKeys,
    compareKeys,
    wishlistCount: wishlistKeys.size,
    compareCount: compareKeys.size,
    isInWishlist: (courseType, courseId) => wishlistKeys.has(toKey(courseType, courseId)),
    isInCompare: (courseType, courseId) => compareKeys.has(toKey(courseType, courseId)),
    toggleWishlist,
    toggleCompare,
    reloadInteractions: loadLists,
  }), [wishlistKeys, compareKeys, toggleWishlist, toggleCompare, loadLists]);

  return (
    <InteractionsContext.Provider value={value}>
      {children}
    </InteractionsContext.Provider>
  );
}

export function useCourseEnglishInteractions() {
  return useContext(InteractionsContext);
}

