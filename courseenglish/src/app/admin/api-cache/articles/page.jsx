"use client";

import { useEffect, useState } from "react";

const ARTICLE_CACHE_KEY = "cache_articles";
const CATEGORY_CACHE_KEY = "cache_categories";

export default function ApiCacheArticlesPage() {
  const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL;
  const ASSET_BASE = API_BASE ? API_BASE.replace(/\/api\/?$/, "/") : "";

  const [status, setStatus] = useState("");
  const [catStatus, setCatStatus] = useState("");
  const [loading, setLoading] = useState(false);
  const [catLoading, setCatLoading] = useState(false);
  const [count, setCount] = useState(0);
  const [catCount, setCatCount] = useState(0);

  const readCount = () => {
    try {
      const raw = localStorage.getItem(ARTICLE_CACHE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      setCount(Array.isArray(parsed?.data) ? parsed.data.length : 0);
    } catch {
      setCount(0);
    }
  };

  const readCatCount = () => {
    try {
      const raw = localStorage.getItem(CATEGORY_CACHE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      setCatCount(Array.isArray(parsed?.data) ? parsed.data.length : 0);
    } catch {
      setCatCount(0);
    }
  };

  useEffect(() => {
    readCount();
    readCatCount();
  }, []);

  const fetchArticles = async () => {
    if (!API_BASE) {
      setStatus("API base URL missing.");
      return;
    }
    try {
      setLoading(true);
      setStatus("Fetching articles...");
      const res = await fetch(`${API_BASE}blogs`);
      const json = await res.json();
      const items = Array.isArray(json?.data?.items)
        ? json.data.items
        : Array.isArray(json?.data)
        ? json.data
        : [];
      if (!res.ok || !Array.isArray(items) || !items.length) {
        throw new Error("No article data received.");
      }

      localStorage.setItem(
        ARTICLE_CACHE_KEY,
        JSON.stringify({ timestamp: Date.now(), data: items })
      );

      await fetch("/api/cache/articles", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ baseUrl: ASSET_BASE, data: items }),
      });

      setStatus("Articles cached successfully (local + file + images).");
      readCount();
    } catch (err) {
      setStatus(err?.message || "Failed to cache articles.");
    } finally {
      setLoading(false);
    }
  };

  const fetchCategories = async () => {
    if (!API_BASE) {
      setCatStatus("API base URL missing.");
      return;
    }
    try {
      setCatLoading(true);
      setCatStatus("Fetching categories...");
      const res = await fetch(`${API_BASE}categories`);
      const json = await res.json();
      const items = Array.isArray(json?.data?.items)
        ? json.data.items
        : Array.isArray(json?.data)
        ? json.data
        : [];
      if (!res.ok || !Array.isArray(items) || !items.length) {
        throw new Error("No category data received.");
      }

      localStorage.setItem(
        CATEGORY_CACHE_KEY,
        JSON.stringify({ timestamp: Date.now(), data: items })
      );

      await fetch("/api/cache/categories", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ data: items }),
      });

      setCatStatus("Categories cached successfully (local + file).");
      readCatCount();
    } catch (err) {
      setCatStatus(err?.message || "Failed to cache categories.");
    } finally {
      setCatLoading(false);
    }
  };

  const clearCategories = () => {
    try {
      localStorage.removeItem(CATEGORY_CACHE_KEY);
      setCatStatus("Category cache cleared.");
      setCatCount(0);
    } catch {
      setCatStatus("Failed to clear category cache.");
    }
  };

  const clearArticles = () => {
    try {
      localStorage.removeItem(ARTICLE_CACHE_KEY);
      setStatus("Article cache cleared.");
      setCount(0);
    } catch {
      setStatus("Failed to clear article cache.");
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-2">
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          Articles cache
        </p>
        <h1 className="text-2xl font-normal text-slate-900">
          Manage Articles page caches
        </h1>
        <p className="text-slate-600">
          Fetch from backend once, serve from stored cache. Clear to refetch.
        </p>
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              Articles
            </p>
            <p className="text-sm text-slate-600">
              Cached items:{" "}
              <span className="font-normal text-slate-900">{count}</span>
            </p>
          </div>
          <div className="flex gap-3">
            <button
              type="button"
              onClick={fetchArticles}
              disabled={loading}
              className="rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
            >
              {loading ? "Fetching..." : "Fetch & cache"}
            </button>
            <button
              type="button"
              onClick={clearArticles}
              className="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
            >
              Clear cache
            </button>
          </div>
        </div>
        {status ? (
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {status}
          </div>
        ) : null}
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              Categories
            </p>
            <p className="text-sm text-slate-600">
              Cached items:{" "}
              <span className="font-normal text-slate-900">{catCount}</span>
            </p>
          </div>
          <div className="flex gap-3">
            <button
              type="button"
              onClick={fetchCategories}
              disabled={catLoading}
              className="rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
            >
              {catLoading ? "Fetching..." : "Fetch & cache"}
            </button>
            <button
              type="button"
              onClick={clearCategories}
              className="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
            >
              Clear cache
            </button>
          </div>
        </div>
        {catStatus ? (
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {catStatus}
          </div>
        ) : null}
      </div>
    </div>
  );
}
