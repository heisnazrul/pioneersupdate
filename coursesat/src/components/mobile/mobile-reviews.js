"use client";

/* eslint-disable @next/next/no-img-element */
import { useEffect, useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
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

      {review.screenshot ? (
        <div className="mb-3 overflow-hidden rounded-2xl border border-[#E4EDF8] bg-slate-50">
          <img
            src={review.screenshot}
            alt={review.name}
            className="max-h-44 w-full object-cover object-top"
            loading="lazy"
          />
        </div>
      ) : null}

      <h3 className="mb-2 text-md font-bold text-slate-900 leading-snug">{review.title}</h3>
      <p className="mb-4 whitespace-pre-line text-[13px] leading-relaxed text-slate-600">{review.text}</p>
      <div className="text-[13px] font-semibold text-slate-800">{review.name}</div>
      <div className="text-[11px] text-slate-500">{review.role}</div>
    </article>
  );
}

export default function MobileReviews() {
  const { data: reviewsData } = useApi("/coursesat/home/reviews");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";
  const heading = t("pages.homepage.reviews.heading", "What our students say about their English courses");

  const reviews = useMemo(() => {
    const list = reviewsData?.reviews ?? [];
    if (!list.length) return [];

    return list.map((item, idx) => {
      const course = item.course_name || item.title || "";
      const university = item.university_name || item.institute_name || "";
      const role = [course, university].filter(Boolean).join(" · ");

      return {
        id: item.id ?? idx,
        name: isArabic ? item.ar_name || item.name || "طالب" : item.name || item.ar_name || "Student",
        role: role || (isArabic ? "طالب" : "Student"),
        title: isArabic
          ? course || item.ar_title || item.title || "تجربة طالب"
          : course || item.title || "Student review",
        text:
          item.ar_review ||
          item.ar_review_text ||
          item.review ||
          item.review_text ||
          item.text ||
          "",
        rating: item.rating ?? 5,
        screenshot: getImageUrl(item.screenshot || item.thumbnail),
      };
    });
  }, [reviewsData?.reviews, isArabic]);

  const [mobileIndex, setMobileIndex] = useState(0);

  useEffect(() => {
    if (!reviews.length) return;
    const id = setInterval(() => {
      setMobileIndex((i) => (i + 1) % reviews.length);
    }, MOBILE_AUTOPLAY_MS);
    return () => clearInterval(id);
  }, [reviews.length]);

  if (reviews.length === 0) return null;

  const trackStyle = {
    transform: `translateX(${-1 * mobileIndex * 100}%)`,
    transition: "transform 500ms cubic-bezier(0.4, 0, 0.2, 1)",
  };

  return (
    <section className="block md:hidden bg-[#F3F7FF] py-10 w-full" dir={direction}>
      <div className="px-4">
        <h2 className="text-xl font-bold leading-snug text-slate-900 text-center">{heading}</h2>

        <div className="relative mt-8 overflow-hidden" dir="ltr">
          <div className="flex" style={trackStyle}>
            {reviews.map((rev) => (
              <div key={rev.id} className="min-w-full shrink-0 px-2 select-none">
                <ReviewCard review={rev} />
              </div>
            ))}
          </div>
        </div>

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
