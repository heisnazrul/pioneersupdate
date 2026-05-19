"use client";

import Link from 'next/link';
import { useRef, useState, useEffect } from 'react';
import Image from 'next/image';
import { motion } from 'framer-motion';
import { faPlay, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import SectionHeading from '@/components/ui/SectionHeading';
import { VideoReviewApiData } from '@/lib/api';

interface VideoReviewsSectionProps {
    reviews?: VideoReviewApiData[];
    copy?: any;
    lang?: 'en' | 'ar';
}

export default function VideoReviewsSection({ reviews = [], copy, lang = 'en' }: VideoReviewsSectionProps) {
    const isAr = lang === 'ar';
    const scrollRef = useRef<HTMLDivElement>(null);
    const [isHovered, setIsHovered] = useState<number | null>(null);

    // Removed internal fetch logic since we accept props now

    // Triple the data to create seamless infinite scroll illusion
    const displayedReviews = reviews.length > 0 ? [...reviews, ...reviews, ...reviews] : [];

    // Auto-scroll logic
    useEffect(() => {
        const scrollContainer = scrollRef.current;
        if (!scrollContainer || reviews.length === 0) return;

        let animationFrameId: number;
        let scrollSpeed = 0.5;

        const scroll = () => {
            if (isHovered === null && scrollContainer) {
                scrollContainer.scrollLeft += scrollSpeed;

                // Reset position when scrolled past the first set (1/3 of total width)
                if (scrollContainer.scrollLeft >= scrollContainer.scrollWidth / 3) {
                    scrollContainer.scrollLeft = 0;
                }
            }
            animationFrameId = requestAnimationFrame(scroll);
        };

        animationFrameId = requestAnimationFrame(scroll);
        return () => cancelAnimationFrame(animationFrameId);
    }, [isHovered, reviews]);

    if (reviews.length === 0) return null;

    return (
        <section className="py-20 bg-white overflow-hidden">
            <div className="px-4 md:px-10 xl:px-20 2xl:px-40">
                <div className="flex justify-between items-center mb-12 gap-6">
                    <SectionHeading
                        subtitle={copy?.video_subtitle || (isAr ? 'قصص الطلاب' : 'Student Stories')}
                        title={copy?.video_title || (isAr ? 'تجارب الطلاب المصوّرة' : 'Hear from our students')}
                        description={copy?.video_description || (isAr ? 'قصص حقيقية من طلاب أتمّوا رحلتهم التعليمية حول العالم بنجاح.' : 'Real stories from students who have successfully started their global education journey.')}
                        align="left"
                        className="mb-0"
                    />
                    <div className="hidden md:block">
                        <Link href="/reviews">
                            <button className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md flex items-center gap-2">
                                <span>{copy?.view_all || (isAr ? 'عرض الكل' : 'View All')}</span>
                                <FontAwesomeIcon icon={faArrowRight} className="w-4 h-4" />
                            </button>
                        </Link>
                    </div>
                </div>

                {/* Carousel */}
                <div
                    ref={scrollRef}
                    className="flex gap-6 overflow-x-auto pb-8 scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0"
                    style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
                >
                    {displayedReviews.map((video, index) => (
                        <motion.div
                            key={`${video.id}-${index}`}
                            className={`min-w-[280px] md:min-w-[320px] relative group ${video.video_url ? 'cursor-pointer' : 'cursor-default'}`}
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            viewport={{ once: true }}
                            transition={{ delay: 0.1 }}
                            onHoverStart={() => setIsHovered(video.id)}
                            onHoverEnd={() => setIsHovered(null)}
                            onClick={() => video.video_url && window.open(video.video_url, '_blank')}
                        >
                            <div className="relative aspect-[3/4] rounded-3xl overflow-hidden bg-gray-900 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                {/* Thumbnail */}
                                <Image
                                    src={video.thumbnail || '/logo.png'}
                                    alt={video.name}
                                    fill
                                    className="object-cover opacity-80 group-hover:opacity-60 transition-opacity duration-300 group-hover:scale-105"
                                    unoptimized
                                />

                                {/* Overlay Gradient */}
                                <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent" />

                                {/* Play Button */}
                                <div className="absolute inset-0 flex items-center justify-center">
                                    <div className="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/30">
                                        <div className="w-12 h-12 rounded-full bg-white text-[#135FAE] flex items-center justify-center pl-1 shadow-lg">
                                            <FontAwesomeIcon icon={faPlay} className="w-4 h-4" />
                                        </div>
                                    </div>
                                </div>

                                {/* Content */}
                                <div className="absolute bottom-0 left-0 right-0 p-6 text-white z-10">
                                    <div className="flex items-center gap-3 mb-3">
                                        {video.country_name && (
                                            <div className="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium border border-white/10 flex items-center gap-1.5">
                                                <div className="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse" />
                                                {video.country_name}
                                            </div>
                                        )}
                                        <div className="bg-black/40 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium border border-white/10">
                                            {video.duration || '2:00'}
                                        </div>
                                    </div>

                                    <div>
                                        <h3 className="font-bold text-lg leading-tight mb-1">{video.name}</h3>
                                        <p className="text-sm text-gray-200 line-clamp-1">
                                            {video.course_name || 'Student'}
                                            {video.university_name ? ` @ ${video.university_name}` : ''}
                                        </p>
                                    </div>
                                </div>

                                {/* Quote */}
                                <div className={`overflow-hidden transition-all duration-300 ${isHovered === video.id ? 'max-h-20 opacity-100 mt-3' : 'max-h-0 opacity-0'}`}>
                                    <p className="text-xs text-gray-200 italic border-l-2 border-primary pl-3">
                                        "{video.review_text}"
                                    </p>
                                </div>
                            </div>
                        </motion.div>
                    ))}
                </div>

                <div className="mt-8 text-center md:hidden">
                    <Link href="/reviews">
                        <button className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-white border border-gray-200 hover:bg-gray-50 text-gray-900 shadow-sm">
                            {copy?.view_all || (isAr ? 'عرض الكل' : 'View All')}
                        </button>
                    </Link>
                </div>
            </div>
        </section >
    );
}
