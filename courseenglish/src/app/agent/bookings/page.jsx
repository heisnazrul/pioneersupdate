"use client";

import { useState, useMemo } from "react";
import { useAgentApi, fetchAgentJson } from "@/lib/agentApi";
import { pickLang } from "@/lib/i18nFallback";
import AlertBanner from "@/app/agent/components/AlertBanner";
import StepHeader from "./components/StepHeader";
import CoursePicker from "./components/CoursePicker";

export default function AgentBookingsPage() {
  const { data: studentsData, loading: studentsLoading, error: studentsError, refetch } = useAgentApi("/agent/students");
  const students = studentsData?.data || [];
  const [form, setForm] = useState({
    student_id: "",
    course_type: "language",
    course_id: "",
    course_title: "",
    start_date: "",
    weeks: 1,
    final_price: "",
    currency: "GBP",
    accommodation_id: "",
    pickup_id: "",
    insurance_id: "",
    supplements_ids: "",
    notes: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const [message, setMessage] = useState("");
  const isArabic = (typeof localStorage !== "undefined" ? localStorage.getItem("ce_language") : "ar") === "ar";
  const lang = isArabic ? "ar" : "en";
  const [step, setStep] = useState(0); // 0 student, 1 course, 2 options, 3 review

  const studentOptions = useMemo(
    () => students.map((s) => ({ value: s.id, label: `${s.name} • ${s.email}` })),
    [students]
  );

  const handleChange = (field) => (e) => {
    const value = e.target.value;
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");
    setSubmitting(true);
    try {
      const payload = {
        ...form,
        weeks: Number(form.weeks) || 1,
        final_price: Number(form.final_price) || 0,
        supplements_ids: form.supplements_ids
          ? form.supplements_ids.split(",").map((v) => v.trim()).filter(Boolean)
          : [],
      };
      await fetchAgentJson("/agent/bookings", { method: "POST", body: JSON.stringify(payload) });
      setMessage(pickLang(lang, "Booking created successfully.", "تم إنشاء الحجز بنجاح."));
      setForm((prev) => ({ ...prev, course_id: "", start_date: "", final_price: "", notes: "" }));
      refetch();
    } catch (err) {
      setMessage(err?.message || pickLang(lang, "Failed to create booking.", "تعذر إنشاء الحجز."));
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-2">
        <p className="text-xs font-normal uppercase tracking-[0.2em] text-slate-600">
          {pickLang(lang, "Bookings", "الحجوزات")}
        </p>
        <h1 className="text-2xl font-normal text-slate-900">
          {pickLang(lang, "Book a course for a student", "احجز دورة لأحد الطلاب")}
        </h1>
        <p className="text-slate-600">
          {pickLang(
            lang,
            "Select a student, pick a course type, add dates and optional services, then submit.",
            "اختر الطالب ونوع الدورة وأضف التواريخ والخدمات الإضافية ثم أرسل الطلب."
          )}
        </p>
      </div>

      <StepHeader
        steps={[
          pickLang(lang, "Student", "الطالب"),
          pickLang(lang, "Course", "الدورة"),
          pickLang(lang, "Options", "الخيارات"),
          pickLang(lang, "Review", "مراجعة"),
        ]}
        current={step}
        dir={isArabic ? "rtl" : "ltr"}
      />

      {studentsError && (
        <AlertBanner
          tone="error"
          message={pickLang(lang, "Failed to load students.", "تعذر تحميل الطلاب.")}
          onRetry={refetch}
          dir={isArabic ? "rtl" : "ltr"}
        />
      )}

      {/* Step 0: student & type */}
      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div className="grid gap-4 md:grid-cols-2">
          <Field label={pickLang(lang, "Student", "الطالب")}>
            <select
              value={form.student_id}
              onChange={handleChange("student_id")}
              className="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-slate-300"
              required
              disabled={studentsLoading}
              onBlur={() => setStep((s) => Math.max(s, 1))}
            >
              <option value="">{studentsLoading ? pickLang(lang, "Loading...", "جاري التحميل...") : pickLang(lang, "Select student", "اختر الطالب")}</option>
              {studentOptions.map((opt) => (
                <option key={opt.value} value={opt.value}>
                  {opt.label}
                </option>
              ))}
            </select>
          </Field>
          <Field label={pickLang(lang, "Course type", "نوع الدورة")}>
            <select
              value={form.course_type}
              onChange={(e) => {
                handleChange("course_type")(e);
                setStep(1);
              }}
              className="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-slate-300"
            >
              <option value="language">{pickLang(lang, "Language institute / on-site", "معهد لغة / حضوري")}</option>
              <option value="online">{pickLang(lang, "Online course", "دورة عبر الإنترنت")}</option>
            </select>
          </Field>
        </div>
      </div>

      {/* Step 1: course picker */}
      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
        <p className="text-sm font-normal text-slate-800">
          {pickLang(lang, "Choose a course", "اختر دورة")}
        </p>
        <CoursePicker
          courseType={form.course_type}
          dir={isArabic ? "rtl" : "ltr"}
          onSelect={(course) => {
            setForm((prev) => ({
              ...prev,
              course_id: course.id,
              course_title: course.title,
              final_price: course.pricePerWeek || "",
              currency: course.currency || "GBP",
            }));
            setStep(2);
          }}
        />
        <div className="grid gap-4 md:grid-cols-2">
          <Input label={pickLang(lang, "Course ID or slug", "معرّف الدورة")} value={form.course_id} onChange={handleChange("course_id")} required />
          <Input type="date" label={pickLang(lang, "Start date", "تاريخ البدء")} value={form.start_date} onChange={handleChange("start_date")} required />
        </div>
      </div>

      {/* Step 2: options & pricing */}
      <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div className="grid gap-4 md:grid-cols-3">
          <Input type="number" min={1} label={pickLang(lang, "Weeks", "عدد الأسابيع")} value={form.weeks} onChange={handleChange("weeks")} required />
          <Input type="number" step="0.01" label={pickLang(lang, "Final price", "السعر النهائي")} value={form.final_price} onChange={handleChange("final_price")} required />
          <Input label={pickLang(lang, "Currency", "العملة")} value={form.currency} onChange={handleChange("currency")} />
        </div>

        <div className="grid gap-4 md:grid-cols-3">
          <Input label={pickLang(lang, "Accommodation ID", "معرّف السكن")} value={form.accommodation_id} onChange={handleChange("accommodation_id")} />
          <Input label={pickLang(lang, "Pickup ID", "معرّف الاستقبال")} value={form.pickup_id} onChange={handleChange("pickup_id")} />
          <Input label={pickLang(lang, "Insurance ID", "معرّف التأمين")} value={form.insurance_id} onChange={handleChange("insurance_id")} />
        </div>

        <Input
          label={pickLang(lang, "Supplement IDs (comma separated)", "معرّفات الإضافات (مفصولة بفواصل)")}
          value={form.supplements_ids}
          onChange={handleChange("supplements_ids")}
        />

        <div className="space-y-1">
          <label className="text-sm font-normal text-slate-800">{pickLang(lang, "Notes", "ملاحظات")}</label>
          <textarea
            value={form.notes}
            onChange={handleChange("notes")}
            rows={3}
            className="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-slate-300"
          />
        </div>
      </div>

      {/* Step 3: review & submit */}
      <form className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-3" onSubmit={handleSubmit}>
        <p className="text-sm font-normal text-slate-800">
          {pickLang(lang, "Review & submit", "مراجعة وارسال")}
        </p>
        <div className="grid gap-3 md:grid-cols-2 text-sm text-slate-700">
          <ReviewItem label={pickLang(lang, "Student", "الطالب")} value={studentOptions.find((s) => s.value === Number(form.student_id))?.label || "—"} />
          <ReviewItem label={pickLang(lang, "Course", "الدورة")} value={form.course_title || form.course_id || "—"} />
          <ReviewItem label={pickLang(lang, "Course type", "نوع الدورة")} value={form.course_type} />
          <ReviewItem label={pickLang(lang, "Start date", "تاريخ البدء")} value={form.start_date || "—"} />
          <ReviewItem label={pickLang(lang, "Weeks", "الأسابيع")} value={form.weeks} />
          <ReviewItem label={pickLang(lang, "Price", "السعر")} value={`${form.final_price || 0} ${form.currency}`} />
        </div>

        <button
          type="submit"
          disabled={submitting}
          className="w-full rounded-full bg-[#1277BE] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#0f649f] disabled:opacity-60"
        >
          {submitting ? pickLang(lang, "Submitting...", "جاري الإرسال...") : pickLang(lang, "Book course", "تأكيد الحجز")}
        </button>

        {message && (
          <div className="text-sm font-normal text-slate-700" dir={isArabic ? "rtl" : "ltr"}>
            {message}
          </div>
        )}
      </form>
    </div>
  );
}

function Field({ label, children }) {
  return (
    <div className="space-y-1">
      <label className="text-sm font-normal text-slate-800">{label}</label>
      {children}
    </div>
  );
}

function Input({ label, value, onChange, type = "text", required = false, min, step }) {
  return (
    <div className="space-y-1">
      <label className="text-sm font-normal text-slate-800">{label}</label>
      <input
        type={type}
        value={value}
        onChange={onChange}
        required={required}
        min={min}
        step={step}
        className="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-slate-300"
      />
    </div>
  );
}

function ReviewItem({ label, value }) {
  return (
    <div className="rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2">
      <p className="text-xs text-slate-500">{label}</p>
      <p className="text-sm font-normal text-slate-900 break-all">{value}</p>
    </div>
  );
}
