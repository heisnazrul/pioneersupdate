"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState, useEffect, useRef } from "react";
import {
  getCourseEnglishDashboardPath,
  registerCourseEnglish,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import AuthLayout from "../components/AuthLayout";

// ... (existing imports) ...

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

export default function SignupPage() {
  const router = useRouter();

  const [form, setForm] = useState({
    name: "",
    email: "",
    phone: "",
    password: "",
    agreed: false,
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [showPassword, setShowPassword] = useState(false);
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

  const onChange = (field) => (event) => {
    const value = field === "agreed" ? event.target.checked : event.target.value;
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const { language } = useCourseEnglishSettings();

  const t = {
    en: {
      title: "Create Your Account",
      subtitle: "Create your account to send applications and track your bookings easily.",
      fullName: "Full Name",
      placeholderName: "Enter full name",
      email: "Email Address",
      placeholderEmail: "Enter email address",
      mobile: "Mobile Number",
      placeholderPhone: "5xxxxxxxx",
      password: "Password",
      placeholderPassword: "••••••••••••",
      agreePrefix: "I agree to",
      terms: "Terms & Conditions",
      and: "and",
      privacy: "Privacy Policy",
      suffix: "of Course English",
      createButton: "Create New Account",
      creating: "Creating Account...",
      alreadyHaveAccount: "Already have an account?",
      login: "Log In",
      errorRequired: "Name, email, and password are required.",
      errorTerms: "You must agree to the Terms and Conditions.",
      errorFailed: "Failed to create account.",
    },
    ar: {
      title: "أنشئ حسابك",
      subtitle: "أنشئ حسابك لتتمكن من إرسال الطلبات ومتابعة حجوزاتك بسهولة.",
      fullName: "الاسم بالكامل",
      placeholderName: "أدخل الاسم بالكامل",
      email: "البريد الالكتروني",
      placeholderEmail: "أدخل البريد الالكتروني",
      mobile: "رقم الجوال",
      placeholderPhone: "5xxxxxxxx",
      password: "كلمة المرور",
      placeholderPassword: "••••••••••••",
      agreePrefix: "أوافق على",
      terms: "الشروط والأحكام",
      and: "و",
      privacy: "سياسة الاستخدام",
      suffix: "بكورس إنجليزي",
      createButton: "انشاء حساب جديد",
      creating: "جاري إنشاء الحساب...",
      alreadyHaveAccount: "لديك حساب بالفعل؟",
      login: "تسجيل الدخول",
      errorRequired: "الاسم والبريد الالكتروني وكلمة المرور مطلوبة.",
      errorTerms: "يجب الموافقة على الشروط والأحكام.",
      errorFailed: "فشل إنشاء الحساب.",
    }
  };

  const currentT = t[language] || t.en;
  const isRTL = language === "ar";

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");

    if (!form.name.trim() || !form.email.trim() || !form.password.trim()) {
      setError(currentT.errorRequired);
      return;
    }

    if (!form.agreed) {
      setError(currentT.errorTerms);
      return;
    }

    try {
      setLoading(true);

      let finalPhone = form.phone.trim();
      if (finalPhone && !finalPhone.startsWith("+")) {
        // Remove leading zero if present
        if (finalPhone.startsWith("0")) {
          finalPhone = finalPhone.substring(1);
        }
        finalPhone = `${country.dial}${finalPhone}`;
      }

      const data = await registerCourseEnglish({
        name: form.name.trim(),
        email: form.email.trim(),
        phone: finalPhone || null,
        password: form.password,
        role: "lg_student",
      });

      saveCourseEnglishAuthSession(data);
      router.push(getCourseEnglishDashboardPath(data?.user?.role));
    } catch (err) {
      setError(err.message || currentT.errorFailed);
    } finally {
      setLoading(false);
    }
  };

  return (
    <AuthLayout
      title={currentT.title}
      subtitle={currentT.subtitle}
    >
      <form className="mt-8 space-y-5" onSubmit={handleSubmit}>
        <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
          <label className="text-sm font-medium text-slate-900">{currentT.fullName}</label>
          <input
            type="text"
            name="name"
            placeholder={currentT.placeholderName}
            value={form.name}
            onChange={onChange("name")}
            className="w-full rounded-2xl bg-white border border-slate-200 px-5 py-4 text-sm font-normal text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400 shadow-sm"
          />
        </div>

        <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
          <label className="text-sm font-medium text-slate-900">{currentT.email}</label>
          <input
            type="email"
            name="email"
            placeholder={currentT.placeholderEmail}
            value={form.email}
            onChange={onChange("email")}
            className="w-full rounded-2xl bg-white border border-slate-200 px-5 py-4 text-sm font-normal text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400 shadow-sm"
          />
        </div>

        <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
          <label className="text-sm font-medium text-slate-900">{currentT.mobile}</label>
          <div
            className="group relative flex items-center w-full rounded-2xl bg-white border border-slate-200 transition-all focus-within:border-[#0057B7] focus-within:ring-4 focus-within:ring-blue-500/10 shadow-sm"
          >
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
              value={form.phone}
              onChange={onChange("phone")}
              className={`flex-1 bg-transparent px-4 py-4 text-base font-medium text-slate-900 outline-none placeholder:text-slate-300 placeholder:font-normal ${isRTL ? 'text-right' : 'text-left'}`}
              dir="ltr"
            />
          </div>
        </div>

        <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
          <label className="text-sm font-medium text-slate-900">{currentT.password}</label>
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              name="password"
              placeholder={currentT.placeholderPassword}
              value={form.password}
              onChange={onChange("password")}
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

        <label className="flex items-start gap-3 w-full cursor-pointer group">
          <div className="relative flex items-center">
            <input
              type="checkbox"
              checked={form.agreed}
              onChange={onChange("agreed")}
              className="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-[#0057B7] checked:bg-[#0057B7]"
            />
            <svg className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none opacity-0 peer-checked:opacity-100 text-white transition-opacity" viewBox="0 0 14 14" fill="none">
              <path d="M3 7L5.5 9.5L11 4" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>
          <span className="text-sm text-slate-600 font-normal group-hover:text-slate-900 transition-colors">
            {currentT.agreePrefix} <Link href="/terms" className="text-[#0057B7] font-medium hover:underline">{currentT.terms}</Link> {currentT.and} <Link href="/privacy" className="text-[#0057B7] font-medium hover:underline">{currentT.privacy}</Link> {currentT.suffix}
          </span>
        </label>

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
          {loading ? currentT.creating : currentT.createButton}
        </button>
      </form>

      <div className="mt-8 text-center text-sm font-medium text-slate-600">
        {currentT.alreadyHaveAccount}{" "}
        <Link href="/login" className="text-[#0057B7] hover:underline">
          {currentT.login}
        </Link>
      </div>
    </AuthLayout>
  );
}
