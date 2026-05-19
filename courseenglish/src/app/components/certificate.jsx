// components/CertificatesSection.js
"use client";

import { useState, useRef, useEffect, useMemo } from "react";
import Link from "next/link";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const certificates = [
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

export default function CertificatesSection() {
  const [activeIndex, setActiveIndex] = useState(0);
  const sliderRef = useRef(null);
  const { data: certData } = useApi("/courseenglish/certificates");
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const meta = getCourseEnglishMessages(language)?.pages?.homepage?.certificates ?? {};
  const heading =
    meta?.heading || (isArabic ? "نحن معتمدون من العديد من المؤسسات" : "We are accredited by many institutions");
  const subheading =
    meta?.subheading ||
    (isArabic
      ? "نفخر بشراكاتنا مع أبرز معاهد اللغة الإنجليزية والمؤسسات التعليمية المعتمدة حول العالم."
      : "We are proud of our partnerships with leading English language institutes and accredited educational organizations around the world.");

  const certificateItems = useMemo(() => {
    const apiItems = certData?.certificates ?? [];
    if (!apiItems.length) return certificates;
    return apiItems.map((item, idx) => ({
      id: item.id ?? idx,
      image: item.image || item.certificate_image,
      title: isArabic ? item.ar_title || item.title || "Certification" : item.title || item.ar_title || "Certification",
      link: item.link || item.certification_link || "#",
    }));
  }, [certData, isArabic]);

  const VISIBLE_DESKTOP = 2;

  // Get visible items for desktop slider
  const getVisibleCertificates = (visibleCount) => {
    const items = [];
    for (let i = 0; i < visibleCount; i++) {
      const idx = (activeIndex + i) % certificateItems.length;
      items.push(certificateItems[idx]);
    }
    return items;
  };

  const handleDotClick = (idx) => {
    setActiveIndex(idx);

    // Scroll on mobile slider
    if (sliderRef.current) {
      sliderRef.current.scrollTo({
        left: idx * 280, // card width + gap
        behavior: "smooth",
      });
    }
  };

  // AUTO SLIDE every 4 seconds
  useEffect(() => {
    const interval = setInterval(() => {
      setActiveIndex((prev) => (prev + 1) % certificateItems.length);

      if (sliderRef.current) {
        sliderRef.current.scrollTo({
          left: ((activeIndex + 1) % certificateItems.length) * 280,
          behavior: "smooth",
        });
      }
    }, 4000);

    return () => clearInterval(interval);
  }, [activeIndex, certificateItems.length]);

  return (
    <>
      {/* ========================================================= */}
      {/* DESKTOP VERSION */}
      {/* ========================================================= */}
      <section className="hidden md:block w-full pb-12 md:pb-10 px-4 md:px-10 xl:px-20 2xl:px-40">
        <div className="mx-auto rounded-3xl bg-[#E8F3FC] py-10 md:px-14 md:py-14">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6 2xl:gap-10">
            
            {/* LEFT — Desktop Slider */}
            <div className="order-2 md:order-1 w-full md:w-3/5">
              <div className="hidden md:flex gap-4 p-2 2xl:gap-4">
                {getVisibleCertificates(VISIBLE_DESKTOP).map((item, idx) => {
                  const Card = item.link ? Link : "div";
                  return (
                    <Card
                      key={idx}
                      href={item.link || undefined}
                      className={`flex-1 rounded-2xl bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)] p-4 transition hover:shadow-[0_20px_60px_rgba(15,23,42,0.12)] ${
                        item.link ? "cursor-pointer" : "cursor-default"
                      }`}
                    >
                      <div className="relative h-60 rounded-2xl overflow-hidden bg-white flex items-center justify-center">
                        <img
                          src={item.image}
                          alt={item.title}
                          className="h-full w-full object-contain p-2 md:p-2"
                          loading="lazy"
                        />
                      </div>
                      <p className="my-2 text-center text-md font-normal text-slate-900">
                        {item.title}
                      </p>
                    </Card>
                  );
                })}
              </div>

              {/* Desktop Dots */}
              <div className="mt-4 flex justify-center gap-1.5">
                {certificateItems.map((_, idx) => (
                  <button
                    key={idx}
                    type="button"
                    onClick={() => handleDotClick(idx)}
                    aria-label={`Go to slide ${idx + 1}`}
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

            {/* RIGHT — Text */}
            <div className="order-1 md:order-2 w-full md:w-2/5">
              <h2 className="text-2xl xl:text-4xl 2xl:text-5xl font-extrabold text-slate-900 leading-tight">
                {heading}
              </h2>

              <p className="mt-4 text-sm md:text-base text-slate-700 leading-relaxed max-w-md">
                {subheading}
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* ========================================================= */}
      {/* MOBILE VERSION */}
      {/* ========================================================= */}
      <section className="md:hidden w-full pb-10 pt-6">
        
        {/* Heading */}
        <div className="px-4 text-center mb-4">
          <h2 className="text-2xl font-extrabold text-slate-900 leading-snug">
            {heading}
          </h2>
        </div>

        {/* Mobile Slider */}
        <div className="relative">
          <div
            ref={sliderRef}
            className="flex gap-3 overflow-x-auto snap-x snap-mandatory px-2 pb-4 scrollbar-hide"
          >
            {certificateItems.map((item, idx) => {
              const Card = item.link ? Link : "div";
              return (
                <Card
                  key={idx}
                  href={item.link || undefined}
                  className="min-w-[260px] snap-center bg-white p-2 "
                >
                  <div className="relative h-52 w-full rounded-2xl overflow-hidden bg-white">
                    <img
                      src={item.image}
                      alt={item.title}
                      className="h-full w-full object-contain p-3"
                      loading="lazy"
                    />
                  </div>

                  <p className="mt-4 text-center text-xs font-normal text-slate-900">
                    {item.title}
                  </p>
                </Card>
              );
            })}
          </div>

          {/* Mobile Dots */}
          <div className="mt-1 flex justify-center gap-1.5">
            {certificateItems.map((_, idx) => (
              <button
                key={idx}
                onClick={() => handleDotClick(idx)}
                aria-label={`Go to slide ${idx + 1}`}
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
    </>
  );
}
