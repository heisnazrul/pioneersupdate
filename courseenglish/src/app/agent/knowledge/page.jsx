"use client";

import { pickLang } from "@/lib/i18nFallback";

export default function AgentKnowledgePage() {
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";
  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm" dir={isArabic ? "rtl" : "ltr"}>
      <h1 className="text-2xl font-medium text-slate-900">
        {pickLang(lang, "Knowledge Base", "مركز المعرفة")}
      </h1>
      <p className="mt-3 text-slate-600">
        {pickLang(
          lang,
          "Guides and FAQs will appear here. Visit our help center meanwhile.",
          "الإرشادات والأسئلة الشائعة ستظهر هنا. يمكنك زيارة مركز المساعدة حالياً."
        )}
      </p>
      <div className="mt-6 inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-sm font-normal text-slate-800 hover:bg-slate-50">
        <span>📚</span>
        <a href="https://courseenglish.com/help" target="_blank" rel="noreferrer">
          {pickLang(lang, "Open Help Center", "افتح مركز المساعدة")}
        </a>
      </div>
    </div>
  );
}
