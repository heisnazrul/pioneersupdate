"use client";

import { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCopy, faShareNodes } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { useApi } from "@/lib/api";
import { formatCurrency } from "@/lib/format";

function Metric({ title, value }) {
  return (
    <div className="rounded-2xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
      <p className="text-xs font-normal uppercase tracking-[0.18em] text-slate-500">{title}</p>
      <p className="pt-3 text-3xl font-normal text-slate-900">{value ?? "—"}</p>
    </div>
  );
}

export default function StudentReferrals() {
  const { direction, language, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.student.referrals.${key}`, fallback);
  const { data, loading, error } = useApi("/courseenglish/student/referrals");
  const [copied, setCopied] = useState("");

  const referrals = data?.data || {};
  const referralCode = referrals.referral_code || "";
  const referralLink = referrals.referral_link || "";

  const copyText = async (text, key) => {
    if (!text) return;
    try {
      await navigator.clipboard.writeText(text);
      setCopied(key);
      setTimeout(() => setCopied(""), 2000);
    } catch {
      // ignore
    }
  };

  const shareLink = () => {
    if (!referralLink) return;
    if (navigator.share) {
      navigator.share({
        title: "Course English",
        text: loc("share_text", "Join via my referral link"),
        url: referralLink,
      });
    } else {
      window.open(referralLink, "_blank");
    }
  };

  return (
    <div className="space-y-6" dir={direction}>
      <div className={`flex flex-col gap-3 md:flex-row md:items-center md:justify-between ${isRtl ? "text-right" : "text-left"}`}>
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">{loc("eyebrow", "Referrals")}</p>
          <h1 className="text-2xl font-normal text-slate-900 md:text-3xl">{loc("title", "Refer friends, earn rewards")}</h1>
          <p className="mt-2 text-lg text-slate-500">{loc("subtitle", "Share your link and track signups from your dashboard.")}</p>
        </div>
        <div className="flex flex-wrap gap-2 text-xs text-slate-600">
          <button
            type="button"
            onClick={() => copyText(referralCode, "code")}
            disabled={!referralCode}
            className="rounded-full border border-slate-300 px-3 py-1.5 font-normal hover:bg-white disabled:opacity-50"
          >
            <FontAwesomeIcon icon={faCopy} className={isRtl ? "ml-1.5" : "mr-1.5"} />
            {copied === "code" ? loc("copied", "Copied!") : loc("copy_code", "Copy code")}
          </button>
          <button
            type="button"
            onClick={() => copyText(referralLink, "link")}
            disabled={!referralLink}
            className="rounded-full border border-slate-300 px-3 py-1.5 font-normal hover:bg-white disabled:opacity-50"
          >
            <FontAwesomeIcon icon={faCopy} className={isRtl ? "ml-1.5" : "mr-1.5"} />
            {copied === "link" ? loc("copied", "Copied!") : loc("copy_link", "Copy link")}
          </button>
          <button
            type="button"
            onClick={shareLink}
            disabled={!referralLink}
            className="rounded-full bg-[#1277BE] px-3 py-1.5 font-normal text-white hover:bg-[#0f649f] disabled:opacity-50"
          >
            <FontAwesomeIcon icon={faShareNodes} className={isRtl ? "ml-1.5" : "mr-1.5"} />
            {loc("share", "Share")}
          </button>
        </div>
      </div>

      {error && (
        <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">{loc("error", "Failed to load referrals.")}</div>
      )}

      {loading ? (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>
      ) : (
        <>
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <Metric title={loc("total_clicks", "Total clicks")} value={referrals.total_clicks} />
            <Metric title={loc("signups", "Signups")} value={referrals.signups} />
            <Metric
              title={loc("conversion", "Conversion")}
              value={referrals.conversion != null ? `${referrals.conversion}%` : "—"}
            />
            <Metric
              title={loc("reward_balance", "Reward balance")}
              value={formatCurrency(referrals.reward_balance_sar, "SAR")}
            />
          </div>

          <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div className="space-y-2 text-sm text-slate-700">
              <p>
                {loc("your_code", "Your referral code:")}{" "}
                <span className="font-normal text-[#102233]">{referralCode || "—"}</span>
              </p>
              <p className="text-slate-500">{loc("hint", "Friends who sign up through your link count toward your referral stats.")}</p>
              {referralLink ? (
                <p className="break-all text-xs text-slate-500">
                  {loc("your_link", "Your referral link:")} {referralLink}
                </p>
              ) : null}
            </div>
          </div>
        </>
      )}
    </div>
  );
}
