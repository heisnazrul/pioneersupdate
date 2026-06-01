"use client";

/* eslint-disable @next/next/no-img-element */
import { useMemo } from "react";
import Image from "next/image";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi, getImageUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import MobileInfiniteCarousel from "@/components/mobile/mobile-infinite-carousel";

const TOKENS = {
  border: "#E6EBF0",
};

function BlogCard({ post, isArabic, t }) {
  const summaryText =
    post.summary && post.summary.length > 80
      ? `${post.summary.slice(0, 80)}...`
      : post.summary;
  const href = `/articles/${post.slug || post.id}`;

  return (
    <article
      className="shrink-0 w-[82vw] snap-start rounded-[20px] bg-white p-3 shadow-sm text-start"
      style={{ border: `1px solid ${TOKENS.border}` }}
    >
      <Link href={href} className="block overflow-hidden rounded-[14px]">
        <img
          src={post.image}
          alt={post.title}
          className="h-44 w-full object-cover"
          loading="lazy"
        />
      </Link>

      <div className="px-1 pt-4">
        <Link href={href} className="block text-[16px] font-bold leading-snug text-slate-900 line-clamp-2 min-h-[48px]">
          {post.title}
        </Link>
        <p className="mt-2 mb-3 text-[13px] leading-relaxed text-slate-500 line-clamp-2 min-h-[40px]">
          {summaryText}
        </p>
      </div>

      <div
        className="mt-2 flex items-center justify-between border-t px-3 pt-3 pb-1"
        style={{ borderColor: TOKENS.border }}
      >
        <span className="text-[12px] font-semibold text-slate-500">{post.date}</span>
        <Link
          href={href}
          className="grid h-8 w-8 place-items-center rounded-full border text-slate-700 active:bg-slate-50"
          style={{ borderColor: TOKENS.border }}
          aria-label={t("pages.homepage.blogs.read_more", "Read more")}
          title={t("pages.homepage.blogs.read_more", "Read more")}
        >
          <Image
            src="/assets/icons/arrow-right.svg"
            alt="Read more"
            width={14}
            height={14}
            className={`h-3.5 w-3.5 ${isArabic ? "rotate-180" : ""}`}
          />
        </Link>
      </div>
    </article>
  );
}

export default function MobileBlogs() {
  const { data } = useApi("/coursesat/home/blogs");
  const { language, direction, t } = useLocale();
  const isArabic = language === "ar";

  const heading = t(
    "pages.homepage.blogs.mobile_heading",
    t("pages.homepage.blogs.heading", "Blogs & Latest News")
  );
  const viewAllLabel = t(
    "pages.homepage.blogs.mobile_view_all",
    t("pages.homepage.blogs.view_all", "All articles")
  );
  const viewAllUrl = "/articles";

  const posts = useMemo(() => {
    const list = data?.blogs ?? [];
    if (!list.length) return [];

    return list.map((item, idx) => ({
      id: item.id ?? idx,
      title: isArabic ? item.ar_title || item.title || "مقال" : item.title || item.ar_title || "Blog post",
      summary: isArabic ? item.ar_summary || item.summary || "" : item.summary || item.ar_summary || "",
      category: isArabic ? item.category_ar_name || item.category || "مدونة" : item.category || item.category_ar_name || "Blog",
      date: item.date || "",
      image: getImageUrl(item.image) || "/assets/hero.png",
      slug: item.slug || item.id || idx,
    }));
  }, [data?.blogs, isArabic]);

  if (posts.length === 0) return null;

  return (
    <section className="block md:hidden bg-white py-10 w-full" dir={direction}>
      <div className="flex items-center justify-between gap-3 px-4">
        <h2 className="text-xl font-bold leading-snug text-slate-900 max-w-[55%] text-start">{heading}</h2>
        <Link
          href={viewAllUrl}
          className="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-[#1F63AE] transition-colors hover:text-[#135FAE]"
        >
          <span>{viewAllLabel}</span>
          <FontAwesomeIcon icon={isArabic ? faChevronLeft : faChevronRight} className="text-xs" />
        </Link>
      </div>

      <div className="mt-6">
        <MobileInfiniteCarousel
          items={posts}
          getItemKey={(post) => post.id}
          renderItem={(post, _idx, key) => (
            <BlogCard key={key} post={post} isArabic={isArabic} t={t} />
          )}
        />
      </div>
    </section>
  );
}
