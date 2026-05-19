"use client";

import { useEffect, useMemo, useState } from "react";
import { normalizeLang } from "./i18nFallback";
import { buildApiUrl } from "./courseenglishApi";

const defaultHeaders = () => {
  if (typeof localStorage === "undefined") return {};
  const token = localStorage.getItem("auth_token");
  const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
  return token ? { Authorization: `${tokenType} ${token}` } : {};
};

export async function fetchAgentJson(path, { signal, ...options } = {}) {
  const url = buildApiUrl(path);
  const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
    "Accept-Language": normalizeLang(
      typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar"
    ),
    ...defaultHeaders(),
    ...(options.headers || {}),
  };

  const res = await fetch(url, { ...options, headers, signal });
  const json = await res.json().catch(() => ({}));
  if (!res.ok || json?.success === false) {
    const message = json?.message || `Request failed (${res.status})`;
    const error = new Error(message);
    error.status = res.status;
    error.body = json;
    throw error;
  }
  return json;
}

export function useAgentApi(path, options = {}) {
  const { skip = false, ...fetchOptions } = options;
  const [state, setState] = useState({ data: null, loading: !skip, error: null });

  const refetch = useMemo(
    () => () => {
      if (!path || skip) {
        setState({ data: null, loading: false, error: null });
        return Promise.resolve(null);
      }
      setState((s) => ({ ...s, loading: true, error: null }));
      const controller = new AbortController();
      fetchAgentJson(path, { ...fetchOptions, signal: controller.signal })
        .then((json) => setState({ data: json, loading: false, error: null }))
        .catch((error) => {
          if (error.name === "AbortError") return;
          setState({ data: null, loading: false, error });
        });
      return () => controller.abort();
    },
    [path, skip, JSON.stringify(fetchOptions)]
  );

  useEffect(() => {
    const abort = refetch();
    return () => (typeof abort === "function" ? abort() : undefined);
  }, [refetch]);

  return { ...state, refetch };
}

export function useAgentUser() {
  const [user, setUser] = useState(null);

  useEffect(() => {
    try {
      const raw = localStorage.getItem("auth_user");
      setUser(raw ? JSON.parse(raw) : null);
    } catch {
      setUser(null);
    }
  }, []);

  return {
    user,
    name: user?.name || "",
    email: user?.email || "",
    role: user?.role || "",
  };
}
