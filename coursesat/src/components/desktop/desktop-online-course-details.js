"use client";

/* eslint-disable @next/next/no-img-element */
import { useState, useEffect } from "react";
import Link from "next/link";
import { useSearchParams, useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faStar,
  faSignal,
  faUser,
  faClock,
  faBookOpen,
  faHeart,
  faShare,
  faExchangeAlt,
  faChevronLeft,
  faChevronRight,
  faArrowLeft,
  faArrowRight,
} from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/components/shared/hero-dropdown";
import HeroDatePicker from "@/components/shared/hero-date-picker";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import { useApi } from "@/lib/api";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import {
  buildOnlineCourseBookingUrl,
  formatOnlineCourseQueryDate,
  parseOnlineCourseQueryDate,
} from "@/lib/online-course-booking-url";
import ReferralDiscountModal from "@/components/shared/referral-discount-modal";
import { applyReferralCode, getStoredReferral } from "@/lib/referral";
import { getStoredAuthUser } from "@/lib/auth";

function Price({ value, currency, activeCurrency, className = "", size = "md", muted = false }) {
  if (value === null || value === undefined) return null;

  const iconClassName = size === "lg" ? "h-[30px] w-[30px]" : size === "sm" ? "h-[10px] w-[10px]" : "h-[14px] w-[14px]";
  const textClass = size === "lg" ? "text-[30px] font-bold" : size === "sm" ? "text-[14px]" : "text-[18px] font-bold";

  return (
    <CurrencyAmount
      currency={currency}
      amount={value}
      activeCurrency={activeCurrency}
      className={`inline-flex items-center gap-1 ${textClass} ${className}`}
      iconClassName={iconClassName}
      variant="light"
      muted={muted}
    />
  );
}

function ImageSlider({ images, schoolName }) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const imgs = images && images.length > 0 ? images : [];

  if (imgs.length === 0) return null;

  const prev = () => setCurrentIndex((i) => (i === 0 ? imgs.length - 1 : i - 1));
  const next = () => setCurrentIndex((i) => (i === imgs.length - 1 ? 0 : i + 1));

  return (
    <div className="group relative aspect-[4/3] w-full overflow-hidden rounded-2xl bg-gray-100">
      <img
        src={imgs[currentIndex]}
        alt={schoolName || "Gallery"}
        className="h-full w-full object-cover transition-all duration-500"
      />
      {imgs.length > 1 && (
        <>
          <button
            type="button"
            onClick={prev}
            className="absolute left-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-slate-700 opacity-0 shadow transition hover:bg-white group-hover:opacity-100"
          >
            <FontAwesomeIcon icon={faChevronLeft} className="h-3 w-3" />
          </button>
          <button
            type="button"
            onClick={next}
            className="absolute right-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-slate-700 opacity-0 shadow transition hover:bg-white group-hover:opacity-100"
          >
            <FontAwesomeIcon icon={faChevronRight} className="h-3 w-3" />
          </button>
          <div className="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
            {imgs.map((_, idx) => (
              <button
                key={idx}
                type="button"
                onClick={() => setCurrentIndex(idx)}
                className={`h-2 rounded-full transition-all ${idx === currentIndex ? "w-6 bg-[#0057B7]" : "w-2 bg-white"}`}
              />
            ))}
          </div>
        </>
      )}
    </div>
  );
}

function isWeeklyCourse(course) {
  return course?.fee_type === "weekly" || course?.price_unit === "per week";
}

function resolveOnlineUnitPrice(course, currency) {
  return getCoursePrice(course, currency, "new") || 0;
}

function resolveOnlineCourseTotal(course, weeks, currency) {
  if (!course) return 0;
  const unitPrice = resolveOnlineUnitPrice(course, currency);
  if (isWeeklyCourse(course)) {
    return unitPrice * Math.max(1, Number(weeks) || 1);
  }
  return unitPrice;
}

export default function DesktopOnlineCourseDetails({ slug }) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { language, t } = useLocale();
  const { currency, activeCurrency } = useCurrency();
  const isArabic = language === "ar";

  const initialWeeks = searchParams.get("weeks") ? parseInt(searchParams.get("weeks"), 10) : 4;
  const initialCourseId = searchParams.get("course_id") ? parseInt(searchParams.get("course_id"), 10) : null;
  const initialStartDate = parseOnlineCourseQueryDate(searchParams.get("start_date"));

  const apiPath = slug
    ? `/courseenglish/online-courses/${encodeURIComponent(slug)}${searchParams.toString() ? `?${searchParams.toString()}` : ""}`
    : null;
  const { data: detailData, loading } = useApi(apiPath);

  const [selectedCourseId, setSelectedCourseId] = useState(initialCourseId);
  const [weeks, setWeeks] = useState(initialWeeks);
  const [startDate, setStartDate] = useState(initialStartDate);
  const [toastMsg, setToastMsg] = useState(null);
  const [referralPopup, setReferralPopup] = useState(null);
  const [appliedReferral, setAppliedReferral] = useState(null);
  const [referralCodeInput, setReferralCodeInput] = useState("");
  const [referralApplying, setReferralApplying] = useState(false);
  const [referralError, setReferralError] = useState("");
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "online_courses";

  const school = detailData?.school;
  const courses = detailData?.courses || [];

  useEffect(() => {
    if (courses.length > 0 && !selectedCourseId) {
      setSelectedCourseId(initialCourseId || detailData?.selected_course_id || courses[0].id);
    }
  }, [courses, selectedCourseId, initialCourseId, detailData?.selected_course_id]);

  useEffect(() => {
    const stored = getStoredReferral();
    if (stored?.code) {
      setAppliedReferral(stored);
      setReferralCodeInput(stored.code);
    }
  }, []);

  const showToast = (msg) => {
    setToastMsg(msg);
    setTimeout(() => setToastMsg(null), 2500);
  };

  const handleShare = () => {
    const url = new URL(window.location.href);
    if (selectedCourseId) url.searchParams.set("course_id", selectedCourseId);
    navigator.clipboard.writeText(url.toString()).then(() => {
      showToast(isArabic ? "تم نسخ الرابط!" : "Link copied!");
    });
  };

  const handleApplyReferral = async () => {
    const code = referralCodeInput.trim();
    if (!code) {
      setReferralError(isArabic ? "يرجى إدخال كود الإحالة" : "Please enter a referral code.");
      return;
    }

    setReferralApplying(true);
    setReferralError("");

    try {
      const authUser = getStoredAuthUser();
      const data = await applyReferralCode(code);

      if (
        data.referrer_type === "student"
        && authUser?.id
        && Number(data.referrer_user_id) === Number(authUser.id)
      ) {
        throw new Error(isArabic ? "لا يمكنك استخدام كود الإحالة الخاص بك" : "You cannot use your own referral code.");
      }

      const referralEntry = {
        code: data.referral_code || code.toUpperCase(),
        referrer_type: data.referrer_type,
        referrer_name: data.referrer_name,
        discount_percent: data.discount_percent,
      };
      setAppliedReferral(referralEntry);
      setReferralCodeInput(referralEntry.code);
      setReferralPopup(referralEntry);
      showToast(isArabic ? "تم تطبيق كود الإحالة" : "Referral code applied.");
    } catch (err) {
      setReferralError(err.message || (isArabic ? "كود إحالة غير صالح" : "Invalid referral code."));
    } finally {
      setReferralApplying(false);
    }
  };

  if (loading && !detailData) {
    return (
      <div className="flex min-h-[50vh] items-center justify-center">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
      </div>
    );
  }

  if (!loading && !school) {
    return (
      <div className="flex min-h-[50vh] flex-col items-center justify-center px-4 text-center">
        <p className="text-lg text-slate-600">{isArabic ? "الدورة غير موجودة" : "Course not found"}</p>
        <Link href="/online-courses" className="mt-4 text-[#0057B7] hover:underline">
          {isArabic ? "العودة إلى الدورات" : "Back to courses"}
        </Link>
      </div>
    );
  }

  const l = (key) => t(`pages.institute_details.${key}`);
  const loc = (en, ar) => (isArabic && ar ? ar : en);

  const selectedCourse = courses.find((c) => c.id === selectedCourseId);
  const weeklySelected = isWeeklyCourse(selectedCourse);
  const unitPrice = resolveOnlineUnitPrice(selectedCourse, currency);
  const coursePrice = resolveOnlineCourseTotal(selectedCourse, weeks, currency);
  const registrationFee = Number(selectedCourse?.registration_fee ?? detailData?.registration_fee ?? 0);
  const referralDiscountPercent = appliedReferral?.discount_percent
    ? Number(appliedReferral.discount_percent)
    : 0;
  const referralDiscountAmount = referralDiscountPercent > 0
    ? coursePrice * (referralDiscountPercent / 100)
    : 0;
  const coursePriceAfterReferral = coursePrice - referralDiscountAmount;
  const totalPrice = coursePrice + registrationFee - referralDiscountAmount;

  const handleToggleWishlist = async () => {
    if (!selectedCourseId) return;
    const added = !isInWishlist(interactionType, selectedCourseId);
    const ok = await toggleWishlist(interactionType, selectedCourseId);
    if (ok) {
      showToast(
        added
          ? (isArabic ? "تمت الإضافة إلى المفضلة" : "Added to wishlist")
          : (isArabic ? "تمت الإزالة من المفضلة" : "Removed from wishlist"),
      );
    }
  };

  const handleToggleCompare = async () => {
    if (!selectedCourseId) return;
    const added = !isInCompare(interactionType, selectedCourseId);
    const ok = await toggleCompare(interactionType, selectedCourseId, weeks);
    if (ok) {
      showToast(
        added
          ? (isArabic ? "تمت الإضافة إلى المقارنة" : "Added to compare")
          : (isArabic ? "تمت الإزالة من المقارنة" : "Removed from compare"),
      );
    }
  };

  const inWishlist = selectedCourseId ? isInWishlist(interactionType, selectedCourseId) : false;
  const inCompare = selectedCourseId ? isInCompare(interactionType, selectedCourseId) : false;

  const formatDisplayDate = (date) => {
    if (!date) return isArabic ? "اختر التاريخ" : "Select date";
    const formatted = new Intl.DateTimeFormat(isArabic ? "ar-EG-u-nu-latn" : "en-GB", {
      weekday: "long",
      month: "long",
      day: "numeric",
    }).format(date);
    if (isArabic) return formatted.replace(/\s*,\s*/g, "، ");
    return formatted;
  };

  const schoolDisplayName = isArabic
    ? `${school?.ar_name || ""} - ${school?.city_ar || ""} - ${school?.name || ""}`.replace(/^\s*-\s*|\s*-\s*$/g, "").trim() || school?.name
    : [school?.name, school?.city].filter(Boolean).join(" - ");

  const sliderImages = [];
  if (school?.image) sliderImages.push(school.image);
  const gallery = school?.gallery || school?.gallery_urls || [];
  if (gallery.length) sliderImages.push(...gallery.filter((img) => img !== school.image));
  if (selectedCourse?.image && !sliderImages.includes(selectedCourse.image)) {
    sliderImages.unshift(selectedCourse.image);
  }

  const accreditationLogos = school?.accreditations || [];
  const studyEndDate =
    weeklySelected && startDate instanceof Date && !Number.isNaN(startDate.getTime())
      ? new Date(startDate.getTime() + weeks * 7 * 24 * 60 * 60 * 1000)
      : null;

  const selectIconPosition = isArabic ? "left-4" : "right-4";

  return (
    <div className="container mx-auto px-4 py-8" dir={isArabic ? "rtl" : "ltr"}>
      <div className="mb-6 flex">
        <Link href="/online-courses" className="flex items-center gap-2 text-sm font-semibold text-[#102233] transition hover:text-[#0057B7]">
          <FontAwesomeIcon icon={isArabic ? faArrowRight : faArrowLeft} className="h-4 w-4" />
          <span>{isArabic ? "العودة إلى الدورات" : l("backToList")}</span>
        </Link>
      </div>

      <div className="flex flex-col justify-between gap-6 md:flex-row md:items-center">
        <div>
          <p className="mb-1 text-sm font-medium text-[#0057B7]">{isArabic ? "دورة أونلاين" : "Online Course"}</p>
          <h1 className="text-3xl font-bold leading-tight text-[#102233] md:text-[32px]" dir={isArabic ? "rtl" : "ltr"}>
            {schoolDisplayName}
          </h1>
        </div>

        <div className="relative flex items-center gap-8 pb-1">
          <button type="button" onClick={handleShare} className="flex items-center gap-2 text-sm font-semibold text-[#102233] transition hover:text-[#0057B7]">
            <FontAwesomeIcon icon={faShare} className="hidden h-4 w-4" />
            <span>{l("share")}</span>
          </button>
          <button
            type="button"
            onClick={handleToggleWishlist}
            className={`flex items-center gap-2 text-sm font-semibold transition ${inWishlist ? "text-red-500" : "text-[#102233] hover:text-red-500"}`}
          >
            <FontAwesomeIcon icon={faHeart} className={`h-4 w-4 ${inWishlist ? "opacity-100" : "opacity-70"}`} />
            <span>{inWishlist ? l("inFavorite") : l("addFavorite")}</span>
          </button>
          <button
            type="button"
            onClick={handleToggleCompare}
            className={`flex items-center gap-2 text-sm font-semibold transition ${inCompare ? "text-[#0057B7]" : "text-[#102233] hover:text-[#0057B7]"}`}
          >
            <FontAwesomeIcon icon={faExchangeAlt} className={`h-4 w-4 ${inCompare ? "opacity-100" : "opacity-70"}`} />
            <span>{inCompare ? l("inCompare") : l("addCompare")}</span>
          </button>
          {toastMsg && (
            <div className="absolute -bottom-12 left-1/2 z-50 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg">
              {toastMsg}
            </div>
          )}
        </div>
      </div>

      <div className="mb-10 mt-4 flex items-center justify-start gap-3 text-sm font-medium text-slate-600" dir={isArabic ? "rtl" : "ltr"}>
        {school?.flag && <img src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" />}
        <span>{loc(school?.location, school?.city_ar ? `${school?.country_ar} ، ${school?.city_ar}` : null)}</span>
        {school?.rating ? (
          <span className="inline-flex items-center gap-1.5 text-[#F59E0B]">
            {isArabic ? (
              <>
                <FontAwesomeIcon icon={faStar} className="h-3.5 w-3.5" />
                <span className="font-semibold text-slate-800">{school.rating}</span>
              </>
            ) : (
              <>
                <span className="font-semibold text-slate-800">{school.rating}</span>
                <FontAwesomeIcon icon={faStar} className="h-3.5 w-3.5" />
              </>
            )}
          </span>
        ) : null}
      </div>

      <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div className="space-y-8 lg:col-span-8">
          <div className="flex flex-col items-center gap-8 rounded-[15px] border border-gray-100 bg-white p-3 shadow-sm md:flex-row">
            <div className="hidden w-full md:block md:w-[45%]">
              <ImageSlider images={sliderImages} schoolName={loc(school?.name, school?.ar_name)} />
            </div>
            <div className="flex-1 space-y-6 py-4">
              <p
                className={`max-h-[160px] overflow-y-auto text-[14px] leading-relaxed text-black scrollbar-thin ${isArabic ? "pr-3 text-right" : "pl-3 text-left"}`}
                dir={isArabic ? "rtl" : "ltr"}
              >
                {loc(school?.description, school?.ar_description)}
              </p>
              {accreditationLogos.length > 0 && (
                <div className="pt-2">
                  <div className="flex flex-wrap justify-start gap-3">
                    {accreditationLogos.map((acc, idx) => (
                      <div key={acc.id ?? idx} className="relative flex h-[36px] w-[80px] items-center justify-center rounded-[10px] border border-gray-200 bg-white p-1.5 shadow-sm transition hover:border-[#0057B7]">
                        {acc.logo && <img src={acc.logo} alt={loc(acc.name, acc.ar_name)} className="h-full w-full object-contain" />}
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>

          <div>
            <div className="mb-4 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
              <div>
                <h3 className="text-xl font-semibold text-slate-900">{l("step1")}</h3>
                <p className="mt-1 text-sm text-slate-500">{l("step1Sub")}</p>
              </div>
              <div className="flex w-full gap-3 md:w-auto">
                <div className="w-full md:w-48">
                  <HeroDatePicker
                    label={l("startDate")}
                    placeholder={l("selectStart")}
                    selectedDate={startDate}
                    onSelect={(date) => setStartDate(date)}
                  />
                </div>
                {weeklySelected ? (
                  <div className="w-full md:w-50">
                    <HeroDropdown
                      label={l("numWeeks")}
                      placeholder={l("selectWeeks")}
                      scroll
                      options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks")}`, value: i + 1 }))}
                      onSelect={(opt) => setWeeks(opt.value)}
                      selectedValue={weeks}
                    />
                  </div>
                ) : null}
              </div>
            </div>

            <div className="space-y-4 rounded-[15px] border border-gray-100 bg-white p-6 shadow-sm">
              {courses.map((course) => {
                const weekly = isWeeklyCourse(course);
                const price = weekly ? resolveOnlineUnitPrice(course, currency) : resolveOnlineCourseTotal(course, 1, currency);
                return (
                  <label
                    key={course.id}
                    className={`relative block cursor-pointer overflow-hidden rounded-[15px] border-2 bg-white transition-all ${selectedCourseId === course.id ? "border-[#0057B7]" : "border-gray-100 hover:border-gray-200"}`}
                  >
                    <div className={`absolute top-3 ${selectIconPosition}`}>
                      <img
                        src={selectedCourseId === course.id ? "/assets/icons/selected-blue.svg" : "/assets/icons/selected-null.svg"}
                        alt=""
                        className="h-6 w-6"
                      />
                    </div>
                    <div className="relative flex flex-col justify-between gap-2 p-6 md:flex-row">
                      <div className="flex-1">
                        <div className="mb-4 flex items-center gap-3">
                          <h4 className="text-[18px] font-bold text-[#102233]">
                            {loc(course.name || course.title, course.ar_name || course.ar_title)}
                          </h4>
                          {course.tag && (
                            <span className="rounded-md bg-[#4CAF50] px-2.5 py-0.5 text-[12px] font-medium text-white">
                              {loc(course.tag, course.tag_ar)}
                            </span>
                          )}
                        </div>
                        <div className="flex flex-wrap items-center gap-2 text-[12px] font-medium text-slate-500 xl:gap-3">
                          {course.lessons ? (
                            <span className="flex items-center gap-1.5">
                              <FontAwesomeIcon icon={faBookOpen} className="h-[14px] w-[14px] text-[#0057B7]" /> {course.lessons} {l("lessonsWeek")}
                            </span>
                          ) : null}
                          {course.hours ? (
                            <span className="flex items-center gap-1.5">
                              <FontAwesomeIcon icon={faClock} className="h-[14px] w-[14px] text-[#0057B7]" /> {course.hours} {l("hoursWeek")}
                            </span>
                          ) : null}
                          {course.min_age ? (
                            <span className="flex items-center gap-1.5">
                              <FontAwesomeIcon icon={faUser} className="h-[14px] w-[14px] text-[#0057B7]" /> +{course.min_age} {l("requiredAge")}
                            </span>
                          ) : null}
                          {course.level ? (
                            <span className="flex items-center gap-1.5">
                              <FontAwesomeIcon icon={faSignal} className="h-[14px] w-[14px] text-[#0057B7]" /> {loc(course.level, course.ar_level)} {l("requiredLevel")}
                            </span>
                          ) : null}
                        </div>
                      </div>

                      <div className="mt-4 flex h-full min-w-[150px] flex-col items-end justify-between">
                        <div className="mt-auto text-left">
                          <div className="flex items-baseline justify-end gap-1">
                            <span className="text-[18px] font-bold text-[#102233]">
                              <Price activeCurrency={activeCurrency} value={price} currency={currency} />
                            </span>
                            <span className="mx-1 text-[15px] font-bold text-[#102233]">/</span>
                            <span className="text-[15px] font-medium text-[#102233]">
                              {weekly ? (isArabic ? "للاسبوع" : "week") : (isArabic ? "للدورة" : "course")}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <input
                      type="radio"
                      name="course"
                      className="hidden"
                      value={course.id}
                      checked={selectedCourseId === course.id}
                      onChange={() => setSelectedCourseId(course.id)}
                    />
                  </label>
                );
              })}
            </div>
          </div>
        </div>

        <div className="relative sticky top-4 flex h-max flex-col gap-6 lg:col-span-4">
          <div className="flex flex-col gap-6 rounded-[15px] border border-gray-100 bg-white p-2 shadow-sm">
            <div className="flex flex-col items-center justify-center rounded-[15px] bg-[#F0F7FC] py-4">
              <div className="flex items-center gap-1 text-[30px] font-bold text-[#102233]" dir="ltr">
                <Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="lg" />
              </div>
              <div className="text-[12px] text-[#475569]">{l("totalIncludes")}</div>
            </div>

            <div className="mx-2">
              <HeroDatePicker
                variant="sidebar"
                label={l("studyDate")}
                placeholder={l("selectStart")}
                selectedDate={startDate}
                onSelect={(date) => setStartDate(date)}
                fromLabel={l("from")}
                toLabel={l("to")}
                endDate={studyEndDate}
                formatDisplay={formatDisplayDate}
              />
            </div>

            {weeklySelected ? (
              <div className="mx-2">
                <HeroDropdown
                  variant="sidebar"
                  label={l("numWeeks")}
                  placeholder={l("selectWeeks")}
                  scroll
                  maxVisibleItems={8}
                  options={Array.from({ length: 52 }, (_, i) => ({ label: `${i + 1} ${l("weeks")}`, value: i + 1 }))}
                  onSelect={(opt) => setWeeks(opt.value)}
                  selectedValue={weeks}
                />
              </div>
            ) : null}

            <div className="mx-2">
              <div className={`mb-3 text-[18px] text-[#102233] ${isArabic ? "text-right" : "text-left"}`}>{l("couponQ")}</div>
              <div className="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-1.5 shadow-sm">
                <input
                  type="text"
                  value={referralCodeInput}
                  onChange={(event) => {
                    setReferralCodeInput(event.target.value.toUpperCase());
                    setReferralError("");
                  }}
                  placeholder={l("couponCode")}
                  className={`w-full bg-transparent px-3 text-[16px] outline-none placeholder:text-gray-400 ${isArabic ? "text-right" : "text-left"}`}
                  dir={isArabic ? "rtl" : "ltr"}
                />
                <button
                  type="button"
                  onClick={handleApplyReferral}
                  disabled={referralApplying}
                  className={`shrink-0 rounded-xl px-8 py-3 text-[16px] text-white transition ${appliedReferral ? "bg-emerald-500 hover:bg-emerald-600" : "bg-[#0284c7] hover:bg-[#0369a1]"} disabled:opacity-60`}
                >
                  {referralApplying ? "..." : (appliedReferral ? l("applied") : l("apply"))}
                </button>
              </div>
              {referralError ? (
                <p className={`mt-2 text-sm text-red-600 ${isArabic ? "text-right" : "text-left"}`}>{referralError}</p>
              ) : null}
              {appliedReferral ? (
                <p className={`mt-2 text-xs text-emerald-600 ${isArabic ? "text-right" : "text-left"}`}>
                  {l("referralApplied")}: {appliedReferral.referrer_name} ({referralDiscountPercent}%)
                </p>
              ) : null}
            </div>

            <div className="mx-4 space-y-4 pt-4 text-[14px] text-[#102233]">
              <div className="flex items-center justify-between">
                <span className="text-right">
                  {selectedCourse ? loc(selectedCourse.name || selectedCourse.title, selectedCourse.ar_name || selectedCourse.ar_title) : l("step1")}
                  {weeklySelected ? ` (${weeks} ${l("weeks")})` : ""}
                </span>
                <span className="flex items-center gap-2">
                  {referralDiscountAmount > 0 && (
                    <span className="text-slate-400 line-through">
                      <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" muted />
                    </span>
                  )}
                  <Price activeCurrency={activeCurrency} value={referralDiscountAmount > 0 ? coursePriceAfterReferral : coursePrice} currency={currency} size="sm" />
                </span>
              </div>
              {referralDiscountAmount > 0 && (
                <div className="flex items-center justify-between text-[#10B981]">
                  <span>
                    {l("referralDiscount")} ({appliedReferral?.referrer_name}) ({referralDiscountPercent}%)
                  </span>
                  <span>-<Price activeCurrency={activeCurrency} value={referralDiscountAmount} currency={currency} size="sm" /></span>
                </div>
              )}
              {registrationFee > 0 && (
                <div className="flex items-center justify-between">
                  <span className="text-right">{l("registrationFee")}</span>
                  <span>
                    <Price activeCurrency={activeCurrency} value={registrationFee} currency={currency} size="sm" />
                  </span>
                </div>
              )}
            </div>

            <div className="flex items-center justify-between border-t border-gray-200 px-4 py-4">
              <div className="flex flex-col">
                <div className="flex items-center gap-2">
                  <span className="text-[18px] font-bold text-[#0284c7]">
                    <Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="md" />
                  </span>
                </div>
                <div className="mt-1 text-[12px] font-medium text-[#102233]">{l("totalIncludes")}</div>
              </div>
              <button
                type="button"
                onClick={() => {
                  router.push(
                    buildOnlineCourseBookingUrl(slug, {
                      courseId: selectedCourseId,
                      weeks: weeklySelected ? weeks : undefined,
                      startDate: formatOnlineCourseQueryDate(startDate) || formatOnlineCourseQueryDate(new Date()),
                    }),
                  );
                }}
                className="flex items-center gap-2 rounded-xl bg-[#0284c7] p-4 text-[14px] text-white transition hover:bg-[#0369a1]"
              >
                <span>{l("booking.reviewConfirm") || l("reviewRequest")}</span>
                <FontAwesomeIcon icon={isArabic ? faArrowLeft : faArrowRight} className="h-5 w-5 shrink-0" />
              </button>
            </div>
          </div>

          <div className="rounded-2xl bg-[#E8F5E9] px-5 py-6 md:py-8">
            <h4 className={`mb-6 text-[16px] font-semibold leading-normal text-[#001432] ${isArabic ? "text-right" : "text-left"}`}>
              {l("haveQuestion")}
            </h4>
            <p className={`mb-6 text-[15px] font-normal leading-[1.65] text-[#001432] ${isArabic ? "text-right" : "text-left"}`}>
              {l("haveQuestionDesc")}
            </p>
            <Link
              href="https://wa.me/966550027268"
              target="_blank"
              rel="noopener noreferrer"
              dir={isArabic ? "rtl" : "ltr"}
              className="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-[14px] font-semibold leading-none text-[#25D376] transition hover:bg-white/90 hover:text-[#25D366]"
            >
              <img src="/assets/icons/whatsapp-circle.svg" alt="" width={20} height={20} className="h-7 w-7 shrink-0" />
              <span>{l("contactUsWhatsapp")}</span>
            </Link>
          </div>
        </div>
      </div>

      <ReferralDiscountModal
        open={Boolean(referralPopup)}
        onClose={() => setReferralPopup(null)}
        referrerName={referralPopup?.referrer_name}
        discountPercent={referralPopup?.discount_percent}
        discountAmount={referralDiscountAmount}
        currency={currency}
        activeCurrency={activeCurrency}
      />
    </div>
  );
}
