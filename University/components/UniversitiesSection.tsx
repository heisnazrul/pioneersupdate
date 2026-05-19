'use client';

import { University } from '@/lib/data/types';
import UniversityCard from '@/components/UniversityCard';
import SectionHeading from '@/components/ui/SectionHeading';
import Link from 'next/link';
import FadeIn from '@/components/FadeIn';

import { useState, useEffect } from 'react';

interface UniversitiesSectionProps {
    universities: University[];
    copy?: any;
    lang?: 'en' | 'ar';
}

export default function UniversitiesSection({ universities, copy, lang = 'en' }: UniversitiesSectionProps) {
    const isAr = lang === 'ar';
    const [startIndex, setStartIndex] = useState(0);
    const [visibleCount, setVisibleCount] = useState(5);

    // Responsive Visible Count
    useEffect(() => {
        const handleResize = () => {
            const width = window.innerWidth;
            if (width < 640) {
                setVisibleCount(1); // Mobile Small
            } else if (width < 768) {
                setVisibleCount(1); // Mobile Large
            } else if (width < 1024) {
                setVisibleCount(2); // Tablet
            } else if (width < 1280) {
                setVisibleCount(3); // LG
            } else {
                setVisibleCount(4); // XL and up
            }
        };

        handleResize(); // Init
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    const nextSlide = () => {
        if (startIndex + visibleCount < universities.length) {
            setStartIndex(prev => prev + 1);
        } else {
            setStartIndex(0);
        }
    };

    const prevSlide = () => {
        if (startIndex > 0) {
            setStartIndex(prev => prev - 1);
        }
    };

    // Auto-Play Logic
    useEffect(() => {
        const interval = setInterval(() => {
            nextSlide();
        }, 3000);

        return () => clearInterval(interval);
    }, [startIndex, visibleCount]);

    return (
        <section className="py-24 bg-slate-50 relative">
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40">
                {/* Header */}
                <div className="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
                    <SectionHeading
                        title={copy?.title || "Prestigious Universities"}
                        subtitle={copy?.subtitle || "World Class Rankings"}
                        align="left"
                        className="mb-0"
                    />

                    {/* Desktop Controls: Nav + View All */}
                    <div className="hidden md:flex items-center gap-6">
                        {/* Navigation Buttons */}
                        <div className="flex gap-4">
                            <button
                                onClick={prevSlide}
                                className={`w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center transition hover:bg-slate-50 ${startIndex === 0 ? 'opacity-50 cursor-not-allowed' : ''}`}
                                disabled={startIndex === 0}
                            >
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="text-slate-900">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button
                                onClick={nextSlide}
                                className={`w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center transition hover:bg-slate-50 ${startIndex + visibleCount >= universities.length ? 'opacity-50 cursor-not-allowed' : ''}`}
                                disabled={startIndex + visibleCount >= universities.length}
                            >
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="text-slate-900">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        {/* View All Button */}
                        <Link
                            href="/search/universities"
                            className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md"
                        >
                            {copy?.view_all || (isAr ? 'عرض الكل' : 'View All')} →
                        </Link>
                    </div>
                </div>

                {/* Universities Slider */}
                <div className="overflow-hidden w-full">
                    <div
                        className="flex transition-transform duration-500 ease-in-out"
                        style={{ transform: `translateX(calc(-${startIndex * (100 / visibleCount)}%))` }}
                    >
                        {universities.map((uni, idx) => (
                            <div
                                key={uni.id}
                                className="shrink-0 px-3"
                                style={{ width: `${100 / visibleCount}%` }}
                            >
                                <UniversityCard university={uni} index={idx} />
                            </div>
                        ))}
                    </div>
                </div>

                <div className="mt-8 text-center md:hidden">
                    <Link
                        href="/search/universities"
                        className="inline-flex px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md"
                    >
                        {copy?.browse_all || (isAr ? 'تصفح الجامعات' : 'Browse Universities')} →
                    </Link>
                </div>
            </div>
        </section>
    );
}
