"use client";

import { useEffect, useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

const MOBILE_AUTOPLAY_MS = 5000;

function ReviewCard({ review }) {
  const totalStars = 5;
  return (
    <article className="rounded-3xl border border-[#E4EDF8] bg-white px-6 py-6 shadow-sm text-start">
      <div className="mb-3 flex gap-0.5 text-xs justify-start">
        {Array.from({ length: totalStars }).map((_, idx) => (
          <FontAwesomeIcon
            key={idx}
            icon={idx < review.rating ? faStarSolid : faStarRegular}
            className={idx < review.rating ? "text-[#FFC107]" : "text-slate-300"}
          />
        ))}
      </div>
      <h3 className="mb-2 text-md font-bold text-slate-900 leading-snug">
        {review.title}
      </h3>
      <p className="mb-4 text-[13px] leading-relaxed text-slate-600">
        {review.text}
      </p>
      <div className="text-[13px] font-semibold text-slate-800">{review.name}</div>
      <div className="text-[11px] text-slate-500">{review.role}</div>
    </article>
  );
}

export default function MobileReviews() {
  const { data: reviewsData } = useApi("/courseenglish/reviews");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const heading = t("pages.homepage.reviews.heading", "What our students say about their English courses");

  const rawReviews = reviewsData?.reviews;

  const reviews = useMemo(() => {
    const list = rawReviews ?? [];
    if (!list.length) return [];
    return list.map((item, idx) => ({
      id: item.id ?? idx,
      name: isArabic ? item.ar_name || item.name || "طالب" : item.name || item.ar_name || "Student",
      role:
        item.role ||
        item.institute ||
        item.institute_name ||
        item.course_name ||
        item.university_name ||
        (isArabic ? "طالب" : "Student"),
      title: isArabic ? item.ar_title || item.title || "تجربة طالب" : item.title || item.ar_title || "Student review",
      text:
        (isArabic ? item.ar_review_text || item.review_text : item.review_text || item.ar_review_text) ||
        item.text ||
        item.review ||
        "",
      rating: item.rating ?? 5,
    }));
  }, [rawReviews, isArabic]);

  const [mobileIndex, setMobileIndex] = useState(0);

  // Autoplay
  useEffect(() => {
    if (!reviews.length) return;
    const id = setInterval(() => {
      setMobileIndex((i) => (i + 1) % reviews.length);
    }, MOBILE_AUTOPLAY_MS);
    return () => clearInterval(id);
  }, [reviews.length]);

  if (reviews.length === 0) return null;

  const dir = isArabic ? 1 : -1;
  const trackStyle = {
    transform: `translateX(${dir * mobileIndex * 100}%)`,
    transition: "transform 500ms cubic-bezier(0.4, 0, 0.2, 1)",
  };

  return (
    <section className="block md:hidden bg-[#F3F7FF] py-10 w-full" dir={direction}>
      <div className="px-4">
        
        {/* Title */}
        <h2 className="text-xl font-bold leading-snug text-slate-900 text-center">
          {heading}
        </h2>

        {/* Carousel Window */}
        <div className="relative mt-8 overflow-hidden">
          <div className="flex" style={trackStyle}>
            {reviews.map((rev) => (
              <div key={rev.id} className="min-w-full shrink-0 px-2 select-none">
                <ReviewCard review={rev} />
              </div>
            ))}
          </div>
        </div>

        {/* Indicators Dots */}
        <div className="mt-4 flex justify-center gap-1.5">
          {reviews.map((_, idx) => (
            <button
              key={idx}
              type="button"
              onClick={() => setMobileIndex(idx)}
              aria-label={`Go to review ${idx + 1}`}
            >
              <div
                className={`h-1.5 rounded-full transition-all duration-300 ${
                  idx === mobileIndex ? "w-4 bg-[#1F63AE]" : "w-1.5 bg-slate-300"
                }`}
              />
            </button>
          ))}
        </div>

      </div>
    </section>
  );
}
