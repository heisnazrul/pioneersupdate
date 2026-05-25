"use client";

/* eslint-disable @next/next/no-img-element */
import { useEffect, useMemo, useRef, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronRight, faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

const TOKENS = {
  border: "#E6EBF0",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};

const GAP = 20;
const AUTO_MS = 4000;

function BlogCard({ post, style, isArabic, t }) {
  const summaryText =
    post.summary && post.summary.length > 100
      ? `${post.summary.slice(0, 100)}...`
      : post.summary;
  const href = `/articles/${post.slug || post.id}`;

  return (
    <article
      className="shrink-0 rounded-[20px] bg-white p-4 shadow-sm text-start transition duration-300 hover:shadow-md"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
    >
      <Link href={href} className="block overflow-hidden rounded-[14px]">
        <img
          src={post.image}
          alt={post.title}
          className="h-64 w-full object-cover transition duration-500 hover:scale-105"
          loading="lazy"
        />
      </Link>

      <div className="px-2 pt-5">
        <Link href={href} className="block text-[20px] font-bold leading-snug text-slate-900 line-clamp-2 min-h-[60px] hover:text-[#1F63AE] transition">
          {post.title}
        </Link>
        <p className="mt-3 mb-4 text-[15px] leading-6 text-slate-500 line-clamp-3 min-h-[72px]">
          {summaryText}
        </p>
      </div>

      <div
        className="mt-2 flex items-center justify-between border-t px-3 pt-4"
        style={{ borderColor: TOKENS.border }}
      >
        <span className="text-[13px] font-semibold text-slate-500">
          {post.date || "29/06/2025"}
        </span>
        <Link
          href={href}
          className="grid h-10 w-10 place-items-center rounded-full border text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition"
          style={{ borderColor: TOKENS.border }}
          aria-label={t("pages.homepage.blogs.read_more", "Read more")}
          title={t("pages.homepage.blogs.read_more", "Read more")}
        >
          <Image
            src="/assets/icons/arrow-right.svg"
            alt="Read more"
            width={16}
            height={16}
            className={`h-4 w-4 ${isArabic ? "rotate-180" : ""}`}
          />
        </Link>
      </div>
    </article>
  );
}

export default function DesktopBlogs() {
  const viewportRef = useRef(null);
  const { data } = useApi("/courseenglish/home/blogs");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";

  const heading = t("pages.homepage.blogs.heading", "المدونات واخر الاخبار");
  const subheading = t("pages.homepage.blogs.subheading", "ابق علي اطلاع: موجز يومي للقضايا الحاسمة");

  const rawBlogs = data?.blogs;

  const dummyBlogs = [
    {
      id: 1,
      title: "دليل شامل لدراسة اللغة الإنجليزية في بريطانيا 10 خطوات أساسية",
      summary: "استمتع بتجربة فريدة لتعلّم اللغة الإنجليزية في واحدة من أكثر مدن بريطانيا حيوية على البحر!",
      date: "29/06/2025",
      category: "مدونة",
      image: "https://images.pexels.com/photos/256417/pexels-photo-256417.jpeg?auto=compress&cs=tinysrgb&w=800",
      slug: "study-in-uk-guide"
    },
    {
      id: 2,
      title: "دليل شامل لدراسة اللغة الإنجليزية في بريطانيا 10 خطوات أساسية",
      summary: "استمتع بتجربة فريدة لتعلّم اللغة الإنجليزية في واحدة من أكثر مدن بريطانيا حيوية على البحر!",
      date: "29/06/2025",
      category: "مدونة",
      image: "https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=800",
      slug: "study-in-uk-guide-2"
    },
    {
      id: 3,
      title: "دليل شامل لدراسة اللغة الإنجليزية في بريطانيا 10 خطوات أساسية",
      summary: "استمتع بتجربة فريدة لتعلّم اللغة الإنجليزية في واحدة من أكثر مدن بريطانيا حيوية على البحر!",
      date: "29/06/2025",
      category: "مدونة",
      image: "https://images.pexels.com/photos/159581/dictionary-reference-book-learning-meaning-159581.jpeg?auto=compress&cs=tinysrgb&w=800",
      slug: "study-in-uk-guide-3"
    },
    {
      id: 4,
      title: "دليل شامل لدراسة اللغة الإنجليزية في بريطانيا 10 خطوات أساسية",
      summary: "استمتع بتجربة فريدة لتعلّم اللغة الإنجليزية في واحدة من أكثر مدن بريطانيا حيوية على البحر!",
      date: "29/06/2025",
      category: "مدونة",
      image: "https://images.pexels.com/photos/267669/pexels-photo-267669.jpeg?auto=compress&cs=tinysrgb&w=800",
      slug: "study-in-uk-guide-4"
    },
    {
      id: 5,
      title: "دليل شامل لدراسة اللغة الإنجليزية في بريطانيا 10 خطوات أساسية",
      summary: "استمتع بتجربة فريدة لتعلّم اللغة الإنجليزية في واحدة من أكثر مدن بريطانيا حيوية على البحر!",
      date: "29/06/2025",
      category: "مدونة",
      image: "https://images.pexels.com/photos/356079/pexels-photo-356079.jpeg?auto=compress&cs=tinysrgb&w=800",
      slug: "study-in-uk-guide-5"
    }
  ];

  const posts = useMemo(() => {
    const list = rawBlogs && rawBlogs.length > 0 ? rawBlogs : dummyBlogs;
    if (!list.length) return [];
    return list.map((item, idx) => ({
      id: item.id ?? idx,
      title: isArabic ? item.ar_title || item.title || "مقال" : item.title || item.ar_title || "Blog post",
      summary: isArabic ? item.ar_summary || item.summary || "" : item.summary || item.ar_summary || "",
      category: isArabic ? item.category_ar_name || item.category || "مدونة" : item.category || item.category_ar_name || "Blog",
      date: item.date || "29/06/2025",
      image: item.image || "/assets/hero.png",
      slug: item.slug || item.id || idx,
    }));
  }, [rawBlogs, isArabic]);

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);

  const len = posts.length;

  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;
      if (w >= 1536) {
        setVisible(4);
      } else if (w >= 1280) {
        setVisible(3);
      } else {
        setVisible(2);
      }
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  useEffect(() => {
    const el = viewportRef.current;
    if (!el) return;

    const calc = () => {
      const w = el.clientWidth;
      const totalGap = GAP * (visible - 1);
      const width = Math.max(240, Math.floor((w - totalGap) / visible));
      setCardW(width);
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible]);

  const maxIndex = Math.max(0, len - visible);

  // Autoplay
  useEffect(() => {
    if (len <= visible) return;
    const id = setInterval(() => {
      setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
    }, AUTO_MS);
    return () => clearInterval(id);
  }, [len, visible, maxIndex]);

  const prev = () => {
    if (len <= visible) return;
    setIndex((i) => (i - 1 < 0 ? maxIndex : i - 1));
  };

  const next = () => {
    if (len <= visible) return;
    setIndex((i) => (i + 1 > maxIndex ? 0 : i + 1));
  };

  const dir = isArabic ? 1 : -1;
  const trackStyle = {
    gap: `${GAP}px`,
    width: cardW ? `${len * cardW + (len - 1) * GAP}px` : "auto",
    transform: `translateX(${dir * Math.min(index, maxIndex) * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = { width: `${cardW}px`, flex: `0 0 ${cardW}px` };

  if (len === 0) return null;

  return (
    <section className="hidden md:block py-16 sm:py-20 bg-white w-full" dir={direction}>
      <div className="px-6 md:px-10 xl:px-20 2xl:px-40 mx-auto">

        {/* heading */}
        <div className="text-center">
          <h2 className="py-4 text-3xl font-bold text-slate-900 sm:text-4xl">
            {heading}
          </h2>
          <p className="mt-2 text-slate-500 text-[16px] max-w-2xl mx-auto">
            {subheading}
          </p>
        </div>

        {/* carousel */}
        <div className="relative mt-10 px-4 md:px-12">
          {len > visible && (
            <>
              {/* Prev Button */}
              <button
                type="button"
                onClick={prev}
                aria-label="Previous articles"
                className="absolute -left-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full border border-slate-200 bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 transition"
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>

              {/* Next Button */}
              <button
                type="button"
                onClick={next}
                aria-label="Next articles"
                className="absolute -right-4 top-1/2 z-10 -translate-y-1/2 grid place-items-center rounded-full p-4 text-white shadow-md hover:brightness-110 transition"
                style={{ background: TOKENS.primary, boxShadow: TOKENS.primaryShadow }}
              >
                <Image
                  src="/assets/icons/arrow-right-white.svg"
                  alt="Next"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>
            </>
          )}

          <div ref={viewportRef} className="overflow-hidden m-4">
            <div className="flex" style={trackStyle}>
              {posts.map((p) => (
                <BlogCard key={p.id} post={p} style={cardStyle} isArabic={isArabic} t={t} />
              ))}
            </div>
          </div>
        </div>

      </div>
      <div className="mt-12 flex justify-center">
        <Link
          href="/articles"
          className="rounded-[8px] bg-[#1F63AE] px-8 py-3.5 text-sm font-bold !text-white shadow-[0_10px_25px_rgba(31,99,174,0.4)] hover:brightness-110 transition-all"
        >
          {isArabic ? "عرض كل المقالات" : "View all articles"}
        </Link>
      </div>
    </section>
  );
}
