"use client";

import { useState } from "react";
import { useAgentApi, fetchAgentJson } from "@/lib/agentApi";
import { pickLang } from "@/lib/i18nFallback";
import AlertBanner from "@/app/agent/components/AlertBanner";

export default function AgentReferralsPage() {
  const { data: referralData, loading, error, refetch } = useAgentApi("/agent/referrals");
  const { data: agentData } = useAgentApi("/agent/me");
  const [creating, setCreating] = useState(false);
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";
  const agent = agentData?.data || {};
  const referrals = referralData?.data || {};
  const referralCode = referrals?.referral_code || agent?.referral_code || "";
  const referralLink = referrals?.referral_link || agent?.referral_link || (referralCode ? `https://courseenglish.com/?ref=${referralCode}` : "");

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
            {pickLang(lang, "Referrals", "الإحالات")}
          </p>
          <h1 className="text-2xl font-normal text-slate-900">
            {pickLang(lang, "Grow with referrals", "نمِّ أعمالك بالإحالات")}
          </h1>
        </div>
        <button
          type="button"
          onClick={async () => {
            setCreating(true);
            try {
              await fetchAgentJson("/agent/referrals", { method: "POST" });
              await refetch();
            } catch {
              alert(pickLang(lang, "Failed to create referral code.", "تعذر إنشاء كود إحالة."));
            } finally {
              setCreating(false);
            }
          }}
          disabled={creating}
          className="rounded-full bg-[#1277BE] px-5 py-2.5 text-sm font-normal text-white shadow-sm transition hover:bg-[#0f649f] disabled:opacity-60"
        >
          {creating
            ? pickLang(lang, "Creating...", "جاري الإنشاء...")
            : pickLang(lang, "Create / refresh code", "إنشاء / تحديث الكود")}
        </button>
        <div className="flex flex-wrap gap-2 text-xs text-slate-600">
          <button
            type="button"
            onClick={() => navigator.clipboard?.writeText(referralCode || "")}
            className="rounded-full border border-slate-300 px-3 py-1 font-normal hover:bg-white"
            disabled={!referralCode}
          >
            {pickLang(lang, "Copy code", "نسخ الكود")}
          </button>
          <button
            type="button"
            onClick={() => navigator.clipboard?.writeText(referralLink || "")}
            className="rounded-full border border-slate-300 px-3 py-1 font-normal hover:bg-white"
            disabled={!referralLink}
          >
            {pickLang(lang, "Copy link", "نسخ الرابط")}
          </button>
          <button
            type="button"
            onClick={() => {
              if (navigator.share && referralLink) {
                navigator.share({ title: "CourseEnglish", text: pickLang(lang, "Join via my referral", "سجل عبر رابط الإحالة الخاص بي"), url: referralLink });
              } else if (referralLink) {
                window.open(referralLink, "_blank");
              }
            }}
            className="rounded-full border border-slate-300 px-3 py-1 font-normal hover:bg-white"
            disabled={!referralLink}
          >
            {pickLang(lang, "Share", "مشاركة")}
          </button>
        </div>
      </div>

      {error && (
        <AlertBanner
          tone="error"
          message={pickLang(lang, "Failed to load referrals.", "تعذر تحميل الإحالات.")}
          dir={isArabic ? "rtl" : "ltr"}
        />
      )}

      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <Metric title={pickLang(lang, "Total clicks", "إجمالي النقرات")} value={referrals.total_clicks ?? "—"} />
        <Metric title={pickLang(lang, "Signups", "التسجيلات")} value={referrals.signups ?? "—"} />
        <Metric
          title={pickLang(lang, "Conversion", "التحويل")}
          value={referrals.conversion ? `${referrals.conversion}%` : "—"}
        />
        <Metric
          title={pickLang(lang, "Earned commission", "العمولة المكتسبة")}
          value={agent?.commission_percent ? `${agent.commission_percent}%` : "—"}
        />
      </div>

      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        {loading ? (
          <div className="space-y-3">
            {Array.from({ length: 2 }).map((_, i) => (
              <div key={i} className="h-16 animate-pulse rounded-2xl bg-slate-100" />
            ))}
          </div>
        ) : (
          <div className="space-y-2 text-sm text-slate-700" dir={isArabic ? "rtl" : "ltr"}>
            <p>
              {pickLang(
                lang,
                "Your referral code:",
                "كود الإحالة الخاص بك:"
              )}{" "}
              <span className="font-normal">{referralCode || "—"}</span>
            </p>
            <p className="text-slate-500">
              {pickLang(
                lang,
                "Share this code or link with students to track signups.",
                "شارك هذا الكود أو الرابط مع الطلاب لتتبع التسجيلات."
              )}
            </p>
            {referralLink ? (
              <p className="text-xs text-slate-500 break-all">
                {pickLang(lang, "Referral link", "رابط الإحالة")}: {referralLink}
              </p>
            ) : null}
          </div>
        )}
      </div>
    </div>
  );
}

function Metric({ title, value }) {
  return (
    <div className="rounded-2xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
      <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-500">{title}</p>
      <p className="pt-3 text-3xl font-normal text-slate-900">{value}</p>
    </div>
  );
}
