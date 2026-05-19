"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState, useEffect, useRef, useMemo } from "react";
import {
  getCourseEnglishDashboardPath,
  loginCourseEnglish,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import AuthLayout from "../components/AuthLayout";

const COUNTRIES = [
  { code: "sa", name: "Saudi Arabia", dial: "+966" },
  { code: "ae", name: "United Arab Emirates", dial: "+971" },
  { code: "kw", name: "Kuwait", dial: "+965" },
  { code: "qa", name: "Qatar", dial: "+974" },
  { code: "om", name: "Oman", dial: "+968" },
  { code: "bh", name: "Bahrain", dial: "+973" },
  { code: "eg", name: "Egypt", dial: "+20" },
  { code: "gb", name: "United Kingdom", dial: "+44" },
  { code: "us", name: "United States", dial: "+1" },
  { code: "bd", name: "Bangladesh", dial: "+880" },
];

export default function LoginPage() {
  const router = useRouter();
  const [mode, setMode] = useState("password");
  const [step, setStep] = useState("phone");
  const [country, setCountry] = useState(COUNTRIES[0]);
  const [countryOpen, setCountryOpen] = useState(false);
  const dropdownRef = useRef(null);

  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setCountryOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const [phone, setPhone] = useState("");
  const [otp, setOtp] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [showPassword, setShowPassword] = useState(false);

  useEffect(() => {
    setError("");
  }, [mode, step]);

  const fullPhone = useMemo(() => {
    const trimmed = phone.trim();
    if (!trimmed) return "";
    if (trimmed.startsWith("+")) return trimmed;
    return `${country.dial}${trimmed}`;
  }, [phone, country]);

  const { language } = useCourseEnglishSettings();

  const t = {
    en: {
      title: "Welcome Back",
      subtitle: "Log in to track your applications and view your booking details.",
      tabPhone: "Mobile Number",
      tabEmail: "Email Address",
      labelEmail: "Email Address",
      placeholderEmail: "Enter email address",
      labelPassword: "Password",
      placeholderPassword: "••••••••••••",
      forgotPassword: "Forgot Password?",
      rememberMe: "Remember Me",
      loginButton: "Log In",
      loggingIn: "Logging in...",
      noAccount: "Don't have an account?",
      createAccount: "Create New Account",
      labelPhone: "Mobile Number",
      placeholderPhone: "5xxxxxxxx",
      sendButton: "Log In", // Applying same label as design often uses Login for sending OTP initially
      sending: "Sending...",
      verifyTitle: "Verify Mobile Number",
      verifyDesc: "Verification code sent to number",
      changeNumber: "Change Number",
      verifyButton: "Verify Login",
      verifying: "Verifying...",
      resend: "Resend",
      didNotReceive: "Didn't receive the code?",
      errorPhone: "Please enter your mobile number.",
      errorOtp: "Enter the 6-digit code.",
      errorEmailPass: "Enter email and password.",
      errorLoginFailed: "Login failed.",
      errorVerifyFailed: "Verification failed.",
      errorSendFailed: "Failed to send OTP.",
      otpLater: "WhatsApp OTP login will be added later.",
    },
    ar: {
      title: "مرحباً بعودتك",
      subtitle: "سجل دخولك لمتابعة طلباتك والاطلاع على تفاصيل حجوزاتك.",
      tabPhone: "رقم الجوال",
      tabEmail: "البريد الالكتروني",
      labelEmail: "البريد الالكتروني",
      placeholderEmail: "أدخل البريد الالكتروني",
      labelPassword: "كلمة المرور",
      placeholderPassword: "••••••••••••",
      forgotPassword: "هل نسيت كلمة المرور ؟",
      rememberMe: "تذكرني",
      loginButton: "تسجيل الدخول",
      loggingIn: "جاري تسجيل الدخول...",
      noAccount: "ليس لديك حساب؟",
      createAccount: "إنشاء حساب جديد",
      labelPhone: "رقم الجوال",
      placeholderPhone: "5xxxxxxxx",
      sendButton: "تسجيل الدخول",
      sending: "جاري الإرسال...",
      verifyTitle: "تأكيد رقم الجوال",
      verifyDesc: "تم إرسال رمز التحقق إلى الرقم",
      changeNumber: "تغيير الرقم",
      verifyButton: "تأكيد الدخول",
      verifying: "جاري التحقق...",
      resend: "إعادة إرسال",
      didNotReceive: "لم يصلك الرمز؟",
      errorPhone: "الرجاء إدخال رقم الجوال.",
      errorOtp: "أدخل الرمز المكون من 6 أرقام.",
      errorEmailPass: "أدخل البريد الإلكتروني وكلمة المرور.",
      errorLoginFailed: "فشل تسجيل الدخول.",
      errorVerifyFailed: "فشل التحقق.",
      errorSendFailed: "فشل إرسال الرمز.",
      otpLater: "سيتم إضافة تسجيل الدخول عبر واتساب لاحقاً.",
    }
  };

  const currentT = t[language] || t.en;
  const isRTL = language === "ar";

  // Update Country Names based on language? (Optional, kept English for now or add map)

  const handleSendCode = async (event) => {
    event.preventDefault();
    setError("");
    setError(currentT.otpLater);
  };

  const handleVerify = async (event) => {
    event.preventDefault();
    setError("");
    setError(currentT.otpLater);
  };

  const handlePasswordLogin = async (event) => {
    event.preventDefault();
    setError("");
    if (!email.trim() || !password) {
      setError(currentT.errorEmailPass);
      return;
    }
    try {
      setLoading(true);
      const data = await loginCourseEnglish({ email: email.trim(), password });
      saveCourseEnglishAuthSession(data);
      router.push(getCourseEnglishDashboardPath(data?.user?.role));
    } catch (err) {
      setError(err.message || currentT.errorLoginFailed);
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthLayout
      title={currentT.title}
      subtitle={currentT.subtitle}
    >
      {/* Tabs Switcher */}
      <div className="flex bg-slate-100/80 p-1.5 rounded-2xl mb-8 relative">
        <button
          type="button"
          onClick={() => {
            setMode("otp");
            setStep("phone");
          }}
          className={`flex-1 py-3.5 rounded-xl text-sm font-medium transition-all duration-300 ${mode === "otp"
            ? "bg-white text-[#0057B7] shadow-sm ring-1 ring-black/5"
            : "text-slate-500 hover:text-slate-700 hover:bg-slate-200/50"
            }`}
        >
          {currentT.tabPhone}
        </button>
        <button
          type="button"
          onClick={() => setMode("password")}
          className={`flex-1 py-3.5 rounded-xl text-sm font-medium transition-all duration-300 ${mode === "password"
            ? "bg-white text-[#0057B7] shadow-sm ring-1 ring-black/5"
            : "text-slate-500 hover:text-slate-700 hover:bg-slate-200/50"
            }`}
        >
          {currentT.tabEmail}
        </button>
      </div>

      {mode === "password" ? (
        <form className="space-y-6" onSubmit={handlePasswordLogin}>
          {/* Email Input */}
          <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
            <label className="text-sm font-medium text-slate-900">{currentT.labelEmail}</label>
            <input
              type="email"
              name="email"
              placeholder={currentT.placeholderEmail}
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              className="w-full rounded-2xl bg-white border border-slate-200 px-5 py-4 text-sm font-normal text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400 shadow-sm"
            />
          </div>

          {/* Password Input */}
          <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
            <div className="flex justify-between items-center">
              <label className="text-sm font-medium text-slate-900">{currentT.labelPassword}</label>
            </div>
            <div className="relative">
              <input
                type={showPassword ? "text" : "password"}
                name="password"
                placeholder={currentT.placeholderPassword}
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                className="w-full rounded-2xl bg-white border border-slate-200 px-5 py-4 text-sm font-normal text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400 shadow-sm pr-12"
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className={`absolute top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors ${isRTL ? 'left-4' : 'right-4'}`}
              >
                {showPassword ? (
                  <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                ) : (
                  <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                )}
              </button>
            </div>
          </div>

          <div className="flex justify-between items-center text-sm">
            <label className="flex items-center gap-2 cursor-pointer text-slate-600 font-normal select-none">
              <input type="checkbox" className="w-5 h-5 rounded border-gray-300 text-[#0057B7] focus:ring-[#0057B7]" />
              <span>{currentT.rememberMe}</span>
            </label>
            <Link href="/forget" className="font-medium text-[#0057B7] hover:underline">
              {currentT.forgotPassword}
            </Link>
          </div>

          {error && (
            <div className="rounded-xl bg-red-50 p-4 text-sm text-red-600 font-medium border border-red-100 text-center flex items-center justify-center gap-2">
              <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full rounded-2xl bg-[#0057B7] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#004494] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
          >
            {loading ? currentT.loggingIn : currentT.loginButton}
          </button>

          <div className="mt-6 text-center text-sm font-medium text-slate-600">
            {currentT.noAccount}{" "}
            <Link href="/signup" className="text-[#0057B7] hover:underline">
              {currentT.createAccount}
            </Link>
          </div>
        </form>
      ) : step === "phone" ? (
        <form className="space-y-6" onSubmit={handleSendCode}>
          {/* Phone Input */}
          <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
            <label className="text-sm font-medium text-slate-900">{currentT.labelPhone}</label>

            <div
              className="group relative flex items-center w-full rounded-2xl bg-white border border-slate-200 transition-all focus-within:border-[#0057B7] focus-within:ring-4 focus-within:ring-blue-500/10"
            >
              {/* Country Selector */}
              <div className="relative h-full border-e border-slate-100" ref={dropdownRef}>
                <button
                  type="button"
                  onClick={() => setCountryOpen((open) => !open)}
                  className={`h-full flex items-center gap-2 px-4 bg-slate-50/50 hover:bg-slate-50 transition-colors ${isRTL ? 'border-l rounded-r-2xl' : 'border-r rounded-l-2xl'} border-slate-100`}
                >
                  <img
                    src={`/assets/flags/${country.code}.svg`}
                    alt={country.name}
                    className="h-5 w-7 object-cover shadow-sm"
                  />
                  <span className="font-medium text-slate-700 text-sm tracking-wide" dir="ltr">{country.dial}</span>
                  <svg className={`w-3 h-3 text-slate-400 transition-transform ${countryOpen ? "rotate-180" : ""}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                {countryOpen && (
                  <div className={`absolute top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-y-auto max-h-60 py-1 ${isRTL ? 'right-0' : 'left-0'}`}>
                    {COUNTRIES.map((item) => (
                      <button
                        key={item.code}
                        type="button"
                        onClick={() => {
                          setCountry(item);
                          setCountryOpen(false);
                        }}
                        className={`w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors ${isRTL ? 'text-right' : 'text-left'}`}
                      >
                        <img src={`/assets/flags/${item.code}.svg`} alt={item.name} className="h-5 w-7 object-cover shadow-sm" />
                        <span className="flex-1 font-normal text-slate-700 text-sm">{item.name}</span>
                        <span className="text-xs font-medium text-slate-600" dir="ltr">{item.dial}</span>
                      </button>
                    ))}
                  </div>
                )}
              </div>

              <input
                type="tel"
                name="phone"
                placeholder={currentT.placeholderPhone}
                value={phone}
                onChange={(event) => setPhone(event.target.value)}
                className={`flex-1 bg-transparent px-4 py-4 text-base font-medium text-slate-900 outline-none placeholder:text-slate-300 placeholder:font-normal ${isRTL ? 'text-right' : 'text-left'}`}
                dir="ltr"
              />
            </div>
          </div>

          {error && (
            <div className="rounded-xl bg-red-50 p-4 text-sm text-red-600 font-medium border border-red-100 text-center flex items-center justify-center gap-2">
              <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full rounded-2xl bg-[#0057B7] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#004494] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
          >
            {loading ? currentT.sending : currentT.sendButton}
          </button>

          <div className="mt-6 text-center text-sm font-medium text-slate-600">
            {currentT.noAccount}{" "}
            <Link href="/signup" className="text-[#0057B7] hover:underline">
              {currentT.createAccount}
            </Link>
          </div>
        </form>
      ) : (
        <form className="mt-8 space-y-6" onSubmit={handleVerify}>
          <div className="text-center space-y-2 mb-6">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-[#0057B7] mb-4">
              <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h3 className="text-xl font-medium text-slate-900">{currentT.verifyTitle}</h3>
            <p className="text-slate-500 text-sm leading-relaxed">
              {currentT.verifyDesc} <br />
              <span dir="ltr" className="font-medium text-slate-900 mx-1 text-base">{fullPhone}</span>
              <button
                type="button"
                onClick={() => setStep("phone")}
                className="text-[#0057B7] underline text-xs font-medium mr-1 hover:text-[#004494]"
              >
                {currentT.changeNumber}
              </button>
            </p>
          </div>

          <div>
            <input
              type="text"
              name="otp"
              inputMode="numeric"
              placeholder="- - - - - -"
              value={otp}
              onChange={(event) => setOtp(event.target.value)}
              className="w-full text-center text-3xl tracking-[0.5em] font-medium rounded-2xl bg-white border border-slate-200 px-5 py-5 text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-200 shadow-sm"
              maxLength={6}
            />
          </div>

          {error && (
            <div className="rounded-xl bg-red-50 p-4 text-sm text-red-600 font-medium border border-red-100 text-center">
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full rounded-2xl bg-[#0057B7] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#004494] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
          >
            {loading ? currentT.verifying : currentT.verifyButton}
          </button>

          <div className="text-center">
            <p className="text-sm text-slate-500 font-normal">
              {currentT.didNotReceive}{" "}
              <button type="button" className="text-[#0057B7] font-medium hover:underline">
                {currentT.resend}
              </button>
            </p>
          </div>
        </form>
      )}
    </AuthLayout>
  );
}
