"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { pickLang } from "@/lib/i18nFallback";

const CACHE_KEY = "cache_reviews";
const MOBILE_AUTOPLAY_MS = 5000;

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
    <article className="rounded-3xl border border-[#E4EDF8] bg-white px-8 py-8  shadow-sm">
      <div className="mb-4 flex gap-1 text-sm">
        {Array.from({ length: totalStars }).map((_, idx) => (
          <FontAwesomeIcon
            key={idx}
            icon={idx < review.rating ? faStarSolid : faStarRegular}
            className={idx < review.rating ? "text-[#FFC107]" : "text-slate-300"}
          />
        ))}
      </div>
      <h3 className="mb-3 text-lg font-extrabold text-slate-900">
        {review.title}
      </h3>
      <p className="mb-6 text-sm leading-relaxed text-slate-700">
        {review.text}
      </p>
      <div className="text-sm font-normal text-slate-900">{review.name}</div>
      <div className="text-xs text-slate-500">{review.role}</div>
    </article>
  );
}

export default function StudentReviewsSection() {
  const [reviews, setReviews] = useState([]);
  const [mobileIndex, setMobileIndex] = useState(0);
  const [loaded, setLoaded] = useState(false);
  const mobileTrackRef = useRef(null);
  const { data: reviewsData } = useApi("/courseenglish/reviews");
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const reviewCopy = getCourseEnglishMessages(language)?.pages?.homepage?.reviews ?? {};
  const heading =
    reviewCopy?.heading ||
    (isArabic
      ? "ماذا يقول طلابنا عن دورات اللغة الإنجليزية"
      : "What our students say about their English courses");

  useEffect(() => {
    const normalize = (payload) =>
      payload.map((item, idx) => ({
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

    const load = async () => {
      const { default: mockData } = await import("@/data/mocks/reviews.json");
      const normalized = normalize(mockData);
      setReviews(normalized);
      setMobileIndex(0);
      setLoaded(true);
    };

    if (reviewsData?.reviews?.length) {
      const normalized = normalize(reviewsData.reviews);
      setReviews(normalized);
      setMobileIndex(0);
      setLoaded(true);
      return;
    }

    load();
  }, [reviewsData, isArabic]);

  const columns = useMemo(() => splitIntoColumns(reviews, 3), [reviews]);
  const COL1 = columns[0] || [];
  const COL2 = columns[1] || [];
  const COL3 = columns[2] || [];

  useEffect(() => {
    const id = setInterval(() => {
      setMobileIndex((i) => (reviews.length ? (i + 1) % reviews.length : 0));
    }, MOBILE_AUTOPLAY_MS);
    return () => clearInterval(id);
  }, [reviews.length]);

  useEffect(() => {
    const track = mobileTrackRef.current;
    if (!track) return;
    const dir = isArabic ? 1 : -1;
    track.style.transform = `translateX(${dir * mobileIndex * 100}%)`;
  }, [mobileIndex, isArabic]);

  if (loaded && reviews.length === 0) return null;

  return (
    <section className="bg-[#F3F7FF] py-12 md:py-20">
      <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
        <div className="text-center">
          <h2 className="text-2xl font-extrabold text-slate-900 md:text-4xl">
            {heading}
          </h2>
        </div>

        <div className="mt-8 md:hidden">
          <div className="relative overflow-hidden">
            <div
              ref={mobileTrackRef}
              className="flex transition-transform duration-700 ease-out"
            >
              {reviews.map((rev) => (
                <div key={rev.id} className="min-w-full px-2">
                  <ReviewCard review={rev} />
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="relative mt-12 hidden md:block">
          <div className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#F3F7FF] to-transparent z-20" />
          <div className="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#F3F7FF] to-transparent z-20" />

          <div className="relative grid gap-8 md:grid-cols-3 overflow-hidden h-[360px] lg:h-[720px]">
            <div className="scroll-column">
              <div className="scroll-inner scroll-up">
                {[...COL1, ...COL1].map((rev, idx) => (
                  <div key={`${rev.id}-c1-${idx}`} className="mb-6 last:mb-0">
                    <ReviewCard review={rev} />
                  </div>
                ))}
              </div>
            </div>

            <div className="scroll-column">
              <div className="scroll-inner scroll-down">
                {[...COL2, ...COL2].map((rev, idx) => (
                  <div key={`${rev.id}-c2-${idx}`} className="mb-6 last:mb-0">
                    <ReviewCard review={rev} />
                  </div>
                ))}
              </div>
            </div>

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

      <style jsx>{`
        .scroll-column {
          position: relative;
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
      `}</style>
    </section>
  );
}
