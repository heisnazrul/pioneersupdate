"use client";

/* eslint-disable @next/next/no-img-element */
import { useState, useRef, useEffect, useMemo } from "react";
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
    title: "English UK Partner Agency Certificate",
    link: "#",
  },
  {
    image: "/assets/certificate/3.png",
    title: "Accredited UK Training Certificate",
    link: "#",
  },
];

export default function MobileCertificates() {
  const [activeIndex, setActiveIndex] = useState(0);
  const sliderRef = useRef(null);
  const { data: certData } = useApi("/courseenglish/certificates");
  const { language, messages, direction } = useLocale();
  const isArabic = language === "ar";

  const meta = messages?.pages?.homepage?.certificates ?? {};
  const heading =
    meta.heading || (isArabic ? "نحن معتمدون من العديد من المؤسسات" : "We are accredited by many institutions");

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

  const handleDotClick = (idx) => {
    setActiveIndex(idx);
    if (sliderRef.current) {
      sliderRef.current.scrollTo({
        left: idx * 280, // card width + gap
        behavior: "smooth",
      });
    }
  };

  // AUTO SLIDE every 4 seconds
  useEffect(() => {
    if (!certificateItems.length) return;
    const interval = setInterval(() => {
      const nextIdx = (activeIndex + 1) % certificateItems.length;
      setActiveIndex(nextIdx);

      if (sliderRef.current) {
        sliderRef.current.scrollTo({
          left: nextIdx * 280,
          behavior: "smooth",
        });
      }
    }, 4000);

    return () => clearInterval(interval);
  }, [activeIndex, certificateItems.length]);

  return (
    <section className="w-full pb-10 pt-6 bg-white" dir={direction}>
      
      {/* Mobile Heading */}
      <div className="px-4 text-center mb-4">
        <h2 className="text-2xl font-bold text-slate-900 leading-snug">
          {heading}
        </h2>
      </div>

      {/* Mobile Slider Carousel */}
      <div className="relative">
        <div
          ref={sliderRef}
          className="flex gap-3 overflow-x-auto snap-x snap-mandatory px-4 pb-4 scrollbar-none"
          style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
        >
          {certificateItems.map((item, idx) => {
            const Card = item.link ? Link : "div";
            return (
              <Card
                key={idx}
                href={item.link || undefined}
                className="min-w-[260px] snap-center rounded-2xl border border-gray-100 bg-white p-3 shadow-sm flex flex-col justify-between"
              >
                <div className="relative h-52 w-full rounded-2xl overflow-hidden bg-white flex items-center justify-center">
                  <img
                    src={item.image}
                    alt={item.title}
                    className="h-full w-full object-contain p-3"
                    loading="lazy"
                  />
                </div>

                <p className="mt-4 text-center text-xs font-semibold text-slate-800 line-clamp-2">
                  {item.title}
                </p>
              </Card>
            );
          })}
        </div>

        {/* Mobile Dots Pagination */}
        <div className="mt-2 flex justify-center gap-1.5">
          {certificateItems.map((_, idx) => (
            <button
              key={idx}
              onClick={() => handleDotClick(idx)}
              aria-label={`Go to slide ${idx + 1}`}
              type="button"
            >
              <div
                className={`h-2 rounded-full transition-all duration-300 ${
                  idx === activeIndex ? "w-6 bg-[#135FAE]" : "w-2 bg-slate-300"
                }`}
              />
            </button>
          ))}
        </div>
      </div>
    </section>
  );
}
