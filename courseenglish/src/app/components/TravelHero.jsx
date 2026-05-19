"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowRight } from "@fortawesome/free-solid-svg-icons";
import Image from "next/image";

export default function TravelHero({ hero = {}, abs = (v) => v, isArabic = false }) {
    const dir = isArabic ? "rtl" : "ltr";

    return (
        <section className="relative overflow-hidden bg-[#0F172A] py-20 md:py-32 lg:py-40" dir={dir}>
            {/* Background Image with Overlay */}
            <div className="absolute inset-0 z-0">
                <Image
                    src={abs(hero.background_image) || "https://images.pexels.com/photos/1271619/pexels-photo-1271619.jpeg?auto=compress&cs=tinysrgb&w=1600"}
                    alt={hero.title || "Travel Background"}
                    fill
                    className="object-cover opacity-40"
                    priority
                    unoptimized
                />
                <div className="absolute inset-0 bg-gradient-to-r from-[#0F172A] via-[#0F172A]/80 to-transparent"></div>
            </div>

            <div className="container relative z-10 mx-auto px-4">
                <div className="max-w-2xl text-white">
                    {hero.badge && (
                        <span className="mb-4 inline-block rounded-full bg-[#0057B7]/20 px-4 py-1.5 text-sm font-medium text-[#38BDF8] border border-[#38BDF8]/30">
                            {hero.badge}
                        </span>
                    )}
                    <h1 className="mb-6 text-4xl font-semibold leading-tight md:text-6xl">
                        {hero.title || ""}
                    </h1>
                    <p className="mb-8 text-lg font-normal text-slate-300 md:text-xl leading-relaxed">
                        {hero.description || ""}
                    </p>

                    <div className="flex flex-col sm:flex-row gap-4">
                        {hero.primary_cta_text && (
                            <a
                                href={hero.primary_cta_url || "#inquiry-form"}
                                className="flex items-center justify-center gap-2 rounded-full bg-[#0057B7] px-8 py-4 text-base font-medium text-white transition hover:bg-[#004494]"
                            >
                                <span>{hero.primary_cta_text}</span>
                                <FontAwesomeIcon icon={faArrowRight} />
                            </a>
                        )}
                        {hero.secondary_cta_text && (
                            <a
                                href={hero.secondary_cta_url || "#destinations"}
                                className="rounded-full bg-white/10 px-8 py-4 text-base font-medium text-white backdrop-blur-sm transition hover:bg-white/20 text-center"
                            >
                                {hero.secondary_cta_text}
                            </a>
                        )}
                    </div>
                </div>
            </div>
        </section>
    );
}
