"use client";

import { useState, useRef, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";
import { motion } from "framer-motion";
import { fetchCertificates, CertificateData } from "@/lib/api";

// MOCK DATA FOR CERTIFICATES (Fallback)
const MOCK_CERTIFICATES = [
    {
        image: "/assets/certificates/c1.png",
        title: "British Council",
        link: "#",
    },
    {
        image: "/assets/certificates/c2.png",
        title: "ICEF Agency",
        link: "#",
    },
    {
        image: "/assets/certificates/c3.png",
        title: "English UK",
        link: "#",
    },
    {
        image: "/assets/certificates/c4.png",
        title: "Quality English",
        link: "#",
    }
];

interface CertificatesSectionProps {
    data?: CertificateData[] | null;
    copy?: any;
}

export default function CertificatesSection({ data, copy }: CertificatesSectionProps) {
    const [items, setItems] = useState<CertificateData[]>(data || []);
    const [loading, setLoading] = useState(true);

    const [activeIndex, setActiveIndex] = useState(0);
    const sliderRef = useRef<HTMLDivElement>(null);

    const VISIBLE_DESKTOP = 2; // Show 2 at a time

    useEffect(() => {
        // If data is provided and has items, use it and stop loading
        if (data && data.length > 0) {
            setItems(data);
            setLoading(false);
            return;
        }

        // If data is provided but empty, use MOCK as fallback immediately
        if (Array.isArray(data) && data.length === 0) {
            setItems(MOCK_CERTIFICATES);
            setLoading(false);
            return;
        }

        // Otherwise (data is null/undefined), fetch from API client-side
        const loadData = async () => {
            const apiData = await fetchCertificates();
            if (apiData && apiData.length > 0) {
                setItems(apiData);
            } else {
                setItems(MOCK_CERTIFICATES);
            }
            setLoading(false);
        };
        loadData();
    }, [data]);

    // Prevent hydration mismatch or empty render if still loading? 
    // Actually better to render with mock or empty initially or show skeleton.
    // For now, if empty and not loading, we might return null, but fallback handles it.

    // If items empty (shouldn't happen with fallback), just return null
    // Moved to bottom to avoid hook violation

    // Get visible items for desktop slider
    const getVisibleCertificates = (visibleCount: number) => {
        const visibleItems = [];
        for (let i = 0; i < visibleCount; i++) {
            const idx = (activeIndex + i) % items.length;
            visibleItems.push(items[idx]);
        }
        return visibleItems;
    };

    const handleDotClick = (idx: number) => {
        setActiveIndex(idx);

        // Scroll on mobile slider
        if (sliderRef.current) {
            sliderRef.current.scrollTo({
                left: idx * 280, // card width + gap
                behavior: "smooth",
            });
        }
    };

    // AUTO SLIDE every 4 seconds
    useEffect(() => {
        const interval = setInterval(() => {
            setActiveIndex((prev) => (prev + 1) % items.length);

            if (sliderRef.current) {
                sliderRef.current.scrollTo({
                    left: ((activeIndex + 1) % items.length) * 280,
                    behavior: "smooth",
                });
            }
        }, 4000);

        return () => clearInterval(interval);
    }, [activeIndex, items.length]);

    // If items empty (shouldn't happen with fallback), just return null
    if (!loading && items.length === 0) return null;

    return (
        <>
            <section className="hidden lg:block w-full py-20 md:py-10 px-4 md:px-10 xl:px-20 2xl:px-40 bg-white">
                <div className="mx-auto rounded-3xl bg-[#E8F3FC] py-10 md:px-14 md:py-14">
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6 2xl:gap-10">

                        {/* LEFT — Desktop Slider */}
                        <div className="order-2 md:order-1 w-full md:w-3/5">
                            <div className="hidden md:flex gap-4 p-2 2xl:gap-4 overflow-hidden">
                                {getVisibleCertificates(VISIBLE_DESKTOP).map((item, idx) => {
                                    if (!item) return null;
                                    const hasLink = item.link && item.link !== "#";
                                    const content = (
                                        <>
                                            <div className="relative h-60 rounded-2xl overflow-hidden bg-white flex items-center justify-center">
                                                {/* Fallback image if asset missing */}
                                                <Image
                                                    src={item.image || '/logo.png'}
                                                    alt={item.title}
                                                    fill
                                                    sizes="(min-width: 1024px) 40vw, 100vw"
                                                    unoptimized
                                                    className="object-contain p-2 md:p-2"
                                                />
                                            </div>
                                            <p className="my-2 text-center text-md font-semibold text-slate-900">
                                                {item.title}
                                            </p>
                                        </>
                                    );

                                    return (
                                        <motion.div
                                            key={`${activeIndex}-${idx}`}
                                            initial={{ opacity: 0, x: 20 }}
                                            animate={{ opacity: 1, x: 0 }}
                                            className="flex-1"
                                        >
                                            {hasLink ? (
                                                <Link
                                                    href={item.link!}
                                                    className="block rounded-2xl bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)] p-4 transition hover:shadow-[0_20px_60px_rgba(15,23,42,0.12)] cursor-pointer"
                                                >
                                                    {content}
                                                </Link>
                                            ) : (
                                                <div className="block rounded-2xl bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)] p-4 transition hover:shadow-[0_20px_60px_rgba(15,23,42,0.12)] cursor-default">
                                                    {content}
                                                </div>
                                            )}
                                        </motion.div>
                                    );
                                })}
                            </div>

                            {/* Desktop Dots */}
                            <div className="mt-4 flex justify-center gap-1.5">
                                {MOCK_CERTIFICATES.map((_, idx) => (
                                    <button
                                        key={idx}
                                        type="button"
                                        onClick={() => handleDotClick(idx)}
                                        aria-label={`Go to slide ${idx + 1}`}
                                    >
                                        <div
                                            className={`h-2 rounded-full transition-all duration-300 ${idx === activeIndex ? "w-6 bg-[#135FAE]" : "w-2 bg-slate-300"
                                                }`}
                                        />
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* RIGHT — Text */}
                        <div className="order-1 lg:order-2 w-full lg:w-2/5">
                            <h2 className="text-xl lg:text-2xl xl:text-3xl 2xl:text-5xl font-extrabold text-slate-900 leading-tight">
                                {copy?.heading || 'We are accredited by many institutions'}
                            </h2>

                            <p className="mt-4 text-sm lg:text-base text-slate-700 leading-relaxed max-w-md">
                                {copy?.body || 'We are proud of our partnerships with leading English language institutes and accredited educational organizations around the world.'}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========================================================= */}
            {/* MOBILE VERSION */}
            {/* ========================================================= */}
            <section className="lg:hidden w-full py-20">

                {/* Heading */}
                <div className="px-4 text-center mb-6">
                    <h2 className="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-snug mb-10">
                        {copy?.heading || 'We are accredited by many institutions'}
                    </h2>
                </div>

                {/* Mobile Slider */}
                <div className="relative">
                    <div
                        ref={sliderRef}
                        className="flex gap-4 overflow-x-auto snap-x snap-mandatory px-4 pb-4 scrollbar-hide scroll-h-0 items-center"
                    >
                        {items.map((item, idx) => {
                            const hasLink = item.link && item.link !== "#";
                            const content = (
                                <>
                                    <div className="relative h-56 w-full rounded-2xl overflow-hidden bg-white">
                                        <Image
                                            src={item.image || '/logo.png'}
                                            alt={item.title}
                                            fill
                                            unoptimized
                                            className="object-contain p-4"
                                        />
                                    </div>

                                    <p className="py-4 text-center text-sm font-bold text-slate-900 px-2 line-clamp-1">
                                        {item.title}
                                    </p>
                                </>
                            );

                            return (
                                <div key={idx} className="min-w-[280px] snap-center">
                                    {hasLink ? (
                                        <Link
                                            href={item.link!}
                                            className="block bg-white rounded-2xl shadow-lg p-2 overflow-hidden"
                                        >
                                            {content}
                                        </Link>
                                    ) : (
                                        <div className="block bg-white rounded-2xl shadow-lg p-2 overflow-hidden">
                                            {content}
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                    </div>

                    {/* Mobile Dots */}
                    <div className="mt-2 flex justify-center gap-1.5">
                        {items.map((_, idx) => (
                            <button
                                key={idx}
                                type="button"
                                onClick={() => handleDotClick(idx)}
                                aria-label={`Go to slide ${idx + 1}`}
                            >
                                <div
                                    className={`h-2 rounded-full transition-all duration-300 ${idx === activeIndex ? "w-6 bg-[#135FAE]" : "w-2 bg-slate-300"
                                        }`}
                                />
                            </button>
                        ))}
                    </div>
                </div>
            </section>
        </>
    );
}
