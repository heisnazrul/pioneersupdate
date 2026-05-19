"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar as faStarRegular } from "@fortawesome/free-regular-svg-icons";
import { faStar as faStarSolid } from "@fortawesome/free-solid-svg-icons";
import SectionHeading from "./ui/SectionHeading";

// Dummy Data
const DUMMY_REVIEWS = [
    { id: 1, name: "Maria Garcia", role: "Engineering Student", title: "Life-changing Support", text: "Pioneers Admissions made my dream of studying Engineering in Germany a reality. The visa guidance was spot on!", rating: 5 },
    { id: 2, name: "John Smith", role: "MBA Candidate", title: "Smooth Process", text: "The team helped me select the perfect MBA program in the UK. I couldn't have done it without their expert counseling.", rating: 5 },
    { id: 3, name: "Li Wei", role: "Computer Science", title: "Top Notch Service", text: "Excellent support for my destination Canada application. They handled all the documentation perfectly.", rating: 5 },
    { id: 4, name: "Priya Sharma", role: "Data Science", title: "Highly Recommended", text: "I was confused about university choices, but their analysis helped me find the best fit in Australia.", rating: 4 },
    { id: 5, name: "Ahmed Khan", role: "Medical Student", title: "Great Experience", text: "Studying medicine abroad seemed impossible until I met the Pioneers team. They guided me every step of the way.", rating: 5 },
    { id: 6, name: "Sarah Jenkins", role: "Art History", title: "Personalized Care", text: "They really care about your goals. I felt supported throughout my application to Italy.", rating: 5 },
    { id: 7, name: "David Chen", role: "Economics", title: "Scholarship Help", text: "Thanks to them, I secured a partial scholarship for my Masters in the USA!", rating: 5 },
    { id: 8, name: "Fatima Al-Sayed", role: "Architecture", title: "Professional Team", text: "Very professional and knowledgeable about European universities and their requirements.", rating: 4 },
    { id: 9, name: "Lucas Silva", role: "Business Analytics", title: "Quick & Efficient", text: "The visa process for Ireland was fast and stress-free thanks to their preparation.", rating: 5 },
    { id: 10, name: "Elena Popov", role: "Biotechnology", title: "Wonderful Guidance", text: "They helped me navigate the complex requirements for UK universities. Extremely grateful!", rating: 5 }
];

function splitIntoColumns(reviews: any[], columnsCount = 3) {
    const columns: any[][] = Array.from({ length: columnsCount }, () => []);
    reviews.forEach((review, index) => {
        columns[index % columnsCount].push(review);
    });
    return columns;
}

function ReviewCard({ review }: { review: any }) {
    const totalStars = 5;
    return (
        <article className="rounded-3xl border border-[#E4EDF8] bg-white px-8 py-8  shadow-sm hover:shadow-md transition-shadow">
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
            <div className="text-sm font-semibold text-slate-900">{review.name}</div>
            <div className="text-xs text-slate-500">{review.role}</div>
        </article>
    );
}

import { StudentReviewApiData } from "@/lib/api";

interface StudentReviewsSectionProps {
    reviews?: StudentReviewApiData[];
    copy?: any;
}

export default function StudentReviewsSection({ reviews = [], copy }: StudentReviewsSectionProps) {
    // Falls back to dummy reviews if the passed reviews array is empty
    const displayReviews = reviews.length > 0 ? reviews : DUMMY_REVIEWS;

    // We can keep DUMMY_REVIEWS as a constant in this file for fallback
    // or remove it if we want strict API usage. Keeping for safety for now.

    const [mobileIndex, setMobileIndex] = useState(0);
    const mobileTrackRef = useRef<HTMLDivElement>(null);

    const columns = useMemo(() => splitIntoColumns(displayReviews, 3), [displayReviews]);
    const COL1 = columns[0] || [];
    const COL2 = columns[1] || [];
    const COL3 = columns[2] || [];

    useEffect(() => {
        const id = setInterval(() => {
            setMobileIndex((i) => (displayReviews.length ? (i + 1) % displayReviews.length : 0));
        }, 5000);
        return () => clearInterval(id);
    }, [displayReviews.length]);

    useEffect(() => {
        const track = mobileTrackRef.current;
        if (!track) return;
        track.style.transform = `translateX(-${mobileIndex * 100}%)`;
    }, [mobileIndex]);

    return (
        <section className="bg-[#F3F7FF] py-12 md:py-20 relative overflow-hidden">
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40">
                <SectionHeading
                    title={copy?.text_title || "What our students say about their journey with us"}
                    subtitle={copy?.text_subtitle || "Testimonials"}
                    align="center"
                    className="mb-12"
                />

                <div className="mt-8 md:hidden">
                    <div className="relative overflow-hidden">
                        <div
                            ref={mobileTrackRef}
                            className="flex transition-transform duration-700 ease-out"
                        >
                            {displayReviews.map((rev) => (
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
