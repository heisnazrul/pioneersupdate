"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import CountryPhoneInput from "@/components/shared/country-phone-input";
import {
  AuthErrorBox,
  PasswordToggleButton,
  authButtonClass,
  authInputClass,
} from "@/components/shared/auth-ui";
import { AUTH_COUNTRIES } from "@/lib/auth-countries";
import { getDashboardPath, register, saveAuthSession } from "@/lib/auth";
import { getReferralCodeForRequest } from "@/lib/referral";

export default function SignupForm() {
  const router = useRouter();
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.signup.${key}`, fallback);

  const [form, setForm] = useState({
    name: "",
    email: "",
    phone: "",
    password: "",
    agreed: false,
  });
  const [country, setCountry] = useState(AUTH_COUNTRIES[0]);
  const [countryOpen, setCountryOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [showPassword, setShowPassword] = useState(false);

  const onChange = (field) => (event) => {
    const value = field === "agreed" ? event.target.checked : event.target.value;
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");

    if (!form.name.trim() || !form.email.trim() || !form.password.trim()) {
      setError(loc("error_required", "Name, email, and password are required."));
      return;
    }

    if (!form.agreed) {
      setError(loc("error_terms", "You must agree to the Terms and Conditions."));
      return;
    }

    try {
      setLoading(true);

      let finalPhone = form.phone.trim();
      if (finalPhone && !finalPhone.startsWith("+")) {
        if (finalPhone.startsWith("0")) {
          finalPhone = finalPhone.substring(1);
        }
        finalPhone = `${country.dial}${finalPhone}`;
      }

      const referralCode = getReferralCodeForRequest();
      const data = await register({
        name: form.name.trim(),
        email: form.email.trim(),
        phone: finalPhone || null,
        password: form.password,
        role: "lg_student",
        ...(referralCode ? { referral_code: referralCode } : {}),
      });

      saveAuthSession(data);
      router.push(getDashboardPath(data?.user?.role));
    } catch (err) {
      setError(err.message || loc("error_failed", "Failed to create account."));
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <form className="mt-2 space-y-5" onSubmit={handleSubmit}>
        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("full_name", "Full Name")}</label>
          <input
            type="text"
            name="name"
            placeholder={loc("placeholder_name", "Enter full name")}
            value={form.name}
            onChange={onChange("name")}
            className={authInputClass}
          />
        </div>

        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("email", "Email Address")}</label>
          <input
            type="email"
            name="email"
            placeholder={loc("placeholder_email", "Enter email address")}
            value={form.email}
            onChange={onChange("email")}
            className={authInputClass}
          />
        </div>

        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("mobile", "Mobile Number")}</label>
          <CountryPhoneInput
            country={country}
            onCountryChange={setCountry}
            countryOpen={countryOpen}
            onCountryOpenChange={setCountryOpen}
            value={form.phone}
            onChange={onChange("phone")}
            placeholder={loc("placeholder_phone", "5xxxxxxxx")}
            isRtl={isRtl}
          />
        </div>

        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("password", "Password")}</label>
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              name="password"
              placeholder={loc("placeholder_password", "••••••••••••")}
              value={form.password}
              onChange={onChange("password")}
              className={`${authInputClass} ${isRtl ? "pl-12" : "pr-12"}`}
            />
            <PasswordToggleButton
              show={showPassword}
              onToggle={() => setShowPassword(!showPassword)}
              isRtl={isRtl}
            />
          </div>
        </div>

        <label className="group flex w-full cursor-pointer items-start gap-3">
          <div className="relative flex items-center">
            <input
              type="checkbox"
              checked={form.agreed}
              onChange={onChange("agreed")}
              className="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-[#135FAE] checked:bg-[#135FAE]"
            />
            <svg
              className="pointer-events-none absolute left-1/2 top-1/2 h-3.5 w-3.5 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100"
              viewBox="0 0 14 14"
              fill="none"
            >
              <path d="M3 7L5.5 9.5L11 4" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>
          <span className="text-sm font-normal text-slate-600 transition-colors group-hover:text-slate-900">
            {loc("agree_prefix", "I agree to")}{" "}
            <Link href="/terms" className="font-medium text-[#135FAE] hover:underline">
              {loc("terms", "Terms & Conditions")}
            </Link>{" "}
            {loc("and", "and")}{" "}
            <Link href="/privacy" className="font-medium text-[#135FAE] hover:underline">
              {loc("privacy", "Privacy Policy")}
            </Link>{" "}
            {loc("suffix", "of Course English")}
          </span>
        </label>

        <AuthErrorBox message={error} />

        <button type="submit" disabled={loading} className={authButtonClass}>
          {loading ? loc("creating", "Creating Account...") : loc("create_button", "Create New Account")}
        </button>
      </form>

      <div className="mt-8 text-center text-sm font-medium text-slate-600">
        {loc("already_have_account", "Already have an account?")}{" "}
        <Link href="/login" className="text-[#135FAE] hover:underline">
          {loc("login", "Log In")}
        </Link>
      </div>
    </>
  );
}
