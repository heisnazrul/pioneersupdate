"use client";

import { useCallback, useEffect, useState } from "react";

import { buildApiUrl } from "@/lib/api";

export async function fetchAgentJson(path, options = {}) {
  const normalized = path.startsWith("/") ? path : `/${path}`;
  const url = buildApiUrl(normalized);

  const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
    ...options.headers,
  };

  if (typeof window !== "undefined") {
    const token = localStorage.getItem("auth_token");
    const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
    if (token) {
      headers.Authorization = `${tokenType} ${token}`;
    }
  }

  const response = await fetch(url, { ...options, headers, cache: "no-store" });
  const json = await response.json().catch(() => ({}));

  if (!response.ok || json?.success === false) {
    const error = new Error(json?.message || `Request failed (${response.status})`);
    error.status = response.status;
    error.body = json;
    throw error;
  }

  return json;
}

export function useAgentApi(path) {
  const [state, setState] = useState({
    data: null,
    loading: !!path,
    error: null,
  });

  const refetch = useCallback(() => {
    if (!path) {
      setState({ data: null, loading: false, error: null });
      return Promise.resolve(null);
    }

    setState((prev) => ({ ...prev, loading: true, error: null }));

    return fetchAgentJson(path)
      .then((data) => {
        setState({ data, loading: false, error: null });
        return data;
      })
      .catch((error) => {
        setState({ data: null, loading: false, error });
        throw error;
      });
  }, [path]);

  useEffect(() => {
    let active = true;

    if (!path) {
      setState({ data: null, loading: false, error: null });
      return undefined;
    }

    setState((prev) => ({ ...prev, loading: true, error: null }));

    fetchAgentJson(path)
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

  return { user };
}
