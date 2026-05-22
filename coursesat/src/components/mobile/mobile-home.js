"use client";

import MobileFooter from "./mobile-footer";
import MobileBottomNav from "./mobile-bottom-nav";
import MobileHeader from "./mobile-header";
import { useLocale } from "@/components/providers/locale-provider";

export default function MobileHome() {
  const { t } = useLocale();

  return (
    <div className="flex min-h-screen flex-col pb-[88px]">
      <MobileHeader />
      <main className="mx-auto flex w-full max-w-sm flex-1 px-5 py-8">
        <section className="flex w-full flex-1 flex-col justify-center rounded-[2rem] border border-white/60 bg-surface p-6 shadow-[0_24px_60px_rgba(20,32,51,0.12)] backdrop-blur">
          <span className="mb-4 text-xs font-semibold uppercase tracking-[0.32em] text-slate-500">
            {t("pages.coursesat.mobile.experience", "Mobile Experience")}
          </span>
          <h1 className="max-w-xs text-4xl font-extrabold tracking-tight text-slate-950">
            {t("pages.coursesat.shared.coming_soon", "CourseSat is coming soon.")}
          </h1>
          <p className="mt-4 max-w-xs text-sm leading-7 text-slate-600">
            {t(
              "pages.coursesat.mobile.description",
              "This mobile view now has its own header, content area, and footer."
            )}
          </p>
        </section>
      </main>
      <MobileFooter />
      <MobileBottomNav />
    </div>
  );
}
