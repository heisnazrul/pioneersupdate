"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { useParams } from "next/navigation";
import { useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

function pickImage(item) {
  if (item?.localImage) return item.localImage;
  if (item?.featured_image) {
    const file = (item.featured_image.split("/").pop() || "").trim();
    return item.featured_image.startsWith("http")
      ? item.featured_image
      : `/cache/articles/${file}`;
  }
  return "/assets/blog/1.png";
}

function summarize(text, limit = 140) {
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

export default function ArticleDetailPage() {
  const params = useParams();
  const slug = Array.isArray(params?.slug) ? params.slug[0] : params?.slug;
  const { data: detail } = useApi(slug ? `/courseenglish/articles/${slug}` : null);
  const { data: list } = useApi("/courseenglish/articles");
  const { isArabic } = useCourseEnglishSettings();
  const [articles, setArticles] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    const load = async () => {
      const { default: mockArticles } = await import("@/data/mocks/blogs.json");
      const { default: mockCats } = await import("@/data/mocks/categories.json");
      const apiArticles = list?.data || [];
      const apiCategories = list?.categories || [];
      setArticles(apiArticles.length ? apiArticles : mockArticles);
      setCategories(apiCategories.length ? apiCategories : mockCats);
      setLoaded(true);
    };
    load();
  }, [slug, list]);

  const post = useMemo(
    () =>
      detail ||
      articles.find(
        (b) =>
          b.slug === slug ||
          (b.title || "").toLowerCase().trim().replace(/\s+/g, "-") === slug
      ),
    [articles, slug, detail]
  );

  const recent = useMemo(
    () => sortByDate(articles).slice(0, 5),
    [articles]
  );

  if (loaded && !post) {
    return (
      <main className="px-2 md:px-20 xl:px-20 2xl:px-40 px-6 py-16 text-slate-900">
        <h1 className="text-3xl font-extrabold">
          {isArabic ? "المقال غير موجود" : "Article not found"}
        </h1>
        <p className="mt-4 text-slate-600">
          {isArabic ? "يرجى العودة إلى قائمة المقالات." : "Please return to the articles list."}
        </p>
        <Link
          href="/articles"
          className="mt-6 inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-normal text-white"
        >
          {isArabic ? "العودة للمقالات" : "Back to articles"}
        </Link>
      </main>
    );
  }

  if (!post) return null;

  const categoryName = isArabic
    ? post?.category?.ar_name || post?.category?.name || post.category || "مقال"
    : post?.category?.name || post.category || "Article";
  const html = isArabic ? post?.ar_content || post?.content || post?.summary || "" : post?.content || post?.summary || "";

  return (
    <main className="px-2 md:px-20 xl:px-20 2xl:px-40 py-14 text-slate-900">
      <div className="mb-6 flex items-center justify-between gap-3">
        <div className="flex items-center gap-3">
          <Link
            href="/articles"
            className="rounded-full border border-slate-200 px-3 py-1 text-sm font-normal text-slate-700 hover:bg-slate-50"
          >
            {isArabic ? "← المقالات" : "← Articles"}
          </Link>
          <span className="rounded-full bg-blue-50 px-3 py-1 text-xs font-normal text-blue-700">
            {categoryName}
          </span>
        </div>
        <span className="text-xs font-normal uppercase tracking-[0.2em] text-slate-500">
          {isArabic ? "تفاصيل المقال" : "Article detail"}
        </span>
      </div>

      <div className="flex flex-col gap-8 lg:flex-row">
        <div className="flex-1">
          <h1 className="text-3xl font-extrabold leading-tight md:text-5xl">
            {isArabic ? post.ar_title || post.title : post.title}
          </h1>
          <div className="mt-6 flex gap-4 overflow-hidden items-center justify-center">
            <img
              src={pickImage(post)}
              alt={post.title}
              className="h-72 w-auto"
            />
            <div>
              <p className="hidden md:block text-2xl text-slate-600">
                {isArabic ? post.ar_summary || post.summary : post.summary}
              </p>
            </div>
          </div>

          <article
            className="prose prose-slate mt-10 max-w-none prose-headings:text-slate-900 prose-a:text-blue-700"
            dangerouslySetInnerHTML={{ __html: html }}
          />
        </div>

        <aside className="w-full lg:w-80 xl:w-96 space-y-6">
          <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h3 className="text-lg font-medium text-slate-900">{isArabic ? "أحدث المقالات" : "Recent posts"}</h3>
              <span className="text-xs font-normal uppercase tracking-[0.2em] text-slate-500">
                {isArabic ? "الأحدث" : "Latest"}
              </span>
            </div>
            <div className="space-y-4">
              {recent.map((item) => (
                <Link
                  key={item.id || item.slug}
                  href={`/articles/${item.slug}`}
                  className="group flex gap-3 rounded-xl p-2 transition hover:bg-slate-50"
                >
                  <div className="h-16 w-16 overflow-hidden rounded-lg bg-slate-100">
                    <img
                      src={pickImage(item)}
                      alt={item.title}
                      className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    />
                  </div>
                  <div className="flex-1">
                    <p className="text-xs font-normal uppercase tracking-[0.14em] text-slate-500">
                      {isArabic
                        ? item?.category?.ar_name || item?.category?.name || item.category || "مقال"
                        : item?.category?.name || item.category || "Article"}
                    </p>
                    <p className="text-sm font-normal text-slate-900 leading-snug group-hover:text-blue-700">
                      {isArabic ? item.ar_title || item.title : item.title}
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
                const slugVal = cat.slug || (cat.name || "").toLowerCase().replace(/\s+/g, "-");
                return (
                  <Link
                    key={slugVal}
                    href={`/articles/category/${slugVal}`}
                    className="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-normal text-slate-700 transition hover:bg-slate-50"
                  >
                    <span>{isArabic ? cat.ar_name || cat.name : cat.name}</span>
                    <span className="text-xs text-slate-500">
                      {
                        articles.filter((a) => {
                          const catSlug =
                            a?.category?.slug ||
                            a.category_slug ||
                            (a?.category?.name || a.category || "")
                              .toLowerCase()
                              .trim()
                              .replace(/\s+/g, "-");
                          return catSlug === slugVal;
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
