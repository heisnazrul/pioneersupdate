"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronLeft, faArrowLeft, faArrowRight } from "@fortawesome/free-solid-svg-icons";
import { faFacebookF, faLinkedinIn, faInstagram } from "@fortawesome/free-brands-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { buildApiUrl } from "@/lib/api";
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

function summarize(text, limit = 160) {
  if (!text) return "";
  const clean = text.replace(/<[^>]+>/g, "");
  if (clean.length <= limit) return clean;
  return `${clean.slice(0, limit)}…`;
}

export default function MobileArticleDetails({ slug }) {
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  const loc = (key) => t(`pages.articles.${key}`);

  const [article, setArticle] = useState(null);
  const [relatedArticles, setRelatedArticles] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    const load = () => {
      // Find the article
      const found = mockBlogs?.find((b) => b.slug === slug) || mockBlogs?.[0];
      setArticle(found);
      
      // Get related/other articles
      const others = mockBlogs?.filter((b) => b.slug !== slug).slice(0, 3) || [];
      setRelatedArticles(others);
      
      setCategories(mockCats || []);
      
      setLoaded(true);
    };
    load();
  }, [slug]);

  if (!loaded) return null;
  if (loaded && !article) {
    return (
      <main className="px-4 py-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
        <div className="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-sm">
          <h1 className="text-2xl font-extrabold">{loc("empty_title")}</h1>
          <p className="mt-4 text-sm text-slate-600">{loc("empty_message")}</p>
        </div>
      </main>
    );
  }

  const title = isArabic ? article.ar_title || article.title : article.title;
  // Fallback content if the mock article doesn't have a full body
  const content = isArabic 
    ? article.ar_content || article.content || "تعد بريطانيا واحدة من أفضل الوجهات العالمية لتعلم اللغة الإنجليزية بفضل معاهدها العريقة ومدنها الغنية ثقافيًا. إذا كنت تفكر في الدراسة هناك، إليك 10 خطوات رئيسية يجب معرفتها: شهرة معاهد بريطانيا هي الوجهة الأولى عالميًا لدراسة اللغة الإنجليزية، حيث تضم معاهد مرموقة تقدم برامج تعليمية عالية الجودة. تكلفة الدراسة تختلف حسب المدينة والمعهد، حيث تتراوح بين 285 و 650 جنيهًا إسترلينيًا أسبوعيًا، بينما تصل تكلفة الدراسة لمدة 6 أشهر إلى 12,000 جنيه إسترليني شاملة السكن. تكلفة المعيشة يمكن للطلاب السكن مع عائلات بريطانية أو في سكن طلابي. تتراوح تكلفة الإقامة مع عائلة بين 1800 و 2000 جنيه إسترليني لمدة 3 أشهر، بينما يختلف السكن الطلابي حسب الموقع والخدمات. أفضل المدن لدراسة اللغة من أبرز المدن التي يقصدها الطلاب: لندن، مانشستر، برايتون، بورنموث، وليفربول، حيث توفر بيئة مناسبة للدراسة والمعيشة. أفضل المعاهد في بريطانيا هناك العديد من المعاهد المميزة مثل: معهد مالفيرن هاوس ومعهد إي سي إنجلش."
    : article.content || "The UK is one of the best global destinations for learning English thanks to its prestigious institutes and culturally rich cities. If you are considering studying there, here are 10 key steps you must know: The reputation of UK institutes makes it the premier global destination for English studies, housing prestigious institutes that offer high-quality educational programs. Tuition costs vary depending on the city and institute, ranging between £285 and £650 per week, while a 6-month study period can reach £12,000 including accommodation. Living costs: students can stay with British families or in student housing. Homestay accommodation ranges between £1,800 and £2,000 for 3 months, while student housing varies by location and services. The best cities to study English include London, Manchester, Brighton, Bournemouth, and Liverpool, providing a suitable environment for study and living. The best institutes in the UK include Malvern House and EC English.";

  return (
    <main className="bg-[#F7F9FB] px-4 py-8 pb-16 text-slate-900" dir={isArabic ? "rtl" : "ltr"}>
      {/* Header */}
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

      {/* Main Content Layout */}
      <section className="mb-12 flex flex-col gap-8">
        {/* Article Text */}
        <div className="text-start">
          <div className="prose prose-base max-w-none text-slate-700 leading-relaxed">
            {content.split("\n").map((p, i) => (
              <p key={i} className="mb-4">{p}</p>
            ))}
          </div>

          {/* Featured Image inside content column */}
          <div className="mt-8 h-[250px] w-full overflow-hidden rounded-2xl">
            <img
              src={pickImage(article)}
              alt={title}
              className="h-full w-full object-cover"
            />
          </div>
        </div>

        {/* Sidebar Info */}
        <aside className="w-full">
          {/* Share Card */}
          <div className="mb-6 rounded-2xl border border-[#D9E4EF] bg-white p-5 shadow-sm text-start">
            <div className="mb-5 border-b border-[#E7EEF5] pb-5">
              <p className="mb-2 text-xs text-slate-500">{loc("published_in")}</p>
              <p className="text-base font-medium text-[#102233]">{article.date || "29/06/2025"}</p>
            </div>
            <div>
              <p className="mb-3 text-xs text-slate-500">{loc("share")}</p>
              <div className="flex gap-3">
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faLinkedinIn} className="h-4 w-4" />
                </a>
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faFacebookF} className="h-4 w-4" />
                </a>
                <a href="#" className="flex h-10 w-10 items-center justify-center rounded-full border border-[#D9E4EF] text-slate-600 transition hover:bg-[#1277BE] hover:text-white hover:border-transparent">
                  <FontAwesomeIcon icon={faInstagram} className="h-4 w-4" />
                </a>
              </div>
            </div>
          </div>

          {/* Categories Card */}
          <div className="rounded-2xl border border-[#D9E4EF] bg-white p-5 shadow-sm text-start">
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

      {/* Related Articles */}
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
        </section>
      )}
    </main>
  );
}
