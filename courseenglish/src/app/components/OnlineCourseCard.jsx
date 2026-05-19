"use client";

import Image from "next/image";
import Link from "next/link";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

const TOKENS = {
    border: "#E4EDF8",
    primary: "#1F63AE",
    primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

export default function OnlineCourseCard({ course, isArabic = false, layout = "vertical", variant = "default", onRemove }) {
    const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
    const interactionType = "online_courses";
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
    const unitRaw = course.priceUnit || "/ course";
    const unitLabel = isArabic
        ? unitRaw.toLowerCase().includes("week")
            ? "/ أسبوع"
            : unitRaw.toLowerCase().includes("course")
                ? "/ الدورة"
                : unitRaw
        : unitRaw;
    const CardTag = course.slug ? Link : "article";
    const cardProps = course.slug ? { href: `/online-course/${course.slug}?course_id=${course.id}` } : {};
    const rawFlag = typeof course.flag === "string" ? course.flag.trim() : "";
    const isFlagImage =
        /^(https?:)?\/\//i.test(rawFlag) ||
        rawFlag.startsWith("/") ||
        rawFlag.startsWith("storage/");
    const normalizedFlagSrc = rawFlag.startsWith("storage/") ? `/${rawFlag}` : rawFlag;
    const renderFlag = () => {
        if (isFlagImage) {
            return (
                <img
                    src={normalizedFlagSrc}
                    alt={course.country || "Country"}
                    className="h-4 w-4 rounded-sm object-cover"
                    loading="lazy"
                />
            );
        }
        if (rawFlag && !rawFlag.includes("/") && !rawFlag.includes(".")) {
            return <span className="text-lg leading-none">{rawFlag}</span>;
        }
        return <span className="text-lg leading-none">🌍</span>;
    };

    // Icons based on variant
    const WishlistIcon = variant === "wishlist" ? "/assets/icons/trash.svg" : (inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg");
    // const CompareIcon = variant === "wishlist" ? "/assets/icons/eye.svg" : (inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg");

    // For Wishlist variant, second icon is View (Eye)
    // For Default, second icon is Compare
    const SecondActionIcon = variant === "wishlist" ? "/assets/icons/eye.svg" : (inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg");

    // Handler for second action
    const handleSecondAction = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (variant === "wishlist") {
            // View Action -> Navigate
            if (course.slug) {
                window.location.href = `/online-course/${course.slug}?course_id=${course.id}`;
            }
        } else {
            toggleCompare(interactionType, course.id);
        }
    };

    if (layout === "horizontal") {
        return (
            <CardTag
                {...cardProps}
                className="flex flex-row items-stretch rounded-[24px] bg-white transition hover:shadow-lg border overflow-hidden h-40 lg:h-48"
                style={{ borderColor: TOKENS.border }}
            >
                {/* Image Section - Auto width based on ratio, full HEIGHT */}
                <div className="relative h-full shrink-0">
                    <img
                        src={course.image}
                        alt={course.title}
                        className="h-full w-auto object-contain bg-[#E7F0FB]"
                        loading="lazy"
                    />
                    {/* If user wants it 'cover', use object-cover. User said 'width auto as per ratio'. contain/cover? 
                        'image shoud be same hight of this box' -> h-full. 
                        Usually cover looks better. Let's try cover with aspect ratio class or just h-full w-auto.
                    */}

                    {/* Overlays */}
                    <div className="absolute top-2 right-2 flex flex-col gap-2">
                        {/* Action 1: Wishlist / Trash */}
                        <button
                            type="button"
                        onClick={async (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            const success = await toggleWishlist(interactionType, course.id);
                            if (success && variant === "wishlist") onRemove?.(course.id, "online_courses");
                        }}
                            className="grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-700 shadow-sm transition hover:bg-red-50 hover:text-red-500 backdrop-blur-sm"
                        >
                            <Image
                                src={variant === "wishlist" && !inWishlist ? "/assets/icons/trash.svg" : WishlistIcon} // Logic check: if removed, it might flicker? toggleWishlist updates state.
                                // Actually, if variant is wishlist, always show trash?
                                // If item is IN wishlist, show trash to remove.
                                // If removed, it stays in list until reload?
                                // Let's use the Trash icon if variant=wishlist.
                                // Note: I don't have trash.svg in assets list potentially. I'll assume standard library or use FontAwesome if image missing.
                                // Fallback to FA if Image fails? No, keeping consistent.
                                alt="Remove"
                                width={14}
                                height={14}
                            />
                        </button>

                        {/* Action 2: View / Compare */}
                        <button
                            type="button"
                            onClick={handleSecondAction}
                            className="grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-700 shadow-sm transition hover:bg-blue-50 hover:text-blue-500 backdrop-blur-sm"
                        >
                            <Image
                                src={SecondActionIcon}
                                alt="View"
                                width={14}
                                height={14}
                            />
                        </button>
                    </div>
                </div>

                {/* Content Section */}
                <div className="flex-1 p-4 flex flex-col justify-between min-w-0">
                    <div>
                        <div className="flex justify-between items-start gap-2">
                            <div className="flex items-center gap-2 text-[11px] text-slate-500 mb-1">
                                <span>{course.provider}</span>
                                <span>•</span>
                                <span>{course.country}</span>
                            </div>
                            {/* Price Pill */}
                            <div className="bg-[#E7F2FF] text-[#1F63AE] px-2 py-1 rounded-lg text-xs font-medium whitespace-nowrap">
                                {isSar ? "SAR" : "£"}{amount ?? (course.priceNew || course.price)}
                            </div>
                        </div>

                        <h3 className="text-base font-medium text-slate-900 leading-tight line-clamp-2 mb-2">
                            {course.title}
                        </h3>

                        <div className="flex flex-wrap gap-2">
                            {course.mode && (
                                <span className="px-2 py-0.5 rounded-md bg-gray-100 text-slate-600 text-[10px] font-normal">
                                    {course.mode}
                                </span>
                            )}
                            {/* Add more tags if available */}
                        </div>
                    </div>

                    {/* Bottom Row if needed, or just cleaner look */}
                </div>
            </CardTag>
        );
    }

    return (
        <CardTag
            {...cardProps}
            className="rounded-[24px] bg-white transition hover:shadow-lg block"
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
                            const ok = await onRemove(course.id, "online_courses");
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
                    onClick={(event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        toggleCompare(interactionType, course.id);
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

                {/* online pill */}
                <div className="absolute left-3 top-3 rounded-full bg-[#1F63AE] px-3 py-1 text-xs font-normal text-white shadow">
                    {course.mode}
                </div>

                {/* discount pill */}
                <div className="absolute left-3 top-12 mt-1 rounded-full bg-[#E53935] px-3 py-1 text-xs font-normal text-white shadow">
                    {course.discountLabel}
                </div>
            </div>

            {/* body */}
            <div className="px-5 pb-4">
                {/* provider pill */}
                <div className="flex justify-start">
                    <span className="inline-flex rounded-full bg-[#E7F2FF] px-4 py-1 text-[13px] font-normal text-[#1F63AE]">
                        {course.provider}
                    </span>
                </div>

                {/* country + flag */}
                <div className="mt-4 flex items-center gap-2 text-[13px] text-slate-600">
                    {renderFlag()}
                    <span>{course.country}</span>
                </div>

                {/* course title */}
                <h3 className="mt-1 text-[18px] font-extrabold leading-snug text-slate-900 line-clamp-2 min-h-[50px]">
                    {course.title}
                </h3>

                {/* price row (LTR) */}
                <div className="mt-3 flex items-baseline gap-2 text-[14px] text-slate-700" dir="ltr">
                    <span className="inline-flex items-center gap-1 font-normal text-slate-900">
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
                        <span>{amount ?? (course.priceNew || course.price)}</span>
                    </span>
                    {course.priceOld && (
                        <span className="text-slate-400 line-through">{course.priceOld}</span>
                    )}
                    <span className="text-slate-600">{unitLabel}</span>
                </div>
            </div>
        </CardTag>
    );
}
