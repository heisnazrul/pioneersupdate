"use client";

import Link from "next/link";
import { useMemo } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowRightArrowLeft, faCalendarDays, faClipboardCheck, faHeart, faStar } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { useApi } from "@/lib/api";
import { formatCurrency } from "@/lib/format";
import { normalizeImageUrl, pickBookingImage } from "@/lib/student-media";

export default function StudentDashboard() {
  const { direction, language, t } = useLocale();
  const isRtl = direction === "rtl";
  const isArabic = language === "ar";
  const loc = (key, fallback = "") => t(`pages.student.dashboard.${key}`, fallback);
  const { data: meData } = useApi("/courseenglish/student/me");
  const { data: bookingsData, loading: bookingsLoading } = useApi("/courseenglish/student/bookings");
  const { data: wishlistData } = useApi("/courseenglish/wishlist");
  const { data: compareData } = useApi("/courseenglish/compare");

  const wishlistCount = Array.isArray(wishlistData?.items) ? wishlistData.items.length : 0;
  const compareCount = Array.isArray(compareData?.items) ? compareData.items.length : 0;

  const user = meData?.data || {};
  const bookings = Array.isArray(bookingsData?.data) ? bookingsData.data : [];
  const recentBooking = bookings[0] || null;
  const firstName = (user?.name || "").trim().split(/\s+/)[0] || (isArabic ? "أحمد" : "Ahmed");

  const displayTitle = useMemo(() => {
    if (!recentBooking) return "-";
    if (isArabic) {
      const schoolAr =
        recentBooking?.school_ar_name ||
        recentBooking?.ar_school_name ||
        recentBooking?.ar_name ||
        recentBooking?.school_name;
      const cityAr =
        recentBooking?.city_ar_name ||
        recentBooking?.ar_city_name ||
        recentBooking?.city_name;
      const schoolEn = recentBooking?.school_name || recentBooking?.course_name || "-";
      return [schoolAr, cityAr, schoolEn].filter(Boolean).join(" - ");
    }
    return recentBooking?.school_name || recentBooking?.course_name || "-";
  }, [recentBooking, isArabic]);

  const stats = useMemo(() => {
    const confirmedCount = bookings.filter((b) =>
      ["confirmed", "approved"].includes(String(b?.status || "").toLowerCase())
    ).length;
    const pendingCount = bookings.filter((b) => String(b?.status || "").toLowerCase() === "pending").length;

    return [
      { key: "confirmed", icon: faClipboardCheck, label: loc("confirmed", "Confirmed Bookings"), value: confirmedCount, href: "/student/bookings" },
      { key: "pending", icon: faCalendarDays, label: loc("pending", "Pending Bookings"), value: pendingCount, href: "/student/bookings" },
      { key: "compare", icon: faArrowRightArrowLeft, label: loc("compare", "Compare List"), value: compareCount, href: "/compare" },
      { key: "wishlist", icon: faHeart, label: loc("wishlist", "Saved Institutes"), value: wishlistCount, href: "/student/wishlist" },
    ];
  }, [bookings, compareCount, wishlistCount, loc]);

  const align = isRtl ? "text-right" : "text-left";

  return (
    <div className="space-y-6">
      <section className={`rounded-3xl border border-slate-200 bg-white px-6 py-5 ${align}`}>
        <h1 className="text-3xl font-semibold leading-[1.1] text-[#102233] md:text-3xl">
          {loc("title", "Hello, {name} 👋").replace("{name}", firstName)}
        </h1>
        <p className="mt-4 text-md text-slate-500 md:text-lg">{loc("subtitle", "Quick view of your activity.")}</p>
      </section>

      <section className="grid grid-cols-2 gap-3 md:gap-4 xl:grid-cols-4">
        {stats.map((item) => (
          <Link
            key={item.key}
            href={item.href}
            className="flex flex-col items-center justify-center rounded-[20px] border border-slate-300 bg-white p-4 text-center transition hover:-translate-y-0.5 hover:shadow-md md:rounded-3xl md:p-5"
          >
            <span className="grid h-10 w-10 place-items-center rounded-full bg-[#E8F1F8] text-[#1277BE] md:h-12 md:w-12">
              <FontAwesomeIcon icon={item.icon} className="text-[14px] md:text-[16px]" />
            </span>
            <p className="mt-3 text-[12px] leading-tight text-slate-500 md:text-[14px]">{item.label}</p>
            <p className="mt-1 text-2xl font-semibold leading-none text-[#102233] md:text-4xl">{item.value}</p>
          </Link>
        ))}
      </section>

      <section className={`py-2 ${align}`}>
        <h2 className="mb-4 text-2xl font-semibold text-[#102233] md:text-3xl">{loc("recent_title", "Recent Activity")}</h2>
        <p className="mb-4 mt-1 text-lg text-slate-500">{loc("recent_sub", "Quick view of recent schools and courses.")}</p>

        {bookingsLoading ? (
          <div className="my-6 rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-400">...</div>
        ) : recentBooking ? (
          <div className="my-6">
            <Link
              href="/student/bookings"
              className="block w-full max-w-[320px] rounded-[18px] border border-[#C8D8E8] bg-white p-[8px] shadow-sm transition hover:shadow-md"
              dir={direction}
            >
              <div className="relative overflow-hidden rounded-[14px] border border-slate-200 bg-slate-100">
                <img
                  src={pickBookingImage(recentBooking)}
                  alt={recentBooking?.course_name || "Booking"}
                  className="h-[180px] w-full object-cover"
                  loading="lazy"
                  onError={(e) => {
                    e.currentTarget.onerror = null;
                    e.currentTarget.src = "/assets/hero.png";
                  }}
                />
                <div className="absolute right-2 top-2 flex flex-col items-end gap-1.5">
                  <span className="rounded-lg bg-[#A3AFBA] px-2.5 py-1 text-[12px] font-medium text-white">
                    {loc("top_rated", "Top Rated")}
                  </span>
                  <span className="rounded-lg bg-[#EA3944] px-2.5 py-1 text-[12px] font-medium text-white">
                    {loc("discount", "20% OFF")}
                  </span>
                </div>
              </div>

              <div className="mt-3 flex items-center justify-between">
                <div className="mt-2 flex items-center gap-2 text-[13px] text-slate-500">
                  {recentBooking?.country_flag ? (
                    <img
                      src={normalizeImageUrl(recentBooking.country_flag) || "/assets/flags/uk.svg"}
                      alt=""
                      className="h-4 w-6 object-cover"
                      loading="lazy"
                      onError={(e) => {
                        e.currentTarget.onerror = null;
                        e.currentTarget.src = "/assets/flags/uk.svg";
                      }}
                    />
                  ) : null}
                  <span>
                    {isArabic
                      ? recentBooking?.country_ar_name || recentBooking?.country_name || "-"
                      : recentBooking?.country_name || "-"}
                  </span>
                </div>
                <div className="mt-2 flex items-center gap-1 text-[#F6C33E]">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <FontAwesomeIcon
                      key={star}
                      icon={faStar}
                      className={`text-[13px] ${star <= Number(recentBooking?.rating || 4) ? "opacity-100" : "opacity-20"}`}
                    />
                  ))}
                </div>
              </div>

              <h3 className="mt-2 text-[18px] font-semibold leading-tight text-[#102233] md:text-[20px]">{displayTitle}</h3>
              <p className="mt-1 text-[13px] text-slate-500">
                {isArabic
                  ? recentBooking?.course_ar_name || recentBooking?.course_name || "-"
                  : recentBooking?.course_name || recentBooking?.course_ar_name || "-"}
              </p>

              <div className="my-6">
                <span className="rounded-2xl bg-[#E8F1F8] px-3 py-1.5 text-[12px] font-normal text-slate-600">
                  {isArabic
                    ? recentBooking?.course_type_ar || recentBooking?.course_ar_name || recentBooking?.course_type || recentBooking?.course_name || "-"
                    : recentBooking?.course_type || recentBooking?.course_name || recentBooking?.course_type_ar || recentBooking?.course_ar_name || "-"}
                </span>
              </div>

              <div className="my-3 flex items-center gap-2">
                <p className="text-xs font-semibold leading-none text-[#102233]">
                  {formatCurrency(recentBooking?.final_price || recentBooking?.total || 0, recentBooking?.currency || "SAR")}
                  <span className="text-xs font-normal text-slate-500"> {loc("per_week", "/ week")}</span>
                </p>
                <p className="text-xs text-slate-400 line-through">
                  {formatCurrency(
                    recentBooking?.original_price ||
                      Number(recentBooking?.final_price || recentBooking?.total || 0) * 1.2,
                    recentBooking?.currency || "SAR"
                  )}
                </p>
              </div>
            </Link>
          </div>
        ) : (
          <div className="mt-6 rounded-3xl border border-slate-200 bg-white p-8 text-center">
            <h3 className="text-[24px] font-semibold text-[#102233]">{loc("no_activity", "No activity yet")}</h3>
            <p className="mt-2 text-[15px] text-slate-500">{loc("no_activity_desc", "Start by saving schools or placing your first booking.")}</p>
          </div>
        )}
      </section>
    </div>
  );
}
