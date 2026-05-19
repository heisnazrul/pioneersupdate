"use client";

import { useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowRight, faFilter } from "@fortawesome/free-solid-svg-icons";

export default function SectionWithFilter({
    title,
    subtitle,
    link,
    data,
    CardComponent,
    dataKey = "item",
    labels = {},
}) {
    const [filter, setFilter] = useState("all"); // 'all' | 'discounted'

    // Filter Logic
    const filteredData = data.filter((item) => {
        if (filter === "all") return true;
        if (filter === "discounted") {
            // Check for various discount indicators across different data types
            const hasTag = item.tags && item.tags.some(t => t.includes("OFF") || t.includes("Offer"));
            const hasLabel = item.discountLabel && item.discountLabel.length > 0;
            const hasOldPrice = item.priceOld || item.old_price;
            return hasTag || hasLabel || hasOldPrice;
        }
        return true;
    });

    return (
        <section>
            <div className="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 className="text-3xl font-semibold text-slate-900">{title}</h2>
                    <p className="mt-2 text-slate-500">{subtitle}</p>
                </div>

                <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    {/* Filter Buttons */}
                    <div className="flex items-center bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
                        <button
                            onClick={() => setFilter("all")}
                            className={`px-4 py-2 rounded-lg text-sm font-medium transition-all ${filter === "all"
                                    ? "bg-[#003B5C] text-white shadow-md"
                                    : "text-slate-500 hover:bg-slate-50"
                                }`}
                        >
                            {labels.all || "جميع العروض"}
                        </button>
                        <button
                            onClick={() => setFilter("discounted")}
                            className={`px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2 ${filter === "discounted"
                                    ? "bg-[#003B5C] text-white shadow-md"
                                    : "text-slate-500 hover:bg-slate-50"
                                }`}
                        >
                            <FontAwesomeIcon icon={faFilter} className="w-3" />
                            {labels.discounted || "العروض المخفضة فقط"}
                        </button>
                    </div>

                    {link && (
                        <Link
                            href={link}
                            className="hidden md:flex items-center gap-2 font-medium text-[#0057B7] hover:text-[#004494]"
                        >
                            <span>{labels.viewAll || "عرض الكل"}</span>
                            <FontAwesomeIcon icon={faArrowRight} className="transition-transform group-hover:translate-x-1" />
                        </Link>
                    )}
                </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {filteredData.slice(0, 8).map((item) => {
                    // Create props object dynamically based on the passed dataKey
                    const props = { [dataKey]: item };
                    return <CardComponent key={item.id} {...props} />;
                })}
            </div>

            {filteredData.length === 0 && (
                <div className="text-center py-12 bg-slate-50 rounded-2xl">
                    <p className="text-slate-500">{labels.empty || "لا توجد عناصر مطابقة للتصفية المحددة."}</p>
                    <button
                        onClick={() => setFilter("all")}
                        className="mt-2 text-[#0057B7] font-medium hover:underline"
                    >
                        {labels.clear || "إزالة التصفية"}
                    </button>
                </div>
            )}
        </section>
    );
}
