"use client";

import { useEffect, useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

function splitIntoColumns(reviews, columnsCount = 3) {
  const columns = Array.from({ length: columnsCount }, () => []);
  reviews.forEach((review, index) => {
    columns[index % columnsCount].push(review);
  });
  return columns;
}

function ReviewCard({ review }) {
  const totalStars = 5;
  return (
    <article className="rounded-3xl border border-[#E4EDF8] bg-white px-8 py-8 shadow-sm text-start">
      <div className="mb-4 flex gap-1 text-sm justify-start">
        {Array.from({ length: totalStars }).map((_, idx) => (
          <FontAwesomeIcon
            key={idx}
            icon={idx < review.rating ? faStarSolid : faStarRegular}
            className={idx < review.rating ? "text-[#FFC107]" : "text-slate-300"}
          />
        ))}
      </div>
      <h3 className="mb-3 text-lg font-bold text-slate-900">
        {review.title}
      </h3>
      <p className="mb-6 text-sm leading-relaxed text-slate-700">
        {review.text}
      </p>
      <div className="text-sm font-semibold text-slate-900">{review.name}</div>
      <div className="text-xs text-slate-500">{review.role}</div>
    </article>
  );
}

export default function DesktopReviews() {
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

  const columns = useMemo(() => splitIntoColumns(reviews, 3), [reviews]);
  const COL1 = columns[0] || [];
  const COL2 = columns[1] || [];
  const COL3 = columns[2] || [];

  if (reviews.length === 0) return null;

  return (
    <section className="hidden md:block bg-[#F3F7FF] py-16 sm:py-20 w-full" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">
        
        {/* Title */}
        <div className="text-center">
          <h2 className="text-3xl font-bold text-slate-900 sm:text-4xl">
            {heading}
          </h2>
        </div>

        {/* Vertical Waterfalls */}
        <div className="relative mt-12 overflow-hidden h-[360px] lg:h-[720px]">
          {/* Top/Bottom Faders */}
          <div className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#F3F7FF] to-transparent z-20" />
          <div className="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#F3F7FF] to-transparent z-20" />

          <div className="relative grid gap-8 md:grid-cols-3 h-full overflow-hidden">
            
            {/* Column 1 (Scroll Up) */}
            <div className="scroll-column">
              <div className="scroll-inner scroll-up">
                {[...COL1, ...COL1].map((rev, idx) => (
                  <div key={`${rev.id}-c1-${idx}`} className="mb-6 last:mb-0">
                    <ReviewCard review={rev} />
                  </div>
                ))}
              </div>
            </div>

            {/* Column 2 (Scroll Down) */}
            <div className="scroll-column">
              <div className="scroll-inner scroll-down">
                {[...COL2, ...COL2].map((rev, idx) => (
                  <div key={`${rev.id}-c2-${idx}`} className="mb-6 last:mb-0">
                    <ReviewCard review={rev} />
                  </div>
                ))}
              </div>
            </div>

            {/* Column 3 (Scroll Up) */}
            <div className="scroll-column">
              <div className="scroll-inner scroll-up">
                {[...COL3, ...COL3].map((rev, idx) => (
                  <div key={`${rev.id}-c3-${idx}`} className="mb-6 last:mb-0">
                    <ReviewCard review={rev} />
                  </div>
                ))}
              </div>
            </div>

          </div>
        </div>

      </div>

      <style dangerouslySetInnerHTML={{__html: `
        .scroll-column {
          position: relative;
          height: 100%;
          overflow: hidden;
        }
        .scroll-inner {
          position: absolute;
          left: 0;
          right: 0;
          display: flex;
          flex-direction: column;
        }
        .scroll-up {
          animation: scrollUp 30s linear infinite;
        }
        .scroll-down {
          animation: scrollDown 30s linear infinite;
        }
        .scroll-column:hover .scroll-inner {
          animation-play-state: paused;
        }
        @keyframes scrollUp {
          0% {
            transform: translateY(0);
          }
          100% {
            transform: translateY(-50%);
          }
        }
        @keyframes scrollDown {
          0% {
            transform: translateY(-50%);
          }
          100% {
            transform: translateY(0);
          }
        }
      `}} />
    </section>
  );
}
