"use client";

import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBullseye, faLightbulb, faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { getImageUrl, useApi } from "@/lib/courseenglishApi";
import CertificatesSection from "@/app/components/certificate";

const FALLBACK = {
  breadcrumb: { home: "Home", current: "About Us" },
  page_title: "About Course English",
  intro: {
    title: "We Help You Choose the Right Language Institute with Confidence",
    paragraph_1:
      "A specialized platform for showcasing and comparing accredited language institutes around the world, helping you make the right decision before booking.",
    paragraph_2:
      "We connect students and families with trusted institutes in different countries and help compare by quality, location, and cost.",
    image: "/img.png",
  },
  vision: {
    title: "Our Vision",
    body: "To lead the market by managing outstanding talent and becoming the best specialized company in our field.",
  },
  mission: {
    title: "Our Mission",
    body: "A specialized platform for showing and comparing accredited language institutes globally, and helping you choose with clarity.",
  },
  why: {
    title: "Why Us?",
    subtitle: "Because we believe your educational journey starts with confidence.",
    items: [
      {
        title: "Free Services",
        body: "Our free services include admission support and guidance to find the most suitable schools.",
        icon: "/like.gif",
      },
      {
        title: "Official Partnerships",
        body: "We partner with trusted English schools worldwide to secure better choices for your learning journey.",
        icon: "/team.gif",
      },
      {
        title: "Exclusive Prices",
        body: "Our prices are exclusive. If you find a better offer, we match it.",
        icon: "/tag.gif",
      },
    ],
  },
};

const FALLBACK_AR = {
  breadcrumb: { home: "الرئيسية", current: "من نحن" },
  page_title: "عن كورس انجليزي",
  intro: {
    title: "نساعدك في اختيار معهد اللغة الأنسب لك، بثقة ووضوح",
    paragraph_1:
      "منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.",
    paragraph_2:
      "نحن منصة تعليمية متخصصة تربط الطلاب والأسر بمعاهد اللغة المعتمدة في مختلف الدول، ونساعدك في مقارنة الخيارات واختيار الأنسب لك من حيث الجودة، الموقع، والتكلفة.",
    image: "/img.png",
  },
  vision: {
    title: "رؤيتنا",
    body: "الريادة المحلية من خلال استراتيجية إدارة النشّات وتوفير الكوادر البشرية المتخصصة من داخل وخارج المملكة لتصبح أفضل شركة رائدة ومتخصصة في مجالها.",
  },
  mission: {
    title: "رسالتنا",
    body: "منصة متخصصة في عرض ومقارنة معاهد اللغة المعتمدة حول العالم، ومساعدتك في اتخاذ القرار الأنسب قبل الحجز.",
  },
  why: {
    title: "لماذا نحن؟",
    subtitle: "لأننا نؤمن أن رحلتك التعليمية تبدأ بثقة",
    items: [
      {
        title: "خدمات مجانية",
        body: "تشمل خدماتنا المجانية دعم القبول والتوجيه لمساعدتك في العثور على المدارس التي تناسب احتياجاتك بشكل أفضل.",
        icon: "/like.gif",
      },
      {
        title: "الشراكات الرسمية",
        body: "نحن نتشارك مع مدارس اللغة الإنجليزية الموثوقة في جميع أنحاء العالم لضمان حصولك على أفضل الخيارات في رحلة التعلم الخاصة بك.",
        icon: "/team.gif",
      },
      {
        title: "الاسعار والعروض الحصرية",
        body: "اسعارنا حصرية. اذا وجدت سعرا افضل فسوف ننافسك.",
        icon: "/tag.gif",
      },
    ],
  },
};

const resolveCmsImage = (value, fallback = "") => {
  const path = (value || "").trim();
  if (!path) return fallback;
  if (path.startsWith("http://") || path.startsWith("https://")) return path;
  if (path.startsWith("/storage/")) return getImageUrl(path);
  // Keep local frontend assets as-is (e.g. /img.png, /like.gif, /tag.gif)
  if (path.startsWith("/")) return path;
  // CMS upload paths are stored as relative disk path (e.g. cms-home/xxx.png)
  return getImageUrl(`/storage/${path}`);
};

export default function AboutPage() {
  const { language, isArabic } = useCourseEnglishSettings();
  const { data } = useApi("/courseenglish/about");

  const raw = isArabic ? data?.ar_content : data?.content;
  const fallback = isArabic ? FALLBACK_AR : FALLBACK;
  const t = {
    ...fallback,
    ...(raw || {}),
    breadcrumb: { ...fallback.breadcrumb, ...(raw?.breadcrumb || {}) },
    intro: { ...fallback.intro, ...(raw?.intro || {}) },
    vision: { ...fallback.vision, ...(raw?.vision || {}) },
    mission: { ...fallback.mission, ...(raw?.mission || {}) },
    why: {
      ...fallback.why,
      ...(raw?.why || {}),
      items: (raw?.why?.items?.length ? raw.why.items : fallback.why.items).slice(0, 3),
    },
  };

  const introImage = resolveCmsImage(t.intro.image, "/img.png");

  return (
    <main className="min-h-screen bg-[#F7F9FB] pb-20" dir={isArabic ? "rtl" : "ltr"}>
      <section className="container mx-auto px-4 pt-14 md:pt-20">
        <div className="mx-auto mb-8 flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm text-slate-500 shadow-sm">
          <span>{t.breadcrumb.home}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <span>{t.breadcrumb.current}</span>
        </div>
        <h1 className="my-10 text-center text-4xl font-semibold text-[#102233] md:mb-20 md:text-6xl md:leading-[1.1]">
          {t.page_title}
        </h1>
      </section>

      <section className="container mx-auto grid grid-cols-1 items-center gap-8 px-4 py-12 md:gap-20 md:py-30 lg:grid-cols-2 xl:gap-30">
        <div className={`order-2 lg:order-1 ${isArabic ? "lg:pr-6" : "lg:pl-6"}`}>
          <h2 className="mb-6 text-2xl font-semibold leading-[1.25] text-[#102233] md:text-5xl md:leading-[1.2]">{t.intro.title}</h2>
          <p className="mb-4 text-base leading-relaxed text-slate-600 md:text-2xl md:leading-[1.9]">{t.intro.paragraph_1}</p>
          <p className="text-base leading-relaxed text-slate-600 md:text-2xl md:leading-[1.9]">{t.intro.paragraph_2}</p>
        </div>
        <div className="order-1 p-2 md:p-10 lg:order-2">
          <div className="relative h-[320px] overflow-hidden rounded-2xl bg-[#E7EEF5] md:h-[650px]">
            <Image src={introImage} alt="About" fill className="object-cover object-center" unoptimized />
            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-[#F7F9FB] to-transparent md:h-78" />
          </div>
        </div>
      </section>

      <section className="container mx-auto grid grid-cols-1 gap-5 px-4 md:grid-cols-[1.2fr_0.8fr] md:gap-20 md:[direction:ltr]">
        <div className={`relative overflow-hidden rounded-3xl bg-[#1277BE] p-8 text-white md:px-20 ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
          <div className={isArabic ? "md:pr-20" : "md:pl-20"}>
            <h3 className="mb-3 text-3xl font-semibold md:text-4xl">{t.vision.title}</h3>
            <p className="text-base leading-relaxed text-blue-50 md:text-xl md:leading-[1.9]">{t.vision.body}</p>
          </div>
          <div className={`pointer-events-none absolute hidden md:block ${isArabic ? "right-10 top-1/2 -translate-y-1/2" : "left-10 top-1/2 -translate-y-1/2"}`}>
            <FontAwesomeIcon icon={faLightbulb} className="text-6xl text-[#FDBA74]" />
          </div>
        </div>

        <div className={`relative overflow-hidden rounded-3xl bg-[#1277BE] p-8 text-white ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
          <div className={isArabic ? "pt-10 md:pr-10" : "pt-10 md:pl-20"}>
            <h3 className="mb-3 text-3xl font-semibold md:text-4xl">{t.mission.title}</h3>
            <p className="text-base leading-relaxed text-blue-50 md:text-xl md:leading-[1.9]">{t.mission.body}</p>
          </div>
          <div className={`pointer-events-none absolute hidden md:block ${isArabic ? "right-20 top-7" : "left-20 top-7"}`}>
            <FontAwesomeIcon icon={faBullseye} className="text-[38px] text-blue-100" />
          </div>
        </div>
      </section>

      <section className="container mx-auto px-4 py-16 md:py-30">
        <h2 className="text-center text-4xl font-semibold text-[#102233] md:text-5xl">{t.why.title}</h2>
        <p className="mt-3 text-center text-lg text-slate-500 md:text-xl">{t.why.subtitle}</p>
        <div className="mt-14 grid grid-cols-1 gap-12 md:grid-cols-3">
          {(t.why.items || []).slice(0, 3).map((item, index) => (
            <div key={`${item.title}-${index}`} className="text-center">
              <Image
                src={resolveCmsImage(item.icon || fallback.why.items[index]?.icon, "/like.gif")}
                alt={item.title || "Icon"}
                width={132}
                height={132}
                className="mx-auto mb-4 h-24 w-24 md:h-32 md:w-32"
                unoptimized
              />
              <h3 className="mb-3 text-3xl font-semibold text-[#102233] md:text-3xl">{item.title}</h3>
              <p className="text-base leading-relaxed text-slate-500 md:text-xl md:leading-[1.8]">{item.body}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="pt-4">
        <CertificatesSection />
      </section>
    </main>
  );
}
