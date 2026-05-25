"use client";

import { useState, useCallback } from "react";

export function useCourseEnglishInteractions() {
  const [wishlist, setWishlist] = useState(() => {
    if (typeof window !== "undefined") {
      try {
        const w = localStorage.getItem("wishlist_keys");
        return w ? JSON.parse(w) : [];
      } catch (e) {
        console.error("Failed to parse interactions storage", e);
      }
    }
    return [];
  });

  const [compare, setCompare] = useState(() => {
    if (typeof window !== "undefined") {
      try {
        const c = localStorage.getItem("compare_keys");
        return c ? JSON.parse(c) : [];
      } catch (e) {
        console.error("Failed to parse interactions storage", e);
      }
    }
    return [];
  });

  const isInWishlist = useCallback((type, id) => {
    const key = `${type}:${id}`;
    return wishlist.includes(key);
  }, [wishlist]);

  const isInCompare = useCallback((type, id) => {
    const key = `${type}:${id}`;
    return compare.includes(key);
  }, [compare]);

  const toggleWishlist = useCallback((type, id) => {
    const key = `${type}:${id}`;
    setWishlist((prev) => {
      const next = prev.includes(key)
        ? prev.filter((k) => k !== key)
        : [...prev, key];
      if (typeof window !== "undefined") {
        localStorage.setItem("wishlist_keys", JSON.stringify(next));
      }
      return next;
    });
  }, []);

  const toggleCompare = useCallback((type, id) => {
    const key = `${type}:${id}`;
    setCompare((prev) => {
      const next = prev.includes(key)
        ? prev.filter((k) => k !== key)
        : [...prev, key];
      if (typeof window !== "undefined") {
        localStorage.setItem("compare_keys", JSON.stringify(next));
      }
      return next;
    });
  }, []);

  return {
    isInWishlist,
    isInCompare,
    toggleWishlist,
    toggleCompare,
  };
}
