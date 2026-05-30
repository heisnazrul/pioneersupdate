"use client";

import { useMemo } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faArrowLeft, faArrowRight } from "@fortawesome/free-solid-svg-icons";
import { faFacebookF, faLinkedinIn, faInstagram } from "@fortawesome/free-brands-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { useApi, buildApiUrl } from "@/lib/api";
import ArticleContent, { formatArticleDate, getArticleField } from "@/lib/article-content";

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

function summarize(text, limit = 160) {
  if (!text) return "";
  const clean = text.replace(/<[^>]+>/g, "");
  if (clean.length <= limit) return clean;
  return `${clean.slice(0, limit)}…`;
}

export default function DesktopArticleDetails({ slug }) {
  const { data: articleData, loading, error } = useApi(slug ? `/courseenglish/articles/${encodeURIComponent(slug)}` : null);
  const { data: listData } = useApi("/courseenglish/articles?per_page=12");
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  const loc = (key) => t(`pages.articles.${key}`);

  const article = articleData?.slug || articleData?.id ? articleData : null;
  const categories = Array.isArray(listData?.categories) ? listData.categories : [];
  const relatedArticles = useMemo(() => {
    const items = Array.isArray(listData?.data) ? listData.data : [];
    return items.filter((b) => b.slug !== slug).slice(0, 3);
  }, [listData, slug]);

  if (loading) {
    return (
      <main className="flex min-h-[40vh] items-center justify-center bg-[#F7F9FB] px-4 py-16">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#1277BE] border-t-transparent" />
      </main>
    );
  }

  if (error || !article) {
    return (
      <main className="px-2 py-16 text-slate-900 md:px-10 lg:px-20" dir={isArabic ? "rtl" : "ltr"}>
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
          <h1 className="text-3xl font-extrabold">{loc("empty_title")}</h1>
          <p className="mt-4 text-slate-600">{loc("empty_message")}</p>
        </div>
      </main>
    );
  }

  const title = getArticleField(article, isArabic, "title");
  const content = getArticleField(article, isArabic, "content");
  const summary = getArticleField(article, isArabic, "summary");
  const publishedDate = formatArticleDate(article);
  const categoryName = isArabic
    ? article.category?.ar_name || article.category_ar_name || article.category?.name
    : article.category?.name || article.category_ar_name;

  return (
    <main className="bg-[#F7F9FB] px-4 py-10 pb-20 text-slate-900 md:px-10 lg:px-20 2xl:px-40" dir={isArabic ? "rtl" : "ltr"}>
      {/* Header */}
      <section className="my-10 text-center">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm text-slate-500 shadow-sm">
          <Link href="/" className="hover:text-[#1277BE]">{loc("breadcrumb_home")}</Link>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <Link href="/articles" className="hover:text-[#1277BE]">{loc("breadcrumb_current")}</Link>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <span className="max-w-[200px] truncate">{title}</span>
        </div>
        <h1 className="mx-auto mb-16 max-w-4xl text-4xl font-bold leading-tight text-[#102233]">
          {title}
        </h1>
      </section>

      {/* Main Content Layout */}
      <section className="my-10 md:my-20 flex flex-col gap-40 lg:flex-row">
        {/* Article Text */}
        <div className="flex-1 text-start">
          {summary ? <p className="mb-8 text-lg text-slate-500">{summary}</p> : null}
          <ArticleContent content={content} />

          {/* Featured Image inside content column */}
          <div className="mt-10 h-[400px] w-full overflow-hidden rounded-3xl md:h-[500px]">
            <img
              src={pickImage(article)}
              alt={title}
              className="h-full w-full object-cover"
            />
          </div>
        </div>

        {/* Sidebar Info */}
        <aside className="w-full shrink-0 lg:w-72">
          {/* Share Card */}
          <div className="mb-6 rounded-3xl border border-[#D9E4EF] bg-white p-6 shadow-sm text-start">
            <div className="mb-6 border-b border-[#E7EEF5] pb-6">
              <p className="mb-2 text-sm text-slate-500">{loc("published_in")}</p>
              <p className="text-lg font-medium text-[#102233]">{publishedDate || "-"}</p>
            </div>
            {categoryName ? (
              <div className="mb-6 border-b border-[#E7EEF5] pb-6">
                <p className="mb-2 text-sm text-slate-500">{loc("categories")}</p>
                <p className="text-lg font-medium text-[#102233]">{categoryName}</p>
              </div>
            ) : null}
            <div>
              <p className="mb-4 text-sm text-slate-500">{loc("share")}</p>
              <div className="flex gap-4">
                <a href="#" className="flex h-12 w-12 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faLinkedinIn} className="h-5 w-5" />
                </a>
                <a href="#" className="flex h-12 w-12 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faFacebookF} className="h-5 w-5" />
                </a>
                <a href="#" className="flex h-12 w-12 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faInstagram} className="h-5 w-5" />
                </a>
              </div>
            </div>
          </div>

          {/* Categories Card */}
          <div className="rounded-3xl border border-[#D9E4EF] bg-white p-6 shadow-sm text-start">
            <h3 className="mb-4 text-lg font-bold text-[#102233]">{loc("categories")}</h3>
            <div className="flex flex-col gap-2">
              {categories.map((cat) => (
                <Link
                  key={cat.id || cat.slug}
                  href={`/articles?category=${cat.slug}`}
                  className="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#1277BE]"
                >
                  <span>{isArabic ? cat.ar_name || cat.name : cat.name}</span>
                </Link>
              ))}
            </div>
          </div>
        </aside>
      </section>

      {/* Related Articles */}
      {relatedArticles.length > 0 && (
        <section>
          <h2 className="mb-10 text-2xl font-bold text-[#102233] md:text-3xl">{loc("other_articles")}</h2>
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {relatedArticles.map((blog) => (
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
                    <span className="text-sm font-medium text-slate-500">{formatArticleDate(blog) || blog.date || "-"}</span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </section>
      )}
    </main>
  );
}
