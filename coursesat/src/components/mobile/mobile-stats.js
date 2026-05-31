"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUserGraduate, faUniversity } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";

export default function MobileStats() {
  const { language, messages, direction } = useLocale();
  const isArabic = language === "ar";
  const stats = messages?.pages?.homepage?.stats ?? {};

  const heading =
    stats.mobile_heading ||
    stats.heading ||
    (isArabic ? "إنجازاتنا بالأرقام" : "Our achievements in numbers");

  const mobileItems = [
    {
      value: stats.state_details?.students?.number || "+15,000",
      label: stats.state_details?.students?.title_mobile || (isArabic ? "طالب وطالبة" : "Students"),
      icon: faUserGraduate,
    },
    {
      value: stats.state_details?.partners?.number || "+50",
      label: stats.state_details?.partners?.title_mobile || (isArabic ? "شراكة مع جامعات" : "Partner universities"),
      icon: faUniversity,
    },
  ];

  return (
    <section className="w-full bg-[#E8F3FC] py-8" dir={direction}>
      <div className="mx-auto w-full px-4">
        {/* Mobile heading */}
        <h2 className="text-center text-2xl font-bold leading-snug text-slate-900 px-8">
          {heading}
        </h2>

        {/* Cards Row */}
        <div className="mt-5 grid grid-cols-2 gap-3" dir={direction}>
          {mobileItems.map((item, index) => (
            <div
              key={index}
              className="rounded-xl bg-white px-4 py-5 shadow-[0_12px_30px_rgba(15,23,42,0.08)] text-start"
            >
              <div className="mb-3 flex justify-start">
                <span className="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#E3F0FF] text-[#135FAE]">
                  <FontAwesomeIcon icon={item.icon} className="h-4 w-4" />
                </span>
              </div>
              <p className="text-sm font-medium text-slate-500 truncate">
                {item.label}
              </p>
              <p className="mt-1 text-xl font-bold text-slate-900">
                {item.value}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
