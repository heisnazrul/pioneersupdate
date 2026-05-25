"use client";

import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBullseye, faLightbulb, faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import MobileCertificates from "./mobile-certificates";
// import { useApi } from "@/lib/api";

export default function MobileAbout() {
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  
  // Try fetching API, fallback to locale if no data
  // const { data } = useApi("/courseenglish/about");
  const data = null;
  
  const loc = (key) => t(`pages.about_us.${key}`);
  
  const introImage = "/assets/images/img.png";

  return (
    <div className="bg-[#F7F9FB] pb-10" dir={isArabic ? "rtl" : "ltr"}>
      <section className="container mx-auto px-4 pt-10">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs text-slate-500 shadow-sm">
          <span>{loc("breadcrumb_home")}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-2.5 w-2.5 ${isArabic ? "rotate-180" : ""}`} />
          <span>{loc("breadcrumb_current")}</span>
        </div>
        <h1 className="mb-8 text-center text-3xl font-semibold text-[#102233]">
          {loc("page_title")}
        </h1>
      </section>

      <section className="container mx-auto flex flex-col gap-6 px-4 py-6">
        <div className="order-1">
          <div className="relative h-[240px] w-full overflow-hidden rounded-2xl bg-[#E7EEF5]">
            <Image src={introImage} alt="About" fill className="object-cover object-center" unoptimized />
            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#F7F9FB] to-transparent" />
          </div>
        </div>
        <div className="order-2 text-center md:text-start">
          <h2 className="mb-4 text-xl font-semibold leading-snug text-[#102233]">{loc("intro_title")}</h2>
          <p className="mb-3 text-sm leading-relaxed text-slate-600">{loc("intro_p1")}</p>
          <p className="text-sm leading-relaxed text-slate-600">{loc("intro_p2")}</p>
        </div>
      </section>

      <section className="container mx-auto flex flex-col gap-4 px-4 py-6">
        <div className="relative overflow-hidden rounded-2xl bg-[#1277BE] p-6 text-white text-center md:text-start">
          <div>
            <h3 className="mb-2 text-2xl font-semibold">{loc("vision_title")}</h3>
            <p className="text-sm leading-relaxed text-blue-50">{loc("vision_body")}</p>
          </div>
        </div>

        <div className="relative overflow-hidden rounded-2xl bg-[#1277BE] p-6 text-white text-center md:text-start">
          <div>
            <h3 className="mb-2 text-2xl font-semibold">{loc("mission_title")}</h3>
            <p className="text-sm leading-relaxed text-blue-50">{loc("mission_body")}</p>
          </div>
        </div>
      </section>

      <section className="container mx-auto px-4 py-10">
        <h2 className="text-center text-2xl font-semibold text-[#102233]">{loc("why_title")}</h2>
        <p className="mt-2 text-center text-sm text-slate-500">{loc("why_subtitle")}</p>
        <div className="mt-8 flex flex-col gap-8">
          
          <div className="text-center">
            <Image
              src="/assets/images/like.gif"
              alt="Icon"
              width={80}
              height={80}
              className="mx-auto mb-3 h-20 w-20"
              unoptimized
            />
            <h3 className="mb-2 text-xl font-semibold text-[#102233]">{loc("why_1_title")}</h3>
            <p className="text-sm leading-relaxed text-slate-500">{loc("why_1_body")}</p>
          </div>
          <div className="text-center">
            <Image
              src="/assets/images/team.gif"
              alt="Icon"
              width={80}
              height={80}
              className="mx-auto mb-3 h-20 w-20"
              unoptimized
            />
            <h3 className="mb-2 text-xl font-semibold text-[#102233]">{loc("why_2_title")}</h3>
            <p className="text-sm leading-relaxed text-slate-500">{loc("why_2_body")}</p>
          </div>
          <div className="text-center">
            <Image
              src="/assets/images/tag.gif"
              alt="Icon"
              width={80}
              height={80}
              className="mx-auto mb-3 h-20 w-20"
              unoptimized
            />
            <h3 className="mb-2 text-xl font-semibold text-[#102233]">{loc("why_3_title")}</h3>
            <p className="text-sm leading-relaxed text-slate-500">{loc("why_3_body")}</p>
          </div>

        </div>
      </section>

      <section className="pt-2">
        <MobileCertificates />
      </section>
    </div>
  );
}
