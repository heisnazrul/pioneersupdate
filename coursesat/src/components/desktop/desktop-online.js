"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useMemo, useRef, useState } from "react";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import OnlineCourseCard, { mapOnlineCourse, onlineCourseHref } from "@/components/shared/online-course-card";

const TOKENS = {
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 28;
const AUTO_MS = 4000;

export default function DesktopOnline() {
  const viewportRef = useRef(null);
  const { data } = useApi("/coursesat/home/online");
  const { language, direction, t } = useLocale();
  const { currency } = useCurrency();
  const isArabic = language === "ar";

  const heading = t("pages.homepage.online_courses.heading", "الدراسة عن بعد");
  const subheading = t(
    "pages.homepage.online_courses.subheading",
    "تعلم اللغة الإنجليزية أينما كنت، بخيارات مرنة تناسب وقتك وأهدافك"
  );
  const ctaText = t("pages.homepage.online_courses.view_all", "View all courses");
  const ctaUrl = "/online-courses";

  const courses = useMemo(
    () => (data?.online_courses ?? []).map((course) => mapOnlineCourse(course, isArabic)),
    [data?.online_courses, isArabic],
  );

  const [cardW, setCardW] = useState(320);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);

  const len = courses.length;

  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;
      if (w >= 1536) setVisible(4);
      else if (w >= 1280) setVisible(3);
      else setVisible(2);
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      const totalGap = GAP * (visible - 1);
      setCardW(Math.max(260, Math.floor((w - totalGap) / visible)));
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible]);

  const maxIndex = Math.max(0, len - visible);

  useEffect(() => {
    if (len <= visible) return;
    const id = setInterval(() => {
      setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
    }, AUTO_MS);
    return () => clearInterval(id);
  }, [len, visible, maxIndex]);

  const prev = () => {
    if (len <= visible) return;
    setIndex((i) => (i - 1 < 0 ? maxIndex : i - 1));
  };

  const next = () => {
    if (len <= visible) return;
    setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
  };

  const dir = -1;
  const trackStyle = {
    gap: `${GAP}px`,
    width: `${len * cardW + (len - 1) * GAP}px`,
    transform: `translateX(${dir * Math.min(index, maxIndex) * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = {
    width: `${cardW}px`,
    flex: `0 0 ${cardW}px`,
  };

  if (len === 0) return null;

  return (
    <section className="hidden md:block py-16 sm:py-20 bg-[#EEF4FB] w-full" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">
        <div className="text-center">
          <h2 className="py-4 text-[32px] sm:text-[40px] font-bold text-[#111827] leading-[1.3]">
            {heading}
          </h2>
          {subheading ? (
            <p className="mt-2 text-lg text-slate-500 max-w-2xl mx-auto">{subheading}</p>
          ) : null}
        </div>

        <div className="relative mt-10 px-4 md:px-12">
          {len > visible ? (
            <>
              <button
                type="button"
                onClick={prev}
                aria-label="Previous courses"
                className="absolute -left-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full border border-slate-200 bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 transition"
              >
                <Image src="/assets/icons/arrow-left.svg" alt="Previous" width={16} height={16} className="h-4 w-4" />
              </button>
              <button
                type="button"
                onClick={next}
                aria-label="Next courses"
                className="absolute -right-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full p-4 text-white shadow-md hover:brightness-110 transition"
                style={{ background: TOKENS.primary, boxShadow: TOKENS.primaryShadow }}
              >
                <Image src="/assets/icons/arrow-right-white.svg" alt="Next" width={16} height={16} className="h-4 w-4" />
              </button>
            </>
          ) : null}

          <div ref={viewportRef} className="overflow-hidden m-4" dir="ltr">
            <div className="flex" style={trackStyle}>
              {courses.map((course) => (
                <OnlineCourseCard
                  key={course.id}
                  course={course}
                  style={cardStyle}
                  className="shrink-0"
                  currency={currency}
                  isArabic={isArabic}
                  href={onlineCourseHref(course)}
                  t={t}
                />
              ))}
            </div>
          </div>
        </div>

        <div className="mt-12 flex justify-center">
          <Link
            href={ctaUrl}
            className="rounded-[8px] bg-[#1F63AE] px-8 py-3.5 text-sm font-bold !text-white shadow-md hover:bg-[#175093] transition-all"
          >
            {ctaText}
          </Link>
        </div>
      </div>
    </section>
  );
}
