"use client";

import { useEffect, useState } from "react";

const API_BASE =
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_URL ||
  "/api";

const requestCache = new Map();

export const buildApiUrl = (path) => {
  if (!path) return API_BASE;
  if (path.startsWith("http://") || path.startsWith("https://")) {
    return path;
  }
  const base = API_BASE.endsWith("/") ? API_BASE.slice(0, -1) : API_BASE;
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${base}${suffix}`;
};

export const getImageUrl = (path) => {
  if (!path) return null;
  if (path.startsWith("http://") || path.startsWith("https://")) {
    return path;
  }
  // Remove /api from the end of API_BASE if present to get the root URL
  const baseUrl = API_BASE.replace(/\/api\/?$/, "");
  const suffix = path.startsWith("/") ? path : `/${path}`;
  return `${baseUrl}${suffix}`;
};

export const fetchApiJson = async (path, options = {}) => {
  const url = buildApiUrl(path);

  const headers = {
    Accept: "application/json",
    ...options.headers
  };

  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('auth_token');
    const tokenType = localStorage.getItem('auth_token_type') || 'Bearer';
    if (token) {
      headers.Authorization = `${tokenType} ${token}`;
    }
  }

  const fetchOptions = { ...options, headers };
  
  const method = fetchOptions.method ? fetchOptions.method.toUpperCase() : "GET";
  
  // Identify dynamic/authenticated paths that should never be cached
  const isDynamicPath = 
    url.includes("/student") || 
    url.includes("/agent") || 
    url.includes("/user") || 
    url.includes("/auth") || 
    url.includes("/wishlist") || 
    url.includes("/compare") || 
    url.includes("/booking");
  
  if (!fetchOptions.cache && !fetchOptions.next) {
    if (method === "GET" && !isDynamicPath) {
      fetchOptions.next = { revalidate: 3600 };
    } else {
      fetchOptions.cache = "no-store";
    }
  }

  const response = await fetch(url, fetchOptions);

  if (!response.ok) {
    // If 401, potentially clear token or handle redirect, but for now just throw
    if (response.status === 401 && typeof window !== 'undefined') {
      // Optional: localStorage.removeItem('auth_token');
    }
    throw new Error(`Request failed (${response.status})`);
  }

  return response.json();
};

export const useApi = (path) => {
  const [state, setState] = useState({
    data: null,
    loading: true,
    error: null,
  });

  useEffect(() => {
    if (!path) {
      setState({ data: null, loading: false, error: null });
      return undefined;
    }

    let active = true;
    let promise = requestCache.get(path);

    if (!promise) {
      promise = fetchApiJson(path);
      requestCache.set(path, promise);
    }

    promise
      .then((data) => {
        if (!active) return;
        setState({ data, loading: false, error: null });
      })
      .catch((error) => {
        if (!active) return;
        setState({ data: null, loading: false, error });
      });

    return () => {
      active = false;
    };
  }, [path]);

  return state;
};

export const formatCurrency = (value, currency = "GBP") => {
  if (value === null || value === undefined || Number.isNaN(Number(value))) {
    return null;
  }

  const numeric = Number(value);
  const symbol = currency === "SAR" ? "﷼" : "£";
  const amount = numeric.toLocaleString("en-GB", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });

  // Wrap in LTR marks so that in RTL contexts the symbol stays to the left of the number
  return `\u202A${symbol}${amount}\u202C`;
};
