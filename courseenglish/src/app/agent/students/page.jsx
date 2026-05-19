"use client";

import { useMemo, useState } from "react";
import { useAgentApi, fetchAgentJson } from "@/lib/agentApi";
import { pickLang } from "@/lib/i18nFallback";
import AlertBanner from "@/app/agent/components/AlertBanner";

export default function AgentStudentsPage() {
  const [page, setPage] = useState(1);
  const [perPage] = useState(10);
  const [search, setSearch] = useState("");
  const [saving, setSaving] = useState(false);
  const [showModal, setShowModal] = useState(false);
  const [form, setForm] = useState({ name: "", email: "", phone: "", country: "" });
  const query = useMemo(
    () => `/agent/students?page=${page}&per_page=${perPage}${search ? `&search=${encodeURIComponent(search)}` : ""}`,
    [page, perPage, search]
  );

  const { data, loading, error, refetch } = useAgentApi(query);
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";
  const students = data?.data || [];
  const total = data?.meta?.total || students.length;
  const totalPages = Math.max(1, Math.ceil(total / perPage));

  const changePage = (next) => {
    setPage((p) => Math.min(Math.max(1, next), totalPages));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!form.name.trim() || !form.email.trim()) return;
    try {
      setSaving(true);
      const res = await fetchAgentJson("/agent/new-student", {
        method: "POST",
        body: JSON.stringify(form),
      });
      if (res?.data) {
        setForm({ name: "", email: "", phone: "", country: "" });
        setShowModal(false);
        refetch();
      }
    } catch (err) {
      alert(pickLang(lang, "Failed to add student.", "تعذر إضافة الطالب."));
    } finally {
      setSaving(false);
    }
  };

  const handleChange = (field) => (event) => setForm((prev) => ({ ...prev, [field]: event.target.value }));

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
            {pickLang(lang, "Students", "الطلاب")}
          </p>
          <h1 className="text-2xl font-normal text-slate-900">
            {pickLang(lang, "Manage your student list", "إدارة قائمة الطلاب")}
          </h1>
        </div>
        <button
          type="button"
          onClick={() => setShowModal(true)}
          className="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-normal text-white shadow-sm transition hover:bg-slate-800"
        >
          {pickLang(lang, "Add new student", "إضافة طالب جديد")}
        </button>
      </div>

      {error ? (
        <AlertBanner
          tone="error"
          message={pickLang(lang, "Failed to load students.", "تعذر تحميل الطلاب.")}
          onRetry={refetch}
          dir={isArabic ? "rtl" : "ltr"}
        />
      ) : null}

      <div className="flex flex-wrap gap-3 items-center">
        <input
          type="search"
          value={search}
          onChange={(e) => {
            setSearch(e.target.value);
            setPage(1);
          }}
          placeholder={pickLang(lang, "Search by name or email", "ابحث بالاسم أو البريد")}
          placeholder={pickLang(lang, "Search by name or email", "ابحث بالاسم أو البريد")}
          className="w-full max-w-xs rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
        />
        <div className="flex items-center gap-2 text-sm text-slate-600">
          {pickLang(lang, "Page", "الصفحة")} {page} / {totalPages}
        </div>
        <div className="flex gap-2">
          <button
            className="rounded-full border border-slate-300 px-3 py-1 text-sm"
            onClick={() => changePage(page - 1)}
            disabled={page === 1}
          >
            {pickLang(lang, "Prev", "السابق")}
          </button>
          <button
            className="rounded-full border border-slate-300 px-3 py-1 text-sm"
            onClick={() => changePage(page + 1)}
            disabled={page >= totalPages}
          >
            {pickLang(lang, "Next", "التالي")}
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
                <div>
                  <p className="text-sm font-normal text-slate-900">
                    {student.name}
                  </p>
                  <p className="text-xs text-slate-500">
                    {student.email || pickLang(lang, "No email", "بلا بريد")} •{" "}
                    {student.country || pickLang(lang, "Unknown country", "دولة غير معروفة")}
                  </p>
                </div>
                <div className="text-xs font-normal text-slate-600">
                  {student.onboarded_at
                    ? `${pickLang(lang, "Onboarded", "تم الانضمام")} ${student.onboarded_at}`
                    : pickLang(lang, "Pending onboarding", "قيد الانضمام")}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-sm text-slate-500">
            {pickLang(lang, "No students match your search.", "لا يوجد طلاب يطابقون البحث.")}
          </div>
        )}
      </div>

      {showModal ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center px-4">
          <div
            className="absolute inset-0 bg-slate-900/50"
            onClick={() => setShowModal(false)}
          />
          <div className="relative z-10 w-full max-w-md rounded-3xl bg-white p-6 shadow-xl">
            <div className="flex items-center justify-between">
              <h2 className="text-lg font-normal text-slate-900">
                {pickLang(lang, "Add new student", "إضافة طالب جديد")}
              </h2>
              <button
                type="button"
                onClick={() => setShowModal(false)}
                className="text-sm font-normal text-slate-500"
              >
                {pickLang(lang, "Close", "إغلاق")}
              </button>
            </div>
            <form className="mt-4 space-y-3" onSubmit={handleSubmit}>
              <input
                type="text"
                placeholder={pickLang(lang, "Student name", "اسم الطالب")}
                value={form.name}
                onChange={handleChange("name")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="email"
                placeholder={pickLang(lang, "Email", "البريد الإلكتروني")}
                value={form.email}
                onChange={handleChange("email")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="tel"
                placeholder={pickLang(lang, "Phone", "رقم الهاتف")}
                value={form.phone}
                onChange={handleChange("phone")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <input
                type="text"
                placeholder={pickLang(lang, "Country", "الدولة")}
                value={form.country}
                onChange={handleChange("country")}
                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 outline-none focus:border-slate-300"
              />
              <button
                type="submit"
                disabled={saving}
                className="w-full rounded-full bg-slate-900 px-4 py-2.5 text-sm font-normal text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
              >
                {saving
                  ? pickLang(lang, "Saving...", "جاري الحفظ...")
                  : pickLang(lang, "Save student", "حفظ الطالب")}
              </button>
            </form>
          </div>
        </div>
      ) : null}
    </div>
  );
}
