"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import {
  AuthErrorBox,
  PasswordToggleButton,
  authButtonClass,
  authInputClass,
} from "@/components/shared/auth-ui";
import { getDashboardPath, getStoredAuthToken, setBookingPassword } from "@/lib/auth";

export default function SetPasswordForm() {
  const router = useRouter();
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.set_password.${key}`, fallback);

  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!getStoredAuthToken()) {
      router.push("/login");
    }
  }, [router]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");

    if (!getStoredAuthToken()) {
      router.push("/login");
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
      await setBookingPassword({
        password,
        password_confirmation: passwordConfirmation,
      });
      router.push(getDashboardPath("lg_student"));
    } catch (err) {
      setError(err.message || loc("error_failed", "Failed to set password."));
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

        <button type="submit" disabled={loading} className={authButtonClass}>
          {loading ? loc("saving", "Saving...") : loc("save_button", "Save & Continue")}
        </button>

        <button
          type="button"
          onClick={() => router.push(getDashboardPath("lg_student"))}
          className="w-full text-center text-sm text-slate-400 transition hover:text-slate-600"
        >
          {loc("skip", "Skip (I'll do this later)")}
        </button>
      </form>

      <div className="mt-6 text-center text-sm font-medium text-slate-600">
        <Link href="/login" className="text-[#135FAE] hover:underline">
          {loc("back_to_login", "Back to Login")}
        </Link>
      </div>
    </>
  );
}
