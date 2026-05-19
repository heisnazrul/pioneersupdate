"use client";

import { useState, useEffect, useRef } from "react";
import Link from 'next/link';
import SectionHeading from '@/components/ui/SectionHeading';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGraduationCap, faCalendarAlt, faArrowRight, faChevronLeft, faChevronRight } from '@fortawesome/free-solid-svg-icons';

import { ScholarshipApiData } from '@/lib/api';

interface ScholarshipsSectionProps {
    scholarships?: ScholarshipApiData[];
    copy?: any;
}

const GAP = 24;
const AUTO_PLAY_MS = 5000;

export default function ScholarshipsSection({ scholarships = [], copy }: ScholarshipsSectionProps) {
    const viewportRef = useRef<HTMLDivElement>(null);
    const [cardW, setCardW] = useState(0);
    const [index, setIndex] = useState(0);
    const [visible, setVisible] = useState(4); // Default to xl
    const len = scholarships.length;

    // Responsive Logic
    useEffect(() => {
        const handleResize = () => {
            if (typeof window === "undefined") return;
            const w = window.innerWidth;
            if (w >= 1536) setVisible(4);      // 2xl - keeping 4 for better spacing
            else if (w >= 1280) setVisible(4); // xl
            else if (w >= 768) setVisible(2);  // md - changed to 2 for better visibility
            else setVisible(1);                // mobile
        };

        handleResize();
        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);
    }, []);

    // Width Calculation
    useEffect(() => {
        const el = viewportRef.current;
        if (!el) return;

        const calc = () => {
            const w = el.clientWidth;
            const totalGap = GAP * (visible - 1);
            const width = Math.floor((w - totalGap) / visible);
            setCardW(width);
        };

        calc();
        const ro = new ResizeObserver(calc);
        ro.observe(el);
        return () => ro.disconnect();
    }, [visible]);

    // Reset index on resize
    useEffect(() => {
        setIndex(0);
    }, [visible]);

    // Autoplay
    useEffect(() => {
        if (len <= visible) return;
        const id = setInterval(() => {
            setIndex((cur) => (cur + 1 > len - visible ? 0 : cur + 1));
        }, AUTO_PLAY_MS);
        return () => clearInterval(id);
    }, [len, visible]);

    const slideTo = (nextIndex: number) => {
        if (nextIndex < 0) {
            setIndex(Math.max(0, len - visible));
        } else if (nextIndex > len - visible) {
            setIndex(0);
        } else {
            setIndex(nextIndex);
        }
    };

    const nextSlide = () => {
        setIndex((cur) => (cur + 1 > len - visible ? 0 : cur + 1));
    };

    const prevSlide = () => {
        setIndex((cur) => (cur - 1 < 0 ? Math.max(0, len - visible) : cur - 1));
    };

    const trackStyle = {
        gap: `${GAP}px`,
        transform: `translateX(-${index * (cardW + GAP)}px)`,
        transition: "transform 600ms cubic-bezier(0.25, 1, 0.5, 1)",
    };

    const cardStyle = {
        width: `${cardW}px`,
        flex: `0 0 ${cardW}px`,
    };

    if (!scholarships || scholarships.length === 0) return null;

    return (
        <section className="py-24 bg-slate-50 relative overflow-hidden">
            {/* Minimal Pattern Background */}
            <div className="absolute inset-0 opacity-[0.03] pointer-events-none"
                style={{
                    backgroundImage: 'radial-gradient(#135FAE 1px, transparent 1px)',
                    backgroundSize: '24px 24px'
                }}>
            </div>

            <div className="px-4 md:px-10 xl:px-20 2xl:px-40 relative z-10">
                <div className="flex flex-col md:flex-row justify-between mb-8 md:mb-12 gap-6 items-center">
                    <SectionHeading
                        title={copy?.title || "Available Scholarships"}
                        subtitle={copy?.subtitle || "Financial Aid"}
                        description="Explore scholarship opportunities to help fund your education."
                        align="left"
                        className="mb-0"
                    />

                    <div className="hidden md:flex items-center gap-6">
                        <div className="flex gap-4">
                            <button
                                onClick={prevSlide}
                                className="bg-white w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition shadow-sm text-slate-700"
                            >
                                <FontAwesomeIcon icon={faChevronLeft} />
                            </button>
                            <button
                                onClick={nextSlide}
                                className="bg-white w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition shadow-sm text-slate-700"
                            >
                                <FontAwesomeIcon icon={faChevronRight} />
                            </button>
                        </div>

                        <Link
                            href="/scholarships"
                            className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md flex items-center gap-2"
                        >
                            <span>{copy?.view_all || 'View All'}</span>
                            <FontAwesomeIcon icon={faArrowRight} className="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <div className="relative">
                    {/* Slider Viewport */}
                    <div ref={viewportRef} className="overflow-hidden py-4 -my-4 px-1 -mx-1">
                        <div className="flex" style={trackStyle}>
                            {scholarships.map((schol) => (
                                <div key={schol.id} style={cardStyle} className="group bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 h-full flex flex-col relative overflow-hidden">
                                    {/* Top Accent Line */}
                                    <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                                    <div className="mb-2">
                                        <div className="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-[#135FAE] mb-3 group-hover:scale-110 transition-transform duration-300">
                                            <FontAwesomeIcon icon={faGraduationCap} />
                                        </div>
                                        <Link href={`/scholarships/${schol.slug}`} className="block">
                                            <h3 className="font-bold text-lg text-gray-900 leading-tight mb-1 hover:text-[#135FAE] transition-colors">
                                                {schol.title}
                                            </h3>
                                        </Link>
                                        <p className="text-2xl font-black text-[#135FAE] tracking-tight truncate">
                                            {schol.amount}
                                        </p>
                                    </div>

                                    <div className="mt-auto space-y-3">
                                        <div className="flex flex-wrap gap-2 content-start">
                                            {schol.tags?.slice(0, 3).map((tag, tIdx) => (
                                                <span key={tIdx} className="text-[10px] font-semibold bg-gray-50 text-gray-600 px-2 py-1 rounded border border-gray-100 whitespace-nowrap">
                                                    {tag}
                                                </span>
                                            ))}
                                        </div>

                                        <div className="flex items-center justify-between pt-4 border-t border-gray-50">
                                            <div className="flex items-center text-xs text-gray-500 font-medium">
                                                <FontAwesomeIcon icon={faCalendarAlt} className="mr-1.5 text-gray-400" />
                                                <span>{schol.deadline}</span>
                                            </div>
                                        </div>

                                        <Link href={`/scholarships/${schol.slug}`} className="block w-full text-center py-2.5 rounded-lg border border-gray-200 text-sm font-bold text-gray-700 hover:border-[#135FAE] hover:text-[#135FAE] hover:bg-blue-50 transition-all">
                                            {copy?.check_eligibility || 'Check Eligibility'}
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Mobile controls: Keep only simple View All for mobile if needed, or remove completely if duplicates header on mobile */}
                <div className="mt-8 text-center md:hidden">
                    <Link
                        href="/scholarships"
                        className="inline-flex px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md"
                    >
                        {copy?.view_all || 'View All'}
                    </Link>
                </div>
            </div>
        </section>
    );
}
