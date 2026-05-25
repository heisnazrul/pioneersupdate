"use client";

import { useLocale } from "@/components/providers/locale-provider";

export default function DesktopStats() {
  const { language, messages, direction } = useLocale();
  const isArabic = language === "ar";
  const stats = messages?.pages?.homepage?.stats ?? {};

  const heading =
    stats.heading || (isArabic ? "إنجازاتنا بالأرقام" : "Our achievements in numbers");
  const body =
    stats.body ||
    (isArabic
      ? "بفضل شركائنا الموثوقين والمؤسسات التعليمية الرائدة حول العالم، ساعدنا آلاف الطلاب على تحقيق حلمهم في تعلم الإنجليزية."
      : "Thanks to our trusted partners and leading educational institutions around the world, we’ve helped thousands of students achieve their dream of learning English in accredited international environments.");

  const items = [
    {
      value: stats.state_details?.students?.number || "+15,000",
      label: stats.state_details?.students?.title || (isArabic ? "طلاب ملتحقون ببرامج إنجليزية معتمدة بالخارج" : "Students enrolled in accredited English programs abroad"),
    },
    {
      value: stats.state_details?.partners?.number || "+50",
      label: stats.state_details?.partners?.title || (isArabic ? "جامعات شريكة تقدم برامج دراسة اللغة الإنجليزية بالخارج" : "Partner universities offering accredited English study programs abroad"),
    },
  ];

  return (
    <section className="w-full bg-white py-16" dir={direction}>
      <div className="mx-auto flex flex-row items-center justify-between gap-16 px-4 md:px-10 xl:px-20 2xl:px-40">

        {/* TEXT COLUMN: Left in LTR, Right in RTL */}
        <div className="max-w-xl flex-1 text-start">
          <h2 className="text-4xl lg:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
            {heading}
          </h2>
          <p className="mt-4 text-sm lg:text-base text-slate-500 leading-relaxed">
            {body}
          </p>
        </div>

        {/* STATS COLUMN: Right in LTR, Left in RTL */}
        <div className="flex gap-16 text-start shrink-0">
          {items.map((item, index) => (
            <div key={index} className="max-w-[240px]">
              <p className="text-3xl lg:text-4xl font-bold text-[#135FAE]">
                {item.value}
              </p>
              <p className="mt-3 text-sm lg:text-base text-slate-600 leading-snug">
                {item.label}
              </p>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
}
