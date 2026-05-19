"use client";

import { useEffect, useRef, useState } from "react";
import Link from "next/link";
import NextImage from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronRight, faChevronLeft, faArrowRight } from "@fortawesome/free-solid-svg-icons";

const TOKENS = {
    border: "#E6EBF0",
    primary: "#135FAE", // Updated to match new brand blue
    primaryShadow: "0 4px 12px rgba(19,95,174,.35)",
};
const GAP = 20;
const AUTO_MS = 4000;

// Dummy Data


function BlogCard({ post, style }: { post: any, style: any }) {
    const rawSummary = post.summary || post.excerpt || "";
    const summaryText =
        rawSummary && rawSummary.length > 100
            ? `${rawSummary.slice(0, 100)}...`
            : rawSummary;
    const href = `/blogs/${post.slug || post.id}`;

    return (
        <article
            className="shrink-0 rounded-[20px] bg-white p-4 shadow-sm"
            style={{ ...style, border: `1px solid ${TOKENS.border}` }}
            data-card
        >
            <Link href={href} className="block overflow-hidden rounded-[14px]">
                {post.image ? (
                    <div className="relative h-48 w-full">
                        <NextImage
                            src={post.image}
                            alt={post.title}
                            fill
                            className="object-cover transition-transform duration-500 hover:scale-110"
                            unoptimized
                        />
                    </div>
                ) : (
                    <div className="h-48 w-full bg-slate-100 flex items-center justify-center text-slate-400 relative overflow-hidden">
                        <div className="absolute inset-0 bg-gradient-to-tr from-blue-50 to-slate-100" />
                        <span className="relative z-10 font-bold opacity-30 text-4xl">BLOG</span>
                    </div>
                )}
            </Link>

            <div className="px-2 pt-5">
                <Link href={href} className="block  text-[18px] font-extrabold leading-snug text-slate-900 min-h-[50px] overflow-hidden" style={{ display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical' }}>
                    {post.title}
                </Link>
                <p className="mt-3 mb-4  text-[15px] leading-6 text-slate-500">
                    <span
                        style={{
                            display: "-webkit-box",
                            WebkitLineClamp: 3,
                            WebkitBoxOrient: "vertical",
                            overflow: "hidden",
                        }}
                    >
                        {summaryText}
                    </span>
                </p>
            </div>

            <div
                className="mt-2 flex items-center justify-between border-t px-3 pt-4 "
                style={{ borderColor: TOKENS.border }}
            >
                <Link
                    href={href}
                    className="grid h-10 w-10 place-items-center rounded-full border text-slate-700 hover:bg-slate-50 transition-colors"
                    style={{ borderColor: TOKENS.border }}
                    aria-label="Read more"
                    title="Read more"
                >
                    <FontAwesomeIcon icon={faArrowRight} className="h-4 w-4" />
                </Link>
                <span className="text-[13px] font-semibold text-[#135FAE]">
                    {post.category || "Blog"}
                </span>
            </div>
        </article>
    );
}

interface BlogNewsSectionProps {
    blogs?: any[];
    copy?: any;
    lang?: 'en' | 'ar';
}

export default function BlogNewsSection({ blogs: initialBlogs = [], copy, lang: langProp = 'en' }: BlogNewsSectionProps) {
    const viewportRef = useRef<HTMLDivElement>(null);
    const [posts, setPosts] = useState<any[]>(initialBlogs);
    const [lang, setLang] = useState(langProp);

    // UI State
    const [cardW, setCardW] = useState(0);
    const [index, setIndex] = useState(0);
    const [visible, setVisible] = useState(3);
    const [peek, setPeek] = useState(0);

    const len = posts.length;

    useEffect(() => {
        // Just sync posts if the server passes new ones, though they are likely static from page hydration.
        if (initialBlogs.length > 0) setPosts(initialBlogs);
        
        const handleStorage = () => setLang((localStorage.getItem('uni_language') || 'en').toLowerCase() === 'ar' ? 'ar' : 'en');
        window.addEventListener('storage', handleStorage);
        return () => window.removeEventListener('storage', handleStorage);
    }, [initialBlogs]);

    // breakpoint logic
    useEffect(() => {
        const handleResize = () => {
            if (typeof window === "undefined") return;
            const w = window.innerWidth;

            if (w >= 1280) {
                setVisible(4);
                setPeek(0);
            } else if (w >= 768) {
                setVisible(2);
                setPeek(0);
            } else {
                setVisible(1);
                setPeek(0.15); // Show a bit of the next card on mobile
            }
        };

        handleResize();
        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);
    }, []);

    // Measure viewport and compute card width
    useEffect(() => {
        const el = viewportRef.current;
        if (!el) return;

        const calc = () => {
            const w = el.clientWidth;
            let width;

            if (visible === 1 && peek > 0) {
                // Mobile with peek
                width = Math.floor(w * (1 - peek));
            } else {
                const totalGap = GAP * (visible - 1);
                width = Math.max(240, Math.floor((w - totalGap) / visible));
            }

            setCardW(width);
        };

        calc();
        // Use ResizeObserver for more robust width tracking
        const ro = new ResizeObserver(calc);
        ro.observe(el);
        return () => ro.disconnect();
    }, [visible, peek]);

    useEffect(() => {
        setIndex(0);
    }, [visible, len]);

    // Autoplay
    useEffect(() => {
        if (len <= visible) return;
        const id = setInterval(() => {
            setIndex((i) => (i + 1 > Math.max(0, len - visible) ? 0 : i + 1));
        }, AUTO_MS);
        return () => clearInterval(id);
    }, [len, visible]);

    const trackStyle = {
        gap: `${GAP}px`,
        width: cardW ? `${len * cardW + (len - 1) * GAP}px` : "auto",
        transform: `translateX(${lang === 'ar' ? '' : '-'}${index * (cardW + GAP)}px)`,
        transition: "transform 500ms ease",
    };

    const cardStyle = { width: `${cardW}px`, flex: `0 0 ${cardW}px` };

    return (
        <section className="py-16 sm:py-24 bg-white">
            <div className="px-4 md:px-10 xl:px-20 2xl:px-30">
                {/* Heading */}
                <div className="hidden md:flex items-center justify-between mb-8">
                    <h2 className="py-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                        {copy?.heading || (lang === 'ar' ? 'المدونة وآخر الأخبار' : 'Blogs & Latest News')}
                    </h2>
                    <Link
                        href="/blogs"
                        className="px-6 py-3 rounded-lg font-bold transition-all text-base bg-[#135FAE] hover:bg-[#115293] text-white shadow-md flex items-center gap-2"
                    >
                        <span>{copy?.cta_text || (lang === 'ar' ? 'كل المقالات' : 'All articles')}</span>
                        <FontAwesomeIcon icon={faArrowRight} className="w-4 h-4" />
                    </Link>
                </div>

                <div className="my-4 flex items-center justify-between md:hidden">
                    <div className="flex">
                        <h2 className="text-2xl font-extrabold leading-snug text-slate-900">
                            {copy?.heading_mobile || copy?.heading || (lang === 'ar' ? 'المدونة والأخبار' : 'Blogs & News')}
                        </h2>
                    </div>
                    <div className="flex items-center text-md font-semibold text-[#135FAE]">
                        <Link href="/blogs" className="pb-1 flex items-center gap-1">
                            {(copy?.cta_mobile || (lang === 'ar' ? 'الكل' : 'All'))} <FontAwesomeIcon icon={faChevronRight} className="text-xs" />
                        </Link>
                    </div>
                </div>

                {/* Carousel */}
                <div className="relative mt-6 md:mt-10 mx-0 md:mx-6">
                    {len > visible && (
                        <>
                            {/* Prev Button */}
                            <button
                                type="button"
                                onClick={() =>
                                    setIndex((i) => (i - 1 < 0 ? Math.max(0, len - visible) : i - 1))
                                }
                                aria-label="Previous"
                                className="absolute -left-16 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full border bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 md:grid transition-all hover:scale-105"
                                style={{ borderColor: TOKENS.border, width: '48px', height: '48px' }}
                            >
                                <FontAwesomeIcon icon={faChevronLeft} />
                            </button>

                            {/* Next Button */}
                            <button
                                type="button"
                                onClick={() =>
                                    setIndex((i) => (i + 1 > Math.max(0, len - visible) ? 0 : i + 1))
                                }
                                aria-label="Next"
                                className="absolute -right-16 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full text-white md:grid transition-all hover:scale-105"
                                style={{
                                    background: TOKENS.primary,
                                    boxShadow: TOKENS.primaryShadow,
                                    width: '48px',
                                    height: '48px'
                                }}
                            >
                                <FontAwesomeIcon icon={faChevronRight} />
                            </button>
                        </>
                    )}

                    <div ref={viewportRef} className="overflow-hidden" dir="ltr">
                        <div className="flex" style={trackStyle}>
                            {posts.map((p) => (
                                <div key={p.id} dir={lang === 'ar' ? 'rtl' : 'ltr'} style={{ display: 'contents' }}>
                                    <BlogCard post={p} style={cardStyle} />
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
