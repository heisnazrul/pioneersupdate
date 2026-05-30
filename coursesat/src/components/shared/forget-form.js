"use client";

import Link from "next/link";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import {
  AuthErrorBox,
  AuthSuccessBox,
  authButtonClass,
  authInputClass,
} from "@/components/shared/auth-ui";
import { requestPasswordReset } from "@/lib/auth";

export default function ForgetForm() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.forget.${key}`, fallback);

  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");
    setSuccess("");

    if (!email.trim()) {
      setError(loc("error_email", "Email is required."));
      return;
    }

    try {
      setLoading(true);
      await requestPasswordReset(email.trim());
      setSuccess(loc("success", "If an account exists for this email, a reset link has been sent."));
    } catch (err) {
      setError(err.message || loc("error_failed", "Failed to send reset link."));
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <form className="mt-2 space-y-6" onSubmit={handleSubmit}>
        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("email", "Email Address")}</label>
          <input
            type="email"
            name="email"
            placeholder={loc("placeholder_email", "Enter email address")}
            value={email}
            onChange={(event) => setEmail(event.target.value)}
            className={authInputClass}
          />
        </div>

        <AuthErrorBox message={error} />
        <AuthSuccessBox message={success} />

        <button type="submit" disabled={loading} className={authButtonClass}>
          {loading ? loc("sending", "Sending...") : loc("reset_button", "Reset Password")}
        </button>
      </form>

      <div className="mt-8 text-center text-sm font-medium text-slate-600">
        {loc("remembered", "Remembered your password?")}{" "}
        <Link href="/login" className="text-[#135FAE] hover:underline">
          {loc("back_to_login", "Back to Login")}
        </Link>
      </div>
    </>
  );
}
