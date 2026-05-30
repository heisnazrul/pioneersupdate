"use client";

import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import {
  AuthErrorBox,
  AuthSuccessBox,
  PasswordToggleButton,
  authButtonClass,
  authInputClass,
} from "@/components/shared/auth-ui";
import { resetPassword } from "@/lib/auth";

export default function ResetPasswordForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.reset_password.${key}`, fallback);

  const token = searchParams.get("token") || "";
  const email = searchParams.get("email") || "";

  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");
    setSuccess("");

    if (!token || !email) {
      setError(loc("error_invalid_link", "This reset link is invalid or has expired."));
      return;
    }

    if (password.length < 8) {
      setError(loc("error_min_length", "Password must be at least 8 characters."));
      return;
    }

    if (password !== passwordConfirmation) {
      setError(loc("error_mismatch", "Passwords do not match."));
      return;
    }

    try {
      setLoading(true);
      await resetPassword({
        token,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      setSuccess(loc("success", "Your password has been reset successfully."));
      setTimeout(() => router.push("/login"), 1500);
    } catch (err) {
      setError(err.message || loc("error_failed", "Failed to reset password."));
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <form className="mt-2 space-y-5" onSubmit={handleSubmit}>
        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("new_password", "New Password")}</label>
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

        <div className={`space-y-2 ${isRtl ? "text-right" : "text-left"}`}>
          <label className="text-sm font-medium text-slate-900">{loc("confirm_password", "Confirm Password")}</label>
          <div className="relative">
            <input
              type={showConfirm ? "text" : "password"}
              name="password_confirmation"
              placeholder={loc("placeholder_confirm", "••••••••••••")}
              value={passwordConfirmation}
              onChange={(event) => setPasswordConfirmation(event.target.value)}
              className={`${authInputClass} ${isRtl ? "pl-12" : "pr-12"}`}
            />
            <PasswordToggleButton
              show={showConfirm}
              onToggle={() => setShowConfirm(!showConfirm)}
              isRtl={isRtl}
            />
          </div>
        </div>

        <AuthErrorBox message={error} />
        <AuthSuccessBox message={success} />

        <button type="submit" disabled={loading || !!success} className={authButtonClass}>
          {loading ? loc("resetting", "Resetting...") : loc("reset_button", "Reset Password")}
        </button>
      </form>

      <div className="mt-8 text-center text-sm font-medium text-slate-600">
        <Link href="/login" className="text-[#135FAE] hover:underline">
          {loc("back_to_login", "Back to Login")}
        </Link>
      </div>
    </>
  );
}
