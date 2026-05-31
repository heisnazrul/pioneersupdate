"use client";

import Link from "next/link";
import { useCallback, useMemo, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faDownload, faFilePdf, faXmark } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import { useApi } from "@/lib/api";
import { buildBookingInvoiceHtmlAsync, downloadBookingInvoice } from "@/lib/booking-invoice";
import { fetchBookingDetail } from "@/lib/language-course-booking-api";
import { formatCurrency, formatDate } from "@/lib/format";
import { pickBookingImage } from "@/lib/student-media";

function bookingListKey(item, index) {
  return (
    item?.reference_no
    || item?.booking_id
    || (item?.id != null ? `${item.booking_type || item.course_type || "booking"}-${item.id}` : null)
    || `booking-${index}`
  );
}

export default function StudentBookings() {
  const { direction, language, t } = useLocale();
  const isArabic = language === "ar";
  const loc = (key, fallback = "") => t(`pages.student.bookings.${key}`, fallback);
  const confirmLoc = (key, fallback = "") => t(`pages.student.confirmation.${key}`, fallback);
  const { data, loading } = useApi("/courseenglish/student/bookings");
  const bookings = Array.isArray(data?.data) ? data.data : [];

  const invoiceLabels = useMemo(
    () => ({
      view_invoice: loc("view_invoice", "View invoice"),
      student_copy: confirmLoc("student_copy", "Student Copy"),
      total: loc("total", "Total"),
      start_date: loc("start_date", "Start Date"),
      booking_no: loc("booking_no", "Booking #"),
      course_name: loc("course_name", "Course"),
      booking_summary: confirmLoc("booking_summary", "Booking Summary"),
      success_title: confirmLoc("title", "Your request was sent successfully"),
      success_subtitle: confirmLoc("subtitle", "Our team will review your request and contact you via WhatsApp."),
      price_summary: confirmLoc("price_summary", "Price Summary"),
      total_discount: confirmLoc("total_discount", "Total Discount"),
      grand_total: confirmLoc("grand_total", "Total (includes all fees)"),
      course_details: confirmLoc("course_details", "Course Details"),
      course_start: confirmLoc("course_start", "Course start"),
      duration: confirmLoc("duration", "Duration"),
      weeks: confirmLoc("weeks", "weeks"),
      acc_details: confirmLoc("acc_details", "Accommodation Details"),
      add_services: confirmLoc("add_services", "Additional Services"),
      payment_methods: confirmLoc("payment_methods", "Payment Methods"),
      payment_hint: confirmLoc("payment_hint", "You can pay via bank transfer to any of the following banks"),
      beneficiary: confirmLoc("beneficiary", "Beneficiary"),
      account: confirmLoc("account", "Account"),
    }),
    [loc, confirmLoc],
  );

  const [invoiceModal, setInvoiceModal] = useState({
    open: false,
    booking: null,
    html: null,
    loading: false,
  });

  const closeInvoiceModal = useCallback(() => {
    setInvoiceModal({ open: false, booking: null, html: null, loading: false });
  }, []);

  const handleOpenInvoice = useCallback(
    async (item) => {
      setInvoiceModal({ open: true, booking: item, html: null, loading: true });

      try {
        const reference = item.reference_no || item.booking_id;
        const detail = reference ? await fetchBookingDetail(reference) : item;
        const booking = detail || item;
        const html = await buildBookingInvoiceHtmlAsync(booking, { isArabic, labels: invoiceLabels });
        setInvoiceModal({
          open: true,
          booking,
          html,
          loading: false,
        });
      } catch {
        const html = await buildBookingInvoiceHtmlAsync(item, { isArabic, labels: invoiceLabels });
        setInvoiceModal({
          open: true,
          booking: item,
          html,
          loading: false,
        });
      }
    },
    [isArabic, invoiceLabels],
  );

  const handleDownloadInvoice = useCallback(async () => {
    if (!invoiceModal.booking) return;
    try {
      await downloadBookingInvoice(invoiceModal.booking, { isArabic, labels: invoiceLabels });
    } catch {
      // Keep modal open if PDF generation fails.
    }
  }, [invoiceModal.booking, isArabic, invoiceLabels]);

  if (loading) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>;
  }

  return (
    <div className="space-y-6" dir={direction}>
      <section>
        <h1 className="text-2xl font-medium leading-[1.1] text-[#102233] md:text-4xl">{loc("title", "My Bookings")}</h1>
        <p className="mt-3 text-[15px] text-slate-500 md:text-lg">{loc("subtitle", "All your requests in one place.")}</p>
      </section>

      {bookings.length === 0 ? (
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center">
          <h3 className="text-[30px] font-semibold text-[#102233]">{loc("empty", "No bookings yet")}</h3>
          <p className="mt-2 text-[18px] text-slate-500">{loc("empty_sub", "Start by booking from institutes page.")}</p>
          <Link
            href="/language-institutes"
            className="mt-5 inline-block rounded-2xl bg-[#1277BE] px-8 py-4 text-[18px] font-medium text-white"
          >
            {loc("explore", "Explore")}
          </Link>
        </div>
      ) : (
        <div className="space-y-5">
          {bookings.map((item, index) => (
            <article key={bookingListKey(item, index)} className="mb-5 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm md:p-6">
              <div className="mb-4 flex flex-wrap items-center justify-between text-[13px] md:text-[14px]">
                <div className="flex items-center gap-1 font-medium text-[#102233]">
                  <span>{loc("booking_no", "Booking #")}</span>
                  <span dir="ltr">#{item.booking_id || item.id}</span>
                </div>
                <div className="mt-1 flex items-center gap-1 md:mt-0">
                  <span className="font-normal text-[#a0aaba]">{loc("start_date", "Start Date")} : </span>
                  <span className="font-medium text-[#102233]" dir="ltr">
                    {formatDate(item.start_date)}
                  </span>
                </div>
              </div>

              <div className="grid grid-cols-[auto_1fr] gap-x-3 gap-y-3 border-t border-slate-100 pt-5 md:gap-x-4">
                <div className="h-[90px] w-[90px] md:h-[110px] md:w-[110px]">
                  <img
                    src={pickBookingImage(item)}
                    alt={item.course_name || "booking"}
                    className="h-full w-full rounded-2xl object-cover shadow-sm"
                    loading="lazy"
                    onError={(e) => {
                      e.currentTarget.onerror = null;
                      e.currentTarget.src = "/assets/hero.png";
                    }}
                  />
                </div>

                <div className="flex h-full flex-col justify-start text-start">
                  <h3 className="mb-1.5 line-clamp-2 text-[14px] font-semibold leading-tight text-[#102233] md:mb-2 md:text-[17px]">
                    {isArabic
                      ? `${item.school_ar_name || "-"} - ${item.school_name || "-"}`
                      : `${item.school_name || "-"} - ${item.city_name || "-"}`}
                  </h3>
                  <div className="flex flex-col gap-1 md:gap-1.5">
                    <p className="flex items-center gap-1 text-[12px] text-[#a0aaba] md:text-[13px]">
                      <span>{loc("course_name", "Course")} :</span>
                      <span className="font-medium text-[#102233]">
                        {isArabic ? item.course_ar_name || item.course_name : item.course_name}
                      </span>
                    </p>
                    <p className="flex items-center gap-1 text-[12px] text-[#a0aaba] md:text-[13px]">
                      <span>{loc("weeks", "Weeks")} :</span>
                      <span className="font-medium text-[#102233]">{item.weeks || "-"}</span>
                    </p>
                  </div>
                </div>

                <div className="flex flex-col justify-end pt-1 text-center md:text-start">
                  <p className="mb-0.5 text-[11px] text-[#a0aaba] md:text-[12px]">{loc("total", "Total")}</p>
                  <p className="whitespace-nowrap text-[15px] font-semibold leading-none text-[#102233] md:text-[18px]" dir="ltr">
                    {formatCurrency(item.final_price || item.total || 0, item.currency || "SAR", language)}
                  </p>
                </div>

                <div className="flex items-end justify-end pb-1">
                  <button
                    type="button"
                    onClick={() => handleOpenInvoice(item)}
                    className="inline-flex items-center gap-2 rounded-xl bg-[#1277BE] px-4 py-2 text-[12px] font-medium text-white transition hover:bg-[#0e5f97]"
                  >
                    <FontAwesomeIcon icon={faFilePdf} className="text-[13px]" />
                    {loc("view_invoice", "View invoice")}
                  </button>
                </div>
              </div>
            </article>
          ))}
        </div>
      )}

      {invoiceModal.open && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
          <div className="relative flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div className="flex items-center justify-between border-b border-slate-100 px-5 py-4">
              <div className="flex flex-col">
                <span className="text-sm font-normal text-slate-500">
                  {loc("booking_no", "Booking #")} #{invoiceModal.booking?.booking_id || invoiceModal.booking?.reference_no || invoiceModal.booking?.id}
                </span>
                <span className="text-lg font-medium text-[#102233]">{loc("view_invoice", "View invoice")}</span>
              </div>
              <div className="flex items-center gap-2">
                <button
                  type="button"
                  onClick={handleDownloadInvoice}
                  disabled={!invoiceModal.booking || invoiceModal.loading}
                  className="inline-flex items-center gap-2 rounded-xl bg-[#1277BE] px-3 py-2 text-[12px] font-medium text-white transition hover:bg-[#0e5f97] disabled:opacity-60"
                >
                  <FontAwesomeIcon icon={faDownload} className="text-[13px]" />
                  {loc("download_invoice", "Download")}
                </button>
                <button
                  type="button"
                  onClick={closeInvoiceModal}
                  className="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200"
                >
                  <FontAwesomeIcon icon={faXmark} />
                </button>
              </div>
            </div>
            <div className="flex-1 overflow-hidden bg-slate-50">
              {invoiceModal.loading ? (
                <div className="flex h-[78vh] items-center justify-center text-sm text-slate-500">...</div>
              ) : invoiceModal.html ? (
                <iframe title="Invoice" srcDoc={invoiceModal.html} className="h-[78vh] w-full border-none" />
              ) : (
                <div className="flex h-[78vh] items-center justify-center text-sm text-slate-500">...</div>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
