"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faArrowLeft, faArrowRight } from "@fortawesome/free-solid-svg-icons";
// import { useApi, buildApiUrl } from "@/lib/api";
import { useApi, buildApiUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";

function pickImage(item) {
  const apiBase = buildApiUrl("");
  const apiOrigin = (() => {
    try {
      return new URL(apiBase).origin;
    } catch {
      return "";
    }
  })();

  const makeAbsolute = (path) => {
    if (!path) return null;
    if (path.startsWith("http")) return path;
    if (path.startsWith("//")) return `https:${path}`;
    if (path.startsWith("/")) return `${apiOrigin}${path}`;
    return `${apiOrigin}/${path}`;
  };

  const candidates = [
    item?.image,
    item?.imageUrl,
    item?.image_url,
    item?.featured_image,
    item?.localImage,
  ].filter(Boolean);

  for (const src of candidates) {
    const abs = makeAbsolute(src);
    if (abs) return abs;
  }

  return "/assets/blog/1.png";
}

function slugify(str) {
  return (str || "").toString().toLowerCase().trim().replace(/\s+/g, "-");
}

function summarize(text, limit = 160) {
  if (!text) return "";
  const clean = text.replace(/<[^>]+>/g, "");
  if (clean.length <= limit) return clean;
  return `${clean.slice(0, limit)}…`;
}

function sortByDate(items) {
  return [...items].sort((a, b) => {
    const da = a.published_at ? new Date(a.published_at).getTime() : 0;
    const db = b.published_at ? new Date(b.published_at).getTime() : 0;
    return db - da || (b.id || 0) - (a.id || 0);
  });
}

export default function DesktopArticles() {
  const { data, loading } = useApi("/courseenglish/articles?per_page=50");
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  const loc = (key) => t(`pages.articles.${key}`);

  const [articles, setArticles] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeCat, setActiveCat] = useState("all");

  useEffect(() => {
    if (!data) return;
    setArticles(Array.isArray(data.data) ? data.data : []);
    setCategories(Array.isArray(data.categories) ? data.categories : []);
  }, [data]);

  const loaded = !loading;

  const filtered = useMemo(() => {
    if (activeCat === "all") return articles;
    return articles.filter((item) => {
      const catSlug =
        item?.category?.slug ||
        item?.category_slug ||
        slugify(item?.category?.name || item?.category || "");
      return catSlug === activeCat;
    });
  }, [articles, activeCat]);

  if (!loaded) {
    return (
      <main className="flex min-h-[40vh] items-center justify-center bg-[#F7F9FB] px-4 py-16">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#1277BE] border-t-transparent" />
      </main>
    );
  }

  if (loaded && articles.length === 0) {
    return (
      <main className="px-2 py-16 text-slate-900 md:px-10 lg:px-20" dir={isArabic ? "rtl" : "ltr"}>
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
          <h1 className="text-3xl font-extrabold">{loc("empty_title")}</h1>
          <p className="mt-4 text-slate-600">{loc("empty_message")}</p>
        </div>
      </main>
    );
  }

  const recentPosts = articles.slice(0, 2);
  const allPosts = articles.slice(2);

  return (
    <main className="bg-[#F7F9FB] px-4 py-10 pb-20 text-slate-900 md:px-10 lg:px-20 2xl:px-40" dir={isArabic ? "rtl" : "ltr"}>
      {/* Header */}
      <section className="my-10 text-center">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm text-slate-500 shadow-sm">
          <span>{loc("breadcrumb_home")}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <span>{loc("breadcrumb_current")}</span>
        </div>
        <h1 className="text-4xl mb-10 font-bold text-[#102233] md:text-5xl">
          {loc("title")}
        </h1>
      </section>

      {/* Recent Articles */}
      {recentPosts.length > 0 && (
        <section className="my-16">
          <h2 className="my-6 text-2xl font-bold text-[#102233] md:text-3xl">{loc("recent_posts")}</h2>
          <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {recentPosts.map((blog) => (
              <Link
                key={blog.id || blog.slug}
                href={`/articles/${blog.slug}`}
                className="group flex flex-col items-center gap-4 rounded-3xl border border-[#D9E4EF] bg-white p-4 shadow-sm transition hover:shadow-md md:flex-row"
              >
                <div className="h-48 w-full shrink-0 overflow-hidden rounded-2xl md:h-38 md:w-4/12">
                  <img
                    src={pickImage(blog)}
                    alt={blog.title}
                    className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                  />
                </div>
                <div className="flex flex-1 flex-col justify-center px-2 text-start md:px-4">
                  <h3 className="mb-3 text-lg font-bold leading-snug text-[#102233] group-hover:text-[#1277BE] md:text-xl">
                    {isArabic ? blog.ar_title || blog.title : blog.title}
                  </h3>
                  <p className="text-sm leading-relaxed text-slate-500 line-clamp-3 md:text-base">
                    {summarize(isArabic ? blog.ar_summary || blog.summary : blog.summary, 120)}
                  </p>
                </div>
              </Link>
            ))}
          </div>
        </section>
      )}

      {/* All Articles */}
      <section>
        <h2 className="mb-6 text-2xl font-bold text-[#102233] md:text-3xl">{loc("all_articles")}</h2>
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {allPosts.map((blog) => (
            <Link
              key={blog.id || blog.slug}
              href={`/articles/${blog.slug}`}
              className="group flex flex-col overflow-hidden rounded-3xl border border-[#D9E4EF] bg-white shadow-sm transition hover:shadow-md"
            >
              <div className="h-56 w-full overflow-hidden p-3">
                <img
                  src={pickImage(blog)}
                  alt={blog.title}
                  className="h-full w-full rounded-2xl object-cover transition duration-500 group-hover:scale-105"
                />
              </div>
              <div className="flex flex-1 flex-col px-6 py-4 text-start">
                <h3 className="mb-3 text-lg font-bold leading-snug text-[#102233] group-hover:text-[#1277BE] md:text-xl line-clamp-2">
                  {isArabic ? blog.ar_title || blog.title : blog.title}
                </h3>
                <p className="mb-6 text-sm leading-relaxed text-slate-500 line-clamp-3 md:text-base">
                  {summarize(isArabic ? blog.ar_summary || blog.summary : blog.summary, 120)}
                </p>
                <div className="mt-auto flex items-center justify-between border-t border-[#E7EEF5] pt-4">
                  <div className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition group-hover:bg-[#1277BE] group-hover:text-white group-hover:border-transparent">
                    <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-4 w-4" />
                  </div>
                  <span className="text-sm font-medium text-slate-500">{blog.date || "29/06/2025"}</span>
                </div>
              </div>
            </Link>
          ))}
        </div>

        {/* Load More Section */}
        <div className="mt-16 flex flex-col items-center">
          <p className="mb-4 text-sm font-medium text-slate-500">
            {loc("showing")} 12 {loc("of")} {articles.length} {loc("articles_count")}
          </p>
          <div className="mb-8 h-1 w-48 rounded-full bg-slate-200">
            <div className="h-full w-1/3 rounded-full bg-[#1277BE]" />
          </div>
          <button className="rounded-2xl bg-[#1277BE] px-8 py-3 text-base font-semibold text-white transition hover:bg-[#0f64a0]">
            {loc("load_more")}
          </button>
        </div>
      </section>
    </main>
  );
}
