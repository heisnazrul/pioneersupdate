"use client";

import { useState } from "react";
import Image from "next/image";
import { useRouter } from "next/navigation";
import {
  getCourseEnglishDashboardPath,
  loginCourseEnglish,
  registerCourseEnglish,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const COUNTRIES = [
  { code: "SA", dialCode: "+966", flagFile: "sa.svg", name: "Saudi Arabia" },
  { code: "GB", dialCode: "+44", flagFile: "gb.svg", name: "United Kingdom" },
  { code: "US", dialCode: "+1", flagFile: "us.svg", name: "United States" },
];

function useSheetTranslations() {
  const { language } = useCourseEnglishSettings();
  const isArabic = language === "ar";
  const t = isArabic
    ? {
      welcome: "مرحباً 👋",
      welcomeBack: "مرحباً بعودتك 👋",
      createAccountTitle: "أنشئ حسابك",
      welcomeSub: "أنشئ حسابك لتكمل رحلتك.",
      loginSub: "سجّل دخولك لمتابعة رحلتك.",
      createByEmailSub: "أنشئ حسابك بالبريد الإلكتروني لتكمل رحلتك.",
      phonePlaceholder: "55 233 9595",
      enterPhone: "أدخل رقم الجوال.",
      sendOtpFailed: "فشل إرسال رمز التحقق.",
      verificationCode: "رمز التحقق",
      otpPlaceholder: "أدخل الرمز",
      enterOtp: "أدخل رمز التحقق.",
      verifyFailed: "فشل التحقق.",
      continueWithPhone: "المتابعة عبر الجوال",
      verifyAndContinue: "تحقق ومتابعة",
      signupWithEmailInstead: "التسجيل بالبريد الإلكتروني بدلاً من ذلك",
      alreadyHaveAccount: "لديك حساب بالفعل؟ سجّل الدخول",
      phoneComingSoon: "تسجيل الدخول عبر واتساب سيُضاف لاحقاً.",
      email: "البريد الإلكتروني",
      emailPlaceholder: "you@example.com",
      password: "كلمة المرور",
      passwordPlaceholder: "••••••••",
      confirmPassword: "تأكيد كلمة المرور",
      fillAllFields: "يرجى تعبئة جميع الحقول.",
      passwordsNoMatch: "كلمتا المرور غير متطابقتين.",
      createAccountFailed: "فشل إنشاء الحساب.",
      signupWithEmail: "إنشاء حساب بالبريد الإلكتروني",
      usePhoneInstead: "استخدم الجوال بدلاً من ذلك",
      enterEmailPassword: "أدخل البريد الإلكتروني وكلمة المرور.",
      loginFailed: "فشل تسجيل الدخول.",
      login: "تسجيل الدخول",
      pleaseWait: "يرجى الانتظار...",
    }
    : {
      welcome: "Welcome 👋",
      welcomeBack: "Welcome back 👋",
      createAccountTitle: "Create your account",
      welcomeSub: "Create your account to continue your journey.",
      loginSub: "Log in to continue your journey.",
      createByEmailSub: "Sign up with your email to continue your journey.",
      phonePlaceholder: "55 233 9595",
      enterPhone: "Enter your phone number.",
      sendOtpFailed: "Failed to send OTP.",
      verificationCode: "Verification code",
      otpPlaceholder: "Enter OTP",
      enterOtp: "Enter the verification code.",
      verifyFailed: "Verification failed.",
      continueWithPhone: "Continue with phone",
      verifyAndContinue: "Verify and continue",
      signupWithEmailInstead: "Sign up with email instead",
      alreadyHaveAccount: "Already have an account? Log in",
      phoneComingSoon: "WhatsApp login will be added later.",
      email: "Email",
      emailPlaceholder: "you@example.com",
      password: "Password",
      passwordPlaceholder: "••••••••",
      confirmPassword: "Confirm Password",
      fillAllFields: "Fill all fields.",
      passwordsNoMatch: "Passwords do not match.",
      createAccountFailed: "Failed to create account.",
      signupWithEmail: "Sign up with email",
      usePhoneInstead: "Use phone instead",
      enterEmailPassword: "Enter email and password.",
      loginFailed: "Login failed.",
      login: "Log in",
      pleaseWait: "Please wait...",
    };
  return { t, isArabic };
}

function BottomSheet({ isOpen, onClose, children }) {
  return (
    <>
      <div
        className={`fixed inset-0 z-[70] bg-black/40 transition-opacity ${isOpen ? "opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none"
          }`}
        onClick={onClose}
      />

      <div
        className={`fixed inset-0 z-[80] flex items-end transition-transform duration-300 ease-out ${isOpen ? "translate-y-0" : "translate-y-full"
          }`}
        onClick={onClose}
      >
        <div className="w-full" onClick={(e) => e.stopPropagation()}>
          <div className="mx-auto max-w-md rounded-t-3xl bg-white p-5 pb-[calc(env(safe-area-inset-bottom,0px)+20px)] shadow-2xl">
            <div className="mx-auto mb-4 h-1 w-12 rounded-full bg-gray-300" />
            {children}
          </div>
        </div>
      </div>
    </>
  );
}

export function PhoneSignUpSheet({
  isOpen,
  onClose,
  onOpenLogin,
  onOpenEmailSignUp,
}) {
  const { t, isArabic } = useSheetTranslations();
  const [selectedCountry, setSelectedCountry] = useState(COUNTRIES[0]);
  const [phone, setPhone] = useState("");
  const [showCountryList, setShowCountryList] = useState(false);
  const [otpSent] = useState(false);
  const [otp, setOtp] = useState("");
  const [loading] = useState(false);
  const [error, setError] = useState("");

  const handleSendOtp = async () => {
    setError(t.phoneComingSoon);
  };

  const handleVerifyOtp = async () => {
    setError(t.phoneComingSoon);
  };

  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <div className="space-y-5" dir={isArabic ? "rtl" : "ltr"}>
        <div className="text-center">
          <h2 className="text-lg font-normal">{t.welcome}</h2>
          <p className="mt-1 text-sm text-gray-500">{t.welcomeSub}</p>
        </div>

        <div className="flex rounded-2xl border border-gray-300 bg-gray-50 px-3 py-3">
          <input
            type="tel"
            className="flex-1 bg-transparent pr-3 text-sm outline-none"
            placeholder={t.phonePlaceholder}
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
          />

          <div className="mx-2 w-px bg-gray-300" />

          <button
            type="button"
            className="flex items-center gap-2 text-sm font-normal text-gray-900"
            onClick={() => setShowCountryList((v) => !v)}
          >
            <span className="flex items-center justify-center overflow-hidden border border-gray-200">
              <Image
                src={`/assets/flags/${selectedCountry.flagFile}`}
                alt={selectedCountry.name}
                width={20}
                height={20}
              />
            </span>
            <span>{selectedCountry.dialCode}</span>
            <span className="text-xs text-gray-500">▼</span>
          </button>
        </div>

        {showCountryList && (
          <div className="max-h-48 overflow-y-auto rounded-2xl border border-gray-200 bg-white text-sm shadow-md">
            {COUNTRIES.map((country) => (
              <button
                key={country.code}
                type="button"
                className="flex w-full items-center gap-2 px-3 py-2  hover:bg-gray-50"
                onClick={() => {
                  setSelectedCountry(country);
                  setShowCountryList(false);
                }}
              >
                <span className="flex items-center justify-center overflow-hidden border border-gray-200">
                  <Image
                    src={`/assets/flags/${country.flagFile}`}
                    alt={country.name}
                    width={18}
                    height={18}
                  />
                </span>
                <span className="flex-1">{country.name}</span>
                <span className="font-normal">{country.dialCode}</span>
              </button>
            ))}
          </div>
        )}

        {otpSent && (
          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.verificationCode}</label>
            <input
              type="text"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.otpPlaceholder}
              value={otp}
              onChange={(e) => setOtp(e.target.value)}
            />
          </div>
        )}

        {error ? <p className="text-xs font-normal text-red-600">{error}</p> : null}

        <button
          type="button"
          onClick={otpSent ? handleVerifyOtp : handleSendOtp}
          disabled={loading}
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700 disabled:opacity-60"
        >
          {loading ? t.pleaseWait : otpSent ? t.verifyAndContinue : t.continueWithPhone}
        </button>

        <button
          type="button"
          onClick={onOpenEmailSignUp}
          className="flex w-full items-center justify-center rounded-2xl border border-gray-300 bg-white py-3 text-sm font-normal text-gray-800 hover:bg-gray-50"
        >
          {t.signupWithEmailInstead}
        </button>

        <button
          type="button"
          onClick={onOpenLogin}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          {t.alreadyHaveAccount}
        </button>
      </div>
    </BottomSheet>
  );
}

export function EmailSignUpSheet({ isOpen, onClose, onUsePhone }) {
  const router = useRouter();
  const { t, isArabic } = useSheetTranslations();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    if (!email.trim() || !password.trim() || !confirmPassword.trim()) {
      setError(t.fillAllFields);
      return;
    }
    if (password !== confirmPassword) {
      setError(t.passwordsNoMatch);
      return;
    }

    try {
      setLoading(true);
      const data = await registerCourseEnglish({
        name: email.split("@")[0] || "Student",
        email: email.trim(),
        password,
        role: "lg_student",
      });

      saveCourseEnglishAuthSession(data);
      onClose?.();
      router.push(getCourseEnglishDashboardPath(data?.user?.role));
    } catch (err) {
      setError(err.message || t.createAccountFailed);
    } finally {
      setLoading(false);
    }
  };

  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <form className="space-y-5" onSubmit={handleSubmit} dir={isArabic ? "rtl" : "ltr"}>
        <div className="text-center">
          <h2 className="text-lg font-normal">{t.createAccountTitle}</h2>
          <p className="mt-1 text-sm text-gray-500">{t.createByEmailSub}</p>
        </div>

        <div className="space-y-3">
          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.email}</label>
            <input
              type="email"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.emailPlaceholder}
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.password}</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.passwordPlaceholder}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.confirmPassword}</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.passwordPlaceholder}
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
            />
          </div>
        </div>

        {error ? <p className="text-xs font-normal text-red-600">{error}</p> : null}

        <button
          type="submit"
          disabled={loading}
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700 disabled:opacity-60"
        >
          {loading ? t.pleaseWait : t.signupWithEmail}
        </button>

        <button
          type="button"
          onClick={onUsePhone}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          {t.usePhoneInstead}
        </button>
      </form>
    </BottomSheet>
  );
}

export function EmailLoginSheet({ isOpen, onClose, onUsePhone }) {
  const router = useRouter();
  const { t, isArabic } = useSheetTranslations();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    if (!email.trim() || !password.trim()) {
      setError(t.enterEmailPassword);
      return;
    }

    try {
      setLoading(true);
      const data = await loginCourseEnglish({ email: email.trim(), password });

      saveCourseEnglishAuthSession(data);
      onClose?.();
      router.push(getCourseEnglishDashboardPath(data?.user?.role));
    } catch (err) {
      setError(err.message || t.loginFailed);
    } finally {
      setLoading(false);
    }
  };

  return (
    <BottomSheet isOpen={isOpen} onClose={onClose}>
      <form className="space-y-5" onSubmit={handleSubmit} dir={isArabic ? "rtl" : "ltr"}>
        <div className="text-center">
          <h2 className="text-lg font-normal">{t.welcomeBack}</h2>
          <p className="mt-1 text-sm text-gray-500">{t.loginSub}</p>
        </div>

        <div className="space-y-3">
          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.email}</label>
            <input
              type="email"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.emailPlaceholder}
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div className="space-y-1">
            <label className="text-xs font-normal text-gray-600">{t.password}</label>
            <input
              type="password"
              className="w-full rounded-2xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-blue-500"
              placeholder={t.passwordPlaceholder}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>
        </div>

        {error ? <p className="text-xs font-normal text-red-600">{error}</p> : null}

        <button
          type="submit"
          disabled={loading}
          className="flex w-full items-center justify-center rounded-2xl bg-blue-600 py-3 text-sm font-normal text-white hover:bg-blue-700 disabled:opacity-60"
        >
          {loading ? t.pleaseWait : t.login}
        </button>

        <button
          type="button"
          onClick={onUsePhone}
          className="mx-auto block text-xs font-normal text-gray-500 underline"
        >
          {t.usePhoneInstead}
        </button>
      </form>
    </BottomSheet>
  );
}
