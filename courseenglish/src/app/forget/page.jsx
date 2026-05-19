"use client";

import Link from "next/link";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import AuthLayout from "../components/AuthLayout";

export default function ForgotPasswordPage() {
  const { language } = useCourseEnglishSettings();

  const t = {
    en: {
      title: "Reset Password",
      subtitle: "Enter your registered email address to receive a password reset link.",
      email: "Email Address",
      placeholderEmail: "Enter email address",
      resetButton: "Reset Password",
      sending: "Sending...",
      remembered: "Remembered your password?",
      backToLogin: "Back to Login",
      errorEmail: "Email is required.",
      errorFailed: "Failed to send reset link.",
    },
    ar: {
      title: "استعادة كلمة المرور",
      subtitle: "أدخل بريدك الإلكتروني المُسجّل ليصلك رابط إعادة تعيين كلمة المرور.",
      email: "البريد الالكتروني",
      placeholderEmail: "أدخل البريد الالكتروني",
      resetButton: "استعادة كلمة المرور",
      sending: "جاري الإرسال...",
      remembered: "تذكرت كلمة المرور؟",
      backToLogin: "العودة لتسجيل الدخول",
      errorEmail: "البريد الالكتروني مطلوب.",
      errorFailed: "فشل إرسال الرابط.",
    }
  };

  const currentT = t[language] || t.en;
  const isRTL = language === "ar";

  // Logic to handle reset would go here, adding basic state to show 'sending' for better UI feedback
  // kept simple as per original file structure 

  return (
    <AuthLayout
      title={currentT.title}
      subtitle={currentT.subtitle}
    >
      <form className="mt-8 space-y-6">
        <div className={`space-y-2 ${isRTL ? 'text-right' : 'text-left'}`}>
          <label className="text-sm font-medium text-slate-900">{currentT.email}</label>
          <input
            type="email"
            name="email"
            placeholder={currentT.placeholderEmail}
            className="w-full rounded-2xl bg-white border border-slate-200 px-5 py-4 text-sm font-normal text-slate-900 outline-none transition-all focus:border-[#0057B7] focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400 shadow-sm"
          />
        </div>

        <button
          type="submit"
          className="w-full rounded-2xl bg-[#0057B7] py-4 text-base font-medium text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#004494] active:scale-[0.98]"
        >
          {currentT.resetButton}
        </button>
      </form>

      <div className="mt-8 text-center text-sm font-medium text-slate-600">
        {currentT.remembered}{" "}
        <Link href="/login" className="text-[#0057B7] hover:underline">
          {currentT.backToLogin}
        </Link>
      </div>
    </AuthLayout>
  );
}
