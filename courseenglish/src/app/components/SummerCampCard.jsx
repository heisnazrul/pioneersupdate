"use client";

import Image from "next/image";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faUser } from "@fortawesome/free-solid-svg-icons";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

const TOKENS = {
    border: "#E4EDF8",
    primary: "#1F63AE",
    primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

export default function SummerCampCard({ program, isArabic = false, layout = "vertical", variant = "default", onRemove }) {
    const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
    const interactionType = "summer_camps";
    const inWishlist = isInWishlist(interactionType, program.id);
    const inCompare = isInCompare(interactionType, program.id);
    const isSar = (program.currency || "GBP") === "SAR";
    const amount =
        program.priceFromValue !== undefined && program.priceFromValue !== null
            ? Number(program.priceFromValue).toLocaleString("en-GB", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
            : null;
    const weekLabel = isArabic ? "/ أسبوع" : "/ week";

    // Icons based on variant
    const WishlistIcon = variant === "wishlist" ? "/assets/icons/trash.svg" : (inWishlist ? "/assets/icons/heart-fill-black.svg" : "/assets/icons/heart-regular-black.svg");
    const SecondActionIcon = variant === "wishlist" ? "/assets/icons/eye.svg" : (inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg");

    const handleSecondAction = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (variant === "wishlist") {
            // View Action -> Navigate (SummerCampCards don't have slugs passed in prop 'program' usually? Wait, row has course.slug. 'program' prop has slug.)
            if (program.slug) {
                window.location.href = `/summer-programs/${program.slug}?program_id=${program.id}`; // Check route
            }
        } else {
            toggleCompare(interactionType, program.id);
        }
    };

    if (layout === "horizontal") {
        return (
            <article
                className="flex flex-row items-stretch rounded-[24px] bg-white transition hover:shadow-lg border overflow-hidden h-40"
                style={{ borderColor: TOKENS.border }}
            >
                <div className="relative h-full shrink-0 w-auto aspect-[4/3]">
                    <img
                        src={program.image}
                        alt={program.title}
                        className="h-full w-full object-cover bg-[#E7F0FB]"
                        loading="lazy"
                    />
                    <div className="absolute top-2 left-2 flex flex-col gap-2">
                        <button
                            type="button"
                        onClick={async (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            if (variant === "wishlist" && onRemove) {
                                const ok = await onRemove(program.id, "summer_camps");
                                if (!ok) await toggleWishlist(interactionType, program.id);
                            } else {
                                await toggleWishlist(interactionType, program.id);
                            }
                        }}
                            className="grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-700 shadow-sm transition hover:bg-red-50 hover:text-red-500 backdrop-blur-sm"
                        >
                            <Image src={variant === "wishlist" ? "/assets/icons/trash.svg" : WishlistIcon} alt="Wishlist" width={14} height={14} />
                        </button>
                        <button
                            type="button"
                            onClick={handleSecondAction}
                            className="grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-700 shadow-sm transition hover:bg-blue-50 hover:text-blue-500 backdrop-blur-sm"
                        >
                            <Image src={SecondActionIcon} alt="View" width={14} height={14} />
                        </button>
                    </div>
                </div>

                <div className="flex-1 p-4 flex flex-col min-w-0">
                    <h3 className="text-base font-medium text-slate-900 leading-tight line-clamp-2 mb-2">
                        {program.title}
                    </h3>
                    <div className="text-xs text-slate-500 flex items-center gap-1 mb-1">
                        <FontAwesomeIcon icon={faLocationDot} className="text-blue-500 h-3 w-3" />
                        <span className="truncate">{program.city}, {program.country}</span>
                    </div>

                    <div className="mt-auto border-t pt-2 w-full flex justify-between items-center bg-white">
                        <div className="text-sm font-medium text-slate-900">
                            {isSar ? "SAR" : "£"}{amount ?? (program.priceFrom || program.price)}
                        </div>
                    </div>
                </div>
            </article>
        );
    }

    return (
        <Link href={`/summer-programs/${program.slug}?camp=${program.id}`} className="block h-full">
            <article
                className="rounded-[24px] bg-white transition hover:shadow-lg h-full flex flex-col"
                style={{ border: `1px solid ${TOKENS.border}` }}
            >
                {/* Image with fixed ratio & overlays */}
                <div className="relative m-4 rounded-[20px] bg-[#E7F0FB]">
                    {/* ratio box: 4:3 */}
                    <div className="relative w-full pb-[75%] overflow-hidden rounded-[20px]">
                        <img
                            src={program.image}
                            alt={program.title}
                            className="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>

                    {/* Heart icon */}
                    <button
                        type="button"
                        onClick={async (event) => {
                            event.preventDefault();
                            event.stopPropagation();
                            const success = await toggleWishlist(interactionType, program.id);
                            if (success && variant === "wishlist") onRemove?.(program.id, "summer_camps");
                        }}
                        className="absolute left-4 top-4 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:bg-red-50 hover:text-red-500"
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
                            toggleCompare(interactionType, program.id);
                        }}
                        className="absolute left-4 top-15 grid h-9 w-9 place-items-center rounded-full bg-white text-slate-700 shadow-md transition hover:bg-blue-50 hover:text-blue-500"
                    >
                        <Image
                            src={inCompare ? "/assets/icons/selected-blue.svg" : "/assets/icons/compare.svg"}
                            alt="Compare"
                            width={18}
                            height={18}
                            className="h-[18px] w-[18px]"
                        />
                    </button>
                </div>

                {/* Content */}
                <div className="px-6 pb-5">
                    {/* Title */}
                    <h3 className=" text-[18px] font-extrabold leading-snug text-slate-900 line-clamp-2 min-h-[50px]">
                        {program.title}
                    </h3>

                    {/* Location */}
                    <div className="mt-2 flex items-center gap-2 text-[13px] text-slate-600">
                        <FontAwesomeIcon icon={faLocationDot} className="text-[#1F63AE]" />
                        <span>
                            {program.city}, {program.country}
                        </span>
                    </div>

                    {/* Age range */}
                    <div className="mt-1 flex items-center gap-2 text-[13px] text-slate-600">
                        <FontAwesomeIcon icon={faUser} className="text-[#1F63AE]" />
                        <span>{program.ageRange}</span>
                    </div>

                    {/* Description */}
                    <p className="mt-3 line-clamp-2 text-[14px] leading-6 text-slate-500 min-h-[48px]">
                        {(isArabic ? program.descriptionAr : program.description) || program.description}
                    </p>

                    {/* Footer: Price & CTA */}
                    <div className="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                        <div className="text-[14px] text-slate-700" dir="ltr">
                            <span className="text-slate-600" dir={isArabic ? "rtl" : "ltr"}>{isArabic ? "ابتداءً من " : "From "}</span>
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
                                    <span className="text-slate-900">£</span>
                                )}
                                <span>{amount ?? (program.priceFrom || program.price)}</span>
                            </span>
                            <span className="text-slate-600"> {weekLabel}</span>
                        </div>
                    </div>
                </div>
            </article>
        </Link>
    );
}
