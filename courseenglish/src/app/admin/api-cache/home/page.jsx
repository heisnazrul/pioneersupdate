 "use client";

import { useEffect, useState } from "react";

const BLOG_CACHE_KEY = "cache_blogs";
const REVIEW_CACHE_KEY = "cache_reviews";
const FAQ_CACHE_KEY = "cache_faqs";

export default function ApiCacheHomePage() {
  const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL;
  const ASSET_BASE = API_BASE ? API_BASE.replace(/\/api\/?$/, "/") : "";
  const [blogStatus, setBlogStatus] = useState("");
  const [reviewStatus, setReviewStatus] = useState("");
  const [faqStatus, setFaqStatus] = useState("");
  const [blogLoading, setBlogLoading] = useState(false);
  const [reviewLoading, setReviewLoading] = useState(false);
  const [faqLoading, setFaqLoading] = useState(false);
  const [cachedCount, setCachedCount] = useState(0);
  const [reviewCount, setReviewCount] = useState(0);
  const [faqCount, setFaqCount] = useState(0);

  const readCacheCount = () => {
    try {
      const raw = localStorage.getItem(BLOG_CACHE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      setCachedCount(Array.isArray(parsed?.data) ? parsed.data.length : 0);
    } catch {
      setCachedCount(0);
    }
  };
  const readReviewCacheCount = () => {
    try {
      const raw = localStorage.getItem(REVIEW_CACHE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      setReviewCount(Array.isArray(parsed?.data) ? parsed.data.length : 0);
    } catch {
      setReviewCount(0);
    }
  };
  const readFaqCacheCount = () => {
    try {
      const raw = localStorage.getItem(FAQ_CACHE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      setFaqCount(Array.isArray(parsed?.data) ? parsed.data.length : 0);
    } catch {
      setFaqCount(0);
    }
  };

  useEffect(() => {
    readCacheCount();
    readReviewCacheCount();
    readFaqCacheCount();
  }, []);

  const fetchBlogs = async () => {
    if (!API_BASE) {
      setBlogStatus("API base URL missing.");
      return;
    }
    try {
      setBlogLoading(true);
      setBlogStatus("Fetching blogs...");
      const res = await fetch(`${API_BASE}home/blogs`);
      const json = await res.json();
      const payload = Array.isArray(json?.data) ? json.data : json;
      if (!res.ok || !Array.isArray(payload) || !payload.length) {
        throw new Error("No data received.");
      }
      localStorage.setItem(BLOG_CACHE_KEY, JSON.stringify({ timestamp: Date.now(), data: payload }));
      await fetch("/api/cache/blogs", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ baseUrl: ASSET_BASE, data: payload }),
      });
      setBlogStatus("Blogs cached successfully (local + file + images).");
      readCacheCount();
    } catch (err) {
      setBlogStatus(err.message || "Failed to fetch.");
    } finally {
      setBlogLoading(false);
    }
  };

  const clearBlogs = () => {
    try {
      localStorage.removeItem(BLOG_CACHE_KEY);
      setBlogStatus("Blog cache cleared.");
      setCachedCount(0);
    } catch {
      setBlogStatus("Failed to clear cache.");
    }
  };

  const fetchReviews = async () => {
    if (!API_BASE) {
      setReviewStatus("API base URL missing.");
      return;
    }
    try {
      setReviewLoading(true);
      setReviewStatus("Fetching reviews...");
      const res = await fetch(`${API_BASE}home/reviews`);
      const json = await res.json();
      const payload = Array.isArray(json?.data) ? json.data : json;
      if (!res.ok || !Array.isArray(payload) || !payload.length) {
        throw new Error("No review data received.");
      }
      localStorage.setItem(REVIEW_CACHE_KEY, JSON.stringify({ timestamp: Date.now(), data: payload }));
      await fetch("/api/cache/reviews", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      setReviewStatus("Reviews cached successfully (local + file).");
      readReviewCacheCount();
    } catch (err) {
      setReviewStatus(err.message || "Failed to fetch reviews.");
    } finally {
      setReviewLoading(false);
    }
  };

  const clearReviews = () => {
    try {
      localStorage.removeItem(REVIEW_CACHE_KEY);
      setReviewStatus("Review cache cleared.");
      setReviewCount(0);
    } catch {
      setReviewStatus("Failed to clear review cache.");
    }
  };

  const fetchFaqs = async () => {
    if (!API_BASE) {
      setFaqStatus("API base URL missing.");
      return;
    }
    try {
      setFaqLoading(true);
      setFaqStatus("Fetching FAQs...");
      const res = await fetch(`${API_BASE}home/faqs`);
      const json = await res.json();
      const payload = Array.isArray(json?.data) ? json.data : json;
      if (!res.ok || !Array.isArray(payload) || !payload.length) {
        throw new Error("No FAQ data received.");
      }
      localStorage.setItem(FAQ_CACHE_KEY, JSON.stringify({ timestamp: Date.now(), data: payload }));
      await fetch("/api/cache/faqs", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      setFaqStatus("FAQs cached successfully (local + file).");
      readFaqCacheCount();
    } catch (err) {
      setFaqStatus(err.message || "Failed to fetch FAQs.");
    } finally {
      setFaqLoading(false);
    }
  };

  const clearFaqs = () => {
    try {
      localStorage.removeItem(FAQ_CACHE_KEY);
      setFaqStatus("FAQ cache cleared.");
      setFaqCount(0);
    } catch {
      setFaqStatus("Failed to clear FAQ cache.");
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-2">
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          Home cache
        </p>
        <h1 className="text-2xl font-normal text-slate-900">
          Manage Home page caches
        </h1>
        <p className="text-slate-600">
          Fetch once, serve from cache. Clear to force fresh calls.
        </p>
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              Blogs
            </p>
            <p className="text-sm text-slate-600">
              Cached items: <span className="font-normal text-slate-900">{cachedCount}</span>
            </p>
          </div>
          <div className="flex gap-3">
            <button
              type="button"
              onClick={fetchBlogs}
              disabled={blogLoading}
              className="rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
            >
              {blogLoading ? "Fetching..." : "Fetch & cache"}
            </button>
            <button
              type="button"
              onClick={clearBlogs}
              className="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
            >
              Clear cache
            </button>
          </div>
        </div>
        {blogStatus ? (
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {blogStatus}
          </div>
        ) : null}
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              Reviews
            </p>
            <p className="text-sm text-slate-600">
              Cached items: <span className="font-normal text-slate-900">{reviewCount}</span>
            </p>
          </div>
          <div className="flex gap-3">
            <button
              type="button"
              onClick={fetchReviews}
              disabled={reviewLoading}
              className="rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
            >
              {reviewLoading ? "Fetching..." : "Fetch & cache"}
            </button>
            <button
              type="button"
              onClick={clearReviews}
              className="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
            >
              Clear cache
            </button>
          </div>
        </div>
        {reviewStatus ? (
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {reviewStatus}
          </div>
        ) : null}
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              FAQs
            </p>
            <p className="text-sm text-slate-600">
              Cached items: <span className="font-normal text-slate-900">{faqCount}</span>
            </p>
          </div>
          <div className="flex gap-3">
            <button
              type="button"
              onClick={fetchFaqs}
              disabled={faqLoading}
              className="rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
            >
              {faqLoading ? "Fetching..." : "Fetch & cache"}
            </button>
            <button
              type="button"
              onClick={clearFaqs}
              className="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
            >
              Clear cache
            </button>
          </div>
        </div>
        {faqStatus ? (
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {faqStatus}
          </div>
        ) : null}
      </div>
    </div>
  );
}
