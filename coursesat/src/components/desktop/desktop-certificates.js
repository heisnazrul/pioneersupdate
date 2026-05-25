"use client";

/* eslint-disable @next/next/no-img-element */
import { useState, useEffect, useMemo, useRef } from "react";
import Link from "next/link";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

const fallbackCertificates = [
  {
    image: "/assets/certificate/1.png",
    title: "ICEF Certification",
    link: "#",
  },
  {
    image: "/assets/certificate/2.png",
    title: "English UK Partner Agency",
    link: "#",
  },
  {
    image: "/assets/certificate/3.png",
    title: "Accredited UK Training",
    link: "#",
  },
];

export default function DesktopCertificates() {
  const [activeIndex, setActiveIndex] = useState(0);
  const { data: certData } = useApi("/courseenglish/certificates");
  const { language, messages, direction } = useLocale();
  const isArabic = language === "ar";

  const meta = messages?.pages?.homepage?.certificates ?? {};
  const heading =
    meta.heading || (isArabic ? "نحن معتمدون من العديد من المؤسسات" : "We are accredited by many institutions");
  const subheading =
    meta.subheading ||
    (isArabic
      ? "نفخر بشراكاتنا مع أبرز معاهد اللغة الإنجليزية والمؤسسات التعليمية المعتمدة حول العالم."
      : "We are proud of our partnerships with leading English language institutes and accredited educational organizations around the world.");

  const certificateItems = useMemo(() => {
    const apiItems = certData?.certificates ?? [];
    if (!apiItems.length) return fallbackCertificates;
    return apiItems.map((item, idx) => ({
      id: item.id ?? idx,
      image: item.image || item.certificate_image,
      title: isArabic ? item.ar_title || item.title || "Certification" : item.title || item.ar_title || "Certification",
      link: item.link || item.certification_link || "#",
    }));
  }, [certData, isArabic]);

  const VISIBLE_DESKTOP = 2;

  const getVisibleCertificates = (visibleCount) => {
    if (!certificateItems || !certificateItems.length) return [];
    const items = [];
    for (let i = 0; i < visibleCount; i++) {
      const idx = (activeIndex + i) % certificateItems.length;
      items.push(certificateItems[idx]);
    }
    return items;
  };

  const handleDotClick = (idx) => {
    setActiveIndex(idx);
  };

  // AUTO SLIDE every 4 seconds
  useEffect(() => {
    if (!certificateItems.length) return;
    const interval = setInterval(() => {
      setActiveIndex((prev) => (prev + 1) % certificateItems.length);
    }, 4000);

    return () => clearInterval(interval);
  }, [certificateItems.length]);

  return (
    <section className="w-full pb-10 px-4 md:px-10 xl:px-20 2xl:px-40" dir={direction}>
      <div className="mx-auto rounded-3xl bg-[#E8F3FC] py-10 md:px-14 md:py-14">
        <div className="flex flex-col md:flex-row md:items-center gap-20 px-4">

          {/* TEXT COLUMN: Leading position based on direction (Left in LTR, Right in RTL) */}
          <div className="w-full md:w-4/10 text-start">
            <h2 className="text-3xl 2xl:text-4xl font-bold text-slate-900 leading-tight">
              {heading}
            </h2>
            <p className="mt-4 text-sm xl:text-lg text-slate-600 leading-relaxed max-w-sm">
              {subheading}
            </p>
          </div>

          {/* SLIDER COLUMN: Opposite side (Right in LTR, Left in RTL) */}
          <div className="w-full md:w-5/10">
            <div className="flex gap-10 p-2">
              {getVisibleCertificates(VISIBLE_DESKTOP).map((item, idx) => {
                const Card = item.link ? Link : "div";
                return (
                  <Card
                    key={idx}
                    href={item.link || undefined}
                    className={`flex-1 rounded-2xl bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)] p-2 transition duration-300 hover:shadow-[0_20px_60px_rgba(15,23,42,0.12)] ${item.link ? "cursor-pointer" : "cursor-default"
                      }`}
                  >
                    <div className="relative h-50 2xl:h-60 rounded-2xl overflow-hidden bg-white flex items-center justify-center">
                      <img
                        src={item.image}
                        alt={item.title}
                        className="h-full w-auto object-contain"
                        loading="lazy"
                      />
                    </div>
                    <p className="mt-4 mb-2 text-center text-sm font-medium text-slate-800">
                      {item.title}
                    </p>
                  </Card>
                );
              })}
            </div>

            {/* Pagination Dots */}
            <div className="mt-4 flex justify-center gap-1.5">
              {certificateItems.map((_, idx) => (
                <button
                  key={idx}
                  type="button"
                  onClick={() => handleDotClick(idx)}
                  aria-label={`Go to slide ${idx + 1}`}
                >
                  <div
                    className={`h-2 rounded-full transition-all duration-300 ${idx === activeIndex ? "w-6 bg-[#135FAE]" : "w-2 bg-slate-300"
                      }`}
                  />
                </button>
              ))}
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}
