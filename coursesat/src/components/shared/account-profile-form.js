"use client";

import { useEffect, useRef, useState } from "react";
import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faPen, faUser } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { buildAuthUrl, getStoredAuthToken, getStoredAuthTokenType } from "@/lib/auth";
import { fetchAgentJson } from "@/lib/agent-api";
import { formatCurrency } from "@/lib/format";

function ProfileField({
  label,
  value,
  onChange,
  placeholder,
  type = "text",
  after,
  isPhone = false,
  isRtl,
}) {
  return (
    <div className="space-y-2">
      <label className="block text-start text-[14px] font-medium text-[#102233]">{label}</label>
      <div className="flex h-[52px] items-center overflow-hidden rounded-2xl border border-slate-200 bg-white transition-all focus-within:border-[#1277BE] focus-within:ring-1 focus-within:ring-[#1277BE]">
        {isPhone ? (
          <div className="relative z-10 flex h-full shrink-0 items-center gap-2 border-e border-slate-200 bg-white px-4">
            <FontAwesomeIcon icon={faChevronDown} className="text-[10px] text-slate-400" />
            <span className="whitespace-nowrap text-[14px] font-medium text-[#102233]" dir="ltr">
              +966
            </span>
          </div>
        ) : null}
        <input
          type={type}
          value={value}
          onChange={(event) => onChange(event.target.value)}
          placeholder={placeholder || ""}
          className={`h-full w-full bg-transparent px-5 text-[15px] font-normal text-[#102233] placeholder:font-light placeholder:text-slate-400 outline-none ${isRtl ? "text-right" : "text-left"}`}
          dir={type === "email" ? "ltr" : "auto"}
        />
        {after ? (
          <div className="my-auto flex h-[calc(100%-16px)] items-center justify-center border-s border-slate-200">{after}</div>
        ) : null}
      </div>
    </div>
  );
}

const EMPTY_FORM = {
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  birth_date: "",
  gender: "",
  country: "",
  city: "",
  address: "",
  postal_code: "",
  national_id: "",
  alt_phone: "",
  bank_account_name: "",
  bank_name: "",
  bank_account_number: "",
  bank_iban: "",
  bank_swift_code: "",
};

export default function AccountProfileForm({ variant = "student" }) {
  const { direction, language, t } = useLocale();
  const isRtl = direction === "rtl";
  const isArabic = language === "ar";
  const isAgent = variant === "agent";
  const localeKey = isAgent ? "pages.agent.profile" : "pages.student.profile";
  const loc = (key, fallback = "") => t(`${localeKey}.${key}`, fallback);
  const fileRef = useRef(null);

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [payoutLoading, setPayoutLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [payoutMessage, setPayoutMessage] = useState("");
  const [avatarPreview, setAvatarPreview] = useState("");
  const [profileMeta, setProfileMeta] = useState({});
  const [form, setForm] = useState(EMPTY_FORM);

  useEffect(() => {
    let ignore = false;

    async function loadMe() {
      try {
        let user = {};
        if (isAgent) {
          const json = await fetchAgentJson("/agent/me");
          user = json?.data || {};
        } else {
          const token = getStoredAuthToken();
          const tokenType = getStoredAuthTokenType();
          if (!token) {
            setLoading(false);
            return;
          }
          const res = await fetch(buildAuthUrl("/student/me"), {
            headers: {
              Accept: "application/json",
              Authorization: `${tokenType} ${token}`,
            },
            cache: "no-store",
          });
          const json = await res.json();
          if (!res.ok) throw new Error(json?.message || "Failed");
          user = json?.data || {};
        }

        if (ignore) return;

        setProfileMeta(user);
        setForm((prev) => ({
          ...prev,
          name: user.name || "",
          email: user.email || "",
          phone: user.phone || "",
          birth_date: user.birth_date || "",
          gender: user.gender || "",
          country: user.country || "",
          city: user.city || "",
          address: user.address || "",
          postal_code: user.postal_code || "",
          national_id: user.national_id || "",
          alt_phone: user.alt_phone || "",
          bank_account_name: user.bank_account_name || "",
          bank_name: user.bank_name || "",
          bank_account_number: user.bank_account_number || "",
          bank_iban: user.bank_iban || "",
          bank_swift_code: user.bank_swift_code || "",
        }));
        setAvatarPreview(user.avatar || "");
      } catch {
        if (!ignore) setMessage(loc("error", "Something went wrong"));
      } finally {
        if (!ignore) setLoading(false);
      }
    }

    loadMe();
    return () => {
      ignore = true;
    };
  }, [isAgent, loc]);

  const setField = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const onAvatarChange = (event) => {
    const file = event.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => setAvatarPreview(String(reader.result || ""));
    reader.readAsDataURL(file);
  };

  const submit = async (event) => {
    event.preventDefault();
    if (saving) return;

    setSaving(true);
    setMessage("");

    try {
      let user = {};
      if (isAgent) {
        const json = await fetchAgentJson("/agent/profile", {
          method: "POST",
          body: JSON.stringify(form),
        });
        user = json?.data || {};
      } else {
        const token = getStoredAuthToken();
        const tokenType = getStoredAuthTokenType();
        const res = await fetch(buildAuthUrl("/student/profile"), {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `${tokenType} ${token}`,
          },
          body: JSON.stringify(form),
        });
        const json = await res.json();
        if (!res.ok || !json?.success) throw new Error(json?.message || loc("error", "Something went wrong"));
        user = json?.data || {};
        localStorage.setItem("auth_user", JSON.stringify(user));
      }

      setProfileMeta(user);
      setForm((prev) => ({ ...prev, password: "", password_confirmation: "" }));
      setMessage(loc("success", "Profile updated successfully"));
    } catch (err) {
      setMessage(err?.message || loc("error", "Something went wrong"));
    } finally {
      setSaving(false);
    }
  };

  const requestPayout = async () => {
    if (payoutLoading) return;
    setPayoutLoading(true);
    setPayoutMessage("");

    try {
      const path = isAgent ? "/agent/payouts" : "/courseenglish/student/payouts";
      if (isAgent) {
        await fetchAgentJson(path, { method: "POST", body: JSON.stringify({}) });
      } else {
        const token = getStoredAuthToken();
        const tokenType = getStoredAuthTokenType();
        const res = await fetch(buildAuthUrl("/student/payouts"), {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `${tokenType} ${token}`,
          },
          body: JSON.stringify({}),
        });
        const json = await res.json();
        if (!res.ok || !json?.success) throw new Error(json?.message || loc("payout_error", "Unable to submit payout request"));
      }
      setPayoutMessage(loc("payout_success", "Payout request submitted successfully."));
    } catch (err) {
      setPayoutMessage(err?.message || loc("payout_error", "Unable to submit payout request"));
    } finally {
      setPayoutLoading(false);
    }
  };

  const showBankSection = Boolean(profileMeta.has_commission_balance || Number(profileMeta.commission_balance) > 0);
  const commissionBalance = Number(profileMeta.commission_balance || 0);

  if (loading) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>;
  }

  return (
    <div className="space-y-6" dir={direction}>
      <section className={isRtl ? "text-right" : "text-left"}>
        <h1 className="text-3xl font-semibold text-[#102233]">{loc("title", "Profile")}</h1>
        <p className="mt-1 text-lg text-slate-500">{loc("subtitle", "Manage your account information.")}</p>
      </section>

      {isAgent && profileMeta.referral_code ? (
        <div className={`rounded-3xl border border-slate-200 bg-[#E8F1F8] p-6 ${isRtl ? "text-right" : "text-left"}`}>
          <h2 className="text-lg font-medium text-[#102233]">{loc("agent_details", "Agent details")}</h2>
          <div className="mt-3 grid gap-2 text-sm text-slate-700 md:grid-cols-2">
            {profileMeta.company_name ? <p><span className="text-slate-500">{loc("company", "Company")}:</span> {profileMeta.company_name}</p> : null}
            {profileMeta.referral_code ? <p><span className="text-slate-500">{loc("referral_code", "Referral code")}:</span> {profileMeta.referral_code}</p> : null}
            {profileMeta.commission_percent != null ? <p><span className="text-slate-500">{loc("commission", "Commission")}:</span> {profileMeta.commission_percent}%</p> : null}
            {profileMeta.referral_link ? <p className="break-all md:col-span-2"><span className="text-slate-500">{loc("referral_link", "Referral link")}:</span> {profileMeta.referral_link}</p> : null}
          </div>
          <div className="mt-4 flex flex-wrap gap-3">
            <Link href="/agent/students" className="rounded-full bg-white px-4 py-2 text-sm text-[#1277BE] hover:bg-slate-50">{loc("manage_students", "Manage students")}</Link>
            <Link href="/agent/referrals" className="rounded-full bg-white px-4 py-2 text-sm text-[#1277BE] hover:bg-slate-50">{loc("referrals", "Referrals")}</Link>
          </div>
        </div>
      ) : null}

      {showBankSection ? (
        <div className={`rounded-3xl border border-[#1277BE]/20 bg-[#F0F7FF] p-6 ${isRtl ? "text-right" : "text-left"}`}>
          <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <p className="text-xs font-medium uppercase tracking-wide text-[#1277BE]">{loc("commission_balance", "Commission balance")}</p>
              <p className="mt-1 text-3xl font-semibold text-[#102233]">
                {formatCurrency(commissionBalance, profileMeta.commission_currency || "SAR", language)}
              </p>
            </div>
            <button
              type="button"
              onClick={requestPayout}
              disabled={payoutLoading || commissionBalance <= 0}
              className="rounded-xl bg-[#1277BE] px-6 py-3 text-sm font-medium text-white hover:bg-[#0e5c94] disabled:opacity-50"
            >
              {payoutLoading ? "..." : loc("request_payout", "Request payout")}
            </button>
          </div>
          {payoutMessage ? <p className="mt-3 text-sm text-[#1277BE]">{payoutMessage}</p> : null}
          <p className="mt-3 text-sm text-slate-600">{loc("payout_hint", "Add your bank details below before requesting a payout.")}</p>
        </div>
      ) : null}

      <form
        onSubmit={submit}
        className="space-y-6 rounded-3xl border-none bg-transparent p-0 md:space-y-8 md:border md:border-slate-200 md:bg-white md:p-6"
      >
        <div className="flex flex-col items-center justify-center pb-2 pt-4">
          <div className="relative">
            <div className="grid h-[100px] w-[100px] place-items-center overflow-hidden rounded-full border border-slate-200 bg-white md:h-32 md:w-32">
              {avatarPreview ? (
                <img src={avatarPreview} alt="avatar" className="h-full w-full object-cover" />
              ) : (
                <FontAwesomeIcon icon={faUser} className="text-[40px] text-slate-300 md:text-[50px]" />
              )}
            </div>
            <button
              type="button"
              onClick={() => fileRef.current?.click()}
              className="absolute bottom-1 right-1 grid h-8 w-8 place-items-center rounded-full border-2 border-white bg-[#1277BE] text-white shadow-md md:bottom-2 md:right-0 md:h-9 md:w-9"
            >
              <FontAwesomeIcon icon={faPen} className="text-[12px] md:text-[14px]" />
            </button>
            <input ref={fileRef} type="file" className="hidden" accept="image/*" onChange={onAvatarChange} />
          </div>
          {message ? <p className="mt-4 text-[14px] font-normal text-[#1277BE]">{message}</p> : null}
        </div>

        <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
          <ProfileField label={loc("name", "Name")} value={form.name} onChange={(v) => setField("name", v)} isRtl={isRtl} />
          <ProfileField label={loc("email", "Email")} value={form.email} onChange={(v) => setField("email", v)} type="email" isRtl={isRtl} />
          <ProfileField label={loc("phone", "Phone")} value={form.phone} onChange={(v) => setField("phone", v)} isPhone isRtl={isRtl} />
          <ProfileField label={loc("password", "Password")} value={form.password} onChange={(v) => setField("password", v)} type="password" placeholder="••••••••••••••••" isRtl={isRtl} />
          <ProfileField label={loc("birth_date", "Birth date")} value={form.birth_date} onChange={(v) => setField("birth_date", v)} placeholder={isArabic ? "يوم / شهر / سنة" : "YYYY-MM-DD"} isRtl={isRtl} />

          <div className="space-y-2">
            <label className="block text-[14px] font-medium text-[#102233]">{loc("gender", "Gender")}</label>
            <div className="relative">
              <select
                value={form.gender}
                onChange={(event) => setField("gender", event.target.value)}
                className="h-[52px] w-full appearance-none rounded-2xl border border-slate-200 bg-white px-5 text-start text-[15px] font-normal text-[#102233] outline-none transition-all hover:border-[#1277BE] focus:border-[#1277BE] focus:ring-1 focus:ring-[#1277BE]"
              >
                <option value="">{loc("gender_placeholder", "Select gender")}</option>
                <option value="male">{loc("male", "Male")}</option>
                <option value="female">{loc("female", "Female")}</option>
              </select>
              <FontAwesomeIcon icon={faChevronDown} className={`pointer-events-none absolute top-1/2 -translate-y-1/2 text-[12px] text-slate-400 ${isRtl ? "left-5" : "right-5"}`} />
            </div>
          </div>

          <ProfileField label={loc("country", "Country")} value={form.country} onChange={(v) => setField("country", v)} isRtl={isRtl} />
          <ProfileField label={loc("city", "City")} value={form.city} onChange={(v) => setField("city", v)} isRtl={isRtl} />
          <ProfileField label={loc("address", "Address")} value={form.address} onChange={(v) => setField("address", v)} isRtl={isRtl} />
          <ProfileField label={loc("postal_code", "Postal code")} value={form.postal_code} onChange={(v) => setField("postal_code", v)} isRtl={isRtl} />
          <ProfileField label={loc("national_id", "National ID")} value={form.national_id} onChange={(v) => setField("national_id", v)} isRtl={isRtl} />
          <ProfileField label={loc("alt_phone", "Alternative phone")} value={form.alt_phone} onChange={(v) => setField("alt_phone", v)} isPhone isRtl={isRtl} />
        </div>

        {showBankSection ? (
          <div className="space-y-5 border-t border-slate-100 pt-6">
            <h3 className={`text-lg font-semibold text-[#102233] ${isRtl ? "text-right" : "text-left"}`}>{loc("bank_details", "Bank details for payouts")}</h3>
            <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
              <ProfileField label={loc("bank_account_name", "Account holder name")} value={form.bank_account_name} onChange={(v) => setField("bank_account_name", v)} isRtl={isRtl} />
              <ProfileField label={loc("bank_name", "Bank name")} value={form.bank_name} onChange={(v) => setField("bank_name", v)} isRtl={isRtl} />
              <ProfileField label={loc("bank_account_number", "Account number")} value={form.bank_account_number} onChange={(v) => setField("bank_account_number", v)} isRtl={isRtl} />
              <ProfileField label={loc("bank_iban", "IBAN")} value={form.bank_iban} onChange={(v) => setField("bank_iban", v)} isRtl={isRtl} />
              <ProfileField label={loc("bank_swift_code", "SWIFT / BIC")} value={form.bank_swift_code} onChange={(v) => setField("bank_swift_code", v)} isRtl={isRtl} />
            </div>
          </div>
        ) : null}

        <div className="flex justify-start pt-4">
          <button
            type="submit"
            disabled={saving}
            className="w-full rounded-xl bg-[#1277BE] px-8 py-3.5 text-[15px] font-medium text-white shadow-sm transition-colors hover:bg-[#0e5c94] disabled:opacity-60 md:w-auto"
          >
            {saving ? "..." : loc("save", "Save changes")}
          </button>
        </div>
      </form>
    </div>
  );
}
