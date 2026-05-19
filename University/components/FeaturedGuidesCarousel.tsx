"use client";

import { useRef, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBookOpen, faArrowRight } from '@fortawesome/free-solid-svg-icons';

export interface Guide {
    title: string;
    category: string;
    read_time: string;
    image: string;
    link?: string;
}

export default function FeaturedGuidesCarousel({ guides = [] }: { guides?: Guide[] }) {
    const scrollRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const scrollContainer = scrollRef.current;
        if (!scrollContainer) return;

        let animationFrameId: number;
        let startTime: number;
        // const scrollAmountPerSecond = 50; 

        const scroll = (timestamp: number) => {
            if (!startTime) startTime = timestamp;
            // const progress = timestamp - startTime;

            // Move the scroll position
            if (scrollContainer) {
                scrollContainer.scrollLeft += 1; // Smooth incremental scroll

                // Reset if reached end 
                if (scrollContainer.scrollLeft >= scrollContainer.scrollWidth - scrollContainer.clientWidth) {
                    scrollContainer.scrollLeft = 0;
                }
            }

            animationFrameId = requestAnimationFrame(scroll);
        };

        // Start animation
        animationFrameId = requestAnimationFrame(scroll);

        // Pause on hover
        const handleMouseEnter = () => cancelAnimationFrame(animationFrameId);
        const handleMouseLeave = () => {
            startTime = 0; // Reset start time
            animationFrameId = requestAnimationFrame(scroll);
        };

        scrollContainer.addEventListener('mouseenter', handleMouseEnter);
        scrollContainer.addEventListener('mouseleave', handleMouseLeave);

        return () => {
            cancelAnimationFrame(animationFrameId);
            scrollContainer.removeEventListener('mouseenter', handleMouseEnter);
            scrollContainer.removeEventListener('mouseleave', handleMouseLeave);
        };
    }, []);

    if (!guides || guides.length === 0) return null;

    return (
        <div
            ref={scrollRef}
            className="flex overflow-x-auto gap-6 pb-8 -mx-4 px-4 md:mx-0 md:px-0 scrollbar-hide"
            style={{
                scrollbarWidth: 'none',
                msOverflowStyle: 'none'
            }}
        >
            {guides.map((guide, idx) => (
                <Link
                    href={guide.link || '#'}
                    key={idx}
                    className="group relative flex-shrink-0 w-[85vw] sm:w-[45vw] md:w-[300px] bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500"
                >
                    <div className="relative h-60 overflow-hidden">
                        <Image
                            src={guide.image}
                            alt={guide.title}
                            fill
                            className="object-cover group-hover:scale-105 transition-transform duration-700"
                        />
                        <div className="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold shadow-sm uppercase tracking-wider">
                            {guide.category}
                        </div>
                    </div>
                    <div className="p-8">
                        <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                            {guide.title}
                        </h3>
                        <div className="flex items-center justify-between text-sm text-gray-500 mt-4 pt-4 border-t border-gray-50">
                            <span className="flex items-center gap-2">
                                <FontAwesomeIcon icon={faBookOpen} className="text-gray-300" />
                                {guide.read_time}
                            </span>
                            <span className="group-hover:translate-x-1 transition-transform text-blue-600 font-semibold">
                                Read Guide <FontAwesomeIcon icon={faArrowRight} className="ml-1 text-xs" />
                            </span>
                        </div>
                    </div>
                </Link>
            ))}
            {/* Duplicate items for smoother infinite feel (simplified) */}
            {guides.slice(0, 4).map((guide, idx) => (
                <Link
                    href={guide.link || '#'}
                    key={`dup-${idx}`}
                    className="group relative flex-shrink-0 w-[85vw] sm:w-[45vw] md:w-[300px] bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500"
                >
                    <div className="relative h-60 overflow-hidden">
                        <Image
                            src={guide.image}
                            alt={guide.title}
                            fill
                            className="object-cover group-hover:scale-105 transition-transform duration-700"
                        />
                        <div className="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold shadow-sm uppercase tracking-wider">
                            {guide.category}
                        </div>
                    </div>
                    <div className="p-8">
                        <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                            {guide.title}
                        </h3>
                        <div className="flex items-center justify-between text-sm text-gray-500 mt-4 pt-4 border-t border-gray-50">
                            <span className="flex items-center gap-2">
                                <FontAwesomeIcon icon={faBookOpen} className="text-gray-300" />
                                {guide.read_time}
                            </span>
                            <span className="group-hover:translate-x-1 transition-transform text-blue-600 font-semibold">
                                Read Guide <FontAwesomeIcon icon={faArrowRight} className="ml-1 text-xs" />
                            </span>
                        </div>
                    </div>
                </Link>
            ))}
        </div>
    );
}
