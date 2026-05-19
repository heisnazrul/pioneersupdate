"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

function getAuth() {
  const token = typeof window !== "undefined" ? localStorage.getItem("auth_token") : null;
  const tokenType = typeof window !== "undefined" ? localStorage.getItem("auth_token_type") || "Bearer" : "Bearer";
  return { token, tokenType };
}

export default function StudentProfilePage() {
  const { isArabic } = useCourseEnglishSettings();
  const fileRef = useRef(null);

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState("");
  const [avatarFile, setAvatarFile] = useState(null);
  const [avatarPreview, setAvatarPreview] = useState("");

  const [form, setForm] = useState({
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
  });

  const t = useMemo(
    () =>
      isArabic
        ? {
            title: "الملف الشخصي",
            subtitle: "قم بإدارة معلوماتك الشخصية وإعدادات الحساب.",
            save: "حفظ التغييرات",
            changingPhone: "تغيير رقم الجوال",
            changingPassword: "تغيير كلمة المرور",
            name: "الاسم",
            email: "البريد الإلكتروني",
            phone: "رقم الجوال",
            password: "كلمة المرور",
            birthDate: "تاريخ الميلاد",
            gender: "النوع",
            country: "الدولة",
            city: "المدينة",
            address: "العنوان",
            postalCode: "الرمز البريدي",
            nationalId: "رقم الهوية",
            altPhone: "رقم جوال بديل",
            success: "تم تحديث البيانات بنجاح",
            error: "حدث خطأ، حاول مرة أخرى",
            genderPlaceholder: "اختر النوع",
            male: "ذكر",
            female: "أنثى",
          }
        : {
            title: "Profile",
            subtitle: "Manage your account information.",
            save: "Save changes",
            changingPhone: "Change phone",
            changingPassword: "Change password",
            name: "Name",
            email: "Email",
            phone: "Phone",
            password: "Password",
            birthDate: "Birth date",
            gender: "Gender",
            country: "Country",
            city: "City",
            address: "Address",
            postalCode: "Postal code",
            nationalId: "National ID",
            altPhone: "Alternative phone",
            success: "Profile updated successfully",
            error: "Something went wrong",
            genderPlaceholder: "Select gender",
            male: "Male",
            female: "Female",
          },
    [isArabic]
  );

  useEffect(() => {
    let ignore = false;

    async function loadMe() {
      const { token, tokenType } = getAuth();
      if (!token) {
        setLoading(false);
        return;
      }

      try {
        const res = await fetch(buildApiUrl("/student/me"), {
          headers: {
            Accept: "application/json",
            Authorization: `${tokenType} ${token}`,
          },
          cache: "no-store",
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json?.message || "Failed");

        const user = json?.data || {};
        if (ignore) return;

        setForm((prev) => ({
          ...prev,
          name: user?.name || "",
          email: user?.email || "",
          phone: user?.phone || "",
          birth_date: user?.birth_date || "",
          gender: user?.gender || "",
          country: user?.country || "",
          city: user?.city || "",
          address: user?.address || "",
          postal_code: user?.postal_code || "",
          national_id: user?.national_id || "",
          alt_phone: user?.alt_phone || "",
        }));
        setAvatarPreview(user?.avatar || "");
      } catch {
        if (!ignore) setMessage(t.error);
      } finally {
        if (!ignore) setLoading(false);
      }
    }

    loadMe();
    return () => {
      ignore = true;
    };
  }, [t.error]);

  const setField = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const onAvatarChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setAvatarFile(file);
    const reader = new FileReader();
    reader.onload = () => setAvatarPreview(String(reader.result || ""));
    reader.readAsDataURL(file);
  };

  const submit = async (e) => {
    e.preventDefault();
    if (saving) return;

    const { token, tokenType } = getAuth();
    if (!token) return;

    setSaving(true);
    setMessage("");

    try {
      const data = new FormData();
      data.append("name", form.name || "");
      data.append("email", form.email || "");
      data.append("phone", form.phone || "");
      if (form.password) {
        data.append("password", form.password);
        data.append("password_confirmation", form.password_confirmation || "");
      }
      if (avatarFile) data.append("avatar", avatarFile);

      const res = await fetch(buildApiUrl("/student/update-profile"), {
        method: "POST",
        headers: {
          Accept: "application/json",
          Authorization: `${tokenType} ${token}`,
        },
        body: data,
      });
      const json = await res.json();
      if (!res.ok || !json?.success) {
        throw new Error(json?.message || t.error);
      }

      const user = json?.data || {};
      localStorage.setItem("auth_user", JSON.stringify(user));
      setForm((prev) => ({ ...prev, password: "", password_confirmation: "" }));
      setMessage(t.success);
    } catch (err) {
      setMessage(err?.message || t.error);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>;
  }

  return (
    <div className="space-y-6">
      <section className="text-right">
        <h1 className="text-3xl font-semibold text-[#102233]">{t.title}</h1>
        <p className="mt-1 text-lg text-slate-500">{t.subtitle}</p>
      </section>

      <form onSubmit={submit} className="rounded-3xl border-none md:border md:border-slate-200 bg-transparent md:bg-white p-0 md:p-6 space-y-6 md:space-y-8">
        {/* Centered Avatar */}
        <div className="flex flex-col items-center justify-center pt-4 pb-2">
          <div className="relative">
            <div className="grid h-[100px] w-[100px] md:h-32 md:w-32 place-items-center overflow-hidden rounded-full border border-slate-200 bg-white">
              {avatarPreview ? (
                <img src={avatarPreview} alt="avatar" className="h-full w-full object-cover" />
              ) : (
                <i className="fa-solid fa-user text-[40px] md:text-[50px] text-slate-300" />
              )}
            </div>
            <button
              type="button"
              onClick={() => fileRef.current?.click()}
              className="absolute bottom-1 right-1 md:bottom-2 md:right-0 grid h-8 w-8 md:h-9 md:w-9 place-items-center rounded-full bg-[#1277BE] text-white shadow-md border-2 border-white"
            >
              <i className="fa-solid fa-pen text-[12px] md:text-[14px]" />
            </button>
            <input ref={fileRef} type="file" className="hidden" accept="image/*" onChange={onAvatarChange} />
          </div>
          {message && <p className="mt-4 text-[14px] font-normal text-[#1277BE]">{message}</p>}
        </div>

        <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
          <Field label={t.name} value={form.name} onChange={(v) => setField("name", v)} />
          <Field label={t.email} value={form.email} onChange={(v) => setField("email", v)} type="email" />
          <Field
            label={t.phone}
            value={form.phone}
            onChange={(v) => setField("phone", v)}
            isPhone={true}
            after={
              <button type="button" className="grid h-full w-[52px] shrink-0 place-items-center text-[#1277BE] hover:bg-slate-50 transition-colors">
                <i className="fa-solid fa-pen text-[14px]"></i>
              </button>
            }
          />
          <Field
            label={t.password}
            value={form.password}
            onChange={(v) => setField("password", v)}
            type="password"
            placeholder="••••••••••••••••"
            after={
              <button type="button" className="grid h-full w-[52px] shrink-0 place-items-center text-[#1277BE] hover:bg-slate-50 transition-colors">
                <i className="fa-solid fa-pen text-[14px]"></i>
              </button>
            }
          />
          <Field label={t.birthDate} value={form.birth_date} onChange={(v) => setField("birth_date", v)} placeholder={isArabic ? "يوم / شهر / سنة" : "DD/MM/YYYY"} />
          
          <div className="space-y-2">
            <label className="block text-[14px] font-medium text-[#102233]">{t.gender}</label>
            <div className="relative">
              <select
                value={form.gender}
                onChange={(e) => setField("gender", e.target.value)}
                className="h-[52px] w-full appearance-none rounded-2xl border border-slate-200 bg-white px-5 text-[15px] font-normal text-[#102233] outline-none hover:border-[#1277BE] focus:border-[#1277BE] focus:ring-1 focus:ring-[#1277BE] transition-all text-start"
              >

                <option value="">{t.genderPlaceholder}</option>
                <option value="male">{t.male}</option>
                <option value="female">{t.female}</option>
              </select>
              <i className="fa-solid fa-chevron-down absolute top-1/2 -translate-y-1/2 text-[12px] text-slate-400 pointer-events-none" style={{ [isArabic ? 'left' : 'right']: '1.25rem' }}></i>
            </div>
          </div>

          <Field label={t.country} value={form.country} onChange={(v) => setField("country", v)} placeholder={isArabic ? "أدخل دولتك" : "Enter country"} />
          <Field label={t.city} value={form.city} onChange={(v) => setField("city", v)} placeholder={isArabic ? "أدخل مدينتك" : "Enter city"} />
          <Field label={t.address} value={form.address} onChange={(v) => setField("address", v)} placeholder={isArabic ? "أدخل عنوانك" : "Enter address"} />
          <Field label={t.postalCode} value={form.postal_code} onChange={(v) => setField("postal_code", v)} placeholder={isArabic ? "أدخل الرمز البريدي" : "Enter postal code"} />
          <Field label={t.nationalId} value={form.national_id} onChange={(v) => setField("national_id", v)} placeholder={isArabic ? "أدخل رقم هويتك" : "Enter national ID"} />
          
          <Field
            label={t.altPhone}
            value={form.alt_phone}
            onChange={(v) => setField("alt_phone", v)}
            isPhone={true}
          />
        </div>

        <div className="flex justify-start pt-4">
          <button
            type="submit"
            disabled={saving}
            className="w-full md:w-auto rounded-xl bg-[#1277BE] px-8 py-3.5 text-[15px] font-medium text-white shadow-sm hover:bg-[#0e5c94] transition-colors disabled:opacity-60"
          >
            {saving ? "..." : t.save}
          </button>
        </div>
      </form>
    </div>
  );
}

function Field({ label, value, onChange, placeholder, type = "text", after, isPhone = false }) {
  // Try to determine document direction, defaulting to checking if the placeholder has Arabic text
  const isArabic = typeof document !== 'undefined' && document.dir === 'rtl';

  return (
    <div className="space-y-2">
      <label className="block text-[14px] font-medium text-[#102233] text-start">{label}</label>
      {/* We reverse the flex direction ONLY visually so `after` elements go to the start logically, but we use natural flex order: Input, then After button.
          With dir="rtl", normal flex means items are right-to-left. 
          So [ Phone Flag ] [ Input ] [ Edit Button ] natively becomes:
          Right side: [ Phone Flag ] -> Middle: [ Input ] -> Left side: [ Edit Button ]. 
          This perfectly matches screenshot 3! */}
      <div className="flex items-center overflow-hidden rounded-2xl border border-slate-200 bg-white focus-within:border-[#1277BE] focus-within:ring-1 focus-within:ring-[#1277BE] transition-all h-[52px]">
        
        {isPhone && (
           <div className="flex shrink-0 items-center gap-2 px-4 border-e border-slate-200 bg-white h-full relative z-10">
             <i className="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
             <span className="text-[14px] font-medium text-[#102233] whitespace-nowrap" dir="ltr">+966</span>
             <img src="/assets/flags/sa.svg" alt="SA" className="w-[18px] h-[18px] rounded-full object-cover" onError={(e) => { e.currentTarget.style.display='none'; }} />
           </div>
        )}

        {/* Setting text-start ensures that even if text is English (like an email), it aligns to the start of the layout (Right in RTL, Left in LTR) aligned with placeholder.
            For English domains in Arabic UI, we force dir="ltr" if type="email" to fix cursor issues, but keep text-right to match screenshot 3.
            Screenshot 3 has the email test@test.com right-aligned!
        */}
        <input
          type={type}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder || ""}
          className={`h-full w-full bg-transparent px-5 text-[15px] font-normal text-[#102233] placeholder:text-slate-400 placeholder:font-light outline-none ${isArabic ? 'text-right' : 'text-left'}`}
          dir={type === "email" ? "ltr" : "auto"}
        />
        
        {after && (
          <div className="border-s border-slate-200 h-[calc(100%-16px)] flex items-center justify-center my-auto">
            {after}
          </div>
        )}
      </div>
    </div>
  );
}
