"use client";

import { useCallback, useMemo } from "react";
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faClipboardList, faDownload, faFileInvoice } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { getImageUrl } from "@/lib/api";
import { buildBookingInvoiceHtml, downloadBookingInvoice } from "@/lib/booking-invoice";
import { formatCurrency } from "@/lib/format";
import { pickBookingImage } from "@/lib/student-media";

function CopyButton({ value, label }) {
  const copy = useCallback(async () => {
    if (!value) return;
    try {
      await navigator.clipboard.writeText(String(value));
    } catch {
      // ignore
    }
  }, [value]);

  return (
    <button
      type="button"
      onClick={copy}
      className="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:text-[#0B5DB6]"
      title={label}
    >
      <FontAwesomeIcon icon={faClipboardList} className="h-4 w-4" />
    </button>
  );
}

function BankCard({ bank, isArabic, loc }) {
  const name = isArabic ? bank.ar_name || bank.name : bank.name;
  const beneficiary = isArabic ? bank.ar_beneficiary || bank.beneficiary : bank.beneficiary;
  const logoUrl = bank.logo_url ? getImageUrl(bank.logo_url) : null;

  return (
    <div className="overflow-hidden rounded-2xl border border-blue-100 bg-white">
      <div className="flex h-20 items-center justify-center bg-white px-4 text-center text-sm font-bold text-blue-700">
        {logoUrl ? (
          <img src={logoUrl} alt={name} className="max-h-12 max-w-[140px] object-contain" />
        ) : (
          bank.logo_text || name
        )}
      </div>
      <div className="space-y-2 bg-[#EFF6FF] p-4 text-xs text-slate-700">
        <div><span className="font-medium">{loc("beneficiary", "Beneficiary")}:</span> {beneficiary}</div>
        <div dir="ltr"><span className="font-medium">{loc("account", "Account")}:</span> {bank.account_number}</div>
        <div dir="ltr"><span className="font-medium">IBAN:</span> {bank.iban}</div>
      </div>
    </div>
  );
}

export default function BookingConfirmation({ booking }) {
  const { direction, language, t } = useLocale();
  const isArabic = language === "ar";
  const isRtl = direction === "rtl";
  const loc = (key, fallback = "") => t(`pages.student.confirmation.${key}`, fallback);

  const currency = booking?.currency || "SAR";
  const bookingId = booking?.reference_no || booking?.booking_id || "-";
  const schoolName = isArabic
    ? booking?.school_ar_name || booking?.school_name
    : booking?.school_name || booking?.school_ar_name;
  const location = isArabic
    ? [booking?.country_ar_name, booking?.city_ar_name].filter(Boolean).join(" ، ")
    : [booking?.country_name, booking?.city_name].filter(Boolean).join(", ");
  const whatsapp = booking?.contact_whatsapp || booking?.student_phone || "";
  const imageSrc = pickBookingImage(booking);

  const feeLines = Array.isArray(booking?.fee_lines) ? booking.fee_lines : [];
  const discountLines = Array.isArray(booking?.discount_lines)
    ? booking.discount_lines.filter((line) => !line.is_summary)
    : [];
  const totalDiscount = booking?.total_discount || 0;
  const total = booking?.final_price ?? booking?.total ?? 0;

  const invoiceLabels = useMemo(
    () => ({
      view_invoice: loc("view_invoice", "View invoice"),
      student_copy: loc("student_copy", "Student Copy"),
      total: loc("total", "Total"),
      start_date: loc("start_date", "Start Date"),
      booking_no: loc("booking_no", "Booking #"),
      course_name: loc("course_name", "Course"),
      booking_summary: loc("booking_summary", "Booking Summary"),
      success_title: loc("title", "Your request was sent successfully"),
      success_subtitle: loc("subtitle", "Our team will review your request and contact you via WhatsApp."),
      price_summary: loc("price_summary", "Price Summary"),
      total_discount: loc("total_discount", "Total Discount"),
      grand_total: loc("grand_total", "Total"),
      grand_total_hint: loc("grand_total_hint", "( Total includes all fees )"),
      course_details: loc("course_details", "Course Details"),
      course_start: loc("course_start", "Course start"),
      duration: loc("duration", "Duration"),
      weeks: loc("weeks", "weeks"),
      acc_details: loc("acc_details", "Accommodation Details"),
      add_services: loc("add_services", "Additional Services"),
      payment_methods: loc("payment_methods", "Payment Methods"),
      payment_hint: loc("payment_hint", "You can pay via bank transfer to any of the following banks"),
      beneficiary: loc("beneficiary", "Beneficiary"),
      account: loc("account", "Account"),
    }),
    [loc],
  );

  const handleDownload = async () => {
    try {
      await downloadBookingInvoice(booking, { isArabic, labels: invoiceLabels });
    } catch {
      // Ignore PDF generation errors in the confirmation view.
    }
  };

  const handleViewInvoice = () => {
    const html = buildBookingInvoiceHtml(booking, { isArabic, labels: invoiceLabels });
    const blob = new Blob([html], { type: "text/html" });
    const url = URL.createObjectURL(blob);
    window.open(url, "_blank", "noopener,noreferrer");
    setTimeout(() => URL.revokeObjectURL(url), 1000);
  };

  return (
    <div className="container mx-auto px-4 pb-24 pt-6 md:pt-10" dir={direction}>
      <div className="mx-auto max-w-xl">
        {/* Success header — Figma */}
        <div className="flex flex-col items-center text-center">
          <div className="flex h-20 w-20 items-center justify-center rounded-full bg-[#E8F9EF]">
            <div className="flex h-16 w-16 items-center justify-center rounded-full bg-[#22C55E] text-3xl text-white">
              ✓
            </div>
          </div>
          <h1 className="mt-6 text-2xl font-semibold text-slate-900 md:text-[28px]">
            {loc("title", "Your request was sent successfully")}
          </h1>
          <p className="mt-3 max-w-md text-sm leading-6 text-slate-600 md:text-base">
            {loc(
              "subtitle",
              "Our team will review your request and contact you on WhatsApp to confirm details and help you choose the best option before final confirmation.",
            )}
          </p>
        </div>

        {whatsapp ? (
          <div className="mt-6 flex items-center justify-center gap-3 rounded-2xl bg-[#E8F9EF] px-4 py-3 text-lg font-medium text-[#22C55E]">
            <Image src="/assets/icons/whatsapp-circle.svg" alt="WhatsApp" width={24} height={24} />
            <span dir="ltr">{whatsapp}</span>
          </div>
        ) : null}

        <div className="mt-4 flex items-center justify-between rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm">
          <div className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-[#EAF4FD] text-[#0B5DB6]">
              <FontAwesomeIcon icon={faClipboardList} />
            </div>
            <div className="text-sm font-medium text-slate-900">
              {loc("booking_no", "Booking #")}
              <span className="mx-1" dir="ltr">
                #{bookingId}
              </span>
            </div>
          </div>
          <CopyButton value={bookingId} label={loc("copy_booking", "Copy booking number")} />
        </div>

        <div className="mt-6 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm">
          <div className={`flex items-center justify-between gap-4 ${isRtl ? "flex-row-reverse" : ""}`}>
            <div className={isRtl ? "text-right" : "text-left"}>
              <div className="text-sm font-medium text-slate-900">{schoolName || "-"}</div>
              <div className="mt-1 text-xs text-slate-500">{location || "-"}</div>
            </div>
            <div className="relative h-16 w-16 shrink-0 overflow-hidden rounded-xl">
              <img
                src={imageSrc}
                alt={schoolName || "School"}
                className="h-full w-full object-cover"
                onError={(e) => {
                  e.currentTarget.onerror = null;
                  e.currentTarget.src = "/assets/hero.png";
                }}
              />
            </div>
          </div>
        </div>

        <h2 className={`mt-6 text-lg font-semibold text-slate-900 ${isRtl ? "text-right" : "text-left"}`}>
          {loc("price_summary", "Price Summary")}
        </h2>

        <div className="mt-2 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm">
          <div className="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
            {feeLines.map((line) => {
              const label = isArabic ? line.ar_label || line.label : line.label || line.ar_label;
              const detail = isArabic ? line.ar_detail || line.detail : line.detail || line.ar_detail;
              const text = detail ? `${label} (${detail})` : label;
              return (
                <div key={line.key || text} className={`text-slate-700 ${isRtl ? "text-right" : "text-left"}`}>
                  {text}
                </div>
              );
            })}
            {feeLines.map((line) => (
              <div
                key={`${line.key || line.label}-amount`}
                className={`font-medium text-slate-900 ${isRtl ? "text-left" : "text-right"}`}
                dir="ltr"
              >
                {formatCurrency(line.amount, currency, language)}
              </div>
            ))}

            {discountLines.map((line) => {
              const label = isArabic ? line.ar_label || line.label : line.label || line.ar_label;
              return (
                <div key={line.key || label} className={`text-green-600 ${isRtl ? "text-right" : "text-left"}`}>
                  {label}
                </div>
              );
            })}
            {discountLines.map((line) => (
              <div
                key={`${line.key || line.label}-discount-amount`}
                className={`font-medium text-green-600 ${isRtl ? "text-left" : "text-right"}`}
                dir="ltr"
              >
                {formatCurrency(line.amount, currency, language)}
              </div>
            ))}

            {totalDiscount > 0 && discountLines.length === 0 ? (
              <>
                <div className={`text-red-500 ${isRtl ? "text-right" : "text-left"}`}>
                  {loc("total_discount", "Total Discount")}
                </div>
                <div className={`font-medium text-red-500 ${isRtl ? "text-left" : "text-right"}`} dir="ltr">
                  {formatCurrency(-1 * totalDiscount, currency, language)}
                </div>
              </>
            ) : null}
          </div>

          <div className="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
            <div className={isRtl ? "text-right" : "text-left"}>
              <div className="text-sm font-medium text-slate-900">{loc("grand_total", "Total")}</div>
              <div className="text-xs text-slate-500">{loc("grand_total_hint", "( Total includes all fees )")}</div>
            </div>
            <div className="text-lg font-semibold text-[#0B5DB6]" dir="ltr">
              {formatCurrency(total, currency, language)}
            </div>
          </div>
        </div>

        {Array.isArray(booking?.payment_banks) && booking.payment_banks.length > 0 ? (
          <div className="mt-6">
            <h2 className={`mb-2 text-lg font-semibold text-slate-900 ${isRtl ? "text-right" : "text-left"}`}>
              {loc("payment_methods", "Payment Methods")}
            </h2>
            <p className={`mb-4 text-sm text-slate-500 ${isRtl ? "text-right" : "text-left"}`}>
              {loc("payment_hint", "You can pay via bank transfer to any of the following banks")}
            </p>
            <div className="grid gap-4 sm:grid-cols-2">
              {booking.payment_banks.map((bank, index) => (
                <BankCard key={`${bank.name}-${index}`} bank={bank} isArabic={isArabic} loc={loc} />
              ))}
            </div>
          </div>
        ) : null}

        <div className="mt-6 flex flex-col gap-3 sm:flex-row">
          <button
            type="button"
            onClick={handleViewInvoice}
            className="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
          >
            <FontAwesomeIcon icon={faFileInvoice} />
            {loc("view_invoice", "View invoice")}
          </button>
          <button
            type="button"
            onClick={handleDownload}
            className="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
          >
            <FontAwesomeIcon icon={faDownload} />
            {loc("download_invoice", "Download invoice")}
          </button>
        </div>

        <div className="mt-3 flex gap-3">
          <Link
            href="/student/bookings"
            className="flex-1 rounded-2xl border border-gray-200 bg-white py-3 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50"
          >
            {loc("go_bookings", "My Bookings")}
          </Link>
          <Link
            href="/"
            className="flex-1 rounded-2xl bg-[#0B5DB6] py-3 text-center text-sm font-medium text-white transition hover:bg-[#094a98]"
          >
            {loc("back_home", "Back to Home")}
          </Link>
        </div>
      </div>
    </div>
  );
}
