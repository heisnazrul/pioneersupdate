"use client";

import Image from "next/image";

export default function TravelDestinations({ data = {}, abs = (v) => v, isArabic = false }) {
    const { eyebrow, heading, view_all_text, view_all_url, items = [] } = data;
    const dir = isArabic ? "rtl" : "ltr";
    return (
        <section className="py-20 bg-[#F8FAFC]" dir={dir} id="destinations">
            <div className="container mx-auto px-4">
                <div className="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        {eyebrow && <span className="text-[#0057B7] font-medium tracking-wider uppercase text-sm">{eyebrow}</span>}
                        <h2 className="mt-2 text-3xl font-semibold text-slate-900 md:text-4xl">{heading}</h2>
                    </div>
                    {view_all_text && (
                        <a href={view_all_url || "#"} className="hidden md:block text-[#0057B7] font-medium hover:underline">
                            {view_all_text} →
                        </a>
                    )}
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {items.map((dest, idx) => (
                        <a
                            key={idx}
                            href={dest.url || "#"}
                            className="group relative h-[400px] overflow-hidden rounded-3xl block focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0057B7]"
                        >
                            <Image
                                src={abs(dest.image)}
                                alt={dest.name}
                                fill
                                className="object-cover transition duration-500 group-hover:scale-110"
                                unoptimized
                            />
                            {/* Gradient Overlay */}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 transition group-hover:opacity-90"></div>

                            {/* Content */}
                            <div className="absolute bottom-0 left-0 p-6 w-full">
                                {dest.tag && (
                                    <span className="inline-block px-3 py-1 mb-3 text-xs font-medium text-white bg-[#0057B7] rounded-full">
                                        {dest.tag}
                                    </span>
                                )}
                                <h3 className="text-2xl font-medium text-white mb-1">{dest.name}</h3>
                            </div>
                        </a>
                    ))}
                </div>

                {view_all_text && (
                    <div className="mt-8 text-center md:hidden">
                        <a href={view_all_url || "#"} className="text-[#0057B7] font-medium">
                            {view_all_text} →
                        </a>
                    </div>
                )}
            </div>
        </section>
    );
}
