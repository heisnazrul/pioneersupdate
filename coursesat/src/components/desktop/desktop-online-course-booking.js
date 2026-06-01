"use client";

/* eslint-disable @next/next/no-img-element */
import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare, faCircleInfo, faHeart, faExchangeAlt } from "@fortawesome/free-solid-svg-icons";
import { PasswordToggleButton } from "@/components/shared/auth-ui";
import { useLocale } from "@/components/providers/locale-provider";
import { useCurrency } from "@/components/providers/currency-provider";
import CountryPhoneInput from "@/components/shared/country-phone-input";
import { AUTH_COUNTRIES } from "@/lib/auth-countries";
import { useApi } from "@/lib/api";
import { getCoursePrice } from "@/lib/format-currency";
import { CurrencyAmount } from "@/components/shared/currency-amount";
import {
  fetchAuthMe,
  getStoredAuthToken,
  getStoredAuthUser,
  login,
  saveAuthSession,
} from "@/lib/auth";
import { submitOnlineCourseBooking, saveBookingConfirmation } from "@/lib/language-course-booking-api";
import { getReferralCodeForRequest } from "@/lib/referral";
import AgentStudentSelector from "@/components/shared/agent-student-selector";
import { useCourseEnglishInteractions } from "@/lib/interactions";
import {
  buildOnlineCourseDetailsUrl,
  formatOnlineCourseQueryDate,
  readOnlineCourseSelectionFromSearchParams,
} from "@/lib/online-course-booking-url";

function Price({ value, currency, activeCurrency, className = "", size = "md", muted = false }) {
  if (value === null || value === undefined) return null;

  const iconClassName = size === "lg" ? "h-[24px] w-[24px]" : size === "sm" ? "h-[10px] w-[10px]" : "h-[14px] w-[14px]";
  const textClass = size === "lg" ? "text-[24px] font-bold" : size === "sm" ? "text-[14px]" : "text-[14px] font-medium";

  return (
    <CurrencyAmount
      currency={currency}
      amount={value}
      activeCurrency={activeCurrency}
      className={`inline-flex items-center ${textClass} ${className}`}
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

function isWeeklyCourse(course) {
  return course?.fee_type === "weekly" || course?.price_unit === "per week";
}

function resolveOnlineCourseTotal(course, weeks, currency) {
  if (!course) return 0;
  const unitPrice = getCoursePrice(course, currency, "new") || 0;
  if (isWeeklyCourse(course)) {
    return unitPrice * Math.max(1, Number(weeks) || 1);
  }
  return unitPrice;
}

export default function DesktopOnlineCourseBooking({ slug }) {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { language, direction, t } = useLocale();
  const { currency, activeCurrency } = useCurrency();
  const isArabic = language === "ar";
  const isRtl = direction === "rtl";
  const textAlign = isRtl ? "text-right" : "text-left";
  const placeholderAlign = isRtl ? "placeholder:text-right" : "placeholder:text-left";

  const selection = useMemo(
    () => readOnlineCourseSelectionFromSearchParams(searchParams),
    [searchParams],
  );

  const apiPath = slug
    ? `/courseenglish/online-courses/${encodeURIComponent(slug)}${searchParams.toString() ? `?${searchParams.toString()}` : ""}`
    : null;
  const { data: detailData, loading } = useApi(apiPath);

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
  const interactionType = "online_courses";

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

  const effectiveStartDate = selection.startDate || new Date();
  const detailsUrl = buildOnlineCourseDetailsUrl(slug, {
    courseId: selection.courseId,
    weeks: selection.weeks,
    startDate: formatOnlineCourseQueryDate(effectiveStartDate),
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

  const school = detailData?.school;
  const courses = detailData?.courses || [];

  const selectedCourse = courses.find((course) => Number(course.id) === Number(selection.courseId))
    || courses[0];
  const selectedCourseId = selectedCourse?.id ?? selection.courseId;
  const weeklySelected = isWeeklyCourse(selectedCourse);
  const coursePrice = resolveOnlineCourseTotal(selectedCourse, selection.weeks, currency);
  const registrationFee = Number(selectedCourse?.registration_fee ?? detailData?.registration_fee ?? 0);
  const totalPrice = coursePrice + registrationFee;

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

    if (!selectedCourseId) {
      setSubmitError(isArabic ? "يرجى اختيار الدورة" : "Please select a course");
      return;
    }

    const phoneValue = formData.phone
      ? `${country.dial}${String(formData.phone).replace(/\s+/g, "")}`
      : authUser?.phone || "";

    const payload = {
      selection: {
        course_id: selectedCourseId,
        weeks: weeklySelected ? selection.weeks : null,
        start_date: formatOnlineCourseQueryDate(effectiveStartDate),
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
      const result = await submitOnlineCourseBooking(payload, isLoggedIn ? token : null);

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

  const formattedStartDate = formatDisplayDate(effectiveStartDate, isArabic);
  const schoolName = loc(school?.name, school?.ar_name);
  const location = loc(school?.city || school?.location, school?.city_ar);
  const courseLabel = selectedCourse
    ? loc(selectedCourse.name || selectedCourse.title, selectedCourse.ar_name || selectedCourse.ar_title)
    : "-";

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
        <span>{isArabic ? "العودة إلى الدورة" : l("backToList")}</span>
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

      <div className="mb-8 lg:hidden" dir={direction}>
        <div className={textAlign}>{titleBlock}</div>
      </div>

      <div className="grid grid-cols-1 items-start gap-8 lg:grid-cols-2" dir="ltr">
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

        <div className="order-1 lg:order-2">
          <div className="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
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
                    <p className="text-right text-[10px] font-medium uppercase tracking-wide text-[#0057B7]">
                      {isArabic ? "دورة أونلاين" : "Online Course"}
                    </p>
                    <h3 className="text-right text-base font-semibold leading-tight text-slate-900">{schoolName}</h3>
                    <div className="text-right text-xs text-slate-400">{location}</div>
                  </div>
                  <div className="relative h-[88px] w-[88px] shrink-0 overflow-hidden rounded-xl border border-gray-50">
                    <img src={selectedCourse?.image || school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                  </div>
                </>
              ) : (
                <>
                  <div className="relative h-[88px] w-[88px] shrink-0 overflow-hidden rounded-xl border border-gray-50">
                    <img src={selectedCourse?.image || school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                  </div>
                  <div className="flex min-w-0 flex-1 flex-col justify-center gap-1 py-1">
                    <p className="text-left text-[10px] font-medium uppercase tracking-wide text-[#0057B7]">
                      {isArabic ? "دورة أونلاين" : "Online Course"}
                    </p>
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

            <div className="border-t border-gray-100 p-6" dir={direction}>
              <h3 className={`mb-6 text-lg font-semibold text-slate-900 ${textAlign}`}>{l("priceSummary")}</h3>

              <div className="space-y-4 text-[14px] text-[#102233]">
                {selectedCourse && (
                  <SummaryRow
                    isRtl={isRtl}
                    label={`${courseLabel}${weeklySelected ? ` (${selection.weeks} ${l("weeks")})` : ""}`}
                  >
                    <Price activeCurrency={activeCurrency} value={coursePrice} currency={currency} size="sm" />
                  </SummaryRow>
                )}

                {registrationFee > 0 && (
                  <SummaryRow isRtl={isRtl} label={l("registrationFee")}>
                    <Price activeCurrency={activeCurrency} value={registrationFee} currency={currency} size="sm" />
                  </SummaryRow>
                )}
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
                <Price activeCurrency={activeCurrency} value={totalPrice} currency={currency} size="lg" className="font-semibold text-[#0B5DB6]" />
              </SummaryRow>
            </div>

            <div className="border-t border-gray-100 p-6" dir={direction}>
              <CardSectionHeader
                title={l("courseDetails")}
                changeHref={detailsUrl}
                changeLabel={l("change")}
                isRtl={isRtl}
              />
              <div className={`space-y-1 ${textAlign}`}>
                <p className="text-sm font-medium text-slate-800">{courseLabel}</p>
                <p className="text-xs font-normal text-slate-500">
                  {l("startDate")}: {formattedStartDate}
                </p>
                {weeklySelected ? (
                  <p className="text-xs font-normal text-slate-500">
                    {l("duration")}: {selection.weeks} {l("weeks")}
                  </p>
                ) : null}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
