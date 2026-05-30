"use client";

import { useMemo, useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import { fetchAgentJson, useAgentApi } from "@/lib/agent-api";

export default function AgentStudents() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.agent.students.${key}`, fallback);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState("");
  const [showModal, setShowModal] = useState(false);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({ name: "", email: "", phone: "", country: "" });
  const [formError, setFormError] = useState("");

  const perPage = 10;
  const query = useMemo(
    () => `/agent/students?page=${page}&per_page=${perPage}${search ? `&search=${encodeURIComponent(search)}` : ""}`,
    [page, search]
  );

  const { data, loading, error, refetch } = useAgentApi(query);
  const students = data?.data || [];
  const total = data?.meta?.total || students.length;
  const totalPages = Math.max(1, Math.ceil(total / perPage));

  const changePage = (next) => {
    setPage((current) => Math.min(Math.max(1, next), totalPages));
  };

  const handleChange = (field) => (event) => {
    setForm((prev) => ({ ...prev, [field]: event.target.value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!form.name.trim() || !form.email.trim()) {
      setFormError(loc("error_required", "Name and email are required."));
      return;
    }

    setSaving(true);
    setFormError("");
    try {
      await fetchAgentJson("/agent/new-student", {
        method: "POST",
        body: JSON.stringify(form),
      });
      setForm({ name: "", email: "", phone: "", country: "" });
      setShowModal(false);
      setPage(1);
      await refetch();
    } catch (err) {
      setFormError(err.message || loc("error_add", "Failed to add student."));
    } finally {
      setSaving(false);
    }
  };

  const align = isRtl ? "text-right" : "text-left";

  return (
    <div className="space-y-6" dir={direction}>
      <div className={`flex flex-col gap-3 md:flex-row md:items-center md:justify-between ${align}`}>
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">{loc("eyebrow", "Students")}</p>
          <h1 className="text-2xl font-normal text-slate-900 md:text-3xl">{loc("title", "Manage your student list")}</h1>
        </div>
        <button
          type="button"
          onClick={() => setShowModal(true)}
          className="rounded-full bg-[#1277BE] px-5 py-2.5 text-sm font-normal text-white shadow-sm hover:bg-[#0f649f]"
        >
          {loc("add", "Add new student")}
        </button>
      </div>

      {error ? (
        <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">{loc("error", "Failed to load students.")}</div>
      ) : null}

      <div className="flex flex-wrap items-center gap-3">
        <input
          type="search"
          value={search}
          onChange={(event) => {
            setSearch(event.target.value);
            setPage(1);
          }}
          placeholder={loc("search", "Search by name or email")}
          className="w-full max-w-xs rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
        />
        <span className="text-sm text-slate-600">
          {loc("page", "Page")} {page} / {totalPages}
        </span>
        <div className="flex gap-2">
          <button
            type="button"
            className="rounded-full border border-slate-300 px-3 py-1 text-sm disabled:opacity-50"
            onClick={() => changePage(page - 1)}
            disabled={page === 1}
          >
            {loc("prev", "Prev")}
          </button>
          <button
            type="button"
            className="rounded-full border border-slate-300 px-3 py-1 text-sm disabled:opacity-50"
            onClick={() => changePage(page + 1)}
            disabled={page >= totalPages}
          >
            {loc("next", "Next")}
          </button>
        </div>
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        {loading ? (
          <div className="space-y-3">
            {Array.from({ length: 4 }).map((_, i) => (
              <div key={i} className="h-16 animate-pulse rounded-2xl bg-slate-100" />
            ))}
          </div>
        ) : students.length ? (
          <div className="space-y-3">
            {students.map((student) => (
              <div
                key={student.id}
                className="flex flex-col gap-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 md:flex-row md:items-center md:justify-between"
              >
                <div className={align}>
                  <p className="text-sm font-normal text-slate-900">{student.name}</p>
                  <p className="text-xs text-slate-500">
                    {student.email || loc("no_email", "No email")} •{" "}
                    {student.country || loc("unknown_country", "Unknown country")}
                  </p>
                </div>
                <div className="text-xs font-normal text-slate-600">
                  {student.onboarded_at
                    ? `${loc("onboarded", "Onboarded")} ${student.onboarded_at}`
                    : loc("pending_onboarding", "Pending onboarding")}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-sm text-slate-500">{loc("empty", "No students match your search.")}</div>
        )}
      </div>

      {showModal ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center px-4">
          <div className="absolute inset-0 bg-slate-900/50" onClick={() => setShowModal(false)} />
          <div className="relative z-10 w-full max-w-md rounded-3xl bg-white p-6 shadow-xl" dir={direction}>
            <div className={`flex items-center justify-between ${align}`}>
              <h2 className="text-lg font-normal text-slate-900">{loc("modal_title", "Add new student")}</h2>
              <button type="button" onClick={() => setShowModal(false)} className="text-sm text-slate-500">
                {loc("close", "Close")}
              </button>
            </div>
            <form className="mt-4 space-y-3" onSubmit={handleSubmit}>
              <input
                type="text"
                placeholder={loc("name", "Student name")}
                value={form.name}
                onChange={handleChange("name")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="email"
                placeholder={loc("email", "Email")}
                value={form.email}
                onChange={handleChange("email")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="tel"
                placeholder={loc("phone", "Phone")}
                value={form.phone}
                onChange={handleChange("phone")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="text"
                placeholder={loc("country", "Country")}
                value={form.country}
                onChange={handleChange("country")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              {formError ? <p className="text-sm text-red-600">{formError}</p> : null}
              <button
                type="submit"
                disabled={saving}
                className="w-full rounded-full bg-slate-900 px-4 py-2.5 text-sm font-normal text-white hover:bg-slate-800 disabled:opacity-70"
              >
                {saving ? loc("saving", "Saving...") : loc("save", "Save student")}
              </button>
            </form>
          </div>
        </div>
      ) : null}
    </div>
  );
}
