"use client";

import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBullseye, faLightbulb, faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import DesktopCertificates from "./desktop-certificates";
// import { useApi } from "@/lib/api";

export default function DesktopAbout() {
  const { language, t } = useLocale();
  const isArabic = language === "ar";

  // Try fetching API, fallback to locale if no data
  // const { data } = useApi("/courseenglish/about");
  const data = null;

  const loc = (key) => t(`pages.about_us.${key}`);

  const introImage = "/assets/images/img.png";

  return (
    <div className="bg-[#F7F9FB] pb-10 pt-10" dir={isArabic ? "rtl" : "ltr"}>
      <section className="container mx-auto px-4 pt-10">
        <div className="mx-auto mb-4 flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm text-slate-500 shadow-sm">
          <span>{loc("breadcrumb_home")}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <span>{loc("breadcrumb_current")}</span>
        </div>
        <h1 className="my-8 text-center text-5xl font-bold text-[#102233] md:leading-[1.1]">
          {loc("page_title")}
        </h1>
      </section>

      <section className="container mx-auto grid grid-cols-1 items-center gap-8 px-4 py-12 md:gap-20 md:py-20 lg:grid-cols-2 xl:gap-30">
        <div className={`order-2 lg:order-1 ${isArabic ? "lg:pr-6" : "lg:pl-6"}`}>
          <h2 className="mb-6 max-w-lg text-2xl font-bold leading-[1.25] text-[#102233] md:text-4xl md:leading-[1.2]">{loc("intro_title")}</h2>
          <p className="mb-4 max-w-lg text-base leading-relaxed text-slate-600 md:text-lg md:leading-[1.9]">{loc("intro_p1")}</p>
          <p className="text-base max-w-lg leading-relaxed text-slate-600 md:text-lg md:leading-[1.9]">{loc("intro_p2")}</p>
        </div>
        <div className="order-1 p-2 md:p-10 lg:order-2">
          <div className="relative h-[300px] overflow-hidden rounded-2xl bg-[#E7EEF5] md:h-[550px]">
            <Image src={introImage} alt="About" fill className="object-cover object-center" unoptimized />
            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-[#F7F9FB] to-transparent md:h-88" />
          </div>
        </div>
      </section>

      <section className="container mx-auto grid grid-cols-1 gap-5 px-4 md:grid-cols-[1.2fr_0.8fr] md:gap-20 md:[direction:ltr]">
        <div className={`relative overflow-hidden rounded-3xl bg-[#1277BE] p-8 text-white md:px-20 ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
          <div className={isArabic ? "md:pr-20" : "md:pl-20"}>
            <h3 className="mb-3 text-3xl font-semibold md:text-3xl">{loc("vision_title")}</h3>
            <p className="text-base leading-relaxed text-blue-50 md:text-xl md:leading-[1.9]">{loc("vision_body")}</p>
          </div>
          <div className={`pointer-events-none absolute hidden md:block ${isArabic ? "right-10 top-1/2 -translate-y-1/2" : "left-10 top-1/2 -translate-y-1/2"}`}>
            <FontAwesomeIcon icon={faLightbulb} className="text-6xl text-[#FDBA74]" />
          </div>
        </div>

        <div className={`relative overflow-hidden rounded-3xl bg-[#1277BE] p-8 text-white ${isArabic ? "text-right" : "text-left"}`} dir={isArabic ? "rtl" : "ltr"}>
          <div className={isArabic ? "pt-10 md:pr-10" : "pt-10 md:pl-20"}>
            <h3 className="mb-3 text-3xl font-semibold md:text-3xl">{loc("mission_title")}</h3>
            <p className="text-base leading-relaxed text-blue-50 md:text-xl md:leading-[1.9]">{loc("mission_body")}</p>
          </div>
          <div className={`pointer-events-none absolute hidden md:block ${isArabic ? "right-20 top-7" : "left-20 top-7"}`}>
            <FontAwesomeIcon icon={faBullseye} className="text-[38px] text-blue-100" />
          </div>
        </div>
      </section>

      <section className="container mx-auto px-4 py-16 md:py-20">
        <h2 className="text-center text-4xl font-semibold text-[#102233] md:text-4xl">{loc("why_title")}</h2>
        <p className="mt-3 text-center text-lg text-slate-500 md:text-lg">{loc("why_subtitle")}</p>
        <div className="mt-10 grid grid-cols-1 gap-12 md:grid-cols-3">

          <div className="text-center">
            <Image
              src="/assets/images/like.gif"
              alt="Icon"
              width={132}
              height={132}
              className="mx-auto mb-4 h-24 w-24 md:h-32 md:w-32"
              unoptimized
            />
            <h3 className="mb-3 text-2xl font-semibold text-[#102233] ">{loc("why_1_title")}</h3>
            <p className="text-base leading-relaxed text-slate-500 md:text-lg md:leading-[1.8]">{loc("why_1_body")}</p>
          </div>
          <div className="text-center">
            <Image
              src="/assets/images/team.gif"
              alt="Icon"
              width={132}
              height={132}
              className="mx-auto mb-4 h-24 w-24 md:h-32 md:w-32"
              unoptimized
            />
            <h3 className="mb-3 text-2xl font-semibold text-[#102233] ">{loc("why_2_title")}</h3>
            <p className="text-base leading-relaxed text-slate-500 md:text-lg md:leading-[1.8]">{loc("why_2_body")}</p>
          </div>
          <div className="text-center">
            <Image
              src="/assets/images/tag.gif"
              alt="Icon"
              width={132}
              height={132}
              className="mx-auto mb-4 h-24 w-24 md:h-32 md:w-32"
              unoptimized
            />
            <h3 className="mb-3 text-2xl font-semibold text-[#102233] ">{loc("why_3_title")}</h3>
            <p className="text-base leading-relaxed text-slate-500 md:text-lg md:leading-[1.8]">{loc("why_3_body")}</p>
          </div>

        </div>
      </section>

      <section className="pt-4">
        <DesktopCertificates />
      </section>
    </div>
  );
}
