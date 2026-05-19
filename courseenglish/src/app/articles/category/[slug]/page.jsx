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

function slugify(str) {
  return (str || "").toLowerCase().trim().replace(/\s+/g, "-");
}

function summarize(text, limit = 160) {
  if (!text) return "";
  const clean = text.replace(/<[^>]+>/g, "");
  if (clean.length <= limit) return clean;
  return `${clean.slice(0, limit)}…`;
}

export default function CategoryPage() {
  const params = useParams();
  const slug = Array.isArray(params?.slug) ? params.slug[0] : params?.slug;
  const { data: list } = useApi(slug ? `/courseenglish/articles/category/${slug}` : null);
  const { data: categoriesData } = useApi("/courseenglish/articles/categories");
  const { isArabic } = useCourseEnglishSettings();
  const [posts, setPosts] = useState([]);
  const [cat, setCat] = useState(null);
  const [loaded, setLoaded] = useState(false);
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    let mounted = true;
    const load = async () => {
      const { default: mockArticles } = await import("@/data/mocks/blogs.json");
      const { default: mockCats } = await import("@/data/mocks/categories.json");

      const data = list?.data || [];
      const cats = categoriesData?.length ? categoriesData : mockCats;
      const filtered = data.length ? data : mockArticles.filter((b) => {
        const bSlug =
          b.category_slug ||
          b?.category?.slug ||
          slugify(b?.category?.name || b.category || "");
        return bSlug === slug;
      });

      let currentCat =
        cats.find((c) => (c.slug || slugify(c.name)) === slug) || null;

      if (!currentCat && filtered.length) {
        const first = filtered[0];
        currentCat = {
          name: first?.category?.name || first.category || "Category",
          ar_name: first?.category?.ar_name,
          slug,
        };
      }

      if (!mounted) return;
      setCat(currentCat);
      setPosts(filtered);
      setCategories(Array.isArray(cats) ? cats : []);
      setLoaded(true);
    };
    load();
    return () => {
      mounted = false;
    };
  }, [slug, list, categoriesData]);

  if (loaded && posts.length === 0) {
    return (
      <main className="px-2 md:px-20 xl:px-20 2xl:px-40 py-12 text-slate-900">
        <h1 className="text-3xl font-extrabold mb-2">
          {isArabic ? cat?.ar_name || cat?.name || "تصنيف" : cat?.name || "Category"}
        </h1>
        <p className="text-slate-600">
          {isArabic ? "لا توجد مقالات في هذا التصنيف." : "No posts in this category."}
        </p>
      </main>
    );
  }

  const recent = useMemo(() => posts.slice(0, 5), [posts]);

  return (
    <main className="px-2 md:px-20 xl:px-20 2xl:px-40 py-12 text-slate-900">
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div>
          <p className="text-xs font-normal uppercase tracking-[0.25em] text-slate-500">
            {isArabic ? "تصنيف" : "Category"}
          </p>
          <h1 className="text-3xl font-extrabold">
            {isArabic ? cat?.ar_name || cat?.name || "تصنيف" : cat?.name || "Category"}
          </h1>
        </div>
        <Link
          href="/articles"
          className="rounded-full border border-slate-200 px-4 py-2 text-sm font-normal text-slate-700 hover:bg-slate-50"
        >
          {isArabic ? "العودة للكل" : "Back to all"}
        </Link>
      </div>

      <div className="mt-10 flex flex-col gap-8 lg:flex-row">
        <div className="flex-1">
          <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            {posts.map((blog) => {
              const catName = isArabic
                ? blog?.category?.ar_name || blog?.category?.name || blog.category || cat?.name
                : blog?.category?.name || blog.category || cat?.name;
              const catSlug =
                blog?.category?.slug ||
                blog.category_slug ||
                slugify(catName || "category");
              return (
                <Link
                  key={blog.id || blog.slug}
                  href={`/articles/${blog.slug}`}
                  className="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                >
                  <div className="relative h-40 w-full overflow-hidden">
                    <img
                      src={pickImage(blog)}
                      alt={blog.title}
                      className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
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
              <h3 className="text-lg font-medium text-slate-900">
                {isArabic
                  ? `الأحدث في ${cat?.ar_name || cat?.name || ""}`
                  : `Recent in ${cat?.name || ""}`}
              </h3>
              <span className="text-xs font-normal uppercase tracking-[0.2em] text-slate-500">
                {isArabic ? "الأحدث" : "Latest"}
              </span>
            </div>
            <div className="space-y-4">
              {recent.map((post) => (
                <Link
                  key={post.id || post.slug}
                  href={`/articles/${post.slug}`}
                  className="group flex gap-3 rounded-xl p-2 transition hover:bg-slate-50"
                >
                  <div className="h-16 w-16 overflow-hidden rounded-lg bg-slate-100">
                    <img
                      src={pickImage(post)}
                      alt={post.title}
                      className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    />
                  </div>
                  <div className="flex-1">
                    <p className="text-xs font-normal uppercase tracking-[0.14em] text-slate-500">
                      {isArabic
                        ? post?.category?.ar_name || post?.category?.name || post.category || cat?.name || "مقال"
                        : post?.category?.name || post.category || cat?.name || "Article"}
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
            <h3 className="mb-3 text-lg font-medium text-slate-900">
              {isArabic ? "كل التصنيفات" : "All categories"}
            </h3>
            <div className="flex flex-col gap-2">
              {categories.map((c) => {
    const slugVal = c.slug || slugify(c.name);
    return (
                  <Link
                    key={slugVal}
                    href={`/articles/category/${slugVal}`}
                    className="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-normal text-slate-700 transition hover:bg-slate-50"
                  >
                    <span>{isArabic ? c.ar_name || c.name : c.name}</span>
      <span className="text-xs text-slate-500">
        {
          posts.filter((p) => {
            const pSlug =
              p?.category?.slug ||
              p.category_slug ||
              slugify(p?.category?.name || p.category || "");
            return pSlug === slugVal;
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
