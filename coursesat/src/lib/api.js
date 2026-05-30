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

export const getBackendOrigin = () => {
  const configured = process.env.NEXT_PUBLIC_BACKEND_URL || process.env.NEXT_PUBLIC_API_ORIGIN;
  if (configured) {
    return configured.replace(/\/$/, "");
  }

  const base = API_BASE.replace(/\/api\/?$/, "");
  if (base.startsWith("http://") || base.startsWith("https://")) {
    return base.replace(/\/$/, "");
  }

  return "http://127.0.0.1:8000";
};

export const getImageUrl = (path) => {
  if (!path) return null;

  const normalized = String(path).trim();
  if (!normalized) return null;

  if (normalized.startsWith("/assets/") || normalized.startsWith("assets/")) {
    return normalized.startsWith("/") ? normalized : `/${normalized}`;
  }

  const storageIndex = normalized.indexOf("/storage/");
  if (storageIndex !== -1) {
    return normalized.slice(storageIndex);
  }

  if (normalized.startsWith("storage/")) {
    return `/${normalized}`;
  }

  if (normalized.startsWith("http://") || normalized.startsWith("https://")) {
    const fixed = normalized.replace(/^https?:\/\/localhost(?=\/)/, getBackendOrigin());
    const fixedStorageIndex = fixed.indexOf("/storage/");
    if (fixedStorageIndex !== -1) {
      return fixed.slice(fixedStorageIndex);
    }
    return fixed;
  }

  if (normalized.startsWith("/")) {
    return normalized.startsWith("/storage/") ? normalized : `${getBackendOrigin()}${normalized}`;
  }

  return `/storage/${normalized.replace(/^\/+/, "")}`;
};

export const fetchApiJson = async (path, options = {}) => {
  const url = buildApiUrl(path);

  const headers = {
    Accept: "application/json",
    ...options.headers
  };

  if (typeof window !== "undefined") {
    const token = localStorage.getItem("auth_token");
    const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
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
    url.includes("/booking") ||
    url.includes("/utilities") ||
    url.includes("/coursesat/home") ||
    url.includes("/coursesat/language-institutes");
  
  if (!fetchOptions.cache && !fetchOptions.next) {
    if (method === "GET" && !isDynamicPath) {
      fetchOptions.next = { revalidate: 3600 };
    } else {
      fetchOptions.cache = "no-store";
    }
  }

  const response = await fetch(url, fetchOptions);

  if (!response.ok) {
    throw new Error(`Request failed (${response.status})`);
  }

  return response.json();
};

export const useApi = (path) => {
  const [state, setState] = useState({
    data: null,
    loading: !!path,
    error: null,
  });

  const [prevPath, setPrevPath] = useState(path);

  if (path !== prevPath) {
    setPrevPath(path);
    setState({
      data: null,
      loading: !!path,
      error: null,
    });
  }

  useEffect(() => {
    if (!path) {
      return undefined;
    }

    let active = true;
    const skipCache =
      path.includes("/coursesat/home") || path.includes("/coursesat/language-institutes");
    let promise = skipCache ? null : requestCache.get(path);

    if (!promise) {
      promise = fetchApiJson(path).catch((error) => {
        requestCache.delete(path);
        throw error;
      });

      if (!skipCache) {
        requestCache.set(path, promise);
      }
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
