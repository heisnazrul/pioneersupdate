"use client";

/* eslint-disable @next/next/no-img-element */
import { useMemo } from "react";
import Image from "next/image";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

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
        <span className="text-[12px] font-semibold text-slate-500">
          {post.date || "29/06/2025"}
        </span>
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

  if (posts.length === 0) return null;

  return (
    <section className="block md:hidden bg-white py-10 w-full" dir={direction}>
      
      {/* Title block */}
      <div className="text-center px-4">
        <h2 className="text-2xl font-bold leading-snug text-slate-900">
          {heading}
        </h2>
        <p className="mt-2 text-slate-500 text-[14px]">
          {subheading}
        </p>
      </div>

      {/* Swipe Row */}
      <div className="mt-6">
        <div className="flex gap-4 overflow-x-auto px-4 snap-x snap-mandatory scrollbar-hide py-2">
          {posts.map((p) => (
            <BlogCard
              key={p.id}
              post={p}
              isArabic={isArabic}
              t={t}
            />
          ))}
          {/* Peek padding */}
          <div className="shrink-0 w-4 snap-none"></div>
        </div>
      </div>


      <div className="mt-8 flex justify-center px-4">
        <Link
          href="/articles"
          className="rounded-[8px] bg-[#1F63AE] px-6 py-3 text-sm font-bold !text-white shadow-[0_10px_25px_rgba(31,99,174,0.4)] hover:brightness-110 transition-all w-full text-center max-w-[300px]"
        >
          {isArabic ? "عرض كل المقالات" : "View all articles"}
        </Link>
      </div>
    </section>
  );
}
