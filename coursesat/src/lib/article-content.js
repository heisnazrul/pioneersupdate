"use client";

function stripHtml(text) {
  return String(text || "").replace(/<[^>]+>/g, "").trim();
}

export function getArticleField(article, isArabic, field) {
  if (!article) return "";
  const arKey = `ar_${field}`;
  if (isArabic && article[arKey]) return article[arKey];
  return article[field] || article[arKey] || "";
}

export function formatArticleDate(article) {
  if (article?.date) return article.date;
  if (!article?.published_at) return "";
  const date = new Date(article.published_at);
  if (Number.isNaN(date.getTime())) return "";
  return date.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
}

export default function ArticleContent({ content, className = "" }) {
  const raw = String(content || "").trim();
  if (!raw) return null;

  const looksLikeHtml = /<[^>]+>/.test(raw);

  if (looksLikeHtml) {
    return (
      <div
        className={`prose prose-lg max-w-none text-slate-700 leading-[2] [&_img]:rounded-2xl [&_img]:my-6 ${className}`}
        dangerouslySetInnerHTML={{ __html: raw }}
      />
    );
  }

  return (
    <div className={`prose prose-lg max-w-none text-slate-700 leading-[2] ${className}`}>
      {raw.split(/\n+/).filter(Boolean).map((paragraph, index) => (
        <p key={index} className="mb-6">
          {paragraph}
        </p>
      ))}
    </div>
  );
}

export { stripHtml };
