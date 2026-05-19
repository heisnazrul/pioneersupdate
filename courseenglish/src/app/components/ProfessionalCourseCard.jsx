"use client";

import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faCertificate } from "@fortawesome/free-solid-svg-icons";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

const TOKENS = {
    border: "#E4EDF8",
    primary: "#1F63AE",
    primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

export default function ProfessionalCourseCard({ course, isArabic = false, variant = "default", onRemove }) {
    const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
    const interactionType = "training_courses";
    const inWishlist = isInWishlist(interactionType, course.id);
    const inCompare = isInCompare(interactionType, course.id);
    const isSar = (course.currency || "GBP") === "SAR";
    const amount =
        course.priceValue !== undefined && course.priceValue !== null
            ? Number(course.priceValue).toLocaleString("en-GB", {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
              })
            : null;
    return (
        <article
            className="rounded-[24px] bg-white transition hover:shadow-lg"
            style={{ border: `1px solid ${TOKENS.border}` }}
        >
            {/* Image + overlays with ratio box */}
            <div className="relative m-3 overflow-hidden rounded-[22px] bg-[#E7F0FB]">
                {/* 4:3 ratio box (height = 75% of width) */}
                <div className="relative w-full pb-[75%]">
                    <img
                        src={course.image}
                        alt={course.title}
                        className="absolute inset-0 h-full w-full rounded-[22px] object-cover"
                        loading="lazy"
                    />
                </div>

                {/* wishlist heart */}
                <button
                    type="button"
                    onClick={async (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        if (variant === "wishlist" && onRemove) {
                            const ok = await onRemove(course.id, "training_courses");
                            if (!ok) await toggleWishlist(interactionType, course.id);
                        } else {
                            await toggleWishlist(interactionType, course.id);
                        }
                    }}
                    className="absolute right-3 top-3 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:bg-red-50 hover:text-red-500"
                >
                    <Image
                        src={inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg"}
                        alt="Wishlist"
                        width={18}
                        height={18}
                        className="h-[18px] w-[18px]"
                    />
                </button>
                <button
                    type="button"
                    onClick={async (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        await toggleCompare(interactionType, course.id);
                    }}
                    className="absolute right-3 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:bg-blue-50 hover:text-blue-500"
                >
                    <Image
                        src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
                        alt="Compare"
                        width={18}
                        height={18}
                        className="h-[18px] w-[18px]"
                    />
                </button>

                {/* Category pill */}
                <div className="absolute left-3 top-3 rounded-full bg-[#1F63AE] px-3 py-1 text-xs font-normal text-white shadow">
                    {course.category || course.subject || "Course"}
                </div>
            </div>

            {/* body */}
            <div className="px-5 pb-4">
                {/* provider pill */}
                <div className="flex justify-start">
                    <span className="inline-flex items-center gap-2 rounded-full bg-[#E7F2FF] px-3 py-1 text-[12px] font-normal text-[#1F63AE]">
                        <FontAwesomeIcon icon={faCertificate} className="text-[10px]" />
                        {course.provider || "Certified"}
                    </span>
                </div>

                {/* location */}
                <div className="mt-3 flex items-center gap-2 text-[13px] text-slate-600">
                    <FontAwesomeIcon icon={faLocationDot} className="text-[#1F63AE]" />
                    <span>{course.location || "Online / Hybrid"}</span>
                </div>

                {/* course title */}
                <h3 className="mt-2 text-[18px] font-extrabold leading-snug text-slate-900 line-clamp-2 min-h-[50px]">
                    {course.title}
                </h3>

                {/* price row (LTR) */}
                <div className="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                    <div className="text-[14px] text-slate-700">
                        {amount !== null && (
                            <>
                                <span className="text-slate-500">
                                    {isArabic ? "ابتداءً من " : "Starting from "}
                                </span>
                                <span className="inline-flex items-center gap-1 font-medium text-slate-900">
                                    {isSar ? (
                                        <Image
                                            src="/assets/sar.svg"
                                            alt="SAR"
                                            width={16}
                                            height={16}
                                            className="h-4 w-4 invert"
                                        />
                                    ) : (
                                        <span>£</span>
                                    )}
                                    <span>{amount}</span>
                                </span>
                            </>
                        )}
                        {amount === null && (
                            <span className="font-medium text-slate-900">
                                {isArabic ? "يرجى الاستفسار عن السعر" : "Enquire for Price"}
                            </span>
                        )}
                    </div>
                    <span className="text-xs font-medium text-slate-400">{course.duration}</span>
                </div>
            </div>
        </article>
    );
}
