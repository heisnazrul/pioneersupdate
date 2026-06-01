"use client";

import { useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import MobileInfiniteCarousel from "@/components/mobile/mobile-infinite-carousel";

const REVIEW_AUTO_SCROLL_MS = 5000;

function ReviewCard({ review }) {
  const totalStars = 5;

  return (
    <article className="shrink-0 w-[78vw] snap-start rounded-3xl border border-[#E4EDF8] bg-white px-5 py-5 shadow-sm text-start">
      <div className="mb-3 flex gap-0.5 text-sm justify-start">
        {Array.from({ length: totalStars }).map((_, idx) => (
          <FontAwesomeIcon
            key={idx}
            icon={idx < review.rating ? faStarSolid : faStarRegular}
            className={idx < review.rating ? "text-[#FFC107]" : "text-slate-300"}
          />
        ))}
      </div>

      <h3 className="mb-2 text-[18px] font-bold leading-snug text-slate-900 line-clamp-2">{review.title}</h3>
      <p className="mb-4 text-[19px] leading-relaxed text-slate-600 line-clamp-5">{review.text}</p>
      <div className="text-[17px] font-semibold text-slate-800">{review.name}</div>
      <div className="text-[15px] text-slate-500">{review.role}</div>
    </article>
  );
}

export default function MobileReviews() {
  const { data: reviewsData } = useApi("/coursesat/home/reviews");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";

  const heading = t(
    "pages.homepage.reviews.mobile_heading",
    t("pages.homepage.reviews.heading", "What our students say about their English courses")
  );
  const subheading = t(
    "pages.homepage.reviews.mobile_subheading",
    "We're proud of our students' trust. Here are some experiences they shared after joining the best institutes."
  );

  const reviews = useMemo(() => {
    const list = reviewsData?.reviews ?? [];
    if (!list.length) return [];

    return list.map((item, idx) => {
      const course = isArabic
        ? item.course_ar_name || item.course_name || item.title || ""
        : item.course_name || item.title || item.course_ar_name || "";
      const university = isArabic
        ? item.university_ar_name || item.university_name || item.institute_ar_name || item.institute_name || ""
        : item.university_name || item.institute_name || item.university_ar_name || item.institute_ar_name || "";
      const role = [course, university].filter(Boolean).join(" · ");

      return {
        id: item.id ?? idx,
        name: isArabic ? item.ar_name || item.name || "طالب" : item.name || item.ar_name || "Student",
        role: role || (isArabic ? "طالب" : "Student"),
        title: isArabic
          ? item.ar_title || item.title || "تجربة طالب"
          : item.title || item.ar_title || "Student review",
        text: isArabic
          ? item.ar_review || item.ar_review_text || item.review || item.review_text || item.text || ""
          : item.review || item.review_text || item.text || item.ar_review || item.ar_review_text || "",
        rating: item.rating ?? 5,
      };
    });
  }, [reviewsData?.reviews, isArabic]);

  if (reviews.length === 0) return null;

  return (
    <section className="block md:hidden py-10 w-full px-4" dir={direction}>
      <div className="px-4 text-center">
        <h2 className="text-xl font-bold leading-snug text-slate-900">{heading}</h2>
        {subheading ? (
          <p className="mt-2 text-sm leading-relaxed text-slate-500">{subheading}</p>
        ) : null}
      </div>

      <div className="mt-6">
        <MobileInfiniteCarousel
          items={reviews}
          autoScrollMs={REVIEW_AUTO_SCROLL_MS}
          getItemKey={(review) => review.id}
          renderItem={(review, _idx, key) => <ReviewCard key={key} review={review} />}
        />
      </div>
    </section>
  );
}
