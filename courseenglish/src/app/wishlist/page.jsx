"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHeart } from "@fortawesome/free-solid-svg-icons";
import { buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import InstituteCard from "@/app/components/InstituteCard";
import OnlineCourseCard from "@/app/components/OnlineCourseCard";
import SummerCampCard from "@/app/components/SummerCampCard";
import ProfessionalCourseCard from "@/app/components/ProfessionalCourseCard";

const typeLabels = {
  language_courses: { en: "Language Courses", ar: "دورات اللغة" },
  online_courses: { en: "Online Courses", ar: "دورات أونلاين" },
  summer_camps: { en: "Summer Camps", ar: "المخيمات الصيفية" },
  training_courses: { en: "Training Courses", ar: "الدورات التدريبية" },
};

const wishlistTypeOrder = [
  "language_courses",
  "summer_camps",
  "online_courses",
  "training_courses",
];

function getAuthHeaders() {
  if (typeof window === "undefined") return null;
  const token = localStorage.getItem("auth_token_type") + " " + localStorage.getItem("auth_token");
  if (!localStorage.getItem("auth_token")) return null;
  return {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: token,
  };
}

export default function WishlistPage() {
  const { isArabic } = useCourseEnglishSettings();
  const [loading, setLoading] = useState(true);
  const [items, setItems] = useState([]);
  const [error, setError] = useState("");

  const loadWishlist = useCallback(async () => {
    const headers = getAuthHeaders();
    if (!headers) {
      setItems([]);
      setError(isArabic ? "يرجى تسجيل الدخول أولاً." : "Please login first.");
      setLoading(false);
      return;
    }

    setLoading(true);
    setError("");
    try {
      const res = await fetch(buildApiUrl("/courseenglish/wishlist"), {
        method: "GET",
        headers,
        cache: "no-store",
      });
      const json = await res.json();
      if (!res.ok) {
        throw new Error(json?.message || "Failed");
      }
      setItems(json?.items || []);
    } catch (e) {
      setError(e.message || "Failed to load wishlist");
    } finally {
      setLoading(false);
    }
  }, [isArabic]);

  useEffect(() => {
    loadWishlist();
  }, [loadWishlist]);

  const groupedItems = useMemo(() => {
    const groups = {};
    items.forEach((item) => {
      if (!groups[item.course_type]) {
        groups[item.course_type] = [];
      }
      groups[item.course_type].push(item);
    });
    return groups;
  }, [items]);

  const orderedGroupTypes = useMemo(() => {
    const present = Object.keys(groupedItems);
    const orderedKnown = wishlistTypeOrder.filter((type) => groupedItems[type]?.length);
    const remaining = present.filter((type) => !wishlistTypeOrder.includes(type));
    return [...orderedKnown, ...remaining];
  }, [groupedItems]);

  const pageText = useMemo(
    () => ({
      title: isArabic ? "المفضلة" : "Your Wishlist",
      subtitle: isArabic
        ? "الدورات والمعاهد التي قمت بحفظها."
        : "Courses and institutes you saved.",
      loginHint: isArabic ? "يرجى تسجيل الدخول." : "Please login first.",
      empty: isArabic ? "لا توجد عناصر في المفضلة" : "Your wishlist is empty",
      emptyDesc: isArabic
        ? "ابدأ بالتصفح لإضافة الدورات المفضلة لديك."
        : "Start exploring to add your favorite courses.",
      browse: isArabic ? "استكشاف الدورات" : "Explore Courses",
    }),
    [isArabic]
  );

  return (
    <main className="min-h-screen bg-slate-50 py-20" dir={isArabic ? "rtl" : "ltr"}>
      <div className="container mx-auto px-4">
        {/* Header */}
        <div className="mb-10 text-center">
          <div className="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500">
            <FontAwesomeIcon icon={faHeart} className="h-7 w-7" />
          </div>
          <h1 className="text-4xl font-semibold text-slate-900">{pageText.title}</h1>
          <p className="mt-2 text-slate-500">{pageText.subtitle}</p>
        </div>

        {loading && <div className="py-16 text-center text-slate-500">Loading...</div>}

        {!loading && error && (
          <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-center text-red-700">
            {error || pageText.loginHint}
          </div>
        )}

        {!loading && !error && items.length > 0 && (
          <div className="space-y-12">
            {orderedGroupTypes.map((type) => {
              const groupLabel = typeLabels[type]?.[isArabic ? "ar" : "en"] || type;
              const groupItems = groupedItems[type];
              const cardsGridClass =
                type === "language_courses"
                  ? "grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-4 justify-items-center"
                  : type === "training_courses"
                    ? "grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3 justify-items-center"
                    : "grid grid-cols-1 gap-6 md:grid-cols-3 xl:grid-cols-4 justify-items-center";

              return (
                <section key={type} className="animate-in fade-in slide-in-from-bottom-4 duration-500">
                  <div className="mb-6 flex items-center gap-4">
                    <h2 className="text-2xl font-medium text-slate-800">{groupLabel}</h2>
                    <div className="h-px flex-1 bg-gray-200"></div>
                  </div>

                  <div className={cardsGridClass}>
                    {groupItems.map((row) => {
                      const key = `${type}-${row.course_id}`;
                      const wrapClass = "w-full max-w-[360px]";

                      if (type === "language_courses") {
                        const c = row.course || {};
                        const instituteData = {
                          ...c,
                          id: row.course_id ?? c.id,
                          slug: c.school_slug || c.slug,
                          name: c.name || c.school_name || c.provider,
                          ar_name: c.ar_name || c.school_ar_name || c.provider_ar_name,
                          city: c.city || c.city_name,
                          city_ar: c.city_ar || c.city_ar_name,
                          image: c.image || c.logo || "/assets/hero.png",
                          rating: c.rating || 5,
                          flag: c.country_flag || c.flag,
                          level: c.required_level || c.level,
                          lessons: c.lessons_per_week || c.lessons,
                          hours: c.study_time || c.hours,
                          price: c.price,
                          price_sar: c.price_sar ?? c.price_new_sar,
                          price_gbp: c.price_gbp ?? c.price_new_gbp,
                          currency: c.currency || c.currency_code,
                          course_name: c.name || c.course_name,
                          course_ar_name: c.ar_name || c.course_ar_name,
                        };
                        return (
                          <div key={key} className={wrapClass}>
                            <InstituteCard institute={instituteData} />
                          </div>
                        );
                      }

                      if (type === "online_courses") {
                        const c = row.course || {};
                        const rawFlag = typeof (c.flag || c.country_flag) === "string" ? (c.flag || c.country_flag).trim() : "";
                        const rawCountryValue = isArabic
                          ? c.country_ar_name || c.country_name || c.country
                          : c.country || c.country_name || c.country_ar_name;
                        const rawCountry = typeof rawCountryValue === "string" ? rawCountryValue.trim() : "";
                        const looksLikeUrl = (v) => /^(https?:\/\/|\/|storage\/)/i.test(v || "");
                        const countryText = looksLikeUrl(rawCountry)
                          ? (isArabic ? c.country_ar_name || c.country_name : c.country_name || c.country_ar_name) || ""
                          : rawCountry;
                        const flagValue = looksLikeUrl(rawFlag)
                          ? rawFlag
                          : looksLikeUrl(rawCountry)
                            ? rawCountry
                            : rawFlag;
                        const onlineData = {
                          id: row.course_id ?? c.id,
                          slug: c.school_slug || c.slug,
                          title: isArabic
                            ? c.ar_title || c.ar_name || c.title || c.name
                            : c.title || c.name || c.ar_title || c.ar_name,
                          image: c.image || "/assets/hero.png",
                          mode: c.mode || "Online",
                          provider: isArabic
                            ? c.provider_ar_name || c.provider || c.school_ar_name || c.school_name
                            : c.provider || c.school_name || c.provider_ar_name || c.school_ar_name,
                          flag: flagValue,
                          country: countryText,
                          priceValue: c.price ?? c.price_new ?? c.price_new_gbp ?? c.price_new_sar,
                          priceNew: c.price,
                          priceOld: c.old_price,
                          currency: c.currency || c.currency_code || "GBP",
                          priceUnit: c.price_unit || "per week",
                          discountLabel: isArabic ? c.tag_ar_name || c.tag : c.tag || c.tag_ar_name,
                        };
                        return (
                          <div key={key} className={wrapClass}>
                            <OnlineCourseCard course={onlineData} isArabic={isArabic} />
                          </div>
                        );
                      }

                      if (type === "summer_camps") {
                        const c = row.course || {};
                        const campData = {
                          id: row.course_id ?? c.id,
                          title: isArabic
                            ? c.ar_title || c.ar_name || c.title || c.name
                            : c.title || c.name || c.ar_title || c.ar_name,
                          slug: c.slug,
                          image: c.image || "/assets/hero.png",
                          city: isArabic ? c.city_ar_name || c.city : c.city || c.city_name,
                          country: isArabic
                            ? c.country_ar_name || c.country || c.country_name
                            : c.country || c.country_name || c.country_ar_name,
                          ageRange: c.age_range || "12-17",
                          description: c.description,
                          descriptionAr: c.ar_description,
                          priceFromValue: c.price ?? c.price_from ?? c.price_from_gbp ?? c.price_from_sar,
                          priceFrom: c.price,
                          currency: c.currency || c.currency_code || "GBP",
                          discountLabel: isArabic ? c.tag_ar_name || c.tag : c.tag || c.tag_ar_name,
                        };
                        return (
                          <div key={key} className={wrapClass}>
                            <SummerCampCard program={campData} isArabic={isArabic} />
                          </div>
                        );
                      }

                      if (type === "training_courses") {
                        const c = row.course || {};
                        const trainingData = {
                          id: row.course_id ?? c.id,
                          title: isArabic
                            ? c.ar_title || c.ar_name || c.title || c.name
                            : c.title || c.name || c.ar_title || c.ar_name,
                          provider: isArabic ? c.provider_ar_name || c.provider : c.provider || c.provider_ar_name,
                          category: isArabic ? c.category_ar_name || c.category : c.category || c.category_ar_name,
                          location: isArabic ? c.location_ar || c.location : c.location || c.location_ar,
                          duration: c.duration,
                          priceValue: c.price ?? c.price_gbp ?? c.price_sar,
                          currency: c.currency || c.currency_code || "GBP",
                          image: c.image || "/assets/hero.png",
                        };
                        return (
                          <div key={key} className={wrapClass}>
                            <ProfessionalCourseCard course={trainingData} isArabic={isArabic} />
                          </div>
                        );
                      }

                      return (
                        <div key={key} className="rounded-2xl border border-[#DCE6F1] bg-white p-4">
                          {row.course?.name || "Unknown Item"}
                        </div>
                      );
                    })}
                  </div>
                </section>
              );
            })}
          </div>
        )}

        {!loading && !error && items.length === 0 && (
          <div className="py-20 text-center">
            <div className="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-300">
              <FontAwesomeIcon icon={faHeart} className="h-8 w-8" />
            </div>
            <h2 className="text-2xl font-medium text-slate-900">{pageText.empty}</h2>
            <p className="mb-8 mt-2 text-slate-500">{pageText.emptyDesc}</p>
            <Link
              href="/"
              className="inline-flex items-center gap-2 rounded-xl bg-[#003B5C] px-8 py-3 font-medium text-white hover:bg-[#002a42]"
            >
              {pageText.browse}
            </Link>
          </div>
        )}
      </div>
    </main>
  );
}
