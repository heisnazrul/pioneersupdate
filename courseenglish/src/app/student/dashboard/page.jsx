"use client";

import Link from "next/link";
import { useMemo } from "react";
import { useApi, formatCurrency, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

export default function StudentDashboardPage() {
  const { isArabic } = useCourseEnglishSettings();
  const { wishlistCount, compareCount } = useCourseEnglishInteractions();
  const { data: meData } = useApi("/student/me");
  const { data: bookingsData, loading: bookingsLoading } = useApi("/student/bookings");

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

  const t = isArabic
        ? {
            title: `مرحباً، ${firstName} 👋`,
            subtitle: "نظرة سريعة على نشاطك، المفضلة، المقارنات وحالة طلباتك.",
        confirmed: "الحجوزات المؤكدة",
        pending: "الحجوزات قيد المتابعة",
        compare: "قائمة المقارنة",
        wishlist: "المعاهد التي قمت بحفظها",
            recentTitle: "آخر نشاط",
            recentSub: "عرض سريع لآخر المعاهد والدورات التي تفاعلت معها وتصفحتها",
            topRated: "أعلى تقييم",
            discount: "خصم %20",
            noActivity: "لا يوجد نشاط بعد",
            noActivityDesc: "ابدأ بإضافة المعاهد للمفضلة أو إتمام أول حجز.",
          }
    : {
        title: `Hello, ${firstName} 👋`,
        subtitle: "Quick view of your activity, wishlist, compare list and booking status.",
        confirmed: "Confirmed Bookings",
        pending: "Pending Bookings",
        compare: "Compare List",
        wishlist: "Saved Institutes",
            recentTitle: "Recent Activity",
            recentSub: "Quick view of the schools and courses you recently viewed.",
            topRated: "Top Rated",
            discount: "20% OFF",
            noActivity: "No activity yet",
            noActivityDesc: "Start by saving schools or placing your first booking.",
          };

  const align = isArabic ? "text-right" : "text-left";

  const stats = useMemo(() => {
    const confirmedCount = bookings.filter((b) => ["confirmed", "approved"].includes(String(b?.status || "").toLowerCase())).length;
    const pendingCount = bookings.filter((b) => String(b?.status || "").toLowerCase() === "pending").length;

    return [
      { key: "confirmed", icon: "fa-clipboard-check", label: t.confirmed, value: confirmedCount, href: "/student/bookings" },
      { key: "pending", icon: "fa-calendar-days", label: t.pending, value: pendingCount, href: "/student/bookings" },
      { key: "compare", icon: "fa-arrow-right-arrow-left", label: t.compare, value: compareCount, href: "/student/compare" },
      { key: "wishlist", icon: "fa-heart", label: t.wishlist, value: wishlistCount, href: "/student/wishlist" },
    ];
  }, [bookings, compareCount, wishlistCount, t]);

  return (
    <div className="space-y-6">
      <section className={`rounded-3xl border border-slate-200 bg-white px-6 py-5 ${align}`}>
        <h1 className="text-3xl font-semibold leading-[1.1] text-[#102233] md:text-3xl">{t.title}</h1>
        <p className="mt-4 text-md text-slate-500 md:text-lg">{t.subtitle}</p>
      </section>

      <section className="grid grid-cols-2 gap-3 md:gap-4 xl:grid-cols-4">
        {stats.map((item) => (
          <Link
            key={item.key}
            href={item.href}
            className="rounded-[20px] md:rounded-3xl border border-slate-300 bg-white p-4 md:p-5 text-center transition hover:-translate-y-0.5 hover:shadow-md flex flex-col items-center justify-center"
          >
            <span className="grid h-10 w-10 md:h-12 md:w-12 place-items-center rounded-full bg-[#E8F1F8] text-[#1277BE]">
              <i className={`fa-solid ${item.icon} text-[14px] md:text-[16px]`} />
            </span>
            <p className="mt-3 text-[12px] md:text-[14px] leading-tight text-slate-500">{item.label}</p>
            <p className="mt-1 text-2xl md:text-4xl font-semibold leading-none text-[#102233]">{item.value}</p>
          </Link>
        ))}
      </section>

      <section className={`py-2 ${align}`}>
        <h2 className="mb-4 text-2xl font-semibold text-[#102233] md:text-3xl">{t.recentTitle}</h2>
        <p className="mt-1 mb-4 text-lg text-slate-500">{t.recentSub}</p>

        {bookingsLoading ? (
          <div className="my-6 rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-400">...</div>
        ) : recentBooking ? (
          <div className="my-6">
            <Link
              href="/student/bookings"
              className="block w-full max-w-[320px] rounded-[18px] border border-[#C8D8E8] bg-white p-[8px] shadow-sm transition hover:shadow-md"
              dir={isArabic ? "rtl" : "ltr"}
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
                  <span className="rounded-lg bg-[#A3AFBA] px-2.5 py-1 text-[12px] font-medium text-white">{t.topRated}</span>
                  <span className="rounded-lg bg-[#EA3944] px-2.5 py-1 text-[12px] font-medium text-white">{t.discount}</span>
                </div>
              </div>

              <div className="mt-3 flex items-center justify-between">
                <div className="mt-2 flex items-center gap-2 text-[13px] text-slate-500">
                {recentBooking?.country_flag ? (
                  <img
                    src={normalizeImageUrl(recentBooking.country_flag) || "/assets/flags/uk.svg"}
                    alt="flag"
                    className="h-4 w-6 object-cover"
                    loading="lazy"
                    onError={(e) => {
                      e.currentTarget.onerror = null;
                      e.currentTarget.src = "/assets/flags/uk.svg";
                    }}
                  />
                ) : null}
                <span>{isArabic ? recentBooking?.country_ar_name || recentBooking?.country_name || "-" : recentBooking?.country_name || "-"}</span>
              </div>

              <div className="mt-2 flex items-center gap-1 text-[#F6C33E]">
                {[1, 2, 3, 4, 5].map((star) => (
                  <i
                    key={star}
                    className={`fa-solid fa-star text-[13px] ${star <= Number(recentBooking?.rating || 4) ? "opacity-100" : "opacity-20"}`}
                  />
                ))}
              </div>
              </div>

              <h3 className="mt-2 text-[18px] font-semibold leading-tight text-[#102233] md:text-[20px]">
                {displayTitle}
              </h3>
              <p className="mt-1 text-[13px] text-slate-500">
                {isArabic ? recentBooking?.course_ar_name || recentBooking?.course_name || "-" : recentBooking?.course_name || recentBooking?.course_ar_name || "-"}
              </p>

              <div className="my-6">
                <span className="rounded-2xl bg-[#E8F1F8] px-3 py-1.5 text-[12px] font-normal text-slate-600">
                  {isArabic
                    ? recentBooking?.course_type_ar || recentBooking?.course_ar_name || recentBooking?.course_type || recentBooking?.course_name || "-"
                    : recentBooking?.course_type || recentBooking?.course_name || recentBooking?.course_type_ar || recentBooking?.course_ar_name || "-"}
                </span>
              </div>

              <div className="my-3 flex items-center gap-2">
                <p className="text-xs font-semibold leading-none text-[#102233] ">
                  {formatCurrency(
                    recentBooking?.final_price || recentBooking?.total || 0,
                    recentBooking?.currency || "SAR"
                  )}
                  <span className="text-xs font-normal text-slate-500">
                    {" "}
                    {isArabic ? "/ الأسبوع" : "/ week"}
                  </span>
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
            <h3 className="text-[24px] font-semibold text-[#102233]">{t.noActivity}</h3>
            <p className="mt-2 text-[15px] text-slate-500">{t.noActivityDesc}</p>
          </div>
        )}
      </section>
    </div>
  );
}

function normalizeImageUrl(value) {
  if (!value || typeof value !== "string") return "";
  const raw = value.trim();
  if (!raw) return "";
  if (/\/storage\/[a-z]$/i.test(raw)) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://")) return raw;
  if (raw.startsWith("data:")) return raw;
  if (raw.startsWith("/")) return getImageUrl(raw) || "";
  if (raw.startsWith("storage/")) return getImageUrl(`/${raw}`) || "";
  if (raw.startsWith("assets/")) return `/${raw}`;
  return getImageUrl(`/storage/${raw}`) || "";
}

function pickBookingImage(item) {
  const candidates = [
    item?.course_image,
    item?.school_image,
    item?.school_logo,
    item?.branch_image,
    item?.image,
    item?.thumbnail,
    item?.logo,
    item?.gallery_image,
    item?.gallery_url,
    item?.course?.image,
    item?.course?.thumbnail,
    item?.course?.logo,
    item?.institute_image,
    item?.institute_logo,
    item?.main_image,
    item?.featured_image,
    item?.banner,
    item?.cover,
    Array.isArray(item?.course?.gallery) ? item.course.gallery[0] : null,
    Array.isArray(item?.gallery) ? item.gallery[0] : null,
    item?.course?.gallery?.[0]?.url,
    item?.gallery?.[0]?.url,
  ];
  for (const src of candidates) {
    const url = normalizeImageUrl(src);
    if (url) return url;
  }
  return "/assets/hero.png";
}
