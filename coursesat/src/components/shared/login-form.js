"use client";

import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { useMemo, useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import CountryPhoneInput from "@/components/shared/country-phone-input";
import {
  AuthErrorBox,
  PasswordToggleButton,
  authButtonClass,
  authInputClass,
} from "@/components/shared/auth-ui";
import { AUTH_COUNTRIES } from "@/lib/auth-countries";
import { getDashboardPath, login, saveAuthSession } from "@/lib/auth";

export default function LoginForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const redirectTo = searchParams.get("redirect") || "";
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";

  const [mode, setMode] = useState("password");
  const [step, setStep] = useState("phone");
  const [country, setCountry] = useState(AUTH_COUNTRIES[0]);
  const [countryOpen, setCountryOpen] = useState(false);
  const [phone, setPhone] = useState("");
  const [otp, setOtp] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [showPassword, setShowPassword] = useState(false);

  const loc = (key, fallback = "") => t(`pages.login.${key}`, fallback);

  const fullPhone = useMemo(() => {
    const trimmed = phone.trim();
    if (!trimmed) return "";
    if (trimmed.startsWith("+")) return trimmed;
    return `${country.dial}${trimmed}`;
  }, [phone, country]);

  const handleSendCode = async (event) => {
    event.preventDefault();
    setError(loc("otp_later", "WhatsApp OTP login will be added later."));
  };

  const handleVerify = async (event) => {
    event.preventDefault();
    setError(loc("otp_later", "WhatsApp OTP login will be added later."));
  };

  const handlePasswordLogin = async (event) => {
    event.preventDefault();
    setError("");
    if (!email.trim() || !password) {
      setError(loc("error_email_pass", "Enter email and password."));
      return;
    }
    try {
      setLoading(true);
      const data = await login({ email: email.trim(), password });
      saveAuthSession(data);
      if (redirectTo && redirectTo.startsWith("/")) {
        router.push(redirectTo);
      } else {
        router.push(getDashboardPath(data?.user?.role));
      }
    } catch (err) {
      setError(err.message || loc("error_login_failed", "Login failed."));
    } finally {
      setLoading(false);
    }
  };

  const tabClass = (active) =>
    `flex-1 rounded-xl py-3.5 text-sm font-medium transition-all duration-300 ${
      active
        ? "bg-white text-[#135FAE] shadow-sm ring-1 ring-black/5"
        : "text-slate-500 hover:bg-slate-200/50 hover:text-slate-700"
    }`;

  return (
    <>
      <div className="relative mb-8 flex rounded-2xl bg-slate-100/80 p-1.5">
        <button type="button" onClick={() => { setError(""); setMode("otp"); setStep("phone"); }} className={tabClass(mode === "otp")}>
          {loc("tab_phone", "Mobile Number")}
        </button>
        <button type="button" onClick={() => { setError(""); setMode("password"); }} className={tabClass(mode === "password")}>
          {loc("tab_email", "Email Address")}
        </button>
      </div>

      {mode === "password" ? (
        <form className="space-y-6" onSubmit={handlePasswordLogin}>
          <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
            <label className="text-sm font-medium text-slate-900">{loc("label_email", "Email Address")}</label>
            <input
              type="email"
              name="email"
              placeholder={loc("placeholder_email", "Enter email address")}
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              className={authInputClass}
            />
          </div>

          <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
            <label className="text-sm font-medium text-slate-900">{loc("label_password", "Password")}</label>
            <div className="relative">
              <input
                type={showPassword ? "text" : "password"}
                name="password"
                placeholder={loc("placeholder_password", "••••••••••••")}
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                className={`${authInputClass} ${isRtl ? "pl-12" : "pr-12"}`}
              />
              <PasswordToggleButton
                show={showPassword}
                onToggle={() => setShowPassword(!showPassword)}
                isRtl={isRtl}
              />
            </div>
          </div>

          <div className="flex items-center justify-between text-sm">
            <label className="flex cursor-pointer select-none items-center gap-2 font-normal text-slate-600">
              <input type="checkbox" className="h-5 w-5 rounded border-gray-300 text-[#135FAE] focus:ring-[#135FAE]" />
              <span>{loc("remember_me", "Remember Me")}</span>
            </label>
            <Link href="/forget" className="font-medium text-[#135FAE] hover:underline">
              {loc("forgot_password", "Forgot Password?")}
            </Link>
          </div>

          <AuthErrorBox message={error} />

          <button type="submit" disabled={loading} className={authButtonClass}>
            {loading ? loc("logging_in", "Logging in...") : loc("login_button", "Log In")}
          </button>

          <div className="mt-6 text-center text-sm font-medium text-slate-600">
            {loc("no_account", "Don't have an account?")}{" "}
            <Link href="/signup" className="text-[#135FAE] hover:underline">
              {loc("create_account", "Create New Account")}
            </Link>
          </div>
        </form>
      ) : step === "phone" ? (
        <form className="space-y-6" onSubmit={handleSendCode}>
          <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
            <label className="text-sm font-medium text-slate-900">{loc("label_phone", "Mobile Number")}</label>
            <CountryPhoneInput
              country={country}
              onCountryChange={setCountry}
              countryOpen={countryOpen}
              onCountryOpenChange={setCountryOpen}
              value={phone}
              onChange={(event) => setPhone(event.target.value)}
              placeholder={loc("placeholder_phone", "5xxxxxxxx")}
              isRtl={isRtl}
            />
          </div>

          <AuthErrorBox message={error} />

          <button type="submit" disabled={loading} className={authButtonClass}>
            {loading ? loc("sending", "Sending...") : loc("send_button", "Log In")}
          </button>

          <div className="mt-6 text-center text-sm font-medium text-slate-600">
            {loc("no_account", "Don't have an account?")}{" "}
            <Link href="/signup" className="text-[#135FAE] hover:underline">
              {loc("create_account", "Create New Account")}
            </Link>
          </div>
        </form>
      ) : (
        <form className="mt-8 space-y-6" onSubmit={handleVerify}>
          <div className="mb-6 space-y-2 text-center">
            <div className="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-[#135FAE]">
              <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 className="text-xl font-medium text-slate-900">{loc("verify_title", "Verify Mobile Number")}</h3>
            <p className="text-sm leading-relaxed text-slate-500">
              {loc("verify_desc", "Verification code sent to number")} <br />
              <span dir="ltr" className="mx-1 text-base font-medium text-slate-900">
                {fullPhone}
              </span>
              <button
                type="button"
                onClick={() => { setError(""); setStep("phone"); }}
                className="mr-1 text-xs font-medium text-[#135FAE] underline hover:text-[#104a8a]"
              >
                {loc("change_number", "Change Number")}
              </button>
            </p>
          </div>

          <input
            type="text"
            name="otp"
            inputMode="numeric"
            placeholder="- - - - - -"
            value={otp}
            onChange={(event) => setOtp(event.target.value)}
            className="w-full rounded-2xl border border-slate-200 bg-white px-5 py-5 text-center text-3xl font-medium tracking-[0.5em] text-slate-900 shadow-sm outline-none transition-all placeholder:text-slate-200 focus:border-[#135FAE] focus:ring-4 focus:ring-blue-500/10"
            maxLength={6}
          />

          <AuthErrorBox message={error} />

          <button type="submit" disabled={loading} className={authButtonClass}>
            {loading ? loc("verifying", "Verifying...") : loc("verify_button", "Verify Login")}
          </button>

          <div className="text-center">
            <p className="text-sm font-normal text-slate-500">
              {loc("did_not_receive", "Didn't receive the code?")}{" "}
              <button type="button" className="font-medium text-[#135FAE] hover:underline">
                {loc("resend", "Resend")}
              </button>
            </p>
          </div>
        </form>
      )}
    </>
  );
}
