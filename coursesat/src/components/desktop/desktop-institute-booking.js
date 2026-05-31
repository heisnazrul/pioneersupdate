"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare, faCircleInfo, faCheckCircle, faHeart, faExchangeAlt } from "@fortawesome/free-solid-svg-icons";
import { PasswordToggleButton } from "@/components/shared/auth-ui";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import CountryPhoneInput from "@/components/shared/country-phone-input";
import { AUTH_COUNTRIES } from "@/lib/auth-countries";
import { useApi } from "@/lib/api";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import { computeInstitutePricing } from "@/lib/institute-pricing";
import { resolveCoursePromotionPercent } from "@/lib/pioneers-discount";
import {
  fetchAuthMe,
  getStoredAuthToken,
  getStoredAuthUser,
  login,
  saveAuthSession,
} from "@/lib/auth";
import { submitLanguageCourseBooking, saveBookingConfirmation } from "@/lib/language-course-booking-api";
import { getReferralCodeForRequest } from "@/lib/referral";
import AgentStudentSelector from "@/components/shared/agent-student-selector";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import {
  buildInstituteDetailsUrl,
  formatInstituteQueryDate,
  readInstituteSelectionFromSearchParams,
} from "@/lib/institute-booking-url";

function Price({ value, currency, activeCurrency, className = "", size = "md", muted = false }) {
  if (value === null || value === undefined) return null;

  const iconClassName = size === "lg" ? "h-[24px] w-[24px]" : size === "sm" ? "h-[10px] w-[10px]" : "h-[14px] w-[14px]";
  const textClass = size === "lg" ? "text-[24px] font-bold" : size === "sm" ? "text-[14px]" : "text-[14px] font-medium";

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

function formatDisplayDate(date, isArabic) {
  if (!(date instanceof Date) || Number.isNaN(date.getTime())) return "-";
  return date.toLocaleDateString(isArabic ? "ar-SA" : "en-GB", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

/** Figma: price on the left, label on the right (AR). EN: label left, price right. */
function SummaryRow({ label, children, className = "", isRtl }) {
  const labelEl = (
    <span className={`flex-1 font-normal text-slate-600 ${isRtl ? "text-right" : "text-left"}`}>
      {label}
    </span>
  );
  const priceEl = (
    <span className={`flex shrink-0 items-center gap-2 tabular-nums ${className}`}>
      {children}
    </span>
  );

  return (
    <div className="flex items-center justify-between gap-6" dir="ltr">
      {isRtl ? (
        <>
          {priceEl}
          {labelEl}
        </>
      ) : (
        <>
          {labelEl}
          {priceEl}
        </>
      )}
    </div>
  );
}

/** Figma AR: change left, title right */
function CardSectionHeader({ title, changeHref, changeLabel, isRtl }) {
  const changeBtn = (
    <Link
      href={changeHref}
      className="shrink-0 rounded border border-gray-100 bg-white px-3 py-1 text-[10px] font-medium text-slate-500 shadow-sm transition hover:bg-gray-50"
    >
      {changeLabel}
    </Link>
  );
  const titleEl = (
    <h4 className={`text-sm font-semibold text-slate-900 ${isRtl ? "text-right" : "text-left"}`}>
      {title}
    </h4>
  );

  return (
    <div className="mb-3 flex items-center justify-between gap-3" dir="ltr">
      {isRtl ? (
        <>
          {changeBtn}
          <div className="flex-1">{titleEl}</div>
        </>
      ) : (
        <>
          <div className="flex-1">{titleEl}</div>
          {changeBtn}
        </>
      )}
    </div>
  );
}

function SummaryServiceRow({ isRtl, title, subtitle }) {
  const textBlock = (
    <div className={`flex-1 ${isRtl ? "text-right" : "text-left"}`}>
      <p className="text-sm font-medium text-slate-900">{title}</p>
      {subtitle ? <p className="text-xs font-normal text-slate-500">{subtitle}</p> : null}
    </div>
  );
  const checkIcon = (
    <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] text-gray-400">
      <FontAwesomeIcon icon={faCheckCircle} />
    </div>
  );

  return (
    <div className="flex w-full items-start gap-2" dir="ltr">
      {isRtl ? (
        <>
          {textBlock}
          {checkIcon}
        </>
      ) : (
        <>
          {checkIcon}
          {textBlock}
        </>
      )}
    </div>
  );
}

function accommodationFeatureLine(acc) {
  const list = Array.isArray(acc?.features)
    ? acc.features
    : typeof acc?.features === "string"
      ? acc.features.split(",")
      : [];
  const labels = list.map((item) => String(item).trim()).filter(Boolean);
  return labels.length ? labels.join(" - ") : null;
}

export default function DesktopInstituteBooking({ slug }) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { language, direction, t } = useLocale();
  const { currency, activeCurrency } = useCurrency();
  const isArabic = language === "ar";
  const isRtl = direction === "rtl";
  const textAlign = isRtl ? "text-right" : "text-left";
  const placeholderAlign = isRtl ? "placeholder:text-right" : "placeholder:text-left";

  const selection = useMemo(
    () => readInstituteSelectionFromSearchParams(searchParams),
    [searchParams],
  );

  const apiPath = slug
    ? `/coursesat/language-institutes/${encodeURIComponent(slug)}${searchParams.toString() ? `?${searchParams.toString()}` : ""}`
    : null;
  const { data: instituteData, loading } = useApi(apiPath);

  const [formData, setFormData] = useState({ name: "", email: "", phone: "", password: "" });
  const [country, setCountry] = useState(AUTH_COUNTRIES[0]);
  const [countryOpen, setCountryOpen] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [toastMsg, setToastMsg] = useState(null);
  const [submitError, setSubmitError] = useState(null);
  const [authUser, setAuthUser] = useState(null);
  const [authLoading, setAuthLoading] = useState(true);
  const [guestFormMode, setGuestFormMode] = useState("register");
  const [loginLoading, setLoginLoading] = useState(false);
  const [selectedAgentStudentId, setSelectedAgentStudentId] = useState("");
  const { isInWishlist, isInCompare, toggleWishlist, toggleCompare } = useCourseEnglishInteractions();
  const interactionType = "language_courses";

  useEffect(() => {
    let active = true;

    async function loadAuth() {
      const storedUser = getStoredAuthUser();
      const token = getStoredAuthToken();

      if (storedUser) {
        setAuthUser(storedUser);
      }

      if (!token) {
        if (active) setAuthLoading(false);
        return;
      }

      try {
        const me = await fetchAuthMe(token);
        if (active && me?.user) {
          setAuthUser(me.user);
        }
      } catch {
        if (active) setAuthUser(storedUser);
      } finally {
        if (active) setAuthLoading(false);
      }
    }

    loadAuth();

    const onAuthUpdate = () => {
      setAuthUser(getStoredAuthUser());
    };

    window.addEventListener("auth-update", onAuthUpdate);
    return () => {
      active = false;
      window.removeEventListener("auth-update", onAuthUpdate);
    };
  }, []);

  const l = (key) => t(`pages.institute_details.booking.${key}`) || t(`pages.institute_details.${key}`);
  const loc = (en, ar) => ((isArabic && ar) ? ar : en);
  const isAgentUser = authUser?.role === "lg_agent";

  const detailsUrl = buildInstituteDetailsUrl(slug, {
    courseId: selection.courseId,
    weeks: selection.weeks,
    accommodationId: selection.accommodationId,
    pickupId: selection.pickupId,
    startDate: formatInstituteQueryDate(selection.startDate),
    extras: selection.extras,
    accAge: selection.accAge,
  });

  const showToast = (msg) => {
    setToastMsg(msg);
    setTimeout(() => setToastMsg(null), 2500);
  };

  const handleShare = () => {
    if (typeof window === "undefined") return;
    navigator.clipboard.writeText(window.location.href).then(() => {
      showToast(isArabic ? "تم نسخ الرابط!" : "Link copied!");
    });
  };

  const handleInputChange = (event) => {
    const { name, value } = event.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const onGuestLogin = async (event) => {
    event?.preventDefault();
    setSubmitError(null);

    if (!formData.email?.trim() || !formData.password) {
      setSubmitError(l("loginFieldsRequired") || (isArabic ? "يرجى إدخال البريد وكلمة المرور" : "Please enter email and password"));
      return;
    }

    setLoginLoading(true);

    try {
      const data = await login({
        email: formData.email.trim(),
        password: formData.password,
      });
      saveAuthSession(data);
      setAuthUser(data.user);
      setGuestFormMode("register");
    } catch (error) {
      setSubmitError(error.message || l("loginFailed") || (isArabic ? "فشل تسجيل الدخول" : "Login failed"));
    } finally {
      setLoginLoading(false);
    }
  };

  const school = instituteData?.school;
  const courses = instituteData?.courses || [];
  const accommodations = instituteData?.accommodations || [];
  const pickUps = instituteData?.pickups || [];
  const insurances = instituteData?.insurances || [];
  const supplements = instituteData?.supplements || [];
  const regFeeObj = instituteData?.registration_fee;
  const discounts = instituteData?.discounts || [];
  const pioneersDiscounts = instituteData?.pioneers_discounts || [];

  const selectedCourse = courses.find((course) => Number(course.id) === Number(selection.courseId))
    || courses[0];
  const selectedCourseId = selectedCourse?.id ?? selection.courseId;
  const selectedAccommodation = selection.accommodationId && selection.accommodationId !== "no-acc"
    ? accommodations.find((item) => String(item.id) === String(selection.accommodationId))
    : null;
  const selectedPickup = pickUps.find((item) => Number(item.id) === Number(selection.pickupId));
  const selectedInsurances = insurances.filter(
    (ins) => ins.is_mandatory || selection.extras.includes(ins.id),
  );
  const selectedSupplements = supplements.filter((supp) => selection.extras.includes(supp.id));

  const onSendRequest = async (event) => {
    event?.preventDefault();
    setSubmitError(null);

    const token = getStoredAuthToken();
    const isLoggedIn = Boolean(authUser && token);

    if (!isLoggedIn) {
      if (!formData.name || !formData.email || !formData.phone || !formData.password) {
        setSubmitError(isArabic ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields");
        return;
      }
    }

    if (isLoggedIn && isAgentUser && !selectedAgentStudentId) {
      setSubmitError(l("agentStudentRequired"));
      return;
    }

    if (!selectedCourseId || !selection.startDate) {
      setSubmitError(isArabic ? "يرجى اختيار الدورة وتاريخ البدء" : "Please select a course and start date");
      return;
    }

    const phoneValue = formData.phone
      ? `${country.dial}${String(formData.phone).replace(/\s+/g, "")}`
      : authUser?.phone || "";

    const payload = {
      selection: {
        course_id: selectedCourseId,
        weeks: selection.weeks,
        start_date: formatInstituteQueryDate(selection.startDate),
        accommodation_id: selection.accommodationId,
        pickup_id: selection.pickupId || null,
        insurance_ids: selectedInsurances.map((item) => item.id),
        supplement_ids: selectedSupplements.map((item) => item.id),
        extras: selection.extras,
        acc_age: selection.accAge,
      },
      display_currency: currency,
    };

    if (!isLoggedIn) {
      payload.user_data = {
        name: formData.name.trim(),
        email: formData.email.trim(),
        phone: phoneValue,
        password: formData.password,
      };
    }

    const referralCode = getReferralCodeForRequest();
    if (referralCode) {
      payload.referral_code = referralCode;
    }

    if (isLoggedIn && isAgentUser) {
      payload.agent_student_id = Number(selectedAgentStudentId);
    }

    setSubmitting(true);

    try {
      const result = await submitLanguageCourseBooking(payload, isLoggedIn ? token : null);

      if (result?.token && result?.user) {
        saveAuthSession({
          access_token: result.token,
          token_type: result.token_type || "Bearer",
          user: result.user,
        });
      }

      const bookingDetail = result?.booking || null;
      if (bookingDetail) {
        saveBookingConfirmation(bookingDetail);
      }

      const ref = encodeURIComponent(result.reference_no || bookingDetail?.reference_no || "");
      router.push(`/booking/confirmation?ref=${ref}`);
    } catch (error) {
      const emailError = error?.errors?.["user_data.email"]?.[0];
      const agentStudentError = error?.errors?.["agent_student_id"]?.[0];
      if (emailError) {
        setSubmitError(isArabic ? "هذا البريد مسجل بالفعل. يرجى تسجيل الدخول." : emailError);
      } else if (agentStudentError) {
        setSubmitError(agentStudentError);
      } else {
        setSubmitError(error?.message || (isArabic ? "تعذر إرسال الطلب" : "Unable to submit booking"));
      }
    } finally {
      setSubmitting(false);
    }
  };

  const courseDiscountPercent = resolveCoursePromotionPercent(
    selectedCourseId,
    discounts,
    selectedCourse,
  );

  const pricing = computeInstitutePricing({
    selectedCourse,
    selectedAccommodation,
    selectedPickup,
    selectedInsurances,
    selectedSupplements,
    weeks: selection.weeks,
    startDate: selection.startDate,
    accAge: selection.accAge,
    currency,
    registrationFeeObj: regFeeObj,
    courseDiscountPercent,
    pioneersDiscounts,
    supplementLabels: {
      material_books: l("materialBooksFee") || t("pages.institute_details.materialBooksFee"),
      registration: l("registrationFee"),
      mandatory: l("mandatoryFee") || t("pages.institute_details.mandatoryFee"),
      summer: l("summerSupplement") || t("pages.institute_details.summerSupplement"),
      winter: l("winterSupplement") || t("pages.institute_details.winterSupplement"),
      other: l("otherSupplement") || t("pages.institute_details.otherSupplement"),
      under_18: l("under18Supplement") || t("pages.institute_details.under18Supplement"),
      insurance: l("insurance") || t("pages.institute_details.step3insurance"),
      insurance_admin: l("insuranceAdminFee") || t("pages.institute_details.insuranceAdminFee"),
    },
  });

  const {
    courseTotal: coursePrice,
    accPrice,
    accOriginalTotal,
    accWaived,
    oneTimeFees,
    accSupplements,
    insuranceLines,
    supplementLines,
    pickupTotal: pickupPrice,
    pickupOriginalTotal,
    pickupWaived,
    pioneersCashLines,
    pioneersCashTotal,
    courseDiscountPercent: appliedCourseDiscountPercent,
    courseDiscountAmount,
    subtotal,
    total: totalPrice,
  } = pricing;

  const formattedStartDate = formatDisplayDate(selection.startDate, isArabic);
  const accFeatureSubtitle = selectedAccommodation ? accommodationFeatureLine(selectedAccommodation) : null;
  const schoolName = loc(school?.name, school?.ar_name);
  const location = loc(school?.city || school?.location, school?.city_ar);

  if (loading && !instituteData) {
    return (
      <div className="flex min-h-[50vh] items-center justify-center">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
      </div>
    );
  }

  if (!loading && !school) {
    return (
      <div className="flex min-h-[50vh] flex-col items-center justify-center px-4 text-center">
        <p className="text-lg text-slate-600">{isArabic ? "المعهد غير موجود" : "Institute not found"}</p>
        <Link href="/language-institutes" className="mt-4 text-[#0057B7] hover:underline">
          {isArabic ? "العودة إلى المعاهد" : "Back to institutes"}
        </Link>
      </div>
    );
  }

  const actionButtons = (
    <>
      <button
        type="button"
        onClick={handleShare}
        className="flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7]"
      >
        <FontAwesomeIcon icon={faShare} className="h-4 w-4" />
        <span>{l("share")}</span>
      </button>
      <button
        type="button"
        onClick={async () => {
          if (!selectedCourseId) return;
          await toggleWishlist(interactionType, selectedCourseId);
        }}
        className={`flex items-center gap-2 text-sm font-medium transition ${selectedCourseId && isInWishlist(interactionType, selectedCourseId) ? "text-red-500" : "text-slate-600 hover:text-red-500"}`}
      >
        <FontAwesomeIcon icon={faHeart} className="h-4 w-4" />
        <span>{selectedCourseId && isInWishlist(interactionType, selectedCourseId) ? t("pages.institute_details.inFavorite") : t("pages.institute_details.addFavorite")}</span>
      </button>
      <button
        type="button"
        onClick={async () => {
          if (!selectedCourseId) return;
          await toggleCompare(interactionType, selectedCourseId, selection.weeks);
        }}
        className={`flex items-center gap-2 text-sm font-medium transition ${selectedCourseId && isInCompare(interactionType, selectedCourseId) ? "text-[#0057B7]" : "text-slate-600 hover:text-[#0057B7]"}`}
      >
        <FontAwesomeIcon icon={faExchangeAlt} className="h-4 w-4" />
        <span>{selectedCourseId && isInCompare(interactionType, selectedCourseId) ? t("pages.institute_details.inCompare") : t("pages.institute_details.addCompare")}</span>
      </button>
    </>
  );

  const titleBlock = (
    <>
      <Link
        href={detailsUrl}
        className={`mb-4 inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7] ${isRtl ? "flex-row-reverse" : ""}`}
      >
        <img
          src="/assets/icons/arrow-left.svg"
          width={16}
          height={16}
          alt=""
          className={isRtl ? "rotate-180" : ""}
        />
        <span>{l("backToList")}</span>
      </Link>
      <h1 className={`text-2xl font-semibold text-slate-900 lg:text-4xl ${textAlign}`}>
        {l("reviewConfirm")}
      </h1>
      <p className={`mt-2 hidden text-sm text-slate-500 lg:block ${textAlign}`}>
        {l("reviewSubtitle")}
      </p>
    </>
  );

  return (
    <div className="container mx-auto px-4 pb-24 pt-6">
      {/* Header — Figma: actions left, title+back right (AR) */}
      <div className="relative mb-8 hidden lg:flex lg:items-start lg:justify-between" dir="ltr">
        {isRtl ? (
          <>
            <div className="flex items-center gap-6">{actionButtons}</div>
            <div className="max-w-xl text-right">{titleBlock}</div>
          </>
        ) : (
          <>
            <div className="max-w-xl text-left">{titleBlock}</div>
            <div className="flex items-center gap-6">{actionButtons}</div>
          </>
        )}
        {toastMsg && (
          <div className="absolute -bottom-12 left-1/2 z-50 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg">
            {toastMsg}
          </div>
        )}
      </div>

      {/* Mobile header */}
      <div className="mb-8 lg:hidden" dir={direction}>
        <div className={textAlign}>{titleBlock}</div>
      </div>

      {/* Figma: form left, summary right (both languages) */}
      <div className="grid grid-cols-1 items-start gap-8 lg:grid-cols-2" dir="ltr">
        {/* Contact form — left column */}
        <div className="order-2 lg:order-1">
          <div className="rounded-[2rem] border border-gray-100 bg-white p-6 lg:p-10 lg:shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <h3 className={`mb-2 text-2xl font-semibold text-[#102233] ${textAlign}`}>{l("contactDetails")}</h3>
            <p className={`mb-8 text-sm leading-relaxed text-slate-500 ${textAlign}`}>
              {authUser
                ? (isAgentUser ? l("agentBookHint") : l("loggedInBookHint"))
                : guestFormMode === "login"
                  ? l("loginSubtitle")
                  : l("contactSubtitle")}
            </p>

            {submitError && (
              <div className={`mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600 ${textAlign}`}>
                {submitError}
              </div>
            )}

            <form className="space-y-6" onSubmit={authUser ? onSendRequest : guestFormMode === "login" ? onGuestLogin : onSendRequest} dir={direction}>
              {authLoading ? (
                <div className="flex min-h-[120px] items-center justify-center">
                  <div className="h-8 w-8 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
                </div>
              ) : authUser ? (
                isAgentUser ? (
                  <AgentStudentSelector
                    value={selectedAgentStudentId}
                    onChange={setSelectedAgentStudentId}
                    textAlign={textAlign}
                    isRtl={isRtl}
                  />
                ) : (
                  <div className={`rounded-2xl border border-gray-100 bg-[#F8FAFC] p-5 ${textAlign}`}>
                    <p className="text-sm text-slate-500">{l("welcomeUser")}</p>
                    <p className="mt-1 text-lg font-semibold text-[#102233]">{authUser.name}</p>
                    <p className="mt-1 text-sm text-slate-500">{authUser.email}</p>
                  </div>
                )
              ) : guestFormMode === "login" ? (
                <>
                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      <span className="text-red-500">*</span>
                      {" "}
                      {l("email")}
                    </label>
                    <input
                      type="email"
                      name="email"
                      value={formData.email}
                      onChange={handleInputChange}
                      placeholder={l("enterEmail")}
                      dir={direction}
                      className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                    />
                  </div>

                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      <span className="text-red-500">*</span>
                      {" "}
                      {l("password")}
                    </label>
                    <div className="relative">
                      <input
                        type={showPassword ? "text" : "password"}
                        name="password"
                        value={formData.password}
                        onChange={handleInputChange}
                        placeholder={l("placeholder_password")}
                        dir={direction}
                        className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign} ${placeholderAlign} ${isRtl ? "pl-12" : "pr-12"}`}
                      />
                      <PasswordToggleButton
                        show={showPassword}
                        onToggle={() => setShowPassword((prev) => !prev)}
                        isRtl={isRtl}
                      />
                    </div>
                  </div>
                </>
              ) : (
                <>
                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      <span className="text-red-500">*</span>
                      {" "}
                      {l("fullName")}
                    </label>
                    <input
                      type="text"
                      name="name"
                      value={formData.name}
                      onChange={handleInputChange}
                      placeholder={l("enterFullName")}
                      dir={direction}
                      className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                    />
                  </div>

                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      <span className="text-red-500">*</span>
                      {" "}
                      {l("email")}
                    </label>
                    <input
                      type="email"
                      name="email"
                      value={formData.email}
                      onChange={handleInputChange}
                      placeholder={l("enterEmail")}
                      dir={direction}
                      className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                    />
                  </div>

                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      <span className="text-red-500">*</span>
                      {" "}
                      {l("mobile")}
                    </label>
                    <CountryPhoneInput
                      country={country}
                      onCountryChange={setCountry}
                      countryOpen={countryOpen}
                      onCountryOpenChange={setCountryOpen}
                      value={formData.phone}
                      onChange={(event) => setFormData((prev) => ({ ...prev, phone: event.target.value }))}
                      placeholder="5xxxxxxx"
                      isRtl={isRtl}
                      variant="booking"
                    />
                  </div>

                  <div>
                    <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                      {l("password")}
                    </label>
                    <div className="relative">
                      <input
                        type={showPassword ? "text" : "password"}
                        name="password"
                        value={formData.password}
                        onChange={handleInputChange}
                        placeholder={l("placeholder_password")}
                        dir={direction}
                        className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-[#102233] outline-none transition-colors placeholder:text-gray-300 focus:border-[#0057B7] ${textAlign} ${placeholderAlign} ${isRtl ? "pl-12" : "pr-12"}`}
                      />
                      <PasswordToggleButton
                        show={showPassword}
                        onToggle={() => setShowPassword((prev) => !prev)}
                        isRtl={isRtl}
                      />
                    </div>
                  </div>
                </>
              )}

              {!authUser && !authLoading && (
                <p className={`text-sm text-slate-500 ${textAlign}`}>
                  {guestFormMode === "login" ? (
                    <>
                      {l("registerPrompt")}{" "}
                      <button
                        type="button"
                        onClick={() => {
                          setSubmitError(null);
                          setGuestFormMode("register");
                        }}
                        className="font-medium text-[#0057B7] hover:underline"
                      >
                        {l("registerLink")}
                      </button>
                    </>
                  ) : (
                    <>
                      {l("loginPrompt")}{" "}
                      <button
                        type="button"
                        onClick={() => {
                          setSubmitError(null);
                          setGuestFormMode("login");
                        }}
                        className="font-medium text-[#0057B7] hover:underline"
                      >
                        {l("loginLink")}
                      </button>
                    </>
                  )}
                </p>
              )}

              <div className={`flex w-full ${isRtl ? "justify-end" : "justify-start"}`}>
                <p className={`inline-flex max-w-full items-start gap-2 text-xs leading-relaxed text-slate-400 ${isRtl ? "text-right" : "text-left"}`}>
                  {isRtl ? (
                    <>
                      <span>{l("disclaimer")}</span>
                      <FontAwesomeIcon icon={faCircleInfo} className="mt-0.5 shrink-0 text-slate-400" />
                    </>
                  ) : (
                    <>
                      <FontAwesomeIcon icon={faCircleInfo} className="mt-0.5 shrink-0 text-slate-400" />
                      <span>{l("disclaimer")}</span>
                    </>
                  )}
                </p>
              </div>

              <button
                type="submit"
                disabled={submitting || authLoading || loginLoading}
                className="h-14 w-full rounded-xl bg-[#0070CD] text-lg font-medium text-white shadow-lg shadow-blue-100/50 transition hover:bg-[#005EB3] disabled:opacity-50"
              >
                {authUser
                  ? (submitting ? l("processing") : l("sendRequest"))
                  : guestFormMode === "login"
                    ? (loginLoading ? l("loggingIn") : l("loginButton"))
                    : (submitting ? l("processing") : l("sendRequest"))}
              </button>
            </form>
          </div>
        </div>

        {/* Summary — right column (single Figma card) */}
        <div className="order-1 lg:order-2">
          <div className="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
            {/* Institute header — Figma AR: change left, title center-right, image right */}
            <div className="flex items-start gap-4 p-6" dir="ltr">
              {isRtl ? (
                <>
                  <Link
                    href={detailsUrl}
                    className="shrink-0 self-start rounded border border-gray-100 bg-white px-3 py-1 text-[10px] font-medium text-slate-500 shadow-sm transition hover:bg-gray-50"
                  >
                    {l("change")}
                  </Link>
                  <div className="flex min-w-0 flex-1 flex-col justify-center gap-1 py-1">
                    <h3 className="text-right text-base font-semibold leading-tight text-slate-900">{schoolName}</h3>
                    <div className="text-right text-xs text-slate-400">{location}</div>
                  </div>
                  <div className="relative h-[88px] w-[88px] shrink-0 overflow-hidden rounded-xl border border-gray-50">
                    <img src={school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                  </div>
                </>
              ) : (
                <>
                  <div className="relative h-[88px] w-[88px] shrink-0 overflow-hidden rounded-xl border border-gray-50">
                    <img src={school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                  </div>
                  <div className="flex min-w-0 flex-1 flex-col justify-center gap-1 py-1">
                    <h3 className="text-left text-base font-semibold leading-tight text-slate-900">{schoolName}</h3>
                    <div className="text-left text-xs text-slate-400">{location}</div>
                  </div>
                  <Link
                    href={detailsUrl}
                    className="shrink-0 self-start rounded border border-gray-100 bg-white px-3 py-1 text-[10px] font-medium text-slate-500 shadow-sm transition hover:bg-gray-50"
                  >
                    {l("change")}
                  </Link>
                </>
              )}
            </div>

            {/* Price summary */}
            <div className="border-t border-gray-100 p-6" dir={direction}>
              <h3 className={`mb-6 text-lg font-semibold text-slate-900 ${textAlign}`}>{l("priceSummary")}</h3>

              <div className="space-y-4 text-[14px] text-[#102233]">
              {selectedCourse && (
                <SummaryRow
                  isRtl={isRtl}
                  label={`${loc(selectedCourse.name, selectedCourse.ar_name)} (${selection.weeks} ${l("weeks")})`}
                >
                  <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" />
                </SummaryRow>
              )}

              {selectedAccommodation && (
                <SummaryRow
                  isRtl={isRtl}
                  label={`${loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)} (${selection.weeks} ${l("weeks")})`}
                >
                  {accWaived && accOriginalTotal > 0 && (
                    <span className="text-slate-400 line-through">
                      <Price activeCurrency={activeCurrency} value={accOriginalTotal} currency={currency} size="sm" muted />
                    </span>
                  )}
                  <Price activeCurrency={activeCurrency} value={accPrice} currency={currency} size="sm" />
                </SummaryRow>
              )}

              {oneTimeFees.map((fee) => (
                <SummaryRow
                  key={fee.key}
                  isRtl={isRtl}
                  label={`${fee.label}${fee.waived ? ` (${l("freeWithPioneers")})` : ""}`}
                >
                  {fee.waived && fee.originalTotal > 0 && (
                    <span className="text-slate-400 line-through">
                      <Price activeCurrency={activeCurrency} value={fee.originalTotal} currency={currency} size="sm" muted />
                    </span>
                  )}
                  <Price activeCurrency={activeCurrency} value={fee.total} currency={currency} size="sm" />
                </SummaryRow>
              ))}

              {accSupplements.map((supp) => (
                <SummaryRow
                  key={supp.key}
                  isRtl={isRtl}
                  label={`${supp.label}${supp.perWeek ? ` (${supp.weeks} ${l("weeks")})` : ""}`}
                >
                  <Price activeCurrency={activeCurrency} value={supp.total} currency={currency} size="sm" />
                </SummaryRow>
              ))}

              {(pickupWaived ? pickupOriginalTotal > 0 : pickupPrice > 0) && selectedPickup && (
                <SummaryRow
                  isRtl={isRtl}
                  label={`${loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)}${pickupWaived ? ` (${l("freeWithPioneers")})` : ""}`}
                >
                  {pickupWaived && pickupOriginalTotal > 0 && (
                    <span className="text-slate-400 line-through">
                      <Price activeCurrency={activeCurrency} value={pickupOriginalTotal} currency={currency} size="sm" muted />
                    </span>
                  )}
                  <Price activeCurrency={activeCurrency} value={pickupPrice} currency={currency} size="sm" />
                </SummaryRow>
              )}

              {insuranceLines.map((line) => (
                <SummaryRow
                  key={line.key}
                  isRtl={isRtl}
                  label={`${line.label}${line.perWeek ? ` (${line.weeks} ${l("weeks")})` : ""}${line.waived ? ` (${l("freeWithPioneers")})` : ""}`}
                >
                  {line.waived && line.originalTotal > 0 && (
                    <span className="text-slate-400 line-through">
                      <Price activeCurrency={activeCurrency} value={line.originalTotal} currency={currency} size="sm" muted />
                    </span>
                  )}
                  <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                </SummaryRow>
              ))}

              {supplementLines.map((line) => (
                <SummaryRow key={line.key} isRtl={isRtl} label={loc(line.label, line.ar_label)}>
                  <Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                </SummaryRow>
              ))}

              {courseDiscountAmount > 0 && (
                <SummaryRow
                  isRtl={isRtl}
                  className="text-[#10B981]"
                  label={`${l("courseDiscount")} (${appliedCourseDiscountPercent}%)`}
                >
                  -<Price activeCurrency={activeCurrency} value={courseDiscountAmount} currency={currency} size="sm" />
                </SummaryRow>
              )}

              {pioneersCashLines.map((line) => (
                <SummaryRow
                  key={line.key}
                  isRtl={isRtl}
                  className="text-[#10B981]"
                  label={`${loc(line.label, line.ar_label)}${line.multiplier > 1 ? ` (×${line.multiplier})` : ""}`}
                >
                  -<Price activeCurrency={activeCurrency} value={line.total} currency={currency} size="sm" />
                </SummaryRow>
              ))}
            </div>

            <div className="my-4 border-t border-gray-100" />

            <SummaryRow
              isRtl={isRtl}
              label={(
                <span>
                  <span className="block text-lg font-semibold text-slate-900">{l("total")}</span>
                  <span className="block text-[10px] font-normal tracking-wide text-slate-400">{l("totalInclude")}</span>
                </span>
              )}
            >
              <Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="lg" className="text-[#0B5DB6] font-semibold" />
              {courseDiscountAmount + pioneersCashTotal > 0 && (
                <span className="text-lg font-medium text-slate-400 line-through">
                  <Price activeCurrency={activeCurrency} value={subtotal} currency={currency} size="md" muted />
                </span>
              )}
            </SummaryRow>
            </div>

            {/* Course details */}
            <div className="border-t border-gray-100 p-6" dir={direction}>
              <CardSectionHeader
                title={l("courseDetails")}
                changeHref={detailsUrl}
                changeLabel={l("change")}
                isRtl={isRtl}
              />
              <div className={`space-y-1 ${textAlign}`}>
                <p className="text-sm font-medium text-slate-800">
                  {selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : "-"}
                </p>
                <p className="text-xs font-normal text-slate-500">
                  {l("startDate")}: {formattedStartDate}
                </p>
                <p className="text-xs font-normal text-slate-500">
                  {l("duration")}: {selection.weeks} {l("weeks")}
                </p>
              </div>
            </div>

            {selectedAccommodation && (
              <div className="border-t border-gray-100 p-6" dir={direction}>
                <CardSectionHeader
                  title={l("accDetails")}
                  changeHref={detailsUrl}
                  changeLabel={l("change")}
                  isRtl={isRtl}
                />
                <div className={`space-y-1 ${textAlign}`}>
                  <p className="text-sm font-medium text-slate-800">
                    {loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)}
                  </p>
                  {accFeatureSubtitle && (
                    <p className="text-xs font-normal text-slate-500">
                      {accFeatureSubtitle}
                    </p>
                  )}
                </div>
              </div>
            )}

            {(selectedPickup || selectedInsurances.length > 0 || selectedSupplements.length > 0) && (
              <div className="border-t border-gray-100 p-6" dir={direction}>
                <CardSectionHeader
                  title={l("addServices")}
                  changeHref={detailsUrl}
                  changeLabel={l("change")}
                  isRtl={isRtl}
                />
                <div className="space-y-3">
                  {selectedPickup && (
                    <SummaryServiceRow
                      isRtl={isRtl}
                      title={l("pickup")}
                      subtitle={loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)}
                    />
                  )}
                  {selectedInsurances.map((item) => (
                    <SummaryServiceRow
                      key={`ins-${item.id}`}
                      isRtl={isRtl}
                      title={loc(item.name, item.ar_name)}
                    />
                  ))}
                  {selectedSupplements.map((item) => (
                    <SummaryServiceRow
                      key={`supp-${item.id}`}
                      isRtl={isRtl}
                      title={loc(item.name, item.ar_name)}
                    />
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
