"use client";

import Link from "next/link";
import { useMemo } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import { useAgentApi, useAgentUser } from "@/lib/agent-api";

function Metric({ title, value, hint }) {
  return (
    <div className="rounded-2xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
      <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-500">{title}</p>
      <p className="pt-3 text-3xl font-normal text-slate-900">{value ?? "—"}</p>
      {hint ? <p className="text-sm text-emerald-600">{hint}</p> : null}
    </div>
  );
}

export default function AgentDashboard() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.agent.dashboard.${key}`, fallback);
  const { user } = useAgentUser();
  const { data: overviewData, loading: overviewLoading, error: overviewError } = useAgentApi("/agent/overview");
  const { data: agentData } = useAgentApi("/agent/me");
  const { data: studentsData, loading: studentsLoading } = useAgentApi("/agent/students?per_page=5");

  const agent = agentData?.data || {};
  const overview = overviewData?.data || {};
  const students = studentsData?.data || [];
  const firstName = (user?.name || agent?.name || "").trim().split(/\s+/)[0] || "—";

  const stats = useMemo(
    () => [
      {
        title: loc("students_total", "Total students"),
        value: overviewLoading ? "…" : (overview.students_total ?? students.length ?? "—"),
        hint: loc("all_time", "All time"),
      },
      {
        title: loc("referrals_active", "Active referrals"),
        value: overviewLoading ? "…" : (overview.referrals_active ?? (agent.referral_code ? 1 : 0)),
        hint: loc("active", "Active"),
      },
      {
        title: loc("commission", "Commission"),
        value: overviewLoading
          ? "…"
          : overview.commission_percent
            ? `${overview.commission_percent}%`
            : agent.commission_percent
              ? `${agent.commission_percent}%`
              : "—",
        hint: loc("rate", "Rate"),
      },
      {
        title: loc("status", "Status"),
        value: overviewLoading
          ? "…"
          : (overview.status || agent.status || "—").toString().toUpperCase(),
        hint: loc("account", "Account"),
      },
    ],
    [agent, overview, overviewLoading, students.length, loc]
  );

  const align = isRtl ? "text-right" : "text-left";

  return (
    <div className="space-y-6" dir={direction}>
      <section className={`rounded-3xl border border-slate-200 bg-white px-6 py-5 ${align}`}>
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          {loc("eyebrow", "Agent dashboard")}
        </p>
        <h1 className="mt-2 text-2xl font-normal text-slate-900 md:text-3xl">
          {loc("title", "Welcome back, {name}").replace("{name}", firstName)}
        </h1>
        <p className="mt-2 text-lg text-slate-500">
          {loc("subtitle", "Manage students, referrals, and commission details from one workspace.")}
        </p>
        <div className="mt-4 flex flex-wrap gap-3">
          <Link
            href="/agent/students"
            className="rounded-full bg-[#1277BE] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f649f]"
          >
            {loc("add_student", "Add student")}
          </Link>
          <Link
            href="/agent/referrals"
            className="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50"
          >
            {loc("share_referral", "Share referral link")}
          </Link>
        </div>
        {overviewError ? (
          <p className="mt-3 text-sm text-red-600">{loc("error", "Failed to load dashboard data.")}</p>
        ) : null}
      </section>

      <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {stats.map((stat) => (
          <Metric key={stat.title} title={stat.title} value={stat.value} hint={stat.hint} />
        ))}
      </section>

      <section className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          <div className="flex items-center justify-between">
            <div className={align}>
              <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
                {loc("your_students", "Your students")}
              </p>
              <h2 className="text-lg font-normal text-slate-900">{loc("latest", "Latest onboarded")}</h2>
            </div>
            <span className="rounded-full bg-slate-100 px-3 py-2 text-xs font-normal text-slate-700">
              {students.length} {loc("total", "total")}
            </span>
          </div>
          <div className="space-y-3">
            {studentsLoading ? (
              Array.from({ length: 3 }).map((_, i) => (
                <div key={i} className="h-16 animate-pulse rounded-2xl border border-slate-100 bg-slate-50" />
              ))
            ) : students.length ? (
              students.slice(0, 5).map((student) => (
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
              ))
            ) : (
              <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-sm text-slate-500">
                {loc("no_students", "No students yet.")}
              </div>
            )}
          </div>
        </div>

        <div className="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          <div className={align}>
            <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-600">
              {loc("profile", "Profile")}
            </p>
            <h2 className="text-lg font-normal text-slate-900">{loc("your_details", "Your details")}</h2>
          </div>
          <div className={`space-y-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 text-sm text-slate-700 ${align}`}>
            <p className="break-all">
              <span className="font-normal text-slate-900">{loc("name", "Name")}:</span>{" "}
              {agent?.name || user?.name || "—"}
            </p>
            <p className="break-all">
              <span className="font-normal text-slate-900">{loc("email", "Email")}:</span>{" "}
              {agent?.email || user?.email || "—"}
            </p>
            <p>
              <span className="font-normal text-slate-900">{loc("referral_code", "Referral code")}:</span>{" "}
              {agent?.referral_code || "—"}
            </p>
          </div>
        </div>
      </section>
    </div>
  );
}
