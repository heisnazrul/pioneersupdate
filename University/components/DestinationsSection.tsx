"use client";

import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { DestinationData } from '@/lib/api';
import SectionHeading from '@/components/ui/SectionHeading';

// Fallback Mock Data
const MOCK_DESTINATIONS: DestinationData[] = [
    { name: 'USA', slug: 'usa', image: '/assets/destinations/usa.jpg' },
    { name: 'Canada', slug: 'canada', image: '/assets/destinations/canada.jpg' },
    { name: 'UK', slug: 'uk', image: '/assets/destinations/uk.jpg' },
    { name: 'Australia', slug: 'australia', image: '/assets/destinations/australia.jpg' },
    { name: 'Germany', slug: 'germany', image: '/assets/destinations/germany.jpg' },
    { name: 'Ireland', slug: 'ireland', image: '/assets/destinations/ireland.jpg' },
];

interface DestinationsSectionProps {
    destinations: DestinationData[];
    copy?: any;
}

export default function DestinationsSection({ destinations: initialDestinations, copy }: DestinationsSectionProps) {
    // If no data provided or empty, fallback to mock data
    const destinations = initialDestinations?.length > 0 ? initialDestinations : MOCK_DESTINATIONS;
    
    // We can infer layout direction from document or CSS later, but for the carousel sliding math,
    // we'll default to 'en' (LTR) sliding direction. The CSS `dir="rtl"` on individual cards handles the text.
    const lang: string = 'en';

    const [startIndex, setStartIndex] = useState(0);
    const [visibleCount, setVisibleCount] = useState(5);

    // Responsive Visible Count
    useEffect(() => {
        const handleResize = () => {
            const width = window.innerWidth;
            if (width < 768) {
                setVisibleCount(2); // Mobile
            } else if (width < 1024) {
                setVisibleCount(3); // Tablet
            } else if (width < 1280) {
                setVisibleCount(4); // LG
            } else {
                setVisibleCount(5); // XL and up
            }
        };

        handleResize(); // Init
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    const nextSlide = () => {
        if (startIndex + visibleCount < destinations.length) {
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
    }, [startIndex, destinations.length, visibleCount]);

    return (
        <section className="py-20 bg-white overflow-hidden">
            {/* Header */}
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40 mb-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <SectionHeading
                    title={copy?.title || "Prestigious Destinations"}
                    subtitle={copy?.subtitle || "Study Abroad"}
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
                            className={`w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center transition hover:bg-slate-50 ${startIndex + visibleCount >= destinations.length ? 'opacity-50 cursor-not-allowed' : ''}`}
                            disabled={startIndex + visibleCount >= destinations.length}
                        >
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="text-slate-900">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    {/* View All Button */}
                    <Link
                        href="/destinations"
                        className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md"
                    >
                        {(copy?.view_all || 'View All')} →
                    </Link>
                </div>
            </div>

            {/* Slider Track - Carousel */}
            {/* Outer container defines the available width with padding */}
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40">
                {/* Inner container STRICTLY clips the content at the padding edge */}
                <div className="overflow-hidden w-full" dir="ltr">
                    {/* Note: We force the track container to LTR so our manual transform consistently moves slides from left to right visually, regardless of page RTL */}
                    <div
                        className="flex gap-4 transition-transform duration-500 ease-in-out"
                        style={{ transform: `translateX(calc(${lang === 'ar' ? '' : '-'}*${startIndex * (100 / visibleCount)}%))` }}
                    >
                        {destinations.map((d, i) => (
                            <div
                                key={i}
                                className="shrink-0"
                                style={{ width: `calc((100% - ${(visibleCount - 1) * 16}px) / ${visibleCount})` }} // gap-4 is 16px
                                dir={lang === 'ar' ? 'rtl' : 'ltr'}
                            >
                                <DestinationCard destination={d} />
                            </div>
                        ))}
                    </div>
                </div>

                <div className="mt-8 text-center md:hidden">
                    <Link
                        href="/destinations"
                        className="inline-flex px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md"
                    >
                        {(copy?.view_all || 'View All')} →
                    </Link>
                </div>
            </div>
        </section>
    );
}

function DestinationCard({ destination }: { destination: DestinationData }) {
    return (
        <Link href={`/destinations/${destination.slug}`} className="relative block h-[300px] w-full overflow-hidden group select-none rounded-xl">
            {/* Image */}
            <div className="absolute inset-0 w-full h-full">
                <Image
                    src={destination.image || '/hero.png'}
                    alt={destination.name}
                    fill
                    className="object-cover transition-transform duration-700 group-hover:scale-110"
                    draggable={false}
                    unoptimized
                />
                {/* Overlay Gradient */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-90" />
            </div>

            {/* Content */}
            <div className="absolute bottom-0 left-0 w-full p-6 text-center">
                <h3 className="text-xl font-bold text-white mb-2">
                    Study in {destination.name}
                </h3>
            </div>
        </Link>
    );
}
