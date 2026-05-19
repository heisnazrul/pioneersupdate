"use client";

import { useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronLeft,
  faArrowUpRightFromSquare,
  faEnvelope,
  faPhone,
} from "@fortawesome/free-solid-svg-icons";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { buildApiUrl, useApi } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const FALLBACK = {
  breadcrumb: { home: "Home", current: "Contact Us" },
  title: "Contact Us",
  cards: [
    { icon: "phone", label: "Customer Service", value: "+966 55 487 9888", href: "tel:+966554879888" },
    { icon: "whatsapp", label: "WhatsApp", value: "+966 55 487 9888", href: "https://wa.me/966554879888" },
    { icon: "email", label: "Email Us", value: "Courseenglish@gmail.com", href: "mailto:Courseenglish@gmail.com" },
  ],
  form: {
    title: "Or send us a message and we will get back to you quickly",
    full_name_label: "Full Name",
    full_name_placeholder: "Enter full name",
    email_label: "Email",
    email_placeholder: "Enter email",
    message_label: "Message",
    message_placeholder: "Write your message",
    submit_text: "Send",
    sending_text: "Sending...",
    success_text: "Message sent successfully.",
    fail_text: "Failed to send message. Please try again.",
  },
};

const FALLBACK_AR = {
  breadcrumb: { home: "الرئيسية", current: "اتصل بنا" },
  title: "اتصل بنا",
  cards: [
    { icon: "phone", label: "تواصل مع خدمة العملاء", value: "+966 55 487 9888", href: "tel:+966554879888" },
    { icon: "whatsapp", label: "تواصل بالواتساب", value: "+966 55 487 9888", href: "https://wa.me/966554879888" },
    { icon: "email", label: "راسلنا على", value: "Courseenglish@gmail.com", href: "mailto:Courseenglish@gmail.com" },
  ],
  form: {
    title: "أو يمكنك إرسال رسالة وسيتم الرد عليك في أسرع وقت",
    full_name_label: "الاسم بالكامل",
    full_name_placeholder: "ادخل الاسم بالكامل",
    email_label: "البريد الالكتروني",
    email_placeholder: "ادخل البريد الالكتروني",
    message_label: "رسالتك",
    message_placeholder: "رسالتك",
    submit_text: "ارسال",
    sending_text: "جاري الإرسال...",
    success_text: "تم إرسال الرسالة بنجاح.",
    fail_text: "تعذر إرسال الرسالة. حاول مرة أخرى.",
  },
};

const iconByName = (name) => {
  switch ((name || "").toLowerCase()) {
    case "phone":
      return faPhone;
    case "whatsapp":
      return faWhatsapp;
    case "email":
    default:
      return faEnvelope;
  }
};

export default function ContactPage() {
  const { isArabic } = useCourseEnglishSettings();
  const { data } = useApi("/courseenglish/contact-us");

  const raw = isArabic ? data?.ar_content : data?.content;
  const fallback = isArabic ? FALLBACK_AR : FALLBACK;
  const t = {
    ...fallback,
    ...(raw || {}),
    breadcrumb: { ...fallback.breadcrumb, ...(raw?.breadcrumb || {}) },
    form: { ...fallback.form, ...(raw?.form || {}) },
    cards: raw?.cards?.length ? raw.cards : fallback.cards,
  };

  const [form, setForm] = useState({
    fullName: "",
    email: "",
    message: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState({ type: "", text: "" });

  const requiredMark = <span className="text-red-500">*</span>;

  const firstName = useMemo(() => {
    const name = (form.fullName || "").trim();
    return name || "";
  }, [form.fullName]);

  const onSubmit = async (e) => {
    e.preventDefault();
    if (!firstName || !form.email) return;

    setSubmitting(true);
    setStatus({ type: "", text: "" });
    try {
      const res = await fetch(buildApiUrl("/courseenglish/contact-us/submit"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          first_name: firstName,
          email: form.email,
          message: form.message,
          subject: "Contact Us",
        }),
      });
      if (!res.ok) throw new Error("submit_failed");
      setForm({ fullName: "", email: "", message: "" });
      setStatus({ type: "success", text: t.form.success_text });
    } catch {
      setStatus({ type: "error", text: t.form.fail_text });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <main className="min-h-screen bg-[#EEF4FA] pb-16" dir={isArabic ? "rtl" : "ltr"}>
      <section className="container mx-auto px-4 pt-14 md:pt-20">
        <div className="mx-auto mb-8 flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm text-slate-500 shadow-sm">
          <span>{t.breadcrumb.home}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-3 w-3 ${isArabic ? "rotate-180" : ""}`} />
          <span>{t.breadcrumb.current}</span>
        </div>
        <h1 className="mb-12 text-center text-4xl font-semibold text-[#102233] md:mb-20 md:text-6xl">{t.title}</h1>
      </section>

      <section className="container mx-auto my-4 px-4 md:my-20">
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-10 xl:grid-cols-3">
          {t.cards.map((card, idx) => (
            <a
              key={`${card.label}-${idx}`}
              href={card.href || "#"}
              target={(card.href || "").startsWith("http") ? "_blank" : undefined}
              rel={(card.href || "").startsWith("http") ? "noopener noreferrer" : undefined}
              className="rounded-3xl border border-[#D9E4EF] bg-white/75 px-8 py-8 text-center shadow-[0_2px_8px_rgba(15,23,42,0.05)] transition hover:shadow-md md:min-h-[250px] md:px-10 md:py-10"
            >
              <FontAwesomeIcon icon={iconByName(card.icon)} className="mb-5 text-4xl font-medium text-[#1277BE] md:mb-10" />
              <p className="mb-2 text-lg text-slate-700 md:mb-6 md:text-2xl">{card.label}</p>
              <p
                dir="ltr"
                className={`mx-auto mb-4 max-w-full font-semibold leading-tight tracking-tight text-[#252B33] ${
                  (card.value || "").includes("@") ? "break-all text-xl md:text-2xl" : "text-xl md:text-2xl"
                }`}
              >
                {card.value}
              </p>
              <FontAwesomeIcon icon={faArrowUpRightFromSquare} className="mt-6 text-2xl text-slate-600" />
            </a>
          ))}
        </div>
      </section>

      <section className="container mx-auto my-10 px-4 pt-10 md:my-20">
        <h2 className="mb-8 text-center text-3xl font-extrabold leading-tight text-[#252B33] md:mb-20 md:text-4xl">
          {t.form.title}
        </h2>

        <form onSubmit={onSubmit} className="mx-auto max-w-6xl">
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            <label className="block">
              <span className="mb-2 block text-lg font-normal text-[#252B33] md:text-2xl">{t.form.full_name_label} {requiredMark}</span>
              <input
                type="text"
                value={form.fullName}
                onChange={(e) => setForm((prev) => ({ ...prev, fullName: e.target.value }))}
                className="h-14 w-full rounded-2xl border border-[#1277BE] bg-white px-4 text-lg outline-none"
                placeholder={t.form.full_name_placeholder}
                required
              />
            </label>

            <label className="block">
              <span className="mb-2 block text-lg font-normal text-[#252B33] md:text-2xl">{t.form.email_label} {requiredMark}</span>
              <input
                type="email"
                value={form.email}
                onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                className="h-14 w-full rounded-2xl border border-transparent bg-white px-4 text-lg outline-none"
                placeholder={t.form.email_placeholder}
                required
              />
            </label>
          </div>

          <label className="mt-4 block">
            <span className="mb-2 block text-lg font-normal text-[#252B33] md:text-2xl">{t.form.message_label} {requiredMark}</span>
            <textarea
              rows={4}
              value={form.message}
              onChange={(e) => setForm((prev) => ({ ...prev, message: e.target.value }))}
              className="w-full rounded-2xl border border-transparent bg-white px-4 py-3 text-lg outline-none"
              placeholder={t.form.message_placeholder}
              required
            />
          </label>

          {status.text ? (
            <p className={`mt-4 text-lg font-normal ${status.type === "success" ? "text-green-600" : "text-red-600"}`}>
              {status.text}
            </p>
          ) : null}

          <button
            type="submit"
            disabled={submitting}
            className="mt-6 h-14 w-full rounded-2xl bg-[#1277BE] text-xl font-medium text-white disabled:opacity-70"
          >
            {submitting ? t.form.sending_text : t.form.submit_text}
          </button>
        </form>
      </section>
    </main>
  );
}
