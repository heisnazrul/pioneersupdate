"use client";

import { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronLeft,
  faArrowUpRightFromSquare,
  faEnvelope,
  faPhone,
} from "@fortawesome/free-solid-svg-icons";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
// import { useApi, buildApiUrl } from "@/lib/api";

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

export default function MobileContact() {
  const { language, t } = useLocale();
  const isArabic = language === "ar";
  
  // const { data } = useApi("/courseenglish/contact-us");
  const data = null;

  const loc = (key) => t(`pages.contact_us.${key}`);
  const formLoc = (key) => t(`pages.contact_us.form.${key}`);

  const defaultCards = [
    { icon: "phone", label: t("pages.contact_us.cards.customer_service"), value: "+966 53 387 5992", href: "tel:+966533875992" },
    { icon: "whatsapp", label: t("pages.contact_us.cards.whatsapp"), value: "+966 55 002 7268", href: "https://wa.me/966550027268" },
    { icon: "email", label: t("pages.contact_us.cards.email"), value: "info@courseenglish.com", href: "mailto:info@courseenglish.com" },
  ];

  const cards = data?.cards?.length ? data.cards : defaultCards;

  const [form, setForm] = useState({
    fullName: "",
    email: "",
    message: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState({ type: "", text: "" });

  const requiredMark = <span className="text-red-500">*</span>;

  const onSubmit = async (e) => {
    e.preventDefault();
    const firstName = form.fullName.trim();
    if (!firstName || !form.email) return;

    setSubmitting(true);
    setStatus({ type: "", text: "" });
    try {
      // Mocking API call for now
      await new Promise(res => setTimeout(res, 800));
      // const res = await fetch(buildApiUrl("/courseenglish/contact-us/submit"), {
      //   method: "POST",
      //   headers: { "Content-Type": "application/json" },
      //   body: JSON.stringify({
      //     first_name: firstName,
      //     email: form.email,
      //     message: form.message,
      //     subject: "Contact Us",
      //   }),
      // });
      // if (!res.ok) throw new Error("submit_failed");
      setForm({ fullName: "", email: "", message: "" });
      setStatus({ type: "success", text: formLoc("success_text") });
    } catch {
      setStatus({ type: "error", text: formLoc("fail_text") });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="bg-[#EEF4FA] pb-10 pt-6" dir={isArabic ? "rtl" : "ltr"}>
      <section className="container mx-auto px-4 pt-4">
        <div className="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs text-slate-500 shadow-sm">
          <span>{loc("breadcrumb_home")}</span>
          <FontAwesomeIcon icon={faChevronLeft} className={`h-2.5 w-2.5 ${isArabic ? "rotate-180" : ""}`} />
          <span>{loc("breadcrumb_current")}</span>
        </div>
        <h1 className="mb-8 text-center text-3xl font-semibold text-[#102233]">{loc("title")}</h1>
      </section>

      <section className="container mx-auto px-4 mb-10">
        <div className="flex flex-col gap-4">
          {cards.map((card, idx) => (
            <a
              key={`${card.label}-${idx}`}
              href={card.href || "#"}
              target={(card.href || "").startsWith("http") ? "_blank" : undefined}
              rel={(card.href || "").startsWith("http") ? "noopener noreferrer" : undefined}
              className="rounded-3xl border border-[#D9E4EF] bg-white/75 px-6 py-6 text-center shadow-[0_2px_8px_rgba(15,23,42,0.05)] transition hover:shadow-md"
            >
              <FontAwesomeIcon icon={iconByName(card.icon)} className="mb-4 text-3xl text-[#1277BE]" />
              <p className="mb-2 text-base font-medium text-slate-700">{card.label}</p>
              <p
                dir="ltr"
                className={`mx-auto mb-3 max-w-full font-bold text-[#252B33] ${
                  (card.value || "").includes("@") ? "break-all text-base" : "text-lg"
                }`}
              >
                {card.value}
              </p>
              <FontAwesomeIcon icon={faArrowUpRightFromSquare} className="mt-2 text-xl text-slate-400" />
            </a>
          ))}
        </div>
      </section>

      <section className="container mx-auto px-4 mb-6">
        <div className="rounded-3xl bg-white p-6 shadow-sm border border-[#D9E4EF]">
          <h2 className="mb-6 text-center text-xl font-bold leading-tight text-[#252B33]">
            {formLoc("title")}
          </h2>

          <form onSubmit={onSubmit} className="flex flex-col gap-4 text-start">
            <label className="block">
              <span className="mb-2 block text-sm font-medium text-[#252B33]">{formLoc("full_name_label")} {requiredMark}</span>
              <input
                type="text"
                value={form.fullName}
                onChange={(e) => setForm((prev) => ({ ...prev, fullName: e.target.value }))}
                className="h-12 w-full rounded-2xl border border-[#D9E4EF] focus:border-[#1277BE] bg-gray-50/50 px-4 text-sm outline-none transition"
                placeholder={formLoc("full_name_placeholder")}
                required
              />
            </label>

            <label className="block">
              <span className="mb-2 block text-sm font-medium text-[#252B33]">{formLoc("email_label")} {requiredMark}</span>
              <input
                type="email"
                value={form.email}
                onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                className="h-12 w-full rounded-2xl border border-[#D9E4EF] focus:border-[#1277BE] bg-gray-50/50 px-4 text-sm outline-none transition text-start"
                placeholder={formLoc("email_placeholder")}
                dir="ltr"
                required
              />
            </label>

            <label className="block">
              <span className="mb-2 block text-sm font-medium text-[#252B33]">{formLoc("message_label")} {requiredMark}</span>
              <textarea
                rows={4}
                value={form.message}
                onChange={(e) => setForm((prev) => ({ ...prev, message: e.target.value }))}
                className="w-full rounded-2xl border border-[#D9E4EF] focus:border-[#1277BE] bg-gray-50/50 px-4 py-3 text-sm outline-none transition"
                placeholder={formLoc("message_placeholder")}
                required
              />
            </label>

            {status.text ? (
              <p className={`mt-2 text-sm font-medium ${status.type === "success" ? "text-green-600" : "text-red-600"}`}>
                {status.text}
              </p>
            ) : null}

            <button
              type="submit"
              disabled={submitting}
              className="mt-4 h-12 w-full rounded-2xl bg-[#1277BE] text-base font-medium text-white disabled:opacity-70 transition active:scale-[0.98]"
            >
              {submitting ? formLoc("sending_text") : formLoc("submit_text")}
            </button>
          </form>
        </div>
      </section>
    </div>
  );
}
