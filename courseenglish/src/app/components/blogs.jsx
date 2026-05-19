"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { pickLang } from "@/lib/i18nFallback";

const CACHE_KEY = "cache_blogs";
const TOKENS = {
  border: "#E6EBF0",
  primary: "#1F63AE",
  primaryShadow: "0 4px 12px rgba(31,99,174,.35)",
};
const GAP = 20;
const AUTO_MS = 4000;

function pickImage(item) {
  if (typeof item?.localImage === "string" && item.localImage.trim()) {
    return item.localImage;
  }
  const raw =
    typeof item?.featured_image === "string"
      ? item.featured_image
      : typeof item?.image === "string"
        ? item.image
        : "";
  if (!raw) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://") || raw.startsWith("/")) {
    return raw;
  }
  const fname = raw.split("/").pop() || raw;
  return `/cache/blogs/${fname}`;
}

function normalize(payload, language) {
  const isArabic = language === "ar";
  return payload
    .map((item, idx) => {
      const image = pickImage(item);
      if (!image) return null;
      return {
        id: item.id ?? idx,
        title: isArabic
          ? item.ar_title || item.title || pickLang("ar", "Blog post", "مقال")
          : item.title || item.ar_title || pickLang("en", "Blog post", "مقال"),
        summary:
          (isArabic
            ? item.ar_summary || item.summary
            : item.summary || item.ar_summary) ||
          item.description ||
          item.text ||
          "",
        category: isArabic
          ? item.category_ar_name || item.category || pickLang("ar", "Blog", "مدونة")
          : item.category || item.category_ar_name || pickLang("en", "Blog", "مدونة"),
        image,
        slug: item.slug || item.id || idx,
      };
    })
    .filter(Boolean);
}

function BlogCard({ post, style, isArabic }) {
  const summaryText =
    post.summary && post.summary.length > 100
      ? `${post.summary.slice(0, 100)}...`
      : post.summary;
  const href = `/articles/${post.slug || post.id}`;
  return (
    <article
      className="shrink-0 rounded-[20px] bg-white p-4 shadow-sm"
      style={{ ...style, border: `1px solid ${TOKENS.border}` }}
      data-card
    >
      <Link href={href} className="block overflow-hidden rounded-[14px]">
        <img
          src={post.image}
          alt={post.title}
          className="h-64 w-full object-cover"
          loading="lazy"
        />
      </Link>

      <div className="px-2 pt-5">
        <Link href={href} className="block text-left text-[20px] font-extrabold leading-snug text-slate-900">
          {post.title}
        </Link>
        <p className="mt-3 mb-4 text-left text-[15px] leading-6 text-slate-500">
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
          className="grid h-10 w-10 place-items-center rounded-full border text-slate-700 hover:bg-slate-50"
          style={{ borderColor: TOKENS.border }}
          aria-label="Read more"
          title="Read more"
        >
          <Image
            src="/assets/icons/arrow-right.svg"
            alt="Read more"
            width={16}
            height={16}
            className="h-4 w-4"
          />
        </Link>
        <span className="text-[13px] font-normal text-[#1F63AE]">
          {post.category || (isArabic ? "مدونة" : "Blog")}
        </span>
      </div>
    </article>
  );
}

export default function BlogNewsEN() {
  const viewportRef = useRef(null);
  const { data, loading } = useApi("/courseenglish/home/blogs");
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const blogsMeta = getCourseEnglishMessages(language)?.pages?.homepage?.blogs ?? {};
  const heading = blogsMeta?.heading || (isArabic ? "المدونات وآخر الأخبار" : "Blogs & Latest News");
  const ctaText = blogsMeta?.view_all || (isArabic ? "كل المقالات" : "All articles");
  const ctaUrl = "/articles";
  const posts = useMemo(() => normalize(data?.blogs ?? [], language), [data, language]);
  const loaded = !loading;

  const [cardW, setCardW] = useState(0);
  const [index, setIndex] = useState(0);
  const [visible, setVisible] = useState(3);
  const [peek, setPeek] = useState(0);

  const len = posts.length;

  // breakpoint logic: 2xl=4, xl=3, md=2, mobile=1 + peek
  useEffect(() => {
    const handleResize = () => {
      if (typeof window === "undefined") return;
      const w = window.innerWidth;

      if (w >= 1536) {
        setVisible(4);
        setPeek(0);
      } else if (w >= 1280) {
        setVisible(3);
        setPeek(0);
      } else if (w >= 768) {
        setVisible(2);
        setPeek(0);
      } else {
        setVisible(1);
        setPeek(0.15);
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
        width = Math.floor(w * (1 - peek));
      } else {
        const totalGap = GAP * (visible - 1);
        width = Math.max(240, Math.floor((w - totalGap) / visible));
      }

      setCardW(width);
    };

    calc();
    const ro = new ResizeObserver(calc);
    ro.observe(el);
    return () => ro.disconnect();
  }, [visible, peek]);

  useEffect(() => {
    setIndex(0);
  }, [visible, len]);

  useEffect(() => {
    if (len <= visible) return;
    const id = setInterval(() => {
      setIndex((i) => (i + 1 > Math.max(0, len - visible) ? 0 : i + 1));
    }, AUTO_MS);
    return () => clearInterval(id);
  }, [len, visible]);

  const dir = isArabic ? 1 : -1;
  const trackStyle = {
    gap: `${GAP}px`,
    width: cardW ? `${len * cardW + (len - 1) * GAP}px` : "auto",
    transform: `translateX(${dir * index * (cardW + GAP)}px)`,
    transition: "transform 500ms ease",
  };

  const cardStyle = { width: `${cardW}px`, flex: `0 0 ${cardW}px` };

  if (loaded && len === 0) return null;

  return (
    <section className="py-16 sm:py-20">
      <div className="px-4 md:px-10 xl:px-20 2xl:px-40">
        {/* heading */}
        <div className="hidden md:flex items-center justify-between">
          <h2 className="py-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
            {heading}
          </h2>
          <Link
            href={ctaUrl}
            className="flex items-center gap-2 text-md font-normal text-[#1F63AE]"
          >
            <span>{ctaText}</span>
            <FontAwesomeIcon icon={faChevronRight} />
          </Link>
        </div>
        <div className="md:hidden my-4 flex items-center justify-between md:hidden">
          <div className="flex px-4 text-center">
            <h2 className="text-2xl font-extrabold leading-snug text-slate-900">
              {heading}
            </h2>
          </div>
          <div className="flex items-center text-md font-normal text-[#1F63AE]">
            <Link href={ctaUrl} className="pb-1">
              {ctaText}
            </Link>
            <div className="text-lg">
              <FontAwesomeIcon icon={faChevronRight} />
            </div>
          </div>
        </div>

        {/* carousel */}
        <div className="relative mt-10 mx-2 md:mx-6 lg:mx-20">
          {len > visible && (
            <>
              <button
                type="button"
                onClick={() =>
                  setIndex((i) => (i - 1 < 0 ? Math.max(0, len - visible) : i - 1))
                }
                aria-label="Previous"
                className="absolute -left-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full border bg-white p-4 text-slate-800 shadow-md hover:bg-slate-50 md:grid"
                style={{ borderColor: TOKENS.border }}
              >
                <Image
                  src="/assets/icons/arrow-left.svg"
                  alt="Previous"
                  width={16}
                  height={16}
                  className="h-4 w-4"
                />
              </button>

              <button
                type="button"
                onClick={() =>
                  setIndex((i) => (i + 1 > Math.max(0, len - visible) ? 0 : i + 1))
                }
                aria-label="Next"
                className="absolute -right-20 top-1/2 z-10 hidden -translate-y-1/2 place-items-center rounded-full p-4 text-white md:grid"
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

          <div ref={viewportRef} className="overflow-hidden mt-4">
            <div className="flex" style={trackStyle}>
              {posts.map((p) => (
                <BlogCard key={p.id} post={p} style={cardStyle} isArabic={isArabic} />
              ))}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
