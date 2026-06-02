"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleInfo } from "@fortawesome/free-solid-svg-icons";
import { PasswordToggleButton } from "@/components/shared/auth-ui";
import { useLocale } from "@/components/providers/locale-provider";
import CountryPhoneInput from "@/components/shared/country-phone-input";
import { AUTH_COUNTRIES } from "@/lib/auth-countries";
import {
    fetchAuthMe,
    getStoredAuthToken,
    getStoredAuthUser,
    login,
    saveAuthSession,
} from "@/lib/auth";
import {
    submitLanguageCourseBooking,
    saveBookingConfirmation,
} from "@/lib/language-course-booking-api";
import { getReferralCodeForRequest } from "@/lib/referral";
import { formatInstituteQueryDate } from "@/lib/institute-booking-url";
import AgentStudentSelector from "@/components/shared/agent-student-selector";
import { useInstituteBooking } from "@/hooks/use-institute-booking";
import { BookingBackLink } from "@/components/mobile/institute-booking-ui";

export default function MobileInstituteBookingCheckout({ slug }) {
    const router = useRouter();
    const { language, direction, t } = useLocale();
    const isArabic = language === "ar";
    const isRtl = direction === "rtl";
    const textAlign = isRtl ? "text-right" : "text-left";
    const placeholderAlign = isRtl ? "placeholder:text-right" : "placeholder:text-left";

    const {
        loading,
        school,
        reviewUrl,
        l,
        selection,
        currency,
        selectedCourseId,
        selectedInsurances,
        selectedSupplements,
    } = useInstituteBooking(slug, { t, isArabic });

    const [formData, setFormData] = useState({ name: "", email: "", phone: "", password: "" });
    const [country, setCountry] = useState(AUTH_COUNTRIES[0]);
    const [countryOpen, setCountryOpen] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [submitting, setSubmitting] = useState(false);
    const [submitError, setSubmitError] = useState(null);
    const [authUser, setAuthUser] = useState(null);
    const [authLoading, setAuthLoading] = useState(true);
    const [guestFormMode, setGuestFormMode] = useState("register");
    const [loginLoading, setLoginLoading] = useState(false);
    const [selectedAgentStudentId, setSelectedAgentStudentId] = useState("");
    useEffect(() => {
        let active = true;

        async function loadAuth() {
            const storedUser = getStoredAuthUser();
            const token = getStoredAuthToken();

            if (storedUser) setAuthUser(storedUser);

            if (!token) {
                if (active) setAuthLoading(false);
                return;
            }

            try {
                const me = await fetchAuthMe(token);
                if (active && me?.user) setAuthUser(me.user);
            } catch {
                if (active) setAuthUser(storedUser);
            } finally {
                if (active) setAuthLoading(false);
            }
        }

        loadAuth();

        const onAuthUpdate = () => setAuthUser(getStoredAuthUser());
        window.addEventListener("auth-update", onAuthUpdate);
        return () => {
            active = false;
            window.removeEventListener("auth-update", onAuthUpdate);
        };
    }, []);

    const isAgentUser = authUser?.role === "lg_agent";

    const handleInputChange = (event) => {
        const { name, value } = event.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const buildPayload = useCallback((phoneValue, isLoggedIn) => {
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
        if (referralCode) payload.referral_code = referralCode;

        if (isLoggedIn && isAgentUser) {
            payload.agent_student_id = Number(selectedAgentStudentId);
        }

        return payload;
    }, [
        currency,
        formData.email,
        formData.name,
        formData.password,
        isAgentUser,
        selectedAgentStudentId,
        selectedCourseId,
        selectedInsurances,
        selectedSupplements,
        selection,
    ]);

    const submitBooking = useCallback(async (phoneValue) => {
        const token = getStoredAuthToken();
        const isLoggedIn = Boolean(authUser && token);
        const payload = buildPayload(phoneValue, isLoggedIn);

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
            if (bookingDetail) saveBookingConfirmation(bookingDetail);

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
    }, [authUser, buildPayload, isArabic, router]);

    const onGuestLogin = async (event) => {
        event?.preventDefault();
        setSubmitError(null);

        if (!formData.email?.trim() || !formData.password) {
            setSubmitError(l("loginFieldsRequired"));
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
            setSubmitError(error.message || l("loginFailed"));
        } finally {
            setLoginLoading(false);
        }
    };

    const onSendRequest = async (event) => {
        event?.preventDefault();
        setSubmitError(null);

        const token = getStoredAuthToken();
        const isLoggedIn = Boolean(authUser && token);

        if (isLoggedIn && isAgentUser && !selectedAgentStudentId) {
            setSubmitError(l("agentStudentRequired"));
            return;
        }

        if (!selectedCourseId || !selection.startDate) {
            setSubmitError(isArabic ? "يرجى اختيار الدورة وتاريخ البدء" : "Please select a course and start date");
            return;
        }

        if (isLoggedIn) {
            await submitBooking("");
            return;
        }

        if (guestFormMode === "login") {
            await onGuestLogin(event);
            return;
        }

        if (!formData.name || !formData.email || !formData.phone || !formData.password) {
            setSubmitError(isArabic ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields");
            return;
        }

        const phoneValue = `${country.dial}${String(formData.phone).replace(/\s+/g, "")}`;
        await submitBooking(phoneValue);
    };

    if (loading && !school) {
        return (
            <div className="flex min-h-[50vh] items-center justify-center bg-white">
                <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
            </div>
        );
    }

    if (!loading && !school) {
        return (
            <div className="flex min-h-[50vh] flex-col items-center justify-center bg-white px-4 text-center">
                <p className="text-lg text-slate-600">{isArabic ? "المعهد غير موجود" : "Institute not found"}</p>
                <Link href="/language-institutes" className="mt-4 text-[#0057B7] hover:underline">
                    {isArabic ? "العودة إلى المعاهد" : "Back to institutes"}
                </Link>
            </div>
        );
    }

    const submitLabel = authUser
        ? (submitting ? l("processing") : l("sendRequest"))
        : guestFormMode === "login"
            ? (loginLoading ? l("loggingIn") : l("loginButton"))
            : (submitting ? l("processing") : l("sendRequest"));

    return (
        <div className="min-h-screen bg-white pb-28" dir={direction}>
            <div className="border-b border-[#E8ECF1] bg-white px-4 pb-4 pt-5">
                <BookingBackLink href={reviewUrl} label={l("backToReview")} isRtl={isRtl} />
                <h1 className={`text-xl font-bold text-[#102233] ${textAlign}`}>{l("contactDetails")}</h1>
                <p className={`mt-2 text-sm leading-relaxed text-slate-500 ${textAlign}`}>
                    {l("checkoutSubtitle")}
                </p>
            </div>

            <div className="px-4 pt-6">
                {submitError && (
                    <div className={`mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600 ${textAlign}`}>
                        {submitError}
                    </div>
                )}

                <form
                    id="mobile-booking-checkout-form"
                    className="space-y-4"
                    onSubmit={onSendRequest}
                    dir={direction}
                >
                    {authLoading ? (
                        <div className="flex min-h-[200px] items-center justify-center">
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
                            <div className={`rounded-2xl border border-gray-100 bg-[#F8FAFC] p-4 ${textAlign}`}>
                                <p className="text-sm text-slate-500">{l("welcomeUser")}</p>
                                <p className="mt-1 text-base font-semibold text-[#102233]">{authUser.name}</p>
                                <p className="mt-1 text-sm text-slate-500">{authUser.email}</p>
                            </div>
                        )
                    ) : guestFormMode === "login" ? (
                        <>
                            <div>
                                <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                                    <span className="text-red-500">*</span> {l("email")}
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleInputChange}
                                    placeholder={l("enterEmail")}
                                    dir={direction}
                                    className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm outline-none focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                                />
                            </div>
                            <div>
                                <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                                    <span className="text-red-500">*</span> {l("password")}
                                </label>
                                <div className="relative">
                                    <input
                                        type={showPassword ? "text" : "password"}
                                        name="password"
                                        value={formData.password}
                                        onChange={handleInputChange}
                                        placeholder={l("placeholder_password")}
                                        dir={direction}
                                        className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm outline-none focus:border-[#0057B7] ${textAlign} ${placeholderAlign} ${isRtl ? "pl-12" : "pr-12"}`}
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
                                    <span className="text-red-500">*</span> {l("fullName")}
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleInputChange}
                                    placeholder={l("enterFullName")}
                                    dir={direction}
                                    className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm outline-none focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                                />
                            </div>
                            <div>
                                <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                                    <span className="text-red-500">*</span> {l("email")}
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleInputChange}
                                    placeholder={l("enterEmail")}
                                    dir={direction}
                                    className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm outline-none focus:border-[#0057B7] ${textAlign} ${placeholderAlign}`}
                                />
                            </div>
                            <div>
                                <label className={`mb-2 block text-sm font-medium text-[#102233] ${textAlign}`}>
                                    <span className="text-red-500">*</span> {l("mobile")}
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
                                        className={`h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm outline-none focus:border-[#0057B7] ${textAlign} ${placeholderAlign} ${isRtl ? "pl-12" : "pr-12"}`}
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
                                        className="font-medium text-[#0057B7]"
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
                                        className="font-medium text-[#0057B7]"
                                    >
                                        {l("loginLink")}
                                    </button>
                                </>
                            )}
                        </p>
                    )}

                    <p className={`inline-flex items-start gap-2 text-[11px] leading-relaxed text-slate-400 ${isRtl ? "flex-row-reverse text-right" : "text-left"}`}>
                        <FontAwesomeIcon icon={faCircleInfo} className="mt-0.5 shrink-0" />
                        <span>{l("disclaimer")}</span>
                    </p>
                </form>
            </div>

            <div className="fixed bottom-0 left-0 z-30 w-full border-t border-[#E8ECF1] bg-white px-4 py-4">
                <button
                    type="submit"
                    form="mobile-booking-checkout-form"
                    disabled={submitting || authLoading || loginLoading}
                    className="h-12 w-full rounded-2xl bg-[#0070CD] text-[15px] font-semibold text-white transition hover:bg-[#005EB3] disabled:opacity-50"
                >
                    {submitLabel}
                </button>
            </div>

        </div>
    );
}
