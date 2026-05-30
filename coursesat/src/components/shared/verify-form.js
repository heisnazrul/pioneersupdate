"use client";

import Link from "next/link";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import {
  AuthErrorBox,
  authButtonClass,
} from "@/components/shared/auth-ui";

export default function VerifyForm() {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.verify.${key}`, fallback);

  const [code, setCode] = useState(["", "", "", ""]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleChange = (index, value) => {
    if (!/^\d?$/.test(value)) return;
    const next = [...code];
    next[index] = value;
    setCode(next);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");
    const joined = code.join("");
    if (joined.length !== 4) {
      setError(loc("error_code", "Enter the 4-digit verification code."));
      return;
    }
    setLoading(true);
    setError(loc("coming_soon", "Email verification will be added when the backend endpoint is ready."));
    setLoading(false);
  };

  return (
    <>
      <form className="mt-2 space-y-6" onSubmit={handleSubmit}>
        <div className={isRtl ? "text-right" : "text-left"}>
          <label className="text-sm font-normal text-slate-700">{loc("label_code", "Verification code")}</label>
          <div className="mt-3 grid grid-cols-4 gap-3">
            {code.map((digit, index) => (
              <input
                key={index}
                type="text"
                inputMode="numeric"
                maxLength={1}
                value={digit}
                onChange={(event) => handleChange(index, event.target.value)}
                className="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-[#135FAE] focus:ring-2 focus:ring-[#135FAE]/20"
              />
            ))}
          </div>
        </div>

        <AuthErrorBox message={error} />

        <button type="submit" disabled={loading} className={authButtonClass}>
          {loading ? loc("verifying", "Verifying...") : loc("verify_button", "Verify account")}
        </button>
      </form>

      <div className="mt-6 text-center text-sm text-slate-600">
        {loc("did_not_receive", "Didn't receive the code?")}{" "}
        <button type="button" className="font-medium text-[#135FAE] hover:underline">
          {loc("resend", "Resend")}
        </button>
      </div>

      <div className="mt-2 text-center text-sm text-slate-600">
        <Link href="/login" className="font-medium text-[#135FAE] hover:underline">
          {loc("back_to_login", "Back to login")}
        </Link>
      </div>
    </>
  );
}
