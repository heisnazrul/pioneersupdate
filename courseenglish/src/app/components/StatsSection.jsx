// components/StatsSection.js
"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUserGraduate, faUniversity } from "@fortawesome/free-solid-svg-icons";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function StatsSection() {
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const stats = getCourseEnglishMessages(language)?.pages?.homepage?.stats ?? {};
  const statsMobile = stats;

  const heading =
    stats?.heading || (isArabic ? "إنجازاتنا بالأرقام" : "Our achievements in numbers");
  const body =
    stats?.body ||
    (isArabic
      ? "بفضل شركائنا الموثوقين والمؤسسات التعليمية الرائدة حول العالم، ساعدنا آلاف الطلاب على تحقيق حلمهم في تعلم الإنجليزية."
      : "Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.");

  const items = [
    {
      value: stats?.state_details?.students?.number,
      label: stats?.state_details?.students?.title,
      ar_label: stats?.state_details?.students?.title,
    },
    {
      value: stats?.state_details?.partners?.number,
      label: stats?.state_details?.partners?.title,
      ar_label: stats?.state_details?.partners?.title,
    },
  ];
  const mobileItems = [
    {
      value: stats?.state_details?.students?.number,
      label: stats?.state_details?.students?.title_mobile,
      ar_label: stats?.state_details?.students?.title_mobile,
    },
    {
      value: stats?.state_details?.partners?.number,
      label: stats?.state_details?.partners?.title_mobile,
      ar_label: stats?.state_details?.partners?.title_mobile,
    },
  ];

  const headingMobile =
    statsMobile?.heading || heading;
  const labelFor = (item, fallbackEn, fallbackAr) =>
    item?.label || item?.ar_label || (isArabic ? fallbackAr : fallbackEn) || fallbackEn || fallbackAr;

  return (
    <section className="w-full bg-white">
      {/* ===== DESKTOP / LARGE ===== */}
      <div className="hidden md:block">
        <div className="mx-auto flex items-center justify-between gap-16 px-4 md:px-20 lg:px-28 xl:px-40 py-16">
          {/* Heading + description (LTR mirror of Arabic) */}
          <div className="max-w-xl">
            <h2 className="text-3xl lg:text-6xl font-extrabold tracking-tight text-slate-900">
              {heading}
            </h2>
            <p className="mt-3 text-sm lg:text-base text-slate-600 leading-relaxed">
              {body}
            </p>
          </div>

          {/* Stats (mirroring the Arabic layout) */}
          <div className="flex  gap-10">
            {/* +15,000 students */}
            <div>
              <p className="text-4xl lg:text-5xl font-extrabold text-[#135FAE]">
                {items[0]?.value || "+15,000"}
              </p>
              <p className="mt-2 max-w-xs text-sm lg:text-base text-slate-700 leading-snug">
                {labelFor(
                  items[0],
                  "Students enrolled in accredited English programs abroad",
                  "طلاب ملتحقون ببرامج إنجليزية معتمدة بالخارج"
                )}
              </p>
            </div>

            {/* +50 universities */}
            <div>
              <p className="text-4xl lg:text-5xl font-extrabold text-[#135FAE]">
                {items[1]?.value || "+50"}
              </p>
              <p className="mt-2 max-w-xs text-sm lg:text-base text-slate-700 leading-snug">
                {labelFor(
                  items[1],
                  "Partner universities offering accredited English study programs abroad",
                  "جامعات شريكة تقدم برامج دراسة اللغة الإنجليزية بالخارج"
                )}
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* ===== MOBILE / SMALL ===== */}
      <div className="md:hidden bg-[#E8F3FC] py-8">
        <div className="mx-auto w-full  px-4">
          {/* Mobile heading (centered, 2 lines like design) */}
          <h2 className="text-center text-2xl font-extrabold leading-snug text-slate-900">
            {headingMobile}
          </h2>

          {/* Cards row */}
          <div className="mt-5 grid grid-cols-2 gap-3">
            {/* Students card */}
            <div className="rounded-xl bg-white px-4 py-5  shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
              <div className="mb-3 flex ">
                <span className="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#E3F0FF] text-[#135FAE]">
                  <FontAwesomeIcon icon={faUserGraduate} className="h-4 w-4" />
                </span>
              </div>
              <p className="text-lg text-slate-600">
                {labelFor(mobileItems[0], "Students", "طالب وطالبة")}
              </p>
              <p className="mt-1 text-xl font-extrabold text-slate-900">
                {mobileItems[0]?.value || items[0]?.value || "+15,000"}
              </p>
            </div>

            {/* Universities card */}
            <div className="rounded-xl bg-white px-4 py-5  shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
              <div className="mb-3 flex ">
                <span className="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#E3F0FF] text-[#135FAE]">
                  <FontAwesomeIcon icon={faUniversity} className="h-4 w-4" />
                </span>
              </div>
              <p className="text-lg text-slate-600">
                {labelFor(mobileItems[1], "Partner universities", "شراكة مع جامعات")}
              </p>
              <p className="mt-1 text-xl font-extrabold text-slate-900">
                {mobileItems[1]?.value || items[1]?.value || "+50"}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
