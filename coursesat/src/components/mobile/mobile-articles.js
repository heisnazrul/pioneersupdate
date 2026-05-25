"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faArrowLeft, faArrowRight } from "@fortawesome/free-solid-svg-icons";
// import { useApi, buildApiUrl } from "@/lib/api";
import { buildApiUrl } from "@/lib/api";
import { useLocale } from "@/components/providers/locale-provider";
import mockBlogs from "@/mocdata/blogs.json";
import mockCats from "@/mocdata/categories.json";

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

export default function MobileArticles() {
  // const { data } = useApi("/courseenglish/articles");
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  const loc = (key) => t(`pages.articles.${key}`);

  const [articles, setArticles] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeCat, setActiveCat] = useState("all");
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    const load = async () => {
      // Add fallback mock data
      setArticles(mockBlogs || []);
      setCategories(mockCats || []);
      setLoaded(true);
    };
    load();
  }, []);

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

  if (loaded && articles.length === 0) {
    return (
      <main className="px-4 py-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
        <div className="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm">
          <h1 className="text-2xl font-extrabold">{loc("empty_title")}</h1>
          <p className="mt-4 text-sm text-slate-600">{loc("empty_message")}</p>
        </div>
      </main>
    );
  }

  const recentPosts = articles.slice(0, 2);
  const allPosts = articles.slice(2);

  return (
    <main className="bg-[#F7F9FB] px-4 py-8 pb-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
      {/* Header */}
      <section className="mb-10 text-center">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs text-slate-500 shadow-sm">
          <span>{loc("breadcrumb_home")}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-2.5 w-2.5 ${isArabic ? "rotate-180" : ""}`} />
          <span>{loc("breadcrumb_current")}</span>
        </div>
        <h1 className="text-3xl font-extrabold text-[#102233]">
          {loc("title")}
        </h1>
      </section>

      {/* Recent Articles */}
      {recentPosts.length > 0 && (
        <section className="mb-10">
          <h2 className="mb-4 text-xl font-bold text-[#102233]">{loc("recent_posts")}</h2>
          <div className="flex flex-col gap-4">
            {recentPosts.map((blog) => (
              <Link
                key={blog.id || blog.slug}
                href={`/articles/${blog.slug}`}
                className="group flex flex-col items-center gap-3 rounded-2xl border border-[#D9E4EF] bg-white p-3 shadow-sm transition active:scale-[0.98]"
              >
                <div className="h-40 w-full shrink-0 overflow-hidden rounded-xl">
                  <img
                    src={pickImage(blog)}
                    alt={blog.title}
                    className="h-full w-full object-cover"
                  />
                </div>
                <div className="flex flex-1 flex-col text-start w-full px-1">
                  <h3 className="mb-2 text-base font-bold leading-snug text-[#102233]">
                    {isArabic ? blog.ar_title || blog.title : blog.title}
                  </h3>
                  <p className="text-xs leading-relaxed text-slate-500 line-clamp-3">
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
        <h2 className="mb-4 text-xl font-bold text-[#102233]">{loc("all_articles")}</h2>
        <div className="flex flex-col gap-4">
          {allPosts.map((blog) => (
            <Link
              key={blog.id || blog.slug}
              href={`/articles/${blog.slug}`}
              className="group flex flex-col overflow-hidden rounded-2xl border border-[#D9E4EF] bg-white shadow-sm transition active:scale-[0.98]"
            >
              <div className="h-48 w-full overflow-hidden p-2">
                <img
                  src={pickImage(blog)}
                  alt={blog.title}
                  className="h-full w-full rounded-xl object-cover"
                />
              </div>
              <div className="flex flex-1 flex-col px-4 py-3 text-start">
                <h3 className="mb-2 text-base font-bold leading-snug text-[#102233] line-clamp-2">
                  {isArabic ? blog.ar_title || blog.title : blog.title}
                </h3>
                <p className="mb-4 text-xs leading-relaxed text-slate-500 line-clamp-3">
                  {summarize(isArabic ? blog.ar_summary || blog.summary : blog.summary, 120)}
                </p>
                <div className="mt-auto flex items-center justify-between border-t border-[#E7EEF5] pt-3">
                  <div className="flex h-8 w-8 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600">
                    <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-3 w-3" />
                  </div>
                  <span className="text-xs font-medium text-slate-500">{blog.date || "29/06/2025"}</span>
                </div>
              </div>
            </Link>
          ))}
        </div>

        {/* Load More Section */}
        <div className="mt-12 flex flex-col items-center">
          <p className="mb-3 text-xs font-medium text-slate-500">
            {loc("showing")} 12 {loc("of")} {articles.length} {loc("articles_count")}
          </p>
          <div className="mb-6 h-1 w-40 rounded-full bg-slate-200">
            <div className="h-full w-1/3 rounded-full bg-[#1277BE]" />
          </div>
          <button className="rounded-xl bg-[#1277BE] px-6 py-2.5 text-sm font-semibold text-white">
            {loc("load_more")}
          </button>
        </div>
      </section>
    </main>
  );
}
