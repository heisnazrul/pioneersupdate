"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

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

export default function ArticlesPage() {
  const { data } = useApi("/courseenglish/articles");
  const { isArabic } = useCourseEnglishSettings();
  const [articles, setArticles] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeCat, setActiveCat] = useState("all");
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    const load = async () => {
      const { default: mockArticles } = await import("@/data/mocks/blogs.json");
      const { default: mockCats } = await import("@/data/mocks/categories.json");

      const apiArticles = data?.data || [];
      const apiCategories = data?.categories || [];
      setArticles(apiArticles.length ? apiArticles : mockArticles);
      setCategories(apiCategories.length ? apiCategories : mockCats);
      setLoaded(true);
    };
    load();
  }, [data]);

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
      <main className="px-2 md:px-20 xl:px-20 2xl:px-40 py-16 text-slate-900">
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
          <h1 className="text-3xl font-extrabold">Articles</h1>
          <p className="mt-4 text-slate-600">
            No posts available. Please cache data from the admin panel first.
          </p>
        </div>
      </main>
    );
  }

  return (
    <main className="px-2 md:px-20 xl:px-20 2xl:px-40 py-10 text-slate-900">
      <div className=" mb-10">
        <h1 className="text-4xl font-extrabold text-slate-900">
          {isArabic ? "أحدث القصص والأدلة" : "Latest stories and guides"}
        </h1>
        <p className="text-slate-600">
          {isArabic
            ? "تصفح الرؤى والنصائح والإرشادات لرحلتك الدراسية."
            : "Browse insights, tips, and how-tos for your study journey."}
        </p>
      </div>
      <div className="flex flex-col gap-8 lg:flex-row">
        <div className="flex-1">
          <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            {filtered.map((blog) => {
              const catName = isArabic
                ? blog?.category?.ar_name || blog?.category?.name || blog.category
                : blog?.category?.name || blog.category || blog?.category_name;
              const catSlug =
                blog?.category?.slug || blog.category_slug || slugify(catName || "category");
              return (
                <Link
                  key={blog.id || blog.slug}
                  href={`/articles/${blog.slug}`}
                  className="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                >
                  <div className="relative p-2 h-auto w-full overflow-hidden">
                    <img
                      src={pickImage(blog)}
                      alt={blog.title}
                      className="h-full w-full object-cover rounded-xl  transition duration-500 group-hover:scale-105"
                    />
                    <span className="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-normal text-slate-800 shadow">
                      {catName || (isArabic ? "مقال" : "Article")}
                    </span>
                  </div>
                  <div className="flex flex-1 flex-col gap-3 p-5">
                    <h2 className="text-lg font-medium leading-snug text-slate-900 group-hover:text-blue-700">
                      {isArabic ? blog.ar_title || blog.title : blog.title}
                    </h2>
                    <p className="text-sm text-slate-600">
                      {summarize(isArabic ? blog.ar_summary || blog.summary : blog.summary, 160)}
                    </p>
                    <div className="mt-auto flex items-center justify-between text-sm font-normal text-blue-700">
                      <span>{isArabic ? "اقرأ المزيد" : "Read more"}</span>
                      <span className="text-xs text-slate-500">#{catSlug}</span>
                    </div>
                  </div>
                </Link>
              );
            })}
          </div>
        </div>

        <aside className="w-full lg:w-80 xl:w-96 space-y-6">
          <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h3 className="text-lg font-medium text-slate-900">Recent posts</h3>
              <span className="text-xs font-normal uppercase tracking-[0.2em] text-slate-500">
                Latest
              </span>
            </div>
            <div className="space-y-4">
              {sortByDate(articles)
                .slice(0, 5)
                .map((post) => (
                  <Link
                    key={post.id || post.slug}
                    href={`/articles/${post.slug}`}
                    className="group flex gap-3 rounded-xl p-2 transition hover:bg-slate-50"
                  >
                    <div className="h-16 w-auto overflow-hidden rounded-lg bg-slate-100">
                      <img
                        src={pickImage(post)}
                        alt={post.title}
                        className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                      />
                    </div>
                    <div className="flex-1">
                      <p className="text-xs font-normal uppercase tracking-[0.14em] text-slate-500">
                      {isArabic
                        ? post?.category?.ar_name || post?.category?.name || post.category || "مقال"
                        : post?.category?.name || post.category || "Article"}
                    </p>
                    <p className="text-sm font-normal text-slate-900 leading-snug group-hover:text-blue-700">
                      {isArabic ? post.ar_title || post.title : post.title}
                    </p>
                  </div>
                </Link>
              ))}
            </div>
          </div>

          <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 className="mb-3 text-lg font-medium text-slate-900">{isArabic ? "التصنيفات" : "Categories"}</h3>
            <div className="flex flex-col gap-2">
              {categories.map((cat) => {
                const slug = cat.slug || slugify(cat.name);
                return (
                  <Link
                    key={slug}
                    href={`/articles/category/${slug}`}
                    className="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-normal text-slate-700 transition hover:bg-slate-50"
                  >
                    <span>{isArabic ? cat.ar_name || cat.name : cat.name}</span>
                    <span className="text-xs text-slate-500">
                      {
                        articles.filter((a) => {
                          const catSlug =
                            a?.category?.slug ||
                            a.category_slug ||
                            slugify(a?.category?.name || a.category || "");
                          return catSlug === slug;
                        }).length
                      }
                    </span>
                  </Link>
                );
              })}
            </div>
          </div>
        </aside>
      </div>
    </main>
  );
}
