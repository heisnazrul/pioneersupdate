"use client";

import { useEffect, useState } from "react";

const HOME_FIELDS = {
  header: [
    { key: "navHome", label: "Header: Home" },
    { key: "navOffers", label: "Header: Offers" },
    { key: "navContact", label: "Header: Contact" },
  ],
  hero: [
    { key: "title", label: "Hero Title" },
    { key: "subtitle", label: "Hero Subtitle" },
  ],
  stats: [
    { key: "title", label: "Stats Title" },
    { key: "line1", label: "Stats Line 1" },
    { key: "line2", label: "Stats Line 2" },
  ],
  blogs: [{ key: "title", label: "Blogs Title" }],
  faqs: [
    { key: "title", label: "FAQ Title" },
    { key: "subtitle", label: "FAQ Subtitle" },
  ],
  footer: [{ key: "tagline", label: "Footer Tagline" }],
};

export default function TranslationPage() {
  const [translations, setTranslations] = useState({ en: {}, ar: {} });
  const [page, setPage] = useState("home");
  const [status, setStatus] = useState("");
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const load = async () => {
      try {
        const res = await fetch("/api/translations", { cache: "no-store" });
        const json = await res.json();
        if (json?.success && json.data) {
          setTranslations(json.data);
        }
      } catch {
        setStatus("Failed to load translations.");
      }
    };
    load();
  }, []);

  const handleChange = (section, key, value, langKey) => {
    const activeLang = langKey || "en";
    setTranslations((prev) => ({
      ...prev,
      [page]: {
        ...(prev[page] || {}),
        [activeLang]: {
          ...((prev[page] || {})[activeLang] || {}),
          [section]: {
            ...(((prev[page] || {})[activeLang] || {})[section] || {}),
            [key]: value,
          },
        },
      },
    }));
  };

  const save = async () => {
    try {
      setLoading(true);
      setStatus("Saving...");
    const res = await fetch("/api/translations", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(translations),
      });
      const json = await res.json();
      if (!res.ok || !json?.success) {
        throw new Error(json?.message || "Failed to save");
      }
      setStatus("Saved successfully.");
    } catch (err) {
      setStatus(err.message || "Failed to save translations.");
    } finally {
      setLoading(false);
    }
  };

  const tEn = (translations[page] || {}).en || {};
  const tAr = (translations[page] || {}).ar || {};

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
            Translation
          </p>
          <h1 className="text-2xl font-normal text-slate-900">
            Static text translations
          </h1>
          <p className="text-slate-600">
            Edit translations per page/section. Save to persist in the translation store.
          </p>
        </div>
        <div className="flex gap-2">
          <button
            type="button"
            onClick={() => setPage("home")}
            className={`rounded-full px-4 py-2 text-sm font-normal ${
              page === "home"
                ? "bg-slate-900 text-white"
                : "bg-slate-100 text-slate-700"
            }`}
          >
            Home
          </button>
          {/* Future pages go here */}
        </div>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        {Object.entries(HOME_FIELDS).map(([section, fields]) => (
          <div
            key={section}
            className="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm"
          >
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-normal text-slate-900">
                {section.toUpperCase()}
              </h2>
            </div>
            <div className="space-y-3">
              {fields.map((f) => (
                <div key={f.key} className="space-y-2">
                  <p className="text-xs font-normal text-slate-600">
                    {f.label}
                  </p>
                  <div className="grid gap-2 md:grid-cols-2">
                    <input
                      type="text"
                      placeholder="English"
                      value={tEn?.[section]?.[f.key] || ""}
                      onChange={(e) =>
                        handleChange(section, f.key, e.target.value, "en")
                      }
                      className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-800 outline-none focus:border-slate-300"
                    />
                    <input
                      type="text"
                      placeholder="Arabic"
                      value={tAr?.[section]?.[f.key] || ""}
                      onChange={(e) =>
                        handleChange(section, f.key, e.target.value, "ar")
                      }
                      className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-800 outline-none focus:border-slate-300"
                    />
                  </div>
                </div>
              ))}
            </div>
          </div>
        ))}
      </div>

      <div className="flex items-center gap-3">
        <button
          type="button"
          onClick={save}
          disabled={loading}
          className="rounded-full bg-slate-900 px-5 py-3 text-sm font-normal text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
        >
          {loading ? "Saving..." : "Save translations"}
        </button>
        {status ? (
          <span className="text-sm text-slate-600">{status}</span>
        ) : null}
      </div>
    </div>
  );
}
