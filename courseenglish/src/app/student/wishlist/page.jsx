"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import InstituteCard from "@/app/components/InstituteCard";
import OnlineCourseCard from "@/app/components/OnlineCourseCard";
import SummerCampCard from "@/app/components/SummerCampCard";
import ProfessionalCourseCard from "@/app/components/ProfessionalCourseCard";
import { buildApiUrl, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function WishlistPage() {
  const { isArabic, currency } = useCourseEnglishSettings();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [removing, setRemoving] = useState(false);

  const t = useMemo(
    () =>
      isArabic
        ? {
            title: "المفضلة",
            subtitle: "احفظ المعاهد التي تهمك للرجوع إليها لاحقاً ومقارنتها بسهولة.",
            empty: "لا توجد عناصر في المفضلة",
            emptySub: "ابدأ بإضافة المعاهد من صفحات النتائج.",
            explore: "تصفح المعاهد",
          }
        : {
            title: "Wishlist",
            subtitle: "Save institutes to revisit and compare later.",
            empty: "No wishlist items",
            emptySub: "Start adding schools from listing pages.",
            explore: "Explore",
          },
    [isArabic]
  );

  const authHeaders = () => {
    if (typeof window === "undefined") return null;
    const token = localStorage.getItem("auth_token");
    if (!token) return null;
    const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
    return {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `${tokenType} ${token}`,
    };
  };

  const removeFromWishlist = async (courseId, courseType) => {
    const headers = authHeaders();
    if (!headers) return false;
    try {
      setRemoving(true);
      const res = await fetch(buildApiUrl("/courseenglish/wishlist/remove"), {
        method: "POST",
        headers,
        body: JSON.stringify({ course_type: courseType, course_id: Number(courseId) }),
      });
      const ok = res.ok;
      if (ok) {
        setItems((prev) =>
          prev.filter(
            (it) =>
              !(
                Number(it.course_id) === Number(courseId) &&
                normalizeType(it.course_type || it.course?.course_type) === normalizeType(courseType)
              )
          )
        );
      }
      return ok;
    } catch {
      return false;
    } finally {
      setRemoving(false);
    }
  };

  useEffect(() => {
    let ignore = false;
    async function load() {
      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
      if (!token) {
        setLoading(false);
        return;
      }
      try {
        const res = await fetch(buildApiUrl("/courseenglish/wishlist"), {
          headers: { Accept: "application/json", Authorization: `${tokenType} ${token}` },
          cache: "no-store",
        });
        const json = await res.json();
        if (!ignore) setItems(Array.isArray(json?.items) ? json.items : []);
      } catch {
        if (!ignore) setItems([]);
      } finally {
        if (!ignore) setLoading(false);
      }
    }
    load();
    return () => {
      ignore = true;
    };
  }, []);

  if (loading) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>;
  }

  return (
    <div className="space-y-6" dir={isArabic ? "rtl" : "ltr"}>
      <section >
        <h1 className="text-3xl font-semibold text-[#102233]">
          {t.title} ({items.length})
        </h1>
        <p className="mt-2 text-xl text-slate-500">{t.subtitle}</p>
      </section>

      {items.length === 0 ? (
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center">
          <h3 className="text-[30px] font-semibold text-[#102233]">{t.empty}</h3>
          <p className="mt-2 text-[18px] text-slate-500">{t.emptySub}</p>
          <Link
            href="/language-institutes"
            className="mt-5 inline-block rounded-2xl bg-[#1277BE] px-8 py-4 text-[18px] font-medium text-white"
          >
            {t.explore}
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          {items.map((item) => (
            <WishlistCard
              key={`${item.course_type}:${item.course_id}`}
              item={item}
              isArabic={isArabic}
              currency={currency}
              onRemove={removeFromWishlist}
            />
          ))}
        </div>
      )}
    </div>
  );
}

function normalizeType(value) {
  const raw = String(value || "").toLowerCase().replace(/-/g, "_");
  if (["language_courses", "language_course", "language_school_course", "language_school_courses"].includes(raw)) return "language_courses";
  if (["summer_camps", "summer_camp", "language_course_summer_camp", "language_course_summer_camps"].includes(raw)) return "summer_camps";
  if (["online_courses", "online_course", "language_course_online_course", "language_course_online_courses"].includes(raw)) return "online_courses";
  if (["training_courses", "training_course", "language_course_training_course", "language_course_training_courses"].includes(raw)) return "training_courses";
  return raw || "language_courses";
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

function WishlistCard({ item, isArabic, currency, onRemove }) {
  const course = item?.course || {};
  const type = item?.course_type || course?.course_type || "language_courses";

  if (type === "online_courses") {
    const courseData = {
      id: course.id,
      title: isArabic ? course.ar_name || course.title || course.name : course.title || course.name || course.ar_name,
      provider: isArabic ? course.provider_ar_name || course.provider_name || course.provider : course.provider_name || course.provider,
      country: isArabic ? course.country_ar_name || course.country_name : course.country_name || course.country_ar_name,
      flag: course.flag,
      priceValue:
        (currency === "SAR" ? course.price_new_sar ?? course.price_sar : course.price_new_gbp ?? course.price_gbp) ??
        course.price_new ??
        course.price,
      currency: course.currency || course.currency_code || currency || "SAR",
      priceUnit: course.price_unit,
      image: course.thumbnail || course.image || "/assets/hero.png",
      slug: course.slug,
      tag: course.tag,
      tag_ar_name: course.tag_ar_name,
    };
    return (
      <OnlineCourseCard
        course={courseData}
        isArabic={isArabic}
        variant="wishlist"
        onRemove={(id) => onRemove?.(id, "online_courses")}
      />
    );
  }

  if (type === "summer_camps") {
    const program = {
      id: course.id,
      title: isArabic ? course.ar_title || course.title || course.name : course.title || course.name || course.ar_title,
      city: isArabic ? course.city_ar_name || course.city_name || course.city : course.city_name || course.city || course.city_ar_name,
      country: isArabic ? course.country_ar_name || course.country_name : course.country_name || course.country_ar_name,
      priceFromValue:
        (currency === "SAR" ? course.price_from_sar ?? course.price_sar : course.price_from_gbp ?? course.price_gbp) ??
        course.price_from ??
        course.price,
      currency: course.currency || course.currency_code || currency || "SAR",
      image: course.thumbnail || course.image || "/assets/hero.png",
      flag: course.flag,
      slug: course.slug,
      tag: course.tag,
      tag_ar_name: course.tag_ar_name,
    };
    return (
      <SummerCampCard
        program={program}
        isArabic={isArabic}
        variant="wishlist"
        onRemove={(id) => onRemove?.(id, "summer_camps")}
      />
    );
  }

  if (type === "training_courses") {
    const prof = {
      id: course.id,
      title: isArabic ? course.ar_title || course.title || course.name : course.title || course.name || course.ar_title,
      provider: isArabic ? course.provider_ar_name || course.provider_name || course.provider : course.provider_name || course.provider,
      category: isArabic ? course.category_ar_name || course.category : course.category,
      subject: isArabic ? course.subject_ar || course.subject : course.subject || course.subject_ar,
      location: course.location,
      duration: course.duration,
      priceValue:
        (currency === "SAR" ? course.price_sar ?? course.price : course.price_gbp ?? course.price) ?? course.price,
      currency: course.currency || course.currency_code || currency || "SAR",
      image: course.thumbnail || course.image || "/assets/hero.png",
      slug: course.slug,
    };
    return (
      <ProfessionalCourseCard course={prof} isArabic={isArabic} onRemove={(id) => onRemove?.(id, "training_courses")} />
    );
  }

  // default language_courses
  const pickImage = () => {
    const candidates = [
      course.image,
      course.branch_image,
      course.gallery_image,
      course.thumbnail,
      course.logo,
      course.school_logo,
      course.gallery_urls?.[0],
      course.gallery?.[0],
      course.branch_gallery_urls?.[0],
      course.branch?.gallery_urls?.[0],
    ];
    for (const src of candidates) {
      const url = normalizeImageUrl(src);
      if (url) return url;
    }
    return "/assets/hero.png";
  };

  const institute = {
    ...course,
    id: course.id,
    // Use school name + city for the card title
    name: course.school_name,
    ar_name: course.school_ar_name,
    city_name: course.city_name || course.city,
    city_ar_name: course.city_ar_name || course.city_name,
    country_name: course.country_name,
    country_ar_name: course.country_ar_name,
    location: isArabic ? course.country_ar_name || course.country_name : course.country_name || course.country_ar_name,
    flag: course.flag,
    image: pickImage(),
    price_sar: course.price_sar ?? course.price ?? course.price_per_week_sar,
    price_gbp: course.price_gbp ?? course.price_per_week_gbp,
    old_price_sar: course.old_price_sar,
    old_price_gbp: course.old_price_gbp,
    tag: course.tag,
    tag_ar: course.tag_ar_name || course.tag_ar,
    // Show the actual course name in the badge instead of the raw type key
    course_type: isArabic ? course.ar_name || course.name : course.name || course.ar_name,
    course_type_ar: course.ar_name || course.name,
    course_name: course.name,
    course_ar_name: course.ar_name || course.name,
  };

  return (
    <InstituteCard
      institute={institute}
      variant="wishlist"
      type="language_courses"
      onRemove={(id) => onRemove?.(id, "language_courses")}
      searchParamsOverride={{
        weeks: course.weeks_param,
        start_date: course.start_date_param,
      }}
    />
  );
}
