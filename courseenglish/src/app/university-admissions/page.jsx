"use client";

import Image from "next/image";
import { useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUniversity, faGraduationCap, faArrowRight, faSearch } from "@fortawesome/free-solid-svg-icons";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const ICONS = {
  faUniversity,
  faGraduationCap,
  faArrowRight,
  faSearch,
};

const getIcon = (name) => {
  if (!name) return faArrowRight;
  return ICONS[name] || faArrowRight;
};

export default function UniversityAdmissionsPage() {
  const { data, loading } = useApi("/courseenglish/university-admissions");
  const { language } = useCourseEnglishSettings();

  const apiOrigin = useMemo(() => {
    try {
      return new URL(buildApiUrl("")).origin;
    } catch {
      return "";
    }
  }, []);

  const abs = (url) => {
    if (!url) return "/assets/placeholder.png";
    if (url.startsWith("http")) return url;
    if (url.startsWith("//")) return `https:${url}`;
    if (url.startsWith("/")) return `${apiOrigin}${url}`;
    return `${apiOrigin}/${url}`;
  };

  const content =
    language === "ar"
      ? (data?.ar_content && Object.keys(data.ar_content).length ? data.ar_content : data?.content) ?? {}
      : data?.content ?? {};

  if (loading || !content || Object.keys(content).length === 0) {
    return null;
  }

  const hero = content.hero || {};
  const cards = content.cards || [];
  const stats = content.stats || [];

  const isArabic = language === "ar";

  return (
    <main className="min-h-screen bg-slate-50" dir={isArabic ? "rtl" : "ltr"}>
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-[#003B5C] py-20 text-center text-white">
        <div className="absolute inset-0 opacity-10 bg-[url('/pattern.png')]"></div>
        <div className="container relative z-10 mx-auto px-4">
          <span className="mb-4 inline-block rounded-full bg-blue-500/20 border border-blue-400/30 px-4 py-1.5 text-xs font-medium uppercase tracking-wider text-blue-200 backdrop-blur-sm">
            {hero.badge}
          </span>
          <h1 className="mb-6 text-4xl font-semibold md:text-6xl tracking-tight">{hero.title}</h1>
          <p className="mx-auto max-w-2xl text-lg font-extralight text-blue-100 md:text-xl">
            {hero.description}
          </p>
        </div>
      </section>

      {/* Split Cards Section */}
      <section className="relative z-20 container mx-auto px-4 -mt-10 pb-24">
        <div className="grid gap-8 md:grid-cols-2 max-w-5xl mx-auto">
          {cards.map((card, idx) => {
            const overlayColor = idx % 2 === 0 ? "#003B5C" : "#1F63AE";
            return (
            <a
              key={idx}
              href={card.url || "#"}
              target="_blank"
              rel="noopener noreferrer"
              className="group relative flex min-h-[400px] flex-col overflow-hidden rounded-3xl bg-white shadow-xl transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
            >
              <div className="absolute inset-0 h-full w-full">
                <Image
                  src={abs(card.image)}
                  alt={card.title}
                  fill
                  className="object-cover transition-transform duration-700 group-hover:scale-110"
                  unoptimized
                />
                <div
                  className="absolute inset-0 bg-gradient-to-t to-transparent opacity-90 transition-opacity duration-500 group-hover:opacity-100"
                  style={{
                    backgroundImage: `linear-gradient(to top, ${overlayColor}, ${overlayColor}CC, transparent)`,
                  }}
                ></div>
              </div>

              <div className="relative z-10 flex flex-1 flex-col items-center justify-center p-8 text-center text-white">
                <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-white/10 backdrop-blur-md ring-1 ring-white/20 transition-transform duration-500 group-hover:scale-110">
                  <FontAwesomeIcon icon={getIcon(card.icon)} className="h-8 w-8 text-blue-200" />
                </div>

                <h2 className="mb-3 text-3xl font-semibold tracking-tight">{card.title}</h2>
                <p className="mb-8 max-w-xs text-blue-100/90">{card.description}</p>

                <span
                  className="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-medium transition-colors hover:bg-blue-50"
                  style={{ color: overlayColor }}
                >
                  {card.button_text} <FontAwesomeIcon icon={faArrowRight} />
                </span>
              </div>
            </a>
          );
          })}
        </div>
      </section>

      {/* Info Stats Section */}
      <section className="bg-white py-16">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
            {stats.map((item, idx) => (
              <div key={idx} className="p-4">
                <div className="text-4xl font-semibold text-[#003B5C] mb-1">{item.value}</div>
                <div className="text-sm font-medium uppercase tracking-wider text-slate-500">{item.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
}
