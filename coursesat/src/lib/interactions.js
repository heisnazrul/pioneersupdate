"use client";

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
} from "react";
import { buildApiUrl } from "@/lib/api";
import { buildAuthUrl, getStoredAuthToken, getStoredAuthTokenType } from "@/lib/auth";
import {
  addGuestCompare,
  addGuestWishlist,
  guestCompareKeys,
  guestCompareWeeksMap,
  guestWishlistKeys,
  readGuestCompare,
  readGuestWishlist,
  removeGuestCompare,
  removeGuestWishlist,
  toInteractionKey,
} from "@/lib/guest-interactions";

const InteractionsContext = createContext({
  wishlistKeys: new Set(),
  compareKeys: new Set(),
  compareWeeksByKey: {},
  wishlistCount: 0,
  compareCount: 0,
  isAuthenticated: false,
  isInWishlist: () => false,
  isInCompare: () => false,
  toggleWishlist: async () => false,
  toggleCompare: async () => false,
  reloadInteractions: async () => {},
});

async function fetchProtected(path, method = "GET", body) {
  const token = getStoredAuthToken();
  if (!token) {
    return { ok: false, status: 401, json: { message: "Unauthenticated." } };
  }

  const response = await fetch(buildAuthUrl(path), {
    method,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `${getStoredAuthTokenType()} ${token}`,
    },
    body: body ? JSON.stringify(body) : undefined,
    cache: "no-store",
  });

  const contentType = response.headers.get("content-type") || "";
  const json = contentType.includes("application/json")
    ? await response.json()
    : { message: await response.text() };

  return { ok: response.ok, status: response.status, json };
}

function applyGuestState(setWishlistKeys, setCompareKeys, setCompareWeeksByKey) {
  setWishlistKeys(guestWishlistKeys());
  setCompareKeys(guestCompareKeys());
  setCompareWeeksByKey(guestCompareWeeksMap());
}

export function InteractionsProvider({ children }) {
  const [wishlistKeys, setWishlistKeys] = useState(new Set());
  const [compareKeys, setCompareKeys] = useState(new Set());
  const [compareWeeksByKey, setCompareWeeksByKey] = useState({});
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  const loadLists = useCallback(async () => {
    const token = getStoredAuthToken();
    setIsAuthenticated(Boolean(token));

    if (!token) {
      applyGuestState(setWishlistKeys, setCompareKeys, setCompareWeeksByKey);
      return;
    }

    const [wishlistRes, compareRes] = await Promise.all([
      fetchProtected("/wishlist"),
      fetchProtected("/compare"),
    ]);

    if (wishlistRes.ok) {
      setWishlistKeys(new Set(wishlistRes.json?.keys || []));
    }

    if (compareRes.ok) {
      const items = Array.isArray(compareRes.json?.items) ? compareRes.json.items : [];
      const keys = items.map((item) => toInteractionKey(item.course_type, item.course_id));
      const weeksMap = {};
      items.forEach((item) => {
        weeksMap[toInteractionKey(item.course_type, item.course_id)] = Number(item.weeks) || 12;
      });
      setCompareKeys(new Set(keys.length ? keys : (compareRes.json?.keys || [])));
      setCompareWeeksByKey(weeksMap);
    }
  }, []);

  useEffect(() => {
    loadLists();

    if (typeof window === "undefined") return undefined;

    const onAuthUpdate = () => loadLists();
    const onGuestUpdate = () => {
      if (!getStoredAuthToken()) {
        applyGuestState(setWishlistKeys, setCompareKeys, setCompareWeeksByKey);
      }
    };
    const onStorage = (event) => {
      if (event.key === "auth_token" || event.key === "auth_token_type" || event.key === "auth_user") {
        loadLists();
      }
      if (event.key === "coursesat_guest_wishlist" || event.key === "coursesat_guest_compare") {
        onGuestUpdate();
      }
    };

    window.addEventListener("auth-update", onAuthUpdate);
    window.addEventListener("guest-interactions-update", onGuestUpdate);
    window.addEventListener("storage", onStorage);

    return () => {
      window.removeEventListener("auth-update", onAuthUpdate);
      window.removeEventListener("guest-interactions-update", onGuestUpdate);
      window.removeEventListener("storage", onStorage);
    };
  }, [loadLists]);

  const toggleWishlist = useCallback(async (courseType, courseId) => {
    const key = toInteractionKey(courseType, courseId);
    const token = getStoredAuthToken();

    if (!token) {
      const exists = guestWishlistKeys().has(key);
      if (exists) {
        removeGuestWishlist(courseType, courseId);
      } else {
        addGuestWishlist(courseType, courseId);
      }
      applyGuestState(setWishlistKeys, setCompareKeys, setCompareWeeksByKey);
      return true;
    }

    const exists = wishlistKeys.has(key);
    const path = exists ? "/wishlist/remove" : "/wishlist/add";
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

  const toggleCompare = useCallback(async (courseType, courseId, weeks = 12) => {
    const key = toInteractionKey(courseType, courseId);
    const token = getStoredAuthToken();

    if (!token) {
      const exists = guestCompareKeys().has(key);
      if (exists) {
        removeGuestCompare(courseType, courseId);
      } else {
        addGuestCompare(courseType, courseId, weeks);
      }
      applyGuestState(setWishlistKeys, setCompareKeys, setCompareWeeksByKey);
      return true;
    }

    const exists = compareKeys.has(key);
    const path = exists ? "/compare/remove" : "/compare/add";
    const payload = {
      course_type: courseType,
      course_id: Number(courseId),
    };

    if (!exists) {
      payload.weeks = Math.max(1, Number(weeks) || 12);
    }

    const result = await fetchProtected(path, "POST", payload);
    if (!result.ok) return false;

    setCompareKeys((prev) => {
      const next = new Set(prev);
      if (exists) next.delete(key);
      else next.add(key);
      return next;
    });

    setCompareWeeksByKey((prev) => {
      const next = { ...prev };
      if (exists) {
        delete next[key];
      } else {
        next[key] = Math.max(1, Number(weeks) || 12);
      }
      return next;
    });

    return true;
  }, [compareKeys]);

  const value = useMemo(() => ({
    wishlistKeys,
    compareKeys,
    compareWeeksByKey,
    wishlistCount: wishlistKeys.size,
    compareCount: compareKeys.size,
    isAuthenticated,
    isInWishlist: (courseType, courseId) => wishlistKeys.has(toInteractionKey(courseType, courseId)),
    isInCompare: (courseType, courseId) => compareKeys.has(toInteractionKey(courseType, courseId)),
    toggleWishlist,
    toggleCompare,
    reloadInteractions: loadLists,
  }), [wishlistKeys, compareKeys, compareWeeksByKey, isAuthenticated, toggleWishlist, toggleCompare, loadLists]);

  return (
    <InteractionsContext.Provider value={value}>
      {children}
    </InteractionsContext.Provider>
  );
}

export function useCourseEnglishInteractions() {
  return useContext(InteractionsContext);
}

export async function resolveGuestCompareItems() {
  const compare = readGuestCompare();
  if (!compare.length) {
    return [];
  }

  const response = await fetch(buildApiUrl("/courseenglish/interactions/resolve"), {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ compare }),
    cache: "no-store",
  });

  const json = await response.json().catch(() => ({}));
  if (!response.ok) {
    throw new Error(json?.message || "Failed to load compare list");
  }

  return (json?.compare?.items || []).filter((item) => item?.course?.id);
}

export { readGuestCompare, readGuestWishlist, removeGuestCompare, removeGuestWishlist };
