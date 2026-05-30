"use client";

import Image from "next/image";
import Link from "next/link";
import { useLocale } from "@/components/providers/locale-provider";

export default function SummerCampsBlocked() {
  const { direction, t } = useLocale();
  const loc = (key, fallback = "") => t(`pages.summer_programs.blocked.${key}`, fallback);

  return (
    <section className="flex flex-1 items-center justify-center px-6 py-16 sm:py-24" dir={direction}>
      <div className="mx-auto flex max-w-lg flex-col items-center text-center">
        <div className="grid h-24 w-24 place-items-center rounded-full bg-[#E7F0FB]">
          <Image
            src="/assets/icons/compare.svg"
            alt=""
            width={40}
            height={40}
            className="h-10 w-10 opacity-60"
            aria-hidden
          />
        </div>

        <h1 className="mt-8 text-2xl font-bold text-slate-900 sm:text-3xl">
          {loc("heading", "Coming Soon")}
        </h1>
        <p className="mt-3 text-base leading-relaxed text-slate-500 sm:text-lg">
          {loc(
            "subheading",
            "Summer programs are not available yet. We are working on this section and will launch it soon."
          )}
        </p>

        <Link
          href="/"
          className="mt-8 rounded-[8px] bg-[#1F63AE] px-8 py-3.5 text-sm font-bold !text-white shadow-md transition hover:bg-[#175093]"
        >
          {loc("back_home", "Back to Home")}
        </Link>
      </div>
    </section>
  );
}
