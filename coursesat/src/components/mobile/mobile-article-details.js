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

export default function MobileArticleDetails({ slug }) {
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
      <main className="px-4 py-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
        <div className="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm">
          <h1 className="text-2xl font-extrabold">{loc("empty_title")}</h1>
          <p className="mt-4 text-sm text-slate-600">{loc("empty_message")}</p>
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
    <main className="bg-[#F7F9FB] px-4 py-8 pb-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
      <section className="mb-8 text-center">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs text-slate-500 shadow-sm">
          <Link href="/" className="hover:text-[#1277BE]">{loc("breadcrumb_home")}</Link>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-2.5 w-2.5 ${isArabic ? "rotate-180" : ""}`} />
          <Link href="/articles" className="hover:text-[#1277BE]">{loc("breadcrumb_current")}</Link>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-2.5 w-2.5 ${isArabic ? "rotate-180" : ""}`} />
          <span className="max-w-[120px] truncate">{title}</span>
        </div>
        <h1 className="mx-auto mb-10 max-w-full text-3xl font-bold leading-tight text-[#102233]">
          {title}
        </h1>
      </section>

      <section className="mb-12 flex flex-col gap-8">
        <div className="text-start">
          {summary ? <p className="mb-6 text-base text-slate-500">{summary}</p> : null}
          <ArticleContent content={content} className="prose-base leading-relaxed" />

          <div className="mt-8 h-[250px] w-full overflow-hidden rounded-2xl">
            <img src={pickImage(article)} alt={title} className="h-full w-full object-cover" />
          </div>
        </div>

        <aside className="w-full">
          <div className="mb-6 rounded-2xl border border-[#D9E4EF] bg-white p-5 text-start shadow-sm">
            <div className="mb-5 border-b border-[#E7EEF5] pb-5">
              <p className="mb-2 text-xs text-slate-500">{loc("published_in")}</p>
              <p className="text-base font-medium text-[#102233]">{publishedDate || "-"}</p>
            </div>
            {categoryName ? (
              <div className="mb-5 border-b border-[#E7EEF5] pb-5">
                <p className="mb-2 text-xs text-slate-500">{loc("categories")}</p>
                <p className="text-base font-medium text-[#102233]">{categoryName}</p>
              </div>
            ) : null}
            <div>
              <p className="mb-3 text-xs text-slate-500">{loc("share")}</p>
              <div className="flex gap-3">
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:border-transparent hover:bg-[#1277BE] hover:text-white">
                  <FontAwesomeIcon icon={faLinkedinIn} className="h-4 w-4" />
                </a>
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:border-transparent hover:bg-[#1277BE] hover:text-white">
                  <FontAwesomeIcon icon={faFacebookF} className="h-4 w-4" />
                </a>
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:border-transparent hover:bg-[#1277BE] hover:text-white">
                  <FontAwesomeIcon icon={faInstagram} className="h-4 w-4" />
                </a>
              </div>
            </div>
          </div>

          <div className="rounded-2xl border border-[#D9E4EF] bg-white p-5 text-start shadow-sm">
            <h3 className="mb-4 text-base font-bold text-[#102233]">{loc("categories")}</h3>
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

      {relatedArticles.length > 0 && (
        <section>
          <h2 className="mb-6 text-xl font-bold text-[#102233]">{loc("other_articles")}</h2>
          <div className="flex flex-col gap-4">
            {relatedArticles.map((blog) => (
              <Link
                key={blog.id || blog.slug}
                href={`/articles/${blog.slug}`}
                className="group flex flex-col overflow-hidden rounded-2xl border border-[#D9E4EF] bg-white shadow-sm transition active:scale-[0.98]"
              >
                <div className="h-48 w-full overflow-hidden p-2">
                  <img src={pickImage(blog)} alt={blog.title} className="h-full w-full rounded-xl object-cover" />
                </div>
                <div className="flex flex-1 flex-col px-4 py-3 text-start">
                  <h3 className="mb-2 line-clamp-2 text-base font-bold leading-snug text-[#102233]">
                    {isArabic ? blog.ar_title || blog.title : blog.title}
                  </h3>
                  <p className="mb-4 line-clamp-3 text-xs leading-relaxed text-slate-500">
                    {summarize(isArabic ? blog.ar_summary || blog.summary : blog.summary, 120)}
                  </p>
                  <div className="mt-auto flex items-center justify-between border-t border-[#E7EEF5] pt-3">
                    <div className="flex h-8 w-8 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600">
                      <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-3 w-3" />
                    </div>
                    <span className="text-xs font-medium text-slate-500">{formatArticleDate(blog) || blog.date || "-"}</span>
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
