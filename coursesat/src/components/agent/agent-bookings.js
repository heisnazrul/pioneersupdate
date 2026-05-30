"use client";

import Link from "next/link";

import { useLocale } from "@/components/providers/locale-provider";

export default function AgentBookings() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.agent.bookings.${key}`, fallback);
  const align = isRtl ? "text-right" : "text-left";

  return (
    <div className="space-y-6" dir={direction}>
      <div className={align}>
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">{loc("eyebrow", "Bookings")}</p>
        <h1 className="text-2xl font-normal text-slate-900 md:text-3xl">{loc("title", "Book for your students")}</h1>
        <p className="mt-2 text-lg text-slate-500">
          {loc("subtitle", "Browse courses and complete bookings on behalf of your students. Use your referral link so commissions are tracked.")}
        </p>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Link
          href="/language-institutes"
          className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
        >
          <h2 className={`text-lg font-normal text-[#102233] ${align}`}>{loc("institutes_title", "Language institutes")}</h2>
          <p className={`mt-2 text-sm text-slate-500 ${align}`}>
            {loc("institutes_desc", "Book in-person language courses at accredited institutes worldwide.")}
          </p>
        </Link>
        <Link
          href="/online-courses"
          className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
        >
          <h2 className={`text-lg font-normal text-[#102233] ${align}`}>{loc("online_title", "Online courses")}</h2>
          <p className={`mt-2 text-sm text-slate-500 ${align}`}>
            {loc("online_desc", "Book flexible online English courses for your students.")}
          </p>
        </Link>
      </div>

      <div className={`rounded-3xl border border-slate-200 bg-white p-6 text-sm text-slate-600 ${align}`}>
        <p>{loc("hint", "Add students from the Students page first, then share your referral link when they register or book.")}</p>
        <Link href="/agent/students" className="mt-3 inline-block text-[#1277BE] hover:underline">
          {loc("go_students", "Go to students")}
        </Link>
      </div>
    </div>
  );
}
