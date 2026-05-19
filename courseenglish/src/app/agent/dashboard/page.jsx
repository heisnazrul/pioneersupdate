"use client";

import { useMemo } from "react";
import { useAgentApi, useAgentUser } from "@/lib/agentApi";
import AlertBanner from "@/app/agent/components/AlertBanner";
import { pickLang } from "@/lib/i18nFallback";

export default function AgentDashboardPage() {
  const { user } = useAgentUser();
  const { data: overviewData, loading: overviewLoading, error: agentError, refetch: refetchAgent } =
    useAgentApi("/agent/overview");
  const { data: agentData } = useAgentApi("/agent/me");
  const {
    data: studentsData,
    loading: studentsLoading,
    error: studentsError,
    refetch: refetchStudents,
  } = useAgentApi("/agent/students?per_page=5");
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";

  const agent = agentData?.data || {};
  const overview = overviewData?.data || {};
  const students = studentsData?.data || [];

  const stats = useMemo(
    () => [
      {
        label: pickLang(lang, "Total students", "إجمالي الطلاب"),
        value: overview.students_total ?? students.length ?? "—",
        change: pickLang(lang, "All time", "كل الوقت"),
      },
      {
        label: pickLang(lang, "Active referrals", "إحالات نشطة"),
        value: overview.referrals_active ?? (agent.referral_code ? 1 : 0),
        change: pickLang(lang, "Active", "نشط"),
      },
      {
        label: pickLang(lang, "Commission", "العمولة"),
        value: overview.commission_percent ? `${overview.commission_percent}%` : (agent.commission_percent ? `${agent.commission_percent}%` : "—"),
        change: pickLang(lang, "Rate", "النسبة"),
      },
      {
        label: pickLang(lang, "Status", "الحالة"),
        value: overview.status ? overview.status.toUpperCase() : (agent.status ? agent.status.toUpperCase() : "—"),
        change: pickLang(lang, "Account", "الحساب"),
      },
    ],
    [agent, students.length, lang]
  );

  return (
    <div className="space-y-8">
      <div className="flex flex-col gap-3">
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          {pickLang(lang, "Agent dashboard", "لوحة الوكلاء")}
        </p>
        <h1 className="text-2xl font-normal text-slate-900">
          {pickLang(lang, "Welcome back", "مرحباً بعودتك")}
          {user?.name ? `، ${user.name}` : " "}
        </h1>
        <p className="max-w-3xl text-slate-600">
          {pickLang(
            lang,
            "Manage students, referrals, and commission details from one workspace.",
            "إدارة الطلاب والإحالات وتفاصيل العمولات من مكان واحد."
          )}
        </p>
        <div className="flex flex-wrap gap-3">
          <button className="rounded-full bg-[#1277BE] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f649f]">
            {pickLang(lang, "Add student", "إضافة طالب")}
          </button>
          <button className="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50">
            {pickLang(lang, "Share referral link", "مشاركة رابط الإحالة")}
          </button>
        </div>
        {(agentError || studentsError) && (
          <AlertBanner
            tone="error"
            message={pickLang(lang, "Failed to load data.", "تعذر تحميل البيانات.")}
            onRetry={() => {
              refetchAgent();
              refetchStudents();
            }}
            dir={isArabic ? "rtl" : "ltr"}
          />
        )}
      </div>

      <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {stats.map((stat) => (
          <div
            key={stat.label}
            className="rounded-2xl border border-slate-200 bg-white px-4 py-5 shadow-[0_14px_48px_-32px_rgba(15,23,42,0.35)]"
          >
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-500">
              {stat.label}
            </p>
            <p className="pt-3 text-3xl font-normal text-slate-900">
              {overviewLoading ? "…" : stat.value}
            </p>
            <p className="text-sm font-normal text-emerald-600">
              {stat.change}
            </p>
          </div>
        ))}
      </section>

      <section className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_22px_60px_-36px_rgba(15,23,42,0.35)]">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
                {pickLang(lang, "Your students", "طلابك")}
              </p>
              <h2 className="text-lg font-normal text-slate-900">
                {pickLang(lang, "Latest onboarded", "أحدث المنضمين")}
              </h2>
            </div>
            <span className="rounded-full bg-slate-100 px-3 py-2 text-xs font-normal text-slate-700">
              {students.length} {pickLang(lang, "total", "إجمالي")}
            </span>
          </div>
          <div className="space-y-3">
            {studentsLoading ? (
              Array.from({ length: 3 }).map((_, i) => (
                <div
                  key={i}
                  className="h-16 animate-pulse rounded-2xl border border-slate-100 bg-slate-50"
                />
              ))
            ) : students.length ? (
              students.slice(0, 5).map((student) => (
                <div
                  key={student.id}
                  className="flex flex-col gap-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 md:flex-row md:items-center md:justify-between"
                >
                  <div>
                    <p className="text-sm font-normal text-slate-900">
                      {student.name}
                    </p>
                    <p className="text-xs text-slate-500">
                      {(student.email || pickLang(lang, "No email", "بلا بريد"))} •{" "}
                      {student.country || pickLang(lang, "Unknown country", "دولة غير معروفة")}
                    </p>
                  </div>
                  <div className="text-xs font-normal text-slate-600">
                    {student.onboarded_at
                      ? `${pickLang(lang, "Onboarded", "تم الانضمام")} ${student.onboarded_at}`
                      : pickLang(lang, "Pending onboarding", "قيد الانضمام")}
                  </div>
                </div>
              ))
            ) : (
              <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-sm text-slate-500">
                {pickLang(lang, "No students yet.", "لا يوجد طلاب بعد.")}
              </div>
            )}
          </div>
        </div>

        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_22px_60px_-36px_rgba(15,23,42,0.35)]">
          <div>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              {pickLang(lang, "Profile", "الملف الشخصي")}
            </p>
            <h2 className="text-lg font-normal text-slate-900">
              {pickLang(lang, "Your details", "تفاصيلك")}
            </h2>
          </div>
          <div className="space-y-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-sm text-slate-700">
            <p className="break-all">
              <span className="font-normal text-slate-900">{pickLang(lang, "Name", "الاسم")}:</span>{" "}
              {agent?.name || user?.name || "—"}
            </p>
            <p className="break-all">
              <span className="font-normal text-slate-900">{pickLang(lang, "Email", "البريد")}:</span>{" "}
              {agent?.email || user?.email || "—"}
            </p>
            <p>
              <span className="font-normal text-slate-900">{pickLang(lang, "Role", "الدور")}:</span>{" "}
              {user?.role || agent?.role || "—"}
            </p>
            <p>
              <span className="font-normal text-slate-900">{pickLang(lang, "Referral code", "كود الإحالة")}:</span>{" "}
              {agent?.referral_code || "—"}
            </p>
          </div>
        </div>
      </section>
    </div>
  );
}
