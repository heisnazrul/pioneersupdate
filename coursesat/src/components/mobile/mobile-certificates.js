"use client";

/* eslint-disable @next/next/no-img-element */
import { useState, useRef, useEffect, useMemo, useCallback } from "react";
import Link from "next/link";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

const AUTO_SCROLL_MS = 4000;
const SLIDE_TRANSITION_MS = 500;

const fallbackCertificates = [
  {
    image: "/assets/certificate/1.png",
    title: "English UK Certification",
    ar_title: "رابطة English UK البريطانية",
    link: "#",
  },
  {
    image: "/assets/certificate/2.png",
    title: "British ICAF Association",
    ar_title: "رابطة ICAF البريطانية",
    link: "#",
  },
  {
    image: "/assets/certificate/3.png",
    title: "English UK Certification",
    ar_title: "رابطة English UK البريطانية",
    link: "#",
  },
];

export default function MobileCertificates() {
  const [activeIndex, setActiveIndex] = useState(0);
  const [translateX, setTranslateX] = useState(0);
  const containerRef = useRef(null);
  const trackRef = useRef(null);
  const autoScrollPausedRef = useRef(false);
  const { data: certData } = useApi("/courseenglish/certificates");
  const { language, messages, direction } = useLocale();
  const isArabic = language === "ar";

  const meta = messages?.pages?.homepage?.certificates ?? {};
  const heading =
    meta.mobile_heading ||
    meta.heading ||
    (isArabic ? "حاصلون على رخصات وكيل معتمد لدى العديد من المؤسسات" : "We are accredited by many institutions");

  const certificateItems = useMemo(() => {
    const apiItems = certData?.certificates ?? [];
    const source = apiItems.length ? apiItems : fallbackCertificates;
    return source.map((item, idx) => ({
      id: item.id ?? idx,
      image: item.image || item.certificate_image,
      title: isArabic ? item.ar_title || item.title || "Certification" : item.title || item.ar_title || "Certification",
      link: item.link || item.certification_link || "#",
    }));
  }, [certData, isArabic]);

  const updateTranslate = useCallback(() => {
    const container = containerRef.current;
    const track = trackRef.current;
    if (!container || !track) return;

    const card = track.children[activeIndex];
    if (!card) return;

    const containerCenter = container.clientWidth / 2;
    const cardCenter = card.offsetLeft + card.offsetWidth / 2;
    setTranslateX(containerCenter - cardCenter);
  }, [activeIndex]);

  useEffect(() => {
    updateTranslate();
    window.addEventListener("resize", updateTranslate);
    return () => window.removeEventListener("resize", updateTranslate);
  }, [updateTranslate, certificateItems.length]);

  useEffect(() => {
    if (certificateItems.length <= 1) return undefined;

    const interval = window.setInterval(() => {
      if (autoScrollPausedRef.current) return;
      setActiveIndex((prev) => (prev + 1) % certificateItems.length);
    }, AUTO_SCROLL_MS);

    return () => window.clearInterval(interval);
  }, [certificateItems.length]);

  const pauseAutoScroll = () => {
    autoScrollPausedRef.current = true;
    window.setTimeout(() => {
      autoScrollPausedRef.current = false;
    }, AUTO_SCROLL_MS);
  };

  const handleDotClick = (idx) => {
    pauseAutoScroll();
    setActiveIndex(idx);
  };

  return (
    <section className="w-full bg-white pb-8 pt-6" dir={direction}>
      <div className="mb-5 px-4 text-center">
        <h2 className="text-2xl font-bold leading-snug text-slate-900">{heading}</h2>
      </div>

      <div className="relative">
        <div
          ref={containerRef}
          className="overflow-hidden"
          onMouseEnter={() => {
            autoScrollPausedRef.current = true;
          }}
          onMouseLeave={() => {
            autoScrollPausedRef.current = false;
          }}
          onTouchStart={() => {
            autoScrollPausedRef.current = true;
          }}
          onTouchEnd={pauseAutoScroll}
        >
          <div
            ref={trackRef}
            className="flex gap-3 px-[11vw] will-change-transform"
            style={{
              transform: `translateX(${translateX}px)`,
              transition: `transform ${SLIDE_TRANSITION_MS}ms ease-in-out`,
            }}
          >
            {certificateItems.map((item, idx) => {
              const Card = item.link && item.link !== "#" ? Link : "div";
              return (
                <Card
                  key={item.id ?? idx}
                  href={item.link && item.link !== "#" ? item.link : undefined}
                  className="flex w-[78vw] max-w-[300px] shrink-0 flex-col"
                >
                  <div className="relative flex h-[210px] w-full items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <img
                      src={item.image}
                      alt={item.title}
                      className="h-full w-full object-contain"
                      loading="lazy"
                    />
                  </div>

                  <p className="mt-3 text-center text-sm font-bold leading-snug text-slate-900 line-clamp-2">
                    {item.title}
                  </p>
                </Card>
              );
            })}
          </div>
        </div>

        <div className="mt-4 flex items-center justify-center gap-1.5">
          {certificateItems.map((_, idx) => (
            <button
              key={idx}
              type="button"
              onClick={() => handleDotClick(idx)}
              aria-label={`Go to slide ${idx + 1}`}
              aria-current={idx === activeIndex ? "true" : undefined}
            >
              <span
                className={`block h-2 rounded-full transition-all duration-300 ${
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
