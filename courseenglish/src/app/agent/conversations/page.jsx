"use client";

import { pickLang } from "@/lib/i18nFallback";

export default function AgentConversationsPage() {
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";
  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm" dir={isArabic ? "rtl" : "ltr"}>
      <h1 className="text-2xl font-medium text-slate-900">
        {pickLang(lang, "Conversations", "المحادثات")}
      </h1>
      <p className="mt-3 text-slate-600">
        {pickLang(
          lang,
          "Messaging workspace will appear here. Contact support to enable chat.",
          "مساحة الرسائل ستظهر هنا. تواصل مع الدعم لتفعيل الدردشة."
        )}
      </p>
      <div className="mt-6 inline-flex items-center gap-2 rounded-full bg-[#1277BE] px-4 py-2 text-sm font-normal text-white shadow-sm">
        <span>📧</span>
        <a href="mailto:support@courseenglish.com">support@courseenglish.com</a>
      </div>
    </div>
  );
}
