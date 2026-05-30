"use client";

import Link from "next/link";
import { useCallback, useEffect, useMemo, useState } from "react";

import InstituteCard from "@/components/shared/institute-card";
import { useLocale } from "@/components/providers/locale-provider";
import { fetchApiJson, getImageUrl, useApi } from "@/lib/api";
import { useCourseEnglishInteractions } from "@/lib/interactions";

function normalizeImageUrl(value) {
  if (!value || typeof value !== "string") return "";
  const raw = value.trim();
  if (!raw) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://")) return raw;
  if (raw.startsWith("/")) return getImageUrl(raw) || raw;
  if (raw.startsWith("assets/")) return `/${raw}`;
  return getImageUrl(`/storage/${raw}`) || "";
}

function mapWishlistItem(item) {
  const course = item?.course || {};
  if (!course?.id) return null;

  const pickImage = () => {
    const candidates = [course.image, course.logo, course.thumbnail, course.school_logo];
    for (const src of candidates) {
      const url = normalizeImageUrl(src);
      if (url) return url;
    }
    return "/assets/hero.png";
  };

  return {
    item,
    institute: {
      ...course,
      id: course.id,
      slug: course.school_slug || course.slug || course.id,
      school_slug: course.school_slug || course.slug,
      school_name_en: course.school_name || course.name,
      school_name_ar: course.school_ar_name || course.ar_name,
      city_name: course.city_name || course.city,
      city_ar_name: course.city_ar_name || course.city_ar,
      country_en: course.country_name || course.country,
      country_ar: course.country_ar_name,
      flag: course.flag || course.country_flag,
      image: pickImage(),
      price_sar: course.price_sar ?? course.price,
      price_gbp: course.price_gbp ?? course.price,
      old_price_sar: course.old_price_sar,
      old_price_gbp: course.old_price_gbp,
      course_name: course.name,
      course_ar_name: course.ar_name || course.name,
      course_type: course.course_type || course.tag,
      course_type_ar: course.course_type_ar || course.tag_ar_name,
      tag: course.tag,
      tag_ar: course.tag_ar_name,
      rating: course.rating,
      level: course.level || course.required_level,
      lessons: course.lessons_per_week || course.lessons,
      hours: course.hours || course.hours_per_week || course.study_time,
    },
  };
}

export default function StudentWishlist() {
  const { direction, t } = useLocale();
  const loc = (key, fallback = "") => t(`pages.student.wishlist.${key}`, fallback);
  const { data, loading, error } = useApi("/courseenglish/wishlist");
  const { reloadInteractions } = useCourseEnglishInteractions();
  const [items, setItems] = useState(null);

  useEffect(() => {
    if (Array.isArray(data?.items)) {
      setItems(data.items);
    }
  }, [data?.items]);

  const list = useMemo(
    () => (items ?? data?.items ?? []).map(mapWishlistItem).filter(Boolean),
    [items, data?.items]
  );

  const removeFromWishlist = useCallback(async (courseId, courseType) => {
    try {
      const res = await fetchApiJson("/courseenglish/wishlist/remove", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ course_type: courseType, course_id: Number(courseId) }),
      });
      if (res?.success !== false) {
        setItems((prev) => {
          const current = prev ?? (Array.isArray(data?.items) ? data.items : []);
          return current.filter(
            (it) => !(Number(it.course_id) === Number(courseId) && String(it.course_type) === String(courseType))
          );
        });
        await reloadInteractions();
        return true;
      }
    } catch {
      // ignore
    }
    return false;
  }, [data?.items, reloadInteractions]);

  if (loading && items === null) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">{loc("loading", "Loading...")}</div>;
  }

  if (error) {
    return (
      <div className="rounded-3xl border border-red-200 bg-red-50 p-8 text-center text-red-700">
        {loc("error", "Failed to load wishlist")}
      </div>
    );
  }

  return (
    <div className="space-y-6" dir={direction}>
      <section>
        <h1 className="text-3xl font-semibold text-[#102233]">
          {loc("title", "Wishlist")} ({list.length})
        </h1>
        <p className="mt-2 text-xl text-slate-500">{loc("subtitle", "Save institutes to revisit and compare later.")}</p>
      </section>

      {list.length === 0 ? (
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center">
          <h3 className="text-[30px] font-semibold text-[#102233]">{loc("empty", "No wishlist items")}</h3>
          <p className="mt-2 text-[18px] text-slate-500">{loc("empty_sub", "Start adding schools from listing pages.")}</p>
          <Link
            href="/language-institutes"
            className="mt-5 inline-block rounded-2xl bg-[#1277BE] px-8 py-4 text-[18px] font-medium text-white"
          >
            {loc("explore", "Explore")}
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          {list.map(({ item, institute }) => (
            <InstituteCard
              key={`${item.course_type}:${item.course_id}`}
              institute={institute}
              variant="wishlist"
              type={item.course_type || "language_courses"}
              onRemove={(id, courseType) => removeFromWishlist(id, courseType || item.course_type || "language_courses")}
            />
          ))}
        </div>
      )}
    </div>
  );
}
