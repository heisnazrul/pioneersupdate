 "use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTag } from "@fortawesome/free-solid-svg-icons";

// Import Components
import SectionWithFilter from "@/app/components/SectionWithFilter";
import InstituteCard from "@/app/components/InstituteCard";
import SummerCampCard from "@/app/components/SummerCampCard";
import OnlineCourseCard from "@/app/components/OnlineCourseCard";
import ProfessionalCourseCard from "@/app/components/ProfessionalCourseCard";

import { useApi, getImageUrl } from "@/lib/courseenglishApi";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const normalizeImageUrl = (value) => {
  if (!value || typeof value !== "string") return "";
  const raw = value.trim();
  if (!raw || /\/storage\/[a-z]$/i.test(raw)) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://") || raw.startsWith("data:")) return raw;
  if (raw.startsWith("/")) return getImageUrl(raw) || "";
  if (raw.startsWith("storage/")) return getImageUrl(`/${raw}`) || "";
  if (raw.startsWith("assets/")) return `/${raw}`;
  return getImageUrl(`/storage/${raw}`) || "";
};

const pickLanguageImage = (course) => {
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

export default function OffersPage() {
  const { data: offersData } = useApi("/courseenglish/offer");
  const { currency, isArabic, language } = useCourseEnglishSettings();
  const page = getCourseEnglishMessages(language)?.pages?.offers ?? {};
  const hero = page?.hero || {};

  const labels = {
    all: isArabic ? "كل العروض" : "All Offers",
    discounted: isArabic ? "خصومات فقط" : "Discounted Only",
    viewAll: isArabic ? "عرض الكل" : "View All",
    empty: isArabic ? "لا توجد عناصر مطابقة." : "No items match the selected filter.",
    clear: isArabic ? "مسح الفلتر" : "Clear Filter",
  };

  const languageCourses = (offersData?.language_courses || []).map((course) => ({
    ...course,
    image: pickLanguageImage(course),
    price_sar: course.price_sar ?? course.price_new_sar,
    price_gbp: course.price_gbp ?? course.price_new_gbp,
    old_price_sar: course.old_price_sar ?? course.price_old_sar,
    old_price_gbp: course.old_price_gbp ?? course.price_old_gbp,
    weeks_param: null,
    start_date_param: null,
  }));

  const summerPrograms = (offersData?.summer_camps || []).map((camp) => ({
    id: camp.id,
    title: isArabic ? camp.ar_title || camp.title : camp.title,
    city: camp.city,
    country: isArabic ? camp.country_ar_name || camp.country : camp.country,
    countryAr: camp.country_ar_name,
    ageRange: camp.age_range,
    description: camp.description,
    descriptionAr: camp.ar_description,
    priceFromValue:
      currency === "SAR" ? camp.price_from_sar ?? camp.price_from : camp.price_from_gbp ?? camp.price_from,
    currency,
    image: camp.image || "/assets/hero.png",
    flag: camp.flag,
    discountLabel: isArabic ? camp.tag_ar_name || camp.tag : camp.tag,
  }));

  const onlineCourses = (offersData?.online_courses || []).map((course) => ({
    id: course.id,
    title: isArabic ? course.ar_title || course.title : course.title,
    provider: isArabic ? course.provider_ar_name || course.provider : course.provider,
    country: isArabic ? course.country_ar_name || course.country : course.country,
    flag: course.flag,
    mode: course.mode || "Online",
    discountLabel: isArabic ? course.tag_ar_name || course.tag : course.tag,
    priceValue:
      currency === "SAR"
        ? course.price_new_sar ?? course.price_new
        : course.price_new_gbp ?? course.price_new,
    currency,
    priceUnit: course.price_unit,
    image: course.image || "/assets/hero.png",
  }));

  const trainingCourses = (offersData?.training_courses || []).map((course) => ({
    id: course.id,
    title: isArabic ? course.ar_title || course.title : course.title,
    provider: isArabic ? course.provider_ar_name || course.provider : course.provider,
    category: isArabic ? course.category_ar_name || course.category : course.category,
    subject: isArabic ? course.category_ar_name || course.category : course.category,
    location: course.location,
    duration: course.duration,
    priceValue:
      currency === "SAR"
        ? course.price_sar ?? course.price
        : course.price_gbp ?? course.price,
    currency,
    image: course.image || "/assets/hero.png",
  }));

  return (
    <main className="min-h-screen bg-[#F0F7FC] py-20">

      {/* Page Header */}
      <section className="container mx-auto px-4 mb-20 text-center">
        <span className="inline-block rounded-full bg-red-100 px-4 py-1.5 text-sm font-medium text-red-600 border border-red-200 mb-4">
          <FontAwesomeIcon icon={faTag} className="mr-2" />
          {hero.badge || (isArabic ? "عروض لفترة محدودة" : "Limited Time Offers")}
        </span>
        <h1 className="text-4xl md:text-5xl font-semibold text-slate-900 mb-6">
          {hero.headline || (isArabic ? "عروض تعليمية حصرية" : "Exclusive Education Deals")}
        </h1>
        <p className="text-lg text-slate-600 max-w-2xl mx-auto">
          {hero.subheadline ||
            (isArabic
              ? "استكشف باقة من الدورات المتخصصة والبرامج الصيفية والتدريبية بأسعار لا تُنافس."
              : "Explore our handpicked selection of specialized courses, camps, and training programs at unbeatable prices.")}
        </p>
      </section>

      <div className="container mx-auto px-4 space-y-24">

        {/* 1. Language Courses */}
        <SectionWithFilter
          title={page?.sections?.top_bar?.language || (isArabic ? "دورات اللغة" : "Language Courses")}
          subtitle={isArabic ? "معاهد مميزة بخصومات حصرية." : "Top-rated institutes with exclusive discounts."}
          link="/language-institutes"
          data={languageCourses}
          CardComponent={InstituteCard}
          dataKey="institute"
          labels={labels}
        />

        {/* 2. Summer Camps */}
        <SectionWithFilter
          title={page?.sections?.top_bar?.summer || (isArabic ? "برامج الصيف" : "Summer Programs")}
          subtitle={isArabic ? "تجارب صيفية لا تُنسى للمراهقين." : "Unforgettable summer experiences for teens."}
          link="/summer-programs"
          data={summerPrograms}
          CardComponent={(props) => <SummerCampCard {...props} isArabic={isArabic} />}
          dataKey="program"
          labels={labels}
        />

        {/* 3. Online Courses */}
        <SectionWithFilter
          title={page?.sections?.top_bar?.online || (isArabic ? "الدورات الإلكترونية" : "Online Courses")}
          subtitle={isArabic ? "تعلم من أي مكان بجدول مرن." : "Learn from anywhere with flexible schedules."}
          link="/online-courses"
          data={onlineCourses}
          CardComponent={(props) => <OnlineCourseCard {...props} isArabic={isArabic} />}
          dataKey="course"
          labels={labels}
        />

        {/* 4. Professional Courses */}
        <SectionWithFilter
          title={page?.sections?.top_bar?.professional || (isArabic ? "التدريب المهني" : "Professional Training")}
          subtitle={isArabic ? "عزز مسيرتك المهنية بدورات معتمدة." : "Boost your career with certified courses."}
          link="/training-and-professional-courses"
          data={trainingCourses}
          CardComponent={(props) => <ProfessionalCourseCard {...props} isArabic={isArabic} />}
          dataKey="course"
          labels={labels}
        />

      </div>
    </main>
  );
}
